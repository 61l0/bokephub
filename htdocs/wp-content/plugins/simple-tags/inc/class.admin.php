<?php

class SimpleTags_Admin {
	// CPT and Taxonomy support
	static $post_type = 'post';
	static $post_type_name = '';
	static $taxonomy = '';
	static $taxo_name = '';

	static $admin_url = '';
	const menu_slug = 'st_options';

	/**
	 * Initialize Admin
	 *
	 * @return void
	 * @author Amaury Balmer
	 */
	public function __construct() {
		// DB Upgrade ?
		self::upgrade();

		// Which taxo ?
		self::registerDetermineTaxonomy();

		// Admin menu
		add_action( 'admin_menu', array( __CLASS__, 'admin_menu' ) );

		// Load JavaScript and CSS
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_enqueue_scripts' ) );

		// Load custom part of plugin depending option
		if ( (int) SimpleTags_Plugin::get_option_value( 'use_suggested_tags' ) == 1 ) {
			require( STAGS_DIR . '/inc/class.admin.suggest.php' );
			new SimpleTags_Admin_Suggest();
		}

		if ( (int) SimpleTags_Plugin::get_option_value( 'use_click_tags' ) == 1 ) {
			require( STAGS_DIR . '/inc/class.admin.clickterms.php' );
			new SimpleTags_Admin_ClickTags();
		}

		if ( (int) SimpleTags_Plugin::get_option_value( 'use_autocompletion' ) == 1 ) {
			require( STAGS_DIR . '/inc/class.admin.autocomplete.php' );
			new SimpleTags_Admin_Autocomplete();
		}

		if ( (int) SimpleTags_Plugin::get_option_value( 'active_mass_edit' ) == 1 ) {
			require( STAGS_DIR . '/inc/class.admin.mass.php' );
			new SimpleTags_Admin_Mass();
		}

		if ( (int) SimpleTags_Plugin::get_option_value( 'active_manage' ) == 1 ) {
			require( STAGS_DIR . '/inc/class.admin.manage.php' );
			new SimpleTags_Admin_Manage();
		}

		if ( (int) SimpleTags_Plugin::get_option_value( 'active_autotags' ) == 1 ) {
			require( STAGS_DIR . '/inc/class.admin.autoterms.php' );
			new SimpleTags_Admin_AutoTags();
		}

		if ( (int) SimpleTags_Plugin::get_option_value( 'active_autotags' ) == 1 || (int) SimpleTags_Plugin::get_option_value( 'auto_link_tags' ) == 1 ) {
			require( STAGS_DIR . '/inc/class.admin.post.php' );
			new SimpleTags_Admin_Post_Settings();
		}
	}

	/**
	 * Init taxonomy class variable, load this action after all actions on init !
	 * Make a public static function for call it from children class...
	 *
	 * @return void
	 * @author Amaury Balmer
	 */
	public static function registerDetermineTaxonomy() {
		add_action( 'init', array( __CLASS__, 'init' ), 99999999 );
	}

	/**
	 * Put in var class the current taxonomy choose by the user
	 *
	 * @return void
	 * @author Amaury Balmer
	 */
	public static function init() {
		self::$taxo_name      = __( 'Post tags', 'simpletags' );
		self::$post_type_name = __( 'Posts', 'simpletags' );

		// Custom CPT ?
		if ( isset( $_GET['cpt'] ) && ! empty( $_GET['cpt'] ) && post_type_exists( $_GET['cpt'] ) ) {
			$cpt                  = get_post_type_object( $_GET['cpt'] );
			self::$post_type      = $cpt->name;
			self::$post_type_name = $cpt->labels->name;
		}

		// Get compatible taxo for current post type
		$compatible_taxonomies = get_object_taxonomies( self::$post_type );

		// Custom taxo ?
		if ( isset( $_GET['taxo'] ) && ! empty( $_GET['taxo'] ) && taxonomy_exists( $_GET['taxo'] ) ) {
			$taxo = get_taxonomy( $_GET['taxo'] );

			// Taxo is compatible ?
			if ( in_array( $taxo->name, $compatible_taxonomies ) ) {
				self::$taxonomy  = $taxo->name;
				self::$taxo_name = $taxo->labels->name;
			} else {
				unset( $taxo );
			}
		}

		// Default taxo from CPT...
		if ( ! isset( $taxo ) && is_array( $compatible_taxonomies ) && ! empty( $compatible_taxonomies ) ) {
			// Take post_tag before category
			if ( in_array( 'post_tag', $compatible_taxonomies ) ) {
				$taxo = get_taxonomy( 'post_tag' );
			} else {
				$taxo = get_taxonomy( current( $compatible_taxonomies ) );
			}

			self::$taxonomy  = $taxo->name;
			self::$taxo_name = $taxo->labels->name;

			// TODO: Redirect for help user that see the URL...
		} elseif ( ! isset( $taxo ) ) {
			wp_die( __( 'This custom post type not have taxonomies.', 'simpletags' ) );
		}

		// Free memory
		unset( $cpt, $taxo );
	}

	/**
	 * Build HTML form for allow user to change taxonomy for the current page.
	 *
	 * @param string $page_value
	 *
	 * @return void
	 * @author Amaury Balmer
	 */
	public static function boxSelectorTaxonomy( $page_value = '' ) {
		echo '<div class="box-selector-taxonomy">' . PHP_EOL;
		echo '<p class="current-taxonomy">' . sprintf( __( 'You currently use the custom post type "<span>%s</span>" and the taxonomy "<span>%s</span>"', 'simpletags' ), self::$post_type_name, self::$taxo_name ) . '</p>' . PHP_EOL;

		echo '<div class="change-taxo">' . PHP_EOL;
		echo '<form action="" method="get">' . PHP_EOL;
		if ( ! empty( $page_value ) ) {
			echo '<input type="hidden" name="page" value="' . $page_value . '" />' . PHP_EOL;
		}

		echo '<select name="cpt" id="cpt-select">' . PHP_EOL;
		foreach ( get_post_types( array( 'show_ui' => true ), 'objects' ) as $post_type ) {
			$taxonomies = get_object_taxonomies( $post_type->name );
			if ( empty( $taxonomies ) ) {
				continue;
			}

			echo '<option ' . selected( $post_type->name, self::$post_type, false ) . ' value="' . esc_attr( $post_type->name ) . '">' . esc_html( $post_type->labels->name ) . '</option>' . PHP_EOL;
		}
		echo '</select>' . PHP_EOL;

		echo '<select name="taxo" id="taxonomy-select">' . PHP_EOL;
		foreach ( get_object_taxonomies( self::$post_type ) as $tax_name ) {
			$taxonomy = get_taxonomy( $tax_name );
			if ( $taxonomy->show_ui == false ) {
				continue;
			}

			echo '<option ' . selected( $tax_name, self::$taxonomy, false ) . ' value="' . esc_attr( $tax_name ) . '">' . esc_html( $taxonomy->labels->name ) . '</option>' . PHP_EOL;
		}
		echo '</select>' . PHP_EOL;

		echo '<input type="submit" class="button" id="submit-change-taxo" value="' . __( 'Change selection', 'simpletags' ) . '" />' . PHP_EOL;
		echo '</form>' . PHP_EOL;
		echo '</div>' . PHP_EOL;
		echo '</div>' . PHP_EOL;
	}

	/**
	 * Init somes JS and CSS need for simple tags.
	 *
	 * @return void
	 * @author Amaury Balmer
	 */
	public static function admin_enqueue_scripts() {
		global $pagenow;

		// Helper simple tags
		wp_register_script( 'st-helper-add-tags', STAGS_URL . '/assets/js/helper-add-tags.js', array( 'jquery' ), STAGS_VERSION );
		wp_register_script( 'st-helper-options', STAGS_URL . '/assets/js/helper-options.js', array( 'jquery' ), STAGS_VERSION );

		// Register CSS
		wp_register_style( 'st-admin', STAGS_URL . '/assets/css/admin.css', array(), STAGS_VERSION, 'all' );

		// Register location
		$wp_post_pages = array( 'post.php', 'post-new.php' );
		$wp_page_pages = array( 'page.php', 'page-new.php' );

		// Common Helper for Post, Page and Plugin Page
		if (
			in_array( $pagenow, $wp_post_pages ) ||
			( in_array( $pagenow, $wp_page_pages ) && is_page_have_tags() ) ||
			( isset( $_GET['page'] ) && in_array( $_GET['page'], array(
					'st_mass_terms',
					'st_auto',
					'st_options',
					'st_manage'
				) ) )
		) {
			wp_enqueue_style( 'st-admin' );
		}

		// add jQuery tabs for options page. Use jQuery UI Tabs from WP
		if ( isset( $_GET['page'] ) && $_GET['page'] == 'st_options' ) {
			wp_enqueue_script( 'jquery-ui-tabs' );
			wp_enqueue_script( 'st-helper-options' );
		}
	}

	/**
	 * Add settings page on WordPress admin menu
	 *
	 * @return void
	 * @author Amaury Balmer
	 */
	public static function admin_menu() {
		add_options_page( __( 'Simple Tags: Options', 'simpletags' ), __( 'Simple Tags', 'simpletags' ), 'admin_simple_tags', self::menu_slug, array(
			__CLASS__,
			'page_options',
		) );
		self::$admin_url = admin_url( '/options-general.php?page=' . self::menu_slug );
	}

	/**
	 * Build HTML for page options, manage also save/reset settings
	 *
	 * @return void
	 * @author Amaury Balmer
	 */
	public static function page_options() {
		// Get options
		$options = SimpleTags_Plugin::get_option();

		// Update or reset options
		if ( isset( $_POST['updateoptions'] ) ) {
			check_admin_referer( 'updateresetoptions-simpletags' );

			foreach ( (array) $options as $key => $value ) {
				$newval = ( isset( $_POST[ $key ] ) ) ? stripslashes( $_POST[ $key ] ) : '0';
				if ( $newval != $value ) {
					$options[ $key ] = $newval;
				}
			}
			SimpleTags_Plugin::set_option( $options );

			add_settings_error( __CLASS__, __CLASS__, __( 'Options saved', 'simpletags' ), 'updated' );
		} elseif ( isset( $_POST['reset_options'] ) ) {
			check_admin_referer( 'updateresetoptions-simpletags' );

			SimpleTags_Plugin::set_default_option();

			add_settings_error( __CLASS__, __CLASS__, __( 'Simple Tags options resetted to default options!', 'simpletags' ), 'updated' );
		}

		settings_errors( __CLASS__ );
		include( STAGS_DIR . '/views/admin/page-settings.php' );
	}

	/**
	 * Get terms for a post, format terms for input and autocomplete usage
	 *
	 * @param string $taxonomy
	 * @param integer $post_id
	 *
	 * @return string
	 * @author Amaury Balmer
	 */
	public static function getTermsToEdit( $taxonomy = 'post_tag', $post_id = 0 ) {
		$post_id = (int) $post_id;
		if ( ! $post_id ) {
			return false;
		}

		$terms = wp_get_post_terms( $post_id, $taxonomy, array( 'fields' => 'names' ) );
		if ( $terms == false ) {
			return false;
		}

		$terms = array_unique( $terms ); // Remove duplicate
		$terms = join( ', ', $terms );
		$terms = esc_attr( $terms );
		$terms = apply_filters( 'tags_to_edit', $terms );

		return $terms;
	}

	/**
	 * Default content for meta box of Simple Tags
	 *
	 * @return string
	 * @author Amaury Balmer
	 */
	public static function getDefaultContentBox() {
		if ( (int) wp_count_terms( 'post_tag', 'ignore_empty=false' ) == 0 ) { // TODO: Custom taxonomy
			return __( 'This feature requires at least 1 tag to work. Begin by adding tags!', 'simpletags' );
		} else {
			return __( 'This feature works only with activated JavaScript. Activate it in your Web browser so you can!', 'simpletags' );
		}
	}

	/**
	 * A short public static function for display the same copyright on all admin pages
	 *
	 * @return void
	 * @author Amaury Balmer
	 */
	public static function printAdminFooter() {
		?>
		<p class="footer_st"><?php printf( __( '&copy; Copyright 2007-2013 <a href="http://www.herewithme.fr/" title="Here With Me">Amaury Balmer</a> | <a href="http://wordpress.org/extend/plugins/simple-tags">Simple Tags</a> | Version %s', 'simpletags' ), STAGS_VERSION ); ?></p>
		<?php
	}

	/**
	 * Ouput formatted options
	 *
	 * @param array $option_data
	 *
	 * @return string
	 * @author Amaury Balmer
	 */
	public static function print_options( $option_data ) {
		// Get options
		$option_actual = SimpleTags_Plugin::get_option();

		// Generate output
		$output = '';
		foreach ( $option_data as $section => $options ) {
			$colspan       = count( $options ) > 1 ? 'colspan="2"' : '';
			$desc_html_tag = 'div';

			$output .= '<div class="group" id="' . sanitize_title( $section ) . '">' . PHP_EOL;
			$output .= '<fieldset class="options">' . PHP_EOL;
			$output .= '<legend>' . self::getNiceTitleOptions( $section ) . '</legend>' . PHP_EOL;
			$output .= '<table class="form-table">' . PHP_EOL;
			foreach ( (array) $options as $option ) {
				// Helper
				if ( $option[2] == 'helper' ) {
					$output .= '<tr style="vertical-align: middle;"><td class="helper" ' . $colspan . '>' . stripslashes( $option[4] ) . '</td></tr>' . PHP_EOL;
					continue;
				}

				// Fix notices
				if ( ! isset( $option_actual[ $option[0] ] ) ) {
					$option_actual[ $option[0] ] = '';
				}

				switch ( $option[2] ) {
					case 'radio':
						$input_type = array();;
						foreach ( $option[3] as $value => $text ) {
							$input_type[] = '<label><input type="radio" id="' . $option[0] . '" name="' . $option[0] . '" value="' . esc_attr( $value ) . '" ' . checked( $value, $option_actual[ $option[0] ], false ) . ' /> ' . $text . '</label>' . PHP_EOL;
						}
						$input_type = implode( '<br />', $input_type );
						break;

					case 'checkbox':
						$desc_html_tag = 'span';
						$input_type    = '<input type="checkbox" id="' . $option[0] . '" name="' . $option[0] . '" value="' . esc_attr( $option[3] ) . '" ' . ( ( $option_actual[ $option[0] ] ) ? 'checked="checked"' : '' ) . ' />' . PHP_EOL;
						break;

					case 'dropdown':
						$selopts = explode( '/', $option[3] );
						$seldata = '';
						foreach ( (array) $selopts as $sel ) {
							$seldata .= '<option value="' . esc_attr( $sel ) . '" ' . ( ( isset( $option_actual[ $option[0] ] ) && $option_actual[ $option[0] ] == $sel ) ? 'selected="selected"' : '' ) . ' >' . ucfirst( $sel ) . '</option>' . PHP_EOL;
						}
						$input_type = '<select id="' . $option[0] . '" name="' . $option[0] . '">' . $seldata . '</select>' . PHP_EOL;
						break;

					case 'text-color':
						$input_type = '<input type="text" id="' . $option[0] . '" name="' . $option[0] . '" value="' . esc_attr( $option_actual[ $option[0] ] ) . '" class="text-color ' . $option[3] . '" /><div class="box_color ' . $option[0] . '"></div>' . PHP_EOL;
						break;

					case 'text':
						$input_type = '<input type="text" id="' . $option[0] . '" name="' . $option[0] . '" value="' . esc_attr( $option_actual[ $option[0] ] ) . '" class="' . $option[3] . '" />' . PHP_EOL;
						break;

					case 'number':
					default:
						$input_type = '<input type="number" id="' . $option[0] . '" name="' . $option[0] . '" value="' . esc_attr( $option_actual[ $option[0] ] ) . '" class="' . $option[3] . '" />' . PHP_EOL;
						break;
				}

				// Additional Information
				$extra = '';
				if ( ! empty( $option[4] ) ) {
					$extra = '<' . $desc_html_tag . ' class="stpexplan">' . __( $option[4] ) . '</' . $desc_html_tag . '>' . PHP_EOL;
				}

				// Output
				$output .= '<tr style="vertical-align: top;"><th scope="row"><label for="' . $option[0] . '">' . __( $option[1] ) . '</label></th><td>' . $input_type . '	' . $extra . '</td></tr>' . PHP_EOL;
			}
			$output .= '</table>' . PHP_EOL;
			$output .= '</fieldset>' . PHP_EOL;
			$output .= '</div>' . PHP_EOL;
		}

		return $output;
	}

	/**
	 * Get nice title for tabs title option
	 *
	 * @param string $id
	 *
	 * @return string
	 */
	public static function getNiceTitleOptions( $id = '' ) {
		switch ( $id ) {
			case 'administration':
				return __( 'Administration', 'simpletags' );
				break;
			case 'auto-links':
				return __( 'Auto link', 'simpletags' );
				break;
			case 'features':
				return __( 'Features', 'simpletags' );
				break;
			case 'metakeywords':
				return __( 'Meta Keyword', 'simpletags' );
				break;
			case 'embeddedtags':
				return __( 'Embedded Tags', 'simpletags' );
				break;
			case 'tagspost':
				return __( 'Tags for Current Post', 'simpletags' );
				break;
			case 'relatedposts':
				return __( 'Related Posts', 'simpletags' );
				break;
			case 'relatedtags':
				return __( 'Related Tags', 'simpletags' );
				break;
			case 'tagcloud':
				return __( 'Tag cloud', 'simpletags' );
				break;
		}

		return '';
	}

	/**
	 * This method allow to check if the DB is up to date, and if a upgrade is need for options
	 * TODO, useful or delete ?
	 *
	 * @return void
	 * @author Amaury Balmer
	 */
	public static function upgrade() {
		// Get current version number
		$current_version = get_option( STAGS_OPTIONS_NAME . '-version' );

		// Upgrade needed ?
		if ( $current_version == false || version_compare( $current_version, STAGS_VERSION, '<' ) ) {
			$current_options = get_option( STAGS_OPTIONS_NAME );
			$default_options = (array) include( STAGS_DIR . '/inc/helper.options.default.php' );

			// Add new options
			foreach ( $default_options as $key => $default_value ) {
				if ( ! isset( $current_options[ $key ] ) ) {
					$current_options[ $key ] = $default_value;
				}
			}

			// Remove old options
			foreach ( $current_options as $key => $current_value ) {
				if ( ! isset( $default_options[ $key ] ) ) {
					unset( $current_options[ $key ] );
				}
			}

			update_option( STAGS_OPTIONS_NAME . '-version', STAGS_VERSION );
			update_option( STAGS_OPTIONS_NAME, $current_options );
		}

		return true;
	}

	/**
	 * Make a simple SQL query with some args for get terms for ajax display
	 *
	 * @param string $taxonomy
	 * @param string $search
	 * @param string $order_by
	 * @param string $order
	 *
	 * @return array
	 * @author Amaury Balmer
	 */
	public static function getTermsForAjax( $taxonomy = 'post_tag', $search = '', $order_by = 'name', $order = 'ASC' ) {
		global $wpdb;

		if ( ! empty( $search ) ) {
			return $wpdb->get_results( $wpdb->prepare( "
				SELECT DISTINCT t.name, t.term_id
				FROM {$wpdb->terms} AS t
				INNER JOIN {$wpdb->term_taxonomy} AS tt ON t.term_id = tt.term_id
				WHERE tt.taxonomy = %s
				AND t.name LIKE %s
				ORDER BY $order_by $order
			", $taxonomy, '%' . $search . '%' ) );
		} else {
			return $wpdb->get_results( $wpdb->prepare( "
				SELECT DISTINCT t.name, t.term_id
				FROM {$wpdb->terms} AS t
				INNER JOIN {$wpdb->term_taxonomy} AS tt ON t.term_id = tt.term_id
				WHERE tt.taxonomy = %s
				ORDER BY $order_by $order
			", $taxonomy ) );
		}
	}
}
