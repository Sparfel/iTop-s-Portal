<?php
class Config_AdminHomeController extends Centurion_Controller_Action
{
public function preDispatch()
{
	$this->_helper->authCheck();
	$this->_helper->aclCheck();
	$this->_helper->layout->setLayout('admin');

	parent::preDispatch();
}
 
public function dashboardAction()
{
	$config = Centurion_Config_Manager::getModuleConfig('configuration.adminhome');
	//Zend_Debug::dump($config);
	$this->_helper->widgetRenderer($config);
}

public function glanceAction()
{
	$this->view->glanceData = array(
			array(
					'label'         => $this->view->translate('Local Users'),
					'count'         => Centurion_Db::getSingleton('auth/user')->count(),
					'url'           => $this->view->url(array('module' => 'user', 'controller' => 'admin-user', 'action' => 'index'), 'default'),
					'actionLabel'   => $this->view->translate('Create'),
					'actionUrl'     => $this->view->url(array('module' => 'user', 'controller' => 'admin-user', 'action' => 'new'), 'default'),
					'highlight'     => true
			),
			array(
					'label'         => $this->view->translate('iTop Users'),
					'count'         => Centurion_Db::getSingleton('portal_Itop/itopUser')->count(),
					'url'           => $this->view->url(array('module' => 'user', 'controller' => 'admin-itop-user', 'action' => 'index'), 'default'),
					'actionLabel'   => $this->view->translate('Create'),
					'actionUrl'     => $this->view->url(array('module' => 'user', 'controller' => 'admin-itop-user', 'action' => 'new'), 'default'),
					'highlight'     => true
			),
			array(
					'label'         => $this->view->translate('Ldap User'),
					'count'         => Centurion_Db::getSingleton('portal_Ldap/ldapUser')->count(),
					'url'           => $this->view->url(array('module' => 'user', 'controller' => 'admin-ldap-user', 'action' => 'index'), 'default'),
					'actionLabel'   => $this->view->translate('Create'),
					'actionUrl'     => $this->view->url(array('module' => 'user', 'controller' => 'admin-ldap-user', 'action' => 'new'), 'default'),
					'highlight'     => true
			),
			array(
					'label'         => $this->view->translate('Organizations'),
					'count'         => Centurion_Db::getSingleton('portal/organization')->count(),
					'url'           => $this->view->url(array('module' => 'config', 'controller' => 'admin-organizations', 'action' => 'index'), 'default'),
					'actionLabel'   => $this->view->translate('Create'),
					'actionUrl'     => $this->view->url(array('module' => 'config', 'controller' => 'admin-organizations', 'action' => 'new'), 'default'),
					'highlight'     => true
			)/*,
	array(
			'label'         => $this->view->translate('Movie types'),
			'count'         => Centurion_Db::getSingleton('demo/movie_type')->count(),
			'url'           => $this->view->url(array('module' => 'demo', 'controller' => 'admin-movie-type'), 'default'),
			'actionLabel'   => $this->view->translate('Create'),
			'actionUrl'     => $this->view->url(array('module' => 'demo', 'controller' => 'admin-movie-type', 'action' => 'new'), 'default'),
			'highlight'     => false
	)*/
	);
	 
	$this->_helper->widgetRenderer->renderAsWidget();
}

}