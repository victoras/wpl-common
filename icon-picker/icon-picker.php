<?php

/**
 * Class for displaying an icon picker in the WordPress admin. Comes preloaded
 * with hooks to integrate it with OptionTree and Font Awesome as the
 * default set of icons.
 *
 * @package WordPress
 * @subpackage WPL Common
 * @author WPlook Studio
 */
class WPlook_Icon_Picker {

	/**
	 * Variable containing instance wide settings. Contains default settings
	 * unless overwritten in constructor.
	 *
	 * @since 1.0
	 * @access public
	 * @param array $instance_opts Array of options for this instance.
	 */
	private $options = array();

	/**
	 * Constructor for applying instance wide settings.
	 *
	 * @since 1.0
	 * @access public
	 * @param array $instance_opts Array of options for this instance.
	 */
	public function __construct( $instance_opts = array() ) {

		// Merge options with defaults
		$this->options = array_merge( $this->options, $instance_opts );

		// Load scripts in admin area
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) ); // For testing purposes
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Register OT option type
		add_filter( 'ot_option_types_array', array( $this, 'register_ot_option_type' ) );

	}

	/**
	 * Enqueue scripts and styles.
	 *
	 * @since 1.0
	 * @access public
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( 'wplook-icon-picker', get_template_directory_uri() . '/inc/icon-picker/icon-picker.js', array( 'jquery' ), false, true );
		wp_enqueue_style( 'wplook-icon-picker', get_template_directory_uri() . '/inc/icon-picker/icon-picker.css' );

	}

	/**
	 * Return an array of icons for an iconset.
	 *
	 * @since 1.0
	 * @access public
	 * @param string $iconset Name of iconset to load. Defaults to Font Awesome.
	 * @return array A single dimensional array which contains the CSS class names of the icons.
	 */
	public function get_iconset( $iconset = 'font-awesome' ) {

		if( $iconset == 'font-awesome' ) {
			// Font Awesome 4.6.3
			$font_awesome = array( 'fa fa-500px', 'fa fa-adjust', 'fa fa-adn', 'fa fa-align-center', 'fa fa-align-justify', 'fa fa-align-left', 'fa fa-align-right', 'fa fa-amazon', 'fa fa-ambulance', 'fa fa-american-sign-language-interpreting', 'fa fa-anchor', 'fa fa-android', 'fa fa-angellist', 'fa fa-angle-double-down', 'fa fa-angle-double-left', 'fa fa-angle-double-right', 'fa fa-angle-double-up', 'fa fa-angle-down', 'fa fa-angle-left', 'fa fa-angle-right', 'fa fa-angle-up', 'fa fa-apple', 'fa fa-archive', 'fa fa-area-chart', 'fa fa-arrow-circle-down', 'fa fa-arrow-circle-left', 'fa fa-arrow-circle-o-down', 'fa fa-arrow-circle-o-left', 'fa fa-arrow-circle-o-right', 'fa fa-arrow-circle-o-up', 'fa fa-arrow-circle-right', 'fa fa-arrow-circle-up', 'fa fa-arrow-down', 'fa fa-arrow-left', 'fa fa-arrow-right', 'fa fa-arrow-up', 'fa fa-arrows', 'fa fa-arrows-alt', 'fa fa-arrows-h', 'fa fa-arrows-v', 'fa fa-asl-interpreting', 'fa fa-assistive-listening-systems', 'fa fa-asterisk', 'fa fa-at', 'fa fa-audio-description', 'fa fa-automobile', 'fa fa-backward', 'fa fa-balance-scale', 'fa fa-ban', 'fa fa-bank', 'fa fa-bar-chart', 'fa fa-bar-chart-o', 'fa fa-barcode', 'fa fa-bars', 'fa fa-battery-0', 'fa fa-battery-1', 'fa fa-battery-2', 'fa fa-battery-3', 'fa fa-battery-4', 'fa fa-battery-empty', 'fa fa-battery-full', 'fa fa-battery-half', 'fa fa-battery-quarter', 'fa fa-battery-three-quarters', 'fa fa-bed', 'fa fa-beer', 'fa fa-behance', 'fa fa-behance-square', 'fa fa-bell', 'fa fa-bell-o', 'fa fa-bell-slash', 'fa fa-bell-slash-o', 'fa fa-bicycle', 'fa fa-binoculars', 'fa fa-birthday-cake', 'fa fa-bitbucket', 'fa fa-bitbucket-square', 'fa fa-bitcoin', 'fa fa-black-tie', 'fa fa-blind', 'fa fa-bluetooth', 'fa fa-bluetooth-b', 'fa fa-bold', 'fa fa-bolt', 'fa fa-bomb', 'fa fa-book', 'fa fa-bookmark', 'fa fa-bookmark-o', 'fa fa-braille', 'fa fa-briefcase', 'fa fa-btc', 'fa fa-bug', 'fa fa-building', 'fa fa-building-o', 'fa fa-bullhorn', 'fa fa-bullseye', 'fa fa-bus', 'fa fa-buysellads', 'fa fa-cab', 'fa fa-calculator', 'fa fa-calendar', 'fa fa-calendar-check-o', 'fa fa-calendar-minus-o', 'fa fa-calendar-o', 'fa fa-calendar-plus-o', 'fa fa-calendar-times-o', 'fa fa-camera', 'fa fa-camera-retro', 'fa fa-car', 'fa fa-caret-down', 'fa fa-caret-left', 'fa fa-caret-right', 'fa fa-caret-square-o-down', 'fa fa-caret-square-o-left', 'fa fa-caret-square-o-right', 'fa fa-caret-square-o-up', 'fa fa-caret-up', 'fa fa-cart-arrow-down', 'fa fa-cart-plus', 'fa fa-cc', 'fa fa-cc-amex', 'fa fa-cc-diners-club', 'fa fa-cc-discover', 'fa fa-cc-jcb', 'fa fa-cc-mastercard', 'fa fa-cc-paypal', 'fa fa-cc-stripe', 'fa fa-cc-visa', 'fa fa-certificate', 'fa fa-chain', 'fa fa-chain-broken', 'fa fa-check', 'fa fa-check-circle', 'fa fa-check-circle-o', 'fa fa-check-square', 'fa fa-check-square-o', 'fa fa-chevron-circle-down', 'fa fa-chevron-circle-left', 'fa fa-chevron-circle-right', 'fa fa-chevron-circle-up', 'fa fa-chevron-down', 'fa fa-chevron-left', 'fa fa-chevron-right', 'fa fa-chevron-up', 'fa fa-child', 'fa fa-chrome', 'fa fa-circle', 'fa fa-circle-o', 'fa fa-circle-o-notch', 'fa fa-circle-thin', 'fa fa-clipboard', 'fa fa-clock-o', 'fa fa-clone', 'fa fa-close', 'fa fa-cloud', 'fa fa-cloud-download', 'fa fa-cloud-upload', 'fa fa-cny', 'fa fa-code', 'fa fa-code-fork', 'fa fa-codepen', 'fa fa-codiepie', 'fa fa-coffee', 'fa fa-cog', 'fa fa-cogs', 'fa fa-columns', 'fa fa-comment', 'fa fa-comment-o', 'fa fa-commenting', 'fa fa-commenting-o', 'fa fa-comments', 'fa fa-comments-o', 'fa fa-compass', 'fa fa-compress', 'fa fa-connectdevelop', 'fa fa-contao', 'fa fa-copy', 'fa fa-copyright', 'fa fa-creative-commons', 'fa fa-credit-card', 'fa fa-credit-card-alt', 'fa fa-crop', 'fa fa-crosshairs', 'fa fa-css3', 'fa fa-cube', 'fa fa-cubes', 'fa fa-cut', 'fa fa-cutlery', 'fa fa-dashboard', 'fa fa-dashcube', 'fa fa-database', 'fa fa-deaf', 'fa fa-deafness', 'fa fa-dedent', 'fa fa-delicious', 'fa fa-desktop', 'fa fa-deviantart', 'fa fa-diamond', 'fa fa-digg', 'fa fa-dollar', 'fa fa-dot-circle-o', 'fa fa-download', 'fa fa-dribbble', 'fa fa-dropbox', 'fa fa-drupal', 'fa fa-edge', 'fa fa-edit', 'fa fa-eject', 'fa fa-ellipsis-h', 'fa fa-ellipsis-v', 'fa fa-empire', 'fa fa-envelope', 'fa fa-envelope-o', 'fa fa-envelope-square', 'fa fa-envira', 'fa fa-eraser', 'fa fa-eur', 'fa fa-euro', 'fa fa-exchange', 'fa fa-exclamation', 'fa fa-exclamation-circle', 'fa fa-exclamation-triangle', 'fa fa-expand', 'fa fa-expeditedssl', 'fa fa-external-link', 'fa fa-external-link-square', 'fa fa-eye', 'fa fa-eye-slash', 'fa fa-eyedropper', 'fa fa-fa', 'fa fa-facebook', 'fa fa-facebook-f', 'fa fa-facebook-official', 'fa fa-facebook-square', 'fa fa-fast-backward', 'fa fa-fast-forward', 'fa fa-fax', 'fa fa-feed', 'fa fa-female', 'fa fa-fighter-jet', 'fa fa-file', 'fa fa-file-archive-o', 'fa fa-file-audio-o', 'fa fa-file-code-o', 'fa fa-file-excel-o', 'fa fa-file-image-o', 'fa fa-file-movie-o', 'fa fa-file-o', 'fa fa-file-pdf-o', 'fa fa-file-photo-o', 'fa fa-file-picture-o', 'fa fa-file-powerpoint-o', 'fa fa-file-sound-o', 'fa fa-file-text', 'fa fa-file-text-o', 'fa fa-file-video-o', 'fa fa-file-word-o', 'fa fa-file-zip-o', 'fa fa-files-o', 'fa fa-film', 'fa fa-filter', 'fa fa-fire', 'fa fa-fire-extinguisher', 'fa fa-firefox', 'fa fa-first-order', 'fa fa-flag', 'fa fa-flag-checkered', 'fa fa-flag-o', 'fa fa-flash', 'fa fa-flask', 'fa fa-flickr', 'fa fa-floppy-o', 'fa fa-folder', 'fa fa-folder-o', 'fa fa-folder-open', 'fa fa-folder-open-o', 'fa fa-font', 'fa fa-font-awesome', 'fa fa-fonticons', 'fa fa-fort-awesome', 'fa fa-forumbee', 'fa fa-forward', 'fa fa-foursquare', 'fa fa-frown-o', 'fa fa-futbol-o', 'fa fa-gamepad', 'fa fa-gavel', 'fa fa-gbp', 'fa fa-ge', 'fa fa-gear', 'fa fa-gears', 'fa fa-genderless', 'fa fa-get-pocket', 'fa fa-gg', 'fa fa-gg-circle', 'fa fa-gift', 'fa fa-git', 'fa fa-git-square', 'fa fa-github', 'fa fa-github-alt', 'fa fa-github-square', 'fa fa-gitlab', 'fa fa-gittip', 'fa fa-glass', 'fa fa-glide', 'fa fa-glide-g', 'fa fa-globe', 'fa fa-google', 'fa fa-google-plus', 'fa fa-google-plus-circle', 'fa fa-google-plus-official', 'fa fa-google-plus-square', 'fa fa-google-wallet', 'fa fa-graduation-cap', 'fa fa-gratipay', 'fa fa-group', 'fa fa-h-square', 'fa fa-hacker-news', 'fa fa-hand-grab-o', 'fa fa-hand-lizard-o', 'fa fa-hand-o-down', 'fa fa-hand-o-left', 'fa fa-hand-o-right', 'fa fa-hand-o-up', 'fa fa-hand-paper-o', 'fa fa-hand-peace-o', 'fa fa-hand-pointer-o', 'fa fa-hand-rock-o', 'fa fa-hand-scissors-o', 'fa fa-hand-spock-o', 'fa fa-hand-stop-o', 'fa fa-hard-of-hearing', 'fa fa-hashtag', 'fa fa-hdd-o', 'fa fa-header', 'fa fa-headphones', 'fa fa-heart', 'fa fa-heart-o', 'fa fa-heartbeat', 'fa fa-history', 'fa fa-home', 'fa fa-hospital-o', 'fa fa-hotel', 'fa fa-hourglass', 'fa fa-hourglass-1', 'fa fa-hourglass-2', 'fa fa-hourglass-3', 'fa fa-hourglass-end', 'fa fa-hourglass-half', 'fa fa-hourglass-o', 'fa fa-hourglass-start', 'fa fa-houzz', 'fa fa-html5', 'fa fa-i-cursor', 'fa fa-ils', 'fa fa-image', 'fa fa-inbox', 'fa fa-indent', 'fa fa-industry', 'fa fa-info', 'fa fa-info-circle', 'fa fa-inr', 'fa fa-instagram', 'fa fa-institution', 'fa fa-internet-explorer', 'fa fa-intersex', 'fa fa-ioxhost', 'fa fa-italic', 'fa fa-joomla', 'fa fa-jpy', 'fa fa-jsfiddle', 'fa fa-key', 'fa fa-keyboard-o', 'fa fa-krw', 'fa fa-language', 'fa fa-laptop', 'fa fa-lastfm', 'fa fa-lastfm-square', 'fa fa-leaf', 'fa fa-leanpub', 'fa fa-legal', 'fa fa-lemon-o', 'fa fa-level-down', 'fa fa-level-up', 'fa fa-life-bouy', 'fa fa-life-buoy', 'fa fa-life-ring', 'fa fa-life-saver', 'fa fa-lightbulb-o', 'fa fa-line-chart', 'fa fa-link', 'fa fa-linkedin', 'fa fa-linkedin-square', 'fa fa-linux', 'fa fa-list', 'fa fa-list-alt', 'fa fa-list-ol', 'fa fa-list-ul', 'fa fa-location-arrow', 'fa fa-lock', 'fa fa-long-arrow-down', 'fa fa-long-arrow-left', 'fa fa-long-arrow-right', 'fa fa-long-arrow-up', 'fa fa-low-vision', 'fa fa-magic', 'fa fa-magnet', 'fa fa-mail-forward', 'fa fa-mail-reply', 'fa fa-mail-reply-all', 'fa fa-male', 'fa fa-map', 'fa fa-map-marker', 'fa fa-map-o', 'fa fa-map-pin', 'fa fa-map-signs', 'fa fa-mars', 'fa fa-mars-double', 'fa fa-mars-stroke', 'fa fa-mars-stroke-h', 'fa fa-mars-stroke-v', 'fa fa-maxcdn', 'fa fa-meanpath', 'fa fa-medium', 'fa fa-medkit', 'fa fa-meh-o', 'fa fa-mercury', 'fa fa-microphone', 'fa fa-microphone-slash', 'fa fa-minus', 'fa fa-minus-circle', 'fa fa-minus-square', 'fa fa-minus-square-o', 'fa fa-mixcloud', 'fa fa-mobile', 'fa fa-mobile-phone', 'fa fa-modx', 'fa fa-money', 'fa fa-moon-o', 'fa fa-mortar-board', 'fa fa-motorcycle', 'fa fa-mouse-pointer', 'fa fa-music', 'fa fa-navicon', 'fa fa-neuter', 'fa fa-newspaper-o', 'fa fa-object-group', 'fa fa-object-ungroup', 'fa fa-odnoklassniki', 'fa fa-odnoklassniki-square', 'fa fa-opencart', 'fa fa-openid', 'fa fa-opera', 'fa fa-optin-monster', 'fa fa-outdent', 'fa fa-pagelines', 'fa fa-paint-brush', 'fa fa-paper-plane', 'fa fa-paper-plane-o', 'fa fa-paperclip', 'fa fa-paragraph', 'fa fa-paste', 'fa fa-pause', 'fa fa-pause-circle', 'fa fa-pause-circle-o', 'fa fa-paw', 'fa fa-paypal', 'fa fa-pencil', 'fa fa-pencil-square', 'fa fa-pencil-square-o', 'fa fa-percent', 'fa fa-phone', 'fa fa-phone-square', 'fa fa-photo', 'fa fa-picture-o', 'fa fa-pie-chart', 'fa fa-pied-piper', 'fa fa-pied-piper-alt', 'fa fa-pied-piper-pp', 'fa fa-pinterest', 'fa fa-pinterest-p', 'fa fa-pinterest-square', 'fa fa-plane', 'fa fa-play', 'fa fa-play-circle', 'fa fa-play-circle-o', 'fa fa-plug', 'fa fa-plus', 'fa fa-plus-circle', 'fa fa-plus-square', 'fa fa-plus-square-o', 'fa fa-power-off', 'fa fa-print', 'fa fa-product-hunt', 'fa fa-puzzle-piece', 'fa fa-qq', 'fa fa-qrcode', 'fa fa-question', 'fa fa-question-circle', 'fa fa-question-circle-o', 'fa fa-quote-left', 'fa fa-quote-right', 'fa fa-ra', 'fa fa-random', 'fa fa-rebel', 'fa fa-recycle', 'fa fa-reddit', 'fa fa-reddit-alien', 'fa fa-reddit-square', 'fa fa-refresh', 'fa fa-registered', 'fa fa-remove', 'fa fa-renren', 'fa fa-reorder', 'fa fa-repeat', 'fa fa-reply', 'fa fa-reply-all', 'fa fa-resistance', 'fa fa-retweet', 'fa fa-rmb', 'fa fa-road', 'fa fa-rocket', 'fa fa-rotate-left', 'fa fa-rotate-right', 'fa fa-rouble', 'fa fa-rss', 'fa fa-rss-square', 'fa fa-rub', 'fa fa-ruble', 'fa fa-rupee', 'fa fa-safari', 'fa fa-save', 'fa fa-scissors', 'fa fa-scribd', 'fa fa-search', 'fa fa-search-minus', 'fa fa-search-plus', 'fa fa-sellsy', 'fa fa-send', 'fa fa-send-o', 'fa fa-server', 'fa fa-share', 'fa fa-share-alt', 'fa fa-share-alt-square', 'fa fa-share-square', 'fa fa-share-square-o', 'fa fa-shekel', 'fa fa-sheqel', 'fa fa-shield', 'fa fa-ship', 'fa fa-shirtsinbulk', 'fa fa-shopping-bag', 'fa fa-shopping-basket', 'fa fa-shopping-cart', 'fa fa-sign-in', 'fa fa-sign-language', 'fa fa-sign-out', 'fa fa-signal', 'fa fa-signing', 'fa fa-simplybuilt', 'fa fa-sitemap', 'fa fa-skyatlas', 'fa fa-skype', 'fa fa-slack', 'fa fa-sliders', 'fa fa-slideshare', 'fa fa-smile-o', 'fa fa-snapchat', 'fa fa-snapchat-ghost', 'fa fa-snapchat-square', 'fa fa-soccer-ball-o', 'fa fa-sort', 'fa fa-sort-alpha-asc', 'fa fa-sort-alpha-desc', 'fa fa-sort-amount-asc', 'fa fa-sort-amount-desc', 'fa fa-sort-asc', 'fa fa-sort-desc', 'fa fa-sort-down', 'fa fa-sort-numeric-asc', 'fa fa-sort-numeric-desc', 'fa fa-sort-up', 'fa fa-soundcloud', 'fa fa-space-shuttle', 'fa fa-spinner', 'fa fa-spoon', 'fa fa-spotify', 'fa fa-square', 'fa fa-square-o', 'fa fa-stack-exchange', 'fa fa-stack-overflow', 'fa fa-star', 'fa fa-star-half', 'fa fa-star-half-empty', 'fa fa-star-half-full', 'fa fa-star-half-o', 'fa fa-star-o', 'fa fa-steam', 'fa fa-steam-square', 'fa fa-step-backward', 'fa fa-step-forward', 'fa fa-stethoscope', 'fa fa-sticky-note', 'fa fa-sticky-note-o', 'fa fa-stop', 'fa fa-stop-circle', 'fa fa-stop-circle-o', 'fa fa-street-view', 'fa fa-strikethrough', 'fa fa-stumbleupon', 'fa fa-stumbleupon-circle', 'fa fa-subscript', 'fa fa-subway', 'fa fa-suitcase', 'fa fa-sun-o', 'fa fa-superscript', 'fa fa-support', 'fa fa-table', 'fa fa-tablet', 'fa fa-tachometer', 'fa fa-tag', 'fa fa-tags', 'fa fa-tasks', 'fa fa-taxi', 'fa fa-television', 'fa fa-tencent-weibo', 'fa fa-terminal', 'fa fa-text-height', 'fa fa-text-width', 'fa fa-th', 'fa fa-th-large', 'fa fa-th-list', 'fa fa-themeisle', 'fa fa-thumb-tack', 'fa fa-thumbs-down', 'fa fa-thumbs-o-down', 'fa fa-thumbs-o-up', 'fa fa-thumbs-up', 'fa fa-ticket', 'fa fa-times', 'fa fa-times-circle', 'fa fa-times-circle-o', 'fa fa-tint', 'fa fa-toggle-down', 'fa fa-toggle-left', 'fa fa-toggle-off', 'fa fa-toggle-on', 'fa fa-toggle-right', 'fa fa-toggle-up', 'fa fa-trademark', 'fa fa-train', 'fa fa-transgender', 'fa fa-transgender-alt', 'fa fa-trash', 'fa fa-trash-o', 'fa fa-tree', 'fa fa-trello', 'fa fa-tripadvisor', 'fa fa-trophy', 'fa fa-truck', 'fa fa-try', 'fa fa-tty', 'fa fa-tumblr', 'fa fa-tumblr-square', 'fa fa-turkish-lira', 'fa fa-tv', 'fa fa-twitch', 'fa fa-twitter', 'fa fa-twitter-square', 'fa fa-umbrella', 'fa fa-underline', 'fa fa-undo', 'fa fa-universal-access', 'fa fa-university', 'fa fa-unlink', 'fa fa-unlock', 'fa fa-unlock-alt', 'fa fa-unsorted', 'fa fa-upload', 'fa fa-usb', 'fa fa-usd', 'fa fa-user', 'fa fa-user-md', 'fa fa-user-plus', 'fa fa-user-secret', 'fa fa-user-times', 'fa fa-users', 'fa fa-venus', 'fa fa-venus-double', 'fa fa-venus-mars', 'fa fa-viacoin', 'fa fa-viadeo', 'fa fa-viadeo-square', 'fa fa-video-camera', 'fa fa-vimeo', 'fa fa-vimeo-square', 'fa fa-vine', 'fa fa-vk', 'fa fa-volume-control-phone', 'fa fa-volume-down', 'fa fa-volume-off', 'fa fa-volume-up', 'fa fa-warning', 'fa fa-wechat', 'fa fa-weibo', 'fa fa-weixin', 'fa fa-whatsapp', 'fa fa-wheelchair', 'fa fa-wheelchair-alt', 'fa fa-wheelchair-alt', 'fa fa-wifi', 'fa fa-wikipedia-w', 'fa fa-windows', 'fa fa-won', 'fa fa-wordpress', 'fa fa-wpbeginner', 'fa fa-wpforms', 'fa fa-wrench', 'fa fa-xing', 'fa fa-xing-square', 'fa fa-y-combinator', 'fa fa-y-combinator-square', 'fa fa-yahoo', 'fa fa-yc', 'fa fa-yc-square', 'fa fa-yelp', 'fa fa-yen', 'fa fa-yoast', 'fa fa-youtube', 'fa fa-youtube-play', 'fa fa-youtube-square', 'fa fa-adjust' );
			return $font_awesome;
		}

	}

	/**
	 * Generate the HTML code for the icon picker.
	 *
	 * @since 1.0
	 * @access public
	 * @param string $iconset Name of iconset to load. Defaults to Font Awesome.
	 * @param string $selected The currently selected icon to highlight upon load.
	 * @return string HTML code for the icon picker.
	 */
	public function generate_html( $iconset = false, $selected = false ) {

		// Select default iconset
		if( $iconset == false ) {
			$iconset = $this->get_iconset();
		} else {
			$iconset = $this->get_iconset( $iconset );
		}

		ob_start(); ?>

			<div class="icon-picker-parent">
				<div class="icon-picker option-tree">
					<div class="icon-list">

						<?php foreach( $iconset as $icon ) : ?>
							<div class="item <?php echo ( $icon == $selected ? 'selected' : '' ); ?>" data-code="<?php echo $icon; ?>">
								<div class="item-wrapper">
									<div class="item-image">
										<i class="<?php echo $icon; ?>"></i>
									</div>
								</div>
							</div>
						<?php endforeach; ?>

					</div>

					<div class="show-more">
						<div class="background"></div>
						<button type="button" class="button show-more-button"><?php _e( 'Show all icons', 'healthmedical-wpl' ); ?></button>
					</div>
				</div>
			</div>

		<?php
		return ob_get_clean();

	}

	/**
	 * Build the icon picker as an OptionTree option type.
	 *
	 * @since 1.0
	 * @access public
	 * @param string $args Arguments passed from OptionTree.
	 */
	public function generate_ot_html( $args = array() ) {

		/* turns arguments array into variables */
		extract( $args );
		
		/* verify a description */
		$has_desc = $field_desc ? true : false;
		
		?>

		<div class="icon-picker-parent">

			<div class="format-setting type-wplook_icon_picker <?php echo ( $has_desc ? 'has-desc' : 'no-desc' ); ?>">
				<?php if( $has_desc ) : ?>
					<div class="description"><?php echo htmlspecialchars_decode( $field_desc ); ?></div>
				<?php endif; ?>
				
				<div class="format-setting-inner">
					<input type="text" name="<?php echo esc_attr( $field_name ); ?>" id="<?php echo esc_attr( $field_id ); ?>" value="<?php echo esc_attr( $field_value ); ?>" class="widefat option-tree-ui-input icon-picker-input <?php echo esc_attr( $field_class ); ?>" />

					<?php
						global $wplook_icon_picker;
						echo $wplook_icon_picker->generate_html( 'font-awesome', $field_value );
					?>
				</div>
			</div>

		</div>

	<?php }

	/**
	 * Filter the option types list to add the icon picker to it.
	 *
	 * @since 1.0
	 * @access public
	 * @param array $types List of option types slugs and names.
	 * @return array List of option types slugs and names.
	 */
	public function register_ot_option_type( $types ) {

		$types['wplook_icon_picker'] = __( 'Icon Picker', 'healthmedical-wpl' );

		return $types;

	}

}

global $wplook_icon_picker;
$wplook_icon_picker = new WPlook_Icon_Picker;

/**
 * Create the function which links the class to OptionTree.
 * OptionTree option types cannot be called from classes, and
 * their function names must begin with ot_type_, so this is
 * what this function provides.
 */
if( !function_exists( 'ot_type_wplook_icon_picker' ) ) {

	function ot_type_wplook_icon_picker( $args = array() ) {

		global $wplook_icon_picker;
		$wplook_icon_picker->generate_ot_html( $args );

	}

}

?>
