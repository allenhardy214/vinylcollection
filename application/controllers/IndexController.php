<?class IndexController extends Controller{
  public function __construct(){
    parent::__construct();
    
    $record = new RecordModel();
  }
  
  public function index()
  {
    $this->render();
  }
  
  public function edit()
  {
    
  }  
  
}
