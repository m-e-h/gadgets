<?php
/**
 * File for registering custom taxonomies.
 *
 * @package    Gadgets
 * @subpackage Includes
 * @since      0.1.0
 * @author     Marty Helmick 
 * @copyright  Copyright (c) 2013, Marty Helmick
 * @link       https://github.com/m-e-h/gadgets
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/* Register taxonomies on the 'init' hook. */
add_action( 'init', 'gadgets_register_taxonomies' );

/**
 * Register taxonomies for the plugin.
 *
 * @since  0.1.0
 * @access public
 * @return void.
 */
function gadgets_register_taxonomies() {

	/* Set up the arguments for the portfolio taxonomy. */
	$args = array(
		'public'            => false,
		'show_ui'           => true,
		'show_in_nav_menus' => false,
		'show_tagcloud'     => true,
		'show_admin_column' => true,
		'hierarchical'      => false,
		'query_var'         => 'gadget_group',

		/* Only 2 caps are needed: 'manage_portfolio' and 'edit_portfolio_items'. */
		'capabilities' => array(
			'manage_terms' => 'manage_gadgets',
			'edit_terms'   => 'manage_gadgets',
			'delete_terms' => 'manage_gadgets',
			'assign_terms' => 'edit_gadgets',
		),

		/* The rewrite handles the URL structure. */
		'rewrite' => false,

		/* Labels used when displaying taxonomy and terms. */
		'labels' => array(
			'name'                       => __( 'Gadget Groups',                           'gadgets' ),
			'singular_name'              => __( 'Gadget Group',                            'gadgets' ),
			'menu_name'                  => __( 'Gadget Groups',                           'gadgets' ),
			'name_admin_bar'             => __( 'Gadget Group',                            'gadgets' ),
			'search_items'               => __( 'Search Gadget Groups',                    'gadgets' ),
			'popular_items'              => __( 'Popular Gadget Groups',                   'gadgets' ),
			'all_items'                  => __( 'All Gadget Groups',                       'gadgets' ),
			'edit_item'                  => __( 'Edit Gadget Group',                       'gadgets' ),
			'view_item'                  => __( 'View Gadget Group',                       'gadgets' ),
			'update_item'                => __( 'Update Gadget Group',                     'gadgets' ),
			'add_new_item'               => __( 'Add New Gadget Group',                    'gadgets' ),
			'new_item_name'              => __( 'New Gadget Group Name',                   'gadgets' ),
			'separate_items_with_commas' => __( 'Separate gadget groups with commas',      'gadgets' ),
			'add_or_remove_items'        => __( 'Add or remove gadget groups',             'gadgets' ),
			'choose_from_most_used'      => __( 'Choose from the most used gadget groups', 'gadgets' ),
		)
	);

	/* Register the 'portfolio' taxonomy. */
	register_taxonomy( 'gadget_group', array( 'gadget' ), $args );
}

?>