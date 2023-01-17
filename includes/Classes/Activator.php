<?php

namespace reventz\Classes;

if (!defined('ABSPATH')) {
    exit;
}

use reventz\Classes\Traits\DBTraits;
/**
 * Ajax Handler Class
 * @since 1.0.0
 */
class Activator
{
    use DBTraits;
    public function migrateDatabases($network_wide = false)
    {
        global $wpdb;
        if ($network_wide) {
            // Retrieve all site IDs from this network (WordPress >= 4.6 provides easy to use functions for that).
            if (function_exists('get_sites') && function_exists('get_current_network_id')) {
                $site_ids = get_sites(array('fields' => 'ids', 'network_id' => get_current_network_id()));
            } else {
                $site_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs WHERE site_id = $wpdb->siteid;");
            }
            // Install the plugin for all these sites.
            foreach ($site_ids as $site_id) {
                switch_to_blog($site_id);
                $this->migrate();
                restore_current_blog();
            }
        } else {
            $this->migrate();
        }
    }

    public function seedDatabases($network_wide = false){
        global $wpdb;
        if ($network_wide) {
            // Retrieve all site IDs from this network (WordPress >= 4.6 provides easy to use functions for that).
            if (function_exists('get_sites') && function_exists('get_current_network_id')) {
                $site_ids = get_sites(array('fields' => 'ids', 'network_id' => get_current_network_id()));
            } else {
                $site_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs WHERE site_id = $wpdb->siteid;");
            }
            // Install the plugin for all these sites.
            foreach ($site_ids as $site_id) {
                switch_to_blog($site_id);
                $this->seed();
                restore_current_blog();
            }
        } else {
            $this->seed();
        }
    }

    private function migrate()
    {
        $this->createEventsTable();
        $this->createTicketTypesTable();
        $this->createBookingsTable();
    }

    private function seed()
    {
    }

    public function createEventsTable()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 're_events';

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            `id` int NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL,
            `is_online` tinyint(1) NOT NULL DEFAULT '0',
            `slug` varchar(255) NOT NULL DEFAULT '0',
            `description` longtext NULL,
            `social_media` json NOT NULL,
            `form_fields` json NOT NULL,
            PRIMARY KEY (`id`)
        ) $charset_collate;";

        static::runSQL($sql, $table_name);
    }
    public function createTicketTypesTable()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 're_ticket_types';

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            `id` int NOT NULL AUTO_INCREMENT,
            `event_id` int DEFAULT NULL,
            `name` varchar(255) NOT NULL,
            `limit` int DEFAULT NULL,
            `price` decimal(10,2) DEFAULT 0.0,
            `description` longtext,
            `created_at` datetime NOT NULL,
            `updated_at` datetime NOT NULL,
            PRIMARY KEY (`id`)
          ) $charset_collate;";

        static::runSQL($sql, $table_name);
    }

    public function createBookingsTable()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 're_bookings';

        $sql = "CREATE TABLE $table_name (
            `id` int NOT NULL AUTO_INCREMENT,
            `event_id` int NOT NULL,
            `ticket_type_id` int NOT NULL,
            `info` json NOT NULL,
            `payment_info` json NOT NULL,
            `created_at` timestamp NOT NULL,
            `updated_at` timestamp NOT NULL,
            PRIMARY KEY (`id`)
        ) $charset_collate;";

        static::runSQL($sql, $table_name);
    }

    public function createBookmarkTable()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'bookmarks';
        $sql = "CREATE TABLE $table_name (
                                             pl_id int(10) NOT NULL AUTO_INCREMENT,
                                             pl_name varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
                                             chart_values varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
                                             created_at timestamp NULL DEFAULT NULL,
                                             updated_at timestamp NULL DEFAULT NULL,
                                             PRIMARY KEY (chart_id)
                                            ) $charset_collate;";

        static::runSQL($sql, $table_name);
    }

    // private function runSQL($sql, $tableName)
    // {
    //     global $wpdb;
    //     if ($wpdb->get_var("SHOW TABLES LIKE '$tableName'") != $tableName) {
    //         require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    //         dbDelta($sql);
    //     }
    // }
}
