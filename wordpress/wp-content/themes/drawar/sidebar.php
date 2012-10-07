<?php 
/**
 * Sidebar Template
 *
 * If a `primary` widget area is active and has widgets, display the sidebar.
 *
 * @package WooFramework
 * @subpackage Template
 */
	global $woo_options;
	
	if ( isset( $woo_options['woo_layout'] ) && ( $woo_options['woo_layout'] != 'layout-full' ) ) {
?>	
<aside id="sidebar" class="col-right">

    <div class="primary">
    	<?php
    		if ( woo_active_sidebar( 'primary' ) ) {
    			woo_sidebar( 'primary' );
    		} else {
    			// Setup default widgets.
    			
    			// Search
				$args = array( 'title' => '' );
				the_widget( 'Woo_Search', $args );
				
				// Filter
				$args = array( 'title' => '', 'enable_order' => 1, 'enable_category' => 1, 'enable_author' => 1, 'enable_postcount' => 1 );
				the_widget( 'Woo_Widget_Filter', $args );
				
				// Navigation
				// Get menus
				$menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) ); 
				$menu_id = '';
				if ( ! $menus ) {} else {
					$menu_id = $menus[0]->term_id;
				}
				$args = array( 'title' => '', 'nav_menu' => $menu_id );
				the_widget( 'WP_Nav_Menu_Widget', $args );
				
				// Recent Posts
				$args = array( 'title' => '', 'number' => 5 );
				the_widget( 'WP_Widget_Recent_Posts', $args );
    		}
    	?>	           
	</div>
	
</aside><!-- /#sidebar -->
<?php } // End IF Statement ?>