<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

// Function to get Envato data
function writerx_get_envato_elements_data() {
  $provided_code = isset($_SERVER['HTTP_X_WRITERX_CODE']) ? sanitize_text_field(wp_unslash($_SERVER['HTTP_X_WRITERX_CODE'])) : '';

  if (empty($provided_code)) {
    return new WP_Error('empty_code', 'Provided code is empty.', array('status' => 400));
  }

  // Additional length and format check
  if (strlen($provided_code) !== 32 || !ctype_alnum($provided_code)) {
    return new WP_Error('invalid_format', 'Provided code has an invalid format.', array('status' => 400));
  }

  // Get the required data
  $envato_elements_data = array(
    'siteUrl' => admin_url(),
    'extensionId' => md5(get_site_url()),
    'homeUrl' => home_url(),
    'blogName' => get_bloginfo('name'),
    'envatoElementsOptions' => get_option('envato_elements_options') ?: null
  );

  // Return the data as a REST response
  return rest_ensure_response($envato_elements_data);
}