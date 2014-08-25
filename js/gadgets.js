jQuery( document ).ready(
	function() {

		/* Tabs. */
		jQuery( '.gadgets-tabs .gadget-content' ).hide();
		jQuery( '.gadgets-tabs .gadget-content:first-child' ).show();
		jQuery( '.gadgets-tabs-nav :first-child' ).attr( 'aria-selected', 'true' );

		jQuery( '.gadgets-tabs-nav li a' ).click(
			function( j ) {
				j.preventDefault();

				var href = jQuery( this ).attr( 'href' );

				jQuery( this ).parents( '.gadgets-tabs' ).find( '.gadget-content' ).hide();

				jQuery( this ).parents( '.gadgets-tabs' ).find( href ).show();

				jQuery( this ).parents( '.gadgets-tabs' ).find( '.gadget-title' ).attr( 'aria-selected', 'false' );

				jQuery( this ).parent().attr( 'aria-selected', 'true' );
			}
		);

		/* Toggle. */
		jQuery( '.gadgets-toggle .gadget-content' ).hide();
		jQuery( '.gadgets-toggle .gadget-title' ).click(
			function() {
				jQuery( this ).attr( 'aria-selected', 'true' );
				jQuery( this ).next( '.gadget-content' ).slideToggle(
					'slow',
					function() {
						if ( !jQuery( this ).is( ':visible' ) ) {
							jQuery( this ).prev().attr( 'aria-selected', 'false' );
						}
					}
				);
			}
		);

		/* Card. */
  jQuery(".card-title").click(function(e){
    jQuery( this ).parents(".card-single").toggleClass("card-open");
  });


		jQuery( '.card-content' ).hide();
		jQuery( '.card-title' ).click(
			function() {
				jQuery( this ).attr( 'aria-selected', 'true' );
				jQuery( this ).next( '.card-content' ).slideToggle(
					'slow',
					function() {
						if ( !jQuery( this ).is( ':visible' ) ) {
							jQuery( this ).prev().attr( 'aria-selected', 'false' );
						}
					}
				);
			}
		);


		/* Accordion. */
		jQuery( '.gadgets-accordion .gadget-content' ).hide();
		jQuery( '.gadgets-accordion .gadget-content:first-of-type' ).show();
		jQuery( '.gadgets-accordion .gadget-title:first-of-type' ).attr( 'aria-selected', 'true' );
		jQuery( '.gadgets-accordion .gadget-title' ).click(
			function() {
				jQuery( this ).parents( '.gadgets-accordion' ).find( '.gadget-content' ).not( this ).slideUp( 
					'slow',
					function() {
						if ( !jQuery( this ).is( ':visible' ) ) {
							jQuery( this ).prev().attr( 'aria-selected', 'false' );
						}
					}
				);
				jQuery( this ).next( '.gadget-content:hidden' ).slideDown(
					'slow',
					function() {
						jQuery( this ).parents( '.gadgets-accordion' ).find( '.gadget-content' ).not( this ).slideUp( 'slow' );

						if ( !jQuery( this ).is( ':visible' ) ) {
							jQuery( this ).prev().attr( 'aria-selected', 'false' );
						}
					}
				);
				jQuery( this ).attr( 'aria-selected', 'true' );
			}
		);





		/* Slider */
		      jQuery('.flexslider').flexslider({
    animation: "slide",
    controlNav: "thumbnails"
  });

	}
);