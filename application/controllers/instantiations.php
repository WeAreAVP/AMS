<?php

/**
	* AMS Archive Management System
	* 
	* To manage the instantiations
	* 
	* PHP version 5
	* 
	* @category AMS
	* @package  CI
	* @author   Nouman Tayyab <nouman@geekschicago.com>
	* @license  CPB http://nouman.com
	* @version  GIT: <$Id>
	* @link     http://amsqa.avpreserve.com

	*/

/**
	* Instantiations Class
	*
	* @category   AMS
	* @package    CI
	* @subpackage Controller
	* @author     Nouman Tayyab <nouman@geekschicago.com>
	* @license    http://amsqa.avpreserve.com CPB
	* @link       http://amsqa.avpreserve.com
	*/
class	Instantiations	extends	MY_Controller
{

				/**
					* Constructor
					* 
					* Load the layout, Models and Libraries
					* 
					*/
				function	__construct()
				{
								parent::__construct();
								$this->layout	=	'main_layout.php';
								$this->load->model('instantiations_model',	'instantiation');
								$this->load->model('assets_model');
								$this->load->model('sphinx_model',	'sphinx');
								$this->load->library('pagination');
								$this->load->library('Ajax_pagination');
				}

				/**
					* List all the instantiation records with pagination and filters. 
					* 
					* @return instantiations/index view
					*/
				public	function	index()
				{
								$offset	=	($this->uri->segment(3))	?	$this->uri->segment(3)	:	0;
								$params	=	array('search'	=>	'');
								if(isAjax())
								{
												$this->unset_facet_search();
												$search['custom_search']	=	$this->input->post('keyword_field_main_search');
												$search['organization']	=	$this->input->post('organization_main_search');
												$search['nomination']	=	$this->input->post('nomination_status_main_search');
												$search['media_type']	=	$this->input->post('media_type_main_search');
												$search['physical_format']	=	$this->input->post('physical_format_main_search');
												$search['digital_format']	=	$this->input->post('digital_format_main_search');
												$search['generation']	=	$this->input->post('generation_main_search');
												$search['date_range']	=	$this->input->post('date_range');
												$search['date_type']	=	$this->input->post('date_type');
												if($this->input->post('digitized')	&&	$this->input->post('digitized')	===	'1')
												{
																$search['digitized']	=	$this->input->post('digitized');
												}
												if($this->input->post('migration_failed')	&&	$this->input->post('migration_failed')	===	'1')
												{
																$search['migration_failed']	=	$this->input->post('migration_failed');
												}

												$this->set_facet_search($search);
								}
								$this->session->set_userdata('page_link',	'instantiations/index/'	.	$offset);
								$data['get_column_name']	=	$this->make_array();
								$data['stations']	=	$this->station_model->get_all();
								$data['nomination_status']	=	$this->instantiation->get_nomination_status();
								$data['media_types']	=	$this->instantiation->get_media_types();
								$data['physical_formats']	=	$this->instantiation->get_physical_formats();
								$data['digital_formats']	=	$this->instantiation->get_digital_formats();
								$data['generations']	=	$this->instantiation->get_generations();
								$data['date_types']	=	$this->instantiation->get_date_types();
								$data['current_tab']	=	'';
								$is_hidden	=	array();
								$data['table_type']	=	'instantiation';
								foreach($this->column_order	as	$index	=>	$value)
								{
												if($value['hidden']	===	'1')
																$is_hidden[]	=	$index;
								}
								$data['hidden_fields']	=	$is_hidden;
								$data['isAjax']	=	FALSE;

								$records	=	$this->sphinx->instantiations_list($params,	$offset);
								$data['total']	=	$records['total_count'];
								$config['total_rows']	=	$data['total'];
								$config['per_page']	=	100;
								$data['records']	=	$records['records'];
//								debug($data['records'],false);
								$data['count']	=	count($data['records']);
								if($data['count']	>	0	&&	$offset	===	0)
								{
												$data['start']	=	1;
												$data['end']	=	$data['count'];
								}
								else
								{
												$data['start']	=	$offset;
												$data['end']	=	intval($offset)	+	intval($data['count']);
								}
								$data['facet_search_url']	=	site_url('instantiations/index');
								$config['prev_link']	=	'<i class="icon-chevron-left"></i>';
								$config['next_link']	=	'<i class="icon-chevron-right"></i>';
								$config['use_page_numbers']	=	FALSE;
								$config['first_link']	=	FALSE;
								$config['last_link']	=	FALSE;
								$config['display_pages']	=	FALSE;
								$config['js_method']	=	'facet_search';
								$config['postVar']	=	'page';
								$this->ajax_pagination->initialize($config);
								if(isAjax())
								{
												$data['isAjax']	=	TRUE;
												echo	$this->load->view('instantiations/index',	$data,	TRUE);
												exit(0);
								}
								$this->load->view('instantiations/index',	$data);
				}

				/**
					* Show the detail of an instantiation
					*  
					* @return instantiations/detail view
					*/
				public	function	detail()
				{
								$instantiation_id	=	(is_numeric($this->uri->segment(3)))	?	$this->uri->segment(3)	:	FALSE;
								if($instantiation_id)
								{
												$detail	=	$this->instantiation->get_by_id($instantiation_id);
												if(count($detail)	>	0)
												{
																$data['asset_id']	=	$detail->assets_id;
																$data['inst_id']	=	$instantiation_id;
																$data['instantiation_detail']	=	$data['asset_instantiations']	=	$this->sphinx->instantiations_list(array('asset_id'																				=>	$detail->assets_id,	'search'																						=>	''));
																$data['instantiation_events']	=	$this->instantiation->get_events_by_instantiation_id($instantiation_id);
																$data['instantiation_detail']	=	$data['instantiation_detail']['records'][0];
																$data['asset_details']	=	$this->assets_model->get_asset_by_asset_id($detail->assets_id);
																$search_results_data	=	$this->sphinx->instantiations_list(array('index'																	=>	'assets_list'),	0,	1000);
																$data['next_result_id']	=	FALSE;
																$data['prev_result_id']	=	FALSE;
																if(isset($search_results_data['records'])	&&	!	is_empty($search_results_data['records']))
																{
																				$search_results	=	$search_results_data['records'];
																				$search_results_array	=	array();
																				$num_search_results	=	0;
																				if($search_results)
																				{
																								foreach($search_results	as	$search_result)
																								{
																												$search_results_array[]['id']	=	$search_result->id;
																								}
																								$num_search_results	=	count($search_results);
																				}
																				# Get result number of current asset
																				$search_result_pointer	=	0;
																				foreach($search_results_array	as	$search_res)
																				{
																								if($search_res['id']	==	$instantiation_id)
																												break;
																								$search_result_pointer	++;
																				}
																				$data['cur_result']	=	$search_result_pointer	+	1;

																				# Get number of results
																				$data['num_results']	=	$num_search_results;

																				# Get result number of next listings
																				if($search_result_pointer	>=	($num_search_results	-	1))
																								$data['next_result_id']	=	FALSE;
																				else
																								$data['next_result_id']	=	$search_results_array[$search_result_pointer	+	1]['id'];

																				# Get result number of previous listings
																				if($search_result_pointer	<=	0	||	$num_search_results	==	1)
																								$data['prev_result_id']	=	FALSE;
																				else
																								$data['prev_result_id']	=	$search_results_array[$search_result_pointer	-	1]['id'];
																}
																$data['last_page']	=	'';
																if(isset($this->session->userdata['page_link'])	&&	!	is_empty($this->session->userdata['page_link']))
																{
																				$data['last_page']	=	$this->session->userdata['page_link'];
																}

																$this->load->view('instantiations/detail',	$data);
												}
												else
												{
																show_404();
												}
								}
								else
								{
												show_404();
								}
				}

				/**
					* Set last state of table view
					*  
					* @return json
					*/
				public	function	update_user_settings()
				{
								if(isAjax())
								{
												$user_id	=	$this->user_id;
												$settings	=	$this->input->post('settings');
												$freeze_columns	=	$this->input->post('frozen_column');
												$table_type	=	$this->input->post('table_type');
												$settings	=	json_encode($settings);
												$data	=	array('view_settings'	=>	$settings,	'frozen_column'	=>	$freeze_columns);
												$this->user_settings->update_setting($user_id,	$table_type,	$data);
												echo	json_encode(array('success'	=>	TRUE));
												exit(0);
								}
								show_404();
				}

				public	function	export_csv()
				{
								$array	=	array();
								$array[0]	=	array('0'							=>	'Show ID',	'1'							=>	'Show Start Date',	'2'							=>	'Show End Date',	'3'							=>	'Manager Name',	'4'							=>	'City',	'5'							=>	'State');
								$array[1]	=	array('0'							=>	strval("0001532"),	'1'							=>	'nouman',	'2'							=>	'Fahad',	'3'							=>	'Saad',	'4'							=>	'Byw',	'5'							=>	'Stup');
								$array[2]	=	array('0'		=>	'0012',	'1'		=>	'nouman',	'2'		=>	'Fahad',	'3'		=>	'Saad',	'4'		=>	'Byw',	'5'		=>	'Stup');
								$this->load->library('excel');
								$this->excel->getActiveSheetIndex();
								$this->excel->getActiveSheet()->setTitle('test worksheet');
								$row	=	1;
								for($i	=	0;	$i	<	3;	$i	++	)
								{
												foreach($array	as	$key	=>	$value)
												{
																$col	=	0;
																$this->excel->getActiveSheet()->setCellValueByColumnAndRow($col,	$row,	$value);
																$col	++;
												}
												$row	++;
								}
								$filename	=	'just_some_random_name.csv';	//save our workbook as this file name
								header('Content-Type: application/csv');	//mime type
								header('Content-Disposition: attachment;filename="'	.	$filename	.	'"');	//tell browser what's the file name
								header('Cache-Control: max-age=0');	//no cache
//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
//if you want to save it as .XLSX Excel 2007 format
								$objWriter	=	PHPExcel_IOFactory::createWriter($this->excel,	'Excel5');
//force user to download the Excel file without writing it to server's HD
								$objWriter->save('php://output');
								exit;

								$file	=	'/var/www/html/assets/testFile.csv';
								$ourFileName	=	"testFile.csv";
								$ourFileHandle	=	fopen($file,	'w')	or	die("can't open file");
								fclose($ourFileHandle);


								$fp	=	fopen($file,	'w');

								foreach($array	as	$fields)
								{
												fputcsv($fp,	''	.	$fields	.	'');
								}

								fclose($fp);

								$name	=	time()	.	"_showReport";
								$csv	=	file_get_contents($file);
								header("Content-type: application/csv");
								header("Content-Disposition: attachment; filename=$name.csv");
								header("Pragma: no-cache");
								header("Expires: 0");
								echo	$csv;
								exit;
				}

}

// END Instantiations Controller

// End of file instantiations.php 
/* Location: ./application/controllers/instantiations.php */