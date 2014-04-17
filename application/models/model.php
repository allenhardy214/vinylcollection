<?abstract class Model extends Dbclass{
  protected $model;
  protected $table;
  
  protected $foreignFields = array();
  
  public function __construct(){
    $this->model = get_class($this);
    $this->table = str_replace("model","",strtolower($this->model));
    $this->connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME,DB_PORT);
      
  }
  
  public function resolveForeignKeys()
  {
    $fks = array();
    
    
    if(!empty($this->foreignFields))
    {
      foreach($this->foreignFields as $key=>$values)
      {
        $model = new "{$values['model']}";
        
        $models = $model->getModels();
        
        $indexed = array();
        
        foreach($models as $m)
        {
          $indexed[$m->id] = $m;
          $fks[$key] = $indexed;
        }
      }
      
      return $fks;
    }
    
    return false;
  }
  
  public function getFieldTitles()
  {
    $fields = array_keys($this->fields);
    
    $titles = array();
    
    foreach($fields as $f)
    {
      $titles[] = ucfirst($f);
    }
    
    return $titles;
  }
}
