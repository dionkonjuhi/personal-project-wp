<?php
/**
 * Template for Single Match Page
 */

get_header(); ?>

<main class="site-main">
  
  <article class="single-match">
    
    <div class="match-hero">
      
      <div class="match-hero-content">
        <?php the_title('<h1 class="match-title">', '</h1>'); ?>
        
        <div class="match-details">
          <?php
            $date = get_post_meta(get_the_ID(), 'match_date', true);
            $competition = get_post_meta(get_the_ID(), 'competition', true);
            $stadium = get_post_meta(get_the_ID(), 'stadium', true);
            $attendance = get_post_meta(get_the_ID(), 'attendance', true);
            $opponent = get_post_meta(get_the_ID(), 'opponent', true);
            $barcelona_goals = get_post_meta(get_the_ID(), 'barcelona_goals', true);
            $opponent_goals = get_post_meta(get_the_ID(), 'opponent_goals', true);
            $result = get_post_meta(get_the_ID(), 'result', true);
            
            if($date) echo '<div class="detail"><strong>Date:</strong> ' . esc_html($date) . '</div>';
            if($competition) echo '<div class="detail"><strong>Competition:</strong> ' . esc_html($competition) . '</div>';
            if($stadium) echo '<div class="detail"><strong>Stadium:</strong> ' . esc_html($stadium) . '</div>';
            if($attendance) echo '<div class="detail"><strong>Attendance:</strong> ' . esc_html($attendance) . '</div>';
          ?>
        </div>

        <div class="match-score-large">
          <div class="team-column">
            <div class="team-name">Barcelona</div>
            <div class="team-score-large"><?php echo esc_html($barcelona_goals ?? '0'); ?></div>
          </div>

          <div class="vs-large">VS</div>

          <div class="team-column opponent">
            <div class="team-name"><?php echo esc_html($opponent ?? 'Opponent'); ?></div>
            <div class="team-score-large"><?php echo esc_html($opponent_goals ?? '0'); ?></div>
          </div>
        </div>

        <?php if($result) {
          $result_class = 'result-badge-' . sanitize_title($result);
          echo '<div class="result-badge ' . esc_attr($result_class) . '">' . esc_html($result) . '</div>';
        } ?>
      </div>
    </div>

    <div class="match-body">
      <div class="container">
        <?php the_content(); ?>
      </div>
    </div>

    <div class="match-nav">
      <div class="container">
        <div class="nav-buttons">
          <?php
            echo '<a href="' . get_post_type_archive_link('match') . '" class="btn btn-primary">← Back to Matches</a>';
          ?>
        </div>
      </div>
    </div>

  </article>

</main>

<?php get_footer(); ?>
