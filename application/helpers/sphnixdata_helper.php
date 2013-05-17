<?php

/**
 * Make Array for inserting/updating sphnix index of assets.
 * 
 * @param stdObject $row
 * @return array
 */
function make_assets_sphnix_array($row)
{
	$data = array(
		's_organization' => ! empty($row->organization) ? $row->organization : '',
		'organization' => ! empty($row->organization) ? $row->organization : '',
		's_state' => ! empty($row->state) ? $row->state : '',
		'state' => ! empty($row->state) ? $row->state : '',
		'guid_identifier' => ! empty($row->guid_identifier) ? $row->guid_identifier : '',
		'description' => ! empty($row->description) ? $row->description : '',
		'description_type' => ! empty($row->description_type) ? $row->description_type : '',
		'local_identifier' => ! empty($row->local_identifier) ? $row->local_identifier : '',
		'asset_title' => ! empty($row->asset_title) ? $row->asset_title : '',
		'asset_title_source' => ! empty($row->asset_title_source) ? $row->asset_title_source : '',
		'asset_title_ref' => ! empty($row->asset_title_ref) ? $row->asset_title_ref : '',
		'asset_title_type' => ! empty($row->asset_title_type) ? $row->asset_title_type : '',
		'asset_subject' => ! empty($row->asset_subject) ? $row->asset_subject : '',
		'asset_subject_source' => ! empty($row->asset_subject_source) ? $row->asset_subject_source : '',
		'asset_subject_ref' => ! empty($row->asset_subject_ref) ? $row->asset_subject_ref : '',
		'asset_genre' => ! empty($row->asset_genre) ? $row->asset_genre : '',
		'asset_genre_source' => ! empty($row->asset_genre_source) ? $row->asset_genre_source : '',
		'asset_genre_ref' => ! empty($row->asset_genre_ref) ? $row->asset_genre_ref : '',
		'asset_creator_name' => ! empty($row->asset_creator_name) ? $row->asset_creator_name : '',
		'asset_creator_affiliation' => ! empty($row->asset_creator_affiliation) ? $row->asset_creator_affiliation : '',
		'asset_creator_source' => ! empty($row->asset_creator_source) ? $row->asset_creator_source : '',
		'asset_creator_ref' => ! empty($row->asset_creator_ref) ? $row->asset_creator_ref : '',
		'asset_creator_role' => ! empty($row->asset_creator_role) ? $row->asset_creator_role : '',
		'asset_creator_role_source' => ! empty($row->asset_creator_role_source) ? $row->asset_creator_role_source : '',
		'asset_creator_role_ref' => ! empty($row->asset_creator_role_ref) ? $row->asset_creator_role_ref : '',
		'asset_contributor_name' => ! empty($row->asset_contributor_name) ? $row->asset_contributor_name : '',
		'asset_contributor_affiliation' => ! empty($row->asset_contributor_affiliation) ? $row->asset_contributor_affiliation : '',
		'asset_contributor_source' => ! empty($row->asset_contributor_source) ? $row->asset_contributor_source : '',
		'asset_contributor_ref' => ! empty($row->asset_contributor_ref) ? $row->asset_contributor_ref : '',
		'asset_contributor_role' => ! empty($row->asset_contributor_role) ? $row->asset_contributor_role : '',
		'asset_contributor_role_source' => ! empty($row->asset_contributor_role_source) ? $row->asset_contributor_role_source : '',
		'asset_contributor_role_ref' => ! empty($row->asset_contributor_role_ref) ? $row->asset_contributor_role_ref : '',
		'asset_publisher_name' => ! empty($row->asset_publisher_name) ? $row->asset_publisher_name : '',
		'asset_publisher_affiliation' => ! empty($row->asset_publisher_affiliation) ? $row->asset_publisher_affiliation : '',
		'asset_publisher_ref' => ! empty($row->asset_publisher_ref) ? $row->asset_publisher_ref : '',
		'asset_publisher_role' => ! empty($row->asset_publisher_role) ? $row->asset_publisher_role : '',
		'asset_publisher_role_source' => ! empty($row->asset_publisher_role_source) ? $row->asset_publisher_role_source : '',
		'asset_publisher_role_ref' => ! empty($row->asset_publisher_role_ref) ? $row->asset_publisher_role_ref : '',
		'dates' => ! empty($row->dates) ? (int) $row->dates : 0,
		'date_type' => ! empty($row->date_type) ? $row->date_type : '',
		'asset_coverage' => ! empty($row->asset_coverage) ? $row->asset_coverage : '',
		'asset_coverage_type' => ! empty($row->asset_coverage_type) ? $row->asset_coverage_type : '',
		'asset_audience_level' => ! empty($row->asset_audience_level) ? $row->asset_audience_level : '',
		'asset_audience_level_source' => ! empty($row->asset_audience_level_source) ? $row->asset_audience_level_source : '',
		'asset_audience_level_ref' => ! empty($row->asset_audience_level_ref) ? $row->asset_audience_level_ref : '',
		'asset_audience_rating' => ! empty($row->asset_audience_rating) ? $row->asset_audience_rating : '',
		'asset_audience_rating_source' => ! empty($row->asset_audience_rating_source) ? $row->asset_audience_rating_source : '',
		'asset_audience_rating_ref' => ! empty($row->asset_audience_rating_ref) ? $row->asset_audience_rating_ref : '',
		'asset_annotation' => ! empty($row->asset_annotation) ? $row->asset_annotation : '',
		'asset_annotation_type' => ! empty($row->asset_annotation_type) ? $row->asset_annotation_type : '',
		'asset_annotation_ref' => ! empty($row->asset_annotation_ref) ? $row->asset_annotation_ref : '',
		'asset_rights' => ! empty($row->asset_rights) ? $row->asset_rights : '',
		'asset_rights_link' => ! empty($row->asset_rights_link) ? $row->asset_rights_link : '',
		'standard' => ! empty($row->standard) ? $row->standard : '',
		'location' => ! empty($row->location) ? $row->location : '',
		'tracks' => ! empty($row->tracks) ? $row->tracks : '',
		'language' => ! empty($row->language) ? $row->language : '',
		'digitized' => ! empty($row->digitized) ? $row->digitized : '',
		'file_size_unit_of_measure' => ! empty($row->file_size_unit_of_measure) ? $row->file_size_unit_of_measure : '',
		'file_size' => ! empty($row->file_size) ? $row->file_size : '',
		'channel_configuration' => ! empty($row->channel_configuration) ? $row->channel_configuration : '',
		'alternative_modes' => ! empty($row->alternative_modes) ? $row->alternative_modes : '',
		'instantiation_date' => ! empty($row->instantiation_date) ? (int) $row->instantiation_date : 0,
		's_media_type' => ! empty($row->s_media_type) ? $row->s_media_type : '',
		'media_type' => ! empty($row->media_type) ? $row->media_type : '',
		'format_type' => ! empty($row->format_type) ? $row->format_type : '',
		's_format_name' => ! empty($row->s_format_name) ? $row->s_format_name : '',
		'format_name' => ! empty($row->format_name) ? $row->format_name : '',
		'color' => ! empty($row->color) ? $row->color : '',
		's_facet_generation' => ! empty($row->s_facet_generation) ? $row->s_facet_generation : '',
		'facet_generation' => ! empty($row->facet_generation) ? $row->facet_generation : '',
		's_status' => ! empty($row->s_status) ? $row->s_status : '',
		'status' => ! empty($row->status) ? $row->status : '',
		'instantiation_identifier' => ! empty($row->instantiation_identifier) ? $row->instantiation_identifier : '',
		'instantiation_dimension' => ! empty($row->instantiation_dimension) ? $row->instantiation_dimension : '',
		'unit_of_measure' => ! empty($row->unit_of_measure) ? $row->unit_of_measure : '',
		'track_standard' => ! empty($row->track_standard) ? $row->track_standard : '',
		'track_language' => ! empty($row->track_language) ? $row->track_language : '',
		'track_frame_rate' => ! empty($row->track_frame_rate) ? $row->track_frame_rate : '',
		'track_playback_speed' => ! empty($row->track_playback_speed) ? $row->track_playback_speed : '',
		'track_sampling_rate' => ! empty($row->track_sampling_rate) ? $row->track_sampling_rate : '',
		'track_bit_depth' => ! empty($row->track_bit_depth) ? $row->track_bit_depth : '',
		'track_aspect_ratio' => ! empty($row->track_aspect_ratio) ? $row->track_aspect_ratio : '',
		'track_data_rate' => ! empty($row->track_data_rate) ? $row->track_data_rate : '',
		'track_unit_of_measure' => ! empty($row->track_unit_of_measure) ? $row->track_unit_of_measure : '',
		'track_essence_track_type' => ! empty($row->track_essence_track_type) ? $row->track_essence_track_type : '',
		'track_width' => ! empty($row->track_width) ? $row->track_width : '',
		'track_height' => ! empty($row->track_height) ? $row->track_height : '',
		'track_encoding' => ! empty($row->track_encoding) ? $row->track_encoding : '',
		'track_annotation' => ! empty($row->track_annotation) ? $row->track_annotation : '',
		'track_annotation_type' => ! empty($row->track_annotation_type) ? $row->track_annotation_type : '',
		'ins_annotation' => ! empty($row->ins_annotation) ? $row->ins_annotation : '',
		'ins_annotation_type' => ! empty($row->ins_annotation_type) ? $row->ins_annotation_type : ''
	);
	return $data;
}

/**
 * Make Array for inserting/updating sphnix index of instantitations.
 * 
 * @param stdObject $row
 * @return array
 */
function make_instantiation_sphnix_array($row)
{
	$data = array(
		'assets_id' => (int) $row->assets_id,
		's_organization' => ! empty($row->organization) ? $row->organization : '',
		'organization' => ! empty($row->organization) ? $row->organization : '',
		's_state' => ! empty($row->state) ? $row->state : '',
		'state' => ! empty($row->state) ? $row->state : '',
		'standard' => ! empty($row->standard) ? $row->standard : '',
		'data_rate' => ! empty($row->data_rate) ? $row->data_rate : '',
		'location' => ! empty($row->location) ? $row->location : '',
		'tracks' => ! empty($row->tracks) ? $row->tracks : '',
		'digitized' => ! empty($row->digitized) ? $row->digitized : '',
		's_language' => ! empty($row->language) ? $row->language : '',
		'language' => ! empty($row->language) ? $row->language : '',
		'actual_duration' => ! empty($row->actual_duration) ? (int) $row->actual_duration : 0,
		's_projected_duration' => ! empty($row->projected_duration) ? $row->projected_duration : '',
		'projected_duration' => ! empty($row->projected_duration) ? $row->projected_duration : '',
		'file_size_unit_of_measure' => ! empty($row->file_size_unit_of_measure) ? $row->file_size_unit_of_measure : '',
		's_file_size' => ! empty($row->file_size) ? $row->file_size : '',
		'file_size' => ! empty($row->file_size) ? $row->file_size : '',
		'channel_configuration' => ! empty($row->channel_configuration) ? $row->channel_configuration : '',
		'alternative_modes' => ! empty($row->alternative_modes) ? $row->alternative_modes : '',
		'data_rate_unit_of_measure' => ! empty($row->data_rate_unit_of_measure) ? $row->data_rate_unit_of_measure : '',
		'dates' => ! empty($row->dates) ? (int) $row->dates : 0,
		'date_type' => ! empty($row->date_type) ? $row->date_type : '',
		's_media_type' => ! empty($row->media_type) ? $row->media_type : '',
		'media_type' => ! empty($row->media_type) ? $row->media_type : '',
		's_format_type' => ! empty($row->format_type) ? $row->format_type : '',
		'format_type' => ! empty($row->format_type) ? $row->format_type : '',
		's_format_name' => ! empty($row->format_name) ? $row->format_name : '',
		'format_name' => ! empty($row->format_name) ? $row->format_name : '',
		's_color' => ! empty($row->color) ? $row->color : '',
		'color' => ! empty($row->color) ? $row->color : '',
		's_generation' => ! empty($row->generation) ? $row->generation : '',
		'generation' => ! empty($row->generation) ? $row->generation : '',
		's_facet_generation' => ! empty($row->facet_generation) ? $row->facet_generation : '',
		'facet_generation' => ! empty($row->facet_generation) ? $row->facet_generation : '',
		's_status' => ! empty($row->status) ? $row->status : '',
		'status' => ! empty($row->status) ? $row->status : '',
		'outcome_event' => ! empty($row->outcome_event) ? $row->outcome_event : '',
		'event_type' => ! empty($row->event_type) ? $row->event_type : '',
		'event_date' => ! empty($row->event_date) ? $row->event_date : '',
		's_instantiation_identifier' => ! empty($row->instantiation_identifier) ? $row->instantiation_identifier : '',
		'instantiation_identifier' => ! empty($row->instantiation_identifier) ? $row->instantiation_identifier : '',
		's_instantiation_source' => ! empty($row->instantiation_source) ? $row->instantiation_source : '',
		'instantiation_source' => ! empty($row->instantiation_source) ? $row->instantiation_source : '',
		'instantiation_dimension' => ! empty($row->instantiation_dimension) ? $row->instantiation_dimension : '',
		'unit_of_measure' => ! empty($row->unit_of_measure) ? $row->unit_of_measure : '',
		'track_standard' => ! empty($row->track_standard) ? $row->track_standard : '',
		'track_duration' => ! empty($row->track_duration) ? $row->track_duration : '',
		'track_language' => ! empty($row->track_language) ? $row->track_language : '',
		'track_frame_rate' => ! empty($row->track_frame_rate) ? $row->track_frame_rate : '',
		'track_playback_speed' => ! empty($row->track_playback_speed) ? $row->track_playback_speed : '',
		'track_sampling_rate' => ! empty($row->track_sampling_rate) ? $row->track_sampling_rate : '',
		'track_bit_depth' => ! empty($row->track_bit_depth) ? $row->track_bit_depth : '',
		'track_aspect_ratio' => ! empty($row->track_aspect_ratio) ? $row->track_aspect_ratio : '',
		'track_data_rate' => ! empty($row->track_data_rate) ? $row->track_data_rate : '',
		'track_unit_of_measure' => ! empty($row->track_unit_of_measure) ? $row->track_unit_of_measure : '',
		'track_essence_track_type' => ! empty($row->track_essence_track_type) ? $row->track_essence_track_type : '',
		'track_width' => ! empty($row->track_width) ? $row->track_width : '',
		'track_height' => ! empty($row->track_height) ? $row->track_height : '',
		'track_encoding' => ! empty($row->track_encoding) ? $row->track_encoding : '',
		'track_annotation' => ! empty($row->track_annotation) ? $row->track_annotation : '',
		'track_annotation_type' => ! empty($row->track_annotation_type) ? $row->track_annotation_type : '',
		'ins_annotation' => ! empty($row->ins_annotation) ? $row->ins_annotation : '',
		'guid_identifier' => ! empty($row->guid_identifier) ? $row->guid_identifier : '',
		's_asset_title' => ! empty($row->asset_title) ? $row->asset_title : '',
		'asset_title' => ! empty($row->asset_title) ? $row->asset_title : '',
		'asset_subject' => ! empty($row->asset_subject) ? $row->asset_subject : '',
		'asset_coverage' => ! empty($row->asset_coverage) ? $row->asset_coverage : '',
		'asset_genre' => ! empty($row->asset_genre) ? $row->asset_genre : '',
		'asset_publisher_name' => ! empty($row->asset_publisher_name) ? $row->asset_publisher_name : '',
		'asset_description' => ! empty($row->asset_description) ? $row->asset_description : '',
		'asset_creator_name' => ! empty($row->asset_creator_name) ? $row->asset_creator_name : '',
		'asset_creator_affiliation' => ! empty($row->asset_creator_affiliation) ? $row->asset_creator_affiliation : '',
		'asset_contributor_name' => ! empty($row->asset_contributor_name) ? $row->asset_contributor_name : '',
		'asset_contributor_affiliation' => ! empty($row->asset_contributor_affiliation) ? $row->asset_contributor_affiliation : '',
		'asset_rights' => ! empty($row->asset_rights) ? $row->asset_rights : ''
	);
	return $data;
}

/**
 * Make Array for inserting/updating sphnix index of stations.
 * 
 * @param stdObject $row
 * @return array
 */
function make_station_sphnix_array($row)
{
	if ($row->type == 0)
		$type = 'Radio';
	else if ($row->type == 1)
		$type = 'TV';
	else if ($row->type == 2)
		$type = 'JOINT';
	$record = array(
		's_station_name' => $row->station_name,
		'station_name' => $row->station_name,
		's_type' => $type,
		'type' => $type,
		's_address_primary' => $row->address_primary,
		'address_primary' => $row->address_primary,
		's_address_secondary' => ! empty($row->address_secondary) ? $row->address_secondary : '',
		'address_secondary' => ! empty($row->address_secondary) ? $row->address_secondary : '',
		's_city' => $row->city,
		'city' => $row->city,
		's_state' => $row->state,
		'state' => $row->state,
		's_zip' => $row->zip,
		'zip' => $row->zip,
		's_cpb_id' => $row->cpb_id,
		'cpb_id' => $row->cpb_id,
		'allocated_hours' => (int) $row->allocated_hours,
		'allocated_buffer' => (int) $row->allocated_buffer,
		'total_allocated' => (int) $row->total_allocated,
		'is_certified' => (int) $row->is_certified,
		'is_agreed' => (int) $row->is_agreed,
		'start_date' => (int) strtotime($row->start_date),
		'end_date' => (int) strtotime($row->end_date)
	);
	return $record;
}

