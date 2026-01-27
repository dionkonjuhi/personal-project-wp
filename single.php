<?php get_header(); ?>
<div class="content-area">
  <main class="site-main">
    <?php if(have_posts()): while(have_posts()): the_post(); ?>
      <article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
        <?php the_title('<h1>','</h1>'); ?>
        <div class="entry-meta">Posted on <?php the_time('F j, Y'); ?> by <?php the_author_posts_link(); ?></div>
        <div class="entry-content"><?php the_content(); ?></div>
        <div class="entry-tags"><?php the_tags('<strong>Tags:</strong> ',' , ',''); ?></div>
        <?php edit_post_link('Edit this','<p>','</p>'); ?>
      </article>
      <?php comments_template(); ?>
    <?php endwhile; endif; ?>
  </main>
  <?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>
