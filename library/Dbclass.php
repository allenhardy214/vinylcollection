<?abstract class Dbclass{
  static $pdo;
  protected $result;
  protected $model;
  protected $table;
  
  function connect($dbhost,$dbuser,$dbpass,$dbname,$dbport){
    
    $pdo_string = "mysql:dbname=endeva;host={$dbhost};port={$dbport};";

    $this->pdo = new PDO($pdo_string,$dbuser,$dbpass);
    $this->validate();
  }
  
  function disconnect(){
  }
  
  function getAll(){
    
  }
  
  function get(){
  }
  
  function query(){
  }
  
  function rowCount(){
  }
  
  function getError(){
  }
  
  function insert(){
  }
  
  function update(){
  }
}
