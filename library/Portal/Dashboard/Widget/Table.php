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
class Portal_Dashboard_Widget_Table extends Portal_Dashboard_Widget_Widget {

	/* Table Chart need datas, 
	 * The Array in entry will be the result of an OQL query
	 * and the fileds to display
	*/
	
	protected $_ChartData; //String which contains the data to drawn the Pie Chart.
	protected $_ChartLabel;
	protected $_name;
	protected $_id;
	
	public function __construct($widget){
		
		//For translation
		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
		if (null === $viewRenderer->view) {
			$viewRenderer->initView();
		}
		$view = $viewRenderer->view;
		$this->_name = $view->translate($widget->name);
		
		// On récupère les données via le Webservice
		$webService =Zend_Controller_Action_HelperBroker::getStaticHelper('ItopWebservice');
		// Récupération de la source
		$function = $widget->source;
		//Zend_Debug::dump($Opref);
		//echo $session->pref->_userFilter;
		$OQLdata = $webService->$function();
		
		$AlistField = json_decode($widget->parameter);		
		$this->_id = $widget->id;
		$this->_ChartData = $this->buildData($OQLdata, $AlistField);
		$this->_ChartLabel = $this->buildTitle($AlistField);
	
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
					widgetType : "table",
					enableRefresh : false,
					refreshCallBack : function(widgetData){
						//console.log(\'1rst \'+launchAjax('.$this->_id.'));
						//		refresh2(widgetData);
							//launchAjax('.$this->_id.');	
					},
					widgetContent : {
							"aaData" : '.$this->_ChartData.',
							"aoColumns" : '.$this->_ChartLabel.',
							"iDisplayLength": 25,
							"aLengthMenu": [[1, 25, 50, -1], [1, 25, 50, "All"]],
							"bPaginate": true,
							"aaSorting" : [[0, "desc"]],
							"bAutoWidth": false
						}
				}'; 
		
		return $script;
	}
	
	
	/*We compute the OQL query to have the data like the Pie Chart expected.*/
	private function buildData($dataFromOql, $AlistField){
		$ViewGraph1 = '[';
		$j=0;
		foreach ($dataFromOql as $field=>$value) {
			//Zend_Debug::dump($field);
			$ViewGraph1 .= '[';
			$i=0;
			foreach ($AlistField as $key=>$val) {
				//echo $val['label'] .'->'.$value[$val['field']];
				//$ViewGraph1 .='\''.$value[$val->field].'\'';
				$ViewGraph1 .='\''.str_replace("'", "\'", $value[$val->field]).'\'';
				$i++;
				if ($i < count($AlistField)) {$ViewGraph1 .= ',';}
			}
			$ViewGraph1 .= ']';
			$j++;
			//Zend_Debug::dump(count($dataFromOql));
			if ($j < count($dataFromOql)) {$ViewGraph1 .= ',';}
			//Zend_Debug::dump($value); 
			//$ViewGraph1 .= '['$value.']], label : "'.$label.'"},';
		}
		$ViewGraph1 .= ']';
		return $ViewGraph1;
	}

	private function buildTitle($AlistField){
		//For translation
		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
		if (null === $viewRenderer->view) {
			$viewRenderer->initView();
		}
		$view = $viewRenderer->view;
		
		$ViewGraph1 = '[';
		$i=0;
		foreach ($AlistField as $key=>$val) {
			$label = $view->translate($val->label);
			$ViewGraph1 .= '{"sTitle": "'.$label.'"}';
			$i++;
			if ($i < count($AlistField)) {$ViewGraph1 .= ',';}
		}
		$ViewGraph1 .= ']';
		return $ViewGraph1;
	}
	
	

}
