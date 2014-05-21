<?abstract class Model extends Dbclass{
  protected $model;
  protected $table;
  protected $selectOptions = array();
  
  public $options = array();
  public $option_count = 0;
  
  
  public function __construct(){
    $this->model = get_class($this);
    $this->table = str_replace("model","",strtolower($this->model));
    $this->connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME,DB_PORT);
    
    $this->related = $this->resolveForeignKeys();
    $this->selectOptions = $this->getRelatedOptions();
  }
  
  public function getOptions($key)
  {
    return $this->selectOptions[$key];
  }
  
  public function getAllOptions()
  {
    return $this->selectOptions;
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
  
  public function getRelatedOptions(){
    
    $options = array();
    
    if(is_array($this->related) && !empty($this->related))
    {
      foreach($this->related as $k=>$rel)
      {
        foreach($rel as $key=>$val)
        {
          $options[$k].="<option value='{$key}'>{$val->name}</option>";
        }
      }
    }
    
    return $options;
  }
}
