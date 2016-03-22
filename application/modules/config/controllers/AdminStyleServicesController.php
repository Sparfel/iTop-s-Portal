<?php

class Config_AdminStyleServicesController extends  Centurion_Controller_CRUD
{
	
	public function init()
    {
        $this->_formClassName = 'Config_Form_Model_AdminStyleServices';
        
        $this->_toolbarActions['importSrv'] = $this->view->translate('Import Itop Services');
        $this->_toolbarActions['importSubSrv'] = $this->view->translate('Import Itop Services Subcategory');

        $this->_displays = array(
        	'id'	=> $this->view->translate('Service ID'),
        	'name'         =>  $this->view->translate('Name'),
        	'code'   =>  $this->view->translate('Code'),
        	'type' 	=> $this->view->translate('Type'),
            'created_at'       =>  $this->view->translate('Created at')
        );

        $this->view->placeholder('headling_1_content')->set($this->view->translate('Manage Services Styles'));
        $this->view->placeholder('headling_1_add_button')->set($this->view->translate('Services Styles'));

        //Nécessaire pour la compatibilité entre les versions de jquery
        //$this->view->headScript()->prependFile('/cui/libs/jquery-migrate-1.2.1.js');
        
        parent::init();
    }

    public function preDispatch()
    {
        $this->_helper->authCheck();
        $this->_helper->aclCheck();
        //nécessaire pour que les liens (formulaires) conserve le breadrumbs
        parent::preDispatch();
    }
    
    public function importSrvAction(){
    	$Services = new Portal_Itop_ServicesManagement();
    	$Services->synchronizeServices();
    	$this->getHelper('redirector')->gotoRoute(array_merge(array(
    				'controller' => $this->_request->getControllerName(),
    				'module'     => $this->_request->getModuleName(),
    				'action'         => 'index'
    	), $this->_extraParam), null, true);
    
    }
    
    public function importSubSrvAction(){
    	$Services = new Portal_Itop_ServicesManagement();
    	$Services->synchronizeServicesSubcategory();
    	$this->getHelper('redirector')->gotoRoute(array_merge(array(
    			'controller' => $this->_request->getControllerName(),
    			'module'     => $this->_request->getModuleName(),
    			'action'         => 'index'
    	), $this->_extraParam), null, true);
    
    }
}