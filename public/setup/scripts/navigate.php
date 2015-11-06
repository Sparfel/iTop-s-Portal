<?php
defined('__DIR__') || define('__DIR__', dirname(__FILE__));

defined('APPLICATION_PATH')
|| define('APPLICATION_PATH', realpath(__DIR__ . '/../../../application'));

set_include_path(implode(PATH_SEPARATOR, array(
realpath(__DIR__ . '/../../../library/'),
get_include_path(),
)));

		
require_once 'Zend/Loader/Autoloader.php';
$autoloader = Zend_Loader_Autoloader::getInstance()
->setDefaultAutoloader(create_function('$class',
		"include str_replace('_', '/', \$class) . '.php';"
));
		
include_once __DIR__ . '/../class/Installation.php';

//Zend_Debug::dump(__DIR__);
$session = new Zend_Session_Namespace('Installation');
$install = $session->installation;

//$install = Installation::getInstance();

//print_r($install);

//$sie = $_POST['Oinstall'];
//$si = urldecode($sie);
//$install = unserialize($si);
echo $_POST['action'];

if (isset($_POST['action'])) {
	switch ($_POST['action']) {
	case 'next' :
		$install->next();
		echo '<hr>';
		break;
	case 'prev' :
		$install->prev();
		echo 'prev';
		break;
	default : echo 'default';
	}
}


			
	
