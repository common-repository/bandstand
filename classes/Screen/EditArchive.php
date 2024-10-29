<?php
/**
 * Edit Archive administration screen integration.
 *
 * @package   Bandstand\Archives
 * @copyright Copyright (c) 2016, AudioTheme, LLC
 * @license   GPL-2.0+
 * @link      https://audiotheme.com/
 * @since     1.0.0
 */

/**
 * Class providing integration with the Edit Archive administration screen.
 *
 * @package Bandstand\Archives
 * @since   1.0.0
 */
class Bandstand_Screen_EditArchive extends Bandstand_Screen_AbstractScreen {
	/**
	 * Archives module.
	 *
	 * @since 1.0.0
	 * @var Bandstand_Module_Archives
	 */
	protected $module;

	/**
	 * Constructor method.
	 *
	 * @since 1.0.0
	 *
	 * @param Bandstand_Module_Archives $module Archives module.
	 */
	public function __construct( Bandstand_Module_Archives $module ) {
		$this->module = $module;
	}

	/**
	 * Register hooks.
	 *
	 * @since 1.0.0
	 */
	public function register_hooks() {
		add_action( 'add_meta_boxes_bandstand_archive',    array( $this, 'add_meta_boxes' ) );
		add_action( 'bandstand_archive_settings_meta_box', array( $this, 'settings_meta_box_fields' ), 15, 3 );
		add_action( 'save_post',                           array( $this, 'on_archive_save' ), 10, 2 );
	}

	/**
	 * Replace the submit meta box to remove unnecessary fields.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post $post Post object.
	 */
	public function add_meta_boxes( $post ) {
		$post_type = $this->module->is_archive_id( $post->ID );

		// Activate the default archive settings meta box.
		$show = apply_filters( 'add_bandstand_archive_settings_meta_box', false, $post_type );
		$show_for_post_type = apply_filters( 'add_bandstand_archive_settings_meta_box_' . $post_type, false );

		// Show if any settings fields have been registered for the post type.
		$fields = $this->module->get_settings_fields( $post_type );

		remove_meta_box( 'submitdiv', 'bandstand_archive', 'side' );

		add_meta_box(
			'submitdiv',
			esc_html__( 'Update', 'bandstand' ),
			array( $this, 'post_submit_meta_box' ),
			'bandstand_archive',
			'side',
			'high'
		);

		if ( $show || $show_for_post_type || ! empty( $fields ) ) {
			add_meta_box(
				'audiothem-archive-settings',
				esc_html__( 'Archive Settings', 'bandstand' ),
				array( $this, 'display_settings_meta_box' ),
				'bandstand_archive',
				'side',
				'default',
				array( 'fields' => $fields )
			);
		}
	}

	/**
	 * Display archive settings meta box.
	 *
	 * The meta box needs to be activated first, then fields can be displayed
	 * using one of the actions.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post $post Archive post.
	 * @param array   $args Meta box arguments.
	 */
	public function display_settings_meta_box( $post, $args = array() ) {
		$post_type = $this->module->is_archive_id( $post->ID );
		wp_nonce_field( 'save-archive-meta_' . $post->ID, 'bandstand_archive_nonce' );
		do_action( 'bandstand_archive_settings_meta_box', $post, $post_type, $args['args']['fields'] );
		do_action( 'bandstand_archive_settings_meta_box_' . $post_type, $post, $args['args']['fields'] );
	}

	/**
	 * Add fields to the archive settings meta box.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post $post Archive post.
	 * @param string  $post_type Post type name.
	 * @param array   $fields Settings fields.
	 */
	public function settings_meta_box_fields( $post, $post_type, $fields = array() ) {
		if ( empty( $fields ) ) {
			return;
		}

		if ( ! empty( $fields['order'] ) && $fields['order'] ) {
			$value = $this->module->get_archive_meta( 'order', true, 'ASC', $post_type );
			?>
			<p>
				<label for="bandstand-order"><?php esc_html_e( 'Order:', 'bandstand' ); ?></label>
				<select name="order" id="bandstand-order">
					<option value=""></option>
					<option value="asc"<?php selected( $value, 'asc' ); ?>><?php esc_html_e( 'Ascending', 'bandstand' ); ?></option>
					<option value="desc"<?php selected( $value, 'desc' ); ?>><?php esc_html_e( 'Descending', 'bandstand' ); ?></option>
				</select>
			</p>
			<?php
		}

		if ( ! empty( $fields['posts_per_archive_page'] ) && $fields['posts_per_archive_page'] ) {
			$value = $this->module->get_archive_meta( 'posts_per_archive_page', true, '', $post_type );
			?>
			<p>
				<label for="bandstand-posts-per-archive-page"><?php esc_html_e( 'Posts per page:', 'bandstand' ); ?></label>
				<input type="text" name="posts_per_archive_page" id="bandstand-posts-per-archive-page" value="<?php echo esc_attr( $value ); ?>" class="small-text">
			</p>
			<?php
		}

		if ( ! empty( $fields['columns'] ) && $fields['columns'] ) {
			$default = empty( $fields['columns']['default'] ) ? 3 : absint( $fields['columns']['default'] );
			$value   = $this->module->get_archive_meta( 'columns', true, $default, $post_type );
			$choices = range( 3, 5 );

			if ( ! empty( $fields['columns']['choices'] ) && is_array( $fields['columns']['choices'] ) ) {
				$choices = $fields['columns']['choices'];
			}
			?>
			<p>
				<label for="bandstand-columns"><?php esc_html_e( 'Columns:', 'bandstand' ); ?></label>
				<select name="columns" id="bandstand-columns">
					<?php
					foreach ( $choices as $number ) {
						printf(
							'<option value="%1$d"%2$s>%1$d</option>',
							absint( $number ),
							selected( $number, $value, false )
						);
					}
					?>
				</select>
			</p>
			<?php
		}
	}

	/**
	 * Save archive meta data.
	 *
	 * @since 1.0.0
	 *
	 * @param int     $post_id Post ID.
	 * @param WP_Post $post Post object.
	 */
	public function on_archive_save( $post_id, $post ) {
		$is_autosave    = defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE;
		$is_revision    = wp_is_post_revision( $post_id );
		$is_valid_nonce = isset( $_POST['bandstand_archive_nonce'] ) && wp_verify_nonce( $_POST['bandstand_archive_nonce'], 'save-archive-meta_' . $post_id );

		// Bail if the data shouldn't be saved or intention can't be verified.
		if ( $is_autosave || $is_revision || ! $is_valid_nonce ) {
			return;
		}

		$post_type = $this->module->is_archive_id( $post->ID );
		do_action( 'save_bandstand_archive_settings', $post_id, $post, $post_type );

		$fields = $this->module->get_settings_fields( $post_type );

		// Sanitize and save the order setting.
		if ( ! empty( $fields['order'] ) ) {
			$order = '';
			if ( 'desc' === $_POST['order'] ) {
				$order = 'desc';
			} elseif ( 'asc' === $_POST['order'] ) {
				$order = 'asc';
			}

			update_post_meta( $post_id, 'order', $order );
		}

		// Sanitize and save the posts per archive page setting.
		if ( ! empty( $fields['posts_per_archive_page'] ) && $fields['posts_per_archive_page'] ) {
			$posts_per_archive_page = is_numeric( $_POST['posts_per_archive_page'] ) ? intval( $_POST['posts_per_archive_page'] ) : '';
			update_post_meta( $post_id, 'posts_per_archive_page', $posts_per_archive_page );
		}

		// Sanitize and save the columns setting.
		if ( ! empty( $fields['columns'] ) && $fields['columns'] ) {
			$choices = range( 3, 5 );
			if ( ! empty( $fields['columns']['choices'] ) && is_array( $fields['columns']['choices'] ) ) {
				$choices = array_map( 'absint', $fields['columns']['choices'] );
			}

			$value = absint( $_POST['columns'] );
			if ( ! in_array( $value, $choices, true ) ) {
				$choices_min = min( $choices );
				$choices_max = max( $choices );
				$value       = min( max( $value, $choices_min ), $choices_max );
			}

			update_post_meta( $post_id, 'columns', $value );
		}
	}

	/**
	 * Submit meta box.
	 *
	 * @since 1.0.0
	 *
	 * @see post_submit_meta_box()
	 *
	 * @param WP_Post $post Post object.
	 * @param array   $args Additional meta box arguments.
	 */
	public function post_submit_meta_box( $post, $args ) {
		?>
		<div class="submitbox" id="submitpost">
			<div id="minor-publishing">

				<!-- Hidden submit button early on so that the browser chooses the right button when form is submitted with Return key. -->
				<div style="display: none"><?php submit_button( __( 'Update', 'bandstand' ), 'button', 'save' ); ?></div>

				<input type="hidden" name="hidden_post_status" id="hidden_post_status" value="publish">
				<input type="hidden" name="post_status" id="post_status" value="publish">
				<input type="hidden" name="hidden_post_visibility" value="public">
				<input type="hidden" name="visibility" value="public">

				<div class="clear"></div>
			</div>


			<div id="major-publishing-actions">
				<?php do_action( 'post_submitbox_start' ); ?>

				<div id="publishing-action">
					<span class="spinner"></span>
					<input type="hidden" name="original_publish" id="original_publish" value="<?php esc_attr_e( 'Update', 'bandstand' ) ?>">
					<input type="submit" name="save" id="publish" class="button-primary button-large" accesskey="p" value="<?php esc_attr_e( 'Update', 'bandstand' ) ?>">
				</div>

				<div class="clear"></div>
			</div>
		</div>
		<?php
	}
}
