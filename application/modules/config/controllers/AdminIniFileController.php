<?php

class Config_AdminIniFileController extends Centurion_Controller_Action
{
    public function preDispatch()
    {
        $this->_helper->authCheck();
        $this->_helper->aclCheck();
        parent::preDispatch();
    }
    
    public function indexAction(){
    	$this->view->title = $this->view->translate('Modification des fichier de configuration.');
    }
     
    public function applicationAction(){
    	$this->view->title = $this->view->translate('Modification du fichier de configuration de l\'application.');
    }
    
    public function dbAction(){
    	$this->view->title = $this->view->translate('Modification du fichier de connexion à la base de données.');
    }
}