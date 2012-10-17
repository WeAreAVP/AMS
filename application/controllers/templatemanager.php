<?php
class TemplateManager extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->layout = 'main_layout.php';
		$this->load->library('Form_validation');
		$this->load->helper('form');
		$this->load->model('email_template_model','email_template');
	}
	function system_id_check($system_id)
	{
		$result = $this->email_template->get_template_by_sys_id($system_id);
		if ($result)
		{
			$this->form_validation->set_message('system_id', 'System Id is already used. Please choose another system id.');
		}
				
		return $result;
	} 
	function addtemplate()
	{
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
				$email_template_data['replaceables']=$val->set_value('replaceables');
				$email_template_data['created_date']=date("Y-m-d H:i:s");			
				print_r($email_template_data);	
			}
		}
		$this->load->view("templatemanager/add_template");
	}
}
?>