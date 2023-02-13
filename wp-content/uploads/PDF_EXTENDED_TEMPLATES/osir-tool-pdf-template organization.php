<?php 

/**
  * Template Name: OSIR Tool Organizaion Report
  * Version: 1.0
  * Description: Custom PDF template used to output the layout of PDF generated downloadable file outlining the recommendations according to calculated OSIR score when the user completes Gravity Forms OSIR survey submission
  * Author: Omar M.
  * Author URI: https://gravitypdf.com
  * Group: Custom
  * License: GPLv2
  * Required PDF Version: 4.0-alpha
  * Tags: OSIR tool, Header, Footer, Background, Optional HTML Fields, Optional Page Fields, Container Background Color
*/

/* Prevent direct access to the template (always good to include this) */
if ( ! class_exists( 'GFForms' ) ) {
  return;
}

/**
 * All Gravity PDF templates have access to the following variables:
 *
 * @var array  $form      The current Gravity Form array
 * @var array  $entry     The raw entry data
 * @var array  $form_data The processed entry data stored in an array
 * @var array  $settings  The current PDF configuration
 * @var array  $fields    An array of Gravity Form fields which can be accessed with their ID number
 * @var array  $config    The initialised template config class – eg. /config/zadani.php
 * @var object $gfpdf     The main Gravity PDF object containing all our helper classes
 * @var array  $args      Contains an array of all variables - the ones being described right now - passed to the template
 */

  // Docs
  // https://docs.gravitypdf.com/v6/developers/first-custom-pdf
  // https://gist.github.com/jakejackson1/997b5dedf0a5e665e8ef

?>

 <!-- PDF CSS styles -->

<style>

  h1, h2, h3 {
		text-transform: uppercase;
		color: #a62828; 
		font-size: 18px;
	}

  div.pdf-content {
    font-size: 14px;
    letter-spacing: 2px;
  }

	a {
		color: #0040ff;
  	background-color: transparent;
	}

</style>

<!-- PDF Content -->

<!-- PDF Header -->

<table autosize="1">
	<tr>
		<td align="left"><img src="/wp-content/uploads/2021/07/My_logo.png" alt="My Logo" /></td>
		<!-- <td align="right">Page {PAGENO} of {nbpg}</td> -->
	</tr>
</table>

<!-- PDF Content -->

<div class="pdf-content">
  
  <br>Date completed: {date_mdy}

  <?php

    // Output survey submission confirmation messages
    require_once ABSPATH . 'wp-content/themes/astra-child/my-survey-confirmation-messages.php';
  
    $entry_id = $form_data['entry_id'];
    $my_gform_id = gform_get_meta( $entry_id, 'my_gform_id');
    $is_survey_entry_submitted_by_user = gform_get_meta( $entry_id, 'is_survey_entry_submitted_by_user' );

    if ( $my_gform_id > 0 && $is_survey_entry_submitted_by_user !== false && $is_survey_entry_submitted_by_user === 'yes') {    
      $resiliencyBehavioursAverageScore = calculateOrganizationAverageScore ('total_resiliency_behaviours_score', $my_gform_id);
      $supportProgramsAverageScore = calculateOrganizationAverageScore ('total_support_programs_score', $my_gform_id);
      $supportiveLeadershipAverageScore = calculateOrganizationAverageScore ('total_supportive_leadership_score', $my_gform_id);
      $supportiveEnvironmentAverageScore = calculateOrganizationAverageScore ('total_supportive_environment_score', $my_gform_id);

    $osirAverageGrandScore = ( $resiliencyBehavioursAverageScore + $supportProgramsAverageScore + 
      $supportiveLeadershipAverageScore + $supportiveEnvironmentAverageScore ) / 4;

      if ( $entry_id > 0 && $osirAverageGrandScore > 0 ) {
        echo getOrganizationGenericMsg($resiliencyBehavioursAverageScore, $supportProgramsAverageScore, 
          $supportiveLeadershipAverageScore, $supportiveEnvironmentAverageScore);
        echo getOrganizationScalesMsg($osirAverageGrandScore, $resiliencyBehavioursAverageScore, 
          $supportProgramsAverageScore, $supportiveLeadershipAverageScore, $supportiveEnvironmentAverageScore);
      }
    }
  ?>
</div>
