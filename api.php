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
    $table = $wpdb->prefix . 'cappers_corner_chat_channels';
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
    $table = $wpdb->prefix . 'cappers_corner_chat_replies';
    $replies = $wpdb->get_results("
        select * from " . $table . " where " . $table . ".enabled = true and " . $table . ".created_at between '" . $past . "' and '" . $now . "' and " . $table . ".channel = " . $_POST['channel'],
        OBJECT
    );
    // select users.id, SUM(profiles.language) as something from users join profiles on users.id = profiles.user_id group by users.id, profiles.language
    // select users.id, SUM(profiles.language) as something from users join profiles on users.id = profiles.user_id group by users.id, profiles.language
    foreach ($replies as $reply) {
        $reply->image = get_avatar_url($reply->user_id);
        $reply->user = get_userdata($reply->user_id)->user_login;
        if (is_null($reply->user_id)) {
            $reply->user = 'Unknown';
            $reply->reply = '<span class="uk-text-muted">This reply has been deleted</span>';
        }
	    $reply->created_at = date('U', strtotime($reply->created_at));
        $likes = $wpdb->get_results("
            select * from " . $wpdb->prefix . "cappers_corner_chat_votes where reply_id = " . $reply->id,
            OBJECT
        );
        $reply->likes = count($likes);
        /*
        // Check if sticker
        if (preg_match('/sticker:/i', $reply->reply)) {
            $sticker = substr($reply->reply, strpos($reply->reply, ':') + 1);
            $reply->reply = '<img src="' . plugins_url() . '/cappers-corner-chat/images/stickers/' . $sticker . '.png" class="uk-margin-small-top" style="width: 100px;">';
        }
        */
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
	$table = $wpdb->prefix . 'cappers_corner_chat_replies';
	$reply = [
		'reply' => stripslashes($reply),
		'channel' => $_POST['channel'],
		'enabled' => true,
		'user_id' => get_current_user_id(),
	];
	$wpdb->insert($table, $reply);
	wp_die();
}

// Replies Like

add_action('wp_ajax_repliesLike', 'repliesLike');
function repliesLike() {
	global $wpdb;
	$table = $wpdb->prefix . 'cappers_corner_chat_votes';
	$votes = $wpdb->get_results("select * from " . $table . " where user_id = " . get_current_user_id() . " and reply_id = " . $_POST['reply'] . " limit 1", OBJECT);
	if (is_null($votes[0])) {
        $vote = [
            'vote' => 1,
            'reply_id' => $_POST['reply'],
            'user_id' => get_current_user_id(),
        ];
        $wpdb->insert($table, $vote);
        echo 1;
    }
	if ($votes[0]->vote == -1) {
        $wpdb->update($table, [
            'vote' => 1,
        ],
            [
                'reply_id' => $_POST['reply'],
                'user_id' => get_current_user_id(),
            ]);
        echo 2;
    }
	if ($votes[0]->vote == 1) {
        echo 0;
    }
	wp_die();
}

// Replies Dislike

add_action('wp_ajax_repliesDislike', 'repliesDislike');
function repliesDislike() {
    global $wpdb;
    $table = $wpdb->prefix . 'cappers_corner_chat_votes';
    $votes = $wpdb->get_results("select * from " . $table . " where user_id = " . get_current_user_id() . " and reply_id = " . $_POST['reply'] . " limit 1", OBJECT);
    if (is_null($votes[0])) {
        $vote = [
            'vote' => -1,
            'reply_id' => $_POST['reply'],
            'user_id' => get_current_user_id(),
        ];
        $wpdb->insert($table, $vote);
        echo -1;
    }
    if ($votes[0]->vote == 1) {
        $wpdb->update($table, [
            'vote' => -1,
        ],
            [
                'reply_id' => $_POST['reply'],
                'user_id' => get_current_user_id(),
            ]);
        echo -2;
    }
    if ($votes[0]->vote == -1) {
        echo 0;
    }
    wp_die();
}

// Replies Image

add_action('wp_ajax_repliesImage', 'repliesImage');
function repliesImage() {
    $image = $_FILES['chat-image'];
    // Checks
    if (empty($image)) : wp_die('No file is selected.'); endif;
    if($image['size'] > wp_max_upload_size()) : wp_die('That picture is too big.'); endif;
    $mimeTypes = [
        'image/png',
        'image/jpeg',
        'image/gif',
        'image/svg+xml',
    ];
    if(!in_array(mime_content_type($image['tmp_name'] ), $mimeTypes)) : wp_die('That file type is not allowed.'); endif;
    // Save Image
    $file = uniqid('chat-image-') . '-' . $image['name'];
    move_uploaded_file($image['tmp_name'], wp_upload_dir()['path'] . '/' . $file);
    // Save Reply with Image
    global $wpdb;
    $table = $wpdb->prefix . 'cappers_corner_chat_replies';
    $reply = [
        'reply' => '<img src="' . wp_upload_dir()['url'] . '/' . $file . '" class="uk-width-2-3@m">',
        'channel' => $_POST['channel'],
        'enabled' => true,
        'user_id' => get_current_user_id(),
    ];
    $wpdb->insert($table, $reply);
    wp_die();
}
