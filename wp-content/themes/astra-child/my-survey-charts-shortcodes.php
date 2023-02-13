<?php

//----------------------------------------------------------------------------------------
// Charts short codes

// Total number of submissions for all participants regardless of OSIR profiles
function total_number_of_answers_func( $atts ) {
  return '<h5>Total number of answers: '. total_number_of_answers().'</h5>';
}
add_shortcode( 'totalnumberofanswers', 'total_number_of_answers_func' );

function total_number_of_answers(){
	global $wpdb;
	global $post;
  $my_gform_id = 0; // fall back

  // Get current page id
  if (isset($post->ID)) {
    // Added manually using advanced custom fields inside each visualizer word press page
    $my_gform_id = get_post_meta( $post->ID, 'my_gform_id', true );
    // echo "<br><br>page id: ".$post->ID.", my_gform_id: ".$my_gform_id;
  }

	$sql  = "SELECT count(*) AS 'numberSubmissions'";
	$sql .= " FROM `wp_gf_entry_meta`";
	$sql .= " WHERE `wp_gf_entry_meta`.`meta_key` = 'is_survey_entry_submitted_by_user'";
	$sql .= " AND `wp_gf_entry_meta`.`meta_value` = 'yes'";
	$sql .= " AND `wp_gf_entry_meta`.`form_id` = ".$my_gform_id;

	$results = $wpdb->get_results( $sql, ARRAY_A );

	$numberSubmissions = isset($results[0]) ? $results[0]['numberSubmissions'] : 0;
	return $numberSubmissions;
}

// OSIR average company score
function average_company_score_by_osir_func( $atts ) {
  return '<h5>Company Average Score: '. number_format(average_company_score_by_osir(), 2, '.', ' ').'</h5>';
}
add_shortcode( 'avgcompanyscorebyosir', 'average_company_score_by_osir_func' );

function average_company_score_by_osir(){
	global $wpdb;
	global $post;
	$my_gform_id = 0; // fall back

  // Get current page id
  if (isset($post->ID)) {
    // Added manually using advanced custom fields inside each visualizer word press page
    $my_gform_id = get_post_meta( $post->ID, 'my_gform_id', true );
  }

	$sql  = "SELECT `wp_gf_entry_meta`.`meta_value` AS 'osirProfile',"; 
	$sql .= " SUM(osirScore.`meta_value`) AS 'totalOSIRScore'";
	$sql .= " FROM `wp_gf_entry_meta`,";
	$sql .= " (SELECT * FROM `wp_gf_entry_meta` WHERE `meta_key` = 'total_osir_score') osirScore";
	$sql .= " WHERE `wp_gf_entry_meta`.`meta_key` = 'osir_profile'";
	$sql .= " AND `wp_gf_entry_meta`.`entry_id` = osirScore.`entry_id`";
	$sql .= " AND `wp_gf_entry_meta`.`form_id` = ".$my_gform_id;
	$sql .= " GROUP BY osirProfile";

	$results = $wpdb->get_results( $sql, ARRAY_A );

	$myresults[0] = isset($results[0]['totalOSIRScore']) ? $results[0]['totalOSIRScore'] : 0;
	$myresults[1] = isset($results[1]['totalOSIRScore']) ? $results[1]['totalOSIRScore'] : 0;
	$myresults[2] = isset($results[2]['totalOSIRScore']) ? $results[2]['totalOSIRScore'] : 0;

	// echo "<br><br>".$sql."<br><br>";
	// print_r($myresults);

	$OSIRAverageCompanyScore = ( total_number_of_answers() > 0 ) ? 
	( $myresults[0] + $myresults[1] + $myresults[2] ) / total_number_of_answers() : 0;

	return $OSIRAverageCompanyScore;
}

// Total number of submissions for all participants per OSIR profile
function total_number_of_submissions_per_osir_profile($osir_profile, $my_gform_id){
	global $wpdb;
	global $post;
  $my_gform_id = 0; // fall back

  // Get current page id
  if (isset($post->ID)) {
    // Added manually using advanced custom fields inside each visualizer word press page
    $my_gform_id = get_post_meta( $post->ID, 'my_gform_id', true );
    // echo "<br><br>page id: ".$post->ID.", my_gform_id: ".$my_gform_id;
  }

	$sql  = "SELECT `wp_gf_entry_meta`.`meta_value`,";
	$sql .= " count(*) AS 'numberSubmissions'";
	$sql .= " FROM `wp_gf_entry_meta`,";
	$sql .= " (SELECT * FROM `wp_gf_entry_meta` WHERE `meta_key` = 'is_survey_entry_submitted_by_user' AND `wp_gf_entry_meta`.`meta_value` = 'yes') isSubmitted";
	$sql .= " WHERE `wp_gf_entry_meta`.`meta_key` = 'osir_profile'";
	$sql .= " AND `wp_gf_entry_meta`.`entry_id` = isSubmitted.`entry_id`";
	$sql .= " AND `wp_gf_entry_meta`.`form_id` = ".$my_gform_id;
	$sql .= " AND `wp_gf_entry_meta`.`meta_value` = '".$osir_profile."'";

	$results = $wpdb->get_results( $sql, ARRAY_A );
	$numberSubmissionsPerOSIRProfile = isset($results[0]) ? $results[0]['numberSubmissions'] : 0;

	// echo "<br><br>sql:<br>".$sql;
	// echo "<br><br>results:<br>";
	// print_r($results);

	return $numberSubmissionsPerOSIRProfile;
}

/*
// General mental outlook average company score
function outlook_average_company_score_func( $atts ) {
  return '<h5>Company Average Score: '. number_format(outlook_average_company_score(), 2, '.', ' ').'</h5>';
}
add_shortcode( 'outlookavgcompanyscore', 'outlook_average_company_score_func' );

function outlook_average_company_score(){
	global $wpdb;
	global $post;
	$my_gform_id = 0; // fall back

  // Get current page id
  if (isset($post->ID)) {
    // Added manually using advanced custom fields inside each visualizer word press page
    $my_gform_id = get_post_meta( $post->ID, 'my_gform_id', true );
  }

	$sql = "SELECT `wp_gf_entry_meta`.`meta_value` AS 'osirProfile',";
	$sql .= " SUM(outlookScore.`meta_value`) AS 'totalOutlookScore'";
	$sql .= " FROM `wp_gf_entry_meta`,";
	$sql .= " (SELECT * FROM `wp_gf_entry_meta` WHERE `meta_key` = 'total_outlook_score') outlookScore";
	$sql .= " WHERE `wp_gf_entry_meta`.`meta_key` = 'osir_profile'";
	$sql .= " AND `wp_gf_entry_meta`.`entry_id` = outlookScore.`entry_id`";
	$sql .= " AND `wp_gf_entry_meta`.`form_id` = ".$my_gform_id;
	$sql .= " GROUP BY osirProfile";

	$results = $wpdb->get_results( $sql, ARRAY_A );

	$myresults[0] = isset($results[0]['totalOutlookScore']) ? $results[0]['totalOutlookScore'] : 0;
	$myresults[1] = isset($results[1]['totalOutlookScore']) ? $results[1]['totalOutlookScore'] : 0;
	$myresults[2] = isset($results[2]['totalOutlookScore']) ? $results[2]['totalOutlookScore'] : 0;
	$myresults[3] = isset($results[3]['totalOutlookScore']) ? $results[3]['totalOutlookScore'] : 0;

	echo "<br><br>".$sql."<br><br>";
	print_r($myresults);

	$outlookAverageCompanyScore = ( total_number_of_answers() > 0 ) ? 
	( $myresults[0] + $myresults[1] + $myresults[2] + $myresults[3] ) / total_number_of_answers() : 0;

	return $outlookAverageCompanyScore;
}
*/
