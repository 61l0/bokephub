=== Members Restricted Access ===

Contributors: mcostales84
Donate Link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=VWWTUHZWRS4DG
Tags: restrict access, members, visitor, restric by role
Requires at least: 3.0.1
Tested up to: 4.4.2
Stable tag: 3.0
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin creates shortcodes to restrict the access to users (visitors, members or by an specific role). You can also add a shortcode and use nested shortcodes.

== Description ==

This plugin create shortcodes to restrict the access to Visitors, Members or specific role. You can create sections in your page/post only visible to members (logged users), visitors or for an specific role.

To use you need to create the content and wrap it with these shortcodes: [visitor], [member] or [showifrole].

* You can use the parameters 'is' and 'msg'. If you not specify them, the default value will be use instead.
* The paramater 'is' is use it with the shortcode [showifrole] to check for that specific user. The default value is 'administrator'.
* And 'msg' is the message the system is going to show. You can use html code with single apostrophes ('). The default value is null.

Examples:

'[visitor]
   content only visible to visitors
[/visitor]

[member msg="You need to <a href='/wp-login'>Login</a> to see this content."]
   content only visible to members
[/member]

[showifrole is=administrator]
   content only visible for administrators
[/showifrole]

[showifrole is=author msg="You don't have the required role."]
   content only visible for authors
[showifrole]'

== Installation ==

To install this plugin just follow the standard procedure.

or

1. Upload `members-restricted-access.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Any question? =

Send me an email at mcostales@jumptoweb.com and I will answer you as soon as I can.

== Changelog ==

= 3.0 =
- Now you can use nested shortcodes, so you can use a any shortcode inside our shortcode verification.

= 2.1 =
- Add the parameters for message in the members, visitors and showifrole shortcodes.
- Add a default value for 'is' and 'msg'.

= 2.0 =
- Added the shortcode to check by role.

= 1.0 =
- Just launch the plugin!
