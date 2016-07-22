/* ============================================================ *\
	Google Maps integration by WPlook Studio
	----------------------------------------
	https://wplook.com/
\* ============================================================ */

(function($) {

	var wplGoogleMaps = function( elements, options ) {

		this.elements = elements;
		this.options = $.extend( this.defaults, options );
		this.generateMap();

	};

	wplGoogleMaps.prototype = {

		// Default settings
		defaults: {
	    	latitude: null, // Latitude of map marker and centre.
	    	longitude: null, // Longitude of map marker and centre.
	    	zoom: 15, // Number from 1-20 describing the zoom level of the map.
	    	markerImage: null, // URL for a custom marker image.
	    	styles: {
	    		hue: null, // A colour to display the map in.
	    		saturation: null, // Saturation of the map.
	    		lightness: null // Brightness of the map.
	    	}
		},

		/**
		 * Generate a Google Map.
		 */
		generateMap: function() {

			var self = this;

			this.elements.each( function( index, element ) {

				if( !self.options.latitude && !self.options.longitude ) {
					console.error( 'No coordinates provided for map. Map could not be generated.' );
					return;
				}

				var map = new google.maps.Map( $( element )[0], {
					center: {
						lat: parseFloat( self.options.latitude ),
						lng: parseFloat( self.options.longitude )
					},
					zoom: parseFloat( self.options.zoom ),
					disableDefaultUI: true,
					styles: [
						{
							featureType: 'all',
							stylers: [
								{ saturation: parseFloat( self.options.styles.saturation ) },
								{ lightness: parseFloat( self.options.styles.lightness ) },
								{ hue: self.options.styles.hue }
							]
						},
					]
				} );

				if( self.options.markerImage ) {
					var icon = {
						url: self.options.markerImage,
						scaledSize: new google.maps.Size( 32, 32 )
					};

					var marker = new google.maps.Marker( {
						position: {
							lat: parseFloat( self.options.latitude ),
							lng: parseFloat( self.options.longitude )
						},
						map: map,
						icon: icon
					} );
				} else {
					var marker = new google.maps.Marker( {
						position: {
							lat: parseFloat( self.options.latitude ),
							lng: parseFloat( self.options.longitude )
						},
						map: map
					} );
				}

			} );

		}

	};

    $.fn.wplGoogleMaps = function( options ) {

        new wplGoogleMaps( this, options );
        return this;

    };

}(jQuery));
