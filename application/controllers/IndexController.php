<?class IndexController extends Controller{
  public function __construct(){
    parent::__construct();
    
    $this->record = new RecordModel();
  }
  
  public function index()
  {
    $this->data['records'] = $this->record->getModels();
    $this->data['field_titles'] = $this->record->getFieldTitles();
    $this->addView('record_list',$this->data);
    
    $this->render();
  }
}
