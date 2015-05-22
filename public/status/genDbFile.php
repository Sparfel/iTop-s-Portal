<?php 
defined('__DIR__') || define('__DIR__', dirname(__FILE__));

defined('APPLICATION_PATH')
|| define('APPLICATION_PATH', realpath(__DIR__ . '/../../application'));

$checklist = array();

include_once __DIR__ . '/Check.php';

$check = new Check();
$check->check();

/* Db.ini file generation*/
$options = $check->getDbParameters();
$options['database_host'] = isset($_POST['database_host']) ? strip_tags($_POST['database_host']) : $options['database_host'];
$options['database_name'] = isset($_POST['database_name']) ? strip_tags($_POST['database_name']) : $options['database_name'];
$options['database_username'] = isset($_POST['database_username']) ? strip_tags($_POST['database_username']) : $options['database_username'];
$options['database_password'] = isset($_POST['database_password']) ? strip_tags($_POST['database_password']) : $options['database_password'];
$check->genDbFile($options);

if (file_exists($check->_config_db_file_path)) {
	//$db_generation = '<p>The db.ini file was generated successfully.</p>';
	$db_generation = '<li class="tipsyauto" original-title="it\'s in <em>application/configs/</em> directory">
			<span class="ui-icon ui-icon-bluelight ui-icon-check"></span>
			The db.ini file was generated successfully.
			</li>';
	$next_step = 'true';
}
else {
	//$db_generation = '<p>There was a probleme during the db.ini file generation.</p>';
	$db_generation = '<li class="red tipsyauto" original-title="it should be in <em>application/configs/</em> directory">
			<span class="ui-icon ui-icon-red ui-icon-alert"></span>
			There was a probleme during the db.ini file generation.
			</li>';
	$next_step = 'false';
}

$result = array('next_step' => $next_step, 'msg' =>$db_generation  );
echo json_encode($result);
?>