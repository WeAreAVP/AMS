<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of mediainfo_model
 *
 * @author rimsha
 */
class Mediainfo_Model extends CI_Model {

    function __Construct() {
        parent::__construct();
        $this->db->save_queries = FALSE;
    }

    function check_media($folder, $type) {
        $this->db->where('folder', $folder);
        $this->db->where('type', $type);
        return $this->db->get('media_info')->row();
    }

    function insert($data) {
        $this->db->insert('media_info', $data);
    }

//    function update_instant() {
//        $this->db->select('instantiations_id');
//        $this->db->like('instantiation_source', 'mediainfo');
//        $result = $this->db->get('instantiation_identifier')->result();
//        foreach ($result as $value) {
//            $array[] = $value->instantiations_id;
//        }
//        $this->update($array);
//    }
//
//    function update($array) {
//        $this->db->where_in('id', $array);
//        $this->db->update('instantiations', array('mediainfo_import' => 1));
//    }

    //put your code here
}
