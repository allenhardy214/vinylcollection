<?class RecordModel extends Model{
  
  protected $foreignFields = array(
    'format'=>array('model'=>'RecordformatModel'),
    'sides'=>array('model'=>'RecordsideModel'),
    'type'=>array('model'=>'RecordTypeModel')
  );
  
  public function __construct(){
     parent::__construct();
  }
}
