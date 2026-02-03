<?php
/**
 * Template for Trophies Archive Page
 * Displays all Barcelona FC trophies and achievements
 */

get_header(); ?>

<main class="site-main">
  
  <section class="trophies-hero">
    <div class="hero-content">
      <h1 class="page-title">FC Barcelona Trophies</h1>
      <p class="hero-subtitle">A glorious legacy of success and achievement</p>
    </div>
  </section>

  <div class="container">
    
    <div class="trophies-grid">
      <?php
        if(have_posts()) {
          while(have_posts()) {
            the_post();
            get_template_part('template-parts/trophy-card');
          }
        } else {
          echo '<div class="no-trophies"><p>No trophies found.</p></div>';
        }
      ?>
    </div>

    <?php the_posts_pagination([
      'type' => 'list',
      'prev_text' => '← Previous',
      'next_text' => 'Next →'
    ]); ?>

  </div>

</main>

<?php get_footer(); ?>
