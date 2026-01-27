<?php get_header(); ?>
<div class="content-area">
  <main class="site-main">
    <section class="error-404 not-found">
      <h1>Page not found</h1>
      <p>Sorry, the page you're looking for can't be found. Try a search or return to the <a href="<?php echo esc_url(home_url('/')); ?>">homepage</a>.</p>
      <?php get_search_form(); ?>
    </section>
  </main>
  <?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>
