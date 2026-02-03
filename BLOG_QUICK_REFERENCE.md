# Blog System - Quick Reference Guide

## Files Created/Modified

### New Files
1. **blog.php** - Main blog page template with category filtering
2. **template-parts/blog-post.php** - Reusable post card component
3. **template-parts/submit-post-form.php** - User post submission form
4. **inc/blog-submission-handler.php** - Backend form processing & AJAX
5. **assets/js/blog.js** - Form interactions and editor functionality
6. **BLOG_DOCUMENTATION.md** - Complete documentation (this file)

### Modified Files
1. **functions.php** - Added include for blog-submission-handler.php
2. **assets/css/main.css** - Added 300+ lines of blog and form styling

## Getting Started (5 Minutes)

### Step 1: Create Blog Page
1. WordPress Admin → Pages → Add New
2. Title: "Blog"
3. Template: "Blog"
4. Publish

### Step 2: Log In & Test
1. Go to your blog page
2. Click "Submit an Article" button
3. Fill in form and publish

### Step 3: View Your Blog
1. See your new article on blog page
2. Use category filters
3. Click article to view full post

## Key Features at a Glance

| Feature | Location | Notes |
|---------|----------|-------|
| Blog listing | blog.php | Displays posts with filtering |
| Post submission form | template-parts/submit-post-form.php | Only for logged-in users |
| Form processing | inc/blog-submission-handler.php | Handles validation & saving |
| Rich text editor | assets/js/blog.js | Formatting toolbar included |
| Category filtering | blog.php template | Auto-populated from WordPress |
| Featured images | template-parts/blog-post.php | Drag-drop upload support |
| Responsive design | assets/css/main.css | Mobile-optimized |

## Common Tasks

### Hide the Submit Form Button
**File:** `blog.php` (lines near end)
```php
<?php if(is_user_logged_in() && current_user_can('publish_posts')) : ?>
  <!-- CTA appears here -->
<?php endif; ?>
```

### Change Posts Per Page
**File:** `blog.php` (line ~60)
```php
'posts_per_page' => 12, // Change from 9 to 12
```

### Require Post Approval Before Publishing
**File:** `inc/blog-submission-handler.php` (line ~90)
```php
'post_status' => 'pending', // Change from 'publish'
```

### Add Custom Field to Form
**File:** `template-parts/submit-post-form.php`
```php
<div class="form-group">
  <label for="my_field" class="form-label">My Field</label>
  <input type="text" id="my_field" name="my_field" class="form-input">
</div>
```

Then in `inc/blog-submission-handler.php`:
```php
$my_value = sanitize_text_field($_POST['my_field']);
update_post_meta($new_post_id, 'my_field', $my_value);
```

### Change Colors
**File:** `assets/css/main.css` (lines ~78-88)
```css
--color-secondary: #3498db;  /* Links, buttons */
--color-accent: #e74c3c;     /* Highlights */
```

## Security Checklist

✅ **Done**
- Nonce verification on forms
- User capability checks
- Input sanitization
- Output escaping
- Post ownership verification
- SQL injection protection (via WP functions)
- Cross-site scripting (XSS) protection

✅ **You Should**
- Keep WordPress updated
- Use HTTPS on production
- Regularly backup database
- Test with different user roles
- Review user capabilities in plugins

## Styling Reference

### Main Classes
```css
/* Blog page */
.blog-header
.blog-filters
.blog-posts
.blog-post-card
.category-badge

/* Form */
.submit-post-form-wrapper
.form-group
.form-label
.form-input
.form-editor
.editor-toolbar

/* Utilities */
.btn-primary         /* Blue button */
.btn-secondary       /* Gray button */
.btn-outline         /* Transparent button */
.form-notice-success /* Green notice */
.form-notice-error   /* Red notice */
```

### Color Palette
```
Primary:   #2c3e50  (Dark blue-gray)
Secondary: #3498db  (Bright blue)
Accent:    #e74c3c  (Red)
Light:     #ecf0f1  (Light gray)
Lighter:   #f8f9fa  (Very light)
Text:      #333     (Dark gray)
```

## JavaScript Functions

### Available in blog.js

```javascript
// Form interaction
initSubmitPostForm()        // Setup form submission
initContentEditor()         // Setup rich text editor
initExcerptCounter()        // Character counter
initFeaturedImageUpload()   // Image upload handling
initFormValidation()        // Validate on submit
```

### Accessing AJAX
```javascript
ppwpBlog.ajax_url       // WordPress AJAX endpoint
ppwpBlog.upload_nonce   // Nonce for uploads
ppwpBlog.user_can_publish // Permission flag
```

## API/Hooks

### PHP Hooks

**Action:** `wp` (Main form submission trigger)
```php
add_action('wp', 'ppwp_handle_post_submission');
```

**Action:** `wp_ajax_ppwp_upload_featured_image` (Image upload)
```php
add_action('wp_ajax_ppwp_upload_featured_image', 'ppwp_upload_featured_image');
```

**Action:** `wp_enqueue_scripts` (Asset loading)
```php
add_action('wp_enqueue_scripts', 'ppwp_enqueue_blog_assets');
```

**Action:** `template_redirect` (Display messages)
```php
add_action('template_redirect', 'ppwp_display_form_messages');
```

## Query Parameters

### Blog Page Query String
```
/blog/                          # Main blog page
/blog/?cat=technology           # Filter by category slug
/blog/page/2/                   # Pagination
/blog/?cat=tech&paged=2         # Combined
/blog/?action=submit-post       # Show submission form
```

## Database Structure

### Posts Created
- Post Type: `post`
- Post Status: `publish` (default, can be changed)
- Post Format: `standard` (no special format)
- Author: Current logged-in user
- Content: Sanitized HTML via `wp_kses_post()`

### Post Meta
- Featured image set via `set_post_thumbnail()`
- No custom post meta required

### Post Categories
- Standard WordPress categories (default taxonomy)
- Set via `wp_set_post_categories()`
- Multiple categories per post supported

## Troubleshooting Guide

| Problem | Solution |
|---------|----------|
| Form not visible | Check user is logged in AND has `publish_posts` capability |
| Featured image won't upload | Verify file is image, check file permissions, clear browser cache |
| Categories not filtering | Create categories in WordPress Admin, assign to posts |
| Form validation failing | Check browser console for JS errors, verify form field IDs |
| Styled incorrectly | Ensure main.css is enqueued, check for CSS conflicts |
| Can't edit own post | Only authors can edit their own posts (unless admin) |

## Performance Notes

- Grid layout uses CSS Grid (efficient)
- Animations use CSS transforms (GPU-accelerated)
- AJAX upload is non-blocking
- WP_Query optimized with posts_per_page limit
- No additional database tables required

## Next Steps

1. **Customize Styling** - Modify colors in main.css
2. **Add Custom Fields** - Follow "Common Tasks" section
3. **Set Post Approval** - Change post_status to 'pending'
4. **Add More Features** - See full documentation for extension ideas
5. **Test Thoroughly** - Use testing checklist in main documentation

---

**Need Help?** Check BLOG_DOCUMENTATION.md for complete details.
