<?php
defined('ABSPATH') || exit;

do_action('woocommerce_before_customer_login_form');

$frontend_url = get_permalink(get_option('woocommerce_myaccount_page_id'));
?>

<div class="wc-auth-container wcmamtx_custom_login_register wcmamtx_login_popup_modal">

    <div class="wc-auth-card">


        <div class="wc-auth-tabs wcmamtx_login_register_buttons">
            <button class="btn btn-login wc-tab-btn active" data-tab="login">
             <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
             xmlns="http://www.w3.org/2000/svg">
             <path d="M15 3H6C4.89543 3 4 3.89543 4 5V19C4 20.1046 4.89543 21 6 21H15"
             stroke="currentColor" stroke-width="2"/>
             <path d="M10 12H20"
             stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
             <path d="M17 8L21 12L17 16"
             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
         </svg>
         <?php esc_html_e( 'Login', 'customize-my-account-for-woocommerce' ); ?> 
     </button>

     
     <?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) { ?>
        <button class="btn btn-register wc-tab-btn" data-tab="register">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
    xmlns="http://www.w3.org/2000/svg">
    <circle cx="10" cy="8" r="4" stroke="currentColor" stroke-width="2"/>
    <path d="M3 20C3 16.134 6.13401 13 10 13C13.866 13 17 16.134 17 20"
        stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
    <path d="M19 8V14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
    <path d="M16 11H22" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
</svg>
        <?php esc_html_e( 'Register', 'customize-my-account-for-woocommerce' ); ?> 
    </button>
<?php } ?>               

</div>

        <!-- LOGIN -->
        <div class="wcmamtx_login_boxes wc-tab-content wcmamtx_login active" id="login">

            <form class="woocommerce-form woocommerce-form-login login" method="post" action="<?php echo $frontend_url; ?>" novalidate>

                <?php do_action( 'woocommerce_login_form_start' ); ?>

                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <label for="username"><?php esc_html_e( 'Username or email address', 'customize-my-account-for-woocommerce' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e( 'Required', 'customize-my-account-for-woocommerce' ); ?></span></label>
                    <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) && is_string( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" required aria-required="true" /><?php // @codingStandardsIgnoreLine ?>
                </p>
                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <label for="password"><?php esc_html_e( 'Password', 'customize-my-account-for-woocommerce' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e( 'Required', 'customize-my-account-for-woocommerce' ); ?></span></label>
                    <input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" required aria-required="true" />
                </p>

                <?php do_action( 'woocommerce_login_form' ); ?>

                <p class="form-row">
                    <label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
                        <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php esc_html_e( 'Remember me', 'customize-my-account-for-woocommerce' ); ?></span>
                    </label>
                    <?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
                    <button type="submit" class="woocommerce-button button woocommerce-form-login__submit<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="login" value="<?php esc_attr_e( 'Log in', 'customize-my-account-for-woocommerce' ); ?>"><?php esc_html_e( 'Log in', 'customize-my-account-for-woocommerce' ); ?></button>
                </p>
                <p class="woocommerce-LostPassword lost_password">
                    <a class="wc-tab-btn" data-tab="lost_password"><?php esc_html_e( 'Lost your password?', 'customize-my-account-for-woocommerce' ); ?></a>
                </p>
                

                <?php

                $wcmamtx_layout = (array) get_option( 'wcmamtx_layout' );

                $google_client_id = isset( $wcmamtx_layout['google_client_id'] ) ? $wcmamtx_layout['google_client_id'] : '';
                $google_client_secret = isset( $wcmamtx_layout['google_client_secret'] ) ? $wcmamtx_layout['google_client_secret'] : '';

                if (isset($google_client_id) && ($google_client_id != "") && isset($google_client_secret) && ($google_client_secret != "") ) {
                ?>
                <div class="social-login-wrapper">

                    <a href="<?php echo esc_url( wcmamtx_get_google_login_url() ); ?>"
                     class="google-login-btn">

                     <span class="google-icon">
                        <svg width="22" height="22" viewBox="0 0 48 48">
                            <path fill="#EA4335"
                            d="M24 9.5c3.54 0 6.74 1.22 9.25 3.6l6.9-6.9C35.96 2.42 30.42 0 24 0 14.64 0 6.56 5.38 2.62 13.22l8.04 6.24C12.6 13.5 17.84 9.5 24 9.5z"/>
                            <path fill="#4285F4"
                            d="M46.5 24.55c0-1.64-.15-3.22-.42-4.73H24v9.18h12.67c-.55 2.96-2.22 5.47-4.74 7.16l7.36 5.72c4.3-3.96 6.8-9.8 6.8-17.33z"/>
                            <path fill="#FBBC05"
                            d="M10.66 28.54A14.47 14.47 0 0 1 9.5 24c0-1.57.27-3.09.76-4.54l-8.04-6.24A24.02 24.02 0 0 0 0 24c0 3.87.93 7.53 2.58 10.78l8.08-6.24z"/>
                            <path fill="#34A853"
                            d="M24 48c6.48 0 11.92-2.14 15.89-5.82l-7.36-5.72c-2.04 1.37-4.66 2.18-8.53 2.18-6.15 0-11.38-4-13.34-9.42l-8.08 6.24C6.53 42.62 14.62 48 24 48z"/>
                        </svg>
                    </span>

                    <span><?php esc_html_e( 'Login with Google', 'customize-my-account-for-woocommerce' ); ?></span>

                </a>

                </div>
                <?php } ?>



            <?php do_action( 'woocommerce_login_form_end' ); ?>

        </form>

        </div>

        <?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) { ?>

            <!-- REGISTER -->
            <div class="wcmamtx_login_boxes wc-tab-content wcmamtx_register" id="register">

                <form method="post"  action="<?php echo $frontend_url; ?>" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?> >

                    <?php do_action( 'woocommerce_register_form_start' ); ?>

                    <?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

                        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                            <label for="reg_username"><?php esc_html_e( 'Username', 'customize-my-account-for-woocommerce' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e( 'Required', 'customize-my-account-for-woocommerce' ); ?></span></label>
                            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" required aria-required="true" /><?php // @codingStandardsIgnoreLine ?>
                        </p>

                    <?php endif; ?>

                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="reg_email"><?php esc_html_e( 'Email address', 'customize-my-account-for-woocommerce' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e( 'Required', 'customize-my-account-for-woocommerce' ); ?></span></label>
                        <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" required aria-required="true" /><?php // @codingStandardsIgnoreLine ?>
                    </p>

                    <?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

                        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                            <label for="reg_password"><?php esc_html_e( 'Password', 'customize-my-account-for-woocommerce' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e( 'Required', 'customize-my-account-for-woocommerce' ); ?></span></label>
                            <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" required aria-required="true" />
                        </p>

                    <?php else : ?>

                        <p><?php esc_html_e( 'A link to set a new password will be sent to your email address.', 'customize-my-account-for-woocommerce' ); ?></p>

                    <?php endif; ?>

                    <?php do_action( 'woocommerce_register_form' ); ?>

                    <p class="woocommerce-form-row form-row">
                        <?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
                        <button type="submit" class="woocommerce-Button woocommerce-button button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?> woocommerce-form-register__submit" name="register" value="<?php esc_attr_e( 'Register', 'customize-my-account-for-woocommerce' ); ?>"><?php esc_html_e( 'Register', 'customize-my-account-for-woocommerce' ); ?></button>
                    </p>

                    <?php do_action( 'woocommerce_register_form_end' ); ?>

                </form>

            </div>

        <?php } ?>

         <!-- Lost Password -->
         <div class="wcmamtx_login_boxes wcmamtx_lost_password wc-tab-content" id="lost_password">

            <?php


            do_action( 'woocommerce_before_lost_password_form' );
            ?>

            <form method="post" class="woocommerce-ResetPassword lost_reset_password" action="<?php echo $frontend_url; ?>">

                <p class="wcmamtx_lost_password_info" ><?php echo apply_filters( 'woocommerce_lost_password_message', esc_html__( 'Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.', 'customize-my-account-for-woocommerce' ) ); ?></p><?php // @codingStandardsIgnoreLine ?>

                <p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
                    <label for="user_login"><?php esc_html_e( 'Username or email', 'customize-my-account-for-woocommerce' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class=" screen-reader-text"><?php esc_html_e( 'Required', 'customize-my-account-for-woocommerce' ); ?></span></label>
                    <input class="wcmamtx_reset_password_input woocommerce-Input woocommerce-Input--text input-text" type="text" name="user_login" id="user_login" autocomplete="username" required aria-required="true" />
                </p>

                <div class="clear"></div>

                <?php do_action( 'woocommerce_lostpassword_form' ); ?>

                <p class="woocommerce-form-row form-row">
                    <input type="hidden" name="wc_reset_password" value="true" />
                    <button type="submit" class="woocommerce-Button button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" value="<?php esc_attr_e( 'Reset password', 'customize-my-account-for-woocommerce' ); ?>"><?php esc_html_e( 'Reset password', 'customize-my-account-for-woocommerce' ); ?></button>
                </p>

                <?php wp_nonce_field( 'lost_password', 'woocommerce-lost-password-nonce' ); ?>

            </form>
            <?php
            do_action( 'woocommerce_after_lost_password_form' ); ?>


        </div>



    </div>

</div>

<?php do_action('woocommerce_after_customer_login_form'); ?>