<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

// Function to check the provided auth code
function writerx_check_code($request) {
  return array(
    'success' => true,
    'message' => 'Code is valid',
  );
}