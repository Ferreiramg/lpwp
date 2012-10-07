<?php
/*-----------------------------------------------------------------------------------

CLASS INFORMATION

Description: A custom WooThemes related posts widget.
Date Created: 2012-03-13.
Last Modified: 2011-03-13.
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

class Woo_Widget_RelatedPosts extends WP_Widget {

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
		$this->woo_widget_cssclass = 'widget_woo_relatedposts';
		$this->woo_widget_description = __( 'This is a WooThemes standardized related posts widget.', 'woothemes' );
		$this->woo_widget_idbase = 'woo_relatedposts';
		$this->woo_widget_title = __('Woo - Related Posts', 'woothemes' );
		
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
		global $post;

		// Don't show anything if all relationship options are disabled.
		if ( ! is_single() || ( $instance['enable_category'] == false && $instance['enable_tag'] == false ) ) { return; }
		
		$html = '';
		
		extract( $args, EXTR_SKIP );
		
		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'], $instance, $this->id_base );

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title ) {
			echo $before_title . $title . $after_title;
		}
		
		/* Widget content. */
		
		// Add actions for plugins/themes to hook onto.
		do_action( $this->woo_widget_cssclass . '_top' );
		
		// Load widget content here.
		$html = '';

		$query_args = array( 'limit' => intval( $instance['limit'] ), 'post_type' => get_post_type(), exclude => get_the_ID() );

		$taxonomies = array();
		$terms = array();

		if ( $instance['enable_category'] == true ) {
			$taxonomies[] = 'category';

			$categories = get_the_category();

			if ( count( $categories ) > 0 ) {
				foreach ( $categories as $k => $v ) {
					$terms[] = $v->slug;
				}
			}
		}
		if ( $instance['enable_tag'] == true ) {
			$taxonomies[] = 'post_tag';

			$tags = get_the_tags();

			if ( count( $tags ) > 0 ) {
				foreach ( $tags as $k => $v ) {
					$terms[] = $v->slug;
				}
			}
		}

		if ( count( $terms ) > 0 ) {
			$query_args['taxonomies'] = join( ', ', $taxonomies );
			$query_args['specific_terms'] = join( ', ', $terms );

			$posts = woo_get_posts_by_taxonomy( $query_args );
		}

		if ( count( $terms ) > 0 && count( $posts ) > 0 ) {
			$saved_post = $post;

			$html .= '<ul class="related-posts">' . "\n";

			$count = 0;
			foreach ( $posts as $k => $post ) {
				setup_postdata( $post );
				$count++;

				$html .= '<li class="post-number-' . esc_attr( $count ) . ' post-id-' . esc_attr( get_the_ID() ) . '">' . "\n";
					$html .= '<h4><a href="' . esc_url( get_permalink( $post ) ) . '" title="' . the_title_attribute( array( 'echo' => 0 ) ) . '">' . get_the_title() . '</a></h4>' . "\n";
					$html .= '<span class="date">' . get_the_date() . '</span>' . "\n";
				$html .= '</li>' . "\n";
			}

			$html .= '</ul>' . "\n";

			$post = $saved_post;
		} else {
			$html = '<ul class="no-related-posts"><li>' . __( 'No related posts found.', 'woothemes' ) . '</li></ul>';
		}

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

		$instance['limit'] = intval( strip_tags( $new_instance['limit'] ) );

		/* The checkbox is returning a Boolean (true/false), so we check for that. */
		$instance['enable_category'] = (bool) esc_attr( $new_instance['enable_category'] );
		$instance['enable_tag'] = (bool) esc_attr( $new_instance['enable_tag'] );
		
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
						'title' => __( 'Related Posts', 'woothemes' ), 
						'limit' => 5,
						'enable_category' => 1, 
						'enable_tag' => 1
					);
		
		$instance = wp_parse_args( (array) $instance, $defaults );
?>
		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title (optional):', 'woothemes' ); ?></label>
			<input type="text" name="<?php echo $this->get_field_name( 'title' ); ?>"  value="<?php echo $instance['title']; ?>" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" />
		</p>
		<!-- Widget Limit: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php _e( 'Limit:', 'woothemes' ); ?></label>
			<input type="text" name="<?php echo $this->get_field_name( 'limit' ); ?>"  value="<?php echo $instance['limit']; ?>" class="widefat" id="<?php echo $this->get_field_id( 'limit' ); ?>" />
		</p>
	   	<!-- Widget Enable Category: Checkbox Input -->
       	<p>
        	<input id="<?php echo $this->get_field_id( 'enable_category' ); ?>" name="<?php echo $this->get_field_name( 'enable_category' ); ?>" type="checkbox"<?php checked( $instance['enable_category'], 1 ); ?> />
        	<label for="<?php echo $this->get_field_id( 'enable_category' ); ?>"><?php _e( 'Relate by Categories', 'woothemes' ); ?></label>
	   	</p>
	   	<!-- Widget Enable Tag: Checkbox Input -->
       	<p>
        	<input id="<?php echo $this->get_field_id( 'enable_tag' ); ?>" name="<?php echo $this->get_field_name( 'enable_tag' ); ?>" type="checkbox"<?php checked( $instance['enable_tag'], 1 ); ?> />
        	<label for="<?php echo $this->get_field_id( 'enable_tag' ); ?>"><?php _e( 'Relate by Tags', 'woothemes' ); ?></label>
	   	</p>
	   	<p><small><?php _e( 'This widget displays only on single blog posts', 'woothemes' ); ?></small></p>
<?php
	} // End form()
} // End Class

/**
 * Register the widget.
 *
 * @access public
 * @return boolean
 */
add_action( 'widgets_init', create_function( '', 'return register_widget("Woo_Widget_RelatedPosts");' ), 1 ); 
?>