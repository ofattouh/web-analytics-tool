<?php
if ( ! class_exists( 'Visualizer_Pro' ) ) {
	define( 'Visualizer_Pro_Name', 'visualizer' );
	define( 'Visualizer_Pro_Debug', false );

	/**
	 * Class Visualizer_Pro
	 *
	 * Bootstrap the pro features
	 */
	class Visualizer_Pro {

		/**
		 * Check if we have free version installed
		 *
		 * @var bool
		 */
		private $has_free_installed = false;

		/**
		 * The default properties of the post.
		 *
		 * @var array
		 */
		// @codingStandardsIgnoreStart
		private static $POST_DEFAULT_PROPERTIES = array(
			'post_title'    => 'string',
			'post_status'   => 'string',
			'comment_count' => 'int',
			'post_date'     => 'date',
			'post_modified' => 'date',
		);
		// @codingStandardsIgnoreEnd

		/**
		 * The list of aggregator functions.
		 *
		 * @var array
		 */
		// @codingStandardsIgnoreStart
		private static $AGGREGATOR_FUNCTIONS = array(
			'sum',
			'avg',
			'count',
		);
		// @codingStandardsIgnoreEnd

		/**
		 * List of PDOs.
		 *
		 * @var array
		 */
		// @codingStandardsIgnoreStart
		private static $DB_PDO_COLLECTION = array(
			'IBM DB2' => 'ibm',
			'MySQL' => 'mysql',
			'MS SQL' => 'mssql',
			'Oracle' => 'oci',
			'PostgreSQL' => 'pgsql',
			'SQLite3' => 'sqlite',
		);
		// @codingStandardsIgnoreEnd

		/**
		 * List of vendor specific DB libraries.
		 *
		 * @var array
		 */
		// @codingStandardsIgnoreStart
		private static $DB_VENDOR_COLLECTION = array(
			'IBM DB2' => 'ibm_db2',
			'MySQL' => array( 'php_mysql', 'php_mysqli' ),
			'MS SQL' => 'php_mssql',
			'Oracle' => 'php_oci8',
			'PostgreSQL' => 'php_pgsql',
			'SQLite3' => 'php_sqlite3',
		);
		// @codingStandardsIgnoreEnd

		/**
		 *Action used for fetching the data
		 */
		const ACTION_FETCH_DATA = 'visualizer-fetch-data';

		/**
		 *Action used for fetching post type properties
		 */
		const ACTION_FETCH_POST_TYPE_PROPS = 'visualizer-fetch-post-type-props';

		/**
		 *Action used for fetching post type data
		 */
		const ACTION_FETCH_POST_TYPE_DATA = 'visualizer-fetch-post-type-data';

		/**
		 *Action used for checking db connection.
		 */
		const ACTION_DB_CHECK_CONN = 'visualizer-db-check-conn';

		/**
		 * Custom meta field used to store permissions.
		 */
		const CF_PERMISSIONS        = 'visualizer-permissions';


		/**
		 * Visualizer_Pro constructor.
		 */
		function __construct() {
			register_activation_hook( VISUALIZER_PRO_BASEFILE, array( $this, 'activate' ) );
			register_deactivation_hook( VISUALIZER_PRO_BASEFILE, array( $this, 'deactivate' ) );
			$this->loadHooks();
		}

		/**
		 * Load all pro hooks
		 */
		private function loadHooks() {
			add_action( 'admin_init', array( $this, 'adminInit' ) );
			add_action( 'visualizer_pro_debug', array( __CLASS__, 'writeDebug' ) );
			add_action( 'wp_ajax_' . self::ACTION_FETCH_DATA, array( $this, 'fetchData' ) );
			add_action( 'wp_ajax_' . self::ACTION_FETCH_POST_TYPE_PROPS, array( $this, 'fetch_post_type_properties' ) );
			add_action( 'wp_ajax_' . self::ACTION_FETCH_POST_TYPE_DATA, array( $this, 'fetch_post_type_data' ) );
			add_action( 'visualizer_schedule_import', array( $this, 'updateScheduledChart' ) );
			add_action( 'visualizer_schedule_import', array( $this, 'updateScheduledJson' ) );
			add_action( 'visualizer_handle_permissions', array( $this, 'handlePermissions' ), 10, 1 );
			add_action( 'visualizer_pro_frontend_load_resources', array( $this, 'loadFrontendResources' ) );
			add_action( 'visualizer_pro_new_chart_defaults', array( $this, 'createNewChart' ), 10, 1 );
			add_action( 'visualizer_chart_settings', array( $this, 'moreChartSettings' ), 10, 4 );
			add_action( 'visualizer_save_filter', array( $this, 'saveFilter' ), 10, 2 );

			add_filter( 'visualizer_pro_chart_types', array( $this, 'chartTypes' ) );
			add_filter( 'visualizer_pro_chart_type_sidebar', array( $this, 'chartTypeSidebar' ), 10, 2 );
			add_filter( 'visualizer_pro_upsell', array( $this, 'upsell' ), 9, 2 );
			add_filter( 'visualizer_pro_upsell_class', array( $this, 'upsell' ), 9, 2 );
			add_filter( 'visualizer_pro_handle_chart_data', array( $this, '_handleChartData' ), 10, 4 );
			add_filter( 'visualizer_pro_chart_schedule', array( $this, 'updateSchedule' ), 10, 3 );
			add_filter( 'visualizer_pro_remove_schedule', array( $this, 'deleteSchedule' ), 10, 1 );
			add_filter( 'visualizer_is_business', array( $this, 'visualizer_is_business' ), 10, 1 );
			add_filter( 'visualizer_pro_logger_data', array( $this, 'getLoggerData' ), 10, 1 );
			add_filter( 'visualizer_pro_add_actions', array( $this, 'addActions' ), 10, 2 );
			add_filter( 'visualizer_action_buttons', array( $this, 'addActionButtons' ), 10, 1 );
			add_filter( 'visualizer_action_data', array( $this, 'handleAction' ), 10, 5 );
			add_filter( 'visualizer_pro_show_chart', array( $this, 'checkViewChartPermission' ), 10, 2 );
			add_filter( 'visualizer_pro_get_permissions', array( $this, 'getPermissions' ), 10, 1 );
			add_filter( 'visualizer_pro_get_permissions_data', array( $this, 'getPermissionsDataFilter' ), 10, 2 );

			add_filter( 'visualizer_load_chart', array( $this, 'loadChartTypes' ), 10, 2 );

			add_action( 'after_setup_theme', array( $this, 'loadDependencies' ), 999 );

			add_filter( 'visualizer_chart_schedules', array( $this, 'addSchedules' ), 10, 2 );

			// 1.9.0 onwards
			add_filter(
				'visualizer_is_pro',
				function () {
					$status = apply_filters( 'visualizer_pro_license_status', false );
					if ( $status !== 'valid' ) {
						return false;
					}

					return true;
				}
			);
			add_action( 'visualizer_add_scripts', array( $this, '_addScriptsAndStyles' ), 10, 1 );
			add_action( 'visualizer_enqueue_scripts_and_styles', array( $this, '_enqueueScriptsAndStyles' ), 10, 2 );
			add_action( 'visualizer_add_update_hook', array( $this, '_addUpdateHook' ), 10, 2 );
			add_action( 'visualizer_add_editor_etc', array( $this, '_addEditor' ), 10, 1 );
			add_action( 'visualizer_add_editor_etc', array( $this, '_addFilterWizard' ), 11, 1 );

			// db query
			add_action( 'wp_ajax_' . self::ACTION_DB_CHECK_CONN, array( $this, 'db_check_conn' ) );
			add_action( 'visualizer_db_query_add_layout', array( $this, 'dbQueryLayout' ), 10, 1 );
			add_filter( 'visualizer_db_tables_column_mapping', array( $this, 'dbGetMeta' ), 10, 2 );
			add_filter( 'visualizer_db_query_execute', array( $this, 'dbParseResults' ), 10, 7 );
			add_action( 'visualizer_db_query_add_hints', array( $this, 'dbAddHints' ), 10, 1 );

			// editors
			add_filter( 'visualizer_editors', array( $this, 'supportedEditors' ), 10, 1 );

			// Inline style.
			add_action( 'visualizer_inline_css', array( $this, 'loadFrontendInlineStyle' ) );

			// Multilingual support.
			add_action( 'visualizer_chart_languages', array( $this, 'addMultilingualSupport' ) );
			// White list WooCommerce reports API.
			add_filter( 'woocommerce_rest_check_permissions', array( $this, 'WooCommerceRestCheckPermissions' ), 10, 4 );
		}

		/**
		 * Adds additional editors supported for editing manual data.
		 **/
		public function supportedEditors( $editors ) {
			$editors['excel'] = __( 'Excel', 'visualizer' );
			return $editors;
		}

		/**
		 * Adds additional hints to the db query wizard.
		 **/
		public function dbAddHints( $args ) {
			?>
			<li><?php _e( 'If you change the connection parameters, please use the \'Test Connection\' button to verify the connection.', 'visualizer' ); ?></li>
			<li><?php echo sprintf( __( 'To know how to add support for other databases, please refer to %1$sthis%2$s and %3$sthis%4$s. Once you enable those extensions and restart the web server, the options will show up as enabled in the dropdown above.', 'visualizer' ), '<a href="https://www.php.net/manual/en/pdo.installation.php" target="_blank">', '</a>', '<a href="https://www.php.net/manual/en/refs.database.vendors.php" target="_blank">', '</a>' ); ?></li>
			<?php
		}

		/**
		 * Create and execute the query for the remote database, if that is being used.
		 *
		 * @param bool   $override Dumb override.
		 * @param string $query The query.
		 * @param bool   $as_html Should the result be fetched as an HTML table or as an object.
		 * @param bool   $results_as_numeric_array Should the result be fetched as ARRAY_N instead of ARRAY_A.
		 * @param bool   $raw_results Should the result be returned without processing.
		 * @param int    $chart_id The chart ID.
		 * @param array  $params Any additional parameters (e.g. for connecting to a remote db).
		 **/
		public function dbParseResults( $override, $query, $as_html, $results_as_numeric_array, $raw_results, $chart_id, $params ) {
			if ( empty( $params ) ) {
				$params = get_post_meta( $chart_id, Visualizer_Plugin::CF_REMOTE_DB_PARAMS, true );
			}
			if ( ! empty( $params ) ) {
				return Visualizer_Pro_Wrapper_DB::getResults( $query, $as_html, $results_as_numeric_array, $raw_results, $chart_id, $params );
			}
			return $override;
		}

		/**
		 * Gets the table, column mappings for the remote database, if that is being used.
		 *
		 * @param array $mapping The original mapping from the free plugin.
		 * @param int   $chart_id The chart ID.
		 * @param array $params The params that contain the db connection parameters.
		 *                      If this array is not empty, it will be used as priority otherwise
		 *                      the saved data will be used to generate the parameters.
		 **/
		public function dbGetMeta( $mapping, $chart_id, $params = array() ) {
			if ( empty( $params ) ) {
				$params = get_post_meta( $chart_id, Visualizer_Plugin::CF_REMOTE_DB_PARAMS, true );
			}
			if ( ! empty( $params ) ) {
				return Visualizer_Pro_Wrapper_DB::getMeta( $params );
			}
			return $mapping;
		}

		/**
		 * Adds additional layout to the db query wizard.
		 **/
		public function dbQueryLayout( $args ) {
			$supported = array();
			$available  = array();
			// let's check what all dbs can be supported using PDO.
			if ( extension_loaded( 'PDO' ) ) {
				// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				$available = apply_filters( 'visualizer_dbs_supported_pdo', self::$DB_PDO_COLLECTION );
				// phpcs:ignore WordPress.DB.RestrictedClasses.mysql__PDO
				$drivers = PDO::getAvailableDrivers();
				foreach ( $available as $name => $extension ) {
					if ( in_array( $extension, $drivers, true ) ) {
						$supported[] = $name;
					}
				}
				do_action( 'themeisle_log_event', Visualizer_Plugin::NAME, sprintf( 'PDO is installed with support for %s', print_r( $supported, true ) ), 'debug', __FILE__, __LINE__ );
			} else {
				// let's check what all dbs can be supported using vendor specific libraries.
				// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				$available = apply_filters( 'visualizer_dbs_supported_vendors', self::$DB_VENDOR_COLLECTION );
				foreach ( $available as $name => $extension ) {
					$extensions = is_array( $extension ) ? $extension : array( $extension );
					foreach ( $extensions as $extension ) {
						if ( extension_loaded( $extension ) ) {
							$supported[] = $name;
						}
					}
				}
				do_action( 'themeisle_log_event', Visualizer_Plugin::NAME, sprintf( 'Vendor specific support for %s', print_r( $supported, true ) ), 'debug', __FILE__, __LINE__ );
			}

			// assume MySQL is supported even if extensions don't exist
			// cypress tests on travis fail otherwise
			$supported[] = 'MySQL';

			$supported = array_unique( $supported );
			if ( empty( $supported ) ) {
				return;
			}

			$supported = array_merge( array( Visualizer_Plugin::WP_DB_NAME ), $supported );

			// for code mirror
			$name_vs_dialect = array();
			foreach ( $supported as $name ) {
				$dialect    = 'text/x-sql';
				switch ( $name ) {
					case Visualizer_Plugin::WP_DB_NAME:
						// fall-through
					case 'MySQL':
						$dialect = 'text/x-mysql';
						break;
					case 'Oracle':
						$dialect = 'text/x-plsql';
						break;
					case 'PostgreSQL':
						$dialect = 'text/x-pgsql';
						break;
					case 'MS SQL':
						$dialect = 'text/x-mssql';
						break;
				}
				$name_vs_dialect[ $name ] = $dialect;
			}

			$params = get_post_meta( $args[2], Visualizer_Plugin::CF_REMOTE_DB_PARAMS, true );
			if ( ! $params ) {
				$params = array();
			}

			$open_by_default = ! empty( $params ) ? 'open' : '';

			echo
			'<div class="db-query-additional">
				<h3 class="viz-substep ' . $open_by_default . '">' . __( 'Select another/external database', 'visualizer' ) . '</h3>
				<div>';
					echo '<select name="db_type">';
			foreach ( $name_vs_dialect as $name => $dialect ) {
				echo '<option ' . selected( isset( $params['db_type'] ) ? $params['db_type'] : '', $name ) . ' data-dialect="' . $dialect . '">' . $name . '</option>';
			}

							// show the other databases that can be supported as disabled options.
			foreach ( $available as $name => $driver ) {
				if ( ! array_key_exists( $name, $name_vs_dialect ) ) {
					echo '<option disabled>' . $name . '</option>';
				}
			}
					echo '</select>';
					echo '<input type="text" name="db_conn_server" placeholder="' . __( 'Server Name/IP[:port]', 'visualizer' ) . '" value="' . ( isset( $params['db_conn_server'] ) ? $params['db_conn_server'] : '' ) . '">';
					echo '<input type="text" name="db_conn_db" placeholder="' . __( 'Database Name', 'visualizer' ) . '" value="' . ( isset( $params['db_conn_db'] ) ? $params['db_conn_db'] : '' ) . '">';
					echo '<input type="text" name="db_conn_user" placeholder="' . __( 'Username', 'visualizer' ) . '" value="' . ( isset( $params['db_conn_user'] ) ? $params['db_conn_user'] : '' ) . '">';
					echo '<input type="password" name="db_conn_passwd" placeholder="' . __( 'Password', 'visualizer' ) . '" value="' . ( isset( $params['db_conn_passwd'] ) ? $params['db_conn_passwd'] : '' ) . '">';
					echo '<input type="button" class="button button-secondary db-test-conn" value="' . __( 'Test Connection', 'visualizer' ) . '">';
					echo '<span class="db-test-conn-result"></span>
				</div>
			</div>';
		}

		/**
		 * Checks the remote db connection.
		 *
		 * @access friendly
		 */
		function db_check_conn() {
			$nonce    = isset( $_POST['nonce'] ) ? wp_verify_nonce( $_POST['nonce'], self::ACTION_DB_CHECK_CONN ) : '';
			if ( ! $nonce ) {
				wp_die();
			}

			$params = wp_parse_args( $_POST['data'] );
			$chart_id = filter_var( $params['chart_id'], FILTER_VALIDATE_INT );

			if ( $params['db_type'] === Visualizer_Plugin::WP_DB_NAME || ! $chart_id ) {
				wp_die();
			}

			$success = false;
			$msg    = __( 'Unable to connect!', 'visualizer' );
			$meta   = null;

			if ( Visualizer_Pro_Wrapper_DB::checkConnection( $params ) ) {
				$success    = true;
				$msg    = __( 'Connected!', 'visualizer' );
				$meta = $this->dbGetMeta( null, $chart_id, $params );
			}

			$this->_sendResponse(
				array(
					'success' => $success,
					'msg' => $msg,
					'meta' => $meta,
				)
			);
			wp_die();
		}

		/**
		 * Adds custom schedules.
		 **/
		public function addSchedules( $schedules, $type ) {
			switch ( $type ) {
				case 'json':
					// do not use array merge here as numeric keys will be renumbered.
					$new = array(
						'0' => __( 'Live', 'visualizer' ),
						'1'  => __( 'Each hour', 'visualizer' ),
						'12' => __( 'Each 12 hours', 'visualizer' ),
						'24' => __( 'Each day', 'visualizer' ),
						'72' => __( 'Each 3 days', 'visualizer' ),
					);
					foreach ( $new as $num => $text ) {
						$schedules[ $num ] = $text;
					}
					break;
				case 'csv':
					// do not use array merge here as numeric keys will be renumbered.
					$new = array(
						'0' => __( 'Live', 'visualizer' ),
						'1'  => __( 'Each hour', 'visualizer' ),
						'12' => __( 'Each 12 hours', 'visualizer' ),
						'24' => __( 'Each day', 'visualizer' ),
						'72' => __( 'Each 3 days', 'visualizer' ),
					);
					foreach ( $new as $num => $text ) {
						$schedules[ $num ] = $text;
					}
					break;
				case 'wp':
					// fall-through.
				case 'db':
					if ( apply_filters( 'visualizer_is_business', false ) ) {
						// do not use array merge here as numeric keys will be renumbered.
						$new = array(
							'0' => __( 'Live', 'visualizer' ),
							'1'  => __( 'Each hour', 'visualizer' ),
							'12' => __( 'Each 12 hours', 'visualizer' ),
							'24' => __( 'Each day', 'visualizer' ),
							'72' => __( 'Each 3 days', 'visualizer' ),
						);
						foreach ( $new as $num => $text ) {
							$schedules[ $num ] = $text;
						}
					}
					break;
			}
			return $schedules;
		}


		/**
		 * Get the permissions meta data for the chart.
		 *
		 * @access public
		 * @param int $chart_id The id of the chart.
		 */
		public function getPermissions( $chart_id ) {
			return get_post_meta( $chart_id, self::CF_PERMISSIONS, true );
		}

		/**
		 * Called while creating a new chart.
		 *
		 * @access public
		 * @param int $chart_id The id of the chart.
		 */
		public function createNewChart( $chart_id ) {
			if ( ! empty( $_GET['lang'] ) && ! empty( $_GET['parent_chart_id'] ) ) {
				$parent_chart_id = (int) $_GET['parent_chart_id'];
				add_post_meta( $chart_id, 'chart_lang', $_GET['lang'] );
				$this->set_wpml_element_language_details( $parent_chart_id, $chart_id, $_GET['lang'] );
			}

			update_post_meta(
				$chart_id,
				self::CF_PERMISSIONS,
				array(
					'permissions'   => array(
						'read'          => 'all',
						'edit'          => 'roles',
						'edit-specific' => array( 'administrator' ),
					),
				)
			);
		}

		/**
		 * Loads resources for the front end.
		 *
		 * @access public
		 */
		public function loadFrontendResources() {
			wp_register_script( 'visualizer-handsontable', Visualizer_Pro_ABSURL . 'vendor/handsontable/dist/handsontable.full.min.js', array( 'jquery' ), VISUALIZER_PRO_VERSION, true );
			wp_register_script( 'visualizer-magnific', '//cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js', array( 'visualizer-handsontable' ), VISUALIZER_PRO_VERSION, true );

			wp_register_script(
				'visualizer-pro-render-edit',
				Visualizer_Pro_ABSURL . 'inc/js/render.js',
				array(
					'visualizer-magnific',
				),
				VISUALIZER_PRO_VERSION,
				true
			);
			wp_localize_script(
				'visualizer-pro-render-edit',
				'visualizer2',
				array(
					'locale' => get_locale(),
					'types' => $this->getAllowedTypes(),
					'i10n'  => array(
						'save'      => esc_html__( 'Save', 'visualizer' ),
						'cancel'    => esc_html__( 'Cancel', 'visualizer' ),
					),
					'wp_nonce'      => wp_create_nonce( 'wp_rest' ),
					'rest_url'      => rest_url( 'visualizer/v' . VISUALIZER_REST_VERSION . '/action/#id#/#type#/' ),
				)
			);

			wp_register_script(
				'visualizer-pro-render',
				Visualizer_Pro_ABSURL . 'inc/js/render.js',
				array( 'jquery' ),
				VISUALIZER_PRO_VERSION,
				true
			);
			wp_localize_script(
				'visualizer-pro-render',
				'visualizer2',
				array(
					'types' => $this->getAllowedTypes(),
					'i10n'  => array(
						'save'      => esc_html__( 'Save', 'visualizer' ),
						'cancel'    => esc_html__( 'Cancel', 'visualizer' ),
					),
					'rest_url'      => rest_url( 'visualizer/v' . VISUALIZER_REST_VERSION . '/action/#id#/#type#/' ),
				)
			);

			wp_register_style( 'visualizer-handsontable', Visualizer_Pro_ABSURL . 'vendor/handsontable/dist/handsontable.full.min.css', array(), VISUALIZER_PRO_VERSION );
			wp_register_style( 'visualizer-magnific', '//cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css', array( 'visualizer-handsontable' ), VISUALIZER_PRO_VERSION );
		}

		/**
		 * Check whether to show the chart or not.
		 *
		 * @access public
		 * @param bool $show The default action (true).
		 * @param int  $chart_id The id of the chart.
		 */
		public function checkViewChartPermission( $show, $chart_id ) {
			return $this->checkChartPermissionFor( 'read', $show, $chart_id );
		}

		/**
		 * Check whether the chart has a certain permission or not.
		 *
		 * @access private
		 * @param string $type The type of permission.
		 * @param bool   $show The default action (true).
		 * @param int    $chart_id The id of the chart.
		 * @param bool   $check_license Should we check license or allow all licenses.
		 */
		private function checkChartPermissionFor( $type, $show, $chart_id, $check_license = true ) {
			if ( $check_license && ! apply_filters( 'visualizer_is_business', false ) ) {
				return $show;
			}

			$permissions    = get_post_meta( $chart_id, self::CF_PERMISSIONS, true );
			if ( $permissions ) {
				if ( isset( $permissions['permissions'] ) && isset( $permissions['permissions'][ $type ] ) ) {
					$show       = false;
					switch ( $permissions['permissions'][ $type ] ) {
						case 'all':
							$show       = true;
							break;
						case 'users':
							$allowed    = isset( $permissions['permissions'][ $type . '-specific' ] ) ? $permissions['permissions'][ $type . '-specific' ] : array();
							// phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
							$show       = is_user_logged_in() && in_array( get_current_user_id(), $allowed );
							break;
						case 'roles':
							global $current_user;
							$roles      = $current_user->roles;
							$role       = array_shift( $roles );
							$allowed    = isset( $permissions['permissions'][ $type . '-specific' ] ) ? $permissions['permissions'][ $type . '-specific' ] : array();
							// phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
							$show       = is_user_logged_in() && in_array( $role, $allowed );
							break;
					}
				}
			}
			do_action( 'themeisle_log_event', Visualizer_Plugin::NAME, sprintf( 'Permissions for type %s, id %d, user %d = *%d*', $type, $chart_id, get_current_user_id(), $show ), 'debug', __FILE__, __LINE__ );
			return $show;
		}


		/**
		 * Handles the custom actions.
		 *
		 * @access public
		 * @param array               $data The default data (null).
		 * @param int                 $chart_id The id of the chart.
		 * @param string              $type The type of action.
		 * @param WP_REST_Request     $params The parameters of the request.
		 * @param Visualizer_Frontend $frontend_module The front end module instance.
		 */
		public function handleAction( $data, $chart_id, $type, WP_REST_Request $params, $frontend_module ) {
			switch ( $type ) {
				case 'edit':
					$chart      = get_post( $chart_id );
					$chart_type = get_post_meta( $chart->ID, Visualizer_Plugin::CF_CHART_TYPE, true );
					$series     = apply_filters( Visualizer_Plugin::FILTER_GET_CHART_SERIES, get_post_meta( $chart->ID, Visualizer_Plugin::CF_SERIES, true ), $chart->ID, $chart_type );
					$content    = apply_filters( Visualizer_Plugin::FILTER_GET_CHART_DATA, unserialize( $chart->post_content ), $chart->ID, $chart_type );
					$data       = array(
						'series'    => $series,
						'data'      => $content,
					);
					if ( method_exists( 'Visualizer_Source', 'get_date_formats_if_exists' ) ) {
						$date_formats = Visualizer_Source::get_date_formats_if_exists( $series, $content );
						$data['date_formats'] = $date_formats;
					}
					break;
				case 'save':
					$source     = $this->_handleChartData( $params['data'] );
					if ( $source ) {
						if ( $source->fetch() ) {
							$chart  = get_post( $chart_id );
							$chart->post_content = $source->getData();
							wp_update_post( $chart->to_array() );
							update_post_meta( $chart_id, Visualizer_Plugin::CF_SERIES, $source->getSeries() );
							update_post_meta( $chart_id, Visualizer_Plugin::CF_SOURCE, $source->getSourceName() );
							update_post_meta( $chart_id, Visualizer_Plugin::CF_DEFAULT_DATA, 0 );
							// Clear existing chart cache.
							$cache_key = Visualizer_Plugin::CF_CHART_CACHE . '_' . $chart_id;
							if ( get_transient( $cache_key ) ) {
								delete_transient( $cache_key );
							}
							apply_filters( Visualizer_Plugin::FILTER_UNDO_REVISIONS, $chart_id, false );
						}
					}
					break;
			}

			return $data;
		}

		/**
		 * Adds the custom actions.
		 *
		 * @access public
		 * @param array $actions The default actions.
		 */
		public function addActionButtons( $actions ) {
			$actions['edit'] = array(
				'label' => esc_html__( 'Edit', 'visualizer' ),
				'title' => esc_html__( 'Edit data', 'visualizer' ),
			);
			return $actions;
		}

		/**
		 * Adds the custom actions names.
		 *
		 * @access public
		 * @param array $actions The default actions, as enabled in the settings.
		 * @param int   $chart_id The id of the chart.
		 */
		public function addActions( $actions, $chart_id ) {
			if ( $this->checkChartPermissionFor( 'edit', false, $chart_id, false ) ) {
				$actions['edit']    = 'edit';
				wp_enqueue_style( 'visualizer-magnific' );
				wp_enqueue_script( 'visualizer-pro-render-edit' );
			} else {
				wp_enqueue_script( 'visualizer-pro-render' );
			}
			return $actions;
		}

		/**
		 * Handles the permissions data.
		 *
		 * @access public
		 * @param WP_Post $chart The chart object.
		 */
		public function handlePermissions( $chart ) {
			if ( apply_filters( 'visualizer_is_business', false ) && ! empty( $_POST['permissions'] ) ) {
				update_post_meta( $chart->ID, self::CF_PERMISSIONS, array( 'permissions' => $_POST['permissions'] ) );
			}
		}

		/**
		 * Returns the permissions data.
		 *
		 * @access public
		 * @params string $default Null.
		 * @params string $type The type of data to fetch for.
		 */
		public function getPermissionsDataFilter( $default, $type ) {
			return $this->getPermissionsData( $type );
		}

		/**
		 * Gets the data for users/roles.
		 *
		 * @params string $type The type of data to fetch for.
		 * @access private
		 */
		private function getPermissionsData( $type ) {
			$options    = array();
			switch ( $type ) {
				case 'users':
					$query  = new WP_User_Query(
						array(
							'number'        => 1000,
							'orderby'       => 'display_name',
							'fields'        => array( 'ID', 'display_name' ),
							'count_total'   => false,
						)
					);
					$users  = $query->get_results();
					if ( ! empty( $users ) ) {
						foreach ( $users as $user ) {
							$options[ $user->ID ] = $user->display_name;
						}
					}
					break;
				case 'roles':
					$roles  = get_editable_roles();
					if ( ! empty( $roles ) ) {
						foreach ( $roles as $name => $info ) {
							$options[ $name ] = $name;
						}
					}
					break;
			}
			return $options;
		}

		/**
		 * Gets the data for users/roles.
		 *
		 * @access friendly
		 */
		function fetch_permissions_data() {
			if ( ! defined( 'Visualizer_Plugin::ACTION_FETCH_PERMISSIONS_DATA' ) ) {
				return;
			}
			global $wpdb;
			$nonce  = isset( $_POST['nonce'] ) ? wp_verify_nonce( $_POST['nonce'], Visualizer_Plugin::ACTION_FETCH_PERMISSIONS_DATA ) : '';
			$type   = isset( $_POST['type'] ) ? $_POST['type'] : '';
			if ( $nonce && $type ) {
				$this->_sendResponse(
					array(
						'success' => true,
						'data'    => $this->getPermissionsData( $type ),
					)
				);
				wp_die();
			}
		}

		/**
		 * Fetches the SDK logger data.
		 *
		 * @param array $data The default data that needs to be sent.
		 *
		 * @access public
		 */
		public function getLoggerData( $data ) {
			return apply_filters(
				'visualizer_get_chart_counts',
				array(
					'wordpress_filters' => 'visualizer-filter-config', // make this Visualizer_Plugin::CF_FILTER_CONFIG later
				)
			);
		}

		/**
		 * Add/update the chart schedule
		 *
		 * @param int    $chart_id chart id.
		 * @param string $url the url.
		 * @param int    $hours how many hours.
		 */
		function updateSchedule( $chart_id, $url, $hours ) {
			update_post_meta( $chart_id, Visualizer_Plugin::CF_CHART_URL, $url );
			update_post_meta( $chart_id, Visualizer_Plugin::CF_CHART_SCHEDULE, $hours );
			$schedules              = get_option( Visualizer_Plugin::CF_CHART_SCHEDULE, array() );
			$schedules[ $chart_id ] = time() + $hours * HOUR_IN_SECONDS;
			update_option( Visualizer_Plugin::CF_CHART_SCHEDULE, $schedules );

			return null;
		}

		/**
		 * Delete the chart from the schedule
		 *
		 * @param int $chart_id chart id.
		 */
		function deleteSchedule( $chart_id ) {
			delete_post_meta( $chart_id, Visualizer_Plugin::CF_CHART_SCHEDULE );
			delete_post_meta( $chart_id, Visualizer_Plugin::CF_CHART_URL );
			$schedules = get_option( Visualizer_Plugin::CF_CHART_SCHEDULE, array() );
			if ( $schedules ) {
				unset( $schedules[ $chart_id ] );
			}
			update_option( Visualizer_Plugin::CF_CHART_SCHEDULE, $schedules );
		}

		/**
		 * Activate the plugin
		 */
		function activate( $network_wide ) {
			if ( is_multisite() && $network_wide ) {
				foreach ( get_sites( array( 'fields' => 'ids' ) ) as $blog_id ) {
					switch_to_blog( $blog_id );
					$this->activate_on_site();
					restore_current_blog();
				}
			} else {
				$this->activate_on_site();
			}
		}

		/**
		 * Activates the plugin on a particular blog instance (supports multisite and single site).
		 */
		private function activate_on_site() {
			wp_clear_scheduled_hook( 'visualizer_schedule_import' );
			wp_schedule_event( strtotime( 'midnight' ) - get_option( 'gmt_offset' ) * HOUR_IN_SECONDS, apply_filters( 'visualizer_chart_schedule_interval', 'hourly' ), 'visualizer_schedule_import' );
		}

		/**
		 * Deactivate the plugin
		 */
		public function deactivate( $network_wide ) {
			if ( is_multisite() && $network_wide ) {
				foreach ( get_sites( array( 'fields' => 'ids' ) ) as $blog_id ) {
					switch_to_blog( $blog_id );
					$this->deactivate_on_site();
					restore_current_blog();
				}
			} else {
				$this->deactivate_on_site();
			}
		}

		/**
		 * Deactivates the plugin on a particular blog instance (supports multisite and single site).
		 */
		private function deactivate_on_site() {
			wp_clear_scheduled_hook( 'visualizer_schedule_import' );
		}

		/**
		 * Update the json that are scheduled for now
		 */
		function updateScheduledJson() {
			$schedules = get_option( Visualizer_Plugin::CF_JSON_SCHEDULE, array() );
			if ( ! $schedules ) {
				return;
			}
			if ( ! defined( 'VISUALIZER_DO_NOT_DIE' ) ) {
				// define this so that the ajax call does not die
				// this means that if the new version of pro and the old version of free are installed, only the first chart will be updated
				define( 'VISUALIZER_DO_NOT_DIE', true );
			}
			$new_schedules = array();
			$now           = time();
			foreach ( $schedules as $chart_id => $time ) {
				$chart = get_post( $chart_id );
				if ( ! $chart ) {
					// chart does not exist.
					continue;
				}

				$hours                      = get_post_meta( $chart_id, Visualizer_Plugin::CF_JSON_SCHEDULE, true );
				if ( $hours === '' ) {
					continue;
				}

				$new_schedules[ $chart_id ] = $time;
				if ( $time > $now ) {
					do_action( 'themeisle_log_event', Visualizer_Plugin::NAME, sprintf( 'Not updating scheduled json chart %d as it is not yet time %d', $chart_id, $time ), 'debug', __FILE__, __LINE__ );
					continue;
				}
				do_action( 'themeisle_log_event', Visualizer_Plugin::NAME, sprintf( 'Trying to update scheduled json chart %d', $chart_id ), 'info', __FILE__, __LINE__ );
				// run it
				$url        = get_post_meta( $chart_id, Visualizer_Plugin::CF_JSON_URL, true );
				$root       = get_post_meta( $chart_id, Visualizer_Plugin::CF_JSON_ROOT, true );
				$paging     = get_post_meta( $chart_id, Visualizer_Plugin::CF_JSON_PAGING, true );
				$series     = get_post_meta( $chart_id, Visualizer_Plugin::CF_SERIES, true );
				$source     = new Visualizer_Source_Json(
					array(
						'url' => $url,
						'root' => $root,
						'paging' => $paging,
					)
				);
				if ( $source ) {
					if ( $source->fetch() ) {
						$data   = $source->getRawData();
						$source->refresh( $series );
						$content    = $source->getData( get_post_meta( $chart_id, Visualizer_Plugin::CF_EDITABLE_TABLE, true ) );
						$populate   = true;
						if ( is_string( $content ) && is_array( unserialize( $content ) ) ) {
							$json   = unserialize( $content );
							// if source exists, so should data. if source exists but data is blank, do not populate the chart.
							// if we populate the data even if it is empty, the chart will show "Table has no columns".
							if ( array_key_exists( 'source', $json ) && ! empty( $json['source'] ) && ( ! array_key_exists( 'data', $json ) || empty( $json['data'] ) ) ) {
								do_action( 'themeisle_log_event', Visualizer_Plugin::NAME, sprintf( 'Not populating chart data as source exists (%s) but data is empty!', $json['source'] ), 'warn', __FILE__, __LINE__ );
								update_post_meta( $chart_id, Visualizer_Plugin::CF_ERROR, sprintf( 'Not populating chart data as source exists (%s) but data is empty!', $json['source'] ) );
								$populate   = false;
							}
						}
						if ( $populate ) {
							$chart->post_content = $content;
						}
						$revisions = wp_get_post_revisions( $chart_id, array( 'order' => 'ASC' ) );
						if ( ! empty( $revisions ) ) {
							$revision_ids = array_keys( $revisions );
							// delete all revisions.
							foreach ( $revision_ids as $id ) {
								wp_delete_post_revision( $id );
							}
						}
						wp_update_post( $chart->to_array() );
						update_post_meta( $chart->ID, Visualizer_Plugin::CF_SERIES, $source->getSeries() );
						update_post_meta( $chart->ID, Visualizer_Plugin::CF_SOURCE, $source->getSourceName() );
						update_post_meta( $chart->ID, Visualizer_Plugin::CF_DEFAULT_DATA, 0 );

						$hours                      = get_post_meta( $chart_id, Visualizer_Plugin::CF_JSON_SCHEDULE, true );
						$next                       = time() + $hours * HOUR_IN_SECONDS;
						$new_schedules[ $chart_id ] = $next;
						// Clear existing chart cache.
						$cache_key = Visualizer_Plugin::CF_CHART_CACHE . '_' . $chart_id;
						if ( get_transient( $cache_key ) ) {
							delete_transient( $cache_key );
						}
						do_action( 'themeisle_log_event', Visualizer_Plugin::NAME, sprintf( 'Updated scheduled json chart %d, set next update time %d', $chart_id, $next ), 'info', __FILE__, __LINE__ );
					}
					update_option( Visualizer_Plugin::CF_JSON_SCHEDULE, $new_schedules );
				}
			}
		}

		/**
		 * Update the charts that are scheduled for now
		 */
		function updateScheduledChart() {
			$schedules = get_option( Visualizer_Plugin::CF_CHART_SCHEDULE, array() );
			if ( ! $schedules ) {
				return;
			}
			if ( ! defined( 'VISUALIZER_DO_NOT_DIE' ) ) {
				// define this so that the ajax call does not die
				// this means that if the new version of pro and the old version of free are installed, only the first chart will be updated
				define( 'VISUALIZER_DO_NOT_DIE', true );
			}
			$new_schedules = array();
			$now           = time();
			foreach ( $schedules as $chart_id => $time ) {
				$chart = get_post( $chart_id );
				if ( ! $chart ) {
					// chart does not exist.
					continue;
				}

				$hours                      = get_post_meta( $chart_id, Visualizer_Plugin::CF_CHART_SCHEDULE, true );
				if ( $hours === '' ) {
					// chart is no longer a csv chart? (it was csv before and now it has become json or something else)
					continue;
				}

				$new_schedules[ $chart_id ] = $time;
				if ( $time > $now ) {
					do_action( 'themeisle_log_event', Visualizer_Plugin::NAME, sprintf( 'Not updating scheduled chart %d as it is not yet time %d', $chart_id, $time ), 'debug', __FILE__, __LINE__ );
					continue;
				}
				do_action( 'themeisle_log_event', Visualizer_Plugin::NAME, sprintf( 'Trying to update scheduled chart %d', $chart_id ), 'info', __FILE__, __LINE__ );
				// run it
				$_GET['nonce']           = wp_create_nonce();
				$_GET['chart']           = $chart_id;
				$_POST['remote_data']    = get_post_meta( $chart_id, Visualizer_Plugin::CF_CHART_URL, true );
				$_POST['vz-import-time'] = get_post_meta( $chart_id, Visualizer_Plugin::CF_CHART_SCHEDULE, true );
				do_action( 'wp_ajax_' . Visualizer_Plugin::ACTION_UPLOAD_DATA );
				apply_filters( Visualizer_Plugin::FILTER_UNDO_REVISIONS, $chart_id, false );
				$hours                      = get_post_meta( $chart_id, Visualizer_Plugin::CF_CHART_SCHEDULE, true );
				$next                       = time() + $hours * HOUR_IN_SECONDS;
				$new_schedules[ $chart_id ] = $next;
				// Clear existing chart cache.
				$cache_key = Visualizer_Plugin::CF_CHART_CACHE . '_' . $chart_id;
				if ( get_transient( $cache_key ) ) {
					delete_transient( $cache_key );
				}
				do_action( 'themeisle_log_event', Visualizer_Plugin::NAME, sprintf( 'Updated scheduled chart %d, set next update time %d', $chart_id, $next ), 'info', __FILE__, __LINE__ );
			}
			update_option( Visualizer_Plugin::CF_CHART_SCHEDULE, $new_schedules );
		}

		/**
		 * Return the upsell html/class
		 *
		 * @param string $dummy dummy.
		 * @param string $feature feature.
		 *
		 * @return string
		 */
		function upsell( $dummy, $feature = null ) {
			$is_pro = apply_filters( 'visualizer_is_pro', false );
			if ( ! $is_pro ) {
				return $dummy;
			}
			$biz_features   = method_exists( 'Visualizer_Module', 'get_features_for_license' ) ? Visualizer_Module::get_features_for_license( 2 ) : array( 'schedule-chart', 'chart-permissions' );
			if ( ! $feature || ! in_array( $feature, $biz_features, true ) || ( in_array( $feature, $biz_features, true ) && apply_filters( 'visualizer_is_business', false ) ) ) {
				return '';
			}

			return $dummy;
		}

		/**
		 * Build the sidebar with pro features
		 *
		 * @param mixed $sidebar Sidebar to load.
		 * @param mixed $data Chart specific info.
		 *
		 * @return mixed
		 */
		function chartTypeSidebar( $sidebar, $data ) {
			$type = ucwords( $data['type'] );
			$name = 'Visualizer_Render_Sidebar_Type_' . $type;
			if ( file_exists( Visualizer_Pro_PATH . '/inc/types/' . $type . '.php' ) ) {
				require_once Visualizer_Pro_PATH . '/inc/types/' . $type . '.php';
				$type    = ucwords( $data['type'] );
				$sidebar = new $name( $data['settings'] );
			}

			return $sidebar;
		}

		/**
		 * Load the class for the chart type.
		 *
		 * @param bool   $return The default return value.
		 * @param string $class  The name of the class.
		 *
		 * @return bool
		 */
		function loadChartTypes( $return, $class ) {
			$type = str_replace( array( 'Visualizer_Render_Sidebar_Type_DataTable_', 'Visualizer_Render_Sidebar_Type_' ), array( '', '' ), $class );
			if ( file_exists( Visualizer_Pro_PATH . '/inc/types/' . $type . '.php' ) ) {
				require_once Visualizer_Pro_PATH . '/inc/types/' . $type . '.php';
				return true;
			}
			return $return;
		}

		/**
		 * Add additional settings for the given chart type.
		 * This will only be for charts that are defined in free with additional settings in pro.
		 *
		 * @param string $class      The name of the class.
		 * @param array  $data       The chart data/settings.
		 * @param string $section    The section in settings.
		 * @param array  $settings    Additional settings, if any.
		 */
		function moreChartSettings( $class, $data, $section, $settings = array() ) {
			if ( $settings && array_key_exists( 'generic', $settings ) && true === $settings['generic'] ) {
				$this->addGenericSettings( $class, $data, $section, $settings );
				return;
			}

			static $_visualizer_class_array;
			$type = str_replace( array( 'Visualizer_Render_Sidebar_Type_DataTable_', 'Visualizer_Render_Sidebar_Type_' ), array( '', '' ), $class );
			if ( 'Tabular' === $type ) {
				$type = 'DataTable';
			}
			if ( file_exists( Visualizer_Pro_PATH . '/inc/types/' . $type . '.php' ) ) {
				require_once Visualizer_Pro_PATH . '/inc/types/' . $type . '.php';
				if ( ! isset( $_visualizer_class_array[ $type ] ) ) {
					$_visualizer_class_array[ $type ] = new $type( $data );
				}
				do_action( 'visualizer_chart_settings_' . $type, $section );
			}
		}

		/**
		 * Add additional generic settings for the given chart type.
		 *
		 * @param string $class      The name of the class.
		 * @param array  $data       The chart data/settings.
		 * @param string $section    The section in settings.
		 * @param array  $settings    Additional settings, if any.
		 */
		private function addGenericSettings( $class, $data, $section, $settings ) {
			if ( strpos( $class, 'Google' ) !== false ) {
				require_once Visualizer_Pro_PATH . '/inc/types/Google.php';
				new Visualizer_Render_Sidebar_Type_Google( $class, $data );
			}
		}

		/**
		 * Add chart types from the pro version
		 *
		 * @param array $types Array of available charts.
		 *
		 * @return mixed
		 */
		function chartTypes( $types ) {
			// convert old charts to new values;
			if ( isset( $types['line']['name'] ) ) {
				$types['timeline'] = array(
					'name'    => esc_html__( 'Timeline', 'visualizer' ),
					'enabled' => true,
					'supports' => array( 'Google Charts' ),
				);
				$types['combo']    = array(
					'name'    => esc_html__( 'Combo', 'visualizer' ),
					'enabled' => true,
					'supports' => array( 'Google Charts' ),
				);
				$types['polarArea']    = array(
					'name'    => esc_html__( 'Polar Area', 'visualizer' ),
					'enabled' => true,
					'supports' => array( 'ChartJS' ),
				);
				$types['radar']    = array(
					'name'    => esc_html__( 'Radar/Spider', 'visualizer' ),
					'enabled' => true,
					'supports' => array( 'ChartJS' ),
				);
			} else {
				$types['timeline'] = esc_html__( 'Timeline', 'visualizer' );
				$types['combo']    = esc_html__( 'Combo', 'visualizer' );
				$types['polarArea']    = esc_html__( 'Polar Area', 'visualizer' );
				$types['radar']    = esc_html__( 'Radar', 'visualizer' );
			}

			return $types;
		}

		/**
		 * Loads the plugin deps
		 */
		function loadDependencies() {
			if ( ! function_exists( 'tgmpa' ) ) {
				include_once Visualizer_Pro_PATH . '/lib/tgmpa/tgm-plugin-activation/class-tgm-plugin-activation.php';
			}

			if ( function_exists( 'tgmpa' ) ) {
				add_action( 'tgmpa_register', array( $this, 'tgmpa_register' ) );
			}
		}

		/**
		 * Initialize TGM.
		 */
		public function tgmpa_register() {
			$plugins = array(
				array(
					'name'     => 'Visualizer Lite',
					'slug'     => 'visualizer',
					'required' => true,
				),
			);
			$config  = array(
				'id'           => 'visualizer',
				// Unique ID for hashing notices for multiple instances of TGMPA.
				'default_path' => '',
				// Default absolute path to bundled plugins.
				'menu'         => 'tgmpa-install-plugins',
				// Menu slug.
				'parent_slug'  => 'plugins.php',
				// Parent menu slug.
				'capability'   => 'manage_options',
				// Capability needed to view plugin install page, should be a capability associated with the parent menu used.
				'has_notices'  => true,
				// Show admin notices or not.
				'dismissable'  => true,
				// If false, a user cannot dismiss the nag message.
				'dismiss_msg'  => '',
				// If 'dismissable' is false, this message will be output at top of nag.
				'is_automatic' => false,
				// Automatically activate plugins after installation or not.
				'message'      => '',
				// Message to output right before the plugins table.
				'strings'      => array(
					'page_title'                      => __( 'Install Required Plugins', 'visualizer' ),
					'menu_title'                      => __( 'Install Plugins', 'visualizer' ),
					/* translators: %s: plugin name. */
					'installing'                      => __( 'Installing Plugin: %s', 'visualizer' ),
					/* translators: %s: plugin name. */
					'updating'                        => __( 'Updating Plugin: %s', 'visualizer' ),
					'oops'                            => __( 'Something went wrong with the plugin API.', 'visualizer' ),
					/* translators: 1: plugin name(s). */
					'notice_can_install_required'     => _n_noop(
						'Visualizer requires the following plugin: %1$s.',
						'Visualizer requires the following plugins: %1$s.',
						'visualizer'
					),
					/* translators: 1: plugin name(s). */
					'notice_can_install_recommended'  => _n_noop(
						'Visualizer recommends the following plugin: %1$s.',
						'Visualizer recommends the following plugins: %1$s.',
						'visualizer'
					),
					/* translators: 1: plugin name(s). */
					'notice_ask_to_update'            => _n_noop(
						'The following plugin needs to be updated to its latest version to ensure maximum compatibility with Visualizer: %1$s.',
						'The following plugins need to be updated to their latest version to ensure maximum compatibility with Visualizer: %1$s.',
						'visualizer'
					),
					/* translators: 1: plugin name(s). */
					'notice_ask_to_update_maybe'      => _n_noop(
						'There is an update available for: %1$s.',
						'There are updates available for the following plugins: %1$s.',
						'visualizer'
					),
					/* translators: 1: plugin name(s). */
					'notice_can_activate_required'    => _n_noop(
						'The following required plugin is currently inactive: %1$s.',
						'The following required plugins are currently inactive: %1$s.',
						'visualizer'
					),
					/* translators: 1: plugin name(s). */
					'notice_can_activate_recommended' => _n_noop(
						'The following recommended plugin is currently inactive: %1$s.',
						'The following recommended plugins are currently inactive: %1$s.',
						'visualizer'
					),
					'install_link'                    => _n_noop(
						'Begin installing plugin',
						'Begin installing plugins',
						'visualizer'
					),
					'update_link'                     => _n_noop(
						'Begin updating plugin',
						'Begin updating plugins',
						'visualizer'
					),
					'activate_link'                   => _n_noop(
						'Begin activating plugin',
						'Begin activating plugins',
						'visualizer'
					),
					'return'                          => __( 'Return to Required Plugins Installer', 'visualizer' ),
					'plugin_activated'                => __( 'Plugin activated successfully.', 'visualizer' ),
					'activated_successfully'          => __( 'The following plugin was activated successfully:', 'visualizer' ),
					/* translators: 1: plugin name. */
					'plugin_already_active'           => __( 'No action taken. Plugin %1$s was already active.', 'visualizer' ),
					/* translators: 1: plugin name. */
					'plugin_needs_higher_version'     => __( 'Plugin not activated. A higher version of %s is needed for Visualizer. Please update the plugin.', 'visualizer' ),
					/* translators: 1: dashboard link. */
					'complete'                        => __( 'All plugins installed and activated successfully. %1$s', 'visualizer' ),
					'dismiss'                         => __( 'Dismiss this notice', 'visualizer' ),
					'notice_cannot_install_activate'  => __( 'There are one or more required or recommended plugins to install, update or activate.', 'visualizer' ),
					'contact_admin'                   => __( 'Please contact the administrator of this site for help.', 'visualizer' ),
					'nag_type'                        => '',
					// Determines admin notice type - can only be one of the typical WP notice classes, such as 'updated', 'update-nag', 'notice-warning', 'notice-info' or 'error'. Some of which may not work as expected in older WP versions.
				),
			);
			tgmpa( $plugins, $config );
		}

		/**
		 * Init the admin
		 */
		function adminInit() {
			$this->has_free_installed = defined( 'VISUALIZER_BASEFILE' );
			if ( ! defined( 'Visualizer_Plugin::ACTION_FETCH_PERMISSIONS_DATA' ) ) {
				return;
			}
			add_action( 'wp_ajax_' . Visualizer_Plugin::ACTION_FETCH_PERMISSIONS_DATA, array( $this, 'fetch_permissions_data' ) );
		}

		/**
		 * Ads scripts and styles
		 */
		public function _addScriptsAndStyles( $nothing = null ) {
			if ( ! $this->has_free_installed ) {
				return;
			}
			wp_register_script( 'visualizer-handsontable', Visualizer_Pro_ABSURL . 'vendor/handsontable/dist/handsontable.full.min.js', array(), VISUALIZER_PRO_VERSION, true );
			wp_register_script( 'visualizer-chosen', Visualizer_Pro_ABSURL . 'inc/js/chosen.jquery.min.js', array(), VISUALIZER_PRO_VERSION );
			wp_register_script(
				'visualizer-editor',
				Visualizer_Pro_ABSURL . 'inc/js/editor.js',
				array(
					'visualizer-handsontable',
					'visualizer-chosen',
				),
				VISUALIZER_PRO_VERSION,
				true
			);
			wp_register_style( 'visualizer-handsontable', Visualizer_Pro_ABSURL . 'vendor/handsontable/dist/handsontable.full.min.css', array(), VISUALIZER_PRO_VERSION );
			wp_register_style( 'visualizer-chosen', Visualizer_Pro_ABSURL . 'inc/css/chosen.min.css', array(), VISUALIZER_PRO_VERSION );
			wp_register_style( 'visualizer-main', Visualizer_Pro_ABSURL . 'inc/css/main.css', array(), VISUALIZER_PRO_VERSION );
		}

		/**
		 * Enqueue the admin script and styles
		 *
		 * @param mixed $data Data to output for js scripts.
		 * @param mixed $chart_id The current chart ID.
		 */
		public function _enqueueScriptsAndStyles( $data, $chart_id = null ) {
			if ( ! $this->has_free_installed ) {
				return;
			}
			// support for old free
			$types = $this->getAllowedTypes();
			wp_enqueue_script( 'visualizer-handsontable' );
			wp_enqueue_style( 'visualizer-handsontable' );
			wp_enqueue_script( 'visualizer-editor' );
			wp_enqueue_script( 'visualizer-chosen' );
			wp_enqueue_style( 'visualizer-chosen' );
			wp_enqueue_style( 'visualizer-main' );
			wp_localize_script(
				'visualizer-editor',
				'visualizer1',
				array(
					'locale'            => get_locale(),
					'data'                 => $data,
					'types'                => $types,
					'max_selected_options' => '',
					'l10n'                 => array(
						'invalid_source'      => esc_html__( 'You have entered invalid URL. Please, insert proper URL.', 'visualizer' ),
						'loading'             => esc_html__( 'Loading...', 'visualizer' ),
						'filter_config_error' => esc_html__( 'Please check the filters you have configured.', 'visualizer' ),
						'db_remote_provide'   => esc_html__( 'Please connect to the database before providing the query.', 'visualizer' ),
					),
					'ajax'                 => array(
						'url'     => admin_url( 'admin-ajax.php' ),
						'nonces'  => array(
							'filter_get_props' => wp_create_nonce( self::ACTION_FETCH_POST_TYPE_PROPS ),
							'filter_get_data'  => wp_create_nonce( self::ACTION_FETCH_POST_TYPE_DATA ),
							'db_check_conn'  => wp_create_nonce( self::ACTION_DB_CHECK_CONN ),
						),
						'actions' => array(
							'filter_get_props' => self::ACTION_FETCH_POST_TYPE_PROPS,
							'filter_get_data'  => self::ACTION_FETCH_POST_TYPE_DATA,
							'db_check_conn'  => self::ACTION_DB_CHECK_CONN,
						),
					),
					'filter'    => defined( 'Visualizer_Plugin::CF_SOURCE_FILTER' ) ? Visualizer_Plugin::CF_SOURCE_FILTER : '',
					'db_query' => array(
						// this is to handle the case where we select a remote db and then want to switch back to WP DB
						// this does not handle the case (and its not required) where remote db1 was selected and then remote db2 is selected and then back to db1
						'tables'    => Visualizer_Source_Query_Params::get_all_db_tables_column_mapping( $chart_id, false ),
					),
				)
			);
		}

		/**
		 * Handle chart import data
		 *
		 * @param mixed $data Chart data.
		 * @param mixed $dumb empty always.
		 * @param int   $chart_id The chart id.
		 * @param array $post The _POST array.
		 *
		 * @return Visualizer_Source_Csv
		 */
		function _handleChartData( $data, $dumb = null, $chart_id = null, $post = null ) {
			// handle import from WP.
			if ( $chart_id ) {
				// make this Visualizer_Plugin::CF_FILTER_CONFIG later
				$filter_config  = get_post_meta( $chart_id, 'visualizer-filter-config', true );
				$attributes     = $this->parse_filter_config( $filter_config );
				if ( ! empty( $attributes['query'] ) ) {
					$query      = $attributes['query'];
					return new Visualizer_Source_Query( $query );
				}
			}

			$tmpfile = tempnam( get_temp_dir(), Visualizer_Plugin::NAME );
			$handle  = fopen( $tmpfile, 'w' );
			$arr     = json_decode( stripslashes( trim( $data ) ), true );
			foreach ( $arr as $array ) {
				$data = array_filter( $array, array( __CLASS__, 'chart_array_filter' ) );
				if ( $data ) {
					fputcsv( $handle, $data );
				}
			}
			$source = new Visualizer_Source_Csv( $tmpfile );
			fclose( $handle );

			// unlink($tmpfile);
			return $source;
		}

		/**
		 * Filter the chart array
		 *
		 * @param string $val Cell value.
		 *
		 * @return bool
		 */
		private static function chart_array_filter( $val ) {
			return strlen( trim( $val ) ) !== 0;
		}

		/**
		 * Adds the editor
		 */
		public function _addEditor( $chart_id = null ) {
			if ( ! $this->has_free_installed ) {
				return;
			}
			?>
			<div id="chart-editor" class="visualizer-editor-lhs" style="display: none"></div>
			<?php
		}

		/**
		 * Returns the array of examples that the plugin can choose for first-time users.
		 */
		private function get_first_time_example_filter() {
			return array(
				'any' => array(
					array(
						'type'      => 'post',
						'default'   => array(
							'post_title'    => array(
								'type'  => 'string',
								'alias' => 'post_title',
							),
							'comment_count' => array(
								'type'  => 'number',
								'alias' => 'comment_count',
							),
						),
					),
					array(
						'type'      => 'post',
						'default'   => array(
							'day(post_date)'    => array(
								'type'  => 'number',
								'alias' => 'day(post_date)',
							),
							'sum(comment_count)'    => array(
								'type'  => 'number',
								'alias' => 'sum(comment_count)',
							),
						),
					),
					array(
						'type'      => 'post',
						'default'   => array(
							'week(post_date)'   => array(
								'type'  => 'number',
								'alias' => 'week(post_date)',
							),
							'sum(comment_count)'    => array(
								'type'  => 'number',
								'alias' => 'sum(comment_count)',
							),
						),
					),
					array(
						'type'      => 'post',
						'default'   => array(
							'month(post_date)'  => array(
								'type'  => 'number',
								'alias' => 'month(post_date)',
							),
							'sum(comment_count)'    => array(
								'type'  => 'number',
								'alias' => 'sum(comment_count)',
							),
						),
					),
				),
			);
		}

		/**
		 * Returns the array of examples that the plugin can choose for first-time users.
		 *
		 * @param   string $feature    The feature for which example is required.
		 * @param   string $type       The chart type for which example is required.
		 */
		private function get_first_time_example( $feature, $type ) {
			$examples   = null;
			switch ( $feature ) {
				case 'filter':
					$examples   = $this->get_first_time_example_filter();
					break;
			}

			if ( ! $examples ) {
				return $examples;
			}

			// choose one random example from the examples.
			if ( array_key_exists( $type, $examples ) ) {
				return $examples[ $type ][ rand( 0, count( $examples ) - 1 ) ];
			}
			return $examples['any'][ rand( 0, count( $examples ) - 1 ) ];
		}

		/**
		 * Checks if the given feature is being used for the first time.
		 *
		 * @param   string $feature    The feature for which we are testing usage.
		 */
		private function is_first_time( $feature ) {
			switch ( $feature ) {
				case 'filter':
					$query  = new WP_Query(
						array(
							'post_type'                 => 'visualizer',
							'no_rows_found'             => false,
							'post_per_page'             => 1,
							'fields'                    => 'ids',
							'update_post_meta_cache'    => false,
							'update_post_term_cache'    => false,
							'meta_query'                => array(
								array(
									'key'       => 'visualizer-filter-config', // make this Visualizer_Plugin::CF_FILTER_CONFIG later
									'compare'   => 'EXISTS',
								),
							),
						)
					);
					return ! $query->have_posts();
					break;
			}
			return true;
		}

		/**
		 * Save the filter and its associated query/schedule.
		 */
		function saveFilter( $chart_id, $hours ) {
			// copy transients to actual meta.
			$filter_config  = get_post_meta( $chart_id, '__transient-' . Visualizer_Plugin::CF_FILTER_CONFIG, true );
			if ( ! empty( $filter_config ) ) {
				// save the filter and then save the schedule.
				$query  = get_post_meta( $chart_id, '__transient-' . Visualizer_Plugin::CF_DB_QUERY, true );
				delete_post_meta( $chart_id, '__transient-' . Visualizer_Plugin::CF_FILTER_CONFIG );
				delete_post_meta( $chart_id, '__transient-' . Visualizer_Plugin::CF_DB_QUERY );
				update_post_meta( $chart_id, Visualizer_Plugin::CF_FILTER_CONFIG, $filter_config );
			} else {
				// directly save the schedule.
				$query  = get_post_meta( $chart_id, Visualizer_Plugin::CF_DB_QUERY, true );
				if ( empty( $query ) ) {
					// an old version (where version doesnt exist) filter being saved without changing the filter
					$filter_config = get_post_meta( $chart_id, Visualizer_Plugin::CF_FILTER_CONFIG, true );
					$attributes = $this->parse_filter_config( $filter_config );
					$query = $attributes['query'];

					// update the filter config to the latest version.
					update_post_meta(
						$chart_id,
						Visualizer_Plugin::CF_FILTER_CONFIG,
						array(
							'default' => $attributes['default'],
							'custom'  => $attributes['custom'],
							'type'    => $attributes['type'],
							'version' => 1, // change this version whenever we change the structure of the config object and add this version to parse_filter_config
						)
					);

				}
			}

			update_post_meta( $chart_id, Visualizer_Plugin::CF_DB_QUERY, $query );
			update_post_meta( $chart_id, Visualizer_Plugin::CF_DEFAULT_DATA, 0 );
			update_post_meta( $chart_id, Visualizer_Plugin::CF_SOURCE, 'Visualizer_Source_Query' );

			// update the schedule.
			update_post_meta( $chart_id, Visualizer_Plugin::CF_DB_SCHEDULE, $hours );
			$schedules              = get_option( Visualizer_Plugin::CF_DB_SCHEDULE, array() );
			$schedules[ $chart_id ] = time() + $hours * HOUR_IN_SECONDS;
			update_option( Visualizer_Plugin::CF_DB_SCHEDULE, $schedules );
		}

		/**
		 * Parse the filter config depending on how the config is stored as per different versions.
		 */
		private function parse_filter_config( $filter_config ) {
			$default_keys = $custom_keys = $post_type = $query = '';

			if ( $filter_config ) {
				// empty version.
				if ( ! isset( $filter_config['version'] ) ) {
					$filter_config['version'] = 0;
				}

				// version specific structure.
				switch ( intval( $filter_config['version'] ) ) {
					case 0:
						$params = array();
						if ( isset( $filter_config['default'] ) ) {
							$default_keys = $filter_config['default'];
							if ( ! empty( $default_keys ) ) {
								$params['key'] = array_keys( $default_keys );
								foreach ( $default_keys as $key => $attributes ) {
									$params['type'][] = $attributes['type'];
									$params['alias'][] = $attributes['alias'];
								}
							}
						}
						if ( isset( $filter_config['custom'] ) ) {
							$custom_keys = $filter_config['custom'];
							if ( ! empty( $custom_keys ) ) {
								$params['key'] = array_merge( $params['key'], array_keys( $custom_keys ) );
								foreach ( $custom_keys as $key => $attributes ) {
									$params['type'][] = $attributes['type'];
									$params['alias'][] = $attributes['alias'];
								}
							}
						}
						if ( isset( $filter_config['type'] ) ) {
							$post_type = $filter_config['type'];
							$params['post_type'] = $post_type;
						}

						$query = $this->create_filter_query( $params, true );
						break;
					case 1:
						if ( isset( $filter_config['default'] ) ) {
							$array = $filter_config['default'];
							$default_keys = array();
							foreach ( $array as $item ) {
								$default_keys[ $item['field'] ] = $item;
							}
						}
						if ( isset( $filter_config['custom'] ) ) {
							$array = $filter_config['custom'];
							$custom_keys = array();
							foreach ( $array as $item ) {
								$custom_keys[ $item['field'] ] = $item;
							}
						}
						if ( isset( $filter_config['type'] ) ) {
							$post_type = $filter_config['type'];
						}
						if ( isset( $filter_config['query'] ) ) {
							$query = $filter_config['query'];
						}
						break;
				}
			}

			return array(
				'default' => $default_keys,
				'custom' => $custom_keys,
				'type' => $post_type,
				'query' => $query,
			);
		}


		/**
		 * Adds the filter creator form
		 */
		public function _addFilterWizard( $chart_id ) {
			if ( ! $this->has_free_installed ) {
				return;
			}
			$hints          = array();
			// make this Visualizer_Plugin::CF_FILTER_CONFIG later
			$filter_config = get_post_meta( $chart_id, 'visualizer-filter-config', true );
			if ( $filter_config ) {
				$hints[]    = __( 'If you get an error while displaying the chart, try to reverse the order of the columns.', 'visualizer' );
			}

			$attributes     = $this->parse_filter_config( $filter_config );
			$default_keys  = $attributes['default'];
			$custom_keys   = $attributes['custom'];
			$post_type     = $attributes['type'];
			?>
			<div id="filter-wizard" class="visualizer-editor-lhs" style="display: none">
				<div class="filter-wizard-container">
					<div id="filter-wizard-rules">
						<select id="visualizer-post-types">
							<option value=""><?php _e( 'Select post type', 'visualizer' ); ?></option>
							<?php
							$post_types = get_post_types(
								array(
									'public'   => true,
								),
								'names'
							);
							foreach ( $post_types as $type ) {
								?>
								<option value="<?php echo $type; ?>" <?php echo $post_type === $type ? 'selected' : ''; ?>><?php echo $type; ?></option>
								<?php
							}
							?>
						</select>
						<select id="visualizer-post-fields" class="visualizer-chosen" multiple
								data-placeholder="<?php _e( 'Select properties', 'visualizer' ); ?>">
							<?php
							$fields = null;
							if ( $post_type ) {
								$fields = $this->get_post_type_properties( $post_type );
								if ( $fields ) {
									if ( is_null( $custom_keys ) || ! is_array( $custom_keys ) ) {
										$custom_keys    = array();
									}
									if ( is_null( $default_keys ) || ! is_array( $default_keys ) ) {
										$default_keys    = array();
									}
									$selected = array_merge( array_keys( $default_keys ), array_keys( $custom_keys ) );
									foreach ( $fields as $field ) {
										?>
										<option value="<?php echo $field; ?>" <?php echo in_array( $field, $selected, true ) ? 'selected' : ''; ?>><?php echo $field; ?></option>
										<?php
									}
								}
							}
							?>
						</select>
					</div>
					<div id="vz-no-fields"><?php echo sprintf( __( 'Please select the post type fields that you would like to plot. You can refer to our documentation %1$shere%2$s', 'visualizer' ), '<a href="https://docs.themeisle.com/article/673-how-to-create-charts-from-your-wordpress-posts" target="_blank">', '</a>' ); ?> </div>
					<div id="filter-wizard-details">
						<form id="filter-wizard-form">
							<input type="hidden" name="post_type" id="filter-wizard-post-type" value="<?php echo $post_type; ?>">
							<table class="filter-wizard">
								<tr class="filter-wizard-template " style="display: none">
									<td></td>
									<td><input type="hidden" name="key[]"><input type="text" name="alias[]"></td>
									<td>
										<select name="type[]">
											<?php foreach ( $this->getAllowedTypes() as $type ) { ?>
												<option value="<?php echo $type; ?>"><?php echo $type; ?></option>
											<?php } ?>
										</select>
									</td>
								</tr>
								<tr>
									<th><?php _e( 'Field', 'visualizer' ); ?></th>
									<th><?php _e( 'Alias', 'visualizer' ); ?></th>
									<th><?php _e( 'Type', 'visualizer' ); ?></th>
								</tr>
								<?php
								if ( ! empty( $default_keys ) ) {
									foreach ( $default_keys as $key => $array ) {
										?>
										<tr class="filter-wizard-new vz-filter-row" id="<?php echo $key; ?>">
											<td><?php echo $key; ?></td>
											<td><input type="hidden" name="key[]" value="<?php echo $key; ?>"><input
														type="text" name="alias[]" value="<?php echo $array['alias']; ?>">
											</td>
											<td>
												<select name="type[]">
													<?php foreach ( $this->getAllowedTypes() as $type ) { ?>
														<option value="<?php echo $type; ?>" <?php echo $type === $array['type'] ? 'selected' : ''; ?>><?php echo $type; ?></option>
													<?php } ?>
												</select>
											</td>
										</tr>
										<?php
									}
								}
								if ( ! empty( $custom_keys ) ) {
									foreach ( $custom_keys as $key => $array ) {
										?>
										<tr class="filter-wizard-new vz-filter-row" id="<?php echo $key; ?>">
											<td><?php echo $key; ?></td>
											<td><input type="hidden" name="key[]" value="<?php echo $key; ?>"><input
														type="text" name="alias[]" value="<?php echo $array['alias']; ?>">
											</td>
											<td>
												<select name="type[]">
													<?php foreach ( $this->getAllowedTypes() as $type ) { ?>
														<option value="<?php echo $type; ?>" <?php echo $type === $array['type'] ? 'selected' : ''; ?>><?php echo $type; ?></option>
													<?php } ?>
												</select>
											</td>
										</tr>
										<?php
									}
								}
								?>
							</table>
						</form>
					</div>
					<ul class="filter-wizard-hints">
					<?php
					if ( $hints ) {
						foreach ( $hints as $hint ) {
							?>
					<li><?php echo $hint; ?></li>
							<?php
						}
					}
					?>
					</ul>
				</div>
			</div>
			<?php
		}

		/**
		 * Update hook
		 *
		 * @param mixed $series Series options.
		 * @param mixed $data Chart data options.
		 */
		public function _addUpdateHook( $series, $data ) {
			echo 'if (win.visualizer1) {';
			echo 'win.visualizer1.render(', $series, ',', $data, ');';
			echo '}';
		}

		/**
		 * Gets the properties of the post type
		 *
		 * @access friendly
		 */
		function fetch_post_type_properties() {
			$nonce = isset( $_POST['nonce'] ) ? wp_verify_nonce( $_POST['nonce'], self::ACTION_FETCH_POST_TYPE_PROPS ) : '';
			if ( $nonce ) {
				$array = $this->get_post_type_properties( $_POST['post_type'] );
				$this->_sendResponse(
					array(
						'success' => true,
						'fields'  => $array,
					)
				);
				wp_die();
			}
		}

		/**
		 * Gets the properties of the post type
		 *
		 * @access private
		 */
		private function get_post_type_properties( $post_type ) {
			$array = null;
			$query = new WP_Query(
				array(
					'post_type'              => $post_type,
					'no_rows_found'          => false,
					'post_per_page'          => 1,
					'orderby'                => 'post_date',
					'order'                  => 'DESC',
					'fields'                 => 'ids',
					'update_post_meta_cache' => false,
					'update_post_term_cache' => false,
				)
			);
			$array = array();
			// @codingStandardsIgnoreLine WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			foreach ( self::$POST_DEFAULT_PROPERTIES as $name => $type ) {
				$array[] = $name;
				switch ( $type ) {
					case 'int':
						$array[]    = "sum($name)";
						$array[]    = "avg($name)";
						break;
					case 'string':
						$array[]    = "count($name)";
						break;
					case 'date':
						$array[]    = "day($name)";
						$array[]    = "week($name)";
						$array[]    = "month($name)";
						$array[]    = "year($name)";
						break;
				}
			}

			if ( $query->have_posts() ) {
				$id   = $query->posts[0];
				$meta = get_post_meta( $id, '', true );
				foreach ( $meta as $key => $values ) {
					$array[] = $key;
					$array[] = "count($key)";
				}
			}

			return $array;
		}

		/**
		 * Determines the db field and the function used from the input parameter.
		 *
		 * @param string $key    The input parameter.
		 * @return array
		 */
		private function get_column_and_function( $key ) {
			preg_match( '/(.*)\((.*)\)/', $key, $output );
			if ( count( $output ) === 3 ) {
				return array( $output[2], $output[1] );
			}
			return $key;
		}

		/**
		 * Creates the query corresponding to the filter parameters.
		 *
		 * @param array $params    The parameters.
		 * @param bool  $only_query    Return only the query or an array of other parameters as well.
		 * @return string|array
		 */
		private function create_filter_query( $params, $only_query = false ) {
			global $wpdb;
			$wpdb->hide_errors();

			// @codingStandardsIgnoreLine WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			$post_columns = array_keys( self::$POST_DEFAULT_PROPERTIES );
			$index  = 0;
			$custom_keys = array();
			$default_keys = array();
			$aggregate_function = false;
			// this will capture the order the elements are sent as this is very important. The first element is the x axis and second is the y axis
			$order = array();
			foreach ( $params['key'] as $field ) {
				if ( ! $field ) {
					$index ++;
					continue;
				}
				$key            = $field;
				$function       = '';
				$col_function   = $this->get_column_and_function( $field );

				if ( is_array( $col_function ) ) {
					$key        = $col_function[0];
					$function   = $col_function[1];
					// @codingStandardsIgnoreLine WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
					if ( ! $aggregate_function && in_array( $function, self::$AGGREGATOR_FUNCTIONS ) ) {
						$aggregate_function = true;
					}
				}
				$order[] = $key;
				if ( in_array( $key, $post_columns, true ) ) {
					$default_keys[] = array(
						'field'  => $field,
						'type'  => $params['type'][ $index ],
						'alias' => $params['alias'][ $index ],
						'function' => $function,
						'key'       => $key,
					);
				} else {
					$custom_keys[] = array(
						'field'  => $field,
						'type'  => $params['type'][ $index ],
						'alias' => $params['alias'][ $index ],
						'function' => $function,
						'key'       => $key,
					);
				}
				$index ++;
			}
			$query  = null;
			$values = array();
			// this will capture the first element in the select order
			$first = array_shift( $order );
			$group_by = '';
			if ( $aggregate_function ) {
				$group_by = ' GROUP BY id ';
			}

			if ( count( $custom_keys ) === 2 ) {
				// both custom keys
				$results = $wpdb->get_col( $wpdb->prepare( "SELECT id FROM {$wpdb->prefix}posts WHERE post_type=%s LIMIT 1000", $params['post_type'] ) );
				if ( $results ) {
					$ids    = implode( ',', array_fill( 0, count( $results ), '%d' ) );
					$fields = wp_list_pluck( $custom_keys, 'field' );
					$alias  = wp_list_pluck( $custom_keys, 'alias' );
					if ( ! $aggregate_function ) {
						// 2 custom keys
						$query  = "SELECT pm1.meta_value AS %s, pm2.meta_value AS %s FROM {$wpdb->prefix}postmeta pm1, {$wpdb->prefix}postmeta pm2 WHERE pm1.meta_key = %s AND pm2.meta_key = %s AND pm1.post_id = pm2.post_id AND pm1.post_id IN ($ids) ORDER BY pm1.post_id, FIELD(pm1.meta_key, %s), FIELD(pm2.meta_key, %s)";
						$values = array_merge( $alias, $fields, $results, $fields );
					} else {
						// 1 custom key and 1 function
						// a. custom_field_1, FUNCTION(custom_field_1)
						// b. custom_field_1, FUNCTION(custom_field_2)
						$query  = "SELECT func_1(pm1.meta_value) AS %s, func_2(pm2.meta_value) AS %s FROM {$wpdb->prefix}postmeta pm1, {$wpdb->prefix}postmeta pm2 WHERE pm1.meta_key = %s AND pm2.meta_key = %s AND pm1.post_id = pm2.post_id AND pm1.post_id IN ($ids) group_by_clause ORDER BY pm1.post_id";

						$index = 1;
						foreach ( $custom_keys as $attributes ) {
							$query = str_replace( 'func_' . $index, $attributes['function'], $query );
							// the group by clause will use the field that does not use an aggregate function.
							if ( empty( $attributes['function'] ) ) {
								$query = str_replace( 'group_by_clause', "GROUP BY pm{$index}.meta_value", $query );
							}
							$index++;
						}

						$keys   = wp_list_pluck( $custom_keys, 'key' );
						$values = array_merge( $alias, $keys, $results );
					}
				}
			} elseif ( 1 === count( $custom_keys ) ) {
				// one custom key and one post property
				// lets check if the first field is a custom key or a property so that the select statement can be created in the same order
				$select = '';
				$fields_default = wp_list_pluck( $default_keys, 'field' );
				$fields_custom  = wp_list_pluck( $custom_keys, 'field' );
				$alias_default  = wp_list_pluck( $default_keys, 'alias' );
				$alias_custom  = wp_list_pluck( $custom_keys, 'alias' );
				$aliases = array();
				if ( in_array( $first, $post_columns, true ) ) {
					$select = $fields_default[0] . ' AS %s, pm.meta_value AS %s';
					$aliases = array( $alias_default[0], $alias_custom[0] );
				} else {
					$select = 'pm.meta_value AS %s, ' . $fields_default[0] . ' AS %s';
					$aliases = array( $alias_default[0], $alias_custom[0] );
				}
				$query    = "SELECT $select FROM {$wpdb->prefix}posts p LEFT JOIN {$wpdb->prefix}postmeta pm ON p.id = pm.post_id and pm.meta_key=%s WHERE p.post_type=%s $group_by";
				$values   = array_merge( $values, $aliases );
				$values[] = implode( ',', $fields_custom );
				$values[] = $params['post_type'];
			} else {
				// both post properties
				$fields = wp_list_pluck( $default_keys, 'field' );
				$aliases = wp_list_pluck( $default_keys, 'alias' );

				// the group by clause will use the field that does not use an aggregate function.
				foreach ( $default_keys as $attributes ) {
					if ( empty( $attributes['function'] ) ) {
						if ( ! in_array( $attributes['field'], array( 'comment_count', 'post_status' ), true ) ) {
							$group_by = ' GROUP BY ' . $attributes['field'];
						}
					}
				}

				$query    = 'SELECT ' . implode( ' AS %s,', $fields ) . " AS %s FROM {$wpdb->prefix}posts WHERE post_type=%s $group_by";
				$values   = array_merge( $values, $aliases );
				$values[] = $params['post_type'];
			}

			do_action( 'themeisle_log_event', Visualizer_Plugin::NAME, sprintf( 'default_keys %s, custom_keys %s for query %s with values %s', print_r( $default_keys, true ), print_r( $custom_keys, true ), $query, print_r( $values, true ) ), 'debug', __FILE__, __LINE__ );

			// @codingStandardsIgnoreLine WordPress.DB.PreparedSQL.NotPrepared
			$query  = $wpdb->prepare( $query, $values );

			if ( $only_query ) {
				return $query;
			}

			// lets see what the order of default/custom keys are for processing
			$keys = array_merge( $default_keys, $custom_keys );
			if ( ! in_array( $first, $post_columns, true ) ) {
				$keys = array_merge( $custom_keys, $default_keys );
			}

			return array(
				'query' => $query,
				'default_keys' => $default_keys,
				'custom_keys' => $custom_keys,
				'keys' => $keys,
			);
		}

		/**
		 * Gets the data of the selected post type/properties
		 *
		 * @access friendly
		 */
		function fetch_post_type_data() {
			global $wpdb;
			$wpdb->hide_errors();
			// @codingStandardsIgnoreLine WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			$post_columns = array_keys( self::$POST_DEFAULT_PROPERTIES );
			$nonce    = isset( $_POST['nonce'] ) ? wp_verify_nonce( $_POST['nonce'], self::ACTION_FETCH_POST_TYPE_DATA ) : '';
			$chart_id = isset( $_POST['chart_id'] ) ? filter_var( $_POST['chart_id'], FILTER_VALIDATE_INT ) : '';
			if ( $nonce && $chart_id ) {
				parse_str( $_POST['props'], $params );
				$params = array_filter( $params );
				$query_attributes = $this->create_filter_query( $params, false );
				$custom_keys  = $query_attributes['custom_keys'];
				$default_keys = $query_attributes['default_keys'];
				$keys = $query_attributes['keys'];
				$query = $query_attributes['query'];

				$data = array();
				if ( $query ) {
					$source = new Visualizer_Source_Query( $query );
					$results = $source->fetch( false, true, true );
					if ( $results ) {
						$aliases = array();
						$types   = array();
						foreach ( $keys as $key => $array ) {
							$aliases[] = $array['alias'];
							$types[]   = $array['type'];
						}
						$data[] = $aliases;
						$data[] = $types;
						foreach ( $results as $row ) {
							$data[] = $row;
						}
					}
					$wpdb->show_errors();
				}
				if ( $data ) {
					// we will update a transient.
					update_post_meta(
						$chart_id,
						'__transient-visualizer-filter-config',
						array(
							'default' => $default_keys,
							'custom'  => $custom_keys,
							'type'    => $params['post_type'],
							'version' => 1, // change this version whenever we change the structure of the config object and add this version to parse_filter_config
						)
					);
					update_post_meta(
						$chart_id,
						'__transient-' . Visualizer_Plugin::CF_DB_QUERY,
						$query
					);
				}
				$this->_sendResponse(
					array(
						'success' => true,
						'data'    => json_encode( $data ),
					)
				);
				wp_die();
			}// End if().
		}

		/**
		 * Gets the chart data corresponding to a specific chart
		 *
		 * @since 1.4.2.3
		 *
		 * @access public
		 */
		public function fetchData() {
			$nonce    = isset( $_GET['nonce'] ) ? wp_verify_nonce( $_GET['nonce'], Visualizer_Pro::ACTION_FETCH_DATA ) : '';
			$chart_id = isset( $_GET['chart_id'] ) ? filter_var( $_GET['chart_id'], FILTER_VALIDATE_INT ) : '';
			if ( $chart_id ) {
				$chart   = get_post( $chart_id );
				$success = $chart && $chart->post_type === Visualizer_Plugin::CPT_VISUALIZER;
				$type    = get_post_meta( $chart->ID, Visualizer_Plugin::CF_CHART_TYPE, true );
				$series  = apply_filters( Visualizer_Plugin::FILTER_GET_CHART_SERIES, get_post_meta( $chart->ID, Visualizer_Plugin::CF_SERIES, true ), $chart->ID, $type );
				$data    = apply_filters( Visualizer_Plugin::FILTER_GET_CHART_DATA, unserialize( $chart->post_content ), $chart->ID, $type );
				if ( $success ) {
					$results = array(
						'success' => true,
						'series'  => $series,
						'data'    => $data,
					);
					$this->_sendResponse( $results );
				}
			}
			wp_die();
		}

		/**
		 * Sends the response to the ajax requester
		 *
		 * @access private
		 */
		private function _sendResponse( $results ) {
			$method_checker = new ReflectionMethod( 'Visualizer_Module_Chart', '_sendResponse' );
			if ( $method_checker->isStatic() ) {
				Visualizer_Module_Chart::_sendResponse( $results );
			} else {
				header( 'Content-type: application/json' );
				nocache_headers();
				echo json_encode( $results );
			}
		}

		/**
		 * Gets the allowed types
		 *
		 * @access private
		 */
		private function getAllowedTypes() {
			return method_exists( 'Visualizer_Source', 'getAllowedTypes' ) ? Visualizer_Source::getAllowedTypes() : array(
				'string',
				'number',
				'boolean',
				'date',
				'datetime',
				'timeofday',
			);
		}

		/**
		 * Write debug messages
		 *
		 * @param string $msg Message to report.
		 */
		static function writeDebug( $msg ) {
			if ( Visualizer_Pro_Debug ) {
				try {
					mkdir( Visualizer_Pro_PATH . '/tmp' );
				} catch ( Exception $e ) {
					error_log( 'Unable to create report dir' );
				}
				// @unlink(Visualizer_Pro_PATH . "/tmp/log.log");
				// phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date, WordPress.DateTime.CurrentTimeTimestamp.Requested
				file_put_contents( Visualizer_Pro_PATH . '/tmp/log.log', date( 'F j, Y H:i:s' ) . ' - ' . $msg . "\n", FILE_APPEND );
			}
		}

		/**
		 * Return if the user is a business user
		 *
		 * @param mixed $default Default value.
		 *
		 * @return boolean
		 */
		function visualizer_is_business( $default = null ) {
			// proceed to check the plan only if the license is active.
			if ( ! defined( 'TI_UNIT_TESTING' ) ) {
				$status = apply_filters( 'product_visualizer_license_status', false );
				if ( $status !== 'valid' ) {
					return $default;
				}
			}

			$plan = apply_filters( 'product_visualizer_license_plan', 0 );
			$plan = intval( $plan );
			return ( $plan > 1 );
		}

		/**
		 * Adds ajax action for the pro
		 *
		 * @param mixed $plugin Free plugin class.
		 *
		 * @return array
		 */
		public function _getAjaxAction( $plugin ) {
			return array( Visualizer_Pro::ACTION_FETCH_DATA, 'fetchData', $this );
		}

		/**
		 * Add form elements
		 */
		public function _addFormElements() {
			if ( ! $this->has_free_installed ) {
				return;
			}
			echo '<input type="hidden" id="chart-data" name="chart_data">';
		}

		/**
		 * Add editor elements
		 */
		public function _addEditorElements() {
			if ( ! $this->has_free_installed ) {
				return;
			}
			$fetch_link = add_query_arg(
				array(
					'action' => Visualizer_Pro::ACTION_FETCH_DATA,
					'nonce'  => wp_create_nonce(),
				),
				admin_url( 'admin-ajax.php' )
			);
			?>
			<div id="editor-resources" data-ajax="<?php echo $fetch_link; ?>"></div>
			<?php
			$query_args_charts = array(
				'post_type'      => Visualizer_Plugin::CPT_VISUALIZER,
				'posts_per_page' => - 1,
			);
			$charts            = array();
			$query             = new WP_Query( $query_args_charts );
			?>
			<input type="button" class="button from-chart-btn" id="existing-chart" name="existing_chart" value="<?php esc_attr_e( 'From Chart', 'visualizer' ); ?>:">
			<select id="chart-id" name="chart_id">
				<?php
				while ( $query->have_posts() ) {
					$chart    = $query->next_post();
					$settings = get_post_meta( $chart->ID, Visualizer_Plugin::CF_SETTINGS, true );
					?>
					<option value="<?php echo $chart->ID; ?>"><?php echo empty( $settings['title'] ) ? '#' . $chart->ID : $settings['title']; ?></option>
					<?php
				}
				?>
			</select>
			<div>
				<button id="editor-chart-button" class="button button-primary show-live-btn" data-current="chart"
						data-t-chart="<?php esc_attr_e( 'Show Live Editor', 'visualizer' ); ?>"
						data-t-editor="<?php esc_attr_e( 'Show Chart', 'visualizer' ); ?>"><?php esc_attr_e( 'Show Live Editor', 'visualizer' ); ?></button>
			</div>

			<?php
		}

		/**
		 * Add inline fronted style.
		 *
		 * @param array $arguments Filter arguments.
		 */
		public function loadFrontendInlineStyle( &$arguments ) {
			$inline_css = '.visualizer-editor-front-container{position:relative;width:auto;margin:5%;background:#fff}.visualizer-editor-front{overflow:hidden;width:100%;height:500px}.visualizer-editor-front-actions{padding-bottom:3px}.visualizer-editor-save,.visualizer-editor-cancel{margin:0 4px;padding:2px 15px}.visualizer-cw-error .visualizer-actions{display:none !important;}';
			if ( ! empty( $arguments[0] ) ) {
				$arguments[0] = str_replace( '</style>', "$inline_css </style>", $arguments[0] );
			}
		}

		/**
		 * WPML set element language details.
		 *
		 * @param int    $post_id Post ID.
		 * @param int    $translated_post_id Translated post ID.
		 * @param string $language_code Selected language code.
		 */
		public function set_wpml_element_language_details( $post_id = 0, $translated_post_id = 0, $language_code = '' ) {
			global $sitepress;
			if ( $post_id && ! empty( $language_code ) ) {
				$post_type          = Visualizer_Plugin::CPT_VISUALIZER;
				$wpml_element_type  = apply_filters( 'wpml_element_type', $post_type );
				$trid               = $sitepress->get_element_trid( $post_id, 'post_' . $post_type );
				$recursive          = false;
				$original_post_id   = $translated_post_id;
				$original_lang_code = $language_code;
				if ( empty( $trid ) ) {
					$translated_post_id = $post_id;
					$trid               = $post_id;
					$recursive          = true;
					$language_code      = icl_get_default_language();
				}

				$language_args = array(
					'element_id'           => $translated_post_id,
					'element_type'         => $wpml_element_type,
					'trid'                 => $trid,
					'language_code'        => $language_code,
					'source_language_code' => ! $recursive ? icl_get_default_language() : null,
				);
				// Set language details.
				do_action( 'wpml_set_element_language_details', $language_args );

				if ( $recursive ) {
					$this->set_wpml_element_language_details( $post_id, $original_post_id, $original_lang_code );
				}
			}
		}

		/**
		 * Multilingual Support.
		 *
		 * @param int $chart_id Chart ID.
		 * @return bool Default false
		 */
		public function addMultilingualSupport( $chart_id ) {
			global $sitepress;
			if ( function_exists( 'icl_get_languages' ) && $sitepress instanceof \SitePress ) {
				$language     = icl_get_languages();
				$current_lang = icl_get_current_language();
				$default_lang = icl_get_default_language();
				$post_info    = wpml_get_language_information( null, $chart_id );

				$translations = array();
				if ( ! empty( $post_info ) && ( $default_lang === $post_info['language_code'] ) ) {
					$trid         = $sitepress->get_element_trid( $chart_id, 'post_' . Visualizer_Plugin::CPT_VISUALIZER );
					$translations = $sitepress->get_element_translations( $trid );
				}
				if ( empty( $translations ) ) {
					return;
				}
				?>
				<hr><div class="visualizer-languages-list">
					<?php
					foreach ( $language as $lang ) {
						$lang_code = $lang['code'];
						if ( $current_lang !== $lang_code ) {
							$lang_chart_exist = get_post_meta( $chart_id, Visualizer_Plugin::NAME . '_' . $lang_code, true );
							$translate_info = isset( $translations[ $lang_code ] ) ? $translations[ $lang_code ] : false;
							?>
							<a href="javascript:;" data-lang_code="<?php echo esc_attr( $lang_code ); ?>" data-chart="<?php echo $translate_info ? $translate_info->element_id : $chart_id; ?>">
								<img src="<?php echo esc_url( $lang['country_flag_url'] ); ?>" alt="<?php echo esc_attr( $lang['translated_name'] ); ?>">
								<?php if ( $translate_info ) : ?>
									<i class="otgs-ico-edit"></i>
								<?php else : ?>
									<i class="otgs-ico-add"></i>
								<?php endif; ?>
							</a>
							<?php
						}
					}
					?>
				</div>
				<?php
			}
		}

		/** WooCommerce reports api permissions.
		 *
		 * @param bool   $permission Permission.
		 * @param string $context Request context.
		 * @param int    $object_id Object ID.
		 * @param string $object API namespace.
		 *
		 * @return bool
		 */
		public function WooCommerceRestCheckPermissions( $permission, $context, $object_id, $object ) {
			if ( $permission ) {
				return $permission;
			}
			if ( 'read' === $context && 'reports' === $object ) {
				return true;
			}
			return $permission;
		}
	}
} // End if().
if ( class_exists( 'Visualizer_Pro' ) ) {
	// @codingStandardsIgnoreStart WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase
	global $Visualizer_Pro;
	$Visualizer_Pro = new Visualizer_Pro();
	// @codingStandardsIgnoreEnd WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase
}
