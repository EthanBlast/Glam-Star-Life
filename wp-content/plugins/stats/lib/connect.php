<?php

function stats_client() {
	require_once( ABSPATH . WPINC . '/class-IXR.php' );
	$client = new IXR_ClientMulticall( STATS_XMLRPC_SERVER );
	$client->useragent = 'WordPress/' . $client->useragent;
	return $client;
}

function stats_add_call() {
	global $stats_xmlrpc_client;
	if ( empty($stats_xmlrpc_client) ) {
		$stats_xmlrpc_client = stats_client();
		ignore_user_abort(true);
		add_action('shutdown', 'stats_multicall_query');
	}

	$args = func_get_args();

	call_user_method_array( 'addCall', $stats_xmlrpc_client, $args );
}

function stats_multicall_query() {
	global $stats_xmlrpc_client;

	$stats_xmlrpc_client->query();
}

// TODO
function stats_is_connected() {
	return false;
}