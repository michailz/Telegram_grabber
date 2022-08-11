<?php define('SYSTEM','YES');
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define ('DIRSEP', DIRECTORY_SEPARATOR);

include 'core' . DIRSEP . 'functions.php';
function autoloader($class_name) {
  $filename = strtolower($class_name) . '.php';
  $file = 'core' . DIRSEP . $filename;
  if (file_exists($file) == false) {
    return false;
  }
  include ($file);
}
spl_autoload_register('autoloader');

$registry = new Registry;
$db = new PDO('pgsql:host=postgres; dbname=telegram', 'postgres', 'root') or die('Cant connect to postgresql db');
$registry->set('db', $db);


$template = new Template($registry);
$registry->set ('template', $template);
$router = new Router($registry);
$registry->set('router', $router);
$router->setPath('controllers');
$router->delegate();
