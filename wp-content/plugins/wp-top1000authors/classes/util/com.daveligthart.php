<?php
/**
 * Dashboard feed widget.
 * @author dligthart
 * @version 0.1
 * @package com.daveligthart
 */
if (!class_exists('DLPosts')) {
	class DLPosts {

		/**
		 * DLPosts.
		 * @access public
		 */
		function DLPosts() {
			if (isset($_GET['show_dl_widget'])) {
				if ($_GET['show_dl_widget'] == "true") {
					update_option( 'show_dl_widget', 'noshow' );
				} else {
					update_option( 'show_dl_widget', 'show' );
				}
			}
			add_action( 'wp_dashboard_setup', array(&$this, 'register_widget') );
			add_filter( 'wp_dashboard_widgets', array(&$this, 'add_widget') );
		}

		/**
		 * @access private
		 */
		function register_widget() {
			wp_register_sidebar_widget('dl_posts', 'DaveLigthart.com - Freelance Webdeveloper',
				array(&$this, 'widget'),
				array(
				'all_link' => 'http://daveligthart.com/',
				'feed_link' => 'http://daveligthart.com/feed/',
				'edit_link' => 'options.php' )
			);
		}

		/**
		 * @access private
		 */
		function add_widget( $widgets ) {
			global $wp_registered_widgets;
			if ( !isset($wp_registered_widgets['dl_posts']) ) return $widgets;
			array_splice( $widgets, 2, 0, 'dl_posts' );
			return $widgets;
		}

		/**
		 * @access private
		 */
		function widget($args = array()) {
			$show = get_option('show_dl_widget');
			if ($show != 'noshow') {
				if (is_array($args))
					extract( $args, EXTR_SKIP );

				echo $before_widget.$before_title.$widget_name.$after_title;

				echo '<a href="http://daveligthart.com/"><img style="margin: 0 0 5px 5px;" src="http://daveligthart.com/images/feed_logo.png" align="right" alt="daveligthart.com"/></a>';

				// Include WordPress native Rss functions.
				include_once(ABSPATH . WPINC . '/rss.php');

				$rss = fetch_rss('http://feeds.feedburner.com/daveligthart');

				if(null != $rss->items):

					$items = array_slice($rss->items, 0, 2);

					if (empty($items)) echo '<li>No items</li>';
						else foreach ( $items as $item ) :
				?>
				<a style="font-size: 14px; font-weight:bold;" href='<?php echo $item['link']; ?>' title='<?php echo $item['title']; ?>'><?php echo $item['title']; ?></a><br/>
				<p style="font-size: 10px; color: #aaa;"><?php echo date('j F Y',strtotime($item['pubdate'])); ?></p>
				<p><?php echo $item['summary']; ?></p>
				<?php
					endforeach;
				else:
				?>
<a style="font-size: 14px; font-weight:bold;" href='http://daveligthart.com' title='daveligthart.com'>Hire me!</a>
<p style="font-size: 10px; color: #aaa;">IÕm available for all kinds of commissions and freelance work. As a consultant, programmer (software architect), webdeveloper and designer.
I have indepth knowledge of Flash, streaming media (web video), video advertising solutions<br/> and content management systems.</p>
				<?php
				endif;

				echo $after_widget;
			}
		}
	}

	// Start this plugin once all other plugins are fully loaded
	add_action( 'plugins_loaded', create_function( '', 'global $dlPosts; $dlPosts = new DLPosts();' ) );
}
?>