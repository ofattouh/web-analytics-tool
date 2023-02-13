<?php

class Stla_Checkbox_Radio_Update {

	const PLUGIN_NAME = 'Checkbox & Radio Inputs Styler';
	const PLUGIN_FILE_NAME = 'styles-layouts-gf-checkbox-radio';
	const PLUGIN_FOLDER_NAME = 'styles-layouts-gf-checkbox-radio';
	const ITEM_ID = 15080;
	const LICENSE_SETTING_KEY = 'gravity_forms_checkbox_radio_addon_license';
	const LICENSE_SETTING_STATUS = 'gravity_forms_checkbox_radio_license_status';
	const LICENSE_PAGE = 'stla_licenses';

	// Stores license status .. changed everytime license key is changed.
	public $license_status = 'invalid';

	function __construct() {
		//set_site_transient( 'update_plugins', null );

		// Hook specific to Booster for WPForms
		add_action( 'stla-license-fields', array( $this, 'add_license_input_field' ) ); 

		add_filter( 'pre_update_option_'.self::LICENSE_PAGE , array( $this, 'activate_license' ), 10, 3  );
		add_action( 'admin_init', array( $this, 'check_update' ) );
		add_action( 'in_plugin_update_message-'.self::PLUGIN_FOLDER_NAME.'/'.self::PLUGIN_FILE_NAME.'.php', array( $this, 'plugin_update_message_suffix') );
	}

	/**
	 * Append to plugin update message
	 *
	 * @param [type] $license
	 * @return void
	 */
	 function plugin_update_message_suffix() {

		$settings = get_option( self::LICENSE_PAGE );
		$license_status = get_option( self::LICENSE_SETTING_STATUS );

		switch( $license_status ) {

			case 'valid':
				break;

			case 'expired':
				echo '<span class="update-message"> <a href="'.GF_STLA_STORE_URL.'/checkout/?edd_license_key='.$settings[self::LICENSE_SETTING_KEY].'&download_id='.self::ITEM_ID.'" target="_blank"><strong>Renew your license key at special discounted price</strong></a></span>';
				break;

			default:
				echo '<span class="update-message"> <a href="'.admin_url( 'admin.php?page='.self::LICENSE_PAGE ) .'"><strong>Enter/Purchase valid license key</strong></span>';
				break;
		}
	 }

	/**
	 * Add license setting Field
	 *
	 * @author Sushil Kumar
	 * @since  1.0
	 * @param [type]  $license
	 */
	function add_license_input_field( $license ) {

		add_settings_field(
			self::LICENSE_SETTING_KEY,
			self::PLUGIN_NAME.' License',
			array( $this, 'license_input_field_callback' ),
			self::LICENSE_PAGE,
			self::LICENSE_PAGE.'_section'
		);
	}

	/**
	 * Show field
	 *
	 * @author Sushil Kumar
	 * @since  1.0
	 * @return [type]
	 */
	function license_input_field_callback() {

		$settings = get_option( self::LICENSE_PAGE );
		$addon_license= !empty( $settings[self::LICENSE_SETTING_KEY] )?$settings[self::LICENSE_SETTING_KEY]:'';
		$license_status = get_option( self::LICENSE_SETTING_STATUS );

		// Render the output
		echo '<input type="text" class="regular-text" id="url" name="'.self::LICENSE_PAGE.'['.self::LICENSE_SETTING_KEY.']" value="' . $addon_license. '" />';


		if ( $license_status === 'valid' ) { ?>
                 <img  style="vertical-align:middle" src="<?php echo GF_STLA_URL.'/css/images/active.png' ?>"> Active
<?php    }
		elseif ( $license_status === 'expired' ) { ?>
                 <img  style="vertical-align:middle" src="<?php echo GF_STLA_URL.'/css/images/inactive.png' ?>"> <strong>Expired</strong>: <a href="<?php echo GF_STLA_STORE_URL.'/checkout/?edd_license_key='.$settings[self::LICENSE_SETTING_KEY].'&download_id='.self::ITEM_ID ?>" target="_blank">Renew at special discounted price</a>
<?php	}
		else { ?>
			<img  style="vertical-align:middle" src="<?php echo GF_STLA_URL.'/css/images/inactive.png' ?>"> <strong>Inactive</strong>: <a href="<?php echo GF_STLA_STORE_URL.'/?p='.self::ITEM_ID ?>" target="_blank">Buy new license</a> OR <a href="<?php echo GF_STLA_STORE_URL.'/my-account/' ?>" target="_blank"> Get your purchased license key</a>
<?php	}
	}

	/**
	 * Check if license key is valid or not
	 */
	function check_license( $license ) {

		$api_params = array(
			'edd_action' => 'check_license',
			'license' => $license,
			'item_id' => self::ITEM_ID,
			'url' => home_url()
		);
		$response = wp_remote_post( GF_STLA_STORE_URL, array( 'body' => $api_params, 'timeout' => 15, 'sslverify' => false ) );

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$license_data = json_decode( wp_remote_retrieve_body( $response ) );
		
		return $license_data->license; // status of license
	}


	// Activate License
	function activate_license( $value, $old_value, $option ) {
		if ( !empty( $value[self::LICENSE_SETTING_KEY] ) ) {

			// Activate the license if needed
			// data to send in our API request
			$api_params = array(
				'edd_action'=> 'activate_license',
				'license'  => $value[self::LICENSE_SETTING_KEY],
				'item_id' => self::ITEM_ID,
			);

			// Call the custom API.
			$response = wp_remote_get( add_query_arg( $api_params, GF_STLA_STORE_URL ), array( 'timeout' => 15, 'sslverify' => false ) );

			// make sure the response came back okay
			if ( is_wp_error( $response ) )
				return false;

			// decode the license data
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			// $license_data->license will be either "active" or "inactive"
			update_option( self::LICENSE_SETTING_STATUS, $license_data->license );
		}

		return $value;
	}

	/**
	 * Check for plugin updates
	 *
	 * @return void
	 */
	function check_update() {

		$settings = get_option( self::LICENSE_PAGE );
		$addon_license = false;
		$license_status = get_option( self::LICENSE_SETTING_STATUS );

		if ( isset( $settings[self::LICENSE_SETTING_KEY] ) ) {

			// get transient
			$transient_license_key = get_transient( self::LICENSE_SETTING_KEY );

			// database and transient have different license keys then update transient
			if ( $settings[self::LICENSE_SETTING_KEY] !== $transient_license_key  ) {

				// clear plugins update cache .. useful because sometimes it doesn't let user update
				// similar issue explained here https://support.advancedcustomfields.com/forums/topic/automatic-update-is-unavailable-for-this-plugin
				wp_clean_plugins_cache();

				$license_status = $this->check_license( $settings[self::LICENSE_SETTING_KEY] );
	
				// Set the new key in transinet as well
				set_transient( self::LICENSE_SETTING_KEY, $settings[self::LICENSE_SETTING_KEY], 86400 );
	
				// Update license status in database as well
				update_option( self::LICENSE_SETTING_STATUS, $license_status );
	
			}
			$addon_license = $license_status === 'valid' ? $settings[self::LICENSE_SETTING_KEY] : false;
		}

		// Now check if update is available
		$main_file = WP_PLUGIN_DIR . "/" . basename( dirname( __FILE__ ) ).'/'.self::PLUGIN_FILE_NAME.'.php';

		// setup the updater
		if( class_exists( 'EDD_SL_Plugin_Updater' ) && defined( 'GF_STLA_STORE_URL' ) ) {
			$edd_updater = new EDD_SL_Plugin_Updater( GF_STLA_STORE_URL, $main_file, array(
					'version'  => STLA_CHECKBOX_RADIO_VERSION,
					'license'  => $addon_license,
					'item_id' => self::ITEM_ID,
					'author'  => 'Sushil Kumar'
				)
			);
		}
	}
}

new Stla_Checkbox_Radio_Update();
