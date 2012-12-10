<?php
class User_Settings extends CI_Model 
{
	function __construct()
	{
		parent::__construct();

		$this->_prefix = '';		
		$this->_table = $this->_prefix.'user_settings';
	}
	
	function insert_settings($data)
	{
		$this->db->insert($this->_table,$data);
		return $this->db->insert_id();
	}

	function get_setting($user_id,$table_type,$table_subtype='')
	{
		$this->db->where('user_id', $user_id);
		$this->db->where('table_type', $table_type);
		
		if(!empty($table_subtype) )
			$this->db->where('table_subtype', $table_subtype);
		$res=$this->db->get($this->_table);
		if(isset($res) && !empty($res))
			return $res->row();
		return false;
	}

	function delete_setting($id)
	{
		$this->db->where('id', $id);
		return $this->db->delete($this->_table);
	}
	
    function update_setting($user_id,$type,$data)
	{
		$this->db->where('user_id', $user_id);
		$this->db->where('table_type', $type);
        $this->db->update($this->_table, $data);
    }
}

?>