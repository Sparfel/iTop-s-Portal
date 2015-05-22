<?php

class User_Form_Model_LdapUser extends Centurion_Form_Model_Abstract
{
    public function __construct($options = array())
    {
        //$this->_model = Centurion_Db::getSingleton('syleps/ldap/ldapuser');
        $this->_model = Centurion_Db::getSingleton('Portal_Ldap/LdapUser');
        $this->_elementLabels = array(
            'id'           =>  'Id',
            'sn'     =>  'Username',
            'first_name'   =>  'First Name',
            'last_name'    =>  'Last Name',
            'email'        =>  'Email',
        	'email2'		=> 'Email 2',
        	'group_id'	=>	'Groupe',
        	'is_local'		=> 'Compte local',
        );
        
        //$this->_exclude = array('created_at', 'updated_at', 'password', 'salt','algorithm','can_be_deleted','is_super_admin','last_login','user_parent_id');
        
        $this->setLegend($this->_translate('Edit Ldap User'));        
        
        parent::__construct($options);
    }
    
    public function init()
    {
        parent::init();
          
    }
}