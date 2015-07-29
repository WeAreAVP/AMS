<?php

/**
 * AMS Archive Management System
 * 
 * 
 * PHP version 5
 * 
 * @category   AMS
 * @package    CI
 * @subpackage Library
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @copyright  Copyright (c) WGBH (http://www.wgbh.org/). All Rights Reserved.
 * @license    http://www.gnu.org/licenses/gpl.txt GPLv3
 * @version    GIT: <$Id>
 * @link       https://github.com/avpreserve/AMS
 */

/**
 * Import_mediainfo Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Library
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @copyright  Copyright (c) WGBH (http://www.wgbh.org/). All Rights Reserved.
 * @license    http://www.gnu.org/licenses/gpl.txt GPLv3
 * @link       https://ams.americanarchive.org
 */
class Import_mediainfo
{

	/**
	 * CI instance.
	 * 
	 * @var object 
	 */
	private $_CI;
	/*
	 * 
	 * @var object
	 */
	private $_model;

	/**
	 * Instantiation ID.
	 * 
	 * @var integer 
	 */
	private $instantiation_info = array();

	/**
	 *
	 * @var integer 
	 */
	private $_instantiation_id = NULL;

	/**
	 * Constructor.
	 * 
	 */
	function __construct()
	{
		$this->_CI = & get_instance();
		$this->_CI->load->model('pbcore_model');
		$this->_CI->load->library('import_essencetrack_mediainfo');
		$this->_model = $this->_CI->pbcore_model;
		enable_errors();
	}
	/**
	 * 
	 * @param string $file_path
	 */
	function initialize($file_path)
	{

		$data = convert_file_to_xml($file_path);
		$tracks_data = $data['children']['file'][0]['children']['track'];
		if (isset($tracks_data) && count($tracks_data) > 0)
		{
			$instantiation = array();
			$this->_instantiation_id = $this->_model->insert_record($this->_model->table_instantiations, array('digitized' => 0,'mediainfo_import' => 1, 'location' => 'N/A', 'created' => date('Y-m-d H:i:s')));
			foreach ($tracks_data as $index => $track)
			{
				if (isset($track['attributes']['type']) && $track['attributes']['type'] === 'General')
				{
					$general_track = $track['children'];
					$this->get_general_track($general_track);
				}
				else
				{
					$this->_CI->import_essencetrack_mediainfo->initialize($this->_instantiation_id, $track);
				}
			}
			$this->insert_ins_asset_index($this->_instantiation_id);
			$this->instantiation_info = array();
			$this->_instantiation_id = NULL;
		}
	}
	/**
	 * 
	 * @param array $general_track
	 */
	function get_general_track($general_track)
	{
		$this->set_media_type($general_track);
		/* Actual Duration Start */
		if (isset($general_track['duration_string3']) && isset($general_track['duration_string3'][0]))
			if ( ! empty($general_track['duration_string3'][0]['text']))
				$this->instantiation_info['actual_duration'] = date('H:i:s', strtotime($general_track['duration_string3'][0]['text']));
		/* Actual Duration End */
		/* Standard Start */
		if (isset($general_track['format_profile']) && isset($general_track['format_profile'][0]))
		{
			if ( ! empty($general_track['format_profile'][0]['text']) || $general_track['format_profile'][0]['text'] != NULL)
				$this->instantiation_info['standard'] = $general_track['format_profile'][0]['text'];
			else if (isset($general_track['format']) && isset($general_track['format'][0]))
				if ( ! empty($general_track['format'][0]['text']) || $general_track['format'][0]['text'] != NULL)
					$this->instantiation_info['standard'] = $general_track['format'][0]['text'];
		}
		else if (isset($general_track['format']) && isset($general_track['format'][0]))
			if ( ! empty($general_track['format'][0]['text']) || $general_track['format'][0]['text'] != NULL)
				$this->instantiation_info['standard'] = $general_track['format'][0]['text'];

		/* Standard End */
		$this->save_instantiation_tracks($general_track);
		myLog('Tracks are updated');
		$this->save_filesize_and_daterate($general_track);
		myLog('Fize size and data rate imported');
		$this->update_parent_instantiaion($general_track);
		myLog('Parent Instantiation updated');
		$this->save_instantiation_date($general_track);
		myLog('Dates imported');
		/* Instantiation Format Start */
		if (isset($general_track['internetmediatype']) && isset($general_track['internetmediatype'][0]))
		{
			$format['format_name'] = $general_track['internetmediatype'][0]['text'];
			$format['format_type'] = 'digital';
			$format['instantiations_id'] = $this->_instantiation_id;
			$this->_model->insert_record($this->_model->table_instantiation_formats, $format);
		}
		/* Instantiation Format End */
		$this->save_annotations($general_track);
	}
	/**
	 * 
	 * @param array $general_track
	 */
	function save_annotations($general_track)
	{
		if (isset($general_track['encoded_library_string']) && isset($general_track['encoded_library_string'][0]))
		{
			if ( ! empty($general_track['encoded_library_string'][0]['text']))
			{
				$annotation['annotation'] = $general_track['encoded_library_string'][0]['text'];
				$annotation['annotation_type'] = 'encoded by';
				$annotation['instantiations_id'] = $this->_instantiation_id;
				$this->_model->insert_record($this->_model->table_instantiation_annotations, $annotation);
			}
		}
		else if (isset($general_track['encodedby']) && isset($general_track['encodedby'][0]))
		{
			if ( ! empty($general_track['encodedby'][0]['text']))
			{
				$annotation['annotation'] = $general_track['encodedby'][0]['text'];
				$annotation['annotation_type'] = 'encoded by';
				$annotation['instantiations_id'] = $this->_instantiation_id;
				$this->_model->insert_record($this->_model->table_instantiation_annotations, $annotation);
			}
		}
		/* Instantiation Annotation End */
	}
	/**
	 * 
	 * @param array $general_track
	 */
	function save_instantiation_date($general_track)
	{

		/* Instantiation Date Start */
		if (isset($general_track['encoded_date']) && isset($general_track['encoded_date'][0]))
		{

			if ( ! empty($general_track['encoded_date'][0]['text']) || $general_track['encoded_date'][0]['text'] != NULL)
				$date['instantiation_date'] = date('Y-m-d', strtotime($general_track['encoded_date'][0]['text']));
			else if (isset($general_track['file_modified_date']) && isset($general_track['file_modified_date'][0]))
				$date['instantiation_date'] = date('Y-m-d', strtotime($general_track['file_modified_date'][0]['text']));
		}
		else if (isset($general_track['file_modified_date']) && isset($general_track['file_modified_date'][0]))
			$date['instantiation_date'] = date('Y-m-d', strtotime($general_track['file_modified_date'][0]['text']));

		if (isset($date['instantiation_date']) && $date['instantiation_date'] != '')
		{
			$date_type = $this->_model->get_one_by($this->_model->table_date_types, array('date_type' => 'encoded'), TRUE);
			if (isset($date_type) && isset($date_type->id))
				$date['date_types_id'] = $date_type->id;
			else
				$date['date_types_id'] = $this->_model->insert_record($this->_model->table_date_types, array('date_type' => 'encoded'));

			$date['instantiations_id'] = $this->_instantiation_id;
			$this->_model->insert_record($this->_model->table_instantiation_dates, $date);
		}
		/* Instantiation Date End */
	}
	/**
	 * 
	 * @param string $guid
	 * @return boolean
	 */
	function get_asset_id_for_media_import($guid)
	{
		$asset_guid = explode('.', $guid);
		if (count($asset_guid) > 0)
		{
			$asset_guid = $asset_guid[0];
			$make_db_name = explode('cpb-aacip-', $asset_guid);
			if (count($make_db_name) > 1)
			{
				$make_db_name = explode('-', $make_db_name[1]);
				$guid_db = trim('cpb-aacip/' . $make_db_name[0] . '-' . $make_db_name[1]);
				$asset_id = $this->_model->get_one_by($this->_model->table_identifers, array('identifier' => $guid_db, 'identifier_source' => 'http://americanarchiveinventory.org'), TRUE);
				if ($asset_id && ! empty($asset_id))
				{
					myLog('Asset ID => ' . $asset_id->assets_id);
					return $asset_id->assets_id;
				}
			}
			return FALSE;
		}
		return FALSE;
	}
	/**
	 * Update the parent instantiation.
	 * 
	 * @param array $general_track
	 */
	function update_parent_instantiaion($general_track)
	{
		if (isset($general_track['filename']) && isset($general_track['filename'][0]))
		{
			if (isset($general_track['fileextension']) && isset($general_track['fileextension'][0]))
			{
				$identifier['instantiation_identifier'] = $general_track['filename'][0]['text'];
				$db_asset_id = $this->get_asset_id_for_media_import($identifier['instantiation_identifier']);
				$parent_instantiations = $this->_model->get_by($this->_model->table_instantiations, array('assets_id' => $db_asset_id));
				if (count($parent_instantiations) == 1)
				{
					$this->_model->update_instantiations($parent_instantiations[0]->id, array('digitized' => 1));
					$this->update_ins_asset_index($parent_instantiations[0]->id);
				}
				else
				{
					$parent_instantiations = $this->_model->get_instantiation_with_event_by_asset_id($db_asset_id);
					if (count($parent_instantiations) > 0)
					{
						$this->_model->update_instantiations($parent_instantiations->id, array('digitized' => 1));
						$this->update_ins_asset_index($parent_instantiations->id);
					}
				}
				if ($db_asset_id)
				{
					$this->instantiation_info['assets_id'] = $db_asset_id;
					$this->_model->update_instantiations($this->_instantiation_id, $this->instantiation_info);
					myLog('Instantiation updated ID => ' . $this->_instantiation_id);
				}

				// Save Identifier of Instantiation Start
				$identifier['instantiation_identifier'] = $general_track['filename'][0]['text'] . '.' . $general_track['fileextension'][0]['text'];
				$identifier['instantiation_source'] = 'mediainfo';
				$identifier['instantiations_id'] = $this->_instantiation_id;
				$this->_model->insert_record($this->_model->table_instantiation_identifier, $identifier);

				// Save Identifier of Instantiation End
				$filename = $identifier['instantiation_identifier'];
				$generation = '';
				if (strstr($filename, '.j2k.mxf') || strstr($filename, '.wav'))
					$generation = 'Preservation Master';
				else if (strstr($filename, '.mpeg2.mxf'))
					$generation = 'Mezzanine';
				else if (strstr($filename, '.h264.mov') || strstr($filename, '.mp3'))
					$generation = 'Proxy';
				if ($generation !== '')
				{
					$generations_d = $this->_model->get_one_by($this->_model->table_generations, array('generation' => $generation), TRUE);
					if (isset($generations_d) && isset($generations_d->id))
						$generations['generations_id'] = $generations_d->id;
					else
						$generations['generations_id'] = $this->_model->insert_record($this->_model->table_generations, array("generation" => $generation));

					$generations['instantiations_id'] = $this->_instantiation_id;
					$this->_model->insert_record($this->_model->table_instantiation_generations, $generations);
				}
			}
		}
	}
	/**
	 * 
	 * @param array $general_track
	 */
	function save_filesize_and_daterate($general_track)
	{
		/* Data Rate Start */
		if (isset($general_track['overallbitrate_string']) && isset($general_track['overallbitrate_string'][0]))
		{
			if ( ! empty($general_track['overallbitrate_string'][0]['text']))
			{
				$datarate = explode(' ', $general_track['overallbitrate_string'][0]['text']);
				$this->instantiation_info['data_rate'] = (isset($datarate[0])) ? $datarate[0] : '';
				$data_rate_unit = (isset($datarate[1])) ? $datarate[1] : '';
				if ($data_rate_unit != '')
				{
					$datarate_unit = $this->_model->get_one_by($this->_model->table_data_rate_units, array('unit_of_measure' => $data_rate_unit), TRUE);
					if ( ! is_empty($datarate_unit))
						$this->instantiation_info['data_rate_units_id'] = $datarate_unit->id;
					else
						$this->instantiation_info['data_rate_units_id'] = $this->_model->insert_record($this->_model->table_data_rate_units, array('unit_of_measure' => $data_rate_unit));
				}
			}
		}
		/* Data Rate End */
		/* File Size Start */
		if (isset($general_track['filesize_string4']) && isset($general_track['filesize_string4'][0]))
		{
			if ( ! empty($general_track['filesize_string4'][0]['text']))
			{
				$filesize = explode(' ', $general_track['filesize_string4'][0]['text']);
				$this->instantiation_info['file_size'] = (isset($filesize[0])) ? $filesize[0] : '';
				$this->instantiation_info['file_size_unit_of_measure'] = (isset($filesize[1])) ? $filesize[1] : '';
			}
		}
		/* File Size End */
	}
	/**
	 * 
	 * @param array $general_track
	 */
	function save_instantiation_tracks($general_track)
	{

		$this->instantiation_info['tracks'] = '';
		if (isset($general_track['videocount']) && isset($general_track['videocount'][0]))
		{
			if ( ! empty($general_track['videocount'][0]['text']))
			{
				$add_comma = '';
				if ($this->instantiation_info['tracks'] !== '')
					$add_comma = ', ';

				$this->instantiation_info['tracks'].=$add_comma . $general_track['videocount'][0]['text'] . ' video';
			}
		}
		if (isset($general_track['audiocount']) && isset($general_track['audiocount'][0]))
		{
			if ( ! empty($general_track['audiocount'][0]['text']))
			{
				$add_comma = '';
				if ($this->instantiation_info['tracks'] !== '')
					$add_comma = ', ';
				$this->instantiation_info['tracks'].=$add_comma . $general_track['audiocount'][0]['text'] . ' audio';
			}
		}
		if (isset($general_track['menucount']) && isset($general_track['menucount'][0]))
		{
			if ( ! empty($general_track['menucount'][0]['text']))
			{
				$add_comma = '';
				if ($this->instantiation_info['tracks'] !== '')
					$add_comma = ', ';
				$this->instantiation_info['tracks'].=$add_comma . $general_track['menucount'][0]['text'] . ' menu';
			}
		}
		if (isset($general_track['textcount']) && isset($general_track['textcount'][0]))
		{
			if ( ! empty($general_track['textcount'][0]['text']))
			{
				$add_comma = '';
				if ($this->instantiation_info['tracks'] !== '')
					$add_comma = ', ';
				$this->instantiation_info['tracks'].=$add_comma . $general_track['textcount'][0]['text'] . ' text';
			}
		}
	}
	/**
	 * 
	 * @param array $general_track
	 */
	function set_media_type($general_track)
	{
		$media_type = '';
		if (isset($general_track['videocount']) && isset($general_track['videocount'][0]))
		{
			if ( ! empty($general_track['videocount'][0]['text']) || $general_track['videocount'][0]['text'] != NULL || $general_track['videocount'][0]['text'] > 0)
				$media_type = 'Moving Image';

			else if ($general_track['videocount'][0]['text'] == 0)
			{
				if (isset($general_track['audiocount']) && isset($general_track['audiocount'][0]))
					if ( ! empty($general_track['audiocount'][0]['text']) || $general_track['audiocount'][0]['text'] != NULL)
						$media_type = 'Sound';
			}
		}
		else if (isset($general_track['audiocount']) && isset($general_track['audiocount'][0]))
			if ( ! empty($general_track['audiocount'][0]['text']) || $general_track['audiocount'][0]['text'] != NULL)
				$media_type = 'Sound';

		if ($media_type != '')
		{
			$inst_media_type = $this->_model->get_one_by($this->_model->table_instantiation_media_types, array('media_type' => $media_type), TRUE);
			if ( ! is_empty($inst_media_type))
				$this->instantiation_info['instantiation_media_type_id'] = $inst_media_type->id;
			else
				$this->instantiation_info['instantiation_media_type_id'] = $this->_model->insert_record($this->_model->table_instantiation_media_types, array('media_type' => $media_type));
			myLog('Inserted Media ID => ' . $this->instantiation_info['instantiation_media_type_id']);
		}
	}
	/**
	 * 
	 * @param array $db_instantiation_id
	 */
	function insert_ins_asset_index($db_instantiation_id)
	{
		$this->_CI->load->library('sphnixrt');
		$this->_CI->load->model('searchd_model');
		$this->_CI->load->helper('sphnixdata');
		$instantiation_list = $this->_CI->searchd_model->get_ins_index(array($db_instantiation_id));
		if (count($instantiation_list) > 0 && isset($instantiation_list[0]))
		{
			$new_list_info = make_instantiation_sphnix_array($instantiation_list[0]);
			myLog('Instantiation Inserted');
			$this->_CI->sphnixrt->insert('instantiations_list', $new_list_info, $db_instantiation_id);
			$asset_list = $this->_CI->searchd_model->get_asset_index(array($instantiation_list[0]->assets_id));
			$new_asset_info = make_assets_sphnix_array($asset_list[0], FALSE);
			$this->_CI->sphnixrt->update('assets_list', $new_asset_info);
		}
		else
		{
			myLog('Issue Found ' . $db_instantiation_id);
		}
	}
	/**
	 * 
	 * @param array $db_instantiation_id
	 */
	function update_ins_asset_index($db_instantiation_id)
	{
		$this->_CI->load->library('sphnixrt');
		$this->_CI->load->model('searchd_model');
		$this->_CI->load->helper('sphnixdata');
		$instantiation_list = $this->_CI->searchd_model->get_ins_index(array($db_instantiation_id));
		$new_list_info = make_instantiation_sphnix_array($instantiation_list[0], FALSE);
		myLog('Instantiation Updated');
		$this->_CI->sphnixrt->update('instantiations_list', $new_list_info);

		$asset_list = $this->_CI->searchd_model->get_asset_index(array($instantiation_list[0]->assets_id));
		$new_asset_info = make_assets_sphnix_array($asset_list[0], FALSE);
		$this->_CI->sphnixrt->update('assets_list', $new_asset_info);
	}

	/* End of Import_mediainfo Class */
}

// END Import_mediainfo Controller
// End of file import_mediainfo.php 
/* Location: ./application/libraries/import_mediainfo.php */
