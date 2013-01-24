<?php

/**
	* Assets Model.
	*
	* @package    AMS
	* @subpackage assets_model
	* @author     ALi RAza
	*/
class	Instantiations_Model	extends	CI_Model
{

				/**
					* constructor. set table name amd prefix
					* 
					*/
				function	__construct()
				{
								parent::__construct();
								$this->_prefix	=	'';

								$this->table_date_types	=	'date_types';
								$this->table_generations	=	'generations';
								$this->table_instantiations	=	'instantiations';
								$this->table_relation_types	=	'relation_types';
								$this->table_data_rate_units	=	'data_rate_units';
								$this->table_instantiation_dates	=	'instantiation_dates';
								$this->table_instantiation_colors	=	'instantiation_colors';
								$this->table_instantiation_formats	=	'instantiation_formats';
								$this->table_instantiation_relations	=	'instantiation_relations';
								$this->table_instantiation_identifier	=	'instantiation_identifier';
								$this->table_instantiation_dimensions	=	'instantiation_dimensions';
								$this->table_instantiation_media_types	=	'instantiation_media_types';
								$this->table_instantiation_generations	=	'instantiation_generations';
								$this->table_instantiation_annotations	=	'instantiation_annotations';

								$this->_assets_table	=	'assets';
								$this->asset_titles	=	'asset_titles';
								$this->stations	=	'stations';
								$this->table_nominations	=	'nominations';
								$this->table_nomination_status	=	'nomination_status';
								$this->table_events	=	'events';
								$this->table_event_types	=	'event_types';
				}

				function	get_by_id($id)
				{
								$this->db->where('id ',	$id);
								return	$this->db->get($this->table_instantiations)->row();
				}

				function	get_date_types()
				{
								$this->db->order_by("date_type");
								return	$this->db->get($this->table_date_types)->result();
				}

				function	list_all()
				{
								$this->db->select("$this->table_instantiations.*",	FALSE);
								$this->db->select("$this->_assets_table.id as asset_id",	FALSE);
								$this->db->select("GROUP_CONCAT($this->asset_titles.title SEPARATOR ' | ') as multi_assets",	FALSE);
								$this->db->select("$this->stations.station_name as organization",	FALSE);
								$this->db->select("$this->table_instantiation_dates.instantiation_date",	FALSE);
								$this->db->select("$this->table_date_types.date_type",	FALSE);
								$this->db->select("$this->table_instantiation_media_types.media_type",	FALSE);
								$this->db->select("$this->table_instantiation_formats.format_type,$this->table_instantiation_formats.format_name",	FALSE);
								$this->db->select("$this->table_instantiation_colors.color",	FALSE);
								$this->db->select("$this->table_generations.generation",	FALSE);
								$this->db->select("$this->table_nomination_status.status",	FALSE);
								$this->db->select("CASE WHEN $this->table_events.event_outcome=0 THEN 'FAIL' WHEN $this->table_events.event_outcome=1 THEN 'PASS' END AS outcome_evnet",	FALSE);
								$this->db->select("$this->table_event_types.event_type",	FALSE);






								$this->db->join($this->_assets_table,	"$this->_assets_table.id = $this->table_instantiations.assets_id",	'left');
								$this->db->join($this->_table_asset_descriptions,	"$this->_table_asset_descriptions.assets_id = $this->_assets_table.id",	'left');

								$this->db->join($this->asset_titles,	"$this->asset_titles.assets_id	 = $this->table_instantiations.assets_id",	'left');
								$this->db->join($this->stations,	"$this->stations.id = $this->_assets_table.stations_id",	'left');
								$this->db->join($this->table_instantiation_dates,	"$this->table_instantiation_dates.instantiations_id = $this->table_instantiations.id",	'left');
								$this->db->join($this->table_date_types,	"$this->table_date_types.id = $this->table_instantiation_dates.date_types_id",	'left');
								$this->db->join($this->table_instantiation_media_types,	"$this->table_instantiation_media_types.id = $this->table_instantiations.instantiation_media_type_id",	'left');
								$this->db->join($this->table_instantiation_formats,	"$this->table_instantiation_formats.instantiations_id = $this->table_instantiations.id",	'left');
								$this->db->join($this->table_instantiation_colors,	"$this->table_instantiation_colors.id = $this->table_instantiations.instantiation_colors_id",	'left');
								$this->db->join($this->table_instantiation_generations,	"$this->table_instantiation_generations.instantiations_id = $this->table_instantiations.id",	'left');
								$this->db->join($this->table_generations,	"$this->table_generations.id = $this->table_instantiation_generations.generations_id",	'left');
								$this->db->join($this->table_nominations,	"$this->table_nominations.instantiations_id = $this->table_instantiations.id",	'left');
								$this->db->join($this->table_nomination_status,	"$this->table_nomination_status.id = $this->table_nominations.nomination_status_id",	'left');
								$this->db->join($this->table_events,	"$this->table_events.instantiations_id	 = $this->table_instantiations.id",	'left');
								$this->db->join($this->table_event_types,	"$this->table_event_types.id	 = $this->table_events.event_types_id",	'left');
								$this->db->limit(5);
								$this->db->group_by("$this->_assets_table.id");
								$result	=	$this->db->get($this->table_instantiations);
								if(isset($result)	&&	!	empty($result))
								{
												return	$result->result();
								}
								return	$result;
				}

				function	get_nomination_status()
				{
								$this->db->order_by("status");
								return	$query	=	$this->db->get($this->table_nomination_status)->result();
				}

				function	get_media_types()
				{
								$this->db->order_by("media_type");
								return	$query	=	$this->db->get($this->table_instantiation_media_types)->result();
				}

				function	get_physical_formats()
				{
								$this->db->order_by("format_name");
								$this->db->where('format_type ',	'physical');
								$this->db->group_by('format_name');
								return	$query	=	$this->db->get($this->table_instantiation_formats)->result();
				}

				function	get_digital_formats()
				{
								$this->db->order_by("format_name");
								$this->db->where('format_type ',	'digital');
								$this->db->group_by('format_name');
								return	$query	=	$this->db->get($this->table_instantiation_formats)->result();
				}

				function	get_generations()
				{
								$this->db->order_by("generation");
								$this->db->group_by('generation');
								return	$query	=	$this->db->get($this->table_generations)->result();
				}

				function	get_file_size()
				{
								$this->db->select("file_size");
								$this->db->where('file_size !=',	'NULL');
								$this->db->order_by("file_size");
								$this->db->distinct();
								$result	=	$this->db->get($this->table_instantiations)->result();
								return	$result;
				}

				function	get_event_type()
				{
								$this->db->order_by("event_type");
								$this->db->group_by('event_type');
								return	$query	=	$this->db->get($this->table_event_types)->result();
				}

				function	get_event_outcome()
				{
								$this->db->select("CASE WHEN event_outcome=0 THEN 'FAIL' WHEN event_outcome=1 THEN 'PASS' END AS event_outcome",	FALSE);
								$this->db->order_by("event_outcome");
								$query	=	$this->db->get($this->table_events)->result();
								return	$query;
				}

				/**
					* 
					* @param Integer $instantiation_id
					* @return boolean , Object
					*/
				function	get_events_by_instantiation_id($instantiation_id)
				{
								$this->db->select("$this->table_event_types.event_type,$this->table_events.id,$this->table_events.instantiations_id,$this->table_events.event_types_id,$this->table_events.event_date,CASE WHEN $this->table_events.event_outcome=0 THEN 'FAIL' WHEN $this->table_events.event_outcome=1 THEN 'PASS' END AS event_outcome,$this->table_events.event_note",	FALSE);
								$this->db->join($this->table_event_types,	"$this->table_event_types.id	 = $this->table_events.event_types_id",	'left');
								$this->db->where('instantiations_id',	$instantiation_id);
								$query	=	$this->db->get($this->table_events);
								if(isset($query)	&&	!	is_empty($query))
								{
												return	$query->result();
								}
								return	false;
				}

				/**
					* search generations by @generation
					* 
					* @param type $status
					* @return object 
					*/
				function	get_generations_by_generation($generation)
				{
								$sql	=	'SELECT * FROM `'	.	$this->table_generations	.	'` WHERE `generation` LIKE CONVERT( _utf8 "'	.	$generation	.	'" USING latin1 )
																COLLATE latin1_swedish_ci';
								$res	=	$this->db->query($sql);
								if(isset($res)	&&	!	empty($res))
								{
												return	$res->row();
								}
								return	false;
				}

				/**
					* search instantiation event exists 
					* 
					* @param type array of different event fields
					* @param $instantiation_id
					* @return bool
					*/
				function	is_event_exists($instantiation_id,	$event_types_id)
				{
								$this->db->where('instantiations_id',	$instantiation_id);
								$this->db->where('event_types_id',	$event_types_id);
								$res	=	$this->db->get($this->table_events);
								if(isset($res)	&&	!	empty($res))
												return	$res->row();
								return	false;
				}

				/**
					* search instantiation by @guid and $physical_format
					* 
					* @param type $guid
					* @param type $physical_format
					* @return object 
					*/
				function	get_instantiation_by_guid_physical_format($guid,	$physical_format)
				{
								$sql	=	'SELECT ins.id,IFNULL(gen.generation,"")  FROM instantiations AS ins 
				LEFT JOIN identifiers AS ide ON  ins.assets_id=ide.assets_id
				LEFT JOIN instantiation_formats AS inf ON  ins.id=inf.instantiations_id
				LEFT JOIN instantiation_generations AS ing ON  ins.id=ing.instantiations_id
				LEFT JOIN generations AS gen ON ing.generations_id=gen.id
				WHERE ide.identifier LIKE "'	.	$guid	.	'" AND inf.format_name LIKE "'	.	$physical_format	.	'" AND inf.format_type="physical"';
								$res	=	$this->db->query($sql);
								if(isset($res)	&&	!	empty($res))
								{
												return	$res->row();
								}
								return	false;
				}

				/**
					* search event_type id by @event_type
					* 
					* @param type $event_type
					* @return object 
					*/
				function	get_id_by_event_type($event_type)
				{
								$this->db->where('event_type LIKE',	$event_type);
								$res	=	$this->db->get($this->table_event_types);
								if(isset($res)	&&	!	empty($res))
								{
												return	$res->row();
								}
								return	false;
				}

				/**
					* search instantiation_colors by @color
					* 
					* @param type $status
					* @return object 
					*/
				function	get_instantiation_colors_by_color($color)
				{
								$this->db->where('color LIKE',	$color);
								$res	=	$this->db->get($this->table_instantiation_colors);
								if(isset($res)	&&	!	empty($res))
								{
												return	$res->row();
								}
								return	false;
				}

				/**
					* search data_rate_units by @unit
					* 
					* @param type $status
					* @return object 
					*/
				function	get_data_rate_units_by_unit($unit_of_measure)
				{
								$this->db->where('unit_of_measure LIKE',	$unit_of_measure);
								$res	=	$this->db->get($this->table_data_rate_units);
								if(isset($res)	&&	!	empty($res))
								{
												return	$res->row();
								}
								return	false;
				}

				/**
					* search instantiation_media_types by @media_type
					* 
					* @param type $status
					* @return object 
					*/
				function	get_instantiation_media_types_by_media_type($media_type)
				{
								$this->db->where('media_type LIKE',	$media_type);
								$res	=	$this->db->get($this->table_instantiation_media_types);
								if(isset($res)	&&	!	empty($res))
								{
												return	$res->row();
								}
								return	false;
				}

				/**
					* search date_types by @date_type
					* 
					* @param type $status
					* @return object 
					*/
				function	get_date_types_by_type($date_type)
				{
								$this->db->where('date_type LIKE',	$date_type);
								$res	=	$this->db->get($this->table_date_types);
								if(isset($res)	&&	!	empty($res))
								{
												return	$res->row();
								}
								return	false;
				}

				/*
					*
					*  Insert the record in data_rate_units table
					*  @param array $data
					*  @return integer last_inserted id
					* 
					*/

				function	insert_data_rate_units($data)
				{
								$this->db->insert($this->table_data_rate_units,	$data);
								return	$this->db->insert_id();
				}

				/*
					*
					*  Insert the record in generations table
					*  @param array $data
					*  @return integer last_inserted id
					* 
					*/

				function	insert_generations($data)
				{
								$this->db->insert($this->table_generations,	$data);
								return	$this->db->insert_id();
				}

				/*
					*
					*  Insert the record in instantiation_generations table
					*  @param array $data
					*  @return integer last_inserted id
					* 
					*/

				function	insert_instantiation_generations($data)
				{
								$this->db->insert($this->table_instantiation_generations,	$data);
								return	$this->db->insert_id();
				}

				/*
					*
					*  Insert the record in instantiation_media_types table
					*  @param array $data
					*  @return integer last_inserted id
					* 
					*/

				function	insert_instantiation_media_types($data)
				{
								$this->db->insert($this->table_instantiation_media_types,	$data);
								return	$this->db->insert_id();
				}

				/*
					*
					*  Insert the record in instantiation_formats table
					*  @param array $data
					*  @return integer last_inserted id
					* 
					*/

				function	insert_instantiation_formats($data)
				{
								$this->db->insert($this->table_instantiation_formats,	$data);
								return	$this->db->insert_id();
				}

				/*
					*
					*  Insert the record in instantiation_dimensions table
					*  @param array $data
					*  @return integer last_inserted id
					* 
					*/

				function	insert_instantiation_dimensions($data)
				{
								$this->db->insert($this->table_instantiation_dimensions,	$data);
								return	$this->db->insert_id();
				}

				/*
					*
					*  Insert the record in instantiations table
					*  @param array $data
					*  @return integer last_inserted id
					* 
					*/

				function	insert_instantiations($data)
				{
								$data['created']	=	date('Y-m-d H:i:s');
								$this->db->insert($this->table_instantiations,	$data);
								return	$this->db->insert_id();
				}

				/**
					* update the instantiations record
					* 
					* @param type $instantiation_id
					* @param array $data
					* @return boolean 
					*/
				function	update_instantiations($instantiation_id,	$data)
				{
								$data['updated']	=	date('Y-m-d H:i:s');
								$this->db->where('id',	$instantiation_id);
								return	$this->db->update($this->table_instantiations,	$data);
				}

				/*
					*
					*  Insert the record in instantiation_identifier table
					*  @param array $data
					*  @return integer last_inserted id
					* 
					*/

				function	insert_instantiation_identifier($data)
				{
								$this->db->insert($this->table_instantiation_identifier,	$data);
								return	$this->db->insert_id();
				}

				/*
					*
					*  Insert the record in instantiation_dates table
					*  @param array $data
					*  @return integer last_inserted id
					* 
					*/

				function	insert_instantiation_dates($data)
				{
								$this->db->insert($this->table_instantiation_dates,	$data);
								return	$this->db->insert_id();
				}

				/*
					*
					*  Insert the record in date_types table
					*  @param array $data
					*  @return integer last_inserted id
					* 
					*/

				function	insert_date_types($data)
				{
								$this->db->insert($this->table_date_types,	$data);
								return	$this->db->insert_id();
				}

				/*
					*
					*  Insert the record in instantiation_colors table
					*  @param array $data
					*  @return integer last_inserted id
					* 
					*/

				function	insert_instantiation_colors($data)
				{
								$this->db->insert($this->table_instantiation_colors,	$data);
								return	$this->db->insert_id();
				}

				/*
					*
					*  Insert the record in instantiation_annotations table
					*  @param array $data
					*  @return integer last_inserted id
					* 
					*/

				function	insert_instantiation_annotations($data)
				{
								$this->db->insert($this->table_instantiation_annotations,	$data);
								return	$this->db->insert_id();
				}

				/*
					*
					*  Insert the record in table_events table
					*  @param array $data
					*  @return integer last_inserted id
					* 
					*/

				function	insert_event($data)
				{
								$this->db->insert($this->table_events,	$data);
								return	$this->db->insert_id();
				}

				/**
					* update the events record
					* 
					* @param type $id
					* @param array $data
					* @return boolean 
					*/
				function	update_event($id,	$data)
				{
								$this->db->where('id',	$id);
								return	$this->db->update($this->table_events,	$data);
				}

				/*
					*
					*  Insert the record in table_event_types table
					*  @param array $data
					*  @return integer last_inserted id
					* 
					*/

				function	insert_event_types($data)
				{
								$this->db->insert($this->table_event_types,	$data);
								return	$this->db->insert_id();
				}

				/**
					* Insert or update event 
					* 
					* @param Integer $instantiation_id use to match instantiation
					* @param Integer $event_types_id   use to event_types_id
					* @param Array   $event_data       use to insert event type
					* 
					* @return helper
					*/
				function	_insert_or_update_event($instantiation_id,	$event_types_id,	$event_data)
				{
								$is_exists	=	$this->is_event_exists($instantiation_id,	$event_types_id);
								if($is_exists)
								{
												echo	'<strong><br/>Event migration already Exists against Instantiation Id: '	.	$instantiation_id	.	'</strong><br/>';
												print_r($event_data);
												$this->update_event($is_exists->id,	$event_data);
								}
								else
								{
												echo	'<strong><br/>New migration event against Instantiation Id: '	.	$instantiation_id	.	'</strong><br/>';
												print_r($event_data);
												$this->insert_event($event_data);
								}
				}

				/**
					* Get event type Id on event_type base else store event  
					* 
					* @param string $event_type use to check/store event types
					* 
					* @return integer event_types_id
					*/
				function	_get_event_type($event_type)
				{
								$event_type_data	=	$this->get_id_by_event_type($event_type);
								if($event_type_data)
								{
												$event_types_id	=	$event_type_data->id;
								}
								else
								{
												$event_types_id	=	$this->insert_event_types(array(
												'event_type'	=>	$event_type
																				));
								}
								return	$event_types_id;
				}

				function	get_digitized_formats()
				{
								$this->db->select("$this->table_instantiation_formats.format_name",	FALSE);

								$this->db->join($this->table_instantiation_formats,	"$this->table_instantiation_formats.instantiations_id = $this->table_instantiations.id");
								$this->db->where("$this->table_instantiations.digitized",	'1');
								$this->db->group_by("$this->table_instantiation_formats.instantiations_id");
								$result	=	$this->db->get($this->table_instantiations);

								return	$result->result();
				}

				function	get_scheduled_formats()
				{
								$this->db->select("$this->table_instantiation_formats.format_name",	FALSE);

								$this->db->join($this->table_instantiations,	"$this->table_instantiations.id = $this->table_instantiation_formats.instantiations_id");
								$this->db->join($this->table_nominations,	"$this->table_nominations.instantiations_id = $this->table_instantiation_formats.instantiations_id");
								$this->db->where("$this->table_instantiations.digitized",	'0');
								$this->db->or_where("$this->table_instantiations.digitized IS NULL");
								$this->db->group_by("$this->table_instantiation_formats.instantiations_id");
								$result	=	$this->db->get($this->table_instantiation_formats);

								return	$result->result();
				}

				function	get_material_goal()
				{
								$this->db->select("count(DISTINCT $this->table_instantiations.id) AS total",	FALSE);
								$this->db->join($this->table_nominations,	"$this->table_nominations.instantiations_id = $this->table_instantiations.id");
								$this->db->join($this->table_nomination_status,	"$this->table_nomination_status.id = $this->table_nominations.nomination_status_id");
								$this->db->where("$this->table_nomination_status.status",	'Nominated/1st Priority');
								$result	=	$this->db->get($this->table_instantiations);

								return	$result->row();
				}

				function	get_digitized_hours()
				{
								$this->db->select("count(DISTINCT $this->table_instantiations.id) AS total",	FALSE);
								$this->db->where("$this->table_instantiations.digitized",	'1');
								$result	=	$this->db->get($this->table_instantiations);

								return	$result->row();
				}

				function	export_limited_csv()
				{
								$this->db->select("identifiers.identifier as GUID",	FALSE);
								$this->db->select("GROUP_CONCAT(local.identifier SEPARATOR ' | ') AS unique_id",	FALSE);
								$this->db->select("GROUP_CONCAT($this->asset_titles.title SEPARATOR ' | ') as titles",	FALSE);
								$this->db->select("$this->table_instantiation_formats.format_type",	FALSE);
								$this->db->select("$this->table_instantiations.projected_duration",	FALSE);
								$this->db->select("$this->table_nomination_status.status",	FALSE);

								$this->db->join($this->_assets_table,	"$this->_assets_table.id = $this->table_instantiations.assets_id",	'left');
								$this->db->join("identifiers AS local",	"$this->_assets_table.id = local.assets_id AND local.identifier_source!='http://americanarchiveinventory.org'",	'left');
								$this->db->join("identifiers",	"$this->_assets_table.id = identifiers.assets_id AND identifiers.identifier_source='http://americanarchiveinventory.org'",	'left');

								$this->db->join($this->table_instantiation_formats,	"$this->table_instantiation_formats.instantiations_id = $this->table_instantiations.id");
								$this->db->join($this->table_nominations,	"$this->table_nominations.instantiations_id = $this->table_instantiations.id");
								$this->db->join($this->table_nomination_status,	"$this->table_nomination_status.id = $this->table_nominations.nomination_status_id");



//								$this->db->join($this->_table_asset_descriptions,	"$this->_table_asset_descriptions.assets_id = $this->_assets_table.id",	'left');

								$this->db->join($this->asset_titles,	"$this->asset_titles.assets_id	 = $this->table_instantiations.assets_id",	'left');
								$this->db->join($this->stations,	"$this->stations.id = $this->_assets_table.stations_id",	'left');
//								$this->db->join($this->table_instantiation_dates,	"$this->table_instantiation_dates.instantiations_id = $this->table_instantiations.id",	'left');
//								$this->db->join($this->table_date_types,	"$this->table_date_types.id = $this->table_instantiation_dates.date_types_id",	'left');
//								$this->db->join($this->table_instantiation_media_types,	"$this->table_instantiation_media_types.id = $this->table_instantiations.instantiation_media_type_id",	'left');
//								$this->db->join($this->table_instantiation_formats,	"$this->table_instantiation_formats.instantiations_id = $this->table_instantiations.id",	'left');
//								$this->db->join($this->table_instantiation_colors,	"$this->table_instantiation_colors.id = $this->table_instantiations.instantiation_colors_id",	'left');
//								$this->db->join($this->table_instantiation_generations,	"$this->table_instantiation_generations.instantiations_id = $this->table_instantiations.id",	'left');
//								$this->db->join($this->table_generations,	"$this->table_generations.id = $this->table_instantiation_generations.generations_id",	'left');
//								$this->db->join($this->table_nominations,	"$this->table_nominations.instantiations_id = $this->table_instantiations.id",	'left');
//								$this->db->join($this->table_nomination_status,	"$this->table_nomination_status.id = $this->table_nominations.nomination_status_id",	'left');
//								$this->db->join($this->table_events,	"$this->table_events.instantiations_id	 = $this->table_instantiations.id",	'left');
//								$this->db->join($this->table_event_types,	"$this->table_event_types.id	 = $this->table_events.event_types_id",	'left');
//								$this->db->limit(5);



								$session	=	$this->session->userdata;
								if(isset($session['organization'])	&&	$session['organization']	!=	'')
								{
												$station_name	=	explode('|||',	trim($session['organization']));
												$this->db->where_in('organization',	$station_name);
								}
								if(isset($session['nomination'])	&&	$session['nomination']	!=	'')
								{
												$nomination	=	explode('|||',	trim($session['nomination']));
												$this->db->where_in('status',	$nomination);
								}
								if(isset($session['media_type'])	&&	$session['media_type']	!=	'')
								{
												$media_type	=	explode('|||',	trim($session['media_type']));
												$this->db->where_in('media_type',	$media_type);
								}
								if(isset($session['physical_format'])	&&	$session['physical_format']	!=	'')
								{
												$physical_format	=	str_replace('|||',	'" | "',	trim($session['physical_format']));
												$where	.=" @format_name \"$physical_format\"";
								}
								if(isset($session['digital_format'])	&&	$session['digital_format']	!=	'')
								{
												$digital_format	=	str_replace('|||',	'" | "',	trim($session['digital_format']));
												$where	.=" @format_name \"$digital_format\"";
								}
								if(isset($session['generation'])	&&	$session['generation']	!=	'')
								{
												$generation	=	str_replace('|||',	'" | "',	trim($session['generation']));
												$where	.=" @generation \"$generation\"";
								}
								if(isset($session['digitized'])	&&	$session['digitized']	===	'1')
								{
												$where	.=' @digitized "1" @!actual_duration "0"';
								}
								if(isset($session['migration_failed'])	&&	$session['migration_failed']	===	'1')
								{
												$where	.=' @event_type "migration" @event_outcome "FAIL"';
								}

								if(isset($session['custom_search'])	&&	$session['custom_search']	!=	'')
								{
												$custom_search	=	str_replace('|||',	'"',	trim($session['custom_search']));
												$where	.=$custom_search;
								}
								if(isset($session['date_range'])	&&	$session['date_range']	!=	'')
								{
												$date_range	=	explode("to",	$session['date_range']);
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
								if(isset($session['date_type'])	&&	$session['date_type']	!=	'')
								{
												$date_type	=	str_replace('|||',	'" | "',	trim($session['date_type']));
												$where	.=" @date_type \"$date_type\"";
								}
								if($this->is_station_user)
								{
												$this->db->where_in('organization',	$this->station_name);
								}

								$this->db->group_by("$this->table_instantiations.id");
								$this->db->limit(5);
								$result	=	$this->db->get($this->table_instantiations);
								echo	$this->db->last_query();
								if(isset($result)	&&	!	empty($result))
								{
												debug($result->result());
												return	$result->result();
								}
								return	false;
				}

}

?>