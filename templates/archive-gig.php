<?php
/**
 * The template to display a list of gigs.
 *
 * @package   Bandstand\Template
 * @copyright Copyright (c) 2016, AudioTheme, LLC
 * @license   GPL-2.0+
 * @link      https://audiotheme.com/
 * @since     1.0.0
 */

get_header();
?>

<?php do_action( 'bandstand_before_content' ); ?>

<?php get_bandstand_template_part( 'parts/archive-header', 'event' ); ?>

<?php get_bandstand_template_part( 'gig/loop', 'archive' ); ?>

<?php do_action( 'bandstand_after_content' ); ?>

<?php
get_footer();
