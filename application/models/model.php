<?abstract class Model extends DBClass{
  protected $model;
  protected $table;
  
  public function __construct(){
      $this->connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
      $this->model = get_class($this);
      $this->table = strtolower($this->model);
  }
}
