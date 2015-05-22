<?php

class Cron_Form_Model_AdminCron extends Centurion_Form_Model_Abstract
{
    public function __construct($options = array())
    {
        $this->_model = Centurion_Db::getSingleton('cron/cron_task');
        
        $this->_elementLabels = array(
            'name'          =>  'Name',
            'class_name'	=>	'Classe',
        	'function_name' =>  'Function',
            'frequency'     =>  'Frequence',
            'is_active'         =>  'Active',
            'last_execution'=>  'Last Execution Date',
        );
        
        //$this->_exclude = array('created_at', 'updated_at', 'id', 'avatar_id');
        
        $this->setLegend($this->_translate('Edit User'));        
        
        parent::__construct($options);
    }
    
    public function init()
    {
        parent::init();
        
    }
}