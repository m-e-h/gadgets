<?php
/**
 * Gadgets_And_Cards class.  Extends the Gadgets_And_Posts class to format the gadget posts into 
 * a group of cards.
 *
 * @package    Gadgets
 * @subpackage Includes
 * @since      0.1.0
 * @author     Marty Helmick 
 * @copyright  Copyright (c) 2013, Marty Helmick
 * @link       https://github.com/m-e-h/gadgets
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
class Gadgets_And_Cards extends Gadgets_And_Posts {

	/**
	 * Custom markup for the ouput of cards.
	 *
	 * @since  0.1.0
	 * @access public
	 * @param  array   $gadgets
	 * @return string
	 */
	public function set_markup( $gadgets ) {

		/* Load custom JavaScript for cards unless the current theme is handling it. */
		if ( !current_theme_supports( 'gadgets', 'scripts' ) )
			wp_enqueue_script( 'gadgets' );

		/* Set up an empty string to return. */
		$output = '';

		/* If we have gadgets, let's roll! */
		if ( !empty( $gadgets ) ) {

			/* Open the card wrapper. */
			$output .= '<div class="gadgets image-grid gadgets-card">';

			/* Loop through each of the gadgets and format the output. */
			foreach ( $gadgets as $gadget ) {

				$output .= '<figure class="effect-sadie">';

				$output .= $gadget['thumbnail_square'];

				$output .= '<figcaption><h2 class="card-title">' . $gadget['title'] . '</h2>';

				$output .= '<p class="imgcard-content">' . $gadget['excerpt'] . '</p>';

				$output .= '<a href="' . $gadget['link'] . '"> View More</a></figcaption>';

				$output .= '</figure>';
			}

			/* Close the card wrapper. */
			$output .= '</div>';
		}

		/* Return the formatted output. */
		return $output;
	}
}

?>