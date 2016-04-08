<?php

class Home_ContactController extends Centurion_Controller_Action 
{
    protected $_org_id;
	
	public function preDispatch()
    {
        $this->_helper->authCheck();
        $this->_helper->aclCheck();
	 	Zend_Layout::getMvcInstance()->assign('titre', $this->view->translate('Contacts Services'));
        $session = new Zend_Session_Namespace('Zend_Auth');
    	$this->_org_id = $session->pref->_org_id;
    	$this->view->headTitle()->prepend('iTop');
    }
	
	
	
    public function indexAction() {
        //List of the Team Support Member. 
        $webservice = $this->_helper->getHelper('ItopWebservice');
    	
    	// Team Leader has the role 'Manager'
    	// Team Support will be all member because no role are defined.
    	// Define Role for each person and we can list each role or some roles
    	$responsable = $webservice->getTeamLeaderSupport($this->_org_id);
    	$this->view->leader = $responsable;
    	$liste = $webservice->getTeamSupport($this->_org_id);
    	$this->view->team = $liste;
    	
    }

  
 
    
  
}