<?php

/***********************/
/* DATABASE MIGRATIONS */
/***********************/

// Migrates the three required databases upon activation of plugin

global $wpdb;
$charsetCollate = $wpdb->get_charset_collate();

// Channels Table
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

// Replies Table
$table = $wpdb->prefix . 'cappers_corner_chat_replies';
$sql = "CREATE TABLE " . $table . " (
            id bigint NOT NULL AUTO_INCREMENT,
            reply text,
            channel bigint,
            enabled tinyint(1),
            user_id bigint,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        )" . $charsetCollate . ";";
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
dbDelta($sql);

// Votes Table
$table = $wpdb->prefix . 'cappers_corner_chat_votes';
$sql = "CREATE TABLE " . $table . " (
            id bigint NOT NULL AUTO_INCREMENT,
            vote int(11),
            reply_id bigint,
            user_id bigint,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        )" . $charsetCollate . ";";
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
dbDelta($sql);
