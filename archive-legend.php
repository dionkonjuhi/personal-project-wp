<?php
/**
 * Template for Legends Archive Page
 * Displays all Barcelona FC legends
 */

get_header(); ?>

<main class="site-main">
  
  <section class="legends-hero">
    <div class="hero-content">
      <h1 class="page-title">FC Barcelona Legends</h1>
      <p class="hero-subtitle">The immortal heroes who shaped our club's destiny</p>
    </div>
  </section>

  <div class="container">
    
    <div class="legends-grid">
      <?php
        if(have_posts()) {
          while(have_posts()) {
            the_post();
            get_template_part('template-parts/legend-card');
          }
        } else {
          echo '<div class="no-legends"><p>No legends found.</p></div>';
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
