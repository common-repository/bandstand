<?php
/**
 * Manage Gigs administration screen integration.
 *
 * @package   Bandstand\Gigs
 * @copyright Copyright (c) 2016, AudioTheme, LLC
 * @license   GPL-2.0+
 * @link      https://audiotheme.com/
 * @since     1.0.0
 */

/**
 * Class providing integration with the Manage Gigs administration screen.
 *
 * @package Bandstand\Gigs
 * @since   1.0.0
 */
class Bandstand_Screen_ManageGigs extends Bandstand_Screen_AbstractScreen {
	/**
	 * Register hooks.
	 *
	 * @since 1.0.0
	 */
	public function register_hooks() {
		add_filter( 'parse_query',   array( $this, 'parse_admin_query' ) );
		add_action( 'load-edit.php', array( $this, 'load_screen' ) );
	}

	/**
	 * Set up the screen.
	 *
	 * @since 1.0.0
	 */
	public function load_screen() {
		if ( 'edit-bandstand_gig' !== get_current_screen()->id ) {
			return;
		}

		add_filter( 'views_edit-bandstand_gig',                   array( $this, 'filter_views' ) );
		add_action( 'restrict_manage_posts',                      array( $this, 'display_months_filter' ) );
		add_action( 'restrict_manage_posts',                      array( $this, 'display_venues_filter' ) );
		add_filter( 'manage_bandstand_gig_posts_columns',         array( $this, 'register_columns' ) );
		add_filter( 'list_table_primary_column',                  array( $this, 'register_primary_column' ) );
		add_action( 'manage_posts_custom_column',                 array( $this, 'display_columns' ), 10, 2 );
		add_action( 'manage_edit-bandstand_gig_sortable_columns', array( $this, 'register_sortable_columns' ) );
	}

	/**
	 * Sort posts on the screen.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Query $wp_query The main WP_Query object passed by reference.
	 */
	public function parse_admin_query( $wp_query ) {
		// Ensure this only affects requests in the admin panel.
		if (
			! is_admin() ||
			! $wp_query->is_main_query() ||
			empty( $_GET['post_type'] ) ||
			'bandstand_gig' !== $_GET['post_type']
		) {
			return;
		}

		$current_view = $this->get_current_view();
		$meta_query   = array();

		// Sort in descending order by default.
		$order = isset( $_REQUEST['order'] ) && 'asc' === strtolower( $_REQUEST['order'] ) ? 'asc' : 'desc';
		$wp_query->set( 'order', $order );

		// Upcoming and past views.
		if ( empty( $_REQUEST['m'] ) && ( 'upcoming' === $current_view || 'past' === $current_view ) ) {
			$start = current_time( 'mysql', true );
			if ( isset( $_REQUEST['start_datetime'] ) ) {
				$start = sanitize_text_field( urldecode( $_REQUEST['start_datetime'] ) );
			}

			$compare = '>=';
			if ( isset( $_REQUEST['compare'] ) && in_array( $_REQUEST['compare'], array( '=', '!=', '>', '>=', '<', '<=' ) ) ) {
				$compare = $_REQUEST['compare'];
			}

			$meta_query[] = array(
				'key'     => 'bandstand_upcoming_until_utc',
				'value'   => $start,
				'compare' => $compare,
				'type'    => 'DATETIME',
			);

			// Sort upcoming in ascending order by default.
			if ( 'upcoming' === $current_view && empty( $_REQUEST['order'] ) ) {
				$wp_query->set( 'order', 'asc' );
			}
		}

		// Monthly views.
		elseif ( ! empty( $_REQUEST['m'] ) ) {
			$m     = absint( substr( $_REQUEST['m'], 4 ) );
			$y     = absint( substr( $_REQUEST['m'], 0, 4 ) );
			$start = sprintf( '%s-%s-01', $y, zeroise( $m, 2 ) );
			$end   = sprintf( '%s', date( 'Y-m-t', mktime( 0, 0, 0, $m, 1, $y ) ) );

			$wp_query->set( 'm', null );

			$meta_query = array(
				'key'     => 'bandstand_start_date',
				'value'   => array( $start, $end ),
				'compare' => 'BETWEEN',
				'type'    => 'DATE',
			);

			// Sort in ascending order.
			if ( empty( $_REQUEST['order'] ) ) {
				$wp_query->set( 'order', 'asc' );
			}
		}

		// Filter by venue.
		if ( ! empty( $_REQUEST['venue'] ) ) {
			$meta_query[] = array(
				'key'   => 'bandstand_venue_id',
				'value' => absint( $_REQUEST['venue'] ),
			);
		}

		if ( isset( $_REQUEST['orderby'] ) ) {
			switch ( $_REQUEST['orderby'] ) {
				case 'title':
					$wp_query->set( 'orderby', 'title' );
					break;
				case 'start_datetime' :
					$wp_query->set( 'meta_key', 'bandstand_sort_datetime_utc' );
					$wp_query->set( 'orderby', 'meta_value' );
					break;
				default:
					$wp_query->set( 'meta_key', 'bandstand_' . sanitize_key( $_REQUEST['orderby'] ) );
					$wp_query->set( 'orderby', 'meta_value' );
					break;
			}
		} elseif ( empty( $_REQUEST['post_status'] ) || 'draft' !== $_REQUEST['post_status'] ) {
			$wp_query->set( 'meta_key', 'bandstand_sort_datetime_utc' );
			$wp_query->set( 'orderby', 'meta_value' );
		}

		$wp_query->set( 'meta_query', $meta_query );
	}

	/**
	 * Filter the view links.
	 *
	 * @since 1.0.0
	 *
	 * @param array $views Array of view links.
	 * @return array
	 */
	public function filter_views( $views ) {
		global $wpdb;

		$post_type    = 'bandstand_gig';
		$current_view = $this->get_current_view();
		$base_url     = admin_url( sprintf( 'edit.php?post_type=%s', $post_type ) );

		/*
		 * Add post status query arg to the 'all' link to differentiate from the
		 * default 'upcoming' view and manage the 'current' class.
		 */
		if ( preg_match( '/<a.*?>(?P<text>.+?)<\/a>/', $views['all'], $matches ) ) {
			$views['all'] = sprintf(
				'<a href="%s"%s>%s</a>',
				esc_url( add_query_arg( 'post_status', 'any', $base_url ) ),
				'any' === $current_view ? ' class="current"' : '',
				$matches['text']
			);
		}

		$sql = "SELECT COUNT( DISTINCT p.ID )
			FROM $wpdb->posts p
			INNER JOIN $wpdb->postmeta pm ON p.ID = pm.post_id
			WHERE
				p.post_type = 'bandstand_gig' AND
				p.post_status NOT IN ( 'auto-draft', 'trash' ) AND
				pm.meta_key = 'bandstand_sort_datetime_utc'";

		$current_time   = date( 'Y-m-d H:i:s', current_time( 'timestamp' ) - DAY_IN_SECONDS );
		$upcoming_count = $wpdb->get_var( $wpdb->prepare( $sql . ' AND pm.meta_value >= %s', $current_time ) );
		$past_count     = $wpdb->get_var( $wpdb->prepare( $sql . ' AND pm.meta_value < %s', $current_time ) );

		// @todo Translate the numbers?
		$new_views['upcoming'] = sprintf(
			'<a href="%s"%s>%s <span class="count">(%d)</span></a>',
			esc_url( $base_url ),
			'upcoming' === $current_view ? ' class="current"' : '',
			__( 'Upcoming', 'bandstand' ),
			$upcoming_count
		);

		$new_views['past'] = sprintf(
			'<a href="%s"%s>%s <span class="count">(%d)</span></a>',
			esc_url( add_query_arg( array( 'start_datetime' => current_time( 'mysql' ), 'compare' => rawurlencode( '<' ) ), $base_url ) ),
			'past' === $current_view ? ' class="current"' : '',
			__( 'Past', 'bandstand' ),
			$past_count
		);

		return array_merge( $new_views, $views );
	}

	/**
	 * Display the months filter dropdown.
	 *
	 * @since 1.0.0
	 */
	public function display_months_filter() {
		global $wpdb, $wp_locale;

		$months = $wpdb->get_results(
			"SELECT
				DISTINCT YEAR( meta_value ) AS year,
				MONTH( meta_value ) AS month
			FROM
				$wpdb->posts p,
				$wpdb->postmeta pm
			WHERE
				p.post_type = 'bandstand_gig' AND
				p.post_status NOT IN ( 'auto-draft', 'trash' ) AND
				p.ID = pm.post_id AND
				pm.meta_key = 'bandstand_start_date'
			ORDER BY meta_value DESC"
		);

		$month_count = count( $months );

		if ( ! $month_count || ( 1 === $month_count && 0 === $months[0]->month ) ) {
			return;
		}

		$m = isset( $_GET['m'] ) ? (int) $_GET['m'] : 0;
		?>
		<select name="m">
			<option value="0" <?php selected( $m, 0 ); ?>><?php esc_html_e( 'All dates', 'bandstand' ); ?></option>
			<?php
			foreach ( $months as $arc_row ) {
				if ( empty( $arc_row->year ) ) {
					continue;
				}

				$month = zeroise( $arc_row->month, 2 );
				$year  = $arc_row->year;

				printf(
					'<option value="%s"%s>%s</option>',
					esc_attr( $year . $month ),
					selected( $m, $year . $month, false ),
					esc_html( sprintf( '%s %s', $wp_locale->get_month( $month ), $year ) )
				);
			}
			?>
		</select>
		<?php
	}

	/**
	 * Display the venues filter dropdown.
	 *
	 * @since 1.0.0
	 */
	public function display_venues_filter() {
		global $wpdb;

		$venues = $wpdb->get_results(
			"SELECT p.ID, p.post_title
			FROM $wpdb->posts p
			INNER JOIN $wpdb->postmeta pm ON pm.meta_key = 'bandstand_venue_id' AND p.ID = pm.meta_value
			WHERE p.post_type = 'bandstand_venue'
			GROUP BY p.ID
			ORDER BY p.post_title ASC"
		);
		?>
		<select name="venue">
			<option value=""><?php esc_html_e( 'All venues', 'bandstand' ); ?></option>
			<?php
			if ( $venues ) {
				$selected = ! empty( $_REQUEST['venue'] ) ? absint( $_REQUEST['venue'] ) : '';
				foreach ( $venues as $venue ) {
					printf( '<option value="%s"%s>%s</option>',
						absint( $venue->ID ),
						selected( $selected, $venue->ID, false ),
						esc_html( $venue->post_title )
					);
				}
			}
			?>
		</select>
		<?php
	}

	/**
	 * Register list table columns.
	 *
	 * @since 1.0.0
	 *
	 * @param array $columns An array of the column names to display.
	 * @return array Filtered array of column names.
	 */
	public function register_columns( $columns ) {
		$title_column = $columns['title'];

		$columns['start_datetime'] = esc_html_x( 'Date', 'column name', 'bandstand' );

		// Move the title column after the date column.
		unset( $columns['title'] );
		$columns['title'] = $title_column;

		$columns['venue'] = esc_html__( 'Venue', 'bandstand' );
		unset( $columns['date'] );

		return $columns;
	}

	/**
	 * Register the primary column.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $column_id Column name.
	 * @return string
	 */
	public function register_primary_column( $column_id ) {
		return 'start_datetime';
	}

	/**
	 * Register sortable list table columns.
	 *
	 * @since 1.0.0
	 *
	 * @param array $columns Column query vars with their corresponding column id as the key.
	 * @return array
	 */
	public function register_sortable_columns( $columns ) {
		$columns['title']          = 'title';
		$columns['start_datetime'] = array( 'start_datetime', true );

		return $columns;
	}

	/**
	 * Display custom list table columns.
	 *
	 * @since 1.0.0
	 *
	 * @param string $column_id The id of the column to display.
	 * @param int    $post_id Post ID.
	 */
	public function display_columns( $column_id, $post_id ) {
		switch ( $column_id ) {
			case 'start_datetime' :
				$gig = get_bandstand_gig( $post_id );
				$this->display_date_column( $gig );
				break;
			case 'venue' :
				$gig = get_bandstand_gig( $post_id );
				if ( $gig->has_venue() ) {
					echo esc_html( $gig->get_venue()->get_name() );
				}
				break;
		}
	}

	/**
	 * Display the date column value.
	 *
	 * @since 1.0.0
	 *
	 * @param Bandstand_Post_Gig $gig Gig post object.
	 */
	protected function display_date_column( $gig ) {
		$title = esc_html__( '(no date)', 'bandstand' );
		if ( ! empty( $gig->start_date ) ) {
			$title = $gig->format_start_date( get_option( 'date_format' ) );
		}

		$time = esc_html__( 'TBD', 'bandstand' );
		if ( $gig->has_start_time() ) {
			$time = $gig->format_start_date( '', get_option( 'time_format' ) );
		}

		printf(
			'<a href="%1$s" class="row-title">%2$s</a> - %3$s',
			esc_url( get_edit_post_link( $gig->ID ) ),
			esc_html( $title ),
			esc_html( $time )
		);
	}

	/**
	 * Retrieve the current view.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	protected function get_current_view() {
		$view = 'upcoming';

		if (
			( isset( $_REQUEST['start_datetime'] ) && empty( $_REQUEST['m'] ) && ( empty( $_REQUEST['compare'] ) || false !== strpos( $_REQUEST['compare'], '>' ) ) ) ||
			( empty( $_REQUEST['post_status'] ) && empty( $_REQUEST['start_datetime'] ) )
		) {
			$view = 'upcoming';
		} elseif (
			isset( $_REQUEST['start_datetime'] ) &&
			isset( $_REQUEST['compare'] ) &&
			'<' === $_REQUEST['compare'] &&
			empty( $_REQUEST['m'] )
		) {
			$view = 'past';
		} elseif ( ! empty( $_REQUEST['post_status'] ) ) {
			$view = sanitize_key( $_REQUEST['post_status'] );
		}

		return $view;
	}
}
