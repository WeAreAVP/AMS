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
	}

	public function edit()
	{
		$asset_id = $this->uri->segment(3);

		if ( ! empty($asset_id))
		{

			
			$data['asset_detail'] = $this->manage_asset->get_asset_detail_by_id($asset_id);
			if ($data['asset_detail'])
			{
				debug($data['asset_detail'],FALSE);
				$data['pbcore_asset_types'] = $this->manage_asset->get_picklist_values(1);
				$data['pbcore_asset_date_types'] = $this->manage_asset->get_picklist_values(2);
				$data['pbcore_asset_title_types'] = $this->manage_asset->get_picklist_values(3);
				$data['pbcore_asset_subject_types'] = $this->manage_asset->get_subject_types();
				$data['pbcore_asset_description_types'] = $this->manage_asset->get_picklist_values(4);
				$data['pbcore_asset_audience_level'] = $this->manage_asset->get_picklist_values(5);
				$data['pbcore_asset_audience_rating'] = $this->manage_asset->get_picklist_values(6);
				$data['pbcore_asset_relation_types'] = $this->manage_asset->get_picklist_values(7);
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

	public function insert_pbcore_values()
	{
		$asset_type = array('Actor','Advisor','Anchor','Announcer','Arranger','Artist' ,'Assistant Camera Operator',
			'Assistant Director','Assistant Editor','Assistant Producer','Assistant Researcher','Assistant Stage Manager',
			'Assistant to the Producer','Assistant Unit Manager','Associate Director','Associate Producer','Audio',
			'Audio Assistant','Audio Editor','Audio Engineer','Audio Mixer','Author','Boom Operator','Broadcast Engineer',
			'Camera Assistant','Camera Operator','Captions','Casting','Chief Camera Operator','Cinematographer','Co-Producer',
			'Commentary Editor','Commentator','Community Coordinator','Composer','Concept','Conductor','Crane','Describer',
			'Designer','Developer','Director','Director: Artistic','Director: Dance','Director: Documentary Material',
			'Director: Photography','Director: Segment','Edit Programmer','Editor','Editor: Graphics','Editor: Segment',
			'Editorial Director','Engineer','Essayist','Executive Producer','Fashion Consultant','Field Producer',
			'Film Editor','Film Sound','Filmmaker','Floor Manager','Funder','Gaffer','Graphic Designer','Graphics',
			'Guest','Host','Illustrator','Instrumentalist','Intern','Interpreter','Interviewee','Interviewer','Lecturer',
			'Lighting','Lighting Assistant','Lighting Director','Make-Up','Manager','Mobile Unit Supervisor','Moderator',
			'Music Assistant','Music Coordinator','Music Director','Musical Staging','Musician','Narrator','News Director',
			'Panelist','Performer','Performing Group','Photographer','Post-production Audio','Post-production Supervisor',
			'Producer','Producer: Coordinating','Producer: Segment','Producer: Website','Production Assistant','Production Manager',
			'Production Personnel','Production Secretary','Production Unit','Project Director','Publicist','Reader',
			'Recording engineer','Recordist','Reporter','Researcher','Scenic Design','Senior Broadcast Engineer','Senior Editor',
			'Senior Producer','Senior Researcher','Series Producer','Sound','Sound Mix','Speaker','Sponsor','Staff Volunteer',
			'Stage Manager','Still Photography','Studio Technician','Subject','Switcher','Synthesis','Synthesis Musician',
			'Talent Coordinator','Technical Consultant','Technical Director','Technical Production','Theme Music','Titlist',
			'Translator','Unit Manager','Video','Videotape Assembly','Videotape Editor','Videotape Engineer','Videotape Recordist',
			'Vidifont Operator','Vocalist','VTR Recordist','Wardrobe','Writer'
			
			);
		foreach ($asset_type as $value)
		{
			$this->manage_asset->insert_picklist_value(array('element_type_id' => 9, 'value' => $value));
		}
	}

}