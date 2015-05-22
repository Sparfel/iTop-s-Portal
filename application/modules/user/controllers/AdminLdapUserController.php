<?php

class User_AdminLdapUserController extends Centurion_Controller_CRUD
{
	protected $Default_group;
	
    public function preDispatch()
    {
        $this->_helper->authCheck();
        $this->_helper->aclCheck();
        parent::preDispatch();
        //Création du compte local avec le groupe n° 7 'Basic Syleps' par défaut !
        //TODO : mettre le grouope dans une liste de valeur
        $this->Default_group = 7;
    }
    
    
    public function init()
    {
    	$this->_formClassName = 'User_Form_Model_LdapUser';
    
    	$this->_toolbarActions['import'] = $this->view->translate('Import');
    	$this->_toolbarActions['accountCreation'] = $this->view->translate('Create Account');
    	$this->_toolbarActions['deleteAll'] = $this->view->translate('Delete all');
    	
    	
    	//$this->importLdap();
    	
    	$this->_displays = array(
    			'id'           =>  $this->view->translate('Id'),
    			'sn'     =>  $this->view->translate('Username'),
    			'first_name'   =>  $this->view->translate('First Name'),
    			'last_name'    =>  $this->view->translate('Last Name'),
    			'email'        =>  $this->view->translate('Email'),
    			'email2'        =>  $this->view->translate('Email2'),
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
            'sn'      =>  array('type'    =>  self::FILTER_TYPE_TEXT,
                                      'label'   =>  $this->view->translate('Username')),
            'is_local'     =>  array('type'    =>  self::FILTER_TYPE_RADIO,
                                      'label'   =>  $this->view->translate('Has a local Account'),
                                      'data'    =>  array(1 => $this->view->translate('Yes'),
                                                          0 => $this->view->translate('No'))),
    	 	/*'Group_id'	=> array('type'		=> self::FILTER_TYPE_SELECT,
    	 						'label'		=> $this->view->translate('Groupe'),
    	 						'data'	=>$arrTypes)*/
    	 	);
    	
    	$this->view->placeholder('headling_1_content')->set($this->view->translate('Manage Ldap'));
    	$this->view->placeholder('headling_1_add_button')->set($this->view->translate('ldap'));
    	
    	parent::init();
    }
    
    public function importAction(){
    	//$this->_helper->viewRenderer->setNoRender(true);
    	// pas de layout autour
    	//$this->_helper->layout()->disableLayout();
    	$ldapuser = new Syleps_Ldap_Model_DbTable_LdapUser();
    	//On vide la table et on va la recharger
    	$ldapuser->truncate();
    	
    	//Récupération de l'annuaire Ldap.
    	$ldap = new Syleps_Auth_Adapter_Ldap();
    	$ldapDatas = $ldap->listAllPeople();
    	foreach ($ldapDatas as $person) {
    		if (isset($person['cn'][0])) { 
    			$tab_name = explode(' ',$person['cn'][0],2);
    			$first_name = $tab_name[0];
    			if (isset($tab_name[1])) {$last_name =  $tab_name[1];}
    				else {$last_name = '';}
    		}
    		if (isset($person['sn'][0])) { $sn = $person['sn'][0];}
    		if (isset($person['mail'][0])) { $email = $person['mail'][0];}
    		//if (isset($person['mail'][1])) {
    			//On est dans le cas ou 2 emails sont renseignées, l'idée est de mettre 
    			//l'adresse @syleps.fr en n°1
    		if (strpos($person['mail'][0],'@syleps.fr')== 0 ) {
    			if (isset($person['mail'][1])){
    				$email = $person['mail'][1];
    				$email2 = $person['mail'][0];}
    			} 
    		else    			{$email2 = null;}
    		
    		//On vérifie si le compte en question est déjà créé ou pas.
    		//TODO
    		$user = new Auth_Model_DbTable_User();
    		$result = $user->findBy('username', $sn);
    		if (count($result)> 0 ) { $is_local = 1;}
    			else { $is_local = 0;}
    		
    		
    		$ldapuser->insUser($sn,$first_name,$last_name,$email,$email2,$this->Default_group, $is_local);
    		
    		
    	}
    	 $this->getHelper('redirector')->gotoRoute(array_merge(array(
            'controller' => $this->_request->getControllerName(),
            'module'     => $this->_request->getModuleName(),
            'action'         => 'index'
        ), $this->_extraParam), null, true);
    }
    
    public function deleteAllAction(){
    	$ldapuser = new Syleps_Ldap_Model_DbTable_LdapUser();
    	//On vide la table et on va la recharger
    	$ldapuser->truncate();
    	$this->getHelper('redirector')->gotoRoute(array_merge(array(
    			'controller' => $this->_request->getControllerName(),
    			'module'     => $this->_request->getModuleName(),
    			'action'         => 'index'
    	), $this->_extraParam), null, true);
    }
	
    
    public function accountCreationAction($rowset = null)
    {
    	
   
        if (null===$rowset) {
            return;
        }

        foreach ($rowset as $key => $row) {
            //On ne crée les comptes que pour ls user qui n'en ont pas !
            if ($row->is_local == 0){
	        	// On crée ensuite l'enregistrement le user local (table auth_user) 
	            $user = new Auth_Model_DbTable_User();
	            //Détermination du password par défaut
	            $username = $row->sn;
	            $salt = $this->getSalt($username);
	            $password =  $this->setPassword($username,$salt,$row->first_name.'1234','sha1');
	            $data = array ('username' => $username,
	            				'first_name' => $row->first_name,
	            				'last_name' => $row->last_name,
	            				'email' => $row->email,
	            				'is_active' => 1,
	            				'is_staff' => 1,
	            				'password' => $password,
	            				'salt' => $salt,
	            				'algorithm' =>'sha1'
	            		);
	            $pk = $user->insert($data);
	            
	            //On gère ensuite les autorisations
	            //Zend_Debug::dump($pk);
				$belong = new Auth_Model_DbTable_Belong();
				$data = array('user_id' => $pk,
							'group_id' => $this->Default_group);
				$belong->insert($data);
				//Puis le profile
				$profile = new User_Model_DbTable_Profile();
				$data = array('user_id' => $pk,
								'nickname' => $username);
				$profile->insert($data);
	            //Maj des données origines
	            $row->group_id = $this->Default_group; // Groupe Syleps par défaut
	            $row->is_local = 1;
	            $row->save();
            }
        }

        $this->_cleanCache();
        $this->getHelper('redirector')->gotoRoute(array_merge(array(
            'controller' => $this->_request->getControllerName(),
            'module'     => $this->_request->getModuleName(),
            'action'         => 'index'
        ), $this->_extraParam), null, true);
    }
    
    
    protected function getSalt($username){
    	return  md5(rand(100000, 999999). $username);
    }
    
    public function setPassword($username,$salt,$password,$algorithm)
    {
    	$algorithmAsStr = is_array($algorithm) ? $algorithm[0] . '::' . $algorithm[1] : $algorithm;
    
    	if (!is_callable($algorithm)) {
    		throw new Centurion_Exception(sprintf('The algorithm callable "%s" is not callable.', $algorithmAsStr));
    	}
    
    	$algorithm = $algorithmAsStr;
    
    	return call_user_func_array($algorithm, array($salt . $password));
    }
    
    
}