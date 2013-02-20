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
												$tablesort[$index][]	=	'<span style="float:left;min-width:30px;max-width:30px;"><i style="margin:0px" class="unflag"></i></span>';
												$asset_title_type	=	explode(' | ',	trim(str_replace('(**)',	'',	$value->asset_title_type)));
												$asset_title	=	explode(' | ',	trim(str_replace('(**)',	'',	$value->asset_title)));
												$asset_title_ref	=	explode(' | ',	trim(str_replace('(**)',	'',	$value->asset_title_ref)));
												$asset_combine	=	'';
												foreach($asset_title	as	$aindex	=>	$title)
												{
																if(isset($asset_title_type[$aindex])	&&	$asset_title_type[$aindex]	!=	'')
																				$asset_combine.=	$asset_title_type[$aindex]	.	': ';
																if(isset($asset_title_ref[$aindex]))
																{
																				if($asset_title_ref[$aindex]	!=	'')
																				{
																								$asset_combine.="<a target='_blank' href='$asset_title_ref[$aindex]'>$title</a>: ";
																								$asset_combine.=' ('	.	$asset_title_ref[$aindex]	.	')';
																				}
																				else
																								$asset_combine.=$title;
																}
																else
																				$asset_combine.=$title;
																$asset_combine.='<div class="clearfix"></div>';
												}

												$tablesort[$index][]	=	str_replace("(**)",	'',	'<span style="float:left;min-width:200px;max-width:200px;">'.$value->organization.'</span>');
												$tablesort[$index][]	=	str_replace("(**)",	'',	'<span style="float:left;min-width:250px;max-width:250px;"><a href="'	.	site_url('records/details/'	.	$value->id)	.	'">'	.	$value->guid_identifier	.	'</a></span>');
												$tablesort[$index][]	=	str_replace("(**)",	'',	'<span style="float:left;min-width:250px;max-width:250px;">'.$value->local_identifier.'</span>');
												$tablesort[$index][]	=	str_replace("(**)",	'',	'<span style="float:left;min-width:300px;max-width:300px;">'.$asset_combine.'</span>');
												if(strlen($value->description)	>	200)
																$description	=	substr($value->description,	0,	strpos($value->description,	' ',	200))	.	'...';
												else
																$description	=	$value->description;
												$tablesort[$index][]	=	str_replace("(**)",	'',	'<span style="float:left;min-width:300px;max-width:350px;">'	.	$description	.	'</span>');
								}

								$dataTable	=	array(
								"sEcho"																=>	$this->input->get('sEcho')	+	1,
								"iTotalRecords"								=>	$data['count'],
								"iTotalDisplayRecords"	=>	$data['count'],
								'aaData'															=>	$tablesort
								);
								echo	json_encode($dataTable);
								exit;
				}

				public	function	sort_full_table()
				{
								$offset	=	($this->uri->segment(3))	?	$this->uri->segment(3)	:	0;
								$column	=	array(
								'Organization'				=>	'organization',
								'Titles'										=>	'asset_title',
								'AA_GUID'									=>	'guid_identifier',
								'Local_ID'								=>	'local_identifier',
								'Description'					=>	'description',
								'Subjects'								=>	'asset_subject',
								'Genre'											=>	'asset_genre',
								'Assets_Date'					=>	'dates',
								'Creator'									=>	'asset_creator_name',
								'Contributor'					=>	'asset_contributor_name',
								'Publisher'							=>	'asset_publisher_name',
								'Coverage'								=>	'asset_coverage',
								'Audience_Level'		=>	'asset_audience_level',
								'Audience_Rating'	=>	'asset_audience_rating',
								'Annotation'						=>	'asset_annotation',
								'Rights'										=>	'asset_rights');


								$this->session->unset_userdata('column');
								$this->session->unset_userdata('jscolumn');
								$this->session->unset_userdata('column_order');
								$this->session->set_userdata('jscolumn',	$this->input->get('iSortCol_0'));
								$this->session->set_userdata('column',	$column[$this->column_order[$this->input->get('iSortCol_0')]['title']]);
								$this->session->set_userdata('column_order',	$this->input->get('sSortDir_0'));

								$param	=	array('index'								=>	'assets_list');
								$records	=	$this->sphinx->assets_listing($param,	$offset);
								$data['total']	=	$records['total_count'];
								$records	=	$records['records'];
								$data['count']	=	count($records);
								$tablesort	=	array();
								foreach($records	as	$main_index	=>	$asset)
								{
												foreach($this->column_order	as	$row)
												{
																$type	=	$row['title'];

																if($type	==	'Organization')
																{
																				$tablesort[$main_index][]	=	$asset->organization;
																}
																else	if($type	==	'Titles')
																{
																				$asset_title_type	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_title_type)));
																				$asset_title	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_title)));
																				$asset_title_ref	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_title_ref)));
																				$column	=	'';
																				foreach($asset_title	as	$index	=>	$title)
																				{
																								if(isset($asset_title_type[$index])	&&	$asset_title_type[$index]	!=	'')
																												$column.=	$asset_title_type[$index]	.	': ';
																								if(isset($asset_title_ref[$index]))
																								{
																												if($asset_title_ref[$index]	!=	'')
																												{
																																$column.="<a target='_blank' href='$asset_title_ref[$index]'>$title</a>: ";
																																$column.=' ('	.	$asset_title_ref[$index]	.	')';
																												}
																												else
																																$column.=$title;
																								}
																								else
																												$column.=$title;
																								$column.='<div class="clearfix"></div>';
																				}
																				$tablesort[$main_index][]	=	$column;
																}
																else	if($type	==	'AA_GUID')
																{
																				$tablesort[$main_index][]	=	($asset->guid_identifier)	?	'<a href="'	.	site_url('records/details/'	.	$asset->id)	.	'" >'	.	$asset->guid_identifier	:	'';
																}
																else	if($type	==	'Local_ID')
																{
																				$tablesort[$main_index][]	=	$asset->local_identifier;
																}
																else	if($type	==	'Description')
																{
																				$column	=	'';
																				if(isset($asset->description)	&&	!	empty($asset->description))
																				{
																								$asset_description	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->description)));
																								$description_type	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->description_type)));

																								if(count($asset_description)	>	0)
																								{
																												foreach($asset_description	as	$index	=>	$description)
																												{
																																if(isset($description)	&&	!	empty($description))
																																{
																																				if(isset($description_type[$index])	&&	$description_type[$index]	!=	'')
																																								$column.='Type:'	.	$description_type[$index]	.	'<br/>';
																																				if(strlen($description)	>	160)
																																				{
																																								$messages	=	str_split($description,	160);
																																								$column.=	$messages[0]	.	' ...';
																																				}
																																				else
																																								$column.=$description;
																																}
																																$column	.=	'<div class="clearfix"></div>';
																												}
																								}
																				}
																				$tablesort[$main_index][]	=	$column;
																}
																else	if($type	==	'Subjects')
																{

																				$asset_subject	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_subject)));
																				$asset_subject_ref	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_subject_ref)));
																				$asset_subject_source	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_subject_source)));
																				$column	=	'';
																				if(count($asset_subject)	>	0)
																				{
																								foreach($asset_subject	as	$index	=>	$subject)
																								{

																												if(isset($asset_subject_ref[$index]))
																												{
																																if($asset_subject_ref[$index]	!=	'')
																																{
																																				$column.="<a target='_blank' href='$asset_subject_ref[$index]'>$subject</a>";
																																}
																																else
																																				$column.=$subject;
																												}
																												else
																																$column.=$subject;
																												if(isset($asset_subject_source[$index])	&&	$asset_subject_source[$index]	!=	'')
																																$column.=' ('	.	$asset_subject_source[$index]	.	')';
																												$column.='<div class="clearfix"></div>';
																								}
																				}
																				$tablesort[$main_index][]	=	$column;
																}
																else	if($type	==	'Genre')
																{

																				$asset_genre	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_genre)));
																				$asset_genre_ref	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_genre_ref)));
																				$asset_genre_source	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_genre_source)));
																				$column	=	'';
																				if(count($asset_genre)	>	0)
																				{
																								foreach($asset_genre	as	$index	=>	$genre)
																								{

																												if(isset($asset_genre_ref[$index]))
																												{
																																if($asset_genre_ref[$index]	!=	'')
																																{
																																				$column.="<a target='_blank' href='$asset_genre_ref[$index]'>$genre</a>";
																																}
																																else
																																				$column.=$genre;
																												}
																												else
																																$column.=$genre;
																												if(isset($asset_genre_source[$index])	&&	$asset_genre_source[$index]	!=	'')
																																$column.=' ('	.	$asset_genre_source[$index]	.	')';
																												$column.='<div class="clearfix"></div>';
																								}
																				}
																				$tablesort[$main_index][]	=	$column;
																}
																else	if($type	==	'Assets_Date')
																{
																				$column	=	'';
																				$asset_dates	=	explode(' | ',	$asset->dates);
																				$asset_dates_types	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->date_type)));
																				if(count($asset_dates)	>	0)
																				{
																								foreach($asset_dates	as	$index	=>	$dates)
																								{

																												if(isset($asset_dates_types[$index])	&&	$dates	>	0)
																												{
																																$column	.=	$asset_dates_types[$index]	.	': '	.	date('Y-m-d',	$dates);
																												}
																												else	if($dates	>	0)
																												{
																																$column.=date('Y-m-d',	$dates);
																												}
																												$column.='<div class="clearfix"></div>';
																								}
																				}
																				$tablesort[$main_index][]	=	$column;
																}
																else	if($type	==	'Creator')
																{
																				$asset_creator_name	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_creator_name)));
																				$asset_creator_ref	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_creator_ref)));
																				$asset_creator_affiliation	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_creator_affiliation)));
																				$asset_creator_role	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_creator_role)));
																				$asset_creator_role_ref	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_creator_role_ref)));
																				$asset_creator_role_source	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_creator_role_source)));
																				$column	=	'';
																				if(count($asset_creator_name)	>	0)
																				{
																								foreach($asset_creator_name	as	$index	=>	$creator_name)
																								{

																												if(isset($asset_creator_ref[$index])	&&	!	empty($asset_creator_ref[$index]))
																												{
																																$column.="<a target='_blank' href='$asset_creator_ref[$index]'>$creator_name</a>";
																												}
																												else
																																$column.=$creator_name;
																												if(isset($asset_creator_affiliation[$index])	&&	$asset_creator_affiliation[$index]	!=	'')
																																$column.=','	.	$asset_creator_affiliation[$index];

																												if(isset($asset_creator_role[$index])	&&	!	empty($asset_creator_role[$index]))
																												{
																																if(isset($asset_creator_role_ref[$index])	&&	!	empty($asset_creator_role_ref[$index]))
																																{
																																				$column.=",<a target='_blank' href='$asset_contributor_role_ref[$index]'>$asset_creator_role[$index]</a>";
																																}
																																else
																																				$column.=','	.	$asset_creator_role[$index];
																												}
																												if(isset($asset_creator_role_source[$index])	&&	$asset_creator_role_source[$index]	!=	'')
																																$column.=' ('	.	$asset_creator_role_source[$index]	.	')';
																												$column.='<div class="clearfix"></div>';
																								}
																				}
																				$tablesort[$main_index][]	=	$column;
																}
																else	if($type	==	'Contributor')
																{
																				$asset_contributor_name	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_contributor_name)));
																				$asset_contributor_ref	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_contributor_ref)));
																				$asset_contributor_affiliation	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_contributor_affiliation)));
																				$asset_contributor_role	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_contributor_role)));
																				$asset_contributor_role_ref	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_contributor_role_ref)));
																				$asset_contributor_role_source	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_contributor_role_source)));
																				$column	=	'';
																				if(count($asset_contributor_name)	>	0)
																				{
																								foreach($asset_contributor_name	as	$index	=>	$contributor_name)
																								{

																												if(isset($asset_contributor_ref[$index])	&&	!	empty($asset_contributor_ref[$index]))
																												{
																																$column.="<a target='_blank' href='$asset_contributor_ref[$index]'>$contributor_name</a>";
																												}
																												else
																																$column.=$contributor_name;
																												if(isset($asset_contributor_affiliation[$index])	&&	$asset_contributor_affiliation[$index]	!=	'')
																																$column.=','	.	$asset_contributor_affiliation[$index];

																												if(isset($asset_contributor_role[$index])	&&	!	empty($asset_contributor_role[$index]))
																												{
																																if(isset($asset_contributor_role_ref[$index])	&&	!	empty($asset_contributor_role_ref[$index]))
																																{
																																				$column.=",<a target='_blank' href='$asset_contributor_role_ref[$index]'>$asset_contributor_role[$index]</a>";
																																}
																																else
																																				$column.=','	.	$asset_contributor_role[$index];
																												}
																												if(isset($asset_contributor_role_source[$index])	&&	$asset_contributor_role_source[$index]	!=	'')
																																$column.=' ('	.	$asset_contributor_role_source[$index]	.	')';
																												$column.='<div class="clearfix"></div>';
																								}
																				}
																				$tablesort[$main_index][]	=	$column;
																}
																else	if($type	==	'Publisher')
																{
																				$asset_publisher_name	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_publisher_name)));
																				$asset_publisher_ref	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_publisher_ref)));
																				$asset_publisher_affiliation	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_publisher_affiliation)));
																				$asset_publisher_role	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_publisher_role)));
																				$asset_publisher_role_ref	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_publisher_role_ref)));
																				$asset_publisher_role_source	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_publisher_role_source)));
																				$column	=	'';
																				if(count($asset_publisher_name)	>	0)
																				{
																								foreach($asset_publisher_name	as	$index	=>	$publisher_name)
																								{

																												if(isset($asset_publisher_ref[$index])	&&	!	empty($asset_publisher_ref[$index]))
																												{
																																$column.="<a target='_blank' href='$asset_publisher_ref[$index]'>$publisher_name</a>";
																												}
																												else
																																$column.=$publisher_name;
																												if(isset($asset_publisher_affiliation[$index])	&&	$asset_publisher_affiliation[$index]	!=	'')
																																$column.=','	.	$asset_publisher_affiliation[$index];

																												if(isset($asset_publisher_role[$index])	&&	!	empty($asset_publisher_role[$index]))
																												{
																																if(isset($asset_publisher_role_ref[$index])	&&	!	empty($asset_publisher_role_ref[$index]))
																																{
																																				$column.=",<a target='_blank' href='$asset_publisher_role_ref[$index]'>$asset_publisher_role[$index]</a>";
																																}
																																else
																																				$column.=','	.	$asset_publisher_role[$index];
																												}
																												if(isset($asset_publisher_role_source[$index])	&&	$asset_publisher_role_source[$index]	!=	'')
																																$column.=' ('	.	$asset_publisher_affiliation[$index]	.	')';
																												$column.='<div class="clearfix"></div>';
																								}
																				}
																				$tablesort[$main_index][]	=	$column;
																}
																else	if($type	==	'Coverage')
																{
																				$asset_coverage	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_coverage)));
																				$asset_coverage_type	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_coverage_type)));
																				$column	=	'';
																				if(count($asset_coverage)	>	0)
																				{
																								foreach($asset_coverage	as	$index	=>	$coverage)
																								{
																												if(isset($asset_coverage_type[$index])	&&	!	empty($asset_coverage_type[$index]))
																												{
																																$column.=	$asset_coverage_type[$index]	.	':';
																												}
																												if(isset($coverage)	&&	!	empty($coverage))
																												{
																																$column.=	$coverage;
																												}
																												$column.='<div class="clearfix"></div>';
																								}
																				}
																				$tablesort[$main_index][]	=	$column;
																}
																else	if($type	==	'Audience_Level')
																{
																				$asset_audience_level	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_audience_level)));
																				$asset_audience_level_ref	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_audience_level_ref)));
																				$asset_audience_level_source	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_audience_level_source)));
																				$column	=	'';
																				if(count($asset_audience_level)	>	0)
																				{
																								foreach($asset_audience_level	as	$index	=>	$audience_level)
																								{

																												if(isset($asset_audience_level_ref[$index])	&&	!	empty($asset_audience_level_ref[$index]))
																												{
																																$column.="<a target='_blank' href='$asset_audience_level_ref[$index]'>$audience_level</a>";
																												}
																												else
																																$column.=$audience_level;
																												if(isset($asset_audience_level_source[$index])	&&	$asset_audience_level_source[$index]	!=	'')
																																$column.=' ('	.	$asset_audience_level_source[$index]	.	')';
																												$column.='<div class="clearfix"></div>';
																								}
																				}
																				$tablesort[$main_index][]	=	$column;
																}
																else	if($type	==	'Audience_Rating')
																{
																				$asset_audience_rating	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_audience_rating)));
																				$asset_audience_rating_ref	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_audience_rating_ref)));
																				$asset_audience_rating_source	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_audience_rating_source)));
																				$column	=	'';
																				if(count($asset_audience_rating)	>	0)
																				{
																								foreach($asset_audience_rating	as	$index	=>	$audience_rating)
																								{

																												if(isset($asset_audience_rating_ref[$index])	&&	!	empty($asset_audience_rating_ref[$index]))
																												{
																																$column.="<a target='_blank' href='$asset_audience_level_ref[$index]'>$audience_rating</a>";
																												}
																												else
																																$column.=$audience_rating;
																												if(isset($asset_audience_rating_source[$index])	&&	$asset_audience_rating_source[$index]	!=	'')
																																$column.=' ('	.	$asset_audience_rating_source[$index]	.	')';
																												$column.='<div class="clearfix"></div>';
																								}
																				}
																				$tablesort[$main_index][]	=	$column;
																}
																else	if($type	==	'Annotation')
																{
																				$asset_annotation	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_annotation)));
																				$asset_annotation_ref	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_annotation_ref)));
																				$asset_annotation_type	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_annotation_type)));
																				$column	=	'';
																				if(count($asset_annotation)	>	0)
																				{
																								foreach($asset_annotation	as	$index	=>	$annotation)
																								{
																												if(isset($asset_annotation_type[$index])	&&	$asset_annotation_type[$index]	!=	'')
																																$column.=$asset_annotation_type[$index]	.	': ';
																												if(isset($asset_annotation_ref[$index])	&&	!	empty($asset_annotation_ref[$index]))
																												{
																																$column.="<a target='_blank' href='$asset_annotation_ref[$index]'>$annotation</a>: ";
																												}
																												else
																																$column.=$annotation;

																												$column.='<div class="clearfix"></div>';
																								}
																				}
																				$tablesort[$main_index][]	=	$column;
																}
																else	if($type	==	'Rights')
																{
																				$asset_rights	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_rights)));
																				$asset_rights_link	=	explode(' | ',	trim(str_replace('(**)',	'',	$asset->asset_rights_link)));
																				$column	=	'';
																				if(count($asset_rights)	>	0)
																				{
																								foreach($asset_rights	as	$index	=>	$rights)
																								{

																												if(isset($asset_rights_link[$index])	&&	!	empty($asset_rights_link[$index]))
																												{
																																$column.="<a target='_blank' href='"	.	$asset_rights_link[$index]	.	"'>"	.	$rights	.	"</a>: ";
																												}
																												else
																																$column.=$rights;
																												$column.='<div class="clearfix"></div>';
																								}
																				}
																				$tablesort[$main_index][]	=	$column;
																}
												}

												debug($tablesort);
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

}
