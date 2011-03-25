<?php

add_action( 'wp_dashboard_setup', 'stats_register_dashboard_widget' );
add_filter( 'wp_dashboard_widgets', 'stats_add_dashboard_widget' );


// Boooooooooooring init stuff
add_action( 'admin_menu', 'stats_admin_menu' );
add_action( 'init', 'stats_load_translations' );

// Plant the tracking code in the footer
add_action( 'wp_footer', 'stats_footer', 101 );

// Tell HQ about changed settings
add_action( 'update_option_home', 'stats_update_bloginfo' );
add_action( 'update_option_siteurl', 'stats_update_bloginfo' );
add_action( 'update_option_blogname', 'stats_update_bloginfo' );
add_action( 'update_option_blogdescription', 'stats_update_bloginfo' );
add_action( 'update_option_timezone_string', 'stats_update_bloginfo' );
add_action( 'add_option_timezone_string', 'stats_update_bloginfo' );
add_action( 'update_option_gmt_offset', 'stats_update_bloginfo' );

// Tell HQ about changed posts
add_action( 'save_post', 'stats_update_post', 10, 1 );

// Tell HQ to drop all post info for this blog
add_action( 'update_option_permalink_structure', 'stats_flush_posts' );

// Teach the XMLRPC server how to dance properly
add_filter( 'xmlrpc_methods', 'stats_xmlrpc_methods' );

