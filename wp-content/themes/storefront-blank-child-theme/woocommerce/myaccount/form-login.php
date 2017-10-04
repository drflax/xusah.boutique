<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<?php wc_print_notices(); ?>

<?php do_action( 'woocommerce_before_customer_login_form' ); ?>

<?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) : ?>


   <div class="row">
      <div class="col-xs-12 col-sm-8 col-sm-offset-2">
        <div class="row">

        <?php if( isset( $_GET['action']) && $_GET['action'] == "register") : ?>

            <div class="col-xs-12">

                <form method="post" class="box">

                  <p class="headline"><?php _e( 'Sign up for an account with the boutique using the form below. Its hassle-free and you wont regret it, promise :)', 'woocommerce' ); ?></p>

                    <?php do_action( 'woocommerce_register_form_start' ); ?>

                    <?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

                        <p class="form-row form-row-wide">
                            <label for="reg_username"><?php _e( 'Username', 'woocommerce' ); ?> <span class="required">*</span></label>
                            <input type="text" class="input-text" name="username" id="reg_username" value="<?php if ( ! empty( $_POST['username'] ) ) echo esc_attr( $_POST['username'] ); ?>" />
                        </p>

                    <?php endif; ?>

                    <p class="form-row form-row-wide">
                        <label for="reg_email"><?php _e( 'Email address', 'woocommerce' ); ?> <span class="required">*</span></label>
                        <input type="email" class="input-text" name="email" id="reg_email" value="<?php if ( ! empty( $_POST['email'] ) ) echo esc_attr( $_POST['email'] ); ?>" />
                    </p>

                    <?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

                        <p class="form-row form-row-wide" id="signup-password-row">
                            <label for="reg_password"><?php _e( 'Password', 'woocommerce' ); ?> <span class="required">*</span></label>
                            <input type="password" class="input-text" name="password" id="reg_password" />
                        </p>

                    <?php endif; ?>

                    <!-- Spam Trap -->
                    <div style="<?php echo ( ( is_rtl() ) ? 'right' : 'left' ); ?>: -999em; position: absolute;"><label for="trap"><?php _e( 'Anti-spam', 'woocommerce' ); ?></label><input type="text" name="email_2" id="trap" tabindex="-1" /></div>

                    <?php do_action( 'woocommerce_register_form' ); ?>
                    <?php do_action( 'register_form' ); ?>

                    <div class="form-row-register-button">
                        <?php wp_nonce_field( 'woocommerce-register' ); ?>
                        <input type="submit" id="register-button" name="register" value="<?php _e( 'Register', 'woocommerce' ); ?>" />
                    </div>

                    <?php do_action( 'woocommerce_register_form_end' ); ?>

                </form>

            </div> <!-- end of register section -->

        <?php else : ?>

          <div class="col-xs-12">

                <form method="post" class="box">

                  <p class="headline"><?php _e( 'Greetings! If you are a returning visitor, please login to your account using the form below. If you are just coming aboard and do not have an account yet, please click on "Register" to create a new one.', 'woocommerce' ); ?></p>

                    <?php do_action( 'woocommerce_login_form_start' ); ?>

                    <p class="form-row form-row-wide">
                        <label for="username"><?php _e( 'Username or email address', 'woocommerce' ); ?> <span class="required">*</span></label>
                        <input type="text" class="input-text" name="username" id="username" value="<?php if ( ! empty( $_POST['username'] ) ) echo esc_attr( $_POST['username'] ); ?>" />
                    </p>
                    <p class="form-row form-row-wide">
                        <label for="password"><?php _e( 'Password', 'woocommerce' ); ?> <span class="required">*</span></label>
                        <input class="input-text" type="password" name="password" id="password" />
                    </p>

                    <?php do_action( 'woocommerce_login_form' ); ?>

                    <p class="form-row">
                        <?php wp_nonce_field( 'woocommerce-login' ); ?>
                        <input type="submit" class="button" name="login" value="<?php _e( 'Login', 'woocommerce' ); ?>" />
                        <label for="rememberme" class="inline">
                            <input name="rememberme" type="checkbox" id="rememberme" value="forever" /> <?php _e( 'Remember me', 'woocommerce' ); ?>
                        </label>
                    </p>
                     <p id="register-follow-link">
                        <a href="<?php echo get_permalink(woocommerce_get_page_id('myaccount')) . '?action=register'; ?>"><?php _e( 'Register' ); ?></a>
                    </p>
                    <p class="lost_password">
                        <a href="<?php echo esc_url( wc_lostpassword_url() ); ?>"><?php _e( 'Lost your password?', 'woocommerce' ); ?></a>
                    </p>
                    <?php do_action( 'woocommerce_login_form_end' ); ?>

                </form>

            </div> <!-- end of login section -->

        <?php endif; ?>

        </div>
      </div>
    </div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
