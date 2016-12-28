<div class="wrap">
    <h1><?php _e('WP Custom Registration Login', $this->plugin_name); ?></h1>
    <div id="poststuff" class="">

        <div id="post-body">
            <div id="post-body-content">
                <form method="post" action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>">
                    <h2><strong><?php _e("General Settings", $this->plugin_name); ?></strong></h2>


                    <h2><?php _e("Redirection Settings", $this->plugin_name); ?></h2>
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row">
                                <label><?php _e("Global Login Redirect", $this->plugin_name); ?></label>
                            </th>
                            <td>

                                <?php
                                $selected = (empty($wpcrl_settings['wpcrl_login_redirect']) || $wpcrl_settings['wpcrl_login_redirect'] == '-1') ? '' : $wpcrl_settings['wpcrl_login_redirect'];
                                wp_dropdown_pages($args = array('name' => 'wpcrl_login_redirect', 'selected' => $selected, 'show_option_none' => 'Same page', 'option_none_value' => '-1'));

                                ?>
                                <br>
                                <em><?php _e("Redirect the user to a specific page after login (Default: Same page keeps the user on the same page after login).", $this->plugin_name); ?></em>


                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label><?php _e("Global Logout Redirect", $this->plugin_name); ?></label>
                            </th>
                            <td>
                                <?php
                                $selected = (empty($wpcrl_settings['wpcrl_logout_redirect']) || $wpcrl_settings['wpcrl_logout_redirect'] == '-1') ? '' : $wpcrl_settings['wpcrl_logout_redirect'];
                                wp_dropdown_pages($args = array('name' => 'wpcrl_logout_redirect', 'selected' => $selected, 'show_option_none' => 'Same page', 'option_none_value' => '-1'));

                                ?>
                                <br>
                                <em><?php _e("Redirect the user to a specific page after logout (Default: Same page keeps the user on the same page on logout).", $this->plugin_name); ?></em>


                            </td>
                        </tr>


                    </table>

                    <h2><?php _e("Display Settings", $this->plugin_name); ?></h2>
                    <table class="form-table">

                        <tr valign="top">
                            <th scope="row">
                                <label><?php _e("Sign Up form heading", $this->plugin_name); ?></label>
                            </th>
                            <td>
                                <input type="text" name="wpcrl_signup_heading" value='<?php echo (!empty($wpcrl_settings['wpcrl_signup_heading'])) ? $wpcrl_settings['wpcrl_signup_heading'] : ''; ?>' class='wide' />
                                <em><?php _e("Enter here the heading of the Sign Up from (Default: Register)", $this->plugin_name); ?></em>


                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row">
                                <label><?php _e("Sign In form heading", $this->plugin_name); ?></label>
                            </th>
                            <td>
                                <input type="text" name="wpcrl_signin_heading" value='<?php echo (!empty($wpcrl_settings['wpcrl_signin_heading'])) ? $wpcrl_settings['wpcrl_signin_heading'] : ''; ?>' class='wide' />
                                <em><?php _e("Enter here the heading of the Sign In from (Default: Login)", $this->plugin_name); ?></em>


                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row">
                                <label><?php _e("Sign Up button text", $this->plugin_name); ?></label>
                            </th>
                            <td>
                                <input type="text" name="wpcrl_signup_button_text" value='<?php echo (!empty($wpcrl_settings['wpcrl_signup_button_text'])) ? $wpcrl_settings['wpcrl_signup_button_text'] : ''; ?>' class='wide' />
                                <em><?php _e("Enter here the text of the Sign Up button (Default: Register)", $this->plugin_name); ?></em>


                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row">
                                <label><?php _e("Sign In button text", $this->plugin_name); ?></label>
                            </th>
                            <td>
                                <input type="text" name="wpcrl_signin_button_text" value='<?php echo (!empty($wpcrl_settings['wpcrl_signin_button_text'])) ? $wpcrl_settings['wpcrl_signin_button_text'] : ''; ?>' class='wide' />
                                <em><?php _e("Enter here the text of the Sign In button (Default: Login)", $this->plugin_name); ?></em>


                            </td>
                        </tr>


                    </table>

                    <h2><?php _e("Form Settings", $this->plugin_name); ?></h2>
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row">
                                <label><?php _e("Enable New User Email Confirmation", $this->plugin_name); ?></label>
                            </th>
                            <td>

                                <input  type="checkbox" name="wpcrl_user_email_confirmation" value= '1' class='wide' <?php echo (!empty($wpcrl_settings['wpcrl_user_email_confirmation']) && $wpcrl_settings['wpcrl_user_email_confirmation'] == '1' ) ? 'checked="checked"' : ''; ?> />
                                
                                <em><?php _e("Use this feature, if you want to make sure new user must confirm his/her email address at the time of registration.", $this->plugin_name); ?></em>


                            </td>
                        </tr>


                    </table>

                    <h2><?php _e("Email Notification Settings", $this->plugin_name); ?></h2>

                    <table class="form-table">

                        <tr valign="top">
                            <th>
                                <label><?php _e("Want admin to receive new user notification email?", $this->plugin_name); ?></label>
                            </th>
                            <td>
                                <input style="margin:0px; padding:0px; width:auto;" type="checkbox" name="wpcrl_admin_email_notification" value='1' class='wide' <?php echo (!empty($wpcrl_settings['wpcrl_admin_email_notification']) && $wpcrl_settings['wpcrl_admin_email_notification'] == '1' ) ? 'checked="checked"' : ''; ?> />
                            </td>
                        </tr>

                        <tr valign="top">
                            <th>
                                <label><?php _e("Subject", $this->plugin_name); ?></label>
                            </th>
                            <td>
                                <?php
                                if (empty($wpcrl_settings['wpcrl_notification_subject'])) {
                                    $wpcrl_settings['wpcrl_notification_subject'] = __('Welcome to %BLOGNAME%', $this->plugin_name);
                                }

                                ?>
                                <input type="text" name="wpcrl_notification_subject" value='<?php echo (!empty($wpcrl_settings['wpcrl_notification_subject'])) ? $wpcrl_settings['wpcrl_notification_subject'] : ''; ?>' class='wide' />
                                <em><?php _e("<code>%USERNAME%</code> will be replaced with a username.", $this->plugin_name); ?></em><br />
                                <em><?php _e("<code>%BLOGNAME%</code> will be replaced with the name of your blog.", $this->plugin_name); ?></em>
                                <em><?php _e("<code>%BLOGURL%</code> will be replaced with the url of your blog.", $this->plugin_name); ?></em>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th>
                                <label><?php _e("Message", $this->plugin_name); ?></label>
                            </th>
                            <td>
                                <?php
                                if (empty($wpcrl_settings['wpcrl_notification_message'])) {

                                    $wpcrl_settings['wpcrl_notification_message'] = __(
                                        'Thank you for registering on %BLOGNAME%.
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
The team at %BLOGNAME%'
                                        , $this->plugin_name);
                                }

                                ?>
                                <textarea name="wpcrl_notification_message" class='wide' style="width:100%; height:250px;"><?php echo $wpcrl_settings['wpcrl_notification_message'] ?></textarea>
                                <em><?php _e("<code>%USERNAME%</code> will be replaced with a username.", $this->plugin_name); ?></em><br />
                                <em><?php _e("<code>%BLOGNAME%</code> will be replaced with the name of your blog.", $this->plugin_name); ?></em>
                                <em><?php _e("<code>%BLOGURL%</code> will be replaced with the url of your blog.", $this->plugin_name); ?></em>
                            </td>
                        </tr>
                    </table>

                    <div>
                        <input type="hidden" name="wpcrlsubmitted" value="1" />
                        <input type="hidden" name="_nonce" value="<?php echo wp_create_nonce('wpcrl-admin' . get_current_user_id()); ?>" />
                        <p class="submit">
                            <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
                        </p>
                    </div>		
                </form>
            </div>
        </div>
    </div>
</div>