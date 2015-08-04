<?php

/**
 * AMS Archive Management System
 * 
 * 
 * PHP version 5
 * 
 * @category   AMS
 * @package    CI
 * @subpackage Model
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @copyright  Copyright (c) WGBH (http://www.wgbh.org/). All Rights Reserved.
 * @license    http://www.gnu.org/licenses/gpl.txt GPLv3
 * @version    GIT: <$Id>
 * @link       https://github.com/avpreserve/AMS
 */

/**
 * Messages_Model Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Model
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @copyright  Copyright (c) WGBH (http://www.wgbh.org/). All Rights Reserved.
 * @license    http://www.gnu.org/licenses/gpl.txt GPLv3
 * @link       https://ams.americanarchive.org
 */
class Messages_Model extends CI_Model
{

	/**
	 * constructor. set table name amd prefix
	 * 
	 */
	function __construct()
	{
		parent::__construct();
		$this->_prefix = '';
		$this->_table = 'messages';
		$this->_table_users = 'users';
                $this->db->save_queries = FALSE;
	}

	/**
	 * Get Messages By Sender Id 
	 * 
	 * @return rows object 
	 */
	function get_msg_by_sender_id($sender_id)
	{
		$this->db->select("*");
		$this->db->from($this->_table);
		$this->db->where("sender_id", $sender_id);
		$res = $this->db->get();
		if (isset($res) && ! empty($res))
			return $res->result();
		return false;
	}

	/**
	 * Get Messages By Receiver Id 
	 * 
	 * @return rows object 
	 */
	function get_msg_by_receiver_id($receiver_id)
	{
		$this->db->select("*");
		$this->db->from($this->_table);
		$this->db->where("receiver_id", $receiver_id);
		$res = $this->db->get();
		if (isset($res) && ! empty($res))
			return $res->result();
		return false;
	}

	/**
	 * Add Email Message
	 * 
	 * @return insert id
	 */
	function add_msg($data)
	{
		$this->db->insert($this->_table, $data);
		return $this->db->insert_id();
	}

	/**
	 * update Message by id
	 * 
	 * @return bool
	 */
	function update_msg_by_id($msg_id, $data)
	{
		$this->db->where("id", $msg_id);
		$this->db->update($this->_table, $data);
		return $this->db->affected_rows() > 0;
	}

	/**
	 * Get Messages for inbox with filter array perm so key will be table field and value will be search text
	 * 
	 * @return rows object 
	 */
	function get_inbox_msgs($receiver_id, $where = '')
	{
		$this->db->select("{$this->_table}.*,stations.station_name, CONCAT(user_profile.first_name,' ',user_profile.last_name) AS from_name", FALSE);
		$this->db->select('CONCAT(usr.first_name," ",usr.last_name) AS to_name', FALSE);
		$this->db->join('user_profile', 'user_profile.user_id=' . $this->_table . '.sender_id');
		$this->db->join('user_profile as usr', 'usr.user_id=' . $this->_table . '.receiver_id');
		$this->db->join("stations", "stations.id=" . $this->_table . ".station_id");
		if ((int) $this->role_id !== 1)
			$this->db->where('receiver_id', $receiver_id);
		$this->db->where('receiver_folder', 'inbox');
		if ( ! empty($where))
		{
			foreach ($where as $key => $value)
			{
				$this->db->where($this->_table . '.' . $key, $value);
			}
		}
		$this->db->order_by('created_at', 'DESC');
		$result = $this->db->get($this->_table);
		if (isset($result) && ! empty($result))
			return $result->result();
		return TRUE;
	}

	/**
	 * Get Messages for sent with filter array perm so key will be table field and value will be search text
	 * 
	 * @return rows object 
	 */
	function get_sent_msgs($sender_id, $where = '')
	{
		$this->db->select("{$this->_table}.*,stations.station_name,email_queue.is_email_read,email_queue.read_at AS email_read_at", FALSE);
		$this->db->select("CONCAT(user_profile.first_name,' ',user_profile.last_name) AS from_name", FALSE);
		$this->db->select('CONCAT(usr.first_name," ",usr.last_name) AS to_name', FALSE);
		$this->db->from($this->_table);
		$this->db->join("stations", "stations.id=" . $this->_table . ".station_id");
		$this->db->join('user_profile', 'user_profile.user_id=' . $this->_table . '.sender_id');
		$this->db->join('user_profile as usr', 'usr.user_id=' . $this->_table . '.receiver_id');
		$this->db->join("email_queue", "email_queue.id=" . $this->_table . ".email_queue_id");
		$this->db->where("sender_id", $sender_id);
		$this->db->where("sender_folder", "sent");
		if ( ! empty($where))
		{
			foreach ($where as $key => $value)
			{
				$this->db->where($this->_table . "." . $key, $value);
			}
		}
		$this->db->order_by('created_at', 'DESC');
		$res = $this->db->get();
		if (isset($res) && ! empty($res))
			return $res->result();
		return false;
	}

	/**
	 * Get Unread Messages Count
	 * 
	 * @return rows object 
	 */
	function get_unread_msgs_count($receiver_id)
	{
		$this->db->select('COUNT(id) as total');
		$this->db->where('receiver_id', $receiver_id);
		$this->db->where('receiver_folder', 'inbox');
		$this->db->where('msg_status', 'unread');
		$this->db->group_by('receiver_id');
		$result = $this->db->get($this->_table);
		if (isset($result) && ! empty($result))
		{
			$count = $result->row();
			if (isset($count) && isset($count->total) && $count->total > 0)
				return $count->total;
		}
		return 0;
	}

	/**
	 * Get Messages By Receiver Id And id
	 * 
	 * @return rows object 
	 */
	function get_msg_by_receiverid_msgid($receiver_id, $id)
	{
		$this->db->select("*");
		$this->db->from($this->_table);
		$this->db->where("receiver_id", $id);
		$this->db->where("id", $receiver_id);
		$res = $this->db->get();
		if (isset($res) && ! empty($res))
			return $res->result();
		return false;
	}

	function get_station_admin($station_id)
	{
		$this->db->select('id,email');
		$this->db->where("station_id", $station_id);
		$this->db->where("role_id", 3); // role id 3 is station admin
		return $this->db->get($this->_table_users)->result();
	}

}
