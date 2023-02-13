<?php

/*************************************
  Export Survey Entries
*************************************/

require_once('../../../wp-load.php');

// Global variables
$export_entries_file_name = 'survey-data.csv';
$export_entries_file_dir = 'export/'. $export_entries_file_name;
$path_parts = pathinfo($export_entries_file_name);
$file_parts = $path_parts['filename']."-".date('m-d-Y').".".$path_parts['extension'];

// Fetch Gravity Form ID for this parent owner account
function get_gform_id($parent_user_id) {
  global $wpdb;

  if ($parent_user_id > 0) {
    $sql  = "SELECT * FROM `wp_gf_entry_meta` WHERE `meta_key`='gf_corporate_parent_uid' ";
    $sql .= "AND `meta_value`=".$parent_user_id;
    $results = $wpdb->get_results( $sql );
    return (isset($results) && isset($results[0])? $results[0]->form_id : 0);
  }
}

if ( isset($_GET['parent_user_id']) && file_exists($export_entries_file_dir) ) {
    $gform_id = get_gform_id($_GET['parent_user_id']);

    if (isset($gform_id) && $gform_id > 0) {

        // Generate CSV export file
        export_survey_csv_entries($gform_id, $export_entries_file_dir);

        // Force browser to download CSV file
        header('Content-Description: File Transfer');
        header('Content-Type: text/csv');
        header("Content-Disposition: attachment; filename=" . $file_parts);
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($export_entries_file_dir));

        $fp = fopen($export_entries_file_dir, "r");
        if ($fp) {
            while (($line = fgets($fp)) !== false) {
                echo $line;
            }
            if (!feof($fp)) {
                echo "Error: unexpected fgets() fail\n";
            }
            fclose($fp);
        }

        exit;
    }
    else {
        echo "<br><p>No matching entries were found!</p><br><a href='/?action=subscriptions'>&#60;&#60;Go back</a>";
    }
}
else {
    echo "<br><p>Error opening file: ".$export_entries_file_name."</p><br><a href='/?action=subscriptions'>&#60;&#60;Go back</a>";
}

// Generate CSV export entries file
function export_survey_csv_entries($gform_id, $export_entries_file_dir) {
    global $wpdb;
    $demographics_dependents_yes_first_index = 'demographics_dependents_yes_first_index_0';
    
    // Create GFSurvey instance
    if ( ! class_exists( 'GFSurvey' ) ) {
		require_once ABSPATH . 'wp-content/plugins/gravityformssurvey/class-gf-survey.php';
	}
	$gf_survey_instance = GFSurvey::get_instance();

    if ($gform_id > 0) {
        $sql  = "SELECT * FROM `wp_gf_entry_meta` WHERE `form_id`=".$gform_id;
        $sql .= " ORDER BY `wp_gf_entry_meta`.`id` ASC";
        $survey_entries = $wpdb->get_results( $sql, ARRAY_A );

        // echo "<br>sql: ".$sql;
        // echo "<br>survey_entries: "; var_dump($survey_entries);

        $fp = fopen($export_entries_file_dir, 'w');

        // Create CSV Data
        $csv_rows_data = writeCSVData($survey_entries, $gf_survey_instance);
        
        foreach ($csv_rows_data as $fields) {
            // Remove whitespaces and - last occurence from this question if any
            if ( isset($fields[$demographics_dependents_yes_first_index]) ) {
                $fields[$demographics_dependents_yes_first_index] = trim($fields[$demographics_dependents_yes_first_index], "- ");
            }
            fputcsv($fp, $fields);

            // echo "<br><br>csv_rows_data: "; var_dump($fields);
        }

        fclose($fp);
    }
}

// Write CSV spread sheet data
function writeCSVData($survey_entries, $gf_survey_instance) {
    $csv_scores_answers = array();
    $demographics_dependents_yes_first_index = 'demographics_dependents_yes_first_index_0';

    foreach ($survey_entries as $k => $fields) {
        $entry = GFAPI::get_entry( $fields['entry_id'] );
        $field = GFAPI::get_field( $fields['form_id'], $fields['meta_key'] );

        // Survey Likert Fields Questions
        // --------------------------------------------------------------------------

        if ( $field && $field->get_input_type() == 'likert' && $field->gsurveyLikertEnableScoring ) {
            // Header
            if ($field->label === 'We have a beneficial Employee and Family Assistance Program (EFAP)') {
                $csv_scores_answers['entry_id']['header_cells'] = 'ENTRY ID';
            }

            if ( !isset($csv_scores_answers['entry_id'][$field->label]) ) {
                $csv_scores_answers['entry_id'][$field->label] = $field->label;
            }

            // Rows
            if ($field->label === 'We have a beneficial Employee and Family Assistance Program (EFAP)') {
                $csv_scores_answers[$fields['entry_id']][$fields['entry_id']] = $fields['entry_id'];
            }   

            if ( !isset($csv_scores_answers[$fields['entry_id']][$k]) ) {
                $csv_scores_answers[$fields['entry_id']][$k] = $gf_survey_instance:: get_field_score($field, $entry);
            }
            else {
                $csv_scores_answers[$fields['entry_id']][$k] .= $gf_survey_instance:: get_field_score($field, $entry);
            }
        }

        // Other Fields types Questions
        // ---------------------------------------------------------------------------

        if ($field && $field->cssClass === 'impact_questions_attendance') {
            // Header
            if ( !isset($csv_scores_answers['entry_id'][$field->label]) ) {
                $csv_scores_answers['entry_id'][$field->label] = $field->label;
            }

            // Rows
            if ( !isset($csv_scores_answers[$fields['entry_id']][$k]) ) {
                $csv_scores_answers[$fields['entry_id']][$k] = $fields['meta_value'];
            }
            else {
                $csv_scores_answers[$fields['entry_id']][$k] .= $fields['meta_value'];
            }
        }

        if ($field && $field->cssClass === 'impact_questions_presenteeism') {
            // Header
            if ( !isset($csv_scores_answers['entry_id'][$field->label]) ) {
                $csv_scores_answers['entry_id'][$field->label] = $field->label;
            }

            // Rows
            if ( !isset($csv_scores_answers[$fields['entry_id']][$k]) ) {
                $csv_scores_answers[$fields['entry_id']][$k] = $fields['meta_value'];
            }
            else {
                $csv_scores_answers[$fields['entry_id']][$k] .= $fields['meta_value'];
            }
        }

        if ($field && $field->cssClass === 'vulnerability_site_department') {
            // Header
            if ( !isset($csv_scores_answers['entry_id'][$field->label]) ) {
                $csv_scores_answers['entry_id'][$field->label] = $field->label;
            }

            // Rows
            if ( !isset($csv_scores_answers[$fields['entry_id']][$k]) ) {
                $csv_scores_answers[$fields['entry_id']][$k] = $fields['meta_value'];
            }
            else {
                $csv_scores_answers[$fields['entry_id']][$k] .= $fields['meta_value'];
            }
        }
            
        if ($field && $field->cssClass === 'vulnerability_university_site_department') {
            // Header
            if ( !isset($csv_scores_answers['entry_id']['Department']) ) {
                $csv_scores_answers['entry_id']['Department'] = 'Department';
            }

            // Rows
            if ( !isset($csv_scores_answers[$fields['entry_id']][$k]) ) {
                $csv_scores_answers[$fields['entry_id']][$k] = $fields['meta_value'];
            }
            else {
                $csv_scores_answers[$fields['entry_id']][$k] .= $fields['meta_value'];
            }
        }

        if ($field && $field->cssClass === 'vulnerability_victoria_site_department') {
            // Header
            if ( !isset($csv_scores_answers['entry_id']['Department']) ) {
                $csv_scores_answers['entry_id']['Department'] = 'Department';
            }

            // Rows
            if ( !isset($csv_scores_answers[$fields['entry_id']][$k]) ) {
                $csv_scores_answers[$fields['entry_id']][$k] = $fields['meta_value'];
            }
            else {
                $csv_scores_answers[$fields['entry_id']][$k] .= $fields['meta_value'];
            }
        }

        if ($field && $field->cssClass === 'vulnerability_vocation') {
            // Header
            if ( !isset($csv_scores_answers['entry_id'][$field->label]) ) {
                $csv_scores_answers['entry_id'][$field->label] = $field->label;
            }

            // Rows
            if ( !isset($csv_scores_answers[$fields['entry_id']][$k]) ) {
                $csv_scores_answers[$fields['entry_id']][$k] = $fields['meta_value'];
            }
            else {
                $csv_scores_answers[$fields['entry_id']][$k] .= $fields['meta_value'];
            }
        }

        if ($field && $field->cssClass === 'demographics_gender') {
            // Header
            if ( !isset($csv_scores_answers['entry_id'][$field->label]) ) {
                $csv_scores_answers['entry_id'][$field->label] = $field->label;
            }

            // Rows
            if ( !isset($csv_scores_answers[$fields['entry_id']][$k]) ) {
                $csv_scores_answers[$fields['entry_id']][$k] = $fields['meta_value'];
            }
            else {
                $csv_scores_answers[$fields['entry_id']][$k] .= $fields['meta_value'];
            }

            // Age Header (Edge case: age Header is not set if very first entry was: prefer not to answer)
            if ( !isset($csv_scores_answers['entry_id']['What is your age?']) ) {
                $csv_scores_answers['entry_id']['What is your age?'] = 'What is your age?';
            }

            // Place holder value for demographics_age question if user selects: prefer not to answer
            $index_age = $k.'_age';
            $csv_scores_answers[$fields['entry_id']][$index_age] = 'Prefer not to answer';
        }

        if ($field && $field->cssClass === 'demographics_age') {
            // echo "<br><br>demographics_age field: ". $field->id. ", ". $field->cssClass. ", ". $field->label;
            // echo "<br>index_age: ".$index_age.", entry_id: ".$fields['entry_id'].", k:".$k.", meta_value: ".$fields['meta_value'];

            // Age Field has actual numeric value, unset the place holder value: prefer not to answer
            unset($csv_scores_answers[$fields['entry_id']][$index_age]);

            // Rows
            if ( !isset($csv_scores_answers[$fields['entry_id']][$k]) ) {
                $csv_scores_answers[$fields['entry_id']][$k] = $fields['meta_value'];
            }
            else {
                $csv_scores_answers[$fields['entry_id']][$k] .= $fields['meta_value'];
            }
        }

        if ($field && $field->cssClass === 'demographics_dependents') {
            $demographics_dependents_yes_cell_header =  'Which of the following care responsibilities do you have outside the workplace? Please select all that apply.';

            // Header
            if ( !isset($csv_scores_answers['entry_id'][$field->label]) ) {
                $csv_scores_answers['entry_id'][$field->label] = $field->label;
            }

            // Rows
            if ( !isset($csv_scores_answers[$fields['entry_id']][$k]) ) {
                $csv_scores_answers[$fields['entry_id']][$k] = $fields['meta_value'];
            }
            else {
                $csv_scores_answers[$fields['entry_id']][$k] .= $fields['meta_value'];
            }

            // Header (Add demographics_dependents_yes Field Header just after demographics_dependents Field Header)
            if ( !isset($csv_scores_answers['entry_id'][$demographics_dependents_yes_cell_header]) ) {
                $csv_scores_answers['entry_id'][$demographics_dependents_yes_cell_header] = $demographics_dependents_yes_cell_header;
            }

            // Rows (Placeholder value for demographics_dependents_yes if user selected No for demographics_dependents)
            $csv_scores_answers[$fields['entry_id']][$demographics_dependents_yes_first_index] = ' ';
        }

        if ($field && $field->cssClass === 'demographics_dependents_yes') {
            // CSV format use "," .Bypass Question text with ","
            $meta_value = str_replace(',', ' ', $fields['meta_value']);

            // Rows
            if ( !isset($csv_scores_answers[$fields['entry_id']][$demographics_dependents_yes_first_index]) ) {           
                $csv_scores_answers[$fields['entry_id']][$demographics_dependents_yes_first_index] = $meta_value;
            }
            else {
                $csv_scores_answers[$fields['entry_id']][$demographics_dependents_yes_first_index] .= $meta_value.' - ';
            }
        }

        // MOL Demographics
        // -------------------------------------------------------------------------------
        if ($field && $field->cssClass === 'demographics_region_branch_primarily_work') {
            // echo "<br><br>entry_id: ".$fields['entry_id'].", field: ".$field->id.", ".$field->cssClass.", ".$field->label;
            // echo "<br>k:".$k.", meta_value: ".$fields['meta_value'];

            // Header: At which Region or Branch do you primarily work?
            if ( !isset($csv_scores_answers['entry_id'][$field->label]) ) {
                $csv_scores_answers['entry_id'][$field->label] = $field->label;
            }

            // Rows
            if ( !isset($csv_scores_answers[$fields['entry_id']][$k]) ) {
                $csv_scores_answers[$fields['entry_id']][$k] = $fields['meta_value'];
            }

            // Header: Demographics Drop down selection
            if ( !isset($csv_scores_answers['entry_id']['Please select the functional group that best represents your position']) ) {
                $csv_scores_answers['entry_id']['Please select the functional group that best represents your position'] = 'Please select the functional group that best represents your position';
            }

        }

        if ($field && $field->cssClass === 'demographics_region_branch_employment_practices') {
            // Rows
            if ( !isset($csv_scores_answers[$fields['entry_id']][$k]) ) {
                $csv_scores_answers[$fields['entry_id']][$k] = $fields['meta_value'];
            }
        }

        if ($field && $field->cssClass === 'demographics_region_branch_occupational_health_and_safety') {
            // Rows
            if ( !isset($csv_scores_answers[$fields['entry_id']][$k]) ) {
               $csv_scores_answers[$fields['entry_id']][$k] = $fields['meta_value'];
            }

        }

        if ($field && $field->cssClass === 'demographics_region_branch_operation_integration_unit') {
            // Rows
            if ( !isset($csv_scores_answers[$fields['entry_id']][$k]) ) {
                $csv_scores_answers[$fields['entry_id']][$k] = $fields['meta_value'];
            }

        }

        if ($field && $field->cssClass === 'demographics_region_branch_assistant_deputy_minister_office') {
            // Rows
            if ( !isset($csv_scores_answers[$fields['entry_id']][$k]) ) {
                $csv_scores_answers[$fields['entry_id']][$k] = $fields['meta_value'];
            }
        }

        if ($field && $field->cssClass === 'demographics_region_branch_central_east_region') {
            // Rows
            if ( !isset($csv_scores_answers[$fields['entry_id']][$k]) ) {
               $csv_scores_answers[$fields['entry_id']][$k] = $fields['meta_value'];
            }
        }

        if ($field && $field->cssClass === 'demographics_region_branch_central_west_region') {
            // Rows
            if ( !isset($csv_scores_answers[$fields['entry_id']][$k]) ) {
               $csv_scores_answers[$fields['entry_id']][$k] = $fields['meta_value'];
            }
        }

        if ($field && $field->cssClass === 'demographics_region_branch_eastern_region') {
            // Rows
            if ( !isset($csv_scores_answers[$fields['entry_id']][$k]) ) {
                $csv_scores_answers[$fields['entry_id']][$k] = $fields['meta_value'];
            }
        }

        if ($field && $field->cssClass === 'demographics_region_branch_northern_region') {
            // Rows
            if ( !isset($csv_scores_answers[$fields['entry_id']][$k]) ) {
               $csv_scores_answers[$fields['entry_id']][$k] = $fields['meta_value'];
            }

        }

        if ($field && $field->cssClass === 'demographics_region_branch_south_western_region') {
            // Rows
            if ( !isset($csv_scores_answers[$fields['entry_id']][$k]) ) {
                $csv_scores_answers[$fields['entry_id']][$k] = $fields['meta_value'];
            }

        }

        if ($field && $field->cssClass === 'demographics_region_branch_western_region') {
            // Rows
            if ( !isset($csv_scores_answers[$fields['entry_id']][$k]) ) {
                $csv_scores_answers[$fields['entry_id']][$k] = $fields['meta_value'];
            }
        }

        // OSIR 4 Subscales Totals & OSIR Total Score
        // ------------------------------------------

        if ($fields['meta_key'] === 'total_support_programs_score') {
            // echo "<br><br>total_support_programs_score: ".$fields['id'].", k:".$k.", meta_value: ".$fields['meta_value'];

            // Header
            if ( !isset($csv_scores_answers['entry_id']['Total Support Programs Score']) ) {
                $csv_scores_answers['entry_id']['Total Support Programs Score'] = 'Total Support Programs Score';
            }

            // Rows
            if ( !isset($csv_scores_answers[$fields['entry_id']][$k]) ) {
                $csv_scores_answers[$fields['entry_id']][$k] = $fields['meta_value'];
            }
        }

        if ($fields['meta_key'] === 'total_resiliency_behaviours_score') {
            // Header
            if ( !isset($csv_scores_answers['entry_id']['Total Resiliency Behaviours Score']) ) {
                $csv_scores_answers['entry_id']['Total Resiliency Behaviours Score'] = 'Total Resiliency Behaviours Score';
            }

            // Rows
            if ( !isset($csv_scores_answers[$fields['entry_id']][$k]) ) {
                $csv_scores_answers[$fields['entry_id']][$k] = $fields['meta_value'];
            }
        }

        if ($fields['meta_key'] === 'total_supportive_environment_score') {
            // Header
            if ( !isset($csv_scores_answers['entry_id']['Total Supportive Environment Score']) ) {
                $csv_scores_answers['entry_id']['Total Supportive Environment Score'] = 'Total Supportive Environment Score';
            }

            // Rows
            if ( !isset($csv_scores_answers[$fields['entry_id']][$k]) ) {
                $csv_scores_answers[$fields['entry_id']][$k] = $fields['meta_value'];
            }
        }

        if ($fields['meta_key'] === 'total_supportive_leadership_score') {
            // Header
            if ( !isset($csv_scores_answers['entry_id']['Total Supportive Leadership Score']) ) {
                $csv_scores_answers['entry_id']['Total Supportive Leadership Score'] = 'Total Supportive Leadership Score';
            }

            // Rows
            if ( !isset($csv_scores_answers[$fields['entry_id']][$k]) ) {
                $csv_scores_answers[$fields['entry_id']][$k] = $fields['meta_value'];
            }
        }

        if ($fields['meta_key'] === 'total_osir_score') {
            // Header
            if ( !isset($csv_scores_answers['entry_id']['Total OSIR Score']) ) {
                $csv_scores_answers['entry_id']['Total OSIR Score'] = 'Total OSIR Score';
            }

            // Rows
            if ( !isset($csv_scores_answers[$fields['entry_id']][$k]) ) {
                $csv_scores_answers[$fields['entry_id']][$k] = $fields['meta_value'];
            }
        }

    }

    // echo "<br><br><pre>csv_scores_answers<br>";
    // var_dump($csv_scores_answers);
    // echo "</pre>";
    
    return $csv_scores_answers;
}

// $form = GFAPI::get_form( $fields['form_id'] );
// $result = GFAPI::update_form( $form );
// $result = GFAPI::update_entry( $entry );
