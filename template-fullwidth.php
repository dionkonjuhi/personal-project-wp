<?php
/*
Template Name: Full Width
*/
get_header(); ?>
<div class="content-area full-width">
  <main class="site-main">
    <?php if(have_posts()): while(have_posts()): the_post(); ?>
      <article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
        <?php the_title('<h1>','</h1>'); ?>
        <div class="entry-content"><?php the_content(); ?></div>
      </article>
    <?php endwhile; endif; ?>
  </main>
</div>
<?php get_footer(); ?>
