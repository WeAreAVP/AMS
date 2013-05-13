<?php

if ( ! defined('BASEPATH'))
	exit('No direct script access allowed');

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
	public $track_email;

	function __construct()
	{
		$this->CI = & get_instance();
		$this->sent_now = FALSE; /* If set to true then email will sent immediately */
		log_message('debug', 'Email Templates Initialized');
		/* Load Email Tempalte Model */
		$this->CI->load->model('email_template_model', 'email_templates');
		$this->track_email = true; /* If set to true then email will track */
	}

	/*
	  Insert Data in to email queue table also check if sent
	 */

	function queue_email($template_sys_id, $email_to, $replace_able = '')
	{
		$email_template = $this->CI->email_templates->get_template_by_sys_id($template_sys_id);

		if (isset($email_template) && ! empty($email_template))
		{
			if (valid_email($email_to))
			{

				$queue_data = array();
				$queue_data['template_id'] = $email_template->id;
				$queue_data['email_from'] = $email_template->email_from;
				$queue_data['email_reply_to'] = $email_template->reply_to;
				$queue_data['email_to'] = $email_to;
				$queue_data['email_subject'] = $email_template->subject;
				$queue_data['email_type'] = $email_template->email_type;
				if ($email_template->email_type == 'plain')
				{
					$email_body = $email_template->body_plain;
				}
				else
				{
					$email_body = $email_template->body_html;
				}
				if (isset($email_body) && ! empty($email_body))
				{
					$template_replaceable = json_decode($email_template->replaceables, true);
					if (isset($template_replaceable) && ! empty($template_replaceable) && ! empty($replace_able))
					{
						foreach ($template_replaceable as $replaceable_key)
						{
							if (isset($replace_able[$replaceable_key]) && ! empty($replace_able[$replaceable_key]))
							{
								$email_body = str_replace("{" . $replaceable_key . "}", $replace_able[$replaceable_key], $email_body);
							}
							else
							{
								log_message('error', 'Email template Replaceable ' . $replaceable_key . ' not found.');
								return false;
							}
						}
					}
				}
				else
				{

					log_message('error', 'Email template body not define.');
					return false;
				}
				$queue_data['email_body'] = $email_body;
				$queue_data['created_at'] = date('Y-m-d H:i:s');
				$queue_data['is_sent'] = 1;

				$last_inserted_id = $this->CI->email_templates->add_email_queue($queue_data);
				if ($this->sent_now)
				{
					$now_queue_data = $queue_data['email_body'];
					if ($this->track_email)
					{
						$now_queue_data .='<img src="' . site_url('emailtracking/' . $last_inserted_id . 'png') . '" height="1" width="1" />';
					}
					if ($this->config->item('demo') == FALSE)
						send_email($queue_data['email_to'], $queue_data['email_from'], $queue_data['email_subject'], $queue_data['email_body'], $queue_data['email_reply_to']);
					$this->CI->email_templates->update_email_queue_by_id($last_inserted_id, array("is_sent" => 2, "sent_at" => date('Y-m-d H:i:s')));
				}
				return $last_inserted_id;
			}
		}
		else
		{
			log_message('error', 'Email template ' . $template_sys_id . ' not found.');
			return false;
		}
	}

}
