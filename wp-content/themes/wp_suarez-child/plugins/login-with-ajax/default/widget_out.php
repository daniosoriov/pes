<?php
/*
 * This is the page users will see logged out. 
 * You can edit this, but for upgrade safety you should copy and modify this file into your template folder.
 * The location from within your template folder is plugins/login-with-ajax/ (create these directories if they don't exist)
*/
?>
<div class="lwa lwa-pes"><?php //class must be here, and if this is a template, class name should be that of template directory ?>
    <form class="lwa-form" action="<?php echo esc_attr( LoginWithAjax::$url_login ); ?>" method="post">
        <div>
            <span class="lwa-status"></span>
            <div class="pes-login-fields">
                <div class="um-field um-field-username um-field-text">
                    <div class="um-field-area">
                        <input class="um-form-field valid" type="text" name="log"
                               placeholder="<?php esc_html_e( 'E-mail', 'login-with-ajax' ) ?> *"/>
                    </div>
                </div>
                <div class="um-field um-field-user_password um-field-password">
                    <div class="um-field-area">
                        <input class="um-form-field valid" type="password" name="pwd"
                               placeholder="<?php esc_html_e( 'Password', 'login-with-ajax' ) ?> *"/>
                    </div>
                </div>
            </div>
            <span><?php do_action( 'login_form' ); ?></span>
            <div class="um-col-alt pes-login-actions">
                <div class="um-left um-half">
                    <input class="um-button" type="submit" name="wp-submit" id="lwa_wp-submit"
                           value="<?php esc_attr_e( 'Login', 'login-with-ajax' ); ?>" tabindex="100"/>
                </div>
                <input type="hidden" name="lwa_profile_link"
                       value="<?php echo esc_attr( $lwa_data['profile_link'] ); ?>"/>
                <input type="hidden" name="login-with-ajax" value="login"/>
				<?php if ( ! empty( $lwa_data['redirect'] ) ): ?>
                    <input type="hidden" name="redirect_to" value="<?php echo esc_url( $lwa_data['redirect'] ); ?>"/>
				<?php endif; ?>

				<?php if ( get_option( 'users_can_register' ) && ! empty( $lwa_data['registration'] ) ) : ?>
                    <div class="um-right um-half">
                        <a href="/register/" target="_blank"
                           class="um-button um-alt um-register-button"><?php esc_html_e( 'Register', 'login-with-ajax' ) ?></a>
                    </div>
				<?php endif; ?>
                <div class="um-clear"></div>

				<?php if ( ! empty( $lwa_data['remember'] ) ): ?>
                    <div class="um-col-alt-b">
                        <a href="/password-reset/" target="_blank" class="um-link-alt">Forgot your password?</a>
                    </div>
				<?php endif; ?>
            </div>
        </div>
    </form>
</div>