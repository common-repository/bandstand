<?php
/**
 * Edit Record Archive administration screen integration.
 *
 * @package   Bandstand\Discography
 * @copyright Copyright (c) 2016, AudioTheme, LLC
 * @license   GPL-2.0+
 * @link      https://audiotheme.com/
 * @since     1.0.0
 */

/**
 * Class providing integration with the Edit Record Archive administration screen.
 *
 * @package Bandstand\Discography
 * @since   1.0.0
 */
class Bandstand_Screen_EditRecordArchive extends Bandstand_Screen_AbstractScreen {
	/**
	 * Register hooks.
	 *
	 * @since 1.0.0
	 */
	public function register_hooks() {
		add_action( 'add_bandstand_archive_settings_meta_box_bandstand_record', '__return_true' );
		add_action( 'save_bandstand_archive_settings',     array( $this, 'on_save' ), 10, 3 );
		add_action( 'bandstand_archive_settings_meta_box', array( $this, 'display_orderby_field' ) );
	}

	/**
	 * Save record archive sort order.
	 *
	 * The $post_id and $post parameters will refer to the archive CPT, while the
	 * $post_type parameter references the type of post the archive is for.
	 *
	 * @since 1.0.0
	 *
	 * @param int     $post_id   Post ID.
	 * @param WP_Post $post      Post object.
	 * @param string  $post_type The type of post the archive lists.
	 */
	public function on_save( $post_id, $post, $post_type ) {
		if ( 'bandstand_record' !== $post_type ) {
			return;
		}

		$orderby = isset( $_POST['bandstand_orderby'] ) ? sanitize_key( $_POST['bandstand_orderby'] ) : '';
		update_post_meta( $post_id, 'orderby', $orderby );
	}

	/**
	 * Add an orderby setting to the record archive.
	 *
	 * Allows for changing the sort order of videos. Custom would require a plugin
	 * like Simple Page Ordering.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post $post Post object.
	 */
	public function display_orderby_field( $post ) {
		$post_type = is_bandstand_post_type_archive_id( $post->ID );
		if ( 'bandstand_record' !== $post_type ) {
			return;
		}

		$options = array(
			'release_date' => esc_html__( 'Release Date', 'bandstand' ),
			'title'        => esc_html__( 'Title', 'bandstand' ),
			'custom'       => esc_html__( 'Custom', 'bandstand' ),
		);

		$orderby = get_bandstand_archive_meta( 'orderby', true, 'release_date', 'bandstand_record' );
		?>
		<p>
			<label for="bandstand-orderby"><?php esc_html_e( 'Order by:', 'bandstand' ); ?></label>
			<select name="bandstand_orderby" id="bandstand-orderby">
				<?php
				foreach ( $options as $id => $value ) {
					printf(
						'<option value="%1$s"%2$s>%3$s</option>',
						esc_attr( $id ),
						selected( $id, $orderby, false ),
						esc_html( $value )
					);
				}
				?>
			</select>
		</p>
		<?php
	}
}
