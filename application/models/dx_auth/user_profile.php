<?php

class User_Profile extends CI_Model
{

	function __construct()
	{
		parent::__construct();

		$this->_prefix = $this->config->item('DX_table_prefix');
		$this->_table = $this->_prefix . $this->config->item('DX_user_profile_table');
	}

	function create_profile($user_id)
	{
		$this->db->set('user_id', $user_id);
		return $this->db->insert($this->_table);
	}

	function get_profile_field($user_id, $fields)
	{
		$this->db->select($fields);
		$this->db->where('user_id', $user_id);
		return $this->db->get($this->_table);
	}

	function get_profile($user_id)
	{
		$this->db->where('user_id', $user_id);
		return $this->db->get($this->_table);
	}

	function get_profile_by_mint_id($mint_user_id)
	{
		$this->db->where('mint_user_id', $mint_user_id);
		$result = $this->db->get($this->_table);
		if (isset($result) && ! empty($result))
			return $result->row();
		return FALSE;
	}

	function set_profile($user_id, $data)
	{
		$this->db->where('user_id', $user_id);
		return $this->db->update($this->_table, $data);
	}

	function delete_profile($user_id)
	{
		$this->db->where('user_id', $user_id);
		return $this->db->delete($this->_table);
	}

	function insert_profile($data)
	{
		$this->db->insert($this->_table, $data);
		return $this->db->insert_id();
	}

}

?>