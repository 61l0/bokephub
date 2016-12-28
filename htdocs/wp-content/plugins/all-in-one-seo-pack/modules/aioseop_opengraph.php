<?php
/**
 * @package All-in-One-SEO-Pack
 */
/**
 * The Opengraph class.
 */
if ( ! class_exists( 'All_in_One_SEO_Pack_Opengraph' ) ) {
	class All_in_One_SEO_Pack_Opengraph extends All_in_One_SEO_Pack_Module {
		var $fb_object_types;
		var $type;

		function __construct() {
			$this->name            = __( 'Social Meta', 'all-in-one-seo-pack' );    // Human-readable name of the plugin
			$this->prefix          = 'aiosp_opengraph_';                        // option prefix
			$this->file            = __FILE__;                                    // the current file
			$this->fb_object_types = Array(
				'Activities'                 => Array(
					'activity' => __( 'Activity', 'all-in-one-seo-pack' ),
					'sport'    => __( 'Sport', 'all-in-one-seo-pack' ),
				),
				'Businesses'                 => Array(
					'bar'        => __( 'Bar', 'all-in-one-seo-pack' ),
					'company'    => __( 'Company', 'all-in-one-seo-pack' ),
					'cafe'       => __( 'Cafe', 'all-in-one-seo-pack' ),
					'hotel'      => __( 'Hotel', 'all-in-one-seo-pack' ),
					'restaurant' => __( 'Restaurant', 'all-in-one-seo-pack' ),
				),
				'Groups'                     => Array(
					'cause'         => __( 'Cause', 'all-in-one-seo-pack' ),
					'sports_league' => __( 'Sports League', 'all-in-one-seo-pack' ),
					'sports_team'   => __( 'Sports Team', 'all-in-one-seo-pack' ),
				),
				'Organizations'              => Array(
					'band'       => __( 'Band', 'all-in-one-seo-pack' ),
					'government' => __( 'Government', 'all-in-one-seo-pack' ),
					'non_profit' => __( 'Non Profit', 'all-in-one-seo-pack' ),
					'school'     => __( 'School', 'all-in-one-seo-pack' ),
					'university' => __( 'University', 'all-in-one-seo-pack' ),
				),
				'People'                     => Array(
					'actor'         => __( 'Actor', 'all-in-one-seo-pack' ),
					'athlete'       => __( 'Athlete', 'all-in-one-seo-pack' ),
					'author'        => __( 'Author', 'all-in-one-seo-pack' ),
					'director'      => __( 'Director', 'all-in-one-seo-pack' ),
					'musician'      => __( 'Musician', 'all-in-one-seo-pack' ),
					'politician'    => __( 'Politician', 'all-in-one-seo-pack' ),
					'profile'       => __( 'Profile', 'all-in-one-seo-pack' ),
					'public_figure' => __( 'Public Figure', 'all-in-one-seo-pack' ),
				),
				'Places'                     => Array(
					'city'           => __( 'City', 'all-in-one-seo-pack' ),
					'country'        => __( 'Country', 'all-in-one-seo-pack' ),
					'landmark'       => __( 'Landmark', 'all-in-one-seo-pack' ),
					'state_province' => __( 'State Province', 'all-in-one-seo-pack' ),
				),
				'Products and Entertainment' => Array(
					'album'   => __( 'Album', 'all-in-one-seo-pack' ),
					'book'    => __( 'Book', 'all-in-one-seo-pack' ),
					'drink'   => __( 'Drink', 'all-in-one-seo-pack' ),
					'food'    => __( 'Food', 'all-in-one-seo-pack' ),
					'game'    => __( 'Game', 'all-in-one-seo-pack' ),
					'movie'   => __( 'Movie', 'all-in-one-seo-pack' ),
					'product' => __( 'Product', 'all-in-one-seo-pack' ),
					'song'    => __( 'Song', 'all-in-one-seo-pack' ),
					'tv_show' => __( 'TV Show', 'all-in-one-seo-pack' ),
					'episode' => __( 'Episode', 'all-in-one-seo-pack' ),
				),
				'Websites'                   => Array(
					'article' => __( 'Article', 'all-in-one-seo-pack' ),
					'blog'    => __( 'Blog', 'all-in-one-seo-pack' ),
					'website' => __( 'Website', 'all-in-one-seo-pack' ),
				),
			);
			parent::__construct();

			$categories = Array(
				'blog'    => __( 'Blog', 'all-in-one-seo-pack' ),
				'website' => __( 'Website', 'all-in-one-seo-pack' ),
				'article' => __( 'Article', 'all-in-one-seo-pack' ),
			);

			$this->help_text = Array(
				"setmeta"                => __( "Checking this box will use the Home Title and Home Description set in All in One SEO Pack, General Settings as the Open Graph title and description for your home page.", 'all-in-one-seo-pack' ),
				"key"                    => __( "Enter your Facebook Admin ID here. You can look up your Facebook ID using this tool http://findmyfbid.com/", 'all-in-one-seo-pack' ),
				"appid"                  => __( "Enter your Facebook App ID here. Information about how to get your Facebook App ID can be found at https://developers.facebook.com/docs/apps/register", 'all-in-one-seo-pack' ),
				"title_shortcodes"       => __( "Run shortcodes that appear in social title meta tags.", 'all-in-one-seo-pack' ),
				"description_shortcodes" => __( "Run shortcodes that appear in social description meta tags.", 'all-in-one-seo-pack' ),
				"sitename"               => __( "The Site Name is the name that is used to identify your website.", 'all-in-one-seo-pack' ),
				"hometitle"              => __( "The Home Title is the Open Graph title for your home page.", 'all-in-one-seo-pack' ),
				"description"            => __( "The Home Description is the Open Graph description for your home page.", 'all-in-one-seo-pack' ),
				"homeimage"              => __( "The Home Image is the Open Graph image for your home page.", 'all-in-one-seo-pack' ),
				"hometag"                => __( "The Home Tag allows you to add a list of keywords that best describe your home page content.", 'all-in-one-seo-pack' ),
				"generate_descriptions"  => __( "Check this and your Open Graph descriptions will be auto-generated from your content.", 'all-in-one-seo-pack' ),
				"defimg"                 => __( "This option lets you choose which image will be displayed by default for the Open Graph image. You may override this on individual posts.", 'all-in-one-seo-pack' ),
				"fallback"               => __( "This option lets you fall back to the default image if no image could be found above.", 'all-in-one-seo-pack' ),
				"dimg"                   => __( "This option sets a default image that can be used for the Open Graph image. You can upload an image, select an image from your Media Library or paste the URL of an image here.", 'all-in-one-seo-pack' ),
				"dimgwidth"              => __( "This option lets you set a default width for your images, where unspecified.", 'all-in-one-seo-pack' ),
				"dimgheight"             => __( "This option lets you set a default height for your images, where unspecified.", 'all-in-one-seo-pack' ),
				"meta_key"               => __( "Enter the name of a custom field (or multiple field names separated by commas) to use that field to specify the Open Graph image on Pages or Posts.", 'all-in-one-seo-pack' ),
				"categories"             => __( "Set the Open Graph type for your website as either a blog or a website.", 'all-in-one-seo-pack' ),
				"image"                  => __( "This option lets you select the Open Graph image that will be used for this Page or Post, overriding the default settings.", 'all-in-one-seo-pack' ),
				"customimg"              => __( "This option lets you upload an image to use as the Open Graph image for this Page or Post.", 'all-in-one-seo-pack' ),
				"imagewidth"             => __( "Enter the width for your Open Graph image in pixels (i.e. 600).", 'all-in-one-seo-pack' ),
				"imageheight"            => __( "Enter the height for your Open Graph image in pixels (i.e. 600).", 'all-in-one-seo-pack' ),
				"video"                  => __( "This option lets you specify a link to the Open Graph video used on this Page or Post.", 'all-in-one-seo-pack' ),
				"videowidth"             => __( "Enter the width for your Open Graph video in pixels (i.e. 600).", 'all-in-one-seo-pack' ),
				"videoheight"            => __( "Enter the height for your Open Graph video in pixels (i.e. 600).", 'all-in-one-seo-pack' ),
				"defcard"                => __( "Select the default type of Twitter card to display.", 'all-in-one-seo-pack' ),
				"setcard"                => __( "Select the default type of Twitter card to display.", 'all-in-one-seo-pack' ),
				"twitter_site"           => __( "Enter the Twitter username associated with your website here.", 'all-in-one-seo-pack' ),
				"twitter_creator"        => __( "Allows your authors to be identified by their Twitter usernames as content creators on the Twitter cards for their posts.", 'all-in-one-seo-pack' ),
				"twitter_domain"         => __( "Enter the name of your website here.", 'all-in-one-seo-pack' ),
				"customimg_twitter"      => __( "This option lets you upload an image to use as the Twitter image for this Page or Post.", 'all-in-one-seo-pack' ),
				"gen_tags"               => __( "Automatically generate article tags for Facebook type article when not provided.", 'all-in-one-seo-pack' ),
				"gen_keywords"           => __( "Use keywords in generated article tags.", 'all-in-one-seo-pack' ),
				"gen_categories"         => __( "Use categories in generated article tags.", 'all-in-one-seo-pack' ),
				"gen_post_tags"          => __( "Use post tags in generated article tags.", 'all-in-one-seo-pack' ),
				"types"                  => __( "Select which Post Types you want to use All in One SEO Pack to set Open Graph meta values for.", 'all-in-one-seo-pack' ),
				"title"                  => __( "This is the Open Graph title of this Page or Post.", 'all-in-one-seo-pack' ),
				"desc"                   => __( "This is the Open Graph description of this Page or Post.", 'all-in-one-seo-pack' ),
				"category"               => __( "Select the Open Graph type that best describes the content of this Page or Post.", 'all-in-one-seo-pack' ),
				"facebook_debug"         => __( "Press this button to have Facebook re-fetch and debug this page.", 'all-in-one-seo-pack' ),
				"section"                => __( "This Open Graph meta allows you to add a general section name that best describes this content.", 'all-in-one-seo-pack' ),
				"tag"                    => __( "This Open Graph meta allows you to add a list of keywords that best describe this content.", 'all-in-one-seo-pack' ),
				"facebook_publisher"     => __( "Link articles to the Facebook page associated with your website.", 'all-in-one-seo-pack' ),
				"facebook_author"        => __( "Allows your authors to be identified by their Facebook pages as content authors on the Opengraph meta for their articles.", 'all-in-one-seo-pack' ),
				"person_or_org"          => __( "Are the social profile links for your website for a person or an organization?", 'all-in-one-seo-pack' ),
				"profile_links"          => __( "Add URLs for your website's social profiles here (Facebook, Twitter, Google+, Instagram, LinkedIn), one per line.", 'all-in-one-seo-pack' ),
				"social_name"            => __( "Add the name of the person or organization who owns these profiles.", 'all-in-one-seo-pack' ),
			);

			$this->help_anchors = Array(
				'title_shortcodes'		=> '#run-shortcodes-in-title',
				'description_shortcodes' => '#run-shortcodes-in-description',
				'generate_descriptions' => '#auto-generate-og-descriptions',
				'setmeta'               => '#use-aioseo-title-and-description',
				'sitename'              => '#site-name',
				'hometitle'             => '#home-title-and-description',
				'description'           => '#home-title-and-description',
				'homeimage'             => '#home-image',
				'hometag'				=> '#home-article-tags',
				'defimg'                => '#select-og-image-source',
				'fallback'              => '#use-default-if-no-image-found',
				'dimg'                  => '#default-og-image',
				'dimgwidth'				=> '#default-image-width',
				'dimgheight'			=> '#default-image-height',
				'meta_key'              => '#use-custom-field-for-image',
				'profile_links'			=> '#social-profile-links',
				'person_or_org'			=> '#social-profile-links',
				'social_name'			=> '#social-profile-links',
				'key'                   => '#facebook-admin-id',
				'appid'                 => '#facebook-app-id',
				'categories'            => '#facebook-object-type',
				'gen_tags'				=> '#automatically-generate-article-tags',
				'gen_keywords'			=> '#use-keywords-in-article-tags',
				'gen_categories'		=> '#use-categories-in-article-tags',
				'gen_post_tags'			=> '#use-post-tags-in-article-tags',
				'facebook_publisher'    => '#show-facebook-publisher-on-articles',
				'facebook_author'       => '#show-facebook-author-on-articles',
				'types'                 => '#enable-facebook-meta-for',
				'defcard'               => '#default-twitter-card',
				'setcard'               => '#default-twitter-card',
				'twitter_site'          => '#twitter-site',
				'twitter_creator'       => '#show-twitter-author',
				'twitter_domain'        => '#twitter-domain',
				'scan_header'           => '#scan-social-meta',
				'title'					=> 'https://semperplugins.com/documentation/social-meta-settings-individual-pagepost-settings/#title',
				'desc'					=> 'https://semperplugins.com/documentation/social-meta-settings-individual-pagepost-settings/#description',
				'image'					=> 'https://semperplugins.com/documentation/social-meta-settings-individual-pagepost-settings/#image',
				'customimg'				=> 'https://semperplugins.com/documentation/social-meta-settings-individual-pagepost-settings/#custom-image',
				'imagewidth'			=> 'https://semperplugins.com/documentation/social-meta-settings-individual-pagepost-settings/#specify-image-width-height',
				'imageheight'			=> 'https://semperplugins.com/documentation/social-meta-settings-individual-pagepost-settings/#specify-image-width-height',
				'video'					=> 'https://semperplugins.com/documentation/social-meta-settings-individual-pagepost-settings/#custom-video',
				'videowidth'			=> 'https://semperplugins.com/documentation/social-meta-settings-individual-pagepost-settings/#specify-video-width-height',
				'videoheight'			=> 'https://semperplugins.com/documentation/social-meta-settings-individual-pagepost-settings/#specify-video-width-height',
				'category'				=> 'https://semperplugins.com/documentation/social-meta-settings-individual-pagepost-settings/#facebook-object-type',
				'facebook_debug'		=> 'https://semperplugins.com/documentation/social-meta-settings-individual-pagepost-settings/#facebook-debug',
				'section'				=> 'https://semperplugins.com/documentation/social-meta-settings-individual-pagepost-settings/#article-section',
				'tag'					=> 'https://semperplugins.com/documentation/social-meta-settings-individual-pagepost-settings/#article-tags',
				'setcard'				=> 'https://semperplugins.com/documentation/social-meta-settings-individual-pagepost-settings/#twitter-card-type',
				'customimg_twitter'		=> 'https://semperplugins.com/documentation/social-meta-settings-individual-pagepost-settings/#custom-twitter-image',
			);

			$count_desc            = __( " characters. Open Graph allows up to a maximum of %s chars for the %s.", 'all-in-one-seo-pack' );
			$this->default_options = array(
				'scan_header'            => Array(
					'name' => __( 'Scan Header', 'all-in-one-seo-pack' ),
					'type' => 'custom',
					'save' => true,
				),
				'setmeta'                => Array(
					'name' => __( 'Use AIOSEO Title and Description', 'all-in-one-seo-pack' ),
					'type' => 'checkbox',
				),
				'key'                    => Array(
					'name'    => __( 'Facebook Admin ID', 'all-in-one-seo-pack' ),
					'default' => '',
					'type'    => 'text',
				),
				'appid'                  => Array(
					'name'    => __( 'Facebook App ID', 'all-in-one-seo-pack' ),
					'default' => '',
					'type'    => 'text',
				),
				'title_shortcodes'       => Array( 'name' => __( 'Run Shortcodes In Title', 'all-in-one-seo-pack' ) ),
				'description_shortcodes' => Array( 'name' => __( 'Run Shortcodes In Description', 'all-in-one-seo-pack' ) ),
				'sitename'               => Array(
					'name'    => __( 'Site Name', 'all-in-one-seo-pack' ),
					'default' => get_bloginfo( 'name' ),
					'type'    => 'text',
				),
				'hometitle'              => Array(
					'name'     => __( 'Home Title', 'all-in-one-seo-pack' ),
					'default'  => '',
					'type'     => 'textarea',
					'condshow' => Array(
						'aiosp_opengraph_setmeta' => Array(
							'lhs' => "aiosp_opengraph_setmeta",
							'op'  => '!=',
							'rhs' => 'on',
						),
					),
				),
				'description'            => Array(
					'name'     => __( 'Home Description', 'all-in-one-seo-pack' ),
					'default'  => '',
					'type'     => 'textarea',
					'condshow' => Array(
						'aiosp_opengraph_setmeta' => Array(
							'lhs' => "aiosp_opengraph_setmeta",
							'op'  => '!=',
							'rhs' => 'on',
						),
					),
				),
				'homeimage'              => Array(
					'name' => __( 'Home Image', 'all-in-one-seo-pack' ),
					'type' => 'image',
				),
				'hometag'                => Array(
					'name'     => __( 'Home Article Tags', 'all-in-one-seo-pack' ),
					'type'     => 'text',
					'default'  => '',
					'condshow' => Array( 'aiosp_opengraph_categories' => 'article' ),
				),
				'generate_descriptions'  => Array(
					'name'    => __( 'Autogenerate OG Descriptions', 'all-in-one-seo-pack' ),
					'default' => 1,
				),
				'defimg'                 => Array(
					'name'            => __( 'Select OG:Image Source', 'all-in-one-seo-pack' ),
					'type'            => 'select',
					'initial_options' => Array(
						''         => __( 'Default Image' ),
						'featured' => __( 'Featured Image' ),
						'attach'   => __( 'First Attached Image' ),
						'content'  => __( 'First Image In Content' ),
						'custom'   => __( 'Image From Custom Field' ),
						'author'   => __( 'Post Author Image' ),
						'auto'     => __( 'First Available Image' ),
					),
				),
				'fallback'               => Array(
					'name' => __( 'Use Default If No Image Found', 'all-in-one-seo-pack' ),
					'type' => 'checkbox',
				),
				'dimg'                   => Array(
					'name'    => __( 'Default OG:Image', 'all-in-one-seo-pack' ),
					'default' => AIOSEOP_PLUGIN_IMAGES_URL . 'default-user-image.png',
					'type'    => 'image',
				),
				'dimgwidth'              => Array(
					'name'    => __( 'Default Image Width', 'all-in-one-seo-pack' ),
					'type'    => 'text',
					'default' => '',
				),
				'dimgheight'             => Array(
					'name'    => __( 'Default Image Height', 'all-in-one-seo-pack' ),
					'type'    => 'text',
					'default' => '',
				),
				'meta_key'               => Array(
					'name'    => __( 'Use Custom Field For Image', 'all-in-one-seo-pack' ),
					'type'    => 'text',
					'default' => '',
				),
				'categories'             => Array(
					'name'            => __( 'Facebook Object Type', 'all-in-one-seo-pack' ),
					'type'            => 'radio',
					'initial_options' => $categories,
					'default'         => 'blog',
				),
				'image'                  => Array(
					'name'            => __( 'Image', 'all-in-one-seo-pack' ),
					'type'            => 'radio',
					'initial_options' => Array( 0 => '<img style="width:50px;height:auto;display:inline-block;vertical-align:bottom;" src="' . AIOSEOP_PLUGIN_IMAGES_URL . 'default-user-image.png' . '">' ),
				),
				'customimg'              => Array(
					'name' => __( 'Custom Image', 'all-in-one-seo-pack' ),
					'type' => 'image',
				),
				'imagewidth'             => Array(
					'name'    => __( 'Specify Image Width', 'all-in-one-seo-pack' ),
					'type'    => 'text',
					'default' => '',
				),
				'imageheight'            => Array(
					'name'    => __( 'Specify Image Height', 'all-in-one-seo-pack' ),
					'type'    => 'text',
					'default' => '',
				),
				'video'                  => Array(
					'name' => __( 'Custom Video', 'all-in-one-seo-pack' ),
					'type' => 'text',
				),
				'videowidth'             => Array(
					'name'     => __( 'Specify Video Width', 'all-in-one-seo-pack' ),
					'type'     => 'text',
					'default'  => '',
					'condshow' => Array(
						'aioseop_opengraph_settings_video' => Array(
							'lhs' => "aioseop_opengraph_settings_video",
							'op'  => '!=',
							'rhs' => '',
						),
					),
				),
				'videoheight'            => Array(
					'name'     => __( 'Specify Video Height', 'all-in-one-seo-pack' ),
					'type'     => 'text',
					'default'  => '',
					'condshow' => Array(
						'aioseop_opengraph_settings_video' => Array(
							'lhs' => "aioseop_opengraph_settings_video",
							'op'  => '!=',
							'rhs' => '',
						),
					),
				),
				'defcard'                => Array(
					'name'            => __( 'Default Twitter Card', 'all-in-one-seo-pack' ),
					'type'            => 'select',
					'initial_options' => Array(
						'summary'             => __( 'Summary', 'all-in-one-seo-pack' ),
						'summary_large_image' => __( 'Summary Large Image', 'all-in-one-seo-pack' )
						/*, *******REMOVING THIS TWITTER CARD TYPE FROM SOCIAL META MODULE****** 'photo' => __( 'Photo', 'all-in-one-seo-pack' ) */
					),
					'default'         => 'summary',
				),
				'setcard'                => Array(
					'name'            => __( 'Twitter Card Type', 'all-in-one-seo-pack' ),
					'type'            => 'select',
					'initial_options' => Array(
						'summary_large_image' => __( 'Summary Large Image', 'all-in-one-seo-pack' ),
						'summary'             => __( 'Summary', 'all-in-one-seo-pack' )
						/*, *******REMOVING THIS TWITTER CARD TYPE FROM SOCIAL META MODULE****** 'photo' => __( 'Photo', 'all-in-one-seo-pack' ) */
					),
				),
				'twitter_site'           => Array(
					'name'    => __( 'Twitter Site', 'all-in-one-seo-pack' ),
					'type'    => 'text',
					'default' => '',
				),
				'twitter_creator'        => Array( 'name' => __( 'Show Twitter Author', 'all-in-one-seo-pack' ) ),
				'twitter_domain'         => Array(
					'name'    => __( 'Twitter Domain', 'all-in-one-seo-pack' ),
					'type'    => 'text',
					'default' => '',
				),
				'customimg_twitter'      => Array(
					'name' => __( 'Custom Twitter Image', 'all-in-one-seo-pack' ),
					'type' => 'image',
				),
				'gen_tags'               => Array( 'name' => __( 'Automatically Generate Article Tags', 'all-in-one-seo-pack' ) ),
				'gen_keywords'           => Array(
					'name'     => __( 'Use Keywords In Article Tags', 'all-in-one-seo-pack' ),
					'default'  => 'on',
					'condshow' => Array( 'aiosp_opengraph_gen_tags' => 'on' ),
				),
				'gen_categories'         => Array(
					'name'     => __( 'Use Categories In Article Tags', 'all-in-one-seo-pack' ),
					'default'  => 'on',
					'condshow' => Array( 'aiosp_opengraph_gen_tags' => 'on' ),
				),
				'gen_post_tags'          => Array(
					'name'     => __( 'Use Post Tags In Article Tags', 'all-in-one-seo-pack' ),
					'default'  => 'on',
					'condshow' => Array( 'aiosp_opengraph_gen_tags' => 'on' ),
				),
				'types'                  => Array(
					'name'            => __( 'Enable Facebook Meta for', 'all-in-one-seo-pack' ),
					'type'            => 'multicheckbox',
					'initial_options' => $this->get_post_type_titles( Array( '_builtin' => false ) ),
					'default'         => Array( 'post' => 'post', 'page' => 'page' ),
				),
				'title'                  => Array(
					'name'       => __( 'Title', 'all-in-one-seo-pack' ),
					'default'    => '',
					'type'       => 'text',
					'size'       => 95,
					'count'      => 1,
					'count_desc' => $count_desc,
				),
				'desc'                   => Array(
					'name'       => __( 'Description', 'all-in-one-seo-pack' ),
					'default'    => '',
					'type'       => 'textarea',
					'cols'       => 250,
					'rows'       => 4,
					'count'      => 1,
					'count_desc' => $count_desc,
				),
				'category'               => Array(
					'name'            => __( 'Facebook Object Type', 'all-in-one-seo-pack' ),
					'type'            => 'select',
					'style'           => '',
					'initial_options' => $this->fb_object_types,
					'default'         => '',
				),
				'facebook_debug'         => Array(
					'name'    => __( 'Facebook Debug', 'all-in-one-seo-pack' ),
					'type'    => 'html',
					'save'    => false,
					'default' =>
						'<script>
														jQuery(document).ready(function() {
															var snippet = jQuery("#aioseop_snippet_link");
															if ( !snippet ) {
																jQuery( "#aioseop_opengraph_settings_facebook_debug_wrapper").hide();
															} else {
																snippet = snippet.html();
																jQuery("#aioseop_opengraph_settings_facebook_debug").attr( "href", "https://developers.facebook.com/tools/debug/sharing/?q=" + snippet );
															}
														});
													</script>
													<a name="aioseop_opengraph_settings_facebook_debug" id="aioseop_opengraph_settings_facebook_debug" class="button-primary" href="" target=_blank>' . __( 'Debug This Post', 'all-in-one-seo-pack' )
						. '</a>',
				),

				'section'            => Array(
					'name'     => __( 'Article Section', 'all-in-one-seo-pack' ),
					'type'     => 'text',
					'default'  => '',
					'condshow' => Array( 'aioseop_opengraph_settings_category' => 'article' ),
				),
				'tag'                => Array(
					'name'     => __( 'Article Tags', 'all-in-one-seo-pack' ),
					'type'     => 'text',
					'default'  => '',
					'condshow' => Array( 'aioseop_opengraph_settings_category' => 'article' ),
				),
				'facebook_publisher' => Array(
					'name'    => __( 'Show Facebook Publisher on Articles', 'all-in-one-seo-pack' ),
					'type'    => 'text',
					'default' => '',
				),
				'facebook_author'    => Array( 'name' => __( 'Show Facebook Author on Articles', 'all-in-one-seo-pack' ) ),
				'profile_links'      => Array(
					'name' => __( 'Social Profile Links', 'all-in-one-seo-pack' ),
					'type' => 'textarea',
					'cols' => 60,
					'rows' => 5,
				),
				'person_or_org'      => Array(
					'name'            => __( 'Person or Organization?', 'all-in-one-seo-pack' ),
					'type'            => 'radio',
					'initial_options' => Array(
						'person' => __( 'Person', 'all-in-one-seo-pack' ),
						'org'    => __( 'Organization', 'all-in-one-seo-pack' ),
					),
				),
				'social_name'        => Array(
					'name'    => __( "Associated Name", 'all-in-one-seo-pack' ),
					'type'    => 'text',
					'default' => "",
				),
			);

			// load initial options / set defaults
			$this->update_options();

			$display = Array();
			if ( isset( $this->options['aiosp_opengraph_types'] ) ) {
				$display = $this->options['aiosp_opengraph_types'];
			}

			$this->locations = array(
				'opengraph' => Array(
					'name'    => $this->name,
					'prefix'  => 'aiosp_',
					'type'    => 'settings',
					'options' => Array(
						'scan_header',
						'setmeta',
						'key',
						'appid',
						'sitename',
						'title_shortcodes',
						'description_shortcodes',
						'hometitle',
						'description',
						'homeimage',
						'hometag',
						'generate_descriptions',
						'defimg',
						'fallback',
						'dimg',
						'dimgwidth',
						'dimgheight',
						'meta_key',
						'categories',
						'defcard',
						'profile_links',
						'person_or_org',
						'social_name',
						'twitter_site',
						'twitter_creator',
						'twitter_domain',
						'gen_tags',
						'gen_keywords',
						'gen_categories',
						'gen_post_tags',
						'types',
						'facebook_publisher',
						'facebook_author',
					),
				),
				'settings'  => Array(
					'name'      => __( 'Social Settings', 'all-in-one-seo-pack' ),
					'type'      => 'metabox',
					'help_link' => 'https://semperplugins.com/documentation/social-meta-settings-individual-pagepost-settings/',
					'options'   => Array(
						'title',
						'desc',
						'image',
						'customimg',
						'imagewidth',
						'imageheight',
						'video',
						'videowidth',
						'videoheight',
						'category',
						'facebook_debug',
						'section',
						'tag',
						'setcard',
						'customimg_twitter',
					),
					'display'   => $display,
					'prefix'    => 'aioseop_opengraph_',
				),
			);

			$this->layout = Array(
				'default'   => Array(
					'name'      => __( 'General Settings', 'all-in-one-seo-pack' ),
					'help_link' => 'https://semperplugins.com/documentation/social-meta-module/',
					'options'   => Array() // this is set below, to the remaining options -- pdb
				),
				'home'      => Array(
					'name'      => __( 'Home Page Settings', 'all-in-one-seo-pack' ),
					'help_link' => 'https://semperplugins.com/documentation/social-meta-module/#use-aioseo-title-and-description',
					'options'   => Array( 'setmeta', 'sitename', 'hometitle', 'description', 'homeimage', 'hometag' ),
				),
				'image'     => Array(
					'name'      => __( 'Image Settings', 'all-in-one-seo-pack' ),
					'help_link' => 'https://semperplugins.com/documentation/social-meta-module/#select-og-image-source',
					'options'   => Array( 'defimg', 'fallback', 'dimg', 'dimgwidth', 'dimgheight', 'meta_key' ),
				),
				'links'     => Array(
					'name'      => __( 'Social Profile Links', 'all-in-one-seo-pack' ),
					'help_link' => 'https://semperplugins.com/documentation/social-meta-module/#social-profile-links',
					'options'   => Array( 'profile_links', 'person_or_org', 'social_name' ),
				),
				'facebook'  => Array(
					'name'      => __( 'Facebook Settings', 'all-in-one-seo-pack' ),
					'help_link' => 'https://semperplugins.com/documentation/social-meta-module/#facebook-settings',
					'options'   => Array(
						'key',
						'appid',
						'types',
						'gen_tags',
						'gen_keywords',
						'gen_categories',
						'gen_post_tags',
						'categories',
						'facebook_publisher',
						'facebook_author',
					),
				),
				'twitter'   => Array(
					'name'      => __( 'Twitter Settings', 'all-in-one-seo-pack' ),
					'help_link' => 'https://semperplugins.com/documentation/social-meta-module/#default-twitter-card',
					'options'   => Array( 'defcard', 'setcard', 'twitter_site', 'twitter_creator', 'twitter_domain' ),
				),
				'scan_meta' => Array(
					'name'      => __( 'Scan Social Meta', 'all-in-one-seo-pack' ),
					'help_link' => 'https://semperplugins.com/documentation/social-meta-module/#scan_meta',
					'options'   => Array( 'scan_header' ),
				),
			);

			$other_options = Array();
			foreach ( $this->layout as $k => $v ) {
				$other_options = array_merge( $other_options, $v['options'] );
			}

			$this->layout['default']['options'] = array_diff( array_keys( $this->default_options ), $other_options );

			if ( is_admin() ) {
				add_action( 'admin_init', Array( $this, 'debug_post_types' ), 5 );
			} else {
				add_action( 'wp', Array( $this, 'type_setup' ) );
			}

			if ( ! is_admin() || defined( 'DOING_AJAX' ) ) {
				$this->do_opengraph();
			}

			add_filter( 'jetpack_enable_open_graph', '__return_false' ); // Avoid having duplicate meta tags

			// Force refresh of Facebook cache.
			add_action( 'post_updated', array( $this, 'force_fb_refresh_update' ), 10, 3 );
			add_action( 'transition_post_status', array( $this, 'force_fb_refresh_transition' ), 10, 3 );
		}

		/**
		 * Forces FaceBook OpenGraph to refresh its cache when a post is changed to
		 *
		 * @param $new_status
		 * @param $old_status
		 * @param $post
		 *
		 * @todo  this and force_fb_refresh_update can probably have the remote POST extracted out.
		 *
		 * @see   https://developers.facebook.com/docs/sharing/opengraph/using-objects#update
		 * @since 2.3.11
		 */
		function force_fb_refresh_transition( $new_status, $old_status, $post ) {
			if ( 'publish' !== $new_status ) {
				return;
			}
			if ( 'future' !== $old_status ) {
				return;
			}

			$current_post_type = get_post_type();

			// Only ping Facebook if Social SEO is enabled on this post type.
			if ( $this->option_isset( 'types' ) && is_array( $this->options['aiosp_opengraph_types'] ) && in_array( $current_post_type, $this->options['aiosp_opengraph_types'] ) ) {
				$post_url = get_permalink( $post->ID );
				$endpoint = sprintf( 'https://graph.facebook.com/?%s', http_build_query( array(
					'id'     => $post_url,
					'scrape' => true,
				) ) );
				wp_remote_post( $endpoint, array( 'blocking' => false ) );
			}
		}

		/**
		 * Forces FaceBook OpenGraph refresh on update.
		 *
		 * @param $post_ID
		 * @param $post_after
		 *
		 * @see   https://developers.facebook.com/docs/sharing/opengraph/using-objects#update
		 * @since 2.3.11
		 */
		function force_fb_refresh_update( $post_ID, $post_after ) {

			$current_post_type = get_post_type();

			// Only ping Facebook if Social SEO is enabled on this post type.
			if ( 'publish' === $post_after->post_status && $this->option_isset( 'types' ) && is_array( $this->options['aiosp_opengraph_types'] ) && in_array( $current_post_type, $this->options['aiosp_opengraph_types'] ) ) {
				$post_url = get_permalink( $post_ID );
				$endpoint = sprintf( 'https://graph.facebook.com/?%s', http_build_query( array(
					'id'     => $post_url,
					'scrape' => true,
				) ) );
				wp_remote_post( $endpoint, array( 'blocking' => false ) );
			}
		}

		function settings_page_init() {
			add_filter( 'aiosp_output_option', Array( $this, 'display_custom_options' ), 10, 2 );
			$cat = $this->options["{$this->prefix}categories"];
			if ( ! empty( $cat ) ) {
				if ( $cat == 'blog' ) {
					$show_on_front = get_option( 'show_on_front' );
					if ( ( $show_on_front == 'page' ) && ( get_option( 'page_on_front' ) ) ) {
						$this->output_error( '<p>' . __( "Static front page detected, suggested Facebook Object Type is 'website'.", 'all-in-one-seo-pack' ) . '</p>' );
					}
				} elseif ( $cat == 'website' ) {
					$show_on_front = get_option( 'show_on_front' );
					if ( ( $show_on_front == 'posts' ) ) {
						$this->output_error( '<p>' . __( "Blog on front page detected, suggested Facebook Object Type is 'blog'.", 'all-in-one-seo-pack' ) . '</p>' );
					}
				}
			}
		}

		function filter_options( $options, $location ) {
			if ( $location == 'settings' ) {
				$prefix = $this->get_prefix( $location ) . $location . '_';
				list( $legacy, $images ) = $this->get_all_images( $options );
				if ( isset( $options ) && isset( $options["{$prefix}image"] ) ) {
					$thumbnail = $options["{$prefix}image"];
					if ( ctype_digit( (string) $thumbnail ) || ( $thumbnail == 'post' ) ) {
						if ( $thumbnail == 'post' ) {
							$thumbnail = $images['post1'];
						} else if ( ! empty( $legacy[ $thumbnail ] ) ) {
							$thumbnail = $legacy[ $thumbnail ];
						}
					}
					$options["{$prefix}image"] = $thumbnail;
				}
				if ( empty( $options[ $prefix . 'image' ] ) ) {
					$img = array_keys( $images );
					if ( ! empty( $img ) && ! empty( $img[1] ) ) {
						$options[ $prefix . 'image' ] = $img[1];
					}
				}
			}

			return $options;
		}

		function filter_settings( $settings, $location, $current ) {
			if ( $location == 'opengraph' || $location == 'settings' ) {
				$prefix = $this->get_prefix( $location ) . $location . '_';
				if ( $location == 'opengraph' ) {
					return $settings;
				}
				if ( $location == 'settings' ) {
					list( $legacy, $settings[ $prefix . 'image' ]['initial_options'] ) = $this->get_all_images( $current );
					$opts              = Array( 'title', 'desc' );
					$current_post_type = get_post_type();
					if ( isset( $this->options["aiosp_opengraph_{$current_post_type}_fb_object_type"] ) ) {
						$flat_type_list = Array();
						foreach ( $this->fb_object_types as $k => $v ) {
							if ( is_array( $v ) ) {
								$flat_type_list = array_merge( $flat_type_list, $v );
							} else {
								$flat_type_list[ $k ] = $v;
							}
						}
						$settings[ $prefix . 'category' ]['initial_options'] = array_merge( Array(
							$this->options["aiosp_opengraph_{$current_post_type}_fb_object_type"] => __( 'Default ', 'all-in-one-seo-pack' ) . ' - '
							                                                                         . $flat_type_list[ $this->options["aiosp_opengraph_{$current_post_type}_fb_object_type"] ],
						),
							$settings[ $prefix . 'category' ]['initial_options'] );
					}
					if ( isset( $this->options["aiosp_opengraph_defcard"] ) ) {
						$settings[ $prefix . 'setcard' ]['default'] = $this->options["aiosp_opengraph_defcard"];
					}
					global $aiosp;
					$info = $aiosp->get_page_snippet_info();
					extract( $info );
					$settings["{$prefix}title"]['placeholder'] = $title;
					$settings["{$prefix}desc"]['placeholder']  = $description;
				}
				if ( isset( $current[ $prefix . 'setmeta' ] ) && $current[ $prefix . 'setmeta' ] ) {
					foreach ( $opts as $opt ) {
						if ( isset( $settings[ $prefix . $opt ] ) ) {
							$settings[ $prefix . $opt ]['type']      = 'hidden';
							$settings[ $prefix . $opt ]['label']     = 'none';
							$settings[ $prefix . $opt ]['help_text'] = '';
							unset( $settings[ $prefix . $opt ]['count'] );
						}
					}
				}
			}

			return $settings;
		}

		function override_options( $options, $location, $settings ) {
			$opts = Array();
			foreach ( $settings as $k => $v ) {
				if ( $v['save'] ) {
					$opts[ $k ] = $v['default'];
				}
			}
			foreach ( $options as $k => $v ) {
				if ( $v === null ) {
					unset( $options[ $k ] );
				}
			}
			$options = wp_parse_args( $options, $opts );

			return $options;
		}

		function filter_metabox_options( $options, $location, $post_id ) {
			if ( $location == 'settings' ) {
				$prefix = $this->get_prefix( $location ) . $location;
				if ( ! empty( $options[ $prefix . '_customimg' ] ) ) {
					$old_options = get_post_meta( $post_id, '_' . $prefix );
					$prefix .= '_';
					if ( empty( $old_options[ $prefix . 'customimg' ] ) || ( $old_options[ $prefix . 'customimg' ] != $options[ $prefix . 'customimg' ] ) ) {
						$options[ $prefix . 'image' ] = $options[ $prefix . 'customimg' ];
					}
				}
			}

			return $options;
		}

		/** Custom settings **/
		function display_custom_options( $buf, $args ) {
			if ( $args['name'] == 'aiosp_opengraph_scan_header' ) {
				$buf .= '<div class="aioseop aioseop_options aiosp_opengraph_settings"><div class="aioseop_wrapper aioseop_custom_type" id="aiosp_opengraph_scan_header_wrapper"><div class="aioseop_input" id="aiosp_opengraph_scan_header" style="padding-left:20px;">';
				$args['options']['type'] = 'submit';
				$args['attr']            = " class='button-primary' ";
				$args['value']           = $args['options']['default'] = __( 'Scan Now', 'all-in-one-seo-pack' );
				$buf .= __( 'Scan your site for duplicate social meta tags.', 'all-in-one-seo-pack' );
				$buf .= '<br /><br />' . $this->get_option_html( $args );
				$buf .= '</div></div></div>';
			}

			return $buf;
		}

		function add_attributes( $output ) { // avoid having duplicate meta tags
			$type = $this->type;
			if ( empty( $type ) ) {
				$type = 'website';
			}

			$schema_types = Array(
				'album'      => 'MusicAlbum',
				'article'    => 'Article',
				'bar'        => 'BarOrPub',
				'blog'       => 'Blog',
				'book'       => 'Book',
				'cafe'       => 'CafeOrCoffeeShop',
				'city'       => 'City',
				'country'    => 'Country',
				'episode'    => 'Episode',
				'food'       => 'FoodEvent',
				'game'       => 'Game',
				'hotel'      => 'Hotel',
				'landmark'   => 'LandmarksOrHistoricalBuildings',
				'movie'      => 'Movie',
				'product'    => 'Product',
				'profile'    => 'ProfilePage',
				'restaurant' => 'Restaurant',
				'school'     => 'School',
				'sport'      => 'SportsEvent',
				'website'    => 'WebSite',
			);

			if ( ! empty( $schema_types[ $type ] ) ) {
				$type = $schema_types[ $type ];
			} else {
				$type = 'WebSite';
			}

			$attributes = apply_filters( $this->prefix . 'attributes', Array(
				'itemscope',
				'itemtype="http://schema.org/' . ucfirst( $type ) . '"',
				'prefix="og: http://ogp.me/ns#"'
			) );

			foreach ( $attributes as $attr ) {
				if ( strpos( $output, $attr ) === false ) {
					$output .= "\n\t$attr ";
				}
			}

			return $output;
		}

		function add_meta() {
			global $post, $aiosp, $aioseop_options, $wp_query;
			$metabox           = $this->get_current_options( Array(), 'settings' );
			$key               = $this->options['aiosp_opengraph_key'];
			$dimg              = $this->options['aiosp_opengraph_dimg'];
			$current_post_type = get_post_type();
			$title             = $description = $image = $video = '';
			$type              = $this->type;
			$sitename          = $this->options['aiosp_opengraph_sitename'];

			$appid = isset( $this->options['aiosp_opengraph_appid'] ) ? $this->options['aiosp_opengraph_appid'] : '';

			if ( ! empty( $aioseop_options['aiosp_hide_paginated_descriptions'] ) ) {
				$first_page = false;
				if ( $aiosp->get_page_number() < 2 ) {
					$first_page = true;
				}
			} else {
				$first_page = true;
			}
			$url = $aiosp->aiosp_mrt_get_url( $wp_query );
			$url = apply_filters( 'aioseop_canonical_url', $url );

			$setmeta      = $this->options['aiosp_opengraph_setmeta'];
			$social_links = '';
			if ( is_front_page() ) {
				$title = $this->options['aiosp_opengraph_hometitle'];
				if ( $first_page ) {
					$description = $this->options['aiosp_opengraph_description'];
				}
				if ( ! empty( $this->options['aiosp_opengraph_homeimage'] ) ) {
					$thumbnail = $this->options['aiosp_opengraph_homeimage'];
				} else {
					$thumbnail = $this->options['aiosp_opengraph_dimg'];
				}

				/* If Use AIOSEO Title and Desc Selected */
				if ( $setmeta ) {
					$title = $aiosp->wp_title();
					if ( $first_page ) {
						$description = $aiosp->get_aioseop_description( $post );
					}
				}

				/* Add some defaults */
				if ( empty( $title ) ) {
					$title = get_bloginfo( 'name' );
				}
				if ( empty( $sitename ) ) {
					$sitename = get_bloginfo( 'name' );
				}

				if ( empty( $description ) && $first_page && ( ! empty( $this->options['aiosp_opengraph_generate_descriptions'] ) ) && ! empty( $post ) && ! empty( $post->post_content ) && ! post_password_required( $post ) ) {
					$description = $aiosp->trim_excerpt_without_filters( $aiosp->internationalize( preg_replace( '/\s+/', ' ', $post->post_content ) ), 1000 );
				}

				if ( empty( $description ) && $first_page ) {
					$description = get_bloginfo( 'description' );
				}
				if ( $type == 'article' && ( ! empty( $this->options['aiosp_opengraph_hometag'] ) ) ) {
					$tag = $this->options['aiosp_opengraph_hometag'];
				}
				if ( ! empty( $this->options['aiosp_opengraph_profile_links'] ) ) {
					$social_links = $this->options['aiosp_opengraph_profile_links'];
					if ( ! empty( $this->options['aiosp_opengraph_social_name'] ) ) {
						$social_name = $this->options['aiosp_opengraph_social_name'];
					}
					if ( $this->options['aiosp_opengraph_person_or_org'] == 'person' ) {
						$social_type = "Person";
					} else {
						$social_type = "Organization";
					}
				}
			} elseif ( is_singular() && $this->option_isset( 'types' )
			           && is_array( $this->options['aiosp_opengraph_types'] )
			           && in_array( $current_post_type, $this->options['aiosp_opengraph_types'] )
			) {

				if ( $type == 'article' ) {
					if ( ! empty( $metabox['aioseop_opengraph_settings_section'] ) ) {
						$section = $metabox['aioseop_opengraph_settings_section'];
					}
					if ( ! empty( $metabox['aioseop_opengraph_settings_tag'] ) ) {
						$tag = $metabox['aioseop_opengraph_settings_tag'];
					}
					if ( ! empty( $this->options['aiosp_opengraph_facebook_publisher'] ) ) {
						$publisher = $this->options['aiosp_opengraph_facebook_publisher'];
					}
				}

				if ( ! empty( $this->options['aiosp_opengraph_twitter_domain'] ) ) {
					$domain = $this->options['aiosp_opengraph_twitter_domain'];
				}

				if ( $type == 'article' && ! empty( $post ) ) {
					if ( isset( $post->post_author ) && ! empty( $this->options['aiosp_opengraph_facebook_author'] ) ) {
						$author = get_the_author_meta( 'facebook', $post->post_author );
					}

					if ( isset( $post->post_date ) ) {
						$published_time = date( 'Y-m-d\TH:i:s\Z', mysql2date( 'U', $post->post_date ) );
					}

					if ( isset( $post->post_modified ) ) {
						$modified_time = date( 'Y-m-d\TH:i:s\Z', mysql2date( 'U', $post->post_modified ) );
					}
				}

				$image       = $metabox['aioseop_opengraph_settings_image'];
				$video       = $metabox['aioseop_opengraph_settings_video'];
				$title       = $metabox['aioseop_opengraph_settings_title'];
				$description = $metabox['aioseop_opengraph_settings_desc'];

				/* Add AIOSEO variables if Site Title and Desc from AIOSEOP not selected */
				global $aiosp;
				if ( empty( $title ) ) {
					$title = $aiosp->wp_title();
				}
				if ( empty( $description ) ) {
					$description = trim( strip_tags( get_post_meta( $post->ID, "_aioseop_description", true ) ) );
				}

				/* Add some defaults */
				if ( empty( $title ) ) {
					$title = get_the_title();
				}
				if ( empty( $description ) && ( $this->options['aiosp_opengraph_generate_descriptions'] ) && ! post_password_required( $post ) ) {
					$description = $post->post_content;
				}
				if ( empty( $type ) ) {
					$type = 'article';
				}
			} else {
				return;
			}

			if ( $type == 'article' ) {
				if ( ! empty( $this->options['aiosp_opengraph_gen_tags'] ) ) {
					if ( ! empty( $this->options['aiosp_opengraph_gen_keywords'] ) ) {
						$keywords = $aiosp->get_main_keywords();
						$keywords = $this->apply_cf_fields( $keywords );
						$keywords = apply_filters( 'aioseop_keywords', $keywords );
						if ( ! empty( $keywords ) && ! empty( $tag ) ) {
							$tag .= ',' . $keywords;
						} elseif ( empty( $tag ) ) {
							$tag = $keywords;
						}
					}
					$tag = $aiosp->keyword_string_to_list( $tag );
					if ( ! empty( $this->options['aiosp_opengraph_gen_categories'] ) ) {
						$tag = array_merge( $tag, $aiosp->get_all_categories( $post->ID ) );
					}
					if ( ! empty( $this->options['aiosp_opengraph_gen_post_tags'] ) ) {
						$tag = array_merge( $tag, $aiosp->get_all_tags( $post->ID ) );
					}
				}
				if ( ! empty( $tag ) ) {
					$tag = $aiosp->clean_keyword_list( $tag );
				}
			}

			if ( ! empty( $this->options['aiosp_opengraph_title_shortcodes'] ) ) {
				$title = do_shortcode( $title );
			}

			if ( ! empty( $description ) ) {
				$description = $aiosp->internationalize( preg_replace( '/\s+/', ' ', $description ) );
				if ( ! empty( $this->options['aiosp_opengraph_description_shortcodes'] ) ) {
					$description = do_shortcode( $description );
				}
				$description = $aiosp->trim_excerpt_without_filters( $description, 1000 );
			}

			$title       = $this->apply_cf_fields( $title );
			$description = $this->apply_cf_fields( $description );

			/* Data Validation */
			$title       = strip_tags( esc_attr( $title ) );
			$sitename    = strip_tags( esc_attr( $sitename ) );
			$description = strip_tags( esc_attr( $description ) );

			if ( empty( $thumbnail ) && ! empty( $image ) ) {
				$thumbnail = $image;
			}

			/* Get the first image attachment on the post */
			// if( empty($thumbnail) ) $thumbnail = $this->get_the_image();

			/* Add user supplied default image */
			if ( empty( $thumbnail ) ) {
				if ( empty( $this->options['aiosp_opengraph_defimg'] ) ) {
					$thumbnail = $this->options['aiosp_opengraph_dimg'];
				} else {
					switch ( $this->options['aiosp_opengraph_defimg'] ) {
						case 'featured'    :
							$thumbnail = $this->get_the_image_by_post_thumbnail();
							break;
						case 'attach'    :
							$thumbnail = $this->get_the_image_by_attachment();
							break;
						case 'content'    :
							$thumbnail = $this->get_the_image_by_scan();
							break;
						case 'custom'    :
							$meta_key = $this->options['aiosp_opengraph_meta_key'];
							if ( ! empty( $meta_key ) && ! empty( $post ) ) {
								$meta_key  = explode( ',', $meta_key );
								$thumbnail = $this->get_the_image_by_meta_key( Array(
									'post_id'  => $post->ID,
									'meta_key' => $meta_key,
								) );
							}
							break;
						case 'auto'        :
							$thumbnail = $this->get_the_image();
							break;
						case 'author'    :
							$thumbnail = $this->get_the_image_by_author();
							break;
						default            :
							$thumbnail = $this->options['aiosp_opengraph_dimg'];
					}
				}
			}

			if ( ( empty( $thumbnail ) && ! empty( $this->options['aiosp_opengraph_fallback'] ) ) ) {
				$thumbnail = $this->options['aiosp_opengraph_dimg'];
			}

			if ( ! empty( $thumbnail ) ) {
				$thumbnail = esc_url( $thumbnail );
				$thumbnail = set_url_scheme( $thumbnail );
			}

			$width = $height = '';
			if ( ! empty( $thumbnail ) ) {
				if ( ! empty( $metabox['aioseop_opengraph_settings_imagewidth'] ) ) {
					$width = $metabox['aioseop_opengraph_settings_imagewidth'];
				}
				if ( ! empty( $metabox['aioseop_opengraph_settings_imageheight'] ) ) {
					$height = $metabox['aioseop_opengraph_settings_imageheight'];
				}
				if ( empty( $width ) && ! empty( $this->options['aiosp_opengraph_dimgwidth'] ) ) {
					$width = $this->options['aiosp_opengraph_dimgwidth'];
				}
				if ( empty( $height ) && ! empty( $this->options['aiosp_opengraph_dimgheight'] ) ) {
					$height = $this->options['aiosp_opengraph_dimgheight'];
				}
			}

			if ( ! empty( $video ) ) {
				if ( ! empty( $metabox['aioseop_opengraph_settings_videowidth'] ) ) {
					$videowidth = $metabox['aioseop_opengraph_settings_videowidth'];
				}
				if ( ! empty( $metabox['aioseop_opengraph_settings_videoheight'] ) ) {
					$videoheight = $metabox['aioseop_opengraph_settings_videoheight'];
				}
			}

			$card = 'summary';
			if ( ! empty( $this->options['aiosp_opengraph_defcard'] ) ) {
				$card = $this->options['aiosp_opengraph_defcard'];
			}

			if ( ! empty( $metabox['aioseop_opengraph_settings_setcard'] ) ) {
				$card = $metabox['aioseop_opengraph_settings_setcard'];
			}

			//support for changing legacy twitter cardtype-photo to summary large image
			if ( $card == 'photo' ) {
				$card = 'summary_large_image';
			}

			$site = $domain = $creator = '';

			if ( ! empty( $this->options['aiosp_opengraph_twitter_site'] ) ) {
				$site = $this->options['aiosp_opengraph_twitter_site'];
				$site = AIOSEOP_Opengraph_Public::prepare_twitter_username( $site );
			}

			if ( ! empty( $this->options['aiosp_opengraph_twitter_domain'] ) ) {
				$domain = $this->options['aiosp_opengraph_twitter_domain'];
			}

			if ( ! empty( $post ) && isset( $post->post_author ) && ! empty( $this->options['aiosp_opengraph_twitter_creator'] ) ) {
				$creator = get_the_author_meta( 'twitter', $post->post_author );
				$creator = AIOSEOP_Opengraph_Public::prepare_twitter_username( $creator );
			}

			$twitter_thumbnail = $thumbnail; // Default Twitter image if custom isn't set.

			if ( isset( $metabox['aioseop_opengraph_settings_customimg_twitter'] ) && ! empty( $metabox['aioseop_opengraph_settings_customimg_twitter'] ) ) {
				// Set Twitter image from custom.
				$twitter_thumbnail = set_url_scheme( $metabox['aioseop_opengraph_settings_customimg_twitter'] );
			}

			$meta = Array(
				'facebook' => Array(
					'title'          => 'og:title',
					'type'           => 'og:type',
					'url'            => 'og:url',
					'thumbnail'      => 'og:image',
					'width'          => 'og:image:width',
					'height'         => 'og:image:height',
					'video'          => 'og:video',
					'videowidth'     => 'og:video:width',
					'videoheight'    => 'og:video:height',
					'sitename'       => 'og:site_name',
					'key'            => 'fb:admins',
					'appid'          => 'fb:app_id',
					'description'    => 'og:description',
					'section'        => 'article:section',
					'tag'            => 'article:tag',
					'publisher'      => 'article:publisher',
					'author'         => 'article:author',
					'published_time' => 'article:published_time',
					'modified_time'  => 'article:modified_time',
				),
				'twitter'  => Array(
					'card'              => 'twitter:card',
					'site'              => 'twitter:site',
					'creator'           => 'twitter:creator',
					'domain'            => 'twitter:domain',
					'title'             => 'twitter:title',
					'description'       => 'twitter:description',
					'twitter_thumbnail' => 'twitter:image',
				),
			);

			//Only show if "use schema.org markup is checked"
			if ( ! empty( $aioseop_options['aiosp_schema_markup'] ) ) {
				$meta['google+'] = Array( 'thumbnail' => 'image' );
			}

			// Add links to testing tools

			/*
			http://developers.facebook.com/tools/debug
			https://dev.twitter.com/docs/cards/preview
			http://www.google.com/webmasters/tools/richsnippets
			*/
			/*
			$meta = Array(
				'facebook'	=> Array(
						'title'			=> 'og:title',
						'type'			=> 'og:type',
						'url'			=> 'og:url',
						'thumbnail'		=> 'og:image',
						'sitename'		=> 'og:site_name',
						'key'			=> 'fb:admins',
						'description'	=> 'og:description'
					),
				'google+'	=> Array(
						'thumbnail'		=> 'image',
						'title'			=> 'name',
						'description'	=> 'description'
					),
				'twitter'	=> Array(
						'card'			=> 'twitter:card',
						'url'			=> 'twitter:url',
						'title'			=> 'twitter:title',
						'description'	=> 'twitter:description',
						'thumbnail'		=> 'twitter:image'

					)
			);
			*/

			$tags = Array(
				'facebook' => Array( 'name' => 'property', 'value' => 'content' ),
				'twitter'  => Array( 'name' => 'name', 'value' => 'content' ),
				'google+'  => Array( 'name' => 'itemprop', 'value' => 'content' ),
			);

			foreach ( $meta as $t => $data ) {
				foreach ( $data as $k => $v ) {
					if ( empty( $$k ) ) {
						$$k = '';
					}
					$filtered_value = $$k;
					$filtered_value = apply_filters( $this->prefix . 'meta', $filtered_value, $t, $k );
					if ( ! empty( $filtered_value ) ) {
						if ( ! is_array( $filtered_value ) ) {
							$filtered_value = Array( $filtered_value );
						}
						foreach ( $filtered_value as $f ) {
							echo '<meta ' . $tags[ $t ]['name'] . '="' . $v . '" ' . $tags[ $t ]['value'] . '="' . $f . '" />' . "\n";
						}
					}
				}
			}
			$social_link_schema = '';
			if ( ! empty( $social_links ) ) {
				$home_url     = esc_url( get_home_url() );
				$social_links = explode( "\n", $social_links );
				foreach ( $social_links as $k => $v ) {
					$v = trim( $v );
					if ( empty( $v ) ) {
						unset( $social_links[ $k ] );
					} else {
						$v                  = esc_url( $v );
						$social_links[ $k ] = $v;
					}
				}
				$social_links       = join( '","', $social_links );
				$social_link_schema = <<<END
<script type="application/ld+json">
{ "@context" : "http://schema.org",
  "@type" : "{$social_type}",
  "name" : "{$social_name}",
  "url" : "{$home_url}",
  "sameAs" : ["{$social_links}"] 
}
</script>

END;
			}
			echo apply_filters( 'aiosp_opengraph_social_link_schema', $social_link_schema );
		}

		function do_opengraph() {
			global $aioseop_options;
			if ( ! empty( $aioseop_options ) && ! empty( $aioseop_options['aiosp_schema_markup'] ) ) {
				add_filter( 'language_attributes', Array( $this, 'add_attributes' ) );
			}
			if ( ! defined( 'DOING_AJAX' ) ) {
				add_action( 'aioseop_modules_wp_head', Array( $this, 'add_meta' ), 5 );
			}
		}

		function type_setup() {
			global $aiosp, $wp_query;
			$this->type = '';
			if ( $aiosp->is_static_front_page() ) {
				if ( ! empty( $this->options ) && ! empty( $this->options['aiosp_opengraph_categories'] ) ) {
					$this->type = $this->options['aiosp_opengraph_categories'];
				}
			} elseif ( is_singular() && $this->option_isset( 'types' ) ) {
				$metabox           = $this->get_current_options( Array(), 'settings' );
				$current_post_type = get_post_type();
				if ( ! empty( $metabox['aioseop_opengraph_settings_category'] ) ) {
					$this->type = $metabox['aioseop_opengraph_settings_category'];
				} elseif ( isset( $this->options["aiosp_opengraph_{$current_post_type}_fb_object_type"] ) ) {
					$this->type = $this->options["aiosp_opengraph_{$current_post_type}_fb_object_type"];
				}
			}
		}

		function debug_post_types() {
			add_filter( $this->prefix . 'display_settings', Array( $this, 'filter_settings' ), 10, 3 );
			add_filter( $this->prefix . 'override_options', Array( $this, 'override_options' ), 10, 3 );
			add_filter( $this->get_prefix( 'settings' ) . 'filter_metabox_options', Array(
				$this,
				'filter_metabox_options',
			), 10, 3 );
			$post_types                                        = $this->get_post_type_titles();
			$rempost                                           = array( 'revision' => 1, 'nav_menu_item' => 1 );
			$post_types                                        = array_diff_key( $post_types, $rempost );
			$this->default_options['types']['initial_options'] = $post_types;
			foreach ( $post_types as $slug => $name ) {
				$field                                     = $slug . '_fb_object_type';
				$this->default_options[ $field ]           = Array(
					'name'            => "$name " . __( 'Object Type', 'all-in-one-seo-pack' ) . "<br />($slug)",
					'type'            => 'select',
					'style'           => '',
					'initial_options' => $this->fb_object_types,
					'default'         => 'article',
					'condshow'        => Array( 'aiosp_opengraph_types\[\]' => $slug ),
				);
				$this->help_text[ $field ]                 = __( 'Choose a default value that best describes the content of your post type.', 'all-in-one-seo-pack' );
				$this->help_anchors[ $field ]              = '#content-object-types';
				$this->locations['opengraph']['options'][] = $field;
				$this->layout['facebook']['options'][]     = $field;
			}
			$this->setting_options();
			$this->add_help_text_links();

		}

		function get_all_images( $options = null, $p = null ) {
			static $img = Array();
			if ( ! is_array( $options ) ) {
				$options = Array();
			}
			if ( ! empty( $this->options['aiosp_opengraph_meta_key'] ) ) {
				$options['meta_key'] = $this->options['aiosp_opengraph_meta_key'];
			}
			if ( empty( $img ) ) {
				$size    = apply_filters( 'post_thumbnail_size', 'large' );
				$default = $this->get_the_image_by_default();
				if ( ! empty( $default ) ) {
					$default = set_url_scheme( $default );
					$img[ $default ] = 0;
				}
				$img = array_merge( $img, parent::get_all_images( $options, null ) );
			}

			if ( ! empty( $options ) && ! empty( $options['aioseop_opengraph_settings_customimg'] ) ) {
				$img[ $options['aioseop_opengraph_settings_customimg'] ] = 'customimg';
			}

			if ( ! empty( $options ) && ! empty( $options['aioseop_opengraph_settings_customimg'] ) ) {
				$img[ $options['aioseop_opengraph_settings_customimg'] ]         = 'customimg';
				$img[ $options['aioseop_opengraph_settings_customimg_twitter'] ] = 'customimg_twitter';
			}

			if ( $author_img = $this->get_the_image_by_author( $p ) ) {
				$image["author"] = $author_img;
			}
			$image  = array_flip( $img );
			$images = Array();
			if ( ! empty( $image ) ) {
				foreach ( $image as $k => $v ) {
					$images[ $v ] = '<img height=150 src="' . $v . '">';
				}
			}

			return Array( $image, $images );
		}

		function get_the_image_by_author( $options = null, $p = null ) {
			if ( $p === null ) {
				global $post;
			} else {
				$post = $p;
			}
			if ( ! empty( $post ) && ! empty( $post->post_author ) ) {
				$matches    = Array();
				$get_avatar = get_avatar( $post->post_author, 300 );
				if ( preg_match( "/src='(.*?)'/i", $get_avatar, $matches ) ) {
					return $matches[1];
				}
			}

			return false;
		}

		function get_the_image( $options = null, $p = null ) {
			$meta_key = $this->options['aiosp_opengraph_meta_key'];

			return parent::get_the_image( Array( 'meta_key' => $meta_key ), $p );
		}

		function get_the_image_by_default( $args = array() ) {
			return $this->options['aiosp_opengraph_dimg'];
		}

		function settings_update() {

		}
	}
}
