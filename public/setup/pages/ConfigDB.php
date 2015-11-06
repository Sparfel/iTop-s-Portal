<?php
$session = new Zend_Session_Namespace('Installation');
$install = $session->installation;
$install->checkParam();
//Zend_Debug::dump($install);
if (!(is_null($install))){
	$database_host = $install->DbHost;
	$database_name = $install->DbName;
	$database_username = $install->DbUser;
	$database_password = $install->DbPwd;
}

//we check if we have already a db.ini file. The database may exist and is full
// ICI, on récupere desormais un JSON et non un boolean => recuperer next_step pour savoir si on check la connexio ici !!!*
$Ares = $install->getParamFileStatus();
if ($Ares['result'] == 'ok'){ // file exists
	//we check the database here
	$install->checkCfg(1);
}

?>


<script type="text/javascript">
$(document).ready(function() {	
		$("#btn_submit").click(function (event) {
			DoTheJob();
			});
			
		$("#btn_check").click(function (event) {
		recheck(1);
		});
	});



	function DoTheJob(){
		//Check if field for database connection are set 
			//alert('OK, on est ici ! ');
			var database_host = document.forms["dbform"].elements["database_host"];
			var database_name = document.forms["dbform"].elements["database_name"];
			var database_username = document.forms["dbform"].elements["database_username"];
			var database_password = document.forms["dbform"].elements["database_password"];
			
			if (document.getElementById("dropchk")) {var drop_tables = document.forms["dbform"].elements["drop"];}
			else {var drop_tables = false;}
			//alert('database_host : '+database_host);
			if(database_host.value == '' ) {   
				  alert('Please give a valid server adress');   
				  database_host.focus(); //met le curseur dans le champ demandé   
				  return false; //enpèche l'envoi du formulaire   
				 }  
			if(database_name.value == '' ) {   
				  alert('Please give a valid database name');   
				  database_name.focus(); //met le curseur dans le champ demandé   
				  return false; //enpèche l'envoi du formulaire   
				 }  
			if(database_username.value == '' ) {   
				  alert('Please give a valid database username');   
				  database_username.focus(); //met le curseur dans le champ demandé   
				  return false; //enpèche l'envoi du formulaire   
				 } 
			
			$("#btn_submit").hide();
			
			
			
			//First : db.ini file generation to store the database credentials
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
							//alert(key + ' -> '+value);
		                    });
	                });
	            return xhr;   
	    		},                                                        
	            type: "POST",
	            //url: "./scripts/genDbFile.php",
	            url: "./scripts/genParamFile.php",
	            data: {
					'database_host' : database_host.value,
					'database_name' : database_name.value,
					'database_username' : database_username.value,
					'database_password' : database_password.value
					},
				//dataType: "json",
					success: function(data){
			            $('div#display_import').fadeIn(1000);           // Fade in on the result div
			            JSON.parse(data, function (key, value) {
			            	if ((key == 'result') && (value == 'ok')) {
				            	if (drop_tables.checked) {dropTables = 'true';}
				            	else {dropTables = 'false';}
	                    		importDb(dropTables);
							}
						});
			        //document.forms["dbform"].submit();
		            }
		        });
	}
			
	function importDb(dropTables) {
		//alert('on passe à la suite !');
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
            url: "./scripts/impDb.php",
            data: { 'drop' : dropTables,
                	'installSQL' : <?php echo $install->fullDbInstall();?>},
            success: function(code_html,data){
               if (code_html.indexOf("ERROR")> 0) { 
                	var myarr = code_html.split("ERROR");
                	//alert(myarr[1]);
	            	$('div#display_import').empty();
		            //$("div#display_import").append('<p class="error"><strong>Problem during the import.</strong></p>'); 
		            JSON.parse(myarr[1], function (key, value) {
						if (key == 'msg') {
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
					            $("#help_text").append('Import Canceled.');
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
		            $("div#display_import").append('<li class="tipsyauto" original-title="Tables were imported in your database">'
				            + '<span class="ui-icon ui-icon-bluelight ui-icon-check"></span>'
		                    + 'The import is successfully.'
		                	+ '</li>');        // display the result of the div
		            next();
               		}
        		},
        	
             error : function(resultat, statut, erreur){
	             },
             complete : function(resultat, statut){
	            	 //console.log(resultat);
					//alert(statut);
				recheck(1)
			     }
            });
		}
	
	function next() {	
		$("#help_text").hide(0);
		$("#help_text").delay(500).empty();
		$("#help_text").append('Next step will configure the Webservice\'s configuration file.');
        $("#help_text").show(500);
	    //$("#step_button").hide(500);
        $("#step_button").empty();
        $("#step_button").delay(500).append('<input type="button" class="ui-button ui-button-bg-white" name="btn_cancel" value="Back" onclick="navigate(\'prev\')">');
		$("#step_button").delay(500).append('<input type="submit" class="ui-button ui-button-bg-white" name="btn_next" id="btn_next"  value="Next" onclick="navigate(\'next\')">');
        $("#step_button").show(500);
	}

	function retry() {
		//document.getElementById('btn_submit').value= "Retry";
		//("#step_button").empty();
		$("#btn_retry").remove();
		$("#step_button").delay(500).append('<input type="button" class="ui-button ui-button-bg-white" name="btn_next" id="btn_retry"  value="Retry" onclick="DoTheJob()">');
		//$("#btn_submit").delay(500).show();
		//$("#step_button").delay(500).append('<input type="button" class="ui-button ui-button-bg-white" name="btn_reset" id="btn_reset"  value="Reset">');
	    $("#step_button").show(500);
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
            	//$("#check_list").replaceWith('<ul id="check_list" class="checklist">'+data+'</ul>');
            	}
		});
	}
	
	

	function navigate($value){
		window.location.href='';
		$.ajax({
            url: './scripts/navigate.php',
            type      : 'post',
			  dataType : 'html',
            data: { 'action' :  $value },
            success: function(data) {
          	  //$('.bottom').append('<PRE>'+data+'</PRE>')
            }
           });
	} 

	</script>


<style>
input.form_text {
	margin-bottom: 5px;
}

div.custom {
	background: url("./images/database.png") no-repeat !important;
	background-position: right 5px bottom 25px !important;
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
<section>
	<form method="post" id="database" name="dbform"
		action="#">
		<div class="left custom">
			<h2>Database Creation</h2>
			<p>First if you don't have a database, you have to create it. It can
				be done with PhpAdmin or in line command like this :
			
			
			<pre>mysql -u root -p
mysql>CREATE DATABASE my_database_name;
</pre>
			</p>
			<h2>Database Connection.</h2>
			<p>Please fill the informations for the database connection.</p>
			<!-- <input type="hidden" name="submit" value="2" /> -->
			
			<table class="central text" width="100%" border="0" cellspacing="0"
				cellpadding="2" class="main_text">
				<tr>
					<td>Database Host :</td>
					<td><input type="text" class="form_text" name="database_host" id="database_host"
						value="<?php echo $database_host; ?>" size="30"  data-validate="required" ></td>
				</tr>
				<tr>
					<td>Database Name :</td>
					<td><input type="text" class="form_text" name="database_name"
						size="30" value="<?php echo $database_name; ?>"  data-validate="required"></td>
				</tr>
				<tr>
					<td>Database Username :</td>
					<td><input type="text" class="form_text" name="database_username"
						size="30" value="<?php echo $database_username; ?>"  data-validate="required"></td>
				</tr>
				<tr>
					<td>Database Password :</td>
					<td><input type="password" class="form_text"
						name="database_password" size="30"
						value="<?php echo $database_password; ?>"></td>
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
				//$check->checkCfg(1);
				
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
				<h3>Create database and populate configuration file.</h3>
				<div id="divhelp_text" style="height: 50px;">
					<p id="help_text">
						Next step will create or update db.ini file and import database.<br>
						<?php //Zend_Debug::dump($check->dbInstall());?>
						<?php if ($check->canDropTable()) { 
							echo '<span id="warning" style="color:red;"><strong>All Tables already exist.';
							echo ' Drop all tables and recreate them <input type="checkbox" id="dropchk" name="drop" value="true" ></strong></span> ';
        	  			}?>
					</p>
				</div>
				<table width="100%" border="0" cellspacing="0" cellpadding="2"
					class="main_text">
					<tr>
						<td colspan=2 align='left' id="step_button"><input type="button"
							class="ui-button ui-button-bg-white" name="btn_cancel"
							value="Back"
							onclick="navigate('prev')">

							<input type="button" class="ui-button ui-button-bg-white"
							name="btn_submit" id="btn_submit" value="Continue">
							<!--
							 <input type="button" class="ui-button ui-button-bg-white" name="btn_check" id="btn_check"  value="test">
							-->
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

	<div class="bottom">
	
		<h2>Note</h2>
		<p>
			We can manage several environments (PRODUCTION, DEVELOPMENT, TESTING,
			see <strong>.htaccess</strong> file in the <em>public/</em>
			directory) and each environment can use a different Database. Here,
			we will configure the same database for all environments. You can
			manually change it in the <strong>db.ini</strong> file in <em>application/configs</em>
			directory.
		</p>
		<div class="clear"></div>
	</div>

	<div style="margin: 10px 10px 10px 10px; width: 150px; height: 20px;">
		<label id="import_2"
			style="width: 150px; height: 20px; padding-left: 10px; cursor: pointer;"
			class=""> <!--  <span style="vertical-align: 0px; margin-right: 10px;"><img id="img_tous" src="images/icone-bouton-gris.png" width="10" height="10" style="margin-right: 5px;" /> <strong>IMPORT</strong></span>-->
		</label>
	</div>