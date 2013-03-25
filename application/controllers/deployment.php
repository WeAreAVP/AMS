<?php

/**
 * Deployment Controller
 * 
 * PHP version 5
 * 
 * @category   AMS
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@geekschicago.com>
 * @license    AMS http://ams.avpreserve.com
 * @version    GIT: <$Id>
 * @link       http://ams.avpreserve.com
 */

/**
 * Deployment Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@geekschicago.com>
 * @license    AMS http://ams.avpreserve.com
 * @link       http://ams.avpreserve.com
 */
class Deployment extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->layout = 'deployment.php';
	}

	/**
	 *  After deployment on PRODUCTION check everything works fine.
	 * 
	 * @return 
	 */
	public function check()
	{

		/** Connect & Check status of Sphnix  */
		$this->sphnix_connect();
		/** Connect & Check status of Memcached  */
		$this->memcached_connect();
		/** Check DB Name and BASE URL */
		$this->check_values();
		/** Check Error Reporting  */
		$this->check_reporting();
	}

	/**
	 * Connect and test the sphnix server.
	 * 
	 */
	function sphnix_connect()
	{
		deployment_display("Connecting to Sphnix", '...');
		sleep(3);
		$sphnix_server = $this->config->item('server');
		$fp = @fsockopen($sphnix_server[0], $sphnix_server[1], $errno, $errstr, $this->config->item('connect_timeout'));
		if ( ! $fp)
		{
			deployment_display("$errstr ($errno)");
		}
		else
		{
			deployment_display('Sphnix is running.', 'OK');
		}
	}

	/**
	 * Connect and test the memcached server.
	 * 
	 */
	function memcached_connect()
	{
		deployment_display("Connecting to Memcached", '...');
		sleep(3);
		$this->config->load('memcached');
		$memcached_server = $this->config->item('memcached');

		$fp = @fsockopen($memcached_server['servers']['default']['host'], $memcached_server['servers']['default']['port'], $errno, $errstr, 300);
		if ( ! $fp)
		{
			deployment_display("$errstr ($errno)");
		}
		else
		{
			deployment_display('Memcached is running.', 'OK');
		}
	}

	/**
	 * Check DB names
	 * 
	 */
	function check_values()
	{
		deployment_display("Checking Server values", '...');
		sleep(3);
		if (ENVIRONMENT === 'production')
		{
			if ($this->db->database === 'ams_live')
				deployment_display('Database name is correct.', 'OK');
			else
				deployment_display('Database name is incorrect.');
			if ($this->config->item('base_url') === 'http://ams.avpreserve.com/')
				deployment_display('Base URL is correct.', 'OK');
			else
				deployment_display('Base URL is incorrect.');
		}
		else if (ENVIRONMENT === 'qatesting')
		{
			if ($this->db->database === 'ams_qa')
				deployment_display('Database name is correct.', 'OK');
			else
				deployment_display('Database name is incorrect.');
			if ($this->config->item('base_url') === 'http://amsqa.avpreserve.com/')
				deployment_display('Base URL is correct.', 'OK');
			else
				deployment_display('Base URL is incorrect.');
		}
	}

	/**
	 * Check the error reporting status.
	 * 
	 */
	function check_reporting()
	{
		deployment_display("Checking Error Reporting", '...');
		sleep(3);
		if (ini_get('display_errors') == 0)
			deployment_display('Display Errors. ', 'OFF');
		else
			deployment_display('Display Errors. ', 'ON');
		if (ini_get('error_reporting') == 0)
			deployment_display('Display Reporting. ', 'OFF');
		else
			deployment_display('Display Reporting. ', ini_get('error_reporting'));
	}

}

// END Deployment Controller

// End of file deployment.php 
/* Location: ./application/controllers/deployment.php */