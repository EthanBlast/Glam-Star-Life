<?php get_header(); 
$fb_user = fb_get_loggedin_user();
?>
<div style="padding:20px;" >
<?php if(FBCONNECT_CANVAS=="web") : ?>	
	  <fb:serverfbml style="width: 100%;">
	    <script type="text/fbml">
	      <fb:fbml>
<?php endif; ?>		      	
      	
	          <fb:request-form
	                    action="<?php echo get_option('siteurl'); ?>"
	                    method="GET"
	                    invite="true"
	                    type="<?php echo get_option('blogname');?>"
	                    content="<?php echo get_option('blogname')." : ".get_option('blogdescription'); ?>
	                 <fb:req-choice url='<?php echo get_option('siteurl'); ?>'
	                       label='<?php _e('Become a Member!', 'fbconnect') ?>' />
	              "
	              >
	 
	                    <fb:multi-friend-selector
						rows="5"
						email_invite="false"
						cols="3"
	                    showborder="false"
	                    actiontext="<?php _e('Select the friends you want to invite.', 'fbconnect') ?>">
	        </fb:request-form>
<?php if(FBCONNECT_CANVAS=="web") : ?>
	      </fb:fbml>
	 
	    </script>
	  </fb:serverfbml>
<?php endif; ?>	
</div>
<?php get_footer(); ?>