<?abstract class Model extends Dbclass{
  protected $model;
  protected $table;
  
  public $options = array();
  public $option_count = 0;
  
  
  public function __construct(){
    $this->model = get_class($this);
    $this->table = str_replace("model","",strtolower($this->model));
    $this->connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME,DB_PORT);
    
    $this->related = $this->resolveForeignKeys();
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
