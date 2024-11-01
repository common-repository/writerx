<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

// Function to check if a post is published by ID
function writerx_check_post_publish_status( WP_REST_Request $request ) {
  $post_id = $request->get_param('id');
  $post_status = get_post_status($post_id);
  return rest_ensure_response($post_status === 'publish');
}