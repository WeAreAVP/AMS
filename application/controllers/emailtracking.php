<?php

/**
 * AMS Archive Management System
 * 
 * PHP version 5
 * 
 * @category   AMS
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @copyright  Copyright (c) WGBH (http://www.wgbh.org/). All Rights Reserved.
 * @license    http://www.gnu.org/licenses/gpl.txt GPLv3
 * @version    GIT: <$Id>
 * @link       https://github.com/avpreserve/AMS
 */

/**
 * EmailTracking Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @copyright  Copyright (c) WGBH (http://www.wgbh.org/). All Rights Reserved.
 * @license    http://www.gnu.org/licenses/gpl.txt GPLv3
 * @link       https://ams.americanarchive.org
 */
class EmailTracking extends CI_Controller
{

	/**
	 * constructor. Load Model
	 * 
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->model('email_template_model', 'email_templates');
	}

	/**
	 * To Update Email Alert Status.
	 * 
	 * @param type $email_alert
	 * @return none
	 */
	function index($email_alert)
	{
		if (isset($email_alert) && $email_queue_data = $this->email_templates->get_email_queue_by_id($email_alert))
		{
			header('Content-type: image/png');
			echo(base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAAA1BMVEUAAACnej3aAAAAAXRSTlMAQObYZgAAAApJREFUCNdjYAAAAAIAAeIhvDMAAAAASUVORK5CYII='));
			if ($email_queue_data->is_email_read != 2)
			{
				$this->email_templates->update_email_queue_by_id($email_queue_data->id, array("is_email_read" => 2, "read_at" => date("Y-m-d H:i:s")));
			}
			exit();
		}
	}

}
