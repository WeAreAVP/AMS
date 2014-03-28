<?php

/**
 * AMS Archive Management System
 * 
 * 
 * PHP version 5
 * 
 * @category   AMS
 * @package    PHP
 * @subpackage pgconnect
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @copyright  Copyright (c) WGBH (http://www.wgbh.org/). All Rights Reserved.
 * @license    http://www.gnu.org/licenses/gpl.txt GPLv3
 * @version    GIT: <$Id>
 * @link       https://github.com/avpreserve/AMS
 */
$dbconnection = pg_connect("host=localhost port=5432 dbname=mint user=mint password=mint");
if ($dbconnection)
{
	if (isset($_REQUEST['mint_id']))
	{
		if ($_REQUEST['mint_id'] == '')
		{
			$p_query = pg_query($dbconnection, "SELECT nextval('seq_users_id')");
			$user_id = pg_fetch_row($p_query);
			$username = explode('@', $_REQUEST['username']);
			$random = rand(1, 99);
			$final_username = $username[0] . '_' . $random;
			$data = array(
				'login' => $final_username,
				'first_name' => $_REQUEST['first_name'],
				'last_name' => $_REQUEST['last_name'],
				'email' => $_REQUEST['username'],
				'md5_password' => md5($final_username . 'x0h0@123'),
				'organization_id' => 1,
				'account_created' => date('Y-m-d'),
				'active_account' => 't',
				'rights' => $_REQUEST['rights'],
				'users_id' => $user_id[0]
			);
			$result = pg_insert($dbconnection, 'users', $data);
			if ($result)
			{
				$email = $_REQUEST['username'];
				$p_query = pg_query($dbconnection, "SELECT * FROM users WHERE email = '$email'");
				if ($p_query)
				{
					$user_info = pg_fetch_row($p_query);
					$response = json_encode(array("success" => 'true', 'error' => '', 'result' => $user_info, 'user_id' => $_REQUEST['user_id']));
					echo $_GET['callback'] . '(' . $response . ')';
					exit;
				}
			}
			else
			{
				$response = json_encode(array("success" => 'false', 'error' => 'something went wrong while inserting.'));
				echo $_GET['callback'] . '(' . $response . ')';
				exit;
			}
		}
		else
		{
			$user_id = $_REQUEST['mint_id'];
			$result = pg_query($dbconnection, "SELECT * FROM users WHERE users_id = $user_id");
			if ($result)
			{
				$user_info = pg_fetch_row($result);
				$response = json_encode(array("success" => 'true', 'error' => '', 'result' => $user_info, 'user_id' => $_REQUEST['user_id']));
				echo $_GET['callback'] . '(' . $response . ')';
				exit;
			}
		}
	}
}
else
{
	$response = json_encode(array("success" => 'false', 'error' => 'Connection with pg failed.'));
	echo $_GET['callback'] . '(' . $response . ')';
	exit;
}
?>
