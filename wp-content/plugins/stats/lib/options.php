<?php

function stats_get_api_key() {
	return stats_get_option('api_key');
}

function stats_set_api_key($api_key) {
	stats_set_option('api_key', $api_key);
}

function stats_get_options() {
	$options = get_option( 'stats_options' );

	if ( !isset( $options['version'] ) || $options['version'] < STATS_VERSION )
		$options = stats_upgrade_options( $options );

	return $options;
}

function stats_get_option( $option ) {
	$options = stats_get_options();

	if ( isset( $options[$option] ) )
		return $options[$option];

	return null;
}

function stats_set_option( $option, $value ) {
	$options = stats_get_options();

	$options[$option] = $value;

	stats_set_options($options);
}

function stats_set_options($options) {
	update_option( 'stats_options', $options );
}

function stats_upgrade_options( $options ) {
	$defaults = array(
		'host'         => '',
		'path'         => '',
		'blog_id'      => false,
		'wp_me'        => true,
		'roles'        => array('administrator','editor','author'),
		'reg_users'    => false,
		'footer'       => false,
	);

	if ( is_array( $options ) && !empty( $options ) )
		$options = array_merge( $defaults, $options );
	else
		$options = $defaults;

	// Send new bloginfo with gmt_offset
	if ( $options['version'] < 3 )
		$update_bloginfo = true;

	$options['version'] = STATS_VERSION;

	stats_set_options( $options );

	if ( $update_bloginfo )
		stats_update_bloginfo();

	return $options;
}

