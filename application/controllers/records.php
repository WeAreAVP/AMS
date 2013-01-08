<?php

if	(	!	defined	('BASEPATH'))
				exit	('No direct script access allowed');

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

				function	Records	()
				{
								parent::__construct	();
								$this->load->model	('assets_model');
								$this->load->model	('sphinx_model',	'sphinx');
								$this->load->model	('instantiations_model',	'instantiation');
								$this->load->library	('pagination');
								$this->load->library	('Ajax_pagination');
								$this->layout	=	'main_layout.php';
				}

				/*
					*
					* To List All Assets
					*
					*/

				function	index	()
				{
								if	(isAjax	())
								{
												$this->unset_facet_search	();
												$search['custom_search']	=	$this->input->post	('keyword_field_main_search');
												$search['organization']	=	$this->input->post	('organization_main_search');
												$search['nomination']	=	$this->input->post	('nomination_status_main_search');
												$search['media_type']	=	$this->input->post	('media_type_main_search');
												$search['physical_format']	=	$this->input->post	('physical_format_main_search');
												$search['digital_format']	=	$this->input->post	('digital_format_main_search');
												$search['generation']	=	$this->input->post	('generation_main_search');
												$search['date_range']	=	$this->input->post	('date_range');
												$search['date_type']	=	$this->input->post	('date_type');
												if	($this->input->post	('digitized')	&&	$this->input->post	('digitized')	===	'1')
												{
																$search['digitized']	=	$this->input->post	('digitized');
												}
												if	($this->input->post	('migration_failed')	&&	$this->input->post	('migration_failed')	===	'1')
												{
																$search['migration_failed']	=	$this->input->post	('migration_failed');
												}
												$this->set_facet_search	($search);
								}
								$data['facet_search_url']	=	site_url	('records/index');
								$data['current_tab']	=	'simple';
								if	(isset	($this->session->userdata['current_tab'])	&&	!	empty	($this->session->userdata['current_tab']))
								{
												$data['current_tab']	=	$this->session->userdata['current_tab'];
								}
								$this->session->set_userdata	('current_tab',	$data['current_tab']);
								$data['get_column_name']	=	$this->make_array	();
								$data['stations']	=	$this->station_model->get_all	();
								$data['nomination_status']	=	$this->instantiation->get_nomination_status	();
								$data['media_types']	=	$this->instantiation->get_media_types	();
								$data['physical_formats']	=	$this->instantiation->get_physical_formats	();
								$data['digital_formats']	=	$this->instantiation->get_digital_formats	();
								$data['generations']	=	$this->instantiation->get_generations	();
								$data['date_types']	=	$this->instantiation->get_date_types	();
								$is_hidden	=	array	();
								$data['table_type']	=	'assets';
								foreach	($this->column_order	as	$key	=>	$value)
								{
												if	($value['hidden']	==	1)
																$is_hidden[]	=	$key;
								}
								$data['hidden_fields']	=	$is_hidden;
								$data['isAjax']	=	FALSE;
								$offset	=	($this->uri->segment	(3))	?	$this->uri->segment	(3)	:	0;
								$param	=	array	('index'	=>	'assets_list');
								$records	=	$this->sphinx->assets_listing	($param,	$offset);
								$data['total']	=	$records['total_count'];
								$config['total_rows']	=	$data['total'];
								$config['per_page']	=	100;
								$data['records']	=	$records['records'];
								$data['count']	=	count	($data['records']);
								if	($data['count']	>	0	&&	$offset	==	0)
								{
												$data['start']	=	1;
												$data['end']	=	$data['count'];
								}
								else
								{
												$data['start']	=	$offset;
												$data['end']	=	intval	($offset)	+	intval	($data['count']);
								}
								$config['prev_link']	=	'<i class="icon-chevron-left"></i>';
								$config['next_link']	=	'<i class="icon-chevron-right"></i>';
								$config['use_page_numbers']	=	FALSE;
								$config['first_link']	=	FALSE;
								$config['last_link']	=	FALSE;
								$config['display_pages']	=	FALSE;
								$config['js_method']	=	'facet_search';
								$config['postVar']	=	'page';

								$this->ajax_pagination->initialize	($config);
								if	(isAjax	())
								{
												$data['isAjax']	=	TRUE;
												echo	$this->load->view	('records/index',	$data,	TRUE);
												exit;
								}
								$this->load->view	('records/index',	$data);
				}

				function	set_current_tab	($current_tab)
				{
								if	(isAjax	())
								{
												$this->session->set_userdata	('current_tab',	$current_tab);
												exit;
								}
				}

				/*
					*
					* To List All flagged
					*
					*/

				function	flagged	()
				{
								show_404	();
								exit	();
//			$this->load->view('records/flagged');
				}

				/*
					* To Display Assets details
					*
					*/

				function	details	($asset_id)
				{
								if	($asset_id)
								{
												$data['asset_id']	=	$asset_id;
												$data['asset_details']	=	$this->assets_model->get_asset_by_asset_id	($asset_id);
												$data['asset_guid']	=	$this->assets_model->get_guid_by_asset_id	($asset_id);
												$data['asset_localid']	=	$this->assets_model->get_localid_by_asset_id	($asset_id);
												$data['asset_subjects']	=	$this->assets_model->get_subjects_by_assets_id	($asset_id);
												$data['asset_dates']	=	$this->assets_model->get_assets_dates_by_assets_id	($asset_id);
												$data['asset_genres']	=	$this->assets_model->get_assets_genres_by_assets_id	($asset_id);
												$data['asset_creators_roles']	=	$this->assets_model->get_assets_creators_roles_by_assets_id	($asset_id);
												$data['asset_contributor_roles']	=	$this->assets_model->get_assets_contributor_roles_by_assets_id	($asset_id);
												$data['asset_publishers_roles']	=	$this->assets_model->get_assets_publishers_role_by_assets_id	($asset_id);
												$data['asset_coverages']	=	$this->assets_model->get_coverages_by_asset_id	($asset_id);
												$data['rights_summaries']	=	$this->assets_model->get_rights_summaries_by_asset_id	($asset_id);
												$data['asset_audience_levels']	=	$this->assets_model->get_audience_level_by_asset_id	($asset_id);
												$data['asset_audience_ratings']	=	$this->assets_model->get_audience_rating_by_asset_id	($asset_id);
												$data['annotations']	=	$this->assets_model->get_annotations_by_asset_id	($asset_id);
												$data['asset_instantiations']	=	$this->sphinx->instantiations_list	(array	('asset_id'	=>	$asset_id,	'search'	=>	''));
												$search_results_data	=	$this->sphinx->assets_listing	(array	('index'	=>	'assets_list'),	0,	1000);
												if	(isset	($search_results_data['records'])	&&	!	is_empty	($search_results_data['records']))
												{
																$search_results	=	$search_results_data['records'];
																$search_results_array	=	array	();
																$num_search_results	=	0;
																if	($search_results)
																{
																				foreach	($search_results	as	$search_result)
																				{
																								$search_results_array[]['id']	=	$search_result->id;
																				}
																				$num_search_results	=	count	($search_results);
																}
																# Get result number of current asset
																$search_result_pointer	=	0;
																foreach	($search_results_array	as	$search_result)
																{
																				if	($search_result['id']	===	$asset_id)
																								break;
																				$search_result_pointer	++;
																}
																$data['cur_result']	=	$search_result_pointer	+	1;

																# Get number of results
																$data['num_results']	=	$num_search_results;

																# Get result number of next listings
																if	($search_result_pointer	>=	($num_search_results	-	1))
																				$data['next_result_id']	=	FALSE;
																else
																				$data['next_result_id']	=	$search_results_array[$search_result_pointer	+	1]['id'];

																# Get result number of previous listings
																if	($search_result_pointer	<=	0	||	$num_search_results	==	1)
																				$data['prev_result_id']	=	FALSE;
																else
																				$data['prev_result_id']	=	$search_results_array[$search_result_pointer	-	1]['id'];
												}
												$this->load->view	('records/assets_details',	$data);
								}
								else
								{
												show_404	();
								}
				}

}
