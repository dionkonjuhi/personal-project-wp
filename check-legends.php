<?php
/**
 * Check and Create Legends
 * Access this file at /wp-content/themes/personal-project-wp/check-legends.php
 * 
 * This is a temporary debug file to verify legend creation
 */

// First, let's check if we're in WordPress context by looking for wp-load
$wp_load_found = false;
$current_dir = __DIR__;

while(!$wp_load_found && $current_dir !== dirname($current_dir)) {
    if(file_exists($current_dir . '/wp-load.php')) {
        require_once($current_dir . '/wp-load.php');
        $wp_load_found = true;
        break;
    }
    $current_dir = dirname($current_dir);
}

if(!$wp_load_found) {
    die('WordPress not found. Please access this through your WordPress installation.');
}

// Now we have WordPress loaded
echo '<h1>Barcelona FC Legends Check</h1>';
echo '<hr>';

// Check if legend post type exists
$post_types = get_post_types(['_builtin' => false]);
echo '<h2>Custom Post Types:</h2>';
echo '<pre>';
print_r($post_types);
echo '</pre>';

// Check for existing legends
$legends = get_posts([
    'post_type' => 'legend',
    'posts_per_page' => -1,
    'post_status' => 'publish'
]);

echo '<h2>Existing Legends: ' . count($legends) . '</h2>';
if(!empty($legends)) {
    echo '<ul>';
    foreach($legends as $legend) {
        echo '<li>' . esc_html($legend->post_title) . ' (ID: ' . $legend->ID . ')</li>';
    }
    echo '</ul>';
} else {
    echo '<p style="color: red;">No legends found! Creating them now...</p>';
    ppwp_add_legends();
    echo '<p style="color: green;">✅ Legends created!</p>';
}

// Check the legend archive URL
$legend_post_type = get_post_type_object('legend');
echo '<h2>Legend Archive Settings:</h2>';
echo '<pre>';
echo 'has_archive: ' . ($legend_post_type->has_archive ? 'YES' : 'NO') . "\n";
echo 'rewrite: '; print_r($legend_post_type->rewrite); echo "\n";
echo 'Archive URL: ' . get_post_type_archive_link('legend');
echo '</pre>';

// Check rewrite rules
echo '<h2>Rewrite Rules:</h2>';
global $wp_rewrite;
$rules = $wp_rewrite->rules;
$legend_rules = array_filter($rules, function($key) {
    return strpos($key, 'legend') !== false;
}, ARRAY_FILTER_USE_KEY);

if(!empty($legend_rules)) {
    echo '<pre>';
    print_r($legend_rules);
    echo '</pre>';
} else {
    echo '<p style="color: orange;">No legend rewrite rules found. Flushing rewrite rules...</p>';
    flush_rewrite_rules(false);
    echo '<p style="color: green;">✅ Rewrite rules flushed!</p>';
}

echo '<hr>';
echo '<p><a href="' . get_post_type_archive_link('legend') . '">View Legends Archive →</a></p>';
?>