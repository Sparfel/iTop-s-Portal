<?php

class Home_IndexController extends Centurion_Controller_Action
{
	protected $_bypass = TRUE;

	public function preDispatch()
	{
		$this->_helper->authCheck();
		$this->_helper->aclCheck();
		Zend_Layout::getMvcInstance()->assign('titre', $this->view->translate('Votre espace Services'));
		$this->view->headTitle()->prepend('iTop');
	}

	public function init() {
		 
	}


	public function indexAction() {
		 
		/*echo 'on test ici le lien vers les images';
		 $DbStyleService = new Portal_Model_DbTable_AdminStyleServices();
		$rowset = $DbStyleService->find(1);
		foreach ($rowset as $row) {
		$img =  $row->getImageService();
		//Zend_Debug::dump($img);
		echo '<img src="'.$img->getStaticUrl().'">';
		}*/
		 
		if ($this->_request->isPost()) {
			$this->view->page = 'ValidForm';
			$session = new Zend_Session_Namespace('Zend_Auth');
			$SrvEltTpl = $session->ServiceElementTemplate;
			$newRequest = new Portal_Itop_Form_TemplateForm($SrvEltTpl,null);
			$formData = $this->_request->getPost();
			if ($newRequest->isValid($formData)) {
				//echo 'Title : '.$newRequest->getValue('title');
				try {
					//Création de l'incident titre / description
					$webservice = $this->_helper->getHelper('ItopWebservice');
					$content = $webservice->CreateServiceRequest($newRequest->getValue('title'),
							$newRequest->getValue('description'),
							$session->Service,
							$session->ServiceElement
					);

					$this->view->content = $content;
					$this->view->action='validation';
					// on ajoute la pièce jointe
					if (is_array($content))
					{
						foreach($content as $cle => $request) :
						if (is_array($request))
						{
							foreach ($request as $key => $data) :
							$item_id = $data['fields']['id'];
							$userRequestName = $data['fields']['friendlyname'];
							$msg = $data['message'];
							$item_class = $data['class'];
							//on passe le n° du ticket à la vue
							$this->view->RefRequest = $userRequestName;
							endforeach;
						}
						endforeach;
							
						//Ajout des Extra Data lié au formulaire
						if ( !($SrvEltTpl == null) AND count($SrvEltTpl->_field_list)>0)
						{
							$tab_data = array();
							$i = 1;
							foreach($SrvEltTpl->_field_list as $cle => $field) :
							$tab_data[$i]['code'] = $field->getiTopCode();
							$tab_data[$i]['label'] = $field->getLabel();
							$tab_data[$i]['input_type'] = $field->getInputType();
							$tab_data[$i]['value'] = $newRequest->getValue($field->getCode());
							//$tab_data[$field->getCode()] = 	$newRequest->getValue($field->getCode());
							$i++;
							endforeach;
							$extraData =  $webservice->AddExtraData($SrvEltTpl->getId(),$item_id,$tab_data);
						}
						//Ajout des fichiers joints
						for ($i=0;$i < count($_FILES['tab_files']['name']); $i++)
						{
							$name = $_FILES['tab_files']['name'][$i];
							$type = $_FILES['tab_files']['type'][$i];
							if (!(empty($_FILES['tab_files']['tmp_name'][$i])))
							{
								$fileData =file_get_contents($_FILES['tab_files']['tmp_name'][$i]);
								$fileData = base64_encode($fileData);
								$attachment = $webservice->AddAttachment($name,$fileData,$item_class,$item_id,$type,$session->_org_id);

							}
						}

					}

						
					//$this->_helper->redirector('index', 'newrequest', 'atelys', array('ref_request'=>$userRequestName));
				}
				catch(Zend_Exception $e) {
					echo '*'.$e->getMessage();
				}
			}
			else {
				//Formulaire doit être verrouillé pour forcé la saisie des champs.
				echo 'Ticket non créé.';
			}

		}
		else {
			//Gestion du javascripts pour les pièces jointes
			$this->view->headScript()->appendFile('/layouts/frontoffice/js/jquery.MultiFile.js');
			$serviceRequest = $this->_request->getParam('Service',null);
			$serviceEltRequest = $this->_request->getParam('ServiceSubcategory',null);
			$serviceEltTpl = $this->_request->getParam('tpleltservice',null);
			if (isset($serviceRequest)) { //Etape 2
				$this->showServiceElement($serviceRequest);
			}
			else if (isset($serviceEltRequest)) { //Etape 3
				$this->showServiceElementTemplate($serviceEltRequest);
			}
			else if (isset($serviceEltTpl)) { //Etape 4
				$this->showServiceElementTemplateForm($serviceEltTpl);
			}
			else //Etape 1
			{	$this->showService();
	   
			}
		}
		//echo 'Etape : '.$this->view->page;
	}

	//Affichage des Services
	public function showService() {
		$session = new Zend_Session_Namespace('Zend_Auth');
		//On vide les variables de session pour ne pas hériter des valeurs précédentes qui trainent ...
		//if (isset($session->ServiceCatalog)) {$session->ServiceCatalog = null;}
		if (isset($session->Service)) {
			$session->Service = null;
		}
		if (isset($session->ServiceElement)) {
			$session->ServiceElement = null;
		}
		if (isset($session->ServiceElementTemplate)) {
			$session->ServiceElementTemplate = null;
		}
		// Home pour les employés Syleps
		//$this->view->headScript()->appendFile('/public/layouts/frontoffice/js/jquery-ui-1.9.1.custom.min.js');
		//Gestion de la sauvegarde des preferences (position des items)
		$preference = $this->_helper->getHelper('SavePref');
		$this->_helper->getHelper('SavePref')->init();
		 
		//if ($session->pref->_org_name =='SYLEPS' ){
		if (true){
			//if (strpos(strtoupper($session->pref->_org_name), 'SYLEPS') >= 0){
			//Zend_Debug::dump($session->pref->_org_name);
			//Zend_Debug::dump(strpos(strtoupper($session->pref->_org_name), 'SYLEPS1'));
			$this->view->page = 'iTopServices';
			// Récupération des styles CSS des Services et passage de celui-ci à la vue
			//$this->view->StyleList = $this->getStyleItopService();

			//Création du catalogue de Services.
			$catalog = new Portal_Itop_ServicesCatalog($session->pref->_org_id);
			$session->ServiceCatalog = $catalog; // On le conserve en global
			$this->view->nbServices = $catalog->_nb_services;
			$this->view->ServicesList = $catalog->_list_services;
			//on génère le javascript
			// paramère 1 : spanID (ensemble des icones), 2 : ulClass (class des ul du groupe), 3  : action déclencher sur modif
			$script = $preference->savePrefHomeServicesScript('#AllServices','.product-list-itop','savepref','HOME_SERVICES');
			//on gère le tri du tableau et on le passe à la vue
			$pref = new Portal_Model_DbTable_UserPref();
			$tab = $pref->getPref($session->pref->_user_id,'HOME_SERVICES');

			$this->view->tab_tri = $tab;
			/*echo '<PRE>';
			 print_r($catalog->_list_services);
			echo '</PRE>';*/
			//Zend_Debug::dump($tab);
		}
		//Home pour les clients Externe
		else {$this->view->page = 'ClientServices';
		//on génère le javascript
		$script = $preference->savePrefHomeServicesScript('#AllServices','.product-list','savepref','HOME_SERVICES');
		//Dans le cas des clients Helpdesk, on affiche tous les services.
		//contrairement à Syleps, les liens ne se font pas vers les templates de services
		//mais vers différents module du site.
		$catalog = new Portal_Service_ServicesCatalog($session->org_id,$session->org_name);
		$this->view->nbServices = $catalog->_nb_services;
		$this->view->ServicesList = $catalog->_list_services;
		//print_r( $catalog->_list_services);
		//Zend_Debug::dump($catalog->_list_services);
		//on gère le tri du tableau et on le passe à la vue
		$pref = new Portal_Model_DbTable_UserPref();
		$tab = $pref->getPref($session->pref->_user_id,'HOME_SERVICES');
		$this->view->tab_tri = $tab;
		//Zend_Debug::dump($tab);
		}
		// on injecte le javascript dans la vue
		$this->view->headScript()->appendScript($script);
		//echo 'Etape : '.$this->view->page;
}

//Affichage des Elements de services
public function showServiceElement($service_id) {
	$session = new Zend_Session_Namespace('Zend_Auth');
	//Pour la vue, qu'affiche-t-on ?
	$this->view->page = 'EltService';
	$catalog = $session->ServiceCatalog;
	 
	//On récupère le Service sélectionné;
	$Srv = $catalog->_list_services[$service_id];
	 
	// On génère à la demande la liste des éléments de services du service sélectionné
	$Srv->getServiceElement();
	$session->Service = $Srv; // On le conserve en global
	//On passe ensuite la liste à la vue.
	$this->view->serviceName = $Srv->getName();
	$this->view->serviceDescription = $Srv->getDescription();
	$this->view->ServicesElementList = $Srv->_list_serviceElement;
	 
	$this->view->NbServiceElement = $Srv->_nbServiceElement;
	$this->view->serviceElementId = $Srv->getId();
	//Le nom du paramètre de stockage de la configuration et suffixé de l'ID
	//car différents éléments de service ont différents template ...
	//on sauvegarde la position des item dans la base et on restitue selon le user
	//la config si elle existe.
	$param_cfg_Srv_Elt = 'HOME_SERVICES_ELEMENT_'.$Srv->getId();
	//$this->view->StyleList= $this->getStyleItopServiceElement();
	$preference = $this->_helper->getHelper('SavePref');
	$this->_helper->getHelper('SavePref')->init();
	$script = $preference->savePrefHomeServicesScript('#AllServicesElement','.product-list-Elt-Srv-itop','savepref',$param_cfg_Srv_Elt);
	$this->view->headScript()->appendScript($script);
	 
	//on gère le tri du tableau et on le passe à la vue
	$pref = new Portal_Model_DbTable_UserPref();
	$tab = $pref->getPref($session->pref->_user_id,$param_cfg_Srv_Elt);
	$this->view->tab_tri = $tab;
	 
	 
	//Si 1 seul élement de service, on passe à la suite
	if ($Srv->_nbServiceElement == 1 and $this->_bypass) {
		foreach ($Srv->_list_serviceElement as $serviceElement) {
			$this->showServiceElementTemplate($serviceElement->getId());
		}
	}
	// Si pas d'élément de service, on affiche le formulaire basic
	else if ($Srv->_nbServiceElement == 0) {
		//$RequestForm = new Atelys_Form_newRequest();
		$RequestForm = new Portal_Itop_Form_TemplateForm($SrvEltTpl,null);
		$this->view->form=$RequestForm;
	}
	//echo 'Etape : '.$this->view->page;
}

//Affichage eds Template des éléments de Services
public function showServiceElementTemplate($serviceElement_id){
	$session = new Zend_Session_Namespace('Zend_Auth');
	$this->view->page = 'FrmEltService';
	$this->view->message = 'On affiche ici le formulaire de la demande de service';
	$Srv = $session->Service; // On récupère l'objet Service mis en global
	$this->view->serviceName = $Srv->getName();
	$this->view->serviceDescription = $Srv->getDescription();
	 
	//Zend_Debug::dump($session->ServiceCatalog);
	$SrvElt = $Srv->_list_serviceElement[$serviceElement_id]; // On récupère l'élément de service sélectionné
	$SrvElt->getTemplateList(); // On récupère à la demande les élements de template
	//On memorise en global l'element de service
	$session->ServiceElement = $SrvElt;
	//On passe ensuite la liste à la vue.
	$this->view->serviceElementName = $SrvElt->getName();
	$this->view->serviceElementDescription = $SrvElt->getDescription();
	$this->view->serviceElementId = $SrvElt->getId();
	 
	//Le nom du paramètre de stockage de la configuration et suffixé de l'ID
	//car différents éléments de service ont différents template ...
	//on sauvegarde la position des item dans la base et on restitue selon le user
	//la config si elle existe.
	$param_cfg_Srv_Elt_Tpl = 'HOME_SERVICES_ELEMENT_TEMPLATE_'.$SrvElt->getId();
	 
	$this->view->ServiceElementTemplateList = $SrvElt->_template_list;
	 
	$this->view->NbServiceElementTemplate = $SrvElt->_nbTemplate;
	//Test pour mettre des icones comme pour les services
	//Zend_Debug::dump( $this->getStyleSylepsServiceElementTemplate());
	//On passe à la vue le style pouir l'affichage
	//$this->view->StyleList= $this->getStyleItopServiceElementTemplate();
	$preference = $this->_helper->getHelper('SavePref');
	$this->_helper->getHelper('SavePref')->init();
	$script = $preference->savePrefHomeServicesScript('#AllServicesElementTemplate','.product-list-Frm-Elt-Srv-itop','savepref',$param_cfg_Srv_Elt_Tpl);
	$this->view->headScript()->appendScript($script);
	 
	//on gère le tri du tableau et on le passe à la vue
	$pref = new Portal_Model_DbTable_UserPref();
	$tab = $pref->getPref($session->pref->_user_id,$param_cfg_Srv_Elt_Tpl);
	$this->view->tab_tri = $tab;
	//Zend_Debug::dump( $tab);
	 
	 
	 
	//Si 1 seul Template d'élement de service, on passe à la suite
	if ($SrvElt->_nbTemplate == 1 and $this->_bypass) {
		foreach ( $SrvElt->_template_list as $serviceElementTemplate) {
			$this->showServiceElementTemplateForm($serviceElementTemplate->getId());
		}
	}
	// Si pas de template pour l'élément de service, on affiche le formulaire basic
	else if ($SrvElt->_nbTemplate == 0) {
		//$RequestForm = new Atelys_Form_newRequest();
		$RequestForm = new Portal_Itop_Form_TemplateForm(null,$SrvElt);

		$this->view->form=$RequestForm;

	}
	//echo 'Etape : '.$this->view->page;
	$this->view->session = $session;
}

//Affichage du formulaire
public function showServiceElementTemplateForm($serviceElementTemplate_id){
	$this->view->headScript()->appendFile('/layouts/frontoffice/js/jquery-ui-1.9.1.custom.min.js');
	//$this->view->headLink()->appendStylesheet('/public/layouts/frontoffice/css/jquery-ui.css');
	$this->view->headLink()->appendStylesheet('/layouts/frontoffice/css/smoothness/jquery-ui-1.10.4.custom.css');
	 
	$this->view->headScript()->appendFile('/layouts/frontoffice/js/jquery-ui-timepicker-addon.js');
	$this->view->headLink()->appendStylesheet('/layouts/frontoffice/css/jquery-ui-timepicker-addon.css');
	 
	//$this->view->headScript()->appendFile('/public/layouts/frontoffice/js/calendar/tcal.js');
	//$this->view->headLink()->appendStylesheet('/public/layouts/frontoffice/css/calendar/tcal.css');
	 
	$session = new Zend_Session_Namespace('Zend_Auth');
	$this->view->page = 'TplFrmEltService';
	$this->view->message = 'On affiche ici le template n°'.$serviceElementTemplate_id;
	 
	//On récupère les informations pour aider l'utilisateur.
	$Srv = $session->Service;
	$this->view->serviceName = $Srv->getName();
	$this->view->serviceDescription = $Srv->getDescription();
	 
	$SrvElt = $session->ServiceElement; // On récupère l'objet Element de Service mis en global
	//On récupère les informations pour aider l'utilisateur.
	$this->view->serviceElementName = $SrvElt->getName();
	$this->view->serviceElementDescription = $SrvElt->getDescription();
	$SrvEltTpl = $SrvElt->_template_list[$serviceElementTemplate_id];
	$SrvEltTpl->getFieldList(); // On récupère à la demande le contenu du Template
	//On memorise en global le template de l'element de service
	$session->ServiceElementTemplate = $SrvEltTpl;
	//On passe ensuite la liste à la vue.
	$this->view->serviceElementTemplateName = $SrvEltTpl->getName();
	$this->view->serviceElementTemplateDescription = $SrvEltTpl->getDescription();
	$this->view->ServiceElementTemplateField = $SrvEltTpl->_field_list;
	$RequestForm = new Portal_Itop_Form_TemplateForm($SrvEltTpl,null);
	//echo $SrvEltTpl->getName();
	$this->view->form=$RequestForm;
	 
	$this->view->session = $session;
	 
	/*echo '<PRE>';
	 print_r(explode(',','maintenance,SIC, SIS, autre'));
	echo '</PRE>';*/
	//echo 'Etape : '.$this->view->page;
}


 

/*
 * Fonction qui rend un tableau contenant la correspondante identifiant Service / nom du style CSS
* l'identifiant du service est indispensable car il est la clé pour récupérer les élements de services
* et ensuite les templates associés
*/
/*
 * Add this to application.ini
 * number are thie service Id. useless now because we use configuration from database ...
 * It will be deleted soon !
 * 
		;Relation entre les noms iTop des Services interne à Syleps et les noms des Styles CSS pour la présentation.
		; plutot que le libellé, on va y placer l'identifiant du service dans iTop
		syleps.services.style.infra_si = 8
		syleps.services.style.assistance = 14
		syleps.services.style.administration = 13
		syleps.services.style.amelioration = 2
		syleps.services.style.reporting = 15
		syleps.services.style.virtual = 24
		syleps.services.style.access = 18
		syleps.services.style.collaboration = 21
		syleps.services.style.repair = 23
		syleps.services.style.bureautique = 20
		syleps.services.style.demo = 22
		syleps.services.style.backup = 25
		syleps.services.style.security = 26
		syleps.services.style.user = 27
		syleps.services.style.buy = 19
		
		itop.services.style.atelys = 0
		itop.services.style.admys = 0
		itop.services.style.movys = 0
		itop.services.style.skolys = 0
		itop.services.style.prevys = 0
		itop.services.style.revys = 0
		itop.services.style.migsys = 0
		itop.services.style.ulys = 0
		itop.services.style.storsys = 0
		
		syleps.services.element.style.pc_demo_phenix = 772
		syleps.services.element.style.new_demo_access = 773
		syleps.services.element.style.visioconf_sharedspace = 778
		syleps.services.element.style.telco = 779
		syleps.services.element.style.visioconf_test = 971
		syleps.services.element.style.new_workstation = 767 
		syleps.services.element.style.new_networkdrive = 770
		syleps.services.element.style.new_software = 768
		syleps.services.element.style.lend_workstation = 999
		syleps.services.element.style.reinstall_workstation = 828
		syleps.services.element.style.backup = 781
		syleps.services.element.style.restore = 782*
		syleps.services.element.style.new_secured_access = 764
		syleps.services.element.style.configure_si = 875
		
		syleps.services.element.template.style.new_user = 1
		syleps.services.element.template.style.new_trainee = 4
		syleps.services.element.template.style.visioconf = 2
		syleps.services.element.template.style.gotomeeting = 5 
 * 
 */

/*function getStyleItopService() {
	$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', 'production');
	$tab_service_style = array();
	$tab_service_style['infra_si'] = $config->syleps->services->style->infra_si;
	$tab_service_style['assistance'] = $config->syleps->services->style->assistance;
	$tab_service_style['administration'] = $config->syleps->services->style->administration;
	$tab_service_style['amelioration'] = $config->syleps->services->style->amelioration;
	$tab_service_style['reporting'] = $config->syleps->services->style->reporting;
	$tab_service_style['virtual'] = $config->syleps->services->style->virtual;
	$tab_service_style['access'] = $config->syleps->services->style->access;
	$tab_service_style['collaboration'] = $config->syleps->services->style->collaboration;
	$tab_service_style['repair'] = $config->syleps->services->style->repair;
	$tab_service_style['bureautique'] = $config->syleps->services->style->bureautique;
	$tab_service_style['demo'] = $config->syleps->services->style->demo;
	$tab_service_style['backup'] = $config->syleps->services->style->backup;
	$tab_service_style['security'] = $config->syleps->services->style->security;
	$tab_service_style['user'] = $config->syleps->services->style->user;
	$tab_service_style['buy'] = $config->syleps->services->style->buy;
	$tab_service_style_itop = array_flip($tab_service_style);
	return $tab_service_style_itop;
}

function getStyleItopServiceElement() {
	$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', 'production');
	$tab_service_element_style = array();
	$tab_service_element_style['pc_demo_phenix'] = $config->syleps->services->element->style->pc_demo_phenix;
	$tab_service_element_style['new_demo_access'] = $config->syleps->services->element->style->new_demo_access;
	$tab_service_element_style['visioconf_sharedspace'] = $config->syleps->services->element->style->visioconf_sharedspace;
	$tab_service_element_style['telco'] = $config->syleps->services->element->style->telco;
	$tab_service_element_style['visioconf_test'] = $config->syleps->services->element->style->visioconf_test;
	$tab_service_element_style['new_workstation'] = $config->syleps->services->element->style->new_workstation;
	$tab_service_element_style['new_networkdrive'] = $config->syleps->services->element->style->new_networkdrive;
	$tab_service_element_style['new_software'] = $config->syleps->services->element->style->new_software;
	$tab_service_element_style['lend_workstation'] = $config->syleps->services->element->style->lend_workstation;
	$tab_service_element_style['reinstall_workstation'] = $config->syleps->services->element->style->reinstall_workstation;
	$tab_service_element_style['backup'] = $config->syleps->services->element->style->backup;
	$tab_service_element_style['restore'] = $config->syleps->services->element->style->restore;
	$tab_service_element_style['new_secured_access'] = $config->syleps->services->element->style->new_secured_access;
	$tab_service_element_style['configure_si'] = $config->syleps->services->element->style->configure_si;
	 
	$tab_service_element_style_itop = array_flip($tab_service_element_style);
	return $tab_service_element_style_itop;
}



function getStyleItopServiceElementTemplate() {
	$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', 'production');
	$tab_service_element_template_style = array();
	$tab_service_element_template_style['new_user'] = $config->syleps->services->element->template->style->new_user;
	$tab_service_element_template_style['new_trainee'] = $config->syleps->services->element->template->style->new_trainee;
	$tab_service_element_template_style['gotomeeting'] = $config->syleps->services->element->template->style->gotomeeting;
	$tab_service_element_template_style['visioconf'] = $config->syleps->services->element->template->style->visioconf;
	$tab_service_element_template_style_itop = array_flip($tab_service_element_template_style);
	return $tab_service_element_template_style_itop;
	
}
*/
public function saveprefAction() {
	 
	//$this->_helper->viewRenderer->setNoRender(true);
	// pas de layout autour
	//$this->_helper->layout()->disableLayout();
	if ($this->_request->isPost()) {
		$data = $this->_request->getPost();
		$this->view->data = $data;
	}
	$pref = new Portal_Model_DbTable_UserPref();
	// on sauve ici la préfrence de l'écran d'accueil que l'on nomme HOME_SERVICES
	//$pref -> savePref($data['user_id'],'HOME_SERVICES',$data['pref']);
	$pref -> savePref($data['user_id'],$data['param_name'],$data['pref']);
	 
}
}