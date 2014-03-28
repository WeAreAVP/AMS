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
 * Templatemanager Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @copyright  Copyright (c) WGBH (http://www.wgbh.org/). All Rights Reserved.
 * @license    http://www.gnu.org/licenses/gpl.txt GPLv3
 * @link       https://ams.americanarchive.org
 */
class Templatemanager extends MY_Controller
{

	/**
	 * constructor. Load layout,Model,Library and helpers
	 * 
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->model('sphinx_model', 'sphinx');
		if ($this->role_id == 20)
		{
			
		}
		else if ($this->is_station_user)
		{
			redirect('records/index');
		}
		else if ( ! $this->can_compose_alert)
		{
			redirect(site_url());
		}
	}

	/**
	 * Check the name of the tempate that it already exist.
	 * 
	 * @param integer $system_id email template system id
	 * 
	 * @return boolean 
	 */
	function system_id_check($system_id)
	{
		$result = $this->email_template->get_template_by_sys_id(str_replace(' ', '_', $system_id));
		if ($result)
		{
			$this->form_validation->set_message('system_id_check', 'System Id is already used. Please choose another system id.');
			return FALSE;
		}
		return TRUE;
	}

	/**
	 *  Add new template
	 * 
	 * @return templatemanager/add_template View
	 */
	public function add()
	{
		$data['add_temp'] = FALSE;
		if ($this->input->post())
		{

			$form_val = $this->form_validation;
			$form_val->set_rules('system_id', 'System Id', 'trim|required|xss_clean|callback_system_id_check');
			$form_val->set_rules('subject', 'Subject', 'trim|required|xss_clean');

			$form_val->set_rules('body_html', 'HTML Body', 'trim');
			$form_val->set_rules('replaceables', 'Replaceables', 'trim|xss_clean');
			$form_val->set_rules('email_from', 'Email From', 'trim|required|xss_clean|valid_email');
			$form_val->set_rules('reply_to', 'Reply To', 'trim|required|xss_clean|valid_email');
			if ($this->input->post('body_html') == '')
			{
				$this->form_validation->set_message('body_html', 'You must enter html body');
			}
			if ($form_val->run())
			{
				$email_template_data = array();
				$email_template_data['system_id'] = str_replace(' ', '_', $form_val->set_value('system_id'));
				$email_template_data['subject'] = $form_val->set_value('subject');
				$email_template_data['email_type'] = 'html';


				$email_template_data['body_html'] = $this->input->post('body_html');

				$email_template_data['email_from'] = $form_val->set_value('email_from');
				$email_template_data['reply_to'] = $form_val->set_value('reply_to');
				$replaceable = explode("\n", $form_val->set_value('replaceables'));
				$email_template_data['replaceables'] = isset($replaceable) ? json_encode($replaceable) : '';
				$email_template_data['created_date'] = date('Y-m-d H:i:s');

				$this->email_template->add_email_template($email_template_data);
				$data['add_temp'] = TRUE;
				redirect('templatemanager/lists/added');
			}
		}
		$this->load->view('templatemanager/add_template', $data);
	}

	/**
	 *  List all the available email templates
	 * 
	 * @return templatemanager/list View
	 */
	public function lists()
	{
		$data['message'] = $this->uri->segment(3);
		$data['templates'] = $this->email_template->get_all();
		$this->load->view('templatemanager/list', $data);
	}

	/**
	 * Show Detail of specific templates
	 * 
	 * @param string $template_id template id
	 * 
	 * @return templatemanager/detail View
	 * 
	 */
	public function details($template_id = '')
	{
		if (isset($template_id) && ! empty($template_id))
		{
			$data['template_id'] = $template_id;
			$data['templates'] = $this->email_template->get_all();
			$data['template_detail'] = $this->email_template->get_template_by_id($template_id);
			$this->load->view('templatemanager/detail', $data);
		}
		else
		{
			redirect('templatemanager/lists');
		}
	}

	/**
	 * Detele template
	 * 
	 * @param string $template_id template id
	 * 
	 * @return mixed
	 * 
	 */
	public function delete($template_id = '')
	{
		$message = '';
		if (isset($template_id) && ! empty($template_id))
		{
			if ($this->email_template->delete_template($template_id))
			{
				$this->load->view('templatemanager/detail', $data);
				$message = 'deleted';
			}
		}
		redirect('templatemanager/lists/' . $message);
	}

	/**
	 * Edit the email template
	 * 
	 * @param integer $template_id template id
	 * 
	 * @return templatemanager/edit_template
	 */
	public function edit($template_id)
	{
		if (isset($template_id) && ! empty($template_id))
		{
			$data['update_temp'] = FALSE;
			$data['template_id'] = $template_id;
			$data['template_detail'] = $this->email_template->get_template_by_id($template_id);
			if (isset($data['template_detail']) && ! empty($data['template_detail']))
			{
				if ($this->input->post())
				{
					$form_val = $this->form_validation;

					$form_val->set_rules('subject', 'Subject', 'trim|required|xss_clean');
					$form_val->set_rules('body_html', 'HTML Body', 'trim');
					$form_val->set_rules('replaceables', 'Replaceables', 'trim|xss_clean');

					$form_val->set_rules('email_from', 'Email From', 'trim|required|xss_clean');
					$form_val->set_rules('reply_to', 'Reply To', 'trim|required|xss_clean');
					if ($this->input->post('body_html') == '')
					{
						$this->form_validation->set_message('body_html', 'You must enter html body');
					}
					if ($form_val->run())
					{
						$email_template_data = array();
						$email_template_data['subject'] = $form_val->set_value('subject');
						$email_template_data['email_type'] = 'html';
						$email_template_data['body_html'] = $this->input->post('body_html');
						$email_template_data['email_from'] = $form_val->set_value('email_from');
						$email_template_data['reply_to'] = $form_val->set_value('reply_to');
						$replaceable = explode("\n", $form_val->set_value('replaceables'));
						$email_template_data['replaceables'] = isset($replaceable) ? json_encode($replaceable) : '';
						$this->email_template->update_email_template($data['template_id'], $email_template_data);
						$data['add_temp'] = TRUE;
						redirect('templatemanager/lists/updated');
					}
				}
			}
			else
			{
				redirect('templatemanager/lists');
			}
			$this->load->view('templatemanager/edit_template', $data);
		}
	}

	/**
	 * Manage the crawford contact detail 
	 * 
	 * @return templatemanager/manage_crawfod
	 */
	public function manage_crawford()
	{
		$data['is_updated'] = FALSE;
		if ($this->input->post() && $this->role_id != 20)
		{
			$form_val = $this->form_validation;

			$form_val->set_rules('crawford_contact_details', 'Crawford Contact Detail', 'trim|required|xss_clean');
			if ($form_val->run())
			{
				$data['is_updated'] = TRUE;
				$crawford_detail = $this->input->post('crawford_contact_details');
				$this->email_template->update_email_template(NULL, array('crawford_contact_detail' => $crawford_detail));
			}
		}
		$data['detail'] = $this->email_template->get_crawford_detail();

		$this->load->view('templatemanager/manage_crawfod', $data);
	}

}

// END Template Manager Controller

// End of file templatemanager.php 
/* Location: ./application/controllers/templatemanager.php */