<?php
/**
 * Gadgets_And_Tabs class.  Extends the Gadgets_And_Posts class to format the gadget posts into 
 * a group of tabs.
 *
 * @package    Gadgets
 * @subpackage Includes
 * @since      0.1.0
 * @author     Marty Helmick 
 * @copyright  Copyright (c) 2013, Marty Helmick
 * @link       https://github.com/m-e-h/gadgets
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
class Gadgets_And_Tabs extends Gadgets_And_Posts {

	/**
	 * Custom markup for the ouput of tabs.
	 *
	 * @since  0.1.0
	 * @access public
	 * @param  array   $gadgets
	 * @return string
	 */
	public function set_markup( $gadgets ) {

		/* Load custom JavaScript for tabs unless the current theme is handling it. */
		if ( !current_theme_supports( 'gadgets', 'scripts' ) )
			wp_enqueue_script( 'gadgets' );

		/* Set up an empty string to return. */
		$output = '';

		/* If we have gadgets, let's roll! */
		if ( !empty( $gadgets ) ) {

			/* Generate random ID. */
			$rand = mt_rand();

			/* Open tabs wrapper. */
			$output .= '<div class="gadgets gadgets-tabs">';

			/* Open tabs nav. */
			$output .= '<ul class="gadgets-tabs-nav">';

			/* Loop through each gadget title and format it into a list item. */
			foreach ( $gadgets as $gadget ) {

				$id = sanitize_html_class( 'gadget-' . $this->args['group'] . '-' . $gadget['id'] . '-' . $rand );

				$output .= '<li class="gadget-title"><a href="#' . $id . '">' . $gadget['title'] . '</a></li>';
			}

			/* Close tabs nav. */
			$output .= '</ul><!-- gadgets-tabs-nav -->';

			/* Open tabs content wrapper. */
			$output .= '<div class="gadgets-tabs-wrap">';

			/* Loop through each gadget and format its content into a tab content block. */
			foreach ( $gadgets as $gadget ) {

				$id = sanitize_html_class( 'gadget-' . $this->args['group'] . '-' . $gadget['id'] . '-' . $rand );

				$output .= '<div id="' . $id . '" class="gadget-content">' . $gadget['content'] . '</div>';
			}

			/* Close tabs and tabs content wrappers. */
			$output .= '</div><!-- .gadgets-tabs-wrap -->';
			$output .= '</div><!-- .gadgets-tabs -->';
		}

		/* Return the formatted output. */
		return $output;
	}
}

?>