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
class Portal_Dashboard_Widget_Pie {

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
	
	/*public function __construct($name, $id,$OQLdata, $count_attribute){
		//For translation
		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
		if (null === $viewRenderer->view) {
			$viewRenderer->initView();
		}
		$view = $viewRenderer->view;
		$this->_name = $view->translate($name);
		$this->_id = $id;
		$this->_ChartData = $this->buildData($OQLdata, $count_attribute);
		
		//Zend_Debug::dump($this->_PieChartData);
	}*/
	
	public function __construct($widget){ //$name, $id,$OQLdata, $count_attribute){
		//For translation
		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
		if (null === $viewRenderer->view) {
			$viewRenderer->initView();
		}
		$view = $viewRenderer->view;
		$this->_name = $view->translate($widget->name);
		$this->_id = $widget->id;
		
		$AParam = json_decode($widget->parameter);
		$count_attribute = $AParam->count_attribute;
		$where = $AParam->where;
		// On récupère les données via le Webservice
		$webService =Zend_Controller_Action_HelperBroker::getStaticHelper('ItopWebservice');
		// Récupération de la source
		$function = $widget->source;
		$Piedata = $webService->$function($where);
		
		$this->_ChartData = $this->buildData($Piedata, $count_attribute);
		
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
			widgetType : "chart",
			widgetContent : {
									data : '.$this->_ChartData.',
									options : {
										HtmlText : false,
										grid : {
											verticalLines : false,
											horizontalLines : false
										},
										xaxis : {
											showLabels : false
										},
										yaxis : {
											showLabels : false
										},
										pie : {
											show : true,
											explode :0,
											labelFormatter: function(total, value) {  
												var percent =  (value/total)*100;                       
									            return value +\' (\'+percent.toFixed(2)+\'%)\';
											}
										},
										mouse : {
											track : true
										},
										legend : {
											position : "se",
											backgroundColor : "#D2E8FF"
										}
									}
								}
							
							}';
			return $script;
	}
	

	
	/*We compute the OQL query to have the data like the Pie Chart expected.*/
	private function buildData($dataFromOql, $count_attribute){
		
		//For translation
		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
		if (null === $viewRenderer->view) {
			$viewRenderer->initView();
		}
		$view = $viewRenderer->view;
		
		
		$data = $this->cumulTab($dataFromOql, $count_attribute);
		//Zend_Debug::dump($data3);
		$ViewGraph1 = '[';
		$i=0;
		foreach ($data as $label=>$qte) {
			$translated_label = $view->translate($label);
			$ViewGraph1 .= '{data : [[0,'.$qte.']], label : "'.$translated_label.'"}';
			$i++;
			if ($i < count($data)) {$ViewGraph1 .= ',';}
		}
		$ViewGraph1 .= ']';
		return $ViewGraph1;
	}
	
	private function cumulTab($data,$count_attribute)
	{	
		/*We put in an array each line
		 * array(12) {
				  [0] => array(5) {
				    ["ref"] => string(8) "R-006667"
				    ["resolution_code"] => string(15) "assistance_util"
				    ["service_name"] => string(6) "ATELYS"
				    ["caller_id_friendlyname"] => string(13) "Alain BAYONNE"
				    ["status"] => string(9) "qualified"
				  }
				  [1] => array(5) {
				    ["ref"] => string(8) "R-009304"
				    ["resolution_code"] => string(15) "assistance_util"
				    ["service_name"] => string(6) "ATELYS"
				    ["caller_id_friendlyname"] => string(13) "Alain BAYONNE"
				    ["status"] => string(9) "qualified"
				  }
				  ....
		 */
		
		$i = 0;
		if (count($data['objects'])>0) {
			$tab_result = array();
			foreach ($data['objects'] as $result) {
				$tab_result[$i] = $result['fields'];
				$i++;
			}
			/*Now, We want have :
			 * array(3) {
					  ["qualified"] => int(10)
					  ["pending"] => int(1)
					  ["resolved"] => int(1)
					}
			 */
			$tab_res = Array();
			$i = 0;
			while ($i < count($tab_result))
			{ $tab_res[$i] = $tab_result[$i][$count_attribute];
			//if (is_null($tab_list[$i][$count_attribute]))
			if (strlen($tab_result[$i][$count_attribute])==0)
			{
				$tab_res[$i]='Undefined';
			}
			$i++;}
			//print_r(array_count_values($tab_res));
			return array_count_values($tab_res);
		}
		
	}

}