<?php
/**
 * @author Andrew Killen
 * @version 1.0.0
 * @since 1.0.0
 */

// stop now if not WP
if(!defined('ABSPATH')){
    die();
}

/**
 * Best to define this constant in the wp-config.php if you want to 
 * have things in dev mode.
 * 
 * Dev mode means that things like non-minified versions of CSS and JS will be loaded.
 */
if(!defined('MONEYSPINNER_ENVIRONMENT')){
    define('MONEYSPINNER_ENVIRONMENT', 'prod');
}

/**
 * Autoloader for entire theme using 
 * @uses MoneySpinner as prefix
 */
require dirname(__FILE__) . "/vendor/autoload.php";

MoneySpinner\Theme\Setup::init();

MoneySpinner\Theme\ScriptsAndStyles::init();


add_action('widgets_init', 'register_widgets_area', 9);

function register_widgets_area(){
    $areas = [
        [
            'name' => 'Homepage : Above Content',
            'description' => 'On the homepage above the main content grid',
            'id' => 'homepage-above',
            'tag' => 'div',
            'class' => '',
        ],
        [
            'name' => 'Homepage : below Content',
            'description' => 'On the homepage below the main content grid and loadmore',
            'id' => 'homepage-below',
            'tag' => 'div',
            'class' => '',
        ],
        [
            'name' => 'Post : Above Content',
            'description' => 'On posts page between the heading and the article body',
            'id' => 'post-above',
            'tag' => 'aside',
            'class' => '',
        ],
        [
            'name' => 'Post : below Content',
            'description' => 'On posts page between the article body and the tag and comments',
            'id' => 'post-below',
            'tag' => 'aside',
            'class' => '',
        ],
        [
            'name' => 'Homepage : Second item',
            'description' => 'If there is a widget in here it will show on the homepage as the 2nd item.  This only supports 1 widget',
            'id' => 'homepage-second',
            'tag' => 'article',
            'class' => 'post-excerpt format-status type-cta ',
        ]
    ];

     // Check if SRHR plugin is actvted, then add the survey widget area
    // if ( is_plugin_active( 'rnw-cv-srhr-microsite/rnw-cv-srhr-microsite.php' ) ) {
    //     $areas[] = [
    //         'name' => 'SRHR Homepage : Second item',
    //         'description' => 'If there is a widget in here it will show on the homepage as the 2nd item.  This only supports 1 widget',
    //         'id' => 'srhr-second',
    //         'tag' => 'article',
    //         'class' => 'post-excerpt format-status type-cta ',
    //     ];
    // }

    foreach ( $areas as $widgetinfo){
        register_sidebar( [
            'name' => $widgetinfo['name'],
            'id' => $widgetinfo['id'],
            'description' => $widgetinfo['description'],
            'before_widget' => '<'.$widgetinfo['tag'].' id="%1$s" class="widget '.$widgetinfo['class'].' widget-area-'.$widgetinfo['id'].' %2$s">',
            'after_widget'  => '</'.$widgetinfo['tag'].'>',
            'before_title'  => '<span class="widget-title">',
            'after_title'   => '</span>',
         ] );
    }
}

add_action('widgets_init', 'register_widgets', 10);

function register_widgets(){
    // if(!current_theme_supports('widgets-block-editor')){
        register_widget( 'MoneySpinner\Widgets\Example' );
    // }
    
}