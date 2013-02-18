<?php

/**
	* Records controller.
	*
	* @package    AMS
	* @author     Ali Raza
	*/
class	Records	extends	MY_Controller
{
				/*
					*
					* Constructor
					* 
					*/

				function	__construct()
				{
								parent::__construct();
								$this->load->model('assets_model');
								$this->load->model('sphinx_model',	'sphinx');
								$this->load->model('instantiations_model',	'instantiation');
								$this->load->model('nomination_model',	'mix');
								$this->load->library('pagination');
								$this->load->library('Ajax_pagination');
				}

				/*
					*
					* To List All Assets
					*
					*/

				function	index()
				{


								$offset	=	($this->uri->segment(3))	?	$this->uri->segment(3)	:	0;
								if(isAjax())
								{
												$this->unset_facet_search();
												$search['custom_search']	=	$this->input->post('keyword_field_main_search');
												$search['organization']	=	$this->input->post('organization_main_search');
												$search['states']	=	$this->input->post('states_main_search');
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
								$this->session->set_userdata('page_link',	'records/index');
								$data['facet_search_url']	=	site_url('records/index');
								$data['current_tab']	=	'simple';
								if(isset($this->session->userdata['current_tab'])	&&	!	empty($this->session->userdata['current_tab']))
								{
												$data['current_tab']	=	$this->session->userdata['current_tab'];
								}
								$this->session->set_userdata('current_tab',	$data['current_tab']);
								$data['get_column_name']	=	$this->make_array();
								$states	=	$this->sphinx->facet_index('asset_state');
								$data['org_states']	=	$states['records'];
								unset($states);
								$stations	=	$this->sphinx->facet_index('assets_stations');
								$data['stations']	=	$stations['records'];
								unset($stations);
								$nomination	=	$this->sphinx->facet_index('assets_nomination');
								$data['nomination_status']	=	$nomination['records'];
								unset($nomination);
								$media_type	=	$this->sphinx->facet_index('assets_media_type');
								$data['media_types']	=	$media_type['records'];
								unset($media_type);
								$p_format	=	$this->sphinx->facet_index('assets_format_physical');
								$data['physical_formats']	=	$p_format['records'];
								unset($p_format);
								$d_format	=	$this->sphinx->facet_index('assets_format_digital');
								$data['digital_formats']	=	$d_format['records'];
								unset($d_format);
								$generation	=	$this->sphinx->facet_index('assets_generation');
								$data['generations']	=	$generation['records'];
								unset($generation);
								$data['date_types']	=	$this->instantiation->get_date_types();

								$is_hidden	=	array();
								$data['table_type']	=	'assets';
								foreach($this->column_order	as	$key	=>	$value)
								{
												if($value['hidden']	==	1)
																$is_hidden[]	=	$key;
								}
								$data['hidden_fields']	=	$is_hidden;
								$data['isAjax']	=	FALSE;
								$param	=	array('index'															=>	'assets_list');
								$records	=	$this->sphinx->assets_listing($param,	$offset);
								$data['total']	=	$records['total_count'];
								$config['total_rows']	=	$data['total'];
								$config['per_page']	=	100;
								$data['records']	=	$records['records'];
								$data['count']	=	count($data['records']);
								if($data['count']	>	0	&&	$offset	==	0)
								{
												$data['start']	=	1;
												$data['end']	=	$data['count'];
								}
								else
								{
												$data['start']	=	$offset;
												$data['end']	=	intval($offset)	+	intval($data['count']);
								}
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
												echo	$this->load->view('records/index',	$data,	TRUE);
												exit;
								}
								$this->load->view('records/index',	$data);
				}

				function	set_current_tab($current_tab)
				{
								if(isAjax())
								{
												$this->session->set_userdata('current_tab',	$current_tab);
												exit;
								}
				}

				/*
					*
					* To List All flagged
					*
					*/

				function	flagged()
				{
								show_404();
								exit();
//			$this->load->view('records/flagged');
				}

				/*
					* To Display Assets details
					*
					*/

				function	details($asset_id)
				{
								if($asset_id)
								{
												$data['asset_id']	=	$asset_id;
												$data['list_assets']	=	$this->instantiation->get_instantiations_by_asset_id($asset_id);
												$data['asset_details']	=	$this->assets_model->get_asset_by_asset_id($asset_id);
												$data['asset_guid']	=	$this->assets_model->get_guid_by_asset_id($asset_id);
												$data['asset_localid']	=	$this->assets_model->get_localid_by_asset_id($asset_id);
												$data['asset_subjects']	=	$this->assets_model->get_subjects_by_assets_id($asset_id);
												$data['asset_dates']	=	$this->assets_model->get_assets_dates_by_assets_id($asset_id);
												$data['asset_genres']	=	$this->assets_model->get_assets_genres_by_assets_id($asset_id);
												$data['asset_creators_roles']	=	$this->assets_model->get_assets_creators_roles_by_assets_id($asset_id);
												$data['asset_contributor_roles']	=	$this->assets_model->get_assets_contributor_roles_by_assets_id($asset_id);
												$data['asset_publishers_roles']	=	$this->assets_model->get_assets_publishers_role_by_assets_id($asset_id);
												$data['asset_coverages']	=	$this->assets_model->get_coverages_by_asset_id($asset_id);
												$data['rights_summaries']	=	$this->assets_model->get_rights_summaries_by_asset_id($asset_id);
												$data['asset_audience_levels']	=	$this->assets_model->get_audience_level_by_asset_id($asset_id);
												$data['asset_audience_ratings']	=	$this->assets_model->get_audience_rating_by_asset_id($asset_id);
												$data['annotations']	=	$this->assets_model->get_annotations_by_asset_id($asset_id);
												$search_results_data	=	$this->sphinx->assets_listing(array('index'																	=>	'assets_list'),	0,	1000);
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
																				if($search_res['id']	==	$asset_id)
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

												$this->load->view('records/assets_details',	$data);
								}
								else
								{
												show_404();
								}
				}

				public	function	sort_simple_table()
				{
								$this->load->helper('string');
								$offset	=	($this->uri->segment(3))	?	$this->uri->segment(3)	:	0;
								$columns	=	array('flag',	'organization',	'guid_identifier',	'local_identifier',	'asset_title',	'description');
								$this->session->unset_userdata('column');
								$this->session->unset_userdata('jscolumn');
								$this->session->unset_userdata('column_order');

								$this->session->set_userdata('jscolumn',	$this->input->get('iSortCol_0'));
								$this->session->set_userdata('column',	$columns[$this->input->get('iSortCol_0')]);

								$this->session->set_userdata('column_order',	$this->input->get('sSortDir_0'));


								$param	=	array('index'								=>	'assets_list');
								$records	=	$this->sphinx->assets_listing($param,	$offset);
								$data['total']	=	$records['total_count'];
								$records	=	$records['records'];
								$data['count']	=	count($records);
								$tablesort	=	array();
								foreach($records	as	$index	=>	$value)
								{
//												guid_identifier
												$tablesort[$index][]	=	'<i style="margin:0px" class="unflag"></i>';
												$asset_title_type	=	explode(' | ',	trim(str_replace('(**)',	'',	$value->asset_title_type)));
												$asset_title	=	explode(' | ',	trim(str_replace('(**)',	'',	$value->asset_title)));
												$asset_title_ref	=	explode(' | ',	trim(str_replace('(**)',	'',	$value->asset_title_ref)));
												$asset_combine	=	'';
												foreach($asset_title	as	$index	=>	$title)
												{
																if(isset($asset_title_type[$index])	&&	$asset_title_type[$index]	!=	'')
																				$asset_combine.=	$asset_title_type[$index]	.	': ';
																if(isset($asset_title_ref[$index]))
																{
																				if($asset_title_ref[$index]	!=	'')
																				{
																								$asset_combine.="<a target='_blank' href='$asset_title_ref[$index]'>$title</a>: ";
																								$asset_combine.=' ('	.	$asset_title_ref[$index]	.	')';
																				}
																				else
																								$asset_combine.=$title;
																}
																else
																				$asset_combine.=$title;
																$asset_combine.='<div class="clearfix"></div>';
												}

												$tablesort[$index][]	=	str_replace("(**)",	'',	$value->organization);
												$tablesort[$index][]	=	str_replace("(**)",	'',	$value->guid_identifier);
												$tablesort[$index][]	=	str_replace("(**)",	'',	$value->local_identifier);
												$tablesort[$index][]	=	str_replace("(**)",	'',	addslashes(($asset_combine)));
												$tablesort[$index][]	=	str_replace("(**)",	'',	$value->description);
								}

								$dataTable	=	array(
								"sEcho"																=>	$this->input->get('sEcho')	+	1,
								"iTotalRecords"								=>	$data['count'],
								"iTotalDisplayRecords"	=>	"100",
								'aaData'															=>	$tablesort
								);
								echo	json_encode($dataTable);
								exit;
				}

}
