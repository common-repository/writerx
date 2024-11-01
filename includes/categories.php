<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

// Callback for checking and creating categories
function writerx_check_and_create_categories( WP_REST_Request $request ) {
  $category_names = $request->get_param('categories'); // Get category names from the request
  $slugs = array(); // Array to store slugs of categories

  foreach ( $category_names as $name ) {
    $category = get_term_by( 'name', $name, 'category' ); // Check if the category exists

    // If the category does not exist, create it
    if ( ! $category ) {
      $category_id = wp_insert_term( $name, 'category' ); // Insert new category
      $slugs[] = get_term( $category_id['term_id'], 'category' )->slug; // Get the slug of the newly created category
    } else {
      $slugs[] = $category->slug; // Get the slug of the existing category
    }
  }

  return new WP_REST_Response( $slugs, 200 ); // Return the array of slugs with a 200 status code
}