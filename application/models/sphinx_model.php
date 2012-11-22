<?php

/**
 * Sphinx Model.
 *
 * @package    AMS
 * @subpackage Sphinx_Model
 * @author     Ali Raza
 */
class Sphinx_Model extends CI_Model
{
    /*
     *
     * constructor. Load Sphinx Search Library
     * 
     */

    function __construct()
    {
        parent::__construct();
        $this->load->library('sphinxsearch');
    }

    /**
     * Get list of all the stations based on search params
     * 
     * @Perm Get Array of Perm possible value of array are certified,agreed,start_date,end_date,search_kewords
     * @return Object 
     */
    public function search_stations($params, $offset = 0, $limit = 100)
    {

        $total_record = 0;
        $this->sphinxsearch->reset_filters();
        $this->sphinxsearch->reset_group_by();
        //$where = $this->get_sphinx_search_condtion($params);
        $mode = SPH_MATCH_EXTENDED;
        $this->sphinxsearch->set_array_result(true);
        $this->sphinxsearch->set_match_mode($mode);
        $this->sphinxsearch->set_connect_timeout(120);
        if ($limit)
            $this->sphinxsearch->set_limits((int) $offset, (int) $limit, ( $limit > 1000 ) ? $limit : 1000 );
        if (isset($params['certified']) && $params['certified'] != '')
            $this->sphinxsearch->set_filter("is_certified", array($params['certified']));
        if (isset($params['agreed']) && $params['agreed'] != '')
            $this->sphinxsearch->set_filter("is_agreed", array($params['agreed']));
        if (isset($params['start_date']) && $params['start_date'] != '' && isset($params['end_date']) && $params['end_date'] != '')
            $this->sphinxsearch->set_filter_range("start_date", strtotime($params['start_date']), strtotime($params['end_date']));

        $res = $this->sphinxsearch->query($params['search_kewords'], 'stations');


        $execution_time = $res['time'];
        if ($res)
        {
            $total_record = $res['total_found'];
            if ($total_record > 0)
            {
                if (isset($res['matches']))
                {
                    foreach ($res['matches'] as $record)
                    {
                        $listings[] = (object) array_merge(array('id' => $record['id']), $record['attrs']);
                    }
                }
            }
        }

        return array("total_count" => $total_record, "records" => $listings, "query_time" => $execution_time);
    }

    /*
     * Update Index Attribute Value
     * @Perm Name of index
     * @Perm Name of attribute
     * @Perm Value of attribute
     */

    public function update_indexes($index, $attr, $values)
    {
        $this->sphinxsearch->update_attributes($index, $attr, $values);
    }

    /*
     * Get All Stations
     */

    public function get_all_stations()
    {
        $res = $this->search_stations('', 0, 400);
        if ($res['total_count'] > 0)
        {
            return $res['records'];
        }
    }

    function instantiations_list($params, $offset = 0, $limit = 100)
    {
//        /usr/bin/indexer --all --rotate
        $instantiations=array();
        $total_record = 0;
        $this->sphinxsearch->reset_filters();
        $this->sphinxsearch->reset_group_by();
        //$where = $this->get_sphinx_search_condtion($params);
        $mode = SPH_MATCH_EXTENDED;
        $this->sphinxsearch->set_array_result(true);
        $this->sphinxsearch->set_match_mode($mode);
        $this->sphinxsearch->set_connect_timeout(120);
//        if ($limit)
//            $this->sphinxsearch->set_limits((int) $offset, (int) $limit, ( $limit > 1000 ) ? $limit : 1000 );


        $res = $this->sphinxsearch->query($params['search'], 'instantiations_list');


        $execution_time = $res['time'];
        if ($res)
        {
            $total_record = $res['total_found'];
            if ($total_record > 0)
            {
                if (isset($res['matches']))
                {
                    foreach ($res['matches'] as $record)
                    {
                        $instantiations[] = (object) array_merge(array('id' => $record['id']), $record['attrs']);
                    }
                }
            }
        }

        return array("total_count" => $total_record, "records" => $instantiations, "query_time" => $execution_time);
    }

}

?>
