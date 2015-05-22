<?php
class Storsys_IndexController extends Centurion_Controller_Action {

    public function init() {
    }

    public function indexAction() {
        //$this->_helper->redirector('index','product');
    	$this->view->headLink()->appendStylesheet('/layouts/frontoffice/css/store/slide.css');
    	//$this->view->headScript()->appendFile('/layouts/frontoffice/js/jquery-1.8.2.js');
    	$this->view->headScript()->appendFile('/layouts/frontoffice/js/store/jquery.featureList-1.0.0.js');
    	//$this->view->headScript()->appendFile('/layouts/frontoffice/js/store/modernizr.custom.63321.js');
    	 
    	
    }

    public function fillprodAction() {
        $prod = new Syleps_Ecommerce_Models_DbTable_Produits();
        $prod->fillTable(50);
        $this->render('index');

    }

}

