<?php
/**
 * wp_bootstrap functions and definitions
 *
 * @package wp_bootstrap
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}

if ( ! function_exists( 'wp_bootstrap_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function wp_bootstrap_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on wp_bootstrap, use a find and replace
	 * to change 'wp_bootstrap' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'wp_bootstrap', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	//add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'wp_bootstrap' ),
	) );

	// Enable support for Post Formats.
	add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );

	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'wp_bootstrap_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	// Enable support for HTML5 markup.
	add_theme_support( 'html5', array( 'comment-list', 'search-form', 'comment-form', ) );
}
endif; // wp_bootstrap_setup
add_action( 'after_setup_theme', 'wp_bootstrap_setup' );

/**
 * Register widgetized area and update sidebar with default widgets.
 */
function wp_bootstrap_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'wp_bootstrap' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'wp_bootstrap_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function wp_bootstrap_scripts() {
	wp_enqueue_style( 'wp_bootstrap-style', get_stylesheet_uri() );
	wp_enqueue_style( 'bootstrap-style', get_template_directory_uri() . '/css/bootstrap.min.css' );
	wp_enqueue_style( 'wp_bootstrap-style-design', get_template_directory_uri() . '/css/wp_bootstrap.css' );

	wp_enqueue_script("jquery");
	wp_enqueue_script( 'bootstrap-js', get_template_directory_uri() . '/js/bootstrap.min.js', array(), '123', true );
	wp_enqueue_script( 'wp_bootstrap-script', get_template_directory_uri() . '/js/wp_bootstrap.js', array(), '20123206', true );

	wp_enqueue_script( 'wp_bootstrap-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'wp_bootstrap_scripts' );


/**
 * Customizing comment form to support Bootstrap 3
 */
function wp_bootstrap_comment_form_fields($fields) {
	$commenter = wp_get_current_commenter();
	$req      = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );
	$html5    = 'html5' === current_theme_supports( 'html5', 'comment-form' ) ? 'html5' : 'xhtml';
	$fields   =  array(
		'author' => '<div class="comment-form-author form-group">' . '<label for="author">' . __( 'Name', 'wp_bootstrap' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
		            '<input id="author" class="form-control" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></div>',
		'email'  => '<div class="comment-form-email form-group"><label for="email">' . __( 'Email', 'wp_bootstrap' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
		            '<input id="email" class="form-control" name="email" ' . ( $html5 ? 'type="email"' : 'type="text"' ) . ' value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></div>',
		'url'    => '<div class="comment-form-url form-group"><label for="url">' . __( 'Website', 'wp_bootstrap' ) . '</label> ' .
		            '<input id="url" class="form-control" name="url" ' . ( $html5 ? 'type="url"' : 'type="text"' ) . ' value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></div>',
	);
	return $fields;
}
add_filter( 'comment_form_default_fields','wp_bootstrap_comment_form_fields' );

/**
 * Customizing comment form textarea to support Bootstrap 3
 */
function wp_bootstrap_comment_form_field_comment($field) {
	$field = '<div class="comment-form-comment form-group"><label for="comment">' . _x( 'Comment', 'noun', 'wp_bootstrap' ) . '</label> <textarea id="comment" class="form-control" name="comment" cols="45" rows="8" aria-required="true"></textarea></div>';
	return $field;
}
add_filter( 'comment_form_field_comment','wp_bootstrap_comment_form_field_comment' );

/**
 * Implement the Custom Header feature.
 */
//require get_template_directory() . '/inc/custom-header.php';

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

// Register Custom Navigation Walker
require_once('inc/wp_bootstrap_navwalker.php');