<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

// Callback for checking and creating tags
function writerx_check_and_create_tags( WP_REST_Request $request ) {
  $tag_names = $request->get_param('tags'); // Get tag names from the request
  $slugs = array(); // Array to store slugs of tags

  foreach ( $tag_names as $name ) {
    $tag = get_term_by( 'name', $name, 'post_tag' ); // Check if the tag exists

    // If the tag does not exist, create it
    if ( ! $tag ) {
      $tag_id = wp_insert_term( $name, 'post_tag' ); // Insert new tag
      $slugs[] = get_term( $tag_id['term_id'], 'post_tag' )->slug; // Get the slug of the newly created tag
    } else {
      $slugs[] = $tag->slug; // Get the slug of the existing tag
    }
  }

  return new WP_REST_Response( $slugs, 200 ); // Return the array of slugs with a 200 status code
}