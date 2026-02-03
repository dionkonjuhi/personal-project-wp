<?php
/**
 * Template Part: Player Card
 * Displays individual player profile
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('player-card'); ?>>
  
  <div class="player-image">
    <?php if(has_post_thumbnail()) : ?>
      <?php the_post_thumbnail('large'); ?>
    <?php else : ?>
      <div class="player-image-placeholder">
        <span>⚽</span>
      </div>
    <?php endif; ?>
  </div>

  <div class="player-content">
    <?php the_title('<h2 class="player-name">', '</h2>'); ?>
    
    <div class="player-meta">
      <?php
        $number = get_post_meta(get_the_ID(), 'number', true);
        $position = get_post_meta(get_the_ID(), 'position', true);
        $nationality = get_post_meta(get_the_ID(), 'nationality', true);
        $joined = get_post_meta(get_the_ID(), 'joined', true);
        
        if($number) echo '<div class="meta-item jersey-number">#' . esc_html($number) . '</div>';
        if($position) echo '<div class="meta-item"><strong>Position:</strong> ' . esc_html($position) . '</div>';
        if($nationality) echo '<div class="meta-item"><strong>Nationality:</strong> ' . esc_html($nationality) . '</div>';
        if($joined) echo '<div class="meta-item"><strong>Joined:</strong> ' . esc_html($joined) . '</div>';
      ?>
    </div>

    <div class="player-excerpt">
      <?php the_excerpt(); ?>
    </div>

    <a href="<?php the_permalink(); ?>" class="btn btn-primary">
      View Profile →
    </a>
  </div>

</article>
