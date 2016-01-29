<?php 

Class Portal_Cron_Cron {
	
	protected $_connected = false;
	
	public function __construct() {
		
	}
	
	public function connexion() {
		//Connexion.
		$config = new  Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', 'production');
		//=> Si Ldap, pas besoin de password, on considère que le Ldap a déjà répondu oui
		$useLdapSyleps = $config->ldap->server1->use;
		// Login / pwd pour Crontab
		$login  = $config->cron->login;
		$password = $config->cron->pwd;
		// salt
		$saltingMechanism = $config->ticket->salt;
		
		$dbAdapter = Zend_Db_Table_Abstract::getDefaultAdapter();
		$adapter = new Portal_Auth_Adapter_DbTable($dbAdapter,
				'auth_user',
				'username',
				'password',
				$saltingMechanism,
				$useLdapSyleps);
			
		$adapter->setIdentity($login);
		$adapter->setCredential($password);
		$result = Centurion_Auth::getInstance()->authenticate($adapter);
		if ($result->isValid()) {
			//print 'On effectue le getStorage\n';
			Centurion_Signal::factory('pre_login')->send(null, $adapter);
			$result = $adapter->getResultRowObject(null);
			Centurion_Auth::getInstance()->clearIdentity();
			Centurion_Auth::getInstance()->getStorage()->write($result);
			//print_r($result);
			//Création du profile de préférence / par forcément utile !
			$pref= new Portal_Preference_Preference($result->id,$result->email,$result->first_name,$result->last_name);
			$session = new Zend_Session_Namespace('Zend_Auth');
			$session->pref = $pref;
			//print_r($pref);
			$this->_connected = true;
		}
		else {
			error_log('Problème d\'authentification.');
		}
	}
	
	public function run() {
		$Tasks = new Cron_Model_DbTable_CronTask();
		$tasks = $Tasks->getactiveTask();
		foreach ($tasks as $task)
		{	$class_name = $task->class_name;
			$function_name = $task->function_name;
			$frequency = $task->frequency;
			$last = strtotime($task->last_execution);
			$now = time();
			$next_time = $last+$frequency;
			if ($now > $next_time){
				// Connexion si nécessaire
				if (!($this->_connected)) { $this->connexion();}				
				$Obj = new $class_name;
				$Obj->$function_name();
				$task->setExecution();
				$task->save();
			}
		}
		
	}
	
}

