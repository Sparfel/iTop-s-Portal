<?php

class Config_Form_Model_AdminAlerts extends Centurion_Form_Model_Abstract
{

	public function __construct($options = array(), Centurion_Db_Table_Row_Abstract $instance = null)
	{
		$this->_model = Centurion_Db::getSingleton('portal/alert');
	
		$this->_elementLabels = array(
				'name'          =>  'Name',
				'text'	=>	'Texte',
				'is_active'         =>  'Active',
				'start' =>  'Start date',
				'stop' =>  'End date',
				'organizations' => 'Organizations',
				'type' => 'Type',
				'priority' => 'Priority'				
		);
		
		//$this->_model = Centurion_Db::getSingleton('portal/alert');
	
		//$this->_exclude = array('created_at', 'updated_at', 'id', 'avatar_id');
	
		$this->setLegend($this->_translate('Edit User'));
	
		parent::__construct($options,$instance);
	}
	
	public function init()
	{
		parent::init();

		$this->setLegend($this->_translate('Edit Alert'));
		
		$this->getElement('text')->setAttrib('class', 'field-rte')
		->setAttrib('large', true)
		->removeFilter('StripTags');
		
		
	}

}
	
