let selectedChannel = undefined;

jQuery(document).ready(function() {
    loadChannels();
    // Check for new replies repeatedly
    setInterval(() => {
        loadReplies()
    }, 50000);
});

// Load and render channels

function loadChannels() {
    jQuery.post(Obj.url, {
        'action': 'ChannelsIndex',
    }, function (response) {
        let channels = JSON.parse(response);
        if (channels.length > 0) {
            jQuery('#heading').text('Locks of the Week'); // Unhide the heading
            jQuery('#channels').show(); // Unhide the heading
        }
        let selected = false;
        channels.map((channel, key) => {
            if (eval(channel.main)) {
                selected = 'selected';
                selectedChannel = channel.id
            } else {
                selected = ''
            }
            jQuery('#channels').append('<option value="' + channel.id + '" ' + selected +'>' + channel.name + '</option>');
        });
        loadReplies() // for the first time
    });
}

jQuery('#channels').change(function() {
    selectedChannel = jQuery('#channels').val();
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
                    reply.created_at +
                    '</time></div><div class="message">' +
                    reply.reply +
                    '</div></div></div>'
                );
            });
        } else {
            jQuery('#replies').html('<div class="uk-position-center --welcome">It\s empty in here. Try writing a comment.</div>');
        }
        // Scroll to Bottom of Chats After Loaded
        let element = document.getElementById('replies-body');
        element.scrollTop = element.scrollHeight;
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
        loadReplies();
    });
});



    

