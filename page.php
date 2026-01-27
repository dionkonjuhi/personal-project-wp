<?php get_header(); ?>
<div class="content-area">
  <main class="site-main">
    <?php if(have_posts()): while(have_posts()): the_post(); ?>
      <article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
        <?php the_title('<h1>','</h1>'); ?>
        <div class="entry-content"><?php the_content(); ?></div>
        <?php edit_post_link('Edit page','<p>','</p>'); ?>
      </article>
    <?php endwhile; endif; ?>
  </main>
  <?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>
