# Professional Blog System Documentation

## Overview

This is a complete, production-ready blog system for the Personal Project WordPress theme. It provides a modern, editorial-style blog with user-facing post submission functionality, category filtering, and professional design.

## Features

### 1. **Modern Blog Listing Page**
- Responsive grid layout with category filtering
- Featured image with hover effects
- Post metadata (author, date, reading time estimate)
- Category badges and tags
- Pagination with smooth transitions
- Editorial-style header with gradient background

### 2. **User-Facing Post Submission**
- Front-end form for registered users to publish posts
- Rich text editor with formatting toolbar
- Featured image upload with drag-and-drop
- Category selection via checkboxes
- Real-time validation and character counters
- Success/error messaging

### 3. **Security & Validation**
- WordPress nonce verification
- Capability checks for publish_posts
- Input sanitization and escaping
- Word count validation (minimum 100 words)
- Content length validation
- Post ownership verification for editing

### 4. **Performance Optimized**
- CSS animations use GPU acceleration (transforms)
- Minimal JavaScript footprint
- Efficient database queries with WP_Query
- Media upload via AJAX
- Smooth scroll animations

### 5. **Accessibility**
- Semantic HTML5 structure
- ARIA labels and roles
- Keyboard navigation support
- High contrast form elements
- Focus visible states
- Screen reader compatibility

## File Structure

```
theme-root/
├── blog.php                           # Main blog page template
├── template-parts/
│   ├── blog-post.php                 # Individual post card component
│   └── submit-post-form.php           # User post submission form
├── inc/
│   └── blog-submission-handler.php    # Backend form processing
├── assets/
│   ├── css/
│   │   └── main.css                  # (Updated with blog styles)
│   └── js/
│       └── blog.js                   # Blog form interactions
└── functions.php                      # (Updated with includes)
```

## Setup Instructions

### 1. Create the Blog Page

1. Go to WordPress Admin → Pages → Add New
2. Set Title: "Blog"
3. Go to right sidebar → "Page Attributes" → Template
4. Select "Blog" template
5. Click Publish
6. Note the page URL/slug

### 2. Verify User Roles

Ensure your user role has the `publish_posts` capability. By default:
- Administrators can publish posts
- Editors can publish posts
- Authors can publish posts
- Contributors cannot publish posts

To customize, edit user roles in WordPress Admin → Users → User Roles (if using a role manager plugin).

### 3. Verify Categories

1. Go to WordPress Admin → Posts → Categories
2. Create categories for your blog (e.g., "Technology", "Design", "Business")
3. These will appear automatically in the blog page filter

## Templates

### blog.php - Main Blog Page Template

**Location:** `theme-root/blog.php`

**Features:**
- Conditional rendering for post listing vs. submission form
- Category filtering via `$_GET['cat']`
- WP_Query with custom arguments
- Pagination with proper link structure

**Usage:**
- Display page with `?action=submit-post` query parameter to show form
- Display page normally to show blog listing
- Category filter via `?cat=category-slug`

**Template Hierarchy:**
```
Page Title: "Blog"
Template: "Blog"
URL: yoursite.com/blog/
```

### template-parts/blog-post.php - Post Card Component

**Location:** `theme-root/template-parts/blog-post.php`

**Features:**
- Featured image with hover zoom effect
- Post title linked to full post
- Author link with author archive
- Publication date
- Estimated reading time calculation
- Category badges
- Post excerpt
- "Read More" button

**Output Example:**
```html
<article class="blog-post-card">
  <div class="blog-post-image">
    <a href="/post-url">
      <img src="featured-image.jpg" alt="...">
    </a>
    <div class="post-categories">
      <span class="post-category-tag">Technology</span>
    </div>
  </div>
  <div class="blog-post-content">
    <!-- Post title, metadata, excerpt, etc. -->
  </div>
</article>
```

### template-parts/submit-post-form.php - Post Submission Form

**Location:** `theme-root/template-parts/submit-post-form.php`

**Features:**
- Title input with character counter (max 200)
- Featured image upload with drag-and-drop
- Excerpt textarea with character counter (max 500)
- Category multi-select checkboxes
- Rich text editor with toolbar
- Form validation and error display
- Capability checking (logged-in users only)

**Capabilities Required:**
- `publish_posts` - Required to view and submit form
- `upload_files` - Required for featured image upload

**Form Nonce:**
```php
wp_nonce_field('ppwp_submit_post_nonce', 'ppwp_post_nonce');
```

## Backend Processing

### inc/blog-submission-handler.php - Form Handler

**Location:** `theme-root/inc/blog-submission-handler.php`

**Functions:**

#### `ppwp_handle_post_submission()`
- Triggered on `wp` hook
- Verifies nonce and user capabilities
- Validates input (title, content length, etc.)
- Creates or updates post via `wp_insert_post()` or `wp_update_post()`
- Sets featured image via `set_post_thumbnail()`
- Assigns categories via `wp_set_post_categories()`
- Redirects to new post with success message

**Validation:**
- Title: Required, max 200 characters
- Content: Required, min 100 words
- Excerpt: Optional, max 500 characters
- Featured Image: Optional
- Categories: Optional

**Error Handling:**
- Stores errors in transients
- Redirects back to form with error messages
- Displays via `ppwp_display_form_messages()` hook

#### `ppwp_upload_featured_image()`
- AJAX handler for image upload
- Triggered by action `ppwp_upload_featured_image`
- Uses WordPress media library via `media_handle_upload()`
- Returns JSON with attachment ID and URL

**Nonce Required:**
```php
'ppwp_upload_image_nonce' => wp_create_nonce('ppwp_upload_image_nonce')
```

#### `ppwp_enqueue_blog_assets()`
- Enqueues blog.js on blog page template and single post pages
- Localizes script with AJAX URL and nonce

#### `ppwp_display_form_messages()`
- Hooks into `template_redirect`
- Displays success/error messages from form submission
- Cleans up transients after display

## Styling

### CSS Classes & Structure

**Blog Listing Container:**
```css
.blog-header              /* Header section with title */
.blog-title               /* H1 title */
.blog-description         /* Description paragraph */

.blog-filters             /* Category filter container */
.filter-title             /* Filter heading */
.category-list            /* Flex container for badges */
.category-badge           /* Individual category filter button */
.category-badge.active    /* Active category state */

.blog-posts               /* Grid container for post cards */
.blog-post-card           /* Individual post article wrapper */
.blog-post-image          /* Featured image container */
.blog-post-content        /* Post metadata and excerpt */
.blog-post-title          /* H2 post title */
.blog-post-meta           /* Metadata section (author, date) */
.blog-post-excerpt        /* Excerpt text */

.blog-pagination          /* Pagination container */
.cta-submit-post          /* Call-to-action section */
```

**Form Styling:**
```css
.submit-post-form-wrapper /* Main form container */
.form-header              /* Form header section */

.form-group               /* Individual form field group */
.form-label               /* Input label */
.form-input               /* Text input field */
.form-textarea            /* Textarea field */
.form-editor              /* Rich text editor div */
.form-help                /* Help text below fields */

.editor-toolbar           /* Rich text toolbar */
.toolbar-btn              /* Toolbar button */

.featured-image-upload    /* Featured image section */
.featured-image-preview   /* Image preview area */

.category-checkboxes      /* Category checkbox group */
.checkbox-item            /* Individual checkbox */

.form-actions             /* Submit/cancel buttons */
.form-notice              /* Success/error messages */
```

**Color Variables (CSS):**
```css
--color-primary: #2c3e50      /* Dark blue - headings */
--color-secondary: #3498db    /* Light blue - links, buttons */
--color-accent: #e74c3c       /* Red - highlights, errors */
--color-light: #ecf0f1        /* Light gray - backgrounds */
--color-lighter: #f8f9fa      /* Very light gray */
```

### Responsive Breakpoints

- **Desktop (768px+)**: Full layout with sidebar
- **Tablet (768px)**: Adjusted spacing, mobile menu
- **Mobile (480px)**: Single column, optimized touch targets

## JavaScript

### blog.js - Form Interactions

**Location:** `theme-root/assets/js/blog.js`

**Features:**

#### Content Editor
- Contenteditable DIV for rich text editing
- Toolbar buttons for formatting (bold, italic, underline, lists, links, images)
- Keyboard shortcuts (Ctrl+B, Ctrl+I, Ctrl+U)
- Sync content to hidden textarea on submit

#### Featured Image Upload
- Media uploader integration with WordPress media library
- Fallback file input for non-admin users
- Drag-and-drop support
- AJAX upload to media library
- Preview with remove option

#### Form Validation
- Real-time validation feedback
- Word count tracking for content
- Character count for title and excerpt
- Required field validation
- Error message display

#### User Experience
- Form field focus management
- Loading states on submit
- Scroll-to-error functionality
- Smooth transitions

**Initialization:**
```javascript
// Triggers on DOM ready
initSubmitPostForm()
initContentEditor()
initExcerptCounter()
initFeaturedImageUpload()
initFormValidation()
```

**Localized Variables:**
```javascript
ppwpBlog = {
  ajax_url: 'https://site.com/wp-admin/admin-ajax.php',
  upload_nonce: 'abc123...',
  user_can_publish: 'true'
}
```

## Security Considerations

### WordPress Standards

1. **Nonce Verification**
   - Post submission: `wp_nonce_field('ppwp_submit_post_nonce', 'ppwp_post_nonce')`
   - Image upload: `wp_verify_nonce($_POST['_wpnonce'])`

2. **Capability Checks**
   - `current_user_can('publish_posts')` - For post creation
   - `current_user_can('upload_files')` - For file uploads
   - Post ownership verification for editing

3. **Input Sanitization**
   - `sanitize_text_field()` - Title, category
   - `wp_kses_post()` - Post content (allows safe HTML)
   - `sanitize_textarea_field()` - Excerpt

4. **Output Escaping**
   - `esc_attr()` - HTML attributes
   - `esc_html()` - Text content
   - `esc_url()` - URLs
   - `wp_kses_post()` - Rich content

### Best Practices Implemented

- ✅ All AJAX requests use nonces
- ✅ Database queries use `wpdb->prepare()` implicitly via WP functions
- ✅ Form data sanitized before storage
- ✅ Output properly escaped for context
- ✅ Capability checks on all sensitive operations
- ✅ Redirect after POST to prevent re-submission
- ✅ Transient-based error messaging (temporary storage)
- ✅ Use of `is_wp_error()` for error handling

## Database Operations

### Post Creation

```php
$post_data = array(
  'post_title'   => sanitize_text_field($title),
  'post_content' => wp_kses_post($content),
  'post_excerpt' => sanitize_textarea_field($excerpt),
  'post_status'  => 'publish',
  'post_author'  => get_current_user_id(),
  'post_type'    => 'post'
);

$post_id = wp_insert_post($post_data);
```

### Featured Image

```php
set_post_thumbnail($post_id, $image_id);
```

### Categories

```php
wp_set_post_categories($post_id, array(1, 2, 3));
```

### Querying Posts

```php
$args = array(
  'post_type'      => 'post',
  'posts_per_page' => 9,
  'orderby'        => 'date',
  'order'          => 'DESC',
  'tax_query'      => array(array(
    'taxonomy' => 'category',
    'field'    => 'slug',
    'terms'    => 'technology'
  ))
);

$query = new WP_Query($args);
```

## Customization Guide

### Changing Post Per Page

**File:** `blog.php`
**Line:** `'posts_per_page' => 9,`
```php
'posts_per_page' => 12, // Show 12 posts per page
```

### Changing Post Status

**File:** `inc/blog-submission-handler.php`
**Line:** `'post_status' => 'publish',`
```php
'post_status' => 'pending', // Require moderation
```

### Adding Custom Fields

**In template-parts/submit-post-form.php:**
```php
<div class="form-group">
  <label for="my_custom_field" class="form-label">My Field</label>
  <input type="text" id="my_custom_field" name="my_custom_field" class="form-input">
</div>
```

**In inc/blog-submission-handler.php:**
```php
$custom_value = sanitize_text_field($_POST['my_custom_field']);
update_post_meta($post_id, 'my_custom_field', $custom_value);
```

### Modifying Editor Toolbar

**File:** `template-parts/submit-post-form.php`
```html
<button type="button" class="toolbar-btn" data-action="insertHorizontalRule">
  HR
</button>
```

**Supported actions:**
- bold, italic, underline
- strikeThrough, insertOrderedList, insertUnorderedList
- indent, outdent, formatBlock
- createLink, insertImage, insertHorizontalRule

### Changing Colors

**File:** `assets/css/main.css` (Lines with color variables)
```css
--color-secondary: #e74c3c; /* Change link colors */
--color-accent: #f39c12;    /* Change highlight color */
```

## Testing Checklist

- [ ] Blog page displays with category filter
- [ ] Click category badges to filter posts
- [ ] Click "Submit an Article" button (logged-in users only)
- [ ] Fill form and submit with valid data
- [ ] View new post published on blog page
- [ ] Test validation (try submitting empty form)
- [ ] Test featured image upload
- [ ] Test drag-and-drop for featured image
- [ ] Verify form errors display correctly
- [ ] Test editing existing post
- [ ] Test character counters
- [ ] Verify responsive design on mobile
- [ ] Check keyboard navigation
- [ ] Test with different user roles

## Troubleshooting

**Issue: Form not submitting**
- Check nonce is correct: `wp_verify_nonce()`
- Ensure user is logged in: `is_user_logged_in()`
- Verify user has `publish_posts` capability

**Issue: Featured image not uploading**
- Check `upload_files` capability
- Verify file is actually an image
- Check WordPress uploads folder permissions
- Inspect browser console for AJAX errors

**Issue: Categories not appearing**
- Ensure categories exist in WordPress Admin
- Check `hide_empty` is set correctly in category query
- Verify categories assigned to posts

**Issue: Form validation not working**
- Check JavaScript is loaded: Inspect `ppwpBlog` object
- Verify form IDs match JavaScript selectors
- Check browser console for JavaScript errors

**Issue: Styling looks broken**
- Ensure main.css is enqueued
- Check for CSS conflicts with other plugins
- Verify viewport meta tag in header

## Performance Optimization

1. **Lazy Load Featured Images**
   ```html
   <img ... loading="lazy" />
   ```

2. **Implement Caching**
   - Cache category queries
   - Use transients for expensive operations
   ```php
   if(!$categories = get_transient('blog_categories')) {
     $categories = get_categories();
     set_transient('blog_categories', $categories, 24*HOUR_IN_SECONDS);
   }
   ```

3. **Optimize Database Queries**
   - Use `posts_per_page` limit
   - Use `fields => 'ids'` for simple queries
   - Use `no_found_rows => true` if pagination not needed

4. **Minify Assets**
   - Use WordPress minification plugins
   - Or manually minify during production build

## Browser Support

- ✅ Chrome/Edge 90+
- ✅ Firefox 88+
- ✅ Safari 14+
- ✅ iOS Safari 14+
- ✅ Chrome Android

## License

This blog system is part of the Personal Project WordPress theme and follows the same license terms (GPL v2 or later).

## Support & Updates

For issues, feature requests, or customization help, refer to the theme documentation or contact the theme developer.
