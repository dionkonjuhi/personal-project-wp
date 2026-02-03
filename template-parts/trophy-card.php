<?php
/**
 * Template Part: Trophy Card
 * Displays individual trophy achievement
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('trophy-card'); ?>>
  
  <div class="trophy-icon">
    <?php if(has_post_thumbnail()) : ?>
      <?php the_post_thumbnail('medium'); ?>
    <?php else : ?>
      <div class="trophy-icon-placeholder">
        <span>🏆</span>
      </div>
    <?php endif; ?>
  </div>

  <div class="trophy-content">
    <?php the_title('<h3 class="trophy-name">', '</h3>'); ?>
    
    <div class="trophy-meta">
      <?php
        $year = get_post_meta(get_the_ID(), 'year', true);
        $count = get_post_meta(get_the_ID(), 'count', true);
        $competition = get_post_meta(get_the_ID(), 'competition', true);
        
        if($year) echo '<div class="meta-item"><strong>Year:</strong> ' . esc_html($year) . '</div>';
        if($count) echo '<div class="meta-item"><strong>Total:</strong> ' . esc_html($count) . '</div>';
        if($competition) echo '<div class="meta-item"><strong>Competition:</strong> ' . esc_html($competition) . '</div>';
      ?>
    </div>

    <div class="trophy-description">
      <?php the_excerpt(); ?>
    </div>
  </div>

</article>
