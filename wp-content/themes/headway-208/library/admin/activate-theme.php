<h2>Activating Theme...</h2>

<?php
if(delete_option('current_theme') && update_option('template', $_GET['activate-theme']) && update_option('stylesheet', $_GET['activate-theme']) && delete_option('_site_transient_theme_roots')){
	echo '<div class="success"><span>Theme Activated!</span> <p>The latest version of Headway has been successfully activated.</p></div>';
} else {
	echo '<p class="notice"><strong>Error!</strong> Unable to activate theme.</p>';
}

$tools_page_url = get_bloginfo('wpurl').'/wp-admin/admin.php?page=headway-tools';
?>

<p class="clear">You are now being redirected.  If you are not redirected within 5 seconds, click <a href="<?php echo $tools_page_url; ?>"><strong>here</strong></a>.</p>

<meta http-equiv="refresh" content="3;URL=<?php echo $tools_page_url; ?>">