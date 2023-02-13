<?php
/*
Plugin Name: Checkbox & Radio Inputs Styler for Gravity Forms
Plugin URI:  https://wpmonks.com/downloads/checkbox-radio-styler-for-gravity-forms/
Description: Add Icons, Images or styles to Gravity Forms checkbox and radio inputs
Version:     1.3
Author:      Sushil Kumar
Author URI:  https://wpmonks.com/
License:     GPL2License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

// don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

//file to send automatic updates
include_once WP_PLUGIN_DIR . "/" . basename( dirname( __FILE__ ) ).'/update.php';

//set constants for plugin directory and plugin url
define( "STLA_CHECKBOX_RADIO_DIR", WP_PLUGIN_DIR . "/" . basename( dirname( __FILE__ ) ) );
define( "STLA_CHECKBOX_RADIO_URL", plugins_url() . "/" . basename( dirname( __FILE__ ) ) );
define( "STLA_CHECKBOX_RADIO_VERSION", "1.3" );

class Sk_Stla_Checkbox_Radio { 

	public function __construct() {

		//modify  radio, and checkbox fields
		add_filter( 'gform_field_content', array( $this, 'gform_field_content' ), 10, 5 );
		add_action( 'gf_stla_add_theme_section', array($this, 'customizer_controls'), 14, 2);
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'stla_radio_checkbox_customizer' ) );
		add_action( 'gform_enqueue_scripts', array( $this, 'gform_enqueue_scripts' ), 10, 2 );

		// Alert if plugin dependencies are missing
		add_action( 'admin_notices', array( $this, 'check_plugin_dependencies' ) );
	}

	/**
	 * Enqueue Gravity Forms frontend scripts
	 *
	 * @param [object] $form
	 * @param [boolean] $is_ajax
	 * @return void
	 */
	public function gform_enqueue_scripts( $form, $is_ajax ) {

		// is_admin doesn't work in gutenberg. used REST_REQUEST for this
        if (! is_admin() && ! defined('REST_REQUEST')) {
			wp_enqueue_style('stla_custom_checkbox_radio_fontawesome', STLA_CHECKBOX_RADIO_URL.'/css/fontawesome/all.css', '', STLA_CHECKBOX_RADIO_VERSION );
			
			$form_id = $form['id'];
			$settings = get_option( 'gf_stla_checkbox_radio_' . $form_id );

			$checkbox_enabled = isset( $settings['checkbox']['enabled'] ) ? $settings['checkbox']['enabled'] : false;
			$radio_enabled = isset( $settings['radio']['enabled'] ) ? $settings['radio']['enabled'] : false;
			if ( ! $checkbox_enabled  && ! $radio_enabled ) {
				return;
			}

			wp_enqueue_style( 'stla_checkbox_radio_lib_', STLA_CHECKBOX_RADIO_URL . '/css/pretty-checkbox.css', '', STLA_CHECKBOX_RADIO_VERSION );
			wp_enqueue_style( 'stla_checkbox_radio_custom_css', STLA_CHECKBOX_RADIO_URL . '/css/custom.css', '', STLA_CHECKBOX_RADIO_VERSION );
			include 'css/style.php';
        }

	}

	/**
	 * Enqueue Customizer scripts
	 *
	 * @return void
	 */
	function stla_radio_checkbox_customizer(){
		$current_form_id = get_option( 'gf_stla_select_form_id' );
		wp_enqueue_script( 'stla_checkbox_radio_customizer_script', STLA_CHECKBOX_RADIO_URL . '/js/customizer-control.js', array( 'jquery' ), STLA_CHECKBOX_RADIO_VERSION, true );
		wp_localize_script( 'stla_checkbox_radio_customizer_script', 'stla_checkbox_radio_current_form', array( 'formId' =>  $current_form_id ) );
	}

	/**
	 * modify the html structure of checkbox and radio fields
	 *
	 * @param [string] $content
	 * @param [object] $field
	 * @param [string] $value
	 * @param [string] $lead_id
	 * @param [string] $form_id
	 * @return void
	 */
	function gform_field_content( $content, $field, $value, $lead_id, $form_id ) {

		// Don't run in backend
        if ( is_admin() ||  defined('REST_REQUEST') ) {
			return $content;
        }

		$checkbox_fields = array( 'checkbox', 'post_tags', 'post_custom_field', 'post_category', 'quantity', 'option', 'product', 'survey' );
		$radio_fields = array( 'radio', 'post_tags', 'post_custom_field', 'post_category', 'option', 'shipping', 'product', 'survey' );
		$checkbox_radio_settings = get_option( 'gf_stla_checkbox_radio_'.$form_id );

		$checkbox_settings = isset( $checkbox_radio_settings['checkbox'] ) ? $checkbox_radio_settings['checkbox']: false;
		$checkbox_enabled = isset( $checkbox_settings['enabled'] ) ? $checkbox_settings['enabled'] : false;

		$radio_settings = isset( $checkbox_radio_settings['radio'] ) ? $checkbox_radio_settings['radio']: false;
		$radio_enabled = isset( $radio_settings['enabled'] ) ? $radio_settings['enabled'] : false;



		// Not checkbox or radio field then don't modify
		if( ! in_array( $field->type, $checkbox_fields )  && ! in_array( $field->type, $radio_fields ) ) {
			return $content;
		}

		// setting not enabled for this particular type of field.
		if( ( in_array( $field->type, $checkbox_fields ) && ! $checkbox_enabled ) || ( in_array( $field->type, $radio_fields ) && ! $radio_enabled ) ){
			return $content;
		}

		
		$checkbox_type = isset( $checkbox_settings['type'] ) ? $checkbox_settings['type'] : 'default';
		$checkbox_icon_library = isset( $checkbox_settings['type'] ) ? $checkbox_settings['type'] : 'fontawesome';
		$checkbox_icon = isset( $checkbox_settings['fontawesome-icon'] ) ? $checkbox_settings['fontawesome-icon'] : 'none';
		$checkbox_image = isset( $checkbox_settings['image'] ) ? $checkbox_settings['image'] : 'none';
		$checkbox_shape = isset( $checkbox_settings['shape'] ) ? $checkbox_settings['shape'] : 'default';
		$checkbox_style = isset( $checkbox_settings['style'] ) ? $checkbox_settings['style'] : 'default';
		$checkbox_checked_color = isset( $checkbox_settings['checked-color'] ) ? $checkbox_settings['checked-color'] : 'bdc3c7';
		$checkbox_unchecked_color = isset( $checkbox_settings['unchecked-color'] ) ? $checkbox_settings['unchecked-color'] : 'bdc3c7';
		$checkbox_animation = isset( $checkbox_settings['animation'] ) ? $checkbox_settings['animation'] : 'smooth';
		$checkbox_size = isset( $checkbox_settings['animation'] ) ? $checkbox_settings['animation'] : 'inherit';
		$checkbox_switch_type = isset( $checkbox_settings['switch'] ) ? $checkbox_settings['switch'] : 'outline';
		
	
		$classes = '';

		switch($checkbox_type) {
			case 'icon':
				$classes .= ' p-icon';
				if( $checkbox_shape !== 'default' ) {
					$classes .= ' p-'.$checkbox_shape;
				}
				break;

			case 'default':
			$classes .= ' p-default';
			if( $checkbox_style !== 'default' ) {
				$classes .= ' p-'.$checkbox_style;
			}
			if($checkbox_shape !== 'default'){
				$classes .= ' p-'.$checkbox_shape;
			}
			break;

			case 'switch':
				$classes .= ' p-switch';
				if( $checkbox_switch_type !== 'outline' ) {
					$classes .= ' p-'. $checkbox_switch_type;
				}
			break;

			case 'image':
				$classes .= ' p-image p-plain';
			break;
		}
		$classes .= ' p-'. $checkbox_animation;

		// Radio Settings
		
		$radio_type = isset( $radio_settings['type'] ) ? $radio_settings['type'] : 'default';
		$radio_shape = isset( $radio_settings['shape'] ) ? $radio_settings['shape'] : 'default';
		$radio_style = isset( $radio_settings['style'] ) ? $radio_settings['style'] : '';
		$radio_library = isset( $radio_settings['icon-library'] ) ? $radio_settings['icon-library'] : '';
		$radio_icon = isset( $radio_settings['fontawesome-icon'] ) ? $radio_settings['fontawesome-icon'] : '';
		$radio_switch_type = isset( $radio_settings['switch_type'] ) ? $radio_settings['switch_type'] : '';
		$radio_unchecked_color = isset( $radio_settings['uncheck-color'] ) ? $radio_settings['uncheck-color'] : '';
		$radio_checked_color = isset( $radio_settings['checked-color'] ) ? $radio_settings['checked-color']  : '';
		$radio_size = !empty($radio_settings['size'] ) ? $radio_settings['size'] : ''; 
		$radio_animation = isset( $radio_settings['animation'] ) ? $radio_settings['animation'] : 'smooth';

		switch( $radio_type ){
			case 'default':
			$radio_classes = 'p-default';
				if( $radio_shape !== 'default' ) {
					$radio_classes .= ' p-'. $radio_shape;
				}
				if($radio_style !== 'default' ) {
					$radio_classes .= ' p-'. $radio_style;
				}
			break;

			case 'icon':
			$radio_classes = 'p-icon';
			if( $radio_shape !== 'default' ) {
				$radio_classes .= ' p-'. $radio_shape;
			}
			break;

			case 'switch':
				$radio_classes = 'p-switch';
				if( $radio_switch_type !== 'outline' ) {
					$radio_classes .= ' p-'. $radio_switch_type;
				}
			break;
		}
		$radio_classes .= ' p-'. $radio_animation;

		// checkbox settings
		$dom = new DOMDocument;
		$content = str_replace ('&nbsp;', '@nbsp;', $content);
		$content = str_replace ('&', '@amp;', $content);
		$dom->loadHTML( $content );

		$form = GFAPI::get_form( $form_id );
		//comparing gf version with old version
		$gf_version_info = GFForms::$version;
		$has_old_gf = version_compare( $gf_version_info, '2.5', '<' );

		include STLA_CHECKBOX_RADIO_DIR . '/includes/checkbox.php';
		include STLA_CHECKBOX_RADIO_DIR . '/includes/radio.php';
		
		$content = preg_replace('~<(?:!DOCTYPE|/?(?:html|body))[^>]*>\s*~i', '', $content);
		$content = str_replace ('@nbsp;', '&nbsp;', $content);
		$content = str_replace ('@amp;', '&', $content);
		return $content;

	}	
	
	public function customizer_controls($wp_customize, $current_form_id){
		// checkbox type option. 
		$checkbox_type = array(
			'default' => 'Basic',
			'icon' => 'Icon',
			'image' => 'Image',
			'switch' => 'Switch'
		);
		$radio_type = array(
			'default' => 'Basic',
			'icon' => 'Icon',
			'switch' => 'Switch'
		);

		$basic_checkbox_type = array(
			'default' => 'Default',
			'curve' => 'Curve',
			'round' => 'Round'
		);
		$basic_checkbox_style = array(
			'default' => 'Default',
			'fill' => 'Fill',
			'thick' => 'Thick'
		);
		$animations = array(
			'smooth' => 'Smooth',
			'jelly' => 'Jelly',
			'tada' => 'Tada',
			'rotate' => 'Rotate',
			'pulse' => 'Pulse'
		);
		$switch_type = array(
			'outline' => 'Outline',
			'fill' => 'Fill',
			'slim' => 'Slim'
		);

		$wp_customize->add_section( 'gf_stla_custom_checkbox' , array(
			'title' => 'Custom Checkbox',
			'panel' => 'gf_stla_panel',
		) );
		$wp_customize->add_section( 'gf_stla_custom_radio' , array(
			'title' => 'Custom Radio',
			'panel' => 'gf_stla_panel',
		) );
		include 'includes/icon-choice/fontawesome-icon-list.php';
		include 'customizer-controls/checkbox-controls.php';
		include 'customizer-controls/radio-controls.php';
		

	}

	/**
	 * Check if Styles & Layouts is installed or not
	 *
	 * @return void
	 */
	function check_plugin_dependencies() {
		if ( ! class_exists( 'Gravity_customizer_admin' ) ) {
			$class = 'notice notice-error';
			$styles_layouts_url = admin_url( 'plugin-install.php?s=styles+and+layouts+for+gravity+forms&tab=search&type=term');
			$message = '<a href="'.$styles_layouts_url.'">Styles & Layouts for Gravity Forms </a>is not installed. <strong>Checkbox & Radio Styler</strong> can\'t work without it.';

			printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ),  $message  );
		}
	}

} // Class ends here

add_action( 'plugins_loaded', 'sk_stla_checkbox_radio');

function sk_stla_checkbox_radio() {
	new Sk_Stla_Checkbox_Radio();
}