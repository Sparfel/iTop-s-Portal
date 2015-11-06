<?php 
//include_once __DIR__ . '/Installation.php';
include_once __DIR__ . '/InstallState.php';
include_once __DIR__ . '/ConfigDBState.php';

class InitState implements InstallState {
	
	private static $_instance = null;
	
	protected $installation;
	protected $page = 'Init.php';
	
	public $test;
	
	public function __construct($installation){
		$this->installation = $installation;
	
	}
	
	
	/**
	 * Méthode qui crée l'unique instance de la classe
	 * si elle n'existe pas encore puis la retourne.
	 *
	 * @param void
	 * @return Singleton
	 */
	public static function getInstance($installation) {
		if(is_null(self::$_instance)) {
			self::$_instance = new InitState($installation);
		}
		return self::$_instance;
	}
	
	public function getPage(){
		return $this->page;
	}
	
	public function next(){
		if ($this->doTheJob() == 'OK'){
			$this->installation->setState(new ConfigDBState($this->installation));
		}
	}
	
	public function prev(){
		null;		
	}
	
	public function cancel(){
		null;
	}
	
	public function getState(){
		return $this;
	}
	
	public function checkState(){
		return FALSE;
	}
	
	public function checkParam($check){
	}
	
	public function validState(){
	}
	
	protected function doTheJob(){
		//nothing to do
		return 'OK';
	}
	
	
}

