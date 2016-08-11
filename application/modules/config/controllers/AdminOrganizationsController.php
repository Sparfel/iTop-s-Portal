<?php

class Config_AdminOrganizationsController extends Centurion_Controller_CRUD
{
    public function init()
    {
        $this->_formClassName = 'Config_Form_Model_AdminOrganizations';
        $this->_toolbarActions['import'] = $this->view->translate('Import');
        
        $this->_displays = array(
        	//'id '		=> array( 'label'=>$this->view->translate('Id'), 'sort'=>'col'),
            'name'  => array( 'label'=>$this->view->translate('Name'), 'sort'=>'col'),
            'created_at'=> array( 'label'=>$this->view->translate('Creation date'), 'sort'=>'col'),
            
        );
        
        
        $this->_filters = array(
        		'name'      =>  array('type'    =>  self::FILTER_TYPE_TEXT,
        				'label'   =>  $this->view->translate('Name'))
        );

        $this->view->placeholder('headling_1_content')->set($this->view->translate('Manage Organizations'));
        $this->view->placeholder('headling_1_add_button')->set($this->view->translate('Organizations'));
      
        parent::init();

    }

    public function preDispatch()
    {
        $this->_helper->authCheck();
        $this->_helper->aclCheck();
        parent::preDispatch();
    }

    public function importAction(){
    	$itopOrg = new Portal_Organization_Organization();
    	$itopOrg->importAll();
    	$this->getHelper('redirector')->gotoRoute(array_merge(array(
    			'controller' => $this->_request->getControllerName(),
    			'module'     => $this->_request->getModuleName(),
    			'action'         => 'index'
    	), $this->_extraParam), null, true);
    }
    
}