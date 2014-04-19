<?php   
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__));

session_start();

require_once (ROOT . DS . 'config' . DS . 'config.php');
require_once 'library/loader.php';

class Router{
  public function __construct()
  {
    $this->loader = new AutoLoader();
    $this->route();
  }
  
  private function route()
  {
    $url = (isset($_GET['url'])) ? $_GET['url'] : '';
    $url = explode("/",$url);
    
    if(count($url)>0)
    {
      if(isset($url[0]))
      {
        $controller = ucfirst($url[0]);
      }
      
      if(isset($url[1]))
      {
        $method = $url[1];
      }
    }
    
    if(!isset($controller)||$controller==false)
    {
      $controller = "Index";
    }
    
    if(!isset($method)||$method==false)
    {
      $method = "index";
    }
    
    $controller.="Controller";
    
    $object = new $controller;
    $object->$method();
  }
}

$router = new Router();

