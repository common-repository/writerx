<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

// Function to check if multiple posts are published
function writerx_check_posts_publish_status_mass( WP_REST_Request $request ) {
  $post_ids = $request->get_param('ids');
  $result = array();

  foreach ($post_ids as $post_id) {
    $post_status = get_post_status($post_id);
    $result[] = array(
      'id' => $post_id,
      'is_publish' => $post_status === 'publish'
    );
  }

  return rest_ensure_response($result);
}