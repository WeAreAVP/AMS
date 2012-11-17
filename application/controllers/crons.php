<?php

/**
 * Settings controller.
 *
 * @package    AMS
 * @subpackage Scheduled Tasks
 * @author     Ali Raza
 */
class Crons extends CI_Controller
{

  /**
   *
   * constructor. Load layout,Model,Library and helpers
   * 
   */
  public $assets_path;

  function __construct()
  {
    parent::__construct();
    $this->load->model('email_template_model', 'email_template');
    $this->load->model('cron_model');
    $this->load->model('assets_model');
    $this->load->model('station_model');
    $this->assets_path = 'assets/';
  }

  /**
   * Process all pending email 
   *  
   */
  function processemailqueues()
  {
    $email_queue = $this->email_template->get_all_pending_email();
    foreach ($email_queue as $queue)
    {
      $now_queue_body = $queue->email_body . '<img src="' . site_url('emailtracking/' . $queue->id . '.png') . '" height="1" width="1" />';
      if (send_email($queue->email_to, $queue->email_from, $queue->email_subject, $now_queue_body))
      {
        $this->email_template->update_email_queue_by_id($queue->id, array("is_sent" => 2, "sent_at" => date('Y-m-d H:i:s')));
        echo "Email Sent To " . $queue->email_to . " <br/>";
      }
    }
  }

  /**
   * Store All Assets Data Files Structure in database
   *  
   */
  function process_dir()
  {
    set_time_limit(0);
    $this->cron_model->scan_directory($this->assets_path, 'assets');
    echo "All Data Path Under {$this->assets_path} Directory Stored ";
    exit(0);
  }

  /**
   * Process all pending assets Data Files
   *
    2:// now we get station_id and store in assets table and get asset_id



    xml procssing start from here
    3. not get asset_type in pbcore.xml ( So there will be no entry in assets_asset_types{look up} ,asset_dates,date_types {look up} )
    4. for American Archive GUID pbcoreidentifier (field identifier) and store in identifiers
    5 assets_titles store(title and asset_title_types_id from refrence table) asset_title_types(Store titletype from xml {look up}) titale_source and title_ref not available in 1.3
    6. subjects and assets_subjects (If available )
    7.asset_description, description_types {look up}
    8 genres (Store info from xml and its id and asset_id store in asset_genres)
    9 coverage and coverage type  not required fiedls
    10 audience_levels(Store info from xml and its id and asset_id store in assets_audience_levels)
    11  relation_types(Store info from xml and its id and asset_id store in assets_relations)
    12  creators and creator_role(store info from xml and) assets_creators_roles(save creator_id,creater_role_id and asset_id)
    13 contributors and contributor_roles(store info from xml and) assets_contributors_roles (save contributors_id,contributor_roles_id and asset_id)
    14 publishers and publishers_roles(store info from xml and) assets_publishers_role(save assets_id,publishers_id and asset_id)
    15  nomination_status {lookup} nominations(store data from xml)
   */
  function process_xml_file()
  {
    $folders = $this->cron_model->get_all_data_folder();
    foreach ($folders as $folder)
    {
      $data = file_get_contents($folder->folder_path . 'data/organization.xml');
      $x = @simplexml_load_string($data);
      $data = xmlObjToArr($x);
      $station_cpb_id = $data['children']['cpb-id'][0]['text'];
      if (isset($station_cpb_id))
      {
        $station_data = $this->station_model->get_station_by_cpb_id($station_cpb_id);
        if (isset($station_data))
        {
          $data_files = $this->cron_model->get_pbcore_file_by_folder_id($folder->id);
          if (isset($data_files))
          {
            foreach ($data_files as $d_file)
            {
              if ($d_file->is_processed == 0)
              {
                $file_path = '';
                $file_path = trim($folder->folder_path . $d_file->file_path);
                if (is_file($file_path))
                {
                  $file_parts = pathinfo($file_path);
                  if (!isset($file_parts['extension']))
                  {
                    $server_root_path = trim(shell_exec('pwd'));
                    $src = ($server_root_path . '/' . $file_path);
                    $des = ($server_root_path . '/' . $file_path . '.xml');
                    copy($src, $des);
                  }

                  $asset_data = file_get_contents($file_path . '.xml');
                  if (isset($asset_data) && !empty($asset_data))
                  {
                    $asset_xml_data = @simplexml_load_string($asset_data);
                    $asset_d = xmlObjToArr($asset_xml_data);
                    echo "<pre>";
                    $asset_id = 1;
                    //$asset_id=$this->assets_model->insert_assets(array("stations_id"=>$station_data->id,"created"=>date("Y-m-d H:i:s")));
                    if (!isset($asset_d['attributes']['version']) || $asset_d['attributes']['version'] == '1.3')
                    {
                      $asset_children = $asset_d['children'];
                      if (isset($asset_children))
                      {
                        // pbcoreAssetType Start here
                        if (isset($asset_children['pbcoreAssetType']) || isset($asset_children['pbcoreassettype']))
                        {
                          print_r($asset_children);
                          exit();
                        }
                        // pbcoreAssetType End here
                        // pbcoreidentifier Start here
                        if (isset($asset_children['pbcoreidentifier']))
                        {
                          foreach ($asset_children['pbcoreidentifier'] as $pbcoreidentifier)
                          {
                            $identifier_d = array();
                            if (isset($pbcoreidentifier['children']['identifier'][0]))
                            {
                              $identifier_d['assets_id'] = $asset_id;
                              $identifier_d['identifier'] = $pbcoreidentifier['children']['identifier'][0]['text'];
                              $identifier_d['identifier_source'] = $pbcoreidentifier['children']['identifiersource'][0]['text'];
                              //print_r($identifier_d);	
                              //$this->assets_model->insert_identifiers($identifier_d);
                            }
                          }
                        }
                        // pbcoreidentifier End here
                        // pbcoreTitle Start here
                        if (isset($asset_children['pbcoretitle']))
                        {

                          foreach ($asset_children['pbcoretitle'] as $pbcoretitle)
                          {
                            $pbcore_title_d = array();

                            if (isset($pbcoretitle['children']['title'][0]))
                            {

                              $pbcore_title_d['assets_id'] = $asset_id;
                              $pbcore_title_d['title'] = $pbcoretitle['children']['title'][0]['text'];
                              if (isset($pbcoretitle['children']['titletype'][0]['text']))
                              {
                                $asset_title_types = $this->assets_model->get_asset_title_types_by_title_type($pbcoretitle['children']['titletype'][0]['text']);
                                if ($asset_title_types)
                                {
                                  $asset_title_types_id = $asset_title_types->id;
                                } else
                                {
                                  $asset_title_types_id = $this->assets_model->insert_asset_title_types(array("title_type" => $pbcoretitle['children']['titletype'][0]['text']));
                                }
                                $pbcore_title_d['asset_title_types_id'] = $asset_title_types_id;
                              }

                              $pbcore_title_d['created'] = date('Y-m-d H:i:s');
                              //For 2.0 
                              // $pbcore_title_d['title_source'] 
                              // $pbcore_title_d['title_ref']
                              //print_r($pbcore_title_d);	
                              //$this->assets_model->insert_asset_titles($pbcore_title_d);
                            }
                          }
                        }
                        // pbcoreTitle End here
                        // pbcoreSubject Start here
                        if (isset($asset_children['pbcoreSubject']) || isset($asset_children['pbcoresubject']))
                        {

                          foreach ($asset_children['pbcoreSubject'] as $pbcore_subject)
                          {
                            $pbcoreSubject_d = array();

                            if (isset($pbcore_subject['children']['subject'][0]))
                            {

                              $pbcoreSubject_d['assets_id'] = $asset_id;
                              if (isset($pbcore_subject['children']['subject'][0]['text']))
                              {
                                $subjects = $this->assets_model->get_subjects_id_by_subject($pbcore_subject['children']['subject'][0]['text']);
                                if ($subjects)
                                {
                                  $subject_id = $subjects->id;
                                } else
                                {
                                  //For 2.0  also add following value in insert array of subject
                                  //  subject_ref
                                  $subject_id = $this->assets_model->insert_subjects(
                                          array(
                                              "subject" => $pbcore_subject['children']['subject'][0]['text'],
                                              "subject_source" => isset($pbcore_subject['children']['subjectAuthorityUsed'][0]['text']) ? $pbcore_subject['children']['subjectAuthorityUsed'][0]['text'] : ''
                                          ));
                                }
                                $pbcoreSubject_d['subjects_id'] = $subject_id;

                                //Add Data into insert_assets_subjects
                                $assets_subject_id = $this->assets_model->insert_assets_subjects($pbcoreSubject_d);
                              }
                            }
                          }
                        }
                        // pbcoreSubject End here
                        // pbcoreDescription Start here
                        if (isset($asset_children['pbcoreDescription']))
                        {

                          foreach ($asset_children['pbcoreDescription'] as $pbcore_description)
                          {
                            $asset_descriptions_d = array();

                            if (isset($pbcore_description['children']['description'][0]))
                            {

                              $asset_descriptions_d['assets_id'] = $asset_id;
                              $asset_descriptions_d['description'] = $pbcore_description['children']['description'][0]['text'];
                              if (isset($pbcoretitle['children']['descriptionType'][0]['text']))
                              {
                                $asset_description_type = $this->assets_model->get_description_by_type($pbcoretitle['children']['descriptionType'][0]['text']);
                                if ($asset_description_type)
                                {
                                  $asset_description_types_id = $asset_description_type->id;
                                } else
                                {
                                  $asset_description_types_id = $this->assets_model->insert_asset_title_types(array("description_type" => $pbcoretitle['children']['descriptionType'][0]['text']));
                                }
                                $asset_descriptions_d['description_types_id'] = $asset_title_types_id;
                              }
                              // Insert Data into asset_description
                              //print_r($asset_descriptions_d);
                              $this->assets_model->insert_asset_descriptions($asset_descriptions_d);
                            }
                          }
                        }
                        // pbcoreDescription End here
                        // Nouman Tayyab
                        // pbcoreGenre Start
                        if (isset($asset_children['pbcoreGenre']))
                        {

                          foreach ($asset_children['pbcoreGenre'] as $pbcore_genre)
                          {
                            $asset_genre_d = array();
                            $asset_genre = array();
                            $asset_genre['assets_id'] = $asset_id;
                            if (isset($pbcore_genre['children']['genre'][0]))
                            {

                              $asset_genre_d['genre'] = $pbcore_genre['children']['genre'][0]['text'];
                              $asset_genre_type = $this->assets_model->get_genre_type($asset_genre_d['genre']);
                              if ($asset_genre_type)
                              {
                                $asset_genre['genres_id'] = $asset_genre_type->id;
                              } else
                              {
                                if (isset($pbcore_genre['children']['genreAuthorityUsed'][0]))
                                {

                                  $asset_genre_d['genre_source'] = $pbcore_genre['children']['genreAuthorityUsed'][0]['text'];
                                }
                                $asset_genre_id = $this->assets_model->insert_genre($asset_genre_d);
                                $asset_genre['genres_id'] = $asset_genre_id;
                              }



                              $this->assets_model->insert_asset_genre($asset_genre);
                            }
                          }
                        }
                        // pbcoreGenre End
                        // pbcoreCoverage Start
                        if (isset($asset_children['pbcoreCoverage']))
                        {

                          foreach ($asset_children['pbcoreCoverage'] as $pbcore_coverage)
                          {
                            $coverage = array();
                            $coverage['assets_id'] = $asset_id;
                            if (isset($pbcore_coverage['children']['coverage'][0]))
                            {

                              $coverage['coverage'] = $pbcore_coverage['children']['coverage'][0]['text'];
                              if (isset($pbcore_coverage['children']['coverageType'][0]))
                              {

                                $coverage['genre_source'] = $pbcore_coverage['children']['coverageType'][0]['text'];
                              }
                              $asset_coverage = $this->assets_model->insert_coverage($coverage);
                            }
                          }
                        }
                        // pbcoreCoverage End
                        // pbcoreAudienceLevel Start
                        if (isset($asset_children['pbcoreAudienceLevel']))
                        {

                          foreach ($asset_children['pbcoreAudienceLevel'] as $pbcore_aud_level)
                          {
                            $audience_level = array();
                            $asset_audience_level = array();
                            $asset_audience_level['assets_id'] = $asset_id;
                            if (isset($pbcore_aud_level['children']['audienceLevel'][0]))
                            {

                              $audience_level['audience_level'] = $pbcore_aud_level['children']['audienceLevel'][0]['text'];
                              $db_audience_level = $this->assets_model->get_audience_level($audience_level['audience_level']);
                              if ($db_audience_level)
                              {
                                $asset_audience_level['audience_levels_id'] = $db_audience_level->id;
                              } else
                              {
                                $asset_audience_level['audience_levels_id'] = $this->assets_model->insert_audience_level($audience_level);
                              }
                              $asset_audience = $this->assets_model->insert_asset_audience($asset_audience_level);
                            }
                          }
                        }
                        // pbcoreAudienceLevel End
                        // pbcoreAudienceRating Start
                        if (isset($asset_children['pbcoreAudienceRating']))
                        {

                          foreach ($asset_children['pbcoreAudienceRating'] as $pbcore_aud_rating)
                          {
                            $audience_rating = array();
                            $asset_audience_rating = array();
                            $asset_audience_rating['assets_id'] = $asset_id;
                            if (isset($pbcore_aud_rating['children']['audienceRating'][0]))
                            {

                              $audience_rating['audience_rating'] = $pbcore_aud_rating['children']['audienceRating'][0]['text'];
                              $db_audience_rating = $this->assets_model->get_audience_rating($audience_rating['audience_rating']);
                              if ($db_audience_rating)
                              {
                                $asset_audience_rating['audience_ratings_id'] = $db_audience_rating->id;
                              } else
                              {
                                $asset_audience_rating['audience_ratings_id'] = $this->assets_model->insert_audience_rating($audience_rating);
                              }
                              $asset_audience_rate = $this->assets_model->insert_asset_audience_rating($asset_audience_rating);
                            }
                          }
                        }
                        // pbcoreAudienceRating End
                        // pbcoreAnnotation Start
                        if (isset($asset_children['pbcoreAnnotation']))
                        {

                          foreach ($asset_children['pbcoreAnnotation'] as $pbcore_annotation)
                          {
                            $annotation = array();
                            $annotation['assets_id'] = $asset_id;
                            if (isset($pbcore_annotation['children']['annotation'][0]))
                            {

                              $annotation['annotation'] = $pbcore_annotation['children']['annotation'][0]['text'];

                              $asset_annotation = $this->assets_model->insert_annotation($annotation);
                            }
                          }
                        }
                        // pbcoreAnnotation End
                        // pbcoreRelation Start here
                        if (isset($asset_children['pbcoreRelation']))
                        {

                          foreach ($asset_children['pbcoreRelation'] as $pbcore_relation)
                          {
                            $assets_relation = array();
                            $assets_relation['assets_id'] = $asset_id;
                            $relation_types = array();
                            if (isset($pbcore_relation['children']['relationType'][0]))
                            {
                              $relation_types['relation_type'] = $pbcore_relation['children']['relationType'][0]['text'];
                              $db_relations = $this->assets_model->get_relation_types($relation_types['relation_type']);
                              if ($db_relations)
                              {
                                $assets_relation['relation_types_id'] = $db_relations->id;
                              } else
                              {
                                $assets_relation['relation_types_id'] = $this->assets_model->insert_relation_types($relation_types);
                              }
                              if (isset($pbcore_relation['children']['relationIdentifier'][0]))
                              {
                                $assets_relation['relation_identifier'] = $pbcore_relation['children']['relationIdentifier'][0]['text'];
                                $this->assets_model->insert_asset_relation($assets_relation);
                              }
                              // Insert Data into asset_description
                              //print_r($asset_descriptions_d);
                            }
                          }
                        }
                        // pbcoreRelation End here
                        // End By Nouman Tayyab
                      }

                      unset($asset_d);
                      unset($asset_xml_data);
                      unset($asset_data);
                      unlink($des);
                      //$this->db->where('id',$d_file->id);
                      //$this->db->update('process_pbcore_data',array('is_processed'=>1));
                      //echo $this->db->last_query();
                      echo "<br/>";
                    }
                  }
                }
              }
            }
          }
        }
      }

      exit();
    }
  }

}