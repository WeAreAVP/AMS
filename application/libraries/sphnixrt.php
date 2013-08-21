<?php

// SphinxRT Search Interface for CodeIgniter
class Sphnixrt
{

	// variables
	public $sphinxql_link;
	public $link_status = false;
	public $errors = array('1' => 'Err#1: Bad link',
		'2' => 'Err#2: Missing structure',
		'3' => 'Err#3: No results');
	public $storage = array();
	public $counter = 1;
	private $CI;

	// construct
	public function __construct()
	{
		// get CI
		$this->CI = &get_instance();

		// load the config
		$this->CI->config->load('sphnixrt');

		// attempt to connect to Sphinx
		$this->sphinxql_link = new mysqli($this->CI->config->config['hostname'], 'sphinx', '', '', $this->CI->config->config['port']);

		// did the link work?
		if ( ! $this->sphinxql_link)
		{
			// update link status
			$this->link_status = false;

			// didn't work
			throw new Exception('Unable to communicate to the Sphinx Server');
		}
		else
		{
			// we did get an object
			$this->link_status = true;
		}
	}

	/**
	 * Make where for sphnix result
	 * @param string $type
	 * @param string $sphnix_index
	 * @return string
	 */
	function make_where_clause($type = NULL, $sphnix_index = NULL)
	{
		$where = '';
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
						if ($sphnix_index == 'assets_list')
						{
							$col_name = "s_{$index}";
							if ($index == 'asset_description')
								$col_name = 's_description';
						}
						else
						{
							$col_name = $index;
							if ($index == 'asset_title')
								$col_name = "s_{$index}";
						}

						if ($count == 0)
							$where .=" @{$col_name} \"$keyword\"";
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
						$column_sphinx = 'dates';
						if ($sphnix_index == 'assets_list')
							$column_sphinx = 'instantiation_date';
						$this->sphinxsearch->set_filter_range($column_sphinx, $start_date, $end_date);
						if ($index != 'All')
						{
							$where .=" @date_type \"$index\"";
						}
					}
				}
			}
		}
		if ((isset($this->session->userdata['digitized']) && $this->session->userdata['digitized'] === '1') || $type == 'digitized')
		{
			$this->sphinxsearch->set_filter("digitized", array(1));
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
		else if ($type == 'physical')
		{

			$where .= " @s_format_type \"physical\"";
		}

		if (isset($this->session->userdata['digital_format']) && $this->session->userdata['digital_format'] != '')
		{
			$digital_format = str_replace('|||', '" | "', trim($this->session->userdata['digital_format']));
			$where .=" @s_format_name \"^$digital_format$\" @s_format_type \"digital\"";
		}
		else if ($type == 'digital')
		{
			$where .= " @s_format_type \"digital\"";
		}
		if (isset($this->session->userdata['generation']) && $this->session->userdata['generation'] != '')
		{
			$generation = str_replace('|||', '" | "', trim($this->session->userdata['generation']));
			$where .=" @s_generation \"^$generation$\"";
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

	public function select($index_name, $data_array)
	{



		$this->_clear();
		// build first part of query
		$query = "SELECT {$data_array['column_name']},@count FROM `{$index_name}`";
		if (isset($data_array['where']) && ! empty($data_array['where']))
			$query .= ' WHERE ' . $data_array['where'] . '';
		if (isset($data_array['group_by']) && ! empty($data_array['group_by']))
		{
			// have some values, push these
			$query .= ' GROUP BY `' . $data_array['group_by'] . '`';
		}
// add start/limits?
		if (is_int($data_array['limit']) && is_int($data_array['start']))
		{
			// have some values, push these
			$query .= ' LIMIT ' . $data_array['start'] . ', ' . $data_array['limit'];
		}
		// execute query
		$result = $this->sphinxql_link->query($query);

		// successful query?
		if ($result)
		{
			// loop through results
			while ($rows = $result->fetch_array())
			{
				// add in row
				$this->storage['results']['records'][] = $rows;
			}

			// are there any results?
			if (isset($this->storage['results']))
			{
				// clean up the records
				$this->storage['results']['records'] = $this->_fix_records($this->storage['results']['records']);

				// we need meta information
				$result_meta = $this->sphinxql_link->query('SHOW META');

				// let's parse that result meta information
				while ($rows_meta = $result_meta->fetch_array())
				{
					// add in meta
					$this->storage['results']['meta'][$rows_meta['Variable_name']] = $rows_meta['Value'];
				}

				// pass back all the result data
				return $this->storage['results'];
			}
			else
			{
				// define
				$this->storage['results'] = array();

				// still need meta information
				$result_meta = $this->sphinxql_link->query('SHOW META');

				// let's parse that result meta information
				while ($rows_meta = $result_meta->fetch_array())
				{
					// add in meta
					$this->storage['results']['meta'][$rows_meta['Variable_name']] = $rows_meta['Value'];
				}

				// no results
				return $this->storage['results'];
			}
		}
		else
		{
			// no results
			return array('error' => $this->errors[3],
				'native' => $this->sphinxql_link->error);
		}
	}

	// fix up "records"
	public function _fix_records($records)
	{
		// define
		$new_records = array();

		// loop through
		foreach ($records as $key => $value)
		{
			// loop through values
			foreach ($value as $column => $data)
			{
				// is the column numeric?
				if ( ! is_numeric($column))
				{
					// add it back
					$new_records[$key][$column] = $data;
				}
			}
		}

		// return
		return $new_records;
	}

	// insert record
	/*	 * ********************
	  required array system
	  just an array e.g.
	  array('column_name' => 'column_data',
	  etc...);
	  )
	 * ******************** */
	public function insert($index_name, $data_array, $id)
	{
		// is the link working?
		if ( ! $this->check_link_status())
		{
			// link is already bad
			return array('error' => $this->errors[1]);

			// end
			break;
		}

		// add in id
		$data_array['id'] = $id;

		// continue processing
		// process the fieldnames
		foreach ($data_array as $key => $value)
		{
			// build up column names
			$this->data['insert']['column_names'][] = '`' . $key . '`';

			// build up match data
			// add escaping
			$this->data['insert']['column_data'][] = $this->_escape($value);
		}

		// build query
		$query = 'INSERT INTO `' . $index_name . '`
						(' . implode(', ', $this->data['insert']['column_names']) . ')
					VALUES
						(' . implode(', ', $this->data['insert']['column_data']) . ')';


		// let's perform the query
		$result = $this->sphinxql_link->query($query) or die(mysqli_error($this->sphinxql_link));

		// reset insert data
		unset($this->data['insert'], $query);
		// added By Nouman Tayyab 
		return TRUE;
		// did it work?
		if ( ! $result)
		{
			// failed
			return false;
		}
		else
		{
			// return our result set
			return $result;
		}
	}

	// perform a search
	/*	 * ********************
	  required array system
	  array('search' => 'query',
	  'limit' => 'int',
	  'start' => 'int',
	  'where' => array(array('id,=' => int),
	  array('author_id,=' => int)), (example columns)
	  'columns' => array([] => 'column_name'); # this will be added later
	  # to allow for more complex
	  # queries to take place
	  )

	 * ******************** */
	public function search($index_name, $data_array)
	{
		// is the link working?
		if ( ! $this->check_link_status())
		{
			// link is already bad
			return array('error' => $this->errors[1]);

			// end
			break;
		}

		// clear up
		$this->_clear();

		// do we have the right kind of information?
		if (isset($data_array['search']/* , $data_array['columns'] */))
		{
			// build first part of query
			$query = 'SELECT * FROM `' . $index_name . '` WHERE MATCH (' . $this->_escape($data_array['search']) . ')';

			// let's add in some more clauses
			if (isset($data_array['where']))
			{
				// we're looking to add some more
				// build up some cases
				foreach ($data_array['where'] as $key => $value)
				{
					// add into new array
					// explode values to find operators
					$new_operator = explode(',', $value[0]);

					// escape
					$this->storage['temp']['search_where_clauses'][] = '`' . $new_operator[0] . '` ' . $new_operator[1] . ' ' . $this->_escape($value[1]);
				}

				// implde them onto the query
				$query .= ' AND ' . implode(' AND ', $this->storage['temp']['search_where_clauses']);
			}

			// add start/limits?
			if (is_int($data_array['limit']) && is_int($data_array['start']))
			{
				// have some values, push these
				$query .= ' LIMIT ' . $data_array['start'] . ', ' . $data_array['limit'];
			}

			// execute query
			$result = $this->sphinxql_link->query($query);

			// successful query?
			if ($result)
			{
				// loop through results
				while ($rows = $result->fetch_array())
				{
					// add in row
					$this->storage['results']['records'][] = $rows;
				}

				// are there any results?
				if (isset($this->storage['results']))
				{
					// clean up the records
					$this->storage['results']['records'] = $this->_fix_records($this->storage['results']['records']);

					// we need meta information
					$result_meta = $this->sphinxql_link->query('SHOW META');

					// let's parse that result meta information
					while ($rows_meta = $result_meta->fetch_array())
					{
						// add in meta
						$this->storage['results']['meta'][$rows_meta['Variable_name']] = $rows_meta['Value'];
					}

					// pass back all the result data
					return $this->storage['results'];
				}
				else
				{
					// define
					$this->storage['results'] = array();

					// still need meta information
					$result_meta = $this->sphinxql_link->query('SHOW META');

					// let's parse that result meta information
					while ($rows_meta = $result_meta->fetch_array())
					{
						// add in meta
						$this->storage['results']['meta'][$rows_meta['Variable_name']] = $rows_meta['Value'];
					}

					// no results
					return $this->storage['results'];
				}
			}
			else
			{
				// no results
				return array('error' => $this->errors[3],
					'native' => $this->sphinxql_link->error);
			}
		}
		else
		{
			// missing information
			return array('error' => $this->errors[2]);
		}
	}

	// replace into, basically, update a record
	/*	 * ********************
	  required array system
	  just an array e.g.
	  array('column_name' => 'column_data',
	  etc...);
	  )
	 * ******************** */
	public function update($index_name, $data_array)
	{
		// is the link working?
		if ( ! $this->check_link_status())
		{
			// link is already bad
			return array('error' => $this->errors[1]);

			// end
			break;
		}

		// continue processing
		// process the fieldnames
		foreach ($data_array as $key => $value)
		{
			// build up column names
			$this->data['update']['column_names'][] = '`' . $key . '`';

			// build up match data
			$this->data['update']['column_data'][] = $this->_escape($value);
		}

		// build query
		$query = 'REPLACE INTO `' . $index_name . '`
						(' . implode(', ', $this->data['update']['column_names']) . ')
					VALUES
						(' . implode(', ', $this->data['update']['column_data']) . ')';

		// let's perform the query
		$result = $this->sphinxql_link->query($query);

		// reset insert data
		unset($this->data['update'], $query);

		// added By Nouman Tayyab 
		return TRUE;
		// did it work?
		
		return $result !== false;
	}

	// truncate an index
	public function truncate($index_name)
	{
		// is the link working?
		if ( ! $this->check_link_status())
		{
			// link is already bad
			return array('error' => $this->errors[1]);

			// end
			break;
		}

		// build query
		$query = 'TRUNCATE RTINDEX `' . $index_name . '`';

		// perform query
		$result = $this->sphinxql_link->query($query);

		// reset truncate data
		unset($index_name);

		// did it work?
		return $result !== false;
	}

	// delete an item
	public function delete($index_name, $data_array, $segmentation = false)
	{
		// is the link working?
		if ( ! $this->check_link_status())
		{
			// link is already bad
			return array('error' => $this->errors[1]);

			// end
			break;
		}

		// build query
		$query = 'DELETE FROM `' . $index_name . '`';

		// is it a query?
		if (is_array($data_array))
		{
			// process
			// this should be a list of id's
			$query .= ' WHERE IN (' . implode(',', $data_array) . ')';
		}
		else
		{
			// give it a raw query
			$query .= ' WHERE ' . $data_array;
		}

		// perform query
		$result = $this->sphinxql_link->query($query);

		// reset data
		unset($index_name, $data_array);

		// are we locally looking up?
		if ( ! $segmentation)
		{
			// did it work?
			return $result !== false;
		}
	}

	// delete from a result set
	public function delete_from_resultset($index_name, $result_array, $id_column = 'id')
	{
		// make sure we have data
		if (is_array($result_array) && (count($result_array) > 0))
		{
			// define
			$id_array = array();

			// let's loop through
			foreach ($result_array as $row => $record)
			{
				// add it (or overwrite, not that that should happen)
				$id_array[$record[$id_column]] = $record[$id_column];
			}

			// do we have any results?
			if (is_array($id_array) && (count($id_array) > 0))
			{
				// we'll now chunk our array into 50 pieces
				$new_id_array = array_chunk($id_array, 50, true);

				// loop through and pass to the delete function
				foreach ($new_id_array as $chunk_id => $chunk_pieces)
				{
					// pass
					$this->delete($index_name, $chunk_pieces, true);
				}

				// we'll assume we've completed (or at least we've executed the correct commands)
				return true;
			}
			else
			{
				// we'll say this didn't work
				return false;
			}
		}
		else
		{
			// assume we were successful
			return true;
		}
	}

	// flatten records
	public function flatten_records($records, $as_array = false)
	{
		// define
		$ids = array();

		// loop through and flatten
		foreach ($records as $record_id => $result)
		{
			// loop through the record for id
			foreach ($result as $column => $data)
			{
				// is this the id column?
				if ($column === 'id')
				{
					// add an id
					$ids[] = (int) $data;
				}
			}
		}

		// make sure it isn't empty
		if (empty($ids))
		{
			// no ids?
			return false;
		}
		else
		{
			// are we returning an array?
			if ($as_array)
			{
				// just return
				return $ids;
			}
			else
			{
				// return results
				return implode(', ', $ids);
			}
		}
	}

	// clear storage items that might get in the way
	public function _clear()
	{
		// clear
		unset($this->storage['results'], $this->storage['temp']);
	}

	// escape a string to Sphinx standard
	public function _escape($string)
	{
		// determine the variable type
		if ((gettype($string) == 'integer') || (gettype($string) == 'double') || (gettype($string) == 'boolean') || (gettype($string) == 'NULL'))
		{
			// it's numeric, return it raw
			return $string;
		}
		else
		{
			// let's do some processing
			// remove tags, if any
			$string = strip_tags($string);

			// trim
			$string = trim($string);

			// scape the main things
			$from = array('\\', '(', ')', '|', '-', '!', '@', '~', '"', '&', '/', '^', '$', '=', ';', '\'');
			$to = array('\\\\', '\(', '\)', '\|', '\-', '\!', '\@', '\~', '\"', '\&', '\/', '\^', '\$', '\=', '\;', '\\\'');

			// execute
			$string = str_replace($from, $to, $string);

			// remove new lines, they aren't needed
			$string = str_replace(array("\r", "\r\n", "\n"), ' ', $string);

			// remove whitespace
			$string = preg_replace('/(?:(?)|(?))(\s+)(?=\<\/?)/', ' ', $string);

			// this is safe
			return '\'' . (string) $string . '\'';
		}
	}

	// check link status
	public function check_link_status()
	{
		// is the link available?
		if ($this->link_status)
		{
			// is there
			return true;
		}
		else
		{
			// failed
			return false;
		}
	}

}