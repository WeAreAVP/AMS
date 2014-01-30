<?php

if ( ! defined('BASEPATH'))
	exit('No direct script access allowed');

define('SPHINXSEARCH_SPARK_HOME', dirname(dirname(__FILE__)));

class_exists('SphinxClient') or require_once(SPHINXSEARCH_SPARK_HOME . '/third_party/BagIt/bagit.php');

class Bagit_lib
{

	private $bagit_lib;
	private $bagit_path = './assets/bagit';

	function __construct($config = array())
	{
		$this->bagit_lib = new Bagit($this->bagit_path);
		
	}

}
