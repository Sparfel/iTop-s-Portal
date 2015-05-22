<?php
class Portal_Itop_Service_Service {
	
	public $_id;
	public $_name;
	public $_servicefamily_id;
	public $_servicefamily_name;
	public $_description;
	public $_list_serviceElement = array(); // array d'objet de type serviceElement
	public $_nbServiceElement;
		
	public function __construct($id, $name, $servicefamily_id,$servicefamily_name = null,$description) {
		$this->_id = $id;
		$this->_name = $name;
		$this->_description = $description;
		$this->_servicefamily_id = $servicefamily_id;
		$this->_servicefamily_name = $servicefamily_name;
		$this->_nbServiceElement = -1; //non encore défini
	}
	
	public function getName() {
		return $this->_name;
	} 
	
	public function getId(){
		return $this->_id;
	}
	
	public function getFamilyId() {
		return $this->_servicefamily_id;
	}
	
	public function getFamilyName() {
		return $this->_servicefamily_name;
	}
	
	public function getDescription() {
		return $this->_description;
	}
	
	public function getListServiceElement(){
		return $this->_list_serviceElement;
	}
	
	public function getServiceElement() {
		$webService =Zend_Controller_Action_HelperBroker::getStaticHelper('ItopWebservice');
		$ListServiceElement = $webService->getServiceSubcategory($this->_id);
		foreach ($ListServiceElement as $serviceElement)
		{	
			$this->_list_serviceElement[$serviceElement['id']] = new Portal_Itop_Service_ServiceSubcategory($serviceElement['id'],
																						$serviceElement['name'],
																						$serviceElement['friendlyname'],
																						$serviceElement['description'],
																						$serviceElement['status']);
		}	
		$this->_nbServiceElement = count($ListServiceElement);
	
		
	}
	
}