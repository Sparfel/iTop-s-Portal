<?php

class User_AdminUserController extends Centurion_Controller_CRUD
{
    public function preDispatch()
    {
        $this->_helper->authCheck();
        $this->_helper->aclCheck();
        parent::preDispatch();
    }
    
    
    public function init()
    {
    	$this->_formClassName = 'User_Form_Model_User';
    
    	$this->_displays = array(
    			'id'           =>  $this->view->translate('Id'),
    			'username'     =>  array( 'label'=>$this->view->translate('Username'), 'sort'=>'col'),
    			'first_name'   =>  array( 'label'=>$this->view->translate('First Name'), 'sort'=>'col'),
    			'last_name'    =>  array( 'label'=>$this->view->translate('Last Name'), 'sort'=>'col'),
    			'email'        =>  array( 'label'=>$this->view->translate('Email'), 'sort'=>'col'),
    			//'is_active'		=> $this->view->translate('Activ'),
    			'switch'    => array(
    					'type'   => self::COL_TYPE_ONOFF,
    					'column' => 'is_active',
    					'label' => $this->view->translate('Is active'),
    					'onoffLabel' => array($this->view->translate('Active'), $this->view->translate('Not active')),
    			),
    			//'is_staff'		=> $this->view->translate('iTop Production'),
    			'switch2'    => array(
    					'type'   => self::COL_TYPE_ONOFF,
    					'column' => 'is_staff',
    					'label' => $this->view->translate('iTop Production'),
    					'onoffLabel' => array($this->view->translate('Oui'), $this->view->translate('Non')),
    			),
    	);
    
    	//$this->_toolbarActions['Activate'] = $this->view->translate('Activate');
    	//$this->_toolbarActions['Desactivate'] = $this->view->translate('Desactivate');
    	
    	$this->_filters = array(
    			'last_name'      =>  array('type'    =>  self::FILTER_TYPE_TEXT,
    					'label'   =>  $this->view->translate('Name')),
    			'first_name'      =>  array('type'    =>  self::FILTER_TYPE_TEXT,
    					'label'   =>  $this->view->translate('First Name'))
    			);
    	
    	$this->view->placeholder('headling_1_content')->set($this->view->translate('Manage Users'));
    	$this->view->placeholder('headling_1_add_button')->set($this->view->translate('users'));
    	
    	parent::init();
    }
    

}