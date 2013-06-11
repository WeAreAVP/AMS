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

	public function select($index_name,$data_array)
	{



		
		// build first part of query
		$query = 'SELECT * FROM `' . $index_name . '`';
		
		if (is_int($data_array['group_by']))
		{
			// have some values, push these
			$query .= ' GROUP BY `' . $data_array['group_by']. '`';
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