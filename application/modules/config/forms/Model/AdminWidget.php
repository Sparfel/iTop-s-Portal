<?php

class Config_Form_Model_AdminWidget extends Centurion_Form_Model_Abstract
{
    public function __construct($options = array())
    {
        //$this->_model = Centurion_Db::getSingleton('config/services');
        $this->_model = Centurion_Db::getSingleton('Portal/Widget');
        
        $this->_exclude = array('id', 'created_at', 'updated_at');        
        
        $this->_elementLabels = array(
            'name'          =>  'Name',
        	'dashboard'		=>	'Dashboard',
        	'description'	=>	'Description',
            'source'        =>  'Source',
            'parameter'     =>  'Parameter',
            'type'    		=>  'Type',
        	'order'   		=>  'order',
        	'is_active'		 =>	 'Active',
        	'size'			=>	'Size'
        );
                
        
        
        parent::__construct($options);
    }
    
    
    public function init()
    {
    	parent::init();
    	$this->getElement('description')->setAttrib('style', 'height:100px;');
    	$this->getElement('parameter')->setAttrib('style', 'height:100px;');
    }
}