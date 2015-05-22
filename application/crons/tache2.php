#!/usr/bin/php
<?php
include 'BootstrapCron.php';

try
{
  	$boucle=true;
	while ($boucle) 
		{
		$tasksTBL = new System_Model_DbTable_Tasks();
		$tasks = $tasksTBL->getTask(3);
		if (!($tasks->active)) {$boucle=false;
								break;}
	   	$tasksTBL->setPulse(1);
        sleep($tasks->timeout);

        } 
}
catch (Exception $e)
{
    // Gestion de l'exception.
    print "Une erreur est survenue \n";
    flush();
}