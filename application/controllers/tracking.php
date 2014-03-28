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
 * Tracking Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @copyright  Copyright (c) WGBH (http://www.wgbh.org/). All Rights Reserved.
 * @license    http://www.gnu.org/licenses/gpl.txt GPLv3
 * @link       https://ams.americanarchive.org
 */
class Tracking extends MY_Controller
{

	/**
	 * Constructor
	 * 
	 * Load the layout and tracking model
	 * 
	 */
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * Create a new Tracking Record
	 * Get station_id as uri segment 3
	 * 
	 * @return tracking/add view
	 */
	public function add()
	{
		$this->layout = 'default.php';
		$data['station_id'] = $this->uri->segment(3);
		$form_val = $this->form_validation;
		$form_val->set_rules('tracking_ship_date', 'Ship Date', 'trim|required|xss_clean');
		$form_val->set_rules('ship_to', 'Ship To', 'trim|required|xss_clean');
		$form_val->set_rules('ship_via', 'Ship Via', 'trim|required|xss_clean');
		$form_val->set_rules('tracking_no', 'Tracking #', 'trim|required|xss_clean');
		$form_val->set_rules('no_box_shipped', '# of box shipped', 'trim|required|is_natural|xss_clean');
		$form_val->set_rules('media_received_date', 'Media Received Date', 'trim|xss_clean');
		if ($this->input->post())
		{
			if ($form_val->run())
			{
				$tracking_no = $this->input->post('tracking_no');
				$record = array('ship_date' => date('Y-m-d', strtotime($form_val->set_value('tracking_ship_date'))),
					'ship_to' => $form_val->set_value('ship_to'),
					'ship_via' => $form_val->set_value('ship_via'),
					'tracking_no' => $tracking_no,
					'no_box_shipped' => $form_val->set_value('no_box_shipped'),
					'station_id' => $data['station_id'],
					'media_received_date' => $form_val->set_value('media_received_date'),
				);

				$inserted_id = $this->tracking->insert_record($record);
				$tracking_info = $this->tracking->get_by_id($inserted_id);
				$this->shipment_tracking_email($tracking_info);
				echo 'done';
				exit_function();
			}
			else
			{
				$errors = $form_val->error_string();
				$data['errors'] = $errors;
			}
		}
		echo $this->load->view('tracking/add', $data, TRUE);
		exit_function();
	}

	/**
	 * Edit Tracking Information.
	 * Get tracking_id as uri segment 3
	 * 
	 * @return tracking/edit view
	 */
	public function edit()
	{
		$this->layout = 'default.php';
		$tracking_id = $this->uri->segment(3);
		$form_val = $this->form_validation;

		$form_val->set_rules('tracking_ship_date', 'Ship Date', 'trim|required|xss_clean');
		$form_val->set_rules('ship_to', 'Password', 'trim|required|xss_clean');
		$form_val->set_rules('ship_via', 'First Name', 'trim|required|xss_clean');
		$form_val->set_rules('tracking_no', 'Last Name', 'trim|required|xss_clean');
		$form_val->set_rules('no_box_shipped', 'Phone #', 'trim|required|is_natural|xss_clean');
		$form_val->set_rules('media_received_date', 'Media Received Date', 'trim|xss_clean');

		if ($this->input->post())
		{
			if ($form_val->run())
			{
				$tracking_no = $this->input->post('tracking_no');
				$record = array('ship_date' => date('Y-m-d', strtotime($form_val->set_value('tracking_ship_date'))),
					'ship_to' => $form_val->set_value('ship_to'),
					'ship_via' => $form_val->set_value('ship_via'),
					'tracking_no' => $tracking_no,
					'no_box_shipped' => $form_val->set_value('no_box_shipped'),
					'media_received_date' => $form_val->set_value('media_received_date'),
				);
				$this->tracking->update_record($tracking_id, $record);
				$tracking_info = $this->tracking->get_by_id($tracking_id);
				$this->shipment_tracking_email($tracking_info);
				echo 'done';
				exit_function();
			}
			else
			{
				$errors = $form_val->error_string();
				$data['errors'] = $errors;
			}
		}
		$data['tracking_info'] = $this->tracking->get_by_id($tracking_id);
		echo $this->load->view('tracking/edit', $data, TRUE);
		exit_function();
	}

	/**
	 * Delete the tracking info
	 * Get station_id as uri segment 4
	 * Get tracking_id as uri segment 3
	 *  
	 * @return mixed
	 */
	public function delete()
	{
		$tracking_id = $this->uri->segment(3);
		$staion_id = $this->uri->segment(4);
		$this->tracking->delete_record($tracking_id);
		redirect('stations/detail/' . $staion_id, 'location');
	}

	/**
	 * Send Email on Add/Edit station tracking info
	 * 
	 * @param array $record get record of a station
	 * 
	 * @return boolean 
	 */
	function shipment_tracking_email($record)
	{

		$template = '_Tracking_Ship_Date';
		$template_data = $this->email_template->get_template_by_sys_id($template);
		$extra = array();
		if (isset($template_data) && ! empty($template_data))
		{
			$station_details = $this->station_model->get_station_by_id($record->station_id);
			$subject = $template_data->subject;

			$extra['ship_date'] = $replacebale['ship_date'] = $record->ship_date;
			$extra['ship_via'] = $replacebale['ship_via'] = $record->ship_via;
			$extra['tracking_no'] = $replacebale['tracking_no'] = nl2br($record->tracking_no);
			$extra['no_box_shipped'] = $replacebale['no_box_shipped'] = $record->no_box_shipped;
			$extra['station_name'] = $replacebale['station_name'] = isset($station_details->station_name) ? $station_details->station_name : '';


			$replacebale['inform_to'] = 'ssapienza@cpb.org';
//												$this->emailtemplates->sent_now	=	TRUE;
			if ($this->session->userdata['DX_email'] !== $this->config->item('crawford_email'))
			{
				$user = $this->users->get_user_by_email($this->config->item('crawford_email'))->row();
				$data = array('sender_id' => $this->user_id, 'receiver_id' => $user->id, 'station_id' => $record->station_id, 'msg_type' => $template, 'subject' => $subject, 'msg_extras' => json_encode($extra), 'created_at' => date('Y-m-d h:m:i'));
				$email_queue_id = $this->emailtemplates->queue_email($template, $this->config->item('crawford_email'), $replacebale);
				if (isset($email_queue_id) && $email_queue_id)
				{
					$data['email_queue_id'] = $email_queue_id;
				}
				$this->msgs->add_msg($data);
			}


			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 * Get Tracking information
	 * 
	 * @return json
	 *  
	 */
	public function get_tracking_info()
	{
		$stations = $this->input->post('stations');
		$type = $this->input->post('type');
		$list = array();
		foreach ($stations as $station_id)
		{
			$tracking_info = $this->tracking->get_last_tracking_info($station_id);
			$station = $this->station_model->get_station_by_id($station_id);
			if (count($tracking_info) > 0)
			{

				if (empty($tracking_info->$type) OR $tracking_info->$type === NULL)
				{
					$list[] = array('tracking_id' => $tracking_info->id, 'station_id' => $station_id, $type => '', 'station_name' => $station->station_name);
				}
				else
				{
					$list[] = array('tracking_id' => $tracking_info->id, 'station_id' => $station_id, $type => $tracking_info->$type, 'station_name' => $station->station_name);
				}
			}
			else
			{
				$list[] = array('tracking_id' => '', 'station_id' => $station_id, $type => '', 'station_name' => $station->station_name);
			}
		}
		echo json_encode($list);
		exit_function();
	}

	/**
	 * Update the tracking information
	 * 
	 * @return json
	 */
	public function update_tracking_info()
	{
		if (isAjax())
		{
			$dates = $this->input->post();
			foreach ($dates as $index => $value)
			{
				$tracking_id = explode('_', $index);
				$tracking_id = $tracking_id[count($tracking_id) - 1];
				$media_date = date('Y-m-d', strtotime($value));
				$this->tracking->update_record($tracking_id, array('media_received_date' => $media_date));
			}
			echo json_encode(array('success' => TRUE));
			exit_function();
		}
		show_404();
	}

}

// END Tracking

// End of file tracking.php
/* Location: ./application/controllers/tracking.php */