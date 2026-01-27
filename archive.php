<?php get_header(); ?>
<div class="content-area">
  <main class="site-main">
    <header class="archive-header">
      <h1 class="archive-title"><?php the_archive_title(); ?></h1>
      <div class="archive-description"><?php the_archive_description(); ?></div>
    </header>
    <?php if(have_posts()): while(have_posts()): the_post(); get_template_part('template-parts/content'); endwhile; the_posts_pagination(); else: get_template_part('template-parts/content','none'); endif; ?>
  </main>
  <?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>
