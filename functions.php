<?php

/**
 * Define enviroment
 */
const ENV = 'DEVELOPMENT'; // PRODUCTION

/**
 * Parent theme style
 */
function custom_parent_theme_style() {
    wp_enqueue_style( 
        'theme-parent-style', 
        get_template_directory_uri().'/style.css' 
    );
}
add_action( 'wp_enqueue_scripts', 'custom_parent_theme_style' );

/**
 * Dequeue parent theme priority-menu scripts
 */
function custom_parent_theme_priority_menu_script() {
    wp_dequeue_script('twentynineteen-priority-menu');
}
// add_action('wp_print_scripts', 'custom_parent_theme_priority_menu_script');

/**
 * Theme style
 */
function custom_theme_style() {
    wp_enqueue_style( 
        'theme-style', 
        get_stylesheet_directory_uri().'/css/main.css', 
        false, 
        ENV == 'PRODUCTION' ? '1.0' : theme_get_timestamp()
    );
}
add_action( 'wp_enqueue_scripts', 'custom_theme_style' );

/**
 * Theme fonts
 */
function custom_theme_fonts() {
    wp_enqueue_style( 
        'theme-fonts', 
        'https://fonts.googleapis.com/css?family=Raleway:400,500,700', 
        false,
        ENV == 'PRODUCTION' ? '1.0' : theme_get_timestamp()
    );
    wp_enqueue_style( 
        'theme-fontawesome', 
        'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', 
        false,
        ENV == 'PRODUCTION' ? '1.0' : theme_get_timestamp()
    );
}
add_action( 'wp_enqueue_scripts', 'custom_theme_fonts' );

/**
 * Gutenberg editor style
 */
function custom_gutenberg_editor_styles() {
    wp_enqueue_style( 
        'custom-block-editor-styles', 
        get_theme_file_uri( '/style-editor.css' ), 
        false, 
        '1.0', 
        'all' 
    );
}
add_action( 'enqueue_block_editor_assets', 'custom_gutenberg_editor_styles' );

/**
 * WP SCSS
 * https://github.com/ConnectThink/WP-SCSS
 * 
 * IMPORTANT: Set WP_SCSS_ALWAYS_RECOMPILE to false on production
 */
define('WP_SCSS_ALWAYS_RECOMPILE', ENV == 'DEVELOPMENT' ? true : false );
if (isset($_GET['compile'])) {
    add_filter('wp_scss_needs_compiling', function () {
        return true;
    });
}

/**
 * Make sure Ultimate Blocks CSS file is enqueued.
 * 
 * The file fails to load when there are 'resolve issues' 
 * notices in the editor
 */
function custom_enqueue_ultimate_blocks_css(){
    if ( !is_singular() or !has_blocks() ) {
        return;
    }
    if ( wp_script_is('ultimate_blocks-cgb-style-css') ) {
        return;
    }
    if ( strpos( get_post()->post_content, 'wp:ub/' ) === false) {
        return;
    }
    wp_enqueue_style(
        'ultimate_blocks-cgb-style-css',
        plugins_url( 'ultimate-blocks/dist/blocks.style.build.css' )
    );
}
add_action('wp_enqueue_scripts', 'custom_enqueue_ultimate_blocks_css', 100);

// ========== Helper functions ==========

/**
 * Get current timestamp
 */
function theme_get_timestamp() {
    $date = new DateTime();
    return $date->getTimestamp();
}