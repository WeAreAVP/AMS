<?php

/**
 * AMS Archive Management System
 * 
 * 
 * PHP version 5
 * 
 * @category   AMS
 * @package    CI
 * @subpackage Library
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @copyright  Copyright (c) WGBH (http://www.wgbh.org/). All Rights Reserved.
 * @license    http://www.gnu.org/licenses/gpl.txt GPLv3
 * @version    GIT: <$Id>
 * @link       https://github.com/avpreserve/AMS
 */

/**
 * Googlerefine Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Library
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @copyright  Copyright (c) WGBH (http://www.wgbh.org/). All Rights Reserved.
 * @license    http://www.gnu.org/licenses/gpl.txt GPLv3
 * @link       https://ams.americanarchive.org
 */
class Googlerefine
{

	var $server = '';
	var $project_id = FALSE;

	function __construct()
	{
		$CI = & get_instance();
		$this->server = $CI->config->item('google_refine_url');
	}
	/**
	 * Create Project on open refine.
	 * 
	 * @param string $project_name
	 * @param string $file_path
	 * @return boolean
	 */
	function create_project($project_name, $file_path)
	{
		$data = NULL;
		$uri = $this->server . '/command/core/create-project-from-upload';
		myLog("URL=> {$uri}");
		myLog("File Path=> {$file_path}");
		$post_field = array('project-file' => "@$file_path", 'project-name' => $project_name);
		$response = $this->send_curl_request($uri, $post_field);

		/* Checking the google refine url */
		$pattern = '`.*?((http)://[\w#$&+,\/:;=?@.-]+)[^\w#$&+,\/:;=?@.-]*?`i'; //this regexp finds your url
		if (preg_match_all($pattern, $response, $matches))
			$project_url = $matches[1][0]; //project ID URL
		if (isset($project_url))
		{
			$data['project_url'] = $project_url;
			$explode_url = explode('project=', $project_url);
			$data['project_id'] = $explode_url[1];
			return $data;
		}
		else
		{
			return FALSE;
		}
	}
	/**
	 * Send Curl url 
	 * 
	 * @param string $url
	 * @param array $postFields
	 * @param string $headers
	 * @return string
	 */
	function send_curl_request($url, $postFields = null, $headers = 1)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);

		if ($postFields != null)
		{
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
		}
		curl_setopt($ch, CURLOPT_HEADER, $headers);

		$page = curl_exec($ch);
		$response = curl_getinfo($ch);

		return $page;
	}
	/**
	 * Prepare POST fields for curl call.
	 * 
	 * @param array $array
	 * @return string
	 */
	function prepare_post_fields($array)
	{
		$params = array();

		foreach ($array as $key => $value)
		{
			$params[] = $key . '=' . urlencode($value);
		}

		return implode('&', $params);
	}
	/**
	 * Export rows from open refine for AMS.
	 * 
	 * @param string $project_name
	 * @param integer $project_id
	 * @param string $format
	 * @return type
	 */
	function export_rows($project_name, $project_id, $format = 'tsv')
	{


//        $uri = $this->server . '/command/core/export-rows/' . $project_name . '.' . $format;
		$uri = $this->server . '/command/core/export-rows/' . $project_name;
		$post_field = array('engine' => '{"facets":[],"mode":"row-based"}', 'project' => $project_id, 'format' => $format, "contentType" => "application-unknown");
		$post_field = $this->prepare_post_fields($post_field);
		$response = $this->send_curl_request($uri, $post_field, 0);
		return $response;
	}
	/**
	 * Delete project from open refine.
	 * 
	 * @param integer $project_id
	 * @return boolean
	 */
	function delete_project($project_id)
	{
		$uri = $this->server . '/command/core/delete-project';
		$post_field = array('project' => $project_id);
		$post_field = $this->prepare_post_fields($post_field);
		$response = $this->send_curl_request($uri, $post_field);
		return TRUE;
	}

}
