=== WP Custom Register Login ===
Contributors: jenis_patel, Myself.Neelkanth
Tags: custom registration, responsive, custom login, custom signup, ajax login, ajax signup, signup, signin, register, registration login, reset password, forgot password
Requires at least: 3.0.1
Tested up to: 4.4
Stable tag: 2.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allows you to add fully customizable ajax powered responsive login, registration and password reset forms anywhere on your website.

== Description ==

WP Custom Register Login is for the sites that need customized user logins or registrations and would like to avoid the classic wordpress login and registration pages, this plugin provides the capability of placing a shortcode anywhere on the website.
It also provides the feature to enable or disable the setting from backend for automating the new account activation via user email verification.
With WP Custom Register Login, you can easily make use of login and registration forms within any responsive wesbsite.


= Some of the features =

* AJAX-powered login and registration, no screen refreshes!
* Fully responsive design using Bootstrap.
* Customize your redirect URL after successful login/logout to redirect your users to a custom URL or page.
* Easily customize your login/registration form heading as well as the button text from the backend.
* Custom registration email notification template that can be managed from backend.
* Now your visitors can login or register from the page you've set-up.
* Easy to use : Set up login and registration form using shortcodes.
* Registration notifications emails sent to the registered user and website admin.
* Minimize spam signups via user's email verification.

= New exciting features in version 2.0.0 =

* Enable/Disable password reset feature to enable registered user to reset the password through a user verification email and secret authentication token.
* Enable/Disable numbered Captcha on registration page.
* Fully Customizable notification messages on login, registration and forgot password forms.
* Fully customizable emails for login, registration and password reset feature.
* Completely new tabbed settings page for easy settings management.

= New exciting features in version 1.1.0 =

* Automatic new user account confirmation and activation via verification email.
* Easily enable/disable new user email confirmation setting from admin backend.


Just create a normal WordPress page, and use the following shortcodes:

* For login form: `[wpcrl_login_form]`
* For registration form: `[wpcrl_register_form]`

= Usage =

Steps for creating a login or register page.

1. Create a page
1. Add the following shortcode `[wpcrl_login_form]` or `[wpcrl_register_form]`
1. Publish/Update the page.

Steps for using in theme files:

1. Use the shortcode using `do_shortcode()` wherever you want to show your login or
registration form.

For example : `<?php echo do_shortcode("[wpcrl_login_form]"); ?>`


= Up coming features in future releases: =

* Admin setting to show login and registration form in modal popup.
* Provision to use email address as username.
* Customize your form by choosing fields to show from backend.
* Customize form validations from backend.
* Support internationalization.

== Installation ==

1. Download `wp-custom-register-login.zip` and upload it to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Use the provided shortcodes anywhere on your website.


== Screenshots ==

1. Displaying the login form on front end
2. Displaying the registration form
3. Registration form with validations
4. Email confirmation message
5. Reset password
6. Backend Settings with tabs

== Changelog ==

= 2.0.0 =
* Enable/Disable password reset feature to enable registered user to reset the password through a user verification email and secret authentication token.
* Enable/Disable Captcha on registration page.
* Fully Customizable notification messages on login, registration and forgot password forms.
* Fully customizable emails for login, registration and password reset feature.
* Completely new tabbed settings page for easy settings management.

= 1.1.0 =
* New account confirmation and activation via verification email.
* Enable/disable account confirmation and activation setting from admin settings.
* Minor bug fixes.

== Upgrade Notice ==

= 2.0.0 =
* This is a major update and includes many new features like reset password, numbered captcha etc. After updating the plugin, make sure to deactivate and then reactivate the plugin. Earlier WPCRL settings will be replaced with defaults.

= 1.1.0 =
* Now the plugin provides a new backend setting to enable/disable new user's email address confirmation.