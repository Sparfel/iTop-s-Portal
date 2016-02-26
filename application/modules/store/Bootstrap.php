<?php

class Store_Bootstrap extends Centurion_Application_Module_Bootstrap
{

	 /* Initialize session
	 * 
	 * @return Zend_Session_Namespace
	 */
	protected function _initSession() {
		// On initialise la session
		$session = new Zend_Session_Namespace ( 'ecommerce', true );
		Zend_Registry::set('session',$session);
		// S'il n'existe pas, on crée l'objet panier
    	//$session = Zend_Registry::get('session');
    	if (!(isset($session->panier)))
    		{$session->panier = new Portal_Ecommerce_Panier_Panier();}
		
		return $session;
	}
}