<?php

if ( ! defined('BASEPATH'))
{
	exit('No direct script access allowed');
}

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
	 * Load Facet sidebar
	 * 
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

}

?>