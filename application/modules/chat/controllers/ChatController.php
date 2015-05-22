<?php

class Chat_ChatController extends Centurion_Controller_Action
{
    protected $_chatMapper;

    public function init()
    {
        $this->_chatMapper = new Chat_Model_ChatMapper();
        
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $this->view->headScript()->appendFile('/layouts/frontoffice/js/jquery-1.8.2.js')
        						->appendFile('/layouts/frontoffice/js/chat/chat.js')
        						->appendFile('/layouts/frontoffice/js/chat/jquery-ui.js');
        	
    }

    public function indexAction()
    {
    	
        
    }
    
    public function viewcustomerchatAction()
    {
        if ($this->_request->isXmlHttpRequest()) {
            $request = $this->getRequest();
           
            $chat = new Chat_Model_Chat($request->getParams());
            
            $this->_chatMapper->save($chat);
        }
    }
    
    public function getmessagesAction()
    {
        $request = $this->getRequest()->getParam('sessId');
        $entries = array();

        if ($this->_request->isXmlHttpRequest()) {
            $messages = $this->_chatMapper->findBySess($request);
            foreach ($messages as $result) {
                $entries[] = array('role' => $result->role, 
                			'message' => $result->message,
                			'user' => $result->user,
                			'org_id' => $result->org_id,
                			'organization' => $result->organization);
            }
        }
        echo Zend_Json::encode($entries);
    }
}

