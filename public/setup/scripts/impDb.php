<?php 
defined('__DIR__') || define('__DIR__', dirname(__FILE__));

defined('APPLICATION_PATH')
|| define('APPLICATION_PATH', realpath(__DIR__ . '/../../../application'));

$checklist = array();

include_once __DIR__ . '/../class/Check.php';

$check = new Check();
$check->check();
$check->checkCfg(1);

//http_response_code(404);
//echo '<p>Database import in progress ...';
echo '<div id="progress"style="width:300px;border:1px solid #ccc;"></div>';
echo '<div id="informationdb" style="width"></div>';
include_once __DIR__ . '/../../../library/Centurion/Config/Directory.php';
include_once __DIR__ . '/../../../library/Centurion/Iterator/Directory.php';
include_once __DIR__ . '/../../../library/Zend/Config/Ini.php';

//$config = Centurion_Config_Directory::loadConfig(__DIR__ . '/../../../application/configs', getenv('APPLICATION_ENV'));
$config = Centurion_Config_Directory::loadConfig(__DIR__ . '/../../../application/configs', 'production');
include_once __DIR__ . '/../../../library/Zend/Application/Resource/Db.php';
include_once __DIR__ . '/../../../library/Zend/Db.php';


try {
	$dbRessource = new Zend_Application_Resource_Db();
	$dbRessource->setParams($config['resources']['db']['params']);
	$dbRessource->setAdapter($config['resources']['db']['adapter']);
	$db = $dbRessource->getDbAdapter();
	$db->beginTransaction();
}
catch (Exception $e){
	$msg = '<li class="red tipsyauto" title="'.$e->getMessage().'">
			<span class="ui-icon ui-icon-red ui-icon-alert"></span>
			The import encountered some problems : <br>
			<em style="font-size:70%;color:black;">'.$e->getMessage().'</em>
					</li>';
	$next_step = false;
	$res = array('msg' =>$msg,'next_step' => $next_step);
	echo 'ERROR'.json_encode($res);
	exit;
}


$success = true;
//Query for import
$sql = file_get_contents($check->_sql_file);
$sql = explode(";\n", $sql);
$total = count($sql);
$i = 0;

if ($_POST['drop'] == 'true') {
	//If asked, query for drop all tables befor to reimport them
	$drop = 'SELECT concat("DROP TABLE IF EXISTS ", table_name, ";") FROM information_schema.tables WHERE table_schema = "'.$config['resources']['db']['params']['dbname'].'";';
	try {
		$result = $db->query($drop);
			
		foreach ($result as $qdrop){
			$total += count($qdrop);
			foreach ($qdrop as $query){
				$db->query("SET foreign_key_checks=0;");
				$res = $db->query($query);
				$db->query("SET foreign_key_checks=1;");
				//error_log($query);
				$percent = intval($i/$total * 100)."%";
				echo '<script language="javascript">
						document.getElementById("progress").innerHTML="<div style=\"width:'.$percent.';background:url(\'./images/orange-progress.gif\') repeat; height:10px;\">&nbsp;</div>";
			    	document.getElementById("informationdb").innerHTML="<p>Database import in progress : '.$percent.' completed.</p>";
			    			</script>';
				flush();
				ob_flush();
				$i++;
			}
				
		}
	}
	catch (Exception $e) {
		//throw $e;
		error_log($e->getCode().' - '.$e->getMessage());
		$success= false;
	}
}

//error_log ($_POST['drop'].' ou '.$check->canDropTable().' ou '.$check->dbInstall());
//if ($check->dbInstall()) {error_log('install Tables !');}
//	else {error_log('dbInstall return false :(');}
//We import the Database only if necessary, to not erase some existing production database ...
if ($_POST['drop'] == 'true' 
		|| ($check->canDropTable())
		|| ($_POST['installSQL']== 'true'  )
		) {
	
	foreach($sql as $query){
		try {
			$result = $db->query($query);
			$percent = intval($i/$total * 100)."%";
			echo '<script language="javascript">
					document.getElementById("progress").innerHTML="<div style=\"width:'.$percent.';background:url(\'./images/orange-progress.gif\') repeat; height:10px;\">&nbsp;</div>";
		    	document.getElementById("informationdb").innerHTML="<p>Database import in progress : '.$percent.' completed.</p>";
		    			</script>';
			//error_log($query);
			flush();
			ob_flush();
			$i++;
				
			//sleep(1);
		}
		catch (Exception $e) {
			//throw $e;
			error_log($e->getCode().' - '.$e->getMessage());
				
			//http_response_code(403);
			$success= false;
			$next_step = false;
			//break;
		}
	}
}
else {
	$success= false;
	$msg = '<li class="orange tipsyauto" title="">
			<span class="ui-icon ui-icon-red ui-icon-notice"></span>
			The import was not be done. Tables already exist.<br>
			</li>';
	$next_step = true;
}


//Finalize to 100% and show the result
if ($success) {
	$percent = '100%';
	echo '<script language="javascript">
			document.getElementById("progress").innerHTML="<div style=\"width:'.$percent.';background-color:#ddd; \">&nbsp;</div>";
					document.getElementById("informationdb").innerHTML="<p>'.$percent.' completed.</p>";
							document.getElementById("display_import").style = "hidden";
							</script></p>';
}
else
{
	if (!(isset($msg))) {
		$msg = '<li class="red tipsyauto" title="'.$e->getMessage().'">
				<span class="ui-icon ui-icon-red ui-icon-alert"></span>
				The import encountered some problems : <br>
				<em style="font-size:70%;color:black;">'.$e->getMessage().'</em>
						</li>';
		$next_step = false;
	}
	$res = array('msg' =>$msg,'next_step' => $next_step);
	echo 'ERROR'.json_encode($res);

}


?>