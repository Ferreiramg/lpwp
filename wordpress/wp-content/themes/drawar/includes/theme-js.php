<?php
if ( ! is_admin() ) { add_action( 'wp_enqueue_scripts', 'woothemes_add_javascript' ); }

if ( ! function_exists( 'woothemes_add_javascript' ) ) {
	function woothemes_add_javascript() {
		wp_enqueue_script( 'jquery' );    
		wp_enqueue_script( 'third party', get_template_directory_uri() . '/includes/js/third-party.js', array( 'jquery' ) );
		wp_enqueue_script( 'general', get_template_directory_uri() . '/includes/js/general.js', array( 'jquery' ) );
		
		$translation_strings = array( 'open' => __( 'Open', 'woothemes' ), 'close' => __( 'Close', 'woothemes' ) );
		$ajax_vars = array( 'woo_fetch_gravatar_nonce' => wp_create_nonce( 'woo_fetch_gravatar_nonce' ), 'woo_introbar_toggle_nonce' => wp_create_nonce( 'woo_introbar_toggle_nonce' ), 'ajaxurl' => admin_url( 'admin-ajax.php' ) );
		$data = array_merge( $translation_strings, $ajax_vars );

		/* Specify variables to be made available to the general.js file. */
		wp_localize_script( 'general', 'woo_localized_data', $data );
	}
}
?>