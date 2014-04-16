<?class View{
  
  private $html = array();
  private $data = array();
  
  public function __construct()
  {
  }
  
  public function add($path,$data)
  {
    if($data !==false)
		{
			foreach($data as $k=>$v)
			{
				$this->data[$k]=$v;
			}
			
			extract($this->data);
		}
		
		ob_start(); 
		include(ROOT.DS."application/views/{$path}.php"); 
		$string = ob_get_contents(); 
		ob_end_clean();
		
		$this->html[] = $string; 
  }
  
  public function render()
  {
    $string = '';
    
    if(count($this->html)>0)
    {
      $string = implode($this->html);
    }
    
    return $string;
  }
}
