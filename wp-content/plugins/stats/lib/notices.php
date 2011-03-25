<?php

function stats_admin_notices() {
	if ( function_exists('is_plugin_active_for_network') && is_plugin_active_for_network(plugin_basename(STATS_FILE)) )
		return;
	stats_notice_connect();
}

// If not yet connected, display admin notice
function stats_notice_connect() {
	if ( stats_is_connected() )
		return;
	if ( is_stats_admin_page() )
		return;
	echo "<div class='updated' style='background-color:#f66;'><p>" . sprintf(__('<strong>WordPress.com Stats needs attention</strong>: please <a href="%1$s">connect your blog to WordPress.com</a> or disable the plugin.', 'stats'), stats_admin_path()) . "</p></div>";
}
