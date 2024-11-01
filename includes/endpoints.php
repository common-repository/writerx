<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

// Define a reusable permission callback function
function writerx_permission_callback() {
  $provided_code = isset($_SERVER['HTTP_X_WRITERX_CODE']) ? sanitize_text_field(wp_unslash($_SERVER['HTTP_X_WRITERX_CODE'])) : '';
  return writerx_check_auth_token($provided_code);
}

// Add a REST API endpoint to get the code and check the provided code
function writerx_api_init() {
  // Check the code on the site
  register_rest_route(
    'writerx/v1',
    '/check-code',
    array(
      'methods' => 'POST',
      'callback' => 'writerx_check_code',
      'permission_callback' => 'writerx_permission_callback',
    )
  );

  // Get site name
  register_rest_route(
    'writerx/v1',
    '/get-site-name',
    array(
      'methods' => 'GET',
      'callback' => 'writerx_get_site_name',
      'permission_callback' => 'writerx_permission_callback',
    )
  );

  // Get users
  register_rest_route(
    'writerx/v1',
    '/get-users',
    array(
      'methods' => 'GET',
      'callback' => 'writerx_get_users',
      'permission_callback' => 'writerx_permission_callback',
    )
  );

  // Get categories and tags
  register_rest_route(
    'writerx/v1',
    '/get-categories-and-tags',
    array(
      'methods' => 'GET',
      'callback' => 'writerx_get_categories_and_tags',
      'permission_callback' => 'writerx_permission_callback',
    )
  );

  // Get post types
  register_rest_route(
    'writerx/v1',
    '/get-post-types',
    array(
      'methods' => 'GET',
      'callback' => 'writerx_get_post_types',
      'permission_callback' => 'writerx_permission_callback',
    )
  );

  // Get post templates
  register_rest_route(
    'writerx/v1',
    '/get-post-templates',
    array(
      'methods' => 'GET',
      'callback' => 'writerx_get_post_templates',
      'permission_callback' => 'writerx_permission_callback',
    )
  );

  // Check if a post is published
  register_rest_route(
    'writerx/v1',
    '/check-publish',
    array(
      'methods' => 'GET',
      'callback' => 'writerx_check_post_publish_status',
      'permission_callback' => 'writerx_permission_callback',
    )
  );

  // Check if multiple posts are published
  register_rest_route(
    'writerx/v1',
    '/check-publish-mass',
    array(
      'methods' => 'POST',
      'callback' => 'writerx_check_posts_publish_status_mass',
      'permission_callback' => 'writerx_permission_callback',
    )
  );

  // Create new post
  register_rest_route(
    'writerx/v1',
    '/create-post',
    array(
    'methods'  => 'POST',
    'callback' => 'writerx_create_new_post',
    'permission_callback' => 'writerx_permission_callback',
  ));

  // Get Envato data
  register_rest_route(
    'writerx/v1',
    '/envato-elements-data',
    array(
      'methods' => 'GET',
      'callback' => 'writerx_get_envato_elements_data',
      'permission_callback' => 'writerx_permission_callback',
    )
  );

  // Get post information by ID
  register_rest_route(
    'writerx/v1',
    '/get-post-info/(?P<id>\d+)',
    array(
      'methods' => 'GET',
      'callback' => 'writerx_get_post_info',
      'permission_callback' => 'writerx_permission_callback',
    )
  );

  // Get post categories slugs
  register_rest_route(
    'writerx/v1',
    '/categories',
    array(
      'methods' => 'POST',
      'callback' => 'writerx_check_and_create_categories',
      'permission_callback' => function () {
        $provided_code = isset($_SERVER['HTTP_X_WRITERX_CODE']) ? sanitize_text_field(wp_unslash($_SERVER['HTTP_X_WRITERX_CODE'])) : '';
        return writerx_check_auth_token($provided_code);
      },
    )
  );

  // Get post tags slugs
  register_rest_route(
    'writerx/v1',
    '/tags',
    array(
      'methods' => 'POST',
      'callback' => 'writerx_check_and_create_tags',
      'permission_callback' => function () {
        $provided_code = isset($_SERVER['HTTP_X_WRITERX_CODE']) ? sanitize_text_field(wp_unslash($_SERVER['HTTP_X_WRITERX_CODE'])) : '';
        return writerx_check_auth_token($provided_code);
      },
    )
  );
}

add_action('rest_api_init', 'writerx_api_init');