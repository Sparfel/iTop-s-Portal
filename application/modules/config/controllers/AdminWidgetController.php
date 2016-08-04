<?php

class Config_AdminWidgetController extends Centurion_Controller_CRUD
{
    public function init()
    {
        $this->_formClassName = 'Config_Form_Model_AdminWidget';

        $this->_displays = array(
            'id'		=> array( 'label'=>$this->view->translate('Id'), 'sort'=>'col'),
        	'dashboard' => array( 'label'=>$this->view->translate('Dashboard'), 'sort'=>'col'),
        	'name'      => array( 'label'=>$this->view->translate('Name'), 'sort'=>'col'),
        	'description'	=> array( 'label'=>$this->view->translate('Description'), 'sort'=>'col'),
            /*'source'	=> $this->view->translate('Source'),*/
        	/*'parameter' => array('label' => $this->view->translate('Parameter'),
        					'style' => 'height:100px;'
        				),*/
        	'type'		=> array( 'label'=>$this->view->translate('Type'), 'sort'=>'col'),
        	'order'		=>	array( 'label'=>$this->view->translate('Order'), 'sort'=>'col'),
        	'switch'    => array(
        				'type'   => self::COL_TYPE_ONOFF,
        				'column' => 'is_active',
        				'label' => $this->view->translate('Is active'),
        				'onoffLabel' => array($this->view->translate('Active'), $this->view->translate('Not active')),
        		)
        );

        
        $this->_filters = array(
        		'dashboard'      =>  array('type'    =>  self::FILTER_TYPE_TEXT,
        									'label'   =>  $this->view->translate('Dashboard')),
        		'is_active'     =>  array('type'    =>  self::FILTER_TYPE_RADIO,
                                      'label'   =>  $this->view->translate('Is active'),
                                      'data'    =>  array(1 => $this->view->translate('Yes'),
                                                          0 => $this->view->translate('No'))),
        );
        
        
        $this->view->placeholder('headling_1_content')->set($this->view->translate('Manage Widget'));
        $this->view->placeholder('headling_1_add_button')->set($this->view->translate('Widget'));
        parent::init();
    }
    


    public function preDispatch()
    {
        $this->_helper->authCheck();
        $this->_helper->aclCheck();
        parent::preDispatch();
    }
    
    	
    	//$this->view->headLink()->appendStylesheet('/layouts/frontoffice/css/datatable/jquery.dataTables.css');
    	
    }