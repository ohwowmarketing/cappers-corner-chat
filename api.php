<?php

/********************/
/* REPLIES AJAX API */
/********************/

// Channels Index

add_action('wp_ajax_ChannelsIndex', 'ChannelsIndex');
add_action('wp_ajax_nopriv_ChannelsIndex', 'ChannelsIndex'); // For non-authenticated users
function ChannelsIndex() {
    global $wpdb;
    // Get all enabled channels as JSON
    $table = $wpdb->prefix . 'cappers_corner_channels';
    $channels = $wpdb->get_results("select * from " . $table . " where enabled = true", OBJECT);
    echo json_encode($channels);
    wp_die();
}

// Replies Index

add_action('wp_ajax_repliesIndex', 'repliesIndex');
add_action('wp_ajax_nopriv_repliesIndex', 'repliesIndex'); // For non-authenticated users
function repliesIndex() {
    $now = new DateTime();
    $now = $now->format('Y-m-d H:i:s');
    $past = new DateTime();
    $past = $past->sub(new DateInterval('P7D'));
    $past = $past->format('Y-m-d H:i:s');
    global $wpdb;
    // Get all enabled replies from default channel as JSON
    $table = $wpdb->prefix . 'cappers_corner_replies';
    $replies = $wpdb->get_results("select * from " . $table . " where enabled = true and created_at between '" . $past . "' and '" . $now . "' and channel = " . $_POST['channel'], OBJECT);
    foreach ($replies as $reply) {
        $reply->image = get_avatar_url($reply->user);
        $reply->user = get_userdata($reply->user)->user_login;
        if (is_null($reply->user)) {
            $reply->user = 'Unknown';
            $reply->reply = '<span class="uk-text-muted">This reply has been deleted</span>';
        }
	    $reply->created_at = date('U', strtotime($reply->created_at));
        // Check if sticker
        if (preg_match('/sticker:/i', $reply->reply)) {
            $sticker = substr($reply->reply, strpos($reply->reply, ':') + 1);
            $reply->reply = '<img src="' . plugins_url() . '/cappers-corner-chat/images/stickers/' . $sticker . '.png" class="uk-margin-small-top" style="width: 100px;">';
        }
    }
    echo json_encode($replies);
    wp_die();
}

// Replies Store

add_action('wp_ajax_repliesStore', 'repliesStore');
function repliesStore() {
    // Filter out harmful words
    include 'dictionary.php';
    $reply = preg_replace('/(^|\b|\s)('.implode('|', $dictionary).')(\b|\s|$)/i', ' *** ', $_POST['reply']);
    // Store
    global $wpdb;
    $table = $wpdb->prefix . 'cappers_corner_replies';
    $reply = [
        'reply' => stripslashes($reply),
        'channel' => $_POST['channel'],
        'user' => get_current_user_id(),
        'enabled' => true,
    ];
    $wpdb->insert($table, $reply);
    wp_die();
}

