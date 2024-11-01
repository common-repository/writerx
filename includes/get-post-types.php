<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

// Function to get a list of post types with titles and slugs
function writerx_get_post_types() {
  $post_types = get_post_types(array(
    'public' => true,
  ), 'objects');

  $formatted_post_types = array();

  foreach ($post_types as $post_type) {
    // Exclude 'attachment' post type
    if ($post_type->name !== 'attachment') {
      $formatted_post_types[] = array(
        'ID' => $post_type->name,
        'name' => $post_type->labels->name,
      );
    }
  }

  return $formatted_post_types;
}
