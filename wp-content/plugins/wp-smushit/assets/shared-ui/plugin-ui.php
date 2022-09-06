<?php
/**
 * Provides PHP support for simple use of the WPMUDEV plugin UI.
 *
 * @package WPMUDEV_UI
 */

if ( ! class_exists( 'WDEV_Plugin_Ui' ) ) {

	/**
	 * UI class that encapsulates all module functions.
	 */
	class WDEV_Plugin_Ui {

		/**
		 * Current module version.
		 */
		const VERSION = '1.1';

		/**
		 * Internal translation container.
		 *
		 * @var array
		 */
		static protected $i10n = array();

		/**
		 * Internal storage that holds additional classes for body tag.
		 *
		 * @var array
		 */
		static protected $body_class = '';

		/**
		 * URL to this module (directory). Used to enqueue the css/js files.
		 *
		 * @var string
		 */
		static protected $module_url = '';

		/**
		 * Initializes all UI components.
		 *
		 * @since  1.0.0
		 * @internal
		 */
		static public function reset() {
			self::$i10n = array(
				'empty_search' => __( 'Nothing found', 'wpmudev' ),
				'default_msg_ok' => __( 'Okay, we saved your changes!', 'wpmudev' ),
				'default_msg_err' => __( 'Oops, we could not do this...', 'wpmudev' ),
			);
		}

		/**
		 * Enqueues the CSS and JS files needed for plugin UI
		 *
		 * @since  1.0.0
		 * @api Call this function before/in `admin_head`.
		 * @param  string $module_url URL to this module (directory).
		 * @param  string $body_class List of additional classes for the body tag.
		 */
		static public function load( $module_url, $body_class = '' ) {
			self::$module_url = trailingslashit( $module_url );
			self::$body_class = trim( $body_class );
			add_filter(
				'admin_body_class',
				array( __CLASS__, 'admin_body_class' )
			);

			if ( ! did_action( 'admin_enqueue_scripts' ) ) {
				add_action(
					'admin_enqueue_scripts',
					array( __CLASS__, 'enqueue' )
				);
			} else {
				self::enqueue();
			}
		}

		/**
		 * Enqueues the CSS and JS files.
		 *
		 * @since  1.0.0
		 * @internal Do not call this method manually. It's called by `load()`!
		 */
		static public function enqueue() {
			wp_enqueue_style(
				'wdev-plugin-google_fonts',
				'https://fonts.googleapis.com/css?family=Roboto+Condensed:400,700|Roboto:400,500,300,300italic',
				false,
				self::VERSION
			);

			wp_enqueue_style(
				'wdev-plugin-ui',
				self::$module_url . 'wdev-ui.css',
				array( 'wdev-plugin-google_fonts' ),
				self::VERSION
			);

			wp_enqueue_script(
				'wdev-plugin-ui',
				self::$module_url . 'wdev-ui.js',
				array( 'jquery' ),
				self::VERSION
			);
		}

		/**
		 * Adds the page-specific class to the admin page body tag.
		 *
		 * @since  1.0.0
		 * @internal Action hook
		 * @param  string $classes List of CSS classes of the body tag.
		 * @return string Updated list of CSS classes.
		 */
		static public function admin_body_class( $classes ) {
			$classes .= ' wpmud';
			if ( self::$body_class ) {
				$classes .= ' ' . self::$body_class;
			}
			$classes .= ' ';

			return $classes;
		}

		/**
		 * Sets a translation from javascript.
		 *
		 * @since  1.0.0
		 * @api Use this before calling `output_header()`.
		 * @param  string $key The translation key (used in javascript).
		 * @param  string $value Human readable text.
		 */
		static public function translate( $key, $value ) {
			self::$i10n[ $key ] = (string) $value;
		}

		/**
		 * Outputs code in the page header.
		 *
		 * This function must be called by the plugin!
		 * It's not important if it's in the header or in the footer of the page,
		 * but in top/header is recommended.
		 *
		 * @since  1.0.0
		 * @api Call this function somewhere after output started.
		 * @param  array $commands Optinal list of additional JS commands that
		 *               are executed when page loaded.
		 */
		static public function output( $commands = array() ) {
			$data = array();
			$data[] = 'window.WDP = window.WDP || {}';
			$data[] = 'WDP.data = WDP.data || {}';
			$data[] = 'WDP.data.site_url = ' . json_encode( get_site_url() );
			$data[] = 'WDP.lang = ' . json_encode( self::$i10n );

			// Add custom JS commands to the init-code.
			if ( is_array( $commands ) ) {
				$data = array_merge( $data, $commands );
			}

			/**
			 * Display a custom success message on the WPMU Dashboard pages.
			 *
			 * @var string|array The message to display.
			 *      Array options:
			 *      'type' => [ok|err]  (default: 'ok')
			 *      'delay' => 3000     (default: 3000ms)
			 *      'message' => '...'  (required!)
			 */
			$notice = apply_filters( 'wpmudev-admin-notice', false );
			if ( $notice ) {
				$command = 'WDP';
				if ( is_array( $notice ) && ! empty( $notice['type'] ) ) {
					$command .= sprintf( '.showMessage("type", "%s")', esc_attr( $notice['type'] ) );
				}
				if ( is_array( $notice ) && ! empty( $notice['delay'] ) ) {
					$command .= sprintf( '.showMessage("delay", %s)', intval( $notice['delay'] ) );
				}
				if ( is_array( $notice ) && ! empty( $notice['message'] ) ) {
					$command .= sprintf( '.showMessage("message", "%s")', esc_html( $notice['message'] ) );
				} elseif ( is_string( $notice ) ) {
					$command .= sprintf( '.showMessage("message", "%s")', esc_html( $notice ) );
				}
				$command .= '.showMessage("show")';
				$data[] = $command;
			}

			foreach ( $data as $item ) {
				printf(
					"<script>;jQuery(function(){%s;});</script>\n",
					// @codingStandardsIgnoreStart: This is javascript code, no escaping!
					$item
					// @codingStandardsIgnoreEnd
				);
			}
		}

		/**
		 * Output the HTML code to display the notification.
		 *
		 * @since  1.0.0
		 * @param  string $module_url URL to this module (directory).
		 * @param  array  $msg The message details.
		 *                id .. Required, can be any valid class-name.
		 *                content .. Required, can contain HTML.
		 *                dismissed .. Optional. If true then no message is output.
		 *                can_dismiss .. Optional. If true a Dismiss button is added.
		 *                cta .. Optional. Can be HTML code of a button/link.
		 */
		static public function render_dev_notification( $module_url, $msg ) {
			if ( ! is_array( $msg ) ) { return; }
			if ( ! isset( $msg['id'] ) ) { return; }
			if ( empty( $msg['content'] ) ) { return; }
			if ( $msg['dismissed'] ) { return; }

			$css_url = $module_url . 'notice.css';
			$js_url = $module_url . 'notice.js';

			if ( empty( $msg['id'] ) ) {
				$msg_dismiss = '';
			} else {
				$msg_dismiss = __( 'Saving', 'wpmudev' );
			}

			$show_actions = $msg['can_dismiss'] || $msg['cta'];

			$allowed = array(
				'a' => array( 'href' => array(), 'title' => array(), 'target' => array(), 'class' => array() ),
				'br' => array(),
				'hr' => array(),
				'em' => array(),
				'i' => array(),
				'strong' => array(),
				'b' => array(),
			);

			?>
			<link rel="stylesheet" type="text/css" href="<?php echo esc_url( $css_url ); ?>" />
			<div class="notice frash-notice" style="display:none">
				<input type="hidden" name="msg_id" value="<?php echo esc_attr( $msg['id'] ); ?>" />

				<div class="frash-notice-logo"><span></span></div>
					<div class="frash-notice-message">
						<?php echo wp_kses( $msg['content'], $allowed ); ?>
					</div>
					<?php if ( $show_actions ) : ?>
					<div class="frash-notice-cta">
						<?php echo wp_kses( $msg['cta'], $allowed ); ?>
						<?php if ( $msg['can_dismiss'] ) : ?>
						<button class="frash-notice-dismiss" data-msg="<?php echo esc_attr( $msg_dismiss ); ?>">
							<?php esc_html_e( 'Dismiss', 'wpmudev' ); ?>
						</button>
						<?php endif; ?>
					</div>
					<?php endif; ?>
			</div>
			<script src="<?php echo esc_url( $js_url ); ?>"></script>
			<?php
		}
	};

	// Initialize the UI.
	WDEV_Plugin_Ui::reset();
}
