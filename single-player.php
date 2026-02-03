<?php
/**
 * Template for Single Player Page
 */

get_header(); ?>

<main class="site-main">
  
  <article class="single-player">
    
    <div class="player-hero">
      <?php if(has_post_thumbnail()) : ?>
        <div class="player-hero-image">
          <?php the_post_thumbnail('full'); ?>
        </div>
      <?php endif; ?>
      
      <div class="player-hero-content">
        <?php the_title('<h1 class="player-title">', '</h1>'); ?>
        
        <div class="player-stats">
          <?php
            $number = get_post_meta(get_the_ID(), 'number', true);
            $position = get_post_meta(get_the_ID(), 'position', true);
            $nationality = get_post_meta(get_the_ID(), 'nationality', true);
            $joined = get_post_meta(get_the_ID(), 'joined', true);
            $appearances = get_post_meta(get_the_ID(), 'appearances', true);
            $goals = get_post_meta(get_the_ID(), 'goals', true);
            $assists = get_post_meta(get_the_ID(), 'assists', true);
            
            if($number) echo '<div class="stat"><span class="stat-label">Number</span><span class="stat-value">' . esc_html($number) . '</span></div>';
            if($position) echo '<div class="stat"><span class="stat-label">Position</span><span class="stat-value">' . esc_html($position) . '</span></div>';
            if($nationality) echo '<div class="stat"><span class="stat-label">Nationality</span><span class="stat-value">' . esc_html($nationality) . '</span></div>';
            if($joined) echo '<div class="stat"><span class="stat-label">Joined</span><span class="stat-value">' . esc_html($joined) . '</span></div>';
            if($appearances) echo '<div class="stat"><span class="stat-label">Appearances</span><span class="stat-value">' . esc_html($appearances) . '</span></div>';
            if($goals) echo '<div class="stat"><span class="stat-label">Goals</span><span class="stat-value">' . esc_html($goals) . '</span></div>';
            if($assists) echo '<div class="stat"><span class="stat-label">Assists</span><span class="stat-value">' . esc_html($assists) . '</span></div>';
          ?>
        </div>
      </div>
    </div>

    <div class="player-body">
      <div class="container">
        <?php the_content(); ?>
      </div>
    </div>

    <div class="player-nav">
      <div class="container">
        <div class="nav-buttons">
          <?php
            echo '<a href="' . get_post_type_archive_link('player') . '" class="btn btn-primary">← Back to Squad</a>';
          ?>
        </div>
      </div>
    </div>

  </article>

</main>

<?php get_footer(); ?>
