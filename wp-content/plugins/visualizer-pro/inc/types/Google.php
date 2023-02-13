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
 * Class for generic Google chart settings.
 *
 * @since 1.9.2
 */
class Visualizer_Render_Sidebar_Type_Google extends Visualizer_Render_Sidebar_Google {

	/**
	 * The type of chart.
	 *
	 * @since 1.9.2
	 *
	 * @access protected
	 * @var string
	 */
	private $_chart_type;

	/**
	 * Constructor.
	 *
	 * @since 1.9.2
	 *
	 * @access public
	 * @param string $type The type of chart.
	 * @param array  $data The data what has to be associated with this render.
	 */
	public function __construct( $type, $data = array() ) {
		parent::__construct( $data );
		$this->_chart_type = $type;
		$this->_renderZoomSettings();
	}

	/**
	 * Renders zoom/pan settings under the General block.
	 *
	 * @since 1.9.2
	 *
	 * @access private
	 */
	private function _renderZoomSettings() {
		// which types do not support explorer functions.
		foreach ( array( 'Candlestick', 'Gauge', 'Geo', 'Pie', 'Table', 'Timeline' ) as $type ) {
			if ( strpos( $this->_chart_type, $type ) !== false ) {
				return;
			}
		}

		self::_renderSectionStart( esc_html__( 'Zoom/Pan', 'visualizer' ), false );

			self::_renderCheckboxItem(
				esc_html__( 'Enable zoom/pan', 'visualizer' ),
				'explorer_enabled',
				isset( $this->explorer_enabled ) ? true : false,
				'true',
				esc_html__( 'Enable users to pan horizontally and vertically by dragging, and to zoom in and out by scrolling. This only works with continuous axes (such as numbers or dates).', 'visualizer' )
			);

			self::_renderCheckboxItem(
				esc_html__( 'Drag to Zoom', 'visualizer' ),
				'explorer_actions[]',
				isset( $this->explorer_actions ) && in_array( 'dragToZoom', $this->explorer_actions, true ) ? true : false,
				'dragToZoom',
				esc_html__( 'Change the default from scroll to zoom to drag to zoom.', 'visualizer' )
			);

			self::_renderCheckboxItem(
				esc_html__( 'Drag to Pan', 'visualizer' ),
				'explorer_actions[]',
				isset( $this->explorer_actions ) && in_array( 'dragToPan', $this->explorer_actions, true ) ? true : false,
				'dragToPan',
				esc_html__( 'Drag to pan around the chart horizontally and vertically.', 'visualizer' )
			);

			self::_renderCheckboxItem(
				esc_html__( 'Right click to reset', 'visualizer' ),
				'explorer_actions[]',
				isset( $this->explorer_actions ) && in_array( 'rightClickToReset', $this->explorer_actions, true ) ? true : false,
				'rightClickToReset',
				esc_html__( 'Right clicking on the chart returns it to the original pan and zoom level. Only works with "Drag to Zoom" or "Drag to Pan"', 'visualizer' )
			);

		self::_renderSectionEnd();
	}


	/**
	 * Renders template.
	 *
	 * @since 1.9.2
	 *
	 * @abstract
	 * @access protected
	 */
	protected function _toHTML() {
		// not implemented
	}

}
