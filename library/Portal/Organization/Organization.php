<?php
class Portal_Organization_Organization {
	
	protected $_WS;
	
	public function __construct() {
		$this->_WS = Zend_Controller_Action_HelperBroker::getStaticHelper('ItopWebservice');
	}

	public function importAll(){
		$itopOrg = new Portal_Model_DbTable_Organization();
		$itopDatas = $this->_WS->getAllOrganizations();
		Zend_Debug::dump($itopDatas);
		foreach ($itopDatas['objects'] as $org) {
			$itopOrg->insOrganization($org['fields']['id'],$org['fields']['name']);
		}
	}

}