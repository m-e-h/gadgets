<?php
/**
 * Gadgets widget class.
 *
 * @package    Gadgets
 * @subpackage Includes
 * @since      0.1.0
 * @author     Marty Helmick 
 * @copyright  Copyright (c) 2013, Marty Helmick
 * @link       https://github.com/m-e-h/gadgets
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

class Gadgets_Widget extends WP_Widget {

	/**
	 * Set up the widget's unique name, ID, class, description, and other options.
	 *
	 * @since 1.2.0
	 */
	function __construct() {

		/* Set up the widget options. */
		$widget_options = array(
			'classname'   => 'gadgets',
			'description' => esc_html__( 'Tabs, toggles, cards, accordions, sliders, media objects, sliders, posts, gadgets, and all that jazz.', 'gadgets' )
		);

		/* Set up the widget control options. */
		$control_options = array(
			'width'  => 200,
			'height' => 350
		);

		/* Create the widget. */
		$this->WP_Widget(
			'gadgets',                    // $this->id_base
			__( 'Gadgets', 'gadgets' ),  // $this->name
			$widget_options,               // $this->widget_options
			$control_options               // $this->control_options
		);
	}

	/**
	 * Outputs the widget based on the arguments input through the widget controls.
	 *
	 * @since 0.1.0
	 */
	function widget( $sidebar, $instance ) {
		extract( $sidebar );

		/* Set the $args for wp_get_archives() to the $instance array. */
		$args = $instance;

		/* Overwrite the $echo argument and set it to false. */
		$args['echo'] = false;

		/* Output the theme's $before_widget wrapper. */
		echo $before_widget;

		/* Arguments for the tab set. */
		$args = array(
			'group'   => $instance['group'],
			'limit'   => $instance['limit'],
			'type'    => $instance['type'],
			'order'   => $instance['order'],
			'orderby' => $instance['orderby']
		);

		/* If a title was input by the user, display it. */
		if ( !empty( $instance['title'] ) )
			echo $before_title . apply_filters( 'widget_title',  $instance['title'], $instance, $this->id_base ) . $after_title;

		echo gadgets_get_gadgets( $args );

		/* Close the theme's widget wrapper. */
		echo $after_widget;
	}

	/**
	 * Updates the widget control options for the particular instance of the widget.
	 *
	 * @since 0.1.0
	 */
	function update( $new_instance, $old_instance ) {

		$instance = $new_instance;

		$instance['title']   = strip_tags( $new_instance['title'] );
		$instance['group']   = strip_tags( $new_instance['group'] );
		$instance['type']    = strip_tags( $new_instance['type'] );
		$instance['order']   = strip_tags( $new_instance['order'] );
		$instance['orderby'] = strip_tags( $new_instance['orderby'] );

		$instance['limit'] = ( 0 >= $new_instance['limit'] ) ? -1 : absint( $new_instance['limit'] );

		return $instance;
	}

	/**
	 * Displays the widget control options in the Widgets admin screen.
	 *
	 * @since 0.1.0
	 */
	function form( $instance ) {

		$terms = get_terms( 'gadget_group' );

		if ( !empty( $terms ) && !is_wp_error( $terms ) ) {
			$all_terms = $terms;
			$default_term = array_shift( $all_terms );
			$default_term = $default_term->slug;
		} else {
			$default_term = '';
		}

		/* Set up the default form values. */
		$defaults = array(
			'title'   => '',
			'group'   => $default_term,
			'limit'   => -1,
			'type'    => 'tab',
			'order'   => 'DESC',
			'orderby' => 'date',
		);

		/* Merge the user-selected arguments with the defaults. */
		$instance = wp_parse_args( (array) $instance, $defaults );

		/* Create an array of archive types. */
		$type = gadgets_get_allowed_types();

		/* Create an array of order options. */
		$order = array(
			'ASC'  => esc_attr__( 'Ascending', 'gadgets' ),
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

		<?php if ( empty( $terms ) ) { ?>

			<p>
				<?php _e( 'You need at least one gadget group to display gadgets.', 'gadgets' ); ?> 
				<?php if ( current_user_can( 'manage_gadgets' ) ) { ?>
					<a href="<?php echo admin_url( 'edit-tags.php?taxonomy=gadget_group&post_type=gadget' ); ?>"><?php _e( 'Gadget Groups &rarr;', 'gadgets' ); ?></a>
				<?php } ?>
			</p>

		<?php } else { ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'gadgets' ); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'type' ); ?>"><?php _e( 'Type:', 'gadgets' ); ?></label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'type' ); ?>" name="<?php echo $this->get_field_name( 'type' ); ?>">
				<?php foreach ( $type as $option_value => $option_label ) { ?>
					<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $instance['type'], $option_value ); ?>><?php echo esc_html( $option_label ); ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'group' ); ?>"><?php _e( 'Group:', 'gadgets' ); ?></label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'group' ); ?>" name="<?php echo $this->get_field_name( 'group' ); ?>">
				<?php foreach ( $terms as $term ) { ?>
					<option value="<?php echo esc_attr( $term->slug ); ?>" <?php selected( $instance['group'], $term->slug ); ?>><?php echo esc_html( $term->name ); ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php _e( 'Limit:', 'gadgets' ); ?></label>
			<input type="text" class="code" id="<?php echo $this->get_field_id( 'limit' ); ?>" name="<?php echo $this->get_field_name( 'limit' ); ?>" value="<?php echo esc_attr( $instance['limit'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'order' ); ?>"><?php _e( 'Order', 'gadgets' ); ?></label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'order' ); ?>" name="<?php echo $this->get_field_name( 'order' ); ?>">
				<?php foreach ( $order as $option_value => $option_label ) { ?>
					<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $instance['order'], $option_value ); ?>><?php echo esc_html( $option_label ); ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'orderby' ); ?>"><?php _e( 'Order By:', 'gadgets' ); ?></label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'orderby' ); ?>" name="<?php echo $this->get_field_name( 'orderby' ); ?>">
				<?php foreach ( $orderby as $option_value => $option_label ) { ?>
					<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $instance['orderby'], $option_value ); ?>><?php echo esc_html( $option_label ); ?></option>
				<?php } ?>
			</select>
		</p>
		<?php } ?>
	<?php
	}
}

?>