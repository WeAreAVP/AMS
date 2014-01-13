<?php

$config['assets_setting'] = array(
	'full' => array(
		'Organization' => array('organization'),
		'AA_GUID' => array('guid_identifier'),
		'Local_ID' => array('local_identifier'),
		'Titles' => array('asset_title', 'asset_title_type', 'asset_title_ref', 'asset_title_source'),
		'Description' => array('description', 'description_type'),
		'Subjects' => array('asset_subject', 'asset_subject_ref', 'asset_subject_source'),
		'Genre' => array('asset_genre', 'asset_genre_source', 'asset_genre_ref'),
		'Creator' => array('asset_creator_name', 'asset_creator_affiliation', 'asset_creator_source', 'asset_creator_ref', 'asset_creator_role', 'asset_creator_role_source', 'asset_creator_role_ref'),
		'Contributor' => array('asset_contributor_name', 'asset_contributor_affiliation', 'asset_contributor_source', 'asset_contributor_ref', 'asset_contributor_role', 'asset_contributor_role_source', 'asset_contributor_role_ref'),
		'Publisher' => array('asset_publisher_name', 'asset_publisher_affiliation', 'asset_publisher_ref', 'asset_publisher_role', 'asset_publisher_role_source', 'asset_publisher_role_ref'),
		'Assets_Date' => array('dates', 'date_type'),
		'Coverage' => array('asset_coverage', 'asset_coverage_type'),
		'Audience_Level' => array('asset_audience_level', 'asset_audience_level_source', 'asset_audience_level_ref'),
		'Audience_Rating' => array('asset_audience_rating', 'asset_audience_rating_source', 'asset_audience_rating_ref'),
		'Annotation' => array('asset_annotation', 'asset_annotation_type', 'asset_annotation_ref'),
		'Rights' => array('asset_rights', 'asset_rights_link')
	),
	'simple' => array(
		'Organization' => 'organization',
		'AA_GUID' => 'guid_identifier',
		'Local_ID' => 'local_identifier',
		'Titles' => 'asset_title',
		'Description' => 'description'
));
$config['instantiation_setting'] = array('full' => array(
		'Organization' => 'organization',
		'Instantiation_ID' => 'instantiation_identifier',
		'Nomination' => 'status',
		'Instantiation\'s_Asset_Title' => 'asset_title',
		'Media_Type' => 'media_type',
		'Generation' => 'generation',
		'Format' => 'format_name',
		'Duration' => 'actual_duration',
		'Date' => 'date_type',
		'File_size' => 'file_size',
		'Colors' => 'color',
		'Language' => 'language'));


