<?php

/**
 * Deployment Controller
 * 
 * PHP version 5
 * 
 * @category   Time Tracking
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
	}

	function sphnix_connect()
	{
		$fp = @fsockopen('127.0.0.1', '9312', $errno, $errstr, 300);
		if ( ! $fp)
		{
			deployment_display("$errstr ($errno)");
		}
		else
		{
			deployment_display('Sphnix is running.','OK');
		}
	}

}

// END Deployment Controller

// End of file deployment.php 
/* Location: ./application/controllers/deployment.php */