<?php
global $headway_admin_success;
	
if(isset($headway_admin_success) && $headway_admin_success === true)
	echo '<div class="success"><span>Skin Options Updated!</span> <a href="'.home_url().'">View Site &raquo;</a></div>';

//Memorable hook to be used to define the global in the plugin.
do_action('headway_skin_options');

//Get the skin options.
global $headway_skin_options;
?>
<form method="post">	
	<div class="tab tab-singular">
		<table class="form-table form-table-margin-top">
			<?php echo $headway_skin_options; ?>
		</table>
	</div>

	<p class="submit">
	<input type="hidden" value="<?php echo wp_create_nonce('headway-admin-nonce'); ?>" name="headway-admin-nonce" />
	<input type="submit" value="Save Changes" class="button-primary" name="headway-submit" tabindex="1" />
	</p>
</form>