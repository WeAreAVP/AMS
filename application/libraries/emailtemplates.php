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
	*/
	function queue_email($template_sys_id,$email_to,$replace_able='')
	{
		$email_template=$this->email_template->get_template_by_sys_id($template_sys_id);
		if(isset($email_template) && !empty($email_template))
		{
			if(valid_email($email_to))
			{
				$queue_data=array();
				$queue_data['template_id']=$email_template->id;
				$queue_data['email_from']=$email_template->email_from;
				$queue_data['email_reply_to']=$email_template->reply_to;
				$queue_data['email_to']=$email_to;
				$queue_data['email_subject']=$email_template->subject;
				$queue_data['email_type']=$email_template->email_type;
				if($email_template->email_type=='plain')
				{
					$queue_data['email_body']=$email_template->body_plain;
				}
				else
				{
					$queue_data['email_body']=$email_template->body_html;
				}
				if(isset($queue_data['email_body']) && !empty($queue_data['email_body']))
				{
					if(isset($email_template->replaceables) && !empty($email_template->replaceables) && !empty($replace_able))
					{
						foreach($email_template->replaceables as $replaceable_key)
						{
							if(isset($replace_abl[$replaceable_key]) && !empty($replace_abl[$replaceable_key]))
							{
								str_replace("{".$replaceable_key."}",$replace_abl[$replaceable_key],$queue_data['email_body']);
							}
							else
							{
								log_message('error', 'Email template Replaceable '.$replaceable_key.' not found.' );
								return false;
							}
						}
					}
				}else{log_message('error', 'Email template body not define.' );}
				$queue_data['created_at']=date('Y-m-d H:i:s');
				$queue_data['is_sent']=1;
				$queue_data['sent_at']=date('Y-m-d H:i:s');
				print_r($queue_data);
				return true;
			}
		}
		else
		{
			log_message('error', 'Email template '.$template_sys_id.' not found.' );
			return false;
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
