<?php

/**
 * Manage_Asset_Model Model
 * 
 * PHP version 5
 * 
 * @category   AMS
 * @package    CI
 * @subpackage Model
 * @author     Ali Raza <ali@geekschicago.com>
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @license    AVPS http://ams.avpreserve.com
 * @version    GIT: <$Id>
 * @link       http://ams.avpreserve.com
 */

/**
 * Manage_Asset_Model Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Model
 * @author     Ali Raza <ali@geekschicago.com.com>
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @version    GIT: <$Id>
 * @link       http://ams.avpreserve.com
 */
class Manage_Asset_Model extends CI_Model
{

	/**
	 * constructor. set table name amd prefix
	 * 
	 */
	function __construct()
	{
		parent::__construct();
	}

	function get_asset_detail_by_id($asset_id)
	{
		$this->db->select('assets.id,assets.stations_id,stations.station_name');
		$this->db->select('asset_types.asset_type');
		$this->db->join('stations', 'stations.id=assets.stations_id');
		$this->db->join('assets_asset_types', 'assets_asset_types.assets_id=assets.id', 'LEFT');
		$this->db->join('asset_types', 'asset_types.id=assets_asset_types.asset_types_id', 'LEFT');
		$this->db->where('assets.id', $asset_id);
		$result = $this->db->get('assets');
		if (isset($result) && ! empty($result))
		{
			return $result->row();
		}
		return FALSE;
	}

	function get_asset_types($element_id)
	{
		$this->db->where('element_type_id', $element_id);
		$result = $this->db->get('pbcore_picklist_value_by_type');
		return $result->result();
	}

	function insert_picklist_value($data)
	{
		$this->db->insert('pbcore_picklist_value_by_type', $data);
		return $this->db->insert_id();
	}

}

?>