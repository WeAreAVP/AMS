<?php

/**
 * Make Array for inserting/updating sphnix index of assets.
 * 
 * @param stdObject $row
 * @return array
 */
function make_assets_sphnix_array($row, $new = TRUE)
{
	$data = array();
	if ( ! $new)
		$data['id'] = $row->id;
	$data['s_organization'] = ! empty($row->organization) ? $row->organization : '';
	$data['organization'] = ! empty($row->organization) ? $row->organization : '';
	$data['s_state'] = ! empty($row->state) ? $row->state : '';
	$data['state'] = ! empty($row->state) ? $row->state : '';
	$data['guid_identifier'] = ! empty($row->guid_identifier) ? $row->guid_identifier : '';
	$data['description'] = ! empty($row->description) ? $row->description : '';
	$data['description_type'] = ! empty($row->description_type) ? $row->description_type : '';
	$data['local_identifier'] = ! empty($row->local_identifier) ? $row->local_identifier : '';
	$data['asset_title'] = ! empty($row->asset_title) ? $row->asset_title : '';
	$data['asset_title_source'] = ! empty($row->asset_title_source) ? $row->asset_title_source : '';
	$data['asset_title_ref'] = ! empty($row->asset_title_ref) ? $row->asset_title_ref : '';
	$data['asset_title_type'] = ! empty($row->asset_title_type) ? $row->asset_title_type : '';
	$data['asset_subject'] = ! empty($row->asset_subject) ? $row->asset_subject : '';
	$data['asset_subject_source'] = ! empty($row->asset_subject_source) ? $row->asset_subject_source : '';
	$data['asset_subject_ref'] = ! empty($row->asset_subject_ref) ? $row->asset_subject_ref : '';
	$data['asset_genre'] = ! empty($row->asset_genre) ? $row->asset_genre : '';
	$data['asset_genre_source'] = ! empty($row->asset_genre_source) ? $row->asset_genre_source : '';
	$data['asset_genre_ref'] = ! empty($row->asset_genre_ref) ? $row->asset_genre_ref : '';
	$data['asset_creator_name'] = ! empty($row->asset_creator_name) ? $row->asset_creator_name : '';
	$data['asset_creator_affiliation'] = ! empty($row->asset_creator_affiliation) ? $row->asset_creator_affiliation : '';
	$data['asset_creator_source'] = ! empty($row->asset_creator_source) ? $row->asset_creator_source : '';
	$data['asset_creator_ref'] = ! empty($row->asset_creator_ref) ? $row->asset_creator_ref : '';
	$data['asset_creator_role'] = ! empty($row->asset_creator_role) ? $row->asset_creator_role : '';
	$data['asset_creator_role_source'] = ! empty($row->asset_creator_role_source) ? $row->asset_creator_role_source : '';
	$data['asset_creator_role_ref'] = ! empty($row->asset_creator_role_ref) ? $row->asset_creator_role_ref : '';
	$data['asset_contributor_name'] = ! empty($row->asset_contributor_name) ? $row->asset_contributor_name : '';
	$data['asset_contributor_affiliation'] = ! empty($row->asset_contributor_affiliation) ? $row->asset_contributor_affiliation : '';
	$data['asset_contributor_source'] = ! empty($row->asset_contributor_source) ? $row->asset_contributor_source : '';
	$data['asset_contributor_ref'] = ! empty($row->asset_contributor_ref) ? $row->asset_contributor_ref : '';
	$data['asset_contributor_role'] = ! empty($row->asset_contributor_role) ? $row->asset_contributor_role : '';
	$data['asset_contributor_role_source'] = ! empty($row->asset_contributor_role_source) ? $row->asset_contributor_role_source : '';
	$data['asset_contributor_role_ref'] = ! empty($row->asset_contributor_role_ref) ? $row->asset_contributor_role_ref : '';
	$data['asset_publisher_name'] = ! empty($row->asset_publisher_name) ? $row->asset_publisher_name : '';
	$data['asset_publisher_affiliation'] = ! empty($row->asset_publisher_affiliation) ? $row->asset_publisher_affiliation : '';
	$data['asset_publisher_ref'] = ! empty($row->asset_publisher_ref) ? $row->asset_publisher_ref : '';
	$data['asset_publisher_role'] = ! empty($row->asset_publisher_role) ? $row->asset_publisher_role : '';
	$data['asset_publisher_role_source'] = ! empty($row->asset_publisher_role_source) ? $row->asset_publisher_role_source : '';
	$data['asset_publisher_role_ref'] = ! empty($row->asset_publisher_role_ref) ? $row->asset_publisher_role_ref : '';
	$data['dates'] = ! empty($row->dates) ? (int) $row->dates : 0;
	$data['date_type'] = ! empty($row->date_type) ? $row->date_type : '';
	$data['asset_coverage'] = ! empty($row->asset_coverage) ? $row->asset_coverage : '';
	$data['asset_coverage_type'] = ! empty($row->asset_coverage_type) ? $row->asset_coverage_type : '';
	$data['asset_audience_level'] = ! empty($row->asset_audience_level) ? $row->asset_audience_level : '';
	$data['asset_audience_level_source'] = ! empty($row->asset_audience_level_source) ? $row->asset_audience_level_source : '';
	$data['asset_audience_level_ref'] = ! empty($row->asset_audience_level_ref) ? $row->asset_audience_level_ref : '';
	$data['asset_audience_rating'] = ! empty($row->asset_audience_rating) ? $row->asset_audience_rating : '';
	$data['asset_audience_rating_source'] = ! empty($row->asset_audience_rating_source) ? $row->asset_audience_rating_source : '';
	$data['asset_audience_rating_ref'] = ! empty($row->asset_audience_rating_ref) ? $row->asset_audience_rating_ref : '';
	$data['asset_annotation'] = ! empty($row->asset_annotation) ? $row->asset_annotation : '';
	$data['asset_annotation_type'] = ! empty($row->asset_annotation_type) ? $row->asset_annotation_type : '';
	$data['asset_annotation_ref'] = ! empty($row->asset_annotation_ref) ? $row->asset_annotation_ref : '';
	$data['asset_rights'] = ! empty($row->asset_rights) ? $row->asset_rights : '';
	$data['asset_rights_link'] = ! empty($row->asset_rights_link) ? $row->asset_rights_link : '';
	$data['standard'] = ! empty($row->standard) ? $row->standard : '';
	$data['location'] = ! empty($row->location) ? $row->location : '';
	$data['tracks'] = ! empty($row->tracks) ? $row->tracks : '';
	$data['language'] = ! empty($row->language) ? $row->language : '';
	$data['s_digitized'] = ! empty($row->digitized) ? $row->digitized : '0';
	$data['digitized'] = ! empty($row->digitized) ? $row->digitized : '0';
	$data['file_size_unit_of_measure'] = ! empty($row->file_size_unit_of_measure) ? $row->file_size_unit_of_measure : '';
	$data['file_size'] = ! empty($row->file_size) ? $row->file_size : '';
	$data['channel_configuration'] = ! empty($row->channel_configuration) ? $row->channel_configuration : '';
	$data['alternative_modes'] = ! empty($row->alternative_modes) ? $row->alternative_modes : '';
	$data['instantiation_date'] = ! empty($row->instantiation_date) ? (int) $row->instantiation_date : 0;
	$data['s_media_type'] = ! empty($row->s_media_type) ? $row->s_media_type : '';
	$data['media_type'] = ! empty($row->media_type) ? $row->media_type : '';
	$data['format_type'] = ! empty($row->format_type) ? $row->format_type : '';
	$data['s_format_name'] = ! empty($row->s_format_name) ? $row->s_format_name : '';
	$data['format_name'] = ! empty($row->format_name) ? $row->format_name : '';
	$data['color'] = ! empty($row->color) ? $row->color : '';
	$data['s_facet_generation'] = ! empty($row->s_facet_generation) ? $row->s_facet_generation : '';
	$data['facet_generation'] = ! empty($row->facet_generation) ? $row->facet_generation : '';
	$data['s_status'] = ! empty($row->s_status) ? $row->s_status : '';
	$data['status'] = ! empty($row->status) ? $row->status : '';
	$data['instantiation_identifier'] = ! empty($row->instantiation_identifier) ? $row->instantiation_identifier : '';
	$data['instantiation_dimension'] = ! empty($row->instantiation_dimension) ? $row->instantiation_dimension : '';
	$data['unit_of_measure'] = ! empty($row->unit_of_measure) ? $row->unit_of_measure : '';
	$data['track_standard'] = ! empty($row->track_standard) ? $row->track_standard : '';
	$data['track_language'] = ! empty($row->track_language) ? $row->track_language : '';
	$data['track_frame_rate'] = ! empty($row->track_frame_rate) ? $row->track_frame_rate : '';
	$data['track_playback_speed'] = ! empty($row->track_playback_speed) ? $row->track_playback_speed : '';
	$data['track_sampling_rate'] = ! empty($row->track_sampling_rate) ? $row->track_sampling_rate : '';
	$data['track_bit_depth'] = ! empty($row->track_bit_depth) ? $row->track_bit_depth : '';
	$data['track_aspect_ratio'] = ! empty($row->track_aspect_ratio) ? $row->track_aspect_ratio : '';
	$data['track_data_rate'] = ! empty($row->track_data_rate) ? $row->track_data_rate : '';
	$data['track_unit_of_measure'] = ! empty($row->track_unit_of_measure) ? $row->track_unit_of_measure : '';
	$data['track_essence_track_type'] = ! empty($row->track_essence_track_type) ? $row->track_essence_track_type : '';
	$data['track_width'] = ! empty($row->track_width) ? $row->track_width : '';
	$data['track_height'] = ! empty($row->track_height) ? $row->track_height : '';
	$data['track_encoding'] = ! empty($row->track_encoding) ? $row->track_encoding : '';
	$data['track_annotation'] = ! empty($row->track_annotation) ? $row->track_annotation : '';
	$data['track_annotation_type'] = ! empty($row->track_annotation_type) ? $row->track_annotation_type : '';
	$data['ins_annotation'] = ! empty($row->ins_annotation) ? $row->ins_annotation : '';
	$data['ins_annotation_type'] = ! empty($row->ins_annotation_type) ? $row->ins_annotation_type : '';

	return $data;
}

/**
 * Make Array for inserting/updating sphnix index of instantitations.
 * 
 * @param stdObject $row
 * @return array
 */
function make_instantiation_sphnix_array($row, $new = TRUE)
{
	$data = array();
	if ( ! $new)
		$data['id'] = $row->id;

	$data['assets_id'] = (int) $row->assets_id;
	$data['s_organization'] = ! empty($row->organization) ? $row->organization : '';
	$data['organization'] = ! empty($row->organization) ? $row->organization : '';
	$data['s_state'] = ! empty($row->state) ? $row->state : '';
	$data['state'] = ! empty($row->state) ? $row->state : '';
	$data['standard'] = ! empty($row->standard) ? $row->standard : '';
	$data['data_rate'] = ! empty($row->data_rate) ? $row->data_rate : '';
	$data['location'] = ! empty($row->location) ? $row->location : '';
	$data['tracks'] = ! empty($row->tracks) ? $row->tracks : '';
	$data['s_digitized'] = ! empty($row->digitized) ? $row->digitized : '';
	$data['digitized'] = ! empty($row->digitized) ? $row->digitized : '';
	$data['s_language'] = ! empty($row->language) ? $row->language : '';
	$data['language'] = ! empty($row->language) ? $row->language : '';
	$data['actual_duration'] = ! empty($row->actual_duration) ? (int) $row->actual_duration : 0;
	$data['s_projected_duration'] = ! empty($row->projected_duration) ? $row->projected_duration : '';
	$data['projected_duration'] = ! empty($row->projected_duration) ? $row->projected_duration : '';
	$data['file_size_unit_of_measure'] = ! empty($row->file_size_unit_of_measure) ? $row->file_size_unit_of_measure : '';
	$data['s_file_size'] = ! empty($row->file_size) ? $row->file_size : '';
	$data['file_size'] = ! empty($row->file_size) ? $row->file_size : '';
	$data['channel_configuration'] = ! empty($row->channel_configuration) ? $row->channel_configuration : '';
	$data['alternative_modes'] = ! empty($row->alternative_modes) ? $row->alternative_modes : '';
	$data['data_rate_unit_of_measure'] = ! empty($row->data_rate_unit_of_measure) ? $row->data_rate_unit_of_measure : '';
	$data['dates'] = ! empty($row->dates) ? (int) $row->dates : 0;
	$data['date_type'] = ! empty($row->date_type) ? $row->date_type : '';
	$data['s_media_type'] = ! empty($row->media_type) ? $row->media_type : '';
	$data['media_type'] = ! empty($row->media_type) ? $row->media_type : '';
	$data['s_format_type'] = ! empty($row->format_type) ? $row->format_type : '';
	$data['format_type'] = ! empty($row->format_type) ? $row->format_type : '';
	$data['s_format_name'] = ! empty($row->format_name) ? $row->format_name : '';
	$data['format_name'] = ! empty($row->format_name) ? $row->format_name : '';
	$data['s_color'] = ! empty($row->color) ? $row->color : '';
	$data['color'] = ! empty($row->color) ? $row->color : '';
	$data['s_generation'] = ! empty($row->generation) ? $row->generation : '';
	$data['generation'] = ! empty($row->generation) ? $row->generation : '';
	$data['s_facet_generation'] = ! empty($row->facet_generation) ? $row->facet_generation : '';
	$data['facet_generation'] = ! empty($row->facet_generation) ? $row->facet_generation : '';
	$data['s_status'] = ! empty($row->status) ? $row->status : '';
	$data['status'] = ! empty($row->status) ? $row->status : '';
	$data['outcome_event'] = ! empty($row->outcome_event) ? $row->outcome_event : '';
	$data['event_type'] = ! empty($row->event_type) ? $row->event_type : '';
	$data['event_date'] = ! empty($row->event_date) ? $row->event_date : '';
	$data['s_instantiation_identifier'] = ! empty($row->instantiation_identifier) ? $row->instantiation_identifier : '';
	$data['instantiation_identifier'] = ! empty($row->instantiation_identifier) ? $row->instantiation_identifier : '';
	$data['s_instantiation_source'] = ! empty($row->instantiation_source) ? $row->instantiation_source : '';
	$data['instantiation_source'] = ! empty($row->instantiation_source) ? $row->instantiation_source : '';
	$data['instantiation_dimension'] = ! empty($row->instantiation_dimension) ? $row->instantiation_dimension : '';
	$data['unit_of_measure'] = ! empty($row->unit_of_measure) ? $row->unit_of_measure : '';
	$data['track_standard'] = ! empty($row->track_standard) ? $row->track_standard : '';
	$data['track_duration'] = ! empty($row->track_duration) ? $row->track_duration : '';
	$data['track_language'] = ! empty($row->track_language) ? $row->track_language : '';
	$data['track_frame_rate'] = ! empty($row->track_frame_rate) ? $row->track_frame_rate : '';
	$data['track_playback_speed'] = ! empty($row->track_playback_speed) ? $row->track_playback_speed : '';
	$data['track_sampling_rate'] = ! empty($row->track_sampling_rate) ? $row->track_sampling_rate : '';
	$data['track_bit_depth'] = ! empty($row->track_bit_depth) ? $row->track_bit_depth : '';
	$data['track_aspect_ratio'] = ! empty($row->track_aspect_ratio) ? $row->track_aspect_ratio : '';
	$data['track_data_rate'] = ! empty($row->track_data_rate) ? $row->track_data_rate : '';
	$data['track_unit_of_measure'] = ! empty($row->track_unit_of_measure) ? $row->track_unit_of_measure : '';
	$data['track_essence_track_type'] = ! empty($row->track_essence_track_type) ? $row->track_essence_track_type : '';
	$data['track_width'] = ! empty($row->track_width) ? $row->track_width : '';
	$data['track_height'] = ! empty($row->track_height) ? $row->track_height : '';
	$data['track_encoding'] = ! empty($row->track_encoding) ? $row->track_encoding : '';
	$data['track_annotation'] = ! empty($row->track_annotation) ? $row->track_annotation : '';
	$data['track_annotation_type'] = ! empty($row->track_annotation_type) ? $row->track_annotation_type : '';
	$data['ins_annotation'] = ! empty($row->ins_annotation) ? $row->ins_annotation : '';
	$data['guid_identifier'] = ! empty($row->guid_identifier) ? $row->guid_identifier : '';
	$data['s_asset_title'] = ! empty($row->asset_title) ? $row->asset_title : '';
	$data['asset_title'] = ! empty($row->asset_title) ? $row->asset_title : '';
	$data['asset_subject'] = ! empty($row->asset_subject) ? $row->asset_subject : '';
	$data['asset_coverage'] = ! empty($row->asset_coverage) ? $row->asset_coverage : '';
	$data['asset_genre'] = ! empty($row->asset_genre) ? $row->asset_genre : '';
	$data['asset_publisher_name'] = ! empty($row->asset_publisher_name) ? $row->asset_publisher_name : '';
	$data['asset_description'] = ! empty($row->asset_description) ? $row->asset_description : '';
	$data['asset_creator_name'] = ! empty($row->asset_creator_name) ? $row->asset_creator_name : '';
	$data['asset_creator_affiliation'] = ! empty($row->asset_creator_affiliation) ? $row->asset_creator_affiliation : '';
	$data['asset_contributor_name'] = ! empty($row->asset_contributor_name) ? $row->asset_contributor_name : '';
	$data['asset_contributor_affiliation'] = ! empty($row->asset_contributor_affiliation) ? $row->asset_contributor_affiliation : '';
	$data['asset_rights'] = ! empty($row->asset_rights) ? $row->asset_rights : '';

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

