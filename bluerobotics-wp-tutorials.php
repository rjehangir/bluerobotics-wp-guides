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
    'name'               => __( 'Learn' ),
    'singular_name'      => __( 'Learn' ),
    'add_new'            => __( 'Add New Learn Guide' ),
    'add_new_item'       => __( 'Add New Learn Guide' ),
    'edit_item'          => __( 'Edit Learn Guide' ),
    'new_item'           => __( 'New Learn Guide' ),
    'all_items'          => __( 'All Learn Guides' ),
    'view_item'          => __( 'View Learn Guide' ),
    'search_items'       => __( 'Search Learn Guides' ),
    'featured_image'     => 'Featured Image',
    'set_featured_image' => 'Add Featured Image'
  );
 
  // The arguments for our post type, to be entered as parameter 2 of register_post_type()
  $args = array(
    'labels'            => $labels,
    'description'       => 'Holds our learn guides and guide specific data',
    'public'            => true,
    'menu_position'     => 5,
    'menu_icon'			=> 'dashicons-book-alt',
    'supports'          => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions', 'page-attributes' ),
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
add_filter('single_template', 'learn_single_template', 99);

function learn_single_template($single) {
    global $post;

    /* Checks for single template by post type */
    if ( $post->post_type == 'learn' ) {
        if ( file_exists( plugin_dir_path( __FILE__ ) . 'templates/single-learn.php' ) ) {
            return plugin_dir_path( __FILE__ ) . 'templates/single-learn.php';
        }
    }
    return $single;
}

/* Filter the archive_template with our custom function*/
add_filter('archive_template', 'learn_archive_template', 99);

function learn_archive_template($archive) {
    global $post;

    /* Checks for single template by post type */
    if ( $post->post_type == 'learn' ) {
        if ( file_exists( plugin_dir_path( __FILE__ ) . 'templates/archive-learn.php' ) ) {
            return plugin_dir_path( __FILE__ ) . 'templates/archive-learn.php';
        }
    }
    return $archive;
}

/* Filter the taxonomy_template with our custom function*/
// add_filter('taxonomy_template', 'learn_guide_tag_template', 99);

// function learn_guide_tag_template($taxonomy) {
//     if ( is_tax('guide_tag') ) {
//         if ( file_exists( plugin_dir_path( __FILE__ ) . 'templates/taxonomy-guide_tag.php' ) ) {
//         	echo "hi there";
//             return plugin_dir_path( __FILE__ ) . 'templates/taxonomy-guide_tag.php';
//         }
//     }
//     return $taxonomy;
// }

// Register style sheet.
add_action( 'wp_enqueue_scripts', 'register_plugin_styles' );

/**
 * Register style sheet.
 */
function register_plugin_styles() {
	wp_register_style( 'bluerobotics-wp-tutorials', plugins_url( 'bluerobotics-wp-tutorials/css/style.css' ) );
	wp_enqueue_style( 'bluerobotics-wp-tutorials' );
}

/**
 * Output the side bar navigation menu for the theme.
 */
function get_learn_nav() {
	global $post;
	global $guide_nav_items;

	$learn_link = get_post_meta( $post->ID, 'learn_forum_link', TRUE );

	$tags = get_the_term_list( $post->ID, 'guide_tags', '<span class="label label-primary">', '</span>&nbsp;<span class="label label-primary">', '</span>' );

	echo '<nav class="learnnav listnav"><ul class="list-group nav">';
	echo '<li class="list-group-item"><strong>Navigation</strong></li>';
	foreach ($guide_nav_items as $item) {
		echo '<li class="list-group-item nav-link"><a href="#'.$item["anchor"].'">'.$item["nav_title"].'</a></li>';
	}
	echo '</ul></nav>';

	echo '<nav class="listnav"><ul class="list-group nav">';
	if ( $learn_link != '' ) {
		echo '<li class="list-group-item nav-link"><a href="'.$learn_link.'"><i class="fa fa-fw fa-users" aria-hidden="true"></i> Forum</a></li>';
	}
	echo '<li class="list-group-item nav-link"><a href="javascript:window.print()"><i class="fa fa-fw fa-print" aria-hidden="true"></i> Print</a></li>';
	echo '</ul></nav>';

	if ( $tags != '' ) {
		echo '<nav class="listnav"><ul class="list-group nav">';
		echo '<li class="list-group-item"><strong>Tags</strong></li>';
		echo '<li class="list-group-item">';
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
function get_learn_archive_nav() {

	//$tags = get_the_term_list( $post->ID, 'guide_tags', '<span class="label label-primary">', '</span>&nbsp;<span class="label label-primary">', '</span>' );

	$tags = get_terms( array('taxonomy' => 'guide_tags','hide_empty' => false,) );

	if ( $tags != '' ) {
		echo '<nav class="listnav"><ul class="list-group nav">';
		echo '<li class="list-group-item"><strong>Tags</strong></li>';
		echo '<li class="list-group-item">';
		foreach ( $tags as $tag ) {
			echo '<span class="label label-primary"><a href="'.get_term_link( $tag->slug, $tag->taxonomy ).'">'.$tag->name.'</a></span>&nbsp;';
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
		'caption' => ''
	), $atts, $tag );

	$src = $atts['src'];
	$caption = $atts['caption'];

	$output = '';
	$output .= '<div class="learn-image-wrapper"><a href="'.$src.'"><img src="'.$src.'" class="img-responsive img-center no-lazy-load" style="max-width:90%" /></a>';
	if ( $caption != '' ) {
		$output .= '<p class="learn-image-caption text-center">'.$caption.'</p>';
	}
	$output .= '</div>';

	return $output;
}
add_shortcode( 'guide_image', 'guide_image_func' );

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
	if ( !is_null($content) ) {
		$output = '';
		$output .= '<p class="text-center"><button type="button" class="btn btn-primary">';
		$output .= $content;
		$output .= '</button></p>';
		return $output;
	}
	return $content;
}
add_shortcode( 'guide_button', 'guide_button_func');

?>
