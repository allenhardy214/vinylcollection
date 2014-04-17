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
    
    $select_columns = array();
    
    if(!empty($columns)){
      foreach($columns as $field_name){
        
        if(isset($this->fields[$field_name])){
          $select_columns[] = "`{$field_name}`";
        }
      }
    }
    
    if(empty($select_columns)){
      $field_list = ' * ';
    }
    else{
      $field_list = implode(",",$select_columns);
    }
    
    if($limit===false && $offset===false){
      $query = "SELECT {$field_list} FROM `{$this->table}` ";
    }
    else{
      $query = "SELECT SQL_CALC_FOUND_ROWS {$field_list} FROM `{$this->table}` ";
    }
    
    $valid_conditions = array();
    
    if(!empty($conditions)){
      foreach(array_keys($conditions) as $field){
        if(isset($this->fields[$field])){
          $valid_conditions[$field] = "`{$field}`=:{$field} ";
        }
      }
      
      if(count($valid_conditions)==0){
        $where = '';
      }
      elseif(count($valid_conditions)==1){
        $where = "WHERE {$valid_conditions[0]} ";
      }
      else{
        $where = "WHERE {$valid_conditions[0]} ";
        array_shift($valid_conditions);
        
        $where.= implode(" AND ",$valid_conditions);
      }
    }
    else{
      $where = '';
    }
    
    $query.=$where;
    
    if(!empty($order)){
      
      $valid_order = array();
      
      foreach($order as $o){
        if(isset($this->fields[$o])){
          $valid_order[] = $o;
        }
      }
      
      $query.= " ORDER BY ".implode(",",$valid_order)." {$direction} ";
    }
    
    if($limit!==false && $offset!==false){
      $query = " LIMIT :limit OFFSET :offset";
    }
    
    $this->query($query);
    
    foreach(array_keys($valid_conditions) as $condition)
    {
      $this->bind($condition,$conditons[$condition],$this->fields[$condition]['type']);
    }
    
    if($limit!==false && $offset!==false)
    {
      $this->bind('limit',$limit);
      $this->bind('offset',$offset);
    }
    
    return $this->results;
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
