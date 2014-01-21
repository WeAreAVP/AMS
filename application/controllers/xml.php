<?php

class Xml extends CI_Controller
{

	function __construct()
	{
		$this->layout = 'default.php';
	}

	function pbcore()
	{
		$xml = new SimpleXMLElement();
		$xml->addAttribute('version', "1.0");
		$xml->addAttribute('encoding', "UTF-8");
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

}
