<?php
class User_PreferenceController extends Centurion_Controller_Action
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

	public function indexAction(){
		$session = new Zend_Session_Namespace('Zend_Auth');
		/*$formPref = new Syleps_Form_Preference($session->filter->locationList, //$tab_location_list,
				$session->filter->yearList, //$tab_year,
				$session->filter->locationFilter, //$tab_location_selected,
				$session->filter->yearFilter, //$tab_year_selected,
				$session->filter->userFilter);*/
		$this->view->actionMsg = '';
		
		$formPref = new Portal_Form_Preference($session->pref);
		$this->view->formPref = $formPref;
		
		$this->view->OPref = $session->pref;
		//Zend_Debug::dump($session->pref);
		
		
		$formpwd = new Portal_Form_Password();
		//$formpwd = new Auth_Form_Model_User();
		$this->view->formpwd = $formpwd;
		
		
		//Information relative à la situation du compte
		$identity = Centurion_Auth::getInstance()->getIdentity();
		$itopOK = $this->view->translate('Compte iTop déclaré');
		$itopKO = $this->view->translate('Pas de compte iTop déclaré');
		$iterOK = $this->view->translate('interaction sur les tickets possible');
		$iterKO = $this->view->translate('interaction sur les tickets impossible');
		
		if (($identity->is_staff == '0') AND (isset($session->pref->_user_login_iTop_account) )) { //iTop Test et compte iTop existant => vert - vert
			$this->view->span1 = '<span style="color:green">';
			$this->view->span2 = '<span style="color:green">';
			$this->view->endspan = '</span>';
			$this->view->environment = 'Test';
			$this->view->itopinteraction = '<p>'.$itopOK.' : <b>'.$this->view->span2.$iterOK.'</b>'.$this->view->endspan.'.</p>'; 
		}
		elseif (($identity->is_staff == '0') AND !(isset($session->pref->_user_login_iTop_account))) {//iTop Test et pas compte iTop existant => vert - rouge
			$this->view->span1 = '<span style="color:green">';
			$this->view->span2 = '<span style="color:red">';
			$this->view->endspan = '</span>';
			$this->view->environment = 'Test';
			$this->view->itopinteraction = '<p>'.$itopKO.' : <b>'.$this->view->span2.$iterKO.'</b>'.$this->view->endspan.'.</p>';
		}
		elseif (($identity->is_staff == '1') AND !(isset($session->pref->_user_login_iTop_account))) {//iTop Prod et pas compte iTop existant => rouge - rouge
			$this->view->span1 = '<span style="color:red">';
			$this->view->span2 = '<span style="color:red">';
			$endspan = '</span>';
			$this->view->environment = 'Production';
			$this->view->itopinteraction = '<p>'.$itopKO.' : <b>'.$this->view->span2.$iterKO.'</b>'.$this->view->endspan.'.</p>';
		}
		else { // iTop Production et Compte iTop déclaré => tout va bien, on laisse tout en gris
			$this->view->span1 = $this->view->span2 = $this->view->endspan = '';
			$this->view->environment = 'Production';
			$this->view->itopinteraction = '<p>'.$itopOK.' : <b>'.$this->view->span2.$iterOK.'</b>'.$this->view->endspan.'.</p>';
		} 
		
	}

	
	public function changeprefAction() {
		$this->_helper->viewRenderer->setNoRender(true);
		// pas de layout autour
		$this->_helper->layout()->disableLayout();
		if ($this->_request->isPost()) {
			$session = new Zend_Session_Namespace('Zend_Auth');
			$data = $this->_request->getPost();
			if ($data['param'] == 'CHG_PWD') {
				$user = new Auth_Model_DbTable_User();
				//$rowUser = new Auth_Model_DbTable_Row_User($config)
				$rowUser = $user->find($session->pref->_id);
				//Zend_Debug::dump($rowUser);
				$rowUser->current()->setPassword($data['value']);
				$rowUser->current()->save();
			}
			else {
				$Opref = $session->pref;
				$Opref->changePref($data['param'],$data['value']);
			}
		}
		
		
		
		
		/*$pref = new Syleps_Models_DbTable_UserPref();
		// on sauve ici la préfrence de l'écran d'accueil que l'on nomme HOME_SERVICES
		$pref -> savePref($data['user_id'],'USER_FILTER',$data['value']);
		
		
		$session = new Zend_Session_Namespace('Zend_Auth');
		$session->filter->userFilter =$data['is_checked'];
			*/
		
	}
	
	
	
	public function changeuserfilterAction() {
			$this->_helper->viewRenderer->setNoRender(true);
			// pas de layout autour
			$this->_helper->layout()->disableLayout();
			if ($this->_request->isPost()) {
				$data = $this->_request->getPost();
				
			}
			$pref = new Syleps_Models_DbTable_UserPref();
			// on sauve ici la préfrence de l'écran d'accueil que l'on nomme HOME_SERVICES
			$pref -> savePref($data['user_id'],'USER_FILTER',$data['is_checked']);
			$session = new Zend_Session_Namespace('Zend_Auth');
			$session->filter->userFilter =$data['is_checked']; 
			
			$listFilter = new Syleps_Form_Preference($session->filter->locationList, //$tab_location_list,
					$session->filter->yearList, //$tab_year,
					$session->filter->locationFilter, //$tab_location_selected,
					$session->filter->yearFilter, //$tab_year_selected,
					$session->filter->userFilter);
			//Zend_Debug::dump($session->filter->userFilter,'Variable de Session passé au formulaire');
			$js = "<script type='text/javascript'>
				$(document).ready(function() {
					$('.filterLocation').dropdownchecklist( { icon: {}, width: 150 ,firstItemChecksAll: true } );	
					//
					$('.filterYear').dropdownchecklist( { icon: {}, width: 150 ,firstItemChecksAll: true } );});
				</script>";
			
			echo $js.$listFilter;
		}	
		

	}