<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/..'));

// Define path to library directory
defined('LIBRARY_PATH')
    || define('LIBRARY_PATH', realpath(dirname(__FILE__) . '/../../library'));

// Define path to public directory
defined('PUBLIC_PATH')
    || define('PUBLIC_PATH', realpath(dirname(__FILE__) . '/../../public'));
    
// bootstrap include_path and constants
require realpath(dirname(__FILE__) . '/../../library/library.php');

/** Zend_Application */
require_once 'Zend/Application.php';
require_once 'Centurion/Application.php';

require_once 'Zend/Loader/Autoloader.php';
$autoloader = Zend_Loader_Autoloader::getInstance()
    ->registerNamespace('Centurion_')
    ->registerNamespace('Portal_')
    ->registerNamespace('SF_')
    ->setDefaultAutoloader(create_function('$class',
        "include str_replace('_', '/', \$class) . '.php';"
    ));
$classFileIncCache = realpath(APPLICATION_PATH . '/../data/cache').'/pluginLoaderCache.tmp';
if (file_exists($classFileIncCache)) {
    $fp = fopen($classFileIncCache, 'r');
    flock($fp, LOCK_SH);
    $data = file_get_contents($classFileIncCache);
    flock($fp, LOCK_UN);
    fclose($fp);
    $data = @unserialize($data);

    if ($data !== false) {
        Centurion_Loader_PluginLoader::setStaticCachePlugin($data);
    }
}

Centurion_Loader_PluginLoader::setIncludeFileCache($classFileIncCache);

// Vos fichiers ini de configuration contiennent peut être des sections "developement",
//"production", etc. Si vous voulez faire tourner les mêmes crons dans ces différents
// environnements vous devez passer en paramètre le nom de l'environnement car
// avec PHP CLI vos fichiers .htaccess seront ignorés.
if(empty ($argv[1])) exit("L'argument APPLICATION_ENV n'a pas été livré à l’exécution");
$env = (string) $argv[1];
define('APPLICATION_ENV', $env);
putenv('APPLICATION_ENV='.$env);

// Create application, bootstrap, and run
$application = new Centurion_Application(
    APPLICATION_ENV,
    Centurion_Config_Directory::loadConfig(APPLICATION_PATH . '/configs/', APPLICATION_ENV, true)
);

$application->bootstrap();

if (!defined('RUN_CLI_MODE') || RUN_CLI_MODE === false) {
   //$application->bootstrap()->run();
}

/*
<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/..'));

// Define path to library directory
defined('LIBRARY_PATH')
    || define('LIBRARY_PATH', realpath(dirname(__FILE__) . '/../../library'));

// Define path to public directory
defined('PUBLIC_PATH')
    || define('PUBLIC_PATH', realpath(dirname(__FILE__) . '/../../public'));


// Vos fichiers ini de configuration contiennent peut être des sections "developement",
//"production", etc. Si vous voulez faire tourner les mêmes crons dans ces différents
// environnements vous devez passer en paramètre le nom de l'environnement car
// avec PHP CLI vos fichiers .htaccess seront ignorés.
if(empty ($argv[1])) exit("L'argument APPLICATION_ENV n'a pas été livré à l’exécution");
$env = (string) $argv[1];
define('APPLICATION_ENV', $env);
putenv('APPLICATION_ENV='.$env);


// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(LIBRARY_PATH),
    realpath(APPLICATION_PATH),
    realpath(APPLICATION_PATH . '/models'),
    get_include_path(),

)));


/** Zend_Application 
require_once 'Zend/Application.php';

$application = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH
                                                 . '/configs/application.ini');
$application->bootstrap();*/