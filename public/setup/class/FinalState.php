<?php
include_once __DIR__ . '/ConfigWebSrvState.php';

class FinalState implements InstallState {
	
	private static $_instance = null;
	
	protected $installation;
	protected $page = 'Final.php';
	
	public function __construct($installation){
		$this->installation = $installation;
	}
	
	public static function getInstance($installation) {
	
		if(is_null(self::$_instance)) {
			self::$_instance = new FinalState($installation);
		}
	
		return self::$_instance;
	}
	
	public function getPage(){
		return $this->page;
	}
	
	public function next($installation){
		
	}
	
	public function prev($installation){
		$installation->setState(new configWebSrvState($this->installation));
	}
	
	public function cancel(){
		null;
	}
	
	public function getState(){
		return $this;
	
	}
	
	public function checkState(){
		
	}	
	
	public function validState(){
	
	}
	
	public function checkParam($chec,$installation){
		
	}
	
	public function doTheJob($options){
	
		
			return 'OK';
	}
}