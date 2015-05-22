<?php

class Request_IndexController extends Centurion_Controller_Action 
{
	public function preDispatch()
    {
        $this->_helper->authCheck();
        $this->_helper->aclCheck();
        Zend_Layout::getMvcInstance()->assign('titre', 'Requêtes Utilisateur');
        $session = new Zend_Session_Namespace('Zend_Auth');
    	$this->_org_id = $session->org_id;
    }
    

    public function indexAction() {
    	$this->view->title = 'Assistance';	
    }
}