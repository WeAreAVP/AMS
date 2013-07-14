<?php

/**
 * Dashboard Controller
 * 
 * PHP version 5
 * 
 * @category   AMS
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @license    AVPS http://ams.avpreserve.com
 * @version    GIT: <$Id>
 * @link       http://ams.avpreserve.com
 */

/**
 * Dashboard Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @license    AVPS http://ams.avpreserve.com
 * @link       http://ams.avpreserve.com
 */
class Dashboard extends MY_Controller
{

	/**
	 * Constructor.
	 * 
	 * Load the layout for the dashboard.
	 *  
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->model('dashboard_model');
		$this->load->library('memcached_library');
		if ($this->is_station_user)
		{
			redirect('records/index');
		}
	}
	function convert(){
		set_time_limit(0);
		@ini_set("memory_limit", "2000M"); # 1GB
		@ini_set("max_execution_time", 999999999999); # 1GB
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		$this->load->model('searchd_model');
		$bag_check = file('asset-ids-to-remove.txt');
		
		$string='('.implode(',', $bag_check).')';
		$query="DELETE essence_track_identifiers FROM `essence_track_identifiers` 
INNER JOIN essence_tracks ON essence_tracks.id=essence_track_identifiers.`essence_tracks_id`
INNER JOIN instantiations ON instantiations.id=essence_tracks.`instantiations_id`
INNER JOIN assets ON assets.id=instantiations.`assets_id`
WHERE assets.stations_id IN {$string}";
		$this->searchd_model->run_query($query);
$query="DELETE essence_track_encodings FROM `essence_track_encodings` 
INNER JOIN essence_tracks ON essence_tracks.id=essence_track_encodings.`essence_tracks_id`
INNER JOIN instantiations ON instantiations.id=essence_tracks.`instantiations_id`
INNER JOIN assets ON assets.id=instantiations.`assets_id`
WHERE assets.stations_id IN {$string}";
$this->searchd_model->run_query($query);
$query="DELETE essence_track_annotations FROM `essence_track_annotations` 
INNER JOIN essence_tracks ON essence_tracks.id=essence_track_annotations.`essence_tracks_id`
INNER JOIN instantiations ON instantiations.id=essence_tracks.`instantiations_id`
INNER JOIN assets ON assets.id=instantiations.`assets_id`
WHERE assets.stations_id IN {$string}";
$this->searchd_model->run_query($query);
$query="DELETE essence_tracks FROM `essence_tracks` 
INNER JOIN instantiations ON instantiations.id=essence_tracks.`instantiations_id`
INNER JOIN assets ON assets.id=instantiations.`assets_id`
WHERE assets.stations_id IN {$string}";
$this->searchd_model->run_query($query);
$query="DELETE nominations FROM `nominations` 
INNER JOIN instantiations ON instantiations.id=nominations.`instantiations_id`
INNER JOIN assets ON assets.id=instantiations.`assets_id`
WHERE assets.stations_id IN {$string}";
$this->searchd_model->run_query($query);

$query="DELETE instantiation_relations FROM `instantiation_relations` 
INNER JOIN instantiations ON instantiations.id=instantiation_relations.`instantiations_id`
INNER JOIN assets ON assets.id=instantiations.`assets_id`
WHERE assets.stations_id IN {$string}";
$this->searchd_model->run_query($query);
$query="DELETE instantiation_identifier FROM `instantiation_identifier` 
INNER JOIN instantiations ON instantiations.id=instantiation_identifier.`instantiations_id`
INNER JOIN assets ON assets.id=instantiations.`assets_id`
WHERE assets.stations_id IN {$string}";
$this->searchd_model->run_query($query);
$query="DELETE instantiation_generations FROM `instantiation_generations` 
INNER JOIN instantiations ON instantiations.id=instantiation_generations.`instantiations_id`
INNER JOIN assets ON assets.id=instantiations.`assets_id`
WHERE assets.stations_id IN {$string}";
$this->searchd_model->run_query($query);
$query="DELETE instantiation_formats FROM `instantiation_formats` 
INNER JOIN instantiations ON instantiations.id=instantiation_formats.`instantiations_id`
INNER JOIN assets ON assets.id=instantiations.`assets_id`
WHERE assets.stations_id IN {$string}";
$this->searchd_model->run_query($query);
$query="DELETE instantiation_dimensions FROM `instantiation_dimensions` 
INNER JOIN instantiations ON instantiations.id=instantiation_dimensions.`instantiations_id`
INNER JOIN assets ON assets.id=instantiations.`assets_id`
WHERE assets.stations_id IN {$string}";
$this->searchd_model->run_query($query);
$query="DELETE instantiation_dates FROM `instantiation_dates` 
INNER JOIN instantiations ON instantiations.id=instantiation_dates.`instantiations_id`
INNER JOIN assets ON assets.id=instantiations.`assets_id`
WHERE assets.stations_id IN {$string}";
$this->searchd_model->run_query($query);
$query="DELETE instantiation_annotations FROM `instantiation_annotations` 
INNER JOIN instantiations ON instantiations.id=instantiation_annotations.`instantiations_id`
INNER JOIN assets ON assets.id=instantiations.`assets_id`
WHERE assets.stations_id IN {$string}";
$this->searchd_model->run_query($query);
$query="DELETE events FROM `events` 
INNER JOIN instantiations ON instantiations.id=events.`instantiations_id`
INNER JOIN assets ON assets.id=instantiations.`assets_id`
WHERE assets.stations_id IN {$string}";
$this->searchd_model->run_query($query);

$query="DELETE instantiations FROM `instantiations` 
INNER JOIN assets ON assets.id=instantiations.`assets_id`
WHERE assets.stations_id IN {$string}";
$this->searchd_model->run_query($query);
$query="DELETE rights_summaries FROM `rights_summaries` 
INNER JOIN assets ON assets.id=rights_summaries.`assets_id`
WHERE assets.stations_id IN {$string}";
$this->searchd_model->run_query($query);
$query="DELETE identifiers FROM `identifiers` 
INNER JOIN assets ON assets.id=identifiers.`assets_id`
WHERE assets.stations_id IN {$string}";
$this->searchd_model->run_query($query);
$query="DELETE extensions FROM `extensions` 
INNER JOIN assets ON assets.id=extensions.`assets_id`
WHERE assets.stations_id IN {$string}";
$this->searchd_model->run_query($query);
$query="DELETE coverages FROM `coverages` 
INNER JOIN assets ON assets.id=coverages.`assets_id`
WHERE assets.stations_id IN {$string}";
$this->searchd_model->run_query($query);
$query="DELETE asset_titles FROM `asset_titles` 
INNER JOIN assets ON assets.id=asset_titles.`assets_id`
WHERE assets.stations_id IN {$string}";
$this->searchd_model->run_query($query);
$query="DELETE asset_descriptions FROM `asset_descriptions` 
INNER JOIN assets ON assets.id=asset_descriptions.`assets_id`
WHERE assets.stations_id IN {$string}";
$this->searchd_model->run_query($query);
$query="DELETE asset_dates FROM `asset_dates` 
INNER JOIN assets ON assets.id=asset_dates.`assets_id`
WHERE assets.stations_id IN {$string}";
$this->searchd_model->run_query($query);
$query="DELETE assets_subjects FROM `assets_subjects` 
INNER JOIN assets ON assets.id=assets_subjects.`assets_id`
WHERE assets.stations_id IN {$string}";
$this->searchd_model->run_query($query);
$query="DELETE assets_relations FROM `assets_relations` 
INNER JOIN assets ON assets.id=assets_relations.`assets_id`
WHERE assets.stations_id IN {$string}";
$this->searchd_model->run_query($query);
$query="DELETE assets_publishers_role FROM `assets_publishers_role` 
INNER JOIN assets ON assets.id=assets_publishers_role.`assets_id`
WHERE assets.stations_id IN {$string}";
$this->searchd_model->run_query($query);
$query="DELETE assets_genres FROM `assets_genres` 
INNER JOIN assets ON assets.id=assets_genres.`assets_id`
WHERE assets.stations_id IN {$string}";
$this->searchd_model->run_query($query);
$query="DELETE assets_creators_roles FROM `assets_creators_roles` 
INNER JOIN assets ON assets.id=assets_creators_roles.`assets_id`
WHERE assets.stations_id IN {$string}";
$this->searchd_model->run_query($query);
$query="DELETE assets_contributors_roles FROM `assets_contributors_roles` 
INNER JOIN assets ON assets.id=assets_contributors_roles.`assets_id`
WHERE assets.stations_id IN {$string}";
$this->searchd_model->run_query($query);
$query="DELETE assets_audience_ratings FROM `assets_audience_ratings` 
INNER JOIN assets ON assets.id=assets_audience_ratings.`assets_id`
WHERE assets.stations_id IN {$string}";
$this->searchd_model->run_query($query);
$query="DELETE assets_audience_levels FROM `assets_audience_levels` 
INNER JOIN assets ON assets.id=assets_audience_levels.`assets_id`
WHERE assets.stations_id IN {$string}";
$this->searchd_model->run_query($query);
$query="DELETE assets_asset_types FROM `assets_asset_types` 
INNER JOIN assets ON assets.id=assets_asset_types.`assets_id`
WHERE assets.stations_id IN {$string}";
$this->searchd_model->run_query($query);
$query="DELETE annotations FROM `annotations` 
INNER JOIN assets ON assets.id=annotations.`assets_id`
WHERE assets.stations_id IN {$string}";
$this->searchd_model->run_query($query);
$query="DELETE assets FROM `assets` 
WHERE assets.stations_id IN {$string}";
	$this->searchd_model->run_query($query);
	echo 'done';exit;
								
								
	}
	function new_reverse(){
		$reverse=  array_reverse($array);
		
		foreach ($reverse as $value)
		{
			array_push($new, $value);
		}
		debug($new);
	}
	function mode()
	{
		$random = array(5, 7, 10, 3, 1, 7, 5, 6, 5);
		$mode_array = array();
		foreach ($random as $value)
		{
			if (isset($mode_array[$value]))
				$mode_array[$value] ++;
			else
				$mode_array[$value] = 1;
		}
		$mode_count=0;
		$mode_no=0;
		foreach ($mode_array as $key => $value)
		{
			if($mode_count < $value){
				$mode_count=$value;
				$mode_no=$key;
			}
		}
		echo $mode_no.'<br/>'.$mode_count;exit;
	}
	function reverse(){
		$array=array(1,2,3,4,5,6,7,8,9,10);
		$count=  count($array);
		$reverse=array_fill(0, $count-1, '');
		for($i=0;$i<$count/2;$i++){
			$reverse[$i]=$array[$count-1-$i];
			$reverse[$count-1-$i]=$array[$i];
		}
		debug($array,FALSE);
		debug($reverse);
		
	}
	function min_max(){
		$array=array(5,7,10,13,2,1,5,76);
		$min=0;
		$max=0;
		foreach ($array as  $value)
		{
			if($value > $max)
				$max=$value;
			if($value < $min)
				$min=$value;
			
		}
		echo 'Max Value: '.$max.'<br/>';
		echo 'Min Value: '.$min.'<br/>';
		exit;
	}
	/**
	 * Dashboard Functionality
	 * 
	 * @return view dashboard/index
	 */
	public function index()
	{
		$data['digitized_format_name'] = json_decode($this->memcached_library->get('graph_digitized_format_name'), TRUE);
		$data['digitized_total'] = json_decode($this->memcached_library->get('graph_digitized_total'), TRUE);
		$data['scheduled_format_name'] = json_decode($this->memcached_library->get('graph_scheduled_format_name'), TRUE);
		$data['scheduled_total'] = json_decode($this->memcached_library->get('graph_scheduled_total'), TRUE);
		$data['material_goal'] = json_decode($this->memcached_library->get('material_goal'), TRUE);
		$data['at_crawford'] = json_decode($this->memcached_library->get('at_crawford'), TRUE);
		$data['total_hours'] = json_decode($this->memcached_library->get('total_hours'), TRUE);
		$data['percentage_hours'] = json_decode($this->memcached_library->get('percentage_hours'), TRUE);
		$data['total_region_digitized'] = json_decode($this->memcached_library->get('total_region_digitized'), TRUE);
		$data['total_hours_region_digitized'] = json_decode($this->memcached_library->get('total_hours_region_digitized'), TRUE);
		$data['pie_total_completed'] = json_decode($this->memcached_library->get('pie_total_completed'), TRUE);
		$data['pie_total_scheduled'] = json_decode($this->memcached_library->get('pie_total_scheduled'), TRUE);
		$data['pie_total_radio_completed'] = json_decode($this->memcached_library->get('pie_total_radio_completed'), TRUE);
		$data['pie_total_radio_scheduled'] = json_decode($this->memcached_library->get('pie_total_radio_scheduled'), TRUE);
		$data['pie_total_tv_completed'] = json_decode($this->memcached_library->get('pie_total_tv_completed'), TRUE);
		$data['pie_total_tv_scheduled'] = json_decode($this->memcached_library->get('pie_total_tv_scheduled'), TRUE);

		$this->load->view('dashboard/index', $data);
	}

}

// END Dashboard Controller

// End of file dashboard.php 
/* Location: ./application/controllers/dashboard.php */