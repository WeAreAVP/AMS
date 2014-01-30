<?php

if ( ! defined('BASEPATH'))
	exit('No direct script access allowed');

class Bagit_lib
{

	function __construct()
	{
		set_include_path(dirname(dirname(__FILE__)) . '/third_party/BagIt');

		require_once('bagit.php');
	}

}
