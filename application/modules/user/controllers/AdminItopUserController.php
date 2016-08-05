<?php

class User_AdminItopUserController extends Centurion_Controller_CRUD
{
	//Gestion des User iTop => user autre que Syleps car les user Syleps sont géré via le Ldap
	protected $Default_group;
	
    public function preDispatch()
    {
        $this->_helper->authCheck();
        $this->_helper->aclCheck();
        parent::preDispatch();
        //Création du compte local avec le groupe n° 3 'User' par défaut !
        //TODO : mettre le groupe dans une liste de valeur
        $this->Default_group = 3;
    }
    
    
    public function init()
    {
    	$this->_formClassName = 'User_Form_Model_ItopUser';
    
    	$this->_toolbarActions['import'] = $this->view->translate('Import');
    	$this->_toolbarActions['accountCreation'] = $this->view->translate('Create Account');
    	$this->_toolbarActions['deleteAll'] = $this->view->translate('Delete all');
    	
    	
    	//$this->importLdap();
    	
    	$this->_displays = array(
    			'id'           =>  $this->view->translate('Id'),
    			'login'     =>  $this->view->translate('Username'),
    			'first_name'   =>  $this->view->translate('First Name'),
    			'last_name'    =>  $this->view->translate('Last Name'),
    			'org_name'        =>  array(
    									'label' => $this->view->translate('Organization'),
    									'sort' => 'col'
    								),
    			//'email'        =>  $this->view->translate('Email'),
    			'group_id'		=> $this->view->translate('Groupe'),
    			//'is_active'		=> $this->view->translate('Activ'),
    			'switch'    => array(
    					'type'   => self::COL_TYPE_ONOFF,
    					//'type'   => self::COLS_ROW_FUNCTION,
    					'column' => 'is_local',
    					'label' => $this->view->translate('Is local'),
    					'onoffLabel' => array($this->view->translate('Yes'), $this->view->translate('No')),
    			),
    			
    			
    	);
    
    	$typeTable  = Centurion_Db::getSingleton('auth/group');
    	$typeRowset = $typeTable->fetchAll();
    	
    	//Zend_Debug::dump($typeRowset);
    	
    	$arrTypes = array();
    	foreach ($typeRowset as $key => $row) {
    		$arrTypes[$row->id] = $row->name;
    	}
    	unset($typeRowset);
    	
    	 $this->_filters = array(
            'login'      =>  array('type'    =>  self::FILTER_TYPE_TEXT,
                                      'label'   =>  $this->view->translate('Username')),
    	 	'org_name'      =>  array('type'    =>  self::FILTER_TYPE_TEXT,
    	 				'label'   =>  $this->view->translate('Organization')),
            'is_local'     =>  array('type'    =>  self::FILTER_TYPE_RADIO,
                                      'label'   =>  $this->view->translate('Has a local Account'),
                                      'data'    =>  array(1 => $this->view->translate('Yes'),
                                                          0 => $this->view->translate('No'))),
    	 	/*'Group_id'	=> array('type'		=> self::FILTER_TYPE_SELECT,
    	 						'label'		=> $this->view->translate('Groupe'),
    	 						'data'	=>$arrTypes)*/
    	 	);
    	
    	$this->view->placeholder('headling_1_content')->set($this->view->translate('Manage Import iTop Users'));
    	$this->view->placeholder('headling_1_add_button')->set($this->view->translate('Import iTop Users'));
    	
    	parent::init();
    }
    
    
    public function importAction(){
    	$itopUser = new Portal_Itop_UserLocal();
    	$itopUser->importAll();
    	$this->getHelper('redirector')->gotoRoute(array_merge(array(
    			'controller' => $this->_request->getControllerName(),
    			'module'     => $this->_request->getModuleName(),
    			'action'         => 'index'
    	), $this->_extraParam), null, true);
    }
    
    public function accountCreationAction($rowset = null){
    	$itopUser = new Portal_iTop_UserLocal();
    	$itopUser->createAccount($rowset);
    	$this->getHelper('redirector')->gotoRoute(array_merge(array(
    			'controller' => $this->_request->getControllerName(),
    			'module'     => $this->_request->getModuleName(),
    			'action'         => 'index'
    	), $this->_extraParam), null, true);
    }
    
    public function deleteAllAction(){
    	$itopUser = new Portal_iTop_UserLocal();
    	$itopUser->deleteAll();
    	$this->getHelper('redirector')->gotoRoute(array_merge(array(
    			'controller' => $this->_request->getControllerName(),
    			'module'     => $this->_request->getModuleName(),
    			'action'         => 'index'
    	), $this->_extraParam), null, true);
    }
    
    
}