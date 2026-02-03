<?php
/**
 * Template for Players Archive Page
 * Displays squad roster
 */

get_header(); ?>

<main class="site-main">
  
  <section class="players-hero">
    <div class="hero-content">
      <h1 class="page-title">FC Barcelona Squad</h1>
      <p class="hero-subtitle">Meet the players who represent our colors</p>
    </div>
  </section>

  <div class="container">
    
    <div class="players-filters">
      <button class="filter-btn active" data-filter="all">All Players</button>
      <button class="filter-btn" data-filter="goalkeeper">Goalkeepers</button>
      <button class="filter-btn" data-filter="defender">Defenders</button>
      <button class="filter-btn" data-filter="midfielder">Midfielders</button>
      <button class="filter-btn" data-filter="forward">Forwards</button>
    </div>

    <div class="players-grid">
      <?php
        if(have_posts()) {
          while(have_posts()) {
            the_post();
            $position = get_post_meta(get_the_ID(), 'position', true);
            $position_slug = sanitize_title($position);
            echo '<div class="player-item" data-position="' . esc_attr($position_slug) . '">';
            get_template_part('template-parts/player-card');
            echo '</div>';
          }
        } else {
          echo '<div class="no-players"><p>No players found.</p></div>';
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

<script>
document.querySelectorAll('.players-filters .filter-btn').forEach(btn => {
  btn.addEventListener('click', function() {
    const filter = this.dataset.filter;
    
    document.querySelectorAll('.players-filters .filter-btn').forEach(b => b.classList.remove('active'));
    this.classList.add('active');
    
    document.querySelectorAll('.players-grid .player-item').forEach(item => {
      if(filter === 'all' || item.dataset.position === filter) {
        item.style.display = '';
      } else {
        item.style.display = 'none';
      }
    });
  });
});
</script>

<?php get_footer(); ?>
