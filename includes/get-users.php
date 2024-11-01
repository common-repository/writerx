<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

// Function to get a list of users with their roles
function writerx_get_users() {
  $users = get_users(array(
    'role__in' => array('author', 'editor', 'administrator'), // Include only users with these roles
  ));
  $formatted_users = array();

  foreach ($users as $user) {
    $formatted_user = array(
      'ID' => $user->ID,
      'username' => $user->user_login,
    );
    $formatted_users[] = $formatted_user;
  }

  return $formatted_users;
}