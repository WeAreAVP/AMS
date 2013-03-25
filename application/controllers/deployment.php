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
	}

	/**
	 * Connect and test the sphnix server.
	 * 
	 */
	function sphnix_connect()
	{
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

	function memcached_connect()
	{
		$this->config->load('memcached');
		$memcached_server = $this->config->item('servers');
		
		$fp = @fsockopen($memcached_server['default']['host'], $memcached_server['default']['port'], $errno, $errstr, 300);
		if ( ! $fp)
		{
			deployment_display("$errstr ($errno)");
		}
		else
		{
			deployment_display('Sphnix is running.', 'OK');
		}
	}

}

// END Deployment Controller

// End of file deployment.php 
/* Location: ./application/controllers/deployment.php */