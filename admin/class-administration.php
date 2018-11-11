<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @since 0.2.0
 *
 * @package Theme_Sniffer\Admin
 */

namespace Theme_Sniffer\Admin;

use Theme_Sniffer\Includes\Config;
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package Theme_Sniffer\Admin
 */
class Administration extends Config {
	/**
	 * Add go to theme check page link on plugin page.
	 *
	 * @since 0.1.3
	 *
	 * @param  array $links Array of plugin action links.
	 * @return array Modified array of plugin action links.
	 */
	public function plugin_settings_link( $links ) {
		$settings_page_link = '<a href="themes.php?page=theme-sniffer">' . esc_attr__( 'Theme Sniffer Page', 'theme-sniffer' ) . '</a>';
		array_unshift( $links, $settings_page_link );

		return $links;
	}

	/**
	 * Register admin menu.
	 *
	 * @since 0.1.0
	 */
	public function admin_menu() {
		add_theme_page(
			esc_html__( 'Theme Sniffer', 'theme-sniffer' ),
			esc_html__( 'Theme Sniffer', 'theme-sniffer' ),
			'manage_options',
			'theme-sniffer',
			[ $this, 'render_admin_page' ]
		);
	}

	/**
	 * Callback for admin page.
	 *
	 * @since 0.1.0
	 */
	public function render_admin_page() {
		?>
		<div class="wrap theme-sniffer">
		<?php wp_nonce_field( 'theme_sniffer_nonce', 'theme_sniffer_nonce' ); ?>
			<h1 class="theme-sniffer__title"><?php esc_html_e( 'Theme Sniffer', 'theme-sniffer' ); ?></h1>
			<hr />
		<?php include_once dirname( __FILE__ ) . '/pages/sniff-page.php'; ?>
		</div>
		<?php
	}

	/**
	 * Load admin scripts and styles.
	 *
	 * @since 0.1.2
	 *
	 * @param string $hook Admin hook name.
	 */
	public function enqueue_scripts( $hook ) {
		if ( 'appearance_page_theme-sniffer' !== $hook ) {
			return;
		}

		wp_enqueue_style( static::PLUGIN_NAME . '-admin-css', plugins_url() . '/' . static::PLUGIN_NAME . '/assets/build/styles/application.css', array(), static::PLUGIN_VERSION, 'all' );
		wp_enqueue_script( static::PLUGIN_NAME . '-admin-js', plugins_url() . '/' . static::PLUGIN_NAME . '/assets/build/scripts/application.js', array(), static::PLUGIN_VERSION, false );

		wp_localize_script(
			static::PLUGIN_NAME . '-admin-js',
			'localizationObject',
			array(
				'sniffError'         => esc_html__( 'The check has failed. This could happen due to running out of memory. Either reduce the file length or increase PHP memory.', 'theme-sniffer' ),
				'percentComplete'    => esc_html__( 'Percent completed: ', 'theme-sniffer' ),
				'errorReport'        => esc_html__( 'Error', 'theme-sniffer' ),
				'ajaxStopped'        => esc_html__( 'Sniff stopped', 'theme-sniffer' ),
				'callbackIndividual' => plugins_url() . '/' . static::PLUGIN_NAME . '/admin/callbacks/individual-sniff.php',
				'callbackRunSniffer' => plugins_url() . '/' . static::PLUGIN_NAME . '/admin/callbacks/run-sniffer.php',
			)
		);
	}

	/**
	 * Allow fetching custom headers.
	 *
	 * @since 0.1.3
	 *
	 * @param array $extra_headers List of extra headers.
	 *
	 * @return array List of extra headers.
	 */
	public static function add_headers( $extra_headers ) {
		$extra_headers[] = 'License';
		$extra_headers[] = 'License URI';
		$extra_headers[] = 'Template Version';

		return $extra_headers;
	}
}