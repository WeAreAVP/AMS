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

			$data['pbcore_asset_types'] = $this->manage_asset->get_asset_types(1);
			$data['asset_detail'] = $this->manage_asset->get_asset_detail_by_id($asset_id);
			if ($data['asset_detail'])
			{
				debug($data);
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
		$asset_type = array('Advertisement', 'Air Check', 'Album', 'Brand', 'Clip', 'Collection', 'Element', 'Episode', 'Event',
			'Excerpt', 'Framework', 'Franchise', 'Item', 'Media Object', 'Pilot', 'Program', 'Project', 'Scene', 'Season', 'Segment',
			'Series', 'Shot', 'Song', 'Story', 'Track');
		foreach ($asset_type as $value)
		{
			$this->manage_asset->insert_picklist_value(array('element_type_id' => 1, 'value' => $value));
		}
	}

}