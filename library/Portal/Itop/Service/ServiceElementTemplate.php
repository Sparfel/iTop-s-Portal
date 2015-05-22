<?php
class Portal_Itop_Service_ServiceElementTemplate {
	
	public $_id;
	public $_name;
	public $_description;
	public $_serviceelement_id;
	public $_requesttype;
	public $_field_list;
	public $_nbField;
	public $_parent_name;
	public $_parent_description;
	
	public function __construct($id,$name,$description,$serviceelement_id,$requesttype) {
		$this->_id = $id;
		$this->_name = $name;
		$this->_description = $description;
		$this->_serviceelement_id = $serviceelement_id;
		$this->_requesttype = $requesttype;
	}
	
	public function getName() {
		return $this->_name;
	}
	
	
	public function getId(){
		return $this->_id;
	}
	
	public function getDescription() {
		return $this->_description;
	}
	
	public function getFieldList() {
		$webService =Zend_Controller_Action_HelperBroker::getStaticHelper('SylepsItopWebservice');
		$TemplateField = $webService->getServiceElementTemplateField($this->_id);
		foreach ($TemplateField as $field)
		{	
			$this->_field_list[$field['id']] = new Portal_Itop_Service_ServiceElementTemplateField( $field['id'],
																									 $field['template_id'],
																									 $field['friendlyname'],
																									 $field['template_name'],
																									 $field['template_id_friendlyname'],
																									 $field['code'],
																									 $field['label'],
																									 $field['mandatory'], 
																									 $field['input_type'], 
																									 $field['values'],
																									 $field['initial_value'], 
																									 $field['format'],
																									 $field['order'] );
			
		}
		$this->_nbField = count($TemplateField );
	}
	
	//On mémorise le nom de l'élément de service auquel ce template est attaché
	public function setParentName($name){
		$this->_parent_name = $name;
	}
	
	public function getParentName(){
		return $this->_parent_name;
	}
	
	public function setParentDescription($description){
		$this->_parent_description = $description;
	}
	
	public function getParentDescription(){
		return $this->_parent_description;
	}
}