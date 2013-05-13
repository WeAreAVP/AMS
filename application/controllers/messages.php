<?php

/**
 * Messages Controller
 * 
 * PHP version 5
 * 
 * @category   AMS
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@geekschicago.com>
 * @license    AVPS http://ams.avpreserve.com
 * @version    GIT: <$Id>
 * @link       http://ams.avpreserve.com
 */

/**
 * Messages Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@geekschicago.com>
 * @license    AVPS http://ams.avpreserve.com
 * @link       http://ams.avpreserve.com
 */
class Messages extends MY_Controller
{

	/**
	 * Redirect to inbox
	 *  
	 */
	public function index()
	{
		redirect('messages/inbox', 'location');
	}

	/**
	 * List Received Message 
	 * 
	 * @return view 
	 */
	public function inbox()
	{
		$where = '';
		if ($this->input->post())
		{
			if ($this->input->post('message_type') != '0')
			{
				$where['msg_type'] = $this->input->post('message_type');
			}
			if ($this->input->post('stations') != '')
			{
				$where['station_id'] = $this->input->post('stations');
			}
		}
		$receiver_id = $this->user_id;
		$data['results'] = $this->msgs->get_inbox_msgs($receiver_id, $where);
		$data['station_records'] = $this->station_model->get_all();
		if (isAjax())
		{
			$data['is_ajax'] = true;
			echo $this->load->view('messages/inbox', $data, true);
			exit;
		}
		else
		{
			$data['is_ajax'] = false;
			$this->load->view('messages/inbox', $data);
		}
	}

	/**
	 * List Sent Message 
	 *  
	 */
	public function sent()
	{
		if (in_array($this->role_id, array(1, 2, 5)))
		{
			$where = '';
			if ($this->input->post())
			{
				if ($this->input->post('message_type') != '0')
				{
					$where['msg_type'] = $this->input->post('message_type');
				}
				if ($this->input->post('stations') != '')
				{
					$where['station_id'] = $this->input->post('stations');
				}
			}
			$data['station_records'] = $this->station_model->get_all();
			$data['results'] = $this->msgs->get_sent_msgs($this->user_id, $where);
			if (isAjax())
			{
				$data['is_ajax'] = true;
				echo $this->load->view('messages/sent', $data, true);
				exit;
			}
			else
			{
				$data['is_ajax'] = false;
				$this->load->view('messages/sent', $data);
			}
		}
		else
		{
			show_404();
		}
	}

	/**
	 * Get the message type and load the respective view. Receive an ajax call
	 * 
	 * @param $type as post parameter
	 * @return html view for message type
	 *  
	 */
	public function get_message_type()
	{
		if (isAjax())
		{
			$type = $this->input->post('type');
			$messagesType = $this->config->item('messages_type');
			$messageType = '_' . str_replace(' ', '_', strtolower($messagesType[$type]));
			$messageTypeFields = str_replace(' ', '_', $messagesType[$type]);
			$data['record'] = $this->email_template->get_template_by_sys_id($messageTypeFields);
			$data['is_ajax'] = true;
			echo $this->load->view('messages/' . $messageType, $data, TRUE);
			exit;
		}
		show_404();
	}

	/**
	 * Recieve the compose message parameteres. Store in database and send email
	 * Receive an ajax call
	 * 
	 * @param $to receiver ids
	 * @param $html for email body
	 * @param $type message type
	 * @param $extaras receive the remaining fields as an array
	 *  
	 */
	public function compose()
	{
		if ($this->input->post() && isAjax())
		{
			$alerts_array = $this->config->item('messages_type');
			$html = $this->input->post('html');
			$type = $this->input->post('type');
			$same_dsd = ($this->input->post('same_dsd')) ? $this->input->post('same_dsd') : false;
			$template = str_replace(" ", "_", $alerts_array[$type]);
			$template_data = $this->email_template->get_template_by_sys_id($template);
			$multiple_station = $this->input->post('to');
			$extra = $this->input->post('extras');
			if (isset($template_data) && ! empty($template_data))
			{
				if (isset($multiple_station) && ! empty($multiple_station))
				{
					$this->compose_station_message($multiple_station, $template_data, $extra, $template, $type);
					echo json_encode(array('success' => TRUE));
					exit;
				}
				else
				{
					echo json_encode(array('success' => FALSE, "error_id" => 1));
					exit;
				}
			}
			else
			{
				echo json_encode(array('success' => FALSE, "error_id" => 2));
				exit;
			}
		}
		else
		{
			show_404();
		}
	}

	/**
	 *
	 * @param array $multiple_station
	 * @param array $template_data
	 * @param array $extra
	 * @param string $template
	 * @param integer $type 
	 */
	function compose_station_message($multiple_station, $template_data, $extra, $template, $type)
	{
		$message_type_check = 0;
		foreach ($multiple_station as $to)
		{

			$station_details = $this->station_model->get_station_by_id($to);
			$subject = $template_data->subject;

			if ($template == 'Digitization_Start_Date')
			{
				$extra['ship_date'] = $station_details->start_date;
			}
			else if ($template == 'Materials_Received_Digitization_Vendor')
			{

				$tracking_info = $this->tracking->get_last_tracking_info($to);
				if (count($tracking_info) > 0)
				{
					if (empty($tracking_info->media_received_date) || $tracking_info->media_received_date == NULL)
					{
						$message_type_check = 1;
					}
					else
					{
						$message_type_check = 0;
						$extra['date_received'] = $tracking_info->media_received_date;
					}
				}
			}
			else if ($template == 'Shipment_Return')
			{

				$tracking_info = $this->tracking->get_last_tracking_info($to);
				if (count($tracking_info) > 0)
				{
					if (empty($tracking_info->ship_date) || $tracking_info->ship_date == NULL)
					{
						$message_type_check = 1;
					}
					else
					{
						$message_type_check = 0;
						$extra['ship_date'] = $tracking_info->ship_date;
					}
				}
			}
			if ($message_type_check == 0)
			{
				foreach ($extra as $key => $value)
				{
					$replacebale[$key] = (isset($value) && ! empty($value)) ? $value : '';
				}

				$replacebale['station_name'] = isset($station_details->station_name) ? $station_details->station_name : '';
				$replacebale['inform_to'] = 'cstephenson@mail.crawford.com';
				$replacebale['user_name'] = 'The American Archive';
				$station_admin_users = $this->msgs->get_station_admin($to);
				foreach ($station_admin_users as $row)
				{
					$to_email = $row->email;
					$email_queue_id = $this->emailtemplates->queue_email($template, $to_email, $replacebale);
					$data = array('sender_id' => $this->user_id, 'station_id' => $to, 'receiver_id' => $row->id, 'msg_type' => $type, 'subject' => $subject, 'msg_extras' => json_encode($extra), 'created_at' => date('Y-m-d h:m:i'));
					if (isset($email_queue_id) && $email_queue_id)
						$data['email_queue_id'] = $email_queue_id;
					$this->msgs->add_msg($data);
					$this->session->set_userdata('sent', 'Message Sent');
				}
			}
		}
	}

	/**
	 * Recieve the message id
	 * 
	 * @param $message_id msg id
	 *  Display Message details
	 */
	public function readmessage($message_id = '')
	{
		if (isAjax())
		{
			$rslt["total_unread_text"] = '<a class="message_box" href="' . site_url('messages/inbox') . '"><i class="icon-envelope icon-white"></i></a>';
			$rslt["error"] = TRUE;
			$rslt["reset_row"] = FALSE;
			if ($message_id != '')
			{
				$receiver_id = $this->user_id;
				$data['result'] = $this->msgs->get_inbox_msgs($receiver_id, array("id" => $message_id));
				if (isset($data['result']) && ! empty($data['result']) && $data['result'][0]->msg_status == 'unread' && ( ! $this->can_compose_alert))
				{
					$this->msgs->update_msg_by_id($message_id, array("msg_status" => 'read', "read_at" => date('Y-m-d H:i:s')));
					$this->total_unread = $this->msgs->get_unread_msgs_count($this->user_id);
					if (isset($this->total_unread) && $this->total_unread > 0 && $this->is_station_user)
					{
						$rslt["total_unread_text"] = '<a class="message_box" href="' . site_url('messages/inbox') . '"><i class="icon-envelope icon-white"></i><span class="badge label-important message-alert">' . $this->total_unread . '</span></a>';
						$rslt["reset_row"] = TRUE;
					}
				}
				$rslt["error"] = FALSE;
				$rslt["msg_data"] = $this->load->view('messages/read_msg', $data, true);
				echo json_encode($rslt);
				exit;
			}
			else
			{
				echo json_encode($rslt);
				exit;
			}
		}
		else
		{
			show_404();
		}
	}

	public function readsentmessage($message_id = '')
	{
		if (isAjax())
		{
			$rslt["error"] = true;
			if ($this->can_compose_alert)
			{
				if ($message_id != '')
				{
					$data['result'] = $this->msgs->get_sent_msgs($this->user_id, array("id" => $message_id));
					$rslt["error"] = FALSE;
					$rslt["msg_data"] = $this->load->view('messages/read_msg', $data, TRUE);
					echo json_encode($rslt);
					exit;
				}
				else
				{
					echo json_encode($rslt);
					exit;
				}
			}
			else
			{
				echo json_encode($rslt);
				exit;
			}
		}
		else
		{
			show_404();
		}
	}

	public function assets_list()
	{
		$this->unset_facet_search();
		$record_type = $this->uri->segment(3);
		$station_ids = explode(',', $this->input->post('station_ids'));
		$stations = $this->station_model->get_stations_by_id($station_ids);
		$station_name = '';
		foreach ($stations as $index => $station)
		{

			if ($index === 0)
				$station_name .=$station->station_name;
			else
				$station_name .='|||' . $station->station_name;
		}
		$this->session->set_userdata('organization', $station_name);
		if ($record_type === '1')
		{
			$this->session->set_userdata('digitized', '1');
			$this->session->set_userdata('organization', 'Appalshop, Inc. (WMMT and Appalshop Films)|||Arkansas Educational TV Network (AETN)');
			redirect('records/index');
		}
		else if ($record_type === '2')
		{
			$this->session->set_userdata('migration_failed', '1');
			redirect('records/index');
		}
		else if ($record_type === '3')
		{
			$this->session->set_userdata('nomination', 'Nominated/2nd Priority');
			redirect('records/index');
		}
		else
		{
			show_404();
		}
	}

}

?>