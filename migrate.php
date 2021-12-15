<?php

/***********************/
/* DATABASE MIGRATIONS */
/***********************/

// Migrates the two required databases upon activation of plugin

global $wpdb;
$charsetCollate = $wpdb->get_charset_collate();

$table = $wpdb->prefix . 'cappers_corner_chat_channels';
$sql = "CREATE TABLE " . $table . " (
            id bigint NOT NULL AUTO_INCREMENT,
            name text,
            main tinyint(1),
            enabled tinyint(1),
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        )" . $charsetCollate . ";";
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
dbDelta($sql);

$table = $wpdb->prefix . 'cappers_corner_chat_replies';
$sql = "CREATE TABLE " . $table . " (
            id bigint NOT NULL AUTO_INCREMENT,
            reply text,
            channel bigint,
            enabled tinyint(1),
            likes int(11),
            user_id bigint,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        )" . $charsetCollate . ";";
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
dbDelta($sql);
