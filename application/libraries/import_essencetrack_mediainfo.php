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
 * Import_essencetrack_mediainfo Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Library
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @copyright  Copyright (c) WGBH (http://www.wgbh.org/). All Rights Reserved.
 * @license    http://www.gnu.org/licenses/gpl.txt GPLv3
 * @link       https://ams.americanarchive.org
 */
class Import_essencetrack_mediainfo
{

	/**
	 * CI instance.
	 * 
	 * @var object 
	 */
	private $_CI;
	private $_model;

	/**
	 * Instantiation ID.
	 * 
	 * @var integer 
	 */
	private $_instantiation_id = NULL;

	/**
	 *
	 * @var integer 
	 */
	private $_essence_track_id = NULL;

	/**
	 *
	 * @var type 
	 */
	private $essence_track = array();

	/**
	 * Constructor.
	 * 
	 */
	function __construct()
	{
		$this->_CI = & get_instance();
		$this->_CI->load->model('pbcore_model');
		$this->_model = $this->_CI->pbcore_model;
		enable_errors();
	}

	function initialize($instantiation_id, $track)
	{
		$this->_instantiation_id = $instantiation_id;
		if (isset($track['attributes']['type']) && $track['attributes']['type'] === 'Audio')
		{
			$audio_track = $track['children'];
			$this->fetch_audio_info($audio_track);
		}
		$this->save_track_type($track);
		$this->save_date_rate($track);
		/* Essence Track Standard Start */
		if (isset($track['children']['standard']) && isset($track['children']['standard'][0]) && isset($track['children']['standard'][0]['text']) && ! empty($track['children']['standard'][0]['text']))
			$this->essence_track['standard'] = $track['children']['standard'][0]['text'];

		/* Essence Track Standard End */
		/* Essence Track Bitdepth Start */
		if (isset($track['children']['bitdepth']) && isset($track['children']['bitdepth'][0]) && isset($track['children']['bitdepth'][0]['text']) && ! empty($track['children']['bitdepth'][0]['text']))
			$this->essence_track['bit_depth'] = $track['children']['bitdepth'][0]['text'] . ' bits';

		/* Essence Track Bitdepth End */
		/* Essence Track Duration Start */
		if (isset($track['children']['duration_string3']) && isset($track['children']['duration_string3'][0]) && isset($track['children']['duration_string3'][0]['text']) && ! empty($track['children']['duration_string3'][0]['text']))
			$this->essence_track['duration'] = date('H:i:s', strtotime($track['children']['duration_string3'][0]['text']));

		/* Essence Track Duration End */
		/* Essence Track Language Start */
		if (isset($track['children']['language_string3']) && isset($track['children']['language_string3'][0]) && isset($track['children']['language_string3'][0]['text']) && ! empty($track['children']['language_string3'][0]['text']))
			$this->essence_track['language'] = $track['children']['language_string3'][0]['text'];

		/* Essence Track Language End */
		/* Insert Essence Track Start */
		$this->essence_track['instantiations_id'] = $this->_instantiation_id;

		$this->_essence_track_id = $this->_model->insert_record($this->_model->table_essence_tracks, $this->essence_track);

		/* Insert Essence Track End */
		$this->save_track_encoding($track);
		$this->save_essence_identifier($track);
		if (isset($track['attributes']['type']) && $track['attributes']['type'] === 'Video')
		{
			$this->fetch_video_info($track['children']);
		}
		$this->essence_track = array();
		$this->_essence_track_id = NULL;
	}

	function save_essence_identifier($track)
	{
		$essence_track_identifier = array();
		if (isset($track['children']['id']) && isset($track['children']['id'][0]) && isset($track['children']['id'][0]['text']) && ! empty($track['children']['id'][0]['text']))
		{
			$essence_track_identifier['essence_track_identifiers'] = $track['children']['id'][0]['text'];
			$essence_track_identifier['essence_track_identifier_source'] = 'mediainfo';
		}
		else if (isset($track['children']['streamkindid']) && isset($track['children']['streamkindid'][0]) && isset($track['children']['streamkindid'][0]['text']))
		{
			$essence_track_identifier['essence_track_identifiers'] = $track['children']['streamkindid'][0]['text'];
			$essence_track_identifier['essence_track_identifier_source'] = 'mediainfo';
		}
		if (isset($essence_track_identifier['essence_track_identifiers']))
		{
			$essence_track_identifier['essence_tracks_id'] = $this->_essence_track_id;
			$this->_model->insert_record($this->_model->table_essence_track_identifiers, $essence_track_identifier);
		}

		unset($essence_track_identifier);
	}

	function save_track_encoding($track)
	{
		/* Essence Track Encoding Start */
		$essence_track_encodeing = array();
		if (isset($track['children']['codec_string']) && isset($track['children']['codec_string'][0]) && isset($track['children']['codec_string'][0]['text']) && ! empty($track['children']['codec_string'][0]['text']))
			$essence_track_encodeing['encoding'] = $track['children']['codec_string'][0]['text'];

		else if (isset($track['children']['format']) && isset($track['children']['format'][0]) && isset($track['children']['format'][0]['text']) && ! empty($track['children']['format'][0]['text']))
			$essence_track_encodeing['encoding'] = $track['children']['format'][0]['text'];
		if (isset($track['children']['codec_url']) && isset($track['children']['codec_url'][0]) && isset($track['children']['codec_url'][0]['text']) && ! empty($track['children']['codec_url'][0]['text']))
			$essence_track_encodeing['encoding_ref'] = $track['children']['codec_url'][0]['text'];
		else if (isset($track['children']['format_url']) && isset($track['children']['format_url'][0]) && isset($track['children']['format_url'][0]['text']) && ! empty($track['children']['format_url'][0]['text']))
			$essence_track_encodeing['encoding_ref'] = $track['children']['format_url'][0]['text'];
		if (isset($essence_track_encodeing['encoding']))
		{
			$essence_track_encodeing['essence_tracks_id'] = $this->_essence_track_id;
			$essence_track_encodeing['encoding_source'] = 'mediainfo';
			$this->_model->insert_record($this->_model->table_essence_track_encodings, $essence_track_encodeing);
		}
		unset($essence_track_encodeing);
		/* Essence Track Encoding End */
	}

	function save_date_rate($track)
	{
		if (isset($track['children']['bitrate_string']) && isset($track['children']['bitrate_string'][0]) && isset($track['children']['bitrate_string'][0]['text']) && ! empty($track['children']['bitrate_string'][0]['text']))
		{
			$bitrate = explode(' ', $track['children']['bitrate_string'][0]['text']);
			$this->essence_track['data_rate'] = (isset($bitrate[0])) ? $bitrate[0] : '';
			$data_rate_unit = (isset($bitrate[1])) ? $bitrate[1] : '';
			if ($data_rate_unit != '')
			{
				$data_rate = $this->_model->get_one_by($this->_model->table_data_rate_units, array('unit_of_measure' => $data_rate_unit), TRUE);
				if ( ! is_empty($data_rate))
					$this->essence_track['data_rate_units_id'] = $data_rate->id;
				else
					$this->essence_track['data_rate_units_id'] = $this->_model->insert_record($this->_model->table_data_rate_units, array('unit_of_measure' => $data_rate_unit));
			}
		}
	}

	function save_track_type($track)
	{
		$track_type = '';
		if (isset($track['attributes']['type']))
			$track_type = strtolower($track['attributes']['type']);

		if ($track_type != '')
		{
			$essence_track_type = $this->_model->get_one_by($this->_model->table_essence_track_types, array('essence_track_type' => $track_type), TRUE);
			if (isset($essence_track_type) && isset($essence_track_type->id))
				$this->essence_track['essence_track_types_id'] = $essence_track_type->id;
			else
				$this->essence_track['essence_track_types_id'] = $this->_model->insert_record($this->_model->table_essence_track_types, array('essence_track_type' => $track_type));
		}
//					}
		/* Essence Track type End */
	}

	function fetch_audio_info($audio_track)
	{
		if (isset($audio_track['channel_s__string']) && isset($audio_track['channel_s__string'][0]))
		{
			if (isset($audio_track['channel_s__string'][0]['text']))
			{
				$channel = substr_replace($audio_track['channel_s__string'][0]['text'], "", -1);
				$this->_model->update_instantiations($this->_instantiation_id, array('channel_configuration' => $channel));
			}
		}
		if (isset($audio_track['samplingrate_string']) && isset($audio_track['samplingrate_string'][0]))
		{
			if (isset($audio_track['samplingrate_string'][0]['text']))
			{
				$this->essence_track['sampling_rate'] = $audio_track['samplingrate_string'][0]['text'];
			}
		}
	}

	function fetch_video_info($video_track)
	{
		$this->essence_track = array();
		/* Essence Track Frame Rate Start */
		if (isset($video_track['framerate']) && isset($video_track['framerate'][0]))
		{
			if (isset($video_track['framerate'][0]['text']))
			{
				$this->essence_track['frame_rate'] = $video_track['framerate'][0]['text'];
			}
		}
		/* Essence Track Frame Rate End */
		/* Essence Track Aspect Ratio Start */
		if (isset($video_track['displayaspectratio_string']) && isset($video_track['displayaspectratio_string'][0]))
		{
			if (isset($video_track['displayaspectratio_string'][0]['text']))
			{
				$this->essence_track['aspect_ratio'] = $video_track['displayaspectratio_string'][0]['text'];
			}
		}
		/* Essence Track Aspect Ratio End */
		/* Essence Track Frame Size Start */
		$frame = array();
		if (isset($video_track['width']) && isset($video_track['width'][0]))
		{
			if (isset($video_track['width'][0]['text']))
			{
				$frame['width'] = $video_track['width'][0]['text'];
			}
		}
		if (isset($video_track['height']) && isset($video_track['height'][0]))
		{
			if (isset($video_track['height'][0]['text']))
			{
				$frame['height'] = $video_track['height'][0]['text'];
			}
		}
		if (isset($frame['width']) || isset($frame['height']))
		{
			$track_frame_size = $this->_model->get_one_by($this->_model->table_essence_track_frame_sizes, array('width' => trim($frame['width']), 'height' => trim($frame['height'])));
			if ($track_frame_size)
			{
				$this->essence_track['essence_track_frame_sizes_id'] = $track_frame_size->id;
			}
			else
			{
				$this->essence_track['essence_track_frame_sizes_id'] = $this->_model->insert_record($this->_model->table_essence_track_frame_sizes, $frame);
			}
		}
		unset($frame);
		/* Essence Track Frame Size End */
		$this->_model->update_essence_track($this->_essence_track_id, $this->essence_track);

		$this->save_essence_annotation($video_track);
	}

	function save_essence_annotation($video_track)
	{
		$essence_annotation = array();
		if (isset($video_track['colorspace']) && isset($video_track['colorspace'][0]))
		{
			if (isset($video_track['colorspace'][0]['text']))
			{
				$essence_annotation[] = array('annotation' => $video_track['colorspace'][0]['text'], 'annotation_type' => 'colorspace');
			}
		}
		if (isset($video_track['chromasubsampling']) && isset($video_track['chromasubsampling'][0]))
		{
			if (isset($video_track['chromasubsampling'][0]['text']))
			{
				$essence_annotation[] = array('annotation' => $video_track['chromasubsampling'][0]['text'], 'annotation_type' => 'subsampling');
			}
		}
		if (count($essence_annotation) > 0)
		{
			foreach ($essence_annotation as $annotation)
			{
				$annotation['essence_tracks_id'] = $this->_essence_track_id;
				$this->_model->insert_record($this->_model->table_essence_track_annotations, $annotation);
			}
		}
		unset($essence_annotation);
	}

	/* End of Import_essencetrack_mediainfo Class */
}

// END Import_essencetrack_mediainfo Controller
// End of file import_essencetrack_mediainfo.php 
/* Location: ./application/libraries/import_essencetrack_mediainfo.php */
