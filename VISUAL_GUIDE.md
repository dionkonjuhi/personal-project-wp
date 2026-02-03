# Blog System - Visual Guide & Architecture

## 🏗️ System Architecture Diagram

```
┌─────────────────────────────────────────────────────────┐
│                    WordPress Blog System                │
└─────────────────────────────────────────────────────────┘
                            │
         ┌──────────────────┼──────────────────┐
         │                  │                  │
    ┌────▼────┐      ┌─────▼─────┐      ┌────▼────┐
    │  Blog   │      │  Post Form│      │ Backend │
    │  Page   │      │  Template │      │ Handler │
    │(blog.php)      │(submit-  │      │  (inc/  │
    └─────┬──┘       │post-form)│      │blog-..)│
          │          └─────┬────┘      └────┬───┘
          │                │                │
    ┌─────▼────────────────▼────────────────▼────┐
    │  Styling (assets/css/main.css +600 lines) │
    └────────────────────────────────────────────┘
         │
    ┌────▼──────────────────────┐
    │  JavaScript (blog.js)     │
    │  - Rich text editor       │
    │  - Form validation        │
    │  - Image upload (AJAX)    │
    └────┬──────────────────────┘
         │
    ┌────▼──────────────────┐
    │  WordPress Database   │
    │  - Posts              │
    │  - Categories         │
    │  - Attachments        │
    └───────────────────────┘
```

## 📊 Data Flow Diagram

### Blog Listing Flow
```
User visits /blog/
          ↓
blog.php loads
          ↓
Get categories (with count)
          ↓
Setup WP_Query with filters
          ↓
Loop through posts
          ↓
template-parts/blog-post.php renders each
          ↓
Display pagination
          ↓
Show CTA button (if logged in)
```

### Post Submission Flow
```
User clicks "Submit Article"
          ↓
URL becomes ?action=submit-post
          ↓
submit-post-form.php loads
          ↓
User fills form & uploads image
          ↓
Image upload via AJAX ───→ media_handle_upload()
                              ↓
                         Attachment created
          ↓
User submits form
          ↓
WordPress processes POST
          ↓
ppwp_handle_post_submission() triggered
          ↓
Validate & sanitize inputs
          ↓
wp_insert_post() or wp_update_post()
          ↓
set_post_thumbnail()
          ↓
wp_set_post_categories()
          ↓
Success message in transient
          ↓
Redirect to new post
```

## 🎯 Component Relationships

```
blog.php (Main Template)
    │
    ├─── Includes: template-parts/blog-post.php
    │       │
    │       └─── Loops through posts from WP_Query
    │
    ├─── Includes: template-parts/submit-post-form.php
    │       │
    │       └─── Form submission triggered on POST
    │
    └─── Functions from: header.php, sidebar.php, footer.php

blog-post.php (Component)
    │
    ├─── Uses: get_post_thumbnail()
    ├─── Uses: get_the_category()
    ├─── Uses: get_the_author_posts_url()
    ├─── Uses: get_the_date()
    └─── Uses: get_the_excerpt()

submit-post-form.php (Component)
    │
    └─── Communicates with:
         ├─── blog.js (Frontend validation & upload)
         ├─── blog-submission-handler.php (AJAX endpoint)
         └─── WordPress Media Library (Image upload)

blog-submission-handler.php (Backend)
    │
    ├─── Action Hook: wp (Form submission)
    ├─── AJAX Hook: wp_ajax_ppwp_upload_featured_image (Upload)
    ├─── WordPress: wp_insert_post() / wp_update_post()
    ├─── WordPress: set_post_thumbnail()
    └─── WordPress: wp_set_post_categories()

assets/js/blog.js (Interactions)
    │
    ├─── Rich Text Editor
    │    └─── execCommand() API
    │
    ├─── Form Validation
    │    ├─── Real-time feedback
    │    └─── Submit validation
    │
    ├─── Image Upload (AJAX)
    │    ├─── Drag-and-drop
    │    ├─── WordPress Media Library
    │    └─── Fallback file input
    │
    └─── UX Enhancements
         ├─── Character counters
         ├─── Word counters
         └─── Loading states

assets/css/main.css (Styling)
    │
    ├─── Blog Grid Layout
    │    └─── CSS Grid auto-fit
    │
    ├─── Form Styling
    │    ├─── Input focus states
    │    ├─── Toolbar styling
    │    └─── Editor area
    │
    ├─── Responsive Design
    │    ├─── Desktop (768px+)
    │    ├─── Tablet (768px)
    │    └─── Mobile (480px)
    │
    └─── Animations
         ├─── Fade-in on scroll
         ├─── Hover effects
         └─── Transitions
```

## 🎨 Page Layout Diagram

### Blog Listing Page
```
┌─────────────────────────────────────────┐
│         Blog Header                     │
│  "Latest Articles"                      │
│  "Discover insights..."                 │
└─────────────────────────────────────────┘

┌─────────────────────────────────────────┐
│   Category Filters                      │
│  [All] [Tech] [Design] [Business] ...  │
└─────────────────────────────────────────┘

┌────────────────────┐  ┌────────────────────┐
│  Post Card 1       │  │  Post Card 2       │
│ [Featured Image]   │  │ [Featured Image]   │
│ Title              │  │ Title              │
│ Author | Date      │  │ Author | Date      │
│ Excerpt...         │  │ Excerpt...         │
│ [Read More →]      │  │ [Read More →]      │
└────────────────────┘  └────────────────────┘
         │                      │
         ▼                      ▼
    (Repeats in 3-column grid)

┌─────────────────────────────────────────┐
│     Pagination                          │
│  [← Prev] [1] [2] [3] [Next →]         │
└─────────────────────────────────────────┘

┌─────────────────────────────────────────┐
│     Call to Action                      │
│  "Have something to share?"             │
│  [Submit an Article →]                  │
└─────────────────────────────────────────┘
```

### Post Submission Form Layout
```
┌─────────────────────────────────────────┐
│     Submit Your Article                 │
│  "Share your story with our community." │
└─────────────────────────────────────────┘

┌─────────────────────────────────────────┐
│ Article Title *                         │
│ [Text input field - max 200 chars]      │
│ Keep your title clear... (help text)    │
└─────────────────────────────────────────┘

┌─────────────────────────────────────────┐
│ Featured Image                          │
│ ┌─────────────────────────────────────┐ │
│ │  [Drag drop or Click to upload]     │ │
│ │  JPG, PNG, GIF                      │ │
│ └─────────────────────────────────────┘ │
└─────────────────────────────────────────┘

┌─────────────────────────────────────────┐
│ Article Summary                         │
│ [Text area - max 500 chars]             │
│ Character count: 245/500                │
└─────────────────────────────────────────┘

┌─────────────────────────────────────────┐
│ Categories                              │
│ ☐ Technology    ☐ Design               │
│ ☐ Business      ☐ Other                │
└─────────────────────────────────────────┘

┌─────────────────────────────────────────┐
│ Article Content *                       │
│ ┌─────────────────────────────────────┐ │
│ │ [B] [I] [U] | • # | 🔗 🖼           │ │
│ ├─────────────────────────────────────┤ │
│ │ Write your article here...          │ │
│ │ You can format using toolbar above. │ │
│ │                                     │ │
│ │ (Contenteditable div)               │ │
│ │                                     │ │
│ └─────────────────────────────────────┘ │
└─────────────────────────────────────────┘

┌─────────────────────────────────────────┐
│  [Publish Article]  [Cancel]            │
└─────────────────────────────────────────┘

  ✓ Success: "Article published!"
  ✗ Error: "Please fix the following..."
```

## 🔄 State Diagram

### Form States
```
EMPTY
  │
  ├─→ VALID (Title + Content + 100+ words)
  │     │
  │     └─→ SUBMITTING ──→ PROCESSING
  │                           │
  │                    ┌──────┴──────┐
  │                    ▼             ▼
  │              SUCCESS         ERROR
  │                │               │
  │                └──→ PUBLISHED  └──→ FORM (with errors)
  │
  └─→ INVALID (Validation fails)
        │
        └─→ ERRORS DISPLAYED
              │
              └─→ USER CORRECTS
                    │
                    └─→ VALID
```

## 🛠️ Technology Stack

```
Frontend
  ├── HTML5 (Semantic markup)
  ├── CSS3 (Flexbox, Grid, Animations)
  ├── JavaScript (Vanilla, no jQuery required)
  └── Contenteditable API (Rich text editing)

Backend
  ├── PHP 7.2+
  ├── WordPress 5.0+
  └── MySQL / MariaDB

Security
  ├── Nonce verification
  ├── Capability checks
  ├── Input sanitization
  └── Output escaping

Performance
  ├── CSS Grid (efficient layouts)
  ├── CSS Transforms (GPU acceleration)
  ├── AJAX (non-blocking uploads)
  └── WP_Query (optimized queries)
```

## 📈 Performance Considerations

```
Blog Listing Page
  ├── Initial Load: ~50ms (markup)
  ├── CSS Parsing: ~30ms
  ├── JavaScript: ~20ms (minimal)
  ├── Image Loading: ~500ms+ (lazy-loadable)
  └── Total: ~600ms+ (depending on images)

Form Page
  ├── Initial Load: ~50ms
  ├── CSS: ~30ms
  ├── JavaScript: ~50ms (editor init)
  ├── Editor Ready: ~100ms
  └── Total: ~230ms

Post Submission
  ├── Validation: ~10ms
  ├── Upload (AJAX): ~2-5s (file dependent)
  ├── Form Submit: ~500ms
  └── Redirect: ~200ms
```

## 🔐 Security Layers

```
Input
  ├── Nonce verification (CSRF protection)
  ├── User logged-in check
  ├── Capability check (publish_posts)
  └── Form data validation

Processing
  ├── Input sanitization (sanitize_text_field, etc.)
  ├── Post ownership verification
  ├── Error handling (no sensitive info exposed)
  └── Prepared statements (via WP functions)

Output
  ├── Context-aware escaping (esc_html, esc_attr, etc.)
  ├── HTML content filtering (wp_kses_post)
  ├── URL validation (esc_url)
  └── Proper content-type headers
```

## 🎯 User Flows

### New User Flow
```
1. User signs up → WordPress creates account
2. Admin grants publish_posts capability
3. User logs in → Redirected to blog
4. User clicks "Submit Article"
5. User fills form (guided by labels & help text)
6. User uploads featured image (drag-drop)
7. User selects categories
8. User writes content using editor
9. User submits form
10. Form validated → Post created
11. User sees success message
12. User redirected to published post
```

### Returning Author Flow
```
1. User logs in
2. Navigates to blog
3. Clicks "Submit Article"
4. Form loads (empty)
5. User creates new post
6. Process same as new user
```

### Editor/Admin Flow
```
1. Admin logs in
2. Goes to Posts → All Posts
3. Sees user-submitted posts
4. Can edit, publish, or delete
5. Can change post status to pending (moderation)
6. Can view comments and respond
```

## 📋 Accessibility Features

```
Visual Design
  ├── High contrast (WCAG AA)
  ├── Clear typography
  ├── Proper spacing
  └── Icons with text labels

Keyboard Navigation
  ├── Tab through form fields
  ├── Enter to submit
  ├── Escape to close modals
  └── Arrow keys in editor

Screen Readers
  ├── Semantic HTML5 tags
  ├── ARIA labels on inputs
  ├── Alt text on images
  └── Proper heading hierarchy

Motor Disabilities
  ├── Large touch targets (48px+)
  ├── Drag-drop with keyboard alternative
  ├── Click/tap friendly buttons
  └── No time-dependent interactions
```

## 🚀 Deployment Checklist

```
Pre-Deployment
  ☐ Test on desktop, tablet, mobile
  ☐ Test with Chrome, Firefox, Safari
  ☐ Test with different user roles
  ☐ Test keyboard navigation
  ☐ Check console for JS errors
  ☐ Verify all links work
  ☐ Check form validation
  ☐ Test file uploads

Database
  ☐ Backup WordPress database
  ☐ Verify table permissions
  ☐ Check upload folder permissions

Configuration
  ☐ Set correct post status (publish/pending)
  ☐ Verify file upload limits
  ☐ Check user role capabilities
  ☐ Test email notifications

Performance
  ☐ Enable caching if available
  ☐ Minify assets
  ☐ Optimize images
  ☐ Check server response time

Security
  ☐ Verify HTTPS is enabled
  ☐ Check nonce validity
  ☐ Test permission checks
  ☐ Review error messages (no sensitive info)
  ☐ Enable WordPress security plugins
```

---

**Visual Guide Created:** February 3, 2026  
**Last Updated:** February 3, 2026  
**Status:** Complete
