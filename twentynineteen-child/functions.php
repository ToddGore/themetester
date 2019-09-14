<?php 


add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
function my_theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );

    wp_enqueue_style( 'child-style', 
        get_stylesheet_directory_uri() . '/style.css', 
        array( 'parent-style' ), // Force WordPress to load parent theme first
        wp_get_theme()->get('Version')); // Appending version number onto the query string

    wp_enqueue_script( 'child-script', get_stylesheet_directory_uri() . '/js/script.js');
}

add_action( 'after_setup_theme', 'wpse_setup_theme' );
function wpse_setup_theme() {
    add_theme_support( 'post-thumbnails' );
    add_image_size( 'large_square', 1000, 1000, true );
 }

add_filter( 'the_content', 'filter_the_content_in_the_main_loop' );
function filter_the_content_in_the_main_loop( $content ) {
    // Check if we're inside the main loop.
    if ( in_the_loop() && is_main_query() ) {
        return $content . '<p>Thanks for reading!</p>' ;
    }
    return $content;
}

// Add custom sizes to WordPress Media Library
add_filter( 'image_size_names_choose', 'child_choose_sizes' );
function child_choose_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'large_square' => __('Large Square'),
        'larger_square' => __('Larger Square'),
    ) );
}




add_image_size( 'larger_square', 1100, 1100, true );

?>