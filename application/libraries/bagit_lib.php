<?php

if ( ! defined('BASEPATH'))
	exit('No direct script access allowed');

require_once(dirname(dirname(__FILE__)) . '/third_party/BagIt/bagit.php');

class Bagit_lib
{

//	private $bagit_lib;
	private $bagit_path = './assets/bagit';

	function __construct()
	{
//		$this->bagit_lib = new Bagit($this->bagit_path);
	}

}
