<?php
/**
 * File for registering custom post types.
 *
 * @package    Gadgets
 * @subpackage Includes
 * @since      0.1.0
 * @author     Marty Helmick 
 * @copyright  Copyright (c) 2013, Marty Helmick
 * @link       https://github.com/m-e-h/gadgets
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/* Register custom post types on the 'init' hook. */
add_action( 'init', 'gadgets_register_post_types' );

/**
 * Registers post types needed by the plugin.
 *
 * @since  0.1.0
 * @access public
 * @return void
 */
function gadgets_register_post_types() {

	/* Set up the arguments for the portfolio item post type. */
	$args = array(
		'description'         => '',
		'public'              => false,
		'publicly_queryable'  => false,
		'show_in_nav_menus'   => false,
		'show_in_admin_bar'   => true,
		'exclude_from_search' => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_icon'           => 'dashicons-schedule',
		'menu_position'       => 20,
		'can_export'          => true,
		'delete_with_user'    => false,
		'hierarchical'        => false,
		'has_archive'         => false,
		'query_var'           => 'gadget',
		'capability_type'     => 'gadget',
		'map_meta_cap'        => true,

		/* Only 3 caps are needed: 'manage_gadgets', 'create_gadgets', and 'edit_gadgets'. */
		'capabilities' => array(

			// meta caps (don't assign these to roles)
			'edit_post'              => 'edit_gadget',
			'read_post'              => 'read_gadget',
			'delete_post'            => 'delete_gadget',

			// primitive/meta caps
			'create_posts'           => 'create_gadgets',

			// primitive caps used outside of map_meta_cap()
			'edit_posts'             => 'edit_gadgets',
			'edit_others_posts'      => 'manage_gadgets',
			'publish_posts'          => 'manage_gadgets',
			'read_private_posts'     => 'read',

			// primitive caps used inside of map_meta_cap()
			'read'                   => 'read',
			'delete_posts'           => 'manage_gadgets',
			'delete_private_posts'   => 'manage_gadgets',
			'delete_published_posts' => 'manage_gadgets',
			'delete_others_posts'    => 'manage_gadgets',
			'edit_private_posts'     => 'edit_gadgets',
			'edit_published_posts'   => 'edit_gadgets'
		),

		/* The rewrite handles the URL structure. */
		'rewrite' => false,

		/* What features the post type supports. */
		'supports' => array(
			'title',
			'editor',
			'thumbnail',
		),

		/* Labels used when displaying the posts. */
		'labels' => array(
			'name'               => __( 'Gadgets',                   'gadgets' ),
			'singular_name'      => __( 'Gadget',                    'gadgets' ),
			'menu_name'          => __( 'Gadgets',                   'gadgets' ),
			'name_admin_bar'     => __( 'Gadget',                    'gadgets' ),
			'add_new'            => __( 'Add New',                    'gadgets' ),
			'add_new_item'       => __( 'Add New Gadget',            'gadgets' ),
			'edit_item'          => __( 'Edit Gadget',               'gadgets' ),
			'new_item'           => __( 'New Gadget',                'gadgets' ),
			'view_item'          => __( 'View Gadget',               'gadgets' ),
			'search_items'       => __( 'Search Gadgets',            'gadgets' ),
			'not_found'          => __( 'No gadgets found',          'gadgets' ),
			'not_found_in_trash' => __( 'No gadgets found in trash', 'gadgets' ),
			'all_items'          => __( 'Gadgets',                   'gadgets' ),
		)
	);

	/* Register the portfolio item post type. */
	register_post_type( 'gadget', $args );
}

?>