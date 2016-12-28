<tr valign="top">
    <th scope="row">
        <label><?php _e("Confirmation email not sent", $this->plugin_name); ?></label>
    </th>
    <td>
        <input type="text" name="wpcrl_email_error_message" value='<?php echo (!empty($wpcrl_settings['wpcrl_email_error_message'])) ? $wpcrl_settings['wpcrl_email_error_message'] : ''; ?>' class='wide' />
        <em><?php _e("This message will be shown if confirmation email is not sent to the user (Default: Could not able to send the email notification.)", $this->plugin_name); ?></em>

    </td>
</tr>

<tr valign="top">
    <th scope="row">
        <label><?php _e("Account activated", $this->plugin_name); ?></label>
    </th>
    <td>
        <input type="text" name="wpcrl_account_activated_message" value='<?php echo (!empty($wpcrl_settings['wpcrl_account_activated_message'])) ? $wpcrl_settings['wpcrl_account_activated_message'] : ''; ?>' class='wide' />
        <em><?php _e("This message will be shown only once when user activates the account from email. (Default: Your account has been activated. You can login now.)", $this->plugin_name); ?></em>

    </td>
</tr>

<tr valign="top">
    <th scope="row">
        <label><?php _e("Account Not activated", $this->plugin_name); ?></label>
    </th>
    <td>
        <input type="text" name="wpcrl_account_notactivated_message" value='<?php echo (!empty($wpcrl_settings['wpcrl_account_notactivated_message'])) ? $wpcrl_settings['wpcrl_account_notactivated_message'] : ''; ?>' class='wide' />
        <em><?php _e("This message will be shown only when user tries to login without activating the account. (Default: Your account has not been activated yet, please verify your email first.)", $this->plugin_name); ?></em>

    </td>
</tr>

<tr valign="top">
    <th scope="row">
        <label><?php _e("Login Success", $this->plugin_name); ?></label>
    </th>
    <td>
        <input type="text" name="wpcrl_login_success_message" value='<?php echo (!empty($wpcrl_settings['wpcrl_login_success_message'])) ? $wpcrl_settings['wpcrl_login_success_message'] : ''; ?>' class='wide' />
        <em><?php _e("This message will be shown on login success. (Default: You are successfully logged in.)", $this->plugin_name); ?></em>


    </td>
</tr>


<tr valign="top">
    <th scope="row">
        <label><?php _e("Login Error", $this->plugin_name); ?></label>
    </th>
    <td>
        <input type="text" name="wpcrl_login_error_message" value='<?php echo (!empty($wpcrl_settings['wpcrl_login_error_message'])) ? $wpcrl_settings['wpcrl_login_error_message'] : ''; ?>' class='wide' />
        <em><?php _e("This message will be shown on login error. (Default: Username or password is incorrect.)", $this->plugin_name); ?></em>


    </td>
</tr>
<tr valign="top">
    <th colspan="2">
        <h2>Password reset form messages</h2>
    </th>
</tr>
<tr valign="top">
    <th scope="row">
        <label><?php _e("Password Reset link sent", $this->plugin_name); ?></label>
    </th>
    <td>
        <input type="text" name="wpcrl_password_reset_link_sent_message" value='<?php echo (!empty($wpcrl_settings['wpcrl_password_reset_link_sent_message'])) ? $wpcrl_settings['wpcrl_password_reset_link_sent_message'] : ''; ?>' class='wide' />
        <em><?php _e("This message will be shown on reset password form when reset link is sent to the user. (Default: Password reset link not sent.)", $this->plugin_name); ?></em>


    </td>
</tr>

<tr valign="top">
    <th scope="row">
        <label><?php _e("Password Reset link not sent", $this->plugin_name); ?></label>
    </th>
    <td>
        <input type="text" name="wpcrl_password_reset_link_notsent_message" value='<?php echo (!empty($wpcrl_settings['wpcrl_password_reset_link_notsent_message'])) ? $wpcrl_settings['wpcrl_password_reset_link_notsent_message'] : ''; ?>' class='wide' />
        <em><?php _e("This message will be shown on reset password form when reset link is not sent to the user. (Default: A link to reset your password has been sent to you.)", $this->plugin_name); ?></em>


    </td>
</tr>

<tr valign="top">
    <th scope="row">
        <label><?php _e("Invalid Email on reset password form", $this->plugin_name); ?></label>
    </th>
    <td>
        <input type="text" name="wpcrl_password_reset_invalid_email_message" value='<?php echo (!empty($wpcrl_settings['wpcrl_password_reset_invalid_email_message'])) ? $wpcrl_settings['wpcrl_password_reset_invalid_email_message'] : ''; ?>' class='wide' />
        <em><?php _e("This message will be shown on reset password form when invalid email is entered. (Default: We cannot identify any user with this email.)", $this->plugin_name); ?></em>


    </td>
</tr>

<tr valign="top">
    <th scope="row">
        <label><?php _e("Password changes success message", $this->plugin_name); ?></label>
    </th>
    <td>
        <input type="text" name="wpcrl_password_reset_success_message" value='<?php echo (!empty($wpcrl_settings['wpcrl_password_reset_success_message'])) ? $wpcrl_settings['wpcrl_password_reset_success_message'] : ''; ?>' class='wide' />
        <em><?php _e("This message will be shown when password has been changed successfully. (Default: Your password has been changed successfully.)", $this->plugin_name); ?></em>


    </td>
</tr>

<tr valign="top">
    <th scope="row">
        <label><?php _e("Invalid password reset token", $this->plugin_name); ?></label>
    </th>
    <td>
        <input type="text" name="wpcrl_invalid_password_reset_token_message" value='<?php echo (!empty($wpcrl_settings['wpcrl_invalid_password_reset_token_message'])) ? $wpcrl_settings['wpcrl_invalid_password_reset_token_message'] : ''; ?>' class='wide' />
        <em><?php _e("This message will be shown when the token in invalid. (Default: This token appears to be invalid.)", $this->plugin_name); ?></em>


    </td>
</tr>