<?php
/**
 * Introduction Bar Template
 *
 * Here we setup all logic and XHTML that is required for the introduction bar section of all screens.
 *
 * @package WooFramework
 * @subpackage Template
 */
 
 global $woo_options;
 
/**
 * The Variables
 *
 * Setup default variables, overriding them if the "Theme Options" have been saved.
 */
	
 $settings = array(
				 'intro_heading' => '', 
				 'intro_text' => ''
				 );
				
 $settings = woo_get_dynamic_values( $settings );
?>
<div id="introduction-bar" class="fr">
<?php if ( $settings['intro_heading'] != '' ) { ?>
<h2><?php echo stripslashes( $settings['intro_heading'] ); ?></h2>
<?php } ?>
<?php if ( $settings['intro_text'] != '' ) { ?>
<div class="introduction-text"><?php echo stripslashes( $settings['intro_text'] ); ?></div><!--/.introduction-text-->
<?php } ?>
<a href="#close" class="close toggle" title="<?php esc_attr_e( 'Close the introduction bar', 'woothemes' ); ?>"><?php _e( 'Close', 'woothemes' ); ?></a>
</div><!--/#introduction-bar .fr-->