<?
class AutoLoader
{  
  public function __construct()
  {
    include_once(ROOT.DS.'application/controllers/Controller.php');
    include_once(ROOT.DS.'application/models/model.php');
    spl_autoload_register( array( $this, 'ClassLoader' ));
  }

  public function ClassLoader( $class )
  {    
    if( class_exists( $class, false ))
    {
      return true;
    }

    if( is_readable( ROOT.DS.'application/controllers/' . $class . '.php' ))
    {
      include_once ROOT.DS.'application/controllers/' . $class . '.php';
    }
    
    if( is_readable( ROOT.DS.'application/models/'.$class.'.php'))
    {
      include_once ROOT.DS.'application/models/'.$class.'.php';
    }
    
    if( is_readable( ROOT.DS.'library/'.$class.'.php'))
    {
      include_once ROOT.DS.'library/'.$class.'.php';
    }
  }
}

$autoloader = new AutoLoader();


