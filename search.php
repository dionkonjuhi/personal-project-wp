<?php get_header(); ?>
<div class="content-area">
  <main class="site-main">
    <header class="search-header">
      <h1>Search results for: <?php echo get_search_query(); ?></h1>
    </header>
    <?php if(have_posts()): while(have_posts()): the_post(); get_template_part('template-parts/content'); endwhile; the_posts_pagination(); else: ?>
      <p>No results found. Try another search.</p>
      <?php get_search_form(); endif; ?>
  </main>
  <?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>
