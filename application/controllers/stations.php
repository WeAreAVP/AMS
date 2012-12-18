<?php

/**
	* AMS Stations Controller
 * 
 * PHP version 5
 * 
	* @category   AMS
	* @package    CI
	* @subpackage Controller
	* @author     Nouman Tayyab <nouman@geekschicago.com>
	* @license    CPB http://nouman.com
 * @version    GIT: <$Id>
	* @link       http://amsqa.avpreserve.com
 
	*/

/**
	* Stations Class
	*
	* @category   Class
	* @package    CI
	* @subpackage Controller
	* @author     Nouman Tayyab <nouman@geekschicago.com>
	* @license    CPB http://nouman.com
	* @link       http://amsqa.avpreserve.com/stations
	*/
class	Stations	extends	MY_Controller
{

				/**
					* Constructor.
					* 
					* Load the layout. Sphinx and tracking model
					*  
					*/
				function	__construct	()
				{
								parent::__construct	();
								$this->layout	=	'main_layout.php';
								$this->load->model	('sphinx_model',	'sphinx');
				}

				/**
					* List all the stations and also filters stations
					* 
					* @return stations/list view  
					*/
				public	function	index	()
				{

								$param	=	array	('search_kewords'	=>	'',	'certified'						=>	'',	'agreed'									=>	'');
								$value	=	$this->form_validation;
								$value->set_rules	('search_keyword',	'Search Keyword',	'trim|xss_clean');
								$value->set_rules	('certified',	'Certified',	'trim|xss_clean');
								$value->set_rules	('agreed',	'Agreed',	'trim|xss_clean');
								$value->set_rules	('start_date_range',	'Start Date',	'trim|xss_clean');
								$value->set_rules	('end_date_range',	'End Date',	'trim|xss_clean');
								if	($this->input->post	())
								{
												$param['certified']	=	$this->input->post	('certified');
												$param['agreed']	=	$this->input->post	('agreed');
//            $param['start_date'] = $this->input->post('start_date');
//            $param['end_date'] = $this->input->post('end_date');

												$param['search_kewords']	=	str_replace	(',',	' & ',	trim	($this->input->post	('search_words')));
												$records	=	$this->sphinx->search_stations	($param);
												$data['stations']	=	$records['records'];
								}
								else
								{
												$records	=	$this->sphinx->search_stations	($param);
												$data['stations']	=	$records['records'];
								}
								if	(isAjax	())
								{
												$data['is_ajax']	=	TRUE;
												echo	$this->load->view	('stations/list',	$data,	TRUE);
												return true;
								}
								else
								{
												$data['is_ajax']	=	FALSE;

												$this->load->view	('stations/list',	$data);
								}
				}

				/**
					* Show Detail of specific station
					* 
					* @return stations/detail  
					*/
				public	function	detail	()
				{
								$station_id	=	$this->uri->segment	(3);
								$data['station_detail']	=	$this->station_model->get_station_by_id	($station_id);
								$data['station_contacts']	=	$this->users->get_station_users	($station_id);
								$data['station_tracking']	=	$this->tracking->get_all	($station_id);

								$this->load->view	('stations/detail',	$data);
				}

				/**
					* set or update the start time of station.
					* 
					* @return json 
					*/
				public	function	update_stations	()
				{
								if	(isAjax	())
								{
												$station_ids	=	$this->input->post	('id');
												$station_ids	=	explode	(',',	$station_ids);
												$start_date	=	$this->input->post	('start_date');
												$end_date	=	$this->input->post	('end_date');
												$is_certified	=	$this->input->post	('is_certified');
												$is_agreed	=	$this->input->post	('is_agreed');
												$start_date	=	$start_date	?	$start_date	:	NULL;
												$end_date	=	$end_date	?	$end_date	:	NULL;
												$station	=	array	();
												foreach	($station_ids	as	$value)
												{
																$station[]	=	$this->station_model->update_station	($value,	array	('start_date'			=>	$start_date,	'end_date'					=>	$end_date,	'is_certified'	=>	$is_certified,	'is_agreed'				=>	$is_agreed));

																$this->sphinx->update_indexes	('stations',	array	('start_date',	'end_date',	'is_certified',	'is_agreed'),	array	($value	=>	array	((int)	strtotime	($start_date),	(int)	strtotime	($end_date),	(int)	$is_certified,	(int)	$is_agreed)));
												}

//            print exec("/usr/bin/indexer --all --rotate");


												echo	json_encode	(array	('success'	=>	TRUE,	'station'	=>	$station,	'total'			=>	count	($station_ids)));
												return true;
								}
								show_404	();
				}

				/**
					*  Get List of stations by Id by Ajax Request.
					*  
					* @return json
					*/
				public	function	get_stations	()
				{
								if	(isAjax	())
								{
												$this->station_model->delete_stations_backup	();
												$stations_id	=	$this->input->post	('id');
												$records	=	$this->station_model->get_stations_by_id	($stations_id);
												foreach	($records	as	$value)
												{
																$backup_record	=	array	('station_id'			=>	$value->id,	'start_date'			=>	$value->start_date,	'end_date'					=>	$value->end_date,	'is_certified'	=>	$value->is_certified,	'is_agreed'				=>	$value->is_agreed);
																$this->station_model->insert_station_backup	($backup_record);
												}
												echo	json_encode	(array	('success'	=>	TRUE,	'records'	=>	$records));
												return true;
								}
								show_404	();
				}

				/**
					* Get a list of stations for DSD
					* 
					* @return json
					*/
				public	function	get_dsd_stations	()
				{
								if	(isAjax	())
								{
												$stations_id	=	$this->input->post	('id');
												$records	=	$this->station_model->get_stations_by_id	($stations_id);
												echo	json_encode	(array	('success'	=>	TRUE,	'records'	=>	$records));
												return true;
								}
								show_404	();
				}

				/**
					* Undo the last edited stations
					* 
				 * @return redirect to index method
					*/
				public	function	undostations	()
				{
								$backups	=	$this->station_model->get_all_backup_stations	();
								if	(count	($backups)	>	0)
								{
												foreach	($backups	as	$value)
												{
																$this->station_model->update_station	($value->station_id,	array	('start_date'	=>	$value->start_date,	'end_date'			=>	$value->end_date));
																$this->sphinx->update_indexes	('stations',	array	('start_date',	'end_date'),	array	($value->station_id	=>	array	(strtotime	($value->start_date),	strtotime	($value->end_date))));
												}
								}
								redirect	('stations/index',	'location');
				}

				/**
					* Get Staions info for sending messages
					* 
				 * @return json
					*/
				public	function	get_stations_info	()
				{
								if	(isAjax	())
								{
												$stations	=	$this->input->post	('stations');
												$list	=	array	();
												foreach	($stations	as		$station_id)
												{

																$station_info	=	$this->station_model->get_station_by_id	($station_id);
																if	(count	($station_info)	>	0)
																{
																				if	(empty	($station_info->start_date)	OR	$station_info->start_date	===	NULL)
																				{
																								$list[]	=	array	('station_id'			=>	$station_id,	'dsd'										=>	'',	'station_name'	=>	$station_info->station_name);
																				}
																				else
																				{
																								$list[]	=	array	('station_id'			=>	$station_id,	'dsd'										=>	$station_info->start_date,	'station_name'	=>	$station_info->station_name);
																				}
																}
												}
												echo	json_encode	($list);
												return true;
								}
								show_404	();
				}

				/**
					* Update the satation start date
					* 
				 * @return json
					*/
				public	function	update_dsd_station	()
				{
								if	(isAjax	())
								{
												$dates	=	$this->input->post	();
												foreach	($dates	as	$index	=>	$value)
												{
																$station_id	=	explode	('_',	$index);
																$station_id	=	$station_id[count	($station_id)	-	1];
																$start_date	=	date	('Y-m-d',	strtotime	($value));
																$this->station_model->update_station	($station_id,	array	('start_date'	=>	$start_date));
																$this->sphinx->update_indexes	('stations',	array	('start_date'),	array	($station_id	=>	array	((int)	strtotime	($start_date))));
												}
												echo	json_encode	(array	('success'	=>	TRUE));
												return true;
								}
								show_404	();
				}

}

// END Stations Controller

// End of file stations.php 
/* Location: ./application/controllers/stations.php */