<?php
/**
 * View to display a dashboard screen header.
 *
 * @package   Bandstand\Administration
 * @copyright Copyright (c) 2016, AudioTheme, LLC
 * @license   GPL-2.0+
 * @link      https://audiotheme.com/
 * @since     1.0.0
 */

?>

<div class="wrap">

<header class="bandstand-dashboard-hero">
	<div class="bandstand-dashboard-hero-branding">
		<div class="bandstand-dashboard-hero-logo"><a href="https://audiotheme.com/view/audiotheme/" target="_blank"><svg viewBox="0 0 80 40">
			<path fill="#E4002B" d="M10,40C4.5,40,0,35.5,0,30s4.5-10,10-10s10,4.5,10,10S15.5,40,10,40z M0,0l40,40V20L20,0H0z M80,0H40v14h40V0z M74,40L60,18L46,40H74z"/>
		</svg></a></div>
		<p>
			<?php esc_html_e( 'Bandstand has the tools you need to easily manage your gigs, discography, videos and more.', 'bandstand' ); ?>
		</p>
	</div>
</header>

<!-- Notice catcher -->
<h1 style="display: none"></h1>
