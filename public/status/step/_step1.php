<?php 
//Zend_Debug::dump();
$dbParams = $check->getDbParameters();
$database_host = $dbParams['database_host'];
$database_name = $dbParams['database_name'];
$database_username = $dbParams['database_username'];
$database_password = $dbParams['database_password'];
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
		action="index.php?step=<?php echo $next_step;?>">
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
					<td><input type="text" class="form_text" name="database_host"
						value="<?php echo $database_host; ?>" size="30" required></td>
				</tr>
				<tr>
					<td>Database Name :</td>
					<td><input type="text" class="form_text" name="database_name"
						size="30" value="<?php echo $database_name; ?>" required></td>
				</tr>
				<tr>
					<td>Database Username :</td>
					<td><input type="text" class="form_text" name="database_username"
						size="30" value="<?php echo $database_username; ?>" required></td>
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

		<script type="text/javascript">
	$(document).ready(function() {
		$('input[name=database_host]').change(function () {
		    'use strict';
		    var database_host = document.forms["dbform"].elements["database_host"];
		    if (database_host.value == null || database_host.value == "") {
		    	database_host.setCustomValidity('Please give a database name');
				
			}
			else {database_host.setCustomValidity('');}
		});
		
		$('input[name=database_name]').change(function () {
		    'use strict';
		    var database_name = document.forms["dbform"].elements["database_name"];
		    if (database_name.value == null || database_name.value == "") {
				database_name.setCustomValidity('Please give a database name');
				
			}
			else {database_name.setCustomValidity('');}
		});
		$('input[name=database_username]').change(function () {
		    'use strict';
		    var database_username = document.forms["dbform"].elements["database_username"];
		    if (database_username.value == null || database_username.value == "") {
		    	database_username.setCustomValidity('Please give a database name');
				
			}
			else {database_username.setCustomValidity('');}
		});
	
		
		$("#btn_submit").click(function (event) {
			//Check if field for database connection are set 
			var database_host = document.forms["dbform"].elements["database_host"];
			var database_name = document.forms["dbform"].elements["database_name"];
			var database_username = document.forms["dbform"].elements["database_username"];
			var database_password = document.forms["dbform"].elements["database_password"];
			
			if (document.getElementById("dropchk")) {var drop_tables = document.forms["dbform"].elements["drop"];}
			else {var drop_tables = false;}
			

			var FormIsInvalid = false;
			
			if (database_host.value == null || database_host.value == "") 
				{database_host.setCustomValidity('Please select a host');	
				FormIsInvalid = true;}
			if (database_name.value == null || database_name.value == "") 
				{database_name.setCustomValidity('Please give a database name');	
				FormIsInvalid = true;}
			if (database_username.value == null || database_username.value == "") 
				{database_username.setCustomValidity('Please give an username');	
				FormIsInvalid = true;}
			/*if (database_password.value == null || database_password.value == "") 
			{database_password.setCustomValidity('Please fill the');
			return false;}*/
			if (FormIsInvalid) { return error;}
			$("#btn_submit").hide();
			//First : db.ini file generation to store the database credentials
			$.ajax({ 
	    		xhr: function() {
	                var xhr = new window.XMLHttpRequest();
	                xhr.addEventListener("progress", function(e){
	                    //console.log(e.currentTarget.response);
	                    JSON.parse(e.currentTarget.response, function (key, value) {
							if (key == 'msg') {
								 $('#display_db_file').fadeIn(1000);     
	                    		$("#display_db_file").html(value);
							}
		                    });
	                    
	                });
	            return xhr;   
	    		},                                                        
	            type: "POST",
	            url: "genDbFile.php",
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
		            	if ((key == 'next_step') && (value == 'true')) {
			            	if (drop_tables.checked) {dropTables = true;}
			            	else {dropTables = false;}
                    		importDb(dropTables);
						}
					});
		        //document.forms["dbform"].submit();
	            }
	        });
		});	
	
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
            url: "impDb.php",
            data: { 'drop' : dropTables},
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
				recheck()
					
	             }
            });
		
		}
	
	function next() {	
		$("#help_text").hide(0);
		$("#help_text").delay(500).empty();
		
        $("#help_text").append('Next step will configure the Webservice\'s configuration file.');
        $("#help_text").show(500);
       // $("#step_button").hide(500);
        $("#step_button").empty();
        $("#step_button").delay(500).append('<input type="button" class="ui-button ui-button-bg-white" name="btn_cancel" value="Back" onclick="back()">');
		$("#step_button").delay(500).append('<input type="submit" class="ui-button ui-button-bg-white" name="btn_next" id="btn_submit"  value="Next">');
        $("#step_button").show(500);
	}

	function retry() {
		//document.getElementById('btn_submit').value= "Retry";
		$("#step_button").delay(500).append('<input type="submit" class="ui-button ui-button-bg-white" name="btn_next" id="btn_submit"  value="Retry">');
		$("#btn_submit").delay(500).show();
		//$("#step_button").delay(500).append('<input type="button" class="ui-button ui-button-bg-white" name="btn_reset" id="btn_reset"  value="Reset">');
	    $("#step_button").show(500);
		
		}

	function recheck(){
		getList();
	}

	function getList() {
		$.ajax({ 
            
            type: "POST",
            url: "chkCfg.php",
            data: {
				'check' : 'all' 
					},
			//dataType: "json",
            success: function(data){
            	$("#check_list").replaceWith('<ul id="check_list" class="checklist">'+data+'</ul>');}
		});
	}
	
	$("#btn_check").click(function (event) {
		recheck();
		});
	
	});

	function back(){
		window.location.href='index.php?step=<?php echo $prev_step;?>';
	} 

	</script>


		<div class="middle">
			<div class="left" style="width: 385px;">
				<h3>Create database and populate configuration file.</h3>
				<div id="divhelp_text" style="height: 50px;">
					<p id="help_text">
						Next step will create or update db.ini file and import database.<br>
						<?php if ($check->canDropTable()) { 
							echo '<span id="warning" style="color:red;"><strong>All Tables already exist.';

							echo ' Drop all tables and recreate them <input type="checkbox" id="dropchk" name="drop" value="true" checked></strong></span> ';
        	  			}?>
					</p>
				</div>
				<table width="100%" border="0" cellspacing="0" cellpadding="2"
					class="main_text">
					<tr>
						<td colspan=2 align='left' id="step_button"><input type="button"
							class="ui-button ui-button-bg-white" name="btn_cancel"
							value="Back"
							onclick="window.location.href='index.php?step=<?php echo $prev_step;?>'">

							<input type="button" class="ui-button ui-button-bg-white"
							name="btn_submit" id="btn_submit" value="Continue"> <!-- <input type="button" class="ui-button ui-button-bg-white" name="btn_check" id="btn_check"  value="test">-->
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