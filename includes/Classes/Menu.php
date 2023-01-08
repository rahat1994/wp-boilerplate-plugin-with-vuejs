<?php

namespace reventz\Classes;

class Menu
{
    public function register()
    {
        add_action('admin_menu', array($this, 'addMenus'));
    }

    public function addMenus()
    {
        $menuPermission = AccessControl::hasTopLevelMenuPermission();
        if (!$menuPermission) {
            return;
        }

        $title = __('reventz', 'textdomain');
        global $submenu;
        add_menu_page(
            $title,
            $title,
            $menuPermission,
            'reventz.php',
            array($this, 'enqueueAssets'),
            'dashicons-admin-site',
            25
        );

        $submenu['reventz.php']['my_profile'] = array(
            __('Plugin Dashboard', 'textdomain'),
            $menuPermission,
            'admin.php?page=reventz.php#/',
        );
        $submenu['reventz.php']['settings'] = array(
            __('Settings', 'textdomain'),
            $menuPermission,
            'admin.php?page=reventz.php#/settings',
        );
        $submenu['reventz.php']['supports'] = array(
            __('Supports', 'textdomain'),
            $menuPermission,
            'admin.php?page=reventz.php#/supports',
        );
    }

    public function enqueueAssets()
    {
        do_action('reventz/render_admin_app');
        wp_enqueue_script(
            'reventz_boot',
            REVENTZ_URL . 'assets/js/boot.js',
            array('jquery'),
            REVENTZ_VERSION,
            true
        );

        // 3rd party developers can now add their scripts here
        do_action('reventz/booting_admin_app');
        wp_enqueue_script(
            'reventz_js',
            REVENTZ_URL . 'assets/js/plugin-main-js-file.js',
            array('reventz_boot'),
            REVENTZ_VERSION,
            true
        );

        //enque css file
        wp_enqueue_style('reventz_admin_css', REVENTZ_URL . 'assets/css/element.css');

        $reventzAdminVars = apply_filters('reventz/admin_app_vars', array(
            //'image_upload_url' => admin_url('admin-ajax.php?action=wpf_global_settings_handler&route=wpf_upload_image'),
            'assets_url' => REVENTZ_URL . 'assets/',
            'ajaxurl' => admin_url('admin-ajax.php')
        ));

        wp_localize_script('reventz_boot', 'reventzAdmin', $reventzAdminVars);
    }
}
