let selectedChannel = undefined;
jQuery('#replies-empty').removeClass('uk-hidden');

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
        const channels = JSON.parse(response);
        let css = '';
        channels.map((channel, key) => {
            if (eval(channel.main)) {
                selectedChannel = channel.id;
                css = 'uk-active';
            } else {
                css = '';
            }
            jQuery('#heading').text(channel.name);
            jQuery('#channels-list').append('<li class="' + css + '" id="channels-link-' + channel.id + '" onclick="changeChannel(' + channel.id + ',\'' + channel.name + '\')"><a>' + channel.name + '</a></li>');
        });
        loadReplies() // for the first time
    });
}

// Change active channel

function changeChannel(id, heading) {
    selectedChannel = id;
    // Update switcher
    jQuery('#channels-list li').each(function() {
        jQuery(this).removeClass('uk-active');
    })
    jQuery('#channels-link-' + id).addClass('uk-active');
    // Update heading
    jQuery('#heading').text(heading);
    // Reload replies
    loadReplies();
}

// Load and render replies

function loadReplies() {
    jQuery.post(Obj.url, {
        'action': 'repliesIndex',
        'channel': selectedChannel,
    }, function (response) {
        const replies = JSON.parse(response);
        if (replies.length !== jQuery('#replies').children().length) {
            if (replies.length > 0) {
                jQuery('#replies-empty').addClass('uk-hidden');
                jQuery('#replies').html('');
                replies.map((reply, key) => {
                    jQuery('#replies').append('' +
                        '<div class="ui-comment"><a class="avatar"><img class="uk-border-circle" width="42" height="42" src="' +
                        reply.image +
                        '"></a><div class="content"><a class="author">' +
                        reply.user +
                        '</a><div class="metadata"><time class="date">' +
                        moment.unix(reply.created_at).format('MMM D, YYYY h:mm A') +
                        '</time></div><div class="message">' +
                        reply.reply +
                        '</div><div class="uk-text-bold uk-text-small"><i class="fas fa-thumbs-up uk-margin-small-right" onclick="like(' +
                        reply.id +
                        ')"></i><i class="fas fa-thumbs-down uk-margin-small-right" onclick="dislike(' +
                        reply.id +
                        ')"></i><span id="' +
                        reply.id +
                        '-likes">' +
                        reply.likes +
                        '</span></div></div></div>'
                    );
                });
            } else {
                jQuery('#replies').html('');
                jQuery('#replies-empty').removeClass('uk-hidden');
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
        }
    });
}

// Submit Reply via AJAX
jQuery(function($emojioneArea) {
    jQuery("#reply").emojioneArea({
        shortnames: true,
        buttonTitle: "Use the TAB key to insert emoji faster"
        // standalone: true,
    });
});

jQuery(window).on('load',function() {
    jQuery('.emojionearea-editor').attr('id','emojionearea-editor');
    jQuery('.emojionearea-button-open').append('<span uk-icon="icon: happy"></span>');
    jQuery('.emojionearea-button-close').append('<span uk-icon="icon: close"></span>');

    jQuery('.emojionearea-editor').on('keyup', function(e){
        if (e.keyCode== 13 || e.which== 13) { // if enter key is pressed
            if ( jQuery(this).html() == '' ) {
                jQuery(this).parent().closest('form').addClass('error-submission uk-animation-shake'); // add red border & shake animation
                jQuery(this).attr('placeholder', 'Oops! Please type again'); // remind the user
            } else {
                e.preventDefault();
                var contentEditableValue = jQuery('#emojionearea-editor').html(); //get the div value
                jQuery('#reply').attr('value', contentEditableValue); //add a dummy input to the form to send the value
                jQuery('#replyForm').submit(); //submit the form
                jQuery(this).parent().closest('form').removeClass('error-submission uk-animation-shake'); // remove the red border & shake animation
                jQuery(this).attr('placeholder', 'Write a comment'); // restore placeholder
            }
        }
    });
});

jQuery('#replyForm').submit(function(event) {
    event.preventDefault();
    // reply = jQuery('#reply').val();
    reply = jQuery('#emojionearea-editor').html();
    jQuery.post(Obj.url, {
        'reply': reply,
        'channel': selectedChannel,
        'action': 'repliesStore',
    }, function (response) {
        // Reset input field and focus on it
        jQuery('.emojionearea-editor').text(''); // clear text clone div element from emojionearea
        jQuery('#reply').val('');
        jQuery('#reply').focus();
        loadReplies();
        UIkit.notification('Message Sent!', {pos: "top-right"});
    });
});

/*
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

// function sendEmoji(emoji) {
//     jQuery.post(Obj.url, {
//         'reply': '<span class="uk-h1">&#x' + emoji + ';</span>',
//         'channel': selectedChannel,
//         'action': 'repliesStore',
//     }, function (response) {
//         // Close reactions modal and focus on input field
//         // UIkit.modal('#stickers').hide();
//         // UIkit.modal('#emojis').hide();
//         // UIkit.dropdown('.ui-emojis-wrapper').hide(100);
//         jQuery('#reply').focus();
//         loadReplies();
//     });
// }
*/

// Like a reply
function like(reply) {
    jQuery.post(Obj.url, {
        'reply': reply,
        'action': 'repliesLike',
    }, function (response) {
        // Update vote
        jQuery('#' + reply + '-likes').text(parseInt(jQuery('#' + reply + '-likes').text()) + parseInt(response))
    })
}

// Dislike a reply

function dislike(reply) {
    jQuery.post(Obj.url, {
        'reply': reply,
        'action': 'repliesDislike',
    }, function (response) {
        // Update vote
        jQuery('#' + reply + '-likes').text(parseInt(jQuery('#' + reply + '-likes').text()) + parseInt(response))
    })
}

// Send Image

jQuery('#chat-image').change(function() {
    UIkit.notification('Uploading');
    let formData = new FormData();
    formData.append('chat-image', jQuery('#chat-image')[0].files[0]);
    formData.append('channel', selectedChannel);
    formData.append('action', 'repliesImage');
    jQuery.ajax({
        url: Obj.url,
        type: 'post',
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            if (response.length > 0) {
                UIkit.notification(response);
            }
            loadReplies();
        },
    })
})
