<?php

class Cron_AdminCronController extends Centurion_Controller_CRUD
{
    public function init()
    {
        $this->_formClassName = 'Cron_Form_Model_AdminCron';

        $this->_displays = array(
        		'name'          =>  $this->view->translate('Name'),
        		'class_name'	=>	$this->view->translate('Class'),
        		'function_name' =>  $this->view->translate('Function'),
        		'frequency'     =>  $this->view->translate('Frequence'),
        		//'is_active'         =>  $this->view->translate('Active'),
        		'switch'    => array(
        				'type'   => self::COL_TYPE_ONOFF,
        				'column' => 'is_active',
        				'label' => $this->view->translate('Is active'),
        				'onoffLabel' => array($this->view->translate('Active'), $this->view->translate('Not active')),
        		),
        		'last_execution'=>  $this->view->translate('Last Execution Date'),
       );

        $this->view->placeholder('headling_1_content')->set($this->view->translate('Manage Cron Task'));
        $this->view->placeholder('headling_1_add_button')->set($this->view->translate('Task'));
        $this->_toolbarActions['Activate'] = $this->view->translate('Activate');
        $this->_toolbarActions['Desactivate'] = $this->view->translate('Desactivate');

        parent::init();
    }

    public function preDispatch()
    {
        $this->_helper->authCheck();
        $this->_helper->aclCheck();
    }
    
    public function activateAction($rowset = null)
    {
    	if (null===$rowset) {
    		return;
    	}
    
    	foreach ($rowset as $key => $row) {
    		$row->is_active = 1;
    		$row->save();
    	}
    
    	$this->_cleanCache();
    	$this->getHelper('redirector')->gotoRoute(array_merge(array(
    			'controller' => $this->_request->getControllerName(),
    			'module'     => $this->_request->getModuleName(),
    			'action'         => 'index'
    	), $this->_extraParam), null, true);
    }
    
    public function desactivateAction($rowset = null)
    {
    	if (null===$rowset) {
    		return;
    	}
    
    	foreach ($rowset as $key => $row) {
    		$row->is_active = 0;
    		$row->save();
    	}
    
    	$this->_cleanCache();
    	$this->getHelper('redirector')->gotoRoute(array_merge(array(
    			'controller' => $this->_request->getControllerName(),
    			'module'     => $this->_request->getModuleName(),
    			'action'         => 'index'
    	), $this->_extraParam), null, true);
    }
    
}