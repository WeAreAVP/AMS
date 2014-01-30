<?php

if ( ! defined('BASEPATH'))
	exit('No direct script access allowed');



class Bagit_lib 
{

	function __construct()
	{
		
		
		ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . dirname(dirname(__FILE__)) . '/third_party/BagIt');
		require_once('bagit.php');
	}

}
