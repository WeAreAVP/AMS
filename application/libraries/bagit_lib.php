<?php

if ( ! defined('BASEPATH'))
	exit('No direct script access allowed');

require_once(dirname(dirname(__FILE__)) . '/third_party/BagIt/bagit.php');

class Bagit_lib extends BagIt
{

//	private $bagit_lib;
	private $bagit_path = './assets/bagit';

	function __construct()
	{
		$this->bag ='./assets/bagit';
		$this->bagDirectory='./assets/bagit/abc';
		$this->manifest='manifest-md5.txt';
//		$this->bagit_lib = new Bagit($this->bagit_path);
	}

}
