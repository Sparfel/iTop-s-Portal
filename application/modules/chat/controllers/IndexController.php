<?php

class Chat_IndexController extends Centurion_Controller_Action
{
    protected $_sessId;
    protected $_chatMapper;

    public function init()
    {
        $this->_chatMapper = new Chat_Model_ChatMapper();
        $this->_sessId = Zend_Session::getId();
        $this->view->sessId = $this->_sessId;
        $session = new Zend_Session_Namespace('Zend_Auth');
        $this->view->user = $session->pref->_user_first_name;
        $this->view->org_id = $session->pref->_org_id;
        $this->view->organization = $session->pref->_org_name;
        Zend_Layout::getMvcInstance()->assign('titre', 'Contacter un Agent');
        
        $this->view->headScript()->appendFile('/layouts/frontoffice/js/jquery-1.8.2.js')
        						->appendFile('/layouts/frontoffice/js/chat/chat.js')
        						->appendFile('/layouts/frontoffice/js/chat/jquery-ui.js');
    }

    public function newAction()
    {
        
    }
    public function indexAction()
    {
    
    }
    
    public function viewallchatsAction()
    {
        $this->view->entries = $this->_chatMapper->selectDistinctSessId();
    }
    
    public function viewcustomerchatAction()
    {
        $request = $this->getRequest()->getParam('sessId', null);
        
        if ($request == null) {
            throw new Zend_Exception('Param null.');
        }
        
        $this->view->messages = $this->_chatMapper->findBySess($request);
        $this->view->sessId = $request;
        $this->view->headScript()->appendFile('/layouts/frontoffice/js/chat/chat.js');
    }
}

