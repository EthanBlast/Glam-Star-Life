<?php

/* * *
 * Creates link and postbox (initially hidden with display:none), calls pfs_submit on form-submission.
 * @param string $cat Category ID for posting specifically to one category. Default is '', which allows user to choose from allowed categories.
 * @param string $linktext Link text for post link. Default is set in admin settings, any text here will override that. 
 */
function post_from_site($cat = '', $linktext = ''){
	$pfs_options = get_option('pfs_options');
	if (''==$linktext) $linktext = $pfs_options['pfs_linktext'];
	$idtext = $cat.preg_replace('/[^A-Za-z0-9]/','',$linktext);
	$linktext = htmlspecialchars(htmlspecialchars_decode(strip_tags($linktext)));
	// Javascript displays the box when the link is clicked 
	echo "<a href='#' class='pfs-post-link' id='$idtext-link'>$linktext</a>"; ?>
	<div class="pfs-post-box" id="pfs-post-box-<?php echo "$idtext"; ?>" style="display:none" class="pfs_postbox">
		<div id="closex">x</div>
		<div id="pfs-alert" style='display:none;'></div>
		<?php if ( is_user_logged_in() ) : ?>
			<form class="pfs" id="pfs_form" method="post" action="<?php echo plugins_url('pfs-submit.php',__FILE__); ?>" enctype='multipart/form-data'>
				<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $pfs_options['pfs_maxfilesize'];?>" />
                <center><h3 style="line-height: 150%;">Share You GlamStarLife! <br  />
Add text, images and videos using this form below. To add video, include the link to your YouTube hosted video. Please use the file upload field to include images.                                                 <br /> ALL POSTS ARE MODERATED & MAY TAKE UP TO 24 HRS TO BE PUBLISHED.</h3></center>
				<center><h4><?php _e('Title:','pfs_domain'); ?></h4> <input name="title" id="pfs_title" value="" size="50" /></center>
				<textarea id="postcontent" name="postcontent" rows="12" cols="50"></textarea><br />
				<?php if ($pfs_options['pfs_allowimg']) echo __('Image:','pfs_domain')." <div id='pfs-imgdiv$idtext'><input type='file' class='multi' name='image[]' accept='png|gif|jpg|jpeg'/></div>"; ?>
				<br />
				<div id="pfs_meta">
				<?php
					echo '<div id="pfs_catchecks">';
					if (''==$cat){
						echo "<h4>".__('Categories:','pfs_domain')."</h4>";
						$excats = $pfs_options['pfs_excats'];
						$categories = wp_dropdown_categories("exclude=$excats&echo=0&hide_empty=0&selected=0");
						preg_match_all('/\s*<option class="(\S*)" value="(\S*)">(.*)<\/option>\s*/', $categories, $matches, PREG_SET_ORDER);
						echo "<select id='cats' name='cats[]' size='10' multiple='multiple'>";
						foreach ($matches as $match){
							echo "<option value='{$match[2]}'>{$match[3]}</option>";
						}
						echo "</select><br />\n";
						/* gsl commented out */
						/* echo "<small>".__('create new:','pfs_domain')."</small><input type='text' id='newcats' name='newcats' value='' size='15' />";*/
					} else {
						echo "<h4>";
						printf(__('Posting to %s category','pfs_domain'),get_cat_name($cat));
						echo "</h4>";
						echo "<input type='hidden' id='cats' name='cats[]' value='$cat' />";
					}
					echo "</div>";
					
					echo "<div id='pfs_tagchecks'>";
					if ($pfs_options['pfs_allowtag']){
						echo "<h4>".__('Tags:','pfs_domain')."</h4>";
						$tags = get_tags('get=all');
						if (''!=$tags) {
							echo "<select id='tags' name='tags[]' size='10' multiple='multiple'>";
							foreach ($tags as $tag) {
								echo "<option value='{$tag->name}'>{$tag->name}</option>";
							}
							echo "</select><br />\n";
						}
						/* gsl commented out */
						/* echo "<small>".__('create new:','pfs_domain')."</small><input type='text' id='newtags' name='newtags' value='' size='15' />";*/
					}
					echo "</div>";
				?>
				</div>
				<div class="clear"></div>
				<input type="submit" id="post" class="submit" name="post" value="<?php _e('Post','pfs_domain'); ?>" />
			</form>
		<?php else : ?>
			<h1>You must be logged in to post</h1>
		<?php endif; ?>
		<div class="clear"></div>
	</div>
<?php
}
?>
