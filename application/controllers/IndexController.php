<?php

class IndexController extends Centurion_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    	//Zend_Layout::getMvcInstance()->assign('titre', 'Votre espace Services Syleps');
    	//$this->getFrontController()->getRouter()->setGlobalParam('language', 'en');
    }

    public function indexAction()
    {
        // action body
    	if (!Centurion_Auth::getInstance()->hasIdentity()) {
    		//$this->_redirect('authentification/login');
    		$this->_redirect('authentification/login/login');
    	 	}
    	else {
    		$session = new Zend_Session_Namespace('Zend_Auth');
    		$this->_redirect('home/index/index/language/'.$session->pref->_language);
    		$this->view->headLink()->appendStylesheet('/layouts/frontoffice/css/slide/piecemaker.css');
    		$this->view->headScript()->appendFile('/layouts/frontoffice/js/slide/swfobject.js');
    		
    		
    	}
    	 	
    	
             
             
    }
    public function installationCompleteAction()
    {
    	$this->_helper->layout->setLayout('install');
        // action body
        
    }
}

