<?php
class User_LanguageController extends Centurion_Controller_Action
{
	//protected $_org_id;


	public function preDispatch()
	{
		$this->_helper->authCheck();
		$this->_helper->aclCheck();
		//Zend_Layout::getMvcInstance()->assign('titre', 'Atelys - Vos tableaux de bord');
		//$session = new Zend_Session_Namespace('Zend_Auth');
		//$this->_org_id = $session->org_id;
	
	}

	
	public function changeAction() {
		 
		$session = new Zend_Session_Namespace('Zend_Auth');
		$session->language =$this->_request->getParam('language', 'fr');
		$session->pref->_language = $this->_request->getParam('language', 'fr');
		$tab_url = $this->_request->getParam('url');
		//$tab_url = explode('/',$url);
		$i = 0;
		$Opref = $session->pref;
		foreach ($tab_url as $content)
			{
				if ($content =='en') { $tab_url[$i] = 'fr';
										$Opref->changePref('USER_LANGUAGE',$tab_url[$i]);
									}
				elseif ($content =='fr') { $tab_url[$i] = 'en';
										$Opref->changePref('USER_LANGUAGE',$tab_url[$i]);
									}
				$i++;
			}
		$url = implode('/',$tab_url);
		
		
		//$this->_baseurl =  $this->getRequest()->getBaseUrl();
		//$this->_request =  $this->getRequest()->getRequestUri();
		//$this->getHelper('redirector')->gotoUrlAndExit('/');
		$this->getHelper('redirector')->gotoUrlAndExit($url);
		//$this->getHelper('GoBack')->direct();
		}


	}