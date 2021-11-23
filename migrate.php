<?php

/***********************/
/* DATABASE MIGRATIONS */
/***********************/

// Migrates the two required databases upon activation of plugin

global $wpdb;
$charsetCollate = $wpdb->get_charset_collate();

$table = $wpdb->prefix . 'cappers_corner_replies';
$sql = "CREATE TABLE " . $table . " (
            id bigint NOT NULL AUTO_INCREMENT,
            reply text NOT NULL,
            channel bigint NOT NULL,
            user bigint NOT NULL,
            enabled tinyint(1),
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        )" . $charsetCollate . ";";
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
dbDelta($sql);

$table = $wpdb->prefix . 'cappers_corner_channels';
$sql = "CREATE TABLE " . $table . " (
            id bigint NOT NULL AUTO_INCREMENT,
            name text NOT NULL,
            main tinyint(1) NOT NULL,
            enabled tinyint(1),
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        )" . $charsetCollate . ";";
require_once( ABSPATH . 'wp-admin/includes/upgrade.php');
dbDelta($sql);
