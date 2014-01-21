<?php

class Xml extends CI_Controller
{

	function __construct()
	{
		$this->layout = 'default.php';
	}

	function pbcore()
	{
		$xml = new SimpleXMLElement('<xml/>');

		for ($i = 1; $i <= 8; ++ $i)
		{
			$track = $xml->addChild('track');
			$track->addChild('path', "song$i.mp3");
			$track->addChild('title', "Track $i - Track Title");
		}
		echo $xml->asXML();
	}

}
