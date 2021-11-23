<?php 

// Include Emojis
include 'emojis.php'; 

// Check Ultimate Member ID
$profile_id = um_profile_id();

?>
<div class="--cappers-chat-plugin">
    <div class="ui-comments">
        <form action="" class="ui-comments-msgboard uk-animation-toggle" tabindex="0" id="replyForm">
            <?php if (is_user_logged_in() && array_intersect(['cappers', 'cappers_chat', 'administrator'], wp_get_current_user()->roles)) : ?>
                <input type="text" name="ui-cm-field" class="ui-cm-field" placeholder="Write a comment" autocomplete="off" id="reply" required>
                <div class="ui-cm-widget-controls">
                    <div>
                        <a href="#stickers" uk-icon="icon: happy" uk-toggle></a>
                        <div id="stickers" uk-modal>
                            <div class="uk-modal-dialog uk-modal-body">
                                <div class="uk-grid-small" uk-grid>
                                    <div>
                                        <a href="#stickers" class="uk-button uk-button-primary uk-button-small" uk-toggle>Stickers</a>
                                    </div>
                                    <div>
                                        <a href="#emojis" class="uk-button uk-button-primary uk-button-small" uk-toggle>Emojis</a>
                                    </div>
                                </div>
                                <div class="uk-child-width-1-4 uk-child-width-1-6@m uk-flex-middle" uk-grid>
                                    <div>
                                        <img src="<?php echo plugins_url(); ?>/cappers-corner-chat/images/stickers/angel-girl.png" style="cursor: pointer;" onclick="sendSticker('angel-girl');">
                                    </div>
                                    <div>
                                        <img src="<?php echo plugins_url(); ?>/cappers-corner-chat/images/stickers/angry-girl.png" style="cursor: pointer;" onclick="sendSticker('angry-girl');">
                                    </div>
                                    <div>
                                        <img src="<?php echo plugins_url(); ?>/cappers-corner-chat/images/stickers/dance-girl.png" style="cursor: pointer;" onclick="sendSticker('dance-girl');">
                                    </div>
                                    <div>
                                        <img src="<?php echo plugins_url(); ?>/cappers-corner-chat/images/stickers/exclamation-girl.png" style="cursor: pointer;" onclick="sendSticker('exclamation-girl');">
                                    </div>
                                    <div>
                                        <img src="<?php echo plugins_url(); ?>/cappers-corner-chat/images/stickers/heart-girl.png" style="cursor: pointer;" onclick="sendSticker('heart-girl');">
                                    </div>
                                    <div>
                                        <img src="<?php echo plugins_url(); ?>/cappers-corner-chat/images/stickers/music-girl.png" style="cursor: pointer;" onclick="sendSticker('music-girl');">
                                    </div>
                                    <div>
                                        <img src="<?php echo plugins_url(); ?>/cappers-corner-chat/images/stickers/scared-girl.png" style="cursor: pointer;" onclick="sendSticker('scared-girl');">
                                    </div>
                                    <div>
                                        <img src="<?php echo plugins_url(); ?>/cappers-corner-chat/images/stickers/sport-girl.png" style="cursor: pointer;" onclick="sendSticker('sport-girl');">
                                    </div>
                                    <div>
                                        <img src="<?php echo plugins_url(); ?>/cappers-corner-chat/images/stickers/what-girl.png" style="cursor: pointer;" onclick="sendSticker('what-girl');">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="emojis" uk-modal>
                            <div class="uk-modal-dialog uk-modal-body">
                                <div class="uk-grid-small" uk-grid>
                                    <div>
                                        <a href="#stickers" class="uk-button uk-button-primary uk-button-small" uk-toggle>Stickers</a>
                                    </div>
                                    <div>
                                        <a href="#emojis" class="uk-button uk-button-primary uk-button-small" uk-toggle>Emojis</a>
                                    </div>
                                </div>
                                <p class="uk-text-large">
                                    <?php include 'emojis.php'; ?>
                                    <?php
                                        foreach ($emojis as $emoji) {
                                            echo '<span class="uk-margin-small-right" style="cursor: pointer;" onclick="sendEmoji(\'' . $emoji . '\');">&#x' . $emoji . ';</span>';
                                        }
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <button type="submit" name="ui-msg-submit" class="ui-msg-submit"></button>
                </div>
            <?php else : ?>
                <a href="#form-panel" class="ui-cm-field" uk-toggle="animation: uk-animation-fade">Login or join now to post a reply</a>
            <?php endif; ?>
        </form>
        <header class="ui-comments-header uk-light">
            <h1 class="ui-ch-channel" id="heading"></h1>
            <div class="ui-ch-action">
                <div class="ui-ch-moreaction">
                     <select class="uk-select" id="channels" style="display: none;"></select>
                    <div class="ui-ch-moreaction">
                        <a class="uk-margin-remove"><img src="<?php echo get_avatar_url(wp_get_current_user()->ID); ?>" class="uk-border-circle" style="height: 20px; width: 20px;"></a>
                        <div uk-dropdown="offset: 12; pos: bottom-right">
                            <ul class="uk-nav uk-dropdown-nav">
                                <?php if (is_user_logged_in() && array_intersect(['cappers', 'cappers_chat', 'administrator'], wp_get_current_user()->roles)) : ?>
                                    <li><a href="<?php echo esc_url( site_url('cappers-profile/'.$profile_id) ); ?>">Profile</a></li>
                                    <li><a href="<?php echo esc_url( site_url('logout') ); ?>">Logout</a></li>
                                <?php else : ?>
                                    <li><a id="form-btn" href="#form-panel" uk-toggle="animation: uk-animation-fade">Login</a></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <div class="ui-comments-body" id="replies-body">
            <div id="replies"></div>
        </div>

        <div id="form-panel" class="uk-position-cover uk-overlay uk-overlay-default uk-flex uk-flex-middle" uk-overflow-auto hidden>
            <a href="#form-panel" class="uk-position-small uk-position-top-rigt --close-overlay" uk-toggle="animation: uk-animation-fade">&times;</a>
            
            <div class="uk-card uk-card-default uk-card-body uk-box-shadow-large">
                <ul class="uk-tab uk-flex-center" uk-grid uk-switcher="animation: uk-animation-fade">
                    <li><a href="#">Log In</a></li>
                    <li><a href="#">Sign Up</a></li>
                    <li class="uk-hidden"><a href="#">Forgot Password?</a></li>
                </ul>

                <ul class="uk-switcher uk-margin">                  
                    <li>
                        <h3 class="uk-card-title uk-text-center">Welcome back!</h3>
                        <?php echo do_shortcode('[ultimatemember form_id="2041"]'); ?>
                    </li>
                    <li>
                        <h3 class="uk-card-title uk-text-center">Sign up today. It's free!</h3>
                        <?php echo do_shortcode('[ultimatemember form_id="2040"]'); ?>
                    </li>                    
                    <li>
                        <h3 class="uk-card-title uk-text-center">Forgot your password?</h3>
                        <?php echo do_shortcode('[ultimatemember_password]'); ?>
                    </li>
                </ul>
            </div>

        </div>

    </div>
</div>
