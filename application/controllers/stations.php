<?php

/**
 * AMS Archive Management System
 * 
 * PHP version 5
 * 
 * @category AMS
 * @package  CI
 * @author   Nouman Tayyab <nouman@avpreserve.com>
 * @license  CPB http://nouman.com
 * @version  GIT: <$Id>
 * @link     http://amsqa.avpreserve.com

 */

/**
 * Stations Class
 *
 * @category   AMS
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @license    CPB http://nouman.com
 * @link       http://amsqa.avpreserve.com
 */
class Stations extends MY_Controller
{

	/**
	 * Constructor.
	 * 
	 * Load Model and Library.
	 *  
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->model('sphinx_model', 'sphinx');
		$this->load->library('sphnixrt');
	}

	/**
	 * List all the stations and also filters stations
	 * 
	 * @return stations/list view  
	 */
	public function index()
	{
		$param = array('search_keywords' => '', 'certified' => '', 'agreed' => '');
		$value = $this->form_validation;
		$value->set_rules('search_keyword', 'Search Keyword', 'trim|xss_clean');
		$value->set_rules('certified', 'Certified', 'trim|xss_clean');
		$value->set_rules('agreed', 'Agreed', 'trim|xss_clean');
		if ($this->input->post())
		{
			$param['certified'] = $this->input->post('certified');
			$param['agreed'] = $this->input->post('agreed');
			$param['search_keywords'] = str_replace(',', ' | ', trim($this->input->post('search_words')));
		}
		$records = $this->sphinx->search_stations($param);
		$data['stations'] = $records['records'];

		if (isAjax())
		{
			$data['is_ajax'] = TRUE;
			echo $this->load->view('stations/list', $data, TRUE);
			exit_function();
		}
		else
		{
			$data['is_ajax'] = FALSE;
			$this->load->view('stations/list', $data);
		}
	}

	/**
	 * Show Detail of specific station
	 * 
	 * @return stations/detail  
	 */
	public function detail()
	{
		$station_id = $this->uri->segment(3);
		$data['station_detail'] = $this->station_model->get_station_by_id($station_id);
		$data['station_contacts'] = $this->users->get_station_users($station_id);
		$data['station_tracking'] = $this->tracking->get_all($station_id);

		$this->load->view('stations/detail', $data);
	}

	/**
	 * set or update the start time of station.
	 * 
	 * @return json 
	 */
	public function update_stations()
	{
		if (isAjax())
		{
			$station_ids = explode(',', $this->input->post('id'));
			$start_date = $this->input->post('start_date');
			$end_date = $this->input->post('end_date');
			$is_certified = $this->input->post('is_certified');
			$is_agreed = $this->input->post('is_agreed');
			$start_date = $start_date ? $start_date : NULL;
			$end_date = $end_date ? $end_date : NULL;
			$station = array();
			foreach ($station_ids as $value)
			{
				$station[] = $this->station_model->update_station($value, array('start_date' => $start_date, 'end_date' => $end_date, 'is_certified' => $is_certified, 'is_agreed' => $is_agreed));
				$this->sphinx->update_indexes('stations', array('start_date', 'end_date', 'is_certified', 'is_agreed'), array($value => array((int) strtotime($start_date), (int) strtotime($end_date), (int) $is_certified, (int) $is_agreed)));
				$log = array('user_id' => $this->user_id, 'record_id' => $value, 'record' => 'station', 'type' => 'edit', 'comments' => 'Update from stations list.');
				$this->audit_trail($log);
			}
			echo json_encode(array('success' => TRUE, 'station' => $station, 'total' => count($station_ids)));
			exit_function();
		}
		show_404();
	}

	/**
	 *  Get List of stations by Id by Ajax Request.
	 *  
	 * @return json
	 */
	public function get_stations()
	{
		if (isAjax())
		{
			$this->station_model->delete_stations_backup();
			$stations_id = $this->input->post('id');
			$records = $this->station_model->get_stations_by_id($stations_id);
			foreach ($records as $value)
			{
				$backup_record = array('station_id' => $value->id, 'start_date' => $value->start_date, 'end_date' => $value->end_date, 'is_certified' => $value->is_certified, 'is_agreed' => $value->is_agreed);
				$this->station_model->insert_station_backup($backup_record);
			}
			echo json_encode(array('success' => TRUE, 'records' => $records));
			exit_function();
		}
		show_404();
	}

	/**
	 * Get a list of stations for DSD
	 * 
	 * @return json
	 */
	public function get_dsd_stations()
	{
		if (isAjax())
		{
			$stations_id = $this->input->post('id');
			$records = $this->station_model->get_stations_by_id($stations_id);
			echo json_encode(array('success' => TRUE, 'records' => $records));
			exit_function();
		}
		show_404();
	}

	/**
	 * Undo the last edited stations
	 * 
	 * @return redirect to index method
	 */
	public function undostations()
	{
		$backups = $this->station_model->get_all_backup_stations();
		if (count($backups) > 0)
		{
			foreach ($backups as $value)
			{
				$this->station_model->update_station($value->station_id, array('start_date' => $value->start_date, 'end_date' => $value->end_date));
				$this->sphinx->update_indexes('stations', array('start_date', 'end_date'), array($value->station_id => array(strtotime($value->start_date), strtotime($value->end_date))));
				$log = array('user_id' => $this->user_id, 'record_id' => $value, 'record' => 'station', 'type' => 'undo', 'comments' => 'Undo the last update');
				$this->audit_trail($log);
			}
		}
		redirect('stations/index', 'location');
	}

	/**
	 * Get Staions info for sending messages
	 * 
	 * @return json
	 */
	public function get_stations_info()
	{
		if (isAjax())
		{
			$stations = $this->input->post('stations');
			$list = array();
			foreach ($stations as $station_id)
			{
				$station_info = $this->station_model->get_station_by_id($station_id);
				if (count($station_info) > 0)
				{
					if (empty($station_info->start_date) OR $station_info->start_date === NULL)
					{
						$list[] = array('station_id' => $station_id, 'dsd' => '', 'station_name' => $station_info->station_name);
					}
					else
					{
						$list[] = array('station_id' => $station_id, 'dsd' => $station_info->start_date, 'station_name' => $station_info->station_name);
					}
				}
			}
			echo json_encode($list);
			exit_function();
		}
		show_404();
	}

	/**
	 * Update the satation start date
	 * 
	 * @return json
	 */
	public function update_dsd_station()
	{
		if (isAjax())
		{
			$dates = $this->input->post();
			foreach ($dates as $index => $value)
			{
				$station_id = explode('_', $index);
				$station_id = $station_id[count($station_id) - 1];
				$start_date = date('Y-m-d', strtotime($value));
				$this->station_model->update_station($station_id, array('start_date' => $start_date));
				$this->sphinx->update_indexes('stations', array('start_date'), array($station_id => array((int) strtotime($start_date))));
				$log = array('user_id' => $this->user_id, 'record_id' => $station_id, 'record' => 'station', 'type' => 'edit', 'comments' => 'station updated');
				$this->audit_trail($log);
			}
			echo json_encode(array('success' => TRUE));
			exit_function();
		}
		show_404();
	}

	/**
	 * Update Stations records from a csv file.
	 *  
	 */
	public function import_station_contacts()
	{
		$config['upload_path'] = "./uploads/stations/";
		$config['allowed_types'] = 'csv';
		$this->load->library('upload', $config);

		$upload = NULL;
		if ( ! $this->upload->do_upload('csv_file'))
		{
			$this->session->set_userdata('upload_csv_error', $this->upload->display_errors());
			redirect('stations/index');
		}
		else
		{
			$upload = $this->upload->data();
			if ( ! isset($upload['file_name']) || ! $upload['file_name'])
			{
				$this->session->set_userdata('upload_csv_error', 'Not a valid file name.');

				redirect('stations/index');
			}

			if ($upload['file_ext'] !== '.csv')
			{
				$this->session->set_userdata('upload_csv_error', 'Not a valid csv format.');

				redirect('stations/index');
			}
		}
		$file_name = $upload['file_name'];

		$file = file_get_contents("uploads/stations/$file_name");
		$records = array_map("str_getcsv", preg_split('/\r*\n+|\r+/', $file));

		$count = count($records);

		$type = array('Radio' => 0, 'TV' => 1, 'Joint' => 2, 'Television' => 1);
		$station_id = NULL;
		$station_update_count = array('station' => '', 'user' => '');
		if ($count > 0)
		{
			foreach ($records as $index => $row)
			{
				if ($index !== 0)
				{
					if (count($row) > 21)
					{
						$this->session->set_userdata('upload_csv_error', 'Column count is not correct.');
						redirect('stations/index');
					}
					else
					{
						$valid = $records[0];

						if (strtolower($valid[0]) != 'cpb id' || strtolower($valid[1]) != 'station (brand) name' || strtolower($valid[2]) != 'contact name')
						{
							$this->session->set_userdata('upload_csv_error', 'Column names are not correct.');
							redirect('stations/index');
						}
					}
					if ( ! empty($row[0]) && ! empty($row[1]))
					{
						$station_detail = array(
							'type' => $type[$row[4]], 'address_primary' => $row[5], 'address_secondary' => $row[6], 'city' => $row[7],
							'state' => $row[8], 'zip' => $row[9], 'allocated_hours' => $row[13], 'allocated_buffer' => $row[14],
							'total_allocated' => $row[15], 'nominated_hours_final' => $row[18], 'nominated_buffer_final' => $row[19],
							'is_certified' => ($row[16] == 'TRUE') ? 1 : 0, 'is_agreed' => ($row[17] == 'TRUE') ? 1 : 0
						);

						$station = $this->station_model->get_station_by_cpb_id("$row[0]");

						if ($station)
						{
							$station_id = $station->id;
							$this->station_model->update_station($station_id, $station_detail);
							$this->update_sphnix_index($row, $station_id, FALSE, $station);
							if ( ! isset($station_update_count['station'][$row[0]]))
								$station_update_count['station'][$row[0]] = 'updated';
							$log = array('user_id' => $this->user_id, 'record_id' => $station_id, 'record' => 'station', 'type' => 'edit', 'comments' => 'csv update');
							$this->audit_trail($log);
						}
						else
						{
							$station_detail['cpb_id'] = $row[0];
							$station_detail['station_name'] = $row[1];
							$station_id = $this->station_model->insert_station($station_detail);
							$this->update_sphnix_index($row, $station_id, TRUE);
							if ( ! isset($station_update_count['station'][$row[0]]))
								$station_update_count['station'][$row[0]] = 'inserted';
							$log = array('user_id' => $this->user_id, 'record_id' => $station_id, 'record' => 'station', 'type' => 'edit', 'comments' => 'csv insert');
							$this->audit_trail($log);
						}
						unset($station_detail);

						$station_user = array(
							'role_id' => 3,
							'station_id' => $station_id,
							'is_secondary' => (strtolower($row[20]) == 'yes') ? 0 : 1
						);
						if (isset($row[21]))
						{
							$station_user['password'] = crypt($this->dx_auth->_encode($row[21]));
						}
						$name = explode(' ', $row[2], 2);


						$station_user_detail = array(
							'first_name' => (isset($name[0])) ? $name[0] : '',
							'last_name' => (isset($name[1])) ? utf8_encode($name[1]) : '',
							'phone_no' => $row[10],
							'fax' => $row[11],
							'title' => $row[3],
						);

						$db_usser = $this->users->get_user_by_email($row[12]);
						if ($db_usser->num_rows() == 1)
						{
							$user_info = $db_usser->row();
							$user_id = $user_info->id;
							$this->users->set_user($user_id, $station_user);
							$station_user_detail['user_id'] = $user_id;
							$this->user_profile->set_profile($user_id, $station_user_detail);
							if ( ! isset($station_update_count['user'][$user_id]))
								$station_update_count['user'][$user_id] = 'updated';
						}
						else
						{
							$station_user['email'] = $row[12];
							$user_id = $this->users->create_user($station_user);
							$station_user_detail['user_id'] = $user_id;
							$this->user_profile->insert_profile($station_user_detail);
							if ( ! isset($station_update_count['user'][$user_id]))
								$station_update_count['user'][$user_id] = 'inserted';
						}
						unset($station_user);
						unset($station_user_detail);
					}
				}
			}
		}
		$inserted_user = 0;
		$updated_user = 0;
		$inserted_station = 0;
		$updated_station = 0;
		foreach ($station_update_count['user'] as $user)
		{
			if ($user === 'inserted')
			{
				$inserted_user ++;
			}
			else
			{
				$updated_user ++;
			}
		}
		$total_user = $total_station = '';
		if ($inserted_user !== 0)
			$total_user.= ' ' . $inserted_user . ' user(s) inserted.';
		if ($updated_user !== 0)
		{
			$total_user.=($total_user != '') ? ' and ' : '';
			$total_user.= ' ' . $updated_user . ' user(s) updated.';
		}
		foreach ($station_update_count['station'] as $station)
		{
			if ($station === 'inserted')
			{
				$inserted_station ++;
			}
			else
			{
				$updated_station ++;
			}
		}
		if ($inserted_station !== 0)
			$total_station.= ' ' . $inserted_station . ' station(s) inserted.';
		if ($updated_station !== 0)
		{
			$total_station.=($total_station != '') ? ' and ' : '';
			$total_station.= ' ' . $updated_station . ' station(s) updated.';
		}

		$this->session->set_userdata('upload_success_msg', $total_station . $total_user);
		redirect('stations/index');
	}

	function update_sphnix_index($row, $station_id, $new = FALSE, $station)
	{
		$sphnix_station = array();
		if ( ! $new)
		{
			$sphnix_station['id'] = $station_id;
			$sphnix_station['s_station_name'] = $station->station_name;
			$sphnix_station['station_name'] = $station->station_name;
		}
		else
		{
			$sphnix_station['s_station_name'] = ! empty($row[1]) ? $row[1] : '';
			$sphnix_station['station_name'] = ! empty($row[1]) ? $row[1] : '';
		}
		$sphnix_station['s_type'] = $row[4];
		$sphnix_station['type'] = $row[4];
		$sphnix_station['s_address_primary'] = ! empty($row[5]) ? $row[5] : '';
		$sphnix_station['address_primary'] = ! empty($row[5]) ? $row[5] : '';
		$sphnix_station['s_address_secondary'] = ! empty($row[6]) ? $row[6] : '';
		$sphnix_station['address_secondary'] = ! empty($row[6]) ? $row[6] : '';
		$sphnix_station['s_city'] = ! empty($row[7]) ? $row[7] : '';
		$sphnix_station['city'] = ! empty($row[7]) ? $row[7] : '';
		$sphnix_station['s_state'] = ! empty($row[8]) ? $row[8] : '';
		$sphnix_station['state'] = ! empty($row[8]) ? $row[8] : '';
		$sphnix_station['s_zip'] = ! empty($row[9]) ? $row[9] : '';
		$sphnix_station['zip'] = ! empty($row[9]) ? $row[9] : '';
		if ($new)
		{
			$sphnix_station['s_cpb_id'] = ! empty($row[0]) ? $row[0] : '';
			$sphnix_station['cpb_id'] = ! empty($row[0]) ? $row[0] : '';
		}
		else
		{
			$sphnix_station['s_cpb_id'] = $station->cpb_id;
			$sphnix_station['cpb_id'] = $station->cpb_id;
		}
		$sphnix_station['allocated_hours'] = ! empty($row[13]) ? (int) $row[13] : (int) 0;
		$sphnix_station['allocated_buffer'] = ! empty($row[14]) ? (int) $row[14] : (int) 0;
		$sphnix_station['total_allocated'] = ! empty($row[15]) ? (int) $row[15] : (int) 0;
		$sphnix_station['is_certified'] = ! empty($row[0]) ? $row[0] : '';
		$sphnix_station['is_agreed'] = ($row[16] == 'TRUE') ? 1 : 0;
		if ($new)
			$this->sphnixrt->insert('stations', $sphnix_station, $station_id);
		else
			$this->sphnixrt->update('stations', $sphnix_station);
	}

	/* End of Station Class */
}

// END Stations Controller

// End of file stations.php 
/* Location: ./application/controllers/stations.php */