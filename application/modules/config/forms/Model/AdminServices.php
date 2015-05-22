<?php

class Config_Form_Model_AdminServices extends Centurion_Form_Model_Abstract
{
    public function __construct($options = array())
    {
        $this->_model = Centurion_Db::getSingleton('config/services');
        
        $this->_exclude = array('id', 'created_at', 'updated_at');        
        
        $this->_elementLabels = array(
            'name'           =>  'Name',
            'title'          =>  'Title',
            'subtitle'       =>  'Subtitle',
            'description'    =>  'Description',
        	'link_module'    =>  'Link Module',
        	'link_controller'=>  'Link Controller',
        	'link_action'    =>  'Link Action',
        	'is_active'		 =>	 'Active'
        );
                
        parent::__construct($options);
    }
    
    public function init()
    {
        parent::init();
             
       
    }
}