<?php

/**
 * AMS Archive Management System
 * 
 * PHP version 5
 * 
 * @category AMS
 * @package  CI
 * @author   Nouman Tayyab <nouman@geekschicago.com>
 * @license  CPB http://ams.avpreserve.com
 * @version  GIT: <$Id>
 * @link     http://ams.avpreserve.com

 */

/**
 * Refinecron Class
 *
 * @category   AMS
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@geekschicago.com>
 * @license    CPB http://ams.avpreserve.com
 * @link       http://ams.avpreserve.com
 */
class Refinecrons extends CI_Controller
{

	/**
	 *
	 * Constructor. Load Model and Library.
	 * 
	 */
	function __construct()
	{
		parent::__construct();
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		
	}

	/**
	 * Create a google refine project and returns the project URL.
	 * 
	 * @param string $path
	 * @param string $filename
	 * @param integer $job_id
	 * @return boolean
	 */
	

	/**
	 * Make CSV File for google refinement.
	 * 
	 * @return 
	 */
	public function make_refine_csv()
	{
//		set_time_limit(0);
//		@ini_set("memory_limit", "1000M"); # 1GB
//		@ini_set("max_execution_time", 999999999999); # 1GB
		echo 'Nouman';exit;
	}

}