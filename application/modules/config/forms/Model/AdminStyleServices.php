<?php

class Config_Form_Model_AdminStyleServices extends Centurion_Form_Model_Abstract
{
    public function __construct($options = array())
    {
        $this->_model = Centurion_Db::getSingleton('Portal/AdminStyleServices');
        
        $this->_exclude = array( 'created_at', 'updated_at', 'avatar_id');        
        
        $this->_elementLabels = array(
        	'id'		=> 'Service ID',
        	'name'				=>	'Name',
        	'code'				=> 'Code',
        	'type'				=>'type',
            'color'           =>  'Color'
            
        );
                
        parent::__construct($options);
    }
    
    public function init()
    {
        parent::init();
             
        $avatar = new Media_Form_Model_Admin_File();
        
        $this->addReferenceSubForm($avatar, 'avatar');
        
        
    }
}