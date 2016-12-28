<?php
/**
 * @package All-in-One-SEO-Pack
 */

if ( ! class_exists( 'All_in_One_SEO_Pack_Bad_Robots' ) ) {

	/**
	 * Class All_in_One_SEO_Pack_Bad_Robots
	 */
	class All_in_One_SEO_Pack_Bad_Robots extends All_in_One_SEO_Pack_Module {

		/**
		 * All_in_One_SEO_Pack_Bad_Robots constructor.
		 */
		function __construct() {
			$this->name   = __( 'Bad Bot Blocker', 'all-in-one-seo-pack' );    // Human-readable name of the plugin.
			$this->prefix = 'aiosp_bad_robots_';                        // Option prefix.
			$this->file   = __FILE__;                                    // The current file.
			parent::__construct();

			$help_text = array(
				'block_bots'     => __( 'Block requests from user agents that are known to misbehave with 503.', 'all-in-one-seo-pack' ),
				'block_refer'    => __( 'Block Referral Spam using HTTP.', 'all-in-one-seo-pack' ),
				'track_blocks'   => __( 'Log and show recent requests from blocked bots.', 'all-in-one-seo-pack' ),
				'htaccess_rules' => __( 'Block bad robots via Apache .htaccess rules. Warning: this will change your web server configuration, make sure you are able to edit this file manually as well.', 'all-in-one-seo-pack' ),
				'edit_blocks'    => __( 'Check this to edit the list of disallowed user agents for blocking bad bots.', 'all-in-one-seo-pack' ),
				'blocklist'      => __( 'This is the list of disallowed user agents used for blocking bad bots.', 'all-in-one-seo-pack' ),
				'referlist'      => __( 'This is the list of disallowed referers used for blocking bad bots.', 'all-in-one-seo-pack' ),
				'blocked_log'    => __( 'Shows log of most recent requests from blocked bots. Note: this will not track any bots that were already blocked at the web server / .htaccess level.', 'all-in-one-seo-pack' ),
			);

			$this->default_options = array(
				'block_bots'     => array( 'name' => __( 'Block Bad Bots using HTTP', 'all-in-one-seo-pack' ) ),
				'block_refer'    => array( 'name' => __( 'Block Referral Spam using HTTP', 'all-in-one-seo-pack' ) ),
				'track_blocks'   => array( 'name' => __( 'Track Blocked Bots', 'all-in-one-seo-pack' ) ),
				'htaccess_rules' => array( 'name' => __( 'Block Bad Bots using .htaccess', 'all-in-one-seo-pack' ) ),
				'edit_blocks'    => array( 'name' => __( 'Use Custom Blocklists', 'all-in-one-seo-pack' ) ),
				'blocklist'      => array(
					'name'     => __( 'User Agent Blocklist', 'all-in-one-seo-pack' ),
					'type'     => 'textarea',
					'rows'     => 5,
					'cols'     => 120,
					'condshow' => array( "{$this->prefix}edit_blocks" => 'on' ),
					'default'  => join( "\n", $this->default_bad_bots() ),
				),
				'referlist'      => array(
					'name'     => __( 'Referer Blocklist', 'all-in-one-seo-pack' ),
					'type'     => 'textarea',
					'rows'     => 5,
					'cols'     => 120,
					'condshow' => array(
						"{$this->prefix}edit_blocks" => 'on',
						"{$this->prefix}block_refer" => 'on',
					),
					'default'  => join( "\n", $this->default_bad_referers() ),
				),
				'blocked_log'    => array(
					'name'     => __( 'Log Of Blocked Bots', 'all-in-one-seo-pack' ),
					'default'  => __( 'No requests yet.', 'all-in-one-seo-pack' ),
					'type'     => 'esc_html',
					'disabled' => 'disabled',
					'save'     => false,
					'label'    => 'top',
					'rows'     => 5,
					'cols'     => 120,
					'style'    => 'min-width:950px',
					'condshow' => array( "{$this->prefix}track_blocks" => 'on' ),
				),
			);
			$is_apache             = false;
			if ( ! empty( $_SERVER['SERVER_SOFTWARE'] ) && stristr( $_SERVER['SERVER_SOFTWARE'], 'Apache' ) !== false ) {
				$is_apache = true;
				add_action( $this->prefix . 'settings_update', array( $this, 'generate_htaccess_blocklist' ), 10 );
			} else {
				unset( $this->default_options['htaccess_rules'] );
				unset( $help_text['htaccess_rules'] );
			}

			if ( ! empty( $help_text ) ) {
				foreach ( $help_text as $k => $v ) {
					$this->default_options[ $k ]['help_text'] = $v;
				}
			}

			add_filter( $this->prefix . 'display_options', array( $this, 'filter_display_options' ) );

			// Load initial options / set defaults,
			$this->update_options();

			if ( $this->option_isset( 'edit_blocks' ) ) {
				add_filter( $this->prefix . 'badbotlist', array( $this, 'filter_bad_botlist' ) );
				if ( $this->option_isset( 'block_refer' ) ) {
					add_filter( $this->prefix . 'badreferlist', array( $this, 'filter_bad_referlist' ) );
				}
			}

			if ( $this->option_isset( 'block_bots' ) ) {
				if ( ! $this->allow_bot() ) {
					status_header( 503 );
					$ip         = $this->validate_ip( $_SERVER['REMOTE_ADDR'] );
					$user_agent = $_SERVER['HTTP_USER_AGENT'];
					$this->blocked_message( sprintf( __( 'Blocked bot with IP %s -- matched user agent %s found in blocklist.', 'all-in-one-seo-pack' ), $ip, $user_agent ) );
					exit();
				} elseif ( $this->option_isset( 'block_refer' ) && $this->is_bad_referer() ) {
					status_header( 503 );
					$ip      = $this->validate_ip( $_SERVER['REMOTE_ADDR'] );
					$referer = $_SERVER['HTTP_REFERER'];
					$this->blocked_message( sprintf( __( 'Blocked bot with IP %s -- matched referer %s found in blocklist.', 'all-in-one-seo-pack' ), $ip, $referer ) );
				}
			}
		}

		/**
		 * Validate IP.
		 *
		 * @param $ip
		 *
		 * @since 2.3.7
		 *
		 * @return string
		 */
		function validate_ip( $ip ) {

			if ( filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) ) {
				// Valid IPV4.
				return $ip;
			}

			if ( filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 ) ) {
				// Valid IPV6.
				return $ip;
			}

			// Doesn't seem to be a valid IP.
			return 'invalid IP submitted';

		}

		function generate_htaccess_blocklist() {
			if ( ! $this->option_isset( 'htaccess_rules' ) ) {

				if ( insert_with_markers( get_home_path() . '.htaccess', $this->name, '' ) ) {
					aioseop_output_notice( __( 'Updated .htaccess rules.', 'all-in-one-seo-pack' ) );
				} else {
					aioseop_output_notice( __( 'Failed to update .htaccess rules!', 'all-in-one-seo-pack' ), '', 'error' );
				}

				return;

			}

			if ( function_exists( 'apache_get_modules' ) ) {
				$modules = apache_get_modules();
				foreach ( array( 'mod_authz_host', 'mod_setenvif' ) as $m ) {
					if ( ! in_array( $m, $modules ) ) {
						aioseop_output_notice( sprintf( __( 'Apache module %s is required!', 'all-in-one-seo-pack' ), $m ), '', 'error' );
					}
				}
			}
			$botlist = $this->default_bad_bots();
			$botlist = apply_filters( $this->prefix . 'badbotlist', $botlist );
			if ( ! empty( $botlist ) ) {
				$regex      = $this->quote_list_for_regex( $botlist, '"' );
				$htaccess   = array();
				$htaccess[] = 'SetEnvIfNoCase User-Agent "' . $regex . '" bad_bot';
				if ( $this->option_isset( 'edit_blocks' ) && $this->option_isset( 'block_refer' ) && $this->option_isset( 'referlist' ) ) {
					$referlist = $this->default_bad_referers();
					$referlist = apply_filters( $this->prefix . 'badreferlist', $botlist );
					if ( ! empty( $referlist ) ) {
						$regex      = $this->quote_list_for_regex( $referlist, '"' );
						$htaccess[] = 'SetEnvIfNoCase Referer "' . $regex . '" bad_bot';
					}
				}
				$htaccess[] = 'Deny from env=bad_bot';
				if ( insert_with_markers( get_home_path() . '.htaccess', $this->name, $htaccess ) ) {
					aioseop_output_notice( __( 'Updated .htaccess rules.', 'all-in-one-seo-pack' ) );
				} else {
					aioseop_output_notice( __( 'Failed to update .htaccess rules!', 'all-in-one-seo-pack' ), '', 'error' );
				}
			} else {
				aioseop_output_notice( __( 'No rules to update!', 'all-in-one-seo-pack' ), '', 'error' );
			}
		}

		/**
		 * @param $referlist
		 *
		 * @return array
		 */
		function filter_bad_referlist( $referlist ) {
			if ( $this->option_isset( 'edit_blocks' ) && $this->option_isset( 'block_refer' ) && $this->option_isset( 'referlist' ) ) {
				$referlist = preg_split('/\r\n|[\r\n]/', $this->options["{$this->prefix}referlist"] );
			}

			return $referlist;
		}

		/**
		 * @param $botlist
		 *
		 * @return array
		 */
		function filter_bad_botlist( $botlist ) {
			if ( $this->option_isset( 'edit_blocks' ) && $this->option_isset( 'blocklist' ) ) {
				$botlist = preg_split('/\r\n|[\r\n]/', $this->options["{$this->prefix}blocklist"] );
			}

			return $botlist;
		}


		/**
		 * Updates blocked message.
		 *
		 * @param string $msg
		 */
		function blocked_message( $msg ) {

			if ( ! $this->option_isset( 'track_blocks' ) ) {
				return; // Only log if track blocks is checked.
			}

			if ( empty( $this->options["{$this->prefix}blocked_log"] ) ) {
				$this->options["{$this->prefix}blocked_log"] = '';
			}
			$this->options["{$this->prefix}blocked_log"] = date( 'Y-m-d H:i:s' ) . " {$msg}\n" . $this->options["{$this->prefix}blocked_log"];
			if ( $this->strlen( $this->options["{$this->prefix}blocked_log"] ) > 4096 ) {
				$end = $this->strrpos( $this->options["{$this->prefix}blocked_log"], "\n" );
				if ( false === $end ) {
					$end = 4096;
				}
				$this->options["{$this->prefix}blocked_log"] = $this->substr( $this->options["{$this->prefix}blocked_log"], 0, $end );
			}
			$this->update_class_option( $this->options );
		}


		/**
		 * Filter display options.
		 *
		 * Add in options for status display on settings page, sitemap rewriting on multisite.
		 *
		 * @param $options
		 *
		 * @return mixed
		 */
		function filter_display_options( $options ) {

			if ( $this->option_isset( 'blocked_log' ) ) {
				if ( preg_match( '/\<(\?php|script)/', $options["{$this->prefix}blocked_log"] ) ) {
					$options["{$this->prefix}blocked_log"] = "Probable XSS attempt detected!\n" . $options["{$this->prefix}blocked_log"];
				}
			}

			return $options;
		}
	}
}
