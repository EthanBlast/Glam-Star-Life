<?php

function stats_admin_parent() {
	return 'options-general.php';

	if ( function_exists('is_multisite') && is_multisite() ) {
		$menus = get_site_option( 'menu_items' );
		if ( isset($menus['plugins']) && $menus['plugins'] )
			return 'plugins.php';
		else
			return 'options-general.php';
	} else {
		return 'plugins.php';
	}
}

function stats_admin_path() {
	$parent = stats_admin_parent();
	return "$parent?page=wpstats";
}

// TODO
function is_stats_admin_page() {
	return false;
}

function stats_admin_menu() {
	global $current_user;
	$roles = stats_get_option('roles');
	$cap = 'administrator';
	foreach ( $roles as $role ) {
		if ( current_user_can($role) ) {
			$cap = $role;
			break;
		}
	}
	if ( stats_get_option('blog_id') ) {
		$hook = add_submenu_page('index.php', __('Site Stats', 'stats'), __('Site Stats', 'stats'), $role, 'stats', 'stats_reports_page');
		add_action("load-$hook", 'stats_reports_load');
	}
	$parent = stats_admin_parent();
	$hook = add_submenu_page($parent, __('WordPress.com Stats Plugin', 'stats'), __('WordPress.com Stats', 'stats'), 'manage_options', 'wpstats', 'stats_admin_page');
	add_action("load-$hook", 'stats_admin_load');
	add_action("admin_head-$hook", 'stats_admin_head');
	add_action('admin_notices', 'stats_admin_notices');
}

function stats_admin_load() {
	if ( ! empty( $_POST['action'] ) && $_POST['_wpnonce'] == wp_create_nonce('stats') ) {
		switch( $_POST['action'] ) {
			case 'reset' :
				stats_set_options(array());
				wp_redirect( stats_admin_path() );
				exit;

			case 'enter_key' :
				stats_check_key( $_POST['api_key'] );
				wp_redirect( stats_admin_path() );
				exit;

			case 'add_or_replace' :
				$key_check = stats_get_option('key_check');
				stats_set_api_key($key_check[0]);
				if ( isset($_POST['add']) ) {
					stats_get_blog_id($key_check[0]);
				} else {
					extract( parse_url( get_option( 'home' ) ) );
					$path = rtrim( $path, '/' );
					if ( empty( $path ) )
						$path = '/';
					$options = stats_get_options();
					if ( isset($_POST['recover']) )
						$options['blog_id'] = intval($_POST['recoverblog']);
					else
						$options['blog_id'] = intval($_POST['blog_id']);
					$options['api_key'] = $key_check[0];
					$options['host'] = $host;
					$options['path'] = $path;
					stats_set_options($options);
					stats_update_bloginfo();
				}
				if ( stats_get_option('blog_id') )
					stats_set_option('key_check', false);
				wp_redirect( stats_admin_path() );
				exit;

			case 'save_options' :
				$options = stats_get_options();
				$options['wp_me'] = isset($_POST['wp_me']) && $_POST['wp_me'];
				$options['reg_users'] = isset($_POST['reg_users']) && $_POST['reg_users'];

				$options['roles'] = array('administrator');
				foreach ( get_editable_roles() as $role => $details )
					if ( isset($_POST["role_$role"]) && $_POST["role_$role"] )
						$options['roles'][] = $role;

				stats_set_options($options);
				wp_redirect( stats_admin_path() );
				exit;
		}
	}

	$options = stats_get_options();
	if ( empty( $options['blog_id']) && empty( $options['key_check'] ) && stats_get_api_key() )
		stats_check_key( stats_get_api_key() );
}

function stats_admin_head() {
	?>
	<style type="text/css">
		#statserror {
			border: 1px solid #766;
			background-color: #d22;
			padding: 1em 3em;
		}
	</style>
	<?php
}

function stats_admin_page() {
	$options = stats_get_options();
	?>
	<div class="wrap">
		<h2><?php _e('WordPress.com Stats', 'stats'); ?></h2>
		<div class="narrow">
<?php if ( !empty($options['error']) ) : ?>
			<div id='statserror'>
				<h3><?php _e('Error from last API Key attempt:', 'stats'); ?></h3>
				<p><?php echo $options['error']; ?></p>
			</div>
<?php $options['error'] = false; stats_set_options($options); endif; ?>

<?php if ( empty($options['blog_id']) && !empty($options['key_check']) ) : ?>
			<p><?php printf(__('The API key "%1$s" belongs to the WordPress.com account "%2$s". If you want to use a different account, please <a href="%3$s">enter the correct API key</a>.', 'stats'), $options['key_check'][0], $options['key_check'][1], wp_nonce_url('?page=wpstats&action=reset', 'stats')); ?></p>
			<p><?php _e('Note: the API key you use determines who will be registered as the "owner" of this blog in the WordPress.com database. Please choose your key accordingly. Do not use a temporary key.', 'stats'); ?></p>

<?php	if ( !empty($options['key_check'][2]) ) : ?>
			<form method="post">
			<?php wp_nonce_field('stats'); ?>
			<input type="hidden" name="action" value="add_or_replace" />
<?php
		$domainpath = preg_replace('|.*://|', '', get_bloginfo('siteurl'));
		foreach ( $options['key_check'][2] as $blog ) {
			if ( trailingslashit("{$blog[domain]}{$blog[path]}") == trailingslashit($domainpath) )
				break;
			else
				unset($blog);
		}
?>

			<h3><?php _e('Recommended Action', 'stats'); ?></h3>
<?php		if ( isset($blog) ) : ?>
			<input type='hidden' name='recoverblog' value='<?php echo $blog['userblog_id']; ?>' />
			<p><?php _e('It looks like you have installed Stats on a blog with this URL before. You can recover the stats history from that blog here.', 'stats'); ?></p>
			<p><input type="submit" name="recover" value="<?php echo js_escape(__('Recover stats', 'stats')); ?>" /></p>
<?php		else : ?>
			<p><?php _e('It looks like this blog has never had stats before. There is no record of its URL in the WordPress.com database.', 'stats'); ?></p>
			<p><input type="submit" name="add" value="<?php echo js_escape(__('Add this blog to my WordPress.com account', 'stats')); ?>" /></p>
<?php		endif; ?>

			<h3><?php _e('Recover other stats', 'stats'); ?></h3>
			<p><?php _e("Have you relocated this blog from a different URL? You may opt to have this blog take over the stats history from any other self-hosted blog associated with your WordPress.com account. This is appropriate if this blog had a different URL in the past. The WordPress.com database will rename its records to match this blog's URL.", 'stats'); ?></p>
			<p>
			<select name="blog_id">
				<option selected="selected" value="0"><?php _e('Select a blog', 'stats'); ?></option>
<?php		foreach ( $options['key_check'][2] as $blog ) : ?>
				<option value="<?php echo $blog['userblog_id']; ?>"><?php echo $blog['domain'] . $blog['path']; ?></option>
<?php		endforeach; ?>
			</select>
			<input type="submit" name="replace" value="<?php echo js_escape(__('Take over stats history', 'stats')); ?>" />
			</p>
			</form>

<?php	else : ?>
			<form method="post">
			<?php wp_nonce_field('stats'); ?>
			<input type="hidden" name="action" value="add_or_replace" />
			<h3><?php _e('Add blog to WordPress.com account', 'stats'); ?></h3>
			<p><?php _e("This blog will be added to your WordPress.com account. You will be able to allow other WordPress.com users to see your stats if you like.", 'stats'); ?></p>
			<p><input type="submit" name="add" value="<?php echo esc_attr(__('Add blog to WordPress.com', 'stats')); ?>" /></p>
			</form>
<?php	endif; ?>

<?php elseif ( empty( $options['blog_id'] ) ) : ?>
			<p><?php _e('The WordPress.com Stats Plugin is not working because it needs to be linked to a WordPress.com account.', 'stats'); ?></p>

			<form action="<?php echo stats_admin_path() ?>" method="post">
				<?php wp_nonce_field('stats'); ?>
				<p><?php _e('Enter your WordPress.com API key to link this blog to your WordPress.com account. Be sure to use your own API key! Using any other key will lock you out of your stats. (<a href="http://wordpress.com/profile/">Get your key here.</a>)', 'stats'); ?></p>
				<label for="api_key"><?php _e('API Key:', 'stats'); ?> <input type="text" name="api_key" id="api_key" value="" /></label>
				<input type="hidden" name="action" value="enter_key" />
				<p class="submit"><input type="submit" value="<?php _e('Save &raquo;', 'stats'); ?>" /></p>
			</form>
<?php else : ?>
			<p><?php printf(__('Visit <a href="%s">your Dashboard</a> to see your site stats.', 'stats'), 'index.php?page=stats'); ?></p>
			<p><?php printf(__('You can also see your stats, plus grant access for others to see them, on <a href="https://dashboard.wordpress.com/wp-admin/index.php?page=stats&blog=%s">your WordPress.com dashboard</a>.', 'stats'), $options['blog_id']); ?></p>
			<h3><?php _e('Options', 'stats'); ?></h3>
			<form action="<?php echo stats_admin_path() ?>" method="post">
			<input type='hidden' name='action' value='save_options' />
			<?php wp_nonce_field('stats'); ?>
			<table id="menu" class="form-table">
			<tr valign="top"><th scope="row"><label for="wp_me"><?php _e( 'Registered users' , 'stats'); ?></label></th>
			<td><label><input type='checkbox'<?php checked($options['reg_users']); ?> name='reg_users' id='reg_users' /> <?php _e("Count the page views of registered users who are logged in.", 'stats'); ?></label></td>
			<tr valign="top"><th scope="row"><label for="wp_me"><?php _e( 'Shortlinks' , 'stats'); ?></label></th>
			<td><label><input type='checkbox'<?php checked($options['wp_me']); ?> name='wp_me' id='wp_me' /> <?php _e("Publish WP.me <a href='http://wp.me/sf2B5-shorten'>shortlinks</a> as metadata. This is a free service from WordPress.com.", 'stats'); ?></label></td>
			</tr>
			<tr valign="top"><th scope="row"><?php _e( 'Report visibility' , 'stats'); ?></th>
			<td>
				<?php _e('Select the roles that will be able to view stats reports.', 'stats'); ?><br/>
<?php	$stats_roles = stats_get_option('roles');
	foreach ( get_editable_roles() as $role => $details ) : ?>
				<label><input type='checkbox' <?php if ( $role == 'administrator' ) echo "disabled='disabled' "; ?>name='role_<?php echo $role; ?>'<?php checked($role == 'administrator' || in_array($role, $stats_roles)); ?> /> <?php echo translate_user_role($details['name']); ?></label><br/>
<?php	endforeach; ?>
			</tr>
			</table>
			<p class="submit"><input type='submit' class='button-primary' value='<?php echo esc_attr(__('Save options', 'stats')); ?>' /></p>
			</form>
<?php endif; ?>

		</div>
	</div>

	<?php
	stats_set_options( $options );
}

