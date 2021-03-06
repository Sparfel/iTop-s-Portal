<?php

if (isset($_SERVER['REDIRECT_URL'])) {
	$url = $_SERVER['REDIRECT_URL'];
	
} else if (isset($_SERVER['REQUEST_URI'])) {
	$url = $_SERVER['REQUEST_URI'];
} else {
	$url = null;
}

//For IIS, we have to do this because we have the step parameter into $url
$Aurl = explode('?',$url);
if (isset($_GET['step']) AND $_GET['step'] == -1 ) {
	if (null !== $url && $Aurl[0] == '/test_redirect/') {
		echo 'Mod_Rewrite works!';
	} else {
		echo 'Mod_Rewrite does not works';
	}
	die();
}

// Define application environment
defined('APPLICATION_ENV')
|| define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

if (APPLICATION_ENV == 'testing') {
	//We allow to run test unit without deleting the status page.
	require_once 'index.php_next';
} else {
	header('Location: ' . substr($_SERVER['PHP_SELF'], 0, -strlen('index.php')) . 'setup/');
	die();
}
