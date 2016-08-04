<?php

class Config_AdminAlertsController extends Centurion_Controller_CRUD
{
public function init()
    {
        $this->_formClassName = 'Config_Form_Model_AdminAlerts';

        $this->_displays = array(
        		//'name'          =>  $this->view->translate('Name'),
        		'name' => array(
                                    'type'  => self::COL_TYPE_FIRSTCOL,
                                    'label' => $this->view->translate('name'),
                                    'param' => array(
                                                    'title' => 'name',
                                                    'cover' => null,
                                                    'subtitle' => 'priority',
                                                ),
                                ),
        		/*'name'	=> array('type'  => self::COLS_CALLBACK,
        				'label' => $this->view->translate('name'),
        							'callback' => 'activate'),*/
        		'text'	=>	$this->view->translate('Text'),
        		//'is_active'         =>  $this->view->translate('Active'),
        		'switch'    => array(
        				'type'   => self::COL_TYPE_ONOFF,
        				'column' => 'is_active',
        				'label' => $this->view->translate('Is active'),
        				'onoffLabel' => array($this->view->translate('Active'), $this->view->translate('Not active')),
        		),
        		'start'=>  $this->view->translate('Start Date'),
        		'stop'=>  $this->view->translate('End Date'),
        		'type'	=> $this->view->translate('Type'),
        		'priority' =>   $this->view->translate('Priority')
        		
       );

        $this->view->placeholder('headling_1_content')->set($this->view->translate('Manage Alerts'));
        $this->view->placeholder('headling_1_add_button')->set($this->view->translate('Alert'));
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