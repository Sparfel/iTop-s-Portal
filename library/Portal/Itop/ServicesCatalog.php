<?php
class Portal_Itop_ServicesCatalog {
	
	public $_org_id;
	public $_contract_name;
	public $_list_services = array(); //Array d'objet de type Service
	public $_nb_services;
	
	public function __construct($org_id) {
		$this->_org_id = $org_id;
		
		//$webservice = $this->_helper->getHelper('SylepsItopWebservice');
		$webService =Zend_Controller_Action_HelperBroker::getStaticHelper('ItopRestWebservice');
		$ListService = $webService->getItopServices($org_id);
		
		foreach ($ListService as $service)
		{
			
			$this->_list_services[$service['lnkCustomerContractToService']['service_id']] = new Portal_Itop_Service_Service($service['lnkCustomerContractToService']['service_id'],
																						$service['Service']['name'],
																						$service['Service']['servicefamily_id'],
																						$service['ServiceFamily']['name'],
																						$service['Service']['description']);
			
		$this->_contract_name = $service['CustomerContract']['name'];
		}
		$this->_nb_services = count($ListService);
		
	}
	
	
	
	
	
	
}