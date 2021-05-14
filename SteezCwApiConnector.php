<?php
/**
 * Plugin Name: Steez CW API Connector
 * Plugin URI: https://steez.nl/
 * Description: Add CLI functions to talk with the Cloudways API.
 * Author: Steez Webdevelopment
 * Version: 1.0.0
 * Author URI: https://steez.nl/
 */


/**
 * SteezCwApiConnector
 *
 * @copyright Copyright © 2021 Steez Webdevelopment. All rights reserved.
 * @author    tommy@steez.nl
 */
class SteezCwApiConnector
{
	
	/**
	 * SteezCwApiConnector constructor.
	 */
	public function __construct ()
	{
		$this->bootstrap();
		add_action('cli_init', [$this, 'register_cli_commands']);
	}
	
	/**
	 * Bootstrap all the includes.
	 */
	public function bootstrap ()
	{
		require_once('inc/CwApi.php');
	}
	
	/**
	 * Registers our command when cli get's initialized.
	 * @since  1.0.0
	 * @author Scott Anderson
	 */
	public function register_cli_commands ()
	{
		WP_CLI::add_command('steez:reset_file_permissions', [$this, 'reset_file_permissions']);
	}
	
	/**
	 * @param null $args
	 * @param null $assoc_args
	 * @when before_wp_load
	 */
	public function reset_file_permissions ($args = null, $assoc_args = null)
	{
		if (defined('WP_CLOUDWAYS_EMAIL') && defined('WP_CLOUDWAYS_API_KEY')) {
			$cw_api = new CwApi(WP_CLOUDWAYS_EMAIL, WP_CLOUDWAYS_API_KEY);
			$acces_token_object = $cw_api->call_cloudways_api('POST', '/oauth/access_token', null, [
				'email' => WP_CLOUDWAYS_EMAIL,
				'api_key' => WP_CLOUDWAYS_API_KEY
			]);
			if (!empty($acces_token_object)) {
				WP_CLI::line('Access token acquired, starting reset process...');
				if (defined('WP_CLOUDWAYS_SERVER_ID') && defined('WP_CLOUDWAYS_APP_ID')) {
					WP_CLI::line(sprintf('Resetting permissions for server %s and app %s', WP_CLOUDWAYS_SERVER_ID, WP_CLOUDWAYS_APP_ID));
					$reset_permissions = $cw_api->call_cloudways_api('POST', '/app/manage/reset_permissions', $acces_token_object->access_token, [
						'server_id' => WP_CLOUDWAYS_SERVER_ID,
						'app_id' => WP_CLOUDWAYS_APP_ID,
						'ownership' => 'master_user'
					]);
					if (!empty($reset_permissions->status)) {
						WP_CLI::line(sprintf('Reset the permissions with operation id %s', $reset_permissions->operation_id));
					}
				}
			}
		} else {
			WP_CLI::line('No email or API key defined in the config!');
		}
	}
}

new SteezCwApiConnector();
