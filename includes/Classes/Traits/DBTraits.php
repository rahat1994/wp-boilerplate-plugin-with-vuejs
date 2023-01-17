<?php

namespace reventz\Classes\Traits;
trait DBTraits
{    
    private static function runSQL($sql, $tableName)
    {
        global $wpdb;
        if ($wpdb->get_var("SHOW TABLES LIKE '$tableName'") != $tableName) {
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            return dbDelta($sql);
        }
    }
}