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


// Class for the Widgdet which populate the Dashboard
class Portal_Dashboard_Widget {
	
	public $_widget;
	public $_size;
	
	public function __construct($widget){
		//Zend_Debug::dump($widget);
		$type = $widget->type;
		$this->_size = $widget->size;
		switch ($type) {
			case 'Pie':
				/*$AParam = json_decode($widget->parameter);
				$count_attribute = $AParam->count_attribute;
				$where = $AParam->where;
				// On récupère les données via le Webservice
				$webService =Zend_Controller_Action_HelperBroker::getStaticHelper('ItopWebservice');
				// Récupération de la source
				$function = $widget->source;
				$Piedata = $webService->$function($where);
				//Zend_Debug::dump($Piedata);
				$Widget = new Portal_Dashboard_Widget_Pie($widget->name, $widget->id,$Piedata,$count_attribute);
				$this->_widget = $Widget;*/
				$Widget = new Portal_Dashboard_Widget_Pie($widget);
				$this->_widget = $Widget;
				break;
			case 'Table' :
				$Widget = new Portal_Dashboard_Widget_Table($widget);
				$this->_widget = $Widget;
				break;
			case 'Text' :
				//$Widget = new Portal_Dashboard_Widget_Text($widget->name, $widget->id, $widget->parameter);
				$Widget = new Portal_Dashboard_Widget_Text($widget);
				$this->_widget = $Widget;
				break;
			case 'Flatpage' :
				//$session = new Zend_Session_Namespace('Zend_Auth');
				//$this->_language = $session->pref->_language;
				//$Widget = new Portal_Dashboard_Widget_Flatpage($widget->name, $widget->id,$widget->parameter,$session->pref->_language);
				$Widget = new Portal_Dashboard_Widget_Flatpage($widget);
				$this->_widget = $Widget;
				break;
			/* à développer selon les besoins ... cf. http://humblesoftware.com/flotr2/
			 * case 'Bar' :
			$Widget = new Syleps_Dashboard_Widget_Bar('Informations', 'id5');
			break;
			case 'Line' :
			$Widget = new Syleps_Dashboard_Widget_Line('Informations', 'id6');
			break;*/
		}
		
	}
	
	
	public function getJSONWidget(){
		return $this->_widget->getJSONWidgetElement();
	}
	
}