<?php
/**
 * Post Submission Handler
 * 
 * Handles front-end form submission, validation, sanitization,
 * and creation/updating of blog posts with proper security checks.
 *
 * @package PersonalProjectWP
 */

/**
 * Handle form submission from the blog page
 */
function ppwp_handle_post_submission() {
  // Check if this is a post request with the right action
  if($_SERVER['REQUEST_METHOD'] !== 'POST' || 
     !isset($_POST['action']) || 
     $_POST['action'] !== 'ppwp_submit_post') {
    return;
  }

  // Verify nonce
  if(!isset($_POST['ppwp_post_nonce']) || 
     !wp_verify_nonce($_POST['ppwp_post_nonce'], 'ppwp_submit_post_nonce')) {
    wp_die('Security check failed. Please try again.');
  }

  // Check if user is logged in and has capability
  if(!is_user_logged_in()) {
    wp_die('You must be logged in to submit posts.');
  }

  if(!current_user_can('publish_posts')) {
    wp_die('You do not have permission to publish posts.');
  }

  // Validate and sanitize inputs
  $post_title = isset($_POST['post_title']) ? sanitize_text_field($_POST['post_title']) : '';
  $post_content = isset($_POST['post_content']) ? wp_kses_post($_POST['post_content']) : '';
  $post_excerpt = isset($_POST['post_excerpt']) ? sanitize_textarea_field($_POST['post_excerpt']) : '';
  $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
  $featured_image_id = isset($_POST['featured_image_id']) ? intval($_POST['featured_image_id']) : 0;
  $post_categories = isset($_POST['post_categories']) ? array_map('intval', $_POST['post_categories']) : array();

  // Validation
  $errors = array();

  if(empty($post_title)) {
    $errors[] = 'Article title is required.';
  } elseif(strlen($post_title) > 200) {
    $errors[] = 'Article title must not exceed 200 characters.';
  }

  if(empty($post_content)) {
    $errors[] = 'Article content is required.';
  } else {
    $word_count = str_word_count(strip_tags($post_content));
    if($word_count < 100) {
      $errors[] = 'Article must be at least 100 words. Current: ' . $word_count . ' words.';
    }
  }

  if(!empty($post_excerpt) && strlen($post_excerpt) > 500) {
    $errors[] = 'Article summary must not exceed 500 characters.';
  }

  // Check post ownership for editing
  if($post_id > 0) {
    $existing_post = get_post($post_id);
    if(!$existing_post) {
      $errors[] = 'Post not found.';
    } elseif($existing_post->post_author != get_current_user_id() && !current_user_can('edit_others_posts')) {
      wp_die('You can only edit your own posts.');
    }
  }

  // If there are validation errors, return with error message
  if(!empty($errors)) {
    $error_message = 'Please fix the following errors:<br>' . implode('<br>', array_map('esc_html', $errors));
    set_transient('ppwp_form_error_' . get_current_user_id(), $error_message, 30);
    wp_safe_remote_post(wp_unslash($_SERVER['HTTP_REFERER']), array(
      'blocking' => false,
      'sslverify' => apply_filters('https_local_over_ssl', false),
    ));
    wp_redirect(wp_unslash($_SERVER['HTTP_REFERER']));
    exit;
  }

  // Prepare post data
  $post_data = array(
    'post_title' => $post_title,
    'post_content' => $post_content,
    'post_excerpt' => $post_excerpt,
    'post_status' => 'publish', // Can be 'draft' or 'pending' for moderation
    'post_author' => get_current_user_id(),
    'post_type' => 'post',
  );

  // Update or insert post
  if($post_id > 0) {
    $post_data['ID'] = $post_id;
    $post_result = wp_update_post($post_data, true);
  } else {
    $post_result = wp_insert_post($post_data, true);
  }

  // Check for errors
  if(is_wp_error($post_result)) {
    $error_message = 'Error creating post: ' . $post_result->get_error_message();
    set_transient('ppwp_form_error_' . get_current_user_id(), $error_message, 30);
    wp_redirect(wp_unslash($_SERVER['HTTP_REFERER']));
    exit;
  }

  $new_post_id = $post_result;

  // Set featured image if provided
  if(!empty($featured_image_id)) {
    set_post_thumbnail($new_post_id, $featured_image_id);
  }

  // Set post categories
  if(!empty($post_categories)) {
    wp_set_post_categories($new_post_id, $post_categories);
  }

  // Set success message and redirect
  $success_message = $post_id > 0 ? 'Article updated successfully!' : 'Article published successfully!';
  set_transient('ppwp_form_success_' . get_current_user_id(), $success_message, 30);

  // Redirect to the new post
  wp_redirect(get_permalink($new_post_id));
  exit;
}
add_action('wp', 'ppwp_handle_post_submission');

/**
 * Handle featured image upload via AJAX
 */
function ppwp_upload_featured_image() {
  // Verify nonce
  check_ajax_referer('ppwp_upload_image_nonce');

  // Check capabilities
  if(!current_user_can('upload_files')) {
    wp_send_json_error('You do not have permission to upload files.');
  }

  // Check if file was uploaded
  if(empty($_FILES['image'])) {
    wp_send_json_error('No file uploaded.');
  }

  // Process upload
  $upload_id = media_handle_upload('image', 0);

  if(is_wp_error($upload_id)) {
    wp_send_json_error($upload_id->get_error_message());
  }

  // Get attachment URL and HTML
  $attachment_url = wp_get_attachment_url($upload_id);
  $attachment_html = wp_get_attachment_image($upload_id, 'medium');

  wp_send_json_success(array(
    'id' => $upload_id,
    'url' => $attachment_url,
    'html' => $attachment_html,
  ));
}
add_action('wp_ajax_ppwp_upload_featured_image', 'ppwp_upload_featured_image');

/**
 * Enqueue necessary scripts and styles for the blog page
 */
function ppwp_enqueue_blog_assets() {
  // Only enqueue on pages that need it
  if(is_page_template('blog.php') || is_singular('post')) {
    wp_enqueue_script('ppwp-blog', get_template_directory_uri() . '/assets/js/blog.js', array('jquery'), '1.0', true);
    
    wp_localize_script('ppwp-blog', 'ppwpBlog', array(
      'ajax_url' => admin_url('admin-ajax.php'),
      'upload_nonce' => wp_create_nonce('ppwp_upload_image_nonce'),
      'user_can_publish' => current_user_can('publish_posts') ? 'true' : 'false',
    ));
  }
}
add_action('wp_enqueue_scripts', 'ppwp_enqueue_blog_assets');

/**
 * Display form messages on blog page
 */
function ppwp_display_form_messages() {
  if(!is_user_logged_in()) {
    return;
  }

  $user_id = get_current_user_id();
  $success_message = get_transient('ppwp_form_success_' . $user_id);
  $error_message = get_transient('ppwp_form_error_' . $user_id);

  if($success_message) {
    echo '<div class="form-notice form-notice-success">';
    echo wp_kses_post($success_message);
    echo '</div>';
    delete_transient('ppwp_form_success_' . $user_id);
  }

  if($error_message) {
    echo '<div class="form-notice form-notice-error">';
    echo wp_kses_post($error_message);
    echo '</div>';
    delete_transient('ppwp_form_error_' . $user_id);
  }
}
add_action('template_redirect', 'ppwp_display_form_messages');
