<?php
include_once __DIR__ . '/InstallState.php';
include_once __DIR__ . '/ConfigWebSrvState.php';


class ConfigDBState implements InstallState {
	
	private static $_instance = null;
	
	protected $installation;
	protected $page = 'ConfigDB.php';
	
	
	public function __construct($installation){
		$this->installation = $installation;
	}
	
	public static function getInstance($installation) {
		if(is_null(self::$_instance)) {
			self::$_instance = new ConfigDBState($installation);
		}
		return self::$_instance;
	}
	
	public function getPage(){
		return $this->page;
	}
	
	public function next(){
		if ($this->doTheJob(null) == 'OK'){
			$this->installation->setState(new ConfigWebSrvState($this->installation));
		}
	}
	
	public function prev(){
		$this->installation->setState(new InitState($this->installation));
	}
	
	public function cancel(){
		null;
	}
	
	public function getState(){
		return $this;
	}
		
	public function checkParam($check) {
		$param = $check->getDbParameters('ajax');
		$this->installation->DbHost = $param['database_host'];
		$this->installation->DbName = $param['database_name'];
		$this->installation->DbUser = $param['database_username'];
		$this->installation->DbPwd = $param['database_password'];
	}
	
	public function genParamFile($check,$options){
		//return $check->genDbFile($options);
		if ($check->genDbFile($options)) {
			$result = 'ok';
			$result_message = '<li class="tipsyauto" original-title="it\'s in <em>application/configs/</em> directory">
								<span class="ui-icon ui-icon-bluelight ui-icon-check"></span>
								The db.ini file was generated successfully.
								</li>';
		}
		else {
			$result = 'error';
			$result_message = '<li class="red tipsyauto" original-title="it should be in <em>application/configs/</em> directory">
								<span class="ui-icon ui-icon-red ui-icon-alert"></span>
								There was a probleme during the db.ini file generation.
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
								The db.ini file was generated successfully.
								</li>';
			
		}
		else {
			$result = 'error';
			$result_message = '<li class="red tipsyauto" original-title="it should be in <em>application/configs/</em> directory">
								<span class="ui-icon ui-icon-red ui-icon-alert"></span>
								There was a probleme during the db.ini file generation.
								</li>';
			
		}
		$Aresult = array('result' => $result, 'result_message' => $result_message);
		return $Aresult;
	}
	
	
	
	
	
	public function validState(){
	
	}
	
	public function doTheJob($options){
		/*if (strlen($options['database_password']) == 0)
		{return 'KO';}
		else */{			
			return 'OK';
		}
	}
}