<?php

/**
 * Assets Controller
 * 
 * PHP version 5
 * 
 * @category   AMS
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@geekschicago.com>
 * @license    AMS http://ams.avpreserve.com
 * @version    GIT: <$Id>
 * @link       http://ams.avpreserve.com
 */

/**
 * Assets Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@geekschicago.com>
 * @license    AMS http://ams.avpreserve.com
 * @link       http://ams.avpreserve.com
 */
class Assets extends MY_Controller
{

	/**
	 * Constructor
	 * 
	 * Load the layout.
	 * 
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->model('manage_asset_model', 'manage_asset');
	}

	public function edit()
	{
		$asset_id = $this->uri->segment(3);

		if ( ! empty($asset_id))
		{

			
			$data['asset_detail'] = $this->manage_asset->get_asset_detail_by_id($asset_id);
			if ($data['asset_detail'])
			{
				debug($data['asset_detail'],FALSE);
				$data['pbcore_asset_types'] = $this->manage_asset->get_picklist_values(1);
				$data['pbcore_asset_date_types'] = $this->manage_asset->get_picklist_values(2);
				$data['pbcore_asset_title_types'] = $this->manage_asset->get_picklist_values(3);
				$data['pbcore_asset_subject_types'] = $this->manage_asset->get_subject_types();
				$data['pbcore_asset_description_types'] = $this->manage_asset->get_picklist_values(4);
				$data['organization'] = $this->station_model->get_all();
				$this->load->view('assets/edit', $data);
			}
			else
			{
				show_error('Not a valid asset id');
			}
		}
		else
		{
			show_error('Require asset id for editing');
		}
	}

	public function insert_pbcore_values()
	{
		$asset_type = array('Abstract', 'Anecdotal Comments & Reflections', 'Annotation', 'Art Work', 'Assessment', 'Awards', 'Bookmarks', 'Caption', 'Clip',
			'Collection','Comments','Content Flags','Credit Line','Cue Words','Description','Dopesheet','DVS','Edit Decision List','Element','Ensemble',
			'Episode','Evaluation','Event','Excerpt','Headline','Highlights','Instructions','Item','Key Points','Keyword','Listing Services',
			'Log','Model','Movement','Number','Object','Outline','Package','Playlist','PODS','Process','Program',
			'Project','Promotional','Public','Purpose','Review','Rundown','Script','Segment','Selection','Sequence','Series',
			'Shot List','Speech-to-text','Story','Subtitles','Summary','Synopsis','Table of Contents','Text-to-speech','Theme'
			
			);
		foreach ($asset_type as $value)
		{
			$this->manage_asset->insert_picklist_value(array('element_type_id' => 4, 'value' => $value));
		}
	}

}