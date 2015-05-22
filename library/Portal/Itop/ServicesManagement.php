<?php
// Management All Services and Services Subcategory.
// It's for Admin backoffice 
class Portal_Itop_ServicesManagement {
	
	protected $_webservice;
	public $_list_services = array(); //Array of Objet Services
	public $_list_serviceSubcategory = array(); //Array of Objet ServicesSubcategory
	
	public function __construct() {
		
		
		//$webservice = $this->_helper->getHelper('SylepsItopWebservice');
		$this->_webservice =Zend_Controller_Action_HelperBroker::getStaticHelper('ItopWebservice');
		
		
		
	}
	
	//getting all Services from iTop
	public function listServices() {
		$ListService = $this->_webservice->getAllServices();
		//Zend_Debug::dump($ListService);
		foreach ($ListService as $service)
		{
				
			$this->_list_services[$service['id']] = new Portal_Itop_Service_Service(
																$service['id'],
																$service['name'],
																$service['servicefamily_id'],
																$service['servicefamily_name'],
																$service['description']);
		}
		
		
		
	}
	
	
	
	
	//getting all Services Subcategory from iTop
	public function listServicesSubcategory(){
		$ListServiceSubcategory = $this->_webservice->getAllServiceSubcategory();
		//Zend_Debug::dump($ListServiceSubcategory);
		foreach ($ListServiceSubcategory as $serviceSubcategory)
		{
			$this->_list_serviceSubcategory[$serviceSubcategory['id']] = new Portal_Itop_Service_ServiceSubcategory($serviceSubcategory['id'],
					$serviceSubcategory['name'],
					$serviceSubcategory['friendlyname'],
					$serviceSubcategory['description'],
					$serviceSubcategory['status']);
		}
		
	}
	
	// Insertion of all new Services and Updating existing Services
	public function synchronizeServices() {
		$TableService = new Portal_Model_DbTable_AdminStyleServices();
		//récupération via le Webservice des userLocal dans iTop
		$this->listServices();
		//Zend_Debug::dump($this->_list_services);
		foreach ($this->_list_services as $Oservice) {
			//Zend_Debug::dump($TableService->find($Oservice->getId()));
			$rowset = $TableService->find($Oservice->getId());
			if ($rowset->count() == 1)
				{// Update the record
			}
			else {// insert a new record
				$TableService->insService($Oservice->getId(),
											$Oservice->getName(),
											$Oservice->getDescription(),
											'Service'.$Oservice->getId(), // code = service . Id 
											'Service', //type
											$Oservice->getFamilyId(),
											$Oservice->getFamilyName()
						);
			}
		}
	}
	
	
	// Insertion of all new Services Subcategories and Updating existing Services Subcategories
	public function synchronizeServicesSubcategory() {
		$TableService = new Portal_Model_DbTable_AdminStyleServices();
		//récupération via le Webservice des userLocal dans iTop
		$this->listServicesSubcategory();
		//Zend_Debug::dump($this->_list_services);
		foreach ($this->_list_serviceSubcategory as $OserviceSubcategory) {
			//Zend_Debug::dump($TableService->find($Oservice->getId()));
			$rowset = $TableService->find($OserviceSubcategory->getId());
			if ($rowset->count() == 1)
			{// Update the record
			}
			else {// insert a new record
				$TableService->insService($OserviceSubcategory->getId(),
						$OserviceSubcategory->getName(),
						$OserviceSubcategory->getDescription(),
						'ServiceSubcategory'.$OserviceSubcategory->getId(), // code = service . Id
						'ServiceSubcategory', //type
						null, //parent_id
						null //parent_name
				);
			}
		}
	}
	
	
}