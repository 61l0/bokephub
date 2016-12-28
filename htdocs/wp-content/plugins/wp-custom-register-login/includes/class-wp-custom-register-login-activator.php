<?php
/**
 * Fired during plugin activation
 *
 * @link       http://www.daffodilsw.com/
 * @since      1.0.0
 *
 * @package    Wp_Custom_Register_Login
 * @subpackage Wp_Custom_Register_Login/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_Custom_Register_Login
 * @subpackage Wp_Custom_Register_Login/includes
 * @author     Jenis Patel <jenis.patel@daffodilsw.com>
 */
class Wp_Custom_Register_Login_Activator
{

    /**
     *
     * @since    1.0.0
     */
    public static function activate()
    {
        //delete old settings "wpcrl_settings"
        if (get_option('wpcrl_settings')) {
            delete_option('wpcrl_settings');
        }
        $wpcrl_redirect_settings = get_option("wpcrl_redirect_settings");
        $wpcrl_display_settings = get_option("wpcrl_display_settings");
        $wpcrl_form_settings = get_option("wpcrl_form_settings");
        $wpcrl_email_settings = get_option("wpcrl_email_settings");


        //initialize redirect settings
        if (empty($wpcrl_redirect_settings)) {

            $wpcrl_redirect_settings = array(
                'wpcrl_login_redirect' => '-1',
                'wpcrl_logout_redirect' => '-1',
            );
            add_option('wpcrl_redirect_settings', $wpcrl_redirect_settings);
        }

        //initialize display settings
        if (empty($wpcrl_display_settings)) {

            $wpcrl_display_settings = array(
                'wpcrl_email_error_message' => 'Could not able to send the email notification.',
                'wpcrl_account_activated_message' => 'Your account has been activated. You can login now.',
                'wpcrl_account_notactivated_message' => 'Your account has not been activated yet, please verify your email first.',
                'wpcrl_login_error_message' => 'Username or password is incorrect.',
                'wpcrl_login_success_message' => 'You are successfully logged in.',
                'wpcrl_password_reset_invalid_email_message' => 'We cannot identify any user with this email.',
                'wpcrl_password_reset_link_sent_message' => 'A link to reset your password has been sent to you.',
                'wpcrl_password_reset_link_notsent_message' => 'Password reset link not sent.',
                'wpcrl_password_reset_success_message' => 'Your password has been changed successfully.',
                'wpcrl_invalid_password_reset_token_message' => 'This token appears to be invalid.'
            );
            add_option('wpcrl_display_settings', $wpcrl_display_settings);
        }

        //initialize form settings
        if (empty($wpcrl_form_settings)) {

            $wpcrl_form_settings = array(
                'wpcrl_signup_heading' => 'Register',
                'wpcrl_signin_heading' => 'Login',
                'wpcrl_resetpassword_heading' => 'Reset Password',
                'wpcrl_signin_button_text' => 'Login',
                'wpcrl_signup_button_text' => 'Register',
                'wpcrl_returntologin_button_text' => 'Return to Login',
                'wpcrl_forgot_password_button_text' => 'Forgot Password',
                'wpcrl_resetpassword_button_text' => 'Reset Password',
                'wpcrl_enable_captcha' => '1',
                'wpcrl_enable_forgot_password' => '1'
            );
            add_option('wpcrl_form_settings', $wpcrl_form_settings);
        }

        //initialize email settings
        if (empty($wpcrl_email_settings)) {

            $wpcrl_email_settings = array(
                'wpcrl_notification_subject' => 'Welcome to %BLOGNAME%',
                'wpcrl_notification_message' => 'Thank you for registering on %BLOGNAME%.
<br><br>
<strong>First Name :</strong> %FIRSTNAME%<br>
<strong>Last Name : </strong>%LASTNAME%<br>
<strong>Username :</strong> %USERNAME%<br>
<strong>Email :</strong> %USEREMAIL%<br>
<strong>Password :</strong> As choosen at the time of registration.
<br><br>
Please visit %BLOGURL% to login.
<br><br>
Thanks and regards,
<br>
The team at %BLOGNAME%',
                'wpcrl_admin_email_notification' => '1',
                'wpcrl_user_email_confirmation' => '1',
                'wpcrl_new_account_verification_email_subject' => '%BLOGNAME% | Please confirm your email',
                'wpcrl_new_account_verification_email_message' => 'Thank you for registering on %BLOGNAME%.
<br><br>
Please confirm your email by clicking on below link :
<br><br>
%ACTIVATIONLINK%
<br><br>
Thanks and regards,
<br>
The team at %BLOGNAME%',
                'wpcrl_password_reset_email_subject' => '%BLOGNAME% | Password Reset',
                'wpcrl_password_reset_email_message' => 'Hello %USERNAME%,
<br><br>
We have received a request to change your password.
Click on the link to change your password : 
<br><br>
%RECOVERYLINK%
<br><br>
Thanks and regards,
<br>
The team at %BLOGNAME%',
            );
            add_option('wpcrl_email_settings', $wpcrl_email_settings);
        }
    }
}
