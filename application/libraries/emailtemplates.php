<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Email Templates Class
 *
 * Email Templates library for Code Igniter.
 *
 * @author		Ali Raza
 */
class Emailtemplates
{

	private $CI;
 	var $sent_now;
  function __construct()
  {
    $this->CI =& get_instance();
		$this->sent_now=false;/*If set to true then email will sent immediately*/
		log_message('debug', 'Email Templates Initialized');
		/*Load Email Tempalte Model */
		$this->load->model('email_template_model','email_template');
  
	}
	/*
		Insert Data in to email queue table also check if sent
		$extra array must contain email_to and replaceables
	*/
	function queue_email($template_sys_id,$email_to,$replace_able='')
	{
		$email_template=$this->email_template->get_template_by_sys_id($template_sys_id);
		if(isset($email_template) && !empty($email_template))
		{
			if(valid_email($email_to))
			{
				log_message('error', 'Email address '.$email_to.' is not valid.' );
				$queue_data=array();
				$queue_data['template_id']=$email_template->id;
				$queue_data['email_from']=$email_template->email_from;
				$queue_data['email_reply_to']=$email_template->reply_to;
				$queue_data['email_to']=$email_to;
				$queue_data['email_subject']=$email_template->subject;
				$queue_data['email_type']=$email_template->email_type;
				if($email_template->email_type=='plain')
				{
					if(!empty($replace_able))
					{
						$queue_data['email_body']=
						$email_template->body_html;
					}
					else
					{
						$queue_data['email_body']=
					}
				}
				else
				{
					body_plain
				}
				
				$queue_data['created_at']=
				$queue_data['is_sent']=
				$queue_data['sent_at']=
				
				












			}
		}
		else
		{
			log_message('error', 'Email template '.$template_sys_id.' not found.' );
		}
	}
	function _email($to, $from, $subject, $message)
	{
		$this->CI->load->library('Email');
		$email = $this->ci->email;
		$email->from($from);
		$email->to($to);
		$email->subject($subject);
		$email->message($message);
		return $email->send();
	}
}
