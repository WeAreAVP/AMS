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
				}

				function	get_assets_nomination_count()
				{
								$this->db->select("COUNT($this->_assets_table.id) as total,$this->_nomination_table.status",	FALSE);
								$this->db->join($this->_table,	"$this->_table.nomination_status_id = $this->_nomination_table.id");
								$this->db->join($this->_instantiations_table,	"$this->_instantiations_table.id = $this->_table.instantiations_id");
								$this->db->join($this->_assets_table,	"$this->_assets_table.id = $this->_instantiations_table.assets_id");
								$this->db->group_by("$this->_nomination_table.id");
								$query	=	$this->db->get($this->_nomination_table);
								echo $this->db->last_query();exit;
								return	$query->result();
				}

				function	get_instantiation_nomination_count()
				{
								$this->db->select("COUNT($this->_table.id) as total,$this->_nomination_table.status",	FALSE);
								$this->db->join($this->_table,	"$this->_table.nomination_status_id = $this->_nomination_table.id");
								$this->db->join($this->_instantiations_table,	"$this->_instantiations_table.id = $this->_table.instantiations_id");
								$this->db->group_by("$this->_nomination_table.id");
								$query	=	$this->db->get($this->_nomination_table);
								return	$query->result();
				}

}

?>