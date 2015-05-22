<?php

class User_AdminProfileController extends Centurion_Controller_CRUD
{
    public function init()
    {
        $this->_formClassName = 'User_Form_Model_AdminProfile';
        

        $this->_toolbarActions['accountCreation'] = $this->view->translate('Create Account');

        $this->_displays = array(
            'nickname'         =>  $this->view->translate('Nickname'),
            'user__username'   =>  $this->view->translate('Login'),
            'created_at'       =>  $this->view->translate('Created at'),
            'user__last_login' =>  $this->view->translate('Last login'),
        );

        $this->view->placeholder('headling_1_content')->set($this->view->translate('Manage profile\'s users'));
        $this->view->placeholder('headling_1_add_button')->set($this->view->translate('profile\'s user'));

        parent::init();
    }

    public function preDispatch()
    {
        $this->_helper->authCheck();
        $this->_helper->aclCheck();
        //nécessaire pour que les liens (formulaires) conserve le breadrumbs
        parent::preDispatch();
    }
}