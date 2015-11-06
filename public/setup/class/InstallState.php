<?php
//include_once __DIR__ . '/Installation.php';

interface InstallState {
	
	public function __construct($installation);
	public function next();
	public function prev();
	public function cancel();
	public function getState();
	public function checkParam($check);
	public function validState();
	
	
	
	//return the page to show
	public function getPage();
	
	

	
	
}