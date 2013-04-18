<?php

/**
 * Assets Controller
 * 
 * PHP version 5
 * 
 * @category   AMS
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@geekschicago.com>
 * @license    AMS http://ams.avpreserve.com
 * @version    GIT: <$Id>
 * @link       http://ams.avpreserve.com
 */

/**
 * Assets Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@geekschicago.com>
 * @license    AMS http://ams.avpreserve.com
 * @link       http://ams.avpreserve.com
 */
class Assets extends MY_Controller
{

	/**
	 * Constructor
	 * 
	 * Load the layout.
	 * 
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->model('manage_asset_model', 'manage_asset');
		$this->load->model('instantiations_model', 'instantiation');
		$this->load->model('assets_model');
	}

	public function edit()
	{
		$asset_id = $this->uri->segment(3);

		if ( ! empty($asset_id))
		{

			if ($this->input->post())
			{

				$this->delete_asset_attributes($asset_id);
				if ( ! $this->is_station_user)
				{
					$this->assets_model->update_assets($asset_id, array('stations_id' => $this->input->post('organization')));
				}
				if ($this->input->post('asset_type'))
				{
					foreach ($this->input->post('asset_type') as $value)
					{
						$asset_type_d['assets_id'] = $asset_id;
						if ($asset_type = $this->assets_model->get_assets_type_by_type($value))
						{
							$asset_type_d['asset_types_id'] = $asset_type->id;
						}
						else
						{
							$asset_type_d['asset_types_id'] = $this->assets_model->insert_asset_types(array("asset_type" => $value));
						}

						$this->assets_model->insert_assets_asset_types($asset_type_d);
					}
				}
				if ($this->input->post('asset_date'))
				{
					foreach ($this->input->post('asset_date') as $index => $value)
					{
						$asset_date_info['assets_id'] = $asset_id;
						$asset_date_info['asset_date'] = $value;
						$date_type = $this->input->post('asset_date_type');

						if ($asset_date_type = $this->instantiation->get_date_types_by_type($date_type[$index]))
						{
							$asset_date_info['date_types_id'] = $asset_date_type->id;
						}
						else
						{
							$asset_date_info['date_types_id'] = $this->instantiation->insert_date_types(array("date_type" => $date_type[$index]));
						}

						$this->assets_model->insert_asset_date($asset_date_info);
					}
				}
				if ($this->input->post('asset_identifier'))
				{
					foreach ($this->input->post('asset_identifier') as $index => $value)
					{
						if ( ! empty($value))
						{
							$identifier_source = $this->input->post('asset_identifier_source');
							$identifier_ref = $this->input->post('asset_identifier_ref');
							$identifier_detail['assets_id'] = $asset_id;
							$identifier_detail['identifier'] = $value;
							if ( ! empty($identifier_source[$index]))
								$identifier_detail['identifier_source'] = $identifier_source[$index];
							if ( ! empty($identifier_ref[$index]))
								$identifier_detail['identifier_ref'] = $identifier_ref[$index];
							$this->assets_model->insert_identifiers($identifier_detail);
						}
					}
				}
				if ($this->input->post('asset_title'))
				{
					foreach ($this->input->post('asset_title') as $index => $value)
					{
						$title_type = $this->input->post('asset_title_type');
						$title_source = $this->input->post('asset_title_source');
						$title_ref = $this->input->post('asset_title_ref');
						if ( ! empty($value))
						{
							$title_detail['assets_id'] = $asset_id;
							$title_detail['title'] = $value;
							if ($title_type[$index])
							{
								$asset_title_types = $this->assets_model->get_asset_title_types_by_title_type($title_type[$index]);
								if (isset($asset_title_types) && isset($asset_title_types->id))
								{
									$asset_title_types_id = $asset_title_types->id;
								}
								else
								{
									$asset_title_types_id = $this->assets_model->insert_asset_title_types(array("title_type" => $title_type[$index]));
								}
								$title_detail['asset_title_types_id'] = $asset_title_types_id;
							}
							if ($title_ref[$index])
							{
								$title_detail['title_ref'] = $title_ref[$index];
							}
							if ($title_source[$index])
							{
								$title_detail['title_source'] = $title_source[$index];
							}
							$title_detail['created'] = date('Y-m-d H:i:s');
							$title_detail['updated'] = date('Y-m-d H:i:s');
							$this->assets_model->insert_asset_titles($title_detail);
						}
					}
				}
				if ($this->input->post('asset_subject'))
				{
					foreach ($this->input->post('asset_subject') as $index => $value)
					{
						$subject_type = $this->input->post('asset_subject_type');
						$subject_source = $this->input->post('asset_subject_source');
						$subject_ref = $this->input->post('asset_subject_ref');
						if ( ! empty($value))
						{
							$subject_detail['assets_id'] = $asset_id;

							$subject_d = array();
							$subject_d['subject'] = $value;
							$subject_d['subjects_types_id'] = $subject_type[$index];
							if ( ! empty($subject_ref[$index]))
							{
								$subject_d['subject_ref'] = $subject_ref[$index];
							}
							if ( ! empty($subject_source[$index]))
							{
								$subject_d['subject_source'] = $subject_source[$index];
							}

							$subject_id = $this->assets_model->insert_subjects($subject_d);



							$subject_detail['subjects_id'] = $subject_id;
							$assets_subject_id = $this->assets_model->insert_assets_subjects($subject_detail);
						}
					}
				}
				if ($this->input->post('asset_description'))
				{
					$desc_type = $this->input->post('asset_description_type');
					foreach ($this->input->post('asset_description') as $index => $value)
					{
						if ( ! empty($value))
						{
							$asset_descriptions_d['assets_id'] = $asset_id;
							$asset_descriptions_d['description'] = $value;
							$asset_description_type = $this->assets_model->get_description_by_type($desc_type[$index]);
							if (isset($asset_description_type) && isset($asset_description_type->id))
							{
								$asset_description_types_id = $asset_description_type->id;
							}
							else
							{
								$asset_description_types_id = $this->assets_model->insert_description_types(array("description_type" => $desc_type[$index]));
							}
							$asset_descriptions_d['description_types_id'] = $asset_description_types_id;
							$this->assets_model->insert_asset_descriptions($asset_descriptions_d);
						}
					}
				}
				if ($this->input->post('asset_genre'))
				{
					$genre_source = $this->input->post('asset_genre_source');
					$genre_ref = $this->input->post('asset_genre_ref');
					foreach ($this->input->post('asset_genre') as $index => $value)
					{
						if ( ! empty($value))
						{
							$asset_genre_d['genre'] = $value;
							$asset_genre_d['genre_source'] = $genre_source[$index];
							$asset_genre_d['genre_ref'] = $genre_ref[$index];
							$asset_genre_type = $this->assets_model->get_genre_type_all($asset_genre_d);
							if (isset($asset_genre_type) && isset($asset_genre_type->id))
							{
								$asset_genre['genres_id'] = $asset_genre_type->id;
							}
							else
							{
								$asset_genre['genres_id'] = $this->assets_model->insert_genre($asset_genre_d);
							}


							$asset_genre['assets_id'] = $asset_id;
							$this->assets_model->insert_asset_genre($asset_genre);
						}
					}
				}
				if ($this->input->post('asset_coverage'))
				{
					$coverage_type = $this->input->post('asset_coverage_type');
					foreach ($this->input->post('asset_coverage') as $index => $value)
					{
						if ( ! empty($value))
						{
							$coverage['assets_id'] = $asset_id;
							$coverage['coverage'] = $value;
							$coverage['coverage_type'] = $coverage_type[$index];
							$this->assets_model->insert_coverage($coverage);
						}
					}
				}
				if ($this->input->post('asset_audience_level'))
				{
					$audience_source = $this->input->post('asset_audience_level_source');
					$audience_ref = $this->input->post('asset_audience_level_ref');
					foreach ($this->input->post('asset_audience_level') as $index => $value)
					{
						if ( ! empty($value))
						{
							$asset_audience_level['assets_id'] = $asset_id;
							$audience_level['audience_level'] = trim($value);
							if ( ! empty($audience_source[$index]))
							{
								$audience_level['audience_level_source'] = $audience_source[$index];
							}
							if ( ! empty($audience_ref[$index]))
							{
								$audience_level['audience_level_ref'] = $audience_ref[$index];
							}
							$db_audience_level = $this->assets_model->get_audience_level_all($audience_level);
							if (isset($db_audience_level) && isset($db_audience_level->id))
							{
								$asset_audience_level['audience_levels_id'] = $db_audience_level->id;
							}
							else
							{
								$asset_audience_level['audience_levels_id'] = $this->assets_model->insert_audience_level($audience_level);
							}
							$asset_audience = $this->assets_model->insert_asset_audience($asset_audience_level);
						}
					}
				}
				if ($this->input->post('asset_audience_rating'))
				{
					$audience_source = $this->input->post('asset_audience_rating_source');
					$audience_ref = $this->input->post('asset_audience_rating_ref');
					foreach ($this->input->post('asset_audience_rating') as $index => $value)
					{
						if ( ! empty($value))
						{
							$asset_audience_rating['assets_id'] = $asset_id;
							$audience_rating['audience_rating'] = trim($value);
							if ( ! empty($audience_source[$index]))
							{
								$audience_rating['audience_rating_source'] = $audience_source[$index];
							}
							if ( ! empty($audience_ref[$index]))
							{
								$audience_rating['audience_rating_ref'] = $audience_ref[$index];
							}
							$db_audience_rating = $this->assets_model->get_audience_rating_all($audience_rating);
							if (isset($db_audience_rating) && isset($db_audience_rating->id))
							{
								$asset_audience_rating['audience_ratings_id'] = $db_audience_rating->id;
							}
							else
							{
								$asset_audience_rating['audience_ratings_id'] = $this->assets_model->insert_audience_rating($audience_rating);
							}
							$asset_audience = $this->assets_model->insert_asset_audience_rating($asset_audience_rating);
						}
					}
				}
				if ($this->input->post('asset_annotation'))
				{
					$annotation_type = $this->input->post('asset_annotation_type');
					$annotation_ref = $this->input->post('asset_annotation_ref');
					foreach ($this->input->post('asset_annotation') as $index => $value)
					{
						if ( ! empty($value))
						{
							$annotation['assets_id'] = $asset_id;
							$annotation['annotation'] = $value;
							if ( ! empty($annotation_type[$index]))
								$annotation['annotation_type'] = $annotation_type[$index];
							if ( ! empty($annotation_ref[$index]))
								$annotation['annotation_ref'] = $annotation_ref[$index];

							$asset_annotation = $this->assets_model->insert_annotation($annotation);
						}
					}
				}
				if ($this->input->post('asset_relation_identifier'))
				{
					$relation_src = $this->input->post('asset_relation_source');
					$relation_ref = $this->input->post('asset_relation_ref');
					$relation_type = $this->input->post('asset_relation_type');
					foreach ($this->input->post('asset_relation_identifier') as $index => $value)
					{
						if ( ! empty($value))
						{
							$assets_relation['assets_id'] = $asset_id;
							$assets_relation['relation_identifier'] = $value;
							$relation_types['relation_type'] = $relation_type[$index];
							if ( ! empty($relation_src[$index]))
								$relation_types['relation_type_source'] = $relation_src[$index];
							if ( ! empty($relation_ref[$index]))
								$relation_types['relation_type_ref'] = $relation_ref[$index];
							$db_relations = $this->assets_model->get_relation_types_all($relation_types);
							if (isset($db_relations) && isset($db_relations->id))
							{
								$assets_relation['relation_types_id'] = $db_relations->id;
							}
							else
							{
								$assets_relation['relation_types_id'] = $this->assets_model->insert_relation_types($relation_types);
							}
							$this->assets_model->insert_asset_relation($assets_relation);
						}
					}
				}
				if ($this->input->post('asset_creator_name'))
				{
					$affiliation = $this->input->post('asset_creator_affiliation');
					$ref = $this->input->post('asset_creator_ref');
					$roles = $this->input->post('asset_creator_role');
					$role_src = $this->input->post('asset_creator_role_source');
					$role_ref = $this->input->post('asset_creator_role_ref');
					foreach ($this->input->post('asset_creator_name') as $index => $value)
					{
						if ( ! empty($value))
						{
							$assets_creators_roles_d['assets_id'] = $asset_id;
							$creater['creator_name'] = $value;
							if ( ! empty($affiliation[$index]))
								$creater['creator_affiliation'] = $affiliation[$index];
							if ( ! empty($ref[$index]))
								$creater['creator_ref'] = $ref[$index];
							$creator_d = $this->assets_model->get_creator_by_creator_info($creater);
							if (isset($creator_d) && isset($creator_d->id))
							{
								$assets_creators_roles_d['creators_id'] = $creator_d->id;
							}
							else
							{
								$assets_creators_roles_d['creators_id'] = $this->assets_model->insert_creators($creater);
							}
							$role['creator_role'] = $roles[$index];
							if ( ! empty($role_src[$index]))
								$role['creator_role_source'] = $role_src[$index];
							if ( ! empty($role_ref[$index]))
								$role['creator_role_ref'] = $role_ref[$index];
							$creator_role = $this->assets_model->get_creator_role_info($role);
							if (isset($creator_role) && isset($creator_role->id))
							{
								$assets_creators_roles_d['creator_roles_id'] = $creator_role->id;
							}
							else
							{
								$assets_creators_roles_d['creator_roles_id'] = $this->assets_model->insert_creator_roles($role);
							}
							$assets_creators_roles_id = $this->assets_model->insert_assets_creators_roles($assets_creators_roles_d);
						}
					}
				}
				if ($this->input->post('asset_contributor_name'))
				{
					$affiliation = $this->input->post('asset_contributor_affiliation');
					$ref = $this->input->post('asset_contributor_ref');
					$roles = $this->input->post('asset_contributor_role');
					$role_src = $this->input->post('asset_contributor_role_source');
					$role_ref = $this->input->post('asset_contributor_role_ref');
					foreach ($this->input->post('asset_contributor_name') as $index => $value)
					{
						if ( ! empty($value))
						{
							$assets_contributors_d['assets_id'] = $asset_id;
							$contributor_info['contributor_name'] = $value;
							if ( ! empty($affiliation[$index]))
								$contributor_info['contributor_affiliation'] = $affiliation[$index];
							if ( ! empty($ref[$index]))
								$contributor_info['contributor_ref'] = $ref[$index];
							$creator_d = $this->assets_model->get_contributor_by_contributor_info($contributor_info);
							if (isset($creator_d) && isset($creator_d->id))
							{
								$assets_contributors_d['contributors_id'] = $creator_d->id;
							}
							else
							{
								$assets_contributors_d['contributors_id'] = $this->assets_model->insert_contributors($contributor_info);
							}
							$contributorrole_info['contributor_role'] = $roles[$index];
							if ( ! empty($role_src[$index]))
								$contributorrole_info['contributor_role_source'] = $role_src[$index];
							if ( ! empty($role_ref[$index]))
								$contributorrole_info['contributor_role_ref'] = $role_ref[$index];
							$contributor_role = $this->assets_model->get_contributor_role_info($contributorrole_info);
							if (isset($contributor_role) && isset($contributor_role->id))
							{
								$assets_contributors_d['contributor_roles_id'] = $contributor_role->id;
							}
							else
							{
								$assets_contributors_d['contributor_roles_id'] = $this->assets_model->insert_contributor_roles($contributorrole_info);
							}

							$this->assets_model->insert_assets_contributors_roles($assets_contributors_d);
						}
					}
				}
				if ($this->input->post('asset_publisher'))
				{
					$affiliation = $this->input->post('asset_publisher_affiliation');
					$ref = $this->input->post('asset_publisher_ref');
					$roles = $this->input->post('asset_publisher_role');
					$role_src = $this->input->post('asset_publisher_role_source');
					$role_ref = $this->input->post('asset_publisher_role_ref');
					foreach ($this->input->post('asset_publisher') as $index => $value)
					{
						if ( ! empty($value))
						{
							$assets_publisher_d['assets_id'] = $asset_id;
							$publisher_info['publisher'] = $value;
							if ( ! empty($affiliation[$index]))
								$publisher_info['publisher_affiliation'] = $affiliation[$index];
							if ( ! empty($ref[$index]))
								$publisher_info['publisher_ref'] = $ref[$index];
							$publisher_d = $this->assets_model->get_publisher_info($publisher_info);
							if (isset($publisher_d) && isset($publisher_d->id))
							{
								$assets_publisher_d['publishers_id'] = $publisher_d->id;
							}
							else
							{
								$assets_publisher_d['publishers_id'] = $this->assets_model->insert_publishers($publisher_info);
							}
							$publisher_role_info['publisher_role'] = $roles[$index];
							if ( ! empty($role_src[$index]))
								$publisher_role_info['publisher_role_source'] = $role_src[$index];
							if ( ! empty($role_ref[$index]))
								$publisher_role_info['publisher_role_ref'] = $role_ref[$index];
							$publisher_role = $this->assets_model->get_publisher_role_by_role($publisher_role_info);
							if (isset($publisher_role) && isset($publisher_role->id))
							{
								$assets_publisher_d['publisher_roles_id'] = $publisher_role->id;
							}
							else
							{
								$assets_publisher_d['publisher_roles_id'] = $this->assets_model->insert_publisher_roles($publisher_role_info);
							}
							$assets_publishers_roles_id = $this->assets_model->insert_assets_publishers_role($assets_publisher_d);
						}
					}
				}
				if ($this->input->post('asset_rights'))
				{
					$right_link = $this->input->post('asset_right_link');
					foreach ($this->input->post('asset_rights') as $index => $value)
					{
						$rights_summary_d['assets_id'] = $asset_id;
						$rights_summary_d['rights'] = $value;
						if ( ! empty($right_link[$index]))
							$rights_summary_d['rights_link'] = $right_link[$index];
						$this->assets_model->insert_rights_summaries($rights_summary_d);
					}
				}
				redirect('records/details/' . $asset_id, 'location');
			}

			$data['asset_detail'] = $this->manage_asset->get_asset_detail_by_id($asset_id);
//debug($data['asset_detail']);
			if ($data['asset_detail'])
			{
				$data['asset_id'] = $asset_id;
				$data['list_assets'] = $this->instantiation->get_instantiations_by_asset_id($asset_id);
				$data['pbcore_asset_types'] = $this->manage_asset->get_picklist_values(1);
				$data['pbcore_asset_date_types'] = $this->manage_asset->get_picklist_values(2);
				$data['pbcore_asset_title_types'] = $this->manage_asset->get_picklist_values(3);
				$data['pbcore_asset_subject_types'] = $this->manage_asset->get_subject_types();
				$data['pbcore_asset_description_types'] = $this->manage_asset->get_picklist_values(4);
				$data['pbcore_asset_audience_level'] = $this->manage_asset->get_picklist_values(5);
				$data['pbcore_asset_audience_rating'] = $this->manage_asset->get_picklist_values(6);
				$data['pbcore_asset_relation_types'] = $this->manage_asset->get_picklist_values(7);
				$data['pbcore_asset_creator_roles'] = $this->manage_asset->get_picklist_values(8);
				$data['pbcore_asset_contributor_roles'] = $this->manage_asset->get_picklist_values(9);
				$data['pbcore_asset_publisher_roles'] = $this->manage_asset->get_picklist_values(10);
				$data['organization'] = $this->station_model->get_all();
				$this->load->view('assets/edit', $data);
			}
			else
			{
				show_error('Not a valid asset id');
			}
		}
		else
		{
			show_error('Require asset id for editing');
		}
	}

	function insert_pbcore_values()
	{
		$static_gen = array('1 inch audio tape', '1 inch videotape', '1 inch videotape: EL3400', '1 inch videotape: EV-200', '1 inch videotape: Helical BVH-1000', 
			'1 inch videotape: IVC-700/800/900', '1 inch videotape: PI-3V','1 inch videotape: SMPTE Type A', '1 inch videotape: SMPTE Type B', 
			'1 inch videotape: SMPTE Type C', '1/2 inch audio tape', '1/2 inch digital audio tape', '1/2 inch videotape', '1/2 inch videotape: CV',
			'1/2 inch videotape: EIAJ Type 1', '1/2 inch videotape: Hawkeye/Recam/M', '1/2 inch videotape: reel-to-reel', '1/2 inch videotape: V2000',
			'1/2 inch videotape: VCR', '1/4 inch audio cassette','1/4 inch audio tape','1/4 inch videotape','1/4 inch videotape: Akai','1/8 inch audio cassette',
			'2 inch audio tape','2 inch videotape','2 inch videotape: Helical Ampex VR-1500','2 inch videotape: Helical IVC-9000','2 inch videotape: Quad',
			'2 inch videotape: Sony Helical SV-201','3/4 inch videotape','3/4 inch videotape: U-matic','3/4 inch videotape: U-matic SP','8 mm Digital-8','8 mm Hi8 Video',
			'8 mm video','8-Track cassette','8mm data','8mm digital audio','Aluminum Record','Artifact','Audio cart','Audio cassette','Award','BD-Videodisc','Beta',
			'Betacam','Betacam Digital (Digi Beta)','Betacam SP','Betacam SX','Betamax','Betamax: HB','Betamax: Super','Binder','Book','Box','Carte de visite','Cartivision',
			'CD','CD-R','CD-ROM','CD-RW','Collage','CompactFlash card','Contact sheet','Costume','CVC','D1','D16','D2','D3','D5','D5 HD','D6','D7','D9','D9 HD','Daguerreotype',
			'DARS','DARS: DA-88','DAT','Data cartridge','Data8','DBX 700 VHS tape','DCT','DDS cassette','Digital Linear Tape','Digital8','Display board','Document','Drawing',
			'DV','DVC','DVCAM','DVCAM: Sony','DVCPRO','DVCPRO 25','DVCPRO 50','DVCPRO 50/P','DVCPRO HD','DVCPRO P','DVD','DVD+R','DVD+R DL','DVD+RW','DVD-R','DVD-RW',
			'ED-Beta','EIAJ','EIAJ Cartridge','Engraving','Envelope','EP Record','EVD-Videodisc: China','Exabyte drive','F1 Beta tape','Film','Film cartridge','Film cassette',
			'Film loop','Film reel','Film roll','Film: 16mm','Film: 16mm magnetic track','Film: 16mm negative','Film: 16mm optical track','Film: 16mm print','Film: 16mm reversal',
			'Film: 35mm','Film: 35mm magnetic track','Film: 35mm negative','Film: 35mm optical track','Film: 35mm print','Film: 70mm','Film: 70mm negative','Film: 8mm',
			'Film: 8mm negative','Film: 8mm print','Film: 8mm reversal','Film: Super 16mm','Film: Super 16mm negative','Film: Super 16mm print','Film: Super 8mm',
			'Film: Super 8mm negative','Film: Super 8mm print','Flash Memory','Flash Memory: USB','Flexidisc','Flip chart','Floppy disk','Floppy disk: 3 1/2 inch',
			'Floppy disk: 5 1/4 inch','Floppy disk: 8 inch','Folder','Hard Drive','Hard Drive: eSATA','Hard Drive: Firewire','Hard Drive: Firewire 400','Hard Drive: Firewire 800',
			'Hard Drive: PATA','Hard Drive: SCSI','Hard Drive: USB','HD','HD-Videodisc','HD: D5','HD: D9','HD: DVC PRO HD','HDCAM','HDCAM SR','HDD1000','HDV','HDV-1000','Hi8',
			'Hi8: DA-78','Hi8: DA-88','Jaz disk','Lacquer discs/acetates','Laser Videodisc','Laser Videodisc CAV: 12-inch','Laser Videodisc CLV: 12-inch','Line art',
			'Lithograph','LP Record','LP Record (45)','LTO','LTO-1','LTO-2','LTO-3','LTO-4','LTO-5','LTO-6','LTO-7','LTO-8','M-II','Macthronics MVC-10','Magazine','Magnet',
			'Manuscript','Map','Memory Card','Merchandise','Microfiche','Microfilm','Mini-cassette','Minidisc','MiniDV','MPEG IMX','Mural','Newspaper','Notebook','Optical disk',
			'Optical disk cartridge','Oversized','P2 Card','Painting','Paper','Periodical','Photocopy','Photographic negative','Photographic print','Piano roll','Postcard',
			'Poster','Press Kit','Print','Quad','Radiograph','Roland DM 80','S-VHS','SACD','Script','SD card','SDHC card','SDXC card','Shellac disc','SIM card','Slide',
			'Stereograph','Sticker','Super DLT','SVCD','SxS Pro card','Sy-Quest diskette','Sy-Quest drive','Technical drawing','Technical drawing: Blueprint','Transparency',
			'Triptych','UMD-Videodisc','UMD-Videodisc: Sony','UniHi','V-Cord','V-Cord II','VHS','Video8','videocartridge','videocassette','videodisc','videoreel','VinylDisc',
			'VTR150','VTR600','VX','W-VHS','Wire','Woodcut','XDCAM cassette','XDCAM Disk','Zip disk');
		foreach ($static_gen as $value)
		{
			$this->manage_asset->insert_picklist_value(array('value' => $value, 'element_type_id' => 13));
		}
	}

	private function delete_asset_attributes($asset_id)
	{
		$this->manage_asset->delete_asset_types($asset_id);
		$this->manage_asset->delete_asset_dates($asset_id);
		$this->manage_asset->delete_local_identifiers($asset_id);
		$this->manage_asset->delete_asset_titles($asset_id);
		$this->manage_asset->delete_asset_subjects($asset_id);
		$this->manage_asset->delete_asset_descriptions($asset_id);
		$this->manage_asset->delete_asset_genre($asset_id);
		$this->manage_asset->delete_asset_coverage($asset_id);
		$this->manage_asset->delete_audience_level($asset_id);
		$this->manage_asset->delete_audience_rating($asset_id);
		$this->manage_asset->delete_annotations($asset_id);
		$this->manage_asset->delete_relations($asset_id);
		$this->manage_asset->delete_creator($asset_id);
		$this->manage_asset->delete_contributor($asset_id);
		$this->manage_asset->delete_publisher($asset_id);
		$this->manage_asset->delete_rights($asset_id);
		return TRUE;
	}

}