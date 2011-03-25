jQuery(document).ready(function() {
	// Feed/iTunes Settings Accordion
	jQuery( "#podpress_accordion" ).accordion({
		header: 'h4',
		autoHeight: false
	});
	
	// Widget Settings Accordion
	jQuery( ".podpress_widget_accordion" ).accordion({
		header: 'h5',
		autoHeight: false
	});
	jQuery('input.widget-control-save').live('click', function() {
		var podpress_a_widget_id = jQuery(this).closest('div.widget').attr('id');
		if ( podpress_a_widget_id.search(/podpress_feedbuttons.+/)  != -1 ) {
			jQuery('#'+podpress_a_widget_id).ajaxComplete( function(event, request, settings) {
				jQuery( ".podpress_widget_accordion" ).accordion({
					header: 'h5',
					autoHeight: false
				});
			});
		}
	});
	
	// Preview Windows for the Feed images
	jQuery( ".podpress_rssimage_preview" ).dialog({
		autoOpen: false,
		modal: true,
		minWidth: 200,
		minHeight: 144,
		open: function(event, ui) { 
			// hide all form elements which may cause problems with z-index in IE 6 and older versions
			if ( jQuery.browser.msie && 7 > parseInt(jQuery.browser.version) ) {
				jQuery(':input').css('visibility', 'hidden');
			}
		},
		close: function(event, ui) { 
			if ( jQuery.browser.msie && 7 > parseInt(jQuery.browser.version) ) {
				jQuery(':input').css('visibility', 'visible');
			}
		},
		resizable: false
	});
 	jQuery( ".podpress_itunesimage_preview" ).dialog({
		autoOpen: false,
		modal: true,
		minWidth: 320,
		minHeight: 300,
		open: function(event, ui) { 
			// hide all form elements which may cause problems with z-index in IE 6 and older versions
			if ( jQuery.browser.msie && 7 > parseInt(jQuery.browser.version) ) {
				jQuery(':input').css('visibility', 'hidden');
			}
		},
		close: function(event, ui) { 
			if ( jQuery.browser.msie && 7 > parseInt(jQuery.browser.version) ) {
				jQuery(':input').css('visibility', 'visible');
			}
		},
		resizable: false
	});
});