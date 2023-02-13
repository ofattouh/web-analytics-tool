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
 * Class for combo chart sidebar settings.
 *
 * @since 1.0.0
 */
class Visualizer_Render_Sidebar_Type_Combo extends Visualizer_Render_Sidebar_Columnar {

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
		// @codingStandardsIgnoreLine WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		$this->_includeCurveTypes = false;
	}

	/**
	 * Renders template.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _toHTML() {
		$this->_renderGeneralSettings();
		$this->_renderAxesSettings();
		$this->_renderComboSettings();
		$this->_renderSeriesSettings();
		$this->_renderViewSettings();
		$this->_renderAdvancedSettings();
	}

	/**
	 * Renders general settings block for horizontal axis settings.
	 *
	 * @since 1.4.3
	 *
	 * @access protected
	 */
	protected function _renderHorizontalAxisGeneralSettings() {
		parent::_renderHorizontalAxisGeneralSettings();
		$this->_renderHorizontalAxisFormatField();
	}

	/**
	 * Renders combo settings block for horizontal axis settings.
	 *
	 * @since 1.4.3
	 *
	 * @access protected
	 */
	protected function _renderComboSettings() {
		self::_renderGroupStart( esc_html__( 'Combo Settings', 'visualizer' ) );
			self::_renderSectionStart();

			self::_renderSelectItem(
				esc_html__( 'Chart Type', 'visualizer' ),
				'seriesType',
				$this->seriesType, // @codingStandardsIgnoreLine WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				array(
					'area'          => esc_html__( 'Area', 'visualizer' ),
					'bars'          => esc_html__( 'Bar', 'visualizer' ),
					'candlesticks'  => esc_html__( 'Candlesticks', 'visualizer' ),
					'line'          => esc_html__( 'Line', 'visualizer' ),
					'steppedArea'   => esc_html__( 'Stepped Area', 'visualizer' ),
				),
				esc_html__( 'Select the default chart type.', 'visualizer' )
			);

			self::_renderSectionEnd();
		self::_renderGroupEnd();

	}

	/**
	 * Renders combo series settings
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _renderSeriesSettings() {
		parent::_renderSeriesSettings();
	}

	/**
	 * Renders concreate series settings.
	 *
	 * @since 1.4.0
	 *
	 * @access protected
	 * @param int $index The series index.
	 */
	protected function _renderSeries( $index ) {
		parent::_renderSeries( $index );

		echo '<div class="viz-section-delimiter section-delimiter"></div>';

		self::_renderSelectItem(
			esc_html__( 'Chart Type', 'visualizer' ),
			'series[' . $index . '][type]',
			isset( $this->series[ $index ]['type'] ) ? $this->series[ $index ]['type'] : '',
			array(
				''              => '',
				'area'          => esc_html__( 'Area', 'visualizer' ),
				'bars'          => esc_html__( 'Bar', 'visualizer' ),
				'candlesticks'  => esc_html__( 'Candlesticks', 'visualizer' ),
				'line'          => esc_html__( 'Line', 'visualizer' ),
				'steppedArea'   => esc_html__( 'Stepped Area', 'visualizer' ),
			),
			esc_html__( 'Select the type of chart to show for this series.', 'visualizer' )
		);

	}

}
