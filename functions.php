<?
require_once(__DIR__ . '/vendor/autoload.php');

function afterSetupTheme() {
	remove_action('wp_head', 'wp_generator');
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' ); 
}
add_action( 'after_setup_theme', 'afterSetupTheme' );


function loadScriptsAndStyles() {
	// wp_enqueue_style( 'tailwind', 'https://unpkg.com/tailwindcss@1.9.6/dist/tailwind.min.css' ); 
	wp_enqueue_style( 'style', get_stylesheet_uri(), '', '1.0' );
	wp_enqueue_style( 'roboto', 'https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap' );
	wp_dequeue_style( 'wp-block-library' );
	wp_deregister_script( 'wp-embed' );
}
add_action( 'wp_enqueue_scripts', 'loadScriptsAndStyles' );


function registerMenus() {
	register_nav_menus(
		array('aside' => 'Side')
	);
}
add_action( 'init', 'registerMenus' );


function registerPostTypes() {
	$args = array(
		'label'               => 'Book',
		'supports'            => array('title', 'thumbnail'),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'can_export'          => false,
		'has_archive'         => false,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
		'menu_icon' 		  => 'dashicons-products',
	);
		
	register_post_type( 'book', $args );
	
}
add_action( 'init', 'registerPostTypes' );

function postsOrderby($orderby, $wpquery) {
	if( 'lastMetaValueWord' === $wpquery->get( 'orderby' ) ) {
		global $wpdb;
		$order = $wpquery->get('order');
		$orderby = " SUBSTRING_INDEX( {$wpdb->postmeta}.meta_value, ' ', -1 ) " . $order;
	}
	return $orderby;
}
add_filter('posts_orderby', 'postsOrderby', 10, 2);


$timber = new \Timber\Timber();
function addToContext( $context ) {
	$context['menu'] = new \Timber\Menu('aside');
	return $context;
}
add_filter('timber/context', 'addToContext');

?>