<?php
/**
 * Plugin setup.
 *
 * @package   Bandstand
 * @copyright Copyright (c) 2016, AudioTheme, LLC
 * @license   GPL-2.0+
 * @link      https://audiotheme.com/
 * @since     1.0.0
 */

/**
 * Plugin setup class.
 *
 * @package Bandstand
 * @since   1.0.0
 */
class Bandstand_Provider_Setup extends Bandstand_AbstractProvider {
	/**
	 * Register hooks.
	 *
	 * @since 1.0.0
	 */
	public function register_hooks() {
		add_action( 'wp_loaded', array( $this, 'maybe_flush_rewrite_rules' ) );
		register_activation_hook( $this->plugin->get_file(),   array( $this, 'activate' ) );
		register_deactivation_hook( $this->plugin->get_file(), array( $this, 'deactivate' ) );
	}

	/**
	 * Flush the rewrite rules if needed.
	 *
	 * @since 1.0.0
	 */
	public function maybe_flush_rewrite_rules() {
		if ( is_network_admin() || 'no' === get_option( 'bandstand_flush_rewrite_rules' ) ) {
			return;
		}

		update_option( 'bandstand_flush_rewrite_rules', 'no' );
		flush_rewrite_rules();
	}

	/**
	 * Activation routine.
	 *
	 * Occurs too late to flush rewrite rules, so set an option to flush them on
	 * the next request.
	 *
	 * @since 1.0.0
	 */
	public function activate() {
		update_option( 'bandstand_flush_rewrite_rules', 'yes' );
	}

	/**
	 * Deactivation routine.
	 *
	 * Deleting the rewrite rules option should force WordPress to regenerate
	 * them next time they're needed.
	 *
	 * @since 1.0.0
	 */
	public function deactivate() {
		delete_option( 'rewrite_rules' );
	}
}
