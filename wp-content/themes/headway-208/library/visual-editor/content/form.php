<?php
function headway_ve_form_start(){
	global $close_url;
	
	$close_needle = (strpos(headway_current_url(), '&visual-editor=true')) ? '&visual-editor=true' : '?visual-editor=true';
	$close_url = str_replace(strstr(headway_current_url(), $close_needle), '', headway_current_url());
?>
<div id="headway-visual-editor">
		<input type="submit" value="Save All Changes" class="visual-editor-button headway-visual-editor-input" name="headway-ve-save" id="headway-save-button" />
		<span id="headway-save-load">Saving...</span>
		
		<div id="save-message">
			<h3>Saving complete!</h3>
			<a href="#" id="save-message-close">Close</a>
			
			<p id="save-message-paragraph">Some changes may not be visible, including the leaf options and site configuration, until <a href="<?php echo str_replace('&safe-mode=true', '', headway_current_url()) ?>" class="keep-active">reloading the visual editor</a>.</p>
			
			<p>				
				<a class="save-button" href="#" id="continue-editing">Continue Editing</a>
				<a class="save-button keep-active" href="<?php echo str_replace('&safe-mode=true', '', headway_current_url()) ?>">Reload Visual Editor</a>
				<a class="save-button keep-active" href="<?php echo $close_url ?>">Close Visual Editor</a>
			</p>
		</div>

		<?php 
		do_action('headway_visual_editor_top');
		
		$is_system_page = (headway_is_system_page(false, true)) ? 'true' : 'false';
		?>
		<input type="hidden" name="current-page" id="current-page" value="<?php echo headway_current_page() ?>" class="headway-visual-editor-input" />
		<input type="hidden" name="is-system-page" id="is-system-page" value="<?php echo $is_system_page ?>" class="headway-visual-editor-input" />
		<input type="hidden" name="current-real-page" id="current-real-page" value="<?php echo headway_current_page(true) ?>" class="headway-visual-editor-input" />
		<?php 
		if(headway_get_page_option(headway_current_page(), 'leaf-columns') > 1){ 
			if(headway_get_page_option(false, 'show-top-leafs-container')) 
				echo '<input type="hidden" name="layout-order[top]" id="top-container-layout-order" value="unserialized" class="headway-visual-editor-input" />';
				
			if(headway_get_page_option(false, 'show-bottom-leafs-container'))
				echo '<input type="hidden" name="layout-order[bottom]" id="bottom-container-layout-order" value="unserialized" class="headway-visual-editor-input" />';
			
			for($i = 1; $i <= headway_get_page_option(headway_current_page(), 'leaf-columns'); $i++){
				echo '<input type="hidden" name="layout-order['.$i.']" id="column-'.$i.'-layout-order" value="unserialized" class="headway-visual-editor-input" />';
			}
			
			echo '<input type="hidden" name="column-order" id="column-order" value="unserialized" class="headway-visual-editor-input" />';
		} else {
		?>
		<input type="hidden" name="layout-order" id="layout-order" value="unserialized" class="headway-visual-editor-input" />
		<?php
		}
		?>
		<input type="hidden" name="header-order" id="header-order" value="unserialized" class="headway-visual-editor-input" />
		<input type="hidden" name="nav_order[main]" id="navigation-order" value="unserialized" class="headway-visual-editor-input" />
		<input type="hidden" name="nav_order[inactive]" id="navigation-order-inactive" value="unserialized" class="headway-visual-editor-input" />
		<input type="hidden" name="headway-nonce" id="nonce" value="<?php echo wp_create_nonce('headway-visual-editor-nonce'); ?>" class="headway-visual-editor-input-nonce" />
<?php
}

function headway_ve_form_end(){
do_action('headway_visual_editor_before_end');
?>
</div>
<?php
do_action('headway_visual_editor_end');
}