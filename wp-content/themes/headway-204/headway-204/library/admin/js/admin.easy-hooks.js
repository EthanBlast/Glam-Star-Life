jQuery(document).ready(function(){
	jQuery('select#select-hook').change(function(){
		this_value = jQuery(this).val();

		jQuery('.hook').hide();
		jQuery('#'+this_value).show();
	});
});