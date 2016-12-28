=== WP-Paginate ===
Contributors: StudioFuel, emartin24
Tags: paginate, pagination, navigation, page, wp-paginate, comments, rtl, seo, usability
Requires at least: 2.6.0 (2.7.0 for comments pagination)
Tested up to: 4.1
Stable tag: 1.3.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
	
WP-Paginate is a simple and flexible pagination plugin which provides users with better navigation on your WordPress site.

== Description ==

= Latest News =
> I am happy to announce that, effective December 15, 2014, Noah Cinquini and the team at Studio Fuel (studiofuel.com) have offered to take over maintenance, support, and development of WP-Paginate! A huge thanks to Noah and all of the people that have used WP-Paginate over the last 5 years! 

WP-Paginate is a simple and flexible pagination plugin which provides users with better navigation on your WordPress site.

In addition to increasing the user experience for your visitors, it has also been widely reported that pagination increases the SEO of your site by providing more links to your content.

Starting in version 1.1, WP-Paginate can also be used to paginate post comments!

Translations: http://plugins.svn.wordpress.org/wp-paginate/I18n (check the version number for the correct file)
	
== Installation ==

*Install and Activate*

1. Unzip the downloaded WP-Paginate zip file
2. Upload the `wp-paginate` folder and its contents into the `wp-content/plugins/` directory of your WordPress installation
3. Activate WP-Paginate from Plugins page

*Implement*

For posts pagination:
* Open the theme files where you'd like pagination to be used. Depending on your theme, this could be in a number of files, such as `index.php`, `archive.php`, `categories.php`, `search.php`, `tag.php`, or the `functions.php` file(s).The `twentyeleven` theme places the pagination code in `functions.php` in the `twentyeleven_content_nav()` function.

Examples:

For the `twentytwelve` theme, in `index.php`, replace:

	<?php twentytwelve_content_nav( 'nav-below' ); ?>

With:

	<?php if(function_exists('wp_paginate')) {
		wp_paginate();
	}
	else {
		twentytwelve_content_nav( 'nav-below' );
	}
	?> 

For the `twentythirteen` theme, in `index.php`, replace:

	<?php twentythirteen_paging_nav(); ?>

With:

	<?php if(function_exists('wp_paginate')) {
		wp_paginate();
	}
	else {
		twentythirteen_paging_nav();
	}
	?> 

For the `twentyfourteen` theme, in `index.php`, replace:

	twentyfourteen_paging_nav();

With:

	if(function_exists('wp_paginate')) {
		wp_paginate();
	}
	else {
		twentyfourteen_paging_nav();
	} 

For comments pagination:
1) Open the theme file(s) where you'd like comments pagination to be used. Usually this is the `comments.php` file.

2) Replace your existing `previous_comments_link()` and `next_comments_link()` code block with the following:

	<?php if(function_exists('wp_paginate_comments')) {
		wp_paginate_comments();
	} ?>


*Configure*

1) Configure the WP-Paginate settings, if necessary, from the WP-Paginate option in the Settings menu

2) The styles can be changed with the following methods:

* Add a `wp-paginate.css` file in your theme's directory and place your custom CSS there
* Add your custom CSS to your theme's `styles.css`
* Modify the `wp-paginate.css` file in the wp-paginate plugin directory

*Note:* The first two options will ensure that WP-Paginate updates will not overwrite your custom styles.

*Upgrading*

To 1.1.1+:

* Update WP-Paginate settings, change `Before Markup` to `<div class="navigation">`
* Update `wp-paginate.css`, change `.wp-paginate ol` to `.wp-paginate`

== Frequently Asked Questions ==

= How can I override the default pagination settings? =

The `wp_paginate()` and `wp_paginate_comments()` functions each takes one optional argument, in query string format, which allows you to override the global settings. The available options are:

* title - The text/HTML to display before the pagination links
* nextpage - The text/HTML to use for the next page link
* previouspage - The text/HTML to use for the previous page link
* before - The text/HTML to add before the pagination links
* after - The text/HTML to add after the pagination links
* empty - Display before markup and after markup code even when the page list is empty
* range - The number of page links to show before and after the current page
* anchor - The number of links to always show at beginning and end of pagination
* gap - The minimum number of pages before a gap is replaced with an ellipsis (...)

You can even control the current page and number of pages with:

* page - The current page. This function will automatically determine the value
* pages - The total number of pages. This function will automatically determine the value

Example (also applies to `wp_paginate_comments()`):

	<?php if(function_exists('wp_paginate')) {
		wp_paginate('range=4&anchor=2&nextpage=Next&previouspage=Previous');
	} ?>


= How can I style the comments pagination differently than the posts pagination? =

When calling `wp_paginate_comments()`, WP-Paginate adds an extra class to the `ol` element, `wp-paginate-comments`.

This allows you to use the `.wp-paginate-comments` styles, already in `wp-paginate.css`, to override the default styles.

== Screenshots ==

1. An example of the WP-Paginate display using the default options and styling
2. The WP-Paginate admin settings page


== Upgrade Notice ==

N/A

== Changelog ==

= 1.3.1 =
* Fixed bug that prevented a wp-paginate.css stylesheet from loading from a child theme (reported by sunamumaya)
* Tested plugin against WordPress 4.1

= 1.3 =
* Plugin ownership transfered to Studio Fuel (http://studiofuel.com) - no functional changes were made
* Tested plugin against WordPress 4.0.1

= 1.2.6 =
* Removed final closing PHP tag
  Github pull request via DeanMarkTaylor
* Do not add the title element if the title is empty
  Github pull request via Claymm / chaika-design

= 1.2.5 =
* Remove PHP4 support to resolve PHP Strict warning
  Github pull request via DeanMarkTaylor
* Test with latest version of WordPress

= 1.2.4 =
* Ensure pagination of posts when wp_paginate() is called
  Github pull request via whacao
* Support loading on https pages (plugin now requires WordPress 2.6+)
  Github pull request via hadvig 

= 1.2.3 =
* Fixed deprecated parameter to the WordPress add_options_page() function
  Github pull request via alexwybraniec

= 1.2.2 =
* Fixed a XSS vulnerability reported by Andreas Schobel (@aschobel)

= 1.2.1 =
* Added is_rtl function check to prevent errors with older version of WordPress

= 1.2 =
* Added RTL language support
* Fixed comments pagination bug
* Changed language domain name from wp_paginate to wp-paginate (this will affect translation file names)

= 1.1.2 =
* Fixed comment pagination bug (nested comments caused blank page)
* Enabled HTML for Pagination Label, Previous Page, and Next Page
* Localization changes were made, Translations need to be updated

= 1.1.1 =
* Changed output to include `wp-paginate` and `wp-paginate-comments` class names on the `ol` element
* Changed the `before` option from `<div class="wp-paginate">` to `<div class="navigation">`
* Added `.wp-paginate-comments` styles to `wp-paginate.css`
* Changed styles in `wp-paginate.css`

= 1.1 =
* Added `wp_paginate_comments()` function for pagination of post comments

= 1.0.1 =
* Added I18n folder and wp-paginate.pot file
* Fixed some internationalization and spelling errors
* Updated readme.txt and added more details

= 1.0 =
* Initial release