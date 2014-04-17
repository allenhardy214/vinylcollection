<?abstract class Dbclass{
  static protected $pdo;
  protected $result;
  protected $model;
  protected $table;
  protected $fields = array();
  
  private $error;
  private $stmt;
  
  private $options = array(
    PDO::ATTR_PERSISTENT => true,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
  );
  
  public function connect($dbhost,$dbuser,$dbpass,$dbname,$dbport){
    
    $pdo_string = "mysql:dbname={$dbname};host={$dbhost};port={$dbport};";
    
    try{
      $this->pdo = new PDO($pdo_string,$dbuser,$dbpass,$this->options);
    }
    catch(PDOException $e){
      $this->error = $e->getMessage();
    }
    
    $fields = array();
    
    $sql = "DESCRIBE `{$this->table}`";
    $this->query($sql);
    
    $table = $this->results();
    
    foreach($table as $row)
    {
      $this->fields[$row['Field']] = array(
        'name'=>$row['Field'],
        'required'=>($row['Null']=='NO') ? true : false,
        'is_primary_key'=>($row['Key']=='PRI') ? true : false,
        'default'=>$row['Default'],
        'type'=>$this->determineType($row['Type'])
      );
    }
  }
  
  private function determineType($dbType){
    
    $type = explode("(",$dbType);
    $dbType = trim($type[0]);
    
    switch($dbType){
      case 'int':
        return PDO::PARAM_INT;
        break;
      case 'varchar':
        PDO::PARAM_STR;
        break;
      default:
        return PDO::PARAM_STR;
    }
  }
  
  function disconnect(){
  }
  
  public function getAll(){
    $sql = "SELECT * FROM `{$this->table}`";
    $this->query($sql);
    
    $all_rows = $this->results();
    
    return $all_rows;
  }
  
  public function getModels(){
    $sql = "SELECT * FROM `{$this->table}`";
    $this->query($sql);
    
    $all_rows = $this->models();
    
    return $all_rows;
  }
  
  public function getWhere($columns=array(),$conditions=array(),$order=array(),$direction='ASC',$limit=false,$offset=false){
  }
  
  public function get(){
  }
  
  public function query($query){
    $this->stmt = $this->pdo->prepare($query);
  }
  
  public function bind($param,$value,$type=null){
    
    if(is_null($type)){
      switch(true){
        case is_int($value):
          $type = PDO::PARAM_INT;
          break;
        case is_bool($value):
          $type = PDO::PARAM_BOOL;
          break;
        case is_null($value):
          $type = PDO::PARAM_NULL;
          break;
        default:
          $type = PDO::PARAM_STR;
      }
    }
    
    $this->stmt->bindValue($param, $value, $type);
  }
  
  public function execute()
  {
    return $this->stmt->execute();
  }
  
  public function results()
  {
    $this->execute();
    return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  
  public function models()
  {
    $this->execute();
    return $this->stmt->fetchAll(PDO::FETCH_CLASS,$this->model);
  }
  
  public function result()
  {
    $this->execute();
    return $this->stmt->fetch(PDO::FETCH_ASSOC);
  }
  
  public function rowCount(){
    return $this->stmt->rowCount();
  }
  
  public function lastInsertId(){
    return $this->pdo->lastInsertId();
  }
  
  function getError(){
  }
  
  function insert(){
  }
  
  function update(){
  }
}
