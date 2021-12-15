<?php

// Include Emojis
include 'emojis.php';

// Check Ultimate Member ID
$profile_id = um_profile_id();

?>
<div class="--cappers-chat-plugin">
    <div class="ui-comments">
        <form action="" class="ui-comments-msgboard uk-animation-toggle" tabindex="0" id="replyForm"">
            <?php if (is_user_logged_in() && array_intersect(['cappers', 'cappers_chat'], wp_get_current_user()->roles)) : ?>
                <input type="hidden" name="ui-cm-field" class="ui-cm-field" placeholder="Write a comment" autocomplete="off" id="reply" required>
                <div class="ui-cm-widget-controls" >
                    <button type="submit" name="ui-msg-submit" class="ui-msg-submit"></button>
                </div>
            <?php else : ?>
                <a href="#form-panel" class="ui-cm-field" uk-toggle="animation: uk-animation-fade">Click here to Login or join now to post a reply</a>
            <?php endif; ?>
        </form>
        <nav class="uk-navbar-container ui-comments-header uk-light" uk-navbar>
            <div class="uk-navbar-left">
                <h1 class="ui-ch-channel" style="min-width: inherit;">&nbsp;</h1>
                <ul class="uk-navbar-nav" id="channel-nav"></ul>
            </div>
            <div class="uk-navbar-right">
                <div class="ui-ch-action">
                    <div class="ui-ch-moreaction">
                        <div class="ui-ch-moreaction-nav">
                            <a class="uk-margin-remove"><img src="<?php echo get_avatar_url(wp_get_current_user()->ID); ?>" class="uk-border-circle" style="height: 20px; width: 20px;"></a>
                            <div uk-dropdown="offset: 12; pos: bottom-right">
                                <ul class="uk-nav uk-dropdown-nav">
                                    <?php if (is_user_logged_in() && array_intersect(['cappers', 'cappers_chat'], wp_get_current_user()->roles)) : ?>
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
            </div>
        </nav>
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
                        <h3 class="uk-card-title uk-text-center">Welcome!</h3>
                        <?php echo do_shortcode('[ultimatemember form_id="2159"]'); ?>
                    </li>
                    <li>
                        <h3 class="uk-card-title uk-text-center">Sign up today. It's free!</h3>
                        <?php echo do_shortcode('[ultimatemember form_id="2158"]'); ?>
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
