<?php 
$AppParams = $check->getApplicationParameters();
/*if (is_array($AppParams)) {*/
$webservice_protocol = $AppParams['webservice_protocol'];
$webservice_adress = $AppParams['webservice_adress'];
$webservice_username = $AppParams['webservice_username'];
$webservice_password = $AppParams['webservice_password'];
/*}
 else {
$webservice_protocol = 'https';
$webservice_adress = 'demo.combodo.com/simple';
$webservice_username = 'admin-fr';
$webservice_password = 'admin';
}*/

//Get the next and previous step :
if (isset($_GET['step']))
{
	$next_step = $_GET['step'] + 1;
	$prev_step = $_GET['step'] - 1;
}
else {$prev_step = 0;
}
?>

<style>
input.form_text {
	margin-bottom: 5px;
}

div.custom {
	background: url("./images/web-service.png") no-repeat !important;
	background-position: right 25px top 25px !important;
}
</style>

<section>
	<form method="post" id="webservice"
		action="index.php?step=<?php echo $next_step;?>">
		<div class="left custom">
			<h2>Database Parameters</h2>
			<p>Please fill the iTop's Webservices informations.</p>
			<input type="hidden" name="submit" value="2" />
			<table class="central text" width="100%" border="0" cellspacing="0"
				cellpadding="2" class="main_text">
				<tr>
					<td>Webservice Protocol</td>
					<td><input type="text" class="form_text" name="webservice_protocol"
						value='<?php echo $webservice_protocol; ?>' size="30"></td>
				</tr>
				<tr>
					<td>Webservice Adress</td>
					<td><input type="text" class="form_text" name="webservice_adress"
						size="30" value="<?php echo $webservice_adress; ?>"></td>
				</tr>
				<tr>
					<td>Webservice Username</td>
					<td><input type="text" class="form_text" name="webservice_username"
						size="30" value="<?php echo $webservice_username; ?>"></td>
				</tr>
				<tr>
					<td>Webservice Password</td>
					<td><input type="password" class="form_text"
						name="webservice_password" size="30"
						value="<?php echo $webservice_password; ?>"></td>
				</tr>
				<tr>
					<td colspan=2>&nbsp;</td>
				</tr>
			</table>
			<?php       
			/*$output =  system('./../../bin/test.bat');
			 //$output = shell_exec('ls -altr ./../../bin/');
			Zend_Debug::dump($output);
			$cmd = './../../bin/test.bat';
			$cmd = 'D:\Site Web\Portail iTop\Portail iTop\bin\test.bat';
			if (substr(php_uname(), 0, 7) == "Windows"){
				pclose(popen("start /B ". $cmd, "r"));
			}
			else {
				exec($cmd . " > /dev/null &");
			}*/
			?>

		</div>
		<div class="right">
			<h2>Checklist</h2>

			<ul class="checklist">
				<?php
				foreach ($check->getCheckList() as $checkItem) :

				if ($checkItem['code'] == -1) {
                        $spanClass = 'ui-icon ui-icon-red ui-icon-alert';
                        $liClass = 'red';
                    } else if ($checkItem['code'] == 0) {
                        $spanClass = 'ui-icon ui-icon-red ui-icon-notice';
                        $liClass = 'orange';
                    } else {
                        if ($checkItem['canBeBetter'] == 1) {
                            $liClass = 'orange';
                            $spanClass = 'ui-icon ui-icon-red ui-icon-notice';
                        } else {
                            $spanClass = 'ui-icon ui-icon-bluelight ui-icon-check';
                            $liClass = '';
                        }
                    }

                    if ($checkItem['alt'] != '') {
                        $liClass .= ' tipsyauto';
                    }
                    ?>
				<li class="<?php echo $liClass ?>"
				<?php echo ($checkItem['alt'] != '')?' title="' . htmlentities($checkItem['alt']) . '"':''; ?>>
					<span class="<?php echo $spanClass; ?>"></span> <?php echo $checkItem['text']; ?>
				</li>
				<?php
				endforeach;
				?>
			</ul>
		</div>

		<div class="clear"></div>

		<div class="middle">
			<h3>Create Webservice's configuration file.</h3>
			<p>Next step will test if the server is able to run the portal.</p>
			<table width="100%" border="0" cellspacing="0" cellpadding="2"
				class="main_text">
				<tr>
					<td colspan=2 align='left'><input type="button"
						class="ui-button ui-button-bg-white" name="btn_cancel"
						value="Back"
						onclick="window.location.href='index.php?step=<?php echo $prev_step;?>'">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="submit"
						class="ui-button ui-button-bg-white" name="btn_submit"
						value="Continue">
					</td>
				</tr>
			</table>
		</div>
	</form>

	<div class="bottom">
		<h2>Note</h2>
		<p>
			It's possible to use several iTop on the same Portal (two at the
			moment). We can switch it into the user profile. It can be usefull
			for testing. But here, we put the same Webservice on the two
			configurations. You can manually change it in the <strong>application.ini</strong>
			file in <em>application/configs</em> directory.
		</p>
		<div class="clear"></div>
	</div>