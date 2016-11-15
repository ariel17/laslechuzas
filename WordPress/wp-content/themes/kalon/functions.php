<?php
/**
 * Kalon functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Kalon
 */

if ( ! function_exists( 'kalon_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function kalon_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Kalon, use a find and replace
	 * to change 'kalon' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'kalon', get_template_directory() . '/languages' );

    // Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary', 'kalon' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See https://developer.wordpress.org/themes/functionality/post-formats/
	 */
		add_theme_support( 'post-formats', array(
		'aside',
		'link',
        'image',
		'quote',
        'video'
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'kalon_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

    //Custom Image Sizes
    add_image_size( 'kalon-recent-post', 67, 67, true);
    add_image_size( 'kalon-slider', 1400, 507, true);
    add_image_size( 'kalon-without-sidebar', 1170, 460, true);
    add_image_size( 'kalon-with-sidebar', 848, 461, true);
    
    /* Custom Logo */
    add_theme_support( 'custom-logo', array(    	
    	'header-text' => array( 'site-title', 'site-description' ),
    ) );
}
endif;
add_action( 'after_setup_theme', 'kalon_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function kalon_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'kalon_content_width', 848 );
}
add_action( 'after_setup_theme', 'kalon_content_width', 0 );

/**
* Adjust content_width value according to template.
*
* @return void
*/
function kalon_template_redirect_content_width() {

	// Full Width in the absence of sidebar.
	if( is_page() ){
	   $sidebar_layout = kalon_sidebar_layout();
       if( ( $sidebar_layout == 'no-sidebar' ) || ! ( is_active_sidebar( 'right-sidebar' ) ) ) $GLOBALS['content_width'] = 1170;
        
	}elseif ( ! ( is_active_sidebar( 'right-sidebar' ) ) ) {
		$GLOBALS['content_width'] = 1170;
	}

}
add_action( 'template_redirect', 'kalon_template_redirect_content_width' );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function kalon_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Right Sidebar', 'kalon' ),
		'id'            => 'right-sidebar',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );

	    register_sidebar( array(
		'name'          => esc_html__( 'Footer One', 'kalon' ),
		'id'            => 'footer-one',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
    
    register_sidebar( array(
		'name'          => esc_html__( 'Footer Two', 'kalon' ),
		'id'            => 'footer-two',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
    
    register_sidebar( array(
		'name'          => esc_html__( 'Footer Three', 'kalon' ),
		'id'            => 'footer-three',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'kalon_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function kalon_scripts() {

	$query_args = array(
		'family' => 'Montserrat:400,700|Lato:400,700|Dancing Script:400,700',
		);
    wp_enqueue_style( 'kalon-font-awesome', get_template_directory_uri(). '/css/font-awesome.css' );
    wp_enqueue_style( 'kalon-flexslider-style', get_template_directory_uri(). '/css/flexslider.css' );
    wp_enqueue_style( 'kalon-meanmenu-style', get_template_directory_uri(). '/css/meanmenu.css' );
    wp_enqueue_style( 'kalon-google-fonts', add_query_arg( $query_args, "//fonts.googleapis.com/css" ) );
	wp_enqueue_style( 'kalon-style', get_stylesheet_uri() );
    
    wp_enqueue_script( 'kalon-flexslider', get_template_directory_uri() . '/js/jquery.flexslider.js', array('jquery'), '2.6.0', true );
    wp_enqueue_script( 'kalon-meanmenu', get_template_directory_uri() . '/js/jquery.meanmenu.js', array('jquery'), '2.0.8', true );
    wp_register_script( 'kalon-custom', get_template_directory_uri() . '/js/custom.js', array('jquery'), '20151228', true );
   
    $kalon_slider_auto = get_theme_mod( 'kalon_slider_auto', '1' );
    $kalon_slider_loop = get_theme_mod( 'kalon_slider_loop', '1' );
    $kalon_slider_control = get_theme_mod( 'kalon_slider_control', '1' );
    $kalon_slider_animation = get_theme_mod( 'kalon_slider_animation', 'slide' );
    $kalon_slider_speed = get_theme_mod( 'kalon_slider_speed', '7000' );
    $kalon_animation_speed = get_theme_mod( 'kalon_animation_speed', '600' );
    
    $kalon_array = array(
        'auto'      => esc_attr( $kalon_slider_auto ),
        'loop'      => esc_attr( $kalon_slider_loop ),
        'control'   => esc_attr( $kalon_slider_control ),
        'animation' => esc_attr( $kalon_slider_animation ),
        'speed'     => absint( $kalon_slider_speed ),
        'a_speed'   => absint( $kalon_animation_speed )
    );
    
    wp_localize_script( 'kalon-custom', 'kalon_data', $kalon_array );
    wp_enqueue_script( 'kalon-custom' );
	

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'kalon_scripts' );

if ( is_admin() ) : // Load only if we are viewing an admin page
function kalon_admin_scripts() {
	wp_enqueue_style( 'kalon-admin-style',get_template_directory_uri().'/inc/css/admin.css', '1.0', 'screen' );
    
    wp_enqueue_script( 'kalon-admin-js', get_template_directory_uri().'/inc/js/admin.js', array( 'jquery' ), '', true );
    wp_localize_script( 'kalon-admin-js', 'kalon_data', array( 'promo_url' => get_template_directory_uri() . '/images/upgrade.png' ) );
	
}
add_action( 'admin_enqueue_scripts', 'kalon_admin_scripts' );
endif;

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Post Meta Box
 */
require get_template_directory() . '/inc/metabox.php';

/**
 * Widget Recent Post
 */
require get_template_directory() . '/inc/widget-recent-post.php';

/**
 * Widget Popular Post
 */
require get_template_directory() . '/inc/widget-popular-post.php';

/**
 * Widget Featured Post
 */
require get_template_directory() . '/inc/widget-featured-post.php';

/**
 * Widget Social Links
 */
require get_template_directory() . '/inc/widget-social-links.php';