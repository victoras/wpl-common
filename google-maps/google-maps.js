/* ============================================================ *\
	Google Maps integration by WPlook Studio.
	-----------------------------------------
	https://wplook.com/
\* ============================================================ */

// Plugin based on this methodology: https://gist.github.com/Air-Craft/1300890

(function( $ ){

	var PLUGIN_NS = 'wplGoogleMaps';

	/**
	 * Main plugin function which will be hooked as jQuery plugin.
	 *
	 * @param {jQuery} target - Elements upon which this plugin is run.
	 * @param {object} options - Options object passed to the plugin.
	 */
	var Plugin = function ( target, options ) {

		// Setup a $T constant so it can be returned at the end of every
		// function for chaining purposes.
		this.$T = $( target );

		// Object containing default settings and merging it with data attributes
		this.options = $.extend(
			true, // deep extend
			{
				latitude: null, // Latitude of map marker and centre.
		    	longitude: null, // Longitude of map marker and centre.
		    	zoom: 15, // Number from 1-20 describing the zoom level of the map.
		    	marker: {
		    		image: null, // URL for a custom marker image.
		    		width: null, // Width of marker image
		    		height: null // Height of marker image
		    	},
		    	styles: {
		    		hue: null, // A colour to display the map in.
		    		saturation: null, // Saturation of the map.
		    		lightness: null // Brightness of the map.
		    	}
			},
			{
				latitude: this.$T.data('latitude'),
		    	longitude: this.$T.data('longitude'),
		    	zoom: this.$T.data('zoom'),
		    	marker: {
		    		image: this.$T.data('marker-image'), // URL for a custom marker image.
		    		width: this.$T.data('marker-width'), // Width of marker image
		    		height: this.$T.data('marker-height') // Height of marker image
		    	},
		    	styles: {
		    		hue: this.$T.data('hue'),
		    		saturation: this.$T.data('saturation'),
		    		lightness: this.$T.data('lightness')
		    	}
			},
		);
		
		// Plugin wide variables
		this.map = null;

		// Initialize
		this._init( target, options );
		return this;

	}

	/**
	 * Initialisation function - happens when the plugin is run on a set of
	 * elements.
	 *
	 * @param {jQuery} target - Elements upon which this plugin is run.
	 * @param {object} options - Options object passed to the plugin.
	 */
	Plugin.prototype._init = function ( target, options ) {
		this._generateMap( target );
	};

	/**
	 * Generate a Google Map. The body of the plugin.
	 *
	 * @param {jQuery} target - Elements upon which this plugin is run.
	 */
	Plugin.prototype._generateMap = function( target ) {

		var self = this;

		target.each( function( index, element ) {

			if( !self.options.latitude && !self.options.longitude ) {
				console.error( 'No coordinates provided for map. Map could not be generated.' );
				return false;
			}

			self.map = new google.maps.Map( $( element )[0], {
				center: {
					lat: parseFloat( self.options.latitude ),
					lng: parseFloat( self.options.longitude )
				},
				zoom: parseFloat( self.options.zoom ),
				disableDefaultUI: true,
				scrollwheel: false,
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

			if( self.options.marker.image ) {
				// Scale image proportionally to nearest size fitting 32x32px
				if(
					( self.options.marker.width != self.options.marker.height ) ||
					!( self.options.marker.width == 32 && self.options.marker.height == 32 )
				) {
					var ratio = Math.min( 32 / self.options.marker.width, 32 / self.options.marker.height );
					self.options.marker.width = Math.round( self.options.marker.width * ratio );
					self.options.marker.height = Math.round( self.options.marker.height * ratio );
				} else {
					self.options.marker.width = 32;
					self.options.marker.height = 32;
				}

				var marker = new google.maps.Marker( {
					position: {
						lat: parseFloat( self.options.latitude ),
						lng: parseFloat( self.options.longitude )
					},
					icon: {
						url: self.options.marker.image,
						scaledSize: new google.maps.Size( self.options.marker.width, self.options.marker.height )
					},
					map: self.map
				} );
			} else {
				var marker = new google.maps.Marker( {
					position: {
						lat: parseFloat( self.options.latitude ),
						lng: parseFloat( self.options.longitude )
					},
					map: self.map
				} );
			}

			return this.$T;

		} );
	}

	/**
	 * Run the Google Maps resize event to redraw the map.
	 */
	Plugin.prototype.resize = function() {
		google.maps.event.trigger( this.map, 'resize' );
	}

	/**
	 * JQUERY HOOK
	 * 
	 * Generic jQuery plugin instantiation method call logic.
	 *
	 * @param {string|object} methodOrOptions - Either a method found in the plugin or an object of options to start the plugin with.
	*/
	$.fn[PLUGIN_NS] = function( methodOrOptions ) {

		if (!$(this).length) {
			return $(this);
		}
		var instance = $(this).data(PLUGIN_NS);
			
		// CASE: action method (public method on PLUGIN class)		
		if ( instance
				&& ( typeof methodOrOptions === 'string' && methodOrOptions.indexOf('_') != 0 )
				&& instance[ methodOrOptions ]
				&& typeof( instance[ methodOrOptions ] ) == 'function' ) {
			
			return instance[ methodOrOptions ]( Array.prototype.slice.call( arguments, 1 ) );
				
				
		// CASE: argument is options object or empty = initialise			
		} else if ( typeof methodOrOptions === 'object' || ! methodOrOptions ) {

			instance = new Plugin( $(this), methodOrOptions );	// ok to overwrite if this is a re-init
			$(this).data( PLUGIN_NS, instance );
			return $(this);
		
		// CASE: method called before init
		} else if ( !instance ) {
			$.error( 'Plugin must be initialised before using method: ' + methodOrOptions );
		
		// CASE: invalid method
		} else if ( methodOrOptions.indexOf('_') == 0 ) {
			$.error( 'Method ' +  methodOrOptions + ' is private!' );
		} else {
			$.error( 'Method ' +  methodOrOptions + ' does not exist.' );
		}
		
	};

})(jQuery);
