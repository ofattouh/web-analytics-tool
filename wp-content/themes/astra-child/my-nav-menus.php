<?php

//----------------------------------------------------------------------------------------
// Show custom navigation menus

// https://www.wpbeginner.com/wp-themes/how-to-add-custom-items-to-specific-wordpress-menus/

// Show different nav menus for logged in/out users
function add_logout_link_func( $items, $args ) {
  if (is_user_logged_in() && $args->theme_location == 'primary') {
    $items .= '<h4 style="line-height:90px;">';
    $items .= '<a class="menu-link" href="'. wp_logout_url() .'">Log Out</a></h4>';
  }
  /* elseif (!is_user_logged_in() && $args->theme_location == 'primary') {
    $items .= '<h4><a class="menu-link" style="line-height:90px;" href="'. site_url('wp-login.php') .'">Log In</a></h4>';
  } */

  return $items;
}
add_filter( 'wp_nav_menu_items', 'add_logout_link_func', 10, 2 );
