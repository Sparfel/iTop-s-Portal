<?php 
defined('__DIR__') || define('__DIR__', dirname(__FILE__));

defined('APPLICATION_PATH')
|| define('APPLICATION_PATH', realpath(__DIR__ . '/../../../application'));

$checklist = array();

include_once __DIR__ . '/../class/Check.php';

$check = new Check();
$check->check();
//$check->checkCfg(2);

echo '<div id="progress"style="width:300px;border:1px solid #ccc;"></div>';
echo '<div id="informationdb" style="width"></div>';
include_once __DIR__ . '/../../../library/Centurion/Config/Directory.php';
include_once __DIR__ . '/../../../library/Centurion/Iterator/Directory.php';
include_once __DIR__ . '/../../../library/Zend/Config/Ini.php';

//$config = Centurion_Config_Directory::loadConfig(__DIR__ . '/../../../application/configs', getenv('APPLICATION_ENV'));
$config = Centurion_Config_Directory::loadConfig(__DIR__ . '/../../../application/configs', 'production');
include_once __DIR__ . '/../../../library/Zend/Application/Resource/Db.php';
include_once __DIR__ . '/../../../library/Zend/Db.php';


$AWebSrvParam = Array();
for ($noItop = 1; $noItop <=2; $noItop++) {
	//error_log('iTop n°'.$noItop);
	$AWebSrvParam[$noItop]['protocol'] =$config['itop'.$noItop]['url']['protocol'];
	$AWebSrvParam[$noItop]['adress'] = $config['itop'.$noItop]['url']['adress'];
	$AWebSrvParam[$noItop]['username'] = $config['itop'.$noItop]['webservice']['user'];
	$AWebSrvParam[$noItop]['password'] = $config['itop'.$noItop]['webservice']['pwd'];
	$AWebSrvParam[$noItop]['url'] = $AWebSrvParam[$noItop]['protocol'].'://'.$AWebSrvParam[$noItop]['adress'].'/webservices/rest.php?version=1.0';
}

$success = true;
$total = count($AWebSrvParam);
$i = 0;

foreach($AWebSrvParam as $webSrv) {
		$aData = array('operation' => 'list_operations');
		$aPostData = array(
				'auth_user' => $webSrv['username'],
				'auth_pwd' => $webSrv['password'],
				'json_data' => json_encode($aData),
		);
		try	{
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($aPostData));
			curl_setopt($curl, CURLOPT_URL, $webSrv['url']);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_VERBOSE, true);
			$percent = intval($i/$total * 100)."%";
			$sResult = curl_exec($curl);
			$aResult = @json_decode($sResult, true /* bAssoc */);
			if ($aResult == null) { 
				$Ares[$i] = 'error';
				$success = false;
			}
			else {
				if ($aResult['code'] == 0) {
					$Ares[$i] = 'ok';
					$success = false;
				}
				else {
					$Ares[$i] = 'error';
					$success = false;
				}
			}
			echo '<script language="javascript">
				document.getElementById("progress").innerHTML="<div style=\"width:'.$percent.';background:url(\'./images/orange-progress.gif\') repeat; height:10px;\">&nbsp;</div>";
	    		document.getElementById("informationdb").innerHTML="<p>Database import in progress : '.$percent.' completed.</p>";
	    			</script>';
			$i++;
			}
		catch (Exception $e) {
			//throw $e;
			error_log($e->getCode().' - '.$e->getMessage());
			$Ares[$i] = 'error';
			$success= false;
			$next_step = false;
		//break;
		}
	}

//Finalize to 100% and show the result
if ($success) {
	$percent = '100%';
	echo '<script language="javascript">
			document.getElementById("progress").innerHTML="<div style=\"width:'.$percent.';background-color:#ddd; \">&nbsp;</div>";
					document.getElementById("informationdb").innerHTML="<p>'.$percent.' completed.</p>";
							document.getElementById("display_import").style = "hidden";
							</script></p>';
	$next_step = true;
}
else //One or more Webservice are not OK
{
	if (!(isset($msg))) {
		$msg ='';
		$no = 1;
		$webSrvPb = 0;
		foreach($Ares as $res) {
			if ($res == 'ok') {
				$msg .= '<li class="tipsyauto" title="WebService n°'.$no.'">
				<span class="ui-icon ui-icon-bluelight ui-icon-check"></span>
				The Webservice '.$no.' is OK </li>';
			}
			else {
				$msg .= '<li class="red tipsyauto" title="WebService n°'.$no.'">
				<span class="ui-icon ui-icon-red ui-icon-alert"></span>
				The Webservice '.$no.' is not OK </li>';
				$webSrvPb++;
			}
			$no++;
		}
		if ($webSrvPb == count($Ares)) { $next_step = false;}
		else {$next_step = true;} // if only one Webservice is Ok, we allow to get further  
	}
	$res = array('msg' =>$msg,'next_step' => $next_step);
	echo 'ERROR'.json_encode($res);

}


?>