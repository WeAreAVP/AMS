<?php

/**
	* Sphinx Model.
	*
	* @package    AMS
	* @subpackage Sphinx_Model
	* @author     Ali Raza
	*/
class	Sphinx_Model	extends	CI_Model
{
				/*
					*
					* constructor. Load Sphinx Search Library
					* 
					*/

				function	__construct()
				{
								parent::__construct();
								$this->load->library('sphinxsearch');
				}

				/**
					* Get list of all the stations based on search params
					* 
					* @Perm Get Array of Perm possible value of array are certified,agreed,start_date,end_date,search_kewords
					* @return Object 
					*/
				public	function	search_stations($params,	$offset	=	0,	$limit	=	100)
				{
								$stations_list	=	array();
								$total_record	=	0;
								$this->sphinxsearch->reset_filters();
								$this->sphinxsearch->reset_group_by();
								//$where = $this->get_sphinx_search_condtion($params);
								$mode	=	SPH_MATCH_EXTENDED;
								$this->sphinxsearch->set_array_result(true);
								$this->sphinxsearch->set_match_mode($mode);
								$this->sphinxsearch->set_connect_timeout(120);
								if($limit)
												$this->sphinxsearch->set_limits((int)	$offset,	(int)	$limit,	(	$limit	>	1000	)	?	$limit	:	1000	);
								if(isset($params['certified'])	&&	$params['certified']	!=	'')
												$this->sphinxsearch->set_filter("is_certified",	array($params['certified']));
								if(isset($params['agreed'])	&&	$params['agreed']	!=	'')
												$this->sphinxsearch->set_filter("is_agreed",	array($params['agreed']));
								if(isset($params['start_date'])	&&	$params['start_date']	!=	''	&&	isset($params['end_date'])	&&	$params['end_date']	!=	'')
												$this->sphinxsearch->set_filter_range("start_date",	strtotime($params['start_date']),	strtotime($params['end_date']));


								$res	=	$this->sphinxsearch->query($params['search_keywords'],	'stations');


								$execution_time	=	$res['time'];
								if($res)
								{
												$total_record	=	$res['total_found'];
												if($total_record	>	0)
												{
																if(isset($res['matches']))
																{
																				foreach($res['matches']	as	$record)
																				{
																								if($this->is_station_user)
																								{
																												if($this->station_id	==	$record['id'])
																																$stations_list[]	=	(object)	array_merge(array('id'	=>	$record['id']),	$record['attrs']);
																								}
																								else
																								{
																												$stations_list[]	=	(object)	array_merge(array('id'	=>	$record['id']),	$record['attrs']);
																								}
																				}
																}
												}
								}

								return	array("total_count"	=>	$total_record,	"records"					=>	$stations_list,	"query_time"		=>	$execution_time);
				}

				public	function	facet_index($index_name,$offset	=	0,	$limit	=	1000)
				{
								$list	=	array();
								$total_record	=	0;
								$this->sphinxsearch->reset_filters();
								$this->sphinxsearch->reset_group_by();

								$mode	=	SPH_MATCH_EXTENDED;
								$this->sphinxsearch->set_array_result(true);
								$this->sphinxsearch->set_match_mode($mode);
								$this->sphinxsearch->set_connect_timeout(120);
								if($limit)
												$this->sphinxsearch->set_limits((int)	$offset,	(int)	$limit,	(	$limit	>	1000	)	?	$limit	:	1000	);
								$res	=	$this->sphinxsearch->query('',	$index_name);


								$execution_time	=	$res['time'];
								if($res)
								{
												$total_record	=	$res['total_found'];
												if($total_record	>	0)
												{
																if(isset($res['matches']))
																{
																				foreach($res['matches']	as	$record)
																				{


																								$list[]	=	(object)	array_merge(array('id'	=>	$record['id']),	$record['attrs']);
																				}
																}
												}
								}

								return	array("total_count"	=>	$total_record,	"records"					=>	$list,	"query_time"		=>	$execution_time);
				}

				/*
					* Update Index Attribute Value
					* @Perm Name of index
					* @Perm Name of attribute
					* @Perm Value of attribute
					*/

				public	function	update_indexes($index,	$attr,	$values)
				{
								$this->sphinxsearch->update_attributes($index,	$attr,	$values);
				}

				/*
					* Get All Stations
					*/

				public	function	get_all_stations()
				{
								$res	=	$this->search_stations('',	0,	400);
								if($res['total_count']	>	0)
								{
												return	$res['records'];
								}
				}

				function	instantiations_list($params,	$offset	=	0,	$limit	=	100)
				{
//        /usr/bin/indexer --all --rotate
								$instantiations	=	array();
								$total_record	=	0;
								$this->sphinxsearch->reset_filters();
								$this->sphinxsearch->reset_group_by();
								//$where = $this->get_sphinx_search_condtion($params);
								if(isset($params['asset_id']))
								{
												$this->sphinxsearch->set_filter("assets_id",	array($params['asset_id']));
								}

								$mode	=	SPH_MATCH_EXTENDED;
								$this->sphinxsearch->set_array_result(true);
								$this->sphinxsearch->set_match_mode($mode);
								$this->sphinxsearch->set_connect_timeout(120);
								if($limit)
												$this->sphinxsearch->set_limits((int)	$offset,	(int)	$limit,	(	$limit	>	1000	)	?	$limit	:	1000	);


								$query	=	$this->make_where_clause();



								$res	=	$this->sphinxsearch->query($query,	'instantiations_list');


								$execution_time	=	$res['time'];
								if($res)
								{
												$total_record	=	$res['total_found'];
												if($total_record	>	0)
												{
																if(isset($res['matches']))
																{
																				foreach($res['matches']	as	$record)
																				{
																								$instantiations[]	=	(object)	array_merge(array('id'	=>	$record['id']),	$record['attrs']);
																				}
																}
												}
								}

								return	array("total_count"	=>	$total_record,	"records"					=>	$instantiations,	"query_time"		=>	$execution_time);
				}

				function	make_where_clause()
				{
								$where	=	'';
								if(isset($this->session->userdata['organization'])	&&	$this->session->userdata['organization']	!=	'')
								{
												$station_name	=	str_replace('|||',	'" | "',	trim($this->session->userdata['organization']));
												$where	.=" @organization \"^$station_name$\"";
								}
								if(isset($this->session->userdata['states'])	&&	$this->session->userdata['states']	!=	'')
								{
												$station_state	=	str_replace('|||',	'" | "',	trim($this->session->userdata['states']));
												$where	.=" @state \"^$station_state$\"";
								}
								if(isset($this->session->userdata['nomination'])	&&	$this->session->userdata['nomination']	!=	'')
								{
												$nomination	=	str_replace('|||',	'" | "',	trim($this->session->userdata['nomination']));
												$where	.=" @status \"^$nomination$\"";
								}
								if(isset($this->session->userdata['media_type'])	&&	$this->session->userdata['media_type']	!=	'')
								{
												$media_type	=	str_replace('|||',	'" | "',	trim($this->session->userdata['media_type']));
												$where	.=" @media_type \"^$media_type$\"";
								}
								if(isset($this->session->userdata['physical_format'])	&&	$this->session->userdata['physical_format']	!=	'')
								{

												$physical_format	=	str_replace('|||',	'" | "',	trim($this->session->userdata['physical_format']));
												$where	.=" @format_name \"^$physical_format$\" @format_type \"physical\"";
								}
								if(isset($this->session->userdata['digital_format'])	&&	$this->session->userdata['digital_format']	!=	'')
								{
												$digital_format	=	str_replace('|||',	'" | "',	trim($this->session->userdata['digital_format']));
												$where	.=" @format_name \"^$digital_format$\" @format_type \"digital\"";
								}
								if(isset($this->session->userdata['generation'])	&&	$this->session->userdata['generation']	!=	'')
								{
												$generation	=	str_replace('|||',	'" | "',	trim($this->session->userdata['generation']));
												$where	.=" @generation \"^$generation$\"";
								}
								if(isset($this->session->userdata['digitized'])	&&	$this->session->userdata['digitized']	===	'1')
								{
												$where	.=' @digitized "1" @!actual_duration "0"';
								}
								if(isset($this->session->userdata['migration_failed'])	&&	$this->session->userdata['migration_failed']	===	'1')
								{
												$where	.=' @event_type "migration" @event_outcome "FAIL"';
								}

								if(isset($this->session->userdata['custom_search'])	&&	$this->session->userdata['custom_search']	!=	'')
								{
												$custom_search	=	str_replace('|||',	'"',	trim($this->session->userdata['custom_search']));
												$where	.=$custom_search;
								}
								if(isset($this->session->userdata['date_range'])	&&	$this->session->userdata['date_range']	!=	'')
								{
												$date_range	=	explode("to",	$this->session->userdata['date_range']);
												if(isset($date_range[0])	&&	trim($date_range[0])	!=	'')
												{
																$start_date	=	strtotime(trim($date_range[0]));
												}
												if(isset($date_range[1])	&&	trim($date_range[1])	!=	'')
												{
																$end_date	=	strtotime(trim($date_range[1]));
												}
												if($start_date	!=	''	&&	isset($end_date)	&&	is_numeric($end_date)	&&	$end_date	>=	$start_date)
												{
																$this->sphinxsearch->set_filter_range("dates",	$start_date,	$end_date);
												}
												else
												{
																$this->sphinxsearch->set_filter_range("dates",	$start_date,	999999999999);
												}
								}
								if(isset($this->session->userdata['date_type'])	&&	$this->session->userdata['date_type']	!=	'')
								{
												$date_type	=	str_replace('|||',	'" | "',	trim($this->session->userdata['date_type']));
												$where	.=" @date_type \"$date_type\"";
								}
								if($this->is_station_user)
								{

												$where	.=" @organization \"	^$this->station_name$\"";
								}

								return	$where;
				}

				function	assets_listing($params,	$offset	=	0,	$limit	=	100)
				{
								$instantiations	=	array();
								$total_record	=	0;
								$this->sphinxsearch->reset_filters();
								$this->sphinxsearch->reset_group_by();
								$mode	=	SPH_MATCH_EXTENDED;
								$this->sphinxsearch->set_array_result(true);
								$this->sphinxsearch->set_match_mode($mode);
								$this->sphinxsearch->set_connect_timeout(120);
								if($limit)
												$this->sphinxsearch->set_limits((int)	$offset,	(int)	$limit,	(	$limit	>	1000	)	?	$limit	:	1000	);


								$query	=	$this->make_where_clause();
								$res	=	$this->sphinxsearch->query($query,	$params['index']);


								$execution_time	=	$res['time'];
								if($res)
								{
												$total_record	=	$res['total_found'];
												if($total_record	>	0)
												{
																if(isset($res['matches']))
																{
																				foreach($res['matches']	as	$record)
																				{
																								$instantiations[]	=	(object)	array_merge(array('id'	=>	$record['id']),	$record['attrs']);
																				}
																}
												}
								}

								return	array("total_count"	=>	$total_record,	"records"					=>	$instantiations,	"query_time"		=>	$execution_time);
				}

}

?>
