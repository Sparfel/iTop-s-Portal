<?php 
class Portal_View_Helper_TinyMCE extends Zend_View_Helper_Abstract
{
	//Helper pour rendre le code plus léger dans la vue
	//We get the alerts in the database.
	
	// in the view : $this->ShowAlerts()
	public function TinyMCE() // type de Service : Service ou ServiceSubcategory
	{
		$this->view->headScript()->appendFile('/layouts/frontoffice/js/tinymce/tinymce.min.js');
		//CSS
		//$this->view->headLink()->appendStylesheet('/layouts/frontoffice/css/alert/alert.css');
		$session = new Zend_Session_Namespace('Zend_Auth');
		$lang = $session->pref->_language;
		if ($lang == 'fr') { $language = 'fr_FR';}
		else {$language = 'en_US';}
		$server = $_SERVER['HTTP_HOST'];
		
		$Version = new Portal_Version();
		$hasHtml = $Version->hasHtmlLog();
		$script = null;
		if ($hasHtml) {
			$script = '<script>
						jQuery(document).ready( function() {
							tinymce.init({
				    			selector: \'textarea#TextArea\',
				   				skin: \'lightgray\',
				    			browser_spellcheck: true,
								auto_focus: \'TextArea\',
				    			height : 300,
				    			menubar: false,
				   				language: \''.$language.'\',
				    			plugins: [
			  	         			"advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
			  	         			"searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
			  	         			"table contextmenu directionality  template textcolor paste fullpage textcolor colorpicker textpattern"
		  	       				],
					  	       toolbar1: " cut copy paste |undo redo | link unlink | image table hr charmap | preview fullscreen", 
								toolbar2: "bold italic underline strikethrough removeformat | bullist numlist | styleselect formatselect fontselect fontsizeselect | forecolor backcolor",
		  						filemanager_crossdomain: true,
						     	external_filemanager_path:"http://'.$server.'/layouts/frontoffice/js/tinymce/plugins/filemanager/",
					 			filemanager_title:"'.$this->view->translate('Gestion des images').'" ,
					 			external_plugins: { "filemanager" : "http://'.$server.'/layouts/frontoffice/js/tinymce/plugins/filemanager/plugin.min.js"},
							    paste_data_images: true,
				    			statusbar: false
							  });
					 					
					 		
					 					
					 					
						});
					</script>';
		
		echo $script;
		}
		
	}
	
}
