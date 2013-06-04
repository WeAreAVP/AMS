<?php

/**
 * Deployment Controller
 * 
 * PHP version 5
 * 
 * @category   AMS
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@avpreserve.com>
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
 * @author     Nouman Tayyab <nouman@avpreserve.com>
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
		$data['sphnix'] = $this->sphnix_connect();
		$data['searchd'] = $this->sphinx_searchd();
		/** Connect & Check status of Memcached  */
		$data['memcached'] = $this->memcached_connect();
		$data['memcached_service'] = $this->memcached_service();
		/** Check DB Name and BASE URL */
		$data['values'] = $this->check_values();
		/** Check Error Reporting  */
		$data['reporting'] = $this->check_reporting();
//		debug($data);
		$this->load->view('deploy_view', $data);
	}

	/**
	 * Connect and test the sphnix server.
	 * 
	 * @return
	 */
	private function sphnix_connect()
	{
		$display['waiting'] = "Connecting to Sphnix .";

		$sphnix_server = $this->config->item('server');
		$fp = @fsockopen($sphnix_server[0], $sphnix_server[1], $errno, $errstr, $this->config->item('connect_timeout'));
		if ( ! $fp)
		{
			$display['msg'] = deployment_display("$errstr ($errno)");
		}
		else
		{
			$display['msg'] = deployment_display('Sphnix Connection', 'OK');
		}
		return $display;
	}

	/**
	 * Check the searchd service status
	 * 
	 * @return array
	 */
	private function sphinx_searchd()
	{
		$display['waiting'] = "Checking Sphnix Status .";
		$output = @exec("/etc/init.d/searchd status");
		$display['msg'] = $output;
		return $display;
	}

	/**
	 * Connect and test the memcached server.
	 * 
	 * @return
	 */
	private function memcached_connect()
	{
		$display['waiting'] = "Connecting to Memcached .";

		$this->config->load('memcached');
		$memcached_server = $this->config->item('memcached');

		$fp = @fsockopen($memcached_server['servers']['default']['host'], $memcached_server['servers']['default']['port'], $errno, $errstr, 300);
		if ( ! $fp)
		{
			$display['msg'] = deployment_display("$errstr ($errno)");
		}
		else
		{
			$display['msg'] = deployment_display('Memcached Connection', 'OK');
		}
		return $display;
	}

	/**
	 * Check the memcached service status
	 * 
	 * @return array
	 */
	private function memcached_service()
	{
		$display['waiting'] = "Checking Memcached Status .";
		$output = @exec("/etc/init.d/memcached status");
		$display['msg'] = $output;
		return $display;
	}

	/**
	 * Check DB names
	 * 
	 * @return
	 */
	private function check_values()
	{
		$display['waiting'] = "Checking Server values .";

		if (ENVIRONMENT === 'production')
		{
			if ($this->db->database === 'ams_live')
				$display['db_name'] = deployment_display('Database name (ams_live)', 'OK');
			else
				$display['db_name'] = deployment_display('Database name (ams_live).');
			if ($this->config->item('base_url') === 'http://ams.avpreserve.com/')
				$display['url'] = deployment_display('Base URL (http://ams.avpreserve.com/)', 'OK');
			else
				$display['url'] = deployment_display('Base URL (http://ams.avpreserve.com/).');
		}
		else if (ENVIRONMENT === 'qatesting')
		{
			if ($this->db->database === 'ams_qa')
				$display['db_name'] = deployment_display('Database name (ams_qa)', 'OK');
			else
				$display['db_name'] = deployment_display('Database name (ams_qa).');
			if ($this->config->item('base_url') === 'http://amsqa.avpreserve.com/')
				$display['url'] = deployment_display('Base URL (http://amsqa.avpreserve.com/)', 'OK');
			else
				$display['url'] = deployment_display('Base URL (http://amsqa.avpreserve.com/)');
		}
		return $display;
	}

	/**
	 * Check the error reporting status.
	 * 
	 * @return
	 */
	private function check_reporting()
	{
		$display['waiting'] = "Checking Error Reporting .";

		if (ini_get('display_errors') == 0)
			$display['errors'] = deployment_display('Display Errors. ', 'OFF');
		else
			$display['errors'] = deployment_display('Display Errors. ', 'ON');
		if (ini_get('error_reporting') == 0)
			$display['reporting'] = deployment_display('Display Reporting. ', 'OFF');
		else
			$display['reporting'] = deployment_display('Display Reporting. ', ini_get('error_reporting'));
		return $display;
	}

}

// END Deployment Controller

// End of file deployment.php 
/* Location: ./application/controllers/deployment.php */