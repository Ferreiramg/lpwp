<?php
/*-----------------------------------------------------------------------------------

CLASS INFORMATION

Description: A custom WooThemes authors widget.
Date Created: 2012-03-09.
Last Modified: 2011-03-09.
Author: WooThemes.
Since: 1.0.0


TABLE OF CONTENTS

- var $woo_widget_cssclass
- var $woo_widget_description
- var $woo_widget_idbase
- var $woo_widget_title

- private $orderby_options
- private $order_options
- private $roles

- function (constructor)
- function widget ()
- function update ()
- function form ()
- function get_roles ()
- function setup_user_query_args ()

- Register the widget on `widgets_init`.

-----------------------------------------------------------------------------------*/

class Woo_Widget_Authors extends WP_Widget {

	/* Variable Declarations. */
	var $woo_widget_cssclass;
	var $woo_widget_description;
	var $woo_widget_idbase;
	var $woo_widget_title;

	private $orderby_options;
	private $order_options;
	private $roles;

	/**
	 * __construct function.
	 * 
	 * @access public
	 * @return void
	 */
	function __construct () {
	
		/* Widget variable settings. */
		$this->woo_widget_cssclass = 'widget_woo_authors';
		$this->woo_widget_description = __( 'This is a WooThemes standardized authors widget.', 'woothemes' );
		$this->woo_widget_idbase = 'woo_authors';
		$this->woo_widget_title = __('Woo - Authors', 'woothemes' );
		
		/* Setup orering options. */
		$this->orderby_options = array(
			'nicename' => __( 'Username', 'woothemes' ), 
			'email' => __( 'E-mail', 'woothemes' ), 
			'url' => __( 'Website URL', 'woothemes' ), 
			'registered' => __( 'Date Registered', 'woothemes' ), 
			'display_name' => __( 'Display Name', 'woothemes' ), 
			'post_count' => __( 'Post Count', 'woothemes' )
		);

		$this->order_options = array(
			'asc' => __( 'Ascending', 'woothemes' ), 
			'desc' => __( 'Descending', 'woothemes' )
		);

		/* Setup Available User Roles. */
		$this->roles = $this->get_roles();

		/* Widget settings. */
		$widget_ops = array( 'classname' => $this->woo_widget_cssclass, 'description' => $this->woo_widget_description );

		/* Widget control settings. */
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => $this->woo_widget_idbase );

		/* Create the widget. */
		$this->WP_Widget( $this->woo_widget_idbase, $this->woo_widget_title, $widget_ops, $control_ops );
	} // End Constructor

	/**
	 * widget function.
	 * 
	 * @access public
	 * @param array $args
	 * @param array $instance
	 * @return void
	 */
	function widget( $args, $instance ) {
		extract( $args, EXTR_SKIP );
		
		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'], $instance, $this->id_base );

		/* Setup arguments and retrieve users data. */
		$query_args = $this->setup_user_query_args( $instance );

		$users = get_users( $query_args );

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title ) {
		
			echo $before_title . $title . $after_title;
		
		} // End IF Statement
		
		/* Widget content. */
		
		// Add actions for plugins/themes to hook onto.
		do_action( $this->woo_widget_cssclass . '_top' );
		
		// Load widget content here.
		$html = '';

		if ( count( $users ) > 0 ) {
			$html .= '<ul class="user-list">' . "\n";
				foreach ( $users as $k => $v ) {
					$html .= '<li class="user-id-' . esc_attr( $v->ID ) . ' user-number-' . esc_attr( $k + 1 ) . '">' . "\n";
						$html .= '<span class="avatar">' . get_avatar( $v->user_email, '48' ) . '</span>' . "\n";
						$html .= '<h3><a href="' . esc_url( get_author_posts_url( $v->ID ) ) . '" title="' . sprintf( __( 'Posts by %s', 'woothemes' ), $v->display_name ) . '">' . $v->display_name . '</a></h3>' . "\n";
						if ( $v->user_email != '' ) { $html .= '<span class="email"><a href="mailto:' . esc_attr( $v->user_email ) . '">' . __( 'E-mail', 'woothemes' ) . '</a></span>' . "\n"; }
						if ( $v->user_url != '' ) { $html .= '<span class="url"><a href="' . esc_url( $v->user_url ) . '">' . __( 'Website', 'woothemes' ) . '</a></span>' . "\n"; }
						$html .= '<div class="fix"></div>' . "\n";
					$html .= '</li>' . "\n";
				}
			$html .= '</ul>' . "\n";
		} else {
			$html = __( 'No users currently listed.', 'woothemes' );
		}

		echo $html;
		
		// Add actions for plugins/themes to hook onto.
		do_action( $this->woo_widget_cssclass . '_bottom' );

		/* After widget (defined by themes). */
		echo $after_widget;
	} // End widget()

	/**
	 * update function.
	 * 
	 * @access public
	 * @param array $new_instance
	 * @param array $old_instance
	 * @return array $instance
	 */
	function update ( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );

		$fields = array( 'role', 'orderby', 'order', 'limit', 'include', 'exclude', 'only_authors' );

		foreach ( $fields as $f ) {
			$instance[$f] = esc_attr( trim( strip_tags( $new_instance[$f] ) ) );
		}
		
		return $instance;
	} // End update()

   /**
    * form function.
    * 
    * @access public
    * @param array $instance
    * @return void
    */
   function form ( $instance ) {       
   
		/* Set up some default widget settings. */
		/* Make sure all keys are added here, even with empty string values. */
		$defaults = array(
						'title' => __( 'Authors', 'woothemes' ), 
						'role' => 'all', 
						'only_authors' => 1, 
						'orderby' => 'login', 
						'order' => 'ASC', 
						'limit' => '', 
						'include' => '', 
						'exclude' => ''
					);
		
		$instance = wp_parse_args( (array) $instance, $defaults );
?>
		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title (optional):', 'woothemes' ); ?></label>
			<input type="text" name="<?php echo $this->get_field_name( 'title' ); ?>"  value="<?php echo $instance['title']; ?>" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" />
		</p>
		<!-- Widget Role: Select Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'role' ); ?>"><?php _e( 'User Role:', 'woothemes' ); ?></label>
			<select name="<?php echo $this->get_field_name( 'role' ); ?>" class="widefat" id="<?php echo $this->get_field_id( 'role' ); ?>">
				<option value="all"<?php selected( $instance['role'], 'all' ); ?>><?php _e( 'All', 'woothemes' ); ?></option>
				<?php foreach ( $this->roles as $k => $v ) { ?>
				<option value="<?php echo $k; ?>"<?php selected( $instance['role'], $k ); ?>><?php echo $v; ?></option>
				<?php } ?>      
			</select>
		</p>
	   	<!-- Widget Show Only Authors: Checkbox Input -->
       	<p>
        	<input id="<?php echo $this->get_field_id( 'only_authors' ); ?>" name="<?php echo $this->get_field_name( 'only_authors' ); ?>" type="checkbox"<?php checked( $instance['only_authors'], 1 ); ?> />
        	<label for="<?php echo $this->get_field_id( 'only_authors' ); ?>"><?php _e( 'Show Only Authors', 'woothemes' ); ?></label>
	   	</p>
	   	<p><small><?php _e( 'If enabled, only users who can author posts (not subscribers) will be displayed.', 'woothemes' ); ?></small></p>
	   	<!-- Widget Order By: Select Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'orderby' ); ?>"><?php _e( 'Order By:', 'woothemes' ); ?></label>
			<select name="<?php echo $this->get_field_name( 'orderby' ); ?>" class="widefat" id="<?php echo $this->get_field_id( 'orderby' ); ?>">
				<?php foreach ( $this->orderby_options as $k => $v ) { ?>
				<option value="<?php echo $k; ?>"<?php selected( $instance['orderby'], $k ); ?>><?php echo $v; ?></option>
				<?php } ?>      
			</select>
		</p>
		<!-- Widget Order: Select Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'order' ); ?>"><?php _e( 'Order:', 'woothemes' ); ?></label>
			<select name="<?php echo $this->get_field_name( 'order' ); ?>" class="widefat" id="<?php echo $this->get_field_id( 'order' ); ?>">
				<?php foreach ( $this->order_options as $k => $v ) { ?>
				<option value="<?php echo $k; ?>"<?php selected( $instance['order'], $k ); ?>><?php echo $v; ?></option>
				<?php } ?>      
			</select>
		</p>
		<!-- Widget Limit: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php _e( 'Limit:', 'woothemes' ); ?></label>
			<input type="text" name="<?php echo $this->get_field_name( 'limit' ); ?>"  value="<?php echo $instance['limit']; ?>" class="widefat" id="<?php echo $this->get_field_id( 'limit' ); ?>" />
		</p>
		<!-- Widget Include: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'include' ); ?>"><?php _e( 'Include (optional):', 'woothemes' ); ?></label>
			<input type="text" name="<?php echo $this->get_field_name( 'include' ); ?>"  value="<?php echo $instance['include']; ?>" class="widefat" id="<?php echo $this->get_field_id( 'include' ); ?>" />
		</p>
		<p><small><?php _e( 'Optionally include only certain users. Place their user IDs, separated by commas, here (eg: 1,2,3).', 'woothemes' ); ?></small></p>
		<!-- Widget Exclude: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'exclude' ); ?>"><?php _e( 'Exclude (optional):', 'woothemes' ); ?></label>
			<input type="text" name="<?php echo $this->get_field_name( 'exclude' ); ?>"  value="<?php echo $instance['exclude']; ?>" class="widefat" id="<?php echo $this->get_field_id( 'exclude' ); ?>" />
		</p>
		<p><small><?php _e( 'Optionally exclude certain users. Place their user IDs, separated by commas, here (eg: 1,2,3).', 'woothemes' ); ?></small></p>
<?php
	} // End form()

	/**
	 * Get the user roles registered within WordPress.
	 * @return array $response
	 * @since  1.0.0
	 */
	private function get_roles () {
		$response = array();

		// Get array of all available user roles.
		$roles_inst = new WP_Roles();
		$roles = $roles_inst->role_names;
		
		foreach ( $roles as $k => $v ) {
			$response[$k] = $v;
		}

		return $response;
	} // End get_roles()

	/**
	 * Setup an array of arguments to be used in the get_users() query.
	 * @param  array $instance Array of values saved in the widget settings form.
	 * @return array $query_args
	 * @since  1.0.0
	 */
	private function setup_user_query_args ( $instance ) {
		$query_args = array();

		$limit = $instance['limit'];
		$role = $instance['role'];
		$include = $instance['include'];
		$exclude = $instance['exclude'];
		$orderby = $instance['orderby'];
		$order = $instance['order'];
		$only_authors = 0;
		if ( isset( $instance['only_authors'] ) ) { $only_authors = $instance['only_authors']; }

		// Format variables according to expected data.
		if ( $limit == '' ) {} else { $limit = intval( $limit ); }
		if ( ! in_array( $role, array_keys( $this->roles ) ) && ( $role != 'all' ) ) { $role = 'all'; }
		if ( ! in_array( $orderby, array_keys( $this->orderby_options ) ) ) { $orderby = 'nicename'; }
		if ( ! in_array( $order, array_keys( $this->order_options ) ) ) { $order = 'asc'; }

		if ( $exclude != '' ) {
			$exclude = explode( ',', $exclude ); 
			$exclude = array_map( 'trim', $exclude );
		}

		if ( $include != '' ) {
			$include = explode( ',', $include ); 
			$include = array_map( 'trim', $include );
		}

		/* Setup query and query arguments. */
		$query_args = array();

		$query_args['orderby'] = $orderby;
		$query_args['order'] = $order;
		$query_args['number'] = $limit;
		
		if ( $role != 'all' ) {
			$query_args['role'] = $role;
		}

		if ( $only_authors == true ) {
			$query_args['who'] = 'authors';
		}

		if ( is_array( $exclude ) ) {
			$query_args['exclude'] = $exclude;
		}

		if ( is_array( $include ) ) {
			$query_args['include'] = $include;
		}

		$query_args['fields'] = array( 'ID', 'user_email', 'user_url', 'display_name' );

		return $query_args;
	} // End setup_user_query_args()
} // End Class

/**
 * Register the widget.
 *
 * @access public
 * @return boolean
 */
add_action( 'widgets_init', create_function( '', 'return register_widget("Woo_Widget_Authors");' ), 1 ); 
?>