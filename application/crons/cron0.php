#!/usr/bin/php
<?php

include 'BootstrapCron.php';


//Zend_Debug::dump('Test !');
print "test 0\n";
try
{ //print "message\n";
	//Connexion.
	$dbAdapter = Zend_Db_Table_Abstract::getDefaultAdapter();
	//Zend_Debug::dump('Test !');
	$tableName =  'auth_user';
	$loginColumn = 'username';
	$passwordColumn = 'password';
	$saltingMechanism = null;
	$passByLdapSyleps = true; //=> pas besoin de password, on considère que le Ldap a déjà répondu oui
	$login = 'crontab';
	$password = '';
	print "test\n";
	
	$adapter = new Syleps_Auth_Adapter_DbTable($dbAdapter,
			$tableName,
			$loginColumn,
			$passwordColumn,
			$saltingMechanism,
			$passByLdapSyleps);
	 
	$adapter->setIdentity($login);
	$adapter->setCredential($password);
	error_log('OK, on est ici !');
	$result = Centurion_Auth::getInstance()->authenticate($adapter);
	
	print_r($result);
	error_log(serialize($result), 0);
	
	if ($result->isValid()) {
		print 'On effectue le getStorage\n';
		Centurion_Signal::factory('pre_login')->send(null, $adapter);
		
		//Ici que cela prend le plus de temps.
		$result = $adapter->getResultRowObject(null);
		Centurion_Auth::getInstance()->clearIdentity();
		Centurion_Auth::getInstance()->getStorage()->write($result);
		
		//Création du profile de préférence / par forcément utile !
		$pref= new Portal_Preference_Preference($result->id,$result->email,$result->first_name,$result->last_name);
		$session = new Zend_Session_Namespace('Zend_Auth');
		$session->pref = $pref;
	}
	else {
		error_log('Problème d\'authentification.');
	}
	
	
	print_r($pref);
	
	$itopuser = new Syleps_iTop_UserLocal();
	$itopuser->importAll();
	
   // Le script peut mettre jusqu'à 10 minutes à s’exécuter.
   //Cette valeur doit être réglée en fonction de vos traitements
   //c'est en quelque sorte le timeout.
    ini_set('max_execution_time', 600);

    // Le script peut utiliser 32 Mo de mémoire
    ini_set('memory_limit', "32M");

    $start = microtime(TRUE);
    print " ---------- Execution du CRON : System \n\n";
    flush();
    
    /*
     * une table contenant ce qu'il faut faire régulèrement
     * - nom de la classe
     * - nom de la fonction a éxécuter
     * - actif ou pas
     * - une fréquence en seconde
     * - une date de dernière exécution
     * => on ajoute la date de dernière exécution et la fréquence, si on dépasse l'heure actuelle alors 'Exécution' !
     *      
     * 
     */
    
    
    /*$tasksTBL = new System_Model_DbTable_Tasks();
	$tasks = $tasksTBL->getactiveTask();
	foreach ($tasks as $task)
	{
		echo $task->id;
		//On charge la class 
		//http://framework.zend.com/manual/1.12/en/zend.loader.load.html
		$class = Zend_Loader::loadFile($task->filename, $task->dirs);
		//on lance son exécution
		$class->execute();
		print_r($tasksTBL->getTask($task->id));
		
		echo chr(13);
	}
    
    */
    
    
    
    
	/*$boucle=true;
	$i=0;
	while ($boucle) 
		{
		$tasksTBL = new System_Model_DbTable_Tasks();
		$tasks = $tasksTBL->getTask(1);
		if (!($tasks->active)) {$boucle=false;
								break;}
		//echo 'Active='.$tasks->active;
	   	$tasksTBL->setPulse(1);
        sleep($tasks->timeout);
        $i++;
        //print " Timeout : ".$tasks->timeout."--boucle ".$i." \n\n";
        }*/ 
}
catch (Exception $e)
{
    // Gestion de l'exception.
    print "Une erreur est survenue \n";
    flush();
}