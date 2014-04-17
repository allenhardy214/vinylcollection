<?abstract class Model extends Dbclass{
  protected $model;
  protected $table;
  
  protected $foreignFields = array();
  
  public function __construct(){
    $this->model = get_class($this);
    $this->table = str_replace("model","",strtolower($this->model));
    $this->connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME,DB_PORT);
      
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
