<?php
/**
 * Underscores.me functions and definitions
 *
 * @package Underscores.me
 * @since Underscores.me 1.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since Underscores.me 1.0
 */
if ( ! isset( $content_width ) )
	$content_width = 640; /* pixels */

if ( ! function_exists( 'underscoresme_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * @since Underscores.me 1.0
 */
function underscoresme_setup() {

	/**
	 * Custom template tags for this theme.
	 */
	require( get_template_directory() . '/inc/template-tags.php' );

	/**
	 * Underscores.me generator pseudo-plugin
	 */
	require( get_template_directory() . '/plugins/underscoresme-generator/underscoresme-generator.php' );

	// Remove me later:
	//if ( ! is_admin() && ! isset( $_REQUEST['underscoresme_generate'] ) )
	//	do_action( 'underscoresme_print_form' );

	/**
	 * Custom functions that act independently of the theme templates
	 */
	//require( get_template_directory() . '/inc/tweaks.php' );

	/**
	 * Custom Theme Options
	 */
	//require( get_template_directory() . '/inc/theme-options/theme-options.php' );

	/**
	 * WordPress.com-specific functions and definitions
	 */
	//require( get_template_directory() . '/inc/wpcom.php' );

	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on Underscores.me, use a find and replace
	 * to change 'underscoresme' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'underscoresme', get_template_directory() . '/languages' );

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	//add_theme_support( 'automatic-feed-links' );

	/**
	 * Enable support for Post Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	/*register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'underscoresme' ),
	) );*/

	/**
	 * Add support for the Aside Post Formats
	 */
	//add_theme_support( 'post-formats', array( 'aside', ) );
}
endif; // underscoresme_setup
add_action( 'after_setup_theme', 'underscoresme_setup' );

/**
 * Register widgetized area and update sidebar with default widgets
 *
 * @since Underscores.me 1.0
 */
function underscoresme_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Sidebar', 'underscoresme' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );
}
//add_action( 'widgets_init', 'underscoresme_widgets_init' );

/**
 * Enqueue scripts and styles
 */
function underscoresme_scripts() {
	global $post;

	wp_enqueue_style( 'style', add_query_arg( 'v', 20140811, get_stylesheet_uri() ) );
	wp_enqueue_script( 'underscores-scripts', get_template_directory_uri() . '/js/underscores-scripts.js', array( 'jquery' ), '20120813' );
	
	/*wp_enqueue_script( 'small-menu', get_template_directory_uri() . '/js/small-menu.js', array( 'jquery' ), '20120206', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image( $post->ID ) ) {
		wp_enqueue_script( 'keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
	}*/
}
add_action( 'wp_enqueue_scripts', 'underscoresme_scripts' );

/**
 * Implement the Custom Header feature
 */
//require( get_template_directory() . '/inc/custom-header.php' );

/**
 * Returns an array of contributors from Github.
 */
function underscoresme_get_contributors() {

	$transient_key = 'underscoresme_contributors';
	$contributors = get_transient( $transient_key );
	if ( false !== $contributors )
		return $contributors;

	$response = wp_remote_get( 'https://api.github.com/repos/Automattic/_s/contributors?per_page=100' );
	if ( is_wp_error( $response ) )
		return array();

	$contributors = json_decode( wp_remote_retrieve_body( $response ) );
	if ( ! is_array( $contributors ) )
		return array();

	set_transient( $transient_key, $contributors, HOUR_IN_SECONDS );

	return (array) $contributors;
}



add_action( 'wp_enqueue_scripts', function (): void {
	// Enqueue the CSS file
	$css_files = glob( get_template_directory() . '/dist/css/styles.*.css' );
	if ( ! empty( $css_files ) ) {
		wp_enqueue_style( 'your-theme-name-style', get_template_directory_uri() . '/dist/css/' . basename( $css_files[0] ), array(), '1.0.0' );
	}

	// Enqueue the main JavaScript file
	$main_js_files = glob( get_template_directory() . '/dist/js/main.*.js' );
	if ( ! empty( $main_js_files ) ) {
		wp_enqueue_script( 'your-theme-name-main-script', get_template_directory_uri() . '/dist/js/' . basename( $main_js_files[0] ), array( 'jquery' ), '1.0.0', true ); 
		wp_localize_script( 'your-theme-name-main-script', 'myAjax', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ) 
		)); 
	}

	// Enqueue the styles JavaScript file (if needed)
	$styles_js_files = glob( get_template_directory() . '/dist/js/styles.*.js' );
	if ( ! empty( $styles_js_files ) ) {
		wp_enqueue_script( 'your-theme-name-styles-script', get_template_directory_uri() . '/dist/js/' . basename( $styles_js_files[0] ), array( 'jquery' ), '1.0.0', true ); 
	}
	// Localize your script 
	wp_localize_script( 'your-theme-name-main-script', 'myAjax', array(
	  'ajaxurl' => admin_url( 'admin-ajax.php' ) 
	));
} );

add_action( 'wp_ajax_my_ajax_action', 'my_ajax_action_callback' );
add_action( 'wp_ajax_nopriv_my_ajax_action', 'my_ajax_action_callback' ); 

function my_ajax_action_callback(): void {
  $data = $_POST['data']; // Get data from the request

  // Send the JSON response
  wp_send_json_success( array( 'message' => 'Success!' ) ); 
  wp_die(); // Always end AJAX with wp_die()
}