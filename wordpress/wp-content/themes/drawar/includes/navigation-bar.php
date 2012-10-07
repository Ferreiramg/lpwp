<?php
/**
 * Navigation Bar Template
 *
 * Here we setup all logic and XHTML that is required for the custom navigation bar section of all screens.
 *
 * @package WooFramework
 * @subpackage Template
 */
 
 global $woo_options, $post;

/**
 * The Variables
 *
 * Setup default variables, overriding them if the "Theme Options" have been saved.
 */
	
 $settings = array(
 				 'intro_enable' => 'true', 
				 'intro_heading' => '', 
				 'intro_text' => '', 
				 'homepage_single' => 'false'
				 );
				
 $settings = woo_get_dynamic_values( $settings );
 
 $before = '';
 $after = '';
 $site_title = get_bloginfo( 'name' );
 
 if ( ! is_front_page() || ( is_home() && $settings['homepage_single'] == 'true' ) ) {
 	$before = '<a href="' . home_url( '/' ) . '" title="' . esc_attr( $site_title ) . '">';
 	$after = '</a>';
 }
?>
<div id="nav-title">
<span class="nav-name"><?php echo $before . $site_title . $after; ?></span><!--/.name-->
<?php if ( $settings['intro_enable'] == 'true' ) { ?>
<span class="nav-info"><a href="#info" title="<?php esc_attr_e( 'View more information', 'woothemes' ); ?>" class="more-information"><?php _e( 'More Information', 'woothemes' ); ?></a></span><!--/.info-->
<?php } ?>
</div><!--/#nav-title-->


<div id="controls">

<?php if ( is_single() || ( is_home() && $settings['homepage_single'] == 'true' ) ) { ?>
<nav class="post-entries">
	<span class="nav-prev"><?php previous_post_link( '%link', '%title' ); ?></span>
	<span class="nav-next"><?php next_post_link( '%link', '%title' ); ?></span>
</nav><!--/.post-entries-->
<?php } ?>

<?php
	// Custom titles per section, except posts page.
	if ( ! is_home() || ( is_home() && $settings['homepage_single'] == 'true' ) ) {
?>
<span class="title">
<?php
	if ( is_singular() ) {
		the_title();
	}
	
	if ( ! is_singular() && is_archive() ) {
		woo_archive_title();
	}

	if ( is_home() && $settings['homepage_single'] == 'true' && ! is_archive() ) {
		global $post;

		the_title();
	}
?>
</span><!--/.title-->
<?php
	}
?>

<?php if ( ( is_singular() && ! is_page_template( 'template-blog.php' ) ) || ( is_home() && $settings['homepage_single'] == 'true' ) ) { ?>
<div id="tags-sharing">

<?php
	// Tag list
	if ( ( is_single() && ( 'post' == get_post_type() ) ) || ( is_home() && $settings['homepage_single'] == 'true' ) ) {
?>
<span class="tags">
<a href="#tags" title="<?php esc_attr_e( 'Tags', 'woothemes' ); ?>"><?php _e( 'Tags', 'woothemes' ); ?></a>
<?php
if( get_the_tag_list() ) {
    echo get_the_tag_list( '<span class="tag-list"><ul><li>', '</li><li>', '</li></ul></span>' );
}
?>
</span><!--/.tags-->
<?php
	}
?>

<?php
	// Sharing options on single posts and pages
	if ( is_singular() || ( is_home() && $settings['homepage_single'] == 'true' ) ) {
		global $post;
		$sharing = array(
						'facebook' => do_shortcode( '[fblike style="button_count" url="' . get_permalink( $post ) . '"]' ), 
						'twitter' => do_shortcode( '[twitter float="none" url="' . get_permalink( $post ) . '"]' ), 
						'digg' => do_shortcode( '[digg style="compact" float="none" link="' . get_permalink( $post ) . '"]' ), 
						'google_plusone' => do_shortcode( '[google_plusone float="none" href="' . get_permalink( $post ) . '"]' ), 
						'stumbleupon' => do_shortcode( '[stumbleupon design="horizontal_small" url="' . get_permalink( $post ) . '"]' )
						);
		
		// Allow child themes/plugins to filter here.
		$sharing = apply_filters( 'woo_navbar_sharing_options', $sharing );
}
?>

<span class="sharing">
	<a href="#share" title="<?php esc_attr_e( 'Share', 'woothemes' ); ?>"><?php _e( 'Share', 'woothemes' ); ?></a>
	<span class="share-list">
		<ul>
			<?php foreach ( $sharing as $k => $v ) { ?>
				<li class="<?php echo esc_attr( $k ); ?>"><?php echo $v; ?></li>
			<?php } ?>
		</ul>
	</span><!--/.share-list-->
</span><!--/.sharing-->

</div><!--/#tags-sharing-->
<?php
	}
?>
</div><!--/#controls-->