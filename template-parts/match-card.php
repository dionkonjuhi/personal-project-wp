<?php
/**
 * Template Part: Match Card
 * Displays individual match result
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('match-card'); ?>>
  
  <div class="match-header">
    <?php
      $date = get_post_meta(get_the_ID(), 'match_date', true);
      $competition = get_post_meta(get_the_ID(), 'competition', true);
      
      if($date) echo '<div class="match-date">' . esc_html($date) . '</div>';
      if($competition) echo '<div class="match-competition">' . esc_html($competition) . '</div>';
    ?>
  </div>

  <div class="match-score">
    <?php
      $opponent = get_post_meta(get_the_ID(), 'opponent', true);
      $barcelona_goals = get_post_meta(get_the_ID(), 'barcelona_goals', true);
      $opponent_goals = get_post_meta(get_the_ID(), 'opponent_goals', true);
      $result = get_post_meta(get_the_ID(), 'result', true);
    ?>
    
    <div class="match-team">
      <div class="team-name">Barcelona</div>
      <div class="team-score"><?php echo esc_html($barcelona_goals ?? '0'); ?></div>
    </div>

    <div class="match-vs">VS</div>

    <div class="match-team opponent">
      <div class="team-name"><?php echo esc_html($opponent ?? 'Opponent'); ?></div>
      <div class="team-score"><?php echo esc_html($opponent_goals ?? '0'); ?></div>
    </div>
  </div>

  <div class="match-footer">
    <?php
      if($result) {
        $result_class = 'result-' . sanitize_title($result);
        echo '<div class="match-result ' . esc_attr($result_class) . '">' . esc_html($result) . '</div>';
      }
    ?>
    
    <a href="<?php the_permalink(); ?>" class="match-link">Details →</a>
  </div>

</article>
