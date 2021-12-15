<?php

    global $wpdb;

    // Insert Channel
    if (isset($_POST['name'])) {
        $table = $wpdb->prefix . 'cappers_corner_chat_channels';
        $data = array(
            'name' => $_POST['name'],
            'main' => false,
            'enabled' => true,
        );
        $wpdb->insert($table, $data);
    }
    if (isset($_POST['action'])) {
        // Make a Default Channel
        if ($_POST['action'] == 'makeDefault') {
            $table = $wpdb->prefix . 'cappers_corner_chat_channels';
            $wpdb->query(
                $wpdb->prepare("
                    UPDATE " . $table . "
                    SET main = false
                ")
            );
            $wpdb->update($table, [
                'main' => true,
            ],
            [
                'id' => $_POST['id'],
            ]);
        }
        // Hide a Channel from Visitors
        if ($_POST['action'] == 'disableChannel') {
            $table = $wpdb->prefix . 'cappers_corner_chat_channels';
            $wpdb->update($table, [
                'enabled' => false,
            ],
            [
                'id' => $_POST['id'],
            ]);
        }
        // Show a Channel for Visitors
        if ($_POST['action'] == 'enableChannel') {
            $table = $wpdb->prefix . 'cappers_corner_chat_channels';
            $wpdb->update($table, [
                'enabled' => true,
            ],
            [
                'id' => $_POST['id'],
            ]);
        }
        // Hide a Reply for Visitors
        if ($_POST['action'] == 'disableReply') {
            $table = $wpdb->prefix . 'cappers_corner_chat_replies';
            $wpdb->update($table, [
                'enabled' => false,
            ],
            [
                'id' => $_POST['id'],
            ]);
        }
        // Show a Reply for Visitors
        if ($_POST['action'] == 'enableReply') {
            $table = $wpdb->prefix . 'cappers_corner_chat_replies';
            $wpdb->update($table, [
                'enabled' => true,
            ],
            [
                'id' => $_POST['id'],
            ]);
        }
    }

    // Get Data
    $channels = $wpdb->get_results("select * from " . $wpdb->prefix . "cappers_corner_chat_channels", OBJECT);
    $replies = $wpdb->get_results("select * from " . $wpdb->prefix . "cappers_corner_chat_replies", OBJECT);

?>

<div class="uk-section">
    <div class="uk-container uk-container-expand">
        <h1>Cappers Corner Chat Dashboard</h1>
        <h2>Channels</h2>
        <form action="" method="post">
            <div uk-grid>
                <div>
                    <input type="text" name="name" class="uk-input uk-form-width-large">
                </div>
                <div>
                    <input type="submit" value="Add" class="uk-button uk-button-primary">
                </div>
            </div>
        </form>
        <table class="uk-table uk-table-striped uk-table-hover">
            <thead>
            <tr>
                <th>Channel</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($channels as $channel) : ?>
                <tr>
                    <td class="uk-flex uk-flex-middle">
                        <?php echo $channel->name; ?>
                        <?php if ($channel->main) : echo '(Default)'; endif; ?>
                        <?php if ($channel->enabled) : if (!$channel->main) : ?>
                                <form action="" method="post" class="uk-inline uk-margin-left">
                                    <input type="hidden" name="action" value="makeDefault">
                                    <input type="hidden" name="id" value="<?php echo $channel->id; ?>">
                                    <input type="submit" value="Make Default" class="uk-button uk-button-text">
                                </form>
                                <form action="" method="post" class="uk-inline uk-margin-small-left">
                                    <input type="hidden" name="action" value="disableChannel">
                                    <input type="hidden" name="id" value="<?php echo $channel->id; ?>">
                                    <input type="submit" value="Disable" class="uk-button uk-button-text">
                                </form>
                        <?php endif; else: ?>
                            <form action="" method="post" class="uk-inline uk-margin-left">
                                <input type="hidden" name="action" value="enableChannel">
                                <input type="hidden" name="id" value="<?php echo $channel->id; ?>">
                                <input type="submit" value="Enable" class="uk-button uk-button-text">
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <h2>Replies</h2>
        <table class="uk-table uk-table-striped uk-table-hover">
            <thead>
            <tr>
                <th>Reply</th>
                <th>Author</th>
                <th>Date/Time</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($replies as $reply) : ?>
                <tr>
                    <td class="uk-flex uk-flex-middle">
                        <?php echo $reply->reply; ?>
                        <?php if ($reply->enabled) : ?>
                            <form action="" method="post" class="uk-inline uk-margin-left">
                                <input type="hidden" name="action" value="disableReply">
                                <input type="hidden" name="id" value="<?php echo $reply->id; ?>">
                                <input type="submit" value="Hide" class="uk-button uk-button-text">
                            </form>
                        <?php else: ?>
                            <form action="" method="post" class="uk-inline uk-margin-left">
                                <input type="hidden" name="action" value="enableReply">
                                <input type="hidden" name="id" value="<?php echo $reply->id; ?>">
                                <input type="submit" value="Enable" class="uk-button uk-button-text">
                            </form>
                        <?php endif; ?>
                   </td>
                    <td><a href="<?php echo get_edit_profile_url($reply->user_id); ?>"><?php echo get_userdata($reply->user_id)->display_name; ?></a></td>
                    <td><?php echo date('F j, Y g:i a', strtotime($reply->created_at)); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
