<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

// Function to get post information by ID
function writerx_get_post_info( $data ) {
  $post_id = $data['id'];
  $post = get_post( $post_id );

  if ( ! $post ) {
    return new WP_Error( 'no_post', 'Post not found', array( 'status' => 404 ) );
  }

  $post_info = array(
    'id' => $post->ID,
    'title' => $post->post_title,
    'preview_url' => has_post_thumbnail( $post_id ) ? get_the_post_thumbnail_url( $post_id ) : null,
  );

  return rest_ensure_response( $post_info );
}