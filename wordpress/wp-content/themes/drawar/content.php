<?php
/**
 * The default template for displaying content
 */

	global $woo_options;
 
/**
 * The Variables
 *
 * Setup default variables, overriding them if the "Theme Options" have been saved.
 */

 	$settings = array(
					'thumb_w' => 100, 
					'thumb_h' => 100, 
					'thumb_align' => 'alignleft', 
					'homepage_single' => 'false'
					);
					
	$settings = woo_get_dynamic_values( $settings );

	$content_var = 'woo_post_content';
	$continue_text =  __( 'Continue Reading &rarr;', 'woothemes' );
	
	if ( is_front_page() ) {
		$content_var = 'woo_post_content_home';
	}

	if ( is_home() && ( $settings['homepage_single'] == 'true' ) ) {
		$continue_text = '';
	}
?>

	<article <?php post_class(); ?>>
	
	    <?php 
	    	if ( isset( $woo_options[$content_var] ) && $woo_options[$content_var] != 'content' ) { 
	    		woo_image( 'width=' . $settings['thumb_w'] . '&height=' . $settings['thumb_h'] . '&class=thumbnail ' . $settings['thumb_align'] ); 
	    	} 
	    ?>
	    
		<header>
			<h1><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
			<?php woo_post_meta(); ?>
		</header>

		<section class="entry">
		<?php if ( isset( $woo_options[$content_var] ) && $woo_options[$content_var] == 'content' ) {
				if ( $settings['homepage_single'] == 'true' ) {
					echo apply_filters( 'the_content', $post->post_content );
				} else {
					the_content( $continue_text );
				}
			} else { the_excerpt(); } ?>
		</section>

		<footer class="post-more">      
		<?php if ( isset( $woo_options[$content_var] ) && $woo_options[$content_var] == 'excerpt' ) { ?>
			<span class="comments"><?php comments_popup_link( __( 'Leave a comment', 'woothemes' ), __( '1 Comment', 'woothemes' ), __( '% Comments', 'woothemes' ) ); ?></span>
			<span class="post-more-sep">&bull;</span>
			<span class="read-more"><a href="<?php the_permalink(); ?>" title="<?php esc_attr_e( 'Continue Reading &rarr;', 'woothemes' ); ?>"><?php _e( 'Continue Reading &rarr;', 'woothemes' ); ?></a></span>
		<?php } ?>
		</footer>   

	</article><!-- /.post -->