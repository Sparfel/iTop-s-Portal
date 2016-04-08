<?php

class Home_DashboardController extends Centurion_Controller_Action 
{
    protected $_org_id;
    protected $_user_id;
    protected $_user_filter;
    
	public function preDispatch()
    {
        $this->_helper->authCheck();
        $this->_helper->aclCheck();
        Zend_Layout::getMvcInstance()->assign('titre', $this->view->translate('Votre espace Services'));
        $session = new Zend_Session_Namespace('Zend_Auth');
    	$this->_org_id = $session->pref->_org_id;
    	$this->_user_id = $session->pref->_user_id;
    	$this->_user_filter = $session->pref->_userFilter;
    	
    	$this->view->headTitle()->prepend('iTop');
    }
    
    public function indexAction() {
    /*	Unused if we use the Google js API 
     * 
     * $this->view->headLink()->appendStylesheet('/layouts/frontoffice/css/graph/jquery.jqplot.min.css');
    	$this->view->headScript()->appendFile('/layouts/frontoffice/js/graph/jquery.min.js');
    	$this->view->headScript()->appendFile('/layouts/frontoffice/js/graph/jquery.jqplot.min.js');
    	$this->view->headScript()->appendFile('/layouts/frontoffice/js/graph/plugins/jqplot.pieRenderer.min.js');
    	*/
       
       	
    	If ($this->_user_filter) {
    		//$this->view->tableau = $this->getRequestCount('resolution_code');
       		$data1 = $this->getRequestCountPerUser('resolution_code');
       		$data2 = $this->getRequestCountPerUser('service_name');
       		
    	} else {
    		//Zend_Debug::dump($param);
    		$data1 = $this->getRequestCount('resolution_code');
    		$data2 = $this->getRequestCount('service_name');
    	}
    	
    	$drawChart1 = $this->_helper->getHelper('PieChart');
    	$drawChart1->initialize($data1, // tableau de données
			    			'choice1', // id du div contenant les radio button
			    			'recherche', // l'action appelé par ajax
			    			$this->view->translate('Ticket par code résolution'), // Le titre
			    			$this->view->translate('Code résolution'), // les variables : code résolution
			    			$this->view->translate('Nbre de tickets'),// selon leur nombre de tickets
			    			'chart_div1', // Le nom du Div contenant le diagramme
			    			'resolution_code');// attribut sur lequel on effectue les cumuls
			    		
    	
    	//Premier diagramme
    	$scriptRB = $drawChart1->RadioButtonScript();
    										/*'choice1',
    										'recherche',
    										'Ticket par code résolution', // Le titre
									    	'Code résolution', // les variables : code résolution
									    	'Nbre de tickets',// selon leur nombre de tickets
									    	'chart_div1', // Le nom du Div contenant le diagramme
									    	'resolution_code');*/
    	$this->view->scriptRB = $scriptRB;
    	$scriptPie = $drawChart1->PieScript();
    										/*$data1, // le tableau de données à afficher
									    	'Ticket par code résolution', // Le titre
									    	'Code résolution', // les variables : code résolution
									    	'Nbre de tickets',// selon leur nombre de tickets
									    	'chart_div1' // Le nom du Div contenant le diagramme
									    	);*/
    	$this->view->scriptPie = $scriptPie;
    	
    	//Second diagramme
    	$drawChart2 = $this->_helper->getHelper('PieChart');
    	$drawChart2->initialize($data2,
		    			'choice2',
		    			'recherche',
		    			$this->view->translate('Ticket par Service'), // Le titre
		    			$this->view->translate('Service'), // les variables : code résolution
		    			$this->view->translate('Nbre de tickets'),// selon leur nombre de tickets
		    			'chart_div2', // Le nom du Div contenant le diagramme
		    			'service_name');
		    					
    			
    	$scriptRB2 = $drawChart2->RadioButtonScript();
    												/*'choice2',
    												'recherche',
    												'Ticket par Service', // Le titre
							    					'Service', // les variables : code résolution
							    					'Nbre de tickets',// selon leur nombre de tickets
							    					'chart_div2', // Le nom du Div contenant le diagramme
    												'service_name'
    											);*/
    	$this->view->scriptRB2 = $scriptRB2;
    	$scriptPie2 = $drawChart2->PieScript();
    										/*$data2, // le tableau de données à afficher
							    			'Ticket par Service', // Le titre
							    			'Service', // les variables : code résolution
							    			'Nbre de tickets',// selon leur nombre de tickets
							    			'chart_div2' // Le nom du Div contenant le diagramme
    										);*/
    	$this->view->scriptPie2 = $scriptPie2;
        }
    

      // Amelioration possible : plutot que de recreer un objet, on pourrait le reutiliser et changer juste les datas ?
	public function rechercheAction() {
        	$this->_helper->viewRenderer->setNoRender(true);
        	// pas de layout autour
        	$this->_helper->layout()->disableLayout();
        	$choice = $this->_request->getParam('choice',null);
        	$action = $this->_request->getParam('action',null);
        	$title = $this->_request->getParam('title',null);
        	$var1 = $this->_request->getParam('var1',null);
        	$var2 = $this->_request->getParam('var2',null);
        	$chart_div = $this->_request->getParam('chart_div',null);
        	$count_attribute = $this->_request->getParam('count_attribute',null);
        	
        	//Zend_Debug::dump($param);
        	if($choice =='user' ) {
        
        		$data = $this->getRequestCountPerUser($count_attribute);
        	} else {
        
        		$data = $this->getRequestCount($count_attribute);
        	}
        	$drawChart = $this->_helper->getHelper('PieChart');
        	$drawChart->initialize($data,
					        	$choice,
        						$action,
					        	$title,
					        	$var1,
					        	$var2,
					        	$chart_div,
					        	$count_attribute);
        	
        	$script = $drawChart->PieScript();
        	echo $script;
        }
        
        
   
    
    public function getRequestCount($count_attribute)
    {	// On récupère les données via le Webservice
	    $webservice = $this->_helper->getHelper('ItopWebservice');
	    $data = $webservice->getCountRequest($this->_org_id);
	    //Zend_Debug::dump($data);
	    //Données que l'on met en tableau lisible
	    $i = 0;
	    //print_r($data);
	    if (count($data['objects'])>0) {
	    	$tab_result = array();
		    foreach ($data['objects'] as $result) {
		    	$tab_result[$i] = $result['fields'];
		    	$i++;
		    }
	    	return $this->cumulTab($tab_result,$count_attribute);
	    }
	    else return null;
	}
    
    public function getRequestCountPerUser($count_attribute)
    {
    	// On récupère les données via le Webservice
    	$webservice = $this->_helper->getHelper('ItopWebservice');
    	$data = $webservice->getCountRequestPerUser($this->_org_id,$this->_user_id);
    	//Données que l'on met en tableau lisible
    	$i = 0;
    	if (count($data['objects'])>0) {
    		$tab_result = array();
    		foreach ($data['objects'] as $result) {
	    		$tab_result[$i] = $result['fields'];
    			$i++;
    		}
    		return $this->cumulTab($tab_result,$count_attribute);
    	}
    	else return null;
    }
    
    /* Prend en entrée un tableau tab_list[]
     Array ([0] => valeur1
     		[1] => valeur2
     		[2] => valeur2
     		[3] => valeur2
     		)
     et retourne un tableau
     Array ([valeur1]=> 1,
     		[valeur2]=> 3
     		)
    */    
    public function cumulTab($tab_list,$count_attribute)
    {	$tab_res = Array();
    	$i = 0;
    	while ($i < count($tab_list))
    	{ $tab_res[$i] = $tab_list[$i][$count_attribute];
    		//if (is_null($tab_list[$i][$count_attribute]))
    		if (strlen($tab_list[$i][$count_attribute])==0)
    		{
    			$tab_res[$i]='Undefined';
    		}
    		$i++;}
    		//print_r(array_count_values($tab_res));
    	return array_count_values($tab_res);
    }

}