<?php

/*
Plugin Name: reventz
Plugin URI: #
Description: A WordPress boilerplate plugin with Vue js.
Version: 1.0.0
Author: #
Author URI: #
License: A "Slug" license name e.g. GPL2
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

if (!defined('ABSPATH')) {
    exit;
}
if (!defined('REVENTZ_VERSION')) {
    define('REVENTZ_VERSION_LITE', true);
    define('REVENTZ_VERSION', '1.1.0');
    define('REVENTZ_MAIN_FILE', __FILE__);
    define('REVENTZ_URL', plugin_dir_url(__FILE__));
    define('REVENTZ_DIR', plugin_dir_path(__FILE__));
    define('REVENTZ_UPLOAD_DIR', '/reventz');

    class reventz
    {
        public function boot()
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

        public function textDomain()
        {
            load_plugin_textdomain('reventz', false, basename(dirname(__FILE__)) . '/languages');
        }
    }

    add_action('plugins_loaded', function () {
        (new reventz())->boot();
    });

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
} else {
    add_action('admin_init', function () {
        deactivate_plugins(plugin_basename(__FILE__));
    });
}
