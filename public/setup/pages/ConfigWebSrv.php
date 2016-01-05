<?php
$session = new Zend_Session_Namespace('Installation');
$install = $session->installation;
$install->checkParam();
//Zend_Debug::dump($install);
if (!(is_null($install))){
	$webservice_protocol = $install->webservice_protocol;
	$webservice_adress = $install->webservice_adress;
	$webservice_username = $install->webservice_username;
	$webservice_password = $install->webservice_password;
}

// DB parameter were completed, 
$install->checkCfg(1);
//we check if we have already a db.ini file. The database may exist and is full
/*
$Ares = $install->getParamFileStatus();
if ($Ares['result'] == 'ok'){ // file exists
	//we check the database here
	$install->checkCfg(2);
}*/

?>

<style>
input.form_text {
	margin-bottom: 5px;
}

div.custom {
	background: url("./images/web-service.png") no-repeat !important;
	background-position: right 25px top 25px !important;
}
#display_import { /*height:70px;*/
	
}

#display_db_file {
	display: none;
	height: 20px;
}

.middle p.error {
	color: red;
	!
	important
}

</style>

<script type="text/javascript">
$(document).ready(function() {


	$("#btn_check").click(function (event) {
		next();
	});
	
	$("#btn_submit").click(function (event) {
		 DoTheJob();
	});

	
});

	function DoTheJob(){

		//Check if field for database connection are set 
		var webservice_protocol = document.forms["websrv"].elements["webservice_protocol"];
		var webservice_adress = document.forms["websrv"].elements["webservice_adress"];
		var webservice_username = document.forms["websrv"].elements["webservice_username"];
		var webservice_password = document.forms["websrv"].elements["webservice_password"];
		if(webservice_protocol.value == '' ) {   
			  alert('Please give a valid protocole (http or https');   
			  webservice_protocol.focus();
			  return false; 
			 }  
		if(webservice_adress.value == '' ) {   
			  alert('Please give a valid adress');   
			  webservice_adress.focus(); 
			  return false; 
			 }  
		if(webservice_username.value == '' ) {   
			  alert('Please give a valid username');   
			  webservice_username.focus(); 
			  return false; 
			 } 
		if(webservice_password.value == '' ) {   
			  alert('Please give a valid password');   
			  webservice_password.focus(); 
			  return false; 
			 } 
		
		$("#btn_submit").hide();
		
		//First : application.ini file generation to store the Webservices credentials
		$.ajax({ 
			xhr: function() {
	            var xhr = new window.XMLHttpRequest();
	            xhr.addEventListener("progress", function(e){
	                console.log(e.currentTarget.response);
	                JSON.parse(e.currentTarget.response, function (key, value) {
						if (key == 'result_message') {
							 $('#display_db_file').fadeIn(1000);     
	                		$("#display_db_file").html(value);
						}
					});
	            });
	        return xhr;   
			},                                                        
	        type: "POST",
	        url: "./scripts/genParamFile.php",
	        data: {
				'webservice_protocol' : webservice_protocol.value,
				'webservice_adress' : webservice_adress.value,
				'webservice_username' : webservice_username.value,
				'webservice_password' : webservice_password.value
				},
			//dataType: "json",
			success: function(data){
					$('div#display_import').fadeIn(1000);           // Fade in on the result div
			        JSON.parse(data, function (key, value) {
			        	//alert(key + ' -> '+value);
			            if ((key == 'result') && (value == 'true')) {}
			        });
			},
	        complete : function(resultat, statut){
	        	 	testWebSrv();
		    }
	    });
		}; //);
	
	
	function testWebSrv(){
		$('div#display_import').hide();
		$.ajax({ 
    		xhr: function() {
    			var xhr = new window.XMLHttpRequest();
                xhr.addEventListener("progress", function(e){
                	if (!(e.currentTarget.response.indexOf("ERROR")> 0)) { 
                    	$("#help_text").html(e.currentTarget.response);
                	}
                });
            return xhr;   
    		},                                                        
            type: "POST",
            url: "./scripts/testWebSrv.php",
            data: { 'test' : 'true'}, // why not ?
            success: function(code_html,data){
               if (code_html.indexOf("ERROR")> 0) { 
                	var myarr = code_html.split("ERROR");
                	$('div#display_import').empty();
		            //$("div#display_import").append('<p class="error"><strong>Problem during the import.</strong></p>'); 
		            JSON.parse(myarr[1], function (key, value) {
						if (key == 'msg') {
							//alert('key '+key+' -> value '+value);
							$("div#display_import").append('<p>'+value+'</p>');
							$('div#display_import').fadeIn(1000);
							retry(); 
						}
						if (key == 'next_step'){
							if (value == true) {
		            	 		next();			
    						}
		            		else {
		            			$("#help_text").empty();
					            $("#help_text").append('Connexion with iTop\'s Webservices has a problem, change connexion\'s parameters and try again.');
					            $("#btn_submit").hide();
					            $("#help_text").show(1000);
		            		} 	
						}
					});
                }
                else 
                	{
					$('div#display_import').fadeIn(1000);
	            	$('div#display_import').empty();
		            $("div#display_import").append('<li class="tipsyauto" original-title="The iTop\'s webservices were tested.">'
				            + '<span class="ui-icon ui-icon-bluelight ui-icon-check"></span>'
		                    + 'The Webservices tests is successfully.'
		                	+ '</li>');        // display the result of the div
		            
               		}
               },
        	
             error : function(resultat, statut, erreur){
	             },
             complete : function(resultat, statut){
	        	recheck(2);
				
			 }
            });
		}
	
	function recheck($step){
		$.ajax({ 
	        type: "POST",
	        url: "./scripts/chkCfg.php",
	        data: {
				'step' : $step 
					},
			//dataType: "json",
	        success: function(data){
	        	$("#check_list").empty();
	        	$("#check_list").hide(0);
	        	$("#check_list").delay(500).append('<ul id="check_list" class="checklist">'+data+'</ul>');
	        	$("#check_list").show(500);
	        	}
		});
	}
		
	function next() {
		$("#help_text").hide(0);
		$("#help_text").delay(500).empty();
		$("#help_text").append('Next step will configure the Webservice\'s configuration file.');
	    $("#help_text").show(500);
	    $("#step_button").empty();
	    $("#step_button").delay(500).append('<input type="button" class="ui-button ui-button-bg-white" name="btn_cancel" value="Back" onclick="navigate(\'prev\')">');
		//$("#step_button").delay(500).append('<input type="submit" class="ui-button ui-button-bg-white" name="btn_next" id="btn_next"  value="Next" onclick="navigate(\'next\',\'test\')">');
		$("#step_button").delay(500).append('<input type="button" class="ui-button ui-button-bg-white" name="btn_next" id="btn_next"  value="Next" onclick="navigate(\'next\')">');
	    $("#step_button").show(500);
	}

	function retry() {
		$("#btn_retry").remove();
		$("#step_button").delay(500).append('<input type="button" class="ui-button ui-button-bg-white" name="btn_retry" id="btn_retry"  value="Retry" onclick="DoTheJob()">');
		$("#btn_submit").delay(500).show();
		$("#step_button").show(500);
	   	document.forms["websrv"].elements["webservice_adress"].focus();
	}

	</script>

<section>
	<form method="post" id="webservice" name="websrv"
		action="">
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
		</div>
		<div class="right">
			<h2>Checklist</h2>

			<ul id="check_list" class="checklist">
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
			<div class="left" style="width: 385px;">
				<h3>Create Webservice's configuration file.</h3>
				<div id="divhelp_text" style="height: 50px;">
					<p id="help_text">
						Next step will test if the server is able to run the portal.</p>
				</div>
				<table width="100%" border="0" cellspacing="0" cellpadding="2"
					class="main_text">
					<tr>
						
						<td colspan=2 align='left' id="step_button">
						<input type="button" class="ui-button ui-button-bg-white" name="btn_cancel"	value="Back" onclick="navigate('prev')">
						<!--<input type="button" class="ui-button ui-button-text-only ui-button-bg-white" value="Cancel" name="btn_cancel" id="btn_cancel" onclick="cancel();">-->
						<input type="button" class="ui-button ui-button-bg-white" name="btn_submit" id="btn_submit" value="Continue">
				
							<!-- <input type="button" class="ui-button ui-button-bg-white" name="btn_check" id="btn_check"  value="test">-->
							
						</td>
					</tr>
				</table>
			</div>
			<div class="right" style="width: 220px;">

				<ul class="checklist">
					<div id="display_db_file"></div>
					<div id="display_import"></div>
				</ul>
			</div>
			<div class="clear"></div>		
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