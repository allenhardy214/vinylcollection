<?abstract class Model extends Dbclass{
  protected $model;
  protected $table;
  
  public function __construct(){
    $this->model = get_class($this);
    $this->table = str_replace("model","",strtolower($this->model));
    $this->connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME,DB_PORT);
      
  }
}
