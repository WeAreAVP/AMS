<?php

/**
 * AMS Tracking Controller
 * 
 * @category	Controllers
 * @package		AMS
 * @subpackage	Tracking Controller
 * @author		Nouman Tayyab <nouman@geekschicago.com>
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
        $this->layout = 'main_layout.php';
    }

    /**
     * Create a new Tracking Record
     * Get station_id as uri segment 3
     *  
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
                $record = array('ship_date' => date('Y-m-d', strtotime($form_val->set_value('tracking_ship_date'))),
                    'ship_to' => $form_val->set_value('ship_to'),
                    'ship_via' => $form_val->set_value('ship_via'),
                    'tracking_no' => $form_val->set_value('tracking_no'),
                    'no_box_shipped' => $form_val->set_value('no_box_shipped'),
                    'station_id' => $data['station_id'],
                    'media_received_date' => $form_val->set_value('media_received_date'),
                );

                $inserted_id = $this->tracking->insert_record($record);
                $tracking_info = $this->tracking->get_by_id($inserted_id);
                $this->shipment_tracking_email($tracking_info);
                echo 'done';
                exit;
            } else
            {
                $errors = $form_val->error_string();
                $data['errors'] = $errors;
            }
        }
        echo $this->load->view('tracking/add', $data, TRUE);
        exit;
    }

    /**
     * Edit Tracking Information.
     * Get tracking_id as uri segment 3
     * 
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
                $record = array('ship_date' => date('Y-m-d', strtotime($form_val->set_value('tracking_ship_date'))),
                    'ship_to' => $form_val->set_value('ship_to'),
                    'ship_via' => $form_val->set_value('ship_via'),
                    'tracking_no' => $form_val->set_value('tracking_no'),
                    'no_box_shipped' => $form_val->set_value('no_box_shipped'),
                    'media_received_date' => $form_val->set_value('media_received_date'),
                );
                $this->tracking->update_record($tracking_id, $record);
                $tracking_info = $this->tracking->get_by_id($tracking_id);
                $this->shipment_tracking_email($tracking_info);
                echo 'done';
                exit;
            } else
            {
                $errors = $form_val->error_string();
                $data['errors'] = $errors;
            }
        }
        $data['tracking_info'] = $this->tracking->get_by_id($tracking_id);
        echo $this->load->view('tracking/edit', $data, TRUE);
        exit;
    }

    /**
     * Delete the tracking info
     * Get station_id as uri segment 4
     * Get tracking_id as uri segment 3
     *  
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
     * @param array $record
     * @return boolean 
     */
    function shipment_tracking_email($record)
    {

        $template = '_Tracking_Ship_Date';
        $template_data = $this->email_template->get_template_by_sys_id($template);

        if (isset($template_data) && !empty($template_data))
        {
            $station_details = $this->station_model->get_station_by_id($record->station_id);
            $subject = $template_data->subject;

            $replacebale['ship_date'] = $record->ship_date;
            $replacebale['ship_via'] = $record->ship_via;
            $replacebale['tracking_no'] = $record->tracking_no;
            $replacebale['no_box_shipped'] = $record->no_box_shipped;
            $replacebale['station_name'] = isset($station_details->station_name) ? $station_details->station_name : '';


            if ($this->config->item('demo') == true)
            {
                $to_email = $this->config->item('to_email');
                $from_email = $this->config->item('from_email');
                $replacebale['user_name'] = 'AMS';
            } else
            {
                $to_email = $station_details->contact_email;
                $from_email = $this->user_detail->email;
                $replacebale['user_name'] = $this->user_detail->first_name . ' ' . $this->user_detail->last_name;
            }
            $replacebale['inform_to'] = 'ssapienza@cpb.org';
            $this->emailtemplates->sent_now = true;
            $email_queue_id = $this->emailtemplates->queue_email($template, $to_email, $replacebale);
            return true;
        } else
        {
            return false;
        }
    }

    public function get_tracking_info()
    {
        $stations = $this->input->post('stations');
        $stations_list = array();
        $stations_empty_list = array();
        $station_name = array();
        foreach ($stations as $id)
        {
            $tracking_info = $this->tracking->get_last_tracking_info($id);
            $station = $this->station_model->get_station_by_id($tracking_info->station_id);
            if (count($tracking_info) > 0)
            {
                if (trim($tracking_info->media_received_date) == '')
                {
                    $stations_list[] = $tracking_info->id;
                    $station_name[] = $station->station_name;
                }
            } else
            {
                $stations_empty_list[] = $id;
                $station_name[] = $station->station_name;
            }
        }
        echo json_encode(array('empty_station' => $stations_empty_list, 'station_list' => $stations_list, 'station_names' => $station_name));
        exit;
    }

    public function update_tracking_info()
    {
        $tracking_id = $this->input->post('tracking_id');
        $tracking_id = explode(',', $tracking_id);
        $media_date = $this->input->post('date');
        $media_date = date('Y-m-d', strtotime($media_date));
        foreach ($tracking_id as $id)
        {
            $this->tracking->update_record($id, array('media_received_date' => $media_date));
        }
        echo json_encode(array('success' => true));
        exit;
    }

}

// END Tracking

/* End of file tracking.php */
/* Location: ./application/controllers/tracking.php */