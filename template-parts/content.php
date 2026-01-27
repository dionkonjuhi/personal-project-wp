<?php // Template part for content fallback
if(!have_posts()){
  echo '<section><h2>No posts found</h2><p>Try a search.</p></section>';
  return;
}
while(have_posts()): the_post();
  ?>
  <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php the_title('<h2 class="entry-title">','</h2>'); ?>
    <div class="entry-meta">Posted on <?php the_time('F j, Y'); ?></div>
    <div class="entry-content"><?php the_content(); ?></div>
    <div class="entry-tags"><?php the_tags('<strong>Tags:</strong> ',' , ',''); ?></div>
    <?php edit_post_link('Edit this post','<p>','</p>'); ?>
  </article>
<?php
endwhile;
