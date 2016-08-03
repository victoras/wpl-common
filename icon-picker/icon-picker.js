/**
 * JavaScript for the WPlook icon picker.
 *
 * @package WordPress
 * @subpackage WPL Common
 * @author WPlook Studio
 */
jQuery(document).ready(function( $ ) {
	
	// Icon picker function
	function wplookIconPicker( parentClass, inputClass ) {

		// Check for events here using event delagation
		// More info here: http://stackoverflow.com/a/5540632

		$( '#customize-controls, .icon-picker-parent' ).on( 'click', '.icon-picker .item', function() {

			// Copy value over to the input box
			$( this ).parents( parentClass ).find( inputClass ).val( $( this ).data( 'code' ) );

			// Manually trigger change event to get Customizer to 'see' it
			$( this ).parents( parentClass ).find( inputClass ).trigger( 'change' );

			// Set the class of the clicked item to selected
			$( this ).siblings().removeClass( 'selected' );
			$( this ).addClass( 'selected' );

		} );

		// Scroll to .selected on load
		// NOTE: Not currently working in Customizer
		$( '.icon-picker' ).each( function() {

			if( $( this ).find( '.item.selected' ).length > 0 ) {
				var selectedTop = $( this ).find( '.item.selected' )[0].getBoundingClientRect().top;
				var listTop = $( this )[0].getBoundingClientRect().top;

				$( this ).find( '.icon-list' ).scrollTop( selectedTop - listTop );
			}

		} );

		// Remove .selected from icons if field is manually changed
		var inputValue;

		$( '#customize-controls, .icon-picker-parent' ).on( 'keydown', parentClass + ' ' + inputClass, function() {

			inputValue = $( this ).val();

		} );

		$( '#customize-controls, .icon-picker-parent' ).on( 'keyup', parentClass + ' ' + inputClass, function() {

			if( $( this ).val() != inputValue ) {
				$( this ).parents( parentClass ).find( '.icon-picker .item' ).removeClass( 'selected' );
			}

		} );

		// Show more icons button
		$( '#customize-controls, .icon-picker-parent' ).on( 'click', '.icon-picker .show-more-button', function() {

			$( this ).parents( '.show-more' ).siblings( '.icon-list' ).css( 'height', 'auto' );
			$( this ).parents( '.show-more' ).css( 'display', 'none' );

		} );

		// Reduce the opacity of the .show-more gradient as the user nears the end of the list
		// Due to this http://stackoverflow.com/a/30476388 we've got to use pure JS methods...
		document.addEventListener( 'scroll', function (event) {

			if( $( event.target ).hasClass( 'icon-list' ) ) {

				var totalHeight = $( event.target )[0].scrollHeight;
				var scrollBottom = $( event.target ).scrollTop() + $( event.target ).innerHeight();
				var actualHeight = ( totalHeight - scrollBottom > 0 ? totalHeight - scrollBottom : 0 );
				
				if( actualHeight <= 300 ) {
					$( event.target ).parents( '.icon-picker' ).find( '.show-more .background' ).css( 'opacity', actualHeight / 300 );
				} else {
					if( $( event.target ).parents( '.icon-picker' ).find( '.show-more .background' ).css( 'opacity' ) < 1 ) {
						$( event.target ).parents( '.icon-picker' ).find( '.show-more .background' ).css( 'opacity', 1 );
					}
				}

			}

		}, true );

	}

	// Run icon picker JS
	if( $( '.icon-picker' ).length > 0 ) {
		wplookIconPicker( '.icon-picker-parent', 'input.icon-picker-input' );
	}
	
});
