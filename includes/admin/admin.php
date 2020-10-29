<?php
/**
 * Create A Simple Theme Options Panel
 *
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Start Class
if ( ! class_exists( 'AIVP_Options' ) ) {

	class AIVP_Options {

		/**
		 * Start things up
		 *
		 * @since 1.0.0
		 */
		public function __construct() {

			// We only need to register the admin panel on the back-end
			if ( is_admin() ) {
				add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
				add_action( 'admin_init', array( $this, 'register_settings' ) );
			}

		}

		/**
		 * Returns all options
		 *
		 * @since 1.0.0
		 */
		public static function get_aivp_options() {
			return get_option( 'aivp_options' );
		}

		/**
		 * Returns single option
		 *
		 * @since 1.0.0
		 */
		public static function get_aivp_option( $id ) {
			$options = self::get_aivp_options();
			if ( isset( $options[$id] ) ) {
				return $options[$id];
			}
		}

		/**
		 * Add sub menu page
		 *
		 * @since 1.0.0
		 */
		public static function add_admin_menu() {

			// Vars.
			$slug = 'edit.php?post_type=aivp';
			$cap = 'manage_options';

			add_menu_page(
				esc_html__( 'AIVP', 'aivp' ),
				esc_html__( 'AIVP', 'aivp' ),
				'manage_options',
				$slug,
				false,
				'dashicons-format-video'
			);

			
			// Add menu items.
			add_submenu_page( $slug, __('Videos','aivp'), __('Videos','aivp'), $cap, $slug );
			add_submenu_page( $slug, __('Add New','aivp'), __('Add New','aivp'), $cap, 'post-new.php?post_type=aivp' );
			add_submenu_page('edit.php?post_type=aivp', __('AIVP Settings','aivp'), __('AIVP Settings','aivp'), 'manage_options', 'aivp-settings', array($this, 'create_admin_page'));
		}

		/**
		 * Register a setting and its sanitization callback.
		 *
		 * We are only registering 1 setting so we can store all options in a single option as
		 * an array. You could, however, register a new setting for each option
		 *
		 * @since 1.0.0
		 */
		public static function register_settings() {
			register_setting( 'aivp_options', 'aivp_options', array( $this, 'sanitize' ) );
		}

		/**
		 * Sanitization callback
		 *
		 * @since 1.0.0
		 */
		public static function sanitize( $options ) {

			// If we have options lets sanitize them
			if ( $options ) {

				// Platform
				if ( ! empty( $options['platform'] ) ) {
					$options['platform'] = $options['platform'];
				} else {
					unset( $options['platform'] ); // Remove from options if not checked
				}

				// Wistia Salesforce Endpoint
				if ( ! empty( $options['wistia_salesforce_endpoint'] ) ) {
					$options['wistia_salesforce_endpoint'] = sanitize_text_field( $options['wistia_salesforce_endpoint'] );
				} else {
					unset( $options['wistia_salesforce_endpoint'] ); // Remove from options if empty
				}

				// Wistia Access Token
				if ( ! empty( $options['wistia_access_token'] ) ) {
					$options['wistia_access_token'] = sanitize_text_field( $options['wistia_access_token'] );
				} else {
					unset( $options['wistia_access_token'] ); // Remove from options if empty
				}

				// Wistia Project ID
				if ( ! empty( $options['wistia_project_id'] ) ) {
					$options['wistia_project_id'] = sanitize_text_field( $options['wistia_project_id'] );
				} else {
					unset( $options['wistia_project_id'] ); // Remove from options if empty
				}

				// Vimeo Salesforce Endpoint
				if ( ! empty( $options['vimeo_salesforce_endpoint'] ) ) {
					$options['vimeo_salesforce_endpoint'] = sanitize_text_field( $options['vimeo_salesforce_endpoint'] );
				} else {
					unset( $options['vimeo_salesforce_endpoint'] ); // Remove from options if empty
				}

				// Vimeo Client ID
				if ( ! empty( $options['vimeo_client_id'] ) ) {
					$options['vimeo_client_id'] = sanitize_text_field( $options['vimeo_client_id'] );
				} else {
					unset( $options['vimeo_client_id'] ); // Remove from options if empty
				}
				// Vimeo Client Secret
				if ( ! empty( $options['vimeo_secret_key'] ) ) {
					$options['vimeo_secret_key'] = sanitize_text_field( $options['vimeo_secret_key'] );
				} else {
					unset( $options['vimeo_secret_key'] ); // Remove from options if empty
				}
				// Vimeo Client Token
				if ( ! empty( $options['vimeo_token'] ) ) {
					$options['vimeo_token'] = sanitize_text_field( $options['vimeo_token'] );
				} else {
					unset( $options['vimeo_token'] ); // Remove from options if empty
				}

				// Page Slug
				if ( ! empty( $options['page_slug'] ) ) {
					$options['page_slug'] = sanitize_text_field( $options['page_slug'] );
				} else {
					unset( $options['page_slug'] ); // Remove from options if empty
				}

			}

			// Return sanitized options
			return $options;

		}


		/**
		 * Settings page output
		 *
		 * @since 1.0.0
		 */
		public static function create_admin_page() { ?>

			<div class="wrap">

				<h1><?php esc_html_e( 'AIVP Settings', 'aivp' ); ?></h1>

				<form method="post" action="options.php">

					<?php settings_fields( 'aivp_options' ); ?>

					<table class="form-table wpex-custom-admin-login-table">

						<?php // Select Provider ?>
						<tr valign="top">
							<th scope="row"><?php esc_html_e( 'Choose platform', 'aivp' ); ?></th>
							<td>
								<?php $value = self::get_aivp_option( 'platform' ); ?>
								<input type="radio" name="aivp_options[platform]" id="wistia" value="wistia" <?php checked( $value, 'wistia' ); ?>> <label for="wistia"><?php esc_html_e( 'Wistia', 'aivp' ); ?></label> <br />
								<input type="radio" name="aivp_options[platform]" id="vimeo" value="vimeo" <?php checked( $value, 'vimeo' ); ?>> <label for="vimeo"><?php esc_html_e( 'Vimeo', 'aivp' ); ?></label>
							</td>
						</tr>

						<?php // Salesforce Endpoint ?>
						<?php $platform = self::get_aivp_option( 'platform' ); ?>
						<?php if( isset($platform) && $platform == 'wistia' ) : ?>
							<tr valign="top">
								<th scope="row"><?php esc_html_e( 'Wistia Salesforce Endpoint', 'aivp' ); ?></th>
								<td>
									<?php $value = self::get_aivp_option( 'wistia_salesforce_endpoint' ); ?>
									<input type="text" name="aivp_options[wistia_salesforce_endpoint]" value="<?php echo esc_attr( $value ); ?>" style="width: 50%">
									<p><em>Or use this alternative.</em></p>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><?php esc_html_e( 'Wistia API Key', 'aivp' ); ?></th>
								<td>
									<?php $value = self::get_aivp_option( 'wistia_access_token' ); ?>
									<input type="text" name="aivp_options[wistia_access_token]" value="<?php echo esc_attr( $value ); ?>" style="width: 50%">
									<p><em>If you want to get all medias on specific project. Add project hashed id below.</em></p>
									<?php $project = self::get_aivp_option( 'wistia_project_id' ); ?>
									<input type="text" name="aivp_options[wistia_project_id]" value="<?php echo esc_attr( $project ); ?>" style="width: 50%">
								</td>
							</tr>
						<?php endif; ?>
						<?php if( isset($platform) && $platform == 'vimeo' ) : ?>
							<tr valign="top">
								<th scope="row"><?php esc_html_e( 'Vimeo Salesforce Endpoint', 'aivp' ); ?></th>
								<td>
									<?php $value = self::get_aivp_option( 'vimeo_salesforce_endpoint' ); ?>
									<input type="text" name="aivp_options[vimeo_salesforce_endpoint]" value="<?php echo esc_attr( $value ); ?>" style="width: 50%">
								</td>
							</tr>

							<tr valign="top">
								<th scope="row"><?php esc_html_e( 'Or use this alternative.', 'aivp' ); ?></th>
								<td>
									<?php $client_id = self::get_aivp_option( 'vimeo_client_id' ); ?>
									<p><em>Client Id</em></p>
									<input type="text" name="aivp_options[vimeo_client_id]" value="<?php echo esc_attr( $client_id ); ?>" style="width: 50%">

									<?php $client_secret = self::get_aivp_option( 'vimeo_secret_key' ); ?>
									<p><em>Client Secret</em></p>
									<input type="text" name="aivp_options[vimeo_secret_key]" value="<?php echo esc_attr( $client_secret ); ?>" style="width: 50%">

									<?php $client_token = self::get_aivp_option( 'vimeo_token' ); ?>
									<p><em>Client Token</em></p>
									<input type="text" name="aivp_options[vimeo_token]" value="<?php echo esc_attr( $client_token ); ?>" style="width: 50%">
								</td>
							</tr>
						<?php endif; ?>

						<?php // Page Slug ?>
						<tr valign="top">
							<th scope="row"><?php esc_html_e( 'Page Slug', 'aivp' ); ?></th>
							<td>
								<?php $value = self::get_aivp_option( 'page_slug' ); ?>
								<input type="text" name="aivp_options[page_slug]" value="<?php echo esc_attr( $value ); ?>" style="width: 50%">
							</td>
						</tr>

					</table>

					<?php submit_button(); ?>

				</form>

			</div><!-- .wrap -->
		<?php }

	}
}
new AIVP_Options();

// Helper function to use in your theme to return a theme option value
function get_aivp_option( $id = '' ) {
	return AIVP_Options::get_aivp_option( $id );
}