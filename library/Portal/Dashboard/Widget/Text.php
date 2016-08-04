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


// Class for the Widget Pie Chart
class Portal_Dashboard_Widget_Text {

	/* Pie Chart need datas, 
	 * The Array in entry will be the result of an OQL query
	 * We'll have an count attribute
	 * Example : the ticket per Status => all the ticket in entry and the attribute 'Status'.
	 * We will cumulate the ticket per status
	 * and then we will construct an array with this for the Pie Chart.	
	*/
	
	protected $_ChartData; //String which contains the data to drawn the Pie Chart.
	protected $_name;
	protected $_id;
	protected $_text;
	
	public function __construct($widget){
		//For translation
		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
		if (null === $viewRenderer->view) {
			$viewRenderer->initView();
		}
		$view = $viewRenderer->view;
		$this->_name = $view->translate($widget->name);
		$this->_id = $widget->id;
		$array = array("\r\n", "\n\r", "\n", "\r");
		$this->_text = str_replace($array, "<br/>", $widget->parameter);
		
		//Zend_Debug::dump($this->_PieChartData);
		//Modification du style si le Widget est double (prend 2 emplacements)
		if ($widget->size == "double") {
			$styles = 'li#Wid'.$this->_id.'{width:1005px;}';
			$view->headStyle()->appendStyle($styles);
		}
		
	}
	
	
	public function getJSONWidgetElement(){
		$script = '{
					widgetTitle : "'.$this->_name.'",
					widgetId : "Wid'.$this->_id.'",
					enableRefresh : true,
					refreshCallBack : function(widgetId){
						return randomString + new Date();
					},
					widgetContent : "'.$this->_text.'"
				}';
			return $script;
	}
	
	/*For translations*/
	private function LabelTranslate(){
		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
		if (null === $viewRenderer->view) {
			$viewRenderer->initView();
		}
		$view = $viewRenderer->view;
		//We use " and not ' in the translation string to be able to use \' in the string.
		$this->Alabel['user'] =  $view->translate("Utilisateur");
		$this->Alabel['organization'] =      $view->translate("Organisation");
	}
	
	

	


}