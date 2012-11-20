<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class SomeControllerTest extends CIUnit_TestCase
{
	public function index(){
        $this->test_no_additional_headers();
    $this->test_x_forwarded_for();
    $this->test_client_ip();
    $this->test_x_forwarded_for_and_client_ip();

    echo $this->unit->report();
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */