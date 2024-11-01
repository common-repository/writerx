<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

// Function to generate or retrieve the WriterX code
function writerx_get_code() {
  $existing_code = get_option('writerx_code'); // Retrieve the existing code from the options table
  if (empty($existing_code)) {
    $new_code = bin2hex(random_bytes(16)); // Generates a new 32-character hexadecimal code
    update_option('writerx_code', $new_code); // Saves the new code in the options table
    return $new_code;
  } else {
    return $existing_code; // Returns the existing code if one already exists
  }
}

// Function to check if the provided auth token matches the stored code
function writerx_check_auth_token($provided_code) {
  // Get the stored code from the database
  $stored_code = get_option('writerx_code');

  // Check if the provided code matches the stored code
  return $provided_code === $stored_code;
}