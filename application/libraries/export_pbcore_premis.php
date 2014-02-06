<?php

class Export_pbcore_premis
{

	private $CI;
	public $xml = NULL;
	public $asset_id = NULL;
	public $is_parent_collection = FALSE;
	public $is_pbcore_export = TRUE;

	function __construct()
	{
		$this->CI = & get_instance();
		$this->CI->load->model('pbcore_model');
	}

	function make_collection_xml($records)
	{
		if ($this->is_pbcore_export)
		{

			$this->xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><xsi:pbcoreCollection></xsi:pbcoreCollection>', LIBXML_NOERROR, false, 'xsi', true);
			foreach ($records as $_key => $asset)
			{

				$document_object = $this->_add_child($this->xml, 'pbcoreDescriptionDocument');
				$attributes = array(
					'xsi:xmlns' => "http://www.w3.org/2001/XMLSchema-instance",
					'xsi:schemaLocation' => "http://www.pbcore.org/PBCore/PBCoreNamespace.html http://www.pbcore.org/xsd/pbcore-2.0.xsd");
				$this->_add_attribute($document_object, array('xmlns' => "http://www.pbcore.org/PBCore/PBCoreNamespace.html"));
				$this->_add_attribute($document_object, $attributes, 'xsi');
				$this->asset_id = $asset->id;
				$this->_fetch_asset($document_object);
			}
			return TRUE;
		}
		else
		{
			$this->xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><premisCollection></premisCollection>');
			foreach ($records as $asset)
			{
				$premis_object = $this->_add_child($this->xml, 'premis');
				$attributes = array(
					'xmlns:premis' => "info:lc/xmlns/premis-v2",
					'xmlns:xsi' => "http://www.w3.org/2001/XMLSchema-instance",
					'xsi:schemaLocation' => "info:lc/xmlns/premis-v2 http://www.loc.gov/standards/premis/v2/premis.xsd",
					'version' => "2.0");
				$this->_add_attribute($premis_object, $attributes);
				$this->asset_id = $asset->id;
				$result = $this->_fetch_events($premis_object);
				if ( ! $result)
					unset($premis_object);
			}
			return TRUE;
		}
	}

	/**
	 * Make pbcore or premis file depend on the parameters.
	 * 
	 * @return boolean
	 */
	public function make_xml()
	{
		if ($this->asset_id !== NULL)
		{
			if ($this->is_pbcore_export)
			{
				$this->xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><xsi:pbcoreDescriptionDocument></xsi:pbcoreDescriptionDocument>', LIBXML_NOERROR, false, 'xsi', true);
				$attributes = array(
					'xsi:xmlns' => "http://www.pbcore.org/PBCore/PBCoreNamespace.html",
					'xsi:xmlns:xsi' => "http://www.w3.org/2001/XMLSchema-instance",
					'xsi:xsi:schemaLocation' => "http://www.pbcore.org/PBCore/PBCoreNamespace.html http://www.pbcore.org/xsd/pbcore-2.0.xsd");
				$this->_add_attribute($this->xml, $attributes);
				$this->_fetch_asset($this->xml);
				return TRUE;
			}
			else
			{
				$this->xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><premis></premis>');
				$attributes = array(
					'xmlns:premis' => "info:lc/xmlns/premis-v2",
					'xmlns:xsi' => "http://www.w3.org/2001/XMLSchema-instance",
					'xsi:schemaLocation' => "info:lc/xmlns/premis-v2 http://www.loc.gov/standards/premis/v2/premis.xsd",
					'version' => "2.0");
				$this->_add_attribute($this->xml, $attributes);
				return $this->_fetch_events($this->xml);
			}
		}
	}

	/**
	 * Fetch events information from database.
	 * 
	 * @param \SimpleXMLElement $xml_object
	 * @return boolean
	 */
	private function _fetch_events($xml_object)
	{
		$pbcore_model = $this->CI->pbcore_model;
		$events = $pbcore_model->get_instantiation_events($this->asset_id);
		if (count($events) > 0)
		{
			$guid = $pbcore_model->get_one_by($this->CI->pbcore_model->table_identifers, array('assets_id' => $this->asset_id, 'identifier_source' => 'http://americanarchiveinventory.org'));
			$object_guid = $this->_add_child($xml_object, 'object');
			$object_identifier = $this->_add_child($object_guid, 'objectIdentifier');
			$this->_add_child($object_identifier, 'objectIdentifierType', 'American Archive GUID');
			$this->_add_child($object_identifier, 'objectIdentifierValue', $guid->identifier);
			foreach ($events as $event)
			{
				$event_object = $this->_add_child($xml_object, 'event');
				$event_identifier_object = $this->_add_child($event_object, 'eventIdentifier');
				$this->_add_child($event_identifier_object, 'eventIdentifierType', 'AMS event ID');
				$this->_add_child($event_identifier_object, 'eventIdentifierValue', $event->id);
				if ( ! empty($event->event_type))
					$this->_add_child($event_object, 'eventType', $event->event_type);
				if ( ! empty($event->event_date))
					$this->_add_child($event_object, 'eventDateTime', $event->event_date);
				if ( ! empty($event->event_note))
					$this->_add_child($event_object, 'eventDetail', $event->event_note);
				$linking_object = $this->_add_child($event_object, 'linkingObjectIdentifier');
				$this->_add_child($linking_object, 'linkingObjectIdentifierType', 'American Archive GUID');


				$this->_add_child($linking_object, 'linkingObjectIdentifierValue', $guid->identifier);
			}
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Fetch Assets information from system.
	 * 
	 * @param \SimpleXMLElement $asset_xml_object
	 * 
	 * @return void
	 */
	private function _fetch_asset($asset_xml_object)
	{
		$pbcore_model = $this->CI->pbcore_model;
		// Asset Type Start
		$asset_types = $pbcore_model->get_asset_type($this->asset_id);
		foreach ($asset_types as $asset_type)
		{
			$attributes = array();
			$xml_object = $this->_add_child($asset_xml_object, 'pbcoreAssetType', $asset_type->asset_type);
		}
		// Asset Type End
		// Asset Date Start
		$asset_dates = $pbcore_model->get_asset_date($this->asset_id);
		foreach ($asset_dates as $asset_date)
		{
			$attributes = array();
			$xml_object = $this->_add_child($asset_xml_object, 'pbcoreAssetDate', $asset_date->asset_date);
			if ( ! empty($asset_date->date_type))
				$attributes['dateType'] = $asset_date->date_type;
			$this->_add_attribute($xml_object, $attributes);
			unset($xml_object);
		}
		// Asset Date End
		// Identifier Start
		$identifiers = $pbcore_model->get_by($pbcore_model->table_identifers, array('assets_id' => $this->asset_id));
		foreach ($identifiers as $identifer)
		{
			$attributes = array();
			$xml_object = $this->_add_child($asset_xml_object, 'pbcoreIdentifier', $identifer->identifier);
			if ( ! empty($identifer->identifier_source))
				$attributes['source'] = $identifer->identifier_source;
			if ( ! empty($identifer->identifier_ref))
				$attributes['ref'] = $identifer->identifier_ref;
			$this->_add_attribute($xml_object, $attributes);
			unset($xml_object);
		}
		// Identifier End
		// Asset Title Start
		$asset_titles = $pbcore_model->get_asset_title($this->asset_id);

		foreach ($asset_titles as $asset_title)
		{
			$attributes = array();
			$xml_object = $this->_add_child($asset_xml_object, 'pbcoreTitle', $asset_title->title);
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
			$xml_object = $this->_add_child($asset_xml_object, 'pbcoreSubject', $asset_subject->subject);
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
			$xml_object = $this->_add_child($asset_xml_object, 'pbcoreDescription', $asset_description->description);
			if ( ! empty($asset_description->description_type))
				$attributes['descriptionType'] = $asset_description->description_type;
			$this->_add_attribute($xml_object, $attributes);
			unset($xml_object);
		}
		if (count($asset_descriptions) <= 0)
		{
			$xml_object = $this->_add_child($asset_xml_object, 'pbcoreDescription', 'No description available');
		}
		// Asset Description End
		// Asset Genre  Start
		$asset_genres = $pbcore_model->get_asset_genre($this->asset_id);

		foreach ($asset_genres as $asset_genre)
		{
			$attributes = array();
			$xml_object = $this->_add_child($asset_xml_object, 'pbcoreGenre', $asset_genre->genre);
			if ( ! empty($asset_genre->genre_source))
				$attributes['source'] = $asset_genre->genre_source;
			if ( ! empty($asset_genre->genre_ref))
				$attributes['ref'] = $asset_genre->genre_ref;
			$this->_add_attribute($xml_object, $attributes);
			unset($xml_object);
		}
		// Asset Genre End
		// Asset Relations  Start
		$asset_relations = $pbcore_model->get_asset_relation($this->asset_id);
		foreach ($asset_relations as $asset_relation)
		{
			$attributes = array();
			$xml_object = $this->_add_child($asset_xml_object, 'pbcoreRelation');
			$this->_add_child($xml_object, 'pbcoreRelationIdentifier', $asset_relation->relation_identifier);
			if ( ! empty($asset_relation->relation_type))
			{
				$xml_object = $this->_add_child($xml_object, 'pbcoreRelationType', $asset_relation->relation_type);
				if ( ! empty($asset_relation->relation_type_source))
					$attributes['source'] = $asset_relation->relation_type_source;
				if ( ! empty($asset_relation->relation_type_ref))
					$attributes['ref'] = $asset_relation->relation_type_ref;
				$this->_add_attribute($xml_object, $attributes);
				unset($attributes);
			}
			unset($xml_object);
		}
		// Asset Relations End
		// Asset Coverage  Start
		$asset_coverages = $pbcore_model->get_by($pbcore_model->table_coverages, array('assets_id' => $this->asset_id));
		foreach ($asset_coverages as $asset_coverage)
		{
			$xml_object = $this->_add_child($asset_xml_object, 'pbcoreCoverage');
			$this->_add_child($xml_object, 'coverage', $asset_coverage->coverage);
			$this->_add_child($xml_object, 'coverageType', $asset_coverage->coverage_type);
		}
		// Asset Coverage  End
		// Asset Audience Level  Start
		$asset_audiences_level = $pbcore_model->get_asset_audience_level($this->asset_id);

		foreach ($asset_audiences_level as $asset_audience_level)
		{
			$attributes = array();
			$xml_object = $this->_add_child($asset_xml_object, 'pbcoreAudienceLevel', $asset_audience_level->audience_level);
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
			$xml_object = $this->_add_child($asset_xml_object, 'pbcoreAudienceRating', $asset_audience_rating->audience_rating);
			if ( ! empty($asset_audience_rating->audience_rating_source))
				$attributes['source'] = $asset_audience_rating->audience_rating_source;
			if ( ! empty($asset_audience_rating->audience_rating_ref))
				$attributes['ref'] = $asset_audience_rating->audience_rating_ref;
			$this->_add_attribute($xml_object, $attributes);
			unset($xml_object);
		}
		// Asset Audience Level  End
		// Asset Creator and Role  Start
		$asset_creators = $pbcore_model->get_asset_creator_and_role($this->asset_id);

		foreach ($asset_creators as $asset_creator)
		{
			$attributes = array();
			$xml_object = $this->_add_child($asset_xml_object, 'pbcoreCreator');
			if ( ! empty($asset_creator->creator_name))
			{
				$xml_creator_object = $this->_add_child($xml_object, 'creator', $asset_creator->creator_name);
				if ( ! empty($asset_creator->creator_affiliation))
					$attributes['affiliation'] = $asset_creator->creator_affiliation;
				if ( ! empty($asset_creator->creator_source))
					$attributes['source'] = $asset_creator->creator_source;
				if ( ! empty($asset_creator->creator_ref))
					$attributes['ref'] = $asset_creator->creator_ref;
				$this->_add_attribute($xml_creator_object, $attributes);
				unset($attributes);
			}
			if ( ! empty($asset_creator->creator_role))
			{
				$attributes = array();
				$xml_creator_role_object = $this->_add_child($xml_object, 'creatorRole', $asset_creator->creator_role);
				if ( ! empty($asset_creator->creator_role_source))
					$attributes['source'] = $asset_creator->creator_role_source;
				if ( ! empty($asset_creator->creator_role_ref))
					$attributes['ref'] = $asset_creator->creator_role_ref;
				$this->_add_attribute($xml_creator_role_object, $attributes);
				unset($attributes);
			}



			unset($xml_object);
		}
		//  Asset Creator and Role  End
		// Asset Contributor and Role  Start
		$asset_contributors = $pbcore_model->get_asset_contributor_and_role($this->asset_id);

		foreach ($asset_contributors as $asset_contributor)
		{
			$attributes = array();
			$xml_object = $this->_add_child($asset_xml_object, 'pbcoreContributor');
			if ( ! empty($asset_contributor->contributor_name))
			{
				$xml_contributor_object = $this->_add_child($xml_object, 'contributor', $asset_contributor->contributor_name);
				if ( ! empty($asset_contributor->contributor_affiliation))
					$attributes['affiliation'] = $asset_contributor->contributor_affiliation;
				if ( ! empty($asset_contributor->contributor_source))
					$attributes['source'] = $asset_contributor->contributor_source;
				if ( ! empty($asset_contributor->contributor_ref))
					$attributes['ref'] = $asset_contributor->contributor_ref;
				$this->_add_attribute($xml_contributor_object, $attributes);
				unset($attributes);
			}
			if ( ! empty($asset_contributor->contributor_role))
			{
				$attributes = array();
				$xml_contributor_role_object = $this->_add_child($xml_object, 'contributorRole', $asset_contributor->contributor_role);
				if ( ! empty($asset_contributor->contributor_role_source))
					$attributes['source'] = $asset_contributor->contributor_role_source;
				if ( ! empty($asset_contributor->contributor_role_ref))
					$attributes['ref'] = $asset_contributor->contributor_role_ref;
				$this->_add_attribute($xml_contributor_role_object, $attributes);
				unset($attributes);
			}
			unset($xml_object);
		}
		//  Asset Contributor and Role  End
		// Asset Publisher and Role  Start
		$asset_publishers = $pbcore_model->get_asset_publisher_and_role($this->asset_id);

		foreach ($asset_publishers as $asset_publisher)
		{
			$attributes = array();
			$xml_object = $this->_add_child($asset_xml_object, 'pbcorePublisher');
			if ( ! empty($asset_publisher->publisher))
			{
				$xml_publisher_object = $this->_add_child($xml_object, 'publisher', $asset_publisher->publisher);
				if ( ! empty($asset_publisher->publisher_affiliation))
					$attributes['affiliation'] = $asset_publisher->publisher_affiliation;
				if ( ! empty($asset_publisher->publisher_ref))
					$attributes['ref'] = $asset_publisher->publisher_ref;
				$this->_add_attribute($xml_publisher_object, $attributes);
				unset($attributes);
			}
			if ( ! empty($asset_publisher->publisher_role))
			{
				$attributes = array();
				$xml_publisher_role_object = $this->_add_child($xml_object, 'publisherRole', $asset_publisher->publisher_role);
				if ( ! empty($asset_publisher->publisher_role_source))
					$attributes['source'] = $asset_publisher->publisher_role_source;
				if ( ! empty($asset_publisher->publisher_role_ref))
					$attributes['ref'] = $asset_publisher->publisher_role_ref;
				$this->_add_attribute($xml_publisher_role_object, $attributes);
				unset($attributes);
			}
			unset($xml_object);
		}
		//  Asset Publisher and Role  End
		// Asset Right Summary Start
		$asset_rights = $pbcore_model->get_by($pbcore_model->table_rights_summaries, array('assets_id' => $this->asset_id));
		foreach ($asset_rights as $asset_right)
		{
			$attributes = array();
			$xml_object = $this->_add_child($asset_xml_object, 'pbcoreRightsSummary');
			if ( ! empty($asset_right->rights))
				$this->_add_child($xml_object, 'rightsSummary', $asset_right->rights);
			if ( ! empty($asset_right->rights_link))
				$this->_add_child($xml_object, 'rightsLink', $asset_right->rights_link);
			unset($xml_object);
		}
		// Asset Right Summary End
		// Asset Extension Start
//		$asset_extensions = $pbcore_model->get_by($pbcore_model->table_extensions, array('assets_id' => $this->asset_id));
//		foreach ($asset_extensions as $asset_extension)
//		{
//			$attributes = array();
//			$xml_object = $this->_add_child($asset_xml_object, 'pbcoreExtension');
//			$xml_object = $this->_add_child($xml_object, 'extensionWrap');
//			if ( ! empty($asset_extension->extension_element))
//				$this->_add_child($xml_object, 'extensionAuthorityUsed', $asset_extension->extension_element);
//			if ( ! empty($asset_extension->extension_value))
//				$this->_add_child($xml_object, 'extensionValue', $asset_extension->extension_value);
//			unset($xml_object);
//		}
		// Asset Extension End
		$this->_fetch_instantiations($asset_xml_object);
		// Asset Annotation  Start
		$asset_annotations = $pbcore_model->get_by($pbcore_model->table_annotations, array('assets_id' => $this->asset_id));
		foreach ($asset_annotations as $asset_annotation)
		{
			$attributes = array();
			$xml_object = $this->_add_child($asset_xml_object, 'pbcoreAnnotation', $asset_annotation->annotation);
			if ( ! empty($asset_annotation->annotation_type))
				$attributes['annotationType'] = $asset_annotation->annotation_type;
			if ( ! empty($asset_annotation->annotation_ref))
				$attributes['ref'] = $asset_annotation->annotation_ref;
			$this->_add_attribute($xml_object, $attributes);
			unset($xml_object);
		}
		// Asset Annotation End
		$asset_info = $pbcore_model->get_one_by($pbcore_model->_assets_table, array('id' => $this->asset_id));
		$last_modified = ( ! empty($asset_info->updated) ? $asset_info->updated : $asset_info->created);
		$xml_object = $this->_add_child($asset_xml_object, 'pbcoreAnnotation', $last_modified);
		$attributes = array();
		$attributes['annotationType'] = 'last_modified';
		$this->_add_attribute($xml_object, $attributes);
		unset($xml_object);
		$station_info = $pbcore_model->get_one_by($pbcore_model->stations, array('id' => $asset_info->stations_id));

		$xml_object = $this->_add_child($asset_xml_object, 'pbcoreAnnotation', $station_info->station_name);
		$attributes = array();
		$attributes['annotationType'] = 'organization';
		$this->_add_attribute($xml_object, $attributes);
		unset($xml_object);
	}

	/**
	 * Fetch instantiations information from system
	 * 
	 * @param \SimpleXMLElement $asset_xml_object
	 * 
	 * @return void
	 */
	private function _fetch_instantiations($asset_xml_object)
	{
		$pbcore_model = $this->CI->pbcore_model;

		$instantiations = $pbcore_model->get_by($pbcore_model->table_instantiations, array('assets_id' => $this->asset_id));
		foreach ($instantiations as $instantiation)
		{
			$instantiations_object = $this->_add_child($asset_xml_object, 'pbcoreInstantiation');
			// Instantiations Identifier Start
			$identifiers = $pbcore_model->get_by($pbcore_model->table_instantiation_identifier, array('instantiations_id' => $instantiation->id));
			foreach ($identifiers as $identifier)
			{
				$xml_identifier = $this->_add_child($instantiations_object, 'instantiationIdentifier', $identifier->instantiation_identifier);
				if ( ! empty($identifier->instantiation_source))
				{
					$attributes = array();
					$attributes['source'] = $identifier->instantiation_source;
					$this->_add_attribute($xml_identifier, $attributes);
					unset($attributes);
				}
			}
			// Instantiations Identifier End
			// Instantiations Date Start
			$instantiation_dates = $pbcore_model->get_instantiation_dates($instantiation->id);
			foreach ($instantiation_dates as $instantiation_date)
			{
				$attributes = array();
				$xml_date_object = $this->_add_child($instantiations_object, 'instantiationDate', $instantiation_date->instantiation_date);
				if ( ! empty($instantiation_date->date_type))
					$attributes['dateType'] = $instantiation_date->date_type;
				$this->_add_attribute($xml_date_object, $attributes);
				unset($xml_object);
			}
			// Instantiations Date End
			// Instantiations Dimensions Start
			$dimensions = $pbcore_model->get_by($pbcore_model->table_instantiation_dimensions, array('instantiations_id' => $instantiation->id));
			foreach ($dimensions as $dimension)
			{
				$xml_dimension = $this->_add_child($instantiations_object, 'instantiationDimensions', $dimension->instantiation_dimension);
				if ( ! empty($dimension->unit_of_measure))
				{
					$attributes = array();
					$attributes['unitOfMeasure'] = $dimension->unit_of_measure;
					$this->_add_attribute($xml_dimension, $attributes);
					unset($attributes);
				}
			}
			// Instantiations Dimensions End
			// Instantiations Physical Format Start
			$physical_formats = $pbcore_model->get_by($pbcore_model->table_instantiation_formats, array('instantiations_id' => $instantiation->id, 'format_type' => 'physical'));
			foreach ($physical_formats as $physical_format)
			{
				$xml_dimension = $this->_add_child($instantiations_object, 'instantiationPhysical', $physical_format->format_name);
			}
			// Instantiations Physical Format End
			// Instantiations Digital Format Start
			$digital_formats = $pbcore_model->get_by($pbcore_model->table_instantiation_formats, array('instantiations_id' => $instantiation->id, 'format_type' => 'digital'));
			foreach ($digital_formats as $digital_format)
			{
				$xml_dimension = $this->_add_child($instantiations_object, 'instantiationDigital', $digital_format->format_name);
			}
			// Instantiations Digital Format End
			if ( ! empty($instantiation->standard))
				$this->_add_child($instantiations_object, 'instantiationStandard', $instantiation->standard);
			if ( ! empty($instantiation->location))
				$this->_add_child($instantiations_object, 'instantiationLocation', $instantiation->location);
			// Instantiations Media Type Start
			if ((int) $instantiation->instantiation_media_type_id !== 0)
			{
				$media_type = $pbcore_model->get_one_by($pbcore_model->table_instantiation_media_types, array('id' => $instantiation->instantiation_media_type_id));
				$this->_add_child($instantiations_object, 'instantiationMediaType', $media_type->media_type);
			}
			// Instantiations Media Type End
			// Instantiations Generation Start
			$generations = $pbcore_model->get_instantiation_generations($instantiation->id);
			foreach ($generations as $generation)
			{
				$this->_add_child($instantiations_object, 'instantiationGenerations', $generation->generation);
			}
			// Instantiations Generation End
			// Instantiations Filesize Start
			if ( ! empty($instantiation->file_size))
			{
				$xml_filesize = $this->_add_child($instantiations_object, 'instantiationFileSize', $instantiation->file_size);

				if ( ! empty($instantiation->file_size_unit_of_measure))
				{
					$attributes = array();
					$attributes['unitsOfMeasure'] = $instantiation->file_size_unit_of_measure;
					$this->_add_attribute($xml_filesize, $attributes);
					unset($attributes);
				}
			}
			// Instantiations Filesize End
			if ( ! empty($instantiation->time_start))
				$this->_add_child($instantiations_object, 'instantiationTimeStart', $instantiation->time_start);
			if ( ! empty($instantiation->projected_duration))
				$this->_add_child($instantiations_object, 'instantiationDuration', $instantiation->projected_duration);
			// Instantiations Date Rate Start
			if ( ! empty($instantiation->data_rate))
			{
				$xml_daterate = $this->_add_child($instantiations_object, 'instantiationDataRate', $instantiation->data_rate);
				if ( ! empty($instantiation->data_rate_units_id))
				{
					$data_rate_unit = $pbcore_model->get_one_by($pbcore_model->table_data_rate_units, array('id' => $instantiation->data_rate_units_id));
					$attributes = array();
					$attributes['unitsOfMeasure'] = $data_rate_unit->unit_of_measure;
					$this->_add_attribute($xml_daterate, $attributes);
					unset($attributes);
				}
			}
			// Instantiations Date Rate End
			// Instantiations Color Start
			if ( ! empty($instantiation->instantiation_colors_id))
			{
				$color = $pbcore_model->get_one_by($pbcore_model->table_instantiation_colors, array('id' => $instantiation->instantiation_colors_id));
				$this->_add_child($instantiations_object, 'instantiationColors', $color->color);
			}
			// Instantiations Color End
			if ( ! empty($instantiation->tracks))
				$this->_add_child($instantiations_object, 'instantiationTracks', $instantiation->tracks);
			if ( ! empty($instantiation->channel_configuration))
				$this->_add_child($instantiations_object, 'instantiationChannelConfiguration', $instantiation->channel_configuration);
			if ( ! empty($instantiation->language))
				$this->_add_child($instantiations_object, 'instantiationLanguage', $instantiation->language);
			if ( ! empty($instantiation->alternative_modes))
				$this->_add_child($instantiations_object, 'instantiationAlternativeModes', $instantiation->alternative_modes);


			$this->_fetch_essence_tracks($instantiation->id, $instantiations_object);


			// Instantiation Relations  Start
			$relations = $pbcore_model->get_instantiation_relations($instantiation->id);
			foreach ($relations as $relation)
			{
				$attributes = array();
				$xml_relation_object = $this->_add_child($instantiations_object, 'pbcoreRelation');
				$this->_add_child($xml_relation_object, 'pbcoreRelationIdentifier', $relation->relation_identifier);
				if ( ! empty($relation->relation_type))
				{
					$xml_relation_type_object = $this->_add_child($xml_relation_object, 'pbcoreRelationType', $relation->relation_type);
					if ( ! empty($relation->relation_type_source))
						$attributes['source'] = $relation->relation_type_source;
					if ( ! empty($relation->relation_type_ref))
						$attributes['ref'] = $relation->relation_type_ref;
					$this->_add_attribute($xml_relation_type_object, $attributes);
					unset($attributes);
				}
				unset($xml_object);
			}
			// Instantiation Relations End
			// Instantiations Annotation Start
			$annotations = $pbcore_model->get_by($pbcore_model->table_instantiation_annotations, array('instantiations_id' => $instantiation->id));
			foreach ($annotations as $annotation)
			{
				$xml_annotation = $this->_add_child($instantiations_object, 'instantiationAnnotation', $annotation->annotation);
				if ( ! empty($annotation->annotation_type))
				{
					$attributes = array();
					$attributes['annotationType'] = $annotation->annotation_type;
					$this->_add_attribute($xml_annotation, $attributes);
					unset($attributes);
				}
			}
			// Instantiation Annotation End
			// Instantiation Nominations Start
			$extensions = $pbcore_model->get_instantiation_nomination($instantiation->id);
			foreach ($extensions as $extension)
			{
				$attributes = array();
				$xml_object = $this->_add_child($instantiations_object, 'instantiationExtension');
				$xml_object = $this->_add_child($xml_object, 'extensionWrap');
				$this->_add_child($xml_object, 'extensionElement', 'AACIP');
				if ( ! empty($extension->status))
					$this->_add_child($xml_object, 'extensionValue', $extension->status);
				$this->_add_child($xml_object, 'extensionAuthorityUsed', 'AACIP Record Nomination Status');
				if ( ! empty($extension->nomination_reason))
				{
					$xml_object = $this->_add_child($instantiations_object, 'instantiationExtension');
					$xml_object = $this->_add_child($xml_object, 'extensionWrap');
					$this->_add_child($xml_object, 'extensionElement', 'AACIP Record Nomination Status');
					$this->_add_child($xml_object, 'extensionValue', $extension->nomination_reason);
					$this->_add_child($xml_object, 'extensionAuthorityUsed', 'AACIP');
				}
				unset($xml_object);
			}
			// Instantiation Nominations End
		}
	}

	/**
	 * Fetch essence track info from system.
	 * 
	 * @param integer $instantiations_id
	 * @param \SimpleXMLElement $instantiations_object
	 * 
	 * @return void
	 */
	private function _fetch_essence_tracks($instantiations_id, $instantiations_object)
	{
		$pbcore_model = $this->CI->pbcore_model;

		$essence_tracks = $pbcore_model->get_by($pbcore_model->table_essence_tracks, array('instantiations_id' => $instantiations_id));
		foreach ($essence_tracks as $essence_track)
		{
			$xml_essencetrack = $this->_add_child($instantiations_object, 'instantiationEssenceTrack');
			// Essence Track Track Type Start
			if ( ! empty($essence_track->essence_track_types_id))
			{
				$essence_track_type = $pbcore_model->get_one_by($pbcore_model->table_essence_track_types, array('id' => $essence_track->essence_track_types_id));
				$this->_add_child($xml_essencetrack, 'essenceTrackType', $essence_track_type->essence_track_type);
			}
			// Essence Track Track Type End
			// Essence Track Identifiers Start
			$essence_track_identifiers = $pbcore_model->get_by($pbcore_model->table_essence_track_identifiers, array('essence_tracks_id' => $essence_track->id));
			foreach ($essence_track_identifiers as $identifier)
			{
				$xml_identifier = $this->_add_child($xml_essencetrack, 'essenceTrackIdentifier', $identifier->essence_track_identifiers);
				if ( ! empty($identifier->essence_track_identifier_source))
				{
					$attributes = array();
					$attributes['source'] = $identifier->essence_track_identifier_source;
					$this->_add_attribute($xml_identifier, $attributes);
					unset($attributes);
				}
			}
			// Essence Track Identifiers End
			if ( ! empty($essence_track->standard))
				$this->_add_child($xml_essencetrack, 'essenceTrackStandard', $essence_track->standard);
			// Essence Track Encoding Start

			$essence_track_encodings = $pbcore_model->get_by($pbcore_model->table_essence_track_encodings, array('essence_tracks_id' => $essence_track->id));
			foreach ($essence_track_encodings as $encoding)
			{
				$xml_encoding = $this->_add_child($xml_essencetrack, 'essenceTrackEncoding', $encoding->encoding);
				$attributes = array();
				if ( ! empty($encoding->encoding_source))
					$attributes['source'] = $encoding->encoding_source;
				if ( ! empty($encoding->encoding_ref))
					$attributes['ref'] = $encoding->encoding_ref;
				$this->_add_attribute($xml_encoding, $attributes);
				unset($attributes);
			}
			// Essence Track Encoding End
			// Essence Track Date Rate Start
			if ( ! empty($essence_track->data_rate))
			{
				$xml_daterate = $this->_add_child($xml_essencetrack, 'essenceTrackDataRate', $essence_track->data_rate);
				if ( ! empty($essence_track->data_rate_units_id))
				{
					$data_rate_unit = $pbcore_model->get_one_by($pbcore_model->table_data_rate_units, array('id' => $essence_track->data_rate_units_id));
					$attributes = array();
					$attributes['unitsOfMeasure'] = $data_rate_unit->unit_of_measure;
					$this->_add_attribute($xml_daterate, $attributes);
					unset($attributes);
				}
			}
			// Essence Track Date Rate End
			if ( ! empty($essence_track->frame_rate))
				$this->_add_child($xml_essencetrack, 'essenceTrackFrameRate', $essence_track->frame_rate);
			if ( ! empty($essence_track->playback_speed))
				$this->_add_child($xml_essencetrack, 'essenceTrackPlaybackSpeed', $essence_track->playback_speed);
			if ( ! empty($essence_track->sampling_rate))
				$this->_add_child($xml_essencetrack, 'essenceTrackSamplingRate', $essence_track->sampling_rate);
			if ( ! empty($essence_track->bit_depth))
				$this->_add_child($xml_essencetrack, 'essenceTrackBitDepth', $essence_track->bit_depth);
			// Essence Track Frame Size Start
			if ((int) $essence_track->essence_track_frame_sizes_id !== 0)
			{
				$essence_track_frame = $pbcore_model->get_one_by($pbcore_model->table_essence_track_frame_sizes, array('id' => $essence_track->essence_track_frame_sizes_id));
				$this->_add_child($xml_essencetrack, 'essenceTrackFrameSize', $essence_track_frame->width . ' x ' . $essence_track_frame->height);
			}
			// Essence Track Frame Size End
			if ( ! empty($essence_track->aspect_ratio))
				$this->_add_child($xml_essencetrack, 'essenceTrackAspectRatio', $essence_track->aspect_ratio);
			if ( ! empty($essence_track->time_start))
				$this->_add_child($xml_essencetrack, 'essenceTrackTimeStart', $essence_track->time_start);
			if ( ! empty($essence_track->duration))
				$this->_add_child($xml_essencetrack, 'essenceTrackDuration', $essence_track->duration);
			if ( ! empty($essence_track->language))
				$this->_add_child($xml_essencetrack, 'essenceTrackLanguage', $essence_track->language);


			// Essence Track Annotation Start
			$essence_track_annotations = $pbcore_model->get_by($pbcore_model->table_essence_track_annotations, array('essence_tracks_id' => $essence_track->id));
			foreach ($essence_track_annotations as $annotation)
			{
				$xml_annotation = $this->_add_child($xml_essencetrack, 'essenceTrackAnnotation', $annotation->annotation);

				if ( ! empty($annotation->annotation_type))
				{
					$attributes = array();
					$attributes['type'] = $annotation->annotation_type;
					$this->_add_attribute($xml_annotation, $attributes);
					unset($attributes);
				}
			}
			// Essence Track Annotation End
		}
	}

	/**
	 * Add child to given object of xml.
	 * 
	 * @param \SimpleXMLElement $object
	 * @param string $tag_name
	 * @param string $value
	 * @return \SimpleXMLElement
	 */
	private function _add_child($object, $tag_name, $value = NULL)
	{
		$object = $object->addChild($tag_name, htmlentities(@iconv('UTF-8', 'ASCII//IGNORE', $value)));
		return $object;
	}

	/**
	 * Add attributes to xml tag.
	 * 
	 * @param \SimpleXMLElement $object
	 * @param array $attributes
	 * 
	 * @return \SimpleXMLElement
	 */
	private function _add_attribute($object, array $attributes, $namespace = NULL)
	{
		foreach ($attributes as $attribute => $value)
		{
			$object->addAttribute($attribute, htmlentities(@iconv('UTF-8', 'ASCII//IGNORE', $value)), $namespace);
		}
		return $object;
	}

	/**
	 * Make file name for xml.
	 * 
	 * @return string
	 */
	public function make_file_name()
	{
		$guid = $this->CI->pbcore_model->get_one_by($this->CI->pbcore_model->table_identifers, array('assets_id' => $this->asset_id, 'identifier_source' => 'http://americanarchiveinventory.org'));
		$file_name = str_replace('/', '-', $guid->identifier);
		return $file_name;
	}

	/**
	 * Format XML and return its path.
	 * 
	 * @param string $path
	 * 
	 * @return string
	 */
	public function format_xml($path)
	{
		$dom = new DOMDocument('1.0');
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;
		$dom->loadXML($this->xml->asXML());
		$dom->save($path);
		unset($dom);
		return $path;
	}

	/**
	 * Make xml of given error.
	 * 
	 * @param string $error
	 * @return \SimpleXMLElement
	 */
	public function xml_error($error)
	{
		$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><error></error>');
		$this->_add_child($xml, 'string', $error);
		return $xml;
	}

}

?>