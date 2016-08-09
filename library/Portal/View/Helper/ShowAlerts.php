<?php 
class Portal_View_Helper_ShowAlerts extends Zend_View_Helper_Abstract
{
	//Helper pour rendre le code plus léger dans la vue
	//We get the alerts in the database.
	
	// in the view : $this->ShowAlerts()
	public function ShowAlerts() // type de Service : Service ou ServiceSubcategory
	{
		$this->view->headScript()->appendFile('/layouts/frontoffice/js/alert/jquery.newsTicker.js');
		//$view->headScript()->appendFile('/layouts/frontoffice/js/dropzone/script.js');
		//CSS
		$this->view->headLink()->appendStylesheet('/layouts/frontoffice/css/alert/alert.css');
		$session = new Zend_Session_Namespace('Zend_Auth');
		$listAlert = new Portal_Model_DbTable_Alert();
		$result = $listAlert->getMyAlerts($session->pref->_org_id);
		$AAlerts = array();
		$i = 0;
		foreach ($result as $key=>$value){
			$AAlerts[$i]['id'] = $value->id;
			$AAlerts[$i]['text'] = $value->text;
			$AAlerts[$i]['type'] = $value->type;
			switch ($value->type) {
				case 'Alert' :
					$AAlerts[$i]['title'] = 'Alert';
					$AAlerts[$i]['titleStyle'] = 'red';
					break;
				case 'Information' :
					$AAlerts[$i]['title'] = 'Info';
					$AAlerts[$i]['titleStyle'] = 'parme';
					break;
				case 'Notification' :
					$AAlerts[$i]['title'] = 'Note';
					$AAlerts[$i]['titleStyle'] = 'light';
					break;
				}
			$i++;	
		}
		//Zend_Debug::dump($AAlerts);
		
		$script = '';
		if (count($AAlerts)>0){
			$script .= '<div class="breakingNews bn-bordernone bn-'.$AAlerts[0]['titleStyle'].'" id="bn1">
	    				<div class="bn-title" style="width: 80px;"><h2 id="alert-title" >
							<span class="title">'.$AAlerts[0]['title'].'</span> </h2><span></span></div>
	        			<ul id="newsticker" style="left: 95px;">';
			for ($i=0; $i < count($AAlerts); $i++) {
				$script .= 	'<li bn-title="'.$AAlerts[$i]['title'].'" class="listAlert" bn-style="'.$AAlerts[$i]['titleStyle'].'" >'.$AAlerts[$i]['text'].'</li>';
			}
			$script .=  '</ul></div>';
			if (count($AAlerts)>1){
				$script .= '<script>
							$("#newsticker").newsTicker({
								    row_height:40,
								    max_rows: 1,
								    speed: 600,
								    direction: "up",
								    duration: 6000,
								    autostart: 1,
								    pauseOnHover: 1,
									hasMoved: function() {
										//var GetAttribute= document.getElementsByTagName("li");
										var GetAttribute= document.getElementsByClassName("listAlert");
										//for(var i=0; i< GetAttribute.length; i++){
										//console.log(GetAttribute.item(0).getAttribute("bn-title"));
										//}
										document.getElementById("bn1").className="breakingNews bn-bordernone bn-"+GetAttribute.item(0).getAttribute("bn-style");
								    	//console.log(this);
										document.getElementsByClassName("title")[0].innerHTML = GetAttribute.item(0).getAttribute("bn-title");
								        }
								});
							</script>';
			}
		}
		echo $script;
	}
		
}
	
