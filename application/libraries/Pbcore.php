<?php

class Pbcore
{

	private $CI;
	public $xml = NULL;
	public $asset_id = NULL;

	function __construct()
	{
		$this->CI = & get_instance();
		$this->CI->load->model('pbcore_model');
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
		$array = array('issue' => 'Fahad', 'issue' => 'Nouman');

		$pbcore_model = $this->CI->pbcore_model;

		// Identifier Start
		$identifiers = $pbcore_model->get_by($pbcore_model->table_identifers, array('assets_id' => $this->asset_id));
		foreach ($identifiers as $identifer)
		{
			$attributes = array();
			$xml_object = $this->_add_child($this->xml, 'pbcoreIdentifier', $identifer->identifier);
			if ( ! empty($identifer->identifier_source))
				$attributes['source'] = $identifer->identifier_source;
			if ( ! empty($identifer->identifier_ref))
				$attributes['ref'] = $identifer->identifier_ref;
			$this->_add_attribute($xml_object, $attributes);
			unset($xml_object);
		}
		// Identifier End
		// Asset Type Start
		$asset_types = $pbcore_model->get_asset_type($this->asset_id);
		foreach ($asset_types as $asset_type)
		{
			$attributes = array();
			$xml_object = $this->_add_child($this->xml, 'pbcoreAssetType', $asset_type->asset_type);
		}
		// Asset Type End
		// Asset Date Start
		$asset_dates = $pbcore_model->get_asset_date($this->asset_id);
		foreach ($asset_dates as $asset_date)
		{
			$attributes = array();
			$xml_object = $this->_add_child($this->xml, 'pbcoreAssetDate', $asset_date->asset_date);
			if ( ! empty($identifer->date_type))
				$attributes['dateType'] = $asset_date->date_type;
			$this->_add_attribute($xml_object, $attributes);
			unset($xml_object);
		}
		// Asset Date End
		// Asset Title Start
		$asset_titles = $pbcore_model->get_asset_title($this->asset_id);

		foreach ($asset_titles as $asset_title)
		{
			$attributes = array();
			$xml_object = $this->_add_child($this->xml, 'pbcoreTitle', $asset_title->title);
			if ( ! empty($asset_title->title_source))
				$attributes['source'] = $asset_title->title_source;
			if ( ! empty($asset_title->title_ref))
				$attributes['ref'] = $asset_title->title_ref;
			if ( ! empty($asset_title->title_type))
				$attributes['titleType'] = $asset_title->title_type;
			$this->_add_attribute($xml_object, $attributes);
			unset($xml_object);
		}
		// Asset Title End
		// Asset Subject  Start
		$asset_subjects = $pbcore_model->get_asset_subject($this->asset_id);

		foreach ($asset_subjects as $asset_subject)
		{
			$attributes = array();
			$xml_object = $this->_add_child($this->xml, 'pbcoreSubject', $asset_subject->title);
			if ( ! empty($asset_subject->subject_source))
				$attributes['source'] = $asset_subject->subject_source;
			if ( ! empty($asset_subject->subject_ref))
				$attributes['ref'] = $asset_subject->subject_ref;
			if ( ! empty($asset_subject->subject_type))
				$attributes['subjectType'] = $asset_subject->subject_type;
			$this->_add_attribute($xml_object, $attributes);
			unset($xml_object);
		}
		// Asset Subject End
		// Asset Description  Start
		$asset_descriptions = $pbcore_model->get_asset_description($this->asset_id);

		foreach ($asset_descriptions as $asset_description)
		{
			$attributes = array();
			$xml_object = $this->_add_child($this->xml, 'pbcoreDescription', $asset_description->description);
			if ( ! empty($asset_description->description_type))
				$attributes['descriptionType'] = $asset_description->description_type;
			$this->_add_attribute($xml_object, $attributes);
			unset($xml_object);
		}
		// Asset Description End
		// Asset Genre  Start
		$asset_genres = $pbcore_model->get_asset_genre($this->asset_id);

		foreach ($asset_genres as $asset_genre)
		{
			$attributes = array();
			$xml_object = $this->_add_child($this->xml, 'pbcoreGenre', $asset_genre->genre);
			if ( ! empty($asset_genre->genre_source))
				$attributes['source'] = $asset_genre->genre_source;
			if ( ! empty($asset_genre->genre_ref))
				$attributes['ref'] = $asset_genre->genre_ref;
			$this->_add_attribute($xml_object, $attributes);
			unset($xml_object);
		}
		// Asset Genre End
		// Asset Coverage  Start
		$asset_coverages = $pbcore_model->get_by($pbcore_model->table_coverages, array('assets_id' => $this->asset_id));
		foreach ($asset_coverages as $asset_coverage)
		{
			$xml_object = $this->_add_child($this->xml, 'pbcoreCoverage');
			$this->_add_child($xml_object, 'coverage', $asset_coverage->coverage);
			$this->_add_child($xml_object, 'coverageType', $asset_coverage->coverage_type);
		}
		// Asset Coverage  End
		// Asset Audience Level  Start
		$asset_audiences_level = $pbcore_model->get_asset_audience_level($this->asset_id);

		foreach ($asset_audiences_level as $asset_audience_level)
		{
			$attributes = array();
			$xml_object = $this->_add_child($this->xml, 'pbcoreAudienceLevel', $asset_audience_level->audience_level);
			if ( ! empty($asset_audience_level->audience_level_source))
				$attributes['source'] = $asset_audience_level->audience_level_source;
			if ( ! empty($asset_audience_level->audience_level_ref))
				$attributes['ref'] = $asset_audience_level->audience_level_ref;
			$this->_add_attribute($xml_object, $attributes);
			unset($xml_object);
		}
		// Asset Audience Level  End
		// Asset Audience Level  Start
		$asset_audiences_rating = $pbcore_model->get_asset_audience_rating($this->asset_id);

		foreach ($asset_audiences_rating as $asset_audience_rating)
		{
			$attributes = array();
			$xml_object = $this->_add_child($this->xml, 'pbcoreAudienceRating', $asset_audience_rating->audience_rating);
			if ( ! empty($asset_audience_rating->audience_rating_source))
				$attributes['source'] = $asset_audience_rating->audience_rating_source;
			if ( ! empty($asset_audience_rating->audience_rating_ref))
				$attributes['ref'] = $asset_audience_rating->audience_rating_ref;
			$this->_add_attribute($xml_object, $attributes);
			unset($xml_object);
		}
		// Asset Audience Level  End
		// Asset Annotation  Start
		$asset_annotations = $pbcore_model->get_by($pbcore_model->table_annotations, array('assets_id' => $this->asset_id));
		foreach ($asset_annotations as $asset_annotation)
		{
			$attributes = array();
			$xml_object = $this->_add_child($this->xml, 'pbcoreAnnotation', $asset_annotation->annotation);
			if ( ! empty($asset_annotation->annotation_type))
				$attributes['annotationType'] = $asset_annotation->annotation_type;
			if ( ! empty($asset_annotation->annotation_ref))
				$attributes['ref'] = $asset_annotation->annotation_ref;
			$this->_add_attribute($xml_object, $attributes);
			unset($xml_object);
		}
		// Asset Annotation End
		// Asset Relations  Start
		$asset_relations = $pbcore_model->get_asset_relation($this->asset_id);
		foreach ($asset_relations as $asset_relation)
		{
			$attributes = array();
			$xml_object = $this->_add_child($this->xml, 'pbcoreRelation');
			$this->_add_child($xml_object, 'pbcoreRelationIdentifier', $asset_relation->relation_identifier);
			if ( ! empty($asset_relation->relation_type))
			{
				$xml_object = $this->_add_child($xml_object, 'pbcorerelationtype', $asset_relation->relation_type);
				if ( ! empty($asset_relation->relation_type_source))
					$attributes['source'] = $asset_relation->relation_type_source;
				if ( ! empty($asset_relation->relation_type_ref))
					$attributes['ref'] = $asset_relation->relation_type_ref;
				$this->_add_attribute($xml_object, $attributes);
			}


			unset($xml_object);
		}
		// Asset Relations End
		// Asset Creator and Role  Start
		$asset_creators = $pbcore_model->get_asset_creator_and_role(4848352);

		foreach ($asset_creators as $asset_creator)
		{
			$attributes = array();
			$xml_object = $this->_add_child($this->xml, 'pbcoreCreator');
			if ( ! empty($asset_creator->creator_name))
			{
				$xml_creator_object = $this->_add_child($xml_object, 'creator', $asset_creator->creator_name);
				if ( ! empty($asset_annotation->creator_affiliation))
					$attributes['affiliation'] = $asset_annotation->creator_affiliation;
				if ( ! empty($asset_annotation->creator_source))
					$attributes['source'] = $asset_annotation->creator_source;
				if ( ! empty($asset_annotation->creator_ref))
					$attributes['ref'] = $asset_annotation->creator_ref;
				$this->_add_attribute($xml_creator_object, $attributes);
			}
			if ( ! empty($asset_creator->creator_role))
			{
				$xml_creator_role_object = $this->_add_child($xml_object, 'creatorRole', $asset_creator->creator_role);
				if ( ! empty($asset_annotation->creator_role_source))
					$attributes['source'] = $asset_annotation->creator_role_source;
				if ( ! empty($asset_annotation->creator_role_ref))
					$attributes['ref'] = $asset_annotation->creator_role_ref;
				$this->_add_attribute($xml_creator_role_object, $attributes);
			}



			unset($xml_object);
		}
		// Asset Relations End
//		debug($identifiers);
	}

	private function _add_child($object, $tag_name, $value = NULL)
	{
		$object = $object->addChild($tag_name, htmlentities($value));
		return $object;
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
			$object->addAttribute($attribute, htmlentities($value));
		}
		return $object;
	}

}

?>