<?php
//include_once __DIR__ . '/Installation.php';

interface InstallState {
	
	public function __construct($installation);
	public function next($installation);
	public function prev($installation);
	public function cancel();
	public function getState();
	public function checkParam($check,$installation);
	public function validState();
	
	
	
	//return the page to show
	public function getPage();
	
	

	
	
}