<?php

include_once __DIR__ . '/InitState.php';
include_once __DIR__ . '/Check.php';


Class Installation {
	
	//private static $_instance = null;
	
	protected $currentState;
	
	//Db parameters
	public $DbHost ; //= 'localhost';
	public $DbName ;//= 'itop_portal_test';
	public $DbUser ;//= 'root';
	public $DbPwd = '';
	
	//Webservices parameters
	public $webservice_protocol;
	public $webservice_adress;
	public $webservice_username;
	public $webservice_password;
	
	public $test = '0';
	
	protected $_checkList = array();
	protected $_check; // Check Object
	
	
	public function __construct()
	{
		$this->setState(new InitState($this));
		$session = new Zend_Session_Namespace('Installation');
		$session->installation = $this;
		
	}
	
	/**
	 * Méthode qui crée l'unique instance de la classe
	 * si elle n'existe pas encore puis la retourne.
	 *
	 * @param void
	 * @return Singleton
	 */
	/*public static function getInstance() {
	
		if(is_null(self::$_instance)) {
			self::$_instance = new Installation();
			error_log('new Installation');
		}
	
		return self::$_instance;
	}*/
	
	public function setState($state){
		$this->currentState = $state;
	}
	
	public function getState() {
		return $this->currentState->getState();
	}
	
	public function getPage(){
		return $this->currentState->getPage();
	}
	
	public function next(){
		$this->currentState->next();
	}
	
	public function prev(){
		$this->currentState->prev();
	}
	
	public function checkParam() {
		$this->currentState->checkParam($this->_check);
	}
	
	public function genParamFile($options){
		return $this->currentState->genParamFile($this->_check,$options);
	} 
	
	
	// returns Array()
	public function getParamFileStatus($caller=null) {
		return $this->currentState->getParamFileStatus($this->_check,$caller);
	}

	public function check($check){
	
		$this->_check = $check;
		//We try to get some parameter from file, if exists
		/*$param = $check->getDbParameters('ajax');
		$this->DbHost = $param['database_host'];
		$this->DbName = $param['database_name'];
		$this->DbUser = $param['database_username'];
		$this->DbPwd = $param['database_password'];*/
	}
	
	public function checkCfg($step){
		$this->_check->checkCfg($step);
		
	}
	

	
	// Import SQL file to create table ?
	public function fullDbInstall(){
		return $this->_check->dbInstall();
	}
	

	
}