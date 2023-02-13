<?php

//----------------------------------------------------------------------------------------
// Custom visualizer config
// https://docs.themeisle.com/article/1364-download-csv-data-of-the-chart-without-the-row-with-series-type

// To download the CSV file without the row of series type
add_filter( 'vizualizer_export_include_series_type', '__return_false' );

// Global visualizer charts settings
/* add_filter( 'visualizer-get-chart-settings', 'myplugin_filter_charts_settings', 10, 3 );
function myplugin_filter_charts_settings( $data, $chart_id, $type ) {
  if ($chart_id === 59){
    var_dump($data);
  }
  return $data;
} */

// ----------------------------------------------------------------------------------
// Chart manual confirguration

/*

https://docs.themeisle.com/article/728-manual-configuration
https://developers.google.com/chart/interactive/docs/gallery/columnchart
https://developers.google.com/chart/interactive/docs/roles#what-roles-are-available

{ "animation": { "duration": 3000, "easing": "out", "startup": true } }


************************************************************************************/
