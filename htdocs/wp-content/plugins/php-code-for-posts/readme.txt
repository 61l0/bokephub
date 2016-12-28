=== PHP Code for posts ===
Contributors: thejfraser
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=SFQZ3KDJ4LQBA
Tags: PHP, allow php, exec php, execute php, php shortcode, php in posts, use php, embed html
Requires at least: 3.3.1
Tested up to: 4.6.0
Stable tag: 2.1.3.1
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add PHP code to your WordPress posts, pages, custom post types and even sidebars using shortcodes

== Description ==

Currently in rapid deployment testing - fixing bugs and quirks with older versions of PHP

PHP Code for posts allows you to add your own PHP code to posts, pages, custom post types (posts) and even sidebars without the need for custom templates

The plugin enables a shortcode and options page so you can add your code to the admin options page and then output it in your post using shortcodes

Multiple code snippets can be used on a post, and multiple posts can use the same code snippet, allowing you to re-use code.

The shortcodes can be used to also display plain HTML content, allowing you to add in iframe, objects, areas and other tags that are removed by the post editor

The plugin also contains a variable array which you can add variables to for use between snippets called $_var and is available though the global variable $PHPPC which is an object, so its $PHPPC::$_vars[]

**Parameter Variable Extraction:** When this option is enabled, you will be able to access the parameters passed to a snippet using their name. For example in the snippet `[php snippet=x param=test=hello]` you will be able to directly access `$test` in your code snippet for the value of "hello", rather than needing to do `$_parameters["test"]`

It is important that the PHP Variable Naming Conventions are followed when using this option otherwise it could have some unexpected results. (see: http://php.net/manual/en/language.variables.basics.php)

= New for 1.1.1 =
The plugin's shortcode can also accept parameters using the param attribute, the value should be a string of name=value pairs, separated by &s, for example `[php snippet=2 param="var1=val1&var2=val2"]`.  Within your snippet, the parameters are assigned a $_parameters array, for example `echo $_parameters["var1"]; //outputs "val1"`

= New for 1.2.0 =
The plugin's snippet editor now has better formatting, and supports AJAX saving for snippet updates (request by eneasgesing)

= New for 2.0.x =
* Refreshed interface
* Custom shortcode name
* Translation support
* Expansion of the parameter system

= New For 2.1.x =

* **Multisite!**

Multisite update comes with more options to make handling your multisite setup simplier.

These options can only be changed on blog id 1 (the master site)

* **New Options**

**Allow Custom Snippets for sub-sites?**

This option allows you to enable or disable the ability for sub-sites to create, edit and use their own snippets

**Allow sharing of snippets between sites**

This option allows you to enable or disable the ability for sub-sites to share their own snippets and to use other sub-site's snippets via the new shortcode

**Allow per-site options for multisite**

This option allows you to enable or disable the ability for sub-sites to change options specific to their site such as the main shortcode, and ajax saving

**Change the multisite Shortcode.**

In keeping with the ability to change the original shortcode, this opion allows you to change the new multisite shortcode to what ever you fancy. Just don't try making it the same as the single site, it wont work!

* **New Shortcode**

With the new multisite comes a new shortcode.

This shortcode allows the loading of shared snippets from other members of your multisite setup.

Simply pass the blog_id and the snippet to load, and if the snippet exists and is shared, it will load it.

By default, blog_id is 1, the master site, and sub sites can always use shared master site snippets

== Installation ==
1. Download the plugin to your computer
1. Extract the contents
1. Upload (via FTP) to a sub folder of the WordPress plugins directory
1. Activate the plugin in the admin dashboard.

== Frequently Asked Questions ==

= You mentioned a variable array to assign variables to for use between snippets, how do I do this? =

To assign variables to the array, use the following code:
` global $PHPPC;
  $PHPPC::$_vars["myvaridentifier"] = $myvar;`

and to read a variable from the array use this code:
` global $PHPPC;
	$myvar = $PHPPC::$_vars["myvaridentifer"];`
Simples!

= AJAX update keeps failing =

One of the main causes of a failed AJAX update is because nothing has actually changed in any of the fields.

= My snippet doesn't work =

One common error is an error in the eval'd code, this is more down to a syntax / parse error in the PHP snippet, rather than the plugin itself, double check your code and make sure its correct.

= Your explanation of the multisite feature doesn't make sense =

Email me your question! I may be able to explain it better!

= No other questions yet! =

:)

== ChangeLog ==

= 1.0 =
* Initial Release
* No changes
= 1.1 =
* Added missing functionality for delete single snippet
* Tested for WP 3.6
* Added class functions for getting and setting shared variables (get_variable and set_variable)
= 1.1.1 =
* added parameters for snippits
= 1.1.2 =
* Fix for php warning handle_extra_shortcode (thanks to paul_martin)
= 1.1.3 =
* Fix for the table not being created in a multi-site installation (thanks to dondela and mediagent)
* Fix for the parameter variables not splitting correctly because of html entity encoding (thanks to eoh1)
= 1.2.0 =
* Ajax saving for updating code snippets (ajax save for initial add still to be implemented) (request by eneasgesing)
* Richer snippet editor using Codemirror (request by eneasgesing)
= 1.3.0 =
* Confirmed support for latest version of WP (4.2.1) just in case you were wondering
= 2.0.0 =
* Refreshed interface
* Custom shortcode name
* Translation support
= 2.0.1 =
* Fixed issue in options class with incompatibility with array accessing a function return i.e. foo()['bar']
= 2.0.2 =
* Based on support queries, I have added in an option to disable ajax saving for snippets
= 2.0.3 =
* Based on feedback, I have removed an anonymous function
= 2.0.4 =
* Fixed issue with parameters not passing through correctly
= 2.0.5 =
* Removed ? : shorthand ifs
= 2.0.6 =
* Fixed issue with compatibility with PHP 5.2
= 2.0.7 =
* Fixed another issue with compatibility with PHP 5.2
= 2.0.8 =
* Fixed the stripping of backslashes from code
= 2.0.9 =
* Fixed another issue with compatibility with PHP 5.2 - seriously, why is anyone using it?!
= 2.0.10 =
* More translation support
= 2.0.11 =
* Notice Error Fix
* Parameter Variable Extraction
= 2.1.0 =
* Multisite! Improved multisite support including shared options and snippets
* Confirmed support for 4.5.1
= 2.1.1 =
* Fixed a massive issue with non-multisites not working (sorry!)
= 2.1.2 =
* Fixed a massive issue with non-multisites not working (sorry!)
= 2.1.3 =
* You asked for an export/import, I made it happen
= 2.1.3.1 =
* removed short array syntax

== Upgrade Notice ==
= 1.0 =
* Nothing :)
= 1.1 =
* Added in missing functionality
* Tested for WP 3.6
= 1.1.3 =
* Multisite Fix
* Parameter Fix
= 2.0.0 =
* Refreshed interface
* Custom shortcode name
* Translation support
= 2.0.1 =
* Bug fix
= 2.0.2 =
* Based on support queries, i have added in an option to disable ajax saving for snippets
* Re-added creating the table if it was missing
= 2.0.3 =
* removal of anonymous function
= 2.0.4 =
* Fixed issue with parameters not passing through correctly
= 2.0.5 =
* Removed ? : shorthand ifs
= 2.0.6 =
* Fixed issue with compatibility with PHP 5.2
= 2.0.7 =
* Fixed another issue with compatibility with PHP 5.2
= 2.0.8 =
* Fixed the stripping of backslashes from code
= 2.0.9 =
* Fixed another issue with compatibility with PHP 5.2
= 2.0.10 =
* More transaction support
= 2.0.11 =
* Notice Error Fix
* Parameter Variable Extraction
= 2.1.0 =
* Multisite Upgrade
= 2.1.1 =
* Fixed a massive issue with non-multisites not working (sorry!)
= 2.1.2 =
* Fixed the part where I accidently disabled the param option
= 2.1.3 =
* You asked for an export/import, I made it happen.

== Screenshots ==
1. The plugin options menu
2. The edit snippet screen
