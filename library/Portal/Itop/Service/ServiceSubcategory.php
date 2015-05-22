<?php
class Portal_Itop_Service_ServiceSubcategory {
	
	public $_id;
	public $_name;
	public $_friendlyname;
	public $_description;
	public $_status;
	public $_template_list = array();
	public $_nbTemplate;
	
	
	public function __construct($id,$name,$friendlyname,$description,$status) {
		$this->_id = $id;
		$this->_name = $name;
		$this->_friendlyname = $friendlyname;
		$this->_description = $description;
		$this->_status = $status;
	}
	
	
	public function getName() {
		return $this->_name;
	}
	
	public function getId(){
		return $this->_id;
	}
	
	public function getFriendlyname() {
		return $this->_friendlyname;
	}
	
	public function getDescription() {
		return $this->_description;
	}
	
	//On va ensuite récupérer les templates pour générer les formulaires.
	public function getTemplateList() {
		/*$webService =Zend_Controller_Action_HelperBroker::getStaticHelper('SylepsItopWebservice');
		$TemplateElement = $webService->getServiceElementTemplate($this->_id);
		foreach ($TemplateElement as $element)
		{
			
			$this->_template_list[$element['id']] = new Portal_Itop_Service_ServiceElementTemplate($element['id'],
																									$element['name'],
																									$element['description'],
																									$element['serviceelement_id'],
																									$element['requesttype']);
			$this->_template_list[$element['id']]->setParentName($this->_name);
			$this->_template_list[$element['id']]->setParentDescription($this->_description);
		}
		$this->_nbTemplate = count($TemplateElement);*/
		}
	
	
	
	
}