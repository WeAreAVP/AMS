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
    function __construct()
		{
  	  parent::__construct();
	   	$this->load->model('email_template_model','email_template');
    }
		//
		//Process all pending email 
		//
		function processemailqueues()
		{
			$email_queue=$this->email_template->get_all_pending_email();
			foreach($email_queue as $queue)
			{	
				$now_queue_body = $queue->email_body. '<img src="'.site_url('emailtracking/'.$queue->id.'png').'" height="1" width="1" />';
				if(send_email($queue->email_to,$queue->email_from,$queue->email_subject,$now_queue_body))
				{
					$this->email_template->update_email_queue_by_id($queue->id,array("is_sent"=>2,"sent_at"=>date('Y-m-d H:i:s')));
					echo "Email Sent To ".$queue->email_to." <br/>";
				}
			}
		}
}