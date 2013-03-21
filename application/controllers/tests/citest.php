<?php

if ( ! defined('BASEPATH'))
	exit('No direct script access allowed');

class Citest extends CIUnit_TestCase
{

	public function index()
	{

		$this->CI = set_controller('unit_testing');
		$this->CI->usertesting();

		// Fetch the buffered output
		$out = output();

		// Check if the content is OK
		$this->assertSame(0, preg_match('/(error|notice)/i', $out));



//        echo $this->unit->report();
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */