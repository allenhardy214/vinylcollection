<?class RecordModel extends Model{
  
  protected $foreignFields = array(
    'artist_id'=>array('model'=>'ArtistModel'),
    'label_id'=>array('model'=>'LabelModel'),
    'size_id'=>array('model'=>'SizeModel'),
    'type_id'=>array('model'=>'TypeModel'),
    'sides_id'=>array('model'=>'SidesModel'),
    'condition_id'=>array('model'=>'ConditionModel')
  );
  
  public function __construct(){
     parent::__construct();
     
     $this->options = array(
		array('action'=>'edit','url'=>'/record/edit/'),
		array('action'=>'delete','url'=>'/record/delete/'),
     );
     
     $this->option_count = count($this->options);
  }
}
