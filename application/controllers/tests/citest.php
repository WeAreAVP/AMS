<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Citest extends CIUnit_TestCase
{
	public function index(){
       
    
    
    

    echo $this->unit->report();
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */