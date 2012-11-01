<?php if (!defined('BASEPATH'))exit('No direct script access allowed');

/**
 * Messages controller.
 *
 * @package    AMS
 * @subpackage Messeges Alerts
 * @author     Ali Raza, Nouman Tayyab
 */
class Messages extends MY_Controller {

    /**
     *
     * constructor. Load layout,Model,Library and helpers
     * 
     */
    function __construct()
		{
    	parent::__construct();
      $this->layout = 'main_layout.php';
    }
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
    */
    public function inbox()
		{
			$where='';
      if ($_POST)
			{
      	if ($_POST['message_type'])
				{
        	$where['msg_type'] = $_POST['message_type'];
        }
        if ($_POST['stations'])
				{
        	$where['sender_id'] = $_POST['stations'];
        }
     	}
      $data['results'] = $this->msgs->get_inbox_msgs($this->user_id, $where);
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
		/*
		*
    * List Sent Message 
    *  
    */
    public function sent()
		{
			$where='';
      if ($_POST)
			{
      	if ($_POST['message_type'])
				{
        	$where['msg_type'] = $_POST['message_type'];
        }
        if ($_POST['stations'])
				{
          $where['receiver_id'] = $_POST['stations'];
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
     * @param $to receiver id
     * @param $from send id
     * @param $html for email body
     * @param $type message type
     * @param $subject email subject
     * @param $extaras receive the remaining fields as an array
     *  
     */
    public function compose()
		{
    	if ($this->input->post())
			{
				$alerts_array=$this->config->item('messages_type');
				$to = $this->input->post('to');
			  $html = $this->input->post('html');
        $type = $this->input->post('type');
				$template = str_replace(" ","_",$alerts_array[$type]);
				$template_data=$this->email_template->get_template_by_sys_id($template);
				if(isset($template_data) && !empty($template_data))
				{
					$station_details = $this->station_model->get_station_by_id($to);
					$subject =$template_data->subject;
					$extra = $this->input->post('extras');
					foreach($extra as $key=>$value)
					{
						$replacebale[$key]=(isset($value) && !empty($value))?$value:'';
					}
					$replacebale['station_name']=isset($station_details->station_name)?$station_details->station_name:'';
					if ($this->config->item('demo') == true)
					{
						$to_email = $this->config->item('to_email');
						$from_email = $this->config->item('from_email');
						$replacebale['user_name']='AMS';
					}
					else
					{
						$to_email = 	$station_details->contact_email;
						$from_email = $this->user_detail->email;
						$replacebale['user_name']=$this->user_detail->first_name.' '.$this->user_detail->last_name;
					}
					$replacebale['inform_to']='ssapienza@cpb.org';
					$email_queue_id=$this->emailtemplates->queue_email('General',$station_email,$replacebale);
					$data = array('sender_id' => $from, 'receiver_id' => $to, 'msg_type' => $type, 'subject' => $subject, 'msg_extras' =>  json_encode($extra), 'created_at' => date('Y-m-d h:m:i'));
					if(isset($email_queue_id) && $email_queue_id)
					{
						$data['email_queue_id']=$email_queue_id;
					}
					$this->msgs->add_msg($data);
					$this->session->set_userdata('sent', 'Message Sent');
					echo json_encode(array('success' => true));
					exit;
				}
				else
				{
					echo json_encode(array('success' => false));
					exit;
				}
			}
			else
			{
				echo json_encode(array('success' => false));
				exit;
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
			if ($message_id != '')
			{
      	$data['result'] = $this->msgs->get_inbox_msgs($this->user_id, array("id" => $message_id));
				if(isset($data['result'] ) && !empty($data['result'] ) && $data['result'][0]->msg_status=='unread')
				{
					$this->msgs->update_msg_by_id($message_id, array("msg_status" =>'read',"read_at"=>date('Y-m-d H:i:s')));
					$this->total_unread = $this->msgs->get_unread_msgs_count($this->user_id);
				}
        $this->load->view('messages/read_msg', $data);
      }
			else
			{
       	show_404();
      }
    }
		public function readsentmessage($message_id = '')
		{
    	if ($message_id != '')
			{
      	$data['result'] = $this->msgs->get_inbox_msgs($this->user_id, array("id" => $message_id));
        $this->load->view('messages/read_msg', $data);
      }
			else
			{
      	show_404();
      }
    }
		

}

?>