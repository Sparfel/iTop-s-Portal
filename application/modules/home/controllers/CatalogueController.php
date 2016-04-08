<?php

class Home_CatalogueController extends Centurion_Controller_Action 
{
	protected $_org_id;
 	
 	private $_fields;
	
	public function preDispatch()
    {
        $this->_helper->authCheck();
        $this->_helper->aclCheck();
        $this->view->headTitle()->prepend('iTop');
    }
 	
	public function init() {
    	Zend_Layout::getMvcInstance()->assign('titre', $this->view->translate('Services - Le catalogue'));
    	$session = new Zend_Session_Namespace('Zend_Auth');
    	$this->_org_id = $session->pref->_org_id;
    	
    	//the fields we want to see in the datagrid
    	$this->_fields = array ( 
				    			
    							array
    								('field' => 'name',
    								'label' => $this->view->translate('Name'),
    							 	'width' => '30%',
    							 	'link'  =>  true,
    								'link_param' => array('service_id'),
    							 	'target' => '',
    							 	'sort'	=> 'asc'), // ou desc
    							array
    								('field' => 'description',
    								'label' => $this->view->translate('Description'),
    							 	'width' => '70%',
    							 	'link'  =>  false,
    							 	'target' => '',
    							 	'sort'	=> '')
    							);
    	
	}
	
    public function indexAction() {
    	$service_id = $this->_request->getParam('service_id',null);
    	$id = $this->_request->getParam('id',null);
    	if (isset($service_id) AND isset($id)) {
    		// load the catalog
    		$session = new Zend_Session_Namespace('Zend_Auth');
    		$catalog = new Portal_iTop_ServicesCatalog($session->pref->_org_id);
    		$session->ServiceCatalog = $catalog;
    		//we get the the Service of the Service Subcategory
    		//we take this service inside the catalog
    		$Srv = $catalog->_list_services[$service_id];
    		//Generation on demand of Service Subcategory
    		$Srv->getServiceElement();
    		$session->Service = $Srv;
    		
    		$this->getHelper('redirector')->gotoUrlAndExit('/home/index/index/language/fr/ServiceSubcategory/'.$id);
    		 
    	}
    	else {
	    	$this->view->headScript()->appendFile('/layouts/frontoffice/js/datatable/jquery.dataTables.js');
	    	$this->view->headLink()->appendStylesheet('/layouts/frontoffice/css/datatable/jquery.dataTables.css');
	    	// Grâce au Helper d'action, on injecte le bon header pour la grille.
	    	$datatable = $this->_helper->getHelper('DataTable');
	    	
	    	//Gestion du OrgChart
	    	//$this->view->headScript()->appendFile('/layouts/frontoffice/js/getorgchart/getorgchart.js');
	    	//$this->view->headLink()->appendStylesheet('/layouts/frontoffice/js/getorgchart/getorgchart.css');
	    	
	    	$script = $datatable->datatableHeader($this->_fields); 
	    	$this->view->headScript()->appendScript($script);
	    	//Send the Array to the view
	    	$this->view->tableau = $datatable->datatableTable();
	    	
	    	//Getting all information to build the OrgChart for the Services
	    	$webservice = $this->_helper->getHelper('ItopRestWebservice');
	    	$this->view->tableau2 = $webservice->getCumulEltService();
	    	
    	}
    	//Zend_Debug::dump($webservice->getCumulService());
    }

    

 
    
    public function getdataAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
	    // No layout, it's a json
    	$this->_helper->layout()->disableLayout();
		
    	//if($this->getRequest()->isXmlHttpRequest()) {
    	$sEcho  = $this->getRequest()->getParam("sEcho");
        $start  = ($this->getRequest()->has("iDisplayStart")) ? $this->getRequest()->getParam("iDisplayStart") : 0;
        $offset = ($this->getRequest()->has("iDisplayLength")) ? $this->getRequest()->getParam("iDisplayLength") : 10;
        $colnum = $this->getRequest()->getParam("iColumns");
 
        $webservice = $this->_helper->getHelper('ItopWebservice');
        $data = $webservice->getListServiceElement($this->_org_id);
        
        $i = 0;
        //echo 'Webservice';
        foreach ($data['objects'] as $result) {
        	$tab_result[$i] = $result['fields'];
        	$i++;
        	//print_r($tab_result);
        }
        $field_list = '';
        foreach ($this->_fields as $field) {
        	$field_list .= $field['field'].',';
        }
        
 		$response = array(
          "iTotalRecords"           => 1, //$datas->getTableDataCount(),
          "iTotalDisplayRecords"    => count($data),
          "sEcho"                   => (int)$sEcho,
          "sColumns"                => $field_list,
          "aaData"                  =>  $tab_result //$data->toArray()
        );
 		return $this->_helper->json($response);
    //};
	}
    
	public function getseidandredirectAction() {
		//getting the ID of the ServiceESubcategory
		$id = $this->_request->getParam('id',null);
		$this->getHelper('redirector')->gotoUrlAndExit('/home/index/index/language/fr/service/'.$id);
		//$this->getHelper('redirector')->gotoUrlAndExit('/');
		
	}

}