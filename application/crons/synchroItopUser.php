#!/usr/bin/php
<?php
include 'BootstrapCron.php';

try
{
   // Le script peut mettre jusqu'à 10 minutes à s’exécuter.
   //Cette valeur doit être réglée en fonction de vos traitements
   //c'est en quelque sorte le timeout.
    ini_set('max_execution_time', 600);

    // Le script peut utiliser 32 Mo de mémoire
    ini_set('memory_limit', "32M");

    $start = microtime(TRUE);
    print " ---------- Execution du CRON : System \n\n";
    flush();
    
    //exécution de la synchro des user iTop.
	
    
    
}
catch (Exception $e)
{
    // Gestion de l'exception.
    print "Une erreur est survenue \n";
    flush();
}