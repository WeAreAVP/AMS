<?php

/**
	* Nomination Model.
	*
	* @package    AMS
	* @subpackage Nomination
	* @author     Nouman Tayyab
	*/
class	Nomination_Model	extends	CI_Model
{

				/**
					* constructor. set table name amd prefix
					* 
					*/
				function	__construct()
				{
								parent::__construct();
								$this->_prefix	=	'';
								$this->_table	=	'nominations';
								$this->_nomination_table	=	'nomination_status';
								$this->_instantiations_table	=	'instantiations';
								$this->_assets_table	=	'assets';
								$this->_stations_table	=	'stations';
								$this->_media_type_table	=	'instantiation_media_types';
								$this->formats_table	=	'instantiation_formats';
								$this->instantiation_generations_table	=	'instantiation_generations';
								$this->generations_table	=	'generations';
				}

				function	get_assets_nomination()
				{
								$this->db->select("COUNT(DISTINCT $this->_assets_table.id) as total,$this->_nomination_table.status",	FALSE);
								$this->db->join($this->_instantiations_table,	"$this->_instantiations_table.assets_id = $this->_assets_table.id");
								$this->db->join($this->_table,	"$this->_table.instantiations_id = $this->_instantiations_table.id");
								$this->db->join($this->_nomination_table,	"$this->_nomination_table.id = $this->_table.nomination_status_id");
								$this->db->group_by("$this->_nomination_table.id");
								$query	=	$this->db->get($this->_assets_table);
								return	$query->result();
				}

				function	get_instantiation_nomination()
				{
								$this->db->select("COUNT($this->_table.id) as total,$this->_nomination_table.status",	FALSE);
								$this->db->join($this->_table,	"$this->_table.nomination_status_id = $this->_nomination_table.id");
								$this->db->join($this->_instantiations_table,	"$this->_instantiations_table.id = $this->_table.instantiations_id");
								$this->db->group_by("$this->_nomination_table.id");
								$query	=	$this->db->get($this->_nomination_table);
								return	$query->result();
				}

				function	get_asset_media_types()
				{
								$this->db->select("COUNT(DISTINCT $this->_assets_table.id) as total,$this->_media_type_table.media_type",	FALSE);
								$this->db->join($this->_instantiations_table,	"$this->_instantiations_table.assets_id = $this->_assets_table.id");
								$this->db->join($this->_media_type_table,	"$this->_media_type_table.id = $this->_instantiations_table.instantiation_media_type_id");
								$this->db->group_by("$this->_media_type_table.id");
								return	$query	=	$this->db->get($this->_assets_table)->result();
				}

				function	get_instantiation_media_types()
				{
								$this->db->select("COUNT(DISTINCT $this->_instantiations_table.id) as total,$this->_media_type_table.media_type",	FALSE);
								$this->db->join($this->_media_type_table,	"$this->_media_type_table.id = $this->_instantiations_table.instantiation_media_type_id");
								$this->db->group_by("$this->_media_type_table.id");
								return	$query	=	$this->db->get($this->_instantiations_table)->result();
				}

				function	get_asset_physical_formats()
				{
								$this->db->select("COUNT(DISTINCT $this->_assets_table.id) as total,$this->formats_table.format_name",	FALSE);
								$this->db->join($this->_instantiations_table,	"$this->_instantiations_table.assets_id = $this->_assets_table.id");
								$this->db->join($this->formats_table,	"$this->formats_table.instantiations_id = $this->_instantiations_table.id");
								$this->db->where("$this->formats_table.format_type",	'physical');
								$this->db->group_by("$this->formats_table.format_name");
								return	$query	=	$this->db->get($this->_assets_table)->result();
				}

				function	get_instantiation_physical_formats()
				{
								$this->db->select("COUNT(DISTINCT $this->_instantiations_table.id) as total,$this->formats_table.format_name",	FALSE);
								$this->db->join($this->formats_table,	"$this->formats_table.instantiations_id = $this->_instantiations_table.id");
								$this->db->where("$this->formats_table.format_type",	'physical');
								$this->db->group_by("$this->formats_table.format_name");
								return	$query	=	$this->db->get($this->_instantiations_table)->result();
				}

				function	get_asset_digital_formats()
				{
								$this->db->select("COUNT(DISTINCT $this->_assets_table.id) as total,$this->formats_table.format_name",	FALSE);
								$this->db->join($this->_instantiations_table,	"$this->_instantiations_table.assets_id = $this->_assets_table.id");
								$this->db->join($this->formats_table,	"$this->formats_table.instantiations_id = $this->_instantiations_table.id");
								$this->db->where("$this->formats_table.format_type",	'digital');
								$this->db->group_by("$this->formats_table.format_name");
								return	$query	=	$this->db->get($this->_assets_table)->result();
				}

				function	get_instantiation_digital_formats()
				{
								$this->db->select("COUNT(DISTINCT $this->_instantiations_table.id) as total,$this->formats_table.format_name",	FALSE);
								$this->db->join($this->formats_table,	"$this->formats_table.instantiations_id = $this->_instantiations_table.id");
								$this->db->where("$this->formats_table.format_type",	'digital');
								$this->db->group_by("$this->formats_table.format_name");
								return	$query	=	$this->db->get($this->_instantiations_table)->result();
				}

				function	get_asset_generations()
				{
								$this->db->select("COUNT(DISTINCT $this->_assets_table.id) as total,$this->generations_table.generation",	FALSE);
								$this->db->join($this->_instantiations_table,	"$this->_instantiations_table.assets_id = $this->_assets_table.id");
								$this->db->join($this->instantiation_generations_table,	"$this->instantiation_generations_table.instantiations_id = $this->_instantiations_table.id");
								$this->db->join($this->generations_table,	"$this->generations_table.id = $this->instantiation_generations_table.generations_id");
								$this->db->group_by("$this->generations_table.generation");
								return	$query	=	$this->db->get($this->_assets_table)->result();
				}

				function	get_instantitation_generations()
				{
								$this->db->select("COUNT(DISTINCT $this->_instantiations_table.id) as total,$this->generations_table.generation",	FALSE);
								$this->db->join($this->instantiation_generations_table,	"$this->instantiation_generations_table.instantiations_id = $this->_instantiations_table.id");
								$this->db->join($this->generations_table,	"$this->generations_table.id = $this->instantiation_generations_table.generations_id");
								$this->db->group_by("$this->generations_table.generation");
								return	$query	=	$this->db->get($this->_instantiations_table)->result();
				}

}

?>