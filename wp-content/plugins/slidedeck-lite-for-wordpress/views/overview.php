<?php
/**
 * Overview list of SlideDecks
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
 * @uses slidedeck_action()
 * @uses slidedeck_show_message()
 * @uses wp_nonce_url()
 */
?>
<div class="wrap" id="slidedeck_overview">
	<div id="icon-edit" class="icon32"></div>
    <h2>Edit SlideDecks 
        <a class="button add-new-h2" href="<?php echo slidedeck_action( '/slidedeck_add_new' ); ?>">Add New</a>
        <a class="button add-new-h2" href="<?php echo slidedeck_action( '/slidedeck_dynamic' ); ?>" style="margin-left:0;"><img src="<?php echo slidedeck_url( '/images/icon_dynamic.png' ); ?>" alt="Smart SlideDeck" /> Add Smart SlideDeck</a>
    </h2>
	
    <?php echo slidedeck_show_message(); ?>
    
    <?php if( (boolean) SLIDEDECK_LEGACY_IMPORT_COMPLETE !== true ): ?>
        <div class="intro-text">
            <p>It doesn't look like you have run the plugin upgrade yet to import your legacy SlideDecks. Please go to the <a href="<?php echo clean_url( admin_url( 'plugins.php' ) ); ?>">plugins section</a> to deactivate and reactivate this plugin.</p>
        </div>
    <?php endif; ?>
    
    <div class="intro-text">
        <p><a href="<?php echo slidedeck_action( '/upgrade' ); ?>&variation=SlideDeck+Overview+CTA">Upgrade to SlideDeck Pro for Wordpress</a> and get access to features like Vertical Slides, Advanced Content Control and Smart SlideDecks from any RSS feed.</p>
    </div>
    
	<?php if( !empty( $slidedecks ) ): ?>
		<table class="widefat post fixed" cellspacing="0">
			<thead>
				<tr>
					<th class="manage-column column-title" scope="col"><a href="<?php echo slidedeck_orderby( 'title' ); ?>"<?php echo slidedeck_get_current_orderby( 'title' ) !== false ? ' class="order ' . slidedeck_get_current_orderby( 'title' ) . '"' : ''; ?>>Title</a></th>
					<th width="150" class="manage-column" scope="col">Actions</th>
					<th width="80" class="manage-column column-date" scope="col"><a href="<?php echo slidedeck_orderby( 'modified' ); ?>"<?php echo slidedeck_get_current_orderby( 'modified' ) !== false ? ' class="order ' . slidedeck_get_current_orderby( 'modified' ) . '"' : ''; ?>>Modified</a></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th class="manage-column column-title" scope="col">Title</th>
					<th class="manage-column" scope="col">Actions</th>
					<th class="manage-column column-date" scope="col">Modified</th>
				</tr>
			</tfoot>
			<tbody>
				<?php $alternate = 0; ?>
				<?php foreach( (array) $slidedecks as $slidedeck ): ?>
					<tr class="author-self status-publish iedit<?php echo ( $alternate & 1 ) ? ' alternate' : ''; ?>" valign="top">
						<td class="post-title column-title">
							<a href="<?php echo slidedeck_action( $slidedeck['dynamic'] == '1' ? '/slidedeck_dynamic' : '' ); ?>&action=edit&id=<?php echo $slidedeck['id']; ?>">
    							<?php if( $slidedeck['dynamic'] == '1' ): ?>
    								<img src="<?php echo slidedeck_url( '/images/icon_dynamic.png' ); ?>" alt="Smart SlideDeck" />
    							<?php endif; ?>
                                <?php echo $slidedeck['title']; ?>
                            </a> <span class="slidedeck-id">[<?php echo $slidedeck['id']; ?>]</span>
						</td>
						<td class="manage-column" scope="col">
							<a href="<?php echo slidedeck_action( $slidedeck['dynamic'] == '1' ? '/slidedeck_dynamic' : '' ); ?>&action=edit&id=<?php echo $slidedeck['id']; ?>" class="slidedeck-action">Edit</a>
							<a href="<?php echo wp_nonce_url( slidedeck_action() . '&action=delete&id=' . $slidedeck['id'], 'slidedeck-delete' ); ?>" class="slidedeck-action delete">Delete</a>
						</td>
						<td clsss="date column-date"><?php echo date( "Y/m/d", strtotime( $slidedeck['updated_at'] ) ); ?></td>
					</tr>
					<?php $alternate++; ?>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php else: ?>
	<div id="message" class="updated">
		<p>No SlideDecks found! <a href="<?php echo slidedeck_action( '/slidedeck_add_new' ); ?>">Create a New SlideDeck</a> or <a href="<?php echo slidedeck_action( '/slidedeck_dynamic' ); ?>">Create a New Smart SlideDeck</a></p>
	</div>
	<?php endif; ?>
</div>
<div class="bug-report"><a href="http://www.getsatisfaction.com/slidedeck/topics" target="_blank" class="button"><span class="inner">Report a bug for SlideDeck</span></a></div>
