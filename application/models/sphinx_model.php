<?php

class Sphinx_Model extends CI_Model
{

	function __construct ()
	{
		parent::__construct();
		$this->load->library('sphinxsearch');
	}

	public function search_stations($params, $offset = 0, $limit = 5)
	{
   	
		$total_record = 0;
		$this->sphinxsearch->reset_filters();
		$this->sphinxsearch->reset_group_by();
		//$where = $this->get_sphinx_search_condtion($params);
		$mode =SPH_MATCH_EXTENDED;
		$this->sphinxsearch->set_array_result ( true );
		$this->sphinxsearch->set_match_mode ( $mode );
		$this->sphinxsearch->set_connect_timeout ( 120 );
		//if ( $limit ) $this->sphinxsearch->set_limits ( (int) $offset, (int) $limit, ( $limit>1000 ) ? $limit : 1000 );
		$res = $this->sphinxsearch->query( $params, 'stations' );
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
		return array("total_count"=>$total_record,"records"=>$listings,"query_time"=>$execution_time);
	}
        public function search($params){
            
            $total_record = 0;
		$this->sphinxsearch->reset_filters();
		$this->sphinxsearch->reset_group_by();
                
                $filter=$this->sphinxsearch->set_filter("is_certified",array(1));
                $mode =SPH_MATCH_EXTENDED;
		$this->sphinxsearch->set_array_result ( true );
		$this->sphinxsearch->set_match_mode ( $mode );
		$this->sphinxsearch->set_connect_timeout ( 120 );
                echo '<pre>';print_r($filter);exit;
        }
}

?>
