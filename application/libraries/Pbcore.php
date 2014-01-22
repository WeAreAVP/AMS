<?php

class Pbcore
{
	
	private $CI;
	protected $xml = NULL;

	function __construct()
	{
		$this->CI = & get_instance();
		$this->xml = new SimpleXMLElement('<pbcoreDescriptionDocument/>');
		$attributes = array(
			'xmlns' => "http://www.pbcore.org/PBCore/PBCoreNamespace.html",
			'xmlns:xsi' => "http://www.w3.org/2001/XMLSchema-instance",
			'xsi:schemaLocation' => "http://www.pbcore.org/PBCore/PBCoreNamespace.html http://pbcore.org/xsd/pbcore-2.0.xsd");
		$this->add_attribute($this->xml, $attributes);
	}

	public function add_attribute($object, array $attributes)
	{
		foreach ($attributes as $attribute => $value)
		{
			$object->addAttribute($attribute, $value);
		}
	}

}

?>