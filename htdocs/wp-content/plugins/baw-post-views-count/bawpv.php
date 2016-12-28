<?php
/* Plugin Name: BAW Post Views Count
 * Plugin URI: http://www.boiteaweb.fr
 * Description: Count views for post and pages, shortcode [post_view] and [most_view] available, widget "Most Viewed Posts" available.
 * Version: 3.0.2
 * Author: Juliobox
 * Author URI: http://www.boiteaweb.fr
 * License: GPLv2
**/
DEFINE( 'BAWPVC_FULLNAME', 'Post Views Count' );
DEFINE( 'BAWPVC_VERSION', '3.0.2' );

function bawpvc_main() {
	global $post;
	$bawpvc_options = bawpvc_get_options();
	$timings = bawpcv_get_timings();
	$bots = array( 	'wordpress', 'googlebot', 'google', 'msnbot', 'ia_archiver', 'lycos', 'jeeves', 'scooter', 'fast-webcrawler', 'slurp@inktomi', 'turnitinbot', 'technorati',
					'yahoo', 'findexa', 'findlinks', 'gaisbo', 'zyborg', 'surveybot', 'bloglines', 'blogsearch', 'pubsub', 'syndic8', 'userland', 'gigabot', 'become.com' );
	if( 	! ( ( $bawpvc_options['no_members']=='on' && is_user_logged_in() ) || ( $bawpvc_options['no_admins']=='on' && current_user_can( 'administrator' ) ) ) && 
			! empty( $_SERVER['HTTP_USER_AGENT'] ) &&
			( defined( 'DOING_AJAX' ) || is_singular( $bawpvc_options['post_types'] ) ) && 
			! preg_match( '/' . implode( '|', $bots ) . '/i', isset( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : BAWPVC_FULLNAME )
		)
	{	
		foreach( $timings as $time=>$date ) {
			if( $time != 'all' ) {
				$date = '-' . date( $date );
			}
			// Filtered meta key name
			$meta_key_filtered = apply_filters( 'baw_count_views_meta_key', '_count-views_' . $time . $date, $time, $date );
			$count = (int) get_post_meta( $post->ID, $meta_key_filtered, true );
			++$count;
			update_post_meta( $post->ID, $meta_key_filtered, $count );
			// Normal meta key name
			$meta_key = '_count-views_' . $time . $date;
			if ( $meta_key_filtered != $meta_key ) {
				$count = (int) get_post_meta( $post->ID, $meta_key, true );
				++$count;
				update_post_meta( $post->ID, $meta_key, $count );
			}
		}
		return true;
	} else {
		return false;
	}
}

add_shortcode( 'post_view', 'bawpvc_views_sc' );
add_shortcode( 'post_views', 'bawpvc_views_sc' );
function bawpvc_views_sc( $atts, $content = null ) {
	global $post;
	$args = shortcode_atts(array(
		"id" => isset( $post->ID ) ? (int) $post->ID : 0,
		"time" => 'all',
		"date" => '' //YYYYMMDD format
	), $atts, 'bawpvc' );

	$id = $args['id'];
	if ( (int) $id > 0 ) {
		$timings = bawpcv_get_timings();
		$time = $args['time'];
		$date = $args['date'] != '' ? $args['date'] : date( $timings[$time] );
		$date = $time == 'all' ? '' : '-' . $date;
		$meta_key = apply_filters( 'baw_count_views_meta_key', '_count-views_' . $time . $date, $time, $date );
		$count = (int) get_post_meta( $id, $meta_key, true );
		do_action( 'baw_count_views_count_action', $count, $meta_key, $time, $date, $id );
		$count = apply_filters( 'baw_count_views_count', $count, $meta_key, $time, $date, $id );
		return '<p class="bawpvc-ajax-counter" data-id="' . $id . '">' . $count . '</p>';
	}
	return '';
}

add_filter( 'baw_count_views_count', 'bawpvc_format', 1 );
function bawpvc_format( $count ) {
	$bawpvc_options = bawpvc_get_options();
	return sprintf( str_replace( '%count%', '%d', $bawpvc_options['format'] ), $count );
}


class BAW_Widget_Most_Viewed_Posts extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'widget_most_viewed_entries', 'description' => __( 'The most viewed posts on your site', 'bawpvc' ) );
		parent::__construct( 'widget_most_viewed_entries', __( 'Most Viewed Posts', 'bawpvc' ), $widget_ops );
		$this->alt_option_name = 'widget_most_viewed_entries';

		add_action( 'save_post', array(&$this, 'flush_widget_cache') );
		add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
	}

	function widget($args, $instance) {
		$cache = wp_cache_get('widget_most_viewed_entries', 'widget');
		if ( !is_array($cache) )
			$cache = array();

		if ( ! isset( $args['widget_id'] ) )
			$args['widget_id'] = $this->id;

		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			echo $cache[ $args['widget_id'] ];
			return;
		}

		ob_start();
		extract($args);

		$title = apply_filters('widget_title', empty($instance['title']) ? __('Most Viewed Posts') : $instance['title'], $instance, $this->id_base);
		if ( empty( $instance['number'] ) || ! $number = absint( $instance['number'] ) )
 			$number = 10;
		$timings = bawpcv_get_timings();
		$date = $instance['date'] != '' ? $instance['date'] : date( $timings[$instance['time']] );
		$date = $instance['time'] == 'all' ? '' : '-' . $date;
		$time = $instance['time'];
		$exclude_cat = $instance['exclude_cat'];
		$order = $instance['order'] == 'ASC' ? 'ASC' : 'DESC';
		$author_id = $instance['author'];
		$meta_key = apply_filters( 'baw_count_views_meta_key', '_count-views_' . $time . $date, $time, $date );
		$bawpvc_options = bawpvc_get_options();
		$r = new WP_Query( array(	'posts_per_page' => $number, 
									'no_found_rows' => true, 
									'post_status' => 'publish', 
									'ignore_sticky_posts' => true, 
									'meta_key' => $meta_key, 'meta_value_num' => '0', 
									'meta_compare' => '>', 
									'orderby'=>'meta_value_num', 
									'order'=>$order,
									'author'=>$author_id,
									'category__not_in'=>$exclude_cat,
									'post_type'=> apply_filters( 'baw_count_views_widget_post_types', $bawpvc_options['post_types'] )
								) 
						);
		if ($r->have_posts()) :
		?>
		<?php echo $before_widget; ?>
		<?php if ( $title ) echo $before_title . $title . $after_title; ?>
		<ul>
		<?php  while ($r->have_posts()) : $r->the_post(); ?>
		<?php
			$count = '';
			if( $instance['show'] ):
				$count = (int)get_post_meta( get_the_ID(), $meta_key, true );
				do_action( 'baw_count_views_count_action', $count, $meta_key, $time, $date, get_the_ID() );
				$count = apply_filters( 'baw_count_views_count', $count, $meta_key, $time, $date );
			endif;
		?>
		<li><a href="<?php the_permalink() ?>" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>"><?php if ( get_the_title() ) the_title(); else the_ID(); echo $count; ?></a></li>
		<?php endwhile; ?>
		</ul>
		<?php echo $after_widget; ?>
		<?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		endif;

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set('widget_most_viewed_entries', $cache, 'widget');
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['exclude_cat'] = $new_instance['exclude_cat'];
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['author'] = $new_instance['author'];
		$instance['time'] = $new_instance['time'];
		$instance['date'] = $new_instance['date'];
		$instance['number'] = (int) $new_instance['number'];
		$instance['show'] = (bool)$new_instance['show'];
		$instance['order'] = $new_instance['order'] == 'ASC' ? 'ASC' : 'DESC';

		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['widget_most_viewed_entries']) )
			delete_option('widget_most_viewed_entries');

		return $instance;
	}

	function flush_widget_cache() {
		wp_cache_delete('widget_most_viewed_entries', 'widget');
	}

	function form( $instance ) {
		$exclude_cat = isset($instance['exclude_cat']) ? $instance['exclude_cat'] : '';
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$number = isset($instance['number']) ? absint($instance['number']) : 5;
		$time = isset($instance['time']) ? ($instance['time']) : 'all';
		$author_id = isset($instance['author']) ? ($instance['author']) : '';
		$date = isset($instance['date']) ? ($instance['date']) : '';
		$show = isset($instance['show']) ? $instance['show'] == 'on' : true;
		if( isset( $instance['order'] ) )
			$order = $new_instance['order'] == 'ASC' ? 'ASC' : 'DESC';
		else
			$order = 'DESC';
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', 'bawpvc' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e( 'How many posts:', 'bawpvc' ); ?></label>
		<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>

		<p><label for="<?php echo $this->get_field_id('time'); ?>"><?php _e( 'Which top do you want:', 'bawpvc' ); ?></label>
		<select id="<?php echo $this->get_field_id('time'); ?>" name="<?php echo $this->get_field_name('time'); ?>">
		<?php 
			$timings = bawpcv_get_timings();
			foreach( $timings as $timing=>$dummy ) { ?>
			<option value="<?php echo esc_attr( $timing ); ?>" <?php selected( $timing, $time ); ?>><?php echo ucwords( esc_html( $timing ) ); ?></option>
			<?php } ?>
		</select>
		
		<p><label for="<?php echo $this->get_field_id('author'); ?>"><?php _e( 'Top for this author only:', 'bawpvc' ); ?></label>
		<select id="<?php echo $this->get_field_id('author'); ?>" name="<?php echo $this->get_field_name('author'); ?>">
			<option value=""><?php _e( 'All authors', 'bawpvc' ); ?></option>
		<?php foreach( get_users() as $u ) { ?>
			<option value="<?php echo $u->ID; ?>" <?php selected( $author_id, $u->ID ); ?>><?php echo ucwords( esc_html( $u->display_name ) ); ?></option>
			<?php } ?>
		</select>
		<?php /* // todo
		<p><label for="<?php echo $this->get_field_id('author'); ?>"><?php _e( 'Exclude categories: (Multiple choise possible)', 'bawpvc' ); ?></label>
		<?php add_filter( 'wp_dropdown_cats', 'bawmrp_wp_dropdown_cats' ); ?>
		<?php wp_dropdown_categories( array( 'name'=>$this->get_field_name('exclude_cat').'[]' ) ); //// ?>
		<?php remove_filter( 'wp_dropdown_cats', 'bawmrp_wp_dropdown_cats' ); ?>
		<?php print_r( $exclude_cat ); ?>
		*/ ?>
		<p><label for="<?php echo $this->get_field_id('date'); ?>"><?php _e( 'Date format', 'bawpvc' ); ?> <code>YYYYMMAA</code></label>
		<input id="<?php echo $this->get_field_id('date'); ?>" name="<?php echo $this->get_field_name('date'); ?>" type="text" value="<?php echo esc_attr( $date ); ?>" size="6" maxlength="8" /><br />
		<code><?php _e( 'If you leave blank the actual time will be used.', 'bawpvc' ); ?></code></p>

		<p><label for="<?php echo $this->get_field_id('show'); ?>"><?php _e( 'Show posts count:', 'bawpvc' ); ?></label>
		<input id="<?php echo $this->get_field_id('show'); ?>" name="<?php echo $this->get_field_name('show'); ?>" type="checkbox" <?php checked( $show == true, true ); ?> /></p>

		<p><label for="<?php echo $this->get_field_id('order'); ?>"><?php _e( 'Order', 'bawpvc' ); ?></label>
		<select id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>">
			<option value="DESC" <?php selected( $order, 'DESC' ); ?>><?php _e( 'From most viewed to less viewed', 'bawpvc' ); ?></option>
			<option value="ASC" <?php selected( $order, 'ASC' ); ?>><?php _e( 'From less viewed to most viewed', 'bawpvc' ); ?></option>
		</select>
		</p>

<?php
	}
}
// todo2
// function bawmrp_wp_dropdown_cats( $output )
// {
// 	$output = str_replace( '<select ', '<select size="4" multiple ', $output );
// 	$output = str_replace( '[exclude_cat]"', '[exclude_cat][]"', $output );
// 	return $output;
// }

add_shortcode( 'most_views', 'bawpvc_shortcode_top_most' );
add_shortcode( 'most_view', 'bawpvc_shortcode_top_most' );
add_shortcode( 'most_viewed', 'bawpvc_shortcode_top_most' );
function bawpvc_shortcode_top_most( $atts, $content ) {
	$timings = bawpcv_get_timings();
	$bawpvc_options = bawpvc_get_options();
	extract(shortcode_atts(array(
		'number' => 10,
		'show' => 1,
		'time' => 'all',
		'date' => '',
		'before' => '',
		'after' => '',
		'ul_class' => 'pvc',
		'li_class' => 'pvc',
		'order' => 'DESC',
		'author' => '',
		'post_type' => $bawpvc_options['post_types']
	), $atts));
	
	$date = $date != '' ? $date : date( $timings[$time] );
	$date = $time == 'all' ? '' : '-' . $date;
	$ul_class = $ul_class!='' ? ' class="' . sanitize_html_class( $ul_class ) . '"' : '';
	$li_class = $ul_class!='' ? ' class="' . sanitize_html_class( $li_class ) . '"' : '';
	$order = $order == 'ASC' ? 'ASC' : 'DESC';
	$author_name = '';
	if( !is_numeric( $author ) ):
		$author_name = $author;
		$author = '';
	endif;
	$meta_key = apply_filters( 'baw_count_views_meta_key', '_count-views_' . $time . $date, $time, $date );
	$post_type = is_array( $post_type ) ? $post_type : explode( ',', $post_type );
	ob_start();
	$r = new WP_Query( array(	'posts_per_page' => $number, 
								'no_found_rows' => true, 
								'post_status' => 'publish', 
								'post_type' => $post_type,
								'ignore_sticky_posts' => true, 
								'meta_key' => $meta_key, 
								'meta_value_num' => '0', 
								'meta_compare' => '>', 
								'orderby' => 'meta_value_num',
								'author' => $author,
								'author_name' => $author_name,
								'order' => $order )
							);
	if ($r->have_posts()) :
		echo $before; ?>
		<ul<?php echo $ul_class; ?>>
		<?php  while ($r->have_posts()) : $r->the_post(); ?>
		<?php 	
			$count = '';
			if( $show ):
				$count = (int)get_post_meta( get_the_ID(), $meta_key, true );
				do_action( 'baw_count_views_count_action', $count, $meta_key, $time, $date, get_the_ID() );
				$count = apply_filters( 'baw_count_views_count', $count, $meta_key, $time, $date );
			endif;
		?>
		<li<?php echo $li_class; ?>><a href="<?php the_permalink() ?>" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>"><?php if ( get_the_title() ) the_title(); else the_ID(); echo $count; ?></a></li>
		<?php endwhile; ?>
		</ul>
		<?php echo $after;
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();
	endif;
	$result = ob_get_contents();
	ob_end_clean();
	return $result;
}
 
add_action( 'widgets_init', 'bawpvc_widgets_init' );
function bawpvc_widgets_init() {
	register_widget( 'BAW_Widget_Most_Viewed_Posts' );
}

add_action( 'init', 'bawpvc_init', 1 );
function bawpvc_init() {
	load_plugin_textdomain( 'bawpvc', '', dirname( plugin_basename( __FILE__ ) ) . '/lang' );
}

function bawpvc_get_options() {
	$bawpvc_args = array( 'time' => 0,
				'format' => ' (%count%)',
				'in_content' => false,
				'no_members' => false,
				'no_admins' => false,
				'post_types' => array( 'post' )
			);
	return wp_parse_args( get_option( 'bawpvc_options' ), $bawpvc_args );
}

function bawpcv_get_timings() {
	return apply_filters( 'baw_count_views_timings', array( 'all'=>'', 'day'=>'Ymd', 'week'=>'YW', 'month'=>'Ym', 'year'=>'Y' ) );
}

function bawpvc_settings_page() {
	add_settings_section( 'bawpvc_settings_page', sprintf( __( 'General', 'bawpvc' ), BAWPVC_FULLNAME ), '__return_false', 'bawpvc_settings' );
		add_settings_field( 'bawpvc_field_format', __( 'Count format', 'bawpvc' ), 'bawpvc_field_format', 'bawpvc_settings', 'bawpvc_settings_page' );
		add_settings_field( 'bawpvc_field_in_content', __( 'Display counter in post content', 'bawpvc' ), 'bawpvc_field_in_content', 'bawpvc_settings', 'bawpvc_settings_page' );
		add_settings_field( 'bawpvc_field_no_members', __( 'Do not count views for logged members', 'bawpvc' ), 'bawpvc_field_no_members', 'bawpvc_settings', 'bawpvc_settings_page' );
		add_settings_field( 'bawpvc_field_no_admins', __( 'Do not count views for admins', 'bawpvc' ), 'bawpvc_field_no_admins', 'bawpvc_settings', 'bawpvc_settings_page' );
		add_settings_field( 'bawpvc_field_post_types', __( 'Select post types', 'bawpvc' ), 'bawpvc_field_post_types', 'bawpvc_settings', 'bawpvc_settings_page' );
?>
	<div class="wrap">
	<h3><?php echo BAWPVC_FULLNAME; ?> v<?php echo BAWPVC_VERSION; ?></h3>

	<form action="options.php" method="post">
		<?php settings_fields( 'bawpvc_settings' ); ?>
		<?php do_settings_sections( 'bawpvc_settings' ); ?>
		<?php submit_button(); ?>
		<?php do_settings_sections( 'bawpvc_settings2' ); ?>
	</form>
<?php
}

function bawpvc_field_post_types() {
	$bawpvc_options = bawpvc_get_options();
	$cpts = get_post_types( array( ), 'objects' );
	foreach ( $cpts as $cpt ) {
		$checked = checked( in_array($cpt->name, $bawpvc_options['post_types']) ? 'on' : '', 'on', false );
		$name = esc_attr( $cpt->name );
		$label = esc_html( $cpt->label );
		$public = $cpt->public ? __( 'Public', 'bawpvc' ) : __( 'Not public', 'bawpvc' );
		printf( '<label><input type="checkbox" %s name="bawpvc_options[post_types][]" value="%s" /> %s <em>(%s)</em></label><br />', $checked, $name, $label, $public );
	}
	?>
	<code>
	<?php _e( 'Public: Whether a post type is intended to be used publicly either via the admin interface or by front-end users.<br />Not public: The opposite...', 'bawpvc' ); ?>
	</code>
	<?php
}

function bawpvc_field_no_members() {
	$bawpvc_options = bawpvc_get_options();
?>
	<label><input type="checkbox" name="bawpvc_options[no_members]" <?php checked( $bawpvc_options['no_members'], 'on' ); ?> /> <em><?php _e( 'Check me to avoid counting views for logged members.', 'bawpvc' ); ?></em></label>
<?php
}

function bawpvc_field_no_admins() {
	$bawpvc_options = bawpvc_get_options();
?>
	<label><input type="checkbox" name="bawpvc_options[no_admins]" <?php checked( $bawpvc_options['no_admins'], 'on' ); ?> /> <em><?php _e( 'Check me to avoid counting views for admins.', 'bawpvc' ); ?></em></label>
<?php
}

function bawpvc_field_time_count() {
	$bawpvc_options = bawpvc_get_options();
?>
	<input type="number" size="3" maxlength="3" name="bawpvc_options[time]" value="<?php echo absint( $bawpvc_options['time'] ); ?>" /> <em><?php _e( 'seconds', 'bawpvc' ); ?></em>
<?php
}

function bawpvc_field_format() {
	$bawpvc_options = bawpvc_get_options();
?>
	<input type="text" name="bawpvc_options[format]" size="40" value="<?php echo esc_attr( $bawpvc_options['format'] ); ?>" /> <em><?php _e( 'Use <code>%count%</code> to display the counter.', 'bawpvc' ); ?></em>
<?php
}

function bawpvc_field_in_content() {
	$bawpvc_options = bawpvc_get_options();
?>
	<label><input type="checkbox" name="bawpvc_options[in_content]" <?php checked( $bawpvc_options['in_content'], 'on' ); ?> /> <em><?php _e( 'Will be displayed in bottom of content.', 'bawpvc' ); ?></em></label>
<?php
}

add_action( 'admin_menu', 'bawpvc_create_menu' );
function bawpvc_create_menu() {
	add_options_page( BAWPVC_FULLNAME, BAWPVC_FULLNAME , 'manage_options', 'bawpvc_settings', 'bawpvc_settings_page' );
	register_setting( 'bawpvc_settings', 'bawpvc_options' );
}


register_activation_hook( __FILE__, 'bawpvc_activation' );
function bawpvc_activation() {
	add_option( 'bawpvc_options',array(	'time' => 0,
										'format' => ' (%count%)',
										'in_content' => false
										) );
}

register_uninstall_hook( __FILE__, 'bawpvc_uninstaller' );
function bawpvc_uninstaller() {
	global $wpdb;
	$wpdb->query( 'DELETE FROM ' . $wpdb->postmeta . ' WHERE meta_key LIKE "_count-views%"' );
	delete_option( 'bawpvc_options' );
}

add_filter( 'the_content', 'bawpvc_in_content', 1 );
function bawpvc_in_content( $content ) {
	global $post;
	$bawpvc_options = bawpvc_get_options();
	// if( is_home() || is_front_page() || bawpvc_main() ) {
		wp_enqueue_script( 'jquery' );
		add_action( 'wp_footer', 'bawpvc_ajax_script', PHP_INT_MAX );
		$bawpvc_options = bawpvc_get_options();
		if( 'on' == $bawpvc_options['in_content'] && in_array( $post->post_type, $bawpvc_options['post_types'] ) ) {
			return $content . do_shortcode( '[post_views]' );
		} else {
			return $content;
		}
	// }
}

function bawpvc_ajax_script() {
	$bawpvc_options = bawpvc_get_options();
?>
<script>
jQuery( document ).ready( function($) {
	$('.bawpvc-ajax-counter').each( function( i ) {
		var $id = $(this).data('id');
		var t = this;
		var n = <?php echo (int) is_single(); ?>;
		$.get('<?php echo admin_url( 'admin-ajax.php' ); ?>?action=bawpvc-ajax-counter&p='+$id+'&n='+n, function( html ) {
			$(t).html( html );
		})
	});
});
</script>
<?php
}

add_action( 'wp_ajax_bawpvc-ajax-counter', 'bawpvc_ajax_callback' );
add_action( 'wp_ajax_nopriv_bawpvc-ajax-counter', 'bawpvc_ajax_callback' );
function bawpvc_ajax_callback() {
	if ( isset( $_GET['p'], $_GET['n'] ) ) {
		global $post;
		$post = get_post( $_GET['p'] );
		$count = (int) get_post_meta( $post->ID, '_count-views_all', true );
		if ( $_GET['n'] == 1 && bawpvc_main() ) {
			++$count;
		}
		die( bawpvc_format( $count ) );
	}
}

add_action( 'add_meta_boxes', 'bawpvc_add_meta_boxes' );
function bawpvc_add_meta_boxes() {
	$cpts = get_post_types( array(  ), 'names' );
	foreach ( $cpts as $cpt )
	add_meta_box(
		'bawpvc_meta_box',
		BAWPVC_FULLNAME,
		'bawpvc_add_metabox',
		$cpt,
		'side'
	);
}

function bawpvc_add_metabox() {
?>
	<div id="bawpvc_box">
		<?php _e( '(Clic value to edit it)', 'bawpvc' ); ?>
		<br />
		<ul>
			<?php
			global $post;
			$timings = bawpcv_get_timings();
			foreach( $timings as $time=>$date ):
				if( $date != '' ) $date = '-' . date( $date );
				$meta_key = apply_filters( 'baw_count_views_meta_key', '_count-views_' . $time . $date, $time, $date );
				$count = (int)get_post_meta( $post->ID, $meta_key, true );
				$capa = apply_filters( 'baw_count_views_capa_role', 'edit_posts' );
				if( current_user_can( $capa, $post->ID ) )
					printf( '<li>' . __( 
								'%1$s: <span class="hide-if-no-js toggle_views" onclick="jQuery(this).hide().next(\'span\').show().find(\'input:visible\').select();" title="%4$s">%2$d</span>'.
											'<span class="hide-if-js">'.
												'<input type="hidden" name="old_views_%1$s" value="%2$d" />'.
												'<input type="hidden" name="old_views_date_%1$s" value="%3$s" />'.
												'<input onblur="jQuery(jQuery(this).parent()).hide().prev(\'span\').text(jQuery(this).val()).show();" type="number" min="0" size="2" name="new_views_%1$s" value="%2$d" />'.
											'</span> views', 'bawpvc' .
							'</li>', 'bawpvc' ),
							esc_html( $time ), (int)$count, $date, __( 'Clic to edit me!', 'bawpvc' )
						);
				// else
					// printf( '<li>' . __( '%s: %d views', 'bawpvc' ) . '</li>', esc_html( $time ), (int)$count );
			endforeach;
			?><br />
			<label><input type="checkbox" name="bawpvc_reset" value="on" /> <?php _e( 'Check me to reset all views', 'bawpvc' ); ?></label>
			<?php wp_nonce_field( 'bawpvc-reset_' . $post->ID, 'bawpvc_reset_nonce', true, true ); ?>
		</ul>
	</div>
	<?php
}

add_action( 'save_post', 'bawpvc_reset_from_meta_box' );
function bawpvc_reset_from_meta_box() {
	$capa = apply_filters( 'baw_count_views_capa_role', 'edit_posts' );
	if( isset( $_POST['bawpvc_reset_nonce'], $_POST['post_ID'] ) && current_user_can( $capa, (int)$_POST['post_ID'] ) && (int)$_POST['post_ID']>0 ):
		check_admin_referer( 'bawpvc-reset_' . $_POST['post_ID'], 'bawpvc_reset_nonce' );
		global $wpdb;
		$timings = bawpcv_get_timings();
		if( isset( $_POST['bawpvc_reset'] ) && $_POST['bawpvc_reset']=='on' ):
			$wpdb->query( 'DELETE FROM ' . $wpdb->postmeta . ' WHERE post_id = ' . (int)$_POST['post_ID'] . ' AND meta_key LIKE "_count-views%"' );
			return;
		endif;
		foreach( $timings as $time=>$date ):
			$date = isset( $_POST['old_views_date_' . $time] ) ? $_POST['old_views_date_' . $time] : '';
			if( isset( $_POST['old_views_date_' . $time], $_POST['old_views_' . $time], $_POST['new_views_' . $time] ) && (int)$_POST['old_views_' . $time]!=(int)$_POST['new_views_' . $time] )
				if( (int)$_POST['new_views_' . $time]==0 )
					delete_post_meta( (int)$_POST['post_ID'], '_count-views_'.$time.$date );
				else
					update_post_meta( (int)$_POST['post_ID'], '_count-views_'.$time.$date, (int)$_POST['new_views_' . $time] );
		endforeach;
	endif;
}

function bawpvc_add_post_columns( $columns ) {
	$columns['bawpvc'] = BAWPVC_FULLNAME;
	return $columns;
}

add_action( 'baw_count_views_render_post_columns', 'bawpv_render_post_columns_action' );
function bawpv_render_post_columns_action( $post_id ) {
	echo (int) get_post_meta( $post_id, '_count-views_all', true );
}

function bawpvc_render_post_columns( $column_name, $post_id ) {
	$capa = apply_filters( 'baw_count_views_capa_role', 'edit_posts' );
	if( $column_name == 'bawpvc' && current_user_can( $capa ) ) {
		do_action( 'baw_count_views_render_post_columns', $post_id );
	}
}

add_action( 'load-edit.php', 'bawpvc_admin_init' );
function bawpvc_admin_init() {
	$bawpvc_options = bawpvc_get_options();
	$capa = apply_filters( 'baw_count_views_capa_role', 'edit_posts' );
	if ( current_user_can( $capa ) )
	foreach ( $bawpvc_options['post_types'] as $cpt ) {
		add_action( 'manage_' . $cpt . '_posts_columns', 'bawpvc_add_post_columns', 10, 2 );
		add_action( 'manage_' . $cpt . '_posts_custom_column', 'bawpvc_render_post_columns', 10, 2 );
	}
}

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'bawpvc_settings_action_links', 10, 2 );
function bawpvc_settings_action_links( $links, $file ) {
	if ( current_user_can( 'manage_options' ) ) {
		array_unshift( $links, '<a href="' . admin_url( 'admin.php?page=bawpvc_settings' ) . '">' . __( 'Settings' ) . '</a>' );
	}
	return $links;
}

remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 ); // avoid bad counts for non viewed posts