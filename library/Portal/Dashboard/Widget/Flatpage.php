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
class Portal_Dashboard_Widget_Flatpage {

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
	protected $_language;
	
	public function __construct($widget){
		$session = new Zend_Session_Namespace('Zend_Auth');
		$this->_language = $session->pref->_language;
		//For translation
		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
		if (null === $viewRenderer->view) {
			$viewRenderer->initView();
		}
		$view = $viewRenderer->view;
		$this->_name = $view->translate($widget->name);
		
		$this->_id = $widget->id;
		if ($this->_language == 'fr')  { $flatid = 'id';}
        	else {$flatid = 'original_id';};
        $controllerRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('getObjectOr404');
        try { $flatpageRow = $controllerRenderer->direct('cms/flatpage', array($flatid                    =>  $widget->parameter, //$flatpageID,
        																	'is_published'          =>  1,
        																	'published_at__lt'      =>  new Zend_Db_Expr('NOW()')));
        
        		$this->_text =  addslashes($flatpageRow->body);
                $array = array("\r\n", "\n\r", "\n", "\r");
		        $this->_text = str_replace($array, "", $this->_text);
		} catch (Exception $e) 
			{$this->_text = 'Flatpage introuvable';}
			
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
					enableRefresh : false,
					widgetContent : "'.$this->_text.'"
				}';
			return $script;
	}

}