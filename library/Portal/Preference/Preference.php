<?php
/**
 * iTop's Portal
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * @copyright   Copyright (c) 2015 Emmaunel Lozachmeur (http://emmanuel.synology.me)
 * @version     $Id$
 * @author      Emmanuel Lozachmeur <emmanuel.lozachmeur@gmail.com>
 */


// Classe utilisée pour gérer les préférences utilisateurs
// l'objet est stocké en variable de session est donc accessible de partout dans le site
class Portal_Preference_Preference {
	
	public $_id; // Id du user Interne au portail
	public $_user_id; // Id du contact du point de vue iTop
	public $_user_name;
	public $_user_first_name;
	public $_org_id;
	public $_org_name;
	public $_email;
	public $_language;
	public $_userFilter; //Filtre sur les données du user
	public $_AyearFilter; // Filtre sur les années
	public $_AyearList ; // Liste des années 
	public $_AlocationFilter; // Filtre sur les sites
	public $_AlocationList; // Liste des sites
	public $_AhomeServicesPosition;
	
	// Donnée lié au compte Portail iTop
	public $_user_id_iTop_account; // Id du compte utilisateur iTop (utile pour signer les public log [principe imposé par iTop])
	public $_user_profiled_iTop_account;
	public $_user_allowedOrgId_iTop_account;
	public $_user_login_iTop_account;
	
	public $_ParamName_UserFilter = 'USER_FILTER';
	public $_ParamName_UserLanguage = 'USER_LANGUAGE';
	public $_ParamName_UserLocation = 'USER_LOCATION';
	public $_ParamName_UserYear = 'USER_YEAR';
	public $_ParamName_UserHomeServices = 'HOME_SERVICES';
	
	private $_start_date = 2012; // Start Date
		
	public function __construct($id,$email,$first_name,$last_name) {
		$webservice = Zend_Controller_Action_HelperBroker::getStaticHelper('ItopWebservice');
        //$request = $webservice->getInfoContact($email);
        $request = $webservice->getInfoContactFull($email,$first_name,$last_name);
        
        if (is_array($request['objects'])) {
	        foreach ($request['objects'] as $result) {
	        	$this->_id = $id;
	        	$this->_user_id =  $result['fields']['id'];
	        	//$this->_user_id_iTop_account=null;
	        	$this->getiTopUserInfo($webservice,$this->_user_id);
	        	$this->_user_name = $result['fields']['name'];
	            $this->_user_first_name = $result['fields']['first_name'];
	            $this->_org_id = $result['fields']['org_id'];
	            $this->_org_name = $result['fields']['org_name'];
	            $this->_email =  $email;
	            
	            // No location in request with iTop original version
	            //$this->_AlocationList = $this->getLocationList();
	            // $this->_AlocationFilter = $this->getLocationFilter();
	            
	           
	            $this->_language = $this->getUserLanguage();
	            //Gestion des filtres d'afichage des tickets.
	            $this->_userFilter = $this->getUserFilter();
	            $this->_AyearList = $this->getYearList();
	            $this->_AyearFilter = $this->getYearFilter();
	          
	            
	            $this->_AhomeServicesPosition = $this->getHomeServices();
	            
			}
        }
       
	}
	
	public function getiTopUserInfo($webservice, $contactId){
		$webrequest = $webservice->getInfoUser($contactId);
		//Zend_Debug::dump($webrequest);
		if (is_array($webrequest['objects'])) {
			foreach ($webrequest['objects'] as $result) {
				$this->_user_id_iTop_account = $result['fields']['id'];
				$this->_user_profiled_iTop_account = $result['fields']['profile_list'];
				$this->_user_allowedOrgId_iTop_account =$result['fields']['allowed_org_list'];
				$this->_user_login_iTop_account =$result['fields']['login'];
				$this->_user_password_iTop_account =$result['fields']['password'];
			}
		}
	}
	
	public function changePref($param,$value){
		switch ($param) {
			case $this->_ParamName_UserFilter :
				$this->setUserFilter($value);
				break;
			case $this->_ParamName_UserLocation :
				$this->setUserLocationFilter($value);
				break;
			case $this->_ParamName_UserYear :
					$this->setUserYearFilter($value);
					break;
			default :
				break;
		}
	}
	
	
	// On récupère les différentes années pour lesquelles on a des tickets (dans iTop)
	public function getYearList() {
		//on liste les années (ici pour ne le faire q'une seule fois)
		/*$webservice = Zend_Controller_Action_HelperBroker::getStaticHelper('ItopWebservice');
		$tab_yearList = $webservice->getPerYearRequest($this->_org_id,"fr_FR");
		//On trie le tableau :
		asort($tab_yearList);*/
		
		$tab_yearList = array();
		$current_date = new Zend_Date();
		$start_year = $this->_start_date;
		$delta_year = $current_date->get(Zend_Date::YEAR) - $start_year;
		for ($i = 0; $i <= $delta_year ; $i++) {
			$tab_yearList[$i] = $start_year + $i;
		}
		//error_log(print_r($tab_yearList));
		return $tab_yearList;
	}
	
	//Donnée stockée en base - Filtre Année
	public function getYearFilter(){
		$userPref = new Portal_Model_DbTable_UserPref();
		$result = $userPref->getPref($this->_user_id,$this->_ParamName_UserYear);
		if ($result == null) {return $this->getYearList();}
		else {return explode(',',$result);}
	}
	
	public function setUserYearFilter($value){
		$this->_AyearFilter = $value;
		$userPref = new Portal_Model_DbTable_UserPref();
		$userPref->savePref($this->_user_id,$this->_ParamName_UserYear,implode(',',$value));
	}
	
	// On récupère les différentes site pour lesquels on a des tickets (dans iTop)
	public function getLocationList(){
		$webservice = Zend_Controller_Action_HelperBroker::getStaticHelper('ItopWebservice');
		return $webservice->getLocation($this->_org_id);
	}
	
	//Donnée stockée en base - Filtre Site
	public function getLocationFilter(){
		$userPref = new Portal_Model_DbTable_UserPref();
		$result = $userPref->getPref($this->_user_id,$this->_ParamName_UserLocation);
		if ($result == null) {return $this->getLocationList();}
		/*$tab1[0] = 'All';
		$tab2 = explode(',',$result);
		$tab_result = array_merge($tab1,$tab2);
		return $tab_result;*/
		else {return explode(',',$result);}
	}
	
	public function setUserLocationFilter($value){
		$this->_AlocationFilter = $value;
		$userPref = new Portal_Model_DbTable_UserPref();
		$userPref->savePref($this->_user_id,$this->_ParamName_UserLocation,implode(',',$value));
	}
	
	//Donnée stockée en base - Filtre Visu
	public function getUserFilter() {
		$userPref = new Portal_Model_DbTable_UserPref();
		$result = $userPref->getPref($this->_user_id,$this->_ParamName_UserFilter);
		if ($result == null) {return 'true';}
		else {return $result;}		
	}
	
	public function setUserFilter($value) {
		$this->_userFilter = $value;
		$userPref = new Portal_Model_DbTable_UserPref();
		$userPref->savePref($this->_user_id,$this->_ParamName_UserFilter,$value);
	}
	
	
	//Donnée stockée en base - Langue
	public function getUserLanguage() {
		$userPref = new Portal_Model_DbTable_UserPref();
		$result = $userPref->getPref($this->_user_id,$this->_ParamName_UserLanguage);
		//Zend_Debug::dump($result);
		if (is_null($result)) {$result = 'fr';}
		return $result;
	}
	
	public function setUserLanguage($value){
		$userPref = new Portal_Model_DbTable_UserPref();
		$this->_language = $value;
		$userPref->savePref($this->_user_id,$this->_ParamName_UserLanguage,$value);
	}
	

	
	//Donnée stockée en base - Positionnement des Services (HOME)
	public function getHomeServices(){
		$userPref = new Portal_Model_DbTable_UserPref();
		$result = $userPref->getPref($this->_user_id,$this->_ParamName_UserHomeServices);
		return $result;
	}
	
	
	

	public function getUserName() {
		return $this->_user_name;
	} 
	
	public function getUserId(){
		return $this->_user_id;
	}
	
}