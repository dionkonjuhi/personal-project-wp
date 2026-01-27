<?php get_header(); ?>
<div class="content-area">
  <main class="site-main">
    <?php if(have_posts()):
      while(have_posts()): the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>
          <?php if(has_post_thumbnail()) the_post_thumbnail('large'); ?>
          <?php the_title('<h2 class="entry-title">','</h2>'); ?>
          <div class="entry-meta">Posted on <?php the_date(); ?> by <?php the_author_posts_link(); ?></div>
          <div class="entry-content"><?php the_excerpt(); ?></div>
          <div class="entry-tags"><?php the_tags('<strong>Tags:</strong> ',' , ',''); ?></div>
          <?php edit_post_link('Edit this','<p>','</p>'); ?>
        </article>
      <?php endwhile;
      // Pagination
      the_posts_pagination(array('mid_size'=>2,'prev_text'=>'&larr; Prev','next_text'=>'Next &rarr;'));
    else:
      get_template_part('template-parts/content','none');
    endif; ?>

    <h3>Sample Projects (WP_Query)</h3>
    <?php if(function_exists('ppwp_sample_query')) ppwp_sample_query(); ?>

  </main>
  <?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>
