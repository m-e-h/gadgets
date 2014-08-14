<?php
/**
 * Gadgets_And_Accordions class.  Extends the Gadgets_And_Posts class to format the gadget posts into 
 * a group of accordions.
 *
 * @package    Gadgets
 * @subpackage Includes
 * @since      0.1.0
 * @author     Marty Helmick 
 * @copyright  Copyright (c) 2013, Marty Helmick
 * @link       https://github.com/m-e-h/gadgets
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
class Gadgets_And_Accordions extends Gadgets_And_Posts {

	/**
	 * Custom markup for the ouput of accordions.
	 *
	 * @since  0.1.0
	 * @access public
	 * @param  array   $gadgets
	 * @return string
	 */
	public function set_markup( $gadgets ) {

		/* Load custom JavaScript for accordions unless the current theme is handling it. */
		if ( !current_theme_supports( 'gadgets', 'scripts' ) )
			wp_enqueue_script( 'gadgets' );

		/* Set up an empty string to return. */
		$output = '';

		/* If we have gadgets, let's roll! */
		if ( !empty( $gadgets ) ) {

			/* Open the accordion wrapper. */
			$output .= '<div class="gadgets gadgets-accordion">';

			/* Loop through each of the gadgets and format the output. */
			foreach ( $gadgets as $gadget ) {

				$output .= '<h3 class="gadget-title">' . $gadget['title'] . '</h3>';

				$output .= '<div class="gadget-content">' . $gadget['content'] . '</div>';
			}

			/* Close the accordion wrapper. */
			$output .= '</div>';
		}

		/* Return the formatted output. */
		return $output;
	}
}

?>