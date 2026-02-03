<?php
/**
 * Template Part: Legend Card
 * Displays individual legend profile
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('legend-card'); ?>>
  
  <div class="legend-image">
    <?php if(has_post_thumbnail()) : ?>
      <?php the_post_thumbnail('large'); ?>
    <?php else : ?>
      <div class="legend-image-placeholder">
        <span>⚽</span>
      </div>
    <?php endif; ?>
  </div>

  <div class="legend-content">
    <?php the_title('<h2 class="legend-name">', '</h2>'); ?>
    
    <div class="legend-meta">
      <?php
        $position = get_post_meta(get_the_ID(), 'position', true);
        $years = get_post_meta(get_the_ID(), 'years', true);
        $nationality = get_post_meta(get_the_ID(), 'nationality', true);
        
        if($position) echo '<div class="meta-item"><strong>Position:</strong> ' . esc_html($position) . '</div>';
        if($years) echo '<div class="meta-item"><strong>Years:</strong> ' . esc_html($years) . '</div>';
        if($nationality) echo '<div class="meta-item"><strong>Nationality:</strong> ' . esc_html($nationality) . '</div>';
      ?>
    </div>

    <div class="legend-excerpt">
      <?php the_excerpt(); ?>
    </div>

    <a href="<?php the_permalink(); ?>" class="btn btn-primary">
      View Full Profile →
    </a>
  </div>

</article>
