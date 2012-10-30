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
 	public $sent_now;
  function __construct()
  {
    $this->CI =& get_instance();
		$this->sent_now=false;/*If set to true then email will sent immediately*/
		log_message('debug', 'Email Templates Initialized');
		/*Load Email Tempalte Model */
		$this->CI->load->model('email_template_model','email_templates');
  
	}
	/*
		Insert Data in to email queue table also check if sent
	*/
	function queue_email($template_sys_id,$email_to,$replace_able='')
	{
		$email_template=$this->CI->email_templates->get_template_by_sys_id($template_sys_id);
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
					$email_body=$email_template->body_plain;
				}
				else
				{
					$email_body=$email_template->body_html;
				}
				if(isset($email_body) && !empty($email_body))
				{
					$template_replaceable=json_decode($email_template->replaceables,true);
					if(isset($template_replaceable) && !empty($template_replaceable) && !empty($replace_able))
					{
						foreach($template_replaceable as $replaceable_key)
						{
							if(isset($replace_able[$replaceable_key]) && !empty($replace_able[$replaceable_key]))
							{
								$email_body=str_replace("{".$replaceable_key."}",$replace_able[$replaceable_key],$email_body);
							}
							else
							{
								log_message('error', 'Email template Replaceable '.$replaceable_key.' not found.' );
								return false;
							}
						}
					}
				}
				else
				{
					log_message('error', 'Email template body not define.' );
					return false;
				}
				$queue_data['email_body']=$email_body;
				$queue_data['created_at']=date('Y-m-d H:i:s');
				$queue_data['is_sent']=1;
				if($this->sent_now)
				{
					$this->_email($queue_data['email_to'],$queue_data['email_from'],$queue_data['email_subject'],$queue_data['email_body']);
					$queue_data['is_sent']=2;
					$queue_data['sent_at']=date('Y-m-d H:i:s');
				}
				$this->CI->email_templates->add_email_queue($queue_data);
				return true;
			}
		}
		else
		{
			log_message('error', 'Email template '.$template_sys_id.' not found.' );
			return false;
		}
	}
	/* Sent Email if $this->sent_now is set true*/
	function _email($to, $from, $subject, $message)
	{
		$this->CI->load->library('Email');
		$config['wordwrap'] = TRUE;
		$config['validate'] = TRUE;
		$config['mailtype'] = 'html';
		$config['charset'] = 'utf-8';
		$config['protocol'] = 'sendmail';
		$email = $this->CI->email;
		$email->clear();
		$email->initialize($config);
		$email->from($from);
		$email->to($to);
		$email->subject($subject);
		$email->message($message);
		if($email->send())
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}
