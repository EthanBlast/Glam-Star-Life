<?php 
if(!get_option('fb_connect_use_thick')){
	get_header(); 
}
?>
<div class="fbnarrowcolumn narrowcolumn">
<?php 
if(!get_option('fb_connect_use_thick')){
	?>
		<h2><?php _e('User profile', 'fbconnect') ?></h2>
	<?php 
}
?>		
		<?php
			if(file_exists (TEMPLATEPATH.'/userprofile.php')){
				include( TEMPLATEPATH.'/userprofile.php');
			}else{
				include( FBCONNECT_PLUGIN_PATH.'/userprofile.php');
			}

		 ?>

		<div style="width:100%;clear:both;">&nbsp;</div>
		<h2 style="margin-top:5px;"><?php _e('User comments', 'fbconnect') ?></h2>
		<?php
			if(file_exists (TEMPLATEPATH.'/usercomments.php')){
				include( TEMPLATEPATH.'/usercomments.php');
			}else{
				include( FBCONNECT_PLUGIN_PATH.'/usercomments.php');
			}
		?>

</div>


<?php 
if(!get_option('fb_connect_use_thick')){
	get_footer(); 
}else{
?>	
<script type="text/javascript">
	FB.XFBML.parse();
</script>
<?php 
}
?>