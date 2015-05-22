<?php

class Config_AdminServicesController extends Centurion_Controller_CRUD
{
    public function init()
    {
        $this->_formClassName = 'Config_Form_Model_AdminServices';

        $this->_displays = array(
            'name'         =>  $this->view->translate('Nickname'),
            'title'   =>  $this->view->translate('Title'),
            'subtitle'       =>   $this->view->translate('Subtitle'),
            'description'    =>   $this->view->translate('Description'),
        	'link_module'    =>   $this->view->translate('Link Module'),
        	'link_controller'=>   $this->view->translate('Link Controller'),
        	'link_action'    =>   $this->view->translate('Link Action'),
        	'is_active'		 =>	  $this->view->translate('Active')
        );

        $this->view->placeholder('headling_1_content')->set($this->view->translate('Manage Services'));
        $this->view->placeholder('headling_1_add_button')->set($this->view->translate('Service'));
        
        //Nécessaire pour la compatibilité entre les versions de jquery
        $this->view->headScript()->prependFile('/cui/libs/jquery-migrate-1.2.1.js');
       
     /*   $this->view->headScript()->appendFile('/cui/libs/jquery.js');
       $this->view->headScript()->appendFile('/cui/libs/jquery-ui-1.11.4.js');
        $this->view->headScript()->appendFile('/cui/jquery.CUI.js');
       $this->view->headScript()->appendFile('/layouts/backoffice/js/all.js');*/
        
        
        
        
        
        parent::init();
    }

    public function preDispatch()
    {
        $this->_helper->authCheck();
        $this->_helper->aclCheck();
        parent::preDispatch();
    }
    
    	
    	//$this->view->headLink()->appendStylesheet('/layouts/frontoffice/css/datatable/jquery.dataTables.css');
    	
    }