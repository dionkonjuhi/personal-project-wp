<?php
/**
 * Template Part: Blog Post Card
 * 
 * Displays a single blog post in an editorial card format with
 * featured image, title, metadata, and excerpt.
 *
 * @package PersonalProjectWP
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('blog-post-card'); ?>>
  
  <!-- Featured Image -->
  <?php if(has_post_thumbnail()) : ?>
    <div class="blog-post-image">
      <a href="<?php the_permalink(); ?>">
        <?php the_post_thumbnail('large'); ?>
      </a>
      <?php
        // Display category badges
        $categories = get_the_category();
        if(!empty($categories)) {
          echo '<div class="post-categories">';
          foreach($categories as $category) {
            printf(
              '<span class="post-category-tag">%s</span>',
              esc_html($category->name)
            );
          }
          echo '</div>';
        }
      ?>
    </div>
  <?php endif; ?>

  <!-- Post Content -->
  <div class="blog-post-content">
    
    <!-- Title -->
    <?php the_title(
      sprintf(
        '<h2 class="blog-post-title"><a href="%s">',
        esc_url(get_permalink())
      ),
      '</a></h2>'
    ); ?>

    <!-- Metadata: Author, Date, Reading Time -->
    <div class="blog-post-meta">
      <div class="meta-item author">
        <?php
          $author_id = get_the_author_meta('ID');
          $author_url = get_author_posts_url($author_id);
          printf(
            '<span class="meta-label">By</span> <a href="%s" class="author-link">%s</a>',
            esc_url($author_url),
            esc_html(get_the_author())
          );
        ?>
      </div>
      
      <div class="meta-item date">
        <?php
          printf(
            '<span class="meta-label">Published</span> <time datetime="%s">%s</time>',
            esc_attr(get_the_date('c')),
            esc_html(get_the_date('F j, Y'))
          );
        ?>
      </div>

      <div class="meta-item reading-time">
        <?php
          $content = get_the_content();
          $word_count = str_word_count(strip_tags($content));
          $reading_time = ceil($word_count / 200); // Average 200 words per minute
          printf(
            '<span class="meta-label">Read time</span> %d min',
            intval($reading_time)
          );
        ?>
      </div>
    </div>

    <!-- Excerpt -->
    <div class="blog-post-excerpt">
      <?php the_excerpt(); ?>
    </div>

    <!-- Read More Link -->
    <a href="<?php the_permalink(); ?>" class="btn btn-secondary btn-small">
      Read Full Article →
    </a>

  </div>

</article>
