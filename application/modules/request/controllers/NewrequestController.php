<?php

class Request_NewrequestController extends Centurion_Controller_Action 
{
    
	public function preDispatch()
    {
        $this->_helper->authCheck();
        $this->_helper->aclCheck();
        Zend_Layout::getMvcInstance()->assign('titre', $this->view->translate('Déclarer un incident'));
        $session = new Zend_Session_Namespace('Zend_Auth');
    	$this->_org_id = $session->pref->_org_id;
    }
	
	public function indexAction() {
		$this->view->title = $this->view->translate('Nouvelle requête utilisateur.');
		$id = $this->_request->getParam('ref_request',null);
		if (is_null($id))
				{
				$this->view->headScript()->appendFile('/layouts/frontoffice/js/jquery.MultiFile.js');
				$Version = new Portal_Version();
				$this->view->hasHtml = $Version->hasHtmlLog();
				/*if ($this->view->hasHtml) { 
					$this->view->headScript()->appendFile('/cui/plugins/ckeditor/ckeditor.js');
					$this->view->headScript()->appendFile('/cui/plugins/ckeditor/adapters/jquery.js');
				}*/
				
				$newRequest = new Portal_Form_NewRequest();
				if ($this->_request->isPost()) {
					$formData = $this->_request->getPost();
		            if ($newRequest->isValid($formData)) {
						try {
							//Zend_Debug::dump($formData);
							$webservice = $this->_helper->getHelper('ItopWebservice');
							$description = $newRequest->getValue('TextArea');
							
							//$HtmlRequest = new Portal_Itop_Request_HtmlContent();
							
							$content = $webservice->CreateRequest($newRequest->getValue('title'),
															$newRequest->getValue('TextArea'),
														//$HtmlRequest->generatePortal2Itop($description),
															null
														);
							Zend_Debug::dump($content);
							$this->view->content = $content;
							$this->view->action='validation';
							//$userRequestName = '123456';
							// Add the attachment
							if (is_array($content))
							{
								foreach($content as $cle => $request) :
									if (is_array($request))
										{
										foreach ($request as $key => $data) :
											$item_id = $data['fields']['id'];
											$userRequestName = $data['fields']['friendlyname'];
											$msg = $data['message'];
											$item_class = $data['class'];
											//Send the Request Id to the view
											$this->view->NoRequest = $userRequestName;
											if ($this->view->hasHtml) {
												//And We update the Request's description to have rich text with htlm and pictures !!
												// We do it here because we need the Request Id to add InlineImage into iTop
												$HtmlRequest = new Portal_Itop_Request_HtmlContent($item_id);
												$webservice->UpdateRequestDescription($item_id,$HtmlRequest->generatePortal2Itop($description));
											}
										endforeach;
										}
								endforeach;
								$this->view->files = $_FILES['tab_files'];
								for ($i=0;$i < count($_FILES['tab_files']['name']); $i++)
								{	
									$name = $_FILES['tab_files']['name'][$i];
									$type = $_FILES['tab_files']['type'][$i];
									if (!(empty($_FILES['tab_files']['tmp_name'][$i])))
										{
											$fileData =file_get_contents($_FILES['tab_files']['tmp_name'][$i]);
											$fileData = base64_encode($fileData);
											$attachment = $webservice->AddAttachment($name,$fileData,$item_class,$item_id,$type,$this->_org_id);
										}
								}
							
							}
							//Zend_Debug::dump($userRequestName);
							$this->_helper->redirector('index', 'newrequest', 'request', array('ref_request'=>$userRequestName));
						}
						catch(Zend_Exception $e) {
							echo '*'.$e->getMessage();
						}
					}
					else {
						//Formulaire doit être verrouillé pour forcé la saisie des champs. 
						echo 'Request not created.';
					}
				}
				else {
					//Request Creation Form
					$this->view->form = $newRequest;
					$this->view->action='creation';
				}
			}
		else
		{
			//The Request is created, we show the message
			$this->view->ref_request = $id;
			$this->view->action='success';
		}
    }
     
}