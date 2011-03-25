<?php
global $headway_admin_success;

if(isset($headway_admin_success) && $headway_admin_success === true)
	echo '<div class="success"><span>Configuration Updated!</span> <a href="'.get_bloginfo('url').'">View Site &raquo;</a></div>';
?>

<form method="post">	
	<div id="headway-tab-container">
	<div id="tabs">
		<ul>
			<li><a href="#general-options">General Options</a></li>
			<li><a href="#posts">Posts</a></li>
			<li><a href="#comments">Comments</a></li>
			<li><a href="#headway-seo">Search Engine Optimization</a></li>
			<li><a href="#scripts-options">Scripts and Analytics</a></li>
			<li><a href="#visual-editor-options">Visual Editor Options</a></li>
			<li><a href="#developer-options">Developer Options</a></li>
			<?php if(is_multisite() && is_main_site()){ ?>
			<li><a href="#multi-site-settings">WordPress Multi-Site Settings</a></li>
			<?php } ?>
		</ul>
	
		<!-- Start General Options -->
		<div id="general-options" class="tab">

			<h2>General Options</h2>
			
			<?php if(is_main_site()){ ?>
			<h3>Headway Registration</h3>
			
			<p>Enter your username and password that you use to get to the Headway members area to get automatic updates and inline documentation in the visual editor.</p>
			<table class="form-table">
				<tr>
					<th scope="row"><label for="headway-username">Headway Username</th>
					<td><input type="text" class="regular-text" value="<?php echo htmlentities(stripslashes(headway_get_option('headway-username'))) ?>" id="headway-username" name="headway-username" />
					</td>
				</tr>


				<tr class="no-border">
					<th scope="row"><label for="headway-password">Headway Password</th>
					<td><input type="password" class="regular-text" value="<?php echo htmlentities(stripslashes(headway_get_option('headway-password'))) ?>" id="headway-password" name="headway-password"/>
					</td>
				</tr>
			</table>
			
			<h3 class="border-top">Options</h3>
			<?php } else { ?>
			<h3>Options</h3>
			<?php } ?>
			
		
			<table class="form-table">
				
				<tr valign="top">
					<th scope="row">Printer Stylesheet</th>
					<td> 
						<fieldset>
							<legend class="hidden">Printer Stylesheet</legend>
							<label for="print-css">
								<?php headway_build_checkbox('print-css') ?>
								Enable Printer Stylesheet
							</label>
						</fieldset>
					<span class="description">Printer stylesheets make websites more printer friendly to keep users from wasting paper/ink and to help them print what they want.  However, some prefer the printer stylesheets to be disabled.</span></td>
				</tr>


				<tr valign="top">
					<th scope="row"><label for="favicon">Favicon Location</label></th>
					<td><input type="text" class="regular-text" value="<?php echo htmlentities(stripslashes(headway_get_option('favicon'))) ?>" id="favicon" name="favicon"/>
					<span class="description">A favicon is the little image that sits next to your address in the favorites menu and on tabs.  If you do not know how to save an image as an icon you can go to <a href="http://www.favicon.cc/" target="_blank">favicon.cc</a> and draw or import an image.</span></td>
				</tr>


				<tr valign="top" class="no-border">
					<th scope="row"><label for="affiliate-link">Affiliate Link <code>(No HTML)</code></label></th>
					<td><input type="text" class="regular-text" value="<?php echo htmlentities(stripslashes(headway_get_option('affiliate-link'))) ?>" id="affiliate-link" name="affiliate-link"/>
					<span class="description">If you are a member of the Headway Affiliate program (if not, you should definitely <a href="http://www.headwaythemes.com/affiliates/" target="_blank">sign up now!</a>), you can paste your affiliate link (found at the top of the affiliate panel) and earn money when someone purchases Headway through your affiliate link.  <strong>Do NOT put HTML in this field.</strong></span></td>
				</tr>
			</table>
			
			<h3 class="border-top">Skins</h3>
			
			<table class="form-table">
				<tr valign="top" class="no-border">
					<th scope="row"><label for="active-skin">Active Skin</label></th>
					<td>
						<select id="skins-selector" name="active-skin">
							<option value="none">&mdash;No Skin&mdash;</option>
							<?php do_action('headway_skins_selector'); ?>
						</select>
						
						<span class="description">Select which skin you would like to have active.  You can find skins in the <a href="http://headwaythemes.com/members" target="_blank">Headway Members Dashboard</a>.</span>
					</td>
				</tr>
			</table>
			
			<h3 class="border-top">Feed Options</h3>
							
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="feed-url">Feed URL</label></th>
					<td><input type="text" class="regular-text" value="<?php echo htmlentities(stripslashes(headway_get_option('feed-url'))) ?>" id="feed-url" name="feed-url"/>
					<span class="description">If you use any service like <a href="http://feedburner.google.com" target="_blank">FeedBurner</a>, type the feed URL here.</span></td>
				</tr>
				
				<tr valign="top">
					<th scope="row"><label for="feed-url">Exclude Categories From Feed</label></th>
					
					<td>
						<span class="description">With Headway you can exclude certain categories and specific posts from the feeds.  You can choose which categories you would like to exclude using the select box below, and you can exclude specific posts on the post edit panel.  To select multiple categories, hold control (or command if you are using a Mac).</span>
						
						<select name="feed-exclude-cats[]" multiple size="10" style="height: 175px;margin-top: 8px;">
							<?php
							if($categories_select){ //Fixes select for multiple featured boxes.  Without this it will compound the categories.
								$categories_select = '';
								$categories = '';
								$select_selected = array();
							}

							$categories = headway_get_option('feed-exclude-cats');
							$categories_select_query = get_categories();
							foreach($categories_select_query as $category){ 
								if(is_array($categories)){
									if(in_array($category->term_id, $categories)) $select_selected[$category->term_id] = ' selected';
								}

								$categories_select .= '<option value="'.$category->term_id.'"'.$select_selected[$category->term_id].'>'.$category->name.'</option>';

							}
					
							echo $categories_select; 
							?>
						</select>
					</td>
				</tr>
			</table>
			
		</div>
		<!-- End General Options -->
		
		
		<!-- Start Posts Options -->
		<div id="posts" class="tab">
			<h2>Posts</h2>
			
				<h3>Featured Posts and Excerpts</h3>
				
				<table class="form-table">
					<?php echo headway_build_admin_input('text', 'posts', 'featured-posts', 'Featured Posts', false, headway_get_option('featured-posts'), false, false, 'Choose the number of featured posts.  Featured posts are what contain the full post (unless cut off by read more tag) on the blog.') ?>
					<?php echo headway_build_admin_input('check', 'posts', 'small-excerpts', 'Small Excerpts', 'Enable Small Excerpts', headway_get_option('small-excerpts'), false, false, 'Enable or disable small excerpts.  Small excerpts are the small posts that are in rows of 2.') ?>
					<?php echo headway_build_admin_input('check', 'posts', 'disable-excerpts', 'Disable Excerpts', 'Disable Excerpts', headway_get_option('disable-excerpts'), true, false, 'Disable excerpts if you wish to display full posts on the blog and not rely on featured posts.') ?>
				</table>
				
				
				<h3 class="border-top">Post Thumbnail Size</h3>
				
				<table class="form-table">
					<?php echo headway_build_admin_input('text', 'posts', 'post-thumbnail-width', 'Width', false, headway_get_option('post-thumbnail-width'), false, true); ?>
					<?php echo headway_build_admin_input('text', 'posts', 'post-thumbnail-height', 'Height', false, headway_get_option('post-thumbnail-height'), true, true); ?>
				</table>
				
				
				<h3 class="border-top">Small Excerpt Thumbnail Size</h3>
				
				<table class="form-table">
					<?php echo headway_build_admin_input('text', 'posts', 'small-excerpt-thumbnail-width', 'Width', false, headway_get_option('small-excerpt-thumbnail-width'), false, true); ?>
					<?php echo headway_build_admin_input('text', 'posts', 'small-excerpt-thumbnail-height', 'Height', false, headway_get_option('small-excerpt-thumbnail-height'), true, true); ?>
				</table>
		
		
				<h3 class="border-top">Post Thumbnail Visibility</h3>
				
				<table class="form-table">
					<?php echo headway_build_admin_input('check', 'posts', 'hide-post-thumbnail-on-single', 'Single Posts', 'Hide Post Thumbnails On Single Post Template', headway_get_option('hide-post-thumbnail-on-single'), false, false, 'If you wish, you can keep the post thumbnails from showing on the single post template.') ?>
					<?php echo headway_build_admin_input('check', 'posts', 'hide-post-thumbnail-on-featured', 'Featured Posts', 'Hide Post Thumbnails On Featured Posts', headway_get_option('hide-post-thumbnail-on-featured'), false, false, 'If you wish, you can hide the thumbnails on the featured posts.') ?>
					<?php echo headway_build_admin_input('check', 'posts', 'hide-post-thumbnail-on-excerpts', 'Excerpts', 'Hide Post Thumbnails On Excerpts', headway_get_option('hide-post-thumbnail-on-excerpts'), false, false, 'If you wish, you can keep the post thumbnails from showing on excerpts.') ?>
					<?php echo headway_build_admin_input('check', 'posts', 'hide-post-thumbnail-on-small-excerpts', 'Small Excerpts', 'Hide Post Thumbnails On Small Excerpts', headway_get_option('hide-post-thumbnail-on-small-excerpts'), true, false, 'If you wish, you can hide the post thumbnails on small excerpts.') ?>
				</table>
				
				
				<h3 class="border-top">Post Meta</h3>
				
				<table class="form-table" id="posts-meta-options">
					<tr>
						<td colspan="2">
							<p class="info-box clearfix">You can use the following variables in the inputs below.  Drag the variable(s) to the desired text boxes or simply type the variable(s) in. <strong>Hover over each one more information.</strong>.<br /> 
							
								<a href="%date%" class="variable button-secondary" title="Will return the date of the post and will be displayed in the format you choose above.">%date%</a>
								<a href="%author%" class="variable button-secondary" title="Displays the author of the post.">%author%</a>
								<a href="%author_no_link%" class="variable button-secondary" title="Displays the author of the post, but doesn't surround it in a hyperlink.">%author_no_link%</a>
								<a href="%categories%" class="variable button-secondary" title="Shows the post categories and their links.">%categories%</a>
								<a href="%comments%" class="variable button-secondary" title="Shows the number of comments and the link to the comments in the post. Customize the format in the 'Date/Comments Meta Format Format' tab.">%comments%</a>
								<a href="%comments_link%" class="variable button-secondary" title="Does the same exact thing as %comments%, but surrounds it in a hyperlink linking to the post comments.">%comments_link%</a>
								<a href="%respond%" class="variable button-secondary" title="Shows a link that will take a visitor directly to the comment form to leave a comment.">%respond%</a>
								<a href="%tags%" class="variable button-secondary" title="Shows the posts tags.">%tags%</a>
								<a href="%edit%" class="variable button-secondary" title="If an admin, editor, or author is logged in they will be able to see this link and edit the post.">%edit%</a>
								
							</p>
						</td>
					</tr>

					<?php echo headway_build_admin_input('text', 'posts', 'post-above-title-left', 'Above Title - Left', false, headway_get_option('post-above-title-left')) ?>
					<?php echo headway_build_admin_input('text', 'posts', 'post-above-title-right', 'Above Title - Right', false, headway_get_option('post-above-title-right')) ?>
					<?php echo headway_build_admin_input('text', 'posts', 'post-below-title-left', 'Below Title - Left', false, headway_get_option('post-below-title-left')) ?>
					<?php echo headway_build_admin_input('text', 'posts', 'post-below-title-right', 'Below Title - Right', false, headway_get_option('post-below-title-right')) ?>
					<?php echo headway_build_admin_input('text', 'posts', 'post-below-content-left', 'Below Content - Left', false, headway_get_option('post-below-content-left')) ?>
					<?php echo headway_build_admin_input('text', 'posts', 'post-below-content-right', 'Below Content - Right', false, headway_get_option('post-below-content-right')) ?>

					<tr>					
						<th scope="row"><label for="post-date-format">Date Format</label></th>					
						<td>
							<select id="post-date-format" name="post-date-format" style="width: 200px;">
									<option value="wp"<?php echo headway_option_value(headway_get_option('post-date-format'), 'wp') ?>>Use WordPress Date Format</option>
									<option value="1"<?php echo headway_option_value(headway_get_option('post-date-format'), '1') ?>>January 1, 2009</option>
									<option value="2"<?php echo headway_option_value(headway_get_option('post-date-format'), '2') ?>>MM/DD/YY</option>
									<option value="3"<?php echo headway_option_value(headway_get_option('post-date-format'), '3') ?>>DD/MM/YY</option>
									<option value="4"<?php echo headway_option_value(headway_get_option('post-date-format'), '4') ?>>Jan 1</option>
									<option value="5"<?php echo headway_option_value(headway_get_option('post-date-format'), '5') ?>>Jan 1, 2009</option>
									<option value="6"<?php echo headway_option_value(headway_get_option('post-date-format'), '6') ?>>January 1</option>
									<option value="7"<?php echo headway_option_value(headway_get_option('post-date-format'), '7') ?>>January 1st</option>
									<option value="8"<?php echo headway_option_value(headway_get_option('post-date-format'), '8') ?>>January 1st, 2009</option>
							</select>
						</td>				
					</tr>
					
					<tr>
						<td colspan="2">
							<p class="info-box clearfix">In the following comment format forms, you are allowed to use the following variable.  Drag the variable to the desired text box or simply type the variable in. <strong>Hover for more information.</strong>.<br /> <a href="%num%" class="variable button-secondary" title="Shows the numbers of comments.">%num%</a></p>
						</td>
					</tr>
					
					<?php echo headway_build_admin_input('text', 'posts', 'post-comment-format-0', 'Comment Format — 0 Comments', false, headway_get_option('post-comment-format-0')) ?>
					<?php echo headway_build_admin_input('text', 'posts', 'post-comment-format-1', 'Comment Format — 1 Comment', false, headway_get_option('post-comment-format-1')) ?>
					<?php echo headway_build_admin_input('text', 'posts', 'post-comment-format', 'Comment Format — More Than 1 Comment', false, headway_get_option('post-comment-format')) ?>
					<?php echo headway_build_admin_input('text', 'posts', 'post-respond-format', 'Respond Format', false, headway_get_option('post-respond-format'), true) ?>
				</table>
			
				
				<h3 class="border-top">Miscellaneous</h3>
				
				<table class="form-table">
					<?php echo headway_build_admin_input('text', 'posts', 'read-more-text', 'Read More Text', false, headway_get_option('read-more-text'), true) ?>
				</table>
				
		</div>
		<!-- End Posts Options -->
		
		
		<!-- Start Comments Options -->
		<div id="comments" class="tab">
			<h2>Comments</h2>
				
				<h3>Avatars</h3>

				<table class="form-table">
					<?php echo headway_build_admin_input('check', 'comments', 'show-avatars', 'Commenter Avatars', 'Show Commenter Avatars', headway_get_option('show-avatars'), false, false, 'Show commenter avatars.  Avatars are the small pictures beside the comment.'); ?>

					<?php echo headway_build_admin_input('text', 'comments', 'avatar-size', 'Avatar Size', true, headway_get_option('avatar-size'), true, true, 'Enter the square dimension of the avatars.  The default is 48px.  If you want it to be 36 pixels by 36 pixels, you\'d simply type 36.'); ?>
				</table>
				
				
				<h3 class="border-top">Pages</h3>
				
				<table class="form-table">
					<?php echo headway_build_admin_input('check', 'comments', 'page-comments', 'Page Comments', 'Show Comments On Pages', headway_get_option('page-comments'), true, false, 'Enable or disable page comments.  Comments on pages are generally not recommended.'); ?>
				</table>
				
				
				<h3 class="border-top">Visibility</h3>
				
				<table class="form-table">
					<?php echo headway_build_admin_input('check', 'comments', 'hide-comment-code-info', 'Comment Info', 'Hide Comment Code Info Box', headway_get_option('hide-comment-code-info'), false, false, 'By default, underneath the reply text area is a box explaining what HTML can be used.'); ?>
					
					<?php echo headway_build_admin_input('check', 'comments', 'hide-trackback-url', 'Trackback URL', 'Hide Trackback URL', headway_get_option('hide-trackback-url'), true, false, 'If you wish, hide the trackback URL box that appears below the comment form submit button.'); ?>
				</table>
	
		</div>
		<!-- Comments Options -->
		
		
		<!-- Start SEO -->
		<div id="headway-seo" class="tab">
			<h2>Search Engine Optimization</h2>
			<?php if(class_exists('All_in_One_SEO_Pack')){ ?>
				<p class="notice">Headway has detected that you are running All in One SEO Pack.  In order to avoid conflict, the Headway SEO settings are disabled.  If you wish to, you can continue using All in One SEO pack or you can deactivate it and use Headway's SEO features.</p>
			<?php } else { ?>
				<p class="notice"><b>Warning:</b> We recommend that if you do not know what a particular option does here, don't mess with it until you have familiarized yourself with this section by reading <a href="http://headwaythemes.com/documentation/search-engine-optimization/configuration/">Headway Search Engine Optimization &rsaquo; Configuration</a>.</p>

							<h3>Title</h3>

							<table class="form-table">
								<tr>
									<th scope="row"><label for="title-home">Home Title</th>
									<td><input type="text" class="regular-text" value="<?php echo stripslashes(headway_get_option('title-home'))?>" id="title-home" name="title-home" /> 
									<span class="description"><br /><span class="available-variables">Available Variables:</span>
										<ul>
											<li><code>%blogname%</code> - <?php echo get_bloginfo('name')?></li>
											<li><code>%tagline%</code> - <?php echo get_bloginfo('description')?></li>
										</ul>
									</span></td>
								</tr>

								<?php if(get_option('page_for_posts') != get_option('page_on_front')): ?>
								<tr>
									<th scope="row"><label for="title-posts-page">Posts Page Title</th>
									<td><input type="text" class="regular-text" value="<?php echo stripslashes(headway_get_option('title-posts-page'))?>" id="title-posts-page" name="title-posts-page" /> 
									<span class="description"><br /><span class="available-variables">Available Variables:</span>
										<ul>
											<li><code>%blogname%</code> - <?php echo get_bloginfo('name')?></li>
											<li><code>%tagline%</code> - <?php echo get_bloginfo('description')?></li>
										</ul>
									</span></td>
								</tr>				
								<?php endif; ?>

								<tr>
									<th scope="row"><label for="title-page">Page Title</th>
									<td><input type="text" class="regular-text" value="<?php echo stripslashes(headway_get_option('title-page'))?>" id="title-page" name="title-page" />
										<span class="description"><br /><span class="available-variables">Available Variables:</span>
											<ul>
												<li><code>%page%</code> - Will return the title of the current page you are on.</li>
												<li><code>%blogname%</code> - <?php echo get_bloginfo('name')?></li>
												<li><code>%tagline%</code> - <?php echo get_bloginfo('description')?></li>
											</ul>
										</span></td>
								</tr>

								<tr>
									<th scope="row"><label for="title-single">Single Post Title</th>
									<td><input type="text" class="regular-text" value="<?php echo stripslashes(headway_get_option('title-single'))?>" id="title-single" name="title-single" />
										<span class="description"><br /><span class="available-variables">Available Variables:</span>
											<ul>
												<li><code>%postname%</code> - Will return the name of the current post you are viewing.</li>
												<li><code>%blogname%</code> - <?php echo get_bloginfo('name')?></li>
												<li><code>%tagline%</code> - <?php echo get_bloginfo('description')?></li>
											</ul>
										</span></td>
								</tr>



								<tr>
									<th scope="row"><label for="title-404">404 Title</th>
									<td><input type="text" class="regular-text" value="<?php echo stripslashes(headway_get_option('title-404'))?>" id="title-404" name="title-404" />
										<span class="description"><br /><span class="available-variables">Available Variables:</span>
											<ul>
												<li><code>%blogname%</code> - <?php echo get_bloginfo('name')?></li>
												<li><code>%tagline%</code> - <?php echo get_bloginfo('description')?></li>
											</ul>
										</span></td>
								</tr>

								<tr>
									<th scope="row"><label for="title-category">Category Title</th>
									<td><input type="text" class="regular-text" value="<?php echo stripslashes(headway_get_option('title-category'))?>" id="title-category" name="title-category" />
										<span class="description"><br /><span class="available-variables">Available Variables:</span>
											<ul>
												<li><code>%category%</code> - Will return the current category archive you are viewing.</li>
												<li><code>%category_description%</code> - Will return the description of the category that is being viewed.  You can define a category's description in the <a href="<?php bloginfo('wpurl') ?>/wp-admin/categories.php" target="_blank">Posts &rsaquo; Categories</a> page.</li>								
												<li><code>%blogname%</code> - <?php echo get_bloginfo('name')?></li>
												<li><code>%tagline%</code> - <?php echo get_bloginfo('description')?></li>
											</ul>
										</span></td>
								</tr>

								<tr>
									<th scope="row"><label for="title-tag">Tag Title</th>
									<td><input type="text" class="regular-text" value="<?php echo stripslashes(headway_get_option('title-tag'))?>" id="title-tag" name="title-tag" />
										<span class="description"><br /><span class="available-variables">Available Variables:</span>
											<ul>
												<li><code>%tag%</code> - Will return the current tag you are viewing.</li>
												<li><code>%blogname%</code> - <?php echo get_bloginfo('name')?></li>
												<li><code>%tagline%</code> - <?php echo get_bloginfo('description')?></li>
											</ul>
										</span></td>
								</tr>

								<tr>
									<th scope="row"><label for="title-archives">Archives Title</th>
									<td><input type="text" class="regular-text" value="<?php echo stripslashes(headway_get_option('title-archives'))?>" id="title-archives" name="title-archives" />
										<span class="description"><br /><span class="available-variables">Available Variables:</span>
											<ul>
												<li><code>%archive%</code> - Will return the current archive you are viewing.  For example, <?php echo date('F Y')?>.</li>
												<li><code>%blogname%</code> - <?php echo get_bloginfo('name')?></li>
												<li><code>%tagline%</code> - <?php echo get_bloginfo('description')?></li>
											</ul>
										</span></td>
								</tr>

								<tr>
									<th scope="row"><label for="title-search">Search Title</th>
									<td><input type="text" class="regular-text" value="<?php echo stripslashes(headway_get_option('title-search'))?>" id="title-search" name="title-search" />
										<span class="description"><br /><span class="available-variables">Available Variables:</span>
											<ul>
												<li><code>%search%</code> - Will return the search query.  For example, when someone searches for 'WordPress', the <code>%search%</code> variable would be WordPress.</li>
												<li><code>%blogname%</code> - <?php echo get_bloginfo('name')?></li>
												<li><code>%tagline%</code> - <?php echo get_bloginfo('description')?></li>
											</ul>
										</span></td>
								</tr>






								<tr class="no-border">
									<th scope="row"><label for="title-author-archives">Author Archives Title</th>
									<td><input type="text" class="regular-text" value="<?php echo stripslashes(headway_get_option('title-author-archives'))?>" id="title-author-archives" name="title-author-archives" />
										<span class="description"><br /><span class="available-variables">Available Variables:</span>
											<ul>
												<li><code>%blogname%</code> - <?php echo get_bloginfo('name')?></li>
												<li><code>%author_name%</code> - Will return the author's "nicename".  This is set in the users panel of WordPress.</li>
												<li><code>%author_description%</code> - This will return the author's description.  This is changed inthe users panel in WordPress by editing the Bio box.</li>
											</ul>
										</span></td>
								</tr>
							</table>

							<h3 class="border-top"><code>META</code> Content</h3>

							<table class="form-table">
								<tr valign="top">
									<th scope="row"><label for="home-description">Home Description</label></th>
									<td><textarea rows="5" cols="45" class="regular-text" id="home-description" name="home-description"><?php echo stripslashes(headway_get_option('home-description'))?></textarea>
									<p class="character-count-container" style="font-style: italic;color:#666;">
										<input type="text" disabled value="" style="width: 35px;text-align:right;" size="3" id="home-description-character-count">&nbsp;<span style="color: #888;">characters.</span>  Most search engines will only recognize up to 150 characters.
									</p>
									<span class="description">This will be the META description for the homepage.  The META description is what shows up underneath your website name in Google.  If this is left blank then it will default to no description.</span></td>
								</tr>

								<tr valign="top">
									<th scope="row"><label for="home-keywords">Home Keywords</label></th>
									<td><textarea rows="5" cols="45" class="regular-text" id="" name="home-keywords"><?php echo stripslashes(headway_get_option('home-keywords'))?></textarea>
									<span class="description">Place relevant words about your website in here separated by commas.  Be sure to not overload this, try to keep it below 10-15 keywords.</span></td>
								</tr>


								<tr valign="top">
									<th scope="row">Treat Categories as META Keywords</th>
									<td> 
										<fieldset>
											<legend class="hidden">Treat Categories as META Keywords</legend>
											<label for="categories-meta">
												<?php headway_build_checkbox('categories-meta') ?>
												Treat Categories as META Keywords
											</label>
										</fieldset>
									<span class="description">If this is enabled the categories of a specific post will be appended to the <code>META</code> keywords of a post.  You can add keywords to a post in the write panel under the Search Engine Optimization box.</span></td>
								</tr>



								<tr valign="top"<?php if(function_exists('get_the_post_thumbnail')){ ?> class="no-border"<?php } ?>>
									<th scope="row">Treat Tags as META Keywords</th>
									<td> 
										<fieldset>
											<legend class="hidden">Treat Tags as META Keywords</legend>
											<label for="tags-meta">
												<?php headway_build_checkbox('tags-meta') ?>
												Treat Tags as META Keywords
											</label>
										</fieldset>
									<span class="description">If this is enabled the tags of a specific post will be appended to the <code>META</code> keywords of a post.  You can add keywords to a post in the write panel under the Search Engine Optimization box.</span></td>
								</tr>



								<?php if(!function_exists('get_the_post_thumbnail')){ ?>
								<tr valign="top" class="no-border">
									<th scope="row">Canonical URLs</th>
									<td> 
										<fieldset>
											<legend class="hidden">Canonical URLs</legend>
											<label for="canonical">
												<?php headway_build_checkbox('canonical') ?>
												Enable Canonical URLs
											</label>
										</fieldset>
									<span class="description">Canonical URLs tell search engines how to treat duplicate content, which increases your rating.  When search engines detect duplicate content they will not know which to index therefore hurting you.  Canonical URLs will fix this.</span></td>
								</tr>
								<?php } ?>
							</table>



							<h3 class="border-top"><code>nofollow</code> Configuration</h3>

							<table class="form-table">
								<tr valign="top">
									<th scope="row">Comment Authors' URL</th>
									<td> 
										<fieldset>
											<legend class="hidden">Add nofollow To Comment Authors' URLs</legend>
											<label for="nofollow-comment-author">
												<?php headway_build_checkbox('nofollow-comment-author') ?>
												Add nofollow To Comment Authors' URL
											</label>
										</fieldset>
									<span class="description">Adding <code>nofollow</code> to the comment authors' URLs will tell search engines to not visit their website and to stay on yours.  Many bloggers frown upon this, which can sometimes discourage comments.  Only enable this if you are 100% sure you know you want to.</span></td>
								</tr>

								<tr valign="top" class="no-border">
									<th scope="row">Home Page Link</th>
									<td> 
										<fieldset>
											<legend class="hidden">Add nofollow To Home Page Link</legend>
											<label for="nofollow-home">
												<?php headway_build_checkbox('nofollow-home') ?>
												Add nofollow To Home Page Link
											</label>
										</fieldset>
									<span class="description">Setting the Home link to nofollow prevents Google from tracing this link back to your blogs' home page with the word "home" as the link text (the word "home" is useful for people but meaningless for SEO). Your home page link in your blog's header is still followed by Google. If you move your navigation bar above the header, and the home link becomes the first link on the page, Google will follow it anyway, even if you set it as nofollow (this is called "first link priority").</span></td>
								</tr>

								<p>If you need to add <code>nofollow</code> to any other page, there is an option in the Search Engine Optimization box on the write page.</p>
							</table>


							<h3 class="border-top"><code>noindex</code> Configuration</h3>

							<table class="form-table">
								<span class="description description-margin-bottom">We recommend that you add <code>noindex</code> to the following.  Doing so will help avoid duplicate indexing, thus helping your SEO.  This will keep the posts in focus for the search engine instead of indexing all the category archives, chronological archives, and tags archives.</span>

								<tr valign="top">
									<th scope="row">Category Archives</th>
									<td> 
										<fieldset>
											<legend class="hidden">Add noindex To Category Archives</legend>
											<label for="noindex-category-archives">
												<?php headway_build_checkbox('noindex-category-archives') ?>
												Add noindex To Category Archives
											</label>
										</fieldset>
									</td>
								</tr>



								<tr valign="top">
									<th scope="row">Archives</th>
									<td> 
										<fieldset>
											<legend class="hidden">Add noindex To Archives</legend>
											<label for="noindex-archives">
												<?php headway_build_checkbox('noindex-archives') ?>
												Add noindex To Archives
											</label>
										</fieldset>
									</td>
								</tr>





								<tr valign="top">
									<th scope="row">Tag Archives</th>
									<td> 
										<fieldset>
											<legend class="hidden">Add noindex Tag Archives</legend>
											<label for="noindex-tag-archives">
												<?php headway_build_checkbox('noindex-tag-archives') ?>
												Add noindex To Tag Archives
											</label>
										</fieldset>
									</td>
								</tr>




								<tr valign="top" class="no-border">
									<th scope="row">Author Archives</th>
									<td> 
										<fieldset>
											<legend class="hidden">Add noindex To Author Archives</legend>
											<label for="noindex-author-archives">
												<?php headway_build_checkbox('noindex-author-archives') ?>
												Add noindex To Author Archives
											</label>
										</fieldset>
									</td>
								</tr>


								<span class="description">Much like <code>nofollow</code>, you have the option to enable <code>noindex</code> in the Search Engine Optimization box on the write panel for all pages and posts.</span>

							</table>
							
						
							<h3 class="border-top">SEO Slugs</h3>

							<table class="form-table">
								<span class="description">SEO Slug Clean-up will scrub your slugs (the end of the URL, such as /about or /post-name) and remove numbers and words such as a, the, new, etc.</span>

								<tr valign="top">
									<th scope="row">SEO Slug Clean-up</th>
									<td> 
										<fieldset>
											<legend class="hidden">SEO Slug Clean-up</legend>
											<label for="seo-slugs">
												<?php headway_build_checkbox('seo-slugs') ?>
												Enable SEO Slug Clean-up
											</label>
										</fieldset>
									</td>									
								</tr>
								
								
								<tr valign="top">
									<th scope="row">Remove Numbers</th>
									<td> 
										<fieldset>
											<legend class="hidden">Remove Numbers</legend>
											<label for="seo-slugs-numbers">
												<?php headway_build_checkbox('seo-slugs-numbers') ?>
												Remove Numbers from Slugs
											</label>
										</fieldset>
									</td>									
								</tr>
								
								
								<tr valign="top" class="no-border">
									<th scope="row"><label for="home-keywords">SEO Slug Bad Words</label></th>
									<?php									
									$bad_words = array_map('headway_filter_array_piece', array_filter(explode("\n", headway_get_option('seo-slug-bad-words'))));
									
									sort($bad_words);
																		
									$bad_words = stripslashes(implode("\n", $bad_words));
									?>
									
									<td><textarea rows="15" cols="45" class="regular-text" id="" name="seo-slug-bad-words"><?php echo $bad_words ?></textarea>
									<span class="description">Place words that you would like to be removed from the SEO slugs.  Every line denotes a new word.</span></td>
								</tr>
							</table>
							
						<?php } ?>
				
		</div>
		<!-- End SEO -->
		
		
		<!-- Start Scripts Options -->
		<div id="scripts-options" class="tab">
			<h2>Scripts and Analytics</h2>

			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="header-scripts">Header Scripts</label></th>
					<td><textarea rows="10" class="regular-text" id="" name="header-scripts"><?php echo htmlentities(stripslashes(headway_get_option('header-scripts'))) ?></textarea>
					<span class="description">Anything here will go in the <code>&lt;head&gt;</code> of the website.  <strong>Do not place plain text in this!</strong></span></td>
				</tr>


				<tr valign="top" class="no-border">
					<th scope="row"><label for="footer-scripts">Footer Scripts</label></th>
					<td><textarea rows="10" class="regular-text" id="" name="footer-scripts"><?php echo htmlentities(stripslashes(headway_get_option('footer-scripts'))) ?></textarea>
					<span class="description">Anything here will be inserted before the <code>&lt;/body&gt;</code> tag of the website.  If you have any stats scripts such as <a href="http://www.google.com/analytics" target="_blank">Google Analytics</a>, paste them here.  <strong>Do not place plain text in this!</strong></span></td>
				</tr>
			</table>
		</div>
		<!-- End Scripts Options -->
		
		
		<!-- Start Visual Editor Options -->
		<div id="visual-editor-options" class="tab">

			<h2>Visual Editor Options</h2>

				<table class="form-table">
					<tr valign="top">
						<th scope="row">Visual Design Editor</th>
						<td> 
							<fieldset>
								<legend class="hidden">Disable Design Panel</legend>
								<label for="disable-visual-editor">
									<?php headway_build_checkbox('disable-visual-editor') ?>
									Disable Visual Design Editor
								</label>
							</fieldset>
							<span class="description">Disabling the Visual Design Editor will temporarily disable the Design panel and speed up loading times in the visual editor.  This will keep all of the design changes you've made, but you will not be able to make any design changes when disabled.</span></td>
							
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row">Visual Design Editor Inspector</th>
						<td> 
							<fieldset>
								<legend class="hidden">Disable Inspector</legend>
								<label for="disable-inspector">
									<?php headway_build_checkbox('disable-inspector') ?>
									Disable Inspector
								</label>
							</fieldset>
							<span class="description">If you wish to get a speed boost and don't find the inspector in the design panel to be beneficial, you can disable it using this checkbox.</span></td>
							
						</td>
					</tr>
					
					<tr valign="top" class="no-border">
						<th scope="row">Visual Editor Link</th>
						<td> 
							<fieldset>
								<legend class="hidden">Hide Visual Editor Link</legend>
								<label for="categories-meta">
									<?php headway_build_checkbox('hide-visual-editor-link') ?>
									Hide Visual Editor Link
								</label>
							</fieldset>
						<span class="description">Hide the visual editor link on the front-end of your site (this will hide it for you and all who use the visual editor, <strong>guests will never be able to see the link</strong>).  You'll still be able to access the visual editor via the link in the WordPress admin under Appearance.</span></td>
					</tr>
				</table>
		</div>
		<!-- End Visual Editor Options -->
		
		
		<!-- Start Developer Options -->
		<div id="developer-options" class="tab">

			<h2>Developer Options</h2>

				<table class="form-table">
					<tr valign="top">
						<th scope="row">Visual Editor Developer Mode</th>
						<td> 
							<fieldset>
								<legend class="hidden">Enable Developer Mode</legend>
								<label for="enable-developer-mode">
									<?php headway_build_checkbox('enable-developer-mode') ?>
									Enable Developer Mode
								</label>
							</fieldset>
							<span class="description">Enabling developer mode will remove the Design panel from the visual editor.  If you are a developer and want to rely only custom CSS, enable developer mode.  Enabling this will ignore all changes in the design panel and revert to the default.</span></td>
							
						</td>
					</tr>
					

					<tr valign="top">
						<th scope="row"><abbr title="gzip is a software application used for file compression. gzip is short for GNU zip; the program is a free software replacement for the compress program used in early Unix systems, intended for use by the GNU Project.">gzip</abbr> Compression</th>
						<td> 
							<fieldset>
								<legend class="hidden">gzip Compression</legend>
								<label for="gzip">
									<?php headway_build_checkbox('gzip'); ?>
									Enable <abbr title="gzip is a software application used for file compression. gzip is short for GNU zip; the program is a free software replacement for the compress program used in early Unix systems, intended for use by the GNU Project.">gzip</abbr> Compression
								</label>
							</fieldset>
						<span class="description"><abbr title="gzip is a software application used for file compression. gzip is short for GNU zip; the program is a free software replacement for the compress program used in early Unix systems, intended for use by the GNU Project.">gzip</abbr> compression allows your pages to load faster and make it easier on your visitors.  Compression is recommended, but some web hosts may not support gzip compression.</span></td>
					</tr>


					<tr valign="top">
						<th scope="row">Caching</th>
						<td> 
							<fieldset>
								<legend class="hidden">Caching</legend>
								<label for="disable-caching">
									<?php headway_build_checkbox('disable-caching'); ?>
									Disable CSS and JavaScript Caching
								</label>
							</fieldset>
						<span class="description">This is not recommended, but if you are having specific issues, disabling the cache may help.</span></td>
					</tr>

					
					<tr valign="top" class="no-border">
						<th scope="row">JavaScript Libraries</th>
						<td> 
							
							<span class="description">In order to speed up development time and less time scrounging to install JS libraries, use the checkboxes below to choose which JS libraries you would like to be loaded on your site.  These will be loaded site-wide.  Click on each library for more information.</span>
							
							<fieldset id="js-libraries">
								
								<legend class="hidden">JS Libraries</legend>
								
								<label>
									<input type="hidden" name="js_libraries_unchecked[unitpngfix]" id="js-unitpngfix" value="0" />
									<input type="checkbox" name="js_libraries[unitpngfix]" id="js-unitpngfix" value="1"<?php if(in_array('unitpngfix', (array)headway_get_option('js-libraries'))) echo ' checked'; ?> />
									<a href="http://labs.unitinteractive.com/unitpngfix.php" class="help" target="_blank">Unit PNG Fix (Internet Explorer .png Image Fix)</a>
								</label>
								
								<label>
									<input type="hidden" name="js_libraries_unchecked[jquery]" id="js-jquery" value="0" />
									<input type="checkbox" name="js_libraries[jquery]" id="js-jquery" value="1"<?php if(in_array('jquery', (array)headway_get_option('js-libraries'))) echo ' checked'; ?> />
									 <a href="http://jquery.com/" class="help" target="_blank">jQuery</a>
								</label>

								<label class="dependency dependency-jquery<?php if(in_array('jquery', (array)headway_get_option('js-libraries'))) echo ' dependency-show'; ?>">
									<input type="hidden" name="js_libraries_unchecked[jquery-ui]" id="js-jquery-ui" value="0" />                                                
									<input type="checkbox" name="js_libraries[jquery-ui]" id="js-jquery-ui" value="1"<?php if(in_array('jquery-ui', (array)headway_get_option('js-libraries'))) echo ' checked'; ?> />                     
									<a href="http://jqueryui.com/" class="help" target="_blank">jQuery UI Core</a>                                                                                                                  
								</label>     								   
							                                                                                                                                    
								<label class="dependency dependency-jquery dependency-jquery-ui<?php if(in_array('jquery', (array)headway_get_option('js-libraries'))) echo ' dependency-show'; ?>">                                                                                                                                 
									<input type="hidden" name="js_libraries_unchecked[jquery-ui-tabs]" id="js-jquery-ui-tabs" value="0" />                                                
									<input type="checkbox" name="js_libraries[jquery-ui-tabs]" id="js-jquery-ui-tabs" value="1"<?php if(in_array('jquery-ui-tabs', (array)headway_get_option('js-libraries'))) echo ' checked'; ?> />
									<a href="http://jqueryui.com/demos/tabs/" class="help" target="_blank">jQuery UI Tabs</a>                                                                                                                      
								</label>                                                                                                                                
								                                                                                                                                        
								<label class="dependency dependency-jquery dependency-jquery-ui dependency-jquery-ui-draggable dependency-jquery-ui-droppable<?php if(in_array('jquery', (array)headway_get_option('js-libraries'))) echo ' dependency-show'; ?>">                                                                                                                                 
									<input type="hidden" name="js_libraries_unchecked[jquery-ui-sortable]" id="js-jquery-ui-sortable" value="0" />                                                
									<input type="checkbox" name="js_libraries[jquery-ui-sortable]" id="js-jquery-ui-sortable" value="1"<?php if(in_array('jquery-ui-sortable', (array)headway_get_option('js-libraries'))) echo ' checked'; ?> />
									<a href="http://jqueryui.com/demos/sortable/" class="help" target="_blank">jQuery UI Sortable</a>                                                                                                                 
								</label>                                                                                                                                
								                                                                                                                                        
								<label class="dependency dependency-jquery dependency-jquery-ui<?php if(in_array('jquery', (array)headway_get_option('js-libraries'))) echo ' dependency-show'; ?>">                                                                                                                                 
									<input type="hidden" name="js_libraries_unchecked[jquery-ui-draggable]" id="js-jquery-ui-draggable" value="0" />                                                
									<input type="checkbox" name="js_libraries[jquery-ui-draggable]" id="js-jquery-ui-draggable" value="1"<?php if(in_array('jquery-ui-draggable', (array)headway_get_option('js-libraries'))) echo ' checked'; ?> />
									<a href="http://jqueryui.com/demos/draggable/" class="help" target="_blank">jQuery UI Draggable</a>                                                                                                            
								</label>                                                                                                                                
								                                                                                                                                        
								<label class="dependency dependency-jquery dependency-jquery-ui<?php if(in_array('jquery', (array)headway_get_option('js-libraries'))) echo ' dependency-show'; ?>">                                                                                                                                 
									<input type="hidden" name="js_libraries_unchecked[jquery-ui-droppable]" id="js-jquery-ui-droppable" value="0" />                                                
									<input type="checkbox" name="js_libraries[jquery-ui-droppable]" id="js-jquery-ui-droppable" value="1"<?php if(in_array('jquery-ui-droppable', (array)headway_get_option('js-libraries'))) echo ' checked'; ?> />
									<a href="http://jqueryui.com/demos/droppable/" class="help" target="_blank">jQuery UI Droppable</a>                                                                                                         
								</label>                                                                                                                                
								                                                                                                                                        
								<label class="dependency dependency-jquery dependency-jquery-ui<?php if(in_array('jquery', (array)headway_get_option('js-libraries'))) echo ' dependency-show'; ?>">                                                                                                                                 
									<input type="hidden" name="js_libraries_unchecked[jquery-ui-selectable]" id="js-jquery-ui-selectable" value="0" />                                                
									<input type="checkbox" name="js_libraries[jquery-ui-selectable]" id="js-jquery-ui-selectable" value="1"<?php if(in_array('jquery-ui-selectable', (array)headway_get_option('js-libraries'))) echo ' checked'; ?> />
									<a href="http://jqueryui.com/demos/selectable/" class="help" target="_blank">jQuery UI Selectable</a>                                                                                                        
								</label>                                                                                                                                
								                                                                                                                                        
								<label class="dependency dependency-jquery dependency-jquery-ui<?php if(in_array('jquery', (array)headway_get_option('js-libraries'))) echo ' dependency-show'; ?>">                                                                                                                                 
									<input type="hidden" name="js_libraries_unchecked[jquery-ui-resizable]" id="js-jquery-ui-resizable" value="0" />                                                
									<input type="checkbox" name="js_libraries[jquery-ui-resizable]" id="js-jquery-ui-resizable" value="1"<?php if(in_array('jquery-ui-resizable', (array)headway_get_option('js-libraries'))) echo ' checked'; ?> />
									<a href="http://jqueryui.com/demos/resizable/" class="help" target="_blank">jQuery UI Resizable</a>                                                                                      
								</label>                                                                                                                                
								                                                                                                                                        
								<label class="dependency dependency-jquery dependency-jquery-ui<?php if(in_array('jquery', (array)headway_get_option('js-libraries'))) echo ' dependency-show'; ?>">                                                                                                                                 
									<input type="hidden" name="js_libraries_unchecked[jquery-ui-dialog]" id="js-jquery-ui-dialog" value="0" />                                                
									<input type="checkbox" name="js_libraries[jquery-ui-dialog]" id="js-jquery-ui-dialog" value="1"<?php if(in_array('jquery-ui-dialog', (array)headway_get_option('js-libraries'))) echo ' checked'; ?> />
									<a href="http://jqueryui.com/demos/dialog/" class="help" target="_blank">jQuery UI Dialog</a>
								</label>
								
								<label class="dependency dependency-jquery<?php if(in_array('jquery', (array)headway_get_option('js-libraries'))) echo ' dependency-show'; ?>">
									<input type="hidden" name="js_libraries_unchecked[thickbox]" id="js-thickbox" value="0" />
									<input type="checkbox" name="js_libraries[thickbox]" id="js-thickbox" value="1"<?php if(in_array('thickbox', (array)headway_get_option('js-libraries'))) echo ' checked'; ?> />
									<a href="http://jquery.com/demo/thickbox/" class="help" target="_blank">Thickbox</a>
								</label>
								
								<label>
									<input type="hidden" name="js_libraries_unchecked[swfobject]" id="js-swfobject" value="0" />
									<input type="checkbox" name="js_libraries[swfobject]" id="js-swfobject" value="1"<?php if(in_array('swfobject', (array)headway_get_option('js-libraries'))) echo ' checked'; ?> />
									<a href="http://code.google.com/p/swfobject/" class="help" target="_blank">SWFObject</a>
								</label>
								
								<label>
									<input type="hidden" name="js_libraries_unchecked[prototype]" id="js-prototype" value="0" />
									<input type="checkbox" name="js_libraries[prototype]" id="js-prototype" value="1"<?php if(in_array('prototype', (array)headway_get_option('js-libraries'))) echo ' checked'; ?> />
									<a href="http://www.prototypejs.org/" class="help" target="_blank">Prototype</a>
								</label>
								
								<label class="dependency-prototype">
									<input type="hidden" name="js_libraries_unchecked[scriptaculous]" id="js-scriptaculous" value="0" />
									<input type="checkbox" name="js_libraries[scriptaculous]" id="js-scriptaculous" value="1"<?php if(in_array('scriptaculous', (array)headway_get_option('js-libraries'))) echo ' checked'; ?> />
									<a href="http://script.aculo.us/" class="help" target="_blank">Scriptaculous</a>
								</label>

							</fieldset>
							
							</td>
							
						</td>
					</tr>
				</table>

		</div>
		<!-- End Developer Options -->
		
		
		<?php if(is_multisite() && is_main_site()){ ?>
		<!-- Start Multi-Site Settings -->
		<div id="multi-site-settings" class="tab">

			<h2>WordPress Multi-Site Settings</h2>

			<table class="form-table">
				<tr valign="top">
					<th scope="row">Permissions &mdash; Tools</th>
					<td> 
						<fieldset>
							<legend class="hidden">Disable Network Sites' Tools panels</legend>
							<label for="disable-tools">
								<?php headway_build_checkbox('disable-tools', false, false, true) ?>
								Disable Network Sites' Tools panels
							</label>
						</fieldset>
						
						<span class="description">Check this if you would like to disable the Headway &raquo; Tools panel on network sites.</span></td>
					</td>
				</tr>
				
				<tr valign="top" class="no-border">
					<th scope="row">Permissions &mdash; Easy Hooks</th>
					<td> 
						<fieldset>
							<legend class="hidden">Disable Network Sites' Easy Hooks panels</legend>
							<label for="disable-easy-hooks">
								<?php headway_build_checkbox('disable-easy-hooks', false, false, true) ?>
								Disable Network Sites to use the Easy Hooks panel
							</label>
						</fieldset>
						
						<span class="description">If you would like to disable the Headway &raquo; Easy Hooks panel on network sites, check this.</span></td>
					</td>
				</tr>
			</table>

		</div>
		<!-- End Multi-Site Settings -->
		<?php } ?>
		
			
	</div>
	<!-- End Tabs -->


	<p class="submit">
	<input type="hidden" value="<?php echo wp_create_nonce('headway-admin-nonce'); ?>" name="headway-admin-nonce" />
	<input type="submit" value="Save Changes" class="button-primary" name="headway-submit" tabindex="1" />
	</p>
	</div>
</form>