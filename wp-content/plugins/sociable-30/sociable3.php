<?php
/*
Plugin Name: Sociable for WordPress 3.0
Plugin URI: http://wordpress.org/extend/plugins/sociable-30
Description: Sociable people need sociable!  Sociable now for WordPress 3.0.  Add sociable bookmarks to posts,  pages and RSS feeds
Version: 5.13
Author: Tom Pokress

Copyright 2010 Tom Pokress
Copyright 2009 and earlier Peter Harkins (ph@malaprop.org), blogplay, Joost de Valk (joost@yoast.com)

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
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

class Sociable {
	var $version = '5.13';
	var $homepage = 'http://wpplugins.com/plugin/155/sociable-pro';
	var $basename;
	var $bug_link;
	var $suggest_link;
	var $pro_link;
	var $pro_button;
	var $debug = false;

	function Sociable() {
		$this->debugging();

		$this->basename = plugin_basename(__FILE__);

		load_plugin_textdomain('sociable3', false, dirname(plugin_basename( __FILE__ )) . '/i18n');

		register_activation_hook(__FILE__, array(&$this, 'activation_hook'));

		$this->bug_link = "<a href='mailto:tompokress@gmail.com?subject=Bug in $this->version' >" . __('Report a Bug', 'sociable3') . "</a>";
		$this->suggest_link = "<a href='mailto:tompokress@gmail.com?subject=Suggestions for $this->version' >" . __('Suggestions', 'sociable3') . "</a>";
		$this->pro_link = "<a href='$this->homepage'><img style='vertical-align:middle' src='" . plugins_url('/images/smallpro.png', __FILE__) . "'/></a>";
		$this->pro_button = "<a href='$this->homepage'><img style='padding-right:25px' src='" . plugins_url('/images/gopro.png', __FILE__) . "'/></a>";

		$options = get_option('sociable');
		if (isset($options['conditionals'])) {
			add_filter('the_content', array(&$this, 'content_hook'));
			add_filter('the_excerpt', array(&$this, 'excerpt_hook'));
		}

		add_action('wp_print_scripts', array(&$this, 'wp_print_scripts_hook'));
		add_action('wp_print_styles', array(&$this, 'wp_print_styles_hook'));
		add_action('wp_insert_post', array(&$this, 'wp_insert_post_hook'));
		add_action('admin_menu', array(&$this, 'admin_menu_hook'));
		add_action('admin_init', array(&$this, 'admin_init_hook'));
		add_action('wp_ajax_sociable_active_sites', array(&$this, 'ajax_active_sites') );

		add_shortcode('sociable', array(&$this, 'shortcode_hook'));

		include_once dirname( __FILE__ ) . '/services.php';
	}

	// s3_errors -> PHP errors
	// s3_info -> phpinfo + dump
	function debugging() {
		global $wpdb;

		if (isset($_GET['s3_errors'])) {
			error_reporting(E_ALL);
			ini_set('error_reporting', E_ALL);
			ini_set('display_errors','On');
			$wpdb->show_errors();
		}

		if (isset($_GET['s3_info'])) {
			$bloginfo = array('version', 'language', 'stylesheet_url', 'wpurl', 'url');
			echo "<br/><b>bloginfo</b><br/>";
			foreach ($bloginfo as $key=>$info)
				echo "$info: " . bloginfo($info) . "<br/>";
			echo "<br/><b>Sociable options</b><br/>";
			$options = get_option('sociable');
			print_r($options);
			echo "<br/><b>phpinfo</b><br/>";
			phpinfo();
		}

		if (isset($_GET['s3_debug'])) {
			$this->debug = true;
		}
	}


	/**
	 * Set the default settings on activation only if no sites are active
	 */
	function activation_hook() {
		$options = get_option('sociable');
		$old_version = get_option('sociable_version');

		// Current version, set any data that might be missing
		if ($options) {
			if (!isset($options['iconset_name']) || !$options['iconset_name'])
				$options['iconset_name'] = 'default';
			if (!isset($options['icon_size']) || !$options['icon_size'])
				$options['icon_size'] = 16;

			// 5.08 or earlier
			if (!isset($old_version)) {
				// Set default tagline position
				$options['tagline_pos'] = 'ABOVE_ICONS';
			}
		} else {
		// Pre-3.0: reset options to default and selectively upgrade some of them
			$options = $this->defaults();

			// Upgrade to new options
			// Disabled: awesmapikey, iframeheight, iframewidth, usetextlinks, usecss & disableasprite are all deprecated
			if(get_option('sociable_active_sites'))
				$options['active_sites'] = get_option('sociable_active_sites');
			if (get_option('sociable-tagline'))
				$options['tagline'] = get_option('sociable-tagline');
			if (get_option('sociable_disablealpha'))
				$options['disablealpha'] = "on";
			if (get_option('sociable_usetargetblank'))
				$options['usetargetblank'] = "on";

			if (get_option('sociable_conditionals')) {
				unset($options['conditionals']);
				$conditionals = get_option('sociable_conditionals');
				foreach((array)$conditionals as $condition => $value) {
					if ($value)
						$options['conditionals'][$condition] = "on";
				}
			}
		}

		update_option('sociable', $options);
		update_option('sociable_version', $this->version);
	}

	function ajax_active_sites() {
		// Split iconset name and size
		$iconset_name = isset($_POST['iconset_name']) ? $_POST['iconset_name'] : null;
		$active_sites = isset($_POST['active_sites']) ? $_POST['active_sites'] : null;
		$result = explode(":", $iconset_name);
		die ($this->get_active_sites($active_sites, $result[0], $result[1]));
	}

	function wp_print_scripts_hook() {
		$options = get_option('sociable');
		$tooltips = (isset($options['tooltips'])) ? $options['tooltips'] : false;
		$active_sites = isset($options['active_sites']) ? $options['active_sites'] : null;

		if ($active_sites && array_search('Add to favorites', (array)$active_sites) !== false) {
			wp_enqueue_script('sociable3-addtofavorites', plugins_url('addtofavorites.js', __FILE__), array(), $this->version);
		}

		if ($tooltips && class_exists('SociablePro'))
			wp_enqueue_script('sociable-js', plugins_url('/pro/sociable.js', __FILE__), array('jquery'), $this->version);
	}

	function wp_print_styles_hook() {
		$options = get_option('sociable');
		$custom_css = (isset($options['custom_css'])) ? $options['custom_css'] : false;

		wp_enqueue_style('sociable3', plugins_url('sociable.css', __FILE__), false, $this->version);

		if ($custom_css)
			wp_enqueue_style('sociable3-custom', plugins_url('custom.css', __FILE__), false, $this->version);
	}

	/**
	 * Displays a checkbox that allows users to disable Sociable on a
	 * per post or page basis.
	 */
	function meta_box_hook($post) {
		$sociableoff = false;
		if (get_post_meta($post->ID,'_sociableoff', true))
			$sociableoff = true;
		?>
		<input type="checkbox" id="sociableoff" name="sociableoff" <?php checked($sociableoff); ?>/> <label for="sociableoff"><?php _e('Sociable disabled?','sociable') ?></label>
		<?php
	}

	/**
	 * If the post is inserted, set the appropriate state for the sociable off setting.
	 */
	function wp_insert_post_hook($post_id) {
		if (isset($_POST['sociableoff'])) {
			if (!get_post_meta($post_id, '_sociableoff', true))
				add_post_meta($post_id, '_sociableoff', true, true);
		} else {
			if (get_post_meta($post_id, '_sociableoff', true))
				delete_post_meta($post_id, '_sociableoff');
		}
	}


	function admin_menu_hook() {
		$pages[] = add_options_page('Sociable WP3', 'Sociable WP3', 'manage_options', 'sociablewp3', array(&$this, 'options'));

		// Load scripts/styles for plugin pages only
		foreach ( (array) $pages as $page) {
			add_action('admin_print_scripts-' . $page, array(&$this, 'admin_print_scripts'));
			add_action('admin_print_styles-' . $page, array(&$this, 'admin_print_styles'));
		}

		add_meta_box('sociablewp3', 'Sociable WP3', array(&$this, 'meta_box_hook'), 'post', 'side');
		add_meta_box('sociablewp3', 'Sociable WP3', array(&$this, 'meta_box_hook'), 'page','side');
	}

	function admin_print_scripts() {
		wp_enqueue_script('sociable3-js', plugins_url('sociable-admin.js', __FILE__), array('jquery','jquery-ui-core','jquery-ui-sortable', 'jquery-ui-dialog'));
	}

	function admin_print_styles() {
		wp_enqueue_style('sociable3-css', plugins_url('sociable-admin.css', __FILE__));
	}

	function shortcode_hook($atts) {

		$options = get_option('sociable');
		if (isset($options['conditionals']))
			$conditionals = $options['conditionals'];

		extract(shortcode_atts(array(
			'tagline' => null,
			'display' => null
		), $atts));

		// For feeds, only return icons if conditional setting is on
		if (is_feed()) {
			if (isset($conditionals['is_feed'])) {
				$html = $this->get_links(null, $tagline);
				$html = strip_tags($html, "<a><img>");
				return $html . "<br/><br/>";
			} else {
				return;
			}
		}

		return $this->get_links($display, $tagline);
	}

	function excerpt_hook($content='') {
		global $post;
		return $this->content_hook($content);
	}

	function content_hook($content='') {
		global $post;

		// If this post is disabled for sociable, there's nothing to do
		if (get_post_meta($post->ID, '_sociableoff', true))
			return $content;

		// Get conditionals.  If none are set, there's nothing to do
		$options = get_option('sociable');
		if (isset($options['conditionals']))
			$conditionals = $options['conditionals'];
		else
			return $content;

		// Don't output links again if already output by shortcode
		if (stristr($content, '[sociable') !== false)
			return $content;

		// Output based on conditionals
		if ( (is_home() && isset($conditionals['is_home'])) ||
			(is_single() && isset($conditionals['is_single'])) ||
			(is_page() && isset($conditionals['is_page'])) ||
			(is_category() && isset($conditionals['is_category'])) ||
			(is_tag() && isset($conditionals['is_tag'])) ||
			(is_date() && isset($conditionals['is_date'])) ||
			(is_author() && isset($conditionals['is_author'])) ||
			(is_search() && isset($conditionals['is_search']))
		) {
			$content .= $this->get_links();
		} elseif ((is_feed() && isset($conditionals['is_feed']))) {
			$html = $this->get_links(null, null);
			$html = strip_tags($html, "<a><img>");
			$content .= $html . "<br/><br/>";
		}

		return $content;
	}

	/**
	 * Add the Sociable menu to the Settings menu
	 * @param boolean $force if set to true, force updates the settings.
	 */
	function defaults() {

		$defaults = array(
			'active_sites' => array('Print', 'Digg', 'StumbleUpon', 'del.icio.us', 'Facebook', 'YahooBuzz', 'Twitter', 'Google'),

			'conditionals' => array(
				'is_single' => 'on',
				'is_page' => 'on'
			),

			'tagline' => __("Share and Enjoy:", 'sociable'),
			'disablealpha' => 'on',
			'iconset_name' => 'default',
			'icon_size' => 16
		);

		return $defaults;
	}


	function admin_init_hook() {
		register_setting('sociable', 'sociable', array($this, 'set_options'));

		add_settings_section('sociable_settings', __('Settings', 'sociable3'), array(&$this, 'settings'), 'sociable');

		add_settings_field('iconset_name', __('Icon Set', 'sociable3'), array(&$this, 'set_iconset_name'), 'sociable', 'sociable_settings');
		add_settings_field('active_sites', __('Sites', 'sociable3'), array(&$this, 'set_active_sites'), 'sociable', 'sociable_settings');
		add_settings_field('conditionals', __('Position', 'sociable3'), array(&$this, 'set_conditionals'), 'sociable', 'sociable_settings');
		add_settings_field('tagline', __('Tag Line', 'sociable3'), array(&$this, 'set_tagline'), 'sociable', 'sociable_settings');
		add_settings_field('tagline_pos', __('Tag Line Position', 'sociable3'), array(&$this, 'set_tagline_pos'), 'sociable', 'sociable_settings');
		add_settings_field('captions', __('Captions', 'sociable3'), array(&$this, 'set_captions'), 'sociable', 'sociable_settings');
		add_settings_field('disablealpha', __('Brighter Icons', 'sociable3'), array(&$this, 'set_disablealpha'), 'sociable', 'sociable_settings');
		add_settings_field('usetargetblank', __('New Window', 'sociable3'), array(&$this, 'set_usetargetblank'), 'sociable', 'sociable_settings');

		add_settings_section('sociable_adv_settings', __('Advanced Settings', 'sociable3'), array(&$this, 'advanced_settings'), 'sociable');

		add_settings_field('tooltips', __('CSS Tooltips', 'sociable3'), array(&$this, 'set_tooltips'), 'sociable', 'sociable_adv_settings');
		add_settings_field('facebook', __('Facebook', 'sociable3'), array(&$this, 'set_facebook'), 'sociable', 'sociable_adv_settings');
		add_settings_field('bitly', __('Bit.ly', 'sociable3'), array(&$this, 'set_bitly'), 'sociable', 'sociable_adv_settings');
		add_settings_field('customCSS', __('Custom CSS', 'sociable3'), array(&$this, 'set_custom_css'), 'sociable', 'sociable_adv_settings');
	}

	function set_options($input) {
		// Process restore requests
		if (isset($_REQUEST['restore']))
			return $this->defaults();

		// Split iconset name and size into 2 fields
		if (isset($input['iconset_name'])) {
			$iconset_name = $input['iconset_name'];
			$result = explode(":", $iconset_name);
			$input['iconset_name'] = $result[0];
			$input['icon_size'] = $result[1];
		}

		// Process clear bitly cache
		if (isset($_REQUEST['clear_bitly'])) {
			$this->clear_bitly_data();
			return $input;
		}

		return $input;
	}

	function settings() {
	}

	function advanced_settings() {
	}

	function set_iconset_name() {
		echo "<select id='sociable_iconset_name' name='sociable[iconset_name]' disabled>";
		echo "<option value='' $selected>Default 16x16</option>";
		echo "</select>";
		echo sprintf(__("Upgrade to %s to get tons of icons in multiple sizes and the ability to use custom icons!", 'sociable3'), $this->pro_link);

	}

	function set_active_sites() {
		_e("Check the sites you want to appear on your site. Drag and drop sites to reorder them.", 'sociable3');
		echo "<div id='sociable_site_list_div'>" . $this->get_active_sites() . "</div>";
	}

	function set_conditionals() {
		$options = get_option('sociable');
		$conditionals = (isset($options['conditionals'])) ? $options['conditionals'] : array();

		_e('Specify which pages to display social bookmarks on:', 'sociable3');
		echo "<br/>";

		$this->option_conditional($conditionals, 'is_home', __("Front page of the blog", 'sociable3'));
		$this->option_conditional($conditionals, 'is_single', __("Individual blog posts", 'sociable3'));
		$this->option_conditional($conditionals, 'is_page', __('Individual WordPress "Pages"', 'sociable3'));
		$this->option_conditional($conditionals, 'is_category', __("Category archives", 'sociable3'));
		$this->option_conditional($conditionals, 'is_tag', __("Tag listings", 'sociable3'));
		$this->option_conditional($conditionals, 'is_date', __("Date-based archives", 'sociable3'));
		$this->option_conditional($conditionals, 'is_author', __("Author archives", 'sociable3'));
		$this->option_conditional($conditionals, 'is_search', __("Search results", 'sociable3'));
		$this->option_conditional($conditionals, 'is_feed', __("RSS feed items", 'sociable3'));
	}

	function option_conditional($conditionals, $field, $label) {
		$checked = (isset($conditionals[$field])) ? checked($conditionals[$field], "on", false) : "";
		echo "<input type='checkbox' name='sociable[conditionals][$field]' $checked /> $label <br/>";
	}

	function set_tagline() {
		$options = get_option('sociable');
		$tagline = (isset($options['tagline'])) ? $options['tagline'] : "";

		_e("Change the text displayed in front of the icons below.", 'sociable3');
		echo '<br/><input size="80" type="text" name="sociable[tagline]" value="' . esc_attr(stripslashes($tagline)) . '" />';
	}

	function set_tagline_pos() {
		$options = get_option('sociable');
		$tagline_pos = (isset($options['tagline_pos'])) ? $options['tagline_pos'] : "";
		$tagline_pos_values = array('ABOVE_ICONS' => 'Above', 'LEFT_ICONS' => 'Left');

		_e('Tagline position relative to the icons ', 'sociable3');
		echo "<select name='sociable[tagline_pos]' >";
		foreach ((array)$tagline_pos_values as $key => $value) {
			$selected = ($tagline_pos == $key) ? "selected='selected'" : "";
			echo "<option value='$key' $selected>$value</option>";
		}
		echo "</select>";
	}

	function set_tooltips() {
		echo sprintf(__("Upgrade to %s to get cool CSS tooltips over your icons!", 'sociable3'), $this->pro_link);
	}

	function set_disablealpha() {
		$options = get_option('sociable');
		$checked = (isset($options['disablealpha'])) ? 'checked="checked"' : "";

		echo "<input type='checkbox' name='sociable[disablealpha]' $checked />";
		_e("If checked icons will be always 'on', otherwise they will be dimmed until the mouse moves over them.", 'sociable3');
	}

	function set_usetargetblank() {
		$options = get_option('sociable');
		$checked = (isset($options['usetargetblank'])) ? 'checked="checked"' : "";

		echo "<input type='checkbox' name='sociable[usetargetblank]' $checked />";
		_e("Use <code>target=_blank</code> on links? (Forces links to open a new window)", "sociable3");
	}

	function set_facebook() {
		$fb_link ="<a href='http://developers.facebook.com/docs/guides/web'>" . __("Facebook 'like' button", 'sociable3') . "</a>";
		echo sprintf(__("Upgrade to %s and add a great-looking %s to your blog", 'sociable3'), $this->pro_link, $fb_link);
	}

	function set_bitly() {
		$bitly_link = "<a href='http://www.bitly.com'>bit.ly</a>";
		echo sprintf(__("Upgrade to %s and use %s to shorten your Twitter links", 'sociable3'), $this->pro_link, $bitly_link);
	}

	function set_custom_css() {
		$options = get_option('sociable');
		$checked = (isset($options['custom_css'])) ? 'checked="checked"' : "";

		echo "<input type='checkbox' name='sociable[custom_css]' $checked />";
		echo sprintf(__("Check to include your own CSS file.  You must create a file named %s in the Sociable plugin directory", 'sociable'), "<code>custom.css</code>");
	}


	function set_captions() {
		$options = get_option('sociable');
		$checked = (isset($options['captions'])) ? 'checked="checked"' : "";

		echo "<input type='checkbox' name='sociable[captions]' $checked />";
		_e("Show a text caption next to each icon", "sociable3");
	}

	function options() {
	?>
		<div class="wrap">
			<?php screen_icon(); ?>
			<h2>
				<?php _e("Sociable for WordPress 3.0", 'sociable3'); ?>
				<span style='float:right;font-size: 12px'><?php echo __(' Version: ', 'sociable3') . $this->version; ?>
				<?php echo " | " . $this->bug_link . " | " . $this->suggest_link ?>
				</span>
			</h2>
			<?php $this->options_header(); ?>
			<form id="sociable_admin_form" action="options.php" method="post">
				<?php settings_fields('sociable'); ?>
				<?php do_settings_sections('sociable'); ?>
				<br/>
				<span class="submit"><input type="submit" name="save" value="<?php _e("Save Changes", 'sociable3'); ?>" /></span>
				<span class="submit"><input name="restore" value="<?php _e("Restore Built-in Defaults", 'sociable3'); ?>" type="submit"/></span>
			</form>
		</div>
	<?php
	}

	function options_header() {
	?>
			<h3><?php _e('Donate', 'sociable3')?></h3>
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
				<input type="hidden" name="cmd" value="_s-xclick" />
				<input type="hidden" name="hosted_button_id" value="H3YD2QYUJH8TY" />
				<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!" />
				<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
				<?php echo __("Please support a starving programmer (me) with a small donation!", 'sociable3') ?>
			</form>
			<hr/>
			<h3><?php _e('Go Pro!', 'sociable3')?></h3>
			<table>
				<tr>
					<th align="left">
						<?php echo $this->pro_button ?>
						<ul>
							<li><img src='<?php echo plugins_url('/images/check.png', __FILE__)?>' /> 10 Sets of new icons</li>
							<li><img src='<?php echo plugins_url('/images/check.png', __FILE__)?>' /> Use your own icons</li>
							<li><img src='<?php echo plugins_url('/images/check.png', __FILE__)?>' /> Themeable CSS tooltips</li>
							<li><img src='<?php echo plugins_url('/images/check.png', __FILE__)?>' /> Bit.ly short URLs for Twitter</li>
							<li><img src='<?php echo plugins_url('/images/check.png', __FILE__)?>' /> Facebook 'like' buttons</li>
						</ul>
					</th>
					<td>
					<img src='<?php echo plugins_url('/images/banner.png', __FILE__)?>' />
					</td>
				</tr>
			</table>
			<hr/>

	<?php
	}

	function get_active_sites($active_sites=null, $iconset_name=null, $icon_size=null) {
		$options = get_option('sociable');

		$iconurl = $this->get_icon_url($iconset_name, $icon_size);

		if (!$active_sites)
			$active_sites = (isset($options['active_sites'])) ? (array)$options['active_sites'] : null;

		if (!$icon_size)
			$icon_size = (isset($options['icon_size'])) ? $options['icon_size'] : 16;

		// Apply filter for other plugins
		$this->sites = apply_filters('sociable_known_sites',  $this->sites);
		$sites = $this->sites;
		uksort($sites, "strnatcasecmp");

		$active = array();
		$inactive = $sites;
		foreach( (array)$active_sites as $sitename) {
			if (isset($sites[$sitename]))
				$active[$sitename] = $sites[$sitename];
			unset($inactive[$sitename]);
		}

		$sites = array_merge($active, $inactive);

		$html = __("<b>Icons location</b>: $iconurl<br/>", 'sociable3');
		$html .= "<ul id='sociable_site_list'>";

		foreach ($sites as $sitename => $site) {

			if (!$this->check_icon_exists($site['favicon'], $iconset_name, $icon_size))
				continue;

			if (array_search($sitename, (array)$active_sites) !== false) {
				$class = "sociable_site active";
				$checked = "checked='checked'";
			} else {
				$class = "sociable_site inactive";
				$checked = "";
			}

			$style = "height:" . $icon_size . "px";
			$html .= "<li id='$sitename' class='$class' style='$style' >";
			$html .= "<input type='checkbox' id='cb_$sitename' name='sociable[active_sites][]' value='$sitename' $checked />";
			$html .= $this->show_site($sitename, $iconurl);
			$html .= $sitename;
			$html .= "</li>";
		}
		$html .= "</ul>";
		return $html;
	}

	/**
	 * Returns the Sociable links list.
	 *
	 * @param array $display optional array of links to return in HTML
	 * @param string tagline optional tagline to override the default
	 */
	function get_links($display = null, $tagline = null) {
		global $post;

		// Get options
		$options = get_option('sociable');
		$tagline = isset($options['tagline']) ? $options['tagline'] : "";
		$tagline_pos = isset($options['tagline_pos']) ? $options['tagline_pos'] : "";
		$bitly_login = isset($options['bitly_login']) ? $options['bitly_login'] : null;
		$bitly_apikey = isset($options['bitly_apikey']) ? $options['bitly_apikey'] : null;
		$active_sites = isset($options['active_sites']) ? $options['active_sites'] : null;

		// Get url to icon files
		$iconurl = $this->get_icon_url();

		// Get post/blog info
		$blogname = urlencode(get_bloginfo('name')." ". get_bloginfo('description'));
		$blogrss = get_bloginfo('rss2_url');
		$rss = urlencode(get_bloginfo('ref_url'));
		$permalink = urlencode(get_permalink($post->ID));
		$title = str_replace('+', '%20', urlencode($post->post_title));

		if ($bitly_login && $bitly_apikey && method_exists($this, 'get_bitly_permalink'))
			$short_link = $this->get_bitly_permalink($post->ID, $bitly_login, $bitly_apikey);
		else
			$short_link = $permalink;

		// Grab the excerpt
		$excerpt = urlencode(strip_tags(strip_shortcodes($post->post_excerpt)));
		if ($excerpt == "")
			$excerpt = urlencode(substr(strip_tags(strip_shortcodes($post->post_content)), 0, 250));

		// Clean the excerpt for use with links
		$excerpt = str_replace('+', '%20', $excerpt);

		// If user didn't provide a list of sites to display, then display all active sites
		if (!$display) {
			if ($active_sites)
				$display = $active_sites;
			else
				return "";
		}

		// Apply filter for other plugins to add sites
		$this->sites = apply_filters('sociable_known_sites',  $this->sites);

		// Start preparing the output
		$html = "<div class='sociable'>";

		if (method_exists($this, 'get_facebook_iframe'))
			$html .= $this->get_facebook_iframe($permalink);

		// Show tagline above links
		if ($tagline_pos == 'LEFT_ICONS' && $tagline) {
			$html .= "<span class='sociable-tagline'>" . stripslashes($tagline) . "</span>";
		} else {
			$html .= "<div><span class='sociable-tagline'>" . stripslashes($tagline) . "</span></div>";
		}

		/**
		 * Start the list of links
		 */
		$html .= "<ul>";

		$i = 0;
		$totalsites = count($display);

		foreach( (array)$display as $sitename ) {

			// Check that site exists
			if (!isset($this->sites[$sitename]))
				continue;

			// Replace arguments in site url
			$url = $this->sites[$sitename]['url'];
			$url = str_replace('TITLE', $title, $url);
			$url = str_replace('RSS', $rss, $url);
			$url = str_replace('BLOGNAME', $blogname, $url);
			$url = str_replace('EXCERPT', $excerpt, $url);
			$url = str_replace('FEEDLINK', $blogrss, $url);
			$url = str_replace('PERMALINK', $permalink, $url);
			$url = str_replace('SHORT_LINK', $short_link, $url);

			// Add the list item to the output HTML, but allow other plugins to process the content first.
			$url = apply_filters('sociable_url', $url);

			// Get html for site
			$link = $this->show_site($sitename, $iconurl, $url, $options);
			$html .= "<li>$link</li>";
		}

		$html .= "</ul>";

		$html .= "</div>";
		return $html;
	}


	function show_site($sitename, $iconurl, $url=null, $options=null) {
		$usetargetblank = isset($options['usetargetblank']) ? $options['usetargetblank'] : false;
		$disablealpha = isset($options['disablealpha']) ? $options['disablealpha'] : false;
		$captions = isset($options['captions']) ? $options['captions'] : false;
		$tooltips = isset($options['tooltips']) ? $options['tooltips'] : false;

		// Special settings for feeds
		if (is_feed()) {
			$disablealpha = false;
			$tooltips = false;
		}

		// If site isn't in sites array there's nothing to do
		if (!isset($this->sites[$sitename]))
			return "";

		$site = $this->sites[$sitename];
		$description = (isset($site['description']) && $site['description'] != "") ? $site['description'] : $sitename;
		$img_src = $iconurl . $site['favicon'];
		$img_class = ($disablealpha) ? "sociable-img" : "sociable-img sociable-hovers";
		$img_title = (!$tooltips) ? $description : "";
		$link_text = ($captions) ? $description : "";
		$link_target = ($usetargetblank) ? "target=\"_blank\"" : "";

		// Special handling for add to favorites link
		if ($sitename == 'Add to favorites') {
			$link_title = "title=\"$description\"";
			$onclick = 'onclick="AddToFavorites(); return false;"';
		} else {
			$link_title = "";
			$onclick = "";
		}

		// For admin screen, just return the icon
		if (is_admin())
			return "<img src='$img_src' alt='' />";

		// Double quotes MUST be used throughout all links because of a bug in Google Analytics
		// Developer seems confused about how to fix it: http://wordpress.org/extend/plugins/google-analytics-for-wordpress/changelog/
		$html = "<a rel=\"nofollow\" $link_target $link_title href=\"$url\" $onclick>"
			. "<img src=\"$img_src\" class=\"$img_class\" title=\"$img_title\" alt=\"$img_title\" />"
			. $link_text
			. "</a>";

		// For non-admin return icon, link and tooltip
		if ($this->debug) {
			echo "\r\n<!-- sociable3 debug:";
			echo "target=\"$target\"";
			echo "title=\"$link_title\"";
			echo "url=\"$link\"";
			echo "Full html string=\"$html\"";
			echo "!-->\r\n";
		}

		if ($tooltips)
			$html .= "<div class='fg-tooltip'>$description"
				. "<div class='fg-tooltip-pointer-down'>"
				. "<div class='fg-tooltip-pointer-down-inner'>"
				. "</div></div></div>";

		return $html;
	}

	function get_icon_url($iconset_name=null, $icon_size=null) {
		$options = get_option('sociable');

		$path = $this->get_icon_path($iconset_name, $icon_size);
		return plugins_url($path, __FILE__);
	}

	function get_icon_path($iconset_name=null, $icon_size=null) {
		return '/images/default/16/';
	}

	function check_icon_exists($filename, $iconset_name=null, $icon_size=null) {
		$path = $this->get_icon_path($iconset_name, $icon_size);
		if (!$path)
			return null;

		$path = dirname(__FILE__) . $path . $filename;
		$size = @getimagesize($path);
		if (!isset($size) || $size[0] == 0)
			return null;
		else
			return $size[0];
	}
} // End class Sociable

@include_once dirname( __FILE__ ) . '/pro/pro.php';
if (class_exists('SociablePro'))
	$sociable = new SociablePro();
else
	$sociable = new Sociable();
?>