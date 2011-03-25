<?php
function feed_leaf_inner($leaf){
	if(isset($leaf['new'])){
		$leaf['options']['mode'] = 'recent';
		$leaf['options']['categories-mode'] = 'include';
		$leaf['options']['post-limit'] = '3';
		$leaf['options']['post-date'] = 'on';
		$leaf['options']['feed-post-date'] = 'on';
		$leaf['options']['item-limit'] = '3';
		
		
		$leaf['config']['show-title'] = 'on';
	}
		
		if($categories_select): //Fixes select for multiple featured boxes.  Without this it will compound the categories.
			$categories_select = '';
			$categories = '';
			$select_selected = array();
		endif;

			$categories = $leaf['options']['categories'];
			$categories_select_query = get_categories();
			foreach($categories_select_query as $category){ 
				if(is_array($categories)){
					if(in_array($category->term_id, $categories)) $select_selected[$category->term_id] = ' selected';
				}

				$categories_select .= '<option value="'.$category->term_id.'"'.$select_selected[$category->term_id].'>'.$category->name.'</option>';

			}

	if($leaf['options']['mode'] != 'feed'){
		$display['feed-options'] = 'display: none;';
	} else {
		$display['recent-posts-options'] = 'display:none;';
	}
?>

	<ul class="clearfix tabs">
        <li><a href="#options-tab-<?php echo $leaf['id'] ?>">Options</a></li>
        <li><a href="#miscellaneous-tab-<?php echo $leaf['id'] ?>">Miscellaneous</a></li>
    </ul>

	<div id="options-tab-<?php echo $leaf['id'] ?>">
		<table class="tab-options" id="leaf-options-<?php echo $leaf['id'] ?>-options">
			<tr>
				<th scope="row"><label>Mode</label></th>
				<td>
						<script type="text/javascript">
							var recent_posts_options_<?php echo $leaf['id'] ?> = ".<?php echo $leaf['id'] ?>_recent_posts_options";
							var feed_options_<?php echo $leaf['id'] ?> = ".<?php echo $leaf['id'] ?>_feed_options";
						</script>
						<p class="radio-container">
							<input type="radio" name="leaf-options[<?php echo $leaf['id'] ?>][mode]" id="<?php echo $leaf['id'] ?>_mode_recent" class="radio headway-visual-editor-input" value="recent" onclick="jQuery(recent_posts_options_<?php echo $leaf['id'] ?>).show();jQuery(feed_options_<?php echo $leaf['id'] ?>).hide();"<?php echo headway_radio_value($leaf['options']['mode'], 'recent') ?> /><label for="<?php echo $leaf['id'] ?>_mode_recent" class="no-clear">Recent Posts</label>
						</p>

						<p class="radio-container">
							<input type="radio" name="leaf-options[<?php echo $leaf['id'] ?>][mode]" id="<?php echo $leaf['id'] ?>_mode_feed" class="radio headway-visual-editor-input" value="feed" onclick="jQuery(recent_posts_options_<?php echo $leaf['id'] ?>).hide();jQuery(feed_options_<?php echo $leaf['id'] ?>).show();"<?php echo headway_radio_value($leaf['options']['mode'], 'feed') ?> /><label for="<?php echo $leaf['id'] ?>_mode_feed" class="no-clear">RSS Feed</label>
						</p>
				</td>
			</tr>
		
			<tr style="<?php echo $display['recent-posts-options'] ?>" class="<?php echo $leaf['id'] ?>_recent_posts_options">
				<td colspan="2">
					<p class="info-box info-box-with-bg">The categories select box has two modes. You can set it to include specific categories or you can exclude specific categories. Leave it blank to include all categories.</p>
				</td>
			</tr>
		
		
		
			<tr style="<?php echo $display['recent-posts-options'] ?>" class="<?php echo $leaf['id'] ?>_recent_posts_options">
				<th scope="row"><label>Categories Mode</label></th>
				<td>
					<p class="radio-container"><input type="radio" name="leaf-options[<?php echo $leaf['id'] ?>][categories-mode]" id="<?php echo $leaf['id'] ?>_mode_include" class="radio headway-visual-editor-input" value="include"<?php echo headway_radio_value($leaf['options']['categories-mode'], 'include') ?> /><label for="<?php echo $leaf['id'] ?>_mode_include" class="no-clear">Include</label></p>
				
					<p class="radio-container"><input type="radio" name="leaf-options[<?php echo $leaf['id'] ?>][categories-mode]" id="<?php echo $leaf['id'] ?>_mode_exclude" class="radio headway-visual-editor-input" value="exclude"<?php echo headway_radio_value($leaf['options']['categories-mode'], 'exclude') ?> /><label for="<?php echo $leaf['id'] ?>_mode_exclude" class="no-clear">Exclude</label></p>
				</td>
			</tr>
		
		
		
		
			<tr style="<?php echo $display['recent-posts-options'] ?>" class="<?php echo $leaf['id'] ?>_recent_posts_options">
				<th scope="row"><label for="<?php echo $leaf['id'] ?>_categories">Categories</label></th>
				<td>
					<select class="headway-visual-editor-input" name="leaf-options[<?php echo $leaf['id'] ?>][categories][]" id="<?php echo $leaf['id'] ?>_categories" multiple size="5">
						<?php echo $categories_select; ?>
					</select>
				</td>
			</tr>
		
		
			<tr style="<?php echo $display['recent-posts-options'] ?>" class="<?php echo $leaf['id'] ?>_recent_posts_options">
				<th scope="row"><label for="<?php echo $leaf['id'] ?>_post_limit">Post Limit</label></th>
				<td>
					<input type="text" class="headway-visual-editor-input" name="leaf-options[<?php echo $leaf['id'] ?>][post-limit]" id="<?php echo $leaf['id'] ?>_post_limit" value="<?php echo $leaf['options']['post-limit'] ?>" />
				</td>
			</tr>
		
		
			<tr style="<?php echo $display['recent-posts-options'] ?>" class="<?php echo $leaf['id'] ?>_recent_posts_options no-border">
				<th scope="row"><label for="<?php echo $leaf['id'] ?>_post_date">Post Date</label></th>	
				<td><p class="radio-container"><input type="checkbox" class="radio headway-visual-editor-input" id="<?php echo $leaf['id'] ?>_post_date" name="leaf-options[<?php echo $leaf['id'] ?>][post-date]"<?php echo headway_checkbox_value($leaf['options']['post-date']) ?> /><label for="<?php echo $leaf['id'] ?>_post_date">Show Post Date</label></p></td>	
			</tr>
		








			<tr style="<?php echo $display['feed-options'] ?>" class="<?php echo $leaf['id'] ?>_feed_options">
				<th scope="row"><label for="<?php echo $leaf['id'] ?>_feed_location">RSS Feed Location/URL</label></th>
				<td>
					<input type="text" class="headway-visual-editor-input" name="leaf-options[<?php echo $leaf['id'] ?>][feed-location]" id="<?php echo $leaf['id'] ?>_feed_location" value="<?php echo $leaf['options']['feed-location'] ?>" />
				</td>
			</tr>
		
			<tr style="<?php echo $display['feed-options'] ?>" class="<?php echo $leaf['id'] ?>_feed_options">
				<th scope="row"><label for="<?php echo $leaf['id'] ?>_item_limit">Item Limit</label></th>
				<td>
					<input type="text" class="headway-visual-editor-input" name="leaf-options[<?php echo $leaf['id'] ?>][item-limit]" id="<?php echo $leaf['id'] ?>_item_limit" value="<?php echo $leaf['options']['item-limit'] ?>" />
				</td>
			</tr>
		
			<tr style="<?php echo $display['feed-options'] ?>" class="<?php echo $leaf['id'] ?>_feed_options">
				<th scope="row"><label for="<?php echo $leaf['id'] ?>_feed_post_date">Post Date</label></th>	
				<td><p class="radio-container"><input type="checkbox" class="radio headway-visual-editor-input" id="<?php echo $leaf['id'] ?>_feed_post_date" name="leaf-options[<?php echo $leaf['id'] ?>][feed-post-date]"<?php echo headway_checkbox_value($leaf['options']['feed-post-date']) ?> /><label for="<?php echo $leaf['id'] ?>_feed_post_date">Show Post Date</label></p></td>	
			</tr>
		
			<tr style="<?php echo $display['feed-options'] ?>" class="<?php echo $leaf['id'] ?>_feed_options no-border">
				<th scope="row"><label for="<?php echo $leaf['id'] ?>_nofollow_feed_links">nofollow</label></th>
				<td><p class="radio-container"><input type="checkbox" class="radio headway-visual-editor-input" id="<?php echo $leaf['id'] ?>_nofollow_feed_links" name="leaf-options[<?php echo $leaf['id'] ?>][nofollow-feed-links]"<?php echo headway_checkbox_value($leaf['options']['nofollow-feed-links']) ?> /><label for="<?php echo $leaf['id'] ?>_nofollow_feed_links"><code>nofollow</code> links to posts in feed.</label></p></td>	
			</tr>
		</table>
	</div>
<?php
	HeadwayLeafsHelper::open_tab('miscellaneous', $leaf['id']);
		HeadwayLeafsHelper::create_show_title_checkbox($leaf['id'], $leaf['config']['show-title']);
		HeadwayLeafsHelper::create_title_link_input($leaf['id'], $leaf['config']['title-link']);
		HeadwayLeafsHelper::create_classes_input($leaf['id'], $leaf['config']['custom-classes'], true);
	HeadwayLeafsHelper::close_tab();
}

function feed_leaf_content($leaf){
?>
	<?php if($leaf['options']['mode'] == 'recent'): ?>
	<?php
		$query_options = array('what_to_show' => 'posts', 'post_status' => 'publish');
		if(isset($leaf['options']['categories']) && $leaf['options']['categories'][0] != NULL){
			if($leaf['options']['categories-mode'] == 'include') $query_options['category__in'] = $leaf['options']['categories'];
			if($leaf['options']['categories-mode'] == 'exclude') $query_options['category__not_in'] = $leaf['options']['categories'];	
		} 
		if($leaf['options']['post-limit']) $query_options['showposts'] = $leaf['options']['post-limit'];




		$leaf_query = new WP_Query($query_options);
			while ( $leaf_query->have_posts() ) : 
			$leaf_query->the_post();
	?>


			<div id="post-<?php the_ID() ?>" class="<?php headway_post_class() ?> small-post feed-post">
				<h3 class="entry-title recent-entry-title"><a href="<?php the_permalink() ?>" title="<?php printf(__('Permalink to %s', 'headway'), esc_html(get_the_title(), 1)) ?>" rel="bookmark"><?php the_title() ?></a></h3>
				<?php if($leaf['options']['post-date']): ?>
				<div class="entry-meta"><?php the_time('F j, Y') ?></div>
				<?php endif; ?>
			</div><!-- .post .small-post -->


	<?php endwhile; ?>
	<?php elseif($leaf['options']['mode'] == 'feed'): ?>

		<?php
			include_once(ABSPATH . WPINC . '/rss.php');
			$rss_query = fetch_rss($leaf['options']['feed-location']);
			if($rss_query) $items = array_slice($rss_query->items, 0, $leaf['options']['item-limit']);

			$nofollow = null;
			if($leaf['options']['nofollow-feed-links']) $nofollow = ' rel="nofollow"';
		?>

		<?php
		if (empty($items)) echo '<p>The feed entered is either invalid or does not contain any items.</p>';
		else foreach ( $items as $item ) : 
			$count++;
		?>


		<div id="post-<?php echo $count?>" class="post small-post feed-post">
			<h3 class="entry-title recent-entry-title"><a href="<?php echo $item['link']?>"<?php echo $nofollow ?>><?php echo $item['title']?></a></h3>
			<?php if($leaf['options']['feed-post-date']): ?>
			<div class="feed-entry-date"><?php echo date('F j, Y', strtotime($item['pubdate'])) ?></div>
			<?php endif; ?>
		</div><!-- .post .small-post -->


		<?php endforeach; ?>


	<?php endif; ?>
<?php
}

$options = array(
		'id' => 'feed',
		'name' => 'Recent Posts/RSS',
		'default_leaf' => true,
		'options_callback' => 'feed_leaf_inner',
		'content_callback' => 'feed_leaf_content',
		'icon' => get_bloginfo('template_directory').'/library/leafs/icons/feed.png'
	);

$feed_leaf = new HeadwayLeaf($options);