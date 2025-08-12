<?php
/**
 * Yoast.
 *
 * @package NewfoldLabs\WP\Module\Activation
 */

namespace NewfoldLabs\WP\Module\Activation\Partners;

/**
 * Yoast class.
 */
class Yoast extends Partner {

	/**
	 * Initialize.
	 *
	 * @return void
	 */
	public function init() {
		add_action(
			'plugins_loaded',
			function () {
				if ( class_exists( 'WPSEO_Options' ) ) {
					add_action( 'admin_init', array( $this, 'disable_notice_and_onboarding' ) );
				}
			}
		);
	}

	/**
	 * Dismiss admin notice and disable redirect to Yoast onboarding.
	 *
	 * @return void
	 */
	public function disable_notice_and_onboarding() {
		// Dismiss admin notice
		\WPSEO_Options::set( 'dismiss_configuration_workout_notice', true );
		// Disable redirect to Yoast onboarding
		\WPSEO_Options::set( 'should_redirect_after_install_free', false );
	}
}
