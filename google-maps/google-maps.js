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

		// Object containing default settings.
		this.options = $.extend(
			true, // deep extend
			{
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
			options
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
					map: self.map,
					icon: icon
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
	$.fn[PLUGIN_NS] = function( methodOrOptions )
	{
		if (!$(this).length) {
			return $(this);
		}
		var instance = $(this).data(PLUGIN_NS);
			
		// CASE: action method (public method on PLUGIN class)		
		if ( instance
				&& methodOrOptions.indexOf('_') != 0
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
