<?php

class Sphinx_Model extends CI_Model
{

	function __construct ()
	{
		parent::__construct();
		$this->load->library('sphinxsearch');
	}

	public function search_listings_new($params, $offset = 0, $limit = 5, $select = FALSE )
	{
   	$listings=false;
		$total_record = 0;
		if (!$this->config->item('USESPHINX'))
		{
			$start_time=time();
			$where = $this->get_search_condition($params);
			$where_clause = implode(' AND ', $where);
			$sql="SELECT * FROM ".$this->search_table."  ";
			$sql_count="SELECT COUNT(id) as total FROM ".$this->search_table." WHERE (".$where_clause.") ";
     	$sql .=$order_by;
			$sql .=" LIMIT ".$offset." , ".$limit;
			$total = $this->db->query($sql_count)->result_array();
			$total_record = $total[0]['total'];
			if($total_record>0)
			{
				$res=$this->read_db->query($sql);
        if($res)
				{
	     		$listings=$res->result();         	
       	}
			}
			$end_time=time();
			$execution_time=$end_time-$start_time;
		}
		else
		{
			$this->sphinxsearch->reset_filters();
			$this->sphinxsearch->reset_group_by();
			$where = $this->get_sphinx_search_condtion($params);
			$mode = SPH_MATCH_EXTENDED;
			
			$this->sphinxsearch->set_array_result ( true );
			$this->sphinxsearch->set_match_mode ( $mode );
			$this->sphinxsearch->set_connect_timeout ( 120 );
			if ( $limit ) $this->sphinxsearch->set_limits ( (int) $offset, (int) $limit, ( $limit>1000 ) ? $limit : 1000 );
			$res = $this->sphinxsearch->query( $where, 'hud_listings' );
			$execution_time=$res['time'];
			if ( $res)
			{
				$total_record=$res['total_found'];
				if($total_record>0)
				{
					if(isset($res['matches']))
					{
						foreach($res['matches'] as $record)
						{
							$listings[]=(object) array_merge(array('id'=>$record['id']),$record['attrs']);
						}
					}
				}
			}
		}
		return array("total_count"=>$total_record,"listings_record"=>$listings,"query_time"=>$execution_time);
	}
	function get_sphinx_search_condtion($params)
		{
			$query='';
			if ( array_key_exists('street', $params) and $params[ 'street' ] != '' )
			{
				$query    .= ' @street '.$params[ 'street' ].' ';
			}
			if ( array_key_exists('city', $params) and $params[ 'city' ] != '' )
			{

				$query    .= ' @city '.$params[ 'city' ].' ';
			}
			if ( array_key_exists('county', $params) and $params[ 'county' ] != '' )
			{
				if($params[ 'county' ] != 'NULL')
				{
					$query    .= ' @county '.$params[ 'county' ].' ';
				}
			}
			if ( array_key_exists('state', $params) and $params[ 'state' ] != '' )
			{
				
				$query    .= ' @state '.$params[ 'state' ].' ';
			}
			if ( array_key_exists('zip', $params) and $params[ 'zip' ] != '' )
			{
				$this->sphinxsearch->set_filter("zip",array($params[ 'zip' ]));
			}
			if ( array_key_exists('property_type', $params) and $params[ 'property_type' ] != '' and is_numeric($params[ 'property_type' ]) )
			{
				$categroy_ids=$this->listing->get_property_type_ids($params[ 'property_type' ]);
				if($categroy_ids)
				{
					$this->sphinxsearch->set_filter("category",$categroy_ids);
				}	
			}
			if ( array_key_exists('price_start', $params) and $params[ 'price_start' ] != '' and is_numeric($params[ 'price_start' ]) )
			{
				if ( array_key_exists('price_end', $params) and $params[ 'price_end' ] != '' and is_numeric($params[ 'price_end' ]) and $params[ 'price_end' ] >= $params[ 'price_start' ] )
				{
					
					$this->sphinxsearch->set_filter_range("price",$params[ 'price_start' ], $params[ 'price_end' ]);
				}
				else
				{
					$this->sphinxsearch->set_filter_range("price",$params[ 'price_start' ],999999999999);
				}
			}
			else
			{
				if ( array_key_exists('price_end', $params) and $params[ 'price_end' ] != '' and is_numeric($params[ 'price_end' ]) )
				{ 
					
					$this->sphinxsearch->set_filter_range("price",0,$params[ 'price_end' ]);
				}
			}
		
		return $query;
	}
}

?>
