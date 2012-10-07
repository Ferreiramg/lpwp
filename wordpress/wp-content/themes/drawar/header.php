<?php
/**
 * Header Template
 *
 * Here we setup all logic and XHTML that is required for the header section of all screens.
 *
 * @package WooFramework
 * @subpackage Template
 */
 
 session_start();

 global $woo_options;
 
/**
 * The Variables
 *
 * Setup default variables, overriding them if the "Theme Options" have been saved.
 */
	
 $settings = array(
				 'intro_enable' => 'true', 
				 'intro_heading' => '', 
				 'intro_text' => '', 
				 'intro_closed' => 'false', 
				 'navbar_enable' => 'false'
				 );
				
 $settings = woo_get_dynamic_values( $settings );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>

<meta charset="<?php bloginfo( 'charset' ); ?>" />

<title><?php woo_title(''); ?></title>
<?php woo_meta(); ?>
<link rel="stylesheet" type="text/css" href="<?php bloginfo( 'stylesheet_url' ); ?>" media="screen" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php
	wp_head();
	woo_head();
?>

</head>

<body <?php body_class(); ?>>
<?php woo_top(); ?>

<div id="wrapper">
<?php
$toggle_class = 'open';

if ( $settings['intro_closed'] == 'true' ) {
	$toggle_class = 'closed';
}

if ( isset( $_SESSION['woo_introbar_state'] ) && $_SESSION['woo_introbar_state'] == 'closed' ) {
	$toggle_class = 'closed';
}

if ( isset( $_SESSION['woo_introbar_state'] ) && $_SESSION['woo_introbar_state'] != 'closed' ) {
	$toggle_class = 'open';
}
?>
	<div id="header-wrap" class="<?php echo $toggle_class; ?>">

	<?php if ( function_exists( 'has_nav_menu' ) && has_nav_menu( 'top-menu' ) ) { ?>

	<div id="top">
		<nav class="col-full" role="navigation">
			<?php wp_nav_menu( array( 'depth' => 6, 'sort_column' => 'menu_order', 'container' => 'ul', 'menu_id' => 'top-nav', 'menu_class' => 'nav fl', 'theme_location' => 'top-menu' ) ); ?>
		</nav>
	</div><!-- /#top -->

    <?php } ?>
    <?php
    	/* Control the display of the header bar, via theme options.*/
    	if ( $settings['intro_enable'] == 'true' ) {
    ?>
	<header id="header" class="col-full">
		
		<?php
		    $logo = get_template_directory_uri() . '/images/logo.png';
		    if ( isset( $woo_options['woo_logo'] ) && $woo_options['woo_logo'] != '' ) { $logo = $woo_options['woo_logo']; }
		?>
		<?php if ( ! isset( $woo_options['woo_texttitle'] ) || $woo_options['woo_texttitle'] != 'true' ) { ?>
		    <a id="logo" href="<?php bloginfo( 'url' ); ?>" title="<?php bloginfo( 'description' ); ?>">
		    	<img src="<?php echo $logo; ?>" alt="<?php bloginfo( 'name' ); ?>" />
		    </a>
	    <?php } ?>
	    
	    <hgroup>
	        
			<h1 class="site-title"><a href="<?php bloginfo( 'url' ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
			<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
			<h3 class="nav-toggle"><a href="#navigation"><?php _e('Navigation', 'woothemes'); ?></a></h3>
		      	
		</hgroup>
		<?php
		/* Load the introduction bar, if there is text. */
		if ( $settings['intro_heading'] != '' || $settings['intro_text'] != '' ) {
			get_template_part( 'includes/introduction', 'bar' );
		} else {
		?>
			<?php if ( isset( $woo_options['woo_ad_top'] ) && $woo_options['woo_ad_top'] == 'true' ) { ?>
	        <div id="topad">
				<?php
					if ( isset( $woo_options['woo_ad_top_adsense'] ) && $woo_options['woo_ad_top_adsense'] != '' ) {
						echo stripslashes( $woo_options['woo_ad_top_adsense'] );
					} else {
						if ( isset( $woo_options['woo_ad_top_url'] ) && isset( $woo_options['woo_ad_top_image'] ) )
				?>
					<a href="<?php echo $woo_options['woo_ad_top_url']; ?>"><img src="<?php echo $woo_options['woo_ad_top_image']; ?>" width="468" height="60" alt="advert" /></a>
				<?php } ?>
			</div><!-- /#topad -->
	        <?php } ?>
        <?php } ?>

	</header><!-- /#header -->
	<?php } // End control of the header bar. ?>
	</div><!-- /#header-wrap -->

	<div id="navigation-wrap"> 

	<nav id="navigation" class="col-full" role="navigation">
		<?php
			/* Include custom navigation area. */
			get_template_part( 'includes/navigation', 'bar' );
		?>
	</nav><!-- /#navigation -->
	
	</div><!-- /#navigation-wrap -->
	