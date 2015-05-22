<?php

class ErrorController extends Centurion_Controller_Error
{
	public function preDispatch()
	{
		$this->_helper->authCheck();
		$this->_helper->aclCheck();
		// Zend_Layout::getMvcInstance()->assign('titre', 'Votre espace Services Atelys');
	
	
	}
}

