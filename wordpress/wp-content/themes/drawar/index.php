<?php
/**
 * Index Template
 *
 * Here we setup all logic and XHTML that is required for the index template, used as both the homepage
 * and as a fallback template, if a more appropriate template file doesn't exist for a specific context.
 *
 * @package WooFramework
 * @subpackage Template
 */
	get_header();
	global $woo_options, $id, $post;

/**
 * The Variables
 *
 * Setup default variables, overriding them if the "Theme Options" have been saved.
 */
	
 $settings = array(
 				 'homepage_single' => 'false'
				 );
				
 $settings = woo_get_dynamic_values( $settings );
?>

    <div id="content" class="col-full">

		<section id="main" class="col-left">      
                    
		<?php if ( isset( $woo_options['woo_breadcrumbs_show'] ) && $woo_options['woo_breadcrumbs_show'] == 'true' ) { ?>
			<section id="breadcrumbs">
				<?php woo_breadcrumbs(); ?>
			</section><!--/#breadcrumbs -->
		<?php } ?>

		<?php
			// To customise the query used on this template, please uncomment the code below.
			/*
			$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; query_posts( array( 'post_type' => 'post', 'paged' => $paged ) );
			*/
        	if ( have_posts() ) : $count = 0;
        ?>
        
			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); $count++; ?>

				<?php
					/* Include the Post-Format-specific template for the content.
					 * If you want to overload this in a child theme then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'content', get_post_format() );
					// Determine wether or not to display comments here, based on "Theme Options".
	            	if ( $settings['homepage_single'] == 'true' && isset( $woo_options['woo_comments'] ) && in_array( $woo_options['woo_comments'], array( 'post', 'both' ) ) ) {
	            		
	            		$current_page_id = $post->ID; // Preserve this for use later.
						$GLOBALS['current_page_id'] = $current_page_id;
						
						$id = $post->ID; // Set this so the comments template assigns comments to our selected page.
						$withcomments = true; // Set this so the comments template assigns comments to our selected page.

	            		comments_template();
	            	}
				?>

			<?php endwhile; ?>

		<?php else : ?>
        
            <article <?php post_class(); ?>>
                <p><?php _e( 'Sorry, no posts matched your criteria.', 'woothemes' ); ?></p>
            </article><!-- /.post -->
        
        <?php endif; ?>
		
		<?php if ( $settings['homepage_single'] != 'true' ) { woo_pagenav(); } ?>
        
		<?php get_footer(); ?>
        
		</section><!-- /#main -->

        <?php get_sidebar(); ?>

    </div><!-- /#content -->

</div><!-- /#wrapper -->
<?php wp_footer(); ?>
<?php woo_foot(); ?>
</body>
</html>