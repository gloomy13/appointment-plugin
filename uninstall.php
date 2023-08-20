<?php // exit if uninstall constant is not defined
if (!defined('WP_UNINSTALL_PLUGIN')) exit;

delete_option('am_calendar_options');

global $wpdb;
$table_name = $wpdb->prefix .'am_appointments';
$wpdb->query("DROP TABLE IF EXISTS {$table_name}");