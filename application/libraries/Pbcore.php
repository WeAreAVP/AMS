<?php

class Pbcore
{

	private $CI;
	protected $xml = NULL;
	public $asset_id = NULL;

	function __construct()
	{
		$this->CI = & get_instance();
		$this->load->model('pbcore_model');
		$this->xml = new SimpleXMLElement('<pbcoreDescriptionDocument/>');
		$attributes = array(
			'xmlns' => "http://www.pbcore.org/PBCore/PBCoreNamespace.html",
			'xmlns:xsi' => "http://www.w3.org/2001/XMLSchema-instance",
			'xmlns:premis' => "info:lc/xmlns/premis-v2",
			'xsi:schemaLocation' => "http://www.pbcore.org/PBCore/PBCoreNamespace.html http://www.pbcore.org/xsd/pbcore-2.0.xsd
									info:lc/xmlns/premis-v2 http://www.loc.gov/standards/premis/v2/premis.xsd");
		$this->_add_attribute($this->xml, $attributes);
	}

	public function make_xml()
	{
		if ($this->asset_id !== NULL)
		{
			$this->_fetch_asset();
		}
	}

	private function _fetch_asset()
	{
		$pbcore_model = $this->CI->pbcore_model;
		$identifiers = $pbcore_model->get_by($pbcore_model->table_identifers, array('assets_id' => $this->asset_id));
		debug($identifiers);
	}

	/**
	 * Add attributes to xml tag.
	 * 
	 * @param type $object
	 * @param array $attributes
	 */
	private function _add_attribute($object, array $attributes)
	{
		foreach ($attributes as $attribute => $value)
		{
			$object->addAttribute($attribute, $value);
		}
	}

}

?>