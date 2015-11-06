<?php
/*
 * Step 0 => start
* Step 1 => Database inputs
* Step 2 => Import Database and db.ini creation (background) and Webservice Inputs
* Step 3 => application.ini creation and check all.
*/

defined('__DIR__') || define('__DIR__', dirname(__FILE__));

if (isset($_GET['removeMe'])) {
	unlink('../index.php');
	//rename('../index.php', '../index.php_previous');
	rename('../index.php_next', '../index.php');

	include_once '../../library/Portal/File/System.php';
	Portal_File_System::rmdir(__DIR__);
	header('Location: ../index/installation-complete');
	die();
}


defined('APPLICATION_PATH')
|| define('APPLICATION_PATH', realpath(__DIR__ . '/../../application'));

include_once __DIR__ . '/class/Installation.php';
include_once __DIR__ . '/class/Check.php';




//$checklist = array();
$check = new Check();
$check->check();


$session = new Zend_Session_Namespace('Installation');
if (is_null($session->installation)) {
	//New Install, we initialize it
	$install = new Installation();
	$install->check($check);
	//$install = Installation::getInstance();
	//error_log('Initialization');
	
}
else if (!is_null($session->installation)){
	//Install in process, we go to the next step
	$install = $session->installation;
	$install->check($check);
	//error_log('____________________');
	//error_log($install->getPage());
		
}

//Zend_Debug::dump($install);
include './pages/_head.php';
//echo $install->getPage();
include './pages/'.$install->getPage();
//Zend_Debug::dump($check);
include './pages/_footer.php';

?>