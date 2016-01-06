<?php

include_once __DIR__ . '/InstallState.php';
include_once __DIR__ . '/ConfigDBState.php';
include_once __DIR__ . '/FinalState.php';

class ConfigWebSrvState implements InstallState {
	
	private static $_instance = null;
	
	protected $installation;
	protected $page = 'ConfigWebSrv.php';
	
	public function __construct($installation){
		$this->installation = $installation;
	}
	
	public static function getInstance($installation) {
		if(is_null(self::$_instance)) {
			self::$_instance = new ConfigWebSrvState($installation);
		}
		return self::$_instance;
	}
	
	public function getPage(){
		return $this->page;
	}
	
	public function next($installation,$param){
		if ($this->doTheJob() == 'OK'){
			
			//if ($param =='test') {
				//$installation->setState(new ConfigDBState($this->installation));
			//}
			//else {
				$installation->setState(new FinalState($this->installation));
			//}
		}
	}
	
	public function prev($installation,$param){
			$installation->setState(new ConfigDBState($this->installation));
	}
	
	public function cancel(){
		null;
	}
	
	public function getState(){
		return $this;
	
	}
	
	public function checkParam($check,$installation) {
		$param = $check->getApplicationParameters('ajax');
		$installation->webservice_protocol = $param['webservice_protocol'];
		$installation->webservice_adress = $param['webservice_adress'];
		$installation->webservice_username = $param['webservice_username'];
		$installation->webservice_password = $param['webservice_password'];

	}
	
	public function genParamFile($check,$options){
		if ($check->genApplicationFile($options)) {
			$result = 'ok';
			$result_message = '<li class="tipsyauto" original-title="it\'s in <em>application/configs/</em> directory">
								<span class="ui-icon ui-icon-bluelight ui-icon-check"></span>
								The application.ini file was generated successfully.
								</li>';
		}
		else {
			$result = 'error';
			$result_message = '<li class="red tipsyauto" original-title="it should be in <em>application/configs/</em> directory">
								<span class="ui-icon ui-icon-red ui-icon-alert"></span>
								There was a probleme during the application.ini file generation.
								</li>';
		}
		$Aresult = array('result' => $result, 'result_message' => $result_message);
		return $Aresult;
	}
	
	public function getParamFileStatus($check,$caller){
		if ($caller == 'ajax') {
			$prefixDir = './../';
		}
		else {
			$prefixDir ='';
		}
		
		if (file_exists($prefixDir.$check->_config_db_file_path))
		{
			$result = 'ok';
			$result_message = '<li class="tipsyauto" original-title="it\'s in <em>application/configs/</em> directory">
								<span class="ui-icon ui-icon-bluelight ui-icon-check"></span>
								The application.ini file was generated successfully.
								</li>';
		}
		else {
			$result = 'error';
			$result_message = '<li class="red tipsyauto" original-title="it should be in <em>application/configs/</em> directory">
								<span class="ui-icon ui-icon-red ui-icon-alert"></span>
								There was a probleme during the application.ini file generation.
								</li>';
		}
		$result = array('result' => $result, 'result_message' =>$result_message);
		return $result;
	}
	
	
	
	
	public function validState(){
	
	}
	
	protected function doTheJob(){
	
		return 'OK';
	
	}
}