<?php

if ( ! class_exists( 'Visualizer_Pro_Wrapper_DB' ) ) {

	/**
	 * Class Visualizer_Pro_Wrapper_DB
	 *
	 * Wrapper for the remote db features
	 */
	class Visualizer_Pro_Wrapper_DB {

		/**
		 * Connects to the database.
		 *
		 * @access private
		 */
		private static function connect( $params ) {
			require Visualizer_Pro_PATH . '/vendor/adodb/adodb-php/adodb.inc.php';

			// let's get the driver names specific to the above library.
			$driver = null;
			switch ( $params['db_type'] ) {
				case 'MySQL':
					$driver = 'mysqli';
					break;
				case 'PostgreSQL':
					$driver = 'postgres7';
					break;
				case 'IBM DB2':
					$driver = 'db2';
					break;
				case 'Oracle':
					$driver = 'oci8';
					break;
				case 'MS SQL':
					$driver = 'mssqlnative';
					break;
				case 'SQLite':
					// fall-through.
				case 'SQLite3':
					$driver = 'sqlite3';
					break;
			}

			if ( $driver ) {
				$db = newAdoConnection( $driver );
				$db->connect( $params['db_conn_server'], $params['db_conn_user'], $params['db_conn_passwd'], $params['db_conn_db'] );
				if ( ! $db->isConnected() ) {
					do_action( 'themeisle_log_event', Visualizer_Plugin::NAME, sprintf( 'Unable to connect to database with params %s', print_r( $params, true ) ), 'error', __FILE__, __LINE__ );
					return false;
				}
				return $db;
			}
			return false;
		}

		/**
		 * Wrapper for checking the remote db connection.
		 *
		 * @access public
		 */
		public static function checkConnection( $params ) {
			return self::connect( $params ) !== false;
		}

		/**
		 * Wrapper for getting the table, column mapping for all tables.
		 *
		 * @access public
		 */
		public static function getMeta( $params ) {
			$mapping = array();

			if ( false !== ( $db = self::connect( $params ) ) ) {
				$tables = $db->metaTables();
				foreach ( $tables as $table ) {
					$mapping[ $table ] = $db->metaColumnNames( $table, true );
				}
			}

			do_action( 'themeisle_log_event', Visualizer_Plugin::NAME, sprintf( 'Meta for params %s is %s', print_r( $params, true ), print_r( $mapping, true ) ), 'debug', __FILE__, __LINE__ );

			return $mapping;
		}

		/**
		 * Wrapper for getting the results.
		 *
		 * @access public
		 */
		public static function getResults( $query, $as_html, $results_as_numeric_array, $raw_results, $chart_id, $params ) {
			$final_results = array( 'error' => '' );

			if ( false !== ( $db = self::connect( $params ) ) ) {
				$db->setFetchMode( $raw_results ? ADODB_FETCH_DEFAULT : ADODB_FETCH_NUM );
				$rows   = $db->getAll( $query );
				$results = $db->execute( $query );
				if ( false !== $results ) {
					$headers = array();
					$cols = $results->fieldCount();
					for ( $i = 0;$i < $cols;$i++ ) {
						$field = $results->FetchField( $i );
						$type = 'string';
						switch ( $db->metaType( $field->type ) ) {
							case 'I':
							case 'N':
							case 'L':
								$type = 'number';
								break;
							case 'D':
							case 'T':
								$type = 'date';
								break;
						}
						$headers[] = array(
							'type' => $type,
							'label' => $field->name,
						);
					}

					$final_results = array(
						'headers' => $headers,
						'results' => $rows,
						'error' => '',
					);
				} else {
					do_action( 'themeisle_log_event', Visualizer_Plugin::NAME, sprintf( 'Error while firing remote query %s = %s', $query, $db->errorMsg() ), 'error', __FILE__, __LINE__ );
					$final_results['error'] = $db->errorMsg();
				}

				do_action( 'themeisle_log_event', Visualizer_Plugin::NAME, sprintf( 'Firing remote query %s to get results %s with error %s', $query, print_r( $final_results, true ), $db->errorMsg() ), 'debug', __FILE__, __LINE__ );
			} else {
				do_action( 'themeisle_log_event', Visualizer_Plugin::NAME, sprintf( 'Unable to connect to database while firing remote query %s', $query ), 'error', __FILE__, __LINE__ );
				$final_results['error'] = __( 'Error: Unable to connect to database', 'visualizer' );
			}

			return $final_results;
		}
	}

}

new Visualizer_Pro_Wrapper_DB();

