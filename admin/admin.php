<?php
/**
 * Admin functions for the plugin.
 *
 * @package    Gadgets
 * @subpackage Admin
 * @since      0.1.0
 * @author     Marty Helmick 
 * @copyright  Copyright (c) 2013, Marty Helmick
 * @link       https://github.com/m-e-h/gadgets
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/* Set up the admin functionality. */
add_action( 'admin_menu', 'gadgets_admin_menu' );

/* Fixes the parent file. */
add_filter( 'parent_file', 'gadgets_parent_file' );

/* Adds a custom media button on the post editor. */
add_action( 'media_buttons', 'gadgets_media_buttons', 11 );

/* Loads media button popup content in the footer. */
add_action( 'admin_footer-post-new.php', 'gadgets_editor_shortcode_popup' );
add_action( 'admin_footer-post.php',     'gadgets_editor_shortcode_popup' );

/**
 * Creates admin sub-menu items under the "Appearance" screen in the admin.
 *
 * @since  0.1.0
 * @access public
 * @return void
 */
function gadgets_admin_menu() {

	/* Get the gadget post type object. */
	$post_type = get_post_type_object( 'gadget' );

	/* Add the gadget post type admin sub-menu. */
	add_theme_page( 
		$post_type->labels->name,
		$post_type->labels->menu_name,
		$post_type->cap->edit_posts,
		'edit.php?post_type=gadget'
	);

	/* Get the gadget group taxonomy object. */
	$taxonomy = get_taxonomy( 'gadget_group' );

	/* Add the gadget group sub-menu page. */
	add_theme_page(
		$taxonomy->labels->name,
		$taxonomy->labels->menu_name,
		$taxonomy->cap->manage_terms,
		'edit-tags.php?taxonomy=gadget_group&amp;post_type=gadget'
	);
}

/**
 * Corrects the parent menu item in the admin menu since we're displaying our admin screens in a custom area.
 *
 * @since  0.1.0
 * @access public
 * @param  string  $parent_file
 * @global object  $current_screen
 * @return string
 */
function gadgets_parent_file( $parent_file ) {
	global $current_screen, $self;

	/* Fix the parent file when viewing the Gadgets or New Gadget screen in the admin. */
	if ( in_array( $current_screen->base, array( 'post', 'edit' ) ) && 'gadget' === $current_screen->post_type ) {
		$parent_file = 'themes.php';
	}

	/* Fix the parent and self file when viewing the Gadget Groups screen in the admin. */
	elseif ( 'gadget_group' === $current_screen->taxonomy ) {
		$parent_file = 'themes.php';
		$self        = 'edit-tags.php?taxonomy=gadget_group&amp;post_type=gadget';
	}

	return $parent_file;
}

/**
 * Displays a link to the Thickbox popup containing the shortcode config popup on the edit post screen.
 *
 * @since  0.1.0
 * @access public
 * @param  string  $editor_id
 * @return void
 */
function gadgets_media_buttons( $editor_id ) {
	global $post;

	if ( !current_user_can( 'edit_gadgets' ) )
		return;

	if ( is_object( $post ) && !empty( $post->post_type ) && 'gadget' !== $post->post_type )
		echo '<a href="#TB_inline?width=200&amp;height=530&amp;inlineId=gadgets-shortcode-popup" class="button-secondary thickbox" data-editor="' . esc_attr( $editor_id ) . '" title="' . esc_attr__( 'Add Gadgets' ) . '">' . __( 'Add Gadgets' ) . '</a>';
}

/**
 * Shortcode config popup when the "Add Gadgets" media button is clicked.
 *
 * @since  0.1.0
 * @access public
 * @return void
 */
function gadgets_editor_shortcode_popup() {

	if ( !current_user_can( 'edit_gadgets' ) )
		return;

	$type = gadgets_get_allowed_types();

	$terms = get_terms( 'gadget_group' );

	if ( !empty( $terms ) && !is_wp_error( $terms ) ) {
		$all_terms = $terms;
		$default_term = array_shift( $all_terms );
		$default_term = $default_term->slug;
	} else {
		$default_term = '';
	}

	/* Create an array of order options. */
	$order = array(
		'ASC'  => esc_attr__( 'Ascending',  'gadgets' ),
		'DESC' => esc_attr__( 'Descending', 'gadgets' )
	);

	/* Create an array of orderby options. */
	$orderby = array( 
		'author' => esc_attr__( 'Author', 'gadgets' ),
		'date'   => esc_attr__( 'Date',   'gadgets' ),
		'ID'     => esc_attr__( 'ID',     'gadgets' ),  
		'rand'   => esc_attr__( 'Random', 'gadgets' ),
		'name'   => esc_attr__( 'Slug',   'gadgets' ),
		'title'  => esc_attr__( 'Title',  'gadgets' ),
	);

	?>
	<script>
		jQuery( document ).ready(

			function() {

				jQuery( '#gadgets-submit' ).attr( 
					'value', 
					'<?php echo esc_js( __( 'Insert', 'gadgets' ) ); ?> ' + jQuery( 'input:radio[name=gadgets-type]:checked + label' ).text()
				);

				jQuery( 'input:radio[name=gadgets-type]' ).change(
					function() {
						jQuery( '#gadgets-submit' ).attr( 
							'value', 
							'<?php echo esc_js( __( 'Insert', 'gadgets' ) ); ?> ' + jQuery( this ).next( 'label' ).text() 
						);
					}
				);
			}
		);

		function gadgets_insert_shortcode(){
			var type    = jQuery( 'input:radio[name=gadgets-type]:checked' ).val();
			var group   = jQuery( 'select#gadgets-id-group option:selected' ).val();
			var order   = jQuery( 'select#gadgets-id-order option:selected' ).val();
			var orderby = jQuery( 'select#gadgets-id-orderby option:selected' ).val();
			var limit   = jQuery( 'input#gadgets-id-limit' ).val();

			window.send_to_editor( 
				'[gadgets type="' + type + '" group="' + group + '" order="' + order + '" orderby="' + orderby + '" limit="' + limit + '"]' 
			);
		}
	</script>

	<div id="gadgets-shortcode-popup" style="display:none;">

		<div class="wrap">

		<?php if ( empty( $terms ) ) { ?>
			<p>
				<?php _e( 'You need at least one gadget group to display gadgets.', 'gadgets' ); ?> 
				<?php if ( current_user_can( 'manage_gadgets' ) ) { ?>
					<a href="<?php echo admin_url( 'edit-tags.php?taxonomy=gadget_group&post_type=gadget' ); ?>"><?php _e( 'Gadget Groups &rarr;', 'gadgets' ); ?></a>
				<?php } ?>
			</p>
			<p class="submitbox">
				<a class="button-secondary" href="#" onclick="tb_remove(); return false;"><?php _e( 'Cancel', 'gadgets' ); ?></a>
			</p>
		<?php } else { ?>
			<p>
				<?php _e( 'Type', 'gadgets' ); ?>
				<?php foreach ( $type as $option_value => $option_label ) { ?>
					<br />
					<input type="radio" name="gadgets-type" id="<?php echo esc_attr( 'gadgets-id-type-' . $option_value ); ?>" value="<?php echo esc_attr( $option_value ); ?>" <?php checked( 'tabs', $option_value ); ?> /> 
					<label for="<?php echo esc_attr( 'gadgets-id-type-' . $option_value ); ?>"><?php echo esc_html( $option_label ); ?></label>
				<?php } ?>
			</p>

			<p>
				<label for="<?php echo esc_attr( 'gadgets-id-group' ); ?>"><?php _e( 'Group', 'gadgets' ); ?></label> 
				<br />
				<select class="widefat" id="<?php echo esc_attr( 'gadgets-id-group' ); ?>" name="<?php echo esc_attr( 'gadgets-name-group' ); ?>">
					<?php foreach ( $terms as $term ) { ?>
						<option value="<?php echo esc_attr( $term->slug ); ?>" <?php selected( $default_term, $term->slug ); ?>><?php echo esc_html( $term->name ); ?></option>
					<?php } ?>
				</select>
			</p>

			<p>
				<label for="<?php echo esc_attr( 'gadgets-id-limit' ); ?>"><?php _e( 'Number of gadgets to display', 'gadgets' ); ?></label> 
				<input type="text" maxlength="3" size="3" class="code" id="<?php echo esc_attr( 'gadgets-id-limit' ); ?>" name="<?php echo esc_attr( 'gadgets-name-limit' ); ?>" value="-1" />
			</p>
			<p>
				<label for="<?php echo esc_attr( 'gadgets-id-order' ); ?>"><?php _e( 'Order', 'gadgets' ); ?></label> 
				<br />
				<select class="widefat" id="<?php echo esc_attr( 'gadgets-id-order' ); ?>" name="<?php echo esc_attr( 'gadgets-name-order' ); ?>">
					<?php foreach ( $order as $option_value => $option_label ) { ?>
						<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( 'DESC', $option_value ); ?>><?php echo esc_html( $option_label ); ?></option>
					<?php } ?>
				</select>
			</p>
			<p>
				<label for="<?php echo esc_attr( 'gadgets-id-orderby' ); ?>"><?php _e( 'Order By', 'gadgets' ); ?></label>
				<br />
				<select class="widefat" id="<?php echo esc_attr( 'gadgets-id-orderby' ); ?>" name="<?php echo esc_attr( 'gadgets-name-orderby' ); ?>">
					<?php foreach ( $orderby as $option_value => $option_label ) { ?>
						<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( 'date', $option_value ); ?>><?php echo esc_html( $option_label ); ?></option>
					<?php } ?>
				</select>
			</p>

			<p class="submitbox">
				<input type="submit" id="gadgets-submit" value="<?php esc_attr_e( 'Insert Gadgets', 'gadgets' ); ?>" class="button-primary" onclick="gadgets_insert_shortcode();" />
				<a class="button-secondary" href="#" onclick="tb_remove(); return false;"><?php _e( 'Cancel', 'gadgets' ); ?></a>
			</p>
		<?php } ?>

		</div>
	</div>
<?php
}

?>