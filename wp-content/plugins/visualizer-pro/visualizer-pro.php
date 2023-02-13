<?php
/**
 * Plugin Name: Visualizer: Tables and Charts Manager for WordPress AddOn
 * Plugin URI: http://themeisle.com/plugins/visualizer-charts-and-graphs/
 * Description: This addon enables the pro functions of WordPress Visualizer plugin.
 * Version: 1.12.1
 * Author: ThemeIsle
 * Author URI: https://themeisle.com
 * WordPress Available:  no
 * Requires License:    yes
 */
define( 'Visualizer_Pro_ABSURL', plugins_url( '/', __FILE__ ) );
define( 'Visualizer_Pro_PATH', realpath( dirname( __FILE__ ) ) );
define( 'VISUALIZER_PRO_VERSION', '1.12.1' );
define( 'VISUALIZER_PRO_BASEFILE', __FILE__ );
define( 'VISUALIZER_PRO_DIRNAME', basename( dirname( VISUALIZER_PRO_BASEFILE ) ) );
/**
 * Run the visualizer pro code.
 */
function run_visualizer_pro() {
	require dirname( __FILE__ ) . '/inc/addon.php';
	require dirname( __FILE__ ) . '/inc/wrappers.php';
	$vendor_file = Visualizer_Pro_PATH . '/vendor/autoload.php';
	if ( is_readable( $vendor_file ) ) {
		include_once( $vendor_file );
	}

	add_filter( 'themeisle_sdk_products', 'visualizer_pro_register_sdk', 10, 1 );

	add_filter(
		'themesle_sdk_namespace_' . md5( __FILE__ ),
		function ( $namespace ) {
			return 'visualizer';
		}
	);

	add_filter(
		'themeisle_sdk_compatibilities/' . VISUALIZER_PRO_DIRNAME,
		function ( $compatibilities ) {
			$compatibilities['Visualizer'] = array(
				'basefile'  => defined( 'VISUALIZER_BASEFILE' ) ? VISUALIZER_BASEFILE : '',
				'required'  => '3.6',
				'tested_up' => '3.9',
			);
			return $compatibilities;
		}
	);
}

/**
 * Registers with the Themeisle SDK
 *
 * @since    1.0.0
 */
function visualizer_pro_register_sdk( $products ) {
	$products[] = VISUALIZER_PRO_BASEFILE;
	return $products;
}

run_visualizer_pro();

