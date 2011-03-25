<?php
function headway_visual_editor_sidebar(){
	global $close_url;
?>
	<div id="visual-editor-menu">
		<ul>
			<li class="bold">
				<a href="#" class="no-link">Headway</a>
				
				<ul>
					<li><a href="<?php echo str_replace('&safe-mode=true', '', headway_current_url()) ?>" class="keep-active"><?php _e('Reload Visual Editor', 'headway'); ?></a></li>
					<li><a href="<?php echo $close_url ?>" id="close-editor" class="no-link"><?php _e('Close Visual Editor', 'headway'); ?></a></li>
				</ul>
			</li>
		
			<li>
				<a href="#" class="no-link"><?php _e('Linking', 'headway'); ?></a>
				
				<ul>
					<li><a href="#" id="linking-options"><?php _e('Linking Options', 'headway'); ?></a></li>
					<li><a href="#" id="save-and-link" class="use-overlay"><?php _e('Save and Link', 'headway'); ?></a></li>
				</ul>
			</li>
		
			<li>
				<a href="#" class="no-link"><?php _e('Tools', 'headway'); ?></a>
				
				<ul>
					<li><a href="#" id="import-window"><?php _e('Import', 'headway'); ?></a></li>
					<li><a href="#" id="export-window"><?php _e('Export', 'headway'); ?></a></li>
					
					<li><a href="#" id="mass-font-change"><?php _e('Mass Font Change', 'headway'); ?></a></li>
					<li><a href="#" id="wizard"><?php _e('Run Wizard', 'headway'); ?></a></li>
					<li><a href="#" id="live-css"><?php _e('Live CSS Editor', 'headway'); ?></a></li>
				</ul>
			</li>
		
			<li>
				<a href="#" id="help">Help</a>
			</li>
			
			<li>
				<a href="<?php echo get_bloginfo('wpurl') ?>/wp-admin" class="keep-active" target="_blank"><?php _e('WordPress Admin', 'headway'); ?></a>
				
				<ul>
					<li>
						<a href="<?php echo get_bloginfo('wpurl') ?>/wp-admin/admin.php?page=headway" class="keep-active" target="_blank"><?php _e('Headway Configuration', 'headway'); ?></a>
					</li>
					
					<li>
						<a href="<?php echo get_bloginfo('wpurl') ?>/wp-admin/admin.php?page=headway-tools" class="keep-active" target="_blank"><?php _e('Headway Tools', 'headway'); ?></a>
					</li>
					
					<li>
						<a href="<?php echo get_bloginfo('wpurl') ?>/wp-admin/admin.php?page=headway-easy-hooks" class="keep-active" target="_blank"><?php _e('Headway Easy Hooks', 'headway'); ?></a>
					</li>
					
					<?php if(is_super_admin() && is_multisite()){ ?>
					<li>
						<a href="<?php echo get_bloginfo('wpurl') ?>/wp-admin/ms-sites.php" class="keep-active" target="_blank"><?php _e('Network Sites', 'headway'); ?></a>
					</li>
					<?php } ?>
					
					<li>
						<a href="<?php echo get_bloginfo('wpurl') ?>/wp-admin/widgets.php" class="keep-active" target="_blank"><?php _e('Widgets', 'headway'); ?></a>
					</li>
					
					<?php 
					if(!headway_is_system_page() || is_single()){
						$edit_what = is_single() ? __('Edit This Post', 'headway') : __('Edit This Page', 'headway');

						global $post;
					?>
					<li>
						<a href="<?php echo get_edit_post_link( $post->ID ); ?>" class="keep-active" target="_blank"><?php echo $edit_what; ?></a>
					</li>
					<?php } ?>
				</ul>
			</li>
			
		</ul>

		<div id="visual-editor-menu-center">
			<span class="dark"><?php _e('Editing:', 'headway'); ?></span>&nbsp; <?php echo headway_nice_page_name(headway_current_page(true)); ?> 
			
			<?php if(headway_is_page_linked()){ ?>
			<span class="dark" id="link-status"><?php _e('Linked To:', 'headway'); ?></span>&nbsp; <?php echo headway_nice_page_name(headway_current_page()); ?>
			<?php } ?>
		</div>
	</div>
	
	<div id="visual-editor-sidebar">
		<div id="visual-editor-sidebar-content">	
				
			<?php 
			if(!headway_get_option('disable-visual-editor') && !headway_get_option('enable-developer-mode') && !headway_is_skin_active()) 
				headway_create_visual_editor_widget('design-editor', __('Styles and Design', 'headway'), 'headway_visual_editor_content'); 
				
			headway_create_visual_editor_widget('skins', __('Skins', 'headway'), 'headway_skins_content');
			
			headway_create_visual_editor_widget('leafs', __('Leafs and Columns', 'headway'), 'headway_leafs_panel_content');
			
			headway_create_visual_editor_widget('header-panel', __('Header', 'headway'), 'headway_header_panel_content');
			
			headway_create_visual_editor_widget('navigation', __('Navigation', 'headway'), 'headway_navigation_panel_content');
			
			headway_create_visual_editor_widget('footer-panel', __('Footer', 'headway'), 'headway_footer_panel_content');
			
			headway_create_visual_editor_widget('site-dimensions', __('Site Dimensions', 'headway'), 'headway_site_dimensions_content');
			?>
			<div class="collapsable break"></div>
				
		</div>
		
		
		<a id="visual-editor-sidebar-toggle"></a>
	</div>
<?php
}
add_action('headway_visual_editor_top', 'headway_visual_editor_sidebar');