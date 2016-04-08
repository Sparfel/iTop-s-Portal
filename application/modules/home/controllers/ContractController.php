<?php

class Home_ContractController extends Centurion_Controller_Action 
{
    protected $_org_id;
	
	public function preDispatch()
    {
        $this->_helper->authCheck();
        $this->_helper->aclCheck();
	 	Zend_Layout::getMvcInstance()->assign('titre', $this->view->translate('Contrats Fournisseur'));
        $session = new Zend_Session_Namespace('Zend_Auth');
    	$this->_org_id = $session->pref->_org_id;
    	$this->view->headTitle()->prepend('iTop');
    }
	
	//List of ProviderContract in iTop 
    public function init() {
    	//Fields we want to see in the datagrid    	
    	$this->_fields = array (
    			 
    			array
    			('field' => 'name',
    					'label' => $this->view->translate('Name'),
    					'width' => '30%',
    					'link'  =>  false,
    					//'link_param' => array('service_id'),
    					'target' => '',
    					'sort'	=> 'asc'), // ou desc
    			array 
    			('field' => 'provider_name',
		    			'label' => $this->view->translate('Provider'),
		    			'width' => '20%',
		    			'link'  =>  false,
		    			'target' => '',
		    			'sort'	=> ''),
    			array
    			('field' => 'description',
    					'label' => $this->view->translate('Description'),
    					'width' => '50%',
    					'link'  =>  false,
    					'target' => '',
    					'sort'	=> '')
    	);
    	 
    }
    
    public function indexAction() {
   		$this->view->headScript()->appendFile('/layouts/frontoffice/js/datatable/jquery.dataTables.js');
   		$this->view->headLink()->appendStylesheet('/layouts/frontoffice/css/datatable/jquery.dataTables.css');
   		// Action Helper to inject the good Header for teh Grid
   		$datatable = $this->_helper->getHelper('DataTable');
   
   		$script = $datatable->datatableHeader($this->_fields);
   		$this->view->headScript()->appendScript($script);
   		//Sending the Array to the view
   		$this->view->tableau = $datatable->datatableTable();
   		//translation will be activate in the view
   		
   	}
    
    public function getdataAction()
    {
    	$this->_helper->viewRenderer->setNoRender(true);
    	// no Layout, it's Json
    	$this->_helper->layout()->disableLayout();
    
    	//if($this->getRequest()->isXmlHttpRequest()) {
    	$sEcho  = $this->getRequest()->getParam("sEcho");
    	$start  = ($this->getRequest()->has("iDisplayStart")) ? $this->getRequest()->getParam("iDisplayStart") : 0;
    	$offset = ($this->getRequest()->has("iDisplayLength")) ? $this->getRequest()->getParam("iDisplayLength") : 10;
    	$colnum = $this->getRequest()->getParam("iColumns");
    
    	$webservice = $this->_helper->getHelper('ItopWebservice');
    	$data = $webservice->getListProviderContract($this->_org_id);
    
    	$i = 0;
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
    			"iTotalRecords"           => 1, //$data->getTableDataCount(),
    			"iTotalDisplayRecords"    =>  count($data),
    			"sEcho"                   => (int)$sEcho,
    			"sColumns"                => $field_list,
    			"aaData"                  =>  $tab_result //$data->toArray()
    	);
    	//print_r($response);
    	return $this->_helper->json($response);
    	//};
    }
    
	
    

       
  
}