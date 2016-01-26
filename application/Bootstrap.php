<?php

class Bootstrap extends Centurion_Application_Bootstrap_Bootstrap
{
    /**
     * Init log for debug, prefixed by current date.
     */
    protected function _initLogDated()
    {
        $options = array(
                'stream' => array(
                    'writerName' => 'Stream',
                    'writerParams' => array(
                        'stream' => APPLICATION_PATH . sprintf('/../data/logs/%s_%s_application.log', APPLICATION_ENV, Zend_Date::now()->toString('yyyy.MM.dd')),
                        'mode'   => 'a'
                    )
                ),
        );

        $this->_loadPluginResource('Log', $options);
        $this->_executeResource('Log');
    }

    protected function _initDb()
    {
        try {
            Zend_Db_Table_Abstract::setDefaultAdapter($this->getPluginResource('db')->getDbAdapter());
            Zend_Db_Table_Abstract::setDefaultMetadataCache($this->_getCache('core'));

            Centurion_Db_Table_Abstract::setDefaultBackendOptions(Centurion_Config_Manager::get('resources.cachemanager.class.backend.options'));
            Centurion_Db_Table_Abstract::setDefaultFrontendOptions(Centurion_Config_Manager::get('resources.cachemanager.class.frontend.options'));
        } catch (Exception $e) {
            exit($e->getMessage());
        }
    }

    protected function _initTranslation()
    {
        $cache = $this->_getCache('core');
        Zend_Translate::setCache($cache);
        Zend_Date::setOptions(array('cache' => $cache));
        Zend_Paginator::setCache($cache);
      
        $session = new Zend_Session_Namespace('Zend_Auth');
        //$this->_org_id = $session->org_id;
        //if ($session->language == 'en') {
        if (isset($session->pref->_language)) {
        	//on bacule la langue
        	$this->bootstrap('frontController');
        	$frontController = $this->getResource('frontController');
        	//permet de mettre le code langue dans l'url.
        	$frontController->getRouter()->setGlobalParam('language', $session->pref->_language);
        	//on passe la langue au layout pour le changement
        	$this->getApplication()->bootstrap('view');
        	$view = $this->getResource ( 'view' );
        	$view->language = $session->pref->_language;
        }
        
        
    }

    protected function _initRequest()
    {
        $this->bootstrap('FrontController');
        $front = $this->getResource('FrontController');
        $request = $front->getRequest();

        if (null === $front->getRequest()) {
            $request = new Zend_Controller_Request_Http();
            $front->setRequest($request);
        }
        Zend_Session::rememberMe();
        return $request;
    }

    
    protected function _initRememberMe(){
    	// User checked remember me on authetification
    	if (isset($_COOKIE["iTop_Auth_RememberMe"])) {
    		Zend_Session::rememberMe($appConfig->sessions->rememberMe);
    		unset($_COOKIE["iTop_Auth_RememberMe"]);
    	}
    	 
    	// set options with multi domain cookie @see : http://www.z-f.fr/forum/viewtopic.php?id=502
    	// activate session garbage collector disabled by default on Debian gc_probability, gc_divisor @see http://oscarm.org/news/detail/666-debian_php5_and_session_garbage_collection
    	// @see http://www.nabble.com/Zend_Session::rememberMe()-td19975066.html
    	/*$sessionOptions = array(
    	 'cookie_path' => '/' ,
    			'cookie_domain' => '.' . $appConfig->url->domain ,
    			'save_path' => self::$_rootPath . '/' . $appConfig->sessions->savePath ,
    			'gc_probability' => 1 ,
    			'gc_divisor' => 100 ,
    			'gc_maxlifetime' => $appConfig->sessions->gc_maxlifetime);
    	Zend_Session::setOptions($sessionOptions);*/
       
    }
    
    protected function _initZFDebug()
    {
        if (Centurion_Config_Manager::get('zfdebug')) {
            $this->bootstrap('frontController');
            $frontController = $this->getResource('frontController');
            
            //permet de mettre le code langue dans l'url.
            //$requestedLocale = 'fr';
            //$frontController->getRouter()->setGlobalParam('language', $requestedLocale);
            //$requestedLocale = 'en';
            //$frontController->getRouter()->setGlobalParam('language', $requestedLocale);

            $options = array(
                'plugins' => array('Variables',
                                   'File' => array('base_path' => APPLICATION_PATH),
                                   'Memory',
                                   'Time',
                                   'Registry',
                                   'Exception',
                                   'Cache' => array('backend' => array('core'   =>  $this->_getCache('core')->getBackend(),
                                                                       'view'   =>  $this->_getCache('view')->getBackend(),
                                                                       '_page'   =>  $this->_getCache('_page')->getBackend()
                                                                        )
                                                                    )
                                                                )
            );

            if ($this->hasPluginResource('db')) {
                $this->bootstrap('db');
                $db = $this->getPluginResource('db')->getDbAdapter();
                $options['plugins']['Database']['adapter'] = $db;
            }

            $debug = new Centurion_ZFDebug_Controller_Plugin_Debug($options);
            $frontController->registerPlugin($debug);
        }
    }

    protected function _initCacheView()
    {
        Centurion_View::setDefaultCache($this->_getCache('view'));
    }

    protected function _initCachePage()
    {
        $this->bootstrap('FrontController');
        if ($this->getResource('FrontController')->getParam('displayExceptions') == false) {
            $this->bootstrap('contrib')
                 ->bootstrap('dbtable')
                 ->bootstrap('modules');
            $this->bootstrap('translate');

            $translator = $this->getResource('translate');

            $regexps = array(

               );

            $this->bootstrap('cachemanager');
            $cache = $this->getResource('cachemanager')->getCache('_page');

            $cache->setRegexps($regexps);
            $cache->start();
        }
    }
    
 
		protected function _initViewHelpers()
		{
		//$view = $this->getPluginResource('view')->getView();*
		//on ajouter le helper d'action
		Zend_Controller_Action_HelperBroker::addPrefix('Portal_Controller_Action_Helper');
		
		
			
		//Ajout pour ZendX
		$this->getApplication()->bootstrap('view');
		//$this->bootstrap ( 'view' );
		
		$view = $this->getResource ( 'view' );
		$view->addHelperPath ( 'ZendX/JQuery/View/Helper', 'ZendX_JQuery_View_Helper');
		$view->addHelperPath ( 'Portal/View/Helper','Portal_View_Helper');
		
		$view->headLink(array('rel' => 'shortcut icon',
			                                 'href' => '/layouts/frontoffice/images/favicon.ico'),
					                         'PREPEND');
		
	    //... paramètres optionnels pour les helpeurs jQuery ....
	    // pas l'impression que cela soit pris en charge ... 
	    // Tout ceci est répété dans le layout ... 
	    //TODO prendre le temps de remettre cette partie d'équerre pour être plus propre !
	    $view->jQuery()//->setLocalPath('/public/cui/jquery.CUI2.js')
	    				->setLocalPath('/layouts/frontoffice/js/jquery-1.11.1.js')
	   					//->setLocalPath('/layouts/backoffice/js/all.js') //test
	    				//->setLocalPath('/public/cui/jquery.CUI.js') //test
	    				->setUILocalPath('/layouts/frontoffice/js/jquery-ui-1.9.1.custom.min.js')
	    				//->addStyleSheet('/layouts/frontoffice/css/jquery-ui.css')
	    				->addStyleSheet('/layouts/frontoffice/css/smoothness/jquery-ui-1.10.4.custom.min.css')
					    ->addStyleSheet('/layouts/frontoffice/styles.css');//test
					  //  ->addStyleSheet('/public/layouts/frontoffice/css/smoothness/jquery-ui-1.10.4.custom.min.css');//test
			    
	    
	    
		$viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer ( );
		$viewRenderer->setView ( $view );
		//var_dump($view->getHelperPaths());
		Zend_Controller_Action_HelperBroker::addHelper ( $viewRenderer );
		//on active Jquery
		
		$view->jQuery()->enable()->uiEnable();
		//ZendX_JQuery_View_Helper_JQuery::enableNoConflictMode();
		
		
	}
	

		
	
}
