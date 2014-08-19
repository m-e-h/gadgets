<?php
/**
 * Functions, filters, and actions for the plugin.
 *
 * @package    Gadgets
 * @subpackage Includes
 * @since      0.1.0
 * @author     Marty Helmick 
 * @copyright  Copyright (c) 2013, Marty Helmick
 * @link       https://github.com/m-e-h/gadgets
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/* Register shortcodes. */
add_action( 'init', 'gadgets_register_shortcodes' );

/* Register widgets. */
add_action( 'widgets_init', 'gadgets_register_widgets' );

/**
 * Function for returning the allowed gadget types for display.
 *
 * @since  0.1.0
 * @access public
 * @return array
 */
function gadgets_get_allowed_types() {

	$allowed_types = array(
		'tabs'      => __( 'Tabs',      'gadgets' ),
		'toggle'    => __( 'Toggle',    'gadgets' ),
		'card'      => __( 'Card',    'gadgets' ),
		'accordion' => __( 'Accordion', 'gadgets' ),
		'slider' 		=> __( 'Slider', 'gadgets' )
	);

	return apply_filters( 'gadgets_allowed_types', $allowed_types );
}


add_filter('image_size_names_choose', 'gadgets_show_image_sizes');

function gadgets_add_image_sizes() {
    add_image_size( 'gadget-square', 500, 500, true );
}
add_action( 'init', 'gadgets_add_image_sizes' );
 
function gadgets_show_image_sizes($sizes) {
    $sizes['gadget-square'] = __( 'Gadget Square', 'gadgets' );
 
    return $sizes;
}

/**
 * Wrapper function for outputting gadgets.  You can call one of the classes directly, but it's best to use 
 * this function if needed within a theme template.
 *
 * @since  0.1.0
 * @access public
 * @return string
 */
function gadgets_get_gadgets( $args = array() ) {

	/* Allow types other than 'tabs' or 'toggle'. */
	$allowed = array_keys( gadgets_get_allowed_types() );

	/* Clean up the type and allow typos of 'tabs' and 'toggle'. */
	$args['type'] = sanitize_key( strtolower( $args['type'] ) );

	if ( 'tab' === $args['type'] )
		$args['type'] = 'tabs';

	elseif ( 'toggles' === $args['type'] )
		$args['type'] = 'toggle';

	/* ================================== */

	/* Only allow a 'type' from the $allowed_types array. */
	$type = $args['type'] = ( isset( $args['type'] ) && in_array( $args['type'], $allowed ) ) ? $args['type'] : 'tabs';

	/**
	 * Developers can overwrite the gadgets object at this point.  This is basically to bypass the 
	 * plugin's classes and use your own.  You must return an object, not a class name.  This object 
	 * must also have a method named "get_markup()" for returning the HTML markup.  It's best to simply 
	 * extend Gadgets_And_Posts and follow the structure outlined in that class.
	 */
	$gadgets_object = apply_filters( 'gadgets_object', null, $args );

	/* If no object was returned, use one of the plugin's defaults. */
	if ( !is_object( $gadgets_object ) ) {

		/* Accordion. */
		if ( 'accordion' === $type )
			$gadgets_object = new Gadgets_And_Accordions( $args );

		/* Toggle. */
		elseif ( 'toggle' === $type )
			$gadgets_object = new Gadgets_And_Toggles( $args );

		/* Card. */
		elseif ( 'card' === $type )
			$gadgets_object = new Gadgets_And_Cards( $args );

		/* Slider. */
		elseif ( 'slider' === $type )
			$gadgets_object = new Gadgets_And_Sliders( $args );

		/* Tabs. */
		else
			$gadgets_object = new Gadgets_And_Tabs( $args );
	}

	/* Return the HTML markup. */
	return $gadgets_object->get_markup();
}

/**
 * Registers the [gadgets] shortcode.
 *
 * @since  0.1.0
 * @access public
 * @return void
 */
function gadgets_register_shortcodes() {
	add_shortcode( 'gadgets', 'gadgets_do_shortcode' );
}

/**
 * Regisers the "Gadgets" widget.
 *
 * @since  0.1.0
 * @access public
 * @return void
 */
function gadgets_register_widgets() {

	require_once( GADGETS_DIR . 'inc/class-gadgets-widget.php' );

	register_widget( 'GADGETS_WIDGET' );
}

/**
 * Shortcode function.  This is just a wrapper for gadgets_get_gadgets().
 *
 * @since  0.1.0
 * @access public
 * @return string
 */
function gadgets_do_shortcode( $attr ) {
	return gadgets_get_gadgets( $attr );
}

?>