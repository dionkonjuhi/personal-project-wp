<aside class="sidebar" role="complementary">
  <?php if(is_active_sidebar('sidebar-1')): dynamic_sidebar('sidebar-1'); else: ?>
    <section class="widget">
      <h3 class="widget-title">Search</h3>
      <?php get_search_form(); ?>
    </section>
    <section class="widget">
      <h3 class="widget-title">Recent Posts</h3>
      <ul><?php foreach(wp_get_recent_posts(array('numberposts'=>5,'post_status'=>'publish')) as $post){ echo '<li><a href="'.get_permalink($post['ID']).'">'.esc_html($post['post_title']).'</a></li>'; } ?></ul>
    </section>
  <?php endif; ?>
</aside>