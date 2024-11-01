<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

// Function to get post templates
function writerx_get_post_templates($request) {
  // Get the list of available post templates
  $templates = wp_get_theme()->get_page_templates();

  // Add the default post template at the beginning of the array
  $formatted_templates = array(
    array(
      'ID' => 'Default Template',
      'file' => 'default',
    ),
  );

  // Format the remaining templates into the array
  foreach ($templates as $template_name => $template_file) {
    $formatted_templates[] = array(
      'ID' => $template_name,
      'file' => $template_file,
    );
  }

  // Return the formatted templates
  return $formatted_templates;
}