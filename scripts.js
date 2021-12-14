let selectedChannel = undefined;

    loadChannels();
    // Check for new replies repeatedly
    setInterval(() => {
        loadReplies();
    }, 7500);

// Load and render channels

function loadChannels() {
    jQuery.post(Obj.url, {
        'action': 'ChannelsIndex',
    }, function (response) {
        let channels = JSON.parse(response);
        if (channels.length > 0) {
            jQuery('#channels').show(); // Unhide the heading
        }
        let selected = false;
        channels.map((channel, key) => {
            if (eval(channel.main)) {
                selected = 'selected';
                selectedChannel = channel.id;
                jQuery('#heading').text(channel.name); // Change heading
            } else {
                selected = ''
            }
            jQuery('#channels').append('<option value="' + channel.id + '">' + channel.name + '</option>');
        });
        loadReplies() // for the first time
    });
}

jQuery('#channels').change(function() {
    selectedChannel = jQuery('#channels').val();
    jQuery('#heading').text(jQuery('#channels').find('option:selected').text()); // Change heading
    loadReplies()
});

// Load and render replies

function loadReplies() {
    jQuery.post(Obj.url, {
        'action': 'repliesIndex',
        'channel': selectedChannel,
    }, function (response) {
        let replies = JSON.parse(response);
        if (replies.length > 0) {
            jQuery('#replies').html('');
            replies.map((reply, key) => {
                jQuery('#replies').append('' +
                    '<div class="ui-comment"><a class="avatar"><img class="uk-border-circle" width="42" height="42" src="' +
                    reply.image +
                    '"></a><div class="content"><a class="author">' +
                    reply.user +
                    '</a><div class="metadata"><time class="date">' +
                    moment.unix(reply.created_at).format('MMM Do, YYYY h:mm a') +
                    '</time></div><div class="message">' +
                    reply.reply +
                    '</div></div></div>'
                );
            });
        } else {
            jQuery('#replies').html('<div class="uk-position-center uk-text-center --welcome">It\'s empty in here. Try writing a comment. <small class="uk-display-block uk-text-meta">Comments are subject to site moderator\'s discretionary removal.</small></div>');
        }
        // Scroll to Bottom After Chats and Images have Loaded
        jQuery('img').each(function() {
            if(this.complete) {
                let element = document.getElementById('replies-body');
                element.scrollTop = element.scrollHeight;
            } else {
                jQuery(this).one('load', function() {
                    let element = document.getElementById('replies-body');
                    element.scrollTop = element.scrollHeight;
                })
            }
        });
    });
}

// Submit Reply via AJAX

jQuery('#replyForm').submit(function(event) {
    event.preventDefault();
    reply = jQuery('#reply').val();
    jQuery.post(Obj.url, {
        'reply': reply,
        'channel': selectedChannel,
        'action': 'repliesStore',
    }, function (response) {
        // Reset input field and focus on it
        jQuery('#reply').val('');
        jQuery('#reply').focus();
        jQuery('.emojionearea-editor').text('');
        loadReplies();
    });
});

// Stickers

function sendSticker(sticker) {
    jQuery.post(Obj.url, {
        'reply': 'sticker:' + sticker,
        'channel': selectedChannel,
        'action': 'repliesStore',
    }, function (response) {
        // Close reactions modal and focus on input field
        UIkit.modal('#stickers').hide();
        UIkit.modal('#emojis').hide();
        jQuery('#reply').focus();
        loadReplies();
    });
}

// Stickers

function sendEmoji(emoji) {
    jQuery.post(Obj.url, {
        'reply': '<span class="uk-h1">&#x' + emoji + ';</span>',
        'channel': selectedChannel,
        'action': 'repliesStore',
    }, function (response) {
        // Close reactions modal and focus on input field
        // UIkit.modal('#stickers').hide();
        // UIkit.modal('#emojis').hide();
        // UIkit.dropdown('.ui-emojis-wrapper').hide(100);
        jQuery('#reply').focus();
        loadReplies();
    });
}
