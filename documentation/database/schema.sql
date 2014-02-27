-- phpMyAdmin SQL Dump
-- version 3.5.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 27, 2014 at 09:48 AM
-- Server version: 5.1.69
-- PHP Version: 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ams`
--

-- --------------------------------------------------------

--
-- Table structure for table `annotations`
--

DROP TABLE IF EXISTS `annotations`;
CREATE TABLE IF NOT EXISTS `annotations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `assets_id` int(11) NOT NULL,
  `annotation` text NOT NULL,
  `annotation_type` varchar(255) DEFAULT NULL,
  `annotation_ref` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_annotation_assets1_idx` (`assets_id`),
  KEY `annotation_type` (`annotation_type`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

DROP TABLE IF EXISTS `assets`;
CREATE TABLE IF NOT EXISTS `assets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stations_id` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_assets_stations1_idx` (`stations_id`),
  KEY `created` (`created`),
  KEY `updated` (`updated`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `assets_asset_types`
--

DROP TABLE IF EXISTS `assets_asset_types`;
CREATE TABLE IF NOT EXISTS `assets_asset_types` (
  `assets_id` int(11) NOT NULL,
  `asset_types_id` int(11) NOT NULL,
  PRIMARY KEY (`assets_id`,`asset_types_id`),
  KEY `fk_assets_has_asset_types_asset_types1_idx` (`asset_types_id`),
  KEY `fk_assets_has_asset_types_assets1_idx` (`assets_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `assets_audience_levels`
--

DROP TABLE IF EXISTS `assets_audience_levels`;
CREATE TABLE IF NOT EXISTS `assets_audience_levels` (
  `assets_id` int(11) NOT NULL,
  `audience_levels_id` int(11) NOT NULL,
  PRIMARY KEY (`assets_id`,`audience_levels_id`),
  KEY `fk_assets_has_audience_levels_audience_levels1_idx` (`audience_levels_id`),
  KEY `fk_assets_has_audience_levels_assets1_idx` (`assets_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `assets_audience_ratings`
--

DROP TABLE IF EXISTS `assets_audience_ratings`;
CREATE TABLE IF NOT EXISTS `assets_audience_ratings` (
  `assets_id` int(11) NOT NULL,
  `audience_ratings_id` int(11) NOT NULL,
  PRIMARY KEY (`assets_id`,`audience_ratings_id`),
  KEY `fk_assets_has_audience_ratings_audience_ratings1_idx` (`audience_ratings_id`),
  KEY `fk_assets_has_audience_ratings_assets1_idx` (`assets_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `assets_contributors_roles`
--

DROP TABLE IF EXISTS `assets_contributors_roles`;
CREATE TABLE IF NOT EXISTS `assets_contributors_roles` (
  `assets_id` int(11) NOT NULL,
  `contributors_id` int(11) NOT NULL,
  `contributor_roles_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`assets_id`,`contributors_id`,`contributor_roles_id`),
  KEY `fk_assets_contributors_roles_contributor_roles1_idx` (`contributor_roles_id`),
  KEY `fk_assets_contributors_roles_assets1_idx` (`assets_id`),
  KEY `fk_assets_contributors_roles_contributors1` (`contributors_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `assets_creators_roles`
--

DROP TABLE IF EXISTS `assets_creators_roles`;
CREATE TABLE IF NOT EXISTS `assets_creators_roles` (
  `assets_id` int(11) NOT NULL,
  `creators_id` int(11) NOT NULL,
  `creator_roles_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`assets_id`,`creators_id`,`creator_roles_id`),
  KEY `fk_assets_creators_roles_creator_roles1_idx` (`creator_roles_id`),
  KEY `fk_assets_creators_roles_creators1_idx` (`creators_id`),
  KEY `fk_assets_creators_roles_assets1_idx` (`assets_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `assets_genres`
--

DROP TABLE IF EXISTS `assets_genres`;
CREATE TABLE IF NOT EXISTS `assets_genres` (
  `assets_id` int(11) NOT NULL,
  `genres_id` int(11) NOT NULL,
  PRIMARY KEY (`assets_id`,`genres_id`),
  KEY `fk_assets_has_genres_genres1_idx` (`genres_id`),
  KEY `fk_assets_has_genres_assets1_idx` (`assets_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `assets_publishers_role`
--

DROP TABLE IF EXISTS `assets_publishers_role`;
CREATE TABLE IF NOT EXISTS `assets_publishers_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `assets_id` int(11) NOT NULL,
  `publishers_id` int(11) NOT NULL,
  `publisher_roles_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`,`assets_id`),
  KEY `fk_assets_publishers_role_assets1_idx` (`assets_id`),
  KEY `fk_assets_publishers_role_publishers1_idx` (`publishers_id`),
  KEY `fk_assets_publishers_role_publisher_roles1_idx` (`publisher_roles_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `assets_relations`
--

DROP TABLE IF EXISTS `assets_relations`;
CREATE TABLE IF NOT EXISTS `assets_relations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `assets_id` int(11) NOT NULL,
  `relation_identifier` varchar(255) NOT NULL,
  `relation_types_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_asset_relations_assets1_idx` (`assets_id`),
  KEY `fk_asset_relations_relation_types1_idx` (`relation_types_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `assets_subjects`
--

DROP TABLE IF EXISTS `assets_subjects`;
CREATE TABLE IF NOT EXISTS `assets_subjects` (
  `assets_id` int(11) NOT NULL,
  `subjects_id` int(11) NOT NULL,
  KEY `fk_assets_has_subjects_subjects1_idx` (`subjects_id`),
  KEY `fk_assets_has_subjects_assets1_idx` (`assets_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `asset_dates`
--

DROP TABLE IF EXISTS `asset_dates`;
CREATE TABLE IF NOT EXISTS `asset_dates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `assets_id` int(11) NOT NULL,
  `asset_date` date NOT NULL,
  `date_types_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`,`assets_id`),
  KEY `fk_asset_dates_assets1_idx` (`assets_id`),
  KEY `fk_asset_dates_date_types1_idx` (`date_types_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `asset_descriptions`
--

DROP TABLE IF EXISTS `asset_descriptions`;
CREATE TABLE IF NOT EXISTS `asset_descriptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `assets_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `description_types_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`,`assets_id`),
  KEY `fk_descriptions_description_types1_idx` (`description_types_id`),
  KEY `fk_descriptions_assets1_idx` (`assets_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `asset_titles`
--

DROP TABLE IF EXISTS `asset_titles`;
CREATE TABLE IF NOT EXISTS `asset_titles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `assets_id` int(11) NOT NULL,
  `title` text NOT NULL,
  `asset_title_types_id` int(11) DEFAULT NULL,
  `title_source` varchar(255) DEFAULT NULL,
  `title_ref` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`,`assets_id`),
  KEY `fk_asset_title_assets1_idx` (`assets_id`),
  KEY `fk_asset_titles_asset_title_types1_idx` (`asset_title_types_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `asset_title_types`
--

DROP TABLE IF EXISTS `asset_title_types`;
CREATE TABLE IF NOT EXISTS `asset_title_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title_type` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `asset_types`
--

DROP TABLE IF EXISTS `asset_types`;
CREATE TABLE IF NOT EXISTS `asset_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_type` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `asset_type` (`asset_type`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `audience_levels`
--

DROP TABLE IF EXISTS `audience_levels`;
CREATE TABLE IF NOT EXISTS `audience_levels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `audience_level` varchar(255) NOT NULL,
  `audience_level_source` varchar(255) DEFAULT NULL,
  `audience_level_ref` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `audience_level` (`audience_level`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `audience_ratings`
--

DROP TABLE IF EXISTS `audience_ratings`;
CREATE TABLE IF NOT EXISTS `audience_ratings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `audience_rating` varchar(45) NOT NULL,
  `audience_rating_source` varchar(255) DEFAULT NULL,
  `audience_rating_ref` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `audience_rating` (`audience_rating`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `audit_trail`
--

DROP TABLE IF EXISTS `audit_trail`;
CREATE TABLE IF NOT EXISTS `audit_trail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `record_id` bigint(5) NOT NULL,
  `record` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `comments` text,
  `changed_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `ip_address` varchar(16) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `user_agent` varchar(150) COLLATE utf8_bin NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `contributors`
--

DROP TABLE IF EXISTS `contributors`;
CREATE TABLE IF NOT EXISTS `contributors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contributor_name` varchar(255) NOT NULL,
  `contributor_affiliation` varchar(255) DEFAULT NULL,
  `contributor_source` varchar(255) DEFAULT NULL,
  `contributor_ref` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `contributor_name` (`contributor_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `contributor_roles`
--

DROP TABLE IF EXISTS `contributor_roles`;
CREATE TABLE IF NOT EXISTS `contributor_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contributor_role` varchar(255) NOT NULL,
  `contributor_role_source` varchar(255) DEFAULT NULL,
  `contributor_role_ref` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `coverages`
--

DROP TABLE IF EXISTS `coverages`;
CREATE TABLE IF NOT EXISTS `coverages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `assets_id` int(11) NOT NULL,
  `coverage` text NOT NULL,
  `coverage_type` enum('spatial','temporal') NOT NULL DEFAULT 'temporal',
  PRIMARY KEY (`id`,`assets_id`),
  KEY `fk_coverages_assets1_idx` (`assets_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `creators`
--

DROP TABLE IF EXISTS `creators`;
CREATE TABLE IF NOT EXISTS `creators` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `creator_name` varchar(255) DEFAULT NULL,
  `creator_affiliation` varchar(255) DEFAULT NULL,
  `creator_source` varchar(255) DEFAULT NULL,
  `creator_ref` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `creator_name` (`creator_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `creator_roles`
--

DROP TABLE IF EXISTS `creator_roles`;
CREATE TABLE IF NOT EXISTS `creator_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `creator_role` varchar(255) DEFAULT NULL,
  `creator_role_source` varchar(255) DEFAULT NULL,
  `creator_role_ref` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `data_folders`
--

DROP TABLE IF EXISTS `data_folders`;
CREATE TABLE IF NOT EXISTS `data_folders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `folder_path` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `data_type` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `folder_status` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `folder_path` (`folder_path`(255))
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `data_rate_units`
--

DROP TABLE IF EXISTS `data_rate_units`;
CREATE TABLE IF NOT EXISTS `data_rate_units` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unit_of_measure` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `date_types`
--

DROP TABLE IF EXISTS `date_types`;
CREATE TABLE IF NOT EXISTS `date_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_type` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `description_types`
--

DROP TABLE IF EXISTS `description_types`;
CREATE TABLE IF NOT EXISTS `description_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description_type` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `email_queue`
--

DROP TABLE IF EXISTS `email_queue`;
CREATE TABLE IF NOT EXISTS `email_queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template_id` int(11) DEFAULT NULL,
  `email_from` varchar(100) DEFAULT NULL,
  `email_reply_to` varchar(100) DEFAULT NULL,
  `email_to` varchar(100) DEFAULT NULL,
  `email_subject` varchar(500) DEFAULT NULL,
  `email_type` enum('plain','html') DEFAULT NULL,
  `email_body` text,
  `created_at` datetime DEFAULT NULL,
  `sent_at` datetime DEFAULT NULL,
  `is_sent` tinyint(4) DEFAULT '1' COMMENT '1 for not 2 for sent',
  `is_email_read` tinyint(1) DEFAULT '1' COMMENT '1 for not read 2 for read',
  `read_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

DROP TABLE IF EXISTS `email_templates`;
CREATE TABLE IF NOT EXISTS `email_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `system_id` varchar(150) DEFAULT NULL,
  `subject` varchar(150) DEFAULT NULL,
  `crawford_contact_detail` text,
  `is_crawford` tinyint(1) NOT NULL DEFAULT '1',
  `body_plain` text,
  `body_html` text,
  `email_type` enum('plain','html') NOT NULL DEFAULT 'html',
  `email_from` varchar(150) DEFAULT NULL,
  `reply_to` varchar(150) DEFAULT NULL,
  `replaceables` varchar(150) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `essence_tracks`
--

DROP TABLE IF EXISTS `essence_tracks`;
CREATE TABLE IF NOT EXISTS `essence_tracks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `instantiations_id` int(11) NOT NULL,
  `essence_track_types_id` int(11) DEFAULT NULL,
  `standard` varchar(255) DEFAULT NULL,
  `frame_rate` varchar(45) DEFAULT NULL,
  `playback_speed` varchar(45) DEFAULT NULL,
  `sampling_rate` varchar(45) DEFAULT NULL,
  `bit_depth` int(11) DEFAULT NULL,
  `aspect_ratio` varchar(5) DEFAULT NULL,
  `time_start` time DEFAULT NULL,
  `duration` time DEFAULT NULL,
  `language` varchar(45) DEFAULT NULL,
  `data_rate` int(11) DEFAULT NULL,
  `data_rate_units_id` int(11) DEFAULT NULL,
  `essence_track_frame_sizes_id` int(11) NOT NULL,
  PRIMARY KEY (`id`,`instantiations_id`,`essence_track_frame_sizes_id`),
  KEY `fk_essence_tracks_instantiations1_idx` (`instantiations_id`),
  KEY `fk_essence_tracks_essence_track_types1_idx` (`essence_track_types_id`),
  KEY `fk_essence_tracks_data_rate_units1_idx` (`data_rate_units_id`),
  KEY `fk_essence_tracks_essence_track_frame_sizes1_idx` (`essence_track_frame_sizes_id`),
  KEY `standard` (`standard`),
  KEY `frame_rate` (`frame_rate`),
  KEY `playback_speed` (`playback_speed`),
  KEY `sampling_rate` (`sampling_rate`),
  KEY `bit_depth` (`bit_depth`),
  KEY `aspect_ratio` (`aspect_ratio`),
  KEY `aspect_ratio_2` (`aspect_ratio`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `essence_track_annotations`
--

DROP TABLE IF EXISTS `essence_track_annotations`;
CREATE TABLE IF NOT EXISTS `essence_track_annotations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `essence_tracks_id` int(11) NOT NULL,
  `annotation` text NOT NULL,
  `annotation_type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`,`essence_tracks_id`),
  KEY `fk_essence_track_annotations_essence_tracks1_idx` (`essence_tracks_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `essence_track_encodings`
--

DROP TABLE IF EXISTS `essence_track_encodings`;
CREATE TABLE IF NOT EXISTS `essence_track_encodings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `essence_tracks_id` int(11) NOT NULL,
  `encoding` varchar(255) NOT NULL,
  `encoding_source` varchar(255) DEFAULT NULL,
  `encoding_ref` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_essence_track_encoding_essence_tracks1_idx` (`essence_tracks_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `essence_track_frame_sizes`
--

DROP TABLE IF EXISTS `essence_track_frame_sizes`;
CREATE TABLE IF NOT EXISTS `essence_track_frame_sizes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `width` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `essence_track_identifiers`
--

DROP TABLE IF EXISTS `essence_track_identifiers`;
CREATE TABLE IF NOT EXISTS `essence_track_identifiers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `essence_tracks_id` int(11) NOT NULL,
  `essence_track_identifiers` varchar(255) NOT NULL,
  `essence_track_identifier_source` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`,`essence_tracks_id`),
  KEY `fk_essence_track_identifiers_essence_tracks1_idx` (`essence_tracks_id`),
  KEY `essence_track_identifiers` (`essence_track_identifiers`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `essence_track_types`
--

DROP TABLE IF EXISTS `essence_track_types`;
CREATE TABLE IF NOT EXISTS `essence_track_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `essence_track_type` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `instantiations_id` int(11) NOT NULL,
  `event_types_id` int(11) NOT NULL,
  `event_date` date NOT NULL,
  `event_outcome` tinyint(1) DEFAULT NULL COMMENT '0 for fail, 1 for pass',
  `event_note` text,
  PRIMARY KEY (`id`,`instantiations_id`),
  KEY `fk_events_event_types1_idx` (`event_types_id`),
  KEY `fk_events_instantiations1_idx` (`instantiations_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `event_types`
--

DROP TABLE IF EXISTS `event_types`;
CREATE TABLE IF NOT EXISTS `event_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_type` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `export_csv_job`
--

DROP TABLE IF EXISTS `export_csv_job`;
CREATE TABLE IF NOT EXISTS `export_csv_job` (
  `id` bigint(5) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(5) NOT NULL,
  `export_query` text NOT NULL,
  `query_loop` int(11) NOT NULL DEFAULT '0',
  `type` enum('limited_csv','pbcore') NOT NULL DEFAULT 'limited_csv',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `file_path` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `extensions`
--

DROP TABLE IF EXISTS `extensions`;
CREATE TABLE IF NOT EXISTS `extensions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `assets_id` int(11) NOT NULL,
  `extension_element` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `extension_value` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `extension_authority` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`,`assets_id`),
  KEY `fk_extensions_assets1_idx` (`assets_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `extensions_backup`
--

DROP TABLE IF EXISTS `extensions_backup`;
CREATE TABLE IF NOT EXISTS `extensions_backup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `assets_id` int(11) NOT NULL,
  `extension_element` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `extension_value` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `extension_authority` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`,`assets_id`),
  KEY `fk_extensions_assets1_idx` (`assets_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `formats_backup`
--

DROP TABLE IF EXISTS `formats_backup`;
CREATE TABLE IF NOT EXISTS `formats_backup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `instantiations_id` int(11) NOT NULL,
  `format_name` varchar(255) NOT NULL,
  `format_type` enum('physical','digital') NOT NULL DEFAULT 'physical',
  PRIMARY KEY (`id`,`instantiations_id`),
  KEY `fk_instantiation_format_instantiations1_idx` (`instantiations_id`),
  KEY `format_name` (`format_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `formats_backup_2`
--

DROP TABLE IF EXISTS `formats_backup_2`;
CREATE TABLE IF NOT EXISTS `formats_backup_2` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `instantiations_id` int(11) NOT NULL,
  `format_name` varchar(255) NOT NULL,
  `format_type` enum('physical','digital') NOT NULL DEFAULT 'physical',
  PRIMARY KEY (`id`,`instantiations_id`),
  KEY `fk_instantiation_format_instantiations1_idx` (`instantiations_id`),
  KEY `format_name` (`format_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `generations`
--

DROP TABLE IF EXISTS `generations`;
CREATE TABLE IF NOT EXISTS `generations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `generation` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `generation` (`generation`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `generations_backup`
--

DROP TABLE IF EXISTS `generations_backup`;
CREATE TABLE IF NOT EXISTS `generations_backup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `generation` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `generation` (`generation`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `genres`
--

DROP TABLE IF EXISTS `genres`;
CREATE TABLE IF NOT EXISTS `genres` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `genre` varchar(255) NOT NULL,
  `genre_source` varchar(255) DEFAULT NULL,
  `genre_ref` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `google_refine`
--

DROP TABLE IF EXISTS `google_refine`;
CREATE TABLE IF NOT EXISTS `google_refine` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `project_id` bigint(5) DEFAULT NULL,
  `project_name` varchar(255) NOT NULL,
  `project_url` varchar(255) DEFAULT NULL,
  `refine_type` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `export_query` text NOT NULL,
  `export_csv_path` varchar(255) DEFAULT NULL,
  `import_csv_path` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `google_spreadsheets`
--

DROP TABLE IF EXISTS `google_spreadsheets`;
CREATE TABLE IF NOT EXISTS `google_spreadsheets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `spreadsheet_name` varchar(255) NOT NULL,
  `spreadsheet_id` varchar(255) NOT NULL,
  `spreadsheet_url` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `identifiers`
--

DROP TABLE IF EXISTS `identifiers`;
CREATE TABLE IF NOT EXISTS `identifiers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `assets_id` int(11) NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `identifier_source` varchar(255) NOT NULL,
  `identifier_ref` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`,`assets_id`),
  KEY `fk_identifiers_assets1_idx` (`assets_id`),
  KEY `index_identifier_source_assets_id` (`assets_id`,`identifier_source`),
  KEY `identifier` (`identifier`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `instanciation_temporary`
--

DROP TABLE IF EXISTS `instanciation_temporary`;
CREATE TABLE IF NOT EXISTS `instanciation_temporary` (
  `id` int(11) NOT NULL,
  `assets_id` int(11) DEFAULT NULL,
  `organization` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `standard` varchar(255) DEFAULT NULL,
  `data_rate` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `tracks` varchar(255) DEFAULT NULL,
  `digitized` varchar(255) DEFAULT NULL,
  `language` varchar(255) DEFAULT NULL,
  `actual_duration` varchar(255) DEFAULT NULL,
  `projected_duration` varchar(255) DEFAULT NULL,
  `file_size_unit_of_measure` varchar(255) DEFAULT NULL,
  `file_size` varchar(255) DEFAULT NULL,
  `channel_configuration` varchar(255) DEFAULT NULL,
  `alternative_modes` varchar(255) DEFAULT NULL,
  `data_rate_unit_of_measure` varchar(255) DEFAULT NULL,
  `dates` varchar(255) DEFAULT NULL,
  `date_type` varchar(255) DEFAULT NULL,
  `media_type` varchar(255) DEFAULT NULL,
  `format_type` varchar(255) DEFAULT NULL,
  `format_name` varchar(255) DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL,
  `generation` varchar(255) DEFAULT NULL,
  `facet_generation` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `outcome_event` varchar(255) DEFAULT NULL,
  `event_type` varchar(255) DEFAULT NULL,
  `event_date` varchar(255) DEFAULT NULL,
  `instantiation_identifier` varchar(255) DEFAULT NULL,
  `instantiation_source` varchar(255) DEFAULT NULL,
  `instantiation_dimension` varchar(255) DEFAULT NULL,
  `unit_of_measure` varchar(255) DEFAULT NULL,
  `track_standard` varchar(255) DEFAULT NULL,
  `track_duration` varchar(255) DEFAULT NULL,
  `track_language` varchar(255) DEFAULT NULL,
  `track_frame_rate` varchar(255) DEFAULT NULL,
  `track_playback_speed` varchar(255) DEFAULT NULL,
  `track_sampling_rate` varchar(255) DEFAULT NULL,
  `track_bit_depth` varchar(255) DEFAULT NULL,
  `track_aspect_ratio` varchar(255) DEFAULT NULL,
  `track_data_rate` varchar(255) DEFAULT NULL,
  `track_unit_of_measure` varchar(255) DEFAULT NULL,
  `track_essence_track_type` varchar(255) DEFAULT NULL,
  `track_width` varchar(255) DEFAULT NULL,
  `track_height` varchar(255) DEFAULT NULL,
  `track_encoding` varchar(255) DEFAULT NULL,
  `track_annotation` varchar(255) DEFAULT NULL,
  `track_annotation_type` varchar(255) DEFAULT NULL,
  `ins_annotation` varchar(255) DEFAULT NULL,
  `ins_annotation_type` varchar(255) DEFAULT NULL,
  `guid_identifier` varchar(255) DEFAULT NULL,
  `asset_title` varchar(255) DEFAULT NULL,
  `asset_subject` varchar(255) DEFAULT NULL,
  `asset_coverage` varchar(255) DEFAULT NULL,
  `asset_genre` varchar(255) DEFAULT NULL,
  `asset_publisher_name` varchar(255) DEFAULT NULL,
  `asset_description` varchar(255) DEFAULT NULL,
  `asset_creator_name` varchar(255) DEFAULT NULL,
  `asset_creator_affiliation` varchar(255) DEFAULT NULL,
  `asset_contributor_name` varchar(255) DEFAULT NULL,
  `asset_contributor_affiliation` varchar(255) DEFAULT NULL,
  `asset_rights` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `instantiations`
--

DROP TABLE IF EXISTS `instantiations`;
CREATE TABLE IF NOT EXISTS `instantiations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `assets_id` int(11) NOT NULL,
  `instantiation_media_type_id` int(11) NOT NULL,
  `digitized` tinyint(1) DEFAULT NULL,
  `time_start` varchar(255) DEFAULT NULL,
  `projected_duration` varchar(255) DEFAULT NULL,
  `actual_duration` time DEFAULT NULL,
  `standard` varchar(255) DEFAULT NULL,
  `location` text NOT NULL,
  `instantiation_colors_id` int(11) DEFAULT NULL,
  `tracks` varchar(255) DEFAULT NULL,
  `channel_configuration` varchar(255) DEFAULT NULL,
  `language` varchar(45) DEFAULT NULL,
  `alternative_modes` varchar(255) DEFAULT NULL,
  `data_rate` int(11) DEFAULT NULL,
  `data_rate_units_id` int(11) DEFAULT NULL,
  `file_size` int(11) DEFAULT NULL,
  `file_size_unit_of_measure` varchar(45) DEFAULT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_instantiations_assets1_idx` (`assets_id`),
  KEY `fk_instantiations_instantiation_media_type1_idx` (`instantiation_media_type_id`),
  KEY `fk_instantiations_instantiation_colors1_idx` (`instantiation_colors_id`),
  KEY `fk_instantiations_data_rate_units1_idx` (`data_rate_units_id`),
  KEY `digitized` (`digitized`),
  KEY `standard` (`standard`),
  KEY `tracks` (`tracks`),
  KEY `channel_configuration` (`channel_configuration`),
  KEY `language` (`language`),
  KEY `alternative_modes` (`alternative_modes`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `instantiations_backup`
--

DROP TABLE IF EXISTS `instantiations_backup`;
CREATE TABLE IF NOT EXISTS `instantiations_backup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `assets_id` int(11) NOT NULL,
  `instantiation_media_type_id` int(11) NOT NULL,
  `digitized` tinyint(1) DEFAULT NULL,
  `time_start` varchar(255) DEFAULT NULL,
  `projected_duration` varchar(255) DEFAULT NULL,
  `actual_duration` time DEFAULT NULL,
  `standard` varchar(255) DEFAULT NULL,
  `location` text NOT NULL,
  `instantiation_colors_id` int(11) DEFAULT NULL,
  `tracks` varchar(255) DEFAULT NULL,
  `channel_configuration` varchar(255) DEFAULT NULL,
  `language` varchar(45) DEFAULT NULL,
  `alternative_modes` varchar(255) DEFAULT NULL,
  `data_rate` int(11) DEFAULT NULL,
  `data_rate_units_id` int(11) DEFAULT NULL,
  `file_size` int(11) DEFAULT NULL,
  `file_size_unit_of_measure` varchar(45) DEFAULT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_instantiations_assets1_idx` (`assets_id`),
  KEY `fk_instantiations_instantiation_media_type1_idx` (`instantiation_media_type_id`),
  KEY `fk_instantiations_instantiation_colors1_idx` (`instantiation_colors_id`),
  KEY `fk_instantiations_data_rate_units1_idx` (`data_rate_units_id`),
  KEY `digitized` (`digitized`),
  KEY `standard` (`standard`),
  KEY `tracks` (`tracks`),
  KEY `channel_configuration` (`channel_configuration`),
  KEY `language` (`language`),
  KEY `alternative_modes` (`alternative_modes`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `instantiation_annotations`
--

DROP TABLE IF EXISTS `instantiation_annotations`;
CREATE TABLE IF NOT EXISTS `instantiation_annotations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `instantiations_id` int(11) NOT NULL,
  `annotation` text NOT NULL,
  `annotation_type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`,`instantiations_id`),
  KEY `fk_instantiation_annotation_instantiations1_idx` (`instantiations_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `instantiation_colors`
--

DROP TABLE IF EXISTS `instantiation_colors`;
CREATE TABLE IF NOT EXISTS `instantiation_colors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `color` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `instantiation_dates`
--

DROP TABLE IF EXISTS `instantiation_dates`;
CREATE TABLE IF NOT EXISTS `instantiation_dates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `instantiations_id` int(11) NOT NULL,
  `instantiation_date` date DEFAULT NULL,
  `date_types_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`,`instantiations_id`,`date_types_id`),
  KEY `fk_instantiation_dates_instantiations1_idx` (`instantiations_id`),
  KEY `fk_instantiation_dates_date_types1_idx` (`date_types_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `instantiation_dimensions`
--

DROP TABLE IF EXISTS `instantiation_dimensions`;
CREATE TABLE IF NOT EXISTS `instantiation_dimensions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `instantiations_id` int(11) NOT NULL,
  `instantiation_dimension` varchar(45) NOT NULL,
  `unit_of_measure` varchar(45) NOT NULL,
  PRIMARY KEY (`id`,`instantiations_id`),
  KEY `fk_instantiation_dimensions_instantiations1_idx` (`instantiations_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `instantiation_formats`
--

DROP TABLE IF EXISTS `instantiation_formats`;
CREATE TABLE IF NOT EXISTS `instantiation_formats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `instantiations_id` int(11) NOT NULL,
  `format_name` varchar(255) NOT NULL,
  `format_type` enum('physical','digital') NOT NULL DEFAULT 'physical',
  PRIMARY KEY (`id`,`instantiations_id`),
  KEY `fk_instantiation_format_instantiations1_idx` (`instantiations_id`),
  KEY `format_name` (`format_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `instantiation_generations`
--

DROP TABLE IF EXISTS `instantiation_generations`;
CREATE TABLE IF NOT EXISTS `instantiation_generations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `instantiations_id` int(11) NOT NULL,
  `generations_id` int(11) NOT NULL,
  PRIMARY KEY (`id`,`instantiations_id`),
  KEY `fk_instantiation_generations_instantiations1_idx` (`instantiations_id`),
  KEY `fk_instantiation_generations_generations1_idx` (`generations_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `instantiation_generations_backup`
--

DROP TABLE IF EXISTS `instantiation_generations_backup`;
CREATE TABLE IF NOT EXISTS `instantiation_generations_backup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `instantiations_id` int(11) NOT NULL,
  `generations_id` int(11) NOT NULL,
  PRIMARY KEY (`id`,`instantiations_id`),
  KEY `fk_instantiation_generations_instantiations1_idx` (`instantiations_id`),
  KEY `fk_instantiation_generations_generations1_idx` (`generations_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `instantiation_identifier`
--

DROP TABLE IF EXISTS `instantiation_identifier`;
CREATE TABLE IF NOT EXISTS `instantiation_identifier` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `instantiations_id` int(11) NOT NULL,
  `instantiation_identifier` varchar(255) NOT NULL,
  `instantiation_source` varchar(255) NOT NULL,
  PRIMARY KEY (`id`,`instantiations_id`),
  KEY `fk_instantiation_identifier_instantiations1_idx` (`instantiations_id`),
  KEY `instantiation_identifier` (`instantiation_identifier`),
  KEY `instantiation_source` (`instantiation_source`),
  KEY `instantiation_source_2` (`instantiation_source`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `instantiation_media_types`
--

DROP TABLE IF EXISTS `instantiation_media_types`;
CREATE TABLE IF NOT EXISTS `instantiation_media_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `media_type` varchar(45) NOT NULL DEFAULT 'Moving Image' COMMENT 'Types =\\nMoving Image \\nSound',
  PRIMARY KEY (`id`),
  KEY `media_type` (`media_type`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `instantiation_relations`
--

DROP TABLE IF EXISTS `instantiation_relations`;
CREATE TABLE IF NOT EXISTS `instantiation_relations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `instantiations_id` int(11) NOT NULL,
  `relation_identifier` varchar(255) NOT NULL,
  `relation_types_id` int(11) NOT NULL,
  PRIMARY KEY (`id`,`instantiations_id`),
  KEY `fk_instantiation_relations_relation_types1_idx` (`relation_types_id`),
  KEY `fk_instantiation_relations_instantiations1_idx` (`instantiations_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `language_lookup`
--

DROP TABLE IF EXISTS `language_lookup`;
CREATE TABLE IF NOT EXISTS `language_lookup` (
  `Id` varchar(255) NOT NULL,
  `Print_Name` varchar(255) NOT NULL,
  `Inverted_Name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

DROP TABLE IF EXISTS `login_attempts`;
CREATE TABLE IF NOT EXISTS `login_attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(40) COLLATE utf8_bin NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `station_id` int(11) NOT NULL,
  `msg_type` varchar(100) DEFAULT NULL,
  `msg_status` enum('read','unread') NOT NULL DEFAULT 'unread',
  `subject` text,
  `msg_extras` text,
  `receiver_folder` enum('inbox','sent','trash') NOT NULL DEFAULT 'inbox',
  `sender_folder` enum('inbox','sent','trash') NOT NULL DEFAULT 'sent',
  `message` text,
  `created_at` datetime NOT NULL,
  `read_at` datetime DEFAULT NULL,
  `email_queue_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `version` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `mint_import`
--

DROP TABLE IF EXISTS `mint_import`;
CREATE TABLE IF NOT EXISTS `mint_import` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `station_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mint_import_info`
--

DROP TABLE IF EXISTS `mint_import_info`;
CREATE TABLE IF NOT EXISTS `mint_import_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `folder` varchar(255) NOT NULL COMMENT 'name of the transformed folder',
  `station_id` int(11) NOT NULL,
  `path` text NOT NULL COMMENT 'complete path of imported file',
  `is_processed` tinyint(1) NOT NULL COMMENT '1 for complete 0 for incomplete',
  `status_reason` varchar(150) NOT NULL,
  `processed_at` datetime DEFAULT NULL COMMENT 'time when file is processed',
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mint_transformation`
--

DROP TABLE IF EXISTS `mint_transformation`;
CREATE TABLE IF NOT EXISTS `mint_transformation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mint_user_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `transformed_id` int(11) NOT NULL,
  `mint_id_approved_by` int(11) DEFAULT NULL,
  `user_id_approved_by` int(11) DEFAULT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=waiting 1=rejected 2=approved',
  `is_downloaded` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 need to download file, 1= file already downloaded',
  `folder_name` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `nominations`
--

DROP TABLE IF EXISTS `nominations`;
CREATE TABLE IF NOT EXISTS `nominations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `instantiations_id` int(11) NOT NULL,
  `nomination_status_id` int(11) NOT NULL,
  `nomination_reason` varchar(255) DEFAULT NULL,
  `nominated_by` int(11) DEFAULT NULL,
  `nominated_at` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`,`instantiations_id`),
  KEY `fk_nominations_nomination_status1_idx` (`nomination_status_id`),
  KEY `fk_nomination_assets1_idx` (`instantiations_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `nominations_backup`
--

DROP TABLE IF EXISTS `nominations_backup`;
CREATE TABLE IF NOT EXISTS `nominations_backup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `instantiations_id` int(11) NOT NULL,
  `nomination_status_id` int(11) NOT NULL,
  `nomination_reason` varchar(255) DEFAULT NULL,
  `nominated_by` int(11) DEFAULT NULL,
  `nominated_at` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`,`instantiations_id`),
  KEY `fk_nominations_nomination_status1_idx` (`nomination_status_id`),
  KEY `fk_nomination_assets1_idx` (`instantiations_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `nomination_status`
--

DROP TABLE IF EXISTS `nomination_status`;
CREATE TABLE IF NOT EXISTS `nomination_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pbcore_element_types`
--

DROP TABLE IF EXISTS `pbcore_element_types`;
CREATE TABLE IF NOT EXISTS `pbcore_element_types` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pbcore_picklist_value_by_type`
--

DROP TABLE IF EXISTS `pbcore_picklist_value_by_type`;
CREATE TABLE IF NOT EXISTS `pbcore_picklist_value_by_type` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `element_type_id` bigint(11) NOT NULL,
  `value` varchar(255) NOT NULL,
  `display_value` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `data` text COLLATE utf8_bin,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `process_pbcore_data`
--

DROP TABLE IF EXISTS `process_pbcore_data`;
CREATE TABLE IF NOT EXISTS `process_pbcore_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_type` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `file_path` text COLLATE utf8_unicode_ci NOT NULL,
  `is_processed` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `data_folder_id` int(11) NOT NULL,
  `processed_at` datetime NOT NULL,
  `processed_start_at` datetime NOT NULL,
  `status_reason` varchar(150) COLLATE utf8_unicode_ci DEFAULT 'Not processed',
  PRIMARY KEY (`id`),
  KEY `data_folder_id` (`data_folder_id`),
  KEY `file_path` (`file_path`(255))
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `publishers`
--

DROP TABLE IF EXISTS `publishers`;
CREATE TABLE IF NOT EXISTS `publishers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `publisher` text NOT NULL,
  `publisher_affiliation` varchar(255) DEFAULT NULL,
  `publisher_ref` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `publisher_roles`
--

DROP TABLE IF EXISTS `publisher_roles`;
CREATE TABLE IF NOT EXISTS `publisher_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `publisher_role` varchar(255) NOT NULL,
  `publisher_role_source` varchar(255) DEFAULT NULL,
  `publisher_role_ref` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `relation_types`
--

DROP TABLE IF EXISTS `relation_types`;
CREATE TABLE IF NOT EXISTS `relation_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `relation_type` varchar(45) NOT NULL,
  `relation_type_source` varchar(45) DEFAULT NULL,
  `relation_type_ref` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

DROP TABLE IF EXISTS `reports`;
CREATE TABLE IF NOT EXISTS `reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `report_type` varchar(255) NOT NULL,
  `filters` text NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `rights_summaries`
--

DROP TABLE IF EXISTS `rights_summaries`;
CREATE TABLE IF NOT EXISTS `rights_summaries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `assets_id` int(11) NOT NULL,
  `rights` text NOT NULL,
  `rights_link` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`,`assets_id`),
  KEY `fk_rights_summaries_assets1_idx` (`assets_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(30) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=6;

INSERT INTO `roles` (`id`, `parent_id`, `name`) VALUES
(1, 0, 'Super Admin'),
(2, 0, 'CPB Admin'),
(3, 0, 'Station Admin'),
(4, 0, 'Station User'),
(5, 0, 'Crawford Project Manager');
-- --------------------------------------------------------

--
-- Table structure for table `rotate_indexes`
--

DROP TABLE IF EXISTS `rotate_indexes`;
CREATE TABLE IF NOT EXISTS `rotate_indexes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('assets','instantiations','stations') NOT NULL,
  `index_name` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `output` text,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sample_table`
--

DROP TABLE IF EXISTS `sample_table`;
CREATE TABLE IF NOT EXISTS `sample_table` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `location` varchar(255) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stations`
--

DROP TABLE IF EXISTS `stations`;
CREATE TABLE IF NOT EXISTS `stations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cpb_id` varchar(255) NOT NULL,
  `station_name` varchar(255) NOT NULL,
  `type` int(5) NOT NULL COMMENT '0 for Radio,1 for TV,2 for Joint',
  `address_primary` text NOT NULL,
  `address_secondary` text,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `zip` varchar(255) NOT NULL,
  `allocated_hours` float NOT NULL,
  `allocated_buffer` float NOT NULL,
  `total_allocated` float NOT NULL COMMENT 'allocated hours + allocated buffer',
  `nominated_hours_final` float DEFAULT NULL,
  `nominated_buffer_final` float DEFAULT NULL,
  `is_certified` tinyint(4) NOT NULL DEFAULT '0',
  `is_agreed` tinyint(4) NOT NULL DEFAULT '0',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `aacip_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cpb_id` (`cpb_id`),
  KEY `index_station_name` (`station_name`),
  KEY `state` (`state`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stations_aacip_id`
--

DROP TABLE IF EXISTS `stations_aacip_id`;
CREATE TABLE IF NOT EXISTS `stations_aacip_id` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stations_backup`
--

DROP TABLE IF EXISTS `stations_backup`;
CREATE TABLE IF NOT EXISTS `stations_backup` (
  `id` bigint(5) NOT NULL AUTO_INCREMENT,
  `station_id` bigint(5) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `is_certified` tinyint(1) DEFAULT NULL,
  `is_agreed` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

DROP TABLE IF EXISTS `subjects`;
CREATE TABLE IF NOT EXISTS `subjects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) NOT NULL,
  `subject_source` varchar(255) DEFAULT NULL,
  `subject_ref` varchar(255) DEFAULT NULL,
  `subjects_types_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `subject_types`
--

DROP TABLE IF EXISTS `subject_types`;
CREATE TABLE IF NOT EXISTS `subject_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject_type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `temp_formats`
--

DROP TABLE IF EXISTS `temp_formats`;
CREATE TABLE IF NOT EXISTS `temp_formats` (
  `id` int(11) NOT NULL,
  `format` varchar(255) NOT NULL,
  `format_type` varchar(255) NOT NULL DEFAULT 'Digital',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `temp_generations`
--

DROP TABLE IF EXISTS `temp_generations`;
CREATE TABLE IF NOT EXISTS `temp_generations` (
  `id` int(11) NOT NULL,
  `generation` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tracking_info`
--

DROP TABLE IF EXISTS `tracking_info`;
CREATE TABLE IF NOT EXISTS `tracking_info` (
  `id` bigint(5) NOT NULL AUTO_INCREMENT,
  `station_id` bigint(5) NOT NULL,
  `ship_date` date NOT NULL,
  `ship_to` varchar(255) NOT NULL,
  `ship_via` enum('fedex','truck','UPS','etc') NOT NULL,
  `tracking_no` varchar(255) NOT NULL,
  `no_box_shipped` varchar(255) NOT NULL,
  `media_received_date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL DEFAULT '1',
  `station_id` bigint(5) DEFAULT NULL,
  `username` varchar(25) COLLATE utf8_bin NOT NULL,
  `password` varchar(34) COLLATE utf8_bin NOT NULL,
  `email` varchar(100) COLLATE utf8_bin NOT NULL,
  `is_secondary` tinyint(1) NOT NULL DEFAULT '0',
  `banned` tinyint(1) NOT NULL DEFAULT '0',
  `ban_reason` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `newpass` varchar(34) COLLATE utf8_bin DEFAULT NULL,
  `newpass_key` varchar(32) COLLATE utf8_bin DEFAULT NULL,
  `newpass_time` datetime DEFAULT NULL,
  `last_ip` varchar(40) COLLATE utf8_bin NOT NULL,
  `last_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2;
--Password is test@123
INSERT INTO `users` (`id`, `role_id`, `station_id`, `username`, `password`, `email`, `is_secondary`, `banned`, `ban_reason`, `newpass`, `newpass_key`, `newpass_time`, `last_ip`, `last_login`, `created`, `modified`) VALUES
(1, 1, NULL, 'admin', '$1$oxvSCFPn$EjswHnLVZhXyAkEYfJTW10', 'test@test.com', 0, 0, NULL, NULL, NULL, NULL, '202.166.163.98', '2014-02-27 09:14:22', '2008-11-30 04:56:32', '2014-02-27 14:14:22');

-- --------------------------------------------------------

--
-- Table structure for table `user_autologin`
--

DROP TABLE IF EXISTS `user_autologin`;
CREATE TABLE IF NOT EXISTS `user_autologin` (
  `key_id` char(32) COLLATE utf8_bin NOT NULL,
  `user_id` mediumint(8) NOT NULL DEFAULT '0',
  `user_agent` varchar(150) COLLATE utf8_bin NOT NULL,
  `last_ip` varchar(40) COLLATE utf8_bin NOT NULL,
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`key_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `user_profile`
--

DROP TABLE IF EXISTS `user_profile`;
CREATE TABLE IF NOT EXISTS `user_profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `mint_user_id` bigint(5) DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `phone_no` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `fax` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `address` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2;

INSERT INTO `user_profile` (`id`, `user_id`, `mint_user_id`, `first_name`, `last_name`, `phone_no`, `title`, `fax`, `address`) VALUES
(1, 1, NULL, 'Admin', 'AMS', '123456789', 'Admin', '764545432', 'Address');
-- --------------------------------------------------------

--
-- Table structure for table `user_settings`
--

DROP TABLE IF EXISTS `user_settings`;
CREATE TABLE IF NOT EXISTS `user_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `table_type` enum('assets','instantiation') NOT NULL,
  `table_subtype` enum('simple','full') NOT NULL DEFAULT 'full',
  `view_settings` text,
  `frozen_column` int(11) NOT NULL DEFAULT '0' COMMENT 'insert number of column',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_temp`
--

DROP TABLE IF EXISTS `user_temp`;
CREATE TABLE IF NOT EXISTS `user_temp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_bin NOT NULL,
  `password` varchar(34) COLLATE utf8_bin NOT NULL,
  `email` varchar(100) COLLATE utf8_bin NOT NULL,
  `activation_key` varchar(50) COLLATE utf8_bin NOT NULL,
  `last_ip` varchar(40) COLLATE utf8_bin NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
