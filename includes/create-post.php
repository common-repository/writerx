<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

// Include file with the function download_url()
require_once ABSPATH . 'wp-admin/includes/file.php';

// Include file with the function wp_read_image_metadata()
require_once ABSPATH . 'wp-admin/includes/image.php';

// Include file with the function media_sideload_image()
require_once ABSPATH . 'wp-admin/includes/media.php';

// Function to create a new post via API with featured image, template, and categories/tags as arrays of arrays
function writerx_create_new_post($request) {
  $parameters = $request->get_json_params();

  if (isset($parameters['title']) && isset($parameters['content']) && isset($parameters['post_type']) && isset($parameters['author'])) {
    $post_title = sanitize_text_field($parameters['title']);
    $post_content = wp_kses_post($parameters['content']);
    $post_type = sanitize_text_field($parameters['post_type']);
    $post_author = intval($parameters['author']);

    // Retrieve and create categories by names from category_names
    $post_category = array();
    if (isset($parameters['category_names']) && is_array($parameters['category_names'])) {
      foreach ($parameters['category_names'] as $category_name) {
        $category_name = sanitize_text_field($category_name);
        $category_term = get_term_by('name', $category_name, 'category');
        if (!$category_term) {
          // If category does not exist, create it
          $category_term = wp_insert_term($category_name, 'category');
          if (!is_wp_error($category_term)) {
            $post_category[] = $category_term['term_id'];
          } else {
            return array(
              'success' => false,
              'message' => 'Error creating category: ' . $category_term->get_error_message(),
            );
          }
        } else {
          // If category exists, add its ID
          $post_category[] = $category_term->term_id;
        }
      }
    }

    // Add categories from array by slugs
    if (isset($parameters['categories']) && is_array($parameters['categories'])) {
      foreach ($parameters['categories'] as $category_slug) {
        $category_term = get_term_by('slug', $category_slug, 'category');
        if ($category_term && !is_wp_error($category_term)) {
          $post_category[] = $category_term->term_id;
        }
      }
    }

    // Retrieve and create tags by names from tag_names
    $post_tags = array();
    if (isset($parameters['tag_names']) && is_array($parameters['tag_names'])) {
      foreach ($parameters['tag_names'] as $tag_name) {
        $tag_name = sanitize_text_field($tag_name);
        $tag_term = get_term_by('name', $tag_name, 'post_tag');
        if (!$tag_term) {
          // If tag does not exist, create it
          $tag_term = wp_insert_term($tag_name, 'post_tag');
          if (!is_wp_error($tag_term)) {
            $post_tags[] = $tag_term['term_id'];
          } else {
            return array(
              'success' => false,
              'message' => 'Error creating tag: ' . $tag_term->get_error_message(),
            );
          }
        } else {
          // If tag exists, add its ID
          $post_tags[] = $tag_term->term_id;
        }
      }
    }

    // Add tags from array by slugs
    if (isset($parameters['tags']) && is_array($parameters['tags'])) {
      foreach ($parameters['tags'] as $tag_slug) {
        $tag_term = get_term_by('slug', $tag_slug, 'post_tag');
        if ($tag_term && !is_wp_error($tag_term)) {
          $post_tags[] = $tag_term->term_id;
        }
      }
    }

    $post_status = isset($parameters['status']) && in_array($parameters['status'], array('publish', 'draft', 'future')) ? $parameters['status'] : 'publish';

    $post_date = '';
    if ($post_status === 'future' && isset($parameters['post_date'])) {
      $post_date = sanitize_text_field($parameters['post_date']);
    }

    $featured_image_url = isset($parameters['featured_image']) ? esc_url_raw($parameters['featured_image']) : '';

    $template_slug = isset($parameters['template_slug']) ? sanitize_text_field($parameters['template_slug']) : '';

    $post_excerpt = isset($parameters['excerpt']) ? sanitize_text_field($parameters['excerpt']) : '';

    $new_post_id = wp_insert_post(array(
      'post_title' => $post_title,
      'post_content' => $post_content,
      'post_type' => $post_type,
      'post_author' => $post_author,
      'post_status' => $post_status,
      'post_category' => array_unique($post_category),
      'tags_input' => array_unique($post_tags),
      'post_date' => $post_date,
      'page_template' => $template_slug,
      'post_excerpt' => $post_excerpt,
    ));

    if (!is_wp_error($new_post_id)) {
      if ($featured_image_url) {
        $image_id = media_sideload_image($featured_image_url, $new_post_id, '', 'id');
        if (!is_wp_error($image_id)) {
          set_post_thumbnail($new_post_id, $image_id);
        }
      }

      /* Yoast SEO meta */
      if (isset($parameters['keywords'])) {
        // Yoast SEO stores keywords in the '_yoast_wpseo_focuskw' meta field
        update_post_meta($new_post_id, '_yoast_wpseo_focuskw', sanitize_text_field($parameters['keywords']));
      }

      if (isset($parameters['description'])) {
        // Yoast SEO stores the meta description in the '_yoast_wpseo_metadesc' meta field
        update_post_meta($new_post_id, '_yoast_wpseo_metadesc', sanitize_text_field($parameters['description']));
      }

      /* All in One SEO meta */
      if (isset($parameters['description']) || isset($parameters['keywords'])) {
        global $wpdb;

        // Sanitize and process the description
        $description = isset($parameters['description']) ? sanitize_text_field($parameters['description']) : '';

        // Sanitize and process the keyphrase
        $keyphrase = isset($parameters['keywords']) ? sanitize_text_field($parameters['keywords']) : '';

        // Prepare the value for the keyphrases field
        $keyphrases_data = [
          "focus" => [
            "keyphrase" => $keyphrase,
            "score" => 0, // You can set your own value
            "analysis" => [
              "keyphraseInTitle" => ["score" => 0, "maxScore" => 9, "error" => 0],
              "keyphraseInDescription" => ["score" => 0, "maxScore" => 9, "error" => 0],
              "keyphraseLength" => ["score" => strlen($keyphrase), "maxScore" => 9, "error" => 0, "length" => strlen($keyphrase)],
              "keyphraseInURL" => ["score" => 0, "maxScore" => 5, "error" => 0],
              "keyphraseInIntroduction" => ["score" => 0, "maxScore" => 9, "error" => 0],
              "keyphraseInSubHeadings" => [],
              "keyphraseInImageAlt" => [],
              "keywordDensity" => ["score" => 0, "type" => "low", "maxScore" => 9, "error" => 0]
            ]
          ],
          "additional" => []
        ];

        // Check if the record exists in the wp_aioseo_posts table
        $post_exists = $wpdb->get_var($wpdb->prepare(
          "SELECT COUNT(*) FROM wp_aioseo_posts WHERE post_id = %d",
          $new_post_id
        ));

        if ($post_exists) {
          // If the record exists, update the description and keyphrases
          $wpdb->update(
            'wp_aioseo_posts',
            array(
              'description' => $description, // Data for updating
              'keyphrases' => json_encode($keyphrases_data) // Update keyphrases
            ),
            array('post_id' => $new_post_id) // Update conditions
          );
        } else {
          // If the record does not exist, create a new one
          $wpdb->insert(
            'wp_aioseo_posts',
            array(
              'post_id' => $new_post_id,
              'description' => $description,
              'keyphrases' => json_encode($keyphrases_data), // Fill keyphrases
            )
          );
        }

        // Update the field in the wp_postmeta table
        update_post_meta($new_post_id, '_aioseop_description', $description);
      }

      $edit_post_url = admin_url('post.php?post=' . $new_post_id . '&action=edit');
      $preview_post_url = add_query_arg( 'preview', 'true', get_permalink($new_post_id) );

      return array(
        'success' => true,
        'message' => 'Post created successfully.',
        'post_id' => $new_post_id,
        'post_url' => get_permalink($new_post_id),
        'edit_post_url' => $edit_post_url,
        'preview_post_url' => $preview_post_url,
      );
    } else {
      return array(
        'success' => false,
        'message' => 'Error creating post: ' . $new_post_id->get_error_message(),
      );
    }
  } else {
    return array(
      'success' => false,
      'message' => 'Missing parameters.',
    );
  }
}