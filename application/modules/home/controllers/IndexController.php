<?php

class Home_IndexController extends Centurion_Controller_Action
{
	

	public function preDispatch()
	{
		$this->_helper->authCheck();
		$this->_helper->aclCheck();
		Zend_Layout::getMvcInstance()->assign('titre', $this->view->translate('Votre espace Services'));
		$this->view->headTitle()->prepend('iTop');
		$this->_Dashboard  = new Portal_Dashboard_Dashboard('HOME_DASHBOARD');
	}

	public function init() {
		 
	}

	//Action to show the Home Dashboard, the initial Home screen
	// we load the Dashboard in asynchrone way top avoid waiting too long if too much informations
	
	public function indexAction(){
		$session = new Zend_Session_Namespace('Zend_Auth');
		//$this->_Dashboard = new Portal_Dashboard_Dashboard('HOME_DASHBOARD');
		//$test->generateDashboard();
		//$script = '';
		//$this->view->headScript()->appendScript($script);
		$this->view->url =  '/'.$this->_request->module.'/'.$this->_request->controller.'/getdashboard/language/'.$session->pref->_language;
	}
	
	//call by ajax
	public function getdashboardAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout()->disableLayout();
		$this->view->headScript()->appendScript($this->_Dashboard->generateDashboard());
	}

}