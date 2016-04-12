<?php

class Request_ClosedrequestController extends Centurion_Controller_Action 
{
 	protected $_org_id;
 	protected $_list_userRequest;
 	private $_fields;
	
	public function preDispatch()
    {
        $this->_helper->authCheck();
        $this->_helper->aclCheck();
    }
 	
	public function init() {
    	Zend_Layout::getMvcInstance()->assign('titre', $this->view->translate('Vos incidents fermés'));
    	
    	
    	
    	 
    	$session = new Zend_Session_Namespace('Zend_Auth');
    	
    	$this->_org_id = $session->pref->_org_id;
    	
    	$this->_fields = array ( 
    							array
    								('field' => 'ref',
    								'label' => $this->view->translate('User Request'),
    							 	'width' => '30px',
    							 	'link'  =>  true,
    								'link_param' => '',
    							 	'target' => '',
    							 	'sort'	=> 'desc'), // ou desc
    							/*array
    								('field' => 'request_type',
    								'label' => 'Type',
    							 	'width' => '10px',
    							 	'link'  =>  false,
    							 	'target' => '',
    							 	'sort'	=> ''),*/
    							array
    							 	('field' => 'title',
    							 	'label' => $this->view->translate('Title'),
    							 	'width' => '120px',
    							 	'link'  =>  false,
    							 	'target' => '',
    							 	'sort'	=> ''
    							 	),
    							 array
    							 	('field' => 'start_date',
    							 	'label' => $this->view->translate('Started'), 
    							 	'width' => '70px',
    							 	'link'  =>  false,
    							 	'target' => '',
    							 	'sort'	=> ''),
    							 array
    								('field' => 'status',
    								'label' => $this->view->translate('Status'),
    							 	'width' => '15px',
    							 	'link'  =>  false,
    							 	'target' => '',
    							 	'sort'	=> ''),
    							/*array
    								('field' => 'service_name',
    								'label' => 'Service',
    							 	'width' => '10px',
    							 	'link'  =>  false,
    							 	'target' => '',
    							 	'sort'	=> ''),*/
    							array
    								('field' => 'priority',
    								'label' => $this->view->translate('Priority'),
    							 	'width' => '20px',
    							 	'link'  =>  false,
    							 	'target' => '',
    							 	'sort'	=> ''),
    						/*	array
    								('field' => 'site_name',
    								'label' => 'Site',
    							 	'width' => '10px',
    							 	'link'  =>  false,
    							 	'target' => '',
    							 	'sort'	=> ''),*/
    							array
    								('field' =>'caller_id_friendlyname',
    								'label' => $this->view->translate('Caller'),
    							 	'width' => '70px',
    							 	'link'  =>  false,
    							 	'target' => '',
    							 	'sort'	=> '')
    							);
    	
	}
	
    public function indexAction() {
    	
    	
    	$session = new Zend_Session_Namespace('Zend_Auth');
     	$id = $this->_request->getParam('id', null);
        //echo 'id :'.$this->_org_id;
       	if (isset($id)) { //ID est défini, on consulte le détail d'un ticket
       		//$this->view->headScript()->appendFile('/layouts/frontoffice/js/jquery-ui-1.9.1.custom.min.js');
       		$this->view->headScript()->appendFile('/layouts/frontoffice/js/jquery.MultiFile.js');
       		// On sépare le titre du nuémro sinon on enrichit sans cesse le dictionnaire des traductions.
       		$this->view->title = $this->view->translate('Détails de la requête');
       		$this->view->id = $id;
       		$this->view->typ = 'view';
       		
       		$webservice = $this->_helper->getHelper('ItopWebservice');
       		
       		$request = $webservice->getInfoTicket($id,$this->_org_id);
       		//On passe le ticket à la vue.
       		if (isset($request['ref'])){
				$ref = $request['ref'];
				$this->view->ref = $ref;
				$this->view->request = $request;
				$ListAttachment = new Portal_Request_Attachments($request['id']);
				$this->view->attached_files = $ListAttachment->_Aattachment;
       		}
       	}
       	else {// on est en mode affichage de la liste des tickets
       	/*
       		 * On a passé des parametres POST => chgt des filtres / paramètres, on recharge le tableau
       		 */ 
       		if ($this->_request->isPost()) {
       			//Zend_Debug::dump($this->_request->getPost());
       			$data = $this->_request->getPost();
       			//if ($data['searchMode']=='true'){
       			//	$this->searchAction();
       			//}
       			//else {
       				//if (session_id() == $data['sessid']) {
       				$this->changefilterAction();
       				/*}
       				else {
       					//$session = new Zend_Session_Namespace('Zend_Auth');
       					session_id($data['sessid']);
       					echo 'Houston, on a un problème !';}*/
       			//}
       			
       			//Zend_Debug::dump('refresh !');
       			//$this->changefilterAction();
       			//$this->SearchAction();
       			}
       		else 
       		/* 
       		 * Affichage simple du tableau des tickets.
       		 */
       			{
	       		$listFilter = new Portal_Form_Preference($session->pref);
	       		$this->view->form = $listFilter;
	       		$this->view->OPref = $session->pref;
	       		
	       		$this->view->headScript()->appendFile('/layouts/frontoffice/js/datatable/jquery.dataTables.js');
		        $this->view->headLink()->appendStylesheet('/layouts/frontoffice/css/datatable/jquery.dataTables.css');
		        $this->view->headScript()->appendFile('/layouts/frontoffice/js/datatable/dataTables.tableTools.js');
		        $this->view->headLink()->appendStylesheet('/layouts/frontoffice/css/datatable/dataTables.tableTools.css');
		        //Style pour le bouton Impression dans le détail d'un ticket
		        $this->view->headLink()->appendStylesheet('/layouts/frontoffice/css/datatable/dataTables.tableTools.css');
		        //Pour les listes de checkbox
		        //$this->view->headScript()->appendFile('/layouts/frontoffice/js/jquery-ui-1.9.1.custom.min.js');
		        //On affiche la grille de résultats.
		        $this->view->title = $this->view->translate('Mes requêtes fermées');
		    	// Grâce au Helper d'action, on injecte le bon header pour la grille.
		    	$datatable = $this->_helper->getHelper('DataTable');
		    	//$this->_helper->getHelper('DataTable')->test();
		    	$script = $datatable->datatableHeader($this->_fields); 
		    	//$this->view->headScript()->appendScript($script);
		    	$this->view->script = $script;
		    	//Puis on passe le tableau à la vue.
		    	$this->view->tableau = $datatable->datatableTable();
		    	
		    	/*if (is_array($session->ASearchCriteria)) {$this->view->SearchModuleOpen = true;}
		    	else {$this->view->SearchModuleOpen = false;}*/
		    	$SearchFrm = new Portal_Form_SearchRequest($session->ASearchCriteria);
		    	$this->view->SearchFrm = $SearchFrm;
		    	$session->ASearchCriteria = null;
       		}
       	}
    }
   public function getdataAction()
	{
		
		$this->_helper->viewRenderer->setNoRender(true);
	    // pas de layout autour
    	$this->_helper->layout()->disableLayout();
		//if($this->getRequest()->isXmlHttpRequest()) {
	    	$sEcho  = $this->getRequest()->getParam("sEcho");
	        $start  = ($this->getRequest()->has("iDisplayStart")) ? $this->getRequest()->getParam("iDisplayStart") : 0;
	        $offset = ($this->getRequest()->has("iDisplayLength")) ? $this->getRequest()->getParam("iDisplayLength") : 10;
	        $colnum = $this->getRequest()->getParam("iColumns");
	        $field_list = '';
	        foreach ($this->_fields as $field) {
	        	$field_list .= $field['field'].',';
	        }
	        
	        $session = new Zend_Session_Namespace('Zend_Auth');
	        $webservice = $this->_helper->getHelper('ItopWebservice');
	        
	        $tab_result = $webservice->getListClosedRequest($this->_org_id,$session->pref,$session->ASearchCriteria);
	        //Zend_Debug::dump($tab_filter);
	        $this->_list_userRequest = $tab_result;
	       
	        //print_r($field);
	 		$response = array(
	          "iTotalRecords"           => 1, //$datas->getTableDataCount(),
	          "iTotalDisplayRecords"    => count($tab_result),
	          "sEcho"                   => (int)$sEcho,
	          "sColumns"                => $field_list,
	          "aaData"                  =>  $tab_result//$data->toArray()
	        );
	 		//print_r($response);
	        return $this->_helper->json($response);
    //};
	}
	
	//modification des filtres => svg en variable globale et regénération du tableau Datatable
	public function changefilterAction(){
		$this->_helper->viewRenderer->setNoRender(true);
		// pas de layout autour
		$this->_helper->layout()->disableLayout();
		//svg de la modification des filtres
		if ($this->_request->isPost()) {
			$session = new Zend_Session_Namespace('Zend_Auth');
			$data = $this->_request->getPost();
			//Gestion des préférences utilisateur
			$Opref = $session->pref;
		
			$Opref->changePref($data['param'],$data['value']);
			//Gestion de l'éventuel requêtes de recherches.
			if ($data['searchMode']=='true'){
				$SearchCriteria = Array(
							'ref' => $data['ref'],
							'title'  => $data['title'],
							'description'  => $data['description'],
							'public_log'  => $data['public_log'],
							'start_date'  => $data['start_date'],
							'close_date' => $data['close_date']
						);
						
				$session->ASearchCriteria = $SearchCriteria;
			}
			
			$Adata = $this->_request->getPost();
		}
		$datatable = $this->_helper->getHelper('DataTable');
		//$this->_helper->getHelper('DataTable')->IsSearchMode();
		$script = $datatable->datatableHeader($this->_fields);
		//$this->view->headScript()->appendScript($script);
		//Puis on renvoie le tableau à la vue.
		$this->view->tableau = $datatable->datatableTable();
		//$this->getdataAction();
		$data = '<script type="text/javascript">'.$script.'</script>';//.$tableau;
		echo $data;
	}
	

	
	
	
}