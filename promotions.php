<?php
/**
 * Plugin Name: Promotions
 * Plugin URI: http://pixelcurb.com
 * Description: This plugin allows you to add featured promotions for on-sale products.
 * Author: Darren King
 * Author URI: http://pixelcurb.com
 * License: GPLv2
 */

//Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once ( plugin_dir_path(__FILE__) . 'taxonomy-specials-type.php' );

//* Create Specials custom post type
add_action( 'init', 'javala_specials_post_type' );
function javala_specials_post_type() {

	register_post_type( 'specials',
		array(
			'labels' => array(
				'name'          => __( 'Specials', 'javala' ),
				'singular_name' => __( 'Special', 'javala' ),
				'add_new' 				=> 'Add New',
				'add_new_item' 			=> 'Add New ' . 'Special',
				'edit'		        	=> 'Edit',
				'edit_item'	        	=> 'Edit ' . 'Special',
				'new_item'	        	=> 'New ' . 'Special',
				'view' 					=> 'View ' . 'Special',
				'view_item' 			=> 'View ' . 'Special',
				'search_term'   		=> 'Search ' . 'Specials',
				'parent' 				=> 'Parent ' . 'Special',
				'not_found' 			=> 'No ' . 'Specials' .' found',
				'not_found_in_trash' 	=> 'No ' . 'Specials' .' in Trash'
			),
			'has_archive'  => true,
			'hierarchical' => false,
			'menu_icon'   => 'dashicons-tag',
			'public'       => true,
			'rewrite'      => array( 'slug' => 'specials', 'with_front' => false ),
			'supports'     => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'revisions', 'page-attributes', 'genesis-seo', 'genesis-cpt-archives-settings' ),
			'taxonomies'   => array( 'specials-type' ),

		)
	);
	
}

//* Add Specials Type Taxonomy to columns
add_filter( 'manage_taxonomies_for_specials_columns', 'javala_specials_columns' );
function javala_specials_columns( $taxonomies ) {

    $taxonomies[] = 'specials-type';
    return $taxonomies;

}

//* Change the number of specials items to be displayed (props Bill Erickson)
add_action( 'pre_get_posts', 'javala_specials_items' );
function javala_specials_items( $query ) {

	if( $query->is_main_query() && !is_admin() && is_post_type_archive( 'specials' ) ) {
		$query->set( 'posts_per_page', '12' );
	}

}

//* Customize Specials post info and post meta
add_filter( 'genesis_post_info', 'javala_specials_post_info_meta' );
add_filter( 'genesis_post_meta', 'javala_specials_post_info_meta' );
function javala_specials_post_info_meta( $output ) {

     if( 'specials' == get_post_type() )
        return '';

    return $output;

}

//* Remove featured image support from Specials custom post type
function remove_featured_image_specials() {
	remove_theme_support( 'post-thumbnails', array( 'specials' ) );
}
add_action( 'admin_menu', 'remove_featured_image_specials' );

//* Modify the WordPress read more link
add_filter( 'the_content_more_link', 'javala_read_more' );
function javala_read_more() {
	return '<a class="more-link" href="' . get_permalink() . '">' . __( 'Continue Reading', 'javala' ) . '</a>';
}