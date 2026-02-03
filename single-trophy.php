<?php
/**
 * Template for Single Trophy Page
 */

get_header(); ?>

<main class="site-main">
  
  <article class="single-trophy">
    
    <div class="trophy-hero">
      <?php if(has_post_thumbnail()) : ?>
        <div class="trophy-hero-image">
          <?php the_post_thumbnail('full'); ?>
        </div>
      <?php endif; ?>
      
      <div class="trophy-hero-content">
        <?php the_title('<h1 class="trophy-title">', '</h1>'); ?>
        
        <div class="trophy-details">
          <?php
            $year = get_post_meta(get_the_ID(), 'year', true);
            $count = get_post_meta(get_the_ID(), 'count', true);
            $competition = get_post_meta(get_the_ID(), 'competition', true);
            $significance = get_post_meta(get_the_ID(), 'significance', true);
            
            if($year) echo '<div class="detail"><strong>Year Won:</strong> ' . esc_html($year) . '</div>';
            if($count) echo '<div class="detail"><strong>Total Count:</strong> ' . esc_html($count) . '</div>';
            if($competition) echo '<div class="detail"><strong>Competition:</strong> ' . esc_html($competition) . '</div>';
            if($significance) echo '<div class="detail"><strong>Significance:</strong> ' . wp_kses_post($significance) . '</div>';
          ?>
        </div>
      </div>
    </div>

    <div class="trophy-body">
      <div class="container">
        <?php the_content(); ?>
      </div>
    </div>

    <div class="trophy-nav">
      <div class="container">
        <div class="nav-buttons">
          <?php
            echo '<a href="' . get_post_type_archive_link('trophy') . '" class="btn btn-primary">← Back to Trophies</a>';
          ?>
        </div>
      </div>
    </div>

  </article>

</main>

<?php get_footer(); ?>
