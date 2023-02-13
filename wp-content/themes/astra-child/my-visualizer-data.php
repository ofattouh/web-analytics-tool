<?php

//----------------------------------------------------------------------------------------
// Visualizer charts calculations & data

// https://docs.themeisle.com/article/1160-roles-for-series-visualizer#style
// https://developers.google.com/chart/interactive/docs/gallery/columnchart
// https://docs.themeisle.com/article/1196-visualizer-table-chart-documentation
// https://docs.themeisle.com/category/657-visualizer
// https://docs.themeisle.com/article/728-manual-configuration
// https://docs.themeisle.com/article/605-how-can-i-populate-chart-series-and-data-dynamically
// https://docs.themeisle.com/article/970-visualizer-sample-queries-to-generate-charts


//----------------------------------------------------------------------------------------
// 1. OSIR Profile chart series styles

define( 'CHALLENGE_STYLE', '"fill-color: #dd3333;"' );
define( 'CONCERN_STYLE', '"fill-color: #eeee22;"' );
define( 'THRIVING_STYLE', '"fill-color: #81d742;"' );

//----------------------------------------------------------------------------------------
// 1. Charts Columns(Series) Hook

add_filter( 'visualizer-get-chart-series', 'myplugin_filter_charts_series', 10, 3 );
function myplugin_filter_charts_series( $series, $chart_id, $type ) {

	// Vulnerability Profile By Vocation: What is your current vocation? - 
	if ( $chart_id === 1255 ){
		 return vulnerability_profile_vocation_chart_header();
	}

	// Vulnerability Profile By Department(University site): At which site do you primarily work?
	if ( $chart_id === 1335 ){
		 return vulnerability_profile_department_university_chart_header();
	}

	// Vulnerability Profile By Department(Victoria site): At which site do you primarily work?
	if ( $chart_id === 1338 ){
		 return vulnerability_profile_department_victoria_chart_header();
	}

	// Average Outcome Measures by Vulnerability Profile
	if ( $chart_id === 1375 ){
		 return vulnerability_profile_average_outcome_measures_table_header();
	}

	// (Attendance) Average Outcome Measures by Vulnerability Profile
	if ( $chart_id === 1427 ){
		 return vulnerability_profile_average_outcome_measures_attendance_chart_header();
	}

	// (Good Mental Health) Average Outcome Measures by Vulnerability Profile - 
	if ( $chart_id === 1432 ){
		 return vulnerability_profile_average_outcome_measures_mental_health_chart_header();
	}

	// (Good Physical Health) Average Outcome Measures by Vulnerability Profile - 
	if ( $chart_id === 1435 ){
		 return vulnerability_profile_average_outcome_measures_physical_health_chart_header();
	}

	// (How Motivated %) Average Outcome Measures by Vulnerability Profile - 
	if ( $chart_id === 1439 ){
		 return vulnerability_profile_average_outcome_measures_motivation_chart_header();
	}

	// (Presenteeism) Average Outcome Measures by Vulnerability Profile - 
	if ( $chart_id === 1451 ){
		 return vulnerability_profile_average_outcome_measures_presenteeism_chart_header();
	}

	// Demographics (Gender): What gender do you identify with? - 
	if ( $chart_id === 1260 ){
		 return demographics_gender_chart_header();
	}

	// Do you have any dependents or care responsibilities outside of the workplace? - 
	if ( $chart_id === 1289 ){
		 return demographics_dependents_chart_header();
	}

  return $series;
}

//----------------------------------------------------------------------------------------
// 2. Charts Data Hook

add_filter( 'visualizer-get-chart-data', 'myplugin_filter_charts_data', 10, 3 );
function myplugin_filter_charts_data( $data, $chart_id, $type ) {
  global $post;
  $my_gform_id = 0; // fall back

  // Get current page id
  if ( isset($post->ID) ) {
    // Added manually using advanced custom fields inside each visualizer word press page
    $my_gform_id = get_post_meta( $post->ID, 'my_gform_id', true );
    // echo "<br><br>page id: ".$post->ID.", my_gform_id: ".$my_gform_id;
  }

	// Average Sub-Scale Score - 
	if ( $chart_id === 1236 && $my_gform_id == 20 ){
			$custom_chart_data = array_merge(
				average_sub_scale_chart_data('Support Program', 'total_support_programs_score', $my_gform_id, 0),
				average_sub_scale_chart_data('Resiliency Behaviours', 'total_resiliency_behaviours_score', $my_gform_id, 1),
				average_sub_scale_chart_data('Supportive Leadership', 'total_supportive_leadership_score', $my_gform_id, 2),
				average_sub_scale_chart_data('Supportive Environment', 'total_supportive_environment_score', $my_gform_id, 3)
			);

			return $custom_chart_data;
	}

	// Vulnerability Profile By Vocation: What is your current vocation? - 
	if ( $chart_id === 1255 && $my_gform_id == 20 ){
		 return vulnerability_profile_vocation_chart_data($my_gform_id);
	}

	// Vulnerability Profile By Department(University site): At which site do you primarily work? - 
	if ( $chart_id === 1335 && $my_gform_id == 20 ){
		 return vulnerability_profile_department_university_chart_data($my_gform_id);
	}

	// Vulnerability Profile By Department(Victoria site): At which site do you primarily work? Victoria site - 
	if ( $chart_id === 1338 && $my_gform_id == 20 ){
		 return vulnerability_profile_department_victoria_chart_data($my_gform_id);
	}

	// Average Outcome Measures by Vulnerability Profile - 
	if ( $chart_id === 1375 && $my_gform_id == 20 ){
		 return vulnerability_profile_average_outcome_measures_table_data($my_gform_id);
	}

	// (Attendance) Average Outcome Measures by Vulnerability Profile - 
	if ( $chart_id === 1427 && $my_gform_id == 20 ){
		 return vulnerability_profile_average_outcome_measures_attendance_chart_data($my_gform_id);
	}

	// (Good Mental Health) Average Outcome Measures by Vulnerability Profile - 
	if ( $chart_id === 1432 && $my_gform_id == 20 ){
		 return vulnerability_profile_average_outcome_measures_mental_health_chart_data($my_gform_id);
	}

	// (Good Physical Health) Average Outcome Measures by Vulnerability Profile - 
	if ( $chart_id === 1435 && $my_gform_id == 20 ){
		 return vulnerability_profile_average_outcome_measures_physical_health_chart_data($my_gform_id);
	}

	// (How Motivated %) Average Outcome Measures by Vulnerability Profile - 
	if ( $chart_id === 1439 &&  $my_gform_id == 20 ){
		 return vulnerability_profile_average_outcome_measures_motivation_chart_data($my_gform_id);
	}

	// (Presenteeism) Average Outcome Measures by Vulnerability Profile - 
	if ( $chart_id === 1451 && $my_gform_id == 20 ){
		 return vulnerability_profile_average_outcome_measures_presenteeism_chart_data($my_gform_id);
	}

	// Demographics (Gender): What gender do you identify with? - 
	if ( $chart_id === 1260 && $my_gform_id == 20 ){
		 return demographics_gender_chart_data($my_gform_id);
	}

	// Do you have any dependents or care responsibilities outside of the workplace? - 
	if ( $chart_id === 1289 && $my_gform_id == 20 ){
		 return demographics_dependents_chart_data($my_gform_id);
	}

  return $data;
}

//
function average_sub_scale_chart_data($header_value, $meta_key, $my_gform_id, $counter) {
	global $wpdb;

	$sql  = "SELECT AVG(`wp_gf_entry_meta`.`meta_value`) AS 'avgScore' ";
	$sql .= "FROM `wp_gf_entry_meta` ";
	$sql .= "WHERE `wp_gf_entry_meta`.`meta_key` ='".$meta_key."'";
	$sql .= " AND `wp_gf_entry_meta`.`form_id` = ".$my_gform_id;

	$results = $wpdb->get_results( $sql, ARRAY_A );

	$chartData = [];

	// Build chart data
	foreach ($results as $k => $v){
		$avgScore = isset($v['avgScore'])? (double)$v['avgScore']: 0;
		$chartData[$counter][0] = $header_value;
		$chartData[$counter][1] = $avgScore;
	}

	// echo "<br><br>sql:<br>".$sql;
	// echo "<br><br>results:<br>";
	// print_r($results);
	// echo "<br><br>chartData:<br>";
	// print_r($chartData);
	
	return $chartData;
}

// 
function vulnerability_profile_vocation_chart_header(){
	$series = array(
		array(
			'label' => 'Vulnerability Profile By Vocation',
			'type' => 'string',
		),
		array(
			'label' => 'Challenge',
			'type' => 'number',
		),
		array(
      'label' => 'style',
      'type' => 'string',
    ),
		array(
			'label' => 'Concern',
			'type' => 'number',
		),
		array(
      'label' => 'style',
      'type' => 'string',
    ),
		array(
			'label' => 'Thriving',
			'type' => 'number',
		),
		array(
      'label' => 'style',
      'type' => 'string',
    ),
	);

	return $series;
}

function vulnerability_profile_vocation_chart_data($my_gform_id){
	global $wpdb;

	$sql = "SELECT `wp_gf_entry_meta`.`meta_value` AS 'osirProfile', COUNT(*) AS 'count', vulnerabilityVocation.`meta_value` AS 'vocation'";
	$sql .= " FROM `wp_gf_entry_meta`,";
	$sql .= " ( SELECT * FROM `wp_gf_entry_meta`";
	$sql .= " WHERE meta_key = 'vulnerability_vocation' ) vulnerabilityVocation";
	$sql .= " WHERE `wp_gf_entry_meta`.`meta_key` = 'osir_profile'";
	$sql .= " AND `wp_gf_entry_meta`.`entry_id` = vulnerabilityVocation.`entry_id`";
  $sql .= " AND `wp_gf_entry_meta`.`form_id` = ".$my_gform_id;
	$sql .= " GROUP BY osirProfile, vulnerabilityVocation.`meta_value`";
	$sql .= " ORDER BY osirProfile ASC";

	$results = $wpdb->get_results( $sql, ARRAY_A );

	foreach ($results as $k => $v){
		$dataSQL[$v['vocation']][$v['osirProfile']] = $v['count'];
	} 

	$counter = 0;
	$chartData = [];

	// Build chart data
  if (isset($dataSQL)){
    foreach ($dataSQL as $k => $v){
      $challenge = isset($v['Challenge'])? (int)$v['Challenge']: 0;
      $concern = isset($v['Concern'])? (int)$v['Concern']: 0;
      $thriving = isset($v['Thriving'])? (int)$v['Thriving']: 0;

      $chartData[$counter][0] = $k;
			$chartData[$counter][1] = $challenge;
			$chartData[$counter][2] = CHALLENGE_STYLE;
			$chartData[$counter][3] = $concern;
			$chartData[$counter][4] = CONCERN_STYLE;
			$chartData[$counter][5] = $thriving;
			$chartData[$counter][6] = THRIVING_STYLE;
      $counter++;
    }
  }

	// echo "<br><br>sql:<br>".$sql;
	// echo "<br><br>results:<br>";
	// print_r($results);
	// echo "<br><br>chartData:<br>";
	// print_r($chartData);
	
	return $chartData;
}

// 
function vulnerability_profile_department_university_chart_header(){
	$series = array(
		array(
			'label' => 'Vulnerability Profile By University Department',
			'type' => 'string',
		),
		array(
			'label' => 'Challenge',
			'type' => 'number',
		),
		array(
			'label' => 'style',
			'type' => 'string',
		),
		array(
			'label' => 'Concern',
			'type' => 'number',
		),
		array(
			'label' => 'style',
			'type' => 'string',
		),
		array(
			'label' => 'Thriving',
			'type' => 'number',
		),
		array(
			'label' => 'style',
			'type' => 'string',
		),
	);

	return $series;
}

function vulnerability_profile_department_university_chart_data($my_gform_id){
	global $wpdb;

	$sql = "SELECT `wp_gf_entry_meta`.`meta_value` AS 'osirProfile', COUNT(*) AS 'count', vulnerabilityUniversitySite.`meta_value` AS 'universitySite'";
	$sql .= " FROM `wp_gf_entry_meta`,";
	$sql .= " ( SELECT * FROM `wp_gf_entry_meta`";
	$sql .= " WHERE meta_key = 'vulnerability_university_site_department' ) vulnerabilityUniversitySite";
	$sql .= " WHERE `wp_gf_entry_meta`.`meta_key` = 'osir_profile'";
	$sql .= " AND vulnerabilityUniversitySite.`meta_value` <> ''";
	$sql .= " AND `wp_gf_entry_meta`.`entry_id` = vulnerabilityUniversitySite.`entry_id`";
  $sql .= " AND `wp_gf_entry_meta`.`form_id` = ".$my_gform_id;
	$sql .= " GROUP BY osirProfile, vulnerabilityUniversitySite.`meta_value`";
	$sql .= " ORDER BY osirProfile ASC";

	$results = $wpdb->get_results( $sql, ARRAY_A );

	foreach ($results as $k => $v){
		$dataSQL[$v['universitySite']][$v['osirProfile']] = $v['count'];
	} 

	$counter = 0;
	$chartData = [];

	// Build chart data
  if (isset($dataSQL)){
    foreach ($dataSQL as $k => $v){
      $challenge = isset($v['Challenge'])? (int)$v['Challenge']: 0;
      $concern = isset($v['Concern'])? (int)$v['Concern']: 0;
      $thriving = isset($v['Thriving'])? (int)$v['Thriving']: 0;

      $chartData[$counter][0] = $k;
			$chartData[$counter][1] = $challenge;
			$chartData[$counter][2] = CHALLENGE_STYLE;
			$chartData[$counter][3] = $concern;
			$chartData[$counter][4] = CONCERN_STYLE;
			$chartData[$counter][5] = $thriving;
			$chartData[$counter][6] = THRIVING_STYLE;
			$counter++;
    }
  }

	// echo "<br><br>sql:<br>".$sql;
	// echo "<br><br>results:<br>";
	// print_r($results);
	// echo "<br><br>chartData:<br>";
	// print_r($chartData);
	
	return $chartData;
}

// 
function vulnerability_profile_department_victoria_chart_header(){
	$series = array(
		array(
			'label' => 'Vulnerability Profile By Victoria Department',
			'type' => 'string',
		),
		array(
			'label' => 'Challenge',
			'type' => 'number',
		),
		array(
      'label' => 'style',
      'type' => 'string',
    ),
		array(
			'label' => 'Concern',
			'type' => 'number',
		),
		array(
      'label' => 'style',
      'type' => 'string',
    ),
		array(
			'label' => 'Thriving',
			'type' => 'number',
		),
		array(
      'label' => 'style',
      'type' => 'string',
    ),
	);

	return $series;
}

function vulnerability_profile_department_victoria_chart_data($my_gform_id){
	global $wpdb;

	$sql = "SELECT `wp_gf_entry_meta`.`meta_value` AS 'osirProfile', COUNT(*) AS 'count', vulnerabilityVictoriaSite.`meta_value` AS 'victoriaSite'";
	$sql .= " FROM `wp_gf_entry_meta`,";
	$sql .= " ( SELECT * FROM `wp_gf_entry_meta`";
	$sql .= " WHERE meta_key = 'vulnerability_victoria_site_department' ) vulnerabilityVictoriaSite";
	$sql .= " WHERE `wp_gf_entry_meta`.`meta_key` = 'osir_profile'";
	$sql .= " AND vulnerabilityVictoriaSite.`meta_value` <> ''";
	$sql .= " AND `wp_gf_entry_meta`.`entry_id` = vulnerabilityVictoriaSite.`entry_id`";
  $sql .= " AND `wp_gf_entry_meta`.`form_id` = ".$my_gform_id;
	$sql .= " GROUP BY osirProfile, vulnerabilityVictoriaSite.`meta_value`";
	$sql .= " ORDER BY osirProfile ASC";

	$results = $wpdb->get_results( $sql, ARRAY_A );

	foreach ($results as $k => $v){
		$dataSQL[$v['victoriaSite']][$v['osirProfile']] = $v['count'];
	} 

	$counter = 0;
	$chartData = [];

	// Build chart data
  if (isset($dataSQL)){
    foreach ($dataSQL as $k => $v){
      $challenge = isset($v['Challenge'])? (int)$v['Challenge']: 0;
      $concern = isset($v['Concern'])? (int)$v['Concern']: 0;
      $thriving = isset($v['Thriving'])? (int)$v['Thriving']: 0;

      $chartData[$counter][0] = $k;
			$chartData[$counter][1] = $challenge;
			$chartData[$counter][2] = CHALLENGE_STYLE;
			$chartData[$counter][3] = $concern;
			$chartData[$counter][4] = CONCERN_STYLE;
			$chartData[$counter][5] = $thriving;
			$chartData[$counter][6] = THRIVING_STYLE;
			$counter++;
    }
  }

	// echo "<br><br>sql:<br>".$sql;
	// echo "<br><br>results:<br>";
	// print_r($results);
	// echo "<br><br>chartData:<br>";
	// print_r($chartData);
	
	return $chartData;
}

//
function vulnerability_profile_average_outcome_measures_table_header(){
	$series = array(
		array(
			'label' => 'Measures',
			'type' => 'string',
		),
		array(
			'label' => 'Challenge <27',
			'type' => 'string',
		),
		array(
			'label' => 'Concern (27-81)',
			'type' => 'string',
		),
		array(
			'label' => 'Thriving >81',
			'type' => 'string',
		),
	);

	return $series;
}

function vulnerability_profile_average_outcome_measures_attendance_table($my_gform_id) {
	global $wpdb;

	$sql = "SELECT `wp_gf_entry_meta`.`meta_value` AS 'osirProfile', COUNT(*) AS 'count', impactAttendance.`meta_value` AS 'attendance'";
	$sql .= " FROM `wp_gf_entry_meta`,";
	$sql .= " ( SELECT * FROM `wp_gf_entry_meta`";
	$sql .= " WHERE meta_key = 'impact_questions_attendance' ) impactAttendance";
	$sql .= " WHERE `wp_gf_entry_meta`.`meta_key` = 'osir_profile'";
	$sql .= " AND `wp_gf_entry_meta`.`entry_id` = impactAttendance.`entry_id`";
  $sql .= " AND `wp_gf_entry_meta`.`form_id` = ".$my_gform_id;
	$sql .= " GROUP BY osirProfile, impactAttendance.`meta_value`";
	$sql .= " ORDER BY osirProfile ASC";

	$results = $wpdb->get_results( $sql, ARRAY_A );

	foreach ($results as $k => $v){
		$dataSQL[$k][$v['osirProfile']] = $v['count']. ' user(s) were absent for '.$v['attendance'] . ' day(s)';
	} 

	$chartData = [];

	// Build chart data
  if (isset($dataSQL)){
		$chartData[0][0] = 'Absent From Work';
		$chartData[0][1] = '';
		$chartData[0][2] = '';
		$chartData[0][3] = '';

    foreach ($dataSQL as $k => $v){
			// echo "<br>dataSQL: k=".$k."=> v="; print_r($v)."<br>";

      if (isset($v['Challenge'])) {
				if (isset($chartData[0][1])) {
					$chartData[0][1] = $chartData[0][1].'<br>'.$v['Challenge'];
				} else {
					$chartData[0][1] = $v['Challenge'];
				}	
			}
			
			if (isset($v['Concern'])) {
				if (isset($chartData[0][2])) {
					$chartData[0][2] = $chartData[0][2].'<br>'.$v['Concern'];
				} else {
					$chartData[0][2] = $v['Concern'];
				}	
			}

			if (isset($v['Thriving'])) {
				if (isset($chartData[0][3])) {
					$chartData[0][3] = $chartData[0][3].'<br>'.$v['Thriving'];
				} else {
					$chartData[0][3] = $v['Thriving'];
				}	
			}
  	}
	}

	// echo "<br><br>sql:<br>".$sql;
	// echo "<br><br>results:<br>";
	// print_r($results);
	// echo "<br><br>chartData:<br>";
	// print_r($chartData);
	
	return $chartData;
}

function vulnerability_profile_average_outcome_measures_mental_health_table($my_gform_id) {
	global $wpdb;

	$sql = "SELECT `wp_gf_entry_meta`.`meta_value` AS 'osirProfile', COUNT(*) AS 'count', mentalHealthScore.`meta_value` AS 'mentalHealthScore'";
	$sql .= " FROM `wp_gf_entry_meta`,";
	$sql .= " ( SELECT * FROM `wp_gf_entry_meta`";
	$sql .= " WHERE meta_key = 'mental_health_score' ) mentalHealthScore";
	$sql .= " WHERE `wp_gf_entry_meta`.`meta_key` = 'osir_profile'";
	$sql .= " AND `wp_gf_entry_meta`.`entry_id` = mentalHealthScore.`entry_id`";
  $sql .= " AND `wp_gf_entry_meta`.`form_id` = ".$my_gform_id;
	$sql .= " GROUP BY osirProfile, mentalHealthScore.`meta_value`";
	$sql .= " ORDER BY osirProfile ASC";

	$results = $wpdb->get_results( $sql, ARRAY_A );

	foreach ($results as $k => $v){
		if (isset($v['mentalHealthScore']) && $v['mentalHealthScore'] == 0) {
			$mentalHealthScore = 'strongly disagree';
		} 
		elseif (isset($v['mentalHealthScore']) && $v['mentalHealthScore'] == 1 ) {
			$mentalHealthScore = 'disagree';
		}
		elseif (isset($v['mentalHealthScore']) && $v['mentalHealthScore'] == 2 ) {
			$mentalHealthScore = 'are undecided';
		}
		elseif (isset($v['mentalHealthScore']) && $v['mentalHealthScore'] == 3 ) {
			$mentalHealthScore = 'agree';
		}
		elseif (isset($v['mentalHealthScore']) && $v['mentalHealthScore'] == 4 ) {
			$mentalHealthScore = 'strongly agree';
		}

		$dataSQL[$k][$v['osirProfile']] = $v['count']. ' user(s) '.$mentalHealthScore;
	}

	$chartData = [];

	// Build chart data
  if (isset($dataSQL)){
		$chartData[0][0] = 'Good Mental Health';
		$chartData[0][1] = '';
		$chartData[0][2] = '';
		$chartData[0][3] = '';

    foreach ($dataSQL as $k => $v){
			// echo "<br>dataSQL: k=".$k."=> v="; print_r($v)."<br>";

      if (isset($v['Challenge'])) {
				if (isset($chartData[0][1])) {
					$chartData[0][1] = $chartData[0][1].'<br>'.$v['Challenge'];
				} else {
					$chartData[0][1] = $v['Challenge'];
				}	
			}
			
			if (isset($v['Concern'])) {
				if (isset($chartData[0][2])) {
					$chartData[0][2] = $chartData[0][2].'<br>'.$v['Concern'];
				} else {
					$chartData[0][2] = $v['Concern'];
				}	
			}

			if (isset($v['Thriving'])) {
				if (isset($chartData[0][3])) {
					$chartData[0][3] = $chartData[0][3].'<br>'.$v['Thriving'];
				} else {
					$chartData[0][3] = $v['Thriving'];
				}	
			}
  	}
	}

	// echo "<br><br>sql:<br>".$sql;
	// echo "<br><br>results:<br>";
	// print_r($results);
	// echo "<br><br>chartData:<br>";
	// print_r($chartData);
	
	return $chartData;
}

function vulnerability_profile_average_outcome_measures_physical_health_table($my_gform_id) {
	global $wpdb;

	$sql = "SELECT `wp_gf_entry_meta`.`meta_value` AS 'osirProfile', COUNT(*) AS 'count', physicalHealthScore.`meta_value` AS 'physicalHealthScore'";
	$sql .= " FROM `wp_gf_entry_meta`,";
	$sql .= " ( SELECT * FROM `wp_gf_entry_meta`";
	$sql .= " WHERE meta_key = 'physical_health_score' ) physicalHealthScore";
	$sql .= " WHERE `wp_gf_entry_meta`.`meta_key` = 'osir_profile'";
	$sql .= " AND `wp_gf_entry_meta`.`entry_id` = physicalHealthScore.`entry_id`";
  $sql .= " AND `wp_gf_entry_meta`.`form_id` = ".$my_gform_id;
	$sql .= " GROUP BY osirProfile, physicalHealthScore.`meta_value`";
	$sql .= " ORDER BY osirProfile ASC";

	$results = $wpdb->get_results( $sql, ARRAY_A );

	foreach ($results as $k => $v){
		if (isset($v['physicalHealthScore']) && $v['physicalHealthScore'] == 0) {
			$physicalHealthScore = 'strongly disagree';
		} 
		elseif (isset($v['physicalHealthScore']) && $v['physicalHealthScore'] == 1 ) {
			$physicalHealthScore = 'disagree';
		}
		elseif (isset($v['physicalHealthScore']) && $v['physicalHealthScore'] == 2 ) {
			$physicalHealthScore = 'are undecided';
		}
		elseif (isset($v['physicalHealthScore']) && $v['physicalHealthScore'] == 3 ) {
			$physicalHealthScore = 'agree';
		}
		elseif (isset($v['physicalHealthScore']) && $v['physicalHealthScore'] == 4 ) {
			$physicalHealthScore = 'strongly agree';
		}

		$dataSQL[$k][$v['osirProfile']] = $v['count']. ' user(s) '.$physicalHealthScore;
	}

	$chartData = [];

	// Build chart data
  if (isset($dataSQL)){
		$chartData[0][0] = 'Good Physical Health';
		$chartData[0][1] = '';
		$chartData[0][2] = '';
		$chartData[0][3] = '';

    foreach ($dataSQL as $k => $v){
			// echo "<br>dataSQL: k=".$k."=> v="; print_r($v)."<br>";

      if (isset($v['Challenge'])) {
				if (isset($chartData[0][1])) {
					$chartData[0][1] = $chartData[0][1].'<br>'.$v['Challenge'];
				} else {
					$chartData[0][1] = $v['Challenge'];
				}	
			}
			
			if (isset($v['Concern'])) {
				if (isset($chartData[0][2])) {
					$chartData[0][2] = $chartData[0][2].'<br>'.$v['Concern'];
				} else {
					$chartData[0][2] = $v['Concern'];
				}	
			}

			if (isset($v['Thriving'])) {
				if (isset($chartData[0][3])) {
					$chartData[0][3] = $chartData[0][3].'<br>'.$v['Thriving'];
				} else {
					$chartData[0][3] = $v['Thriving'];
				}	
			}
  	}
	}

	// echo "<br><br>sql:<br>".$sql;
	// echo "<br><br>results:<br>";
	// print_r($results);
	// echo "<br><br>chartData:<br>";
	// print_r($chartData);
	
	return $chartData;
}

function vulnerability_profile_health_concern_sum_score_per_osir_profile($meta_key, $osir_profile, $my_gform_id) {
	global $wpdb;

	$sql  = "SELECT `wp_gf_entry_meta`.`meta_value` AS 'osirProfile', ";
	$sql .= "`osirProfileScore`.`meta_value` AS 'score', ";
	$sql .= " SUM(`osirProfileScore`.`meta_value`) AS 'sumScore'";
	$sql .= " FROM `wp_gf_entry_meta`,";
	$sql .= " (SELECT * FROM `wp_gf_entry_meta` WHERE `meta_key` = '".$meta_key."') osirProfileScore";
	$sql .= " WHERE `wp_gf_entry_meta`.`meta_key` = 'osir_profile'";
	$sql .= " AND `wp_gf_entry_meta`.`entry_id` = osirProfileScore.`entry_id`";
	$sql .= " AND `wp_gf_entry_meta`.`form_id` = ".$my_gform_id;
	$sql .= " AND `wp_gf_entry_meta`.`meta_value` = '".$osir_profile."'";
	
	$results = $wpdb->get_results( $sql, ARRAY_A );

	foreach ($results as $k => $v){
		$sumScore = isset($v['sumScore'])? (double)$v['sumScore']: 0;
	}

	// echo "<br><br>sql:<br>".$sql;
	// echo "<br><br>results:<br>";
	// print_r($results);
	
	return $sumScore;
}

function vulnerability_profile_average_outcome_measures_health_concern_table($my_gform_id) {
	$health_fatigue_sum_score_challenge = vulnerability_profile_health_concern_sum_score_per_osir_profile('health_fatigue_concerns_score', 'Challenge', $my_gform_id);
	$health_burnout_sum_score_challenge = vulnerability_profile_health_concern_sum_score_per_osir_profile('health_burnout_concerns_score', 'Challenge', $my_gform_id);
	$health_stress_sum_score_challenge = vulnerability_profile_health_concern_sum_score_per_osir_profile('health_stress_concerns_score', 'Challenge', $my_gform_id);
	
	$health_fatigue_sum_score_concern = vulnerability_profile_health_concern_sum_score_per_osir_profile('health_fatigue_concerns_score', 'Concern', $my_gform_id);
	$health_burnout_sum_score_concern = vulnerability_profile_health_concern_sum_score_per_osir_profile('health_burnout_concerns_score', 'Concern', $my_gform_id);
	$health_stress_sum_score_concern = vulnerability_profile_health_concern_sum_score_per_osir_profile('health_stress_concerns_score', 'Concern', $my_gform_id);
	
	$health_fatigue_sum_score_thriving = vulnerability_profile_health_concern_sum_score_per_osir_profile('health_fatigue_concerns_score', 'Thriving', $my_gform_id);
	$health_burnout_sum_score_thriving = vulnerability_profile_health_concern_sum_score_per_osir_profile('health_burnout_concerns_score', 'Thriving', $my_gform_id);
	$health_stress_sum_score_thriving = vulnerability_profile_health_concern_sum_score_per_osir_profile('health_stress_concerns_score', 'Thriving', $my_gform_id);

	$number_of_submissions_per_challenge = total_number_of_submissions_per_osir_profile('Challenge', $my_gform_id);
	$number_of_submissions_per_concern 	 = total_number_of_submissions_per_osir_profile('Concern', $my_gform_id);
	$number_of_submissions_per_thriving  = total_number_of_submissions_per_osir_profile('Thriving', $my_gform_id);

	if ($number_of_submissions_per_challenge > 0) {
		$health_concerns_challenge_avg_score = (($health_fatigue_sum_score_challenge + $health_burnout_sum_score_challenge + $health_stress_sum_score_challenge) / 3) * (1 / $number_of_submissions_per_challenge);
	} else {
		$health_concerns_challenge_avg_score = 0;
	}

	if ($number_of_submissions_per_concern > 0) {
		$health_concerns_concern_avg_score = (($health_fatigue_sum_score_concern + $health_burnout_sum_score_concern + $health_stress_sum_score_concern) / 3) * (1 / $number_of_submissions_per_concern);
	} else {
		$health_concerns_concern_avg_score = 0;
	}

	if ($number_of_submissions_per_thriving > 0) {
		$health_concerns_thriving_avg_score = (($health_fatigue_sum_score_thriving + $health_burnout_sum_score_thriving + $health_stress_sum_score_thriving) / 3) * (1 / $number_of_submissions_per_thriving);
	} else {
		$health_concerns_thriving_avg_score = 0;
	}

	// Build chart data
	$chartData[0][0] = 'Not Concerned for Health';
	$chartData[0][1] = number_format($health_concerns_challenge_avg_score, 2);
	$chartData[0][2] = number_format($health_concerns_concern_avg_score, 2);
	$chartData[0][3] = number_format($health_concerns_thriving_avg_score, 2);

	// echo "<br><br>chartData:<br>";
	// print_r($chartData);
	
	return $chartData;
}

function vulnerability_profile_average_outcome_measures_motivation_table($my_gform_id) {
	global $wpdb;

	$sql = "SELECT `wp_gf_entry_meta`.`meta_value` AS 'osirProfile', COUNT(*) AS 'count', impactMotivation.`meta_value` AS 'motivation'";
	$sql .= " FROM `wp_gf_entry_meta`,";
	$sql .= " ( SELECT * FROM `wp_gf_entry_meta`";
	$sql .= " WHERE meta_key = 'impact_questions_motivation_score' ) impactMotivation";
	$sql .= " WHERE `wp_gf_entry_meta`.`meta_key` = 'osir_profile'";
	$sql .= " AND `wp_gf_entry_meta`.`entry_id` = impactMotivation.`entry_id`";
  $sql .= " AND `wp_gf_entry_meta`.`form_id` = ".$my_gform_id;
	$sql .= " GROUP BY osirProfile, impactMotivation.`meta_value`";
	$sql .= " ORDER BY osirProfile ASC";

	$results = $wpdb->get_results( $sql, ARRAY_A );

	foreach ($results as $k => $v){
		$dataSQL[$k][$v['osirProfile']] = $v['count']. ' user(s) are '.$v['motivation'] . '0% motivated';
	} 

	$chartData = [];

	// Build chart data
  if (isset($dataSQL)){
		$chartData[0][0] = 'How Motivated (%)';
		$chartData[0][1] = '';
		$chartData[0][2] = '';
		$chartData[0][3] = '';

    foreach ($dataSQL as $k => $v){
			// echo "<br>dataSQL: k=".$k."=> v="; print_r($v)."<br>";

      if (isset($v['Challenge'])) {
				if (isset($chartData[0][1])) {
					$chartData[0][1] = $chartData[0][1].'<br>'.$v['Challenge'];
				} else {
					$chartData[0][1] = $v['Challenge'];
				}	
			}
			
			if (isset($v['Concern'])) {
				if (isset($chartData[0][2])) {
					$chartData[0][2] = $chartData[0][2].'<br>'.$v['Concern'];
				} else {
					$chartData[0][2] = $v['Concern'];
				}	
			}

			if (isset($v['Thriving'])) {
				if (isset($chartData[0][3])) {
					$chartData[0][3] = $chartData[0][3].'<br>'.$v['Thriving'];
				} else {
					$chartData[0][3] = $v['Thriving'];
				}	
			}
  	}
	}

	// echo "<br><br>sql:<br>".$sql;
	// echo "<br><br>results:<br>";
	// print_r($results);
	// echo "<br><br>chartData:<br>";
	// print_r($chartData);
	
	return $chartData;
}

function vulnerability_profile_average_outcome_measures_presenteeism_table($my_gform_id) {
	global $wpdb;

	$sql = "SELECT `wp_gf_entry_meta`.`meta_value` AS 'osirProfile', COUNT(*) AS 'count', impactPresenteeism.`meta_value` AS 'presenteeism'";
	$sql .= " FROM `wp_gf_entry_meta`,";
	$sql .= " ( SELECT * FROM `wp_gf_entry_meta`";
	$sql .= " WHERE meta_key = 'impact_questions_presenteeism' ) impactPresenteeism";
	$sql .= " WHERE `wp_gf_entry_meta`.`meta_key` = 'osir_profile'";
	$sql .= " AND `wp_gf_entry_meta`.`entry_id` = impactPresenteeism.`entry_id`";
  $sql .= " AND `wp_gf_entry_meta`.`form_id` = ".$my_gform_id;
	$sql .= " GROUP BY osirProfile, impactPresenteeism.`meta_value`";
	$sql .= " ORDER BY osirProfile ASC";

	$results = $wpdb->get_results( $sql, ARRAY_A );

	foreach ($results as $k => $v){
		$dataSQL[$k][$v['osirProfile']] = $v['count']. ' user(s) went to work unwell for '.$v['presenteeism'] . ' day(s)';
	} 

	$chartData = [];

	// Build chart data
  if (isset($dataSQL)){
		$chartData[0][0] = 'Went to Work Unwell';
		$chartData[0][1] = '';
		$chartData[0][2] = '';
		$chartData[0][3] = '';

    foreach ($dataSQL as $k => $v){
			// echo "<br>dataSQL: k=".$k."=> v="; print_r($v)."<br>";

      if (isset($v['Challenge'])) {
				if (isset($chartData[0][1])) {
					$chartData[0][1] = $chartData[0][1].'<br>'.$v['Challenge'];
				} else {
					$chartData[0][1] = $v['Challenge'];
				}	
			}
			
			if (isset($v['Concern'])) {
				if (isset($chartData[0][2])) {
					$chartData[0][2] = $chartData[0][2].'<br>'.$v['Concern'];
				} else {
					$chartData[0][2] = $v['Concern'];
				}	
			}

			if (isset($v['Thriving'])) {
				if (isset($chartData[0][3])) {
					$chartData[0][3] = $chartData[0][3].'<br>'.$v['Thriving'];
				} else {
					$chartData[0][3] = $v['Thriving'];
				}	
			}
  	}
	}

	// echo "<br><br>sql:<br>".$sql;
	// echo "<br><br>results:<br>";
	// print_r($results);
	// echo "<br><br>chartData:<br>";
	// print_r($chartData);
	
	return $chartData;
}

function vulnerability_profile_average_outcome_measures_table_data($my_gform_id){
	$custom_chart_data = array_merge(
		vulnerability_profile_average_outcome_measures_attendance_table($my_gform_id),      // Q1 of Impact Questions
		vulnerability_profile_average_outcome_measures_mental_health_table($my_gform_id),   // Q1 of Health Questions
		vulnerability_profile_average_outcome_measures_physical_health_table($my_gform_id), // Q2 of Health Questions
		vulnerability_profile_average_outcome_measures_health_concern_table($my_gform_id),  // Average of Q3,4,5 of Health Questions
		vulnerability_profile_average_outcome_measures_motivation_table($my_gform_id),      // Q3 of Impact Questions
		vulnerability_profile_average_outcome_measures_presenteeism_table($my_gform_id)     // Q2 of Impact Questions
	);

	// echo "<br><br>custom_chart_data<br>";
	// print_r($custom_chart_data);
	return $custom_chart_data;
}

function vulnerability_profile_average_outcome_measures_attendance_chart_header(){
	$series = array(
		array(
			'label' => 'Attendance',
			'type' => 'number',
		),
		array(
			'label' => 'Challenge',
			'type' => 'number',
		),
		array(
      'label' => 'style',
      'type' => 'string',
    ),
		array(
			'label' => 'Concern',
			'type' => 'number',
		),
		array(
      'label' => 'style',
      'type' => 'string',
    ),
		array(
			'label' => 'Thriving',
			'type' => 'number',
		),
		array(
      'label' => 'style',
      'type' => 'string',
    ),
	);

	return $series;
}

function vulnerability_profile_average_outcome_measures_attendance_chart_data($my_gform_id) {
	global $wpdb;

	$sql = "SELECT `wp_gf_entry_meta`.`meta_value` AS 'osirProfile', COUNT(*) AS 'count', impactAttendance.`meta_value` AS 'attendance'";
	$sql .= " FROM `wp_gf_entry_meta`,";
	$sql .= " ( SELECT * FROM `wp_gf_entry_meta`";
	$sql .= " WHERE meta_key = 'impact_questions_attendance' ) impactAttendance";
	$sql .= " WHERE `wp_gf_entry_meta`.`meta_key` = 'osir_profile'";
	$sql .= " AND `wp_gf_entry_meta`.`entry_id` = impactAttendance.`entry_id`";
  $sql .= " AND `wp_gf_entry_meta`.`form_id` = ".$my_gform_id;
	$sql .= " GROUP BY osirProfile, impactAttendance.`meta_value`";
	$sql .= " ORDER BY osirProfile ASC";

	$results = $wpdb->get_results( $sql, ARRAY_A );

	foreach ($results as $k => $v){
		$dataSQL[$v['attendance']][$v['osirProfile']] = $v['count'];
	} 

	$counter = 0;
	$chartData = [];

	// Build chart data
  if (isset($dataSQL)){
    foreach ($dataSQL as $k => $v){
      $challenge = isset($v['Challenge'])? (int)$v['Challenge']: 0;
      $concern = isset($v['Concern'])? (int)$v['Concern']: 0;
      $thriving = isset($v['Thriving'])? (int)$v['Thriving']: 0;

      $chartData[$counter][0] = $k;
      $chartData[$counter][1] = $challenge;
      $chartData[$counter][2] = CHALLENGE_STYLE;
      $chartData[$counter][3] = $concern;
      $chartData[$counter][4] = CONCERN_STYLE;
      $chartData[$counter][5] = $thriving;
      $chartData[$counter][6] = THRIVING_STYLE;
      $counter++;
    }
  }

	// echo "<br><br>sql:<br>".$sql;
	// echo "<br><br>results:<br>";
	// print_r($results);
	// echo "<br><br>chartData:<br>";
	// print_r($chartData);
	
	return $chartData;
}

function vulnerability_profile_average_outcome_measures_mental_health_chart_header(){
	$series = array(
		array(
			'label' => 'Good Mental Health',
			'type' => 'string',
		),
		array(
			'label' => 'Challenge',
			'type' => 'number',
		),
		array(
      'label' => 'style',
      'type' => 'string',
    ),
		array(
			'label' => 'Concern',
			'type' => 'number',
		),
		array(
      'label' => 'style',
      'type' => 'string',
    ),
		array(
			'label' => 'Thriving',
			'type' => 'number',
		),
		array(
      'label' => 'style',
      'type' => 'string',
    ),
	);

	return $series;
}

function vulnerability_profile_average_outcome_measures_mental_health_chart_data($my_gform_id) {
	global $wpdb;

	$sql = "SELECT `wp_gf_entry_meta`.`meta_value` AS 'osirProfile', COUNT(*) AS 'count', mentalHealthScore.`meta_value` AS 'mentalHealthScore'";
	$sql .= " FROM `wp_gf_entry_meta`,";
	$sql .= " ( SELECT * FROM `wp_gf_entry_meta`";
	$sql .= " WHERE meta_key = 'mental_health_score' ) mentalHealthScore";
	$sql .= " WHERE `wp_gf_entry_meta`.`meta_key` = 'osir_profile'";
	$sql .= " AND `wp_gf_entry_meta`.`entry_id` = mentalHealthScore.`entry_id`";
  $sql .= " AND `wp_gf_entry_meta`.`form_id` = ".$my_gform_id;
	$sql .= " GROUP BY osirProfile, mentalHealthScore.`meta_value`";
	$sql .= " ORDER BY osirProfile ASC";

	$results = $wpdb->get_results( $sql, ARRAY_A );

	foreach ($results as $k => $v){
		if (isset($v['mentalHealthScore']) && $v['mentalHealthScore'] == 0) {
			$mentalHealthScore = 'Strongly Disagree';
		} 
		elseif (isset($v['mentalHealthScore']) && $v['mentalHealthScore'] == 1 ) {
			$mentalHealthScore = 'Disagree';
		}
		elseif (isset($v['mentalHealthScore']) && $v['mentalHealthScore'] == 2 ) {
			$mentalHealthScore = 'Undecided';
		}
		elseif (isset($v['mentalHealthScore']) && $v['mentalHealthScore'] == 3 ) {
			$mentalHealthScore = 'Agree';
		}
		elseif (isset($v['mentalHealthScore']) && $v['mentalHealthScore'] == 4 ) {
			$mentalHealthScore = 'Strongly Agree';
		}

		$dataSQL[$mentalHealthScore][$v['osirProfile']] = $v['count'];
	} 

	$counter = 0;
	$chartData = [];

	// Build chart data
  if (isset($dataSQL)){
    foreach ($dataSQL as $k => $v){
      $challenge = isset($v['Challenge'])? (int)$v['Challenge']: 0;
      $concern = isset($v['Concern'])? (int)$v['Concern']: 0;
      $thriving = isset($v['Thriving'])? (int)$v['Thriving']: 0;

      $chartData[$counter][0] = $k;
      $chartData[$counter][1] = $challenge;
      $chartData[$counter][2] = CHALLENGE_STYLE;
      $chartData[$counter][3] = $concern;
      $chartData[$counter][4] = CONCERN_STYLE;
      $chartData[$counter][5] = $thriving;
      $chartData[$counter][6] = THRIVING_STYLE;
      $counter++;
    }
  }

	// echo "<br><br>sql:<br>".$sql;
	// echo "<br><br>results:<br>";
	// print_r($results);
	// echo "<br><br>chartData:<br>";
	// print_r($chartData);
	
	return $chartData;
}

function vulnerability_profile_average_outcome_measures_physical_health_chart_header(){
	$series = array(
		array(
			'label' => 'Good Physical Health',
			'type' => 'string',
		),
		array(
			'label' => 'Challenge',
			'type' => 'number',
		),
		array(
      'label' => 'style',
      'type' => 'string',
    ),
		array(
			'label' => 'Concern',
			'type' => 'number',
		),
		array(
      'label' => 'style',
      'type' => 'string',
    ),
		array(
			'label' => 'Thriving',
			'type' => 'number',
		),
		array(
      'label' => 'style',
      'type' => 'string',
    ),
	);

	return $series;
}

function vulnerability_profile_average_outcome_measures_physical_health_chart_data($my_gform_id) {
	global $wpdb;

	$sql = "SELECT `wp_gf_entry_meta`.`meta_value` AS 'osirProfile', COUNT(*) AS 'count', physicalHealthScore.`meta_value` AS 'physicalHealthScore'";
	$sql .= " FROM `wp_gf_entry_meta`,";
	$sql .= " ( SELECT * FROM `wp_gf_entry_meta`";
	$sql .= " WHERE meta_key = 'physical_health_score' ) physicalHealthScore";
	$sql .= " WHERE `wp_gf_entry_meta`.`meta_key` = 'osir_profile'";
	$sql .= " AND `wp_gf_entry_meta`.`entry_id` = physicalHealthScore.`entry_id`";
  $sql .= " AND `wp_gf_entry_meta`.`form_id` = ".$my_gform_id;
	$sql .= " GROUP BY osirProfile, physicalHealthScore.`meta_value`";
	$sql .= " ORDER BY osirProfile ASC";

	$results = $wpdb->get_results( $sql, ARRAY_A );

	foreach ($results as $k => $v){
		if (isset($v['physicalHealthScore']) && $v['physicalHealthScore'] == 0) {
			$physicalHealthScore = 'Strongly Disagree';
		} 
		elseif (isset($v['physicalHealthScore']) && $v['physicalHealthScore'] == 1 ) {
			$physicalHealthScore = 'Disagree';
		}
		elseif (isset($v['physicalHealthScore']) && $v['physicalHealthScore'] == 2 ) {
			$physicalHealthScore = 'Undecided';
		}
		elseif (isset($v['physicalHealthScore']) && $v['physicalHealthScore'] == 3 ) {
			$physicalHealthScore = 'Agree';
		}
		elseif (isset($v['physicalHealthScore']) && $v['physicalHealthScore'] == 4 ) {
			$physicalHealthScore = 'Strongly Agree';
		}

		$dataSQL[$physicalHealthScore][$v['osirProfile']] = $v['count'];
	} 

	$counter = 0;
	$chartData = [];

	// Build chart data
  if (isset($dataSQL)){
    foreach ($dataSQL as $k => $v){
      $challenge = isset($v['Challenge'])? (int)$v['Challenge']: 0;
      $concern = isset($v['Concern'])? (int)$v['Concern']: 0;
      $thriving = isset($v['Thriving'])? (int)$v['Thriving']: 0;

      $chartData[$counter][0] = $k;
      $chartData[$counter][1] = $challenge;
      $chartData[$counter][2] = CHALLENGE_STYLE;
      $chartData[$counter][3] = $concern;
      $chartData[$counter][4] = CONCERN_STYLE;
      $chartData[$counter][5] = $thriving;
      $chartData[$counter][6] = THRIVING_STYLE;
      $counter++;
    }
  }

	// echo "<br><br>sql:<br>".$sql;
	// echo "<br><br>results:<br>";
	// print_r($results);
	// echo "<br><br>chartData:<br>";
	// print_r($chartData);
	
	return $chartData;
}

function vulnerability_profile_average_outcome_measures_motivation_chart_header(){
	$series = array(
		array(
			'label' => 'How Motivated (%)',
			'type' => 'number',
		),
		array(
			'label' => 'Challenge',
			'type' => 'number',
		),
		array(
      'label' => 'style',
      'type' => 'string',
    ),
		array(
			'label' => 'Concern',
			'type' => 'number',
		),
		array(
      'label' => 'style',
      'type' => 'string',
    ),
		array(
			'label' => 'Thriving',
			'type' => 'number',
		),
		array(
      'label' => 'style',
      'type' => 'string',
    ),
	);

	return $series;
}

function vulnerability_profile_average_outcome_measures_motivation_chart_data($my_gform_id) {
	global $wpdb;

	$sql = "SELECT `wp_gf_entry_meta`.`meta_value` AS 'osirProfile', COUNT(*) AS 'count', impactMotivation.`meta_value` AS 'motivation'";
	$sql .= " FROM `wp_gf_entry_meta`,";
	$sql .= " ( SELECT * FROM `wp_gf_entry_meta`";
	$sql .= " WHERE meta_key = 'impact_questions_motivation_score' ) impactMotivation";
	$sql .= " WHERE `wp_gf_entry_meta`.`meta_key` = 'osir_profile'";
	$sql .= " AND `wp_gf_entry_meta`.`entry_id` = impactMotivation.`entry_id`";
  $sql .= " AND `wp_gf_entry_meta`.`form_id` = ".$my_gform_id;
	$sql .= " GROUP BY osirProfile, impactMotivation.`meta_value`";
	$sql .= " ORDER BY osirProfile ASC";

	$results = $wpdb->get_results( $sql, ARRAY_A );

	foreach ($results as $k => $v){
		$motivation = (int)$v['motivation'] * 10;
		$dataSQL[$motivation][$v['osirProfile']] = $v['count'];
	} 

	$counter = 0;
	$chartData = [];

	// Build chart data
  if (isset($dataSQL)){
    foreach ($dataSQL as $k => $v){
      $challenge = isset($v['Challenge'])? (int)$v['Challenge']: 0;
      $concern = isset($v['Concern'])? (int)$v['Concern']: 0;
      $thriving = isset($v['Thriving'])? (int)$v['Thriving']: 0;

			$chartData[$counter][0] = $k;
      $chartData[$counter][1] = $challenge;
      $chartData[$counter][2] = CHALLENGE_STYLE;
      $chartData[$counter][3] = $concern;
      $chartData[$counter][4] = CONCERN_STYLE;
      $chartData[$counter][5] = $thriving;
      $chartData[$counter][6] = THRIVING_STYLE;
      $counter++;
      
    }
  }

	// echo "<br><br>sql:<br>".$sql;
	// echo "<br><br>results:<br>";
	// print_r($results);
	// echo "<br><br>chartData:<br>";
	// print_r($chartData);
	
	return $chartData;
}

function vulnerability_profile_average_outcome_measures_presenteeism_chart_header(){
	$series = array(
		array(
			'label' => 'Went to Work Unwell',
			'type' => 'number',
		),
		array(
			'label' => 'Challenge',
			'type' => 'number',
		),
		array(
      'label' => 'style',
      'type' => 'string',
    ),
		array(
			'label' => 'Concern',
			'type' => 'number',
		),
		array(
      'label' => 'style',
      'type' => 'string',
    ),
		array(
			'label' => 'Thriving',
			'type' => 'number',
		),
		array(
      'label' => 'style',
      'type' => 'string',
    ),
	);

	return $series;
}

function vulnerability_profile_average_outcome_measures_presenteeism_chart_data($my_gform_id) {
	global $wpdb;

	$sql = "SELECT `wp_gf_entry_meta`.`meta_value` AS 'osirProfile', COUNT(*) AS 'count', impactPresenteeism.`meta_value` AS 'presenteeism'";
	$sql .= " FROM `wp_gf_entry_meta`,";
	$sql .= " ( SELECT * FROM `wp_gf_entry_meta`";
	$sql .= " WHERE meta_key = 'impact_questions_presenteeism' ) impactPresenteeism";
	$sql .= " WHERE `wp_gf_entry_meta`.`meta_key` = 'osir_profile'";
	$sql .= " AND `wp_gf_entry_meta`.`entry_id` = impactPresenteeism.`entry_id`";
  $sql .= " AND `wp_gf_entry_meta`.`form_id` = ".$my_gform_id;
	$sql .= " GROUP BY osirProfile, impactPresenteeism.`meta_value`";
	$sql .= " ORDER BY osirProfile ASC";

	$results = $wpdb->get_results( $sql, ARRAY_A );

	foreach ($results as $k => $v){
		$dataSQL[$v['presenteeism']][$v['osirProfile']] = $v['count'];
	} 

	$counter = 0;
	$chartData = [];

	// Build chart data
  if (isset($dataSQL)){
    foreach ($dataSQL as $k => $v){
      $challenge = isset($v['Challenge'])? (int)$v['Challenge']: 0;
      $concern = isset($v['Concern'])? (int)$v['Concern']: 0;
      $thriving = isset($v['Thriving'])? (int)$v['Thriving']: 0;

      $chartData[$counter][0] = $k;
      $chartData[$counter][1] = $challenge;
      $chartData[$counter][2] = CHALLENGE_STYLE;
      $chartData[$counter][3] = $concern;
      $chartData[$counter][4] = CONCERN_STYLE;
      $chartData[$counter][5] = $thriving;
      $chartData[$counter][6] = THRIVING_STYLE;
      $counter++;
    }
  }

	// echo "<br><br>sql:<br>".$sql;
	// echo "<br><br>results:<br>";
	// print_r($results);
	// echo "<br><br>chartData:<br>";
	// print_r($chartData);
	
	return $chartData;
}

//
function demographics_gender_chart_header(){
	$series = array(
		array(
			'label' => 'Gender',
			'type' => 'string',
		),
		array(
			'label' => 'Challenge',
			'type' => 'number',
		),
		array(
      'label' => 'style',
      'type' => 'string',
    ),
		array(
			'label' => 'Concern',
			'type' => 'number',
		),
		array(
      'label' => 'style',
      'type' => 'string',
    ),
		array(
			'label' => 'Thriving',
			'type' => 'number',
		),
		array(
      'label' => 'style',
      'type' => 'string',
    ),
	);

	return $series;
}

function demographics_gender_chart_data($my_gform_id){
	global $wpdb;

	$sql = "SELECT `wp_gf_entry_meta`.`meta_value` AS 'osirProfile', COUNT(*) AS 'count', demographicsGender.`meta_value` AS 'gender'";
	$sql .= " FROM `wp_gf_entry_meta`,";
	$sql .= " ( SELECT * FROM `wp_gf_entry_meta`";
	$sql .= " WHERE meta_key = 'demographics_gender' ) demographicsGender";
	$sql .= " WHERE `wp_gf_entry_meta`.`meta_key` = 'osir_profile'";
	$sql .= " AND `wp_gf_entry_meta`.`entry_id` = demographicsGender.`entry_id`";
  $sql .= " AND `wp_gf_entry_meta`.`form_id` = ".$my_gform_id;
	$sql .= " GROUP BY osirProfile, demographicsGender.`meta_value`";
	$sql .= " ORDER BY osirProfile ASC";

	$results = $wpdb->get_results( $sql, ARRAY_A );

	foreach ($results as $k => $v){
		$dataSQL[$v['gender']][$v['osirProfile']] = $v['count'];
	} 

	$counter = 0;
	$chartData = [];

	// Build chart data
  if (isset($dataSQL)){
    foreach ($dataSQL as $k => $v){
      $challenge = isset($v['Challenge'])? (int)$v['Challenge']: 0;
      $concern = isset($v['Concern'])? (int)$v['Concern']: 0;
      $thriving = isset($v['Thriving'])? (int)$v['Thriving']: 0;

      $chartData[$counter][0] = $k;
      $chartData[$counter][1] = $challenge;
      $chartData[$counter][2] = CHALLENGE_STYLE;
      $chartData[$counter][3] = $concern;
      $chartData[$counter][4] = CONCERN_STYLE;
      $chartData[$counter][5] = $thriving;
      $chartData[$counter][6] = THRIVING_STYLE;
      $counter++;
    }
  }

	// echo "<br><br>sql:<br>".$sql;
	// echo "<br><br>results:<br>";
	// print_r($results);
	// echo "<br><br>chartData:<br>";
	// print_r($chartData);
	
	return $chartData;
}

//
function demographics_dependents_chart_header(){
	$series = array(
		array(
			'label' => 'Dependents or Care Responsibilities',
			'type' => 'string',
		),
		array(
			'label' => 'Challenge',
			'type' => 'number',
		),
		array(
      'label' => 'style',
      'type' => 'string',
    ),
		array(
			'label' => 'Concern',
			'type' => 'number',
		),
		array(
      'label' => 'style',
      'type' => 'string',
    ),
		array(
			'label' => 'Thriving',
			'type' => 'number',
		),
		array(
      'label' => 'style',
      'type' => 'string',
    ),
	);

	return $series;
}

function demographics_dependents_chart_data($my_gform_id){
	global $wpdb;

	$sql = "SELECT `wp_gf_entry_meta`.`meta_value` AS 'osirProfile', COUNT(*) AS 'count', demographicsDependents.`meta_value` AS 'dependents'";
	$sql .= " FROM `wp_gf_entry_meta`,";
	$sql .= " ( SELECT * FROM `wp_gf_entry_meta`";
	$sql .= " WHERE meta_key = 'demographics_dependents' ) demographicsDependents";
	$sql .= " WHERE `wp_gf_entry_meta`.`meta_key` = 'osir_profile'";
	$sql .= " AND `wp_gf_entry_meta`.`entry_id` = demographicsDependents.`entry_id`";
  $sql .= " AND `wp_gf_entry_meta`.`form_id` = ".$my_gform_id;
	$sql .= " GROUP BY osirProfile, demographicsDependents.`meta_value`";
	$sql .= " ORDER BY osirProfile ASC";

	$results = $wpdb->get_results( $sql, ARRAY_A );

	foreach ($results as $k => $v){
		$dataSQL[$v['dependents']][$v['osirProfile']] = $v['count'];
	} 

	$counter = 0;
	$chartData = [];

	// Build chart data
  if (isset($dataSQL)){
    foreach ($dataSQL as $k => $v){
      $challenge = isset($v['Challenge'])? (int)$v['Challenge']: 0;
      $concern = isset($v['Concern'])? (int)$v['Concern']: 0;
      $thriving = isset($v['Thriving'])? (int)$v['Thriving']: 0;

      $chartData[$counter][0] = $k;
      $chartData[$counter][1] = $challenge;
      $chartData[$counter][2] = CHALLENGE_STYLE;
      $chartData[$counter][3] = $concern;
      $chartData[$counter][4] = CONCERN_STYLE;
      $chartData[$counter][5] = $thriving;
      $chartData[$counter][6] = THRIVING_STYLE;
      $counter++;
    }
  }

	// echo "<br><br>sql:<br>".$sql;
	// echo "<br><br>results:<br>";
	// print_r($results);
	// echo "<br><br>chartData:<br>";
	// print_r($chartData);
	
	return $chartData;
}

// -------------------------------------------------------------------------------------------

/*


/* $sql  = "SELECT SUM(`wp_gf_entry_meta`.`meta_value`) AS 'sumScore' ";
$sql .= "FROM `wp_gf_entry_meta` ";
$sql .= "WHERE `wp_gf_entry_meta`.`meta_key` ='".$meta_key."'";
$sql .= " AND `wp_gf_entry_meta`.`form_id` = ".$my_gform_id; */

function vulnerability_profile_average_outcome_measures_chart_osir_scales() {
	$chartData[0][0] = 'OSIR Score';
	$chartData[0][1] = '<27';
	$chartData[0][2] = '27-81';
	$chartData[0][3] = '>81';
	return $chartData;
}

function OSIRPieChartHeader() {
  $series = array(
		array(
			'label' => 'OSIR Index Score',
			'type' => 'string',
		),
		array(
			'label' => 'Count',
			'type' => 'number',
		),
		array(
			'label' => 'style',
			'type' => 'string',
		),
	);

	return $series;
}

function OSIRPieChartData($my_gform_id) {
  global $wpdb;

	$sql  = "SELECT `meta_value` AS osirProfile, COUNT(*) AS Count";
  $sql  .= " FROM `wp_gf_entry_meta`";
  $sql  .= " WHERE `meta_key` = 'osir_profile'";
  $sql  .= " AND `form_id` = ".$my_gform_id;
  $sql  .= " GROUP BY `meta_value`";

	$results = $wpdb->get_results( $sql, ARRAY_A );

	$counter = 0;
	$chartData = [];
	$seriesColors = array( "fill-color: #dd3333", "fill-color: #ff9205", 
		"fill-color: #eeee22", "fill-color: #81d742" );

	// Build chart data
	foreach ($results as $k => $v){
		$osirProfile = isset($v['osirProfile'])? $v['osirProfile']: '';
		$osirProfileCount = isset($v['Count'])? (double)$v['Count']: 0;
		$chartData[$counter][0] = $osirProfile;
		$chartData[$counter][1] = $osirProfileCount;
		$chartData[$counter][2] = $seriesColors[$counter];
		$counter++;
	}

	echo "<br><br>sql:<br>".$sql;
	echo "<br><br>results:<br>";
	print_r($results);
	echo "<br><br>chartData:<br>";
	print_r($chartData);
	
	return $chartData;
}

function OSIRByYearsOfServiceChartHeader() {
	$series = array(
		array(
			'label' => 'Years Of Service',
			'type' => 'string',
		),
		array(
			'label' => 'Average OSIR Score',
			'type' => 'number',
		),
		array(
			'label' => 'annotation',
			'type' => 'number',
		),
	);

	return $series;
}

function outlookMentalScoreCopingChartHeader(){
	$series = array(
		array(
			'label' => 'OSIR Profile',
			'type' => 'string',
		),
		array(
			'label' => 'Outlook Mental Average Score',
			'type' => 'number',
		),
		array(
			'label' => 'style',
			'type' => 'string',
		),
		array(
			'label' => 'annotation',
			'type' => 'number',
		),
	);

	return $series;
}

function outlookMentalScoreCopingAlcoholChartData($my_gform_id = 0) {
	global $wpdb;

	$sql  = "SELECT `wp_gf_entry_meta`.`meta_value` AS 'osirProfile',";
	$sql .= " AVG(healthAlcoholStress.`meta_value`) AS 'outlookAverageScore'";
	$sql .= " FROM `wp_gf_entry_meta`,";
	$sql .= " ( SELECT * FROM `wp_gf_entry_meta` WHERE meta_key = 'health_alcohol_stress_score' ) healthAlcoholStress";
	$sql .= " WHERE `wp_gf_entry_meta`.`meta_key` = 'osir_profile'";
	$sql .= " AND `wp_gf_entry_meta`.`entry_id` = healthAlcoholStress.`entry_id`";
	$sql .= " AND `wp_gf_entry_meta`.`form_id` = ".$my_gform_id;

	$results = $wpdb->get_results( $sql, ARRAY_A );

	$counter = 0;
	$chartData = [];
	$seriesColors = array( "fill-color: #dd3333", "fill-color: #ff9205", 
		"fill-color: #eeee22", "fill-color: #81d742" );

	// Build chart data
	foreach ($results as $k => $v){
		$osirProfile = isset($v['osirProfile'])? $v['osirProfile']: 0;
		$outlookAverageScore = isset($v['outlookAverageScore'])? (double)$v['outlookAverageScore']: 0;
		$chartData[$counter][0] = $osirProfile;
		$chartData[$counter][1] = $outlookAverageScore;
		$chartData[$counter][2] = $seriesColors[$counter];
		$chartData[$counter][3] = $outlookAverageScore;
		$counter++;
	}

	echo "<br><br>sql:<br>".$sql;
	echo "<br><br>results:<br>";
	print_r($results);
	echo "<br><br>seriesColors:<br>";
	print_r($seriesColors);
	echo "<br><br>chartData:<br>";
	print_r($chartData);
	
	return $chartData;
}

function outlookMentalScoreCopingCannabisChartData($my_gform_id = 0) {
	global $wpdb;

	$sql  = "SELECT `wp_gf_entry_meta`.`meta_value` AS 'osirProfile',";
	$sql .= " AVG(healthCannabisStress.`meta_value`) AS 'outlookAverageScore'";
	$sql .= " FROM `wp_gf_entry_meta`,";
	$sql .= " ( SELECT * FROM `wp_gf_entry_meta` WHERE meta_key = 'health_cannabis_stress_score' ) healthCannabisStress";
	$sql .= " WHERE `wp_gf_entry_meta`.`meta_key` = 'osir_profile'";
	$sql .= " AND `wp_gf_entry_meta`.`entry_id` = healthCannabisStress.`entry_id`";
	$sql .= " AND `wp_gf_entry_meta`.`form_id` = ".$my_gform_id;

	$results = $wpdb->get_results( $sql, ARRAY_A );

	$counter = 0;
	$chartData = [];
	$seriesColors = array( "fill-color: #dd3333", "fill-color: #ff9205", 
		"fill-color: #eeee22", "fill-color: #81d742" );

	// Build chart data
	foreach ($results as $k => $v){
		$osirProfile = isset($v['osirProfile'])? $v['osirProfile']: 0;
		$outlookAverageScore = isset($v['outlookAverageScore'])? (double)$v['outlookAverageScore']: 0;
		$chartData[$counter][0] = $osirProfile;
		$chartData[$counter][1] = $outlookAverageScore;
		$chartData[$counter][2] = $seriesColors[$counter];
		$chartData[$counter][3] = $outlookAverageScore;
		$counter++;
	}
	
	return $chartData;
}

function outlookMentalScoreCopingTobaccoChartData($my_gform_id = 0) {
	global $wpdb;

	$sql  = "SELECT `wp_gf_entry_meta`.`meta_value` AS 'osirProfile',";
	$sql .= " AVG(healthTobaccoStress.`meta_value`) AS 'outlookAverageScore'";
	$sql .= " FROM `wp_gf_entry_meta`,";
	$sql .= " ( SELECT * FROM `wp_gf_entry_meta` WHERE meta_key = 'health_tobacco_stress_score' ) healthTobaccoStress";
	$sql .= " WHERE `wp_gf_entry_meta`.`meta_key` = 'osir_profile'";
	$sql .= " AND `wp_gf_entry_meta`.`entry_id` = healthTobaccoStress.`entry_id`";
	$sql .= " AND `wp_gf_entry_meta`.`form_id` = ".$my_gform_id;

	$results = $wpdb->get_results( $sql, ARRAY_A );

	$counter = 0;
	$chartData = [];
	$seriesColors = array( "fill-color: #dd3333", "fill-color: #ff9205", 
		"fill-color: #eeee22", "fill-color: #81d742" );

	// Build chart data
	foreach ($results as $k => $v){
		$osirProfile = isset($v['osirProfile'])? $v['osirProfile']: 0;
		$outlookAverageScore = isset($v['outlookAverageScore'])? (double)$v['outlookAverageScore']: 0;
		$chartData[$counter][0] = $osirProfile;
		$chartData[$counter][1] = $outlookAverageScore;
		$chartData[$counter][2] = $seriesColors[$counter];
		$chartData[$counter][3] = $outlookAverageScore;
		$counter++;
	}
	
	return $chartData;
}

function OSIRDisabilityChartData($my_gform_id) {
  global $wpdb;

	$sql  = "SELECT `wp_gf_entry_meta`.`meta_value` AS 'osirProfile',"; 
	$sql .= " Count(impactQuestionsDisability.`meta_value`) AS 'Yes'";
	$sql .= " FROM `wp_gf_entry_meta`,";
	$sql .= " ( SELECT * FROM `wp_gf_entry_meta` WHERE meta_key = 'impact_questions_disability' ) impactQuestionsDisability";
	$sql .= " WHERE `wp_gf_entry_meta`.`meta_key` = 'osir_profile'";
	$sql .= " AND `wp_gf_entry_meta`.`entry_id` = impactQuestionsDisability.`entry_id`";
	$sql .= " AND impactQuestionsDisability.`meta_value` = 'Yes'";
	$sql .= " AND `wp_gf_entry_meta`.`form_id` = ".$my_gform_id;
	$sql .= " GROUP BY osirProfile";
	
	$results = $wpdb->get_results( $sql, ARRAY_A );

	$counter = 0;
	$chartData = [];
	$seriesColors = array( "fill-color: #dd3333", "fill-color: #ff9205", 
		"fill-color: #eeee22", "fill-color: #81d742" );

	// Build chart data
	foreach ($results as $k => $v){
		$osirProfile = isset($v['osirProfile'])? $v['osirProfile']: '';
		$osirProfileCount = isset($v['Yes'])? (double)$v['Yes']: 0;
		$chartData[$counter][0] = $osirProfile;
		$chartData[$counter][1] = $osirProfileCount;
		$chartData[$counter][2] = $seriesColors[$counter];
		$counter++;
	}
	
	return $chartData;
}

function OSIRWCCChartData($my_gform_id) {
  global $wpdb;

	$sql  = "SELECT `wp_gf_entry_meta`.`meta_value` AS 'osirProfile',"; 
	$sql .= " Count(impactQuestionsWCC.`meta_value`) AS 'Yes'";
	$sql .= " FROM `wp_gf_entry_meta`,";
	$sql .= " ( SELECT * FROM `wp_gf_entry_meta` WHERE meta_key = 'impact_questions_wcc_claim' ) impactQuestionsWCC";
	$sql .= " WHERE `wp_gf_entry_meta`.`meta_key` = 'osir_profile'";
	$sql .= " AND `wp_gf_entry_meta`.`entry_id` = impactQuestionsWCC.`entry_id`";
	$sql .= " AND impactQuestionsWCC.`meta_value` = 'Yes'";
	$sql .= " AND `wp_gf_entry_meta`.`form_id` = ".$my_gform_id;
	$sql .= " GROUP BY osirProfile";
	
	$results = $wpdb->get_results( $sql, ARRAY_A );

	$counter = 0;
	$chartData = [];
	$seriesColors = array( "fill-color: #dd3333", "fill-color: #ff9205", 
		"fill-color: #eeee22", "fill-color: #81d742" );

	// Build chart data
	foreach ($results as $k => $v){
		$osirProfile = isset($v['osirProfile'])? $v['osirProfile']: '';
		$osirProfileCount = isset($v['Yes'])? (double)$v['Yes']: 0;
		$chartData[$counter][0] = $osirProfile;
		$chartData[$counter][1] = $osirProfileCount;
		$chartData[$counter][2] = $seriesColors[$counter];
		$counter++;
	}
	
	return $chartData;
}

function traumaAvgScorebyProfileChartHeader() {
	$series = array(
		array(
			'label' => 'OSIR Profile',
			'type' => 'string',
		),
		array(
			'label' => 'Trauma Events Average Score',
			'type' => 'number',
		),
		array(
			'label' => 'style',
			'type' => 'string',
		),
	);

	return $series;
}

function absenteeismProfileChartHeader() {
	$series = array(
		array(
			'label' => 'Attendance',
			'type' => 'string',
		),
		array(
			'label' => 'Trauma Events Average Score',
			'type' => 'number',
		),
		array(
			'label' => 'style',
			'type' => 'string',
		),
	);

	return $series;
}

function OSIRByYearsOfServiceChartData($my_gform_id = 0){
	global $wpdb;

	$sql  = "SELECT osirYearsOfService.`meta_value` AS 'yearsOfService',";
	$sql .= " AVG(`wp_gf_entry_meta`.`meta_value`) AS 'averageOSIRScore'";
	$sql .= " FROM `wp_gf_entry_meta`,";
	$sql .= " (SELECT * FROM `wp_gf_entry_meta` WHERE meta_key = 'osir_years_of_service') osirYearsOfService";
	$sql .= " WHERE `wp_gf_entry_meta`.`meta_key` = 'total_osir_score'";
	$sql .= " AND `wp_gf_entry_meta`.`entry_id` = osirYearsOfService.`entry_id`";
  $sql .= " AND `wp_gf_entry_meta`.`form_id` = ".$my_gform_id;
	$sql .= " GROUP BY osirYearsOfService.`meta_value`";
	$sql .= " ORDER BY LENGTH(osirYearsOfService.`meta_value`) ASC,";
	$sql .= " osirYearsOfService.`meta_value` ASC";

	$results = $wpdb->get_results( $sql, ARRAY_A );

	$counter = 0;
	$chartData = [];

	// Build chart data
	foreach ($results as $k => $v){
		$yearsOfService = isset($v['yearsOfService'])? $v['yearsOfService']: 0;
		$averageOSIRScore = isset($v['averageOSIRScore'])? (double)$v['averageOSIRScore']: 0;
		$chartData[$counter][0] = $yearsOfService;
		$chartData[$counter][1] = $averageOSIRScore;
		$chartData[$counter][2] = $averageOSIRScore;
		$counter++;
	}

	echo "<br><br>sql:<br>".$sql;
	echo "<br><br>results:<br>";
	print_r($results);
	echo "<br><br>chartData:<br>";
	print_r($chartData);
	
	return $chartData;
}

function traumaAvgScorebyProfileChartData($my_gform_id = 0) {
	global $wpdb;

	$sql  = "SELECT `wp_gf_entry_meta`.`meta_value` AS 'osirProfile',"; 
	$sql .= " AVG(numberTraumaEvents.`meta_value`) AS 'traumaEventsAvgNumber'";
	$sql .= " FROM `wp_gf_entry_meta`,";
	$sql .= " ( SELECT * FROM `wp_gf_entry_meta` WHERE meta_key = 'number_trauma_events' ) numberTraumaEvents";
	$sql .= " WHERE `wp_gf_entry_meta`.`meta_key` = 'osir_profile'";
	$sql .= " AND `wp_gf_entry_meta`.`entry_id` = numberTraumaEvents.`entry_id`";
  $sql .= " AND `wp_gf_entry_meta`.`form_id` = ".$my_gform_id;
	$sql .= " GROUP BY osirProfile";

	$results = $wpdb->get_results( $sql, ARRAY_A );

	$counter = 0;
	$chartData = [];
	$seriesColors = array( "fill-color: #dd3333", "fill-color: #ff9205", 
		"fill-color: #eeee22", "fill-color: #81d742" );

	// Build chart data
	foreach ($results as $k => $v){
		$osirProfile = isset($v['osirProfile'])? $v['osirProfile']: 0;
		$traumaEventsAvgNumber = isset($v['traumaEventsAvgNumber'])? (double)$v['traumaEventsAvgNumber']: 0;
		$chartData[$counter][0] = $osirProfile;
		$chartData[$counter][1] = $traumaEventsAvgNumber;
		$chartData[$counter][2] = $seriesColors[$counter];
		$counter++;
	}

	echo "<br><br>sql:<br>".$sql;
	echo "<br><br>results:<br>";
	print_r($results);
	echo "<br><br>chartData:<br>";
	print_r($chartData);
	
	return $chartData;
}

// Absenteeism Profile Chart Data
/*
function absenteeismProfileChartData() {
	global $wpdb;

	$sql  = "SELECT `wp_gf_entry_meta`.`meta_value` AS 'osirProfile',";
	$sql .= " AVG(absenteeismOSIRProfile.`meta_value`) AS 'averageAbsenteeism'";
	$sql .= " FROM `wp_gf_entry_meta`,"; 
	$sql .= "( SELECT * FROM `wp_gf_entry_meta` WHERE meta_key = 'absenteeism_osir_profile' ) absenteeismOSIRProfile";
	$sql .= " WHERE `wp_gf_entry_meta`.`meta_key` = 'osir_profile'";
	$sql .= " AND `wp_gf_entry_meta`.`entry_id` = absenteeismOSIRProfile.`entry_id`";
	$sql .= " GROUP BY osirProfile";

	$results = $wpdb->get_results( $sql, ARRAY_A );

	$counter = 0;
	$chartData = [];

	// Build chart data
	foreach ($results as $k => $v){
		/* echo "<br>".$k."=>";
		echo "<br>osirProfile: ".$v['osirProfile'];
		echo "<br>averageAbsenteeism: ".$v['averageAbsenteeism'];
	
		$osirProfile = isset($v['osirProfile'])? $v['osirProfile']: 0;
		$averageAbsenteeism = isset($v['averageAbsenteeism'])? (double)$v['averageAbsenteeism']: 0;

		$chartData[$counter][0] = $osirProfile;
		$chartData[$counter][1] = $averageAbsenteeism;
		$counter++;
	}

	// echo "<br><br>sql:<br>".$sql;
	// echo "<br><br>results:<br>";
	// print_r($results);
	// echo "<br><br>chartData:<br>";
	// print_r($chartData);
	
	return $chartData;
}

*/
