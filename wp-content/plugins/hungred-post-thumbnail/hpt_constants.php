<?php
/*  Copyright 2009  Clay Lua  (email : clay@hungred.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if (!(file_exists( ABSPATH . 'wp-config.php') || ( file_exists( dirname(ABSPATH) . '/wp-config.php' ) && ! file_exists( dirname(ABSPATH) . '/wp-settings.php' ) ) || ( file_exists( $path . '/wp-config.php' ) && ! file_exists( $path . '/wp-settings.php' ) ))){
	$path = dirname( __FILE__ );
	for($i = 0; $i < 10; $i++){
		if ( file_exists( $path . '/wp-settings.php') ) {
			$realpath = $path;
		}
		if ( file_exists( $path . '/wp-config.php') ) {
			if(!file_exists( $path . '/wp-settings.php' )){
				if ( !defined('ABSPATH') )
					define('ABSPATH', $realpath . '/');
			}
			if ( ! defined( 'WP_CONFIG_DIR' ) )
				define( 'WP_CONFIG_DIR', $path . '/wp-config.php' );
			require_once($path . '/wp-config.php' );
			break;
		}else{
			$path = dirname($path);
		}
	}
}
/************************************
*									*
*		Hungred Ads Manager			*
*									*
*************************************/	
if ( ! defined( 'HPT_PLUGIN_DIR' ) )
	define( 'HPT_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . plugin_basename( dirname( __FILE__ ) ) );
if ( ! defined( 'HPT_PLUGIN_URL' ) )
	define( 'HPT_PLUGIN_URL', WP_PLUGIN_URL . '/' . plugin_basename( dirname( __FILE__ ) ) );
	
$dir = wp_upload_dir();
$save_path = $dir['basedir'].'/'.plugin_basename( dirname( __FILE__ ) ).'/';	
$save_url = $dir['baseurl'].'/'.plugin_basename( dirname( __FILE__ ) ).'/';

@mkdir($save_path,0755,true);
@mkdir($save_path.'images',0755,true);
@mkdir($save_path.'images/live/',0755,true);
@mkdir($save_path.'images/draft/',0755,true);
@mkdir($save_path.'images/random/',0755,true);
@mkdir($save_path.'images/original/',0755,true);
@mkdir($save_path.'images/original/live/',0755,true);
@mkdir($save_path.'images/original/draft/',0755,true);
@mkdir($save_path.'images/original/random/',0755,true);

if ( ! defined( 'HPT_UPLOAD_URL' ) )
	define( 'HPT_UPLOAD_URL', $save_url );
if ( ! defined( 'HPT_UPLOAD_DIR' ) )
	define( 'HPT_UPLOAD_DIR',$save_path );
?>