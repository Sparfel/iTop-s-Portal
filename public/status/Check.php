<?php

class Check {

	protected $_checklist = array();

	protected $_phpVersion = null;
	protected $_apacheVersion = null;
	protected $_hasZend = null;
	protected $_hasCenturion = null;
	protected $_hasPortal = null;

	protected $_hasApplicationEnv = null;
	protected $_currentEnv = null;

	protected $_WebserviceCanBeTested = null;

	/*configuration Setup*/
	protected $_config_application_file_default    = "application.default";
	protected $_config_db_file_default    = "db.default";
	protected $_config_file_default_directory = "./ini/";
	protected $_config_file_directory  = "../../application/configs/";

	protected $_config_application_file_name  = "application.ini";
	protected $_config_db_file_name = "db.ini";

	protected $_config_application_file_path  = null;
	public $_config_db_file_path = null;

	protected $_config_default_application_file_path  = null;
	protected $_config_default_db_file_path  = null;

	public $_database_host = null;
	public $_database_name = null;
	public $_database_username = null;
	public $_database_password = null;

	public $_sql_file = '_itop_portal_.sql';
	
	private $APerm = Array();
	private $_OS = 'linux'; //or 'windows'  
	

	/**
	 * @var Zend_Application_Resource_Db
	 */
	protected $_dbRessource = null;
	//Interesting to Drop Table befor import ? case
	protected $_DbTable_drop = false;

	public function __construct()
	{
		//initialization for configuration files
		// We will copy the file *.default into the file *.ini and modify the content with the right values.
		$this->_config_application_file_path       = $this->_config_file_directory . $this->_config_application_file_name;
		$this->_config_db_file_path       = $this->_config_file_directory . $this->_config_db_file_name;

		$this->_config_default_application_file_path = $this->_config_file_default_directory . $this->_config_application_file_default;
		$this->_config_default_db_file_path = $this->_config_file_default_directory . $this->_config_db_file_default;

		$this->_DbTable_drop = false;

	}


	protected function _checkPhp()
	{
		$this->_phpVersion = PHP_VERSION;

		//TODO: check true of false
		if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
			$this->_checklist[] = array(
					'code' => 1,
					'canBeBetter' => false,
					'isNotSecure' => false,
					'text' => 'PHP version is <strong>' . PHP_VERSION . '</strong>',
					'alt' => '',
			);
		} else if (version_compare(PHP_VERSION, '5.2.6') >= 0) {
			$this->_checklist[] = array(
					'code' => 1,
					'canBeBetter' => true,
					'isNotSecure' => false,
					'text' => 'PHP version is <strong>' . PHP_VERSION . '</strong>',
					'alt' => '5.3.0 could be better',
			);
		} else {
			$this->_checklist[] = array(
					'code' => 0,
					'canBeBetter' => true,
					'isNotSecure' => true,
					'text' => 'PHP version is <strong>' . PHP_VERSION . '</strong>',
					'alt' => '',
			);
		}

		//TODO: check time limit
		//TODO: check memory limit
	}

	protected function _checkApache()
	{

		$this->_apacheVersion = $_SERVER['SERVER_SOFTWARE'];

		if ($this->_apacheVersion == 'Apache') {
			$this->_checklist[] = array(
					'code' => 1,
					'canBeBetter' => true,
					'isNotSecure' => false,
					'text' => 'Apache version is <strong>unknown</strong>!',
					'alt' => 'Apache version is <strong>unknown</strong>. Please verify manually that your are above 2.0',
			);
		} else {
			if (false !== ($pose = strpos($this->_apacheVersion, ' '))) {
				$this->_apacheVersion = substr($this->_apacheVersion, 0, $pose);
			}

			if (false !== ($pose = strpos($this->_apacheVersion, '/'))) {
				$this->_apacheVersion = substr($this->_apacheVersion, $pose + 1);
			}

			if (version_compare($this->_apacheVersion, '2.') >= 0) {
				$this->_checklist[] = array(
						'code' => 1,
						'canBeBetter' => false,
						'isNotSecure' => false,
						'text' => 'Apache version is <strong>'  . $this->_apacheVersion . '</strong>',
						'alt' => '',
				);
			} else {
				$this->_checklist[] = array(
						'code' => 0,
						'canBeBetter' => true,
						'isNotSecure' => false,
						'text' => 'Apache version is <strong>'  . $this->_apacheVersion . '</strong>',
						'alt' => '',
				);
			}
		}
	}

	protected function _checkLibraryZend()
	{
		$this->_hasZend = file_exists(__DIR__ . '/../../library/Zend');

		if ($this->_hasZend) {
			include_once __DIR__ . '/../../library/Zend/Version.php';
			$zendVersion = Zend_Version::VERSION;

			$this->_checklist[] = array(
					'code' => 1,
					'canBeBetter' => false,
					'isNotSecure' => false,
					'text' => 'Zend version is <strong>'  .$zendVersion . '</strong>',
					'alt' => '',
			);
		} else {
			$this->_checklist[] = array(
					'code' => -1,
					'canBeBetter' => true,
					'isNotSecure' => false,
					'text' => 'Zend was not found',
					'alt' => 'Have you forget to do a "git submodule init" ?',
			);
		}

		//TODO: check true of false
	}

	protected function _checkLibraryCenturion()
	{
		$this->_hasCenturion = file_exists(__DIR__ . '/../../library/Centurion');

		if ($this->_hasCenturion) {
			include_once __DIR__ . '/../../library/Centurion/Version.php';
			$centurionVersion = Centurion_Version::VERSION;

			$this->_checklist[] = array(
					'code' => 1,
					'canBeBetter' => false,
					'isNotSecure' => false,
					'text' => 'Centurion version is <strong>'  .$centurionVersion . '</strong>',
					'alt' => '',
			);
		} else {
			$this->_checklist[] = array(
					'code' => -1,
					'canBeBetter' => true,
					'isNotSecure' => false,
					'text' => 'Centurion library was not found',
					'alt' => 'Have you forget to do a "git submodule init" ?',
			);
		}

		//TODO: check true of false
	}

	protected function _checkLibraryPortal()
	{
		$this->_hasPortal = file_exists(__DIR__ . '/../../library/Portal');

		if ($this->_hasPortal) {
			include_once __DIR__ . '/../../library/Portal/Version.php';
			$portalVersion = Portal_Version::VERSION;

			$this->_checklist[] = array(
					'code' => 1,
					'canBeBetter' => false,
					'isNotSecure' => false,
					'text' => 'Portal version is <strong>'  .$portalVersion . '</strong>',
					'alt' => '',
			);
		} else {
			$this->_checklist[] = array(
					'code' => -1,
					'canBeBetter' => true,
					'isNotSecure' => false,
					'text' => 'Portal library was not found',
					'alt' => 'Have you forget to do a "git submodule init" ?',
			);
		}

		//TODO: check true of false
	}



	public function genDbFile($options) {
		$database_host = $options['database_host'];
		$database_name = $options['database_name'];
		$database_username = $options['database_username'];
		$database_password = $options['database_password'];
		//echo $database_name.'<br>'.$database_username.'<br>'.$database_password.'<br>';
		copy($this->_config_default_db_file_path, $this->_config_db_file_path);
		 
		$config_file = file_get_contents($this->_config_db_file_path);
		$config_file = str_replace("_DB_HOST_", $database_host, $config_file);
		$config_file = str_replace("_DB_NAME_", $database_name, $config_file);
		$config_file = str_replace("_DB_USER_", $database_username, $config_file);
		$config_file = str_replace("_DB_PASSWORD_", $database_password, $config_file);
		 
		$f = @fopen($this->_config_db_file_path, "w+");
		//print_r($config_file);
		if(@fwrite($f, $config_file) > 0){
			//echo 'Completed !';
			$completed = true;
		}
	}

	public function genApplicationFile($options) {
		$webservice_protocol = $options['webservice_protocol'];
		$webservice_adress = $options['webservice_adress'];
		$webservice_username = $options['webservice_username'];
		$webservice_password = $options['webservice_password'];
		 
		copy($this->_config_default_application_file_path,$this->_config_application_file_path);
		 
		$config_file = file_get_contents($this->_config_application_file_path);
		$config_file = str_replace("_WS_PROTOCOL_", $webservice_protocol, $config_file);
		$config_file = str_replace("_WS_ADRESS_", $webservice_adress, $config_file);
		$config_file = str_replace("_WS_USER_", $webservice_username, $config_file);
		$config_file = str_replace("_WS_PASSWORD_", $webservice_password, $config_file);
		 
		$f = @fopen($this->_config_application_file_path, "w+");
		if(@fwrite($f, $config_file) > 0){
			 
			$completed = true;
		}
	}


	public function getDbParameters(){
		$options = array();
		if (file_exists($this->_config_db_file_path)) {

			include_once __DIR__ . '/../../library/Centurion/Config/Directory.php';
			include_once __DIR__ . '/../../library/Centurion/Iterator/Directory.php';
			include_once __DIR__ . '/../../library/Zend/Config/Ini.php';
			$config = Centurion_Config_Directory::loadConfig(__DIR__ . '/../../application/configs', $this->_currentEnv);
			$options['database_host'] = $config['resources']['db']['params']['host'];
			$options['database_name'] = $config['resources']['db']['params']['dbname'];
			$options['database_username'] =  $config['resources']['db']['params']['username'];
			$options['database_password'] =  $config['resources']['db']['params']['password'];
		}
		else
		{
			$options['database_host'] = 'localhost';
			$options['database_name'] = '';
			$options['database_username'] =  '';
			$options['database_password'] = '';
		}
		return $options;
	}


	public function getApplicationParameters(){
		$options = array();
		if (file_exists($this->_config_application_file_path)) {

			include_once __DIR__ . '/../../library/Centurion/Config/Directory.php';
			include_once __DIR__ . '/../../library/Centurion/Iterator/Directory.php';
			include_once __DIR__ . '/../../library/Zend/Config/Ini.php';
			$config = Centurion_Config_Directory::loadConfig(__DIR__ . '/../../application/configs', $this->_currentEnv);
			$options['webservice_protocol'] = $config['itop1']['url']['protocol'];
			$options['webservice_adress'] = $config['itop1']['url']['adress'];
			$options['webservice_username'] = $config['itop1']['webservice']['user'];
			$options['webservice_password'] = $config['itop1']['webservice']['pwd'];
		}
		else
		{
			/* dafault parameters for connection with standard iTop Demo
			 $options['webservice_protocol'] = 'https';
			$options['webservice_adress'] = 'demo.combodo.com/simple';
			$options['webservice_username'] = 'admin-fr';
			$options['webservice_password'] = 'admin';
			*/
			$options['webservice_protocol'] = 'http';
			$options['webservice_adress'] = 'services.sydel.fr/itop-test-web';
			$options['webservice_username'] = 'admin';
			$options['webservice_password'] = 'admin1234';
		}
		return $options;
	}

	protected function _checkPhpExtensions()
	{
		$extensions = get_loaded_extensions();
		// TODO:
		// Verify if curl is present.
		$tst_extension = 'curl';
		if (in_array($tst_extension,$extensions)){
			$this->_checklist[] = array(
					'code' => 1,
					'canBeBetter' => false,
					'isNotSecure' => false,
					'text' => 'Extension <strong>' . $tst_extension . '</strong> is installed.',
					'alt' => 'This extension is used to access to the iTop\'s Webservices',
			);
			$this->_WebserviceCanBeTested = true;
		}
		else {
			$this->_checklist[] = array(
					'code' => -1,
					'canBeBetter' => true,
					'isNotSecure' => true,
					'text' => 'Extension <strong>' . $tst_extension . '</strong> is missing. Please install this extension on your Php server.',
					'alt' => 'To install this extension on a Linux Server, you can try the command:<br /><b>apt-get install php5-curl</b>',
			);
		}
	}

	protected function _checkHtaccess()
	{
		$hasHtaccess = file_exists(__DIR__ . '/.htaccess');
		//TODO:
	}

	protected function _checkApplicationEnv()
	{
		if (defined('APPLICATION_ENV')) {
			$this->_hasApplicationEnv = true;
			$this->_currentEnv = APPLICATION_ENV;
		} else if (getenv('APPLICATION_ENV') != false) {
			$this->_hasApplicationEnv = true;
			$this->_currentEnv = getenv('APPLICATION_ENV');
		} else {
			$this->_hasApplicationEnv = false;
			$this->_currentEnv = 'production';
		}

		if ($this->_currentEnv == 'production') {
			$this->_checklist[] = array(
					'code' => 1,
					'canBeBetter' => true,
					'isNotSecure' => false,
					'text' => 'Current APPLICATION_ENV is <strong>' . $this->_currentEnv . '</strong>',
					'alt' => 'Warning: with this env, you will not see error (it\'s a no debug mode).<br />Try <b>development</b> instead.',
			);
		} else {
			$this->_checklist[] = array(
					'code' => 1,
					'canBeBetter' => false,
					'isNotSecure' => false,
					'text' => 'Current APPLICATION_ENV is <strong>' . $this->_currentEnv . '</strong>',
					'alt' => '',
			);
		}
	}

	protected function _checkDbConnect()
	{
		if (!$this->_hasCenturion || !$this->_hasZend) {
			$this->_checklist[] = array(
					'code' => -1,
					'canBeBetter' => true,
					'isNotSecure' => false,
					'text' => 'Database could not be checked (no Centurion or Zend)',
					'alt' => '',
			);
		}
		else if ((!file_exists($this->_config_db_file_path))) {
			$this->_checklist[] = array(
					'code' => -1,
					'canBeBetter' => true,
					'isNotSecure' => true,
					'text' => 'No Credentials for MySQL Database are set',
					'alt' => 'File <strong>db.ini</strong> is missing.',
			);
		}
		else {
			include_once __DIR__ . '/../../library/Centurion/Config/Directory.php';
			include_once __DIR__ . '/../../library/Centurion/Iterator/Directory.php';
			include_once __DIR__ . '/../../library/Zend/Config/Ini.php';

			$config = Centurion_Config_Directory::loadConfig(__DIR__ . '/../../application/configs', $this->_currentEnv);
			//Zend_Debug::dump($config);
			include_once __DIR__ . '/../../library/Zend/Application/Resource/Db.php';
			include_once __DIR__ . '/../../library/Zend/Db.php';
			$this->_dbRessource = new Zend_Application_Resource_Db();
			$this->_dbRessource->setParams($config['resources']['db']['params']);
			$this->_dbRessource->setAdapter($config['resources']['db']['adapter']);


			try {
				$bddVersion = $this->_dbRessource->getDbAdapter()->getServerVersion();
				//Zend_Debug::dump($bddVersion);
				if (version_compare(PHP_VERSION, '5.1') >= 0) {
					$this->_checklist[] = array(
							'code' => 1,
							'canBeBetter' => false,
							'isNotSecure' => false,
							'text' => 'Connection to Mysql, version <strong>' . $bddVersion . '</strong>',
							'alt' => '',
					);
				} else {
					$this->_checklist[] = array(
							'code' => -1,
							'canBeBetter' => true,
							'isNotSecure' => true,
							'text' => 'Connection to Mysql, version <strong>' . $bddVersion . '</strong>',
							'alt' => '',
					);
				}
			} catch (Exception $e) {
				$this->_dbRessource = null;
				if ($e->getCode() == 1049) {
					$this->_checklist[] = array(
							'code' => -1,
							'canBeBetter' => true,
							'isNotSecure' => true,
							'text' => 'BDD  ' . $config['resources']['db']['params']['dbname'] . ' does not exists',
							'alt' => '',
					);

				}
				if ($e->getCode() == 1044) {
					$this->_checklist[] = array(
							'code' => -1,
							'canBeBetter' => true,
							'isNotSecure' => true,
							'text' => 'Access denied for user <strong>' . $config['resources']['db']['params']['username'] . '</strong> to database <strong>'.$config['resources']['db']['params']['dbname'].'</strong>',
							'alt' => 'User must have access to the database',
					);
				}
				if ($e->getCode() == 1045) {
					$this->_checklist[] = array(
							'code' => -1,
							'canBeBetter' => true,
							'isNotSecure' => true,
							'text' => 'Your mysql credential is not valid.',
							'alt' => 'Change it in application/db.ini',
					);
				} else {
					null;
					//throw $e;
				}
			}
		}
	}

	protected function _checkItopWebservice($noItop)
	{
		if ($this->_WebserviceCanBeTested && (file_exists($this->_config_application_file_path)))  {
			 
			$config = Centurion_Config_Directory::loadConfig(__DIR__ . '/../../application/configs', $this->_currentEnv);
			// We can confiugure 2 iTop : production and Test
			// Primary iTop will be for Production
			// Secondary will be for Test
			// We can switsch user's access with the field iS_Staff for each user.
			$protocol =$config['itop'.$noItop]['url']['protocol'];
			$adress = $config['itop'.$noItop]['url']['adress'];
			$username = $config['itop'.$noItop]['webservice']['user'];
			$password = $config['itop'.$noItop]['webservice']['pwd'];
			$url = $protocol.'://'.$adress.'/webservices/rest.php?version=1.0';

			$aData = array('operation' => 'list_operations');

			$aPostData = array(
					'auth_user' => $username,
					'auth_pwd' => $password,
					'json_data' => json_encode($aData),
			);
			 
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($aPostData));
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

			curl_setopt($curl, CURLOPT_VERBOSE, true);

			$sResult = curl_exec($curl);
			$aResult = @json_decode($sResult, true /* bAssoc */);
			 
			if ($aResult == null)
			{
				$aResult = null;
				$this->_checklist[] = array(
						'code' => -1,
						'canBeBetter' => true,
						'isNotSecure' => false,
						'text' => 'Error: the return value from the web service could not be decoded.',
						'alt' => 'Result : '.$sResult,
				);
			}
			else
			{
				if ($protocol == 'https') {
					$canBeBetter = false;
					$text = 'iTop\'s Webservices <strong>n°'.$noItop.'</strong> access.';
					$alt = $url;
				}
				else {
					$canBeBetter = true;
					$text = 'iTop\'s Webservices <strong>n°'.$noItop.'</strong> access but the protocol is unsecure.';
					$alt = 'Prefer the protocol <strong>HTTPS</strong>, url = '.$url;
				};
				if ($aResult['code'] == 0) {
					$this->_checklist[] = array(
							'code' => 1,
							'canBeBetter' => $canBeBetter,
							'isNotSecure' => false,
							'text' => $text,
							'alt' => $alt,
					);
				}
			}
		}
		else {
			if (file_exists($this->_config_db_file_path))
			{
				$text = 'Error, it\'s not possible to test the Webservice <strong>n°'.$noItop.'</strong>.';
				$alt =  'Verify if <strong>curl</strong> Php extension is installed.';
			}
			else {
				$text = 'No Credentials for iTop\'s Webservice are set';
				$alt =  'File <strong>application.ini</strong> is missing.';
			}
			$this->_checklist[] = array(
					'code' => -1,
					'canBeBetter' => true,
					'isNotSecure' => true,
					'text' => $text,
					'alt' => $alt,
			);
		}
	}

	protected function _checkDbTable()
	{
		if (null !== $this->_dbRessource) {
			$tablesToCheck = array();
			$tablesNotFound = array();

			$tablesToCheck[] = 'auth_belong';
			$tablesToCheck[] = 'auth_group';
			$tablesToCheck[] = 'auth_group_permission';
			$tablesToCheck[] = 'auth_permission';
			$tablesToCheck[] = 'auth_user';
			$tablesToCheck[] = 'auth_user_permission';


			$tablesToCheck[] = 'centurion_content_type';
			$tablesToCheck[] = 'centurion_navigation';
			$tablesToCheck[] = 'centurion_site';

			$tablesToCheck[] = 'cms_flatpage';
			$tablesToCheck[] = 'cms_flatpage_template';

			$tablesToCheck[] = 'media_duplicate';
			$tablesToCheck[] = 'media_file';
			$tablesToCheck[] = 'media_image';
			$tablesToCheck[] = 'media_multiupload_ticket';
			$tablesToCheck[] = 'media_video';

			$tablesToCheck[] = 'translation_language';
			$tablesToCheck[] = 'translation_tag';
			$tablesToCheck[] = 'translation_tag_uid';
			$tablesToCheck[] = 'translation_translation';
			$tablesToCheck[] = 'translation_uid';

			$tablesToCheck[] = 'user_profile';

			$tablesToCheck[] = 'portal_itop_user';
			$tablesToCheck[] = 'portal_ldap_user';
			$tablesToCheck[] = 'portal_chat_questions';
			$tablesToCheck[] = 'portal_service_config';
			$tablesToCheck[] = 'portal_service_style';
			$tablesToCheck[] = 'portal_user_preference';

			$tablesToCheck[] = 'cron_task';

			foreach ($tablesToCheck as $tableName) {
				try {
					$this->_dbRessource->getDbAdapter()->describeTable($tableName);
				} catch (Exception $e) {
					if ($e->getCode() == '42') {
						$tablesNotFound[] = $tableName;
					} else {
						// throw $e;
					}
				}
			}

			if (count($tablesNotFound) > 0) {
				if (count($tablesNotFound) == count($tablesToCheck)) {
					//TODO: zf db install
					$this->_checklist[] = array(
							'code' => -1,
							'canBeBetter' => true,
							'isNotSecure' => false,
							'text' => 'All table are missing.',
							'alt' => 'Have you forget a "zf db install" ?',
					);
					$this->_DbTable_drop = false;
				} else {
					$this->_checklist[] = array(
							'code' => 0,
							'canBeBetter' => true,
							'isNotSecure' => false,
							'text' => 'Some table are missing',
							'alt' => 'Some table are missing: <br /> - ' . implode('<br />- ', $tablesNotFound),
					);
					$this->_DbTable_drop = 'true';
				}
			}
			else
			{
				$this->_checklist[] = array(
						'code' => 1,
						'canBeBetter' => false,
						'isNotSecure' => false,
						'text' => 'All tables are present',
						'alt' => count($tablesToCheck).' tables are into the Database',
				);
				$this->_DbTable_drop = true;
			}

		} else {
			$this->_checklist[] = array(
					'code' => -1,
					'canBeBetter' => true,
					'isNotSecure' => true,
					'text' => 'Can\'t check table; no connection to bdd',
					'alt' => '',
			);
			$this->_DbTable_drop = false;
		}
	}

	public function canDropTable(){
		return  $this->_DbTable_drop;
	}

	protected function _checkPermission()
	{
		$dirs = array(
				'/data/',
				'/data/indexes/',
				'/data/locales/',
				'/data/logs/',
				'/data/sessions/',
				'/data/temp/',
				'/data/uploads/',
				'/data/cache/',
				'/data/cache/class',
				'/data/cache/core',
				'/data/cache/output',
				'/data/cache/page',
				'/data/cache/tags',
				'/public/files',
				'/public/cached',
				'/public/status',
				'/public/index.php',
		);

		$notWritable = array();
		$prefixDir = realpath(dirname(__FILE__) . '/../..');

		foreach ($dirs as $dir) {
			$fullPath = $prefixDir . $dir;

			if (!is_writable($fullPath)) {
				$notWritable[] = $dir;
			}
		}

		if (count($notWritable) > 0) {
			$this->_checklist[] = array(
					'code' => -1,
					'canBeBetter' => true,
					'isNotSecure' => true,
					'text' => 'Some of your file system are not writable',
					'alt' => 'Full list: <br /> - ' . implode('<br />- ', $notWritable),
			);
		}
	}

	public function _checkRedirect()
	{
		$url = 'http://' . $_SERVER['SERVER_NAME'];
		if ($_SERVER['SERVER_PORT'] !== 80) {
			$url .= ':' . $_SERVER['SERVER_PORT'];
		}

		if (isset($_GET['step']) ) {
			$uri = str_replace('index.php', '',$_SERVER['REQUEST_URI']);
			$url .= str_replace('/status/?step='.$_GET['step'], '/test_redirect/', $uri);
		}
		else {
			//usefull when called by chkCfg.php
			$uri = str_replace('chkCfg.php', '',$_SERVER['REQUEST_URI']);
			//$url .= str_replace('/status', '/test_redirect', $_SERVER['REQUEST_URI']);
			$url .= str_replace('/status', '/test_redirect', $uri);
		}
		$url .= '?step=-1';
		 
		//Zend_Debug::dump($url);
		$fp = @file_get_contents(urlencode($url));
		//Zend_Debug::dump(@file_get_contents($url));
		if ($fp === 'Mod_Rewrite works!') {
			$this->_checklist[] = array(
					'code' => 1,
					'canBeBetter' => false,
					'isNotSecure' => true,
					'text' => 'The rewrite works',
					'alt'  => '',
			);
		} else {
			$this->_checklist[] = array(
					'code' => 1,
					'canBeBetter' => true,
					'isNotSecure' => true,
					'text' => 'Your mod_rewrite seems to not worked. <a href="' . $url . '" target="_blank">Click here</a> to check',
					'alt' => 'Click on the link above. If it\'s not worked check mod_rewrite is enabled, or that the directive AllowOverride All is set to the application root.',
			);
		}
	}

	protected function _checkDocumentRoot()
	{
		if (!preg_match('`(/|\\\)public(/|\\\)?$`', $_SERVER['DOCUMENT_ROOT'])) {
			$this->_checklist[] = array(
					'code' => -1,
					'canBeBetter' => true,
					'isNotSecure' => true,
					'text' => 'Your DOCUMENT_ROOT is not correctly set',
					'alt' => 'Your DOCUMENT_ROOT must be set to point to the "public" folder.',
			);
		} else {
			$this->_checklist[] = array(
					'code' => 1,
					'canBeBetter' => false,
					'isNotSecure' => false,
					'text' => 'Your DOCUMENT_ROOT is correctly set',
					'alt' => '',
			);
		}
	}

	public function check() {

		set_include_path(implode(PATH_SEPARATOR, array(
		realpath(__DIR__ . '/../../library/'),
		get_include_path(),
		)));

		$this->_checkPhp();
		$this->_checkApache();
		$this->_checkLibraryZend();
		$this->_checkLibraryCenturion();
		$this->_checkLibraryPortal();

		if ($this->_hasCenturion || $this->_hasZend) {
			require_once 'Zend/Loader/Autoloader.php';
			$autoloader = Zend_Loader_Autoloader::getInstance()
			->setDefaultAutoloader(create_function('$class',
					"include str_replace('_', '/', \$class) . '.php';"
			));
		}

		$this->_checkPhpExtensions();
		$this->_checkHtaccess();
		$this->_checkApplicationEnv();

		$this->_checkRedirect();
		 
		$this->_checkDocumentRoot();
		$this->_checkPermission();
	}


	/* Check according to the step*/
	public function checkCfg($step) {

		 
		 
		//if ($step >1)
		{
			$this->_checkDbConnect();
			$this->_checkDbTable();
		}

		//if ($step >2)
		{
			$this->_checkItopWebservice(1);
			$this->_checkItopWebservice(2);
		}
	}

	
	
	public function hasError()
	{
		foreach ($this->_checklist as $data) {
			if ($data['code'] != '1') {
				return true;
			}
		}

		return false;
	}
	public function getCheckList()
	{
		return $this->_checklist;
	}
	
	
	/*Manage Permission*/
	/* Check Permission for folders and files, the same than './zf.sh check install' in bin folder */
	public function checkPerm(){
		$os = $this->_OS;
		defined('APPLICATION_PATH')
		|| define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../../../../../application'));
	
		
		if ($os == 'linux') {
			$apacheGroup = 'www-data';
			$groupNames = array(
					'www-data',
					'apache2',
					'apache',
					'daemon'
				);
	
			if (function_exists('posix_getgrnam')) {
				foreach ($groupNames as $group) {
					if (false !== posix_getgrnam($group)) {
						$apacheGroup = $group;
						break;
					}
				}
			}
	
			/*if (null === $apacheGroup) {
				echo 'Please fill apache group : ' . "\n";
				$apacheGroup = trim(fgets(STDIN));
			}*/
		}
	
		/**
		 * List of file/folder to check :
		 * Warning :
		 * 1 - path of folder have to finish with "/"
		 * 2 - we can use * to manage all file into a folder
		 *
		 * @var array(string)
		 */
		$pathFiles = array(
				'/data/',
				'/data/indexes/',
				'/data/locales/',
				'/data/logs/',
				'/data/sessions/',
				'/data/temp/',
				'/data/uploads/',
				'/data/cache/',
				'/data/cache/config/',
				'/data/cache/class/',
				'/data/cache/core/',
				'/data/cache/output/',
				'/data/cache/page/',
				'/data/cache/tags/',
				'/public/status/*',
				'/public/index.php',
				'/public/index.php_next',
				'/public/files/',
				'/public/cached/',
				'/public/status/',
				//'/public/status',
		);
	
	
		foreach ($pathFiles as $pathFile) {
			$nodes = explode("/", $pathFile);
			$leaf = array_pop($nodes);
			$dir = APPLICATION_PATH . '/..' .implode("/", $nodes);
			// if we are on a folder path and the folder does'nt exist
			if( ""===$leaf && !file_exists($dir) ){
				array_push($this->APerm, '2.1 Directory ' . $dir . ' does not exists.' . "\n");
				if (is_writable(dirname($dir))) {
					if (!mkdir($dir, 0775)) {
						array_push($this->APerm, '2.1.1 Can\'t create the directory' . "\n");
					} else {
						array_push($this->APerm, '2.1.1 => I fixed it by creating directory' . "\n");
					}
				} else {
					array_push($this->APerm, '2.1.1 Can\'t fix it because i don\'t have write access in parent dir' . "\n");
				}
				// if we are on a folder path and the folder exists
			}elseif ( ""===$leaf &&  is_dir($dir) ){
				if (!is_writable($dir)) {
					array_push($this->APerm, '2.2 Directory ' . $dir . ' is not writable.' . "\n");
				}
				if ($os === 'linux') {
					if (function_exists('posix_getgrgid')) {
						$groupInfo = posix_getgrgid(filegroup($dir));
	
						if ($groupInfo['name'] !== $apacheGroup) {
							array_push($this->APerm, '2.2.1 Group of directory ' . $dir . ' is not apache\'s group' . "\n");
	
							if (chgrp($dir, $apacheGroup)) {
								array_push($this->APerm, '2.2.1.1 => I fixed it, now it\'s : ' . $apacheGroup . "\n\n");
							} else {
								array_push($this->APerm, '2.2.1.2 => Can\'t fix it. Don\'t have permission to make a chown.' . "\n\n");
							}
						}
					}
					$perms = fileperms($dir);
					// fix specific Centurion's perms for file
					$this->_osLinuxFixPerms($dir, $perms, '2.2.2');
				}
				// if we are on an existing folder and and we want to manage all file into folder
			}elseif("*"==$leaf && file_exists($dir) && is_dir($dir) ){
				if ($dh = opendir($dir)) {
					while (($file = readdir($dh)) !== false) {
						$file = $dir . '/' . $file;
						if ($os === 'linux') {
							if (function_exists('posix_getgrgid')) {
								$groupInfo = posix_getgrgid(filegroup($file));
	
								if ($groupInfo['name'] !== $apacheGroup) {
									array_push($this->APerm, '2.3.1 Group of file ' . $file . ' is not apache\'s group' . "\n");
	
									if (chgrp($file, $apacheGroup)) {
										array_push($this->APerm, '2.3.1.1 => I fixed it, now it\'s : ' . $apacheGroup . "\n\n");
									} else {
										array_push($this->APerm, '2.3.1.2 => Can\'t fix it. Don\'t have permission to make a chown.' . "\n\n");
									}
								}
							}
							$perms = fileperms($file);
							// fix specific Centurion's perms for file
							$this->_osLinuxFixPerms($file, $perms, '2.3.2');
						}
					}
					closedir($dh);
				}
				// if we are on an existing file
			}elseif(file_exists($dir . '/' . $leaf) && is_dir($dir) && is_file($dir . '/' . $leaf)  ){
				$file = $dir . '/' . $leaf;
				if ($os === 'linux') {
					if (function_exists('posix_getgrgid')) {
						$groupInfo = posix_getgrgid(filegroup($file));
	
						if ($groupInfo['name'] !== $apacheGroup) {
							array_push($this->APerm, '2.4.1 Group of file ' . $file . ' is not apache\'s group' . "\n");
	
							if (chgrp($file, $apacheGroup)) {
								array_push($this->APerm, '2.4.1.1 => I fixed it, now it\'s : ' . $apacheGroup . "\n\n");
							} else {
								array_push($this->APerm, '2.4.1.2 => Can\'t fix it. Don\'t have permission to make a chown.' . "\n\n");
							}
						}
					}
					$perms = fileperms($file);
	
					// fix specific Centurion's perms for file
					$this->_osLinuxFixPerms($file, $perms, '2.4.2');
				}
				// if the path in conf is not valid path.
			}else {
				array_push($this->APerm, '2.5 => Can\'t manage path : '.$pathFile. "\n\n");
			}
		}
	
		
	}
	
	
	/**
	 *
	 * Enter description here ...
	 * @param unknown_type $dir
	 * @param unknown_type $perms
	 */
	private function _osLinuxFixPerms($path, $perms, $index='2.x.x'){
	
		if (!($perms & 0x0020)) {
			array_push($this->APerm, $index.'.1 FATAL apache can\'t read in ' . $path . "\n");
	
			$perms = $perms | 0x0020;
	
			if (chmod($path, $perms)) {
				array_push($this->APerm, $index.'.1.1 => I fixed it' . "\n");
			} else {
				array_push($this->APerm, $index.'.1.2 => I can\'t fixed it' . "\n");
			}
		}
		if (!($perms & 0x0010)) {
			array_push($this->APerm, $index.'.2 FATAL apache can\'t write in ' . $path . "\n");
	
			$perms = $perms | 0x0010;
	
			if (chmod($path, $perms)) {
				array_push($this->APerm, $index.'.2.1 => I fixed it' . "\n");
			} else {
				array_push($this->APerm, $index.'.2.2 => I can\'t fixed it' . "\n");
			}
		}
		if (!(($perms & 0x0008))) {
			array_push($this->APerm, $index.'.3 FATAL apache can\'t execute in ' . $path . "\n");
	
			$perms = $perms | 0x0008;
	
			if (chmod($path, $perms)) {
				array_push($this->APerm, $index.'.3.1 => I fixed it' . "\n");
			} else {
				array_push($this->APerm, $index.'.3.2 => I can\'t fixed it' . "\n");
			}
		}
	
		if ($perms & 0x0004) {
			array_push($this->APerm, $index.'.4 Warning other can read in ' . $path . "\n");
	
			$perms = $perms & ~0x0004;
	
			if (chmod($path, $perms)) {
				array_push($this->APerm, $index.'.4.1 => I fixed it' . "\n");
			} else {
				array_push($this->APerm, $index.'.4.2 => I can\'t fixed it' . "\n");
			}
		}
		if ($perms & 0x0002) {
			array_push($this->APerm, $index.'.5 Warning other can write in ' . $path . "\n");
	
			$perms = $perms & ~0x0002;
	
			if (chmod($path, $perms)) {
				array_push($this->APerm, $index.'.5.1 => I fixed it' . "\n");
			} else {
				array_push($this->APerm, $index.'.5.2 => I can\'t fixed it' . "\n");
			}
		}
	
		if (($perms & 0x0001)) {
			array_push($this->APerm, $index.'.6 FATAL other can execute in ' . $path . "\n");
	
			$perms = $perms & ~0x0001;
	
			if (chmod($path, $perms)) {
				array_push($this->APerm, $index.'.6.1 => I fixed it' . "\n");
			} else {
				array_push($this->APerm, $index.'.6.2 => I can\'t fixed it' . "\n");
			}
		}
	}
	

	public function getPerm(){
		return $this->APerm;
	}
	
	
	
}
