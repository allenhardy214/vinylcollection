<?class RecordModel extends Model{
  
  protected $foreignFields = array(
    'label_id'=>array('model'=>'LabelModel'),
    'format_id'=>array('model'=>'FormatModel'),
    'rpm_id'=>array('model'=>'RpmModel'),
    'condition_id'=>array('model'=>'ConditionModel'),
    'size_id'=>array('model'=>'SizeModel'),
    'location_id'=>array('model'=>'LocationModel')
  );
  
  protected $complexKeys = array(
    'lk_record_artist'=>array('model'=>'RecordArtistModel','list'=>'ArtistModel'),
    'lk_record_track'=>array('model'=>'RecordTrackModel','list'=>'TrackModel')
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
