<?php
/*
Plugin Name: TheCartPress PhotoZoom
Plugin URI:  http://extend.thecartpress.com/ecommerce-plugins/photozoom-for-thecartpress/
Description: Add zoom to product photos
Version: 1.0.0
Author: TheCartPress team
Author URI: http://thecartpress.com
License: GPL
Parent: thecartpress
*/

/**
 * This file is part of TheCartPress-PhotoZoom.
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

class TCPPhotoZoom {

	function __construct() {
		if ( is_admin() ) {
			add_action( 'tcp_theme_compatibility_settings_page', array( &$this, 'tcp_theme_compatibility_settings_page' ) );
			add_filter( 'tcp_theme_compatibility_unset_settings_action', array( &$this, 'tcp_theme_compatibility_unset_settings_action' ), 10, 2 );
			add_filter( 'tcp_theme_compatibility_settings_action', array( &$this, 'tcp_theme_compatibility_settings_action' ), 10, 2 );
		}
		add_action( 'wp_enqueue_scripts', array( &$this, 'wp_enqueue_scripts' ) );
		add_action( 'wp_footer', array( &$this, 'wp_footer' ) );
	}

	function tcp_theme_compatibility_settings_page( $suffix ) {
		global $thecartpress;
		$zoom_type = $thecartpress->get_setting( 'zoom_type' . $suffix, '' ); ?>
<h3><?php _e( 'PhotoZoom', 'tcp-photozoom' ); ?></h3>

<p class="description"><?php _e( 'Allows to select different types of zoom to apply to images.', 'tcp-photozoom' ); ?></p>

<div class="postbox">
<table class="form-table">
<tbody>
<tr valign="top">
	<th scope="row">
		<label for="zoom_type"><?php _e( 'Load default buy button style', 'tcp' ); ?></label>
	</th>
	<td>
		<select id="zoom_type" name="zoom_type">
			<option value="" <?php selected( '', $zoom_type ); ?>><?php _e( 'Standard Zoom', 'tcp-photozoom' ); ?></option>
			<option value="ON_GRAB" <?php selected( 'ON_GRAB', $zoom_type ); ?>><?php _e( 'On Grab', 'tcp-photozoom' ); ?></option>
			<option value="ON_CLICK" <?php selected( 'ON_CLICK', $zoom_type ); ?>><?php _e( 'On Click', 'tcp-photozoom' ); ?></option>
		</select>
	</td>
</tr>
</tbody>
</table>
</div><?php
	}

	function tcp_theme_compatibility_unset_settings_action( $settings, $suffix ) {
		unset( $settings['tcp_zoom_type' . $suffix] );
		return $settings;
	}

	function tcp_theme_compatibility_settings_action( $settings, $suffix ) {
		$settings['tcp_zoom_type' . $suffix] = isset( $_POST['zoom_type'] ) ? $_POST['zoom_type'] : '';
		return $settings;
	}

	function wp_enqueue_scripts() {
		if ( is_singular() ) wp_enqueue_script( 'jquery-zoom', plugins_url( 'js/jquery.zoom.js', __FILE__ ), array( 'jquery' ) );
	}

	function wp_footer() {
		if ( is_singular() ) : 
			global $thecartpress;
			$zoom_type = $thecartpress->get_setting( 'tcp_zoom_type', '' ); ?>
<script type="text/javascript">
function tcp_set_zoom( clazz ) {
	jQuery( clazz ).wrap( '<div class="tcp_zoom"></div>' );
	jQuery( clazz )
	.parent()
	<?php if ( $zoom_type == '' ) : ?>.zoom();
	<?php elseif ( $zoom_type == 'ON_GRAB' ) : ?>.zoom({ on:'grab' });
	<?php else : ?>.zoom({ on:'click' });
	<?php endif; ?>
}

jQuery( document).ready( function() {
	tcp_set_zoom( '.wp-post-image' );
	tcp_set_zoom( '.tcp_single_img_featured' );
} );
</script>
	<?php endif;
	}
}

new TCPPhotoZoom();
?>