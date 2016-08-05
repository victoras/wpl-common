<?php

/**
 * Class for interfacing with the Google Maps API. At the moment, all it does
 * is store Google Maps Geocaching API long/lat coordinates in the database
 * so addresses can be displayed on maps without running over API limits.
 *
 * @package WordPress
 * @subpackage WPL Common
 * @author WPlook Studio
 */
class WPlook_Google_Maps {

	/**
	 * Variable containing instance wide settings. Contains default settings
	 * unless overwritten in constructor.
	 *
	 * @since 1.0
	 * @access public
	 * @param array $instance_opts Array of options for this instance.
	 */
	private $options = array(
		'db_address_field' => 'wplook_map_coordinates',
		'api_key' => null
	);

	/**
	 * Constructor for applying instance wide settings - mainly the API key.
	 *
	 * @since 1.0
	 * @access public
	 * @param array $instance_opts Array of options for this instance.
	 */
	public function __construct( $instance_opts ) {
		$this->options = array_merge( $this->options, $instance_opts );
	}

	/**
	 * Retrieves the coordinates from the database or makes a new API query
	 * if none are available for this address.
	 *
	 * @since 1.0
	 * @access public
	 * @param string $address Human readable address for which to get coordinates.
	 * @return array Named array of lat/long coordinates.
	 */
	public function get_coordinates( $address ) {

		$map_coordinates = get_option( $this->options['db_address_field'] );

		if( $map_coordinates == false || !array_key_exists( $address, $map_coordinates ) ) {
			$coordinates = $this->geocaching_api_request( $address );

			$address_coordinates = array(
				$address => $coordinates
			);

			if( is_array( $map_coordinates ) ) { // If there are existing coordinates
				$option_address_coordinates = array_merge( $map_coordinates, $address_coordinates );
			} else { // Start new array otherwise
				$option_address_coordinates = $address_coordinates;
			}

			if( $coordinates != false ) {
				update_option( $this->options['db_address_field'], $option_address_coordinates );
			}
			
			return $coordinates;
		} else {
			return $map_coordinates[$address];
		}

	}

	/**
	 * Makes a Google Maps Geocaching API request and returns the result. 
	 *
	 * @since 1.0
	 * @access private
	 * @param string $address Human readable address for which to get coordinates.
	 * @return array|bool Named array of lat/long coordinates, or false on failure.
	 */
	private function geocaching_api_request( $address ) {

		$json = file_get_contents( 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode( $address ) . '&key=' . urlencode( $this->options['api_key'] ) );
		$json = json_decode( $json );

		if( $json->status != 'OK' ) {
			echo 'Something went wrong when getting the coordinates for "' . $address . '" from the Google Maps Geocaching API. Please try again.';
			return false;
		} else {
			$result = array(
				'latitude' => $json->results[0]->geometry->location->lat,
				'longitude' => $json->results[0]->geometry->location->lng
			);

			return $result;
		}

	}

	/**
	 * Generates a div with data attributes, used by google-maps.js to generate
	 * the map.
	 *
	 * @since 1.0
	 * @access public
	 * @param array $args Array of relevant meta fields.
	 * @param bool $echo Whether to return or echo the code.
	 * @return array|bool Named array of lat/long coordinates, or false on failure.
	 */
	public function generate_map( $function_args, $echo = true ) {

		// Set up default options
		$args = array_merge( array(
			'human_address' => null,
			'maps_address' => null,
			'marker' => null,
			'latitude' => null,
			'longitude' => null,
			'class' => null,
			'height' => null,
			'zoom' => null,
			'saturation' => null,
			'lightness' => null,
			'hue' => null
		), $function_args );

		// Set up coordinates
		if( !empty( $args['maps_address'] ) ) {
			$coordinates = $this->get_coordinates( $args['maps_address'] );
		} elseif( !empty( $args['human_address'] ) ) {
			$coordinates = $this->get_coordinates( $args['human_address'] );
		} elseif( !empty( $args['latitude'] ) && !empty( $args['longitude'] ) ) {
			$coordinates['latitude'] = $args['latitude'];
			$coordinates['longitude'] = $args['longitude'];
		} else {
			return;
		}

		// Set up marker, either by ID or URL
		if( intval( $args['marker'] ) != 0 ) {
			$marker = wp_get_attachment_image_url( $args['marker'], 'full' );
			$marker_meta = wp_get_attachment_metadata( $args['marker'] );
			$marker_width = !empty( $marker_meta ) ? $marker_meta['width'] : false;
			$marker_height = !empty( $marker_meta ) ? $marker_meta['height'] : false;
		} else {
			$marker = $args['marker'];
			$marker_width = false;
			$marker_height = false;
		}

		if( !empty( $coordinates ) ) {
			ob_start(); ?>
				<div
					class="wplook-google-map <?php echo esc_attr( $args['class'] ); ?>"
					<?php if( !empty( $coordinates['latitude'] ) ) : ?>data-latitude="<?php echo esc_attr( $coordinates['latitude'] ); ?>"<?php endif; ?>
					<?php if( !empty( $coordinates['longitude'] ) ) : ?>data-longitude="<?php echo esc_attr( $coordinates['longitude'] ); ?>"<?php endif; ?>
					<?php if( !empty( $marker ) ) : ?>data-marker-image="<?php echo esc_attr( $marker ); ?>"<?php endif; ?>
					<?php if( !empty( $marker_width ) ) : ?>data-marker-width="<?php echo esc_attr( $marker_width ); ?>"<?php endif; ?>
					<?php if( !empty( $marker_height ) ) : ?>data-marker-height="<?php echo esc_attr( $marker_height ); ?>"<?php endif; ?>
					<?php if( !empty( $args['height'] ) ) : ?>style="height: <?php echo intval( $args['height'] ); ?>px;"<?php endif; ?>
					<?php if( !empty( $args['zoom'] ) ) : ?>data-zoom="<?php echo esc_attr( $args['zoom'] ); ?>"<?php endif; ?>
					<?php if( !empty( $args['saturation'] ) ) : ?>data-saturation="<?php echo esc_attr( $args['saturation'] ); ?>"<?php endif; ?>
					<?php if( !empty( $args['lightness'] ) ) : ?>data-lightness="<?php echo esc_attr( $args['lightness'] ); ?>"<?php endif; ?>
					<?php if( !empty( $args['hue'] ) ) : ?>data-hue="<?php echo esc_attr( $args['hue'] ); ?>"<?php endif; ?>
					>
				</div>
			<?php $html = ob_get_clean();
		}
		
		if( $echo == false ) {
			return $html;
		} else {
			echo $html;
		}

	}

}

?>
