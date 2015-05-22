<?php

class Authentification_LoginController extends Centurion_Controller_Action 
{
	
  public function indexAction()
    {
    	
    	//Zend_Layout::getMvcInstance()->disableLayout();
    	//$this->_helper->viewRenderer->setNoRender(true);
    	//$this->_helper->layout()->disableLayout();
    	
    	
        //$this->_helper->layout->setLayout('adminlogin');
    	$this->_helper->layout->setLayout('loginframe');

    	$this->view->headLink()->appendStylesheet('/layouts/backoffice/styles.css');
        
        $this->_redirectIfAuthenticated();
        
		//Utilise-t-on Ldap ?       
        $config = new  Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', 'production');
        $useLdapSyleps = $config->ldap->server1->use;
        $form = new Authentification_Form_Login(array(
            'dbAdapter'         =>  Zend_Db_Table_Abstract::getDefaultAdapter(),
            'tableName'         =>  'auth_user',
            'loginColumn'       =>  'username',
            'passwordColumn'    =>  'password',
            'authAdapter'       =>  'Syleps_Auth_Adapter_DbTable',
            'checkColumn'       =>  'is_active = 1',
        	'useLdapSyleps' => $useLdapSyleps,
        ));
        
        if (null !== $this->_getParam('next', null)) {
            $form->getElement('next')->setValue($this->_getParam('next', null));
        }
        
        if ($this->getRequest()->isPost()) {
            $posts = $this->getRequest()->getParams();
            
            //$form->isValid($posts);
            //Zend_Debug::dump($ldap['passByLdapSyleps']);
            if ($form->isValid($posts)) {
                $userRow = Centurion_Auth::getInstance()->getIdentity();
                $userRow->last_login = date('Y-m-d h:i:s');
                $userRow->save();
                
                $this->_redirectIfAuthenticated();
                
            } else {
                $form->populate($posts);
            }
        }
        
        //Comment this to see Login problems
        $this->view->form = $form;
    }
    
    private function _redirectIfAuthenticated()
    {
        if (Centurion_Auth::getInstance()->hasIdentity()) {
            if ($this->_hasParam('next') && '' != $this->_getParam('next')) {
                //$this->getHelper('redirector')->gotoUrlAndExit($this->_getParam('next'));  
                $this->_forward('redirection');
                  //echo 'on est ici !';
            } else {
            	//$this->getHelper('redirector')->gotoUrlAndExit('/authentification/login/redirection');
            	$this->_forward('redirection');
            	//echo 'on est là !';
            }
        }
    }
    
    public function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
        Zend_Session::destroy();
        $this->getHelper('redirector')->gotoSimple('login', 'login','authentification');
        $this->view->message = 'Problème de connexion';
    }
    
    
    public function loginAction() //sert de page globale encapsulant le iframe de login.
    {
    	$this->view->headTitle()->prepend('iTop');
    	$this->_helper->layout->setLayout('adminlogin');
    	$this->view->headLink()->appendStylesheet('/layouts/backoffice/progressbar/progress.css');
    	$this->view->headScript()->appendFile('/layouts/backoffice/progressbar/jquery-asProgress.js');
    }
    
    
   
    
    public function redirectionAction(){
    	//$this->_helper->viewRenderer->setNoRender(true);
    	$this->_helper->layout()->disableLayout();
    	//Zend_Debug::dump($this->_getAllParams());
    }
    
}
?>


