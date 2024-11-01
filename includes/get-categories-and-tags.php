<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

// Function for getting a list of categories & tags
function writerx_get_categories_and_tags() {
  $categories = get_categories(array(
    'hide_empty' => false,
    'orderby' => 'name',
    'order' => 'ASC',
  ));

  $tags = get_tags(array(
    'hide_empty' => false,
    'orderby' => 'name',
    'order' => 'ASC',
  ));

  $formatted_data = array(
    'categories' => array(),
    'tags' => array(),
  );

  foreach ($categories as $category) {
    $formatted_data['categories'][] = array(
      'ID' => $category->slug,
      'name' => $category->name,
    );
  }

  foreach ($tags as $tag) {
    $formatted_data['tags'][] = array(
      'ID' => $tag->slug,
      'name' => $tag->name,
    );
  }

  return $formatted_data;
}