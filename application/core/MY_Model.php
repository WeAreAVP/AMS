<?php

if ( ! defined('BASEPATH'))
{
	exit('No direct script access allowed');
}

class MY_Model extends CI_Model
{

	public $_prefix = '';
	public $_assets_table = 'assets';
	public $_identifiers_table = 'identifiers';

	function __construct()
	{
		parent::__construct();
	}

}

?>