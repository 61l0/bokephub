<?php
/**
 * Provide a public-facing view for the reset password form
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://www.daffodilsw.com/
 * @since      1.0.0
 *
 * @package    Wp_Custom_Register_Login
 * @subpackage Wp_Custom_Register_Login/public/partials
 */

?>
<?php $is_url_has_token = $_GET['wpcrl_reset_password_token'];?>
<div id="wpcrlResetPasswordSection" class="container-fluid <?php echo empty($is_url_has_token) ? ' hidden' : 'ds' ?>">
    <div class="row">
        <div class="col-xs-8 col-md-10"> 
            <?php
            $wpcrl_form_settings = get_option('wpcrl_form_settings');

            $resetpassword_form_heading = empty($wpcrl_form_settings['wpcrl_resetpassword_heading']) ? 'Reset Password' : $wpcrl_form_settings['wpcrl_resetpassword_heading'];
            $resetpassword_button_text = empty($wpcrl_form_settings['wpcrl_resetpassword_button_text']) ? 'Reset password' : $wpcrl_form_settings['wpcrl_resetpassword_button_text'];
            $returntologin_button_text = empty($wpcrl_form_settings['wpcrl_returntologin_button_text']) ? 'Return to Login' : $wpcrl_form_settings['wpcrl_returntologin_button_text'];           

            ?>
            <h3><?php _e($resetpassword_form_heading, $this->plugin_name); ?></h3>

            <div id="wpcrl-resetpassword-loader-info" class="wpcrl-loader" style="display:none;">
                <img src="<?php echo plugins_url('images/ajax-loader.gif', dirname(__FILE__)); ?>"/>
                <span><?php _e('Please wait ...', $this->plugin_name); ?></span>
            </div>
            <div id="wpcrl-resetpassword-alert" class="alert alert-danger" role="alert" style="display:none;"></div>

            <form name="wpcrlResetPasswordForm" id="wpcrlResetPasswordForm" method="post">
                <?php
                // check if the url has token
                if (!$is_url_has_token) :

                    ?>
                    <div class="form-group">
                        <label for="email"><?php _e('Email', $this->plugin_name); ?></label>
                        <input type="text" class="form-control" name="wpcrl_rp_email" id="wpcrl_rp_email" placeholder="Email">
                    </div>
                    <input type="hidden" name="wpcrl_current_url" id="wpcrl_current_url" value="<?php echo get_permalink(); ?>" />
                    <?php
                else:

                    ?>
                    <div class="form-group">
                        <label for="newpassword"><?php _e('New password', $this->plugin_name); ?></label>
                        <input type="password" class="form-control" name="wpcrl_newpassword" id="wpcrl_newpassword" placeholder="New Password">
                    </div>
                    <input type="hidden" name="wpcrl_rp_email" id="wpcrl_rp_email" value="<?php echo $_GET['email'] ?>" />
                    <input type="hidden" name="wpcrl_reset_password_token" id="wpcrl_reset_password_token" value="<?php echo $_GET['wpcrl_reset_password_token']; ?>" />

                <?php
                endif;

                ?>
                <?php
                // this prevent automated script for unwanted spam
                if (function_exists('wp_nonce_field'))
                    wp_nonce_field('wpcrl_resetpassword_action', 'wpcrl_resetpassword_nonce');

                ?>
                <button type="submit" class="btn btn-primary"><?php _e($resetpassword_button_text, $this->plugin_name); ?></button>
                <button type="button" id="btnReturnToLogin" class="btn btn-primary"><?php _e($returntologin_button_text, $this->plugin_name); ?></button>

            </form>

        </div>
    </div>
</div>
