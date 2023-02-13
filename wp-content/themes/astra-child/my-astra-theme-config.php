<?php

//----------------------------------------------------------------------------------------
// Astra theme custom configuration

// Astra theme hooks
// http://developers.wpastra.com/theme-visual-hooks/

// GF ID
$my_gform_id = (isset($_GET['my_gform_id']) && $_GET['my_gform_id'] > 0) ? $_GET['my_gform_id'] : 0;

// Disable next/previous navigation links for membership pages
add_filter( 'astra_single_post_navigation_enabled', '__return_false' );

// check gravity form page submissions limit (per user)
add_action( 'astra_entry_content_before', 'add_my_script_astra_entry_content_before');
function add_my_script_astra_entry_content_before() {
  global $post;

  // Get current page id
  if (isset($post->ID)) {
    // Added manually using advanced custom fields inside gf survey word press page
    $my_gform_submissions_limit = get_post_meta( $post->ID, 'my_gform_submissions_limit', true );
  }
  
  if (isset($my_gform_submissions_limit) && $my_gform_submissions_limit > 0) {
    $gf_survey_entry_user = get_user_meta( get_current_user_id(), 'gf_survey_entry', true ); 
    $is_survey_entry_submitted_by_user = gform_get_meta( $gf_survey_entry_user, 'is_survey_entry_submitted_by_user' );

    /* echo "<br>my_gform_submissions_limit: ".$my_gform_submissions_limit;
    echo "<br>get_current_user_id: ".get_current_user_id().", gf_survey_entry_user: ".$gf_survey_entry_user;
    echo "<br>is_survey_entry_submitted_by_user: ".$is_survey_entry_submitted_by_user; */

    if ( $is_survey_entry_submitted_by_user !== false && $is_survey_entry_submitted_by_user === 'yes') {
      echo '<h5 style="margin-left: 0px !important;">You may only take the OSIR survey once!</h5><a href="/">Go to My Account</a>';
      exit();
    }
  }
}

// https://developer.wordpress.org/reference/functions/wp_is_mobile/
// add_action( 'template_redirect', 'my_custom_redirects_func' );
/* function my_custom_redirects_func() {
  // my_gform_id = 18, corporate_parent_account_user_id = 3 
  if ( is_user_logged_in() && wp_is_mobile() && is_page('osir-survey-analytics-18') ) {
    // Redirect users to the mobile page version of the survey analytics
    wp_safe_redirect( '/osir-survey-analytics-18-m/' );
    exit;
  }

  // my_gform_id = 17, corporate_parent_account_user_id = 9
  if ( is_user_logged_in() && wp_is_mobile() && is_page('osir-survey-analytics-9') ) {
    // Redirect users to the mobile page version of the survey analytics
    wp_safe_redirect( '/osir-survey-analytics-9-m/' );
    exit;
  }
} */
