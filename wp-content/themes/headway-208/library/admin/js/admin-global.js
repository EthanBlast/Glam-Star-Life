jQuery(document).ready(function(){
	
	////////////SEO Preview
	if(jQuery('#seo_description').length == 1){
		//Set Up Character Counters
		jQuery('#seo_description-character-count').val(jQuery('#seo_description').val().length);
		jQuery('#seo_title-character-count').val(jQuery('input#seo_title').val().length);
		
		if(jQuery('input#seo_title').val().length > 60){
			jQuery('#seo_title-character-count').css({color: '#f00', backgroundColor: '#FFEBE8', borderColor: '#CC0000'});
		} 
		
		if(jQuery('#seo_description').val().length > 150){
			jQuery('#seo_description-character-count').css({color: '#f00', backgroundColor: '#FFEBE8', borderColor: '#CC0000'});
		}

		//Insert content into preview
		if(jQuery('#seo_description').val().length > 0){
			description = jQuery('#seo_description').val();
			
			if(description.length > 150){
				description = description.substr(0, 150) + ' ...';
			}
			
			jQuery('div#seo-preview p#seo-preview-description span#text').text(description);
		} else {
			excerpt = jQuery('textarea#content').val().replace(/(<([^>]+)>)/ig, '');
			
			if(excerpt.length > 150){
				excerpt = excerpt.substr(0, 150) + ' ...';
			}
			
			jQuery('div#seo-preview p#seo-preview-description span#text').text(excerpt);
		}
		
		if(jQuery('input#seo_title').val().length > 0){
			jQuery('div#seo-preview h4').text(jQuery('input#seo_title').val());
		} else if(jQuery(' div#titlediv input#title').val().length > 0) {
			if(jQuery('input#page-title-setup').length == 1){
				jQuery('div#seo-preview h4').text(jQuery('input#page-title-setup').val().replace('%page%', jQuery('div#titlediv input#title').val()));
			} else if(jQuery('input#post-title-setup').length == 1) {
				jQuery('div#seo-preview h4').text(jQuery('input#post-title-setup').val().replace('%postname%', jQuery('div#titlediv input#title').val()));
			} else {
				jQuery('div#seo-preview h4').text(jQuery('div#titlediv input#title').val());
			}
		}
		
		if(jQuery('span#seo-preview-url').text().length == 0){
			jQuery('span#seo-preview-url').text(jQuery('span#sample-permalink').text().replace('http://', ''));
		}
		
		//Bind Inputs
		jQuery('input#seo_title').bind('keyup blur', function(){									
			if(jQuery(this).val().length > 60){
				jQuery('#seo_title-character-count').css({color: '#f00', backgroundColor: '#FFEBE8', borderColor: '#CC0000'});
			} else {
				jQuery('#seo_title-character-count').css({color: '#7f7f7f', backgroundColor: '#fff', borderColor: '#dfdfdf'});
			}
			
			jQuery('#seo_title-character-count').val(jQuery(this).val().length);
			
			if(jQuery(this).val().length == 0){
				if(jQuery('input#page-title-setup').length == 1){
					jQuery('div#seo-preview h4').text(jQuery('input#page-title-setup').val().replace('%page%', jQuery('div#titlediv input#title').val()));
				} else if(jQuery('input#post-title-setup').length == 1) {
					jQuery('div#seo-preview h4').text(jQuery('input#post-title-setup').val().replace('%postname%', jQuery('div#titlediv input#title').val()));
				} else {
					jQuery('div#seo-preview h4').text(jQuery('div#titlediv input#title').val());
				}				
			} else {
				jQuery('div#seo-preview h4').text(jQuery(this).val());
			}
		});
		
		jQuery('#seo_description').bind('keyup blur', function(){
			description = jQuery(this).val();
												
			jQuery('#seo_description-character-count').val(description.length);
			
			if(description.length > 150){
				description = description.substr(0, 150) + ' ...';

				jQuery('#seo_description-character-count').css({color: '#f00', backgroundColor: '#FFEBE8', borderColor: '#CC0000'});
			} else {
				jQuery('#seo_description-character-count').css({color: '#7f7f7f', backgroundColor: '#fff', borderColor: '#dfdfdf'});
			}
			
			if(jQuery(this).val().length == 0){
				excerpt = jQuery('textarea#content').val().replace(/(<([^>]+)>)/ig, '');
			
				if(excerpt.length > 150){
					excerpt = excerpt.substr(0, 150) + ' ...';
				}
			
				jQuery('div#seo-preview p#seo-preview-description span#text').text(excerpt);
			} else {
				jQuery('div#seo-preview p#seo-preview-description span#text').text(description);
			}
		});
		
		jQuery('div#titlediv input#title').bind('keyup blur', function(){
			if(jQuery('input#seo_title').val().length == 0){
				if(jQuery('input#page-title-setup').length == 1){
					jQuery('div#seo-preview h4').text(jQuery('input#page-title-setup').val().replace('%page%', jQuery(this).val()));
				} else if(jQuery('input#post-title-setup').length == 1) {
					jQuery('div#seo-preview h4').text(jQuery('input#post-title-setup').val().replace('%postname%', jQuery(this).val()));
				} else {
					jQuery('div#seo-preview h4').text(jQuery(this).val());
				}
			}
		});
		
		jQuery('div#seo').hoverIntent({ 
			over: function(){		
				if(jQuery('textarea#seo_description').val().length > 0) return false;
									
				excerpt = jQuery('textarea#content').val().replace(/(<([^>]+)>)/ig, '');
			
				if(excerpt.length > 150){
					excerpt = excerpt.substr(0, 150) + ' ...';
				}
			
				jQuery('div#seo-preview p#seo-preview-description span#text').text(excerpt);
			}, 
			out: function(){ return false; },
			timeout: 0
		});
		
		
		//Bind Clickables
		jQuery('div#seo-preview h4').click(function(){
			if(jQuery('input#seo_title').val().length == 0){
				jQuery('input#seo_title').val(jQuery(this).text());
				jQuery('#seo_title-character-count').val(jQuery(this).text().length);
			}
			
			jQuery('input#seo_title').focus().css({backgroundColor: '#FFF6BF'}).animate({backgroundColor: '#fff'}, 400);
		});
		
		jQuery('div#seo-preview p#seo-preview-description').click(function(){
			jQuery('textarea#seo_description').focus().css({backgroundColor: '#FFF6BF'}).animate({backgroundColor: '#fff'}, 400);
		});
	}
});