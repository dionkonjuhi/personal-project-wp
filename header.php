<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect fill='%23004BA0' width='50' height='100'/><rect fill='%23C8102E' x='50' width='50' height='100'/></svg>">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<header class="site-header">
  <div class="wrap">
    <div class="site-branding">
      <h1 class="site-title">
        <a href="<?php echo esc_url(home_url('/')); ?>">
          <span class="fcb-logo">⚽ FC BARCELONA</span>
        </a>
      </h1>
      <p class="site-description"><?php bloginfo('description'); ?></p>
    </div>
    <nav class="site-navigation">
      <?php wp_nav_menu(array(
        'theme_location' => 'primary',
        'container' => false,
        'fallback_cb' => 'ppwp_default_menu',
      )); ?>
    </nav>
  </div>
</header>
<div class="site-content wrap">
