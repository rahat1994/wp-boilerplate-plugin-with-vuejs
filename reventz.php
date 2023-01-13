<?php

/*
Plugin Name: reventz
Plugin URI: #
Description: A WordPress boilerplate plugin with Vue js.
Version: 1.0.0
Author: #
Author URI: #
License: GPL2
Text Domain: textdomain
*/


/**
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 *
 * Copyright 2019 Plugin Name LLC. All rights reserved.
 */

defined( 'ABSPATH' ) || exit();

final class Reventz{

    /**
	 * The single instance of the class.
	 *
	 * @since 1.0.0
	 * @var Reventz
	 */
	protected static $instance = null;

    /**
	 * Main Reventz Instance.
	 *
	 * Ensures only one instance of Reventz is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @return Reventz - Main instance.
	 * @see reventz()
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

    /**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.2
	 * @return void
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, 'UnAuthorised action!', '1.0.0' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.2
	 * @return void
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, 'UnAuthorised action!', '1.0.0' );
	}

    /**
	 * Reventz constructor.
    */
	public function __construct() {
		$this->define_constants();
		$this->init_hooks();
	}

    /**
	 * define all required constants
	 *
	 * since 1.0.0
	 *
	 * @return void
	 */
	public function define_constants() {
		$upload_dir = wp_upload_dir( null, false );
        define('REVENTZ_VERSION_LITE', true);
        define('REVENTZ_VERSION', '1.1.0');
        define('REVENTZ_MAIN_FILE', __FILE__);
        define('REVENTZ_URL', plugin_dir_url(__FILE__));
        define('REVENTZ_DIR', plugin_dir_path(__FILE__));
        define('REVENTZ_UPLOAD_DIR', '/reventz');
        define( 'REVENTZ_LOG_DIR', $upload_dir['basedir'] . '/reventz-logs/' );
	}

    /**
	 * Hook into actions and filters.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function init_hooks() {
        register_activation_hook(__FILE__, function ($newWorkWide) {
            require_once(REVENTZ_DIR . 'includes/Classes/Activator.php');
            $activator = new \reventz\Classes\Activator();
            $activator->migrateDatabases($newWorkWide);
        });
    
        // disabled admin-notice on dashboard
        add_action('admin_init', function () {
            $disablePages = [
                'reventz.php',
            ];
            if (isset($_GET['page']) && in_array($_GET['page'], $disablePages)) {
                remove_all_actions('admin_notices');
            }
        });

		register_shutdown_function( array( $this, 'log_errors' ) );
		add_action( 'plugins_loaded', array( $this, 'on_plugins_loaded' ), - 1 );
	}

    /**
	 * Hook into actions and filters.
	 *
	 * @since 1.0.0
	 * @return void
	 */
    public function on_plugins_loaded()
    {
        if (is_admin()) {
            $this->adminHooks();
        }
    }

    public function adminHooks()
    {
        require REVENTZ_DIR . 'includes/autoload.php';

        //Register Admin menu
        $menu = new \reventz\Classes\Menu();
        $menu->register();

        // Top Level Ajax Handlers
        $ajaxHandler = new \reventz\Classes\AdminAjaxHandler();
        $ajaxHandler->registerEndpoints();

        add_action('reventz/render_admin_app', function () {
            $adminApp = new \reventz\Classes\AdminApp();
            $adminApp->bootView();
        });
    }

    /**
	 * Ensures fatal errors are logged so they can be picked up in the status report.
	 *
	 * @since 1.0.2
	 * @return void
	 */
	public function log_errors() {
		//  write error logging function
	}
}

/**
 * Returns the main instance of Plugin.
 *
 * @since  1.0.0
 * @return Reventz
 */
function reventz() {
	return Reventz::instance();

    if (defined('REVENTZ_VERSION')) {
        add_action('admin_init', function () {
            deactivate_plugins(plugin_basename(__FILE__));
        });
    }
}

reventz();


