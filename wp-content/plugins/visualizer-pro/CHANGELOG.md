##### [Version 1.12.1](https://github.com/Codeinwp/visualizer-pro/compare/v1.12.0...v1.12.1) (2022-11-17)

Fixed PHP error when Polylang plugin is activated

#### [Version 1.12.0](https://github.com/Codeinwp/visualizer-pro/compare/v1.11.1...v1.12.0) (2022-11-10)

- Compatibility with the WPML translation plugin for translating charts
- Integration with Woocommerce Data endpoints for creating charts

##### [Version 1.11.1](https://github.com/Codeinwp/visualizer-pro/compare/v1.11.0...v1.11.1) (2022-10-12)

- Updated dependencies
- Fix control Type error
- Fixed simple editor scrolling issue
- Fix filter broken layout
- Fix chartJs javascript error when rendering multiple charts

#### [Version 1.11.0](https://github.com/Codeinwp/visualizer-pro/compare/v1.10.7...v1.11.0) (2022-09-21)

- Add data filter support for charts

##### [Version 1.10.7](https://github.com/Codeinwp/visualizer-pro/compare/v1.10.6...v1.10.7) (2022-06-21)

* Update dependencies
* Enhance build process

##### [Version 1.10.6](https://github.com/Codeinwp/visualizer-pro/compare/v1.10.5...v1.10.6) (2022-05-27)

Fix multiple charts on the same page load inconsistently

##### [Version 1.10.5](https://github.com/Codeinwp/visualizer-pro/compare/v1.10.4...v1.10.5) (2022-01-19)

- Remove cache when editing a chart from front-end

##### [Version 1.10.4](https://github.com/Codeinwp/visualizer-pro/compare/v1.10.3...v1.10.4) (2021-12-20)

- Improve CSS performance
- Fix Radar/Spider charts not rendered
- Fix compatibility issue with Pirate Parrot plugin
- Improve JS performance

##### [Version 1.10.3](https://github.com/Codeinwp/visualizer-pro/compare/v1.10.2...v1.10.3) (2021-10-07)

* Fix issue when saving a custom refresh time for chart

##### [Version 1.10.2](https://github.com/Codeinwp/visualizer-pro/compare/v1.10.1...v1.10.2) (2021-07-08)

#### Fixes
* Import from WordPress allows only two values to select
* Exclude Comment Count and Post Status on Import from WordPress group by.
* Chart doesn't get refreshed automatically when database is used as data source 
* Polar area / Radar charts break when font size is changed 
* Error in console when trying to edit Polar area / Radar charts

#### Features
* Add support of interval role in chart

### v1.9.8 - 2020-12-09 
 
 **Changes:** 
 * [Fix] Missing license field
* [Fix] Missing periodic data synchronization options
 
 ### v1.9.7 - 2020-11-26 
 **Changes:** 
 * [Fix] Option to refresh a chart Live when using Import from WordPress
* [Fix] Periodic data synchronization options on a WordPress Multisite
* [Fix] HTML characters not showing up in table charts for not logged in users
 
 ### v1.9.6 - 2020-09-29 
 **Changes:** 
 * [Feat] Add support for PHP variables in the DB queries
* [Fix] Can't edit data of the chart in front-end of the website
* [Fix] Gutenberg: Clicking on Chart permissions throws errors
* [Fix] Edit button disappears on the frontend, after editing the chart from the library
* [Fix] Data Table - search input missing and option to enable it is no longer present
 
 ### v1.9.5 - 2020-07-08 
 **Changes:** 
 * [Feat] Support for the newly added Google Table Charts
 
 ### v1.9.4 - 2020-06-16 
 **Changes:** 
 * [Fix] Last column options disappear after editing the column in the Gutenberg block
* [Fix] Issues editing Pie charts in the Gutenberg block
* [Fix] Clicking Advanced Options panel breaks Timeline chart
 
 ### v1.9.3 - 2020-05-07 
 **Changes:** 
 * [Feat] Improved chart creation UX
* [Fix] Chart permissions for selected users not applying
* [Fix] Error message when publishing posts with Live updating charts
 
 ### v1.9.2 - 2020-02-17 
 **Changes:** 
 * [Feat] Made one-time import from WordPress and from database available in the Personal plan as well
* [Feat] Ability to import from an external database
* [Feat] Support for charts zoom and pan
* [Feat] Undo changes button for the Excel editor
* [Fix] Live support for importing from an online .csv file
 
 ### v1.9.1 - 2019-09-30 
 **Changes:** 
 * Fix issue with table chart pro features not visible in settings
* Add front end editing to personal plans
 
 ### v1.9.0 - 2019-08-17 
 **Changes:** 
 * Add Polar Area and Radar charts for ChartJS
* Fix issue in excel editor for DataTable charts
* Fix issue with JSON chart not updating live
 
 ### v1.8.3 - 2019-05-06 
 **Changes:** 
 * Fix issue with unresponsive View editor button
 
 ### v1.8.2 - 2019-05-03 
 **Changes:** 
 * Add more schedules to JSON/REST endpoint charts
 
 ### v1.8.1 - 2019-02-24 
 **Changes:** 
 * Fix issues with custom fields for Import from WP 
* Add ability to pick up live data for Import from WP
* Added date picker for date columns in manual editor
 
 ### v1.8.0 - 2018-12-03 
 **Changes:** 
 * Add Table chart to free and deprecate Google Table chart
* Create charts using SQL queries
 
 ### v1.7.8 - 2018-10-11 
 **Changes:** 
 * Added filter to enable users to change schedule of charts.
 
 ### v1.7.7 - 2018-07-12 
 **Changes:** 
 * New chart title option for the back-end of the charts that don't allow a title on the front-end
* Added options for charts animations
* Fixed problem with Table charts throwing a fatal error
 
 ### v1.7.6 - 2018-06-18 
 **Changes:** 
 * Fixed problem with adding a new row in the Manual Data mode
* Support series formatting for Table chart type
 
 ### v1.7.5 - 2018-04-02 
 **Changes:** 
 * Fix small issues non-English sites.
 
 ### v1.7.4 - 2018-03-12 
 **Changes:** 
 * Fix collision check with TGMP library.
* Improve Import from WP default state.
 
 ### v1.7.3 - 2018-01-05 
 **Changes:** 
 * Fix enqueue for frontend assets when no chart is present.
 
 ### v1.7.2 - 2017-10-06 
 **Changes:** 
 * Improves compatibility with various themes.
* Added custom post types filter for import.
 
 ### v1.7.1 - 2017-09-08 
 **Changes:** 
 * Fix for fatal error with admin when using the old LITE version.
 
 ### v1.7.0 - 2017-09-05 
 **Changes:** 
 * Adds more customization options for table charts.
* Adds permission feature for charts
* Allow private charts and front submissions.
 
 ### v1.6.3 - 2017-06-19 
 **Changes:** 
 * Added new sdk logic.
 
 ### v1.6.2 - 2017-05-31 
 **Changes:** 
  
 ### v1.6.1 - 2017-05-30 
 **Changes:** 
 - Updated themeisle sdk package.
 
 ### v1.6.0 - 2017-05-12 
 **Changes:** 
 - Added import from post_type feature.
- Added new deployment stack.
