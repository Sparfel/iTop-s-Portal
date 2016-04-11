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
				$newRequest = new Portal_Form_NewRequest();
				if ($this->_request->isPost()) {
					$formData = $this->_request->getPost();
		            if ($newRequest->isValid($formData)) {
						try {
							$webservice = $this->_helper->getHelper('ItopWebservice');
							$content = $webservice->CreateRequest($newRequest->getValue('title'),
															$newRequest->getValue('description'),
															null
														);
							$this->view->content = $content;
							$this->view->action='validation';
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