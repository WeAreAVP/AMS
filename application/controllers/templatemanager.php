<?php

/**
 * Settings controller.
 *
 * @package    AMS
 * @subpackage Template Manager
 * @author     Ali Raza
 */
class TemplateManager extends CI_Controller
{
	/**
     * constructor. Load layout,Model,Library and helpers
     * 
     */
	function __construct()
	{
		parent::__construct();
		$this->layout = 'main_layout.php';
		$this->load->library('Form_validation');
		$this->load->helper('form');
		$this->load->model('email_template_model','email_template');
	}
	private function system_id_check($system_id)
	{
		$result = $this->email_template->get_template_by_sys_id($system_id);
		if ($result)
		{
			$this->form_validation->set_message('system_id_check', 'System Id is already used. Please choose another system id.');
			return false;
		}	
		return true;
	} 
	/*
		*
  	* Add Custom template
 	 	*  
  */
	public function add()
	{
		$data['add_temp']=false;
		if(isset($_POST) && !empty($_POST) )
		{
			$val = $this->form_validation;
			$val->set_rules('system_id', 'System Id', 'trim|required|xss_clean|callback_system_id_check');
			$val->set_rules('subject', 'Subject', 'trim|required|xss_clean');
			$val->set_rules('body_plain', 'Plain Body', 'trim|xss_clean');
			$val->set_rules('body_html', 'Plain Body', 'trim|xss_clean');
			$val->set_rules('replaceables', 'Replaceables', 'trim|xss_clean');
			$val->set_rules('email_type', 'Email Type', 'trim|required|xss_clean');
			$val->set_rules('email_from', 'Email From', 'trim|required|xss_clean');
			$val->set_rules('reply_to', 'Reply To', 'trim|required|xss_clean');
			if ($val->run() && (isset($_POST['body_plain']) || isset($_POST['body_html'])))
			{
				$email_template_data=array();
				$email_template_data['system_id']=$val->set_value('system_id');
				$email_template_data['subject']=$val->set_value('subject');
				$email_template_data['body_plain']=$val->set_value('body_plain');
				$email_template_data['body_html']=$val->set_value('body_html');
				$email_template_data['email_type']=$val->set_value('email_type');
				$email_template_data['email_from']=$val->set_value('email_from');
				$email_template_data['reply_to']=$val->set_value('reply_to');
				$replaceable=explode("\n",$val->set_value('replaceables'));
				$email_template_data['replaceables']= isset($replaceable)?json_encode($replaceable):'';
				$email_template_data['created_date']=date("Y-m-d H:i:s");			
				$this->email_template->add_email_template($email_template_data);
				$data['add_temp']=true;
				redirect('templatemanager/list/added');
			}
		}
		$this->load->view("templatemanager/add_template",$data);
	}
	/* Lsit all Templates*/
	public function lists()
	{
		$data['info'] = $this->uri->segment(3);
		$data['templates']=$this->email_template->get_all();
		$this->load->view("templatemanager/list",$data);
	}
	/**
	* Show Detail of specific templates
	* 
	* @param $station_id as a uri segment
	*/
	public function details($template_id='')
	{
		if(isset($template_id) && !empty($template_id))
		{
			$data['template_detail'] = $this->email_template->get_template_by_id($template_id);
			$this->load->view('templatemanager/detail', $data);
		}
		else
		{
			redirect('templatemanager/list');
		}
	}

}
?>