<?php
class Store_ProductController extends Centurion_Controller_CRUD {

    protected $_model = 'store/Produits';
    
    public function preDispatch()
    {
        $this->_helper->authCheck();
        $this->_helper->aclCheck();
        Zend_Layout::getMvcInstance()->assign('titre', 'Votre Store Syleps');
        $session = new Zend_Session_Namespace('Zend_Auth');
    	$this->_org_id = $session->org_id;
    }
    
    public function init() {
    	
    }

    public function indexAction() {
        $this->view->headLink()->appendStylesheet('/layouts/frontoffice/css/ecommerce.css');
        $produits = new Portal_Ecommerce_Models_DbTable_Produits();
		$produitsAll = $produits->listProducts()->toArray();
        $this->view->produitsAll = $produitsAll;
        
        $session = Zend_Registry::get('session');
        $cart =  $session->panier;
        
        $this->view->panier = $cart;
    }



}

