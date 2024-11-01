<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

// Function to get the site name
function writerx_get_site_name() {
  $site_name = get_bloginfo('name');
  return rest_ensure_response($site_name);
}