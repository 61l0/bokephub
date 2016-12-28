=== Post Views Count (Support caching plugins!)===
Contributors: juliobox
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=KJGT942XKWJ6W
Tags: popular, views, view, count, widget, stats, popular post
Requires at least: 3.1
Tested up to: 4.4
Stable tag: trunk

This plugin counts views for post and pages, shortcodes [post_view] & [most_view] are available, also a widget "Most Viewed Posts" is.

== Description ==

IMPORTANT: 3.0 supports cahing plugins!

When someone visits a post or page (not a search engine bot crawler), it adds 1 count views to its counter.

But this is not all, the counters are split in days, weeks, months, years, so you can see stats and display any stat day.

Then you can display this views with the provided shortcode [post_view] or use the widget to display a top (or less) posts list.

You can also use [most_view] in a post/page to get a top (or less) post most viewed.

If you were using another kind of plugin before and don't want to lose your counters, this plugin can keep them!


== Installation ==

1. Upload the *"baw-post-views-count"* to the *"/wp-content/plugins/"* directory
1. Activate the plugin through the *"Plugins"* menu in WordPress
1. Go to settings page
1. You can add the [post_view] shortcode to your theme template or directly in a post/page or add the widget.
1. You can also use [most_view] in a post/page to get a top (or less, see FAQ) post most viewed


== Frequently Asked Questions ==

= How to use/display it? =

1. [post_view] will display the counter for the actual post, custom post or page.
1. [post_view id="123"] will display the counter for the post/page ID "123".
1. [post_view time="day"] will display the count view of the day. So same for "week" will display count view for this week.
1. [post_view time="day" date="20120213"] will display the count view for this particular day: 13th, feb 2012. "date" can not be use alone, always use "time" in the same time.
1. [post_view time="month" date="201102"] will display the count view for the last year february.
This mean you can play with date in your template, kind of :
1. echo do_shortcode( '[post_view time="month" date="' . date( 'Ym', mktime( 0, 0, 0, date("n")-1, date("j"), date("Y") ) . '"]' ); // This will always display the count views of the month of last year.

- A shortcode named "most_view" (or "most_views" or "most_viewed") is available.
Same as [post_view], you can set "time" and "date" but also this parameters:
1. "before": A text before the list; default is empty
1. "after": A text after the list; default is empty
1. "show": Do we show counters ? 1= yes, 0=no; default is 1.
1. "number": How many posts to display ?; default is 10.
1. "ul_class": A CSS class
1. "li_class": A CSS class
1. "order": 'desc' or 'asc'
1. "author": an author ID or author name.
1. "post_type": post types list, comma separated, default=settings
Example:
- [most_view number="3" show="0" before="Top 3!"]
Will display the top 3 posts, no counters displayed, with title "Top 3!"

= What are the hooks ? =

1. *baw_count_views_timings*: `apply_filters( 'baw_count_views_timings', array( 'all'=>'', 'day'=>'Ymd', 'week'=>'YW', 'month'=>'Ym', 'year'=>'Y' ) );`
You can add/remove timings with this filter, so if you do not need stats, or just do not need Year stat, you can remove them to avoid the creation of meta data unused in DB.
Example:
`function remove_timing_for_bawpvc( $timings )
{
	unset( $timings['year'] ); // remove Year stat/meta
	return $timings;
}
add_filter( 'baw_count_views_timings', 'remove_timing_for_bawpvc' );`
or
`function remove_timing_for_bawpvc( $timings )
{
	return array( 'all' => '' ); // keep only all counts, no other stats
}
add_filter( 'baw_count_views_timings', 'remove_timing_for_bawpvc' );`

1. *baw_count_views_meta_key*: `apply_filters( 'baw_count_views_meta_key', '_count-views_' . $time . $date, $time, $date );`
This hook is present to give compatibility with any old count plugins that had already record some counts for your blog.
Example of use with wp-postviews: 
Its meta_key is "views" so i do this:
`function modify_metakey_for_bawpvc( $data, $time )
{
	return $time == 'all' ? 'views' : $data;
}
add_filter( 'baw_count_views_meta_key', 'modify_metakey_for_bawpvc', 10, 2 );`
This return "views" if the selected stats count is "all" ! You can also play with $time and $date to do some checkings.

1. *baw_count_views_count*: `apply_filters( 'baw_count_views_count', $count, $meta_key, $time, $date, $id );`
You can modify the $count value here, depending on time, date or meta_key. I added this in case of ...
You can avoid recording count on different date or cheat on counting x)

1. *baw_count_views_count_action*: `do_action( 'baw_count_views_count', $count, $meta_key, $time, $date, $id );`
Same as above, you can run your own code here.

1. *widget_title* This is a WordPress hook, see codex or core code.

1. *baw_count_views_capa_role*: `apply_filters( 'baw_count_views_capa_role', 'edit_posts' );`
You can modify the capatibility or rolle needed to see and modify the posts views stats.
Example:
`function bawpv_count_views_capa_role( $capa )
{
	return 'administrator'; 
	// or
	// return 'manage_options';
}

1. *baw_count_views_render_post_columns*: `apply_filters( 'baw_count_views_render_post_columns', $post_id );`
You can add (or remove?) more then only the "all" count stats in post page list in admin panel.
Example:
`function baw_count_views_render_post_columns( $post_id )
{
	echo '<br />';
	echo 'Month: ' . (int)get_post_meta( $post_id, '_count-views_month-'.date(mY), true );
}
Will display the month stats below the default "all" count.

1. *baw_count_views_widget_post_types*: `apply_filters( 'baw_count_views_widget_post_types', $post_types );`
You can hack the post types displayed in widget, whatever the settings.

== Screenshots ==

1. Shortcodes in post edition
1. Result in front end post with [post_view]
1. Widget demo
1. Shortcode [most_view] demo, in a page
1. Settings page
1. Meta box in each post type
1. Same but i clicked on a value, i can cheat on it!
1. Column in post list, each post type too

== Changelog ==

= 3.0.2 =
* 08 oct 2015
* Fix (bool) false/true in footer, my bad, too quick!

= 3.0.1 =
* 08 oct 2015
* Fix bug double counter

= 3.0 =
* 07 oct 2015
* This version supports now all caching plugins, including the premium WP Rocket.

= 2.19.13 =
* 11 sep 2015
* Remove the "time between counts" options, it was a bad idea believe me. Nor logical and badly coded.

= 2.19.12 =
* 01 sep 2015
* Widget was broken

= 2.19.11 =
* 04 nov 2012
* My bad, sorry for this, i broke any sites because of 2.19.10, fixed now ...

= 2.19.10 =
* 04 nov 2012
* Add a new filter named 'baw_count_views_widget_post_types' to filter post types in the widget.

= 2.19.9 =
* 01 nov 2012
* Widget only shows post type fro settings, not only posts.

= 2.19.8 =
* 01 nov 2012
* Remove the stats informations for roles not allowed with 'baw_count_views_capa_role' (Default: edit_posts)

= 2.19.7 =
* 31 oct 2012
* Added, you can filter the minimum capability or set a role to see and modify the posts view stats. See FAQ (baw_count_views_capa_role)
* Added, a new action hook to add (or remove?) more then only the "all" count stats in post page list in admin panel. See FAQ (baw_count_views_render_post_columns)

= 2.19.6 =
* 30 oct 2012
* Bug fix, a php warning appears when using the [most_views] with the new parameter "post_type"

= 2.19.5 =
* 29 oct 2012
* Bug fix, the counter was not under the content anymore. Sorry !

= 2.19.4 =
* 29 oct 2012
* Code improvment
* Fix a bug in [most_views] shortcode, only 'posts' types were displayed, now a "post_type" parameter can be used.

= 2.19.3 =
* 29 sep 2012
* Try to fix a "WP-To-Twitter" bug which add some count from nowhere, so "WordPress" has been added as a forbidden bot, let's try.
* Code improvment

= 2.19.2 =
* 12 sep 2012
* Fix : if you were using a filter on 'baw_count_views_meta_key', my own count was not updated.

= 2.19.1 =
* 13 aug 2012
* Bug fix on metabox

= 2.19 =
* 12 aug 2012
* Change the post type request to display all CPT (Public or Not, displayed)

= 2.18 =
* 31 jul 2012
* Add a screenshot
* Add some NOJS code
* Change input text=>number

= 2.18 =
* 31 jul 2012
* Add the possibility to hack/cheat on values, directly into the metabox on each posts.

= 2.17 =
* 09 jul 2012
* Added a filter for author in both Widget and [most_views] shortcode.

= 2.16.1 =
* 05 jul 2012
* Bug fix since 2.15, orders from widgets and shortcode can be reversed, now it's ok, sorry

= 2.16 =
* 04 jul 2012
* Remove the timer filter, option is available since 2.7
* Add action hook *baw_count_views_count_action*
* Modify some filters behavior

= 2.15 =
* 03 jul 2012
* Add the possibility to reverse the order to create a kind of "Less viewed posts", for widget and [most_views] shortcode

= 2.14 =
* 02 jul 2012
* Fix a bug in [most_view] shortcode, thanks to Hafid pointing me this

= 2.13 =
* 01 jul 2012
* Change : get_post_types() behavior (not a big deal)

= 2.12 =
* 29 jun 2012
* Parent menu "BoiteAWeb" deleted, settings are in settings now.
* Better nonce for reset action
* Some code imp.

= 2.11 =
* 27 jun 2012
* Version renumbering
* Add a remove_filter on "adjacent_posts_rel_link_wp_head" to avoid post counts without been really viewed.
* About page updated (+2 new plugins)

= 2.10 =
* 07 jun 2012
* Improved: Time between counts now includes post's ID
* Time between counts in seconds, not minutes as described.
* Thanks Jacek (Mechlab) pointing me that!

= 2.9.1 =
* 18 may 2012
* Bug fix : counter is always shown even on uncheck post types

= 2.9 =
* 18 may 2012
* Add some checkboxes in settings page to choose which posts can be "counted" 
* Thanks James Fabian again for this idea !

= 2.8.2 =
* 16 may 2012
* Bug fix : Count is ok now, sorry >_<

= 2.8.1 =
* 15 may 2012
* Bug fix : You can not post new posts ... my bad !
* Added l10n for 2.8 (forgot it)

= 2.8 =
* 14 may 2012
* Add a reset checkbox in each meta box of posts to allow people to reset counters
* Add 2 options to avoid admins and/or logged users to increase the counters
* These 2 ideas are from James Fabian, thanks !

= 2.7.1 =
* 11 may 2012
* Missing pictures ...

= 2.7 =
* 20 apr 2012
* 3 new screenshots
* Settings page
* Meta box in each post type to display all counters
* Added a columns in each post type lists, this display the "all time" counter
* Translation FR/EN


= 2.6.4 =
* 17 mar 2012
* Default added filter "baw_count_views_count_filter" removed

= 2.6.3 =
* 17 mar 2012
* Added 2 parameters for the [most_view] shortcode : "ul_class" and "li_class"
* Default CSS class for UL and LI tags in [most_view] shortcode is "pvc"

= 2.6.1 =
* 15 mar 2012
* Removed the "views" word after counter, my bad, it was a test ... :]

= 2.6 =
* 15 mar 2012
* New shortcode [most_view] (or [most_views] or [most_viewed]) to display in a page/post a top posts list, see FAQ for usage
* Modification of the "baw_count_views_count" filter's behavior, not a big deal.

= 2.5 =
* 02 mar 2012
* Default time between 2 counts is now 0

= 2.4 =
* 02 mar 2012
* Better transient managment
* Now you can use "0" as time between vounts instead of 1 second. 
(See FAQ for more information)

= 2.3 =
* 01 mar 2012
* Remove the compatibility with wp-postviews using a query, bad idea, sorry
* Add a filter on "all" count to give more compatibility with ANY "count like" plugin
(See FAQ for more information)

= 2.2 =
* 29 feb 2012
* Add more crawler bots
* Add import compatibility with wp-postviews
* Fix shortcode : [post_view] or [post_views] works

= 2.1 =
* 29 feb 2012
* First release

= 1.0 =
* Not realeased
* There was no stats, just counts all.

== Upgrade Notice ==

None