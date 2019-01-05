<?php
/**
 * Plugin Name: Blue Robotics WP Tutorials
 * Plugin URI: http://bluerobotics.com
 * Description: Simple guide post generator for documentation on Blue Robotics website.
 * Author: Rustom Jehangir
 * Author URI: http://rstm.io
 * Version: 0.0.1
 *
 * Copyright: (c) 2019 Rustom Jehangir
 *
 * @author    Rustom Jehangir
 * @copyright Copyright (c) 2019, Rustom Jehangir
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 *
 */


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Hook br_guide_post() to the init action hook
add_action( 'init', 'br_guide_post' );

$guide_nav_items = array();

// The custom function to register a tutorial post type
function br_guide_post() {

  // Set the labels, this variable is used in the $args array
	$labels = array(
		'name'               => __( 'Guides' ),
		'singular_name'      => __( 'Guide' ),
		'add_new'            => __( 'Add New Guide' ),
		'add_new_item'       => __( 'Add New Guide' ),
		'edit_item'          => __( 'Edit Guide' ),
		'new_item'           => __( 'New Guide' ),
		'all_items'          => __( 'All Guides' ),
		'view_item'          => __( 'View Guide' ),
		'search_items'       => __( 'Search Guides' ),
		'featured_image'     => 'Featured Image',
		'set_featured_image' => 'Add Featured Image'
	);

  // The arguments for our post type, to be entered as parameter 2 of register_post_type()
	$args = array(
		'labels'            => $labels,
		'description'       => 'Holds our guides and guide specific data',
		'public'            => true,
		'menu_position'     => 5,
		'menu_icon'			=> 'dashicons-book-alt',
		'supports'          => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'page-attributes' ),
		'has_archive'       => true,
		'exclude_from_search' => false,
		'show_in_admin_bar' => true,
		'show_in_nav_menus' => true,
		'has_archive'       => true,
		'query_var'         => true
	);

  // Call the actual WordPress function
  // Parameter 1 is a name for the post type
  // Parameter 2 is the $args array
	register_post_type( 'learn', $args);
}

// hook into the init action and call create_guide_tag_taxonomy when it fires
add_action( 'init', 'create_guide_tag_taxonomy', 0 );
function create_guide_tag_taxonomy() {
	// Labels part for the GUI
	$labels = array(
		'name' => _x( 'Tags', 'taxonomy general name' ),
		'singular_name' => _x( 'Tag', 'taxonomy singular name' ),
		'search_items' =>  __( 'Search Guide Tags' ),
		'popular_items' => __( 'Popular Guide Tags' ),
		'all_items' => __( 'All Guide Tags' ),
		'parent_item' => null,
		'parent_item_colon' => null,
		'edit_item' => __( 'Edit Guide Tag' ), 
		'update_item' => __( 'Update Guide Tag' ),
		'add_new_item' => __( 'Add New Guide Tag' ),
		'new_item_name' => __( 'New Guide Tag Name' ),
		'separate_items_with_commas' => __( 'Separate guide tags with commas' ),
		'add_or_remove_items' => __( 'Add or remove guide tags' ),
		'choose_from_most_used' => __( 'Choose from the most used guide tags' ),
		'menu_name' => __( 'Tags' ),
	); 

	// Now register the non-hierarchical taxonomy like tag
	register_taxonomy('guide_tags','learn',array(
		'hierarchical' => false,
		'labels' => $labels,
		'show_ui' => true,
		'show_admin_column' => true,
		'update_count_callback' => '',
		'query_var' => true,
		'rewrite' => array( 'slug' => 'guide-tag' ),
	));
}

function is_learn() {
	global $post;

	/* Checks for single template by post type */
	if ( $post->post_type == 'learn' ) {
		return true;
	}
	return false;
}

/* Filter the single_template with our custom function*/
add_filter('single_template', 'guide_single_template', 99);

function guide_single_template($single) {
	global $post;

	/* Checks for single template by post type */
	if ( $post->post_type == 'learn' ) {
		if ( file_exists( plugin_dir_path( __FILE__ ) . 'templates/single-guide.php' ) ) {
			return plugin_dir_path( __FILE__ ) . 'templates/single-guide.php';
		}
	}
	return $single;
}

/* Filter the archive_template with our custom function*/
add_filter('archive_template', 'guide_archive_template', 99);

function guide_archive_template($archive) {
	global $post;

	/* Checks for single template by post type */
	if ( $post->post_type == 'learn' ) {
		if ( file_exists( plugin_dir_path( __FILE__ ) . 'templates/archive-guide.php' ) ) {
			return plugin_dir_path( __FILE__ ) . 'templates/archive-guide.php';
		}
	}
	return $archive;
}

// Register style sheet.
add_action( 'wp_enqueue_scripts', 'register_plugin_styles' );

/**
 * Register style sheet.
 */
function register_plugin_styles() {
	wp_register_style( 'bluerobotics-wp-guides', plugins_url( 'bluerobotics-wp-guides/css/style.css' ) );
	wp_enqueue_style( 'bluerobotics-wp-guides' );
}

/**
 * Add meta box on post editor.
 */
function guide_add_custom_meta_box()
{
	$screens = ['learn'];
	foreach ($screens as $screen) {
		add_meta_box(
            'guide_guide_meta_box_id',	// Unique ID
            'Guide Meta Fields',	// Box title
            'guide_meta_box_html',    	// Content callback, must be of type callable
            $screen                   	// Post type
        );
	}
}
add_action('add_meta_boxes', 'guide_add_custom_meta_box');

/**
 * Output meta box on post.
 */
function guide_meta_box_html($post)
{
	?>
	<input type="hidden" name="guide_meta_box_nonce" value="<?php echo wp_create_nonce( basename(__FILE__) ); ?>">

	<label for="guide_forum_link_field">Blue Robotics Discuss Forum URL: </label>
	<input type="text" name="guide_forum_link_field" id="guide_forum_link_field" class="regular-text" value="<?php echo get_post_meta( $post->ID, 'guide_forum_link', true ); ?>" style="width:500px" />
	<?php
}

/**
 * Save field from meta box on post.
 */
function guide_meta_box_save_fields( $post_id ) {   
	// verify nonce
	if ( !wp_verify_nonce( $_POST['guide_meta_box_nonce'], basename(__FILE__) ) ) {
		return $post_id; 
	}
	// check autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return $post_id;
	}
	// check permissions
	// if ( 'page' === $_POST['post_type'] ) {
	// 	if ( !current_user_can( 'edit_page', $post_id ) ) {
	// 		return $post_id;
	// 	} elseif ( !current_user_can( 'edit_post', $post_id ) ) {
	// 		return $post_id;
	// 	}  
	// }
	
	$old = get_post_meta( $post_id, 'guide_forum_link', true );
	$new = $_POST['guide_forum_link_field'];

	if ( $new && $new !== $old ) {
		update_post_meta( $post_id, 'guide_forum_link', $new );
	} elseif ( '' === $new && $old ) {
		delete_post_meta( $post_id, 'guide_forum_link', $old );
	}
}
add_action( 'save_post', 'guide_meta_box_save_fields' );

/**
 * Output the side bar navigation menu for the theme.
 */
function get_guide_nav() {
	global $post;
	global $guide_nav_items;

	$guide_link = get_post_meta( $post->ID, 'guide_forum_link', TRUE );

	$tags = get_the_term_list( $post->ID, 'guide_tags', '<span class="label label-primary">', '</span> <span class="label label-primary">', '</span>' );

	echo '<nav class="guidenav listnav"><ul class="list-group nav">';
	echo '<li class="list-group-item"><strong>Navigation</strong></li>';
	foreach ($guide_nav_items as $item) {
		echo '<li class="list-group-item nav-link"><a href="#'.$item["anchor"].'">'.$item["nav_title"].'</a></li>';
	}
	echo '</ul></nav>';

	echo '<nav class="listnav"><ul class="list-group nav">';
	if ( $guide_link != '' ) {
		echo '<li class="list-group-item nav-link"><a href="'.$guide_link.'"><i class="fa fa-fw fa-users" aria-hidden="true"></i> Forum</a></li>';
	}
	echo '<li class="list-group-item nav-link"><a href="javascript:window.print()"><i class="fa fa-fw fa-print" aria-hidden="true"></i> Print</a></li>';
	echo '</ul></nav>';

	if ( $tags != '' ) {
		echo '<nav class="listnav"><ul class="list-group nav">';
		echo '<li class="list-group-item"><strong>Tags</strong></li>';
		echo '<li class="list-group-item tags">';
		echo $tags;
		echo '</li>';
		echo '</ul></nav>';
	}

	echo '<nav class="listnav"><ul class="list-group nav">';
	echo '<li class="list-group-item small">Posted '.date('j M Y',strtotime($post->post_date)).'<br />Last updated on '.date('j M Y', strtotime($post->post_modified_gmt)).'</li>';
	echo '</ul></nav>';
}

/**
 * Output the side bar navigation menu for the theme.
 */
function get_guide_archive_nav() {

	//$tags = get_the_term_list( $post->ID, 'guide_tags', '<span class="label label-primary">', '</span>&nbsp;<span class="label label-primary">', '</span>' );

	$tags = get_terms( array('taxonomy' => 'guide_tags','hide_empty' => false,) );

	if ( $tags != '' ) {
		echo '<nav class="listnav"><ul class="list-group nav">';
		echo '<li class="list-group-item"><strong>Tags</strong></li>';
		echo '<li class="list-group-item tags">';
		foreach ( $tags as $tag ) {
			echo '<span class="label label-primary"><a href="'.get_term_link( $tag->slug, $tag->taxonomy ).'">'.$tag->name.'</a></span> ';
		}
		echo '</li>';
		echo '</ul></nav>';
	}
}

/**
 * Output an H1 heading with anchors and links
 */
function guide_heading_func( $atts, $content = null, $tag = '' ) {
	global $post;
	global $guide_nav_items;

	// normalize attribute keys, lowercase
	$atts = array_change_key_case((array)$atts, CASE_LOWER);

	$atts = shortcode_atts( array(
		'title' => 'Title Placeholder',
		'nav_title' => ''
	), $atts, $tag );

	$title = $atts['title'];
	$anchor = strtolower(preg_replace("/[^A-Za-z0-9 ]/", '', $atts['title']));
	$anchor = str_replace(' ', '-', $anchor);
	$nav_title = $title;
	if ( $atts['nav_title'] != '' ) {
		$nav_title = $atts['nav_title'];
	}

	$item = array("title" => $title, "anchor" => $anchor, "nav_title" => $nav_title);

	array_push($guide_nav_items, $item);

	$output = '';
	$output .= '<h1  class="anchor-heading" id="' . $anchor . '">';
	$output .= $title . ' <a href="#' . $anchor . '" class="anchor"><i class="fa fa-link" aria-hidden="true"></i>
	</a></h1>';

	return $output;
}
add_shortcode( 'guide_heading', 'guide_heading_func' );

/**
 * Output an H2 heading with anchors and links
 */
function guide_subheading_func( $atts, $content = null, $tag = '' ) {
	global $post;
	global $guide_nav_items;

	// normalize attribute keys, lowercase
	$atts = array_change_key_case((array)$atts, CASE_LOWER);

	$atts = shortcode_atts( array(
		'title' => 'Title Placeholder',
		'nav_title' => ''
	), $atts, $tag );

	$title = $atts['title'];
	$anchor = strtolower(preg_replace("/[^A-Za-z0-9 ]/", '', $atts['title']));
	$anchor = str_replace(' ', '-', $anchor);
	$nav_title = $title;
	if ( $atts['nav_title'] != '' ) {
		$nav_title = $atts['nav_title'];
	}

	$item = array("title" => $title, "anchor" => $anchor, "nav_title" => $nav_title);

	//array_push($guide_nav_items, $item);

	$output = '';
	$output .= '<h2 class="anchor-heading" id="' . $anchor . '">';
	$output .= $title . ' <a href="#' . $anchor . '" class="anchor"><i class="fa fa-link" aria-hidden="true"></i>
	</a></h2>';

	return $output;
}
add_shortcode( 'guide_subheading', 'guide_subheading_func' );

/**
 * Output a formatted image.
 */
function guide_image_func( $atts, $content = null, $tag = '' ) {
	global $post;

	// normalize attribute keys, lowercase
	$atts = array_change_key_case((array)$atts, CASE_LOWER);

	$atts = shortcode_atts( array(
		'src' => '',
		'alt' => '',
		'caption' => ''
	), $atts, $tag );

	$src = $atts['src'];
	$alt = $atts['alt'];
	$caption = $atts['caption'];

	$id = pn_get_attachment_id_from_url($src);

	if ( $id > 0 ) {
		$image_atts = wp_get_attachment_image_src( $id, 'large' );
		$src = $image_atts[0];
	}

	if ( $alt == '' ) {
		if ( $caption == '' ) {
			$alt = get_the_title($id);
		} else {
			$alt = $caption;
		}
	}

	$output = '';
	$output .= '<div class="guide-image-wrapper"><a href="'.$src.'"><img src="'.$src.'" alt="'.$alt.'" class="img-responsive img-center no-lazy-load img-guide" /></a>';
	
	if ( $caption != '' ) {
		$output .= '<p class="guide-image-caption text-center">'.$caption.'</p>';
	}
	$output .= '</div>';

	return $output;
}
add_shortcode( 'guide_image', 'guide_image_func' );

/**
 * Grab the attachment ID from the URL so we can send a smaller image size.
 */
function pn_get_attachment_id_from_url( $attachment_url = '' ) {
 
	global $wpdb;
	$attachment_id = false;
 
	// If there is no url, return.
	if ( '' == $attachment_url )
		return;
 
	// Get the upload directory paths
	$upload_dir_paths = wp_upload_dir();
 
	// Make sure the upload path base directory exists in the attachment URL, to verify that we're working with a media library image
	if ( false !== strpos( $attachment_url, $upload_dir_paths['baseurl'] ) ) {
 
		// If this is the URL of an auto-generated thumbnail, get the URL of the original image
		$attachment_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url );
 
		// Remove the upload path base directory from the attachment URL
		$attachment_url = str_replace( $upload_dir_paths['baseurl'] . '/', '', $attachment_url );
 
		// Finally, run a custom database query to get the attachment ID from the modified attachment URL
		$attachment_id = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $attachment_url ) );
 
	}
 
	return $attachment_id;
}

/**
 * Output a warning with an icon in an alert box.
 */
function guide_warning_func($atts = [], $content = null) {
	if ( !is_null($content) ) {
		$output = '';
		$output .= '<div class="alert alert-danger"><div class="row"><div class="col-sm-1"><i class="fa fa-exclamation-triangle fa-fw fa-3x text-warning"></i></div><div class="col-sm-11">';
		$output .= $content;
		$output .= '</div></div></div>';
		return $output;
	}
	return $content;
}
add_shortcode( 'guide_warning', 'guide_warning_func');

/**
 * Output a note in an icon in an alert box.
 */
function guide_note_func($atts = [], $content = null) {
	if ( !is_null($content) ) {
		$output = '';
		$output .= '<div class="alert alert-info"><div class="row"><div class="col-sm-1"><i class="fa fa-lightbulb-o fa-fw fa-3x blue"></i></div><div class="col-sm-11">';
		$output .= $content;
		$output .= '</div></div></div>';
		return $output;
	}
	return $content;
}
add_shortcode( 'guide_note', 'guide_note_func');

/**
 * Output a button with text.
 */
function guide_button_func($atts = [], $content = null) {
	// normalize attribute keys, lowercase
	$atts = array_change_key_case((array)$atts, CASE_LOWER);

	$atts = shortcode_atts( array(
		'text' => '',
		'url' => ''
	), $atts, $tag );

	$text = $atts['text'];
	$url = $atts['url'];

	if ( !is_null($text) ) {
		$output = '';
		$output .= '<p class="text-center"><a href="'.$url.'" class="btn btn-primary btn-guide" role="button">';
		$output .= $text;
		$output .= '</a></p>';
		return $output;
	}
	return $content;
}
add_shortcode( 'guide_button', 'guide_button_func');

function migration_javascript() {
	wp_enqueue_script('bluerobotics-wp-guides', plugins_url('bluerobotics-wp-guides/js/guideMigration.js'));
}
// load the scripts on only the plugin admin page 
//if (is_page('Guide Migration')) { 
    // if we are on the plugin page, enable the script
	add_action('wp_enqueue_scripts', 'migration_javascript');
//}

?>
