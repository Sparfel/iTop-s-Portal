<?php

class User_Form_Model_User extends Centurion_Form_Model_Abstract
{
    public function __construct($options = array())
    {
        $this->_model = Centurion_Db::getSingleton('auth/user');
        
        $this->_elementLabels = array(
            'id'           =>  'Id',
            'username'     =>  'Username',
            'first_name'   =>  'First Name',
            'last_name'    =>  'Last Name',
            'email'        =>  'Email',
        	'is_active'		=> 'Activ',
        	'is_staff'		=> 'iTop Production',
        );
        
        $this->_exclude = array('created_at', 'updated_at', 'password', 'salt','algorithm','can_be_deleted','is_super_admin','last_login','user_parent_id');
        
        $this->setLegend($this->_translate('Edit User'));        
        
        parent::__construct($options);
    }
    
    public function init()
    {
        parent::init();
          
    }
}