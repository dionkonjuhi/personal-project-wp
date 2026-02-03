<?php
/**
 * Template for Single Legend Page
 */

get_header(); ?>

<main class="site-main">
  
  <article class="single-legend">
    
    <div class="legend-hero">
      <?php if(has_post_thumbnail()) : ?>
        <div class="legend-hero-image">
          <?php the_post_thumbnail('full'); ?>
        </div>
      <?php endif; ?>
      
      <div class="legend-hero-content">
        <?php the_title('<h1 class="legend-title">', '</h1>'); ?>
        
        <div class="legend-stats">
          <?php
            $position = get_post_meta(get_the_ID(), 'position', true);
            $years = get_post_meta(get_the_ID(), 'years', true);
            $nationality = get_post_meta(get_the_ID(), 'nationality', true);
            $matches = get_post_meta(get_the_ID(), 'matches', true);
            $goals = get_post_meta(get_the_ID(), 'goals', true);
            $trophies = get_post_meta(get_the_ID(), 'trophies_won', true);
            
            if($position) echo '<div class="stat"><span class="stat-label">Position</span><span class="stat-value">' . esc_html($position) . '</span></div>';
            if($years) echo '<div class="stat"><span class="stat-label">Years</span><span class="stat-value">' . esc_html($years) . '</span></div>';
            if($nationality) echo '<div class="stat"><span class="stat-label">Nationality</span><span class="stat-value">' . esc_html($nationality) . '</span></div>';
            if($matches) echo '<div class="stat"><span class="stat-label">Matches</span><span class="stat-value">' . esc_html($matches) . '</span></div>';
            if($goals) echo '<div class="stat"><span class="stat-label">Goals</span><span class="stat-value">' . esc_html($goals) . '</span></div>';
            if($trophies) echo '<div class="stat"><span class="stat-label">Trophies</span><span class="stat-value">' . esc_html($trophies) . '</span></div>';
          ?>
        </div>
      </div>
    </div>

    <div class="legend-body">
      <div class="container">
        <?php the_content(); ?>
      </div>
    </div>

    <div class="legend-nav">
      <div class="container">
        <div class="nav-buttons">
          <?php
            $prev_post = get_previous_post();
            $next_post = get_next_post();
            
            if($prev_post) {
              echo '<a href="' . get_permalink($prev_post->ID) . '" class="btn btn-secondary">← Previous Legend</a>';
            }
            
            echo '<a href="' . get_post_type_archive_link('legend') . '" class="btn btn-primary">Back to Legends</a>';
            
            if($next_post) {
              echo '<a href="' . get_permalink($next_post->ID) . '" class="btn btn-secondary">Next Legend →</a>';
            }
          ?>
        </div>
      </div>
    </div>

  </article>

</main>

<?php get_footer(); ?>
