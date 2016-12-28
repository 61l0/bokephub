<?php
/**
 * WP-PostRatings Sortcodes.
 *
 * @package WordPress
 * @subpackage WP-PostRatings Plugin
 */


/**
 * Security check
 * Prevent direct access to the file.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


### Function: Short Code For Inserting Ratings Into Posts
function ratings_shortcode( $atts ) {
    $attributes = shortcode_atts( array( 'id' => 0, 'results' => false ), $atts );
    if( ! is_feed() ) {
        $id = intval( $attributes['id'] );
        if( $attributes['results'] ) {
            return the_ratings_results( $id );
        } else {
            return the_ratings( 'span', $id, false );
        }
    } else {
        return esc_html__( 'Note: There is a rating embedded within this post, please visit this post to rate it.', 'wp-postratings' );
    }
}
add_shortcode( 'ratings', 'ratings_shortcode' );
