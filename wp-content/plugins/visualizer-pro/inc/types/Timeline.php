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
 * Class for table chart sidebar settings.
 *
 * @since 1.0.0
 */
class Visualizer_Render_Sidebar_Type_Timeline extends Visualizer_Render_Sidebar_Columnar {

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
		// @codingStandardsIgnoreLine WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		$this->_supportsAnimation = false;
		$this->_renderGeneralSettings();
		$this->_renderTimelineSettings();
		$this->_renderViewSettings();
		$this->_renderAdvancedSettings();
	}

	/**
	 * Renders chart general settings group.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _renderGeneralSettings() {
		self::_renderGroupStart( esc_html__( 'General Settings', 'visualizer' ) );
			self::_renderSectionStart( esc_html__( 'Title', 'visualizer' ), false );
				self::_renderTextItem(
					esc_html__( 'Chart Title', 'visualizer' ),
					'title',
					$this->title,
					esc_html__( 'Text to display in the back-end admin area.', 'visualizer' )
				);
				self::_renderTextAreaItem(
					esc_html__( 'Chart Description', 'visualizer' ),
					'description',
					$this->description,
					sprintf( esc_html__( 'Description to display in the structured data schema as explained %1$shere%2$s', 'visualizer' ), '<a href="https://developers.google.com/search/docs/data-types/dataset#dataset" target="_blank">', '</a>' )
				);
			self::_renderSectionEnd();

			self::_renderSectionStart( esc_html__( 'License & Creator', 'visualizer' ), false );
				self::_renderTextItem(
					esc_html__( 'License', 'visualizer' ),
					'license',
					$this->license,
					''
				);
				self::_renderTextItem(
					esc_html__( 'Creator', 'visualizer' ),
					'creator',
					$this->creator,
					''
				);
			self::_renderSectionEnd();
		self::_renderGroupEnd();
	}


	/**
	 * Renders timeline items.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _renderTimelineSettings() {
		self::_renderGroupStart( esc_html__( 'Timeline Settings', 'visualizer' ) );
			self::_renderSectionStart();

				self::_renderCheckboxItem(
					esc_html__( 'Show Row Label', 'visualizer' ),
					'showRowLabels',
					$this->showRowLabels, // @codingStandardsIgnoreLine WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
					1,
					esc_html__( 'If checked, shows the category/row label.', 'visualizer' )
				);

				echo '<div class="viz-section-delimiter section-delimiter"></div>';

				self::_renderCheckboxItem(
					esc_html__( 'Group by Row Label', 'visualizer' ),
					'groupByRowLabel',
					$this->groupByRowLabel, // @codingStandardsIgnoreLine WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
					1,
					esc_html__( 'If checked, groups the bars on the basis of the category/row label.', 'visualizer' )
				);

				echo '<div class="viz-section-delimiter section-delimiter"></div>';

				self::_renderCheckboxItem(
					esc_html__( 'Color by Row Label', 'visualizer' ),
					'colorByRowLabel',
					$this->colorByRowLabel, // @codingStandardsIgnoreLine WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
					1,
					esc_html__( 'If checked, colors every bar on the row the same.', 'visualizer' )
				);

				echo '<div class="viz-section-delimiter section-delimiter"></div>';

				self::_renderColorPickerItem(
					esc_html__( 'Single Color', 'visualizer' ),
					'singleColor',
					isset( $this->singleColor ) ? $this->singleColor : null, // @codingStandardsIgnoreLine WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
					null
				);

			self::_renderSectionEnd();
		self::_renderGroupEnd();

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
	}

}
