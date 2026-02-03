<?php
/**
 * Template for Match Archive Page
 * Displays match history
 */

get_header(); ?>

<main class="site-main">
  
  <section class="matches-hero">
    <div class="hero-content">
      <h1 class="page-title">Match History</h1>
      <p class="hero-subtitle">A timeline of Barcelona FC's greatest moments</p>
    </div>
  </section>

  <div class="container">
    
    <div class="matches-filters">
      <button class="filter-btn active" data-filter="all">All Matches</button>
      <button class="filter-btn" data-filter="win">Wins</button>
      <button class="filter-btn" data-filter="draw">Draws</button>
      <button class="filter-btn" data-filter="loss">Losses</button>
    </div>

    <div class="matches-timeline">
      <?php
        if(have_posts()) {
          while(have_posts()) {
            the_post();
            $result = get_post_meta(get_the_ID(), 'result', true);
            $result_slug = sanitize_title($result);
            echo '<div class="match-item" data-result="' . esc_attr($result_slug) . '">';
            get_template_part('template-parts/match-card');
            echo '</div>';
          }
        } else {
          echo '<div class="no-matches"><p>No matches found.</p></div>';
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
document.querySelectorAll('.matches-filters .filter-btn').forEach(btn => {
  btn.addEventListener('click', function() {
    const filter = this.dataset.filter;
    
    document.querySelectorAll('.matches-filters .filter-btn').forEach(b => b.classList.remove('active'));
    this.classList.add('active');
    
    document.querySelectorAll('.matches-timeline .match-item').forEach(item => {
      if(filter === 'all' || item.dataset.result === filter) {
        item.style.display = '';
      } else {
        item.style.display = 'none';
      }
    });
  });
});
</script>

<?php get_footer(); ?>
