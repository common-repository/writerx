<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

// Function to display the generated code on the plugin's page
function writerx_display_code() {
  $code = writerx_get_code();
  echo '<div class="writerx-wrapper">';
  echo '<div class="writerx-inner">';
  echo '<h3>API integration code</h3>';
  echo '<div class="writerx-section">';
  echo '<h4>WriterX Code:</h4>';
  echo '<span class="block-code">' . esc_html($code) . '</span>';
  echo '<span class="saved"><svg height="22px" viewBox="0 0 21 22" width="21px" xmlns="http://www.w3.org/2000/svg">
          <g fill="none" fill-rule="evenodd" stroke="none" stroke-width="1">
            <g fill="#f7f0f0" transform="translate(-86.000000, -127.000000)">
              <g transform="translate(86.500000, 127.000000)">
                <path d="M14,0 L2,0 C0.9,0 0,0.9 0,2 L0,16 L2,16 L2,2 L14,2 L14,0 L14,0 Z M17,4 L6,4 C4.9,4 4,4.9 4,6 L4,20 C4,21.1 4.9,22 6,22 L17,22 C18.1,22 19,21.1 19,20 L19,6 C19,4.9 18.1,4 17,4 L17,4 Z M17,20 L6,20 L6,6 L17,6 L17,20 L17,20 Z"/>
              </g>
            </g>
          </g>
        </svg>
      </span>';
  echo '</div>';
  echo '</div>';
  echo '</div>';
}