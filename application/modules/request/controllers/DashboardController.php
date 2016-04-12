<?php

class Request_DashboardController extends Centurion_Controller_Action 
{
	protected $_org_id;
	protected $_locale = "fr_FR";
	
	public function preDispatch()
    {
        $this->_helper->authCheck();
        $this->_helper->aclCheck();
        Zend_Layout::getMvcInstance()->assign('titre', $this->view->translate('Tableaux de bord'));
        $this->view->headTitle()->prepend($this->view->translate('Gestion des incidents'));
        $session = new Zend_Session_Namespace('Zend_Auth');
    	$this->_org_id = $session->pref->_org_id;
    	If ($session->pref->_language == 'fr') {
    		$this->_locale = "fr_FR";
    	} 
    	else {$this->_locale = "en_US";} 
        
    }
   
    public function indexAction() {
        $this->view->tableau = $this->getPerMonthRequestTab();
    }
    
    public function getPerMonthRequestTab() {
    	$date_tab  = Array();
    	$date_array = Array();
    	// Nb Months we want to show
    	$nb_month = 13;
    	// We build an Array like this : Array([0] => array(month => m, year => y), // 1 year ago
		// 									....
		//							 [11] => array(month=> m+12, year = y+1) // now
    	// Month's list with their name.
    	$month_tab = (Zend_Locale::getTranslationList('Month', $this->_locale));
    	// we start 12 months ago
    	$curr_month = Zend_Date::now($this->_locale)->sub($nb_month,Zend_Date::MONTH);
    	for($i=0;$i<$nb_month;$i++) {
    		$curr_month = Zend_Date::now($this->_locale)->sub($nb_month - $i - 1,Zend_Date::MONTH);
    		$curr_month_array = $curr_month->toArray();
    		$month[$i]['month'] = $month_tab[$curr_month_array['month']]; // pour avoir le libellé du mois en clair
    		$month[$i]['year'] = $curr_month_array['year'];
    	}
    	
    	// We use the Date format String MM-YYYY
    	// We fill the array with 0 if there is no result in iTop
    	for ($i=0; $i < $nb_month; $i++){
    			//$date_tab[$i]['mois'] = $month[$i]['month'].' - '. $month[$i]['year'];
    			$date_tab[$month[$i]['month'].' - '. $month[$i]['year']] = 0;
    			//$date_tab[$i]['total'] = 0;
    	}
    	//Zend_Debug::dump($date_tab);
    	// We use the Webservice to get the datas
    	$webservice = $this->_helper->getHelper('ItopWebservice');
    	$tab_result = $webservice->getPerMonthRequest($this->_org_id,$this->_locale,$nb_month);
    	//Zend_Debug::dump($month_tab);
    	if (count($tab_result)>0){
    		//$date_list = array();
	    	//$i=0;
	    	foreach($tab_result as $result) {
	    		$date = new Zend_Date($result['start_date'], "YYYY-MM-DD HH:mm:ss", $this->_locale);
	    		$date_array = $date->toArray();
	    		$result_month = $month_tab[$date_array['month']].' - '.$date_array['year'];
	    		// on monte un nouveau tableau tel que Array ( [0] (mois => 'mars - 2013',
	    		//													total => 2)
	    		//												[3] (mois => 'juin - 2013',
	    		//													total => 10),
	    		//												[11] (mois => 'mars - 2014',
	    		//													total => 7)
				//											)
	    		$date_tab[$result_month] = $date_tab[$result_month]  + 1; 
	    		//$date_list[$i] =  $result_month;
	    		//$i++;
	    	}
	    	//$date_list = array_count_values($date_list);
    	}
    	//Zend_Debug::dump($date_tab);
    	//Zend_Debug::dump($date_list);
    	return $date_tab;
    }
    
   
}