<?php

// Include Emojis
include 'emojis.php';

// Check Ultimate Member ID
$profile_id = um_profile_id();

?>

<ul class="uk-subnav uk-subnav-pill" id="channels-list"></ul>
<div class="--cappers-chat-plugin">
    <div class="ui-comments">
        <form action="" class="ui-comments-msgboard uk-animation-toggle" tabindex="0" id="replyForm">
            <?php if (is_user_logged_in() && array_intersect(['cappers', 'cappers_chat'], wp_get_current_user()->roles)) : ?>
                <input type="text" name="ui-cm-field" class="ui-cm-field" placeholder="Write a comment" autocomplete="off" id="reply" required>
                <div class="ui-cm-widget-controls" >
                    <div uk-form-custom style="margin-right: 23px;">
                        <input name="chat-image" type="file" accept="image/jpeg,image/png,image/png,image/gif" id="chat-image">
                        <button type="button" tabindex="-1" class="uk-icon-button" uk-icon="image"></button>
                    </div>
                    <button type="submit" name="ui-msg-submit" class="ui-msg-submit"></button>
                </div>
            <?php else : ?>
                <a href="<?php echo esc_url( site_url( '/cappers-login' ) ); ?>" class="ui-cm-field" uk-toggle="animation: uk-animation-fade">Click here to Login or join now to post a reply</a>
            <?php endif; ?>
            <div class="uk-position-absolute --help-text">Note: Comments are subject to site moderator's discretionary removal.</div>
        </form>
        <nav class="uk-navbar-container ui-comments-header uk-light" uk-navbar>
            <div class="uk-navbar-left">
                <h1 class="ui-ch-channel" style="min-width: inherit;" id="heading"></h1>
            </div>
            <div class="uk-navbar-right">
                <div class="ui-ch-action">
                    <?php if (is_user_logged_in() && array_intersect(['cappers', 'cappers_chat'], wp_get_current_user()->roles)) : ?>
                    <div class="ui-ch-moreaction">
                        <a class="um-avatar uk-margin-remove"><img src="<?php echo get_avatar_url(wp_get_current_user()->ID); ?>" class="uk-border-circle" style="height: 2.344rem; width: 2.344rem;"></a>
                        <div uk-dropdown="pos: bottom-right">
                            <ul class="uk-nav uk-dropdown-nav">
                                <li><a href="<?php echo esc_url( site_url('cappers-profile/'.$profile_id) ); ?>">Profile</a></li>
                                <li><a href="<?php echo esc_url( site_url('logout') ); ?>">Logout</a></li>
                            </ul>
                        </div>
                    </div>
                    <?php else : ?>
                    <div class="ui-ch-moreaction">
                        <a href="<?php echo esc_url( site_url( '/cappers-login' ) ); ?>">Login</a>
                        <a href="<?php echo esc_url( site_url( '/cappers-register' ) ); ?>">Register</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
        <div class="ui-comments-body" id="replies-body">
            <div id="replies"></div>
            <div class="uk-position-center uk-text-center --welcome uk-hidden" id="replies-empty">It's empty in here. Try writing a comment. <small class="uk-display-block uk-text-meta">Comments are subject to site moderator's discretionary removal.</small></div>
        </div>

        <?php if ( is_page([ 2053, 2168, 2166 ]) || is_singular( 'cappers_corner' ) ) {
            $hideMe = 'hidden';
        } else {
            $hideMe = '';
        } ?>

        <div id="form-panel" class="uk-position-cover uk-overlay uk-overlay-default uk-flex uk-flex-middle" uk-overflow-auto <?=$hideMe?>>
            
            <?php 
            // Cappers Login
            if ( is_page( 2172 ) ) : ?>

                <div class="uk-card uk-card-default uk-card-body uk-box-shadow-large">
                    <ul class="uk-tab uk-flex-center">
                        <li><a href="<?php echo esc_url( site_url( '/cappers-login' ) ); ?>">Log In</a></li>
                        <li><a href="<?php echo esc_url( site_url( '/cappers-register' ) ); ?>">Sign Up</a></li>
                    </ul>

                    <ul class="um-tab-form uk-margin">
                        <li>
                            <h3 class="uk-card-title uk-text-center">Welcome!</h3>
                            <?php echo do_shortcode('[ultimatemember form_id="2159"]'); ?>
                        </li>
                    </ul>
                </div>

            <?php 
            // Cappers Register
            elseif ( is_page( 2174 ) ) : ?>

                <div class="uk-card uk-card-default uk-card-body uk-box-shadow-large">
                    <ul class="uk-tab uk-flex-center">
                        <li><a href="<?php echo esc_url( site_url( '/cappers-login' ) ); ?>">Log In</a></li>
                        <li class="uk-active"><a href="<?php echo esc_url( site_url( '/cappers-register' ) ); ?>">Sign Up</a></li>
                    </ul>

                    <ul class="um-tab-form uk-margin">
                        <li>
                            <h3 class="uk-card-title uk-text-center">Sign up today. It's free!</h3>
                            <?php echo do_shortcode('[ultimatemember form_id="2158"]'); ?>
                        </li>
                    </ul>
                </div>

            <?php 
            // Cappers Password Reset
            elseif ( is_page( 2182 ) ) : ?>

                <div class="uk-card uk-card-default uk-card-body uk-box-shadow-large">
                    <ul class="uk-tab uk-flex-center">
                        <li><a href="<?php echo esc_url( site_url( '/cappers-login' ) ); ?>">Log In</a></li>
                        <li><a href="<?php echo esc_url( site_url( '/cappers-register' ) ); ?>">Sign Up</a></li>
                    </ul>

                    <ul class="um-tab-form uk-margin">
                        <li>
                            <h3 class="uk-card-title uk-text-center">Forgot your password?</h3>
                            <?php echo do_shortcode('[ultimatemember_password]'); ?>
                        </li>
                    </ul>
                </div>

            <?php endif; ?>

        </div>

    </div>
</div>
