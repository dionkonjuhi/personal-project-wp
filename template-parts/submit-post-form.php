<?php
/**
 * Template Part: Post Submission Form
 * 
 * Front-end form for registered users to submit and publish blog posts.
 * Includes rich text editor, featured image upload, and category selection.
 *
 * @package PersonalProjectWP
 */

// Only show to logged-in users
if(!is_user_logged_in()) {
  echo '<div class="auth-required-notice">';
  printf(
    '<p>Please <a href="%s">log in</a> to submit an article.</p>',
    esc_url(wp_login_url())
  );
  echo '</div>';
  return;
}

// Check if user has capability to create posts
if(!current_user_can('publish_posts')) {
  echo '<div class="permission-denied-notice">';
  echo '<p>You do not have permission to publish posts.</p>';
  echo '</div>';
  return;
}

$post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;
$post = $post_id ? get_post($post_id) : null;

// Security check for editing
if($post && $post->post_author != get_current_user_id() && !current_user_can('edit_others_posts')) {
  echo '<div class="permission-denied-notice">';
  echo '<p>You can only edit your own posts.</p>';
  echo '</div>';
  return;
}

$title = $post ? $post->post_title : '';
$content = $post ? $post->post_content : '';
$excerpt = $post ? $post->post_excerpt : '';
$categories = $post ? wp_get_post_categories($post_id) : array();
$featured_image_id = $post ? get_post_thumbnail_id($post_id) : 0;

?>

<div class="submit-post-form-wrapper">
  
  <div class="form-header">
    <h2><?php echo $post_id ? 'Edit Article' : 'Submit Your Article'; ?></h2>
    <p>Share your story with our community. Use the form below to write and publish.</p>
  </div>

  <form id="submit-post-form" class="submit-post-form" method="post" enctype="multipart/form-data">
    
    <!-- Security Nonce -->
    <?php wp_nonce_field('ppwp_submit_post_nonce', 'ppwp_post_nonce'); ?>
    <input type="hidden" name="action" value="ppwp_submit_post">
    <?php if($post_id) : ?>
      <input type="hidden" name="post_id" value="<?php echo intval($post_id); ?>">
    <?php endif; ?>

    <!-- Title Field -->
    <div class="form-group">
      <label for="post_title" class="form-label">
        Article Title *
        <span class="required-indicator">Required</span>
      </label>
      <input 
        type="text" 
        id="post_title" 
        name="post_title" 
        class="form-input" 
        placeholder="Write a compelling title..." 
        value="<?php echo esc_attr($title); ?>"
        maxlength="200"
        required
      >
      <small class="form-help">Keep your title clear and descriptive (max 200 characters)</small>
    </div>

    <!-- Featured Image -->
    <div class="form-group">
      <label for="post_thumbnail" class="form-label">
        Featured Image
        <span class="optional-indicator">(Optional)</span>
      </label>
      <div class="featured-image-upload">
        <div class="featured-image-preview" id="featured-image-preview">
          <?php if($featured_image_id) : ?>
            <?php echo wp_get_attachment_image($featured_image_id, 'medium'); ?>
            <button type="button" class="btn btn-small btn-danger" id="remove-featured-image">
              Remove Image
            </button>
          <?php else : ?>
            <div class="placeholder">
              <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M42 6H6C4.9 6 4 6.9 4 8v32c0 1.1.9 2 2 2h36c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm0 32H6V8h36v30zm-6-10l-7-10-5 7-4-5-4 6h22z" fill="currentColor"/>
              </svg>
              <p>Click to upload or drag and drop</p>
            </div>
          <?php endif; ?>
        </div>
        <input 
          type="hidden" 
          id="featured_image_id" 
          name="featured_image_id" 
          value="<?php echo intval($featured_image_id); ?>"
        >
        <button 
          type="button" 
          class="btn btn-secondary" 
          id="upload-featured-image"
          <?php echo $featured_image_id ? 'style="display:none;"' : ''; ?>
        >
          Choose Image
        </button>
      </div>
      <small class="form-help">Recommended: 1200x800px or larger. JPG, PNG, GIF.</small>
    </div>

    <!-- Excerpt -->
    <div class="form-group">
      <label for="post_excerpt" class="form-label">
        Article Summary
        <span class="optional-indicator">(Optional)</span>
      </label>
      <textarea 
        id="post_excerpt" 
        name="post_excerpt" 
        class="form-textarea" 
        placeholder="A brief summary of your article (will display in listings)..."
        rows="3"
        maxlength="500"
      ><?php echo esc_textarea($excerpt); ?></textarea>
      <small class="form-help">Character count: <span id="excerpt-count">0</span>/500</small>
    </div>

    <!-- Categories -->
    <div class="form-group">
      <label class="form-label">
        Categories
        <span class="optional-indicator">(Optional)</span>
      </label>
      <div class="category-checkboxes">
        <?php
          $all_categories = get_categories(array('hide_empty' => false));
          foreach($all_categories as $category) {
            $checked = in_array($category->term_id, $categories) ? 'checked' : '';
            printf(
              '<div class="checkbox-item"><input type="checkbox" id="cat_%d" name="post_categories[]" value="%d" %s><label for="cat_%d">%s</label></div>',
              intval($category->term_id),
              intval($category->term_id),
              $checked,
              intval($category->term_id),
              esc_html($category->name)
            );
          }
        ?>
      </div>
      <small class="form-help">Select one or more categories for your article</small>
    </div>

    <!-- Main Content Editor -->
    <div class="form-group">
      <label for="post_content" class="form-label">
        Article Content *
        <span class="required-indicator">Required</span>
      </label>
      <div class="editor-toolbar">
        <div class="toolbar-group">
          <button type="button" class="toolbar-btn" data-action="bold" title="Bold (Ctrl+B)">
            <strong>B</strong>
          </button>
          <button type="button" class="toolbar-btn" data-action="italic" title="Italic (Ctrl+I)">
            <em>I</em>
          </button>
          <button type="button" class="toolbar-btn" data-action="underline" title="Underline (Ctrl+U)">
            <u>U</u>
          </button>
        </div>
        <div class="toolbar-group">
          <button type="button" class="toolbar-btn" data-action="insertUnorderedList" title="Bullet List">
            ▪
          </button>
          <button type="button" class="toolbar-btn" data-action="insertOrderedList" title="Numbered List">
            #
          </button>
        </div>
        <div class="toolbar-group">
          <button type="button" class="toolbar-btn" data-action="createLink" title="Insert Link">
            🔗
          </button>
          <button type="button" class="toolbar-btn" data-action="insertImage" title="Insert Image">
            🖼
          </button>
        </div>
      </div>
      <div 
        id="post_content" 
        class="form-editor" 
        contenteditable="true" 
        data-placeholder="Write your article here... You can format text using the toolbar above."
      ><?php echo wp_kses_post($content); ?></div>
      <textarea 
        id="post_content_hidden" 
        name="post_content" 
        style="display:none;"
        required
      ><?php echo esc_textarea($content); ?></textarea>
      <small class="form-help">Minimum 100 words. You can use the toolbar above to format text, add links, and images.</small>
    </div>

    <!-- Form Actions -->
    <div class="form-actions">
      <button type="submit" class="btn btn-primary btn-large" id="submit-btn">
        <?php echo $post_id ? 'Update Article' : 'Publish Article'; ?>
      </button>
      <a href="<?php echo esc_url(get_permalink()); ?>" class="btn btn-outline">
        Cancel
      </a>
    </div>

    <!-- Form Status Messages -->
    <div class="form-status" id="form-status" style="display:none;"></div>

  </form>

</div>

<style>
  /* Inline styles for form elements - will be moved to main.css */
</style>
