<?abstract class Dbclass{
  protected $pdo;
  protected $result;
  protected $model;
  protected $table;
  protected $fields = array();
  protected $primary;
  
  private $error;
  private $stmt;
  
  protected $foreignFields = array();
  protected $complexKeys = array();
  
  protected $related = false;
  
  private $options = array(
    PDO::ATTR_PERSISTENT => true,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
  );
  
  private $selectOptions = array();
  
  public function connect($dbhost,$dbuser,$dbpass,$dbname,$dbport){
    
    $pdo_string = "mysql:dbname={$dbname};host={$dbhost};port={$dbport};";
    
    try
    {
      $this->pdo = new PDO($pdo_string,$dbuser,$dbpass,$this->options);
    }
    catch(PDOException $e)
    {
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
      
      if($row['Key']=='PRI'){
        $this->primary = $row['Field'];
      }
    }
  }
  
  private function determineType($dbType)
  {
    
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
  
  function disconnect()
  {
  }
  
  public function getSelectOptions(){
    return implode($this->selectOptions);
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
    
    foreach($all_rows as &$r)
    {
		foreach(array_keys($r->fields) as $field)
		{
			if($r->related!==false)
			{
				if(isset($r->related[$field.'_id'][$r->$field]))
				{
					$r->$field = $r->related[$field.'_id'][$r->$field]->name;
				}
			}
		}
	}
    
    return $all_rows;
  }
  
  private function getRequiredFields($include_primary=false)
  {
    $required = array();
    
    foreach($this->fields as $k=>$values){
      if($values['required']==true)
      {
        
        if($values['is_primary_key'])
        {
          if($include_primary==true){
            $required[$k] = $values;
          }
        }
        else{
          $required[$k] = true;
        }
      }
    }
    
    return $required;
  }
  
  private function getFieldDefaults()
  {
    $defaults = array();
    
    foreach($this->fields as $k=>$v)
    {
      $defaults[$k] = $v['default'];
    }
  }
  
  public function insert($data=array())
  {
    $required = $this->getRequiredFields();
    $defaults = $this->getFieldDefaults();
    
    foreach($required as $k=>$v)
    {
      if(!isset($data[$k]))
      {
        if($defaults[$k]===false)
        {
          return false;
        }
        else
        {
          $data[$k] = $defaults[$k];
        }
      }
    }
    
    if(isset($data[$this->primary]))
    {
      unset($data[$this->primary]);
    }
    
    $keys = array_keys($data);
    
    $columns = $placeholders = array();
    
    foreach($keys as $key)
    {
      $columns[] = "`{$key}`";
      $placeholders[] = ":{$key}";
    }
    
    $fields = implode(",",$columns);
    $place = implode(",",$placeholders);
    
    $sql = "INSERT INTO {$this->table} ({$fields}) VALUES ({$place});";
    $this->query($sql);
    
    foreach($data as $k=>$v)
    {
      if(in_array($k,$keys))
      {
        $this->bind($k,$v);
      }
    }
    
    $this->execute();
    return $this->lastInsertId();
  }
  
  public function getById($id){
    $conditions = array(
      $this->primary=>$id
    );
    
    $results = $this->getWhere(array(),$conditions);
    
    if(isset($results[0]))
    {
      return $results[0];
    }
    
    return false;
  }
  
  public function getWhere($columns=array(),$conditions=array(),$order=array(),$direction='ASC',$limit=false,$offset=false,$model=true){
    
    $select_columns = array();
    
    if(!empty($columns))
    {
      foreach($columns as $field_name)
      {
        
        if(isset($this->fields[$field_name]))
        {
          $select_columns[] = "`{$field_name}`";
        }
      }
    }
    
    if(empty($select_columns))
    {
      $field_list = ' * ';
    }
    else
    {
      $field_list = implode(",",$select_columns);
    }
    
    if($limit===false && $offset===false)
    {
      $query = "SELECT {$field_list} FROM `{$this->table}` ";
    }
    else
    {
      $query = "SELECT SQL_CALC_FOUND_ROWS {$field_list} FROM `{$this->table}` ";
    }
    
    $valid_conditions = array();
    
    if(!empty($conditions))
    {
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
    else
    {
      $where = '';
    }
    
    $query.=$where;
    
    if(!empty($order))
    {
      
      $valid_order = array();
      
      foreach($order as $o)
      {
        if(isset($this->fields[$o])){
          $valid_order[] = $o;
        }
      }
      
      $query.= " ORDER BY ".implode(",",$valid_order)." {$direction} ";
    }
    
    if($limit!==false && $offset!==false)
    {
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
    
    if($model==true)
    {
      return $this->models();
    }
    else
    {
      return $this->results();
    }
  }
  
  public function update($data=array())
  {
    if(empty($data))
    {
      return false;
    }
    
    if(!isset($data[$this->primary]))
    {
      return false;
    }
    
    $fields = array();
    
    foreach($data as $k=>$v)
    {
      if($k!=$this->primary)
      {
        $fields[$k] = " `{$k}`=:{$k} ";
      }
    }
    
    if(empty($fields))
    {
      return false;
    }
    
    $fields_to_update = implode(",",$fields);
    
    $sql = "UPDATE {$this->table} SET {$fields_to_update} WHERE `{$this->primary}`={$data[$this->primary]}";
    $this->query($sql);
    
    foreach($data as $k=>$v)
    {
      $this->bind($k,$v);
    }
    
    return $this->execute();
  }
  
  public function delete($id)
  {
    $sql = "DELETE FROM `{$this->table}` WHERE `{$this->primary}`=:{$this->primary}";
    $this->query($sql);
    $this->bind($this->primary,$id);
    $this->execute();
    
    if($this->stmt->rowCount()>0)
    {
      return true;
    }
    
    return false;
  }
  
  
  
  public function get(){
  }
  
  public function query($query){
    $this->stmt = $this->pdo->prepare($query);
  }
  
  public function bind($param,$value,$type=null){
    
    if(is_null($type))
    {
      switch(true)
      {
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
  
  public function rowCount()
  {
    return $this->stmt->rowCount();
  }
  
  public function lastInsertId(){
    return $this->pdo->lastInsertId();
  }
  
  public function resolveComplexKeys(){
    
    $complex = array();
    
    foreach($this->complexKeys as $k=>$v)
    {
      $m = "{$v['list']}";
      $model = new $m;
      $models = $model->getModels();
      $indexed = array();
      
      foreach($models as $m)
      {
        $indexed[$m->id] = $m;
        $complex[$k] = $indexed;
      }
    }
    
    return $complex;
  }
  
  public function resolveForeignKeys()
  {
    $fks = array();
    
    
    if(!empty($this->foreignFields))
    {
      foreach($this->foreignFields as $key=>$values)
      {
        $m = "{$values['model']}";  
		  
        $model = new $m;
        
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
  
  function getError()
  {
  }
  
}
