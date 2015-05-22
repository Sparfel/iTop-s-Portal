<?php

class Storsys_UserController extends Zend_Controller_Action {

    public function init() {
    }

    public function preDispatch() {
        if (Zend_Auth::getInstance()->hasIdentity()) {
            if ('logout' != $this->getRequest()->getActionName()) {
                $this->_helper->redirector('index', 'index');
            }
        } else {
            if ('register' == $this->getRequest()->getActionName()) {
                return false;
            }
            if (isset($_COOKIE['infoUser'])) {
                $infoUser = unserialize(base64_decode($_COOKIE['infoUser']));
                $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter());
                $authAdapter->setTableName('users')
                        ->setIdentityColumn('email')
                        ->setCredentialColumn('password')
                        ->setIdentity($infoUser['email'])
                        ->setCredential($infoUser['password']);
                $authAuthenticate = $authAdapter->authenticate();
                if ($authAuthenticate->isValid()) {
                    $storage = Zend_Auth::getInstance()->getStorage();
                    $storage->write($authAdapter->getResultRowObject(null, 'password'));
                    $session = Zend_Registry::get('session');
                    $session->panier =  new Syleps_Ecommerce_Panier_Panier();
                    if ($this->addToCartTemp()) {
                        return $this->_helper->redirector('index','panier');
                    }
                    $this->_helper->redirector('index','index');
                }
            }
        }
    }

    public function addToCartTemp() {
        $session = Zend_Registry::get('session');
        if (isset($session->produitIdTemp)) {
            $session->panier->ajouterArticle($session->produitIdTemp);
            unset($session->produitIdTemp);
            return true;
        }else {
            return false;
        }
    }

    public function indexAction() {
        $this->view->registerForm = new Model_Form_UserRegister();
        $this->view->loginForm = new Model_Form_Login();

    }

    public function registerAction() {
        $form = new Model_Form_UserRegister ( );
        $this->view->form = $form;
        if ($this->_request->isPost ()) {
            $formData = $this->_request->getPost ();
            if ($form->isValid ( $formData )) {
                $datas = $formData;
                unset($datas['submit']);
                unset($datas['password2']);
                unset($datas['cgv']);
                $datas['password'] = md5($datas['password']);
                $email = $datas['email'];
                $datas = array_map('strtolower', $datas);
                $datas = array_map('ucwords', $datas);
                $datas['email'] = $email;
                $Users = new Model_DbTable_Users();
                $Users->insert($datas);
                $mail = new Zend_Mail();
                $mail->setBodyHtml('Nous avons bien pris en compte votre inscription au site');
                $mail->setFrom($email, 'TP1-Ecommerce');
                $mail->addTo($email);
                $mail->setSubject('Bienvenue sur TP1-Ecommerce');
                $mail->send();
                $email = $form->getValue('email');
                $password = $form->getValue('password');
                $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter());
                $authAdapter->setTableName('users')
                        ->setIdentityColumn('email')
                        ->setCredentialColumn('password')
                        ->setCredentialTreatment('MD5(?)')
                        ->setIdentity($email)
                        ->setCredential($password);
                $authAuthenticate = $authAdapter->authenticate();
                $storage = Zend_Auth::getInstance()->getStorage();
                $storage->write($authAdapter->getResultRowObject(null, 'password'));
                $session = Zend_Registry::get('session');
                $session->panier =  new Model_Panier_Panier();
                if ($this->addToCartTemp()) {
                    return $this->_helper->redirector('index','panier');
                }
                $this->_helper->redirector('index','index');
            }
        }
    }

    public function loginAction() {
        $form = new Model_Form_Login();
        $this->view->formLogin = $form;
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $email = $form->getValue('email');
                $password = $form->getValue('password');
                $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter());
                $authAdapter->setTableName('users')
                        ->setIdentityColumn('email')
                        ->setCredentialColumn('password')
                        ->setCredentialTreatment('MD5(?)')
                        ->setIdentity($email)
                        ->setCredential($password);
                if ($form->getValue('rememberMe')) {
                    $cookieDatas = base64_encode(serialize(array(
                            'email'=>$form->getValue('email'),
                            'password'=>md5($form->getValue('password'))
                    )));
                    setcookie('infoUser',$cookieDatas,time()+60*60*24*365);
                }
                $authAuthenticate = $authAdapter->authenticate();
                if ($authAuthenticate->isValid()) {
                    $storage = Zend_Auth::getInstance()->getStorage();
                    $storage->write($authAdapter->getResultRowObject(null, 'password'));
                    $session = Zend_Registry::get('session');
                    $session->panier =  new Model_Panier_Panier();
                    if ($this->addToCartTemp()) {
                        return $this->_helper->redirector('index','panier');
                    }
                    $this->_helper->redirector('index','index');
                }else {
                    $form->setDescription('Le couple email / mot de passe n\'est pas valide');
                }
            }
        }
    }

    public function logoutAction() {
        Zend_Auth::getInstance()->clearIdentity();
        setcookie('infoUser',"",1);
        unset ($_COOKIE['infoUser']);
        $this->_helper->redirector('index','index');
    }

}

