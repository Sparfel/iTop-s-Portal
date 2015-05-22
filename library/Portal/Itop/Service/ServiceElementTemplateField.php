<?php
class Portal_Itop_Service_ServiceElementTemplateField {

	public $_id;
	public $_template_id;
	public $_friendlyname;
	public $_template_name;
	public $_template_id_friendlyname;
	public $_code; // on enleve les caracteres spéciaux
	public $_itop_code; // code tel que sasi dans iTop
	public $_label;
	public $_mandatory;
	public $_input_type;
	public $_values;
	public $_initial_value;
	public $_format;
	public $_order;
	
	
	public function __construct($id, $template_id,  $friendlyname,$template_name, $template_id_friendlyname, $code, $label, 
								$mandatory, $input_type, $values,$initial_value, $format,$order) {
		$this->_id = $id;
		$this->_template_id = $template_id;
		$this->_friendlyname = $friendlyname;
		$this->_template_name = $template_name;
		$this->_template_id_friendlyname = $template_id_friendlyname;
		$this->_code = preg_replace("#[^a-zA-Z]#", "",$code);
		$this->_itop_code = $code;
		$this->_label = $label;
		$this->_mandatory = $mandatory;
		$this->_input_type = $input_type;
		$this->_values = $values;
		$this->_initial_value = $initial_value;
		$this->_format = $format;
		$this->_order = $order;
	}
	
	
	public function getId() {
		return $this->_id;
	}
	
	public function getCode() {
		return  $this->_code;
	}
	
	public function getiTopCode() {
		return  $this->_itop_code;
	}
	
	public function getFriendlyname() {
		return $this->_friendlyname;
	}
	
	public function getOrder() {
		return $this->_order;
	}
	
	public function getInputType(){
		return $this->_input_type;
	}
	
	public function getLabel(){
		return $this->_label;
	}
	
	public function getMandatory() {
		return $this->_mandatory;
	}
	
	public function getValues() {
		return $this->_values;
	}
	
	public function getInitialValue() {
		return $this->_initial_value;
	}
}