<?php
/*-----------------------------------------------------------------------------------

CLASS INFORMATION

Description: A custom WooThemes filter widget.
Date Created: 2012-02-28.
Last Modified: 2011-02-28.
Author: WooThemes.
Since: 1.0.0


TABLE OF CONTENTS

- var $woo_widget_cssclass
- var $woo_widget_description
- var $woo_widget_idbase
- var $woo_widget_title

- function (constructor)
- function widget ()
- function update ()
- function form ()
- function filter_query ()

- Register the widget on `widgets_init`.

-----------------------------------------------------------------------------------*/

class Woo_Widget_Filter extends WP_Widget {

	/*----------------------------------------
	  Variable Declarations.
	  ----------------------------------------
	  
	  * Variables to setup the widget.
	----------------------------------------*/

	var $woo_widget_cssclass;
	var $woo_widget_description;
	var $woo_widget_idbase;
	var $woo_widget_title;

	/**
	 * __construct function.
	 * 
	 * @access public
	 * @return void
	 */
	function __construct () {
	
		/* Widget variable settings. */
		$this->woo_widget_cssclass = 'widget_woo_filter';
		$this->woo_widget_description = __( 'This is a WooThemes standardized filter widget.', 'woothemes' );
		$this->woo_widget_idbase = 'woo_filter';
		$this->woo_widget_title = __('Woo - Filter', 'woothemes' );
		
		/* Widget settings. */
		$widget_ops = array( 'classname' => $this->woo_widget_cssclass, 'description' => $this->woo_widget_description );

		/* Widget control settings. */
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => $this->woo_widget_idbase );

		/* Create the widget. */
		$this->WP_Widget( $this->woo_widget_idbase, $this->woo_widget_title, $widget_ops, $control_ops );
		
		/* Initiate the query filter, if applicable. */
		if ( ! is_admin() ) { add_filter( 'pre_get_posts', array( &$this, 'filter_query' ), 10 ); }
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
		// Don't show anything if all filter options are disabled.
		if ( $instance['enable_order'] == false && $instance['enable_category'] == false && $instance['enable_author'] == false && $instance['enable_postcount'] == false ) { return; }
		
		$html = '';
		
		extract( $args, EXTR_SKIP );
		
		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'], $instance, $this->id_base );
		
		/* Setup selected query variables. */
		$orderby = get_query_var( 'orderby' );
		$order = get_query_var( 'order' );
		
		$category = intval( $_GET['category'] );
		$author = intval( $_GET['authorid'] );
		
		$postcount = get_query_var( 'posts_per_page' );
		if ( isset( $_GET['postcount'] ) && ( $_GET['postcount'] != '' ) ) {
			$postcount = $_GET['postcount'];
		}
		
		/* Setup filtering options. */
		$orderby_options = array( 'date' => __( 'Most Recent', 'woothemes' ), 'comment_count' => __( 'Most Commented', 'woothemes' ), 'modified' => __( 'Last Modified', 'woothemes' ), 'title' => __( 'Alphabetical', 'woothemes' ) );
		
		$postcount_options = array( '1' );
		for ( $i = 1; $i <= 50; $i++ ) {
			if ( $i % 5 == 0 ) { $postcount_options[] = $i; }
		}
		
		$category_args = apply_filters( $this->woo_widget_cssclass . '_category_args', array( 'show_option_all' => __( 'All', 'woothemes' ), 'name' => 'category', 'echo' => false, 'class' => 'select category', 'selected' => $category ) );
		
		$author_args = apply_filters( $this->woo_widget_cssclass . '_author_args', array( 'show_option_all' => __( 'All', 'woothemes' ), 'name' => 'authorid', 'echo' => false, 'class' => 'select users', 'selected' => $author ) );
		
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
		
		$action_url = '';
		if ( ! is_archive() && ! is_home() && ! is_search() ) {
			$action_url = esc_url( home_url( '/' ) );
		}
		
		$html .= '<form name="woo_filter" action="' . $action_url . '" method="get">' . "\n";
			// Filter fields.
			$html .= '<fieldset class="option-menus">' . "\n";
			// Ordering.
			if ( $instance['enable_order'] == true ) {
				$html .= '<label for="orderby">' . __( 'Order By', 'woothemes' ) . '</label>' . "\n";
				$html .= '<div>' . "\n";
				$html .= '<select class="select orderby" name="orderby">';
					foreach ( $orderby_options as $k => $v ) {
						$html .= '<option value="' . $k . '"' . selected( $k, $orderby, false ) . '>' . $v . '</option>' . "\n";
					}
				$html .= '</select>' . "\n";
				$html .= '</div>' . "\n";
				
				$html .= '<label for="order">' . __( 'Order', 'woothemes' ) . '</label>' . "\n";
				$html .= '<div>' . "\n";
				$html .= '<select class="select order" name="order">';
					foreach ( array( 'asc' => __( 'Ascending', 'woothemes' ), 'desc' => __( 'Descending', 'woothemes' ) ) as $k => $v ) {
						$html .= '<option value="' . $k . '"' . selected( $k, $order, false ) . '>' . $v . '</option>' . "\n";
					}
				$html .= '</select>' . "\n";
				$html .= '</div>' . "\n";
			}
			
			// Category.
			if ( $instance['enable_category'] == true ) {
				$html .= '<label for="category">' . __( 'Category', 'woothemes' ) . '</label>' . "\n";
				$html .= '<div>' . "\n";
					$html .= wp_dropdown_categories( $category_args );
				$html .= '</select>' . "\n";
				$html .= '</div>' . "\n";
			}
			
			// Author.
			if ( $instance['enable_author'] == true && is_multi_author() ) {
				$html .= '<label for="authorid">' . __( 'Author', 'woothemes' ) . '</label>' . "\n";
				$html .= '<div>' . "\n";
					$html .= wp_dropdown_users( $author_args );
				$html .= '</select>' . "\n";
				$html .= '</div>' . "\n";
			}
			
			// Post count.
			if ( $instance['enable_postcount'] == true ) {
				$html .= '<label for="postcount">' . __( 'Show', 'woothemes' ) . '</label>' . "\n";
				$html .= '<div>' . "\n";
				$html .= '<select class="select postcount" name="postcount">';
				foreach ( $postcount_options as $k => $v ) {
					$html .= '<option value="' . $v . '"' . selected( $v, $postcount, false ) . '>' . $v . '</option>' . "\n";
				}
				$html .= '</select>' . "\n";
				$html .= '</div>' . "\n";
			}
			$html .= '</fieldset>' . "\n";
			
			// Submit button.
			$html .= '<fieldset class="submit-buttons">' . "\n";
				
				if ( isset( $_GET['postcount'] ) ) {
					$url = esc_url( $_SERVER['HTTP_REFERER'] . $_SERVER['REQUEST_URI'] );
					$url_bits = explode( '?', $url );
					$url = esc_url( $url_bits[0] );
					$html .= '<a href="' . $url . '" class="button button-cancel">' . __( 'Cancel', 'woothemes' ) . '</a>';
				}
			
				$html .= '<button type="submit" class="submit button">' . __( 'Filter', 'woothemes' ) . '</button>' . "\n";
				
				// Cater for the search form, as it also uses the GET querystring.
				if ( is_search() && ( get_query_var( 's' ) != '' ) ) {
					$html .= '<input type="hidden" name="s" value="' . esc_attr( get_query_var( 's' ) ) . '" />' . "\n";
				}
			$html .= '</fieldset>' . "\n";
		$html .= '</form>' . "\n";
		
		$html .= '<div class="fix"></div>' . "\n";

		echo $html; // If using the $html variable to store the output, you need this. ;)
		
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

		/* The checkbox is returning a Boolean (true/false), so we check for that. */
		$instance['enable_order'] = (bool) esc_attr( $new_instance['enable_order'] );
		$instance['enable_category'] = (bool) esc_attr( $new_instance['enable_category'] );
		$instance['enable_author'] = (bool) esc_attr( $new_instance['enable_author'] );
		$instance['enable_postcount'] = (bool) esc_attr( $new_instance['enable_postcount'] );
		
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
						'title' => __( 'Filter Posts', 'woothemes' ), 
						'enable_order' => 1, 
						'enable_category' => 1, 
						'enable_author' => 1, 
						'enable_postcount' => 1
					);
		
		$instance = wp_parse_args( (array) $instance, $defaults );
?>
		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title (optional):', 'woothemes' ); ?></label>
			<input type="text" name="<?php echo $this->get_field_name( 'title' ); ?>"  value="<?php echo $instance['title']; ?>" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" />
		</p>
		<!-- Widget Enable Order: Checkbox Input -->
       	<p>
        	<input id="<?php echo $this->get_field_id( 'enable_order' ); ?>" name="<?php echo $this->get_field_name( 'enable_order' ); ?>" type="checkbox"<?php checked( $instance['enable_order'], 1 ); ?> />
        	<label for="<?php echo $this->get_field_id( 'enable_order' ); ?>"><?php _e( 'Enable Ordering', 'woothemes' ); ?></label>
	   	</p>
	   	<!-- Widget Enable Category: Checkbox Input -->
       	<p>
        	<input id="<?php echo $this->get_field_id( 'enable_category' ); ?>" name="<?php echo $this->get_field_name( 'enable_category' ); ?>" type="checkbox"<?php checked( $instance['enable_category'], 1 ); ?> />
        	<label for="<?php echo $this->get_field_id( 'enable_category' ); ?>"><?php _e( 'Enable Category', 'woothemes' ); ?></label>
	   	</p>
	   	<!-- Widget Enable Author: Checkbox Input -->
       	<p>
        	<input id="<?php echo $this->get_field_id( 'enable_author' ); ?>" name="<?php echo $this->get_field_name( 'enable_author' ); ?>" type="checkbox"<?php checked( $instance['enable_author'], 1 ); ?> />
        	<label for="<?php echo $this->get_field_id( 'enable_author' ); ?>"><?php _e( 'Enable Author', 'woothemes' ); ?></label>
	   	</p>
	   	<!-- Widget Enable Post Count: Checkbox Input -->
       	<p>
        	<input id="<?php echo $this->get_field_id( 'enable_postcount' ); ?>" name="<?php echo $this->get_field_name( 'enable_postcount' ); ?>" type="checkbox"<?php checked( $instance['enable_postcount'], 1 ); ?> />
        	<label for="<?php echo $this->get_field_id( 'enable_postcount' ); ?>"><?php _e( 'Enable Post Count', 'woothemes' ); ?></label>
	   	</p>
<?php
	} // End form()
	
	/**
	 * filter_query function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @param object $query
	 * @return object $query
	 */
	function filter_query ( $query ) {
		$parse = false;
		
		// Count
		if ( isset( $_GET['postcount'] ) && $_GET['postcount'] != '' ) {
			$query->set( 'posts_per_page', intval( $_GET['postcount'] ) );
			
			$parse = true;
		}
		
		// Author
		if ( isset( $_GET['authorid'] ) && $_GET['authorid'] != '' ) {
			$query->set( 'author', intval( $_GET['authorid'] ) );
			
			$parse = true;
		}
		
		// Category
		if ( isset( $_GET['category'] ) && $_GET['category'] != '' ) {
			$query->set( 'cat', intval( $_GET['category'] ) );
			
			$parse = true;
		}
		
		if ( $parse ) { $query->parse_query(); }
		return $query;
	} // End filter_query()
	
} // End Class

/**
 * Register the widget.
 *
 * @access public
 * @return boolean
 */
add_action( 'widgets_init', create_function( '', 'return register_widget("Woo_Widget_Filter");' ), 1 ); 
?>