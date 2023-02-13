<?php

//----------------------------------------------------------------------------------------
// Member Press & Gravity Form custom hooks

// Numeric field validation: Impact Questions: Q1 and Q2
add_filter( 'gform_field_validation_20_176', 'validate_attendance_presenteeism_impact_fields', 10, 4 );
add_filter( 'gform_field_validation_20_178', 'validate_attendance_presenteeism_impact_fields', 10, 4 );
add_filter( 'gform_field_validation_21_176', 'validate_attendance_presenteeism_impact_fields', 10, 4 );
add_filter( 'gform_field_validation_21_178', 'validate_attendance_presenteeism_impact_fields', 10, 4 );

function validate_attendance_presenteeism_impact_fields( $result, $value, $form, $field ) {

  if (is_numeric($value)){
    $validated_value = ($value == (int) $value) ? (int) $value : (float) $value;
  } else{
    $validated_value = $value;
  }

  /*
  echo "<br><br>result";
  echo $field->id;
  print_r($result);
  
  echo "<br><br>value<br>";
  var_dump($value);
  print_r(is_numeric($value));
  var_dump($validated_value);
  */

  if ( $result['is_valid'] && !is_int($validated_value) ) {
    $result['is_valid'] = false;
    $result['message'] = 'Only positive whole numeric values between 0 to 90 (inclusive) are allowed';
  } else if ($result['is_valid'] && (0 > $validated_value || $validated_value > 90) ){
    $result['is_valid'] = false;
    $result['message'] = 'Only positive whole numeric values between 0 to 90 (inclusive) are allowed';
  }

  return $result;
}

// Numeric field validation: Demographics
add_filter( 'gform_field_validation_20_180', 'validate_age_demographics_field', 10, 4 ); 
add_filter( 'gform_field_validation_19_180', 'validate_age_demographics_field', 10, 4 );
function validate_age_demographics_field( $result, $value, $form, $field ) {

  if (is_numeric($value)){
    $validated_value = ($value == (int) $value) ? (int) $value : (float) $value;
  } else{
    $validated_value = $value;
  }

  if ( $result['is_valid'] && !is_int($validated_value) ) {
    $result['is_valid'] = false;
    $result['message'] = 'Only whole numbers between 18 to 80 years (inclusive) are allowed';
  } else if ($result['is_valid'] && (18 > $validated_value || $validated_value > 80) ){
    $result['is_valid'] = false;
    $result['message'] = 'Only whole numbers between 18 to 80 years (inclusive) are allowed';
  }

  return $result;
}

// Adds custom link to the Account subscription page to manage sub accounts when there's an 
// associated corporate account record but ONLY showing the corporate parent account
function my_mepr_account_subscriptions_actions_func($user, $row, $transaction, $issub) {
  global $wpdb;
  $show_membership_users = 'no';
  $obj_type = ($issub ? 'subscriptions' : 'transactions');
  $prd = ($obj_type === 'transactions') ? $transaction->product(): '';
  $ca = MPCA_Corporate_Account::find_corporate_account_by_obj_id($row->id, $obj_type);
  $ca_parent = get_ca_parent();
  $cap = get_user_capability();

  // Added manually using advanced custom fields inside memberpress membership page
  if ( !empty($prd) && !empty($prd->ID) ) {
    $show_membership_users = get_post_meta( $prd->ID, 'show_membership_users', true );
    $membership_parent_account_user_id = get_post_meta($prd->ID, 'membership_parent_account_user_id', true);
  }
  
  // GF moderators role (customers)
  if ( !empty($cap['gf_moderator']) && isset($ca_parent) && $ca_parent !== '' ) {
    $my_ca = $ca_parent;
  } 
  // For logged in corporate account owner (ONLY 1 customer): the parent_id is empty and should be the logged user_id
  else if ( !empty($cap['corporate_parent_account_moderator']) && isset($ca) &&
      isset($ca->user_id) && $ca->user_id == $user->ID ) {
    $my_ca = $ca;
  } 
  // Web Analytics Tool & Reports role members
  else if ( !empty($cap['administrator']) || !empty($cap['pshsa_analytics_reports']) || !empty($cap['designer']) ) {
    if ( isset($membership_parent_account_user_id) && $membership_parent_account_user_id > 0 ) {
      $my_ca = get_myca($membership_parent_account_user_id);
    }
  }

  // echo "<br><br>my_mepr_account_subscriptions_actions_func, show_membership_users user->ID: ".$user->ID. ", cap: "; print_r($cap);
  // echo "<br>show_membership_users: ".$show_membership_users;
  // echo "<br>"; var_dump($row); echo "<br>";
  // echo "<br>obj_type: "; echo $obj_type;
  // echo "<br>prd->ID: ".$prd->ID;
  // echo "<br>ca: ".$ca;
  // echo "<br>ca_parent: ".$ca_parent;
  // echo "<br>my_ca: ".$my_ca;

  if( !empty($my_ca) && isset($my_ca->id) && !empty($my_ca->id) && $my_ca->is_enabled()
     && !empty($show_membership_users) && $show_membership_users === 'yes' ) {
    ?>
    <a href="<?php echo $my_ca->sub_account_management_url(); ?>" class="mepr-account-row-action mepr-account-manage-sub-accounts"><?php _e('Add or Remove Participants', 'memberpress-corporate'); ?></a>
    <?php
  }
}

// We need to determine which corporate account should be displayed for manage accounts page 
// ONLY 1 corporate parent account is allowed per membership
function get_ca_parent () { 
  global $wpdb;

  // fetch logged in user info
  $user = MeprUtils::get_currentuserinfo();

  // Added by member press corporate add-on (for all none parent accounts)
  $mpca_corporate_account_id = get_user_meta($user->ID, 'mpca_corporate_account_id', true);

  // Added manually user_meta field using advanced custom fields plugin
  $ca_parent_user_id = get_user_meta($user->ID, 'corporate_parent_account_user_id', true);
  
  if( $mpca_corporate_account_id > 0 && $ca_parent_user_id > 0 ) {
    $sql  = "SELECT * FROM `wp_mepr_corporate_accounts` WHERE `user_id`=".$ca_parent_user_id;
    $sql .= " AND `id`=".$mpca_corporate_account_id. " AND `status`='enabled'";

    $parent_ca_res = $wpdb->get_results( $sql );
    $ca_parent_uuid = $parent_ca_res[0]->uuid;
    $ca_parent = MPCA_Corporate_Account::find_by_uuid($ca_parent_uuid);
  
    // use the parent account instead to allow other corporate member accounts to upload 
    // new users to the same membership plan using the ONLY 1 parent corporate account user_id
    return $ca_parent;
  }

  // no corporate account parent found OR user is logged in with his corporate parent account
  return '';
}

// Fetch corporate account membership
function get_myca($parent_user_id) {
  global $wpdb;

  if ($parent_user_id > 0) {
    $sql  = "SELECT * FROM `wp_mepr_corporate_accounts` WHERE `user_id`=".$parent_user_id;
    $sql .= " AND `status`='enabled'";
    $parent_ca_res = $wpdb->get_results( $sql );
    $ca_parent_uuid = $parent_ca_res[0]->uuid;
    $my_ca = MPCA_Corporate_Account::find_by_uuid($ca_parent_uuid);
    return $my_ca;
  }
  return '';
}

// Show analytics & organization report links header inside management page (ONLY Web analytics role)
add_action('mepr-account-subscriptions-th', 'show_analytics_reports_header', 10, 2);
function show_analytics_reports_header($user, $subscriptions) { 
  $cap = get_user_capability();
  if ( !empty($cap['administrator']) || !empty($cap['pshsa_analytics_reports']) || !empty($cap['designer']) ) { ?>
    <th>REPORT MANAGEMENT</th>
  <?php }
}
 
// Show analytics, organization report, export entries & QR code links inside management page cell 
// (ONLY Web analytics role)
add_action('mepr-account-subscriptions-td', 'show_analytics_reports_cell', 10, 4);
function show_analytics_reports_cell($user, $row, $transaction, $issub) {
  $show_organization_report = 'no';
  $obj_type = ($issub ? 'subscriptions' : 'transactions');
  $prd = ($obj_type === 'transactions') ? $transaction->product(): '';
  $cap = get_user_capability();
  // $ca = MPCA_Corporate_Account::find_corporate_account_by_obj_id($row->id, $obj_type);
  // $ca_parent = get_ca_parent();

  // Only run the analytics and reports for Web analytics role
  // You have to manually add all clients survey memberships as seperate transactions for each user
  if ( !empty($cap['administrator']) || !empty($cap['pshsa_analytics_reports']) || !empty($cap['designer']) ) {
    
    // Added manually using advanced custom fields inside memberpress membership page
    if ( !empty($prd) && isset($prd->ID) ) {
      $show_organization_report = get_post_meta( $prd->ID, 'show_organization_report', true );
      $membership_parent_account_user_id = get_post_meta($prd->ID, 'membership_parent_account_user_id', true);
    }

    // echo "<br><br>mepr-account-subscriptions-td, show_organization_report user->ID: ".$user->ID. ", cap: "; print_r($cap);
    // echo "<br>prd->ID: ".$prd->ID;
    // echo "<br>show_organization_report: ".$show_organization_report;
    // echo "<br>membership_parent_account_user_id: ".$membership_parent_account_user_id;
    // echo "<br>row: "; var_dump($row); echo "<br>";
    // echo "<br>transaction: "; var_dump($transaction); echo "<br>";
    // echo "<br>obj_type: "; echo $obj_type;
    // echo "<br>ca: ".$ca;
    // echo "<br>ca_parent: ".$ca_parent;

    // For logged in corporate account owner: the parent_id is empty and should be the logged user_id
    // Link should match organization report back end page permalink consisting of:  
    // /organization-report-{corporate_parent_account_user_id} for: $ca_parent->user_id OR $ca->user_id
    // AND matching gravity form back end field: corporate_parent_account_user_id 
    if ( isset($membership_parent_account_user_id) && $membership_parent_account_user_id > 0 && 
        $show_organization_report === 'yes' ) {
        $my_ca = get_myca($membership_parent_account_user_id);
      ?>

      <td style="padding: 10px;">
        <form class="org-report-form" name="org-report-form-<?php echo $membership_parent_account_user_id; ?>" action="/organization-report-<?php echo $membership_parent_account_user_id; ?>" method="post">
          <p>From&nbsp;<span style="color:#FF0000;font-weight:bold">*</span>
            <input type="text" id="org_report_start_date_<?php echo $membership_parent_account_user_id; ?>" name="org_report_start_date_<?php echo $membership_parent_account_user_id; ?>" class="report-start-date date_picker" placeholder="YYYY-MM-DD" readonly />
          </p>

          <p>To&nbsp;<span style="color:#FF0000;font-weight:bold">*</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="text" id="org_report_end_date_<?php echo $membership_parent_account_user_id; ?>" name="org_report_end_date_<?php echo $membership_parent_account_user_id; ?>" class="report-end-date date_picker" placeholder="YYYY-MM-DD" readonly />
          </p>

          <input type="submit" id="org_report_submit-<?php echo $membership_parent_account_user_id; ?>" name="org_report_submit-<?php echo $membership_parent_account_user_id; ?>" value="Show Report" />
        </form>

        <br>
        <form class="osir-survey-signup-url-qr-form" id="osir-survey-signup-url-qr-form-<?php echo $membership_parent_account_user_id; ?>" name="osir-survey-signup-url-qr-form-<?php echo $membership_parent_account_user_id; ?>" action="/osir-survey-signup-url-qr" method="post">
          <input type="hidden" id="qrsignupurl" name="qrsignupurl" value=<?php echo $my_ca->signup_url(); ?> />
          <input type="submit" id="osir-survey-signup-url-qr-submit-<?php echo $membership_parent_account_user_id; ?>" name="osir-survey-signup-url-qr-submit-<?php echo $membership_parent_account_user_id; ?>" value="Show QR Code" />
        </form>

        <br><a href="/osir-survey-analytics-<?php echo $membership_parent_account_user_id; ?>/" target="_blank">Show Analytics</a>

        <br><br>
        <a href="/wp-content/uploads/my_gravity_forms/download.php?export_survey_entries=1&parent_user_id=<?php echo $membership_parent_account_user_id; ?>">
          Export Data (CSV)</a>
      </td>
      <?php
    }
  }
}

// Setup short code on organization report word press back end page. Should only be shown on frontend
add_shortcode('organizationreport', 'show_organization_report'); 
function show_organization_report($atts){
  if( !is_admin() && is_user_logged_in() && isset($atts['gformid']) && isset($atts['parentuserid']) ) {
    $org_report_start_date_str = 'org_report_start_date_'.$atts['parentuserid'];
    $org_report_end_date_str = 'org_report_end_date_'.$atts['parentuserid'];

    // Required fields (Server validation)
    if ( !isset($_POST[$org_report_start_date_str]) || empty($_POST[$org_report_start_date_str]) || 
      !isset($_POST[$org_report_end_date_str]) || empty($_POST[$org_report_end_date_str]) ) {
      echo "<br><a href='/?action=subscriptions'>&#60;&#60;Go Back</a><br><br><p style='color:#FF0000;font-weight:bold;'>Error! Organization report is not available and reporting period is invalid. You might not have access to view this page</p>";
      return;
    } else {
      // Start date should fall after the end date (Server validation)
      $org_report_start_date = date_create($_POST[$org_report_start_date_str]);
      $org_report_end_date = date_create($_POST[$org_report_end_date_str]);
      $date_diff = date_diff($org_report_start_date, $org_report_end_date);

      // print_r($atts);
      // echo "<br>"; print_r($_POST);
      // echo "<br>".$date_diff->format("%R%a days");
      // echo "<br>".intval($date_diff->format("%R%a days"));

      if (intval($date_diff->format("%R%a days")) < 0) {
        echo "<br><a href='/?action=subscriptions'>&#60;&#60;Go Back</a><br><br><p style='color:#FF0000;font-weight:bold;'>Organization reporting period is invalid. Start date should be equal to or after end date and both are required fields</p>";
        return;
      }

      $resiliencyBehavioursAverageScore = calculateOrganizationAverageScore (
        'total_resiliency_behaviours_score', $atts['gformid'], $org_report_start_date, $org_report_end_date);
      $supportProgramsAverageScore = calculateOrganizationAverageScore (
        'total_support_programs_score', $atts['gformid'], $org_report_start_date, $org_report_end_date);
      $supportiveLeadershipAverageScore = calculateOrganizationAverageScore (
        'total_supportive_leadership_score', $atts['gformid'], $org_report_start_date, $org_report_end_date);
      $supportiveEnvironmentAverageScore = calculateOrganizationAverageScore (
        'total_supportive_environment_score', $atts['gformid'], $org_report_start_date, $org_report_end_date);

      $organizationTotalOSIRScore = calculateOrganizationTotalOSIRScore(
        'total_osir_score', $atts['gformid'], $org_report_start_date, $org_report_end_date);
      $organizationNumberSubmissions = calculateOrganizationNumberSubmissions(
        'is_survey_entry_submitted_by_user', $atts['gformid'], $org_report_start_date, $org_report_end_date);

      // Makesure there is actual report date for this reporting period
      if ( $organizationNumberSubmissions > 0 ) {
        $osirAverageGrandTotalScore = $organizationTotalOSIRScore / $organizationNumberSubmissions;
      } else {
        $osirAverageGrandTotalScore = 0;
      }

      // echo showPDFLinks();
      if ( $organizationNumberSubmissions == 0 ) {
        echo "<br><a href='/?action=subscriptions'>&#60;&#60;Go Back</a><br><br>No data was found for this reporting period ";
        echo "from: <b>".$_POST[$org_report_start_date_str]."</b> to: <b>".$_POST[$org_report_end_date_str]."</b>. ";
        echo "Please choose different start and end dates";
      } else {
        echo getOrganizationGenericMsg( 
          $resiliencyBehavioursAverageScore, $supportProgramsAverageScore, 
          $supportiveLeadershipAverageScore, $supportiveEnvironmentAverageScore,
          $org_report_start_date, $org_report_end_date);
        echo getOrganizationScalesMsg(
          $osirAverageGrandTotalScore, $resiliencyBehavioursAverageScore, 
          $supportProgramsAverageScore, $supportiveLeadershipAverageScore, 
          $supportiveEnvironmentAverageScore);
      }
    }
  }
}

// Show OSIR survey Sign-up URL QR short code only for admin & analytics roles 
add_shortcode('kaya_osir_survey_signup_url', 'show_survey_signup_url_qrcode_shortcode'); 
function show_survey_signup_url_qrcode_shortcode(){
  $cap = get_user_capability();

  if ( !is_admin() && is_user_logged_in() && isset($_POST['qrsignupurl']) && !empty($_POST['qrsignupurl']) ) {
    if ( !empty($cap['administrator']) || !empty($cap['pshsa_analytics_reports']) || !empty($cap['designer']) ) {
      $html_message  = '<br><a href="/?action=subscriptions">&#60;&#60;Go Back</a></p>';
      $html_message .= '<p>This QR code can be scanned to sign-up for the OSIR survey. Click on the button below to print the QR code.</p>';
      $html_message .= '<p>Alternatively, to download the QR code, right click the image below and click Save Image As...</p>';
      $osir_survey_signup_qr_shortcode = '[kaya_qrcode title="Survey Sign-up Link" title_align="aligncenter" ecclevel="L" color="#7a2531" align="aligncenter" css_shadow="1" url="'.$_POST['qrsignupurl'].'" content="'.$_POST['qrsignupurl'].'" new_window="1" alt="OSIR Survey Signup Link"][/kaya_qrcode]';
      $html_message .= '<div id="printableArea" style="display:none !important;color:#7a2531 !important;">'.do_shortcode($osir_survey_signup_qr_shortcode).'</div>';
      echo $html_message;
      echo '<div id="printableArea2">'.do_shortcode($osir_survey_signup_qr_shortcode).'</div>';
      echo '<br><p style="text-align:center;"><button class="kaya-osir-signup-print-btn">Print QR Code</button></p>';
    }
  }
}

// Show Gravity Form survey title, should be different among different surveys pages
add_shortcode('show_gform_title', 'show_gform_title_shortcode'); 
function show_gform_title_shortcode($atts){
  if ( !is_admin() && is_user_logged_in() && isset($_GET['my_gform_id']) && $_GET['my_gform_id'] > 0 && isset($atts['title']) ) {
    echo '<h2 class="gform_title">'.$atts['title'].'</h2>';
  }
}

// Change memberpress password strength meter default message
add_filter( 'mepr-password-meter-text', 'password_meter_custom_message', 10, 2 ); 
function password_meter_custom_message( $txt, $enforce_strong_password ) {
  if ($enforce_strong_password) {
    $tooltipText = 
      '<p class="tooltipStrongPwd">Strong passwords should have:</p><ul class="tooltipStrongPwd">'.
      '<li>At least 8 characters, the more characters, the better</li>'.
      '<li>A mixture of both uppercase and lowercase letters</li>'.
      '<li>A mixture of letters and numbers</li>'.
      '<li>Inclusion of at least one special character, e.g., ! @ # ? ]</li>'.
      '<li>Note: Do not use special characters < or ></li></ul>'.

      '<p class="tooltipWeakPwd">Examples of weak passwords:</p><ul class="tooltipWeakPwd">'.
      '<li>Any word that can be found in a dictionary, in any language (e.g., airplane or aeroplano)</li>'.
      '<li>A dictionary word with some letters simply replaced by numbers (e.g., a1rplan3 or aer0plan0)</li>'.
      '<li>A repeated character or a series of characters (e.g., AAAAA or 12345)</li>'.
      '<li>A keyboard series of characters (e.g., qwerty or poiuy)</li>'.
      '<li>Personal information (e.g., birthdays, names of pets or friends, Social Security number, addresses)</li>'.
      '<li>Anything written down and stored somewhere near your computer</li></ul>';
  
    // https://www.htmlsymbols.xyz/unicode/U+2139
    $html_message =
      '<span class="tooltip">&#8505;<span class="tooltiptext">'.$tooltipText.'</span></span>'.
      ' Password must have combination of letters, numbers, and special characters and should be very strong';
    return $html_message;
  }
}

// Change Memberpress login page (MyAccount) Username/Email label
add_filter('mepr-login-uname-or-email-str', 'mepr_login_uname_or_email_str');
function mepr_login_uname_or_email_str() {
  $tooltipText = '<p class="tooltipMeprLoginUnameLabel">Note that emails are '.
    'used only to establish a survey user and are not stored or connected with your individual '.
    'responses. Your responses are not attributed and kept entirely confidential</p>';
  $html_message =
      '<span class="tooltip">&#8505;<span class="tooltiptext">'.$tooltipText.'</span></span>';
  return 'Email address '.$html_message;
}

// Change Memberpress Login page (MyAccount) validation error messages
add_filter('mepr-validate-login', 'mepr_validate_login');
function mepr_validate_login($errors) {
  if (($key = array_search('Username must not be blank', $errors)) !== false) {
    unset($errors[$key]);
    $errors[$key] = 'Email address must not be blank';
  }

  if (($key = array_search('Your username or password was incorrect', $errors)) !== false) {
    unset($errors[$key]);
    $errors[$key] = 'Your email address or password was incorrect';
  }

  // print_r($errors);
  return $errors;
}

// Validate Memberpress Sign Up page (MyAccount) validation error messages
add_filter('mepr-validate-signup', 'mepr_validate_signup');
function mepr_validate_signup($errors) {
  $pattern = '/^This email address has already been used. If you are an existing user/i';

  if (isset($errors['user_email']) && preg_match($pattern, $errors['user_email'])) { 
    unset($errors['user_email']);
    $errors['user_email'] = 'This email address has already been used. If you have already created an account, please <a href="/">Login</a>';
  } 

  return $errors;
}

// Translate labels defined inside account/home and membership sign-up pages
add_filter( 'gettext_with_context', 'my_account_home_and_membership_strings', 20, 4 );
function my_account_home_and_membership_strings( $translation, $text, $context, $domain ) {
  if ($domain === 'memberpress' && $context === 'ui') {
    switch ( $translation ) {
      case 'Email:*' :
        $tooltipText = '<p class="tooltipMeprLoginUnameLabel">Note that emails are '.
        'used only to establish a survey user and are not stored or connected with your individual '.
        'responses. Your responses are not attributed and kept entirely confidential</p>';
        $html_message = '<span class="tooltip">&#8505;<span class="tooltiptext">'.$tooltipText.'</span></span>';

        return _x( 'Email address '.$html_message, 'ui', 'memberpress' );
        // return __( 'Email address '.$html_message, 'memberpress' );
        break;
      case 'Save Profile' :
        return _x( 'Save', 'ui', 'memberpress' );
        break;
    }
  }
  
	return $translation;
}

// Do not show Signup button if user already subscribed to membership
add_filter( 'mepr-can-you-buy-me-override', 'mepr_can_you_buy_me_override_func', 10, 2 );
function mepr_can_you_buy_me_override_func($null, $product) {
  $cap = get_user_capability();

  // Bypass Member press admin role, which always allow signup (mepr-admin-capability: remove_users bug)
  if ( !empty($cap['administrator']) || !empty($cap['pshsa_analytics_reports']) || !empty($cap['corporate_parent_account_moderator']) || !empty($cap['gf_moderator']) || !empty($cap['designer']) ) {
    return false;
  }
  return null;
}

// Add Header to Demographics Survey Question and disable corresponding description (MLITSD)
// https://docs.gravityforms.com/gform_field_choice_markup_pre_render/
add_filter( 'gform_field_choice_markup_pre_render_21', 'gform_field_choice_markup_pre_render_select_formatted', 10, 4);
function gform_field_choice_markup_pre_render_select_formatted( $choice_markup, $choice, $field, $value ) {
  if ( $field->get_input_type() == 'select' ) {
    if ($choice['value'] == '') {
      $search = array("<option");
      $replace = array("<option disabled='disabled'");
      $choice_markup = str_replace( $search, $replace, $choice_markup );
    }
    
    // echo "<br><br>choice_markup: "; var_dump($choice_markup);
  }

  return $choice_markup;
}

// get logged-in user capability
function get_user_capability() {
  if ( !is_user_logged_in() ) {
    return '';
  }

  $user = MeprUtils::get_currentuserinfo();
  $cap = get_user_meta( $user->ID, 'wp_capabilities', true );

  if ( is_array($cap) ) {
    return $cap;
  }

  return '';
}

//--------------------------------------------------------------------------------------

// Login with email instead of username (Memberpress/ Word press core):
// https://docs.memberpress.com/article/329-filter-hooks-in-memberpress#mepr-login-uname-or-email-str
// https://docs.memberpress.com/article/329-filter-hooks-in-memberpress#mepr-validate-signup
// https://developer.wordpress.org/reference/functions/wp_authenticate_email_password/
// https://docs.memberpress.com/article/325-action-hooks-in-memberpress

// Translate with/out context strings:
// https://developer.wordpress.org/reference/hooks/gettext_with_context/
// https://developer.wordpress.org/reference/functions/_x/   // retrieve only (with context)
// https://developer.wordpress.org/reference/functions/_ex/  // echo (with context)
// https://developer.wordpress.org/reference/hooks/gettext/
// https://developer.wordpress.org/reference/functions/__/


/*

add_shortcode('memberonly', 'member_only_shortcode'); 
function member_only_shortcode($atts){
  if ( !is_user_logged_in() ) {
    return '<h4>PLEASE LOGIN TO YOUR ACCOUNT</h4>';
  }
}

add_filter('mepr-admin-capability', 'mepr_admin_capability');  
function mepr_admin_capability($cap) {
  return $cap;
}

*/

// https://docs.gravityforms.com/gform_field_validation/
// https://ristrettoapps.com/downloads/gravity-press/
// https://members-plugin.com/docs/snippets/
