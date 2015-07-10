<?php

/**
 * AMS Archive Management System
 * 
 * 
 * 
 * PHP version 5
 * 
 * @category   AMS
 * @package    CI
 * @subpackage Core
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @copyright  Copyright (c) WGBH (http://www.wgbh.org/). All Rights Reserved.
 * @license    http://www.gnu.org/licenses/gpl.txt GPLv3
 * @version    GIT: <$Id>
 * @link       https://github.com/avpreserve/AMS
 */

/**
 * MY_Records_Contorller Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Core
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @copyright  Copyright (c) WGBH (http://www.wgbh.org/). All Rights Reserved.
 * @license    http://www.gnu.org/licenses/gpl.txt GPLv3
 * @link       https://ams.americanarchive.org
 */
class MY_Records_Controller extends MY_Controller
{

	/**
	 * constructor. Load layout,Model,Library and helpers
	 * 
	 */
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * Load Facet sidebar for both assets tab and instantations tab.
	 * 
	 * @return view
	 */
	function load_facet_columns()
	{
		if (isAjax())
		{
			$is_all_facet = $this->input->post('issearch');
			$index = $this->input->post('index');
			$this->load->library('sphnixrt');

			if ($is_all_facet > 0 || $this->is_station_user)
			{
				$states = $this->sphinx->facet_index('state', $index);
				$data['org_states'] = sortByOneKey($states['records'], 'state');
				unset($states);

				$stations = $this->sphinx->facet_index('organization', $index);
				$data['stations'] = sortByOneKey($stations['records'], 'organization');

				unset($stations);

				$nomination = $this->sphinx->facet_index('status', $index);
				$data['nomination_status'] = sortByOneKey($nomination['records'], 'status');
				unset($nomination);
				$media_type = $this->sphinx->facet_index('media_type', $index);
				$media_type = $this->make_facet($media_type, 'media_type', 'media_type');
				$data['media_types'] = sortByOneKey($media_type, 'media_type', TRUE);
				unset($media_type);
				$p_format = $this->sphinx->facet_index('physical_format_name', $index, 'physical');
				$p_format = $this->make_facet($p_format, 'physical_format_name', 'physical_format');
				$data['physical_formats'] = sortByOneKey($p_format, 'physical_format_name', TRUE);
				unset($p_format);
				$d_format = $this->sphinx->facet_index('digital_format_name', $index, 'digital');
				$d_format = $this->make_facet($d_format, 'digital_format_name', 'digital_format');
				$data['digital_formats'] = sortByOneKey($d_format, 'digital_format_name', TRUE);
				unset($d_format);
				$generation = $this->sphinx->facet_index('facet_generation', $index);
				$generation = $this->make_facet($generation, 'facet_generation', 'generation');
				$data['generations'] = sortByOneKey($generation, 'facet_generation', TRUE);
				unset($generation);
				$digitized = $this->sphinx->facet_index('digitized', $index, 'digitized');
				$data['digitized'] = $digitized['records'];

				$migration = $this->sphinx->facet_index('migration', $index, 'migration');
				$data['migration'] = $migration['records'];
			}
			else
			{
				if ($index == 'assets_list')
				{
					$key_name = 'asset';
				}
				else
				{
					$key_name = 'ins';
				}
				$data['org_states'] = json_decode($this->memcached_library->get($key_name . '_state'), TRUE);

				$data['stations'] = json_decode($this->memcached_library->get($key_name . '_stations'), TRUE);

				$data['nomination_status'] = json_decode($this->memcached_library->get($key_name . '_status'), TRUE);
				$data['media_types'] = json_decode($this->memcached_library->get($key_name . '_media_type'), TRUE);
				$data['physical_formats'] = json_decode($this->memcached_library->get($key_name . '_physical'), TRUE);

				$data['digital_formats'] = json_decode($this->memcached_library->get($key_name . '_digital'), TRUE);
				$data['generations'] = json_decode($this->memcached_library->get($key_name . '_generations'), TRUE);


				$data['digitized'] = json_decode($this->memcached_library->get($key_name . '_digitized'), TRUE);

				$data['migration'] = json_decode($this->memcached_library->get($key_name . '_migration'), TRUE);
			}

			echo $this->load->view('instantiations/_facet_columns', $data, TRUE);
			exit_function();
		}
		show_404();
	}

	/**
	 * Make instantiations data to display in datatable.
	 * 
	 * @return json
	 */
	public function instantiation_table()
	{
		$params = array('search' => '');
		$column = array(
			'Organization' => 'organization',
			'Instantiation_ID' => 'instantiation_identifier',
			'Nomination' => 'status',
			'Instantiation\'s_Asset_Title' => 'asset_title',
			'Media_Type' => 'media_type',
			'Generation' => 'facet_generation',
			'Format' => 'format_name',
			'Duration' => 'projected_duration',
			'Date' => 'dates',
			'File_size' => 'file_size',
			'Colors' => 'color',
			'Language' => 'language',
		);


		$this->session->unset_userdata('column');
		$this->session->unset_userdata('jscolumn');
		$this->session->unset_userdata('column_order');
		$this->session->set_userdata('jscolumn', $this->input->get('iSortCol_0'));
		$this->session->set_userdata('column', $column[$this->column_order[$this->input->get('iSortCol_0')]['title']]);
		$this->session->set_userdata('column_order', $this->input->get('sSortDir_0'));


		$offset = isset($this->session->userdata['offset']) ? $this->session->userdata['offset'] : 0;
		$records = $this->sphinx->instantiations_list($params, $offset, 100, TRUE);

		$data['total'] = $records['total_count'];
		$record_ids = array_map(array($this, 'make_map_array'), $records['records']);
		$this->load->model('searchd_model', 'searchd');
		$records = $this->searchd->get_instantiation($record_ids);
//		$records = $records['records'];
		$data['count'] = count($records);
		$table_view = instantiations_datatable_view($records, $this->column_order);

		$dataTable = array(
			"sEcho" => intval($this->input->get('sEcho')),
			"iTotalRecords" => intval($data['count']),
			"iTotalDisplayRecords" => intval($data['count']),
			'aaData' => $table_view
		);
		echo json_encode($dataTable);
		exit_function();
	}


                        /**
         * Get Proxy file path by guid from sony service.
         * 
         * @param string $guid
         * @return boolean/array
         */
        function proxy_files_sony($asset_id_num)
        {
                # error_reporting(E_ALL);
                # ini_set('display_errors', 1);
                # $proxy_guid = str_replace('/', '-', $guid);
                # $proxy_response = file_get_contents("http://cpbaaaccess.crawfordmedia.com/xml.php?GUID=$proxy_guid");
		exec("/var/www/html/application/ci_xml.sh " . $asset_id_num , $foo_xml);
                $proxy_response = implode($foo_xml);
                $x = @simplexml_load_string($proxy_response);
                if (is_object($x))
                {
                        $data = xmlObjToArr($x);

                        $child = $data['children'];
                        if (isset($data['name']) && $data['name'] == 'error')
                        {
                                return FALSE;
                        }
                        else
                        {
                                if (isset($child['mediaurl'][0]))
                                {
                                        $media['url'] = $child['mediaurl'][0]['text'];
                                }
                                if (isset($child['format'][0]))
                                {
                                        $media['format'] = $child['format'][0]['text'];
                                }
                                return $media;
                        }
                }
                return FALSE;
        }


	/**
	 * Get Proxy file path by guid from crawford server.
	 * 
	 * @param string $guid
	 * @return boolean/array
	 */
	function proxy_files($guid)
	{
		$proxy_guid = str_replace('/', '-', $guid);
		$proxy_response = file_get_contents("http://cpbaaaccess.crawfordmedia.com/xml.php?GUID=$proxy_guid");
		$x = @simplexml_load_string($proxy_response);
		if (is_object($x))
		{
			$data = xmlObjToArr($x);

			$child = $data['children'];
			if (isset($data['name']) && $data['name'] == 'error')
			{
				return FALSE;
			}
			else
			{
				if (isset($child['mediaurl'][0]))
				{
					$media['url'] = $child['mediaurl'][0]['text'];
				}
				if (isset($child['format'][0]))
				{
					$media['format'] = $child['format'][0]['text'];
				}
				return $media;
			}
		}
		return FALSE;
	}

	/**
	 * Make assets data to display in datatable simple view.
	 * 
	 * @return json
	 */
	public function sort_simple_table()
	{
		$columns = array( 'organization', 'guid_identifier', 'local_identifier', 'asset_title', 'description');
		$this->session->unset_userdata('column');
		$this->session->unset_userdata('jscolumn');
		$this->session->unset_userdata('column_order');

		$this->session->set_userdata('jscolumn', $this->input->get('iSortCol_0'));
		$this->session->set_userdata('column', $columns[$this->input->get('iSortCol_0')]);

		$this->session->set_userdata('column_order', $this->input->get('sSortDir_0'));

		$offset = isset($this->session->userdata['offset']) ? $this->session->userdata['offset'] : 0;

		$records = $this->sphinx->assets_listing($offset, 100, TRUE);
		$data['total'] = $records['total_count'];
		$record_ids = array_map(array($this, 'make_map_array'), $records['records']);
		$this->load->model('searchd_model', 'searchd');
		$records = $this->searchd->get_assets($record_ids);
//		
//		$records = $records['records'];
		$data['count'] = count($records);
		$table_view = simple_simple_datatable_view($records);


		$dataTable = array(
			"sEcho" => $this->input->get('sEcho') + 1,
			"iTotalRecords" => $data['count'],
			"iTotalDisplayRecords" => $data['count'],
			'aaData' => $table_view
		);
		echo json_encode($dataTable);
		exit_function();
	}

	/**
	 * Make assets data to display in datatable for full view.
	 * 
	 * @return json
	 */
	public function sort_full_table()
	{
		$column = array(
			'Organization' => 'organization',
			'Titles' => 'asset_title',
			'AA_GUID' => 'guid_identifier',
			'Local_ID' => 'local_identifier',
			'Description' => 'description',
			'Subjects' => 'asset_subject',
			'Genre' => 'asset_genre',
			'Assets_Date' => 'dates',
			'Creator' => 'asset_creator_name',
			'Contributor' => 'asset_contributor_name',
			'Publisher' => 'asset_publisher_name',
			'Coverage' => 'asset_coverage',
			'Audience_Level' => 'asset_audience_level',
			'Audience_Rating' => 'asset_audience_rating',
			'Annotation' => 'asset_annotation',
			'Rights' => 'asset_rights');


		$this->session->unset_userdata('column');
		$this->session->unset_userdata('jscolumn');
		$this->session->unset_userdata('column_order');
		$this->session->set_userdata('jscolumn', $this->input->get('iSortCol_0'));
		$this->session->set_userdata('column', $column[$this->column_order[$this->input->get('iSortCol_0')]['title']]);
		$this->session->set_userdata('column_order', $this->input->get('sSortDir_0'));
		$offset = isset($this->session->userdata['offset']) ? $this->session->userdata['offset'] : 0;

		$records = $this->sphinx->assets_listing($offset, 100, TRUE);
		$data['total'] = $records['total_count'];
		$record_ids = array_map(array($this, 'make_map_array'), $records['records']);
		$this->load->model('searchd_model', 'searchd');
		$records = $this->searchd->get_assets($record_ids);
//		
//		$records = $records['records'];
		$data['count'] = count($records);
		$table_view = full_assets_datatable_view($records, $this->column_order);

		$dataTable = array(
			"sEcho" => $this->input->get('sEcho') + 1,
			"iTotalRecords" => $data['count'],
			"iTotalDisplayRecords" => $data['count'],
			'aaData' => $table_view
		);

		echo json_encode($dataTable);
		exit_function();
	}

}

?>
