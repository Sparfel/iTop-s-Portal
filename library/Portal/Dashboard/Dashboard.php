<?php
/**
 * iTop's Portal
*
* LICENSE
*
* This source file is subject to the new BSD license that is bundled
* with this package in the file LICENSE.txt.
* @copyright   Copyright (c) 2015 Emmaunel Lozachmeur (http://emmanuel.synology.me)
* @version     $Id$
* @author      Emmanuel Lozachmeur <emmanuel.lozachmeur@gmail.com>
*/


// Class for the Dashboard which contains differents widgets
class Portal_Dashboard_Dashboard {
	
	protected $_script;
	
	protected $_baseurl;
	protected $_request;
	protected $_path;
	protected $_module;
	protected $_controller;
	protected $_view;	//La vue courante
	protected $_OPref; // les préférence utilisateurs
	protected $_dashboard; // le tableau de bord que l'on souhaite afficher (Name dans la table Widget)
	protected $_list_script_widget; // la liste des Widget triée selon la config (defaut ou choix user) pour l'affichage
	protected $_AWidget; // Array listant les Objet Widget que l'on va afficher
	
	public function __construct($dashboard){
		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
		if (null === $viewRenderer->view) {
			$viewRenderer->initView();
		}
		$view = $viewRenderer->view;
		$this->_view = $viewRenderer->view;
		
		$view->headScript()->appendFile('/layouts/frontoffice/js/datatable/jquery.dataTables.js');
		$view->headLink()->appendStylesheet('/layouts/frontoffice/css/datatable/jquery.dataTables.css');
		$view->headScript()->appendFile('/layouts/frontoffice/js/datatable/dataTables.tableTools.js');
		$view->headLink()->appendStylesheet('/layouts/frontoffice/css/datatable/dataTables.tableTools.css');
			
		$view->headScript()->appendFile('/cui/plugins/touchpunch/jquery.ui.touch-punch.js');
		$view->headScript()->appendFile('/cui/plugins/gitter/jquery.gritter.js');
		$view->headLink()->appendStylesheet('/cui/theme/css/gitter/css/jquery.gritter.css');
		$view->headScript()->appendFile('/cui/plugins/flotr2/flotr2.js');
			
		$view->headScript()->appendFile('/layouts/frontoffice/js/jquery-sDashboard.js');
		$view->headScript()->appendFile('/layouts/frontoffice/js/exampleData.js');
		$view->headLink()->appendStylesheet('/layouts/frontoffice/css/sDashboard.css');
		
		$session = new Zend_Session_Namespace('Zend_Auth');
		$this->_OPref = $session->pref;
		$this->_dashboard = $dashboard;
		

		$action = 'savepref';
		$controller = Zend_Controller_Front::getInstance();
		//Zend_Debug::dump( $controller->getRequest()->getBaseUrl());
		$this->_baseurl =  $controller->getRequest()->getBaseUrl();
		$this->_request =  $controller->getRequest()->getRequestUri();
		$this->_module = $controller->getRequest()->getParam('module');
		$this->_controller = $controller->getRequest()->getParam('controller');
		$this->_path = $this->_baseurl.'/'.$this->_module.'/'.$this->_controller.'/'.$action;
		
	}

	
	public function generateDashboard(){
		// Le init est implicite à la création de l'pbjet Dashboard
		// on genere la liste triées des widgets à afficher
		//$this->init($this->_dashboard);
		$this->getListListWidget($this->_OPref);
		// on génère le script JS nécessaire
		$this->generateScript();
	}
	
	

	private function getListListWidget(){
		$this->_list_script_widget = $this->orderWidget();
		//Zend_Debug::dump($this->_list_script_widget);
	}
	
	private function generateScript(){
		$script = '<script type="text/javascript">
			$(function() {
				function refresh2(widgetData){
					return {
							"aaData" : [myExampleData.constructTableWidgetData(),
										myExampleData.constructTableWidgetData(),
										myExampleData.constructTableWidgetData(),
										myExampleData.constructTableWidgetData(),
										myExampleData.constructTableWidgetData(),
										myExampleData.constructTableWidgetData(),
										myExampleData.constructTableWidgetData()
										],
		
										"aoColumns" : [{
										"sTitle" : "Engine"
										}, {
										"sTitle" : "Browser"
										}, {
										"sTitle" : "Platform"
										}]
									};
				}
		
				var randomString = "Lorem ipsum dolor sit amet,consectetur adipiscing elit. Aenean lacinia mollis condimentum. Proin vitae ligula quis ipsum elementum tristique. Vestibulum ut sem erat.";
		
				//**********************************************//
				//dashboard json data
				//this is the data format that the dashboard framework expects
				//**********************************************//
		
				var dashboardJSON = ['.
						$this->_list_script_widget.'];
				//basic initialization example
				$("#myDashboard").sDashboard({
					dashboardData : dashboardJSON
		
				});
		
				//widget order changes event example
				$("#myDashboard").bind("sdashboardorderchanged", function(e, data) {
					//alert(data.sortedDefinitions);
					$.gritter.add({
						position: \'bottom-left\',
						title : \'Order Changed\',
						time : 4000,
						text : \'Ordre modifié et sauvegardé.\'
					});
					if (console) {
						console.log("Sorted Array");
						console.log("+++++++++++++++++++++++++");
						console.log(data.sortedDefinitions);
		
						var props=\'\'
							for (prop in data.sortedDefinitions){ props+= data.sortedDefinitions[prop].widgetId.substr(3)+\',\'; }
						console.log (props)
						//substr(3) car on gere pour le style des widget avec id=WidX (ou X est la clé primaire de la table Portal_Widget)
						// mais on va stocker la clé pour l ordre et non l id.
						console.log("+++++++++++++++++++++++++");
					}
						launchAjax(props);
						//launchAjax();
						//alert(data.sortedDefinitions);
			
				});
								
				//table row clicked event example
				$("#myDashboard").bind("sdashboardrowclicked", function(e, data) {
					$.gritter.add({
						position: \'bottom-left\',
						title : data.selectedRowData[0],
						time : 1000,
						text : \'Redirection vers le ticket \'+data.selectedRowData[1]
					});

				
					pathArray = location.href.split( \'/\' );
					protocol = pathArray[0];
					host = pathArray[2];
					url = protocol + \'//\' + host + \'/request/openedrequest/index/language/'.$this->_OPref->_language.'/ref/\'+data.selectedRowData[0];
					//console.log(url);
					document.location.href=url;
				});
		
				function launchAjax(WidgetId){
						$.ajax({
							url       : \''.$this->_path.'\',
							type      : \'post\',
							dataType: "text",
							data      :{
								user_id : \''.$this->_OPref->_user_id.'\',
								pref : WidgetId,
								param_name : \''.$this->_dashboard.'\'
							},
							success : function(code_html, statut){
										console.log("success " +code_html );
										return code_html;
							},
							error :function(xhr, ajaxOptions, thrownError){
								console.log("Error "+ thrownError);
								//		retour = "error !!";
							},
							complete : function(resultat, statut){
										return resultat.responseText;
										console.log(resultat.responseText);
							}
							});
			
				}
			});
		</script>';
		//On ajoute ici les styles particuliers si size = double 
		// Obligé de la faire ici depuis que l'affichage est asynchrone via Ajax.
		$style = '<style>';
		foreach ($this->_AWidget as $key=>$value) {
			if ($value->_size == 'double') {
				$style .= 'li#Wid'.$key.'{width:1005px;}';
			}
			//echo $key .'->'.$value->_size; 
			//echo '<br>';
		} 
		$style .= '</style>';
		
		$this->script = $script;
		//$this->_view->headScript()->appendScript($script);
		echo $script.$style; 
		
		
	}
	
	public function getScript(){
		return $this->script;
	}
	
	public function listWidgets() {
		$ListWidget = new Portal_Model_DbTable_Widget();
		$A_Id_Widget_def = array(); // tableau des ID trié selon la base de données (default)
		$i = 0;
		foreach ($ListWidget->GetWidgets($this->_dashboard) as $widget){
			$AWidget[$widget->id] = new Portal_Dashboard_Widget($widget);
			//used to compare this list with the preference list if any widget has been added
			$A_Id_Widget_def[$i] = $widget->id;
			$i++;
		}
		return array('AWidget' => $AWidget,
					'A_Id_Widget_def' => $A_Id_Widget_def);
	}
	
	// Renvoir la liste des Widget trier ou non pour insertion dans le script Javascript
	private function orderWidget(){
		
		$list_script_widget = '';
		
		// Array of widgets
		$AWidget = array(); // Tableau des Widgets, la clé du tableau correspond à l'ID
		$A_Id_Widget_def = array(); // tableau des ID trié selon la base de données (default)
		$A_Id_Widget_tri = array(); // tableau des ID trié selon le choix utilisateur
		$A_Id_Widget_tri_completed = array(); // tableau des ID trié selon le choix utilisateur auquel on ajoute le ou les nouveaux Widget
											// qui ne figurent pas dans la liste des widgets
		$AlistWidget = $this->listWidgets();
		$A_Id_Widget_def = $AlistWidget['A_Id_Widget_def'];
		$AWidget = $AlistWidget['AWidget'];
		$this->_AWidget = $AWidget;
		//Zend_Debug::dump($A_Id_Widget_def);
		//Récupération des préférences utilisateurs si elles existent !
		
		/*TODO Pb si la liste des pref sauvegardé est plus longue que la liste des Widget actif
		 * 
		 */
		
		$A_Id_Widget_tri = array_filter(explode(',',$this->_OPref->getStoredPref('HOME_DASHBOARD')));
		if (count($A_Id_Widget_tri)>0) {
			$A_Id_Widget_tri_completed = $this->VerifWidgets($A_Id_Widget_def, $A_Id_Widget_tri);
			$i = 0;
			/*echo 'AWidget';
			Zend_Debug::dump(array_keys($AWidget));
			echo 'A_Id_Widget_tri';
			Zend_Debug::dump($A_Id_Widget_tri);
			echo 'A_Id_Widget_tri_completed';
			Zend_Debug::dump($A_Id_Widget_tri_completed);
			echo 'A_Id_Widget_def';
			Zend_Debug::dump($A_Id_Widget_def);*/
			foreach (array_filter($A_Id_Widget_tri_completed) as $key => $value){
				if ($i < count($A_Id_Widget_tri)) {$sep = ',';}
				else {$sep = '';}
				//echo $value.'<br/>';
				if (array_key_exists ($value,$AWidget )) { // si l'id widget est dans les préférences mais pas actif
					$widget = $AWidget[$value];
					//Zend_Debug::dump($widget);
					$list_script_widget .= $widget->getJSONWidget().$sep;
					//$list_script_widget .= 'Wid'.$widget->getJSONWidget().$sep;
				}
				
			}
		}
		else // pas de pref utilisateur pour le tri des Widget :
		{
			$i =0;
			foreach ($AWidget as $Widget) {
				if ($i < count($AWidget)) {$sep = ',';}
				else {$sep = '';}
				$list_script_widget .= $Widget->getJSONWidget().$sep;
				$i++;
			}	
		}
		
		return $list_script_widget;
		//Zend_Debug::dump($AWidget);
	
		
	}
	
	/*
	 * Compare the saved Array of Widget's position with the Widgets
	* if a new Widget exists and is not saved in preference Array, we
	* add him to the top of the list
	*/
	private function VerifWidgets($TabWidget, //List of Services defined in iTop
			$WidgetPreference) // if exists, it the order to show the service in the page, saved in database.
	{
		//Keys of $TabService are the Services Id (or Services Subcategory's Id)
		foreach ($TabWidget as $key => $values) {
			if (!(in_array( $values,$WidgetPreference) )){
				array_unshift($WidgetPreference, strval($values));
			}
		}
		return $WidgetPreference;
	}
	
}