<?abstract class Controller{
  protected $view = false;
  protected $data = array();
  
  public function __construct(){
    $this->view = new View();
    
    $this->addView('header',array('title'=>'test'));
  }
  
  protected function addView($path_to_view,$data=array())
  {
    $data = array('data'=>$data);
    $this->view->add($path_to_view,$data);
  }
  
  protected function render()
  {
    $this->addView('footer');
    echo $this->view->render();
  }
}
