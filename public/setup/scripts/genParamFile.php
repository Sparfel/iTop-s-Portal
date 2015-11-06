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
//Usefull because Php Warning (like copy not possible or else) may disturb Ajax call.   
error_reporting(0);
$session = new Zend_Session_Namespace('Installation');
$install = $session->installation;
$result = $install->genParamFile($_POST);
//error_log(Zend_Debug::dump($result));
if ($result['result'] == 'ok') {
	$result = $install->getParamFileStatus('ajax');
}
//error_log(Zend_Debug::dump($result));
echo json_encode($result);
?>