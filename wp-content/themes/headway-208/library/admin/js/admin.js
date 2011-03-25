jQuery(document).ready(function() {
		jQuery("#tabs").tabs();
		
		jQuery("a.variable").draggable({ revert: true, helper: 'clone', revertDuration: 150, opacity: 0.6, cursor: 'move' });

		jQuery("table#posts-meta-options input").droppable({
			hoverClass: 'variable-hover',
			drop: function(event, ui) {
				variable = jQuery(ui.draggable).text();
				this_val = jQuery(this).val();
				jQuery(this).val(this_val + variable + ' ');
			}
		});
		
		jQuery('input#reset-headway').click(function(){
			return confirm('Are you sure you want to reset Headway?  All changes and settings will be lost.');
		});
	
		jQuery('input#js-jquery').change(function(){
			if(jQuery(this).is(':checked')){
				jQuery('label.dependency-jquery').addClass('dependency-show');
			} else {
				jQuery('label.dependency-jquery').removeClass('dependency-show');
				jQuery('label.dependency-jquery input').attr('checked', false);
			}
		});
		
		jQuery('label.dependency-jquery-ui input').change(function(){
			if(jQuery(this).is(':checked')){
				jQuery('input#js-jquery-ui').attr('checked', true);
			}
		});
		
		jQuery('label.dependency-jquery-ui-draggable input').change(function(){
			if(jQuery(this).is(':checked')){
				jQuery('input#js-jquery-ui-draggable').attr('checked', true);
			}
		});
		
		jQuery('label.dependency-jquery-ui-droppable input').change(function(){
			if(jQuery(this).is(':checked')){
				jQuery('input#js-jquery-ui-droppable').attr('checked', true);
			}
		});
		
		jQuery('label.dependency-prototype input').change(function(){
			if(jQuery(this).is(':checked')){
				jQuery('input#js-prototype').attr('checked', true);
			}
		});
		
		jQuery('input#export-style-button').click(function(){		
			style_name = jQuery('select#export-style-selector').find(':selected').text();
			style_id = jQuery('select#export-style-selector').find(':selected').val().replace('style-', '');

			url = headway_blog_url + '/?headway-trigger=process&process=export-style&style-id='+escape(style_name + '-' + style_id) + '&style-name='+escape(style_name);

			window.open(url);	

			return false;
		});
		
		jQuery('input#export-leaf-template-button').click(function(){		
			template_name = jQuery('select#export-template-selector').find(':selected').text();
			template_id = jQuery('select#export-template-selector').find(':selected').val().replace('template-', '');

			url = headway_blog_url + '/?headway-trigger=process&process=export-leaf-template&template-id='+escape(template_name + '-' + template_id) + '&template-name='+escape(template_name);

			window.open(url);	

			return false;
		});

		
		jQuery('input.export-button').click(function(){
			what = jQuery(this).attr('id').replace('export-', '').replace('-button', '');
			
			url = headway_blog_url + '/?headway-trigger=process&process=export-settings&what='+what;

			window.open(url);	

			return false;
		});
		
		if(jQuery('#home-description').length == 1){
			jQuery('#home-description-character-count').val(jQuery('#home-description').val().length);
			
			jQuery('#home-description').keyup(function(){
				jQuery('#home-description-character-count').val(jQuery(this).val().length);
			})
		}
});