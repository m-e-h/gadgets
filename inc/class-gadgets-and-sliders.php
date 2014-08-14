<?php
/**
 * Gadgets_And_Sliders class.  Extends the Gadgets_And_Posts class to format the gadget posts into 
 * a group of sliders.
 *
 * @package    Gadgets
 * @subpackage Includes
 * @since      0.1.0
 * @author     Marty Helmick 
 * @copyright  Copyright (c) 2013, Marty Helmick
 * @link       https://github.com/m-e-h/gadgets
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
class Gadgets_And_Sliders extends Gadgets_And_Posts {

	/**
	 * Custom markup for the ouput of sliders.
	 *
	 * @since  0.1.0
	 * @access public
	 * @param  array   $gadgets
	 * @return string
	 */
	public function set_markup( $gadgets ) {

		/* Load custom JavaScript for sliders unless the current theme is handling it. */
		if ( !current_theme_supports( 'gadgets', 'scripts' ) )
			wp_enqueue_script( 'gadgets' );

		/* Set up an empty string to return. */
		$output = '';

		/* If we have gadgets, let's roll! */
		if ( !empty( $gadgets ) ) {

			/* Open the slider wrapper. */

			$output .= '<div class="flexslider-background">';
			
			$output .= '<div class="flexslider">';

			$output .= '<ul class="slides">';

			/* Loop through each of the gadgets and format the output. */
			foreach ( $gadgets as $gadget ) {

				// $output .= '<li class="gadget-title">' . $gadget['title'] . '</li>';

				$output .= '<li class="slide-thumb">' . $gadget['thumbnail'] . '</li>';

				// $output .= '<div class="gadget-content">' . $gadget['content'] . '</div>';
			}

			/* Close the slider wrapper. */
			$output .= '</ul>';

			$output .= '</div>';

			$output .= '</div>';
		}

		/* Return the formatted output. */
		return $output;
	}
}

?>