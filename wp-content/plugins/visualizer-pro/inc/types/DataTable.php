<?php

// +----------------------------------------------------------------------+
// | Copyright 2013  Madpixels  (email : visualizer@madpixels.net)        |
// +----------------------------------------------------------------------+
// | This program is free software; you can redistribute it and/or modify |
// | it under the terms of the GNU General Public License, version 2, as  |
// | published by the Free Software Foundation.                           |
// |                                                                      |
// | This program is distributed in the hope that it will be useful,      |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of       |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        |
// | GNU General Public License for more details.                         |
// |                                                                      |
// | You should have received a copy of the GNU General Public License    |
// | along with this program; if not, write to the Free Software          |
// | Foundation, Inc., 51 Franklin St, Fifth Floor, Boston,               |
// | MA 02110-1301 USA                                                    |
// +----------------------------------------------------------------------+
// | Author: Eugene Manuilov <eugene@manuilov.org>                        |
// +----------------------------------------------------------------------+
/**
 * Class for datatables.net table chart sidebar settings.
 * We have deliberately NOT named this Visualizer_Render_Sidebar_Type_DataTable as that is already defined in free.
 *
 * @since 1.0.0
 */
class DataTable extends Visualizer_Render_Sidebar {

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @param array $data The data what has to be associated with this render.
	 */
	public function __construct( $data = array() ) {
		parent::__construct( $data );

		$this->hooks();
	}

	/**
	 * Registers additional hooks.
	 *
	 * @access protected
	 */
	protected function hooks() {
		add_action( 'visualizer_chart_settings_DataTable', array( $this, 'settings' ), 10, 1 );
	}

	/**
	 * Adds additional settings.
	 *
	 * @access public
	 */
	function settings( $section ) {
		switch ( $section ) {
			case 'table':
				echo '<div class="viz-section-delimiter section-delimiter"></div>';

				self::_renderCheckboxItem(
					esc_html__( 'Enable Search', 'visualizer' ),
					'searching_bool',
					$this->searching_bool,
					'true',
					esc_html__( 'To enable searching on columns.', 'visualizer' )
				);

				echo '<div class="viz-section-delimiter section-delimiter"></div>';

				self::_renderSelectItem(
					esc_html__( 'Enable selection', 'visualizer' ),
					'select_style',
					! empty( $this->select_style ) ? $this->select_style : 'single',
					array(
						'single'  => esc_html__( 'Single item', 'visualizer' ),
						'multi'  => esc_html__( 'Multiple items', 'visualizer' ),
						'os'  => esc_html__( 'Operating System dependent', 'visualizer' ),
					),
					esc_html__( 'Determines how items can be selected from the table.', 'visualizer' )
				);

				echo '<div class="viz-section-delimiter section-delimiter"></div>';

				self::_renderSelectItem(
					esc_html__( 'Enable selection of', 'visualizer' ),
					'select_items',
					! empty( $this->select_items ) ? $this->select_items : 'row',
					array(
						'row'  => esc_html__( 'Row(s)', 'visualizer' ),
						'column'  => esc_html__( 'Column(s)', 'visualizer' ),
						'cell'  => esc_html__( 'Cell(s)', 'visualizer' ),
					),
					esc_html__( 'Determines which item(s) can be selected from the table.', 'visualizer' )
				);

				echo '<div class="viz-section-delimiter section-delimiter"></div>';

				self::_renderCheckboxItem(
					esc_html__( 'Enable information of selection', 'visualizer' ),
					'select_info_bool',
					$this->select_info_bool,
					'true',
					esc_html__( 'Enable / disable the display for item selection information in the table summary.', 'visualizer' )
				);
				break;

			case 'style':
				self::_renderSectionStart( esc_html__( 'Selected Table Item', 'visualizer' ) );

					self::_renderSectionDescription( esc_html__( 'These values will be applied once you save the chart.', 'visualizer' ) );

					self::_renderColorPickerItem(
						esc_html__( 'Background Color', 'visualizer' ),
						'customcss[selectedTableItem][background-color]',
						isset( $this->customcss['selectedTableItem']['background-color'] ) ? $this->customcss['selectedTableItem']['background-color'] : null,
						null
					);

					self::_renderColorPickerItem(
						esc_html__( 'Color', 'visualizer' ),
						'customcss[selectedTableItem][color]',
						isset( $this->customcss['selectedTableItem']['color'] ) ? $this->customcss['selectedTableItem']['color'] : null,
						null
					);

					self::_renderTextItem(
						esc_html__( 'Text Orientation', 'visualizer' ),
						'customcss[selectedTableItem][transform]',
						isset( $this->customcss['selectedTableItem']['transform'] ) ? $this->customcss['selectedTableItem']['transform'] : null,
						esc_html__( 'In degrees.', 'visualizer' ),
						'',
						'number',
						array(
							'min' => -180,
							'max' => 180,
						)
					);
				self::_renderSectionEnd();
				break;

			case 'pagination':
				echo '<div class="viz-section-delimiter section-delimiter"></div>';

				self::_renderCheckboxItem(
					esc_html__( 'Enable paging display length', 'visualizer' ),
					'lengthChange_bool',
					$this->lengthChange_bool, // @codingStandardsIgnoreLine WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
					'true',
					esc_html__( 'Allow user to change the paging display length of the table.', 'visualizer' )
				);
				break;
		}
	}

	/**
	 * Renders template.
	 *
	 * @since 1.0.0
	 *
	 * @abstract
	 * @access protected
	 */
	protected function _toHTML() {
		// empty;
	}


}
