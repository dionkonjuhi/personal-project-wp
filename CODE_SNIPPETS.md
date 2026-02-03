# Blog System - Code Snippets & Reference

## Common Code Patterns

### Querying Blog Posts with Category Filter

```php
<?php
$category_filter = isset($_GET['cat']) ? sanitize_text_field($_GET['cat']) : '';

$args = array(
  'post_type' => 'post',
  'posts_per_page' => 12,
  'orderby' => 'date',
  'order' => 'DESC',
);

// Add category filter if specified
if(!empty($category_filter)) {
  $args['tax_query'] = array(
    array(
      'taxonomy' => 'category',
      'field' => 'slug',
      'terms' => $category_filter,
    ),
  );
}

$query = new WP_Query($args);

if($query->have_posts()) :
  while($query->have_posts()) : $query->the_post();
    // Display post
  endwhile;
  
  // Pagination
  echo paginate_links(array(
    'total' => $query->max_num_pages,
    'current' => max(1, get_query_var('paged')),
  ));
else :
  echo '<p>No posts found.</p>';
endif;

wp_reset_postdata();
?>
```

### Getting Post Metadata

```php
<?php
// Get featured image
$image_id = get_post_thumbnail_id();
$image_url = wp_get_attachment_url($image_id);

// Get categories
$categories = get_the_category();
foreach($categories as $cat) {
  echo esc_html($cat->name); // Category name
  echo intval($cat->count);  // Number of posts
}

// Get author
$author_id = get_the_author_meta('ID');
$author_name = get_the_author();
$author_url = get_author_posts_url($author_id);

// Get date
$publish_date = get_the_date('F j, Y');

// Word count
$word_count = str_word_count(strip_tags(get_the_content()));
?>
```

### Displaying Forms

```php
<?php
// Show form only to logged-in users with permission
if(!is_user_logged_in()) {
  echo '<p>Please log in to submit.</p>';
  return;
}

if(!current_user_can('publish_posts')) {
  echo '<p>You do not have permission.</p>';
  return;
}

// Show form
get_template_part('template-parts/submit-post-form');
?>
```

### Creating/Updating Posts Programmatically

```php
<?php
// Insert new post
$post_id = wp_insert_post(array(
  'post_title' => 'My Post Title',
  'post_content' => 'Post content here',
  'post_excerpt' => 'Short summary',
  'post_status' => 'publish',
  'post_author' => get_current_user_id(),
  'post_type' => 'post',
));

// Update existing post
wp_update_post(array(
  'ID' => $post_id,
  'post_title' => 'Updated Title',
));

// Check for errors
if(is_wp_error($post_id)) {
  echo $post_id->get_error_message();
}

// Set featured image
set_post_thumbnail($post_id, $image_id);

// Set categories
wp_set_post_categories($post_id, array(1, 2, 3));

// Get permalink
$post_url = get_permalink($post_id);
?>
```

### Form Validation & Sanitization

```php
<?php
// Sanitize different input types
$title = sanitize_text_field($_POST['title']);        // String
$content = wp_kses_post($_POST['content']);           // Allow safe HTML
$excerpt = sanitize_textarea_field($_POST['excerpt']); // Multiline text
$url = esc_url($_POST['url']);                        // URL
$count = intval($_POST['count']);                     // Integer

// Validate
if(empty($title)) {
  $errors[] = 'Title is required';
}

if(strlen($title) > 200) {
  $errors[] = 'Title too long';
}

// Word count
$word_count = str_word_count(strip_tags($content));
if($word_count < 100) {
  $errors[] = 'Must be at least 100 words';
}

// Display errors
if(!empty($errors)) {
  foreach($errors as $error) {
    echo '<p class="error">' . esc_html($error) . '</p>';
  }
}
?>
```

### Nonce Security

```php
<?php
// Create nonce field in form
wp_nonce_field('ppwp_submit_post_nonce', 'ppwp_post_nonce');

// Verify nonce on submission
if(!isset($_POST['ppwp_post_nonce']) || 
   !wp_verify_nonce($_POST['ppwp_post_nonce'], 'ppwp_submit_post_nonce')) {
  wp_die('Security check failed');
}

// For AJAX
$nonce = wp_create_nonce('action_name');
// JavaScript: send as _wpnonce parameter
// PHP: check_ajax_referer('action_name');
?>
```

### AJAX Image Upload

```php
<?php
// JavaScript side
const formData = new FormData();
formData.append('action', 'ppwp_upload_featured_image');
formData.append('image', fileInput.files[0]);
formData.append('_wpnonce', ppwpBlog.upload_nonce);

fetch(ppwpBlog.ajax_url, {
  method: 'POST',
  body: formData
}).then(r => r.json()).then(data => {
  if(data.success) {
    console.log(data.data.id);  // Attachment ID
    console.log(data.data.url); // Image URL
  }
});

// PHP Handler (functions.php or inc file)
function my_upload_handler() {
  check_ajax_referer('ppwp_upload_image_nonce');
  
  if(!current_user_can('upload_files')) {
    wp_send_json_error('Permission denied');
  }
  
  $upload_id = media_handle_upload('image', 0);
  
  if(is_wp_error($upload_id)) {
    wp_send_json_error($upload_id->get_error_message());
  }
  
  $url = wp_get_attachment_url($upload_id);
  wp_send_json_success(array('id' => $upload_id, 'url' => $url));
}
add_action('wp_ajax_my_upload', 'my_upload_handler');
?>
```

### Using Transients for Messages

```php
<?php
// Set transient with message
set_transient('my_message_' . get_current_user_id(), 'Success!', 30);

// Get and display
$message = get_transient('my_message_' . get_current_user_id());
if($message) {
  echo '<div class="success">' . esc_html($message) . '</div>';
  delete_transient('my_message_' . get_current_user_id());
}
?>
```

### Styling with WordPress Classes

```css
/* Always available WordPress classes */
.alignleft { float: left; }
.alignright { float: right; }
.aligncenter { text-align: center; }

.wp-caption { border: 1px solid #ccc; }
.wp-caption-text { font-size: 0.9em; }

.screen-reader-text { display: none; }
.screen-reader-text:focus { display: block; }
```

### Template Tags for Post Content

```php
<?php
// Title
the_title('<h1>', '</h1>');
get_the_title(); // Get without echo

// Content
the_content();
get_the_content(); // Get without echo

// Excerpt
the_excerpt();
get_the_excerpt(); // Get without echo

// Featured image
the_post_thumbnail('large');
get_the_post_thumbnail($post_id, 'medium');

// Date
the_date('F j, Y');
get_the_date('Y-m-d');

// Author
the_author();
the_author_posts_link();
get_the_author();
get_the_author_meta('user_email');

// Categories
the_category(', ');
get_the_category();

// Tags
the_tags('Tags: ', ', ', '');
get_the_tags();

// Edit link
edit_post_link('Edit', '<p>', '</p>');

// Permalink
the_permalink();
get_permalink();

// Post ID
the_ID();
get_the_ID();

// Post class
post_class(); // Echo
get_post_class(); // Get array
?>
```

### Enqueue Scripts and Styles

```php
<?php
// In functions.php
function my_enqueue() {
  // Enqueue style
  wp_enqueue_style('my-style', get_stylesheet_directory_uri() . '/style.css');
  
  // Enqueue script
  wp_enqueue_script('my-script', get_template_directory_uri() . '/js/script.js', 
    array('jquery'), '1.0', true); // true = footer
  
  // Localize variables for JavaScript
  wp_localize_script('my-script', 'myVars', array(
    'ajax_url' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('my_nonce'),
  ));
}
add_action('wp_enqueue_scripts', 'my_enqueue');

// Conditional enqueue
if(is_page_template('blog.php')) {
  wp_enqueue_script('blog-js', ...);
}
?>
```

### Conditional Tags

```php
<?php
is_home()              // Blog homepage
is_single()            // Single post/page
is_page()              // Single page (not post)
is_page_template()     // Check specific template
is_singular()          // Single anything
is_archive()           // Archive page
is_category()          // Category archive
is_search()            // Search results
is_user_logged_in()    // Check if logged in
current_user_can()     // Check capability

// Usage
if(is_page_template('blog.php')) {
  // Running on blog page
}
?>
```

### Common Capabilities

```php
<?php
// Publishing
current_user_can('publish_posts')     // Can publish posts
current_user_can('edit_posts')        // Can edit own posts
current_user_can('edit_others_posts') // Can edit any post
current_user_can('delete_posts')      // Can delete posts

// Files
current_user_can('upload_files')      // Can upload media
current_user_can('manage_files')      // Full file management

// Pages
current_user_can('publish_pages')     // Can publish pages

// Users
current_user_can('list_users')        // Can see user list
current_user_can('manage_options')    // Admin capabilities

// Use in conditions
if(!current_user_can('publish_posts')) {
  wp_die('You do not have permission');
}
?>
```

### Getting Current User

```php
<?php
$user = wp_get_current_user();
$user_id = get_current_user_id();
$user_login = $user->user_login;
$user_email = $user->user_email;
$user_name = $user->display_name;

// Meta
get_user_meta($user_id, 'meta_key', true);
update_user_meta($user_id, 'meta_key', 'value');

// Check role
if(in_array('administrator', $user->roles)) {
  // User is admin
}
?>
```

### Database Queries (if needed)

```php
<?php
global $wpdb;

// Get results as objects
$results = $wpdb->get_results($wpdb->prepare(
  "SELECT * FROM {$wpdb->posts} WHERE post_author = %d",
  $user_id
));

// Get single row
$row = $wpdb->get_row($wpdb->prepare(
  "SELECT * FROM {$wpdb->posts} WHERE ID = %d",
  $post_id
));

// Get column
$value = $wpdb->get_var($wpdb->prepare(
  "SELECT post_title FROM {$wpdb->posts} WHERE ID = %d",
  $post_id
));

// Insert
$wpdb->insert('table_name', array(
  'column1' => 'value1',
  'column2' => 'value2',
));

// Always use $wpdb->prepare() to prevent SQL injection!
?>
```

## CSS Grid for Post Layout

```css
/* 3-column responsive grid */
.blog-posts {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 2rem;
}

/* Alternative: Fixed columns */
.blog-posts {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 2rem;
}

@media (max-width: 768px) {
  .blog-posts {
    grid-template-columns: 1fr;
  }
}
```

## Useful Plugins for Blog Enhancement

- **Jetpack** - Additional features and performance
- **Yoast SEO** - Search engine optimization
- **Akismet** - Spam protection
- **WP Super Cache** - Performance caching
- **Redirection** - Manage redirects
- **Backup** - Regular backups

---

**Last Updated:** 2026-02-03  
**Version:** 1.0
