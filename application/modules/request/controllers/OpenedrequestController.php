<?php

class Request_OpenedrequestController extends Centurion_Controller_Action 
{
 	protected $_org_id;
	protected $_list_userRequest;
	protected $filter;
	protected $_locale = "fr_FR";
 	private $_fields;
 	
 	public $_test;
 	
 	public function preDispatch()
    {
        $this->_helper->authCheck();
        $this->_helper->aclCheck();
        
    }
    
    /*public function postDispatch()
    {$session = new Zend_Session_Namespace('Zend_Auth');
    	Zend_Debug::dump($session->Attachments);
    	//Zend_Debug::dump($session->pref);
    }*/
 	
	public function init() {
    	Zend_Layout::getMvcInstance()->assign('titre', 'Vos incidents en cours');
    	$session = new Zend_Session_Namespace('Zend_Auth');
    	$this->_org_id = $session->pref->_org_id;
    	
    	$this->_fields = array ( 
    							array
    								('field' => 'ref',
    								'label' => $this->view->translate('User Request'),
    							 	'width' => '30px',
    							 	'link'  =>  true,
    								'link_param' => '',
    								'target' => '',
    								'sort'	=> 'desc'), // ou desc
    							/*array
    								('field' => 'request_type',
    								'label' => 'Type',
    							 	'width' => '10px',
    							 	'link'  =>  false,
    							 	'target' => '',
    							 	'sort'	=> ''),*/
    							array
    							 	('field' => 'title',
    							 	'label' => $this->view->translate('Title'), //Title',
    							 	'width' => '120px',
    							 	'link'  =>  false,
    							 	'target' => '',
    							 	'sort'	=> ''
    							 	),
    							 array
    							 	('field' => 'start_date',
    							 	'label' => $this->view->translate('Started'), 
    							 	'width' => '70px',
    							 	'link'  =>  false,
    							 	'target' => '',
    							 	'sort'	=> ''),
    							 array
    								('field' => 'status',
    								'label' => $this->view->translate('Status'),
    							 	'width' => '15px',
    							 	'link'  =>  false,
    							 	'target' => '',
    							 	'sort'	=> ''),
    							/*array
    								('field' => 'service_name',
    								'label' => 'Service',
    							 	'width' => '10px',
    							 	'link'  =>  false,
    							 	'target' => '',
    							 	'sort'	=> ''),*/
    							array
    								('field' => 'priority',
    								'label' => $this->view->translate('Priority'),
    							 	'width' => '20px',
    							 	'link'  =>  false,
    							 	'target' => '',
    							 	'sort'	=> ''),
    							/*array
    								('field' => 'site_name',
    								'label' => 'Site',
    							 	'width' => '10px',
    							 	'link'  =>  false,
    							 	'target' => '',
    							 	'sort'	=> ''),*/
    							array
    								('field' => 'caller_id_friendlyname',
    								'label' => $this->view->translate('Caller'),
    							 	'width' => '70px',
    							 	'link'  =>  false,
    							 	'target' => '',
    							 	'sort'	=> '')
    							);
    	
    	
 	}
	
    
    public function indexAction() {
    	$session = new Zend_Session_Namespace('Zend_Auth');
    	
    	$ref = $this->_request->getParam('ref',null);
    	if (isset($ref)) {
    		$id = $this->refToId($ref);
    	}
    	else {
    		$id = $this->_request->getParam('id',null);
    	}
    	$this->view->title = 'Détails de la requête';
    	$this->view->headTitle()->prepend('Détails de la requête'); 
    	/*
    	 *  Request detail visualization
    	 */
		if (isset($id)) {
			$this->view->headScript()->appendFile('/layouts/frontoffice/js/jquery.MultiFile.js');
			$this->view->headScript()->appendFile('/cui/plugins/ckeditor/ckeditor.js');
			$this->view->headScript()->appendFile('/cui/plugins/ckeditor/adapters/jquery.js');
			//Style for the tool to print the Request
			$this->view->headLink()->appendStylesheet('/layouts/frontoffice/css/datatable/dataTables.tableTools.css');
			
			$this->view->typ = 'view';
			$webservice = $this->_helper->getHelper('ItopWebservice');
			
			// Parameters Array sent to the form
			// It depends on the lifecylce of the request.
			// Here, the caller can only update the request.
			$options = array ( 'update' => true,
								'resolve' => false,
								'close' => false,
								'reopen' => false,
								'id' => $id);
			
			$WSrequest = array();
			$WSrequest = $webservice->getInfoTicket($id,$this->_org_id);
			
			//The Request's Reference is sent to the view
			if (isset($WSrequest['ref'])){
				$ref = $WSrequest['ref'];
				
				$this->view->ref = $ref;
				$this->view->request = $WSrequest;
				$itop_version = 2.1; // description et log au format html
				if ($itop_version >= 2.3) {
					new Portal_Request_HtmlContent($WSrequest);
					$this->view->request['description'] = null;
					
				}
				
				$ListAttachment = new Portal_Itop_Request_Attachments($WSrequest['id']);
				$this->view->attached_files = $ListAttachment->_Aattachment;
				// Depends on Request's Status, we allow the caller to solve himself the request
				// See ticket Life Cycle to configure this.
				if ($WSrequest['status'] == 'qualified') {
					$options = array ('update'=> true,
										'resolve' => true,
										'close' => false,
										'reopen' => false);
				}
				elseif ($WSrequest['status'] == 'resolved') {
					$options = array ('update'=> false,
									'resolve' => false,
									'close' => true,
									'reopen' => true);
				} 
				elseif ($WSrequest['status'] == 'closed') {
					$options = array ('update'=> false,
							'resolve' => false,
							'close' => false,
							'reopen' => false);
				}
				
				if (!($WSrequest['status']=='closed')) {
					$options['id'] = $WSrequest['id'];
					$updateRequestForm = new Portal_Form_updateRequest($options);
				}
			}
			else {
				$updateRequestForm = null;
			}
			
			
			$this->view->updateRequestForm = $updateRequestForm;
		}
       	/*
       	 *  Viewing Request List
       	 */
       	else {
       		/*
       		 * POST Parameter were sent => changing Filters and parameters and reloading the Grid
       		 */ 
       		if ($this->_request->isPost()) {
       			$this->changefilterAction();
       			}
       		else 
       		/* 
       		 * Single viewing Requests Datagrid
       		 */
       			{
	       		$listFilter = new Portal_Form_Preference($session->pref);
	       		$this->view->form = $listFilter;
	       		$this->view->OPref = $session->pref;
	       		
	       		$this->view->headScript()->appendFile('/layouts/frontoffice/js/datatable/jquery.dataTables.js');
		        $this->view->headLink()->appendStylesheet('/layouts/frontoffice/css/datatable/jquery.dataTables.css');
		        $this->view->headScript()->appendFile('/layouts/frontoffice/js/datatable/dataTables.tableTools.js');
		        $this->view->headLink()->appendStylesheet('/layouts/frontoffice/css/datatable/dataTables.tableTools.css');
		        $this->view->headLink()->appendStylesheet('/layouts/frontoffice/css/listoption/ui.dropdownchecklist.themeroller.css');
		        //Show Results Grid
		        $this->view->title = 'Mes requêtes ouvertes';
		    	// With Action Helper, we inject the header for the data grid
		    	$datatable = $this->_helper->getHelper('DataTable');
		    	$this->_helper->getHelper('DataTable')->init();
		    	$script = $datatable->datatableHeader($this->_fields); 
		    	//$this->view->headScript()->appendScript($script);
		    	$this->view->script = $script;
		    	//Puis on passe le tableau à la vue.
		    	$this->view->tableau = $datatable->datatableTable();
       		}
       	}
       	
    }
   
   
	/*Function for Json*/
    public function getdataAction()
	{	$this->_helper->viewRenderer->setNoRender(true);
		// no layout, it's Json
		$this->_helper->layout()->disableLayout();
		//if($this->getRequest()->isXmlHttpRequest()) {
			$sEcho  = $this->getRequest()->getParam("sEcho");
	        $start  = ($this->getRequest()->has("iDisplayStart")) ? $this->getRequest()->getParam("iDisplayStart") : 0;
	        $offset = ($this->getRequest()->has("iDisplayLength")) ? $this->getRequest()->getParam("iDisplayLength") : 10;
	        $colnum = $this->getRequest()->getParam("iColumns");
	        $field_list = '';
	        foreach ($this->_fields as $field) {
	        	$field_list .= $field['field'].',';
	        }
	        $session = new Zend_Session_Namespace('Zend_Auth');
	        $webservice = $this->_helper->getHelper('ItopWebservice');
	        $tab_result = $webservice->getListOpenedRequest($this->_org_id,$session->pref);
	        $this->_list_userRequest = $tab_result;
	      	$response = array(
	          "iTotalRecords"           => 1, //$datas->getTableDataCount(),
	          "iTotalDisplayRecords"    => count($tab_result),
	          "sEcho"                   => (int)$sEcho,
	          "sColumns"                => $field,
	          "aaData"                  =>  $this->_list_userRequest //$tab_result //$result['fields'] //$data->toArray()
	        );
	        return $this->_helper->json($response);
		//};
	}
	
	//Action for files download, Attachment
	public function downloadAction()
	{	
		$this->view->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$attachedfile_index = $this->_getParam('attachedfile_index');
		$attachedfile_id = $this->_getParam('attach_id');
		$idRequest = $this->_getParam('id');
		
		$session = new Zend_Session_Namespace('Zend_Auth');
		//Request is loaded in Session, we verify that we are downloading his attachement => they are already in Session
		if ($idRequest == $session->Attachments->_idR) 
			{$OAttachment = $session->Attachments->_Aattachment[$attachedfile_index];}
		else //Else we get the attachment through the Weservice
			{
				$webservice = Zend_Controller_Action_HelperBroker::getStaticHelper('ItopWebservice');
				$OAttachment = $webservice->getAttachmentPerId($attachedfile_id,$session->pref->_org_id);
			}
		$this->getResponse()
			->setHeader('Content-Type', 'text/html;charset=iso-8859-1')
			->setHeader('Content-Type',$OAttachment->_mimetype)
			//->setHeader('Content-Transfer-Encoding', Binary)
			->setHeader('Content-Disposition:attachment', $OAttachment->_filename)
			//->setHeader("Cache-Control: must-revalidate, post-check=0, pre-check=0")
			->appendBody(base64_decode($OAttachment->_data)); // required for certain browsers
	}
    
	
	//Filters modification => bckp in global variable and  datagrid regeneration
	public function changefilterAction(){
		$this->_helper->viewRenderer->setNoRender(true);
		// no layout
		$this->_helper->layout()->disableLayout();
		//saving Filter modification
		if ($this->_request->isPost()) {
			$session = new Zend_Session_Namespace('Zend_Auth');
			$data = $this->_request->getPost();
			$Opref = $session->pref;
			$Opref->changePref($data['param'],$data['value']);
			}
		$datatable = $this->_helper->getHelper('DataTable');
		$this->_helper->getHelper('DataTable')->init();
		$script = $datatable->datatableHeader($this->_fields);
		//sending again the array to the view
		$this->view->tableau = $datatable->datatableTable();
		//$this->getdataAction();		
		$data = '<script type="text/javascript">'.$script.'</script>';//.$tableau;
		echo $data;
		}
	
		
	// Update Request and redirect to the Request viewing (index)
	// Update and redirection avoid to send again the POST after modification if user reloads the page 
	public function updateAction(){
		$session = new Zend_Session_Namespace('Zend_Auth');
		$this->view->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		
		$id = $this->_request->getParam('id',null);
		
		
		// we update the request here 
		$webservice = $this->_helper->getHelper('ItopWebservice');
			
		// Parameters Array sent to the form
		// It depends on the lifecylce of the request.
		// Here, the caller can only update the request.
		$options = array ( 'update' => true,
				'resolve' => false,
				'close' => false,
				'reopen' => false,
				'id' => $id);
		
		//If update then POST was sent
		if ($this->_request->isPost()) {
			$updateRequestForm = new Portal_Form_updateRequest($options);
			$formData = $this->_request->getPost();
			if ($updateRequestForm->isValid($formData)) {
				if (isset($_POST['submit'])) {
					try {
						if (strlen($updateRequestForm->getValue('Log'))> 0) {
							$content = $webservice->UpdateRequest($id, //$request['ref'],
									$session->pref,
									$updateRequestForm->getValue('Log'));
						}
						$this->view->typ='maj';
						$this->view->title = 'Ticket mis à jour';
					}
					catch(Zend_Exception $e) {
						echo '*'.$e->getMessage();
					}
				}
				if (isset($_POST['resolved'])) { // the Request became 'Resolved'
					$content =$webservice->resolveRequest($id,$session->pref,$updateRequestForm->getValue('Log'));
				}
				if (isset($_POST['close'])) { // the Request became 'Closed'
					$content =$webservice->closeRequest($id,$session->pref,$updateRequestForm->getValue('Log'));
				}
				if (isset($_POST['reopen'])) { // the Request is 'ReOpen'
					$content =$webservice->reopenRequest($id,$session->pref,$updateRequestForm->getValue('Log'));
				}
					
				//Attachment
				if (!(is_null($_FILES)))
				{
					$this->view->files = $_FILES['tab_files'];
					for ($i=0;$i < count($_FILES['tab_files']['name']); $i++)
					{
						$name = $_FILES['tab_files']['name'][$i];
						$type = $_FILES['tab_files']['type'][$i];
						$item_class = 'UserRequest';
						$item_id = $id;
						if (!(empty($_FILES['tab_files']['tmp_name'][$i])))
						{
							$fileData =file_get_contents($_FILES['tab_files']['tmp_name'][$i]);
							$fileData = base64_encode($fileData);
							$attachment = $webservice->AddAttachment($name,$fileData,$item_class,$item_id,$type,$session->pref->_org_id);
						}
					}
				}
			}
		}
		
		// and then we redirect to the ticket to see the changes.
		// it avoids to re-send the POST if user reloads the page 
		$module = $this->getRequest()->getParam('module');
		$controller = $this->getRequest()->getParam('controller');
		$url_redirection = '/'.$module.'/'.$controller.'/index/language/'.$session->pref->_language.'/id/'.$id;
		$this->getHelper('redirector')->gotoUrlAndExit($url_redirection);
		
		
		
		echo $this->getRequest()->getBaseUrl();
		echo '<br>';
		echo  $this->getRequest()->getRequestUri();
		echo '<br>';
		echo  $this->getRequest()->getParam('module');
		echo '<br>';
		echo $this->getRequest()->getParam('controller');
		echo '<br>';
		
	}
	
	private function refToId($ref){
		// = $this->_request->getParam('ref',null);
		$id = '';
		if (isset($ref)) {
			$webservice = $this->_helper->getHelper('ItopWebservice');
			$result = $webservice->getTicketId($ref,$this->_org_id);
			$id = $result['id'];
		}
		return $id;
	}
		
}