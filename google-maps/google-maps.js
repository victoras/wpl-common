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
				styles: null,
				snazzymaps: null,
				offsetX: null,
				offsetY: null
			},
			options,
			{
				latitude: this.$T.data('latitude'),
				longitude: this.$T.data('longitude'),
				centerLatitude: this.$T.data('center-latitude'),
				centerLongitude: this.$T.data('center-longitude'),
				zoom: this.$T.data('zoom'),
				marker: {
					image: this.$T.data('marker-image'),
					width: this.$T.data('marker-width'),
					height: this.$T.data('marker-height')
				},
				saturation: this.$T.data('saturation'),
				lightness: this.$T.data('lightness'),
				hue: this.$T.data('hue'),
				offsetX: this.$T.data('offsetX'),
				offsetY: this.$T.data('offsetY')
			}
		);

		var snazzyElement = this.$T.parent().find( '#wplook-google-map-template' );
		if( snazzyElement.length > 0 ) {
			var snazzyArray = JSON.parse( this.$T.parent().find( '#wplook-google-map-template' ).html() );

			if( typeof snazzyArray == 'object') {
				this.options.snazzymaps = snazzyArray;
			}
		}

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

			var additionalMarkers = $( element ).find( '.maps-marker' );
			var markers = new Array();
			var infoWindows = new Array();

			if( ( !self.options.latitude && !self.options.longitude ) && additionalMarkers.length == 0 ) {
				console.error( 'No coordinates provided for map. Map could not be generated.' );
				return false;
			}

			// Set styles options
			if( self.options.saturation || self.options.lightness || self.options.hue ) {
				var styles = [
					{
						featureType: 'all',
						stylers: [
							{ saturation: parseFloat( self.options.saturation ) },
							{ lightness: parseFloat( self.options.lightness ) },
							{ hue: self.options.hue }
						]
					},
				];
			} else if( self.options.styles ) {
				var styles = self.options.styles;
			} else if( self.options.snazzymaps ) {
				var styles = self.options.snazzymaps;
			} else {
				var styles = false;
			}

			// Generate center
			if( self.options.centerLatitude && self.options.centerLongitude ) {
				var center = {
					lat: parseFloat( self.options.centerLatitude ),
					lng: parseFloat( self.options.centerLongitude )
				};
			} else if( self.options.latitude && self.options.longitude ) {
				var center = {
					lat: parseFloat( self.options.latitude ),
					lng: parseFloat( self.options.longitude )
				};
			} else if( additionalMarkers.length > 0 ) {
				var averageLat = 0, averageLng = 0;

				for (var i = 0; i < additionalMarkers.length; i++) {
					averageLat += parseFloat( additionalMarkers[i].dataset.latitude );
					averageLng += parseFloat( additionalMarkers[i].dataset.longitude );
				}

				averageLat = averageLat / additionalMarkers.length;
				averageLng = averageLng / additionalMarkers.length;

				var center = {
					lat: parseFloat( averageLat ),
					lng: parseFloat( averageLng )
				};
			} else {
				var center = false;
			}

			// Generate map
			self.map = new google.maps.Map( $( element )[0], {
				center: center,
				zoom: parseFloat( self.options.zoom ),
				disableDefaultUI: true,
				scrollwheel: false,
				styles: styles
			} );

			// Add [wpls_map] marker
			if( ( self.options.latitude && self.options.longitude ) && self.options.marker.image ) {
				// Scale image proportionally to nearest size fitting 32x32px
				if(
					( !self.options.marker.width || !self.options.marker.height ) ||
					!( self.options.marker.width != self.options.marker.height ) ||
					( self.options.marker.width == 32 && self.options.marker.height == 32 )
				) {
					self.options.marker.width = 32;
					self.options.marker.height = 32;
				} else {
					var ratio = Math.min( 32 / self.options.marker.width, 32 / self.options.marker.height );
					self.options.marker.width = Math.round( self.options.marker.width * ratio );
					self.options.marker.height = Math.round( self.options.marker.height * ratio );
				}

				markers[0] = new google.maps.Marker( {
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
			} else if( self.options.latitude && self.options.longitude ) {
				markers[0] = new google.maps.Marker( {
					position: {
						lat: parseFloat( self.options.latitude ),
						lng: parseFloat( self.options.longitude )
					},
					map: self.map
				} );
			}

			// Add additional markers from the [wpls_map_item] shortcode
			for (var i = 0; i < additionalMarkers.length; i++) {
				var markerOptions = {
					position: {
						lat: parseFloat( additionalMarkers[i].dataset.latitude ),
						lng: parseFloat( additionalMarkers[i].dataset.longitude )
					},
					map: self.map
				};

				// Scale image proportionally to nearest size fitting 32x32px
				if(
					( !additionalMarkers[i].dataset.markerWidth || !additionalMarkers[i].dataset.markerHeight ) ||
					!( additionalMarkers[i].dataset.markerWidth != additionalMarkers[i].dataset.markerHeight ) ||
					( additionalMarkers[i].dataset.markerWidth == 32 && additionalMarkers[i].dataset.markerHeight == 32 )
				) {
					additionalMarkers[i].dataset.markerWidth = 32;
					additionalMarkers[i].dataset.markerHeight = 32;
				} else {
					var ratio = Math.min( 32 / additionalMarkers[i].dataset.markerWidth, 32 / additionalMarkers[i].dataset.markerHeight );
					additionalMarkers[i].dataset.markerWidth = Math.round( additionalMarkers[i].dataset.markerWidth * ratio );
					additionalMarkers[i].dataset.markerHeight = Math.round( additionalMarkers[i].dataset.markerHeight * ratio );
				}

				// Add icon
				markerOptions.icon = {
					url: additionalMarkers[i].dataset.markerImage,
					scaledSize: new google.maps.Size( parseInt( additionalMarkers[i].dataset.markerWidth ), parseInt( additionalMarkers[i].dataset.markerHeight ) )
				};

				markers[i+1] = new google.maps.Marker( markerOptions );

				// Add tooltip
				if( additionalMarkers[i].innerHTML ) {
					infoWindows[i] = new google.maps.InfoWindow({
						content: additionalMarkers[i].innerHTML
					});

					markers[i+1].set('index', i);
					markers[i+1].addListener( 'click', function() {
						infoWindows[this.get('index')].open( self.map, this );
					} );
				}
			}

			// Offset options
			if( self.options.offsetX || self.options.offsetY ) {
				if( self.options.offsetX && typeof self.options.offsetX === 'string' && self.options.offsetX.indexOf( '%' ) != -1 && parseFloat( self.options.offsetX ) <= 100 && parseFloat( self.options.offsetX ) >= -100 ) { // Valid percentage value
					var containerWidth = $( window ).width();
					var offset = parseFloat( self.options.offsetX );
					var offset = offset < 0 ? offset + 50 : offset - 50; // Marker is at 50% by default anyway
					var x = containerWidth / 100 * offset;
				} else if( self.options.offsetX ) {
					var x = parseFloat( self.options.offsetX );
				} else {
					var x = 0;
				}

				if( self.options.offsetY && typeof self.options.offsetY === 'string' && self.options.offsetY.indexOf( '%' ) != -1 && parseFloat( self.options.offsetY ) <= 100 && parseFloat( self.options.offsetY ) >= -100 ) { // Valid percentage value
					var containerWidth = $( window ).width();
					var offset = parseFloat( self.options.offsetY );
					var offset = offset < 0 ? offset + 50 : offset - 50; // Marker is at 50% by default anyway
					var y = containerWidth / 100 * offset;
				} else if( self.options.offsetY ) {
					var y = parseFloat( self.options.offsetY );
				} else {
					var y = 0;
				}

				self.map.panBy( x, y );
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
