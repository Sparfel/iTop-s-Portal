<?php
class Storsys_PanierController extends Centurion_Controller_Action {

    public function init() {
    	
    }
    


    public function preDispatch() {
    	
    	$this->_helper->authCheck();
        $this->_helper->aclCheck();
        Zend_Layout::getMvcInstance()->assign('titre', 'Votre Store Syleps');
        $session_principal = new Zend_Session_Namespace('Zend_Auth');
    	$this->_org_id = $session_principal->org_id;
    	
    	
        if (! Zend_Auth::getInstance()->hasIdentity()) {
            if ($this->_request->getActionName() == 'ajouter') {
                $session = Zend_Registry::get('session');
                $session->produitIdTemp = $this->_request->getParam('id');
                return $this->_forward('index','user');
            }
            $this->_forward('index','index');
        }
       
    }

    public function indexAction() {
		$form = new Storsys_Form_Panier();
        $this->view->form = $form;
        
        $this->view->panier = $this->getPanier();
        
    }

    public function ajouterAction() {
    	$this->getPanier()->ajouterArticle($this->getRequest()->getParam('id'));
    	//echo 'On ajoute '.$this->getRequest()->getParam('id');
        //$this->_helper->redirector('index');
        $this->_helper->redirector('index','product');
    }

    public function updateAction() {
        $panier = $this->getPanier();
        $form = new Storsys_Form_Panier();
        $this->view->form = $form;
        if ($this->_request->isPost ()) {
            $formData = $this->_request->getPost ();
            if ($form->isValid ( $formData )) {
                foreach ($formData as $k => $quantite) {
                    if (FALSE != preg_match("#^NbProduit([0-9]+)$#",$k)) {
                        $produitId = preg_replace("#NbProduit([0-9]+)#","$1",$k);
                        $this->getPanier()->editNbArticle($produitId,$quantite);
                    }
                }
            }
        }
        $this->_helper->redirector('index');
    }


    public function deleteAction() {
        $produitId = $this->getRequest()->getParam('id');
        $validatorInt = new Zend_Validate_Int();
        if ($validatorInt->isValid($produitId)) {
            $this->getPanier()->editNbArticle($produitId,0);
        }
        $this->_helper->redirector('index');
    }


    public function validationAction() {
        $this->view->panier = $this->getPanier();
        $auth = Zend_Auth::getInstance();
        $this->view->utilisateur = $auth->getIdentity();
    }

    public function paiementAction() {
        $form = new Model_Form_Paiement();
        $this->view->form = $form;
        if ($this->_request->isPost ()) {
            $formData = $this->_request->getPost ();
            if ($form->isValid ( $formData )) {
                $figlet = new Zend_Text_Figlet();
                $this->view->form = '<pre>'.$figlet->render('Wahoooo').'</pre>';
                $mail = new Zend_Mail();
                $auth = Zend_Auth::getInstance();
                $email = $auth->getIdentity()->email;
                $mail->setBodyHtml('Nous avons bien pris en compte votre commande,
                    elle sera traité dans les plus bref delai');
                $mail->setFrom($email, 'TP1-Ecommerce');
                $mail->addTo($email);
                $mail->setSubject('Commande sur TP1-Ecommerce');

                //$mail->send();


            }
        }
    }

    public function getPanier() {
        $session = Zend_Registry::get('session');
        return $session->panier;
    }

}

