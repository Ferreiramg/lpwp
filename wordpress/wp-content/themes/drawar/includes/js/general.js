/*-----------------------------------------------------------------------------------*/
/* GENERAL SCRIPTS */
/*-----------------------------------------------------------------------------------*/
jQuery(document).ready(function(){

	// Add class to widget line items with children
	jQuery('.widget li ul').parent().addClass('has-children');

	// Table alt row styling
	jQuery( '.entry table tr:odd' ).addClass( 'alt-table-row' );
	
	// FitVids - Responsive Videos
	jQuery( ".post, .widget" ).fitVids();
	
	// Add class to parent menu items with JS until WP does this natively
	jQuery("ul.sub-menu").parents().addClass('parent');
	
	
	// Responsive Navigation (switch top drop down for select)
	jQuery('ul#top-nav').mobileMenu({
		switchWidth: 767,                   //width (in px to switch at)
		topOptionText: 'Select a page',     //first option text
		indentString: '&nbsp;&nbsp;&nbsp;'  //string for indenting nested items
	});
  	
  	// Avoid widows in headings
  	jQuery("article header h1 a, .single article header h1, .product_title, .page-title, h1.title a, .product a h3").each(function(){var wordArray=jQuery(this).text().split(" ");var finalTitle="";for(i=0;i<=wordArray.length-1;i++){finalTitle+=wordArray[i];if(i==(wordArray.length-2)){finalTitle+="&nbsp;"}else{finalTitle+=" "}}jQuery(this).html(finalTitle)});
  	
  	// Show/hide the main navigation
  	jQuery('.nav-toggle').click(function() {
	  jQuery('#navigation').slideToggle('fast', function() {
	  	return false;
	    // Animation complete.
	  });
	});
	
	// Stop the navigation link moving to the anchor (Still need the anchor for semantic markup)
	jQuery('.nav-toggle a').click(function(e) {
        e.preventDefault();
    });
    
    // Apply styles when viewed on iPad
  	var deviceAgent = navigator.userAgent.toLowerCase();
    var agentID = deviceAgent.match(/(ipad)/);
    if (agentID) {
 
        // remove the transition so dropdowns work
        jQuery(".nav li ul").css("-webkit-transition","none", "visibility","visible", "display", "none");
        jQuery(".nav li:hover ul,.nav li li:hover ul,.nav li li li:hover ul,.nav li li li li:hover ul").css("display", "block");
 
    }

/*-----------------------------------------------------------------------------------*/
/* THEME-SPECIFIC SCRIPTS */
/*-----------------------------------------------------------------------------------*/

  	// Hide the label on comment fields when they have content, and show them when the field is empty.
	jQuery( 'input.input-text' ).each(function(type) {
		if ( jQuery( this ).attr( 'value' ) != '' ) {
			jQuery( this ).next( 'label' ).addClass( 'has-text' );
		}
		jQuery(this).focus(function() {
			jQuery(this).next( 'label.inlined' ).addClass( 'focus' );
		});
		jQuery(this).keypress(function() {
			jQuery(this).next( 'label.inlined' ).addClass( 'has-text' ).removeClass( 'focus' );
		});
		jQuery(this).blur(function() {
			if (jQuery(this).val() == '' ) {
				jQuery(this).next( 'label.inlined' ).removeClass( 'has-text' ).removeClass( 'focus' );
			}
		});
	});
	
	// Handle the toggle of the introduction bar via the "close" link.
	if ( jQuery( '#introduction-bar' ).length ) {
		jQuery( '#introduction-bar a.toggle, #navigation .more-information' ).click( function ( e ) {
			jQuery( '#navigation-wrap' ).removeClass( 'fixed' );
			if ( jQuery( '#header-wrap' ).hasClass( 'closed' ) ) {
				jQuery( '#header-wrap' ).hide().removeClass( 'closed' ).animate({ opacity: 'toggle', height: 'toggle' }, 300, function () {
					woo_save_introbar_state( 'closed' );
					var currentState = 'closed';
				});
			} else {
				jQuery( '#header-wrap' ).animate({ opacity: 'toggle', height: 'toggle' }, 300, function () {
					woo_save_introbar_state( 'open' );
					var currentState = 'open';
				});
			}

			var currentScrollPos = jQuery( window ).scrollTop();
			if ( currentScrollPos > 0 ) {
				jQuery( 'body' ).animate({scrollTop: 0}, 1000);
			}

			return false;
		});
	}
	
	// Determine the scroll offset of the navigation bar and fix it in place if scrolling beyond it.
	if ( jQuery( 'body' ).hasClass( 'fixed-navigation' ) ) {
		var navObj = jQuery( '#navigation-wrap' );
		var navPosition = navObj.offset().top;
		
		if ( jQuery( 'body' ).hasClass( 'admin-bar' ) ) {
			navPosition = navPosition - 10;
		}
		
		var currentScrollPos = jQuery( window ).scrollTop();
			
		if ( currentScrollPos >= ( navPosition ) ) {
			navObj.addClass( 'fixed' );
		} else {
			navObj.removeClass( 'fixed' );
		}
		
		jQuery( window ).scroll( function () {
			var currentScrollPos = jQuery( window ).scrollTop();
				
			if ( currentScrollPos >= ( navPosition ) ) {
				navObj.addClass( 'fixed' );
			} else {
				navObj.removeClass( 'fixed' );
			}	
		});	
	}
	
	// Automatically fetch the gravatar for the commenter, once they have typed in their e-mail address.
	if ( jQuery( '.comment-form-email' ).length && jQuery( '#respond .gravatar' ).length ) {
		jQuery( '.comment-form-email input' ).blur( function ( e ) {
			var emailAddress = jQuery( this ).val();
			
			var ajaxLoaderIcon = jQuery( this ).parents( '#respond' ).find( '.ajax-loading' );
		 	ajaxLoaderIcon.css( 'visibility', 'visible' ).fadeTo( 'slow', 1, function () {
		 		// Perform the AJAX call.	
				jQuery.post(
					woo_localized_data.ajaxurl, 
					{ 
						action : 'woo_fetch_gravatar', 
						woo_fetch_gravatar_nonce : woo_localized_data.woo_fetch_gravatar_nonce, 
						email : emailAddress
					},
					function( response ) {			
						ajaxLoaderIcon.fadeTo( 'slow', 0, function () {
							jQuery( this ).css( 'visibility', 'hidden' );
						});
						
						jQuery( '#respond' ).find( '.gravatar img' ).replaceWith( response );
					}	
				);
		 	});
		});
	}
	
/*-----------------------------------------------------------------------------------*/
/* Custom styling of the WOO FILTER SELECT elements */
/*-----------------------------------------------------------------------------------*/
	
	if ( ! jQuery.browser.opera ) {
	
	    jQuery( 'select.select' ).each( function() {
	        var title = jQuery( this ).attr( 'title' );
	        if( jQuery( 'option:selected', this ).val() != '' ) title = jQuery( 'option:selected', this ).text();
	        jQuery( this )
	            .css( { 'z-index' : 10, 'opacity' : 0, '-khtml-appearance' : 'none' } )
	            .after( '<span class="select">' + title + '</span>' )
	            .change( function() {
	                val = jQuery( 'option:selected', this ).text();
	                jQuery( this ).next().text( val );
	                });
	    });
	
	}
	
/*-----------------------------------------------------------------------------------*/
/* Center Nav Sub Menus */
/*-----------------------------------------------------------------------------------*/

	jQuery('.tag-list ul').each(function(){
	
		li_width = jQuery(this).width();
		li_width = li_width / 2.5;
		
		jQuery(this).css('margin-left', - li_width);
	});
	
	jQuery('.share-list ul').each(function(){
	
		li_width = jQuery(this).width();
		li_width = li_width / 2.5;
		
		jQuery(this).css('margin-right', - li_width);
	});	

}); // End jQuery()

function woo_save_introbar_state ( state ) {
	jQuery( '#header-wrap' ).toggleClass( 'open' );
	if ( state == 'open' ) { jQuery( '#header-wrap' ).toggleClass( 'closed' ); }
	
	// Store the "open/closed" status of the "introduction bar" in a session via AJAX.
	var currentState = 'open';
	if ( jQuery( '#header-wrap' ).hasClass( 'closed' ) ) { currentState = 'closed'; }
	
	// Perform the AJAX call.	
	jQuery.post(
		woo_localized_data.ajaxurl, 
		{ 
			action : 'woo_save_introbar_state', 
			woo_introbar_toggle_nonce : woo_localized_data.woo_introbar_toggle_nonce, 
			current_state : currentState
		},
		function( response ) {}	
	);
} // End woo_save_introbar_state()