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

$checklist = array();

include_once __DIR__ . '/Check.php';

if (!(isset($_GET['step'])) OR is_null($_GET['step'])){
	$step = 0;
}
else {$step= $_GET['step'];
}
$check = new Check();
$check->check();

//Step by Step
switch ($step) {
	case 0 :
		break;
	case 1 :
		// we check here the files and folders permission (like we do with ./zf.sh check install)
		// we may do that at another place ... a trace somewhere what we do or not do !
		$check->checkPerm();
		/*foreach ($check->getPerm() as $info){
			echo ($info);
		}*/
		break;
	case 2 :
		//Step 2 => we configure Webservice and login/pwd to access.
		// delete Webservice's configuration file, it will be created later, at the next step
		//if (!(file_exists($check->_config_db_file_path)))
		//Configuration Database Files and Database import are made during this step with parameters from previous step.
		$options = array();
		$options = $check->getDbParameters();
		$options['database_host'] = isset($_POST['database_host']) ? strip_tags($_POST['database_host']) : $options['database_host'];
		$options['database_name'] = isset($_POST['database_name']) ? strip_tags($_POST['database_name']) : $options['database_name'];
		$options['database_username'] = isset($_POST['database_username']) ? strip_tags($_POST['database_username']) : $options['database_username'];
		$options['database_password'] = isset($_POST['database_password']) ? strip_tags($_POST['database_password']) : $options['database_password'];
		$check->genDbFile($options);
		/* Database creation and filling */
		//$check->db_install();
		//$check->db_import($options);
		break;

	case 3 :
		// Configuration Application Files (Webservices) creation with parameters from previous step.
		$options = array();
		$options = $check->getApplicationParameters();
		$options['webservice_protocol'] = isset($_POST['webservice_protocol']) ? strip_tags($_POST['webservice_protocol']) : $options['webservice_protocol'];
		$options['webservice_adress'] = isset($_POST['webservice_adress']) ? strip_tags($_POST['webservice_adress']) : $options['webservice_adress'];
		$options['webservice_username'] = isset($_POST['webservice_username']) ? strip_tags($_POST['webservice_username']) : $options['webservice_username'];
		$options['webservice_password'] = isset($_POST['webservice_password']) ? strip_tags($_POST['webservice_password']) : $options['webservice_password'];
		$check->genApplicationFile($options);
		break;
	default :
		break;

}

$check->checkCfg($step);


/*
TODO:
TO check :
- version of php, apache, mysql
- extension
- database installed
- droits d'écriture/lecture

*/



include '_head.php';

include './step/_step'.$step.'.php';

include '_footer.php';
?>