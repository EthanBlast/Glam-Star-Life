function headway_disable_enter(){
	hwjs("div#headway-visual-editor input").keypress(function(e) {
	     if (e.which == 13) {
	     	return false;
	     }
      });
}


var keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";


function headway_encode64(input) {
	var output = '';
	var chr1, chr2, chr3;
	var enc1, enc2, enc3, enc4;
	var i = 0;

	while (i < input.length) {
		chr1 = input.charCodeAt(i++);
		chr2 = input.charCodeAt(i++);
		chr3 = input.charCodeAt(i++);

		enc1 = chr1 >> 2;
		enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
		enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
		enc4 = chr3 & 63;

		if (isNaN(chr2)) {
			enc3 = enc4 = 64;
		} else if (isNaN(chr3)) {
			enc4 = 64;
		}

		output = output + keyStr.charAt(enc1) + keyStr.charAt(enc2) + keyStr.charAt(enc3) + keyStr.charAt(enc4);
   }

   return output.toString();
}


function headway_make_form_name(element){
	form_name = element.replace(/\./g, '-period-').replace(/\ /g, '-space-').replace(/\#/g, '-pound-').replace(/\,/g, '-comma-').replace(/\:/g, '-colon-');

	return form_name;
}


function headway_initiate_sidebar_scroll(){
	hwjs('#visual-editor-sidebar-content').jScrollPane();
}


function headway_stop_ve_close(){	
	if(navigator.userAgent.indexOf("Chrome") == -1){
		window.onbeforeunload = function(){
			return "You have unsaved changes.  Are you sure you wish to leave the Visual Editor?";
		}
	}
}


function headway_open_box(box, overrideZ, removeBox){
	var largestZ = 1;
	
	if(typeof removeBox != 'undefined' && removeBox === true){
		box = box;
	} else {
		box = box + '-box';
	}
	
	hwjs('div.floaty-box').each(function(){
		var currentZ = parseFloat(hwjs(this).css('zIndex'));
		
		//Exclude z-index of help box with less than 11000
		if(currentZ < 11000 && currentZ > largestZ){
			largestZ = currentZ;
			zIndex = largestZ + 1;
		}
	});
	
	if(typeof overrideZ != 'undefined' && overrideZ !== false){
		zIndex = overrideZ;
	}
		
	hwjs('div#' + box).show().removeClass('leaf-options-hidden');
	hwjs('div#' + box).css('zIndex', zIndex);
}


function headway_close_box(box){
	hwjs('div#' + box + '-box').hide();
}


function headway_save_editor(redirect){		
	if(typeof redirect == 'undefined'){
		hwjs('span#headway-save-load').show().animate({'opacity':1}, 750, false); 		
		hwjs('input#headway-save-button').animate({'opacity':0}, 750, false);
	}	
	
	form_data = hwjs('.headway-visual-editor-input').serialize();
	font_data = hwjs('.headway-visual-editor-font-input').serialize();
	color_data = hwjs('.headway-visual-editor-color-input').serialize();
	border_data = hwjs('.headway-visual-editor-border-input').serialize();
	
	nonce = hwjs('.headway-visual-editor-input-nonce').val();

	hwjs.post( headway_blog_url + '/?headway-visual-editor-action&headway-ve-truncate-elements=true&headway-ve-nonce=' + nonce, { encoded: headway_encode64(form_data) }, function(){ 
		
		hwjs.post( headway_blog_url + '/?headway-visual-editor-action&headway-ve-nonce=' + nonce, { encoded: headway_encode64(color_data) }, function(){
		
			hwjs.post( headway_blog_url + '/?headway-visual-editor-action&headway-ve-nonce=' + nonce, { encoded: headway_encode64(font_data) }, function(){
			
				hwjs.post( headway_blog_url + '/?headway-visual-editor-action&headway-ve-nonce=' + nonce, { encoded: headway_encode64(border_data) }, function(){
				
					if(typeof redirect == 'undefined'){
						hwjs('input#headway-save-button').animate({'opacity':1}, 750, false); 
						hwjs('span#headway-save-load').animate({'opacity':0}, 750, false, function(){ 
							hwjs(this).hide(); 
						});

						hwjs('div#save-message').stop().show().animate({'opacity':1}, 750, false);

						hwjs('div#save-message').animate({'opacity':.99}, 10000).animate({'opacity':0}, 2500, false, function(){ 
							hwjs(this).hide(); 
						});
					} else {
						window.location.replace(window.location.href);
					}

					hwjs('input.headway-add-leaf-input').remove();
				
				});
			
			});
		
		});
		
	});
	
	window.onbeforeunload = function(){
		return null;
	}	
}


function headway_remove_blank_array_items(someArray) {
    var newArray = [];
    for(var index = 0; index < someArray.length; index++) {
        if(someArray[index]) {
            newArray.push(someArray[index]);
        }
    }
    return newArray;
}


function headway_equal_column_heights(){
	if(hwjs('input#disable-equal-column-heights').is(':checked')) return false;
	
	hwjs('div.leafs-column').css('height', 'auto');
	
	max_height = 0;
	
	hwjs('div.leafs-column').each(function(){			
		if(hwjs(this).height() > max_height){
			max_height = hwjs(this).height();
		}
	});
			
	hwjs('div.leafs-column').css('height', max_height + 'px');
}


function headway_ready_help_links(link){
	hwjs('div#help-box-content').html('<p class="loading"><img src="' + headway_settings['template-directory'] + '/library/shared-media/images/loading.gif" class="loading-image" /></p>');

	hwjs.ajax({
	  url: headway_blog_url+'/?headway-process=proxy&use_auth=true&url='+ escape('http://headwaythemes.com/resources/inline-documentation/?get-slug-id='+link.attr('href').replace('http://headwaythemes.com/', '')),
	  cache: false,
	  success: function(id){
	  	hwjs('select#help-selector').val(id);
	  }
	});
	

    hwjs('div#help-box-content').load(headway_blog_url+'/?headway-process=proxy&use_auth=true&url='+ escape('http://headwaythemes.com/resources/inline-documentation/?slug='+link.attr('href').replace('http://headwaythemes.com/', '')), false, function(){ 
		hwjs('div#help-box-content a[href*="headwaythemes.com/documentation"]').click(
		    function(){
		        headway_ready_help_links(hwjs(this));

		        return false;
		    }
		);
 	});
}


function headway_ready_help_selector(selector){
	hwjs('div#help-box-content').html('<p class="loading"><img src="' + headway_settings['template-directory'] + '/library/shared-media/images/loading.gif" class="loading-image" /></p>');
	hwjs('div#help-box-content').load(headway_blog_url+'/?headway-process=proxy&use_auth=true&url='+ escape('http://headwaythemes.com/resources/inline-documentation/?id='+selector.val()), false, function(){
		hwjs('div#help-box-content a[href*="headwaythemes.com/documentation"]').click(
		    function(){
		        headway_ready_help_links(hwjs(this));

		        return false;
		    }
		);
	});
}


function headway_set_up(){
	headway_equal_column_heights();
			
	hwjs('.headway-visual-editor-input').change(function(){
		headway_stop_ve_close();
	});
	
	headway_disable_enter(); 
		
	hwjs('a:not(a#close-editor, a.keep-active)').click(function(){ return false; });
		
	hwjs('div#headway-visual-editor form').attr('action', '');
	hwjs('div#headway-visual-editor form').attr('method', '');
	hwjs('div#headway-visual-editor form').attr('target', '');
	hwjs('div#headway-visual-editor form').attr('onsubmit', '');

	hwjs("div#headway-visual-editor input[type='submit']").click(function(){ return false; });

	hwjs('div#headway-visual-editor .gform_body input').attr('name', '');

	
	hwjs('input#headway-save-button').click(function(){			
		headway_save_editor();
	});
	
	hwjs('input#save-and-link-button').click(function(){
		hwjs('div#headway-visual-editor').append('<input type="hidden" name="save-and-link" id="save-and-link-hidden" class="headway-visual-editor-input" value="true" />');
		form_data = hwjs('.headway-visual-editor-input').serialize();
		hwjs('input#save-and-link-hidden').remove();
		
		hwjs('div#overlay').css('zIndex', 12001).animate({'opacity':0}, 250, false, function(){ hwjs(this).hide(); });
		hwjs('div#save-and-link-box').animate({'opacity':0}, 250, false, function(){ hwjs(this).hide(); });
		
		headway_save_editor();
	});
	
	
	hwjs('div#save-message a#save-message-close, div#save-message a#continue-editing').click(function(){
		hwjs('div#save-message').stop(true, true).animate({'opacity':0}, 750, false, function(){ 
			hwjs(this).hide(); 
		});
	});
		
	hwjs('div.floaty-box:not(.no-drag)').draggable({ 
		opacity: 0.35, 
		handle:'h4.floaty-box-header',
		start: function(event, ui){			
			largestZ = 0;
			
			hwjs('div.floaty-box:not(.no-drag)').each(function(){
				var currentZ = parseFloat(hwjs(this).css('zIndex'));

				//Exclude z-index of help box with less than 11000
				if(currentZ < 11000 && currentZ > largestZ){
					largestZ = currentZ;
					zIndex = largestZ + 1;
				}
			});

			hwjs(this).css('zIndex', zIndex);
		}
	});

	
	hwjs('h4.collapsable-header a').click(function(){
		collapsable = hwjs(this).parent().parent();

		if(collapsable.hasClass('collapsed')){
			collapsable.children('div.collapsable-content').show();
			collapsable.css({paddingBottom: '5px'});
			collapsable.removeClass('collapsed');
		} else {
			collapsable.children('div.collapsable-content').hide();
			collapsable.css({paddingBottom: 0});
			collapsable.addClass('collapsed');
		}
		
		headway_initiate_sidebar_scroll();
	});

	hwjs('div#visual-editor-menu a:not(.no-link)').click(function(){
		if(hwjs(this).hasClass('use-overlay')){
			if(hwjs('div#overlay').length == 0){
				hwjs('div#headway-visual-editor').append('<div id="overlay"></div>');
				hwjs('div#overlay').animate({opacity: 1}, 50);
			}
			
			headway_open_box(hwjs(this).attr('id'), 15003);
		} else if(hwjs(this).attr('id') == 'help'){
			if(!hwjs('div#help-box-bar-left').hasClass('loaded')){
				hwjs('div#help-box-bar-left').load(headway_blog_url+'/?headway-process=proxy&use_auth=true&url='+ escape('http://headwaythemes.com/resources/inline-documentation/?dropdown=true'), false, function(){
					hwjs('div#help-box-bar-left select').change(function(){
						if(hwjs(this).val()){
							headway_ready_help_selector(hwjs(this));
						}
					});
				});
			
				hwjs('div#help-box-bar-left').addClass('loaded');
			}
			
			headway_open_box(hwjs(this).attr('id'))
		} else {
			headway_open_box(hwjs(this).attr('id'));
		}
	});


	hwjs('a#visual-editor-sidebar-toggle').click(function(){
		if(hwjs('div#visual-editor-sidebar').hasClass('collapsed')){
			hwjs('div#headway-visual-editor').animate({marginLeft: 308}, 750);
			hwjs('div#visual-editor-sidebar').animate({left: 0}, 750);
			hwjs('div#visual-editor-sidebar').removeClass('collapsed');
		} else {
			hwjs('div#headway-visual-editor').animate({marginLeft: 0}, 750);
			hwjs('div#visual-editor-sidebar').animate({left: -308}, 750);
			hwjs('div#visual-editor-sidebar').addClass('collapsed');
		}
	});

	
	hwjs('div.tabs').tabs();
	hwjs('div.floaty-box:not(.floaty-box-close, #floaty-box-loader) h4.floaty-box-header').append('<a class="minimize window-top-right" href="#">&ndash;</a>');
	hwjs('div.floaty-box-close h4.floaty-box-header').append('<a class="close window-top-right" href="#">X</a>');
	hwjs('div.floaty-box-close h4.floaty-box-header a.close').click(function(){
		box = hwjs(this).parent().parent();
		
		box.hide();
		
		if(box.attr('id') == 'wizard-box'){
			hwjs('div#wizard-box, div#wizard-overlay').animate({opacity: 0}, 200, function(){
				hwjs('div#wizard-box, div#wizard-overlay').hide();					
				hwjs('div#wizard-box, div#wizard-overlay').css('opacity', 1);
			});
		
			hwjs.ajax({url: headway_blog_url+'/?headway-process=skip-wizard'});
		} else {
			hwjs('div.overlay, div#overlay').animate({opacity: 0}, 50, false, function(){ hwjs('div.overlay, div#overlay').hide(); });
		}
		
		return false;
	});
	
	hwjs('div.floaty-box h4.floaty-box-header a.minimize').click(function(){
		if(hwjs(this).parent().parent().hasClass('small-floaty-box')){
			hwjs(this).parent().parent().removeClass('small-floaty-box');
			hwjs(this).parent().siblings().removeClass('hidden');
			hwjs(this).html('&ndash;');
		} else {
			hwjs(this).parent().parent().addClass('small-floaty-box');

			hwjs(this).parent().siblings().addClass('hidden');
			hwjs(this).html('+');
		}
				
		return false;
	});

	
	hwjs('form#layout-chooser select').change(function(){
		hwjs(this).siblings('select').val('');
	});

	
	hwjs('div#visual-editor-sidebar div.sub-box:not(.minimize) span.sub-box-heading').append('<a class="minimize window-top-right" href="#">&ndash;</a>');
	hwjs('div#visual-editor-sidebar div.minimize span.sub-box-heading').append('<a class="minimize window-top-right" href="#">+</a>');

	
	hwjs('span.sub-box-heading a.minimize').click(function(){
		this_container = hwjs(this).parent().parent();
		
		if(this_container.hasClass('minimize')){
			this_container.removeClass('minimize');
			hwjs(this).html('&ndash;');
		} else {
			this_container.addClass('minimize');
			hwjs(this).html('+');
		}
		
		headway_initiate_sidebar_scroll();
		
		return false;
	});
	
	
	hwjs('textarea#live-css').keyup(function(){
		hwjs('#live-css-holder').html(hwjs(this).val());
	});
	
	
	hwjs('div#skins-tab ul.thumbnail-grid li a').click(function(){
		hwjs('div#skins-tab li.selected').removeClass('selected');
		hwjs(this).parent('li').addClass('selected');
								
		headway_stop_ve_close();
	});
	
	
	hwjs('a#preview-skin').click(function(){
		skin = hwjs('div#skins-tab ul li.selected').attr('id');
				
		get_variable = '/?headway-skin-preview=' + skin;
		
		hwjs('iframe#skin-preview').attr('src', headway_blog_url + get_variable);
		
		headway_open_box('skin-preview');
		
		return false;
	});
	
	
	hwjs('a#activate-skin').click(function(){
		skin = hwjs('div#skins-tab ul li.selected').attr('id');
						
		hwjs('#skin-notification').show();
		
		hwjs('select#skins-selector').val(skin);
		
		return false;
	});	

	if(typeof hwjs.cookie('headway-visual-editor-live-css-width') != 'undefined' && typeof hwjs.cookie('headway-visual-editor-live-css-top') != 'undefined'){
		hwjs('div#live-css-box').width(hwjs.cookie('headway-visual-editor-live-css-width')+'px');
		hwjs('div#live-css-box').height(hwjs.cookie('headway-visual-editor-live-css-height')+'px');
		
		hwjs('div#live-css-box').css('top', hwjs.cookie('headway-visual-editor-live-css-top')+'px');
		hwjs('div#live-css-box').css('left', hwjs.cookie('headway-visual-editor-live-css-left')+'px');
		
		hwjs('div#live-css-box textarea').width(hwjs.cookie('headway-visual-editor-live-css-width')-20+'px');
		hwjs('div#live-css-box textarea').height(hwjs.cookie('headway-visual-editor-live-css-height')-144+'px');
	}

	hwjs('div#live-css-box').resizable({
		minWidth: 350, 
		minHeight: 200, 
		alsoResize: 'textarea#live-css',
		stop: function(event, ui) {
			hwjs.cookie('headway-visual-editor-live-css-width', ui.size.width);
			hwjs.cookie('headway-visual-editor-live-css-height', ui.size.height);
		}
	});
	
	hwjs('div#live-css-box').draggable( "option" , 'stop', function(event, ui){ 
		hwjs.cookie('headway-visual-editor-live-css-top', ui.position.top);
		hwjs.cookie('headway-visual-editor-live-css-left', ui.position.left); 
	});
	
		
	hwjs('input#s').attr('name', '');
	
	hwjs.fn.reverse = [].reverse;
	
	hwjs('.ve-tooltip').tooltip({track: true, delay: 0, showURL: false, fade: 250, positionLeft: false, extraClass: 've-tooltip-default'});
	headway_initiate_sidebar_scroll();
	
	hwjs('a.floaty-box-expandable-toggle').toggle(function(){
		target = hwjs(this).attr('href');
		
		hwjs(this).text('[-]');
		hwjs('div'+target).show();
	}, function(){
		target = hwjs(this).attr('href');
		
		hwjs(this).text('[+]');
		hwjs('div'+target).hide();
	});
		
	if(typeof headway_design_editor == 'function'){ headway_design_editor(); }
	if(typeof headway_visual_editor_options == 'function'){ headway_visual_editor_options(); }
	if(typeof headway_visual_editor_leafs == 'function' && headway_settings['link'] === false){ headway_visual_editor_leafs(); }
	
	hwjs('div#visual-editor-loader, div#overlay').animate({'opacity':0}, 800, false, function(){ 
		hwjs('div#visual-editor-loader, div#overlay').remove();
		hwjs('div#headway-visual-editor').css('position', 'relative');
	 });
}


hwjs(document).ready(function(){	
	hwjs.ajax({
		url: headway_blog_url+'/?headway-process=visual-editor-run-up&callback=?&page-id='+hwjs('input#current-page').val(),
		async: false,
		dataType: 'json',
		success: function(data){
			headway_settings = data['headway-settings'];
			sizing = data['sizing'];

			headway_settings['link'] = headway_is_linked;
			headway_settings['template-directory'] = headway_template_directory;

			headway_set_up();
		}
	});
});