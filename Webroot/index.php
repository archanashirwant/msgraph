<?php
session_start();
define('WEBROOT', str_replace("Webroot/index.php", "", $_SERVER["SCRIPT_NAME"]));
define('ROOT', str_replace("Webroot/index.php", "", $_SERVER["SCRIPT_FILENAME"]));

define('ROOTS',dirname(__DIR__).DIRECTORY_SEPARATOR);

define('APP', ROOT);
define('VIEW', ROOT.  'Views' . DIRECTORY_SEPARATOR);
define('MODEL', ROOT.  'Models' . DIRECTORY_SEPARATOR);
define('CONTROLLER', ROOT.  'Controllers' . DIRECTORY_SEPARATOR);
define('CORE', ROOT.  'Core' . DIRECTORY_SEPARATOR);


require (ROOT . 'vendor/autoload.php');

require(ROOT . 'Config/core.php');
require(ROOT . 'TokenStore/TokenCache.php');

require(ROOT . 'router.php');
require(ROOT . 'request.php');
require(ROOT . 'dispatcher.php');

function autoload($class)
    {    
        
    
		$controller = CONTROLLER . "{$class}.php";  


        if(is_readable($core)){
            require_once $core;  
        }elseif (is_readable($controller)){
            require_once $controller;
        }
    }

spl_autoload_register("autoload",false);


$dispatch = new Dispatcher();
$dispatch->dispatch();
?>