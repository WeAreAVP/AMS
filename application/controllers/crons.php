<?php

/**
 * Settings controller.
 *
 * @package    AMS
 * @subpackage Scheduled Tasks
 * @author     Ali Raza
 */
class Crons extends CI_Controller
{
	/**
	*
	* constructor. Load layout,Model,Library and helpers
	* 
	*/
	public $assets_path;
	function __construct()
	{
		parent::__construct();
		$this->load->model('email_template_model', 'email_template');
		$this->load->model('cron_model');
		$this->assets_path='assets/';
    }
    /**
     * Process all pending email 
     *  
     */
    function processemailqueues()
	{
    	$email_queue = $this->email_template->get_all_pending_email();
        foreach ($email_queue as $queue)
		{
        	$now_queue_body = $queue->email_body . '<img src="' . site_url('emailtracking/' . $queue->id . '.png') . '" height="1" width="1" />';
			if (send_email($queue->email_to, $queue->email_from, $queue->email_subject, $now_queue_body))
			{
				$this->email_template->update_email_queue_by_id($queue->id, array("is_sent" => 2, "sent_at" => date('Y-m-d H:i:s')));
				echo "Email Sent To " . $queue->email_to . " <br/>";
			}
		}
	}
	/**
     * Store All Assets Data Files Structure in database
     *  
     */
    
	function process_dir()
	{
		set_time_limit(0);
		$this->cron_model->scan_directory($this->assets_path,'assets');
		echo "All Data Path Under {$this->assets_path} Directory Stored ";
		exit(0);
	}
	/**
    * Process all pending assets Data Files
    *  
    */
    function process_xml_file()
	{
		$folders=$this->cron_model->get_all_data_folder();
		$doc = new DOMDocument();
		foreach($folders as $folder)
		{
			$data=file_get_contents( $folder->folder_path.'data/organization.xml' );
			$x = @simplexml_load_string( $data );
			$data=xmlObjToArr($x);
			$station_cpb_id= $data['children']['cpb-id'][0]['text'];
			exit();		
		}
	}
}