<?php
/**
 * Template Name: Blog
 * Description: Modern blog page with category filtering and post listing
 * 
 * This template displays all blog posts with category filtering,
 * featured images, metadata, and an editorial-style layout.
 *
 * @package PersonalProjectWP
 */

get_header();
?>

<div class="content-area">
  <main class="site-main blog-main">
    
    <!-- Check if viewing the post submission form -->
    <?php if(isset($_GET['action']) && $_GET['action'] === 'submit-post') : ?>
      
      <!-- Post Submission Form -->
      <?php get_template_part('template-parts/submit-post-form'); ?>

    <?php else : ?>

      <!-- Blog Listing View -->
      <!-- Blog Header -->
      <div class="blog-header">
        <div class="blog-header-content">
          <h1 class="blog-title">Latest Articles</h1>
          <p class="blog-description">Discover insights, stories, and perspectives curated just for you.</p>
        </div>
      </div>

      <!-- Category Filter -->
      <div class="blog-filters">
        <div class="filter-section">
          <h3 class="filter-title">Browse by Category</h3>
          <div class="category-list">
            <a href="<?php echo esc_url(get_permalink()); ?>" class="category-badge category-badge-all active" data-category="">
              All Articles
            </a>
            <?php
              // Get all post categories
              $categories = get_categories(array(
                'taxonomy' => 'category',
                'hide_empty' => true,
              ));

              foreach($categories as $category) {
                $active_class = isset($_GET['cat']) && $_GET['cat'] == $category->slug ? 'active' : '';
                echo sprintf(
                  '<a href="%s" class="category-badge %s" data-category="%s">%s <span class="badge-count">(%d)</span></a>',
                  esc_url(add_query_arg('cat', $category->slug)),
                  esc_attr($active_class),
                  esc_attr($category->slug),
                  esc_html($category->name),
                  intval($category->count)
                );
              }
            ?>
          </div>
        </div>
      </div>

      <!-- Post List -->
      <div class="blog-posts">
        <?php
          // Get category filter
          $category_filter = isset($_GET['cat']) ? sanitize_text_field($_GET['cat']) : '';

          // Setup WP_Query arguments
          $args = array(
            'post_type' => 'post',
            'posts_per_page' => 9,
            'paged' => get_query_var('paged') ? get_query_var('paged') : 1,
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

          $posts_query = new WP_Query($args);

          if($posts_query->have_posts()) :
            while($posts_query->have_posts()) : $posts_query->the_post();
              get_template_part('template-parts/blog-post');
            endwhile;

            // Pagination
            echo '<div class="blog-pagination">';
            echo paginate_links(array(
              'total' => $posts_query->max_num_pages,
              'current' => max(1, get_query_var('paged')),
              'type' => 'list',
              'prev_text' => __('← Previous', 'personal-project-wp'),
              'next_text' => __('Next →', 'personal-project-wp'),
            ));
            echo '</div>';

          else :
            echo '<div class="no-posts-found">';
            echo '<p>No articles found. Try selecting a different category or check back soon!</p>';
            echo '</div>';
          endif;

          wp_reset_postdata();
        ?>
      </div>

      <!-- Call to Action: Submit Post -->
      <?php if(is_user_logged_in()) : ?>
        <div class="cta-submit-post">
          <div class="cta-content">
            <h3>Have something to share?</h3>
            <p>Share your ideas and contribute to our community of writers.</p>
            <a href="<?php echo esc_url(get_permalink()); ?>?action=submit-post" class="btn btn-primary">
              Submit an Article
            </a>
          </div>
        </div>
      <?php endif; ?>

    <?php endif; ?>

  </main>

  <?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>
