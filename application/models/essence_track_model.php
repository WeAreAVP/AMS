<?php

/**
	* Essence Track Model.
	*
	* @package    AMS
	* @subpackage Essence_Track_Model
	* @author     ALi RAza
	*/
class	Essence_Track_Model	extends	CI_Model
{

				/**
					* constructor. set table name amd prefix
					* 
					*/
				function	__construct()
				{
								parent::__construct();
								$this->_prefix	=	'';
								$this->_table_essence_tracks	=	'essence_tracks';
								$this->_table_essence_track_types	=	'essence_track_types';
								$this->_table_essence_track_identifiers	=	'essence_track_identifiers';
								$this->_table_essence_track_encodings	=	'essence_track_encodings';
								$this->_table_essence_track_annotations	=	'essence_track_annotations';
								$this->_table_essence_track_frame_sizes	=	'essence_track_frame_sizes';
				}

				/**
					* search essence_track_frame_sizes by @width and height
					* 
					* @param type $width
					* @param type $height
					* @return object 
					*/
				function	get_essence_track_frame_sizes_by_width_height($width,	$height)
				{
								$this->db->where('width',	$width);
								$this->db->where('height',	$height);
								$res	=	$this->db->get($this->_table_essence_track_frame_sizes);
								if(isset($res)	&&	!	empty($res))
								{
												return	$res->row();
								}
								return	false;
				}

				/**
					* search generations by @generation
					* 
					* @param type $status
					* @return object 
					*/
				function	get_essence_track_by_type($essence_track_type)
				{
								$this->db->where('essence_track_type LIKE',	$essence_track_type);
								$res	=	$this->db->get($this->_table_essence_track_types);
								if(isset($res)	&&	!	empty($res))
								{
												return	$res->row();
								}
								return	false;
				}

				/*
					*
					*  Insert the record in essence_track_frame_sizes table
					*  @param array $data
					*  @return integer last_inserted id
					* 
					*/

				function	insert_essence_track_frame_sizes($data)
				{
								$this->db->insert($this->_table_essence_track_frame_sizes,	$data);
								return	$this->db->insert_id();
				}

				/*
					*
					*  Insert the record in essence_track_types table
					*  @param array $data
					*  @return integer last_inserted id
					* 
					*/

				function	insert_essence_track_types($data)
				{
								$this->db->insert($this->_table_essence_track_types,	$data);
								return	$this->db->insert_id();
				}

				/*
					*
					*  Insert the record in essence_tracks table
					*  @param array $data
					*  @return integer last_inserted id
					* 
					*/

				function	insert_essence_tracks($data)
				{
								$this->db->insert($this->_table_essence_tracks,	$data);
								return	$this->db->insert_id();
				}

				/*
					*
					*  Insert the record in essence_track_identifiers table
					*  @param array $data
					*  @return integer last_inserted id
					* 
					*/

				function	insert_essence_track_identifiers($data)
				{
								$this->db->insert($this->_table_essence_track_identifiers,	$data);
								return	$this->db->insert_id();
				}

				/*
					*
					*  Insert the record in essence_track_encodings table
					*  @param array $data
					*  @return integer last_inserted id
					* 
					*/

				function	insert_essence_track_encodings($data)
				{
								$this->db->insert($this->_table_essence_track_encodings,	$data);
								return	$this->db->insert_id();
				}

				/*
					*
					*  Insert the record in essence_track_annotations table
					*  @param array $data
					*  @return integer last_inserted id
					* 
					*/

				function	insert_essence_track_annotations($data)
				{
								$this->db->insert($this->_table_essence_track_annotations,	$data);
								return	$this->db->insert_id();
				}

				function	update_essence_track($essence_id,	$data)
				{
								$this->db->where('id',	$essence_id);
								return	$this->db->update($this->_table_essence_tracks,	$data);
				}

}

?>