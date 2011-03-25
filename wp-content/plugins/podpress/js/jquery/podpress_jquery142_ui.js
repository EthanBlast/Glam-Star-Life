podPress_jQuery142(document).ready(function() {
	// Feed/iTunes Settings Accordion
	podPress_jQuery142( "#podpress_accordion" ).accordion({
		header: 'h4',
		autoHeight: false
	});
	
	// Widget Settings Accordion
	podPress_jQuery142( ".podpress_widget_accordion" ).accordion({
		header: 'h5',
		autoHeight: false
	});
	// bind the Accordion effect after saving the widgets settings
	podPress_jQuery142('input.widget-control-save').live('click', function() {
		var podpress_a_widget_id = podPress_jQuery142(this).closest('div.widget').attr('id');
		if ( podpress_a_widget_id.search(/podpress_feedbuttons.+/)  != -1 ) {
			jQuery('#'+podpress_a_widget_id).ajaxComplete( function(event, request, settings) {
				podPress_jQuery142( ".podpress_widget_accordion" ).accordion({
					header: 'h5',
					autoHeight: false
				});
			});
		}
	});
	
	// Preview Windows for the Feed images
	podPress_jQuery142( ".podpress_rssimage_preview" ).dialog({
		autoOpen: false,
		modal: true,
		minWidth: 200,
		minHeight: 144,
		open: function(event, ui) { 
			// hide all form elements which may cause problems with z-index in IE 6 and older versions
			if ( podPress_jQuery142.browser.msie && 7 > parseInt(podPress_jQuery142.browser.version) ) {
				podPress_jQuery142(':input').css('visibility', 'hidden');
			}
		},
		close: function(event, ui) { 
			if ( podPress_jQuery142.browser.msie && 7 > parseInt(podPress_jQuery142.browser.version) ) {
				podPress_jQuery142(':input').css('visibility', 'visible');
			}
		},
		resizable: false
	});
 	podPress_jQuery142( ".podpress_itunesimage_preview" ).dialog({
		autoOpen: false,
		modal: true,
		minWidth: 320,
		minHeight: 300,
		open: function(event, ui) { 
			// hide all form elements which may cause problems with z-index in IE 6 and older versions
			if ( podPress_jQuery142.browser.msie && 7 > parseInt(podPress_jQuery142.browser.version) ) {
				podPress_jQuery142(':input').css('visibility', 'hidden');
			}
		},
		close: function(event, ui) { 
			if ( podPress_jQuery142.browser.msie && 7 > parseInt(podPress_jQuery142.browser.version) ) {
				podPress_jQuery142(':input').css('visibility', 'visible');
			}
		},
		resizable: false
	});
});