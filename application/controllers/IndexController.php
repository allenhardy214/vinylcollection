<?class IndexController extends Controller{
  public function __construct(){
    parent::__construct();
    
    $this->record = new RecordModel();
  }
  
  public function index()
  {
    $records = $this->record->getAll();
    print_r($records);
    $this->render();
  }
  
  public function edit()
  {
    
  }  
  
}
