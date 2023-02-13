<?php
/**
 * Astra Child Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Astra Child Theme
 * @since 1.0.0
 */

/**
 * Define Constants
 */
define( 'CHILD_THEME_ASTRA_CHILD_THEME_VERSION', '1.0.0' );

/**
 * Enqueue styles
 */
add_action( 'wp_enqueue_scripts', 'child_enqueue_styles', 15 );
function child_enqueue_styles() {
	wp_enqueue_style( 'astra-child-theme-theme-css', get_stylesheet_directory_uri() . '/style.css', array('astra-theme-css'), CHILD_THEME_ASTRA_CHILD_THEME_VERSION, 'all' );
}

/******************************************************************************************/
// OSIR project code
/******************************************************************************************/


// Custom navigation menus
require "my-nav-menus.php";

// GF & mepr custom hooks
require "my-custom-hooks.php";

// Astra theme custom config
require "my-astra-theme-config.php";

//----------------------------------------------------------------------------------------
// Survey Logic

// https://www.wpbeginner.com/wp-tutorials/how-to-properly-add-javascripts-and-styles-in-wordpress/
// Gravity Forms custom JS (loaded in footer)
add_action('wp_enqueue_scripts', 'gf_adding_scripts');
function gf_adding_scripts(){

	// Load date picker library only for member press subscriptions tab (remove .datepicker errors)
	if ( isset($_GET['action']) && $_GET['action'] == 'subscriptions') {
		wp_register_script('gf_custom_js', get_stylesheet_directory_uri().'/js/gf_custom.js', array('jquery'), '1.1', true);
		wp_enqueue_script('gf_custom_js');
	}

	// Load print QR code script only for this page
	if (is_page( 'osir-survey-signup-url-qr' )) {
		wp_register_script('qr_code_custom_js', get_stylesheet_directory_uri().'/js/qr_code_custom.js', array('jquery'), '1.1', true);
		wp_enqueue_script('qr_code_custom_js');
	}

	// Show survey submit progress indicator only for survey page  
	if ( isset($_GET['my_gform_id']) && $_GET['my_gform_id'] > 0 ) {
		wp_register_script('survey_page_custom_js', get_stylesheet_directory_uri().'/js/survey_page_custom.js', array('jquery'), '1.1', true);
		wp_enqueue_script('survey_page_custom_js');
	}
}

// Validate survey membership before GF is rendered
// https://docs.gravityforms.com/gform_pre_render/
add_filter( 'gform_pre_render', 'gf_pre_render' );
function gf_pre_render($form ) {
	global $post;

	// Setup on memberpress membership URL back end page
	 if ( isset($_GET['my_gform_id']) ) {
		 $gf_id = $_GET['my_gform_id'];
	 }

	// Setup on gravity form back end field
	foreach( $form['fields'] as $field ) {
		if ($field->get_input_type() === 'hidden' && $field->cssClass === 'my_gform_id') {
			$gform_id = $field->defaultValue;
		}
	}

	// Get current page id
  if (isset($post->ID)) {
    // Added manually using advanced custom fields inside each visualizer word press page
    $page_gform_id = get_post_meta( $post->ID, 'my_gform_id', true );
  }

	// echo "<br>is_admin(): ".is_admin();
	// echo "<br>gf_id: ".$gf_id.", gform_id: ".$gform_id.", page_gform_id: ".$page_gform_id;

	// not back end page for gravity form
	if ( !is_admin() && isset($page_gform_id) ) {
		if ( !isset($gf_id) || !isset($gform_id) || $gf_id != $gform_id ) {
			echo "<a href='/'>&#60;&#60;Go Back</a><br><br><p style='color:#FF0000;font-weight:bold;'>Error! Invalid survey: my_gform_id parameter is missing or invalid. Please contact customer service.</p>";
			exit;
		}
	}

	return $form;
}

// GF survey form submissions hook
add_action( 'gform_after_submission_'.$my_gform_id, 'gf_after_submission', 10, 2 );
function gf_after_submission($entry, $form ) {
	global $survey_entry;
	global $survey_form;
	$survey_entry = $entry;
	$survey_form = $form;

	add_action( 'astra_entry_content_after', 'astra_entry_content_after_gform_submission');
}

// Astra theme hook
function astra_entry_content_after_gform_submission() {
	global $survey_entry;
	global $survey_form;

	$total_resiliency_behaviours_score = 0;
	$total_support_programs_score = 0;
	$total_supportive_leadership_score = 0;
	$total_supportive_environment_score = 0;
	$total_osir_score = 0;

	$mental_health_score = 0;
	$physical_health_score = 0;
	$health_fatigue_concerns_score = 0;
	$health_burnout_concerns_score = 0;
	$health_stress_concerns_score = 0;

	$impact_questions_attendance = 0;
	$impact_questions_presenteeism = 0;
	$impact_questions_motivation_score = 0;

	$vulnerability_university_site_department = '';
	$vulnerability_victoria_site_department = '';
	$vulnerability_vocation = '';
	$demographics_gender = '';
	$demographics_age = 0;
	$demographics_dependents = '';

	// MOL
	$demographics_region_branch_primarily_work = '';
	$demographics_region_branch_employment_practices = '';
	$demographics_region_branch_occupational_health_and_safety = '';
	$demographics_region_branch_operation_integration_unit = '';
	$demographics_region_branch_assistant_deputy_minister_office = '';
	$demographics_region_branch_central_east_region = '';
	$demographics_region_branch_central_west_region = '';
	$demographics_region_branch_eastern_region = '';
	$demographics_region_branch_northern_region = '';
	$demographics_region_branch_south_western_region = '';
	$demographics_region_branch_western_region = '';
	
	// Create GFSurvey instance
	if ( ! class_exists( 'GFSurvey' ) ) {
		require_once ABSPATH . 'wp-content/plugins/gravityformssurvey/class-gf-survey.php';
	}
	$GFSurveyInstance = GFSurvey::get_instance();

	// Survey fields
	foreach ( $survey_form['fields'] as $field ) {
	
		// ---------------------------------------------------------------------

		// GF Form ID
		if ($field->get_input_type() === 'hidden' && $field->cssClass === 'my_gform_id') {
			$my_gform_id = $field->defaultValue;
		}

		// Corporate Parent Account User ID (1 parent account per each membership)
		if ($field->get_input_type() === 'hidden' && $field->cssClass === 'corporate_parent_account_user_id') {
			$gf_corporate_parent_uid = $field->defaultValue;
		}

		// likert fields ---------------------------------------------------------------------
 
		if ( $field->get_input_type() == 'likert' && $field->gsurveyLikertEnableScoring ) {

			// 4 Sub Scales-------------------------------------------------------------------------

			// Resiliency Behaviours
			if ($field->cssClass === 'resiliency_behaviours') {	
				// echo "<br><br>field: ". $field->id. ", ". $field->cssClass. ", ". $field->label;
				// echo "<br>Resiliency Behaviours score: ". $GFSurveyInstance:: get_field_score($field, $survey_entry);
				$total_resiliency_behaviours_score += $GFSurveyInstance:: get_field_score($field, $survey_entry);
			}
			
			// Support Programs
			if ($field->cssClass === 'support_programs') {
				$total_support_programs_score += $GFSurveyInstance:: get_field_score($field, $survey_entry);
			}

			// Supportive Leadership
			if ($field->cssClass === 'supportive_leadership') {
				$total_supportive_leadership_score += $GFSurveyInstance:: get_field_score($field, $survey_entry);
			}
			
			// Supportive Environment
			if ($field->cssClass === 'supportive_environment') {
				$total_supportive_environment_score += $GFSurveyInstance:: get_field_score($field, $survey_entry);
			}

			// Health----------------------------------------------------------------------------------

			// Good mental health
			if ($field->cssClass === 'mental_health') {
				$mental_health_score = $GFSurveyInstance:: get_field_score($field, $survey_entry);
			}

			// Good physical health
			if ($field->cssClass === 'physical_health') {
				$physical_health_score = $GFSurveyInstance:: get_field_score($field, $survey_entry);
			}

			// Fatigue concerns
			if ($field->cssClass === 'health_fatigue_concerns') {
				$health_fatigue_concerns_score = $GFSurveyInstance:: get_field_score($field, $survey_entry);
			}

			// Burnout concerns
			if ($field->cssClass === 'health_burnout_concerns') {
				$health_burnout_concerns_score = $GFSurveyInstance:: get_field_score($field, $survey_entry);
			}

			// Stress concerns
			if ($field->cssClass === 'health_stress_concerns') {
				$health_stress_concerns_score = $GFSurveyInstance:: get_field_score($field, $survey_entry);
			}

			// Impact Questions: Motivation
			if ($field->cssClass === 'impact_questions_motivation') {
				$impact_questions_motivation_score = $GFSurveyInstance:: get_field_score($field, $survey_entry);
			}
		}

		// -----------------------------------------------------------------
		// Impact Questions

		// Attendance 
		if ($field->cssClass === 'impact_questions_attendance') {
			$impact_questions_attendance = intval(GFFormsModel::get_field_value($field));
		}

		// Presenteeism 
		if ($field->cssClass === 'impact_questions_presenteeism') {
			$impact_questions_presenteeism = intval(GFFormsModel::get_field_value($field));
		}

		// -----------------------------------------------------------------
		// Demographic Questions

		if ($field->cssClass === 'vulnerability_university_site_department') {
			$vulnerability_university_site_department = GFFormsModel::get_field_value($field);
		}

		if ($field->cssClass === 'vulnerability_victoria_site_department') {
			$vulnerability_victoria_site_department = GFFormsModel::get_field_value($field);
		}

		// What is your current vocation?
		if ($field->cssClass === 'vulnerability_vocation') {
			$vulnerability_vocation = GFFormsModel::get_field_value($field);
		}

		// What gender do you identify with? 
		if ($field->cssClass === 'demographics_gender') {
			$demographics_gender = GFFormsModel::get_field_value($field);
		}
	
		// Demographics: What is your age? 
		if ($field->cssClass === 'demographics_age') {
			$demographics_age = intval(GFFormsModel::get_field_value($field));
		}

		// Do you have any dependents or care responsibilities outside of the workplace?
		if ($field->cssClass === 'demographics_dependents') {
			$demographics_dependents = GFFormsModel::get_field_value($field);
		}

		// -----------------------------------------------------------------
		// Minsitry of Labour: Demographics Questions

		// At which Region or Branch do you primarily work?
		if ($field->cssClass === 'demographics_region_branch_primarily_work' ) {
			// echo "<br><br>field: ". $field->id. ", ". $field->cssClass. ", "; print_r(GFFormsModel::get_field_value($field));
			$demographics_region_branch_primarily_work = GFFormsModel::get_field_value($field);
		}

		// 1.	Employment Practices Branch 
		if ($field->cssClass === 'demographics_region_branch_employment_practices' ) {
			$demographics_region_branch_employment_practices = GFFormsModel::get_field_value($field);
		}

		// 2.	Occupational Health and Safety Branch 
		if ($field->cssClass === 'demographics_region_branch_occupational_health_and_safety' ) {
			$demographics_region_branch_occupational_health_and_safety = GFFormsModel::get_field_value($field);
		}

		// 3.	Operation Integration Unit
		if ($field->cssClass === 'demographics_region_branch_operation_integration_unit' ) {
			$demographics_region_branch_operation_integration_unit = GFFormsModel::get_field_value($field);
		}

		// 4.	Assistant Deputy Minister Office
		if ($field->cssClass === 'demographics_region_branch_assistant_deputy_minister_office' ) {
			$demographics_region_branch_assistant_deputy_minister_office = GFFormsModel::get_field_value($field);
		}

		// 5.	Central East Region
		if ($field->cssClass === 'demographics_region_branch_central_east_region' ) {
			$demographics_region_branch_central_east_region = GFFormsModel::get_field_value($field);
		}

		// 6.	Central West Region
		if ($field->cssClass === 'demographics_region_branch_central_west_region' ) {
			$demographics_region_branch_central_west_region = GFFormsModel::get_field_value($field);
		}

		// 7.	Eastern Region
		if ($field->cssClass === 'demographics_region_branch_eastern_region' ) {
			$demographics_region_branch_eastern_region = GFFormsModel::get_field_value($field);
		}

		// 8.	Northern Region
		if ($field->cssClass === 'demographics_region_branch_northern_region' ) {
			$demographics_region_branch_northern_region = GFFormsModel::get_field_value($field);
		}

		// 9.	South-Western Region
		if ($field->cssClass === 'demographics_region_branch_south_western_region' ) {
			$demographics_region_branch_south_western_region = GFFormsModel::get_field_value($field);
		}

		// 10. Western Region
		if ($field->cssClass === 'demographics_region_branch_western_region' ) {
			$demographics_region_branch_western_region = GFFormsModel::get_field_value($field);	
		}
	}
	
	$total_osir_score = $total_resiliency_behaviours_score + $total_support_programs_score +
		$total_supportive_leadership_score + $total_supportive_environment_score;

	// Debug
	// echo '<br><br>Entry ID: '.$survey_entry['id'];
	// var_dump($survey_form['fields']);

	// Survey submission confirmation messages
	echo getParticipantReportMsg($my_gform_id, $survey_entry['id']);

	gform_add_meta_entry_survey( $survey_entry, $total_osir_score, $total_resiliency_behaviours_score,
		$total_support_programs_score, $total_supportive_leadership_score, $total_supportive_environment_score,
		$mental_health_score, $physical_health_score, $health_fatigue_concerns_score, $health_burnout_concerns_score,
		$health_stress_concerns_score, $impact_questions_attendance, $impact_questions_presenteeism,
		$impact_questions_motivation_score, $vulnerability_vocation, $vulnerability_university_site_department,
		$vulnerability_victoria_site_department, $demographics_gender, $demographics_age, $demographics_dependents, 
		$demographics_region_branch_primarily_work, $demographics_region_branch_employment_practices,
		$demographics_region_branch_occupational_health_and_safety, $demographics_region_branch_operation_integration_unit,
		$demographics_region_branch_assistant_deputy_minister_office, $demographics_region_branch_central_east_region,
		$demographics_region_branch_central_west_region, $demographics_region_branch_eastern_region,
		$demographics_region_branch_northern_region, $demographics_region_branch_south_western_region,
		$demographics_region_branch_western_region, $my_gform_id, $gf_corporate_parent_uid );
}

// Add survey meta DB entry for each user submission
function gform_add_meta_entry_survey( $survey_entry, $total_osir_score, $total_resiliency_behaviours_score,
	$total_support_programs_score, $total_supportive_leadership_score, $total_supportive_environment_score,
	$mental_health_score, $physical_health_score, $health_fatigue_concerns_score, $health_burnout_concerns_score,
	$health_stress_concerns_score, $impact_questions_attendance, $impact_questions_presenteeism,
	$impact_questions_motivation_score, $vulnerability_vocation, $vulnerability_university_site_department,
	$vulnerability_victoria_site_department, $demographics_gender, $demographics_age, $demographics_dependents,
	$demographics_region_branch_primarily_work, $demographics_region_branch_employment_practices,
	$demographics_region_branch_occupational_health_and_safety, $demographics_region_branch_operation_integration_unit,
	$demographics_region_branch_assistant_deputy_minister_office, $demographics_region_branch_central_east_region,
	$demographics_region_branch_central_west_region, $demographics_region_branch_eastern_region,
	$demographics_region_branch_northern_region, $demographics_region_branch_south_western_region,
	$demographics_region_branch_western_region, $my_gform_id, $gf_corporate_parent_uid ){
	global $survey_entry;

	// save gf submission user meta entry
	add_user_meta( get_current_user_id(), 'gf_survey_entry', $survey_entry['id']);

	// add gf meta entries
	gform_add_meta( $survey_entry['id'], 'gf_subscriber_uid', get_current_user_id() );
	gform_add_meta( $survey_entry['id'], 'gf_corporate_parent_uid', $gf_corporate_parent_uid );
	gform_add_meta( $survey_entry['id'], 'my_gform_id', $my_gform_id );

	gform_add_meta( $survey_entry['id'], 'total_resiliency_behaviours_score', $total_resiliency_behaviours_score );
	gform_add_meta( $survey_entry['id'], 'total_support_programs_score', $total_support_programs_score );
	gform_add_meta( $survey_entry['id'], 'total_supportive_leadership_score', $total_supportive_leadership_score );
	gform_add_meta( $survey_entry['id'], 'total_supportive_environment_score', $total_supportive_environment_score );
	gform_add_meta( $survey_entry['id'], 'total_osir_score', $total_osir_score );
	gform_add_meta( $survey_entry['id'], 'osir_profile', getOSIRProfile($total_osir_score) );

	gform_add_meta( $survey_entry['id'], 'mental_health_score', $mental_health_score );
	gform_add_meta( $survey_entry['id'], 'physical_health_score', $physical_health_score );
	gform_add_meta( $survey_entry['id'], 'health_fatigue_concerns_score', $health_fatigue_concerns_score );
	gform_add_meta( $survey_entry['id'], 'health_burnout_concerns_score', $health_burnout_concerns_score );
	gform_add_meta( $survey_entry['id'], 'health_stress_concerns_score', $health_stress_concerns_score );
	
	gform_add_meta( $survey_entry['id'], 'impact_questions_attendance', $impact_questions_attendance);
	gform_add_meta( $survey_entry['id'], 'impact_questions_presenteeism', $impact_questions_presenteeism );
	gform_add_meta( $survey_entry['id'], 'impact_questions_motivation_score', $impact_questions_motivation_score );
	
	gform_add_meta( $survey_entry['id'], 'vulnerability_university_site_department', $vulnerability_university_site_department );
	gform_add_meta( $survey_entry['id'], 'vulnerability_victoria_site_department', $vulnerability_victoria_site_department );
	gform_add_meta( $survey_entry['id'], 'vulnerability_vocation', $vulnerability_vocation );
	gform_add_meta( $survey_entry['id'], 'demographics_gender', $demographics_gender );
	gform_add_meta( $survey_entry['id'], 'demographics_age', $demographics_age );
	gform_add_meta( $survey_entry['id'], 'demographics_dependents', $demographics_dependents );

	// MOL Demographics
	gform_add_meta( $survey_entry['id'], 'demographics_region_branch_primarily_work', $demographics_region_branch_primarily_work );
	gform_add_meta( $survey_entry['id'], 'demographics_region_branch_employment_practices', $demographics_region_branch_employment_practices );
	gform_add_meta( $survey_entry['id'], 'demographics_region_branch_occupational_health_and_safety', $demographics_region_branch_occupational_health_and_safety );
	gform_add_meta( $survey_entry['id'], 'demographics_region_branch_operation_integration_unit', $demographics_region_branch_operation_integration_unit );
	gform_add_meta( $survey_entry['id'], 'demographics_region_branch_assistant_deputy_minister_office', $demographics_region_branch_assistant_deputy_minister_office );
	gform_add_meta( $survey_entry['id'], 'demographics_region_branch_central_east_region', $demographics_region_branch_central_east_region );
	gform_add_meta( $survey_entry['id'], 'demographics_region_branch_central_west_region', $demographics_region_branch_central_west_region );
	gform_add_meta( $survey_entry['id'], 'demographics_region_branch_eastern_region', $demographics_region_branch_eastern_region );
	gform_add_meta( $survey_entry['id'], 'demographics_region_branch_northern_region', $demographics_region_branch_northern_region );
	gform_add_meta( $survey_entry['id'], 'demographics_region_branch_south_western_region', $demographics_region_branch_south_western_region );
	gform_add_meta( $survey_entry['id'], 'demographics_region_branch_western_region', $demographics_region_branch_western_region );

	// Track number of user submissions instead of relying on GF entry_id
	gform_add_meta( $survey_entry['id'], 'is_survey_entry_submitted_by_user', 'yes' );

	// Save submission entry date/time (EST)
	date_default_timezone_set('America/New_York');
	gform_add_meta( $survey_entry['id'], 'survey_entry_submitted_date', date("Y-m-d") );
}

//----------------------------------------------------------------------------------------

// Survey submission confirmation messages
require "my-survey-confirmation-messages.php";

// Visualizer configuration params
require "my-visualizer-config.php";

// Visualizer charts calculations & data
require "my-visualizer-data.php";

// Charts helper functions
require "my-survey-charts-shortcodes.php";
