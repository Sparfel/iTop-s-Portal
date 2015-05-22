<?php
class Portal_Controller_Action_Helper_SavePref extends Zend_Controller_Action_Helper_Abstract 
{
    /**
     * addMessage() - Add a message to flash message
     *
     * @param  string $message
     * @return Zend_Controller_Action_Helper_FlashMessenger Provides a fluent interface
     */
	
	protected $_baseurl;
	protected $_request;
	protected $_path;
	protected $_module;
	protected $_controller;
	
	
	
	public function __construct() {
		
		$this->_baseurl =  $this->getRequest()->getBaseUrl();
    	$this->_request =  $this->getRequest()->getRequestUri();
    	$this->_module = $this->getRequest()->getParam('module');
    	$this->_controller = $this->getRequest()->getParam('controller');
    	//$this->_path = $this->_baseurl.'/'.$this->_module.'/'.$this->_controller.'/savepref';
    	
    	// On récupère l'id du user qui fera partie de la primary Key 
    	$session = new Zend_Session_Namespace('Zend_Auth');
    	$this->_user_id = $session->pref->_user_id;
    }
	
    public function savePrefHomeServicesScript($spanID, $ulClass, $action,$param_name)
    {
    	$this->_path = $this->_baseurl.'/'.$this->_module.'/'.$this->_controller.'/'.$action;
		$script = "$(document).ready(function(){
	            		// Get items
						function getItems(exampleNr)
						{
							var columns = [];
							$(exampleNr + ' ".$ulClass."').each(function(){
								columns.push($(this).sortable('toArray').join(','));				
							});
							return columns.join('|');
						}
        		
	 			        function launchAjax(){
	            			$.ajax({
	                			url       : '".$this->_path."',
	                			type      : 'post',
	                			data      :{
	                    			user_id : '".$this->_user_id."',
	                    			pref : getItems('".$spanID."'),
	                    			param_name : '".$param_name."'
				                	},
			                	success : function(code_html, statut){
			                    	//$(code_html).appendTo('".$spanID."');
             						//alert(' Yehhhaaaa ' + getItems('".$spanID."'));
			                	},
	                    		error :function(xhr, ajaxOptions, thrownError){
								    //alert(' Damned ' +getItems('".$spanID."'));
					     			// alert(xhr.status);
					     	        //alert(thrownError);
    							},
	           					complete : function(resultat, statut){
    								//alert(getItems('".$spanID."'));
           							}		
			            	});
    					}
	                    					
	                   // Sortable and connectable lists with visual helper
						$('".$spanID." ".$ulClass."').sortable({
							connectWith: '".$spanID." ".$ulClass."',
							placeholder: 'placeholder',
							update: function(){
								//alert(getItems('".$spanID."'));
								launchAjax();
								}
						});
					
	                    					
		        	});";
      return $script;
    }

    
    public function saveGlobalPreference(){
    	
    }
    
    
    
    
    /*public function saveFilterUserOnlyScript($module, $controler, $action)
    {
    	$this->_path = $this->_baseurl.'/'.$module.'/'.$controler.'/'.$action;
    	
    	$script = "$(document).ready(function() {
					      $('#chkSelect').change(function(){
    							var isChecked = $('#chkSelect').is(':checked');
						  		launchAjax(isChecked);
					       });

    					function launchAjax(value){
								$.ajax({
									url       : '".$this->_path."',
									type      : 'post',
									data      :{
										user_id : '".$this->_user_id."',
										is_checked : value
										},
									success : function(code_html, statut){
										alert(' Yehhhaaaa ' );
									},
									error :function(xhr, ajaxOptions, thrownError){
										alert(' Damned ' );
										 alert(xhr.status);
										alert(thrownError);
									},
									complete : function(resultat, statut){
										alert('Ok c bon');
										}		
								});
							}
						});";
    	return $script;
    }*/

  }
