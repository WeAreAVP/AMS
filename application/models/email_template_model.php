<?php

/**
 * station Model.
 *
 * @package    AMS
 * @subpackage email_template_model
 * @author     Ali Raza
 */
class Email_Template_Model extends CI_Model {

    /**
     * constructor. set table name amd prefix
     * 
     */
    function __construct() {
        parent::__construct();
        $this->_prefix = '';
        $this->_table = 'email_templates';
				$this->_email_queue_table='email_queue';
    }

    /**
     * Get Template by System Id
     * 
     * @return row object 
     */
		function get_template_by_sys_id($system_id)
		{
			$this->db->select("*");
			$this->db->from($this->_table);
			$this->db->where("system_id",$system_id);
			$res=$this->db->get();
			if(isset($res) && !empty($res))
				return $res->row();
			return false;
		}
		
    /**
     * Add Email Template
     * 
     * @return insert id
     */
		function add_email_template($data)
		{
			$this->db->insert($this->_table,$data);
			return $this->db->insert_id();
		}
		 /**
     * update Email Template
     * 
     * @return bool
     */
		function update_email_template($template_id,$data)
		{
			$this->db->where("id",$template_id);
			$this->db->update($this->_table,$data);
			return $this->db->affected_rows() > 0;
		}
		/**
     *Get Template by id
     * 
     * @return insert id
    */
		function get_template_by_id($id)
		{
			$this->db->select("*");
			$this->db->from($this->_table);
			$this->db->where("id",$id);
			$res=$this->db->get();
			if(isset($res) && !empty($res))
			{
				return $res->row();
			}
			return false;
		}
		/**
     * Get list of all the stations
     * 
     * @return object
     */
    function get_all()
		{
      $res = $this->db->query("SELECT * FROM ".$this->_table);
			if($res)
			{
			 	return $res->result();
			}
			return false;
    }
		/**
     * Delete Template
     * 
     * @return object
     */
		function delete_template($template_id)
		{
    	$this->db->where('id', $template_id);
      $this->db->delete($this->_table);
      return $this->db->affected_rows() > 0;
    }
		/**
     * Add Email Queue
     * 
     * @return insert id
     */
		function add_email_queue($data)
		{
			$this->db->insert($this->_email_queue_table,$data);
			return $this->db->insert_id();
		}
}