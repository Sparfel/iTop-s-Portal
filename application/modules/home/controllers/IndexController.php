<?php

class Home_IndexController extends Centurion_Controller_Action
{
	

	public function preDispatch()
	{
		$this->_helper->authCheck();
		$this->_helper->aclCheck();
		Zend_Layout::getMvcInstance()->assign('titre', $this->view->translate('Votre espace Services'));
		$this->view->headTitle()->prepend('iTop');
	}

	public function init() {
		 
	}

	//Action to show the Home Dashboard, the initial Home screen
	public function indexAction(){
		
		$test = new Portal_Dashboard_Dashboard('HOME_DASHBOARD');
		$test->generateDashboard();
		$script = '';
		$this->view->headScript()->appendScript($script);
	}


}