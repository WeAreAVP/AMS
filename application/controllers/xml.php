<?php

class Xml extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->layout = 'default.php';
		$this->load->library('pbcore');
		$this->load->model('pbcore_model');
	}

	function pbcore()
	{
		$guid = $this->uri->segment(3, 0);
		if ($guid !== 0)
		{

			$result = $this->pbcore_model->get_one_by($this->pbcore_model->_identifiers_table, array('identifier' =>  "cpb-aacip/{$guid}"));
			if ($result)
			{
				debug($result);
				for ($i = 1; $i <= 8; ++ $i)
				{
					$track = $xml->addChild('track');
					$track->addAttribute('source', 'Test');
					$track->addChild('path', "song$i.mp3");
					$track->addChild('title', "Track $i - Track Title");
				}

				Header('Content-type: text/xml');
				echo $xml->asXML();
				exit;
			}
			else
			{
				show_error('Invalid GUID.');
			}
		}
		else
		{
			show_error('GUID is required.');
		}
	}

}
