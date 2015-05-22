<?php

class User_Form_Model_ItopUser extends Centurion_Form_Model_Abstract
{
    public function __construct($options = array())
    {
        //$this->_model = Centurion_Db::getSingleton('syleps/ldap/ldapuser');
        $this->_model = Centurion_Db::getSingleton('Portal_Itop/ItopUser');
        $this->_elementLabels = array(
            'id'           =>  'Id',
            'login'     =>  'Username',
            'first_name'   =>  'First Name',
            'last_name'    =>  'Last Name',
            'email'        =>  'Email',
        	'group_id'	=>	'Groupe',
        	'is_local'		=> 'Compte local',
        	'org_name'		=>'Organisation'	
        );
        
        //$this->_exclude = array('created_at', 'updated_at', 'password', 'salt','algorithm','can_be_deleted','is_super_admin','last_login','user_parent_id');
        
        $this->setLegend($this->_translate('Edit Itop User'));        
        
        parent::__construct($options);
    }
    
    public function init()
    {
        parent::init();
          
    }
}