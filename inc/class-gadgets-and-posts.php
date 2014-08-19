<?php
/**
 * Base class for creating displaying sets of gadgets (post type) on the front end.  This class isn't meant 
 * to be used directly.  You should extend it was a sub-class.  Your sub-class must overwrite the format() 
 * method.
 *
 * @package    Gadgets
 * @subpackage Includes
 * @since      0.1.0
 * @author     Marty Helmick 
 * @copyright  Copyright (c) 2013, Marty Helmick
 * @link       https://github.com/m-e-h/gadgets
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

class Gadgets_And_Posts {

	/**
	 * Arguments passed in for getting gadgets.	
	 *
	 * @since  0.1.0
	 * @access public
	 * @var    array
	 */
	public $args = array();

	/**
	 * Gadget posts found from the query and formatted into an array.
	 *
	 * @since  0.1.0
	 * @access public
	 * @var    array
	 */
	public $gadgets = array();

	/**
	 * Formatted output of the set of gadgets.
	 *
	 * @since  0.1.0
	 * @access public
	 * @var    string
	 */
	public $markup = '';

	/**
	 * Constructor method.  Sets up everything.
	 *
	 * @since  0.1.0
	 * @access public
	 * @param  array   $args
	 * @return void
	 */
	public function __construct( $args = array() ) {
		global $wp_embed;

		/* Use same default filters as 'the_content' with a little more flexibility. */
		add_filter( 'gadget_content', array( $wp_embed, 'run_shortcode' ),   5 );
		add_filter( 'gadget_content', array( $wp_embed, 'autoembed'     ),   5 );
		add_filter( 'gadget_content',                   'wptexturize',       10 );
		add_filter( 'gadget_content',                   'convert_smilies',   15 );
		add_filter( 'gadget_content',                   'convert_chars',     20 );
		add_filter( 'gadget_content',                   'wpautop',           25 );
		add_filter( 'gadget_content',                   'do_shortcode',      30 );
		add_filter( 'gadget_content',                   'shortcode_unautop', 35 );

		/* Set up the default arguments. */
		$defaults = array(
			'group'   => '',         // 'gadget_group' term slug or term ID.
			'limit'   => -1,         // Display specific number of gadgets from group.
			'order'   => 'DESC',
			'orderby' => 'post_date',
		);

		$this->args = wp_parse_args( $args, $defaults );

		/* Set up the gadgets. */
		$this->set_gadgets();

		/* If there are any gadgets, set the HTML them. */
		if ( !empty( $this->gadgets ) )
			$this->markup = $this->set_markup( $this->gadgets );
	}

	/**
	 * Method for grabbing the array of gadgets queried.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return array
	 */
	public function get_gadgets() {
		return $this->gadgets;
	}

	/**
	 * Runs a posts query to grab the gadgets by the given group (required).  If gadgets are found, sets 
	 * them up in an array of "array( 'id' => $post_id, 'title' => $post_title, 'content' => $post_content )".
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function set_gadgets() {

		/* If no group was given, don't set any gadgets. */
		if ( empty( $this->args['group'] ) )
			return;

		/* Query the gadgets by gadget group. */
		$loop = new WP_Query(
			array(
				'post_type'      => 'gadget',
				'posts_per_page' => $this->args['limit'],
				'order'          => $this->args['order'],
				'orderby'        => $this->args['orderby'],
				'tax_query'      => array(
					array(
						'taxonomy' => 'gadget_group',
						'field'    => is_int( $this->args['group'] ) ? 'id' : 'slug',
						'terms'    => array( $this->args['group'] )
					)
				),
			)
		);

		while ( $loop->have_posts() ) {

			$loop->the_post();

			$this->gadgets[] = array(
				'id'        => get_the_ID(),
				'title'     => get_the_title(),
				'thumbnail' => get_the_post_thumbnail(get_the_ID(), 'large' ),
				'thumbnail_square' => get_the_post_thumbnail(get_the_ID(), 'gadget-square' ),
				'content'   => apply_filters( 'gadget_content', get_post_field( 'post_content', get_the_ID() ) )
			);
		}

		/* Reset the original post data. */
		wp_reset_postdata();
	}

	/**
	 * Return the HTML markup for display.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return string
	 */
	public function get_markup() {
		return $this->markup;
	}

	/**
	 * Sets the HTML markup for display.  Expects the $gadgets property to be passed in.
	 *
	 * Important!  This method must be overwritten in a sub-class.  Your sub-class should return an 
	 * HTML-formatted string of the $gadgets array.
	 *
	 * @since  0.1.0
	 * @access public
	 * @param  array  $gadgets
	 * @return string
	 */
	public function set_markup( $gadgets ) {
		wp_die( sprintf( __( 'The %s method must be overwritten in a sub-class.', 'gadgets' ), '<code>' . __METHOD__ . '</code>' ) );
	}
}

?>