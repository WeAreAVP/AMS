<?php

/**
 * Sphnix Model
 * 
 * PHP version 5
 * 
 * @category   AMS
 * @package    CI
 * @subpackage Model
 * @author     Nouman Tayyab <nouman@geekschicago.com>
 * @license    AVPS http://ams.avpreserve.com
 * @version    GIT: <$Id>
 * @link       http://ams.avpreserve.com
 */

/**
 * Sphnix Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Model
 * @author     Nouman Tayyab <nouman@geekschicago.com>
 * @license    AMS http://ams.avpreserve.com
 * @link       http://ams.avpreserve.com
 */
class Sphinx_Model extends CI_Model
{
	/*
	 *
	 * constructor. Load Sphinx Search Library
	 * 
	 */

	function __construct()
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
	public function search_stations($params, $offset = 0, $limit = 1000)
	{
		$stations_list = array();
		$total_record = 0;
		$this->sphinxsearch->reset_filters();
		$this->sphinxsearch->reset_group_by();
		//$where = $this->get_sphinx_search_condtion($params);
		$mode = SPH_MATCH_EXTENDED;
		$this->sphinxsearch->set_array_result(true);
		$this->sphinxsearch->set_match_mode($mode);
		$this->sphinxsearch->set_sort_mode(SPH_SORT_ATTR_ASC, 'station_name');
		$this->sphinxsearch->set_connect_timeout(120);
		if ($limit)
			$this->sphinxsearch->set_limits((int) $offset, (int) $limit, ( $limit > 1000 ) ? $limit : 1000 );
		if (isset($params['certified']) && $params['certified'] != '')
			$this->sphinxsearch->set_filter("is_certified", array($params['certified']));
		if (isset($params['agreed']) && $params['agreed'] != '')
			$this->sphinxsearch->set_filter("is_agreed", array($params['agreed']));
		if (isset($params['start_date']) && $params['start_date'] != '' && isset($params['end_date']) && $params['end_date'] != '')
			$this->sphinxsearch->set_filter_range("start_date", strtotime($params['start_date']), strtotime($params['end_date']));


		$res = $this->sphinxsearch->query($params['search_keywords'], 'stations');


		$execution_time = $res['time'];
		if ($res)
		{
			$total_record = $res['total_found'];
			if ($total_record > 0)
			{
				if (isset($res['matches']))
				{
					foreach ($res['matches'] as $record)
					{
						if ($this->is_station_user)
						{
							if ($this->station_id == $record['id'])
								$stations_list[] = (object) array_merge(array('id' => $record['id']), $record['attrs']);
						}
						else
						{
							$stations_list[] = (object) array_merge(array('id' => $record['id']), $record['attrs']);
						}
					}
				}
			}
		}

		return array("total_count" => $total_record, "records" => $stations_list, "query_time" => $execution_time);
	}

	public function facet_index($column_name, $index_name, $type = NULL, $offset = 0, $limit = 300)
	{
		$list = array();
		$total_record = 0;
		$query = '';
		$this->sphinxsearch->reset_filters();
		$this->sphinxsearch->reset_group_by();

		$mode = SPH_MATCH_EXTENDED;
		$this->sphinxsearch->set_array_result(true);
		$this->sphinxsearch->set_match_mode($mode);

		$this->sphinxsearch->set_group_by($column_name, SPH_GROUPBY_ATTR);

		$this->sphinxsearch->set_connect_timeout(120);
		if ($limit)
			$this->sphinxsearch->set_limits((int) $offset, (int) $limit, ( $limit > 1000 ) ? $limit : 1000 );

		$query = $this->make_where_clause($type);
		$res = $this->sphinxsearch->query($query, $index_name);



		$execution_time = $res['time'];

		if ($res)
		{
			$total_record = $res['total_found'];
			if ($total_record > 0)
			{
				if (isset($res['matches']))
				{
					foreach ($res['matches'] as $record)
					{


						$list[] = array_merge(array('id' => $record['id']), $record['attrs']);
					}
				}
			}
		}

		return array("total_count" => $total_record, "records" => $list, "query_time" => $execution_time);
	}

	/*
	 * Update Index Attribute Value
	 * @Perm Name of index
	 * @Perm Name of attribute
	 * @Perm Value of attribute
	 */

	public function update_indexes($index, $attr, $values)
	{
		$this->sphinxsearch->update_attributes($index, $attr, $values);
	}

	/*
	 * Get All Stations
	 */

	public function get_all_stations()
	{
		$res = $this->search_stations('', 0, 400);
		if ($res['total_count'] > 0)
		{
			return $res['records'];
		}
	}

	function standalone_report($offset = 0, $limit = 100)
	{
		$instantiations = array();
		$total_record = 0;
		$this->sphinxsearch->reset_filters();
		$this->sphinxsearch->reset_group_by();
		$mode = SPH_MATCH_EXTENDED;
		$this->sphinxsearch->set_array_result(true);
		$this->sphinxsearch->set_match_mode($mode);
		$this->sphinxsearch->set_connect_timeout(120);
		if ($limit)
			$this->sphinxsearch->set_limits((int) $offset, (int) $limit, ( $limit > 1000 ) ? $limit : 1000 );
		if (isset($this->session->userdata['standalone_column_order']))
		{
			if ($this->session->userdata['standalone_column_order'] == 'asc')
				$sort_mode = SPH_SORT_ATTR_ASC;
			else
				$sort_mode = SPH_SORT_ATTR_DESC;
			$this->sphinxsearch->set_sort_mode($sort_mode, $this->session->userdata['index_column']);
		}



		$query = $this->where_filter();

		$res = $this->sphinxsearch->query($query, 'instantiations_list');

		$execution_time = $res['time'];
		if ($res)
		{
			$total_record = $res['total_found'];
			if ($total_record > 0)
			{
				if (isset($res['matches']))
				{
					foreach ($res['matches'] as $record)
					{
						$instantiations[] = (object) array_merge(array('id' => $record['id']), $record['attrs']);
					}
				}
			}
		}

		return array("total_count" => $total_record, "records" => $instantiations, "query_time" => $execution_time);
	}

	function where_filter()
	{
		$where = '@digitized "1"';

		if (isset($this->session->userdata['stand_date_filter']) && $this->session->userdata['stand_date_filter'] != '')
		{
			$date_range = explode("to", str_replace('-', '/', $this->session->userdata['stand_date_filter']));

			if (isset($date_range[0]) && trim($date_range[0]) != '')
			{
				$start_date = strtotime(($date_range[0]));
			}
			if (isset($date_range[1]) && trim($date_range[1]) != '')
			{
				$end_date = strtotime(($date_range[1]));
			}
			else
			{
				$end_date = strtotime(($date_range[0]));
			}

			if ($start_date != '' && is_numeric($start_date) && isset($end_date) && is_numeric($end_date) && $end_date >= $start_date)
			{
				$where .=' @event_type "migration"';
				$this->sphinxsearch->set_filter_range("event_date", $start_date, $end_date);
			}
		}
		return $where;
	}

	function instantiations_list($params, $offset = 0, $limit = 100, $select = FALSE)
	{

		$instantiations = array();
		$total_record = 0;
		$this->sphinxsearch->reset_filters();
		$this->sphinxsearch->reset_group_by();
		//$where = $this->get_sphinx_search_condtion($params);
		if (isset($params['asset_id']))
		{
			$this->sphinxsearch->set_filter("assets_id", array($params['asset_id']));
		}

		$mode = SPH_MATCH_EXTENDED;
		$this->sphinxsearch->set_array_result(true);
		$this->sphinxsearch->set_match_mode($mode);
		$this->sphinxsearch->set_connect_timeout(120);
		if ($select)
			$this->sphinxsearch->set_select('id');
		if ($limit)
			$this->sphinxsearch->set_limits((int) $offset, (int) $limit, ( $limit > 1000 ) ? $limit : 1000 );

		if (isset($this->session->userdata['column']) && $this->session->userdata['column'] != '' && $this->session->userdata['column'] != 'flag')
		{
			if ($this->session->userdata['column_order'] == 'asc')
				$sort_mode = SPH_SORT_ATTR_ASC;
			else
				$sort_mode = SPH_SORT_ATTR_DESC;
			$this->sphinxsearch->set_sort_mode($sort_mode, $this->session->userdata['column']);
		}
		$query = $this->make_where_clause();



		$res = $this->sphinxsearch->query($query, 'instantiations_list');


		$execution_time = $res['time'];
		if ($res)
		{
			$total_record = $res['total_found'];
			if ($total_record > 0)
			{
				if (isset($res['matches']))
				{
					foreach ($res['matches'] as $record)
					{
						$instantiations[] = (object) array_merge(array('id' => $record['id']), $record['attrs']);
					}
				}
			}
		}

		return array("total_count" => $total_record, "records" => $instantiations, "query_time" => $execution_time);
	}

	function get_nomination_status_id($nomination_status)
	{
		$this->db->select('id');
		$this->db->where('status', $nomination_status);
		return $this->db->get('nomination_status')->row();
	}

	function get_nomination_status($nomination_id)
	{
		$this->db->select('status');
		$this->db->where('id', $nomination_id);
		return $this->db->get('nomination_status')->row();
	}

	function make_where_clause($type = NULL)
	{

		$where = '';
		if ($type == 'physical')
		{
			$where = " @s_format_type \"physical\"";
		}
		if ($type == 'digital')
		{
			$where = " @s_format_type \"digital\"";
		}

		if (isset($this->session->userdata['custom_search']) && $this->session->userdata['custom_search'] != '')
		{
			$keyword_json = $this->session->userdata['custom_search'];
			foreach ($keyword_json as $index => $key_columns)
			{
				$count = 0;
				foreach ($key_columns as $keys => $keywords)
				{
					$keyword = trim($keywords->value);
					if ($index == 'all')
					{
						$colums = array(
							'asset_title', 'guid_identifier', 'asset_subject', 'asset_coverage', 'asset_genre', 'asset_publisher_name', 'asset_description', 'asset_creator_name',
							'asset_creator_affiliation', 'asset_contributor_name', 'asset_contributor_affiliation', 'asset_rights', 'asset_annotation',
							'instantiation_identifier', 'instantiation_source', 'unit_of_measure',
							'standard', 'location', 'file_size', 'actual_duration', 'track_data_rate', 'tracks', 'channel_configuration', 'track_language', 'alternative_modes',
							'ins_annotation', 'ins_annotation_type', 'track_essence_track_type', 'track_encoding', 'track_standard', 'track_frame_rate',
							'track_playback_speed', 'track_sampling_rate', 'track_bit_depth', 'track_width', 'track_aspect_ratio',
						);
						if ($count == 0)
						{
							$where .=" \"$keyword\"";
						}
						else
						{
							$where .=" | \"$keyword\"";
						}
					}
					else
					{
						if ($count == 0)
							$where .="@$index \"$keyword\"";
						else
							$where .=" | \"$keyword\"";
					}
					$count ++;
				}
			}
		}
		if (isset($this->session->userdata['date_range']) && $this->session->userdata['date_range'] != '')
		{
			$keyword_json = $this->session->userdata['date_range'];
			foreach ($keyword_json as $index => $key_columns)
			{

				foreach ($key_columns as $keys => $keywords)
				{

					$date_range = explode("to", $keywords->value);
					if (isset($date_range[0]) && trim($date_range[0]) != '')
					{
						$start_date = strtotime(trim($date_range[0]));
					}
					if (isset($date_range[1]) && trim($date_range[1]) != '')
					{
						$end_date = strtotime(trim($date_range[1]));
					}
					else
					{
						$end_date = strtotime(trim($date_range[0]));
					}
					if ($start_date != '' && is_numeric($start_date) && isset($end_date) && is_numeric($end_date) && $end_date >= $start_date)
					{
						$this->sphinxsearch->set_filter_range("dates", $start_date, $end_date);
						if ($index != 'All')
						{
							$where .=" @date_type \"$index\"";
						}
					}
				}
			}
		}
		if (isset($this->session->userdata['organization']) && $this->session->userdata['organization'] != '')
		{
			$station_name = str_replace('|||', '" | "', trim($this->session->userdata['organization']));
			$where .=" @s_organization \"^$station_name$\"";
		}
		if (isset($this->session->userdata['states']) && $this->session->userdata['states'] != '')
		{
			$station_state = str_replace('|||', '" | "', trim($this->session->userdata['states']));
			$where .=" @s_state \"^$station_state$\"";
		}
		if (isset($this->session->userdata['nomination']) && $this->session->userdata['nomination'] != '')
		{
			$nomination = str_replace('|||', '" | "', trim($this->session->userdata['nomination']));
//			$nomination_id = $this->get_nomination_status_id($nomination);
//			$this->sphinxsearch->set_filter("nomination_status_id", array($nomination_id->id));
			$where .=" @s_status \"^$nomination$\"";
		}
		if (isset($this->session->userdata['media_type']) && $this->session->userdata['media_type'] != '')
		{
			$media_type = str_replace('|||', '" | "', trim($this->session->userdata['media_type']));
			$where .=" @s_media_type \"^$media_type$\"";
		}
		if (isset($this->session->userdata['physical_format']) && $this->session->userdata['physical_format'] != '')
		{

			$physical_format = str_replace('|||', '" | "', trim($this->session->userdata['physical_format']));
			$where .=" @s_format_name \"^$physical_format$\" @s_format_type \"physical\"";
		}
		if (isset($this->session->userdata['digital_format']) && $this->session->userdata['digital_format'] != '')
		{
			$digital_format = str_replace('|||', '" | "', trim($this->session->userdata['digital_format']));
			$where .=" @s_format_name \"^$digital_format$\" @s_format_type \"digital\"";
		}
		if (isset($this->session->userdata['generation']) && $this->session->userdata['generation'] != '')
		{
			$generation = str_replace('|||', '" | "', trim($this->session->userdata['generation']));
			$where .=" @s_generation \"^$generation$\"";
		}
		if ((isset($this->session->userdata['digitized']) && $this->session->userdata['digitized'] === '1') || $type == 'digitized')
		{
//			$where .=' @digitized "1" @!actual_duration "0"';
			$where .=' @digitized "1"';
		}
		if ((isset($this->session->userdata['migration_failed']) && $this->session->userdata['migration_failed'] === '1' ) || $type == 'migration')
		{
			$where .=' @event_type "migration" @event_outcome "FAIL"';
		}
		if ($this->is_station_user)
		{

			$where .=" @s_organization \"	^$this->station_name$\"";
		}

		return $where;
	}

	function assets_listing($params, $offset = 0, $limit = 100)
	{
		$instantiations = array();
		$total_record = 0;
		$this->sphinxsearch->reset_filters();
		$this->sphinxsearch->reset_group_by();
		$mode = SPH_MATCH_EXTENDED;
		$this->sphinxsearch->set_array_result(true);
		$this->sphinxsearch->set_match_mode($mode);
		$this->sphinxsearch->set_connect_timeout(120);
		if ($limit)
			$this->sphinxsearch->set_limits((int) $offset, (int) $limit, ( $limit > 1000 ) ? $limit : 1000 );

		if (isset($this->session->userdata['column']) && $this->session->userdata['column'] != '' && $this->session->userdata['column'] != 'flag')
		{
			if ($this->session->userdata['column_order'] == 'asc')
				$sort_mode = SPH_SORT_ATTR_ASC;
			else
				$sort_mode = SPH_SORT_ATTR_DESC;
			$this->sphinxsearch->set_sort_mode($sort_mode, $this->session->userdata['column']);
		}
		$query = $this->make_where_clause();

		$res = $this->sphinxsearch->query($query, $params['index']);


		$execution_time = $res['time'];
		if ($res)
		{
			$total_record = $res['total_found'];
			if ($total_record > 0)
			{
				if (isset($res['matches']))
				{
					foreach ($res['matches'] as $record)
					{
						$instantiations[] = (object) array_merge(array('id' => $record['id']), $record['attrs']);
					}
				}
			}
		}

		return array("total_count" => $total_record, "records" => $instantiations, "query_time" => $execution_time);
	}

}

?>
