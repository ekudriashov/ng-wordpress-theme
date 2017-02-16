<?php
/**
 * ng-WordPress functions and definitions.
 * @package ng-WordPress
 */

if ( !function_exists( 'ngwordpress_setup' ) ) :
/**
 * Sets up theme defaults.
 */
function ngwordpress_setup() {

	/*
	 * Let WordPress manage the document title.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 */
	add_theme_support( 'post-thumbnails' );
	//add_image_size( 'card-thumb', 730, 398, true );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary', 'ngwp' ),
		'footer'  => esc_html__( 'Footer', 'ngwp' )
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
}
endif;
add_action( 'after_setup_theme', 'ngwordpress_setup' );

if ( !function_exists( 'ngwordpress_register_required_plugins' ) ) :
/**
 * Register the required plugins for ng-Wordpress theme.
 */
function ngwordpress_register_required_plugins() {
	$plugins = array(
		// WP REST API v2 plugin from the WordPress Plugin Repository.
		array(
			'name'      => 'WP REST API',
			'slug'      => 'rest-api',
			'required'  => true,
		),

		// This is an example of the use of 'is_callable' functionality. A user could - for instance -
		// have WPSEO installed *or* WPSEO Premium. The slug would in that last case be different, i.e.
		// 'wordpress-seo-premium'.
		// By setting 'is_callable' to either a function from that plugin or a class method
		// `array( 'class', 'method' )` similar to how you hook in to actions and filters, TGMPA can still
		// recognize the plugin as being installed.
		// array(
		// 	'name'        => 'WordPress SEO by Yoast',
		// 	'slug'        => 'wordpress-seo',
		// 	'is_callable' => 'wpseo_init',
		// ),

	);
	/*
	 * Configuration settings.
	 */
	$config = array(
		'id'           => 'ngwp',
		'default_path' => '',
		'menu'         => 'tgmpa-install-plugins',
		'parent_slug'  => 'themes.php',
		'capability'   => 'edit_theme_options',
		'has_notices'  => true,
		'dismissable'  => true,
		'dismiss_msg'  => '',
		'is_automatic' => false,
		'message'      => '',
	);

	tgmpa( $plugins, $config );
}
endif;
add_action( 'tgmpa_register', 'ngwordpress_register_required_plugins' );

if ( !function_exists( 'ngwordpress_enqueue' ) ) :
/**
 * Enqueue ng-Wordpress theme styles and scripts.
 */
function ngwordpress_enqueue() {
	//styles
	wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/node_modules/bootstrap/dist/css/bootstrap.min.css', array(), '3.3.7', 'all' );
	wp_enqueue_style( 'animate', get_template_directory_uri() . '/node_modules/animate.css/animate.min.css', array(), '3.5.1', 'all' );
	wp_enqueue_style( 'flickity', get_template_directory_uri() . '/node_modules/flickity/dist/flickity.min.css', array(), '2.0.5', 'all' );
	wp_enqueue_style( 'font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
	wp_enqueue_style( 'ng-Wordpress', get_stylesheet_uri() );
	//scripts
	wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/node_modules/bootstrap/dist/js/bootstrap.min.js', array('jquery'), '3.3.7', true );
	wp_enqueue_script( 'modernizr-custom', get_template_directory_uri() . '/js/modernizr-custom.js', array('jquery'), '3.3.1', true );
	wp_enqueue_script( 'waypoints', get_template_directory_uri() . '/node_modules/waypoints/lib/jquery.waypoints.min.js', array('jquery'), '4.0.1', true );
	wp_enqueue_script( 'flickity', get_template_directory_uri() . '/node_modules/flickity/dist/flickity.pkgd.min.js', array('jquery'), '2.0.5', true );
	wp_enqueue_script( 'ng-Wordpress', get_template_directory_uri() . '/js/scripts.js', array('jquery'), '', true );

	// AngularJS
	wp_enqueue_script( 'angular', get_template_directory_uri() . '/node_modules/angular/angular.min.js', array(), null );
	wp_enqueue_script( 'angular-route', get_template_directory_uri() . '/node_modules/angular-route/angular-route.min.js', array(), null );
	wp_enqueue_script('angular-sanitize', get_template_directory_uri() . '/node_modules/angular-sanitize/angular-sanitize.min.js', array(), null);

	wp_enqueue_script( 'application', get_template_directory_uri() . '/assets/app.js', array('angular', 'angular-route'), null );
	wp_enqueue_script( 'application-service', get_template_directory_uri() . '/assets/scripts/services/NGWPService.js', array('application'), null );
	

	// With get_stylesheet_directory_uri()
	wp_localize_script('application', 'localized',
		array(
			'partials' => trailingslashit( get_template_directory_uri() ) . 'partials/'
		)
	);
}
endif;
add_action( 'wp_enqueue_scripts', 'ngwordpress_enqueue' );

if ( !function_exists( 'ngwordpress_disable_features' ) ) :
// DISABLE VARIOUS UNUSED WORDPRESS FEATURES
function ngwordpress_disable_features() {
	
	if (!is_admin()) {
		wp_deregister_script('wp-embed');
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
	}
	remove_action('wp_head', 'rsd_link'); // remove really simple discovery
	remove_action('wp_head', 'wp_generator'); // remove wordpress version
	remove_action('wp_head', 'feed_links', 2); // remove rss feed links *** RSS ***
	remove_action('wp_head', 'feed_links_extra', 3); // removes all rss feed links
	remove_action('wp_head', 'index_rel_link'); // removes link to index (home) page
	remove_action('wp_head', 'wlwmanifest_link'); // remove wlwmanifest.xml (windows live writer support)
	remove_action('wp_head', 'start_post_rel_link', 10, 0); // remove random post link
    remove_action('wp_head', 'parent_post_rel_link', 10, 0); // remove parent post link
    remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // remove the next and previous post links
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
    remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0 );
}
endif;
add_action( 'init', 'ngwordpress_disable_features' );


function ngwordpress_fix_excerpt($text) {
  return str_replace('[&hellip;]', '&hellip;', $text);
}
add_filter('the_excerpt', 'ngwordpress_fix_excerpt');

function ngwordpress_excerpt_length( $length ) {
	return 20;
}
add_filter( 'excerpt_length', 'ngwordpress_excerpt_length', 999 );




/**
 * Add Custom Avatar Field
 * @author Bill Erickson
 * @link http://www.billerickson.net/wordpress-custom-avatar/
 *
 * @param object $user
 */
function be_custom_avatar_field( $user ) { ?>
	<h2>User custom profile picture ;)</h2>
	 
	<table class="form-table">
	<tr>
	<th><label for="be_custom_avatar">Image URL:</label></th>
	<td>
	<input type="text" name="be_custom_avatar" id="be_custom_avatar" value="<?php echo esc_attr( get_the_author_meta( 'be_custom_avatar', $user->ID ) ); ?>" class="regular-text code" />
	<p class="description">Specify the URL to custom user avatar image. Upload avatar to 'Media', copy URL, paste here.</p>
	</td>
	</tr>
	</table>
	<?php 
}
add_action( 'show_user_profile', 'be_custom_avatar_field' );
add_action( 'edit_user_profile', 'be_custom_avatar_field' );

/**
 * Save Custom Avatar Field
 * @author Bill Erickson
 * @link http://www.billerickson.net/wordpress-custom-avatar/
 *
 * @param int $user_id
 */
function be_save_custom_avatar_field( $user_id ) {
	if ( !current_user_can( 'edit_user', $user_id ) ) { return false; }
		update_usermeta( $user_id, 'be_custom_avatar', $_POST['be_custom_avatar'] );
}
add_action( 'personal_options_update', 'be_save_custom_avatar_field' );
add_action( 'edit_user_profile_update', 'be_save_custom_avatar_field' );

/**
 * Use Custom Avatar if Provided
 * @author Bill Erickson
 * @link http://www.billerickson.net/wordpress-custom-avatar/
 *
 */
function be_gravatar_filter($avatar, $id_or_email, $size, $default, $alt) {
	
	// If provided an email and it doesn't exist as WP user, return avatar since there can't be a custom avatar
	$email = is_object( $id_or_email ) ? $id_or_email->comment_author_email : $id_or_email;
	if( is_email( $email ) && ! email_exists( $email ) )
		return $avatar;
	
	$custom_avatar = get_the_author_meta('be_custom_avatar');
	if ($custom_avatar) 
		$return = '<img src="'.$custom_avatar.'" width="'.$size.'" height="'.$size.'" alt="'.$alt.'" class="media-object img-circle"/>';
	elseif ($avatar) 
		$return = $avatar;
	else 
		$return = '<img src="'.$default.'" width="'.$size.'" height="'.$size.'" alt="'.$alt.'" />';

	return $return;
}
add_filter('get_avatar', 'be_gravatar_filter', 10, 5);







/**
 * Include the WP REST API modifications file for ngWordpress theme.
 */
require_once get_template_directory() . '/includes/rest-response.php';
/**
 * Include the TGM_Plugin_Activation class.
 */
require_once get_template_directory() . '/includes/class-tgm-plugin-activation.php';