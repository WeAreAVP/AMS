<?php

/**
 * AMS Archive Management System
 * 
 * PHP version 5
 * 
 * @category AMS
 * @package  CI
 * @author   Nouman Tayyab <nouman@geekschicago.com>
 * @license  CPB http://ams.avpreserve.com
 * @version  GIT: <$Id>
 * @link     http://ams.avpreserve.com

 */

/**
 * Refinecron Class
 *
 * @category   AMS
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@geekschicago.com>
 * @license    CPB http://ams.avpreserve.com
 * @link       http://ams.avpreserve.com
 */
class Refinecrons extends CI_Controller
{

	/**
	 *
	 * Constructor. Load Model and Library.
	 * 
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->library('googlerefine');
		$this->load->model('refine_modal');
		$this->load->model('sphinx_model', 'sphinx');
		$this->load->model('instantiations_model', 'instantiation');
		$this->load->model('assets_model');
		$this->load->model('dx_auth/users', 'users');
		set_time_limit(0);
		@ini_set("memory_limit", "1000M"); # 1GB
		@ini_set("max_execution_time", 999999999999); # 1GB
	}

	/**
	 * Create a google refine project and returns the project URL.
	 * 
	 * @param string $path
	 * @param string $filename
	 * @param integer $job_id
	 * @return boolean
	 */
	function create($path, $filename, $job_id)
	{
		$project_name = $filename;
		$file_path = $path;
		$data = $this->googlerefine->create_project($project_name, $file_path);
		if ($data)
		{
			$data['is_active'] = 1;
			$data['project_name'] = $filename;
			$data['project_id'] = $data['project_id'];
			$data['project_url'] = $data['project_url'];
			debug($data, FALSE);
			myLog('Successfully Created AMS Refine Project');

			$this->refine_modal->update_job($job_id, $data);
			return $data['project_url'];
		}
		return FALSE;
	}

	/**
	 * Make CSV File for google refinement.
	 * 
	 * @return 
	 */
	public function make_refine_csv()
	{

		$record = $this->refine_modal->get_job_for_refine();
		if (count($record) > 0)
		{
			if ($record->refine_type == 'instantiation')
			{
				$filename = 'google_refine_' . time() . '.csv';
				$fp = fopen("uploads/google_refine/$filename", 'a');

				$line = "Organization,Asset Title,Description,Instantiation ID,Instantiation ID Source,Generation,Nomination,Nomination Reason,Media Type,Language,__Ins_id,__identifier_id,__gen_id\n";
				fputs($fp, $line);
				fclose($fp);
				$db_count = 0;
				$offset = 0;
				while ($db_count == 0)
				{
					$custom_query = $record->export_query;
					$custom_query.=' LIMIT ' . ($offset * 15000) . ', 15000';

					$records = $this->refine_modal->get_csv_records($custom_query);

					$fp = fopen("uploads/google_refine/$filename", 'a');
					$line = '';
					foreach ($records as $value)
					{
						$line.='"' . str_replace('"', '""', str_replace("\r", "", str_replace("\n", "", str_replace("\"", "\"\"", $value->organization)))) . '",';
						$line.='"' . str_replace('"', '""', str_replace("\r", "", str_replace("\n", "", str_replace("\"", "\"\"", $value->asset_title)))) . '",';
						$line.='"' . str_replace('"', '""', str_replace("\r", "", str_replace("\n", "", str_replace("\"", "\"\"", $value->description)))) . '",';
						$line.='"' . str_replace('"', '""', str_replace("\r", "", str_replace("\n", "", str_replace("\"", "\"\"", $value->instantiation_identifier)))) . '",';
						$line.='"' . str_replace('"', '""', str_replace("\r", "", str_replace("\n", "", str_replace("\"", "\"\"", $value->instantiation_source)))) . '",';
						$line.='"' . str_replace('"', '""', str_replace("\r", "", str_replace("\n", "", str_replace("\"", "\"\"", $value->generation)))) . '",';
						$line.='"' . str_replace('"', '""', str_replace("\r", "", str_replace("\n", "", str_replace("\"", "\"\"", $value->status)))) . '",';
						$line.='"' . str_replace('"', '""', str_replace("\r", "", str_replace("\n", "", str_replace("\"", "\"\"", $value->nomination_reason)))) . '",';
						$line.='"' . str_replace('"', '""', str_replace("\r", "", str_replace("\n", "", str_replace("\"", "\"\"", $value->media_type)))) . '",';
						$line.='"' . str_replace('"', '""', str_replace("\r", "", str_replace("\n", "", str_replace("\"", "\"\"", $value->language)))) . '",';
						$line.="$value->ins_id,";
						$line.="$value->identifier_id,";
						$line.="$value->gen_id";
						$line .= "\n";
					}
					fputs($fp, $line);
					fclose($fp);
					myLog('Total Records on CSV ' . ($offset * 15000));
					$offset ++;
					if (count($records) < 15000)
						$db_count ++;
				}

				$path = $this->config->item('path') . "uploads/google_refine/$filename";
				echo $path . '<br/>';
				myLog('CSV file Successfully Created.');
				$data = array('export_csv_path' => $path);
				$this->refine_modal->update_job($record->id, $data);
				myLog('Creating AMS Refine Project.');
				$project_url = $this->create($path, $filename, $record->id);

				myLog('Successfully Created AMS Refine Project.');
				$user = $this->users->get_user_by_id($record->user_id)->row();
				myLog('Sending Email to ' . $user->email);
				if ($project_url)
					send_email($user->email, $this->config->item('from_email'), 'AMS Refine', $project_url);
			}
			else
			{
				$filename = 'google_refine_' . time() . '.csv';
				$fp = fopen("uploads/google_refine/$filename", 'a');
				$line = "Organization,Asset Title,Description,Subject,Subject Source,Subject Ref,Genre,Genre Source,Genre Ref,Creator Name,Creator Affiliation,Creator Source,Creator Ref,";
				$line .="Contributors Name,Contributors Affiliation,Contributors Source,Contributors Ref,Publisher,Publisher Affiliation,Publisher Ref,Coverage,Coverage Type,";
				$line .="Audience Level,Audience Level Source,Audience Level Ref,";
				$line .="Audience Rating,Audience Rating Source,Audience Rating Ref,";
				$line .="Annotation,Annotation Type,Annotation Ref,";
				$line .="Rights,Rights Link,Asset Type,Identifier,Identifier Source,Identifier Ref,Asset Date,";
				$line .="__subject_id,__genre_id,__creator_id,__contributor_id,__publisher_id,__coverage_id,__audience_levels_id,__audience_ratings_id,__annotation_id,__right_id,__asset_types_id,__identifier_id,__asset_date_id,__asset_id\n";
				fputs($fp, $line);
				fclose($fp);
				$db_count = 0;
				$offset = 0;
				while ($db_count == 0)
				{

					$custom_query = $record->export_query;
					$custom_query.=' LIMIT ' . ($offset * 15000) . ', 15000';

					$records = $this->refine_modal->get_csv_records($custom_query);

					$fp = fopen("uploads/google_refine/$filename", 'a');
					$line = '';
					foreach ($records as $value)
					{
						$count = 1;
						foreach ($value as $index => $column)
						{
							if ($index == 'asset_id')
								$line.='"' . str_replace('"', '""', $column) . '"';
							else
								$line.='"' . str_replace('"', '""', $column) . '",';
						}

						$line .= "\n";
					}

					fputs($fp, $line);
					fclose($fp);
					$offset ++;

					if (count($records) < 15000)
						$db_count ++;
				}

				$path = $this->config->item('path') . "uploads/google_refine/$filename";
				$data = array('export_csv_path' => $path);
				$this->refine_modal->update_job($record->id, $data);
				$project_url = $this->create($path, $filename, $record->id);
				$user = $this->users->get_user_by_id($record->user_id)->row();
				myLog('Sending Email to ' . $user->email);
				debug($project_url);
				if ($project_url)
					send_email($user->email, $this->config->item('from_email'), 'AMS Refine', $project_url);
			}
		}
		else
		{
			myLog('No job available for refinement.');
		}
		exit_function();
	}

	/**
	 * Update the records for AMS Refine records. Also rotate the indexes.
	 * 
	 * @return 
	 */
	function update_refine()
	{
		set_time_limit(0);
		@ini_set("memory_limit", "1000M"); # 1GB
		@ini_set("max_execution_time", 999999999999); # 1GB
		$record = $this->refine_modal->refine_update_records();
		if (count($record) > 0)
		{
			if ($record->refine_type === 'instantiation')
				$this->update_instantiations($record->import_csv_path);
			else
				$this->update_assets($record->import_csv_path);

			@exec("/usr/bin/indexer --all --rotate", $output);
			$email_output = implode('<br/>', $output);
			$this->refine_modal->update_job($record->id, array('is_active' => 0));
			send_email('nouman@avpreserve.com', $this->config->item('from_email'), 'AMS Refine Index Rotation', $email_output);
			myLog("All Indexes Rotated Successfully.");
		}
		else
		{
			myLog('No AMS Refine update available.');
		}
		exit_function();
	}

	function rotate_all_indexes()
	{
		$output = `/usr/bin/indexer --all --rotate --config /etc/sphinx/sphinx.conf`;
		send_email('nouman@avpreserve.com', $this->config->item('from_email'), 'AMS Refine Index Rotation', $output);
		exit_function();
	}

	/**
	 * Update instantiations information from csv that is exported from AMS Refine.
	 * 
	 * @param string $csv_path complete path of csv
	 * 
	 * @return
	 */
	function update_instantiations($csv_path)
	{
		$records = file($csv_path);

		foreach ($records as $index => $line)
		{
			if ($index != 0)
			{
				list($organization, $asset_title, $description, $ins_id, $ins_id_src, $generation, $nomination, $nomination_reason, $media_type, $language, $instantiation_id, $identifier_id, $generation_id)
				= preg_split("/\t/", $line);
				/* Check and update Media Type and Language Start */
				$media_type_id = 0;
				if ( ! empty($media_type))
				{
					$inst_media_type = $this->instantiation->get_instantiation_media_types_by_media_type($media_type);
					if ( ! is_empty($inst_media_type))
						$media_type_id = $inst_media_type->id;
					else
						$media_type_id = $this->instantiation->insert_instantiation_media_types(array("media_type" => $media_type));
				}
				$ins_detail = $this->instantiation->get_by_id($instantiation_id);
				if ($ins_detail)
				{
					$data = array('instantiation_media_type_id' => $media_type_id,
						'language' => $language);
					$ins_detail = $this->instantiation->update_instantiations($instantiation_id, $data);
				}
				/* Check and update Media Type and Language End */
				/* Check and update Generation Start */
				if ( ! empty($generation))
				{
					$db_gen_id = FALSE;
					$db_generation = $this->instantiation->get_generations_by_generation($generation);
					if ($db_generation)
					{
						$db_gen_id = $db_generation->id;
					}
					else
					{
						$db_gen_id = $this->instantiation->insert_generations(array('generation' => $generation));
					}
					if ($db_gen_id)
					{
						if ( ! empty($generation_id))
						{
							$ins_gen_db = $this->refine_modal->get_instantiation_generation_by_id($generation_id);
							if ($ins_gen_db)
							{
								$this->refine_modal->update_instantiation_generation_by_id($ins_gen_db->id, array('generations_id' => $db_gen_id));
							}
							else
							{
								$inst_gen = array('instantiations_id' => $instantiation_id, 'generations_id' => $db_gen_id);
								$this->instantiation->insert_instantiation_generations($inst_gen);
							}
						}
						else
						{
							$inst_gen = array('instantiations_id' => $instantiation_id, 'generations_id' => $db_gen_id);
							$this->instantiation->insert_instantiation_generations($inst_gen);
						}
					}
				}
				/* Check and update Generation End */
				/* Check and update Instantiations Identifier Start */
				if ( ! empty($ins_id))
				{
					if ( ! empty($identifier_id))
					{
						$db_ins_identifier = $this->refine_modal->get_instantiation_idetifier_by_id($identifier_id);
						if ($db_ins_identifier)
						{
							$identifier_data = array('instantiation_identifier ' => $ins_id,
								'instantiation_source ' => $ins_id_src);
							$this->refine_modal->update_instantiation_idetifier_by_id($identifier_id, $identifier_data);
						}
						else
						{
							$identifier_data = array('instantiations_id' => $instantiation_id,
								'instantiation_identifier ' => $ins_id,
								'instantiation_source ' => $ins_id_src
							);
							$this->instantiation->insert_instantiation_identifier($identifier_data);
						}
					}
					else
					{
						$identifier_data = array('instantiations_id' => $instantiation_id,
							'instantiation_identifier ' => $ins_id,
							'instantiation_source ' => $ins_id_src,
						);
						$this->instantiation->insert_instantiation_identifier($identifier_data);
					}
				}
				/* Check and update Instantiations Identifier End */
				/* Check and update Nomination Start */
				if ( ! empty($nomination))
				{
					$nomination_exist = $this->assets_model->get_nominations($instantiation_id);

					$nomination_id = $this->assets_model->get_nomination_status_by_status($nomination)->id;

					$nomination_record = array('nomination_status_id' => $nomination_id, 'nomination_reason' => $nomination_reason, 'nominated_at' => date('Y-m-d H:i:s'));
					if ($nomination_exist)
					{
						$nomination_record['updated'] = date('Y-m-d H:i:s');
						$this->assets_model->update_nominations($instantiation_id, $nomination_record);
					}
					else
					{
						$nomination_record['instantiations_id'] = $instantiation_id;
						$nomination_record['created'] = date('Y-m-d H:i:s');
						$this->assets_model->insert_nominations($nomination_record);
					}
				}
				/* Check and update Nomination End */
			}
		}
	}

	/**
	 * Update assets information from csv that is exported from AMS Refine.
	 * 
	 * @param string $csv_path complete path of csv
	 * 
	 * @return
	 */
	function update_assets($csv_path)
	{
		$records = file($csv_path);
		foreach ($records as $index => $line)
		{
			if ($index != 0)
			{
				$exploded_columns = preg_split("/\t/", $line);

				$asset_id = $exploded_columns[51];
				$asset_date_id = $exploded_columns[50];
				$asset_identifier_id = $exploded_columns[49];
				$asset_type_id = $exploded_columns[48];
				$asset_right_id = $exploded_columns[47];
				$asset_annotation_id = $exploded_columns[46];
				$asset_rating_id = $exploded_columns[45];
				$asset_level_id = $exploded_columns[44];
				$asset_coverage_id = $exploded_columns[43];
				$asset_publisher_id = $exploded_columns[42];
				$asset_contributer_id = $exploded_columns[41];
				$asset_creator_id = $exploded_columns[40];
				$asset_genre_id = $exploded_columns[39];
				$asset_subject_id = $exploded_columns[38];
				/* Check and update Subject Start */
				if ( ! empty($exploded_columns[3]))
				{

					$subjects = $this->assets_model->get_subjects_id_by_subject($exploded_columns[3]);
					if (isset($subjects) && isset($subjects->id))
					{
						$subject_id = $subjects->id;
					}
					else
					{
						$subject_d['subject'] = $exploded_columns[3];
						$subject_d['subject_source'] = $exploded_columns[4];
						$subject_d['subject_ref'] = $exploded_columns[5];

						$subject_id = $this->assets_model->insert_subjects($subject_d);
					}
					if ( ! empty($asset_subject_id))
					{
						$this->refine_modal->update_asset_subject($asset_id, $asset_subject_id, array('subjects_id' => $subject_id));
					}
					else
					{
						$subject_data = array('subjects_id' => $subject_id, 'assets_id' => $asset_id);
						$this->assets_model->insert_assets_subjects($subject_data);
					}
				}
				/* Check and update Subject End */
				/* Check and update Genre Start */
				if ( ! empty($exploded_columns[6]))
				{
					$asset_genre['assets_id'] = $asset_id;
					$asset_genre_type = $this->assets_model->get_genre_type($exploded_columns[6]);
					if (isset($asset_genre_type) && isset($asset_genre_type->id))
					{
						$asset_genre['genres_id'] = $asset_genre_type->id;
					}
					else
					{
						$asset_genre_d['genre'] = $exploded_columns[6];
						$asset_genre_d['genre_source'] = $exploded_columns[7];
						$asset_genre_d['genre_ref'] = $exploded_columns[8];

						$asset_genre['genres_id'] = $this->assets_model->insert_genre($asset_genre_d);
					}
					if ( ! empty($asset_genre_id))
					{
						$this->refine_modal->update_asset_genre($asset_id, $asset_genre_id, array('genres_id' => $asset_genre['genres_id']));
					}
					else
					{
						$this->assets_model->insert_asset_genre($asset_genre);
					}
				}
				/* Check and update Genre Start */

				/* Check and update Creator Start */
				if ( ! empty($exploded_columns[9]))
				{
					$assets_creators_roles_d['assets_id'] = $asset_id;
					$creator_d = $this->assets_model->get_creator_by_creator_name($exploded_columns[9]);
					if (isset($creator_d) && isset($creator_d->id))
					{
						$assets_creators_roles_d['creators_id'] = $creator_d->id;
					}
					else
					{
						$creator_data = array('creator_name' => $exploded_columns[9],
							'creator_affiliation' => $exploded_columns[10],
							'creator_source' => $exploded_columns[11],
							'creator_ref' => $exploded_columns[12],
						);
						$assets_creators_roles_d['creators_id'] = $this->assets_model->insert_creators($creator_data);
					}
					if ( ! empty($asset_creator_id))
					{
						$this->refine_modal->update_creator_role($asset_id, $asset_creator_id, array('creators_id' => $assets_creators_roles_d['creators_id']));
					}
					else
					{
						$this->assets_model->insert_assets_creators_roles($assets_creators_roles_d);
					}
				}
				/* Check and update Creator End */

				/* Check and update Contributer Start */
				if ( ! empty($exploded_columns[13]))
				{
					$assets_contributer_roles_d['assets_id'] = $asset_id;
					$contributer_d = $this->assets_model->get_contributor_by_contributor_name($exploded_columns[13]);
					if (isset($contributer_d) && isset($contributer_d->id))
					{
						$assets_contributer_roles_d['contributors_id'] = $contributer_d->id;
					}
					else
					{
						$contributer_data = array('contributor_name' => $exploded_columns[13],
							'contributor_affiliation' => $exploded_columns[14],
							'contributor_source' => $exploded_columns[15],
							'contributor_ref' => $exploded_columns[16],
						);
						$assets_contributer_roles_d['contributors_id'] = $this->assets_model->insert_contributors($contributer_data);
					}
					if ( ! empty($asset_contributer_id))
					{
						$this->refine_modal->update_contributer_role($asset_id, $asset_contributer_id, array('contributors_id' => $assets_contributer_roles_d['contributors_id']));
					}
					else
					{
						$this->assets_model->insert_assets_contributors_roles($assets_contributer_roles_d);
					}
				}
				/* Check and update Contributer End */

				/* Check and update Publisher Start */
				if ( ! empty($exploded_columns[17]))
				{
					$assets_publisher_d['assets_id'] = $asset_id;
					$publisher_d = $this->assets_model->get_publishers_by_publisher($exploded_columns[17]);
					if (isset($publisher_d) && isset($publisher_d->id))
					{
						$assets_publisher_d['publishers_id'] = $publisher_d->id;
					}
					else
					{
						$publisher_data = array('publisher' => $exploded_columns[17],
							'publisher_affiliation' => $exploded_columns[18],
							'publisher_ref' => $exploded_columns[19],
						);
						$assets_publisher_d['publishers_id'] = $this->assets_model->insert_publishers($publisher_data);
					}
					if ( ! empty($asset_publisher_id))
					{
						$this->refine_modal->update_publisher_role($asset_id, $asset_publisher_id, array('publishers_id' => $assets_publisher_d['publishers_id']));
					}
					else
					{
						$this->assets_model->insert_assets_publishers_role($assets_publisher_d);
					}
				}
				/* Check and update Publisher End */

				/* Check and update Coverage Start */
				if ( ! empty($exploded_columns[20]))
				{
					$coverage['coverage'] = $exploded_columns[20];
					$coverage['coverage_type'] = $exploded_columns[21];
					if ( ! empty($asset_coverage_id))
					{
						$this->refine_modal->update_asset_coverage($asset_id, $asset_coverage_id, $coverage);
					}
					else
					{
						$coverage['assets_id'] = $asset_id;
						$asset_coverage = $this->assets_model->insert_coverage($coverage);
					}
				}
				/* Check and update Coverage End */
				/* Check and update Audience Level Start */
				if ( ! empty($exploded_columns[22]))
				{
					$asset_audience_level['assets_id'] = $asset_id;
					$db_audience_level = $this->assets_model->get_audience_level($exploded_columns[22]);
					if (isset($db_audience_level) && isset($db_audience_level->id))
					{
						$asset_audience_level['audience_levels_id'] = $db_audience_level->id;
					}
					else
					{
						$audience_level['audience_level'] = $exploded_columns[22];
						$audience_level['audience_level_source'] = $exploded_columns[23];
						$audience_level['audience_level_ref'] = $exploded_columns[24];
						$asset_audience_level['audience_levels_id'] = $this->assets_model->insert_audience_level($audience_level);
					}
					if ( ! empty($asset_level_id))
					{
						$this->refine_modal->update_asset_audience_level($asset_id, $asset_level_id, array('audience_levels_id' => $asset_audience_level['audience_levels_id']));
					}
					else
					{
						$asset_audience = $this->assets_model->insert_asset_audience($asset_audience_level);
					}
				}
				/* Check and update Audience Level End */
				/* Check and update Audience Rating Start */
				if ( ! empty($exploded_columns[25]))
				{
					$asset_audience_rating['assets_id'] = $asset_id;
					$db_audience_rating = $this->assets_model->get_audience_rating($audience_rating['audience_rating']);
					if (isset($db_audience_rating) && isset($db_audience_rating->id))
					{
						$asset_audience_rating['audience_ratings_id'] = $db_audience_rating->id;
					}
					else
					{
						$audience_rating['audience_rating'] = $exploded_columns[25];
						$audience_rating['audience_rating_source'] = $exploded_columns[26];
						$audience_rating['audience_rating_ref'] = $exploded_columns[27];
						$asset_audience_rating['audience_ratings_id'] = $this->assets_model->insert_audience_rating($audience_rating);
					}
					if ( ! empty($asset_rating_id))
					{
						$this->refine_modal->update_asset_audience_rating($asset_id, $asset_rating_id, array('audience_ratings_id' => $asset_audience_rating['audience_ratings_id']));
					}
					else
					{
						$asset_audience_rate = $this->assets_model->insert_asset_audience_rating($asset_audience_rating);
					}
				}
				/* Check and update Audience Rating End */

				/* Check and update Annotation Start */
				if ( ! empty($exploded_columns[28]))
				{
					$annotation['annotation'] = $exploded_columns[28];
					$annotation['annotation_type'] = $exploded_columns[29];
					$annotation['annotation_ref'] = $exploded_columns[30];
					if ( ! empty($asset_annotation_id))
					{
						$this->refine_modal->update_annotation_type($asset_id, $asset_annotation_id, $annotation);
					}
					else
					{
						$annotation['assets_id'] = $asset_id;
						$asset_annotation = $this->assets_model->insert_annotation($annotation);
					}
				}
				/* Check and update Annotation End */
				/* Check and update Rights Start */
				if ( ! empty($exploded_columns[31]))
				{

					$rights_summary_d['rights'] = $exploded_columns[31];
					$rights_summary_d['rights_link'] = $exploded_columns[32];
					if ( ! empty($asset_right_id))
					{
						$this->refine_modal->update_right_summary($asset_id, $asset_right_id, $rights_summary_d);
					}
					else
					{
						$rights_summary_d['assets_id'] = $asset_id;
						$this->assets_model->insert_rights_summaries($rights_summary_d);
					}
				}
				/* Check and update Rights End */
				/* Check and update Asset Type Start */
				if ( ! empty($exploded_columns[33]))
				{
					$asset_type_d['assets_id'] = $asset_id;
					if ($asset_type = $this->assets_model->get_assets_type_by_type($exploded_columns[33]))
					{
						$asset_type_d['asset_types_id'] = $asset_type->id;
					}
					else
					{
						$asset_type_d['asset_types_id'] = $this->assets_model->insert_asset_types(array("asset_type" => $exploded_columns[33]));
					}
					if ( ! empty($asset_type_id))
					{
						$this->refine_modal->update_asset_type($asset_id, $asset_right_id, array('asset_types_id' => $asset_type_d['asset_types_id']));
					}
					else
					{
						$this->assets_model->insert_assets_asset_types($asset_type_d);
					}
				}
				/* Check and update Asset Type End */
				/* Check and update Asset Identifier Start */
				if ( ! empty($exploded_columns[34]))
				{
					$identifier_d['identifier'] = $exploded_columns[34];
					$identifier_d['identifier_source'] = $exploded_columns[35];
					$identifier_d['identifier_ref'] = $exploded_columns[36];
					if ( ! empty($asset_identifier_id))
					{
						$this->refine_modal->update_asset_identifier($asset_id, $asset_identifier_id, $identifier_d);
					}
					else
					{
						$identifier_d['assets_id'] = $asset_id;
						$this->assets_model->insert_identifiers($identifier_d);
					}
				}
				/* Check and update Asset Identifier End */
				/* Check and update Asset Date Start */
				if ( ! empty($exploded_columns[37]))
				{
					$asset_date['asset_date'] = $exploded_columns[37];
					if ( ! empty($asset_date_id))
					{
						$this->refine_modal->update_asset_date($asset_id, $asset_date_id, $asset_date);
					}
					else
					{
						$asset_date['assets_id'] = $asset_id;
						$this->assets_model->insert_asset_date($asset_date);
					}
				}
				/* Check and update Asset Date Start */
			}
		}
	}

}