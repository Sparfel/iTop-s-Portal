<?php

class Config_Form_Model_AdminOrganizations extends Centurion_Form_Model_Abstract
{
    public function __construct($options = array())
    {
       $this->_model = Centurion_Db::getSingleton('Portal/Organization');
        
        //$this->_exclude = array('id', 'created_at', 'updated_at');        
        
        $this->_elementLabels = array(
        	'id'			=> 'Id',	
            'name'      =>  'Name',
            'created_at'	=> 'created at'
        );
                
        parent::__construct($options);
    }
    
    public function init()
    {
        parent::init();
             
       
    }
}