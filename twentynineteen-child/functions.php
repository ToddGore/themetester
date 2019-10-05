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
// after_setup_theme, Hook
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


/*
Register Tutorial custom post type
*/
function register_tutorials_post_type() {
    $labels = array(
        'name' => 'Tutorials',
        'singular_name' => 'Tutorial',
        'add_new' => 'Add Item',
        'all_items' => 'All Items',
        'add_new_item' => 'Add Item',
        'edit_item' => 'Edit Item',
        'new_item' => 'New Item',
        'view_item' => 'View Item',
        'not_found' => 'No Items Found',
        'parent_item_colon' => 'Parent Item'
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        // https://wordpress.stackexchange.com/questions/156978/custom-post-type-single-page-returns-404-error
        'menu_position' => 20,
        'has_archive' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'hierarchical' => true,
        'support' => array(
            'title',
            'editor',
            'excerpt',
            'thumbnail',
            'revisions',
        )

    );
    register_post_type( 'Tutorials', $args );
}
add_action( 'init', 'register_tutorials_post_type' );


/*
Plugin Name: Register Difficulty Taxonomy
*/
function register_difficulty_taxonomy() {
    $args = array( 
        'hierarchical' => true,
        'label' => 'Difficulty',
    );
    register_taxonomy( 'difficulty', 'tutorials', $args );
}
add_action( 'init', 'register_difficulty_taxonomy' );


/*
Plugin Name: Register difficulty Taxonomy Terms
*/
function register_difficulty_terms( ) {
	wp_insert_term( 'Beginner', 'difficulty', $args = array(
		'description' => 'Beginner Difficulty'
	) );
	
	wp_insert_term( 'Intermediate', 'difficulty', $args = array(
		'description' => 'Medium Difficulty'
    ) );
    
    wp_insert_term( 'Advanced', 'difficulty', $args = array(
		'description' => 'High Difficulty'
	) );
}
add_action( 'init', 'register_difficulty_terms' );


/*
Plugin Name: Register topics Taxonomy
*/
function register_topics_taxonomy() {
    $args = array( 
        'hierarchical' => false,
        'label' => 'Topics',
    );
    register_taxonomy( 'topics', 'tutorials', $args );
}
add_action( 'init', 'register_topics_taxonomy' );

/*
Plugin Name: Register topics Taxonomy Terms
*/
function register_topics_terms( ) {
	wp_insert_term( 'JavaScript', 'topics', $args = array(
		'description' => 'JavaScript Language'
	) );
	
	wp_insert_term( 'PHP', 'topics', $args = array(
		'description' => 'PHP Language'
    ) );
    
    wp_insert_term( 'Workflows', 'topics', $args = array(
		'description' => 'Workflows'
	) );
}
add_action( 'init', 'register_topics_terms' );

// Custom field
function weather_custom_field( $content ) {
	$fave_weather = get_post_meta( get_the_ID(), 'weather_field', true );
	
	if( empty( $fave_weather ) ) {
		return $content;
	}

	$fave_weather_string = '<em>The current weather is: ' . $fave_weather . '</em><hr>';
	return $fave_weather_string . $content;
}
add_filter( 'the_content', 'weather_custom_field' );

// TO DO
// Custom post type "Tutorial"
//   has: archive, 

//  Custom post template
// use template hierarchy, template parts
//   no: author, publication date
//   show: difficulty, List of Topics

// Custom taxonomy
// Difficulty: Beginner, Intermediate, Advanced
// Topics: Like tags, JavaScript, PHP, Workflows

// Custom properties
// Minutes: "6 minutes," "20 minutes," "40 minutes,"

// Archive page should only show
//   Title, Difficulty, Topics, Minutes



// Custom sidebar
function theme_slug_widgets_init() {
	$args = array(
	    'name'          => 'Widgetized Sidebar',
	    'id'            => "widgetized-sidebar",
	    'description'   => 'Our Widgetized Sidebar',
	    'class'         => '',
	    'before_widget' => '<li id="%1$s" class="widget %2$s">',
	    'after_widget'  => "</li>\n",
	    'before_title'  => '<h2 class="widgettitle">',
	    'after_title'   => "</h2>",
	);
	register_sidebar( $args );
}
add_action( 'widgets_init', 'theme_slug_widgets_init' );







?>