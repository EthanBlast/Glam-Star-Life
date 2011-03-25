<?php
/**
 * Sidebar meta box for posts and pages
 * 
 * SlideDeck for WordPress 1.3.3 - 2010-10-19
 * Copyright 2010 digital-telepathy  (email : support@digital-telepathy.com)
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * 
 * @package SlideDeck
 * @subpackage SlideDeck for WordPress
 * 
 * @uses wp_nonce_field()
 */
?>
<div id="slidedeck-meta-sidebar">
    <div class="misc-pub-section">
        <?php function_exists( 'wp_nonce_field' ) ? wp_nonce_field( 'slidedeck-for-wordpress', 'slidedeck-for-wordpress-dynamic-meta_wpnonce' ) : ''; ?>
        <p><strong>Smart SlideDeck Options</strong></p>
        <p><label for="slidedeck_slide_title">Slide Title:</label>
        <input type="text" name="_slidedeck_slide_title" value="<?php echo $slidedeck_post_meta['_slidedeck_slide_title']; ?>" size="25" maxlength="40" id="slidedeck_slide_title" class="form-input-tip" /></p>
        <p><label><input type="checkbox" name="_slidedeck_post_featured" value="1"<?php echo (boolean) $slidedeck_post_meta['_slidedeck_post_featured'] == true ? ' checked="checked"' : ''; ?> /> Feature This Post in <em>Smart SlideDecks</em></label></p>
    </div>
	<div class="misc-pub-section last">
        Place your cursor in the post body where you want to insert a SlideDeck and click the <em>Insert SlideDeck</em> button below to choose a SlideDeck and add it to your post.
    	<p style="text-align:center;margin: 10px 0;">
            <a href="#insert" class="slidedeck-sidebar-insert button">
                <span class="inner">Insert SlideDeck</span>
            </a>
        </p>
        <p style="margin:0;text-align:center;"><a href="http://www.slidedeck.com/pricing/" class="slidedeck-sidebar-upgrade">Upgrade to SlideDeck Pro</a></p>
    </div>
</div>