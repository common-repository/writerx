<?php
/*
Plugin Name: WriterX
Description: Enables connection with your WriterX account to generate content at scale
Version: 1.3.7
Author: Astoria Company
Author URI: https://writerx.ai/
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: writerx

Domain Path: /languages
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

// Add an admin menu page to display the code
function writerx_menu_page() {
  add_menu_page(
    'WriterX',
    'WriterX',
    'manage_options',
    'writerx-code',
    'writerx_display_code'
  );
}
add_action('admin_menu', 'writerx_menu_page');

// Include files
include_once(plugin_dir_path(__FILE__) . 'includes/functions.php');
include_once(plugin_dir_path(__FILE__) . 'includes/code.php');
include_once(plugin_dir_path(__FILE__) . 'includes/get-users.php');
include_once(plugin_dir_path(__FILE__) . 'includes/get-categories-and-tags.php');
include_once(plugin_dir_path(__FILE__) . 'includes/get-post-types.php');
include_once(plugin_dir_path(__FILE__) . 'includes/get-post-templates.php');
include_once(plugin_dir_path(__FILE__) . 'includes/get-site-name.php');
include_once(plugin_dir_path(__FILE__) . 'includes/check-code.php');
include_once(plugin_dir_path(__FILE__) . 'includes/create-post.php');
include_once(plugin_dir_path(__FILE__) . 'includes/endpoints.php');
include_once(plugin_dir_path(__FILE__) . 'includes/get-envato-elements-data.php');
include_once(plugin_dir_path(__FILE__) . 'includes/check-publish.php');
include_once(plugin_dir_path(__FILE__) . 'includes/check-publish-mass.php');
include_once(plugin_dir_path(__FILE__) . 'includes/get-post-info.php');
include_once(plugin_dir_path(__FILE__) . 'includes/categories.php');
include_once(plugin_dir_path(__FILE__) . 'includes/tags.php');

// Function to generate the code upon plugin activation
function writerx_generate_code() {
    writerx_get_code(); // Call the function to generate or retrieve the code
}
register_activation_hook(__FILE__, 'writerx_generate_code');

// Add information about the author, plugin version, and link in the plugins list
function writerx_plugin_row_meta($links, $file) {
  if (strpos($file, 'writerx.php') !== false) { // Check if the plugin file name contains 'writerx.php'
    $row_meta = array(
      'settings' => '<a href="' . admin_url('admin.php?page=writerx-code') . '">Get WriterX API code</a>',
    );
    return array_merge($links, $row_meta);
  }
  return $links;
}
add_filter('plugin_row_meta', 'writerx_plugin_row_meta', 10, 2);

// Enqueueing scripts and styles for the plugin only on the plugin's page
function writerx_enqueue_scripts($hook) {
  if ($hook != 'toplevel_page_writerx-code') { // Check if the current admin page is the WriterX page
    return;
  }

  // Enqueueing scripts
  wp_enqueue_script('writerx-scripts', plugins_url('scripts.js', __FILE__), array('jquery'), '1.0', true);

  // Enqueueing styles
  wp_enqueue_style('writerx-styles', plugins_url('style.css', __FILE__), array(), '1.0', 'all');
}
add_action('admin_enqueue_scripts', 'writerx_enqueue_scripts');