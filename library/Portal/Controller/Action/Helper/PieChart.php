<?php
class Portal_Controller_Action_Helper_PieChart extends Zend_Controller_Action_Helper_Abstract 
{
    /**
     * 
     */
	
	protected $_baseurl;
	protected $_request;
	protected $_path;
	protected $_module;
	protected $_controller;
	protected $_unique_id;
	
	protected $_data; // tableau de données
	protected $_radio_name;
	protected $_action;
	protected $_title; // Title
	protected $_var1; // Var : Resolution
	protected $_var2;// Per nb tickets
	protected $_chart_div; // The Div of the content
	protected $_count_attribute; //Count attribute
	
	protected $Alabel; // An array wityh all translated labels
	
	
	public function __construct() {
		
		$this->_baseurl =  $this->getRequest()->getBaseUrl();
    	$this->_request =  $this->getRequest()->getRequestUri();
    	$this->_module = $this->getRequest()->getParam('module');
    	$this->_controller = $this->getRequest()->getParam('controller');
    	//$this->_path = $this->_baseurl.'/'.$this->_module.'/'.$this->_controller.'/savepref';
    	
    	// On récupère l'id du user qui fera partie de la primary Key 
    	$session = new Zend_Session_Namespace('Zend_Auth');
    	$this->_user_id = $session->pref->_user_id;
    	$this->_user_filter = $session->pref->_userFilter;
    	
    	//We initialize the Array of Label's translation
    	$this->LabelTranslate();
    	
    }
	
    public function initialize($data,
    					$radio_name,
    					$action,
					    $title, // Le titre
					    $var1, // les variables : code résolution
					    $var2,// selon leur nombre de tickets
					    $chart_div, // Le Div contenant le graphique
    					$count_attribute) 
    {
    	$this->_unique_id = $radio_name.'_'.$chart_div;
    	$this->_data= $data;
    	$this->_radio_name= $radio_name;
    	$this->_action= $action;
    	$this->_title= $title; // Le titre
    	$this->_var1= $var1; // les variables : code résolution
    	$this->_var2= $var2;// selon leur nombre de tickets
    	$this->_chart_div= $chart_div; // Le Div contenant le graphique
    	$this->_count_attribute= $count_attribute;
    }
    
    
    
    //Gestion du script pour les radio button donnant le choix entre User / Organisation
    public function RadioButtonScript()
    		
    		/*$radio_name,
    									$action,
					    				$title, // Le titre
					        			$var1, // les variables : code résolution
					        			$var2,// selon leur nombre de tickets
					        			$chart_div, // Le Div contenant le graphique
    									$count_attribute) // L'attribut que l'on cumule
    									*/
    {
    	$this->_path = $this->_baseurl.'/'.$this->_module.'/'.$this->_controller.'/'.$this->_action;
    	// Le Div que l'on recharge doit être identifier de manière unique
    	
    	//Gestion du checked
    	if ($this->_user_filter) {
    		$chkUser = 'checked';
    		$chkOrg = '';
    		}
    	else {
    		$chkUser = '';
    		$chkOrg = 'checked';
    		}
    	
    	$script = "<script type='text/javascript'>$(document).ready(function() {
						$('.choice').change(function(){
	   		     	        var radio_button_value = $('input:radio[name=".$this->_radio_name."]:checked').val();
	   	       				launchAjax(radio_button_value);
	   	      			});
						function launchAjax(radio_button_value){
	   						$.ajax({
	   							type: 'get',
	   							url: '".$this->_path."',
	    	   					data:
	    	   							{'choice' : radio_button_value,
    									'title' : '".$this->_title."' ,
    									'action' : '".$this->_action."' ,
	   									'var1' : '".$this->_var1."',
	   									'var2' : '".$this->_var2."',
	   									'chart_div' : '".$this->_chart_div."',
	   									'count_attribute' :  '".$this->_count_attribute."'  
    									},
	    	   					dataType : 'html',
	    	   					error :function(xhr, ajaxOptions, thrownError){
	    	   								alert(xhr.status);
	    	   								alert(thrownError);
								    	},
								   success:function(data){
								    		//eval(data);
								    		//on vide la div et on le cache
									    	$('#zone_de_rechargement".$this->_unique_id."').empty(); //.hide();
									    	//on affecte les resultats au div
									    	//alert(data);
									    	$('#zone_de_rechargement".$this->_unique_id."').append(data);
									    	drawChart();
									    	
								    		//on affiche les resultats avec la transition
								    		$('#zone_de_rechargement".$this->_unique_id."').fadeIn(1000);
								    	}
								    });
								 }
							})
	   				</script>
					<div class='choice' style='width:100%; text-align:center;margin-top:-50px; margin-bottom:10px;position:relative;' >
	   						<input type='radio' name='".$this->_radio_name."' value='user' ".$chkUser.">".$this->Alabel['user']."
							<input type='radio' name='".$this->_radio_name."' value='organization' ".$chkOrg.">".$this->Alabel['organization']."
					</div>
					";
   		return $script;
    }
    
    
    // Script pour gérer le diagramme circulaire
    Public function PieScript(){ // le tableau de données à afficher
    	$tab_values='';
    	$i=0;
    	if (count($this->_data)>0)
    	{
	    	foreach($this->_data as $key => $result) {
	    		if ($i>0) {$tab_values.=',';}
	    		$tab_values.= '[\''.$key.'\','.$result.']';
	    		$i++;
	    	}
    	}
    	//Zend_Debug::dump($tab_values);
    	
    	$script = "
    			<div id='zone_de_rechargement".$this->_unique_id."'>
					<script type='text/javascript' src='https://www.google.com/jsapi'></script>
	    			<script type='text/javascript'>//Le diagramme en lui meme
					google.load('visualization', '1', {packages:['corechart']});
				    google.setOnLoadCallback(drawChart);
				    function drawChart() {
				        var datas = google.visualization.arrayToDataTable([
				          ['".$this->_var1."', '".$this->_var2."'],".$tab_values."]);
						var options = {
				        	title: '".$this->_title."',
						  	is3D:true,
						  	chartArea:{left:45,top:20,width:'100%',height:'70%'},
							colors:['#51b1ee','#89ba4b','#28B4E6','#ca4435','#e3aa2f','#5b5b5b','#5cae9b','#646496','#FF9900','#d5d5d5','#7272a8']
				        	};
				        var chart = new google.visualization.PieChart(document.getElementById('".$this->_chart_div."'));
	       				chart.draw(datas, options);
	      			}
			        </script><div id='".$this->_chart_div."' style='width: 600px; height: 300px;'></div>
			    </div>		
			        ";
	
      return $script;
    }
    
    // Script pour gérer le diagramme circulaire
    Public function PieScript2(){ // le tableau de données à afficher
    	$tab_values='';
    	$i=0;
    	foreach($this->_data as $key => $result) {
    		if ($i>0) {$tab_values.=',';}
    		$tab_values.= '[\''.$key.'\','.$result.']';
    		$i++;
    	}
    	 
    	 
    	$script = "
    			<div id='zone_de_rechargement".$this->_unique_id."'>
	    			<script type='text/javascript'>//Le diagramme en lui meme
					$(document).ready(function(){
					    var s1 = ['".$tab_values."'];
					         
					    var plot8 = $.jqplot('pie8', [s1], {
					        grid: {
					            drawBorder: false,
					            drawGridlines: false,
					            background: '#ffffff',
					            shadow:false
					        },
					        axesDefaults: {
					             
					        },
					        seriesDefaults:{
					            renderer:$.jqplot.PieRenderer,
					            rendererOptions: {
					                showDataLabels: true
					            }
					        },
					        legend: {
					            show: true,
					            rendererOptions: {
					                numberRows: 1
					            },
					            location: 's'
					        }
					    });
					});
			        </script><div id='".$this->_chart_div."' style='width: 600px; height: 300px;'></div>
			    </div>
			        ";
    
    	return $script;
    }
    
    private function LabelTranslate(){
    	$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
    	if (null === $viewRenderer->view) {
    		$viewRenderer->initView();
    	}
    	$view = $viewRenderer->view;
    	//We use " and not ' in the translation string to be able to use \' in the string.
    	$this->Alabel['user'] =  $view->translate("Utilisateur");
    	$this->Alabel['organization'] =      $view->translate("Organisation");
    }

  }
