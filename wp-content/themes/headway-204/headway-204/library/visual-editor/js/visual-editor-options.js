function headway_bind_color_selector_button(element){
	hwjs(element).bind('click', function(){
		colorpicker_id = hwjs(this).data('colorpickerId');	
		colorpicker = hwjs('div#' + colorpicker_id);	
		
		colorpicker.show();
	});
}

function headway_visual_editor_options(){
		
	/* Header */
		/* Header Image */
		if(hwjs('div#header-image').length > 0){
			var header_uploader = new qq.FileUploader({
				text: 'Upload a Header Image',
		        element: hwjs('div#header-image')[0],
		        action: headway_blog_url + '/?headway-process=ve-upload&what=header',
				allowedExtensions: ['jpg', 'jpeg', 'gif', 'png'],
			    onComplete: function(id, fileName, response){
					hwjs('#header-image-current').text(response.filename);
					hwjs('#header-image-current-row').show();
		
					if(hwjs('input#enable-header-resizing').attr('checked')){
						image_url = headway_settings['template-directory'] + '/library/resources/timthumb/thumbnail.php?src=' + headway_settings['upload-path'] + '/header-uploads/' + escape(response.filename) + '&w=' + headway_settings['wrapper-width'];
					} else {
						image_url = headway_settings['upload-url'] + '/header-uploads/' + escape(response.filename);
					}
					
					if(hwjs('.header-link-image-inside').length == 0){
					
						rel_attr = hwjs('div#top .header-link-text-inside').attr('rel');
						title_attr = hwjs('div#top .header-link-text-inside').attr('title');
						link_attr = hwjs('div#top .header-link-text-inside').attr('href');
				
						hwjs('div#top').append('<a class="header-link-image-inside" rel="' + rel_attr + '" title="' + title_attr + '" href="' + link_attr + '"><img alt="' + title_attr + '" src="' + image_url +'" /></a>');
			
						hwjs('div#top .header-link-image-inside').click(function(){
							return false;
						});					
					} else {
						hwjs('.header-link-image-inside img').attr('src', image_url );
						hwjs('.header-link-image-inside').show();
					}
				
					hwjs('.header-link-text-inside').hide();

					hwjs('div#top').addClass('header-link-image');
					hwjs('div#top').removeClass('header-link-text');
				
					hwjs('div#header').addClass('header-image');
					hwjs('div#header').removeClass('header-text');
		
					hwjs('input#header-image-hidden').attr('value', response.filename);
				
					headway_stop_ve_close();
				
					hwjs('div#header-image ul.qq-upload-list li.qq-upload-success').remove();
				}
		    });
		
			hwjs('input#enable-header-resizing').click(function(){
				current_header_image = hwjs('input#header-image-hidden').val();
			
				if(current_header_image.match('http')){
					return;
				} else {
					if(hwjs(this).is(':checked')){
						source = headway_settings['upload-path'] + '/header-uploads/' + current_header_image;
						thumbnail = headway_settings['template-directory'] + '/library/resources/timthumb/thumbnail.php?src=' + escape(source) + '&q=90&w=' + hwjs('div#header').width() + 'px&zc=1';
					
						hwjs('a.header-link-image-inside img').attr('src', thumbnail);
					} else {
						source = headway_settings['upload-path'] + '/header-uploads/' + current_header_image;
						hwjs('a.header-link-image-inside img').attr('src', source);
					}
				}
			});
		
		
		
			hwjs('#header-image-delete').click(function(){
				if(confirm('Are you sure?') == true){
					hwjs('#header-image-current').text('');
					hwjs('#header-image-current-row').hide();
				
					hwjs('input#header-image-hidden').attr('value', 'DELETE');
			
					hwjs('.header-link-image-inside').hide();
					if(hwjs('.header-link-text-inside').length > 0){
						hwjs('.header-link-text-inside').show();
					} else {
						hwjs('div#top').prepend('<a class="header-link-text-inside" rel="home" title="' + headway_settings['name'] + '" href="' + headway_blog_url + '" style="cursor: pointer;">' + headway_settings['name'] + '</a>')
					
						hwjs('.header-link-text-inside').bind('click', function(){ return false; });
					}
				
					hwjs('div#top').addClass('header-link-text');
					hwjs('div#top').removeClass('header-link-image');
				
					hwjs('div#header').addClass('header-text');
					hwjs('div#header').removeClass('header-image')
				
					hwjs('input#header-image-margin').parent().parent().removeClass('border-top');
					hwjs('tr#header-resizing').attr('style', '');
				}
			
				headway_stop_ve_close();
			
			});
		
		
			hwjs('input#header-image-margin').blur(function(){
				hwjs('div#top').css('margin', hwjs(this).val());
			});
		}
		/* End (Header Image) */


		/* Header Items */
		hwjs('input#show-tagline').click(function(){
			if(hwjs(this).is(':checked')){
				if(hwjs('#tagline').length == 1){
					hwjs('#tagline').show();
				} else {
					hwjs('div#header').append('<span id="tagline">' + headway_settings['tagline'] + '</span>')
				}
			}
			else
			{
				hwjs('#tagline').hide();
			}
		});

		hwjs('input#show-navigation').click(function(){
			if(hwjs(this).is(':checked')){
				hwjs('div#navigation').show();
			}
			else
			{
				hwjs('div#navigation').hide();
			}
		});
		
		hwjs('input#show-header-search-bar').click(function(){
			if(hwjs(this).is(':checked')){
				hwjs('form#header-search-bar').show();
				hwjs('div#navigation ul.navigation').addClass('search-active');
			}
			else
			{
				hwjs('form#header-search-bar').hide();
				hwjs('div#navigation ul.navigation').removeClass('search-active');
			}
		});

		hwjs('input#show-header-rss-link').click(function(){
			if(hwjs(this).is(':checked')){
				hwjs('a#header-rss-link').show();
			}
			else
			{
				hwjs('a#header-rss-link').hide();
			}
		});

		hwjs('input#show-breadcrumbs').click(function(){
			if(hwjs(this).is(':checked')){
				hwjs('div#breadcrumbs').show();
			}
			else
			{
				hwjs('div#breadcrumbs').hide();
			}
		});
		/* End (Header Items) */
		
		/* Header Style */
		header_fluid_switch = false;
		header_fixed_switch = false;

		hwjs('#header-style-fluid').click(function(){
			headway_stop_ve_close();
			
			if(!header_fluid_switch){
				hwjs('body').addClass('header-fluid');
				hwjs('body').removeClass('header-fixed');


				hwjs('div#header').addClass('header-sortable');
				hwjs('div#navigation').addClass('header-sortable');
				hwjs('div#breadcrumbs').addClass('header-sortable');				
				
				hwjs('div#breadcrumbs').wrap('<div id="breadcrumbs-container" class="header-sortable-container"></div>');
				hwjs('div#navigation').wrap('<div id="navigation-container" class="header-sortable-container"></div>');
				hwjs('div#header').wrap('<div id="header-container" class="header-sortable-container"></div>');
				
				if(hwjs('#header-sortable-container').length > 0){
					hwjs('#header-sortable-container').prependTo('div#headway-visual-editor');
				} else {
					hwjs('div#headway-visual-editor').prepend('<div id="header-sortable-container"></div>');
				}
				

				hwjs('div#header-container').attr('rel', 'headerOrder_header');
				hwjs('div#navigation-container').attr('rel', 'headerOrder_navigation');
				hwjs('div#breadcrumbs-container').attr('rel', 'headerOrder_breadcrumbs');

				hwjs('.header-sortable-container').appendTo('div#header-sortable-container');
				
				hwjs('.header-sortable-container').addClass('header-sortable');
				hwjs('.header-sortable-container > div').removeClass('header-sortable').attr('rel', '');
		

				hwjs('#header').css('float', 'none');
				hwjs('#navigation').css('float', 'none');
				hwjs('#breadcrumbs').css('float', 'none');
				
				
				if(hwjs('#header-sortable-container').sortable().length > 0){
					hwjs('#header-sortable-container').sortable('destroy');
				
					hwjs('#header-sortable-container').sortable( {
						opacity:  0.75,
						forcePlaceholderSize: true,
						items: 'div.header-sortable',
						axis: 'y',
						scroll: false,
						update: function(){ 
							var header_order = hwjs('#header-sortable-container').sortable('serialize', {attribute: 'rel'}); 
							hwjs('#header-order').attr('value', header_order);
						}
					});
				}
				

				header_fluid_switch = true;
				header_fixed_switch = false;
			}

		});

		hwjs('#header-style-fixed').click(function(){
			headway_stop_ve_close();
			
			if(!header_fixed_switch){
				hwjs('body').addClass('header-fixed');
				hwjs('body').removeClass('header-fluid');
				
				hwjs('div#header').addClass('header-sortable').attr('rel', 'headerOrder_header');
				hwjs('div#navigation').addClass('header-sortable').attr('rel', 'headerOrder_navigation');
				hwjs('div#breadcrumbs').addClass('header-sortable').attr('rel', 'headerOrder_breadcrumbs');
				

				if(hwjs('#header-sortable-container').length > 0){
					hwjs('#header-sortable-container').prependTo('div#wrapper');
				} else {
					hwjs('div#wrapper').prepend('<div id="header-sortable-container"></div>');
				}
				

				hwjs('.header-sortable').prependTo('div#header-sortable-container');
				
				hwjs('.header-sortable-container').remove();


				hwjs('#header').css('float', 'left');
				hwjs('#navigation').css('float', 'left');
				hwjs('#breadcrumbs').css('float', 'left');
				
				if(hwjs('#header-sortable-container').sortable().length > 0){
					hwjs('#header-sortable-container').sortable('destroy');
				
					hwjs('#header-sortable-container').sortable( {
						opacity:  0.75,
						forcePlaceholderSize: true,
						items: 'div.header-sortable',
						axis: 'y',
						scroll: false,
						update: function(){ 
							var header_order = hwjs('#header-sortable-container').sortable('serialize', {attribute: 'rel'}); 
							hwjs('#header-order').attr('value', header_order);
						}
					});
				}


				header_fixed_switch = true;
				header_fluid_switch = false;
			}

		});
		/* End (Header Style) */
		
		/* Header Arrange */
		hwjs('#toggle-header-arrange').toggle(function(){
			
			
			if(hwjs('body').hasClass('header-fluid')){
				hwjs('div#header-container').addClass('header-sortable').attr('rel', 'headerOrder_header');
				hwjs('div#navigation-container').addClass('header-sortable').attr('rel', 'headerOrder_navigation');
				hwjs('div#breadcrumbs-container').addClass('header-sortable').attr('rel', 'headerOrder_breadcrumbs');

				if(!hwjs('div#header-sortable-container').length > 0){
					hwjs('div#headway-visual-editor').prepend('<div id="header-sortable-container"></div>');

					hwjs('.header-sortable').appendTo('div#header-sortable-container');
				}
			} else {
				hwjs('div#header').addClass('header-sortable').attr('rel', 'headerOrder_header');
				hwjs('div#navigation').addClass('header-sortable').attr('rel', 'headerOrder_navigation');
				hwjs('div#breadcrumbs').addClass('header-sortable').attr('rel', 'headerOrder_breadcrumbs');

				if(!hwjs('div#header-sortable-container').length > 0){
					hwjs('div#wrapper').prepend('<div id="header-sortable-container"></div>');

					hwjs('.header-sortable').appendTo('div#header-sortable-container');
				}
			}
			

			hwjs('#header-sortable-container').sortable({
				opacity:  0.75,
				forcePlaceholderSize: true,
				items: 'div.header-sortable',
				scroll: false,
				update: function(){ 
					var header_order = hwjs('#header-sortable-container').sortable('serialize', {attribute: 'rel'}); 
					hwjs('#header-order').attr('value', header_order);
					
					headway_stop_ve_close();
				}
			});

			hwjs('div#header-sortable-container div.header-sortable').css('cursor', 'move');

			hwjs(this).text('Disable');


			return false;
		}, function(){
			hwjs('#header-sortable-container').sortable('destroy');

			hwjs(this).text('Enable');

			hwjs('div#header-sortable-container div.header-sortable').css('cursor', 'pointer');


			return false;
		});
		/* End (Header Arrange) */
		
	/* End (Header) */
	
	
	/* Body */
		if(hwjs('div#body-background-image').length > 0){
			var background_uploader = new qq.FileUploader({
				text: 'Upload a Background Image',
		        element: hwjs('div#body-background-image')[0],
		        action: headway_blog_url + '/?headway-process=ve-upload&what=background',
				allowedExtensions: ['jpg', 'jpeg', 'gif', 'png'],
			    onComplete: function(id, fileName, response){
					hwjs('#body-background-image-current').text(response.filename);
					hwjs('#body-background-image-current-row').show();
				
					background_image_url = headway_settings['upload-url'] + '/background-uploads/' + escape(response.filename);
									
					hwjs('div#headway-visual-editor').css('backgroundImage', 'url(' + background_image_url + ')');
				
					hwjs('input#body-background-image-hidden').attr('value', response.filename);
				
					headway_stop_ve_close();

					hwjs('div#body-background-image ul.qq-upload-list li.qq-upload-success').remove();
				}	
		    });
		
			hwjs('input#background-repeat').click(function(){
				hwjs('div#headway-visual-editor').css('backgroundRepeat', 'repeat');
			});
		
			hwjs('input#background-repeat-x').click(function(){
				hwjs('div#headway-visual-editor').css('backgroundRepeat', 'repeat-x');
			});
		
			hwjs('input#background-repeat-y').click(function(){
				hwjs('div#headway-visual-editor').css('backgroundRepeat', 'repeat-y');
			});
		
			hwjs('input#background-no-repeat').click(function(){
				hwjs('div#headway-visual-editor').css('backgroundRepeat', 'no-repeat');
			});
		
			hwjs('#body-background-image-delete').click(function(){
				if(confirm('Are you sure?') == true){
					hwjs('#body-background-image-current').text('');
					hwjs('#body-background-image-current-row').hide();

					hwjs('#body-background-image-hidden').attr('value', 'DELETE');

					hwjs('div#headway-visual-editor').css('backgroundImage', 'url()');
				}
			});
		}
	/* End (Body) */


	/* Navigation */
		dragged = false;
		count = -1;
		this_array_stored = Array();
		
		
		function serialize_navigation(){
			hwjs.fn.reverse = function() {
			    return this.pushStack(this.get().reverse(), arguments);
			};
			
			hwjs('#navigation ul, #navigation ul ul').reverse().each(function(){
				parent_id = hwjs(this).parent().attr('id');
		
				this_array = hwjs(this).sortable('serialize');				
				this_array = this_array.replace(/&page\[\]=/g, '|').replace(/page\[\]=/g, '');  

				count = count + 1;
				this_array_stored.push(this_array);

				if(this_array_stored[count-1]){
					this_cleaned_nav_order = headway_remove_blank_array_items(this_array_stored[count].replace(this_array_stored[count-1], '').split('|'));
				}
				else {
					this_cleaned_nav_order = headway_remove_blank_array_items(this_array_stored[count].split('|'));
				}

				
				if(hwjs('input#navigation-order-'+parent_id).length == 0 && parent_id != 'navigation'){
					hwjs('div#headway-visual-editor').prepend('<input type="hidden" value="" class="headway-visual-editor-input" name="nav_order[child]['+parent_id+']" id="navigation-order-'+parent_id+'" />');
				}
				
			
				if(parent_id != 'navigation'){
					hwjs('input#navigation-order-'+parent_id).attr('value', this_cleaned_nav_order.join('|'));
				} else {
					hwjs('#navigation-order').attr('value', this_cleaned_nav_order.join('|'));
				}
			
			});
		}
		
		
		function start_nav_sortable(){
			hwjs('ul.navigation, #inactive-navigation, ul.navigation ul').sortable( {
				items: 'li:not(.page-item-1)',
				opacity:  0.75,
				connectWith: '.navigation, .navigation ul',
				forcePlaceholderSize: true,
				forceHelperSize: true,
				scroll: false,
				tolerance: 'mouse',
				start: function(event, ui) { 
					dragged = true;
				},
				stop: function(event, ui) {
					setTimeout(function(){
						dragged = false;
					}, 50);
				},
				update: function(){ 	
														
					serialize_navigation();
					
					var navigation_order_inactive = headway_remove_blank_array_items(hwjs('#inactive-navigation').sortable('serialize').replace(/&page\[\]=/g, '|').replace(/page\[\]=/g, '|').split('|')).join('|');	
					hwjs('#navigation-order-inactive').attr('value', navigation_order_inactive);
	
				}
			});
		}
		
		function navigation_item_click(e){
			nav_id = e.parent().attr('class');
		
			pattern = /page-item-\d{1,}/i;
			
			nav_id = nav_id.match(pattern)[0];
			nav_id = nav_id.replace('-item', '');
			
			nav_name = escape(e.text());

			if(hwjs('div#navigation-control-' + nav_id).length > 0){ hwjs('div#navigation-control-' + nav_id).show(); }
			else { load_navigation_item_options(nav_id, nav_name); }

			return false;
		}
	
		hwjs('ul.navigation li:not(.page-item-1) a').bind('dblclick', function(e){
			if(headway_settings['legacy-nav'] == true){
				navigation_item_click(hwjs(this));
			}
		});
	
	
		hwjs('#toggle-navigation').toggle(function(){
			hwjs('body').addClass('nav-reorder');
			
			start_nav_sortable();

			hwjs("ul.navigation li a").unbind('dblclick');			
			hwjs("ul.navigation li a").unbind('click');			
			
			hwjs('ul.navigation li a').dblclick(function(){
				if(hwjs(this).parent().find('ul').hasClass('hover')){
					hwjs(this).parent().find('ul').removeClass('hover').removeClass('show');
				} else {
					hwjs(this).parent().find('ul').addClass('hover').addClass('show');		
				}
				
				return false;
			});
			hwjs('ul.navigation li a').click(function(){
				if(hwjs(this).siblings('ul').length == 0 && !dragged){
					hwjs(this).parent().append('<ul></ul>');
					hwjs(this).siblings('ul').addClass('show').addClass('hover');
					
					start_nav_sortable();
				}
				
				return false;
			});
			
			
			hwjs('ul.navigation li').each(function(){
				this_li = hwjs(this);
								
				if(!this_li.attr('id')){
					nav_id = this_li.attr('class');
				
					pattern = /page-item-\d{1,}/i;

					nav_id = nav_id.match(pattern)[0];
					nav_id = nav_id.replace('-item', '');
					
					this_li.attr('id', nav_id);
				}
				
			});
			
			
			
			hwjs('ul.navigation li').css('cursor', 'move');

			hwjs(this).text('Disable');

			return false;
		}, function(){
			hwjs('body').removeClass('nav-reorder');
			
			hwjs('ul.navigation').sortable('destroy');
			hwjs(this).text('Enable');
			
			hwjs('ul.navigation li a').unbind('dblclick');
			hwjs('ul.navigation li a').unbind('click');

			hwjs('ul.navigation li:not(.page-item-1) a').bind('dblclick', function(e){
				navigation_item_click(hwjs(this));
			});
						
			hwjs('ul.navigation li a').bind('click', function(e){
				return false;
			});
			
			hwjs('ul.navigation ul.show').removeClass('show');

			hwjs('ul.navigation li').css('cursor', 'pointer');


			return false;
		});
		

	
		function load_navigation_item_options(nav_id, nav_name){
			hwjs('div#headway-visual-editor').append('<div id="navigation-control-' + nav_id + '" class="floaty-box navigation-item-options"><p class="loading"><img src="' + headway_settings['template-directory'] + '/library/shared-media/images/loading.gif" class="loading-image" /></p></div>');
			hwjs('div#navigation-control-' + nav_id).load(headway_blog_url+'/?headway-process=nav-item-options&nav-item=' + nav_id + '&nav-item-name='+nav_name, false, function(){
				prepare_navigation_item_options(nav_id, nav_name);
			});
		}
		
		
		function prepare_navigation_item_options(nav_id){
			height = hwjs('div#navigation-control-'+nav_id).height();
			hwjs('div#navigation-control-'+nav_id).resizable({minWidth: 350, minHeight: 150});
			hwjs('div#navigation-control-'+nav_id).draggable({ 
				opacity: 0.35, 
				handle:'h4.floaty-box-header',
			});

			hwjs('div#navigation-control-'+nav_id+' div.tabs').tabs();

			hwjs('div#navigation-control-'+nav_id+' h4.floaty-box-header').append('<a class="close window-top-right" href="#">X</a>');

			hwjs('div#navigation-control-'+nav_id+' h4.floaty-box-header a.close').click(function(){
				hwjs(this).parent().parent().hide();
				return false;
			});

			hwjs('div#navigation-control-'+nav_id+' input#nav-item_'+nav_id+'_name').bind('keyup keydown blur', function(e){
				this_value = hwjs(this).val();
				this_h4 = hwjs('div#navigation-control-'+nav_id+' > h4.floaty-box-header span');
				this_link = hwjs('li.'+nav_id+' > a');

				if(this_value == '') this_value = 'Navigation Item';
				
				this_link.text(this_value);
				this_h4.text(this_value);
			});

		}
	/* End (Navigation) */
	
	/* Footer */
		hwjs('input#show-admin-link').click(function(){
			if(hwjs(this).is(':checked')){
				hwjs('a#footer-admin-link').show();
			}
			else
			{
				hwjs('a#footer-admin-link').hide();
			}
		});
		
		hwjs('input#show-edit-link').click(function(){
			if(hwjs(this).is(':checked')){
				hwjs('span#footer-edit-link').show();
			}
			else
			{
				hwjs('span#footer-edit-link').hide();
			}
		});
		
		hwjs('input#show-copyright').click(function(){
			if(hwjs(this).is(':checked')){
				hwjs('p#footer-copyright').show();
			}
			else
			{
				hwjs('p#footer-copyright').hide();
			}
		});
		
		hwjs('input#show-go-to-top-link').click(function(){
			if(hwjs(this).is(':checked')){
				hwjs('a#footer-go-to-top-link').show();
			}
			else
			{
				hwjs('a#footer-go-to-top-link').hide();
			}
		});
	
	
		hwjs('input#hide-headway-attribution').click(function(){
			if(hwjs(this).is(':checked')){
				hwjs('p#footer-headway-link').hide();
			}
			else
			{
				hwjs('p#footer-headway-link').show();
			}
		});
	
		/* Header Style */
		footer_fluid_switch = false;
		footer_fixed_switch = false;

		hwjs('#footer-style-fluid').click(function(){
			headway_stop_ve_close();
			
			if(!footer_fluid_switch){
				hwjs('body').addClass('footer-fluid');
				hwjs('body').removeClass('footer-fixed');


				hwjs('div#footer').appendTo('div#headway-visual-editor');
				hwjs('div#footer').wrap('<div id="footer-container"></div>');

				if(hwjs('div#columns-container').length > 0){
					hwjs('div#wrapper').append('<div id="columns-clear" class="clear"></div>');
				}
				
				footer_fluid_switch = true;
				footer_fixed_switch = false;
			}

		});

		hwjs('#footer-style-fixed').click(function(){
			headway_stop_ve_close();
			
			if(!footer_fixed_switch){
				hwjs('body').addClass('footer-fixed');
				hwjs('body').removeClass('footer-fluid');

				hwjs('#footer').appendTo('div#wrapper');
				hwjs('#footer-container').remove();
				
				if(hwjs('div#columns-container').length > 0){
					hwjs('#columns-clear').remove();
				}

				footer_fixed_switch = true;
				footer_fluid_switch = false;
			}

		});
		/* End (Header Style) */
		
		hwjs('#navigation-position-left').click(function(){
			headway_stop_ve_close();
			
			if(hwjs('ul.navigation').hasClass('navigation-right')){
				hwjs('ul.navigation').removeClass('navigation-right');
				hwjs('div#navigation').removeClass('navigation-right');
			}
		});
		
		hwjs('#navigation-position-right').click(function(){
			headway_stop_ve_close();

			hwjs('ul.navigation').removeClass('navigation-left');
			hwjs('div#navigation').removeClass('navigation-left');
			
			hwjs('ul.navigation').addClass('navigation-right');
			hwjs('div#navigation').addClass('navigation-right');
		});

	/* End (Footer) */
	
	
	/* Page Specific Settings */
	hwjs('input#hide_header').click(function(){
		if(hwjs(this).is(':checked')){
			hwjs('div#header, div#header-container').hide();
		}
		else
		{
			hwjs('div#header, div#header-container').show();
		}
	});
	
	hwjs('input#hide_breadcrumbs').click(function(){
		if(hwjs(this).is(':checked')){
			hwjs('div#breadcrumbs, div#breadcrumbs-container').hide();
		}
		else
		{
			hwjs('div#breadcrumbs, div#breadcrumbs-container').show();
		}
	});
	
	
	hwjs('input#hide_navigation').click(function(){
		if(hwjs(this).is(':checked')){
			hwjs('div#navigation, div#navigation-container').hide();
		}
		else
		{
			hwjs('div#navigation, div#navigation-container').show();
		}
	});
	
	
	hwjs('input#hide_footer').click(function(){
		if(hwjs(this).is(':checked')){
			hwjs('div#footer, div#footer-container').hide();
		}
		else
		{
			hwjs('div#footer, div#footer-container').show();
		}
	});
	/* End (Page Specific Settings) */
	
	
	/* Sliders */
		hwjs("div#wrapper-width-slider").slider({
			max: 1200,
			min: 500,
			step: 5,
			value: hwjs('input#wrapper-width').val(),
		   	slide: function(event, ui) {											
				elements = hwjs('div#wrapper, div#header, div#navigation, div#breadcrumbs, div#footer');
				elements.css('width', ui.value);
				
				if(hwjs('div.container').length == 1){
					hwjs('div.container').css('width', ui.value-headway_settings['leaf-container-horizontal-padding']*2 + 'px');
				} else {
					number_of_containers = hwjs('div.container:not(.leafs-container)').length;
					
					usable_space = ui.value - (number_of_containers*20);
					
					width = usable_space / number_of_containers;
					
					hwjs('div.container:not(.leafs-container)').css('width', width);
					hwjs('div.leafs-container').css('width', ui.value-headway_settings['leaf-container-horizontal-padding']*2 + 'px');
					
					hwjs('input.column-width-input').val(width);
				}
				
				hwjs('input#wrapper-width').val(ui.value);
				
				headway_settings['wrapper-width'] = ui.value;
			},
			start: function(event, ui) {
				original_wrapper_width = hwjs('div#wrapper').width();
			}
		});
		
		hwjs('input#wrapper-width').blur(function(){
			value = hwjs(this).val();
			
			if(value == ''){
				value = 960;
			}
			
			if(value < 500){
				value = 500;
			}
			
			if(value > 1200){
				value = 1200;
			}
			
			hwjs(this).val(value);
			
			elements = hwjs('div#wrapper, div#header, div#navigation, div#breadcrumbs, div#footer');
			elements.css('width', value + 'px');
			
			if(hwjs('div.container').length == 1){
				hwjs('div.container').css('width', value-headway_settings['leaf-container-horizontal-padding']*2 + 'px');
			} else {
				number_of_containers = hwjs('div.container:not(.leafs-container)').length;
				
				usable_space = value - (number_of_containers*20);
				
				width = usable_space / number_of_containers;
				
				hwjs('div.container:not(.leafs-container)').css('width', width);
				hwjs('div.leafs-container').css('width', value-headway_settings['leaf-container-horizontal-padding']*2 + 'px');
				
				hwjs('input.column-width-input').val(width);
			}
			
			hwjs('input#wrapper-width').val(value);
			
			headway_settings['wrapper-width'] = value;
		});
	
	
		
		
		hwjs("div#wrapper-vertical-margin-slider").slider({
			max: 70,
			min: 0,
			value: hwjs('input#wrapper-vertical-margin').val(),
		   	slide: function(event, ui) {												
				hwjs('input#wrapper-vertical-margin').val(ui.value);
				hwjs('div#wrapper').css('margin', ui.value + 'px auto');
			}
		});
		
		hwjs('input#wrapper-vertical-margin').blur(function(){
			value = hwjs(this).val();
			
			if(value == ''){
				value = 35;
			}
			
			if(value > 70){
				value = 70;
			}
			
			hwjs('div#wrapper').css('margin', value + 'px auto');
			
			hwjs(this).val(value);
			
			hwjs("#wrapper-vertical-margin-slider").slider('option', 'value', value);
		});
		
		
		
			
		hwjs("div#wrapper-border-radius-slider").slider({
			max: 20,
			min: 0,
			value: hwjs('input#wrapper-border-radius').val(),
		   	slide: function(event, ui) {												
				hwjs('input#wrapper-border-radius').val(ui.value);

				hwjs('div#wrapper').css({ 
					WebkitBorderRadius: ui.value + 'px', 
					MozBorderRadius: ui.value + 'px', 
					BorderRadius: ui.value + 'px' 
				});

				hwjs('div.header-rearrange-item-1').css({ 
					WebkitBorderTopLeftRadius: ui.value + 'px', 
					WebkitBorderTopRightRadius: ui.value + 'px', 
					MozBorderRadiusTopleft: ui.value + 'px',
					MozBorderRadiusTopright: ui.value + 'px',
					BorderTopLeftRadius: ui.value + 'px',
					BorderTopRightRadius: ui.value + 'px'
				});

				hwjs('div.header-rearrange-item-1 .navigation-right li:last-child a').css({ 
					WebkitBorderTopRightRadius: ui.value + 'px', 
					MozBorderRadiusTopright: ui.value + 'px',
					BorderTopRightRadius: ui.value + 'px',
				});

				hwjs('div.header-rearrange-item-1 .navigation li:first-child a').css({ 
					WebkitBorderTopLeftRadius: ui.value + 'px', 
					MozBorderRadiusTopleft: ui.value + 'px',
					BorderTopLeftRadius: ui.value + 'px',
				});

				hwjs('div#footer').css({ 
					WebkitBorderBottomLeftRadius: ui.value + 'px', 
					WebkitBorderBottomRightRadius: ui.value + 'px', 
					MozBorderRadiusBottomleft: ui.value + 'px',
					MozBorderRadiusBottomright: ui.value + 'px',
					BorderBottomLeftRadius: ui.value + 'px',
					BorderBottomRightRadius: ui.value + 'px'
				});
				
			}
		});
		
		hwjs('input#wrapper-border-radius').blur(function(){
			value = hwjs(this).val();
			
			if(value == ''){
				value = 0;
			}
			
			if(value > 20){
				value = 20;
			}
			
			hwjs("#wrapper-border-radius-slider").slider('option', 'value', value);
			
			hwjs('div#wrapper').css({ 
				WebkitBorderRadius: value + 'px', 
				MozBorderRadius: value + 'px', 
				BorderRadius: value + 'px' 
			});
			
			hwjs('div.header-rearrange-item-1').css({ 
				WebkitBorderTopLeftRadius: value + 'px', 
				WebkitBorderTopRightRadius: value + 'px', 
				MozBorderRadiusTopleft: value + 'px',
				MozBorderRadiusTopright: value + 'px',
				BorderTopLeftRadius: value + 'px',
				BorderTopRightRadius: value + 'px'
			});

			hwjs('div.header-rearrange-item-1 .navigation-right li:last-child a').css({ 
				WebkitBorderTopRightRadius: value + 'px', 
				MozBorderRadiusTopright: value + 'px',
				BorderTopRightRadius: value + 'px',
			});

			hwjs('div.header-rearrange-item-1 .navigation li:first-child a').css({ 
				WebkitBorderTopLeftRadius: value + 'px', 
				MozBorderRadiusTopleft: value + 'px',
				BorderTopLeftRadius: value + 'px',
			});

			hwjs('div#footer').css({ 
				WebkitBorderBottomLeftRadius: value + 'px', 
				WebkitBorderBottomRightRadius: value + 'px', 
				MozBorderRadiusBottomleft: value + 'px',
				MozBorderRadiusBottomright: value + 'px',
				BorderBottomLeftRadius: value + 'px',
				BorderBottomRightRadius: value + 'px'
			});
		});
		
		
		
		
		hwjs("div#leaf-border-radius-slider").slider({
			max: 20,
			min: 0,
			value: hwjs('input#leaf-border-radius').val(),
		   	slide: function(event, ui) {												
				hwjs('input#leaf-border-radius').val(ui.value);

				hwjs('div.headway-leaf').css({ 
					WebkitBorderRadius: ui.value + 'px', 
					MozBorderRadius: ui.value + 'px', 
					BorderRadius: ui.value + 'px' 
				});

			}
		});
		
		hwjs('input#leaf-border-radius').blur(function(){
			value = hwjs(this).val();
			
			if(value == ''){
				value = 0;
			}
			
			if(value > 20){
				value = 20;
			}
			
			hwjs("div#leaf-border-radius-slider").slider('option', 'value', value);
			
			hwjs('div.headway-leaf').css({ 
				WebkitBorderRadius: value + 'px', 
				MozBorderRadius: value + 'px', 
				BorderRadius: value + 'px' 
			});
			
		});
		
		
		
		
		hwjs("div#leaf-container-horizontal-padding-slider").slider({
			max: 70,
			min: 0,
			value: hwjs('input#leaf-container-horizontal-padding').val(),
		   	slide: function(event, ui) {
				leaf_container = hwjs('div#container, div.leafs-container');
															
				hwjs('input#leaf-container-horizontal-padding').val(ui.value);
				
				if(leaf_container.hasClass('resize-container')){
					leaf_container.css('borderLeftWidth', ui.value + 'px');
					leaf_container.css('borderRightWidth', ui.value + 'px');
				} else {
					leaf_container.css('paddingLeft', ui.value + 'px');
					leaf_container.css('paddingRight', ui.value + 'px');
				}
				
				headway_settings['leaf-container-horizontal-padding'] = ui.value;
				
				leaf_container.css('width', headway_settings['wrapper-width']-ui.value*2);
			}
		});
		
		hwjs('input#leaf-container-horizontal-padding').blur(function(){
			leaf_container = hwjs('div#container, div.leafs-container');
			
			value = hwjs(this).val();
			
			if(value == ''){
				value = 0;
			}
			
			if(value > 20){
				value = 20;
			}
						
			hwjs(this).val(value);
			
			if(leaf_container.hasClass('resize-container')){
				leaf_container.css('borderLeftWidth', value + 'px');
				leaf_container.css('borderRightWidth', value + 'px');
			} else {
				leaf_container.css('paddingLeft', value + 'px');
				leaf_container.css('paddingRight', value + 'px');
			}
			
			hwjs("div#leaf-container-horizontal-padding-slider").slider('option', 'value', value);
			
			headway_settings['leaf-container-horizontal-padding'] = value;
		});
		
		
		
		hwjs("div#leaf-container-vertical-padding-slider").slider({
			max: 70,
			min: 0,
			value: hwjs('input#leaf-container-vertical-padding').val(),
		   	slide: function(event, ui) {						
				leaf_container = hwjs('div#container, div.leafs-container');
									
				hwjs('input#leaf-container-vertical-padding').val(ui.value);
				
				if(leaf_container.hasClass('resize-container')){
					leaf_container.css('borderTopWidth', ui.value + 'px');
					leaf_container.css('borderBottomWidth', ui.value + 'px');
				} else {
					leaf_container.css('paddingTop', ui.value + 'px');
					leaf_container.css('paddingBottom', ui.value + 'px');
				}
				
				headway_settings['leaf-container-vertical-padding'] = ui.value;
			}
		});
		
		hwjs('input#leaf-container-vertical-padding').blur(function(){
			leaf_container = hwjs('div#container, div.leafs-container');
			
			value = hwjs(this).val();
			
			if(value == ''){
				value = 0;
			}
			
			if(value > 20){
				value = 20;
			}
						
			hwjs(this).val(value);
			
			if(leaf_container.hasClass('resize-container')){
				leaf_container.css('borderTopWidth', value + 'px');
				leaf_container.css('borderBottomWidth', value + 'px');
			} else {
				leaf_container.css('paddingTop', value + 'px');
				leaf_container.css('paddingBottom', value + 'px');
			}
			
			hwjs("div#leaf-container-vertical-padding-slider").slider('option', 'value', value);
			
			headway_settings['leaf-container-vertical-padding'] = value;
		});

		
		
		hwjs("div#leaf-margins-slider").slider({
			max: 15,
			min: 0,
			value: hwjs('input#leaf-margins').val(),
		   	slide: function(event, ui) {												
				hwjs('input#leaf-margins').val(ui.value);
				
				if(hwjs('div.resize').length > 0){
					hwjs('div#container div.headway-leaf, div.leafs-container div.headway-leaf').css('borderWidth', ui.value + 'px');
					hwjs('div.leafs-column div.headway-leaf').css('borderBottomWidth', (parseInt(ui.value) * 2) + 'px');
				} else {
					hwjs('div#container div.headway-leaf, div.leafs-container div.headway-leaf').css('margin', ui.value + 'px');
					hwjs('div.leafs-column div.headway-leaf').css('marginBottom', (parseInt(ui.value) * 2) + 'px');
				}
				
				headway_settings['leaf-margins'] = ui.value;
			}
		});
		
		hwjs('input#leaf-margins').blur(function(){
			value = hwjs(this).val();
			
			if(value == ''){
				value = 0;
			}
			
			if(value > 15){
				value = 15;
			}
						
			hwjs(this).val(value);
			
			hwjs('input#leaf-margins').val(value);
			
			hwjs("div#leaf-margins-slider").slider('option', 'value', value);
			
			if(hwjs('div.resize').length > 0){
				hwjs('div#container div.headway-leaf, div.leafs-container div.headway-leaf').css('borderWidth', value + 'px');
				hwjs('div.leafs-column div.headway-leaf').css('borderBottomWidth', (parseInt(value) * 2) + 'px');
			} else {
				hwjs('div#container div.headway-leaf, div.leafs-container div.headway-leaf').css('margin', value + 'px');
				hwjs('div.leafs-column div.headway-leaf').css('marginBottom', (parseInt(value) * 2) + 'px');
			}
			
			headway_settings['leaf-margins'] = value;
		});
		
		
		
		hwjs("div#leaf-padding-slider").slider({
			max: 25,
			min: 0,
			value: hwjs('input#leaf-padding').val(),
		   	slide: function(event, ui) {												
				hwjs('input#leaf-padding').val(ui.value);
				
				hwjs('div#container div.headway-leaf').css('padding', ui.value + 'px');
				hwjs('div.leafs-container div.headway-leaf div.headway-leaf-inside, div.leafs-column div.headway-leaf div.headway-leaf-inside').css('padding', ui.value + 'px');
				
				hwjs('div#columns-container div.headway-leaf div.leaf-content').each(function(){
					hwjs(this).css('width', hwjs(this).parent().width());
				});
			}
		});
		
		hwjs('input#leaf-padding').blur(function(){
			value = hwjs(this).val();
			
			if(value == ''){
				value = 0;
			}
			
			if(value > 25){
				value = 25;
			}
						
			hwjs(this).val(value);
			
			hwjs('input#leaf-padding').val(value);
			
			hwjs("div#leaf-padding-slider").slider('option', 'value', value);
			
			hwjs('div#container div.headway-leaf').css('padding', value + 'px');
			hwjs('div.leafs-container div.headway-leaf div.headway-leaf-inside, div.leafs-column div.headway-leaf div.headway-leaf-inside').css('padding', value + 'px');
			
			hwjs('div#columns-container div.headway-leaf div.leaf-content').each(function(){
				hwjs(this).css('width', hwjs(this).parent().width());
			});
		});
	/* End (Dimensions) */

	/* Wizard */
		if(hwjs('div#wizard-box').length == 1){
			
			hwjs('div#wizard-box').delegate('span.wizard-layout-selector', 'click', function(){
				hwjs(this).siblings('span.wizard-layout-selected').removeClass('wizard-layout-selected');
				hwjs(this).addClass('wizard-layout-selected');
						
				hwjs('select#wizard-layout-select').val(hwjs(this).children('img').attr('alt'));
			});
		
			hwjs('div#wizard-box a.wizard-go').click(function(){			
				next_box = hwjs(this).attr('href').replace('#', '');
			
				hwjs('div.wizard-panel').addClass('wizard-panel-hidden');
				hwjs('div#wizard-panel-' + next_box).removeClass('wizard-panel-hidden');
			
				return false;
			});
			
			var wizard_header_uploader = new qq.FileUploader({
				text: 'Upload a Header Image',
		        element: hwjs('div#wizard-header-image-upload')[0],
		        action: headway_blog_url + '/?headway-process=ve-upload&what=header',
				allowedExtensions: ['jpg', 'jpeg', 'gif', 'png'],
			    onComplete: function(id, fileName, response){
					hwjs('input#header-image-hidden').attr('value', response.filename);
					hwjs('input#show-tagline').attr('checked', false);
					
					hwjs.ajax({
					  url: headway_blog_url+'/?headway-process=analyze-image&callback=?&image='+response.filename,
					  dataType: 'json',
					  success: function(data){				
							colors = data.length;
							count = 0;
														
							hwjs('div.wizard-available-color').each(function(){	
								hwjs(this).show();							
								hwjs(this).css('backgroundColor', '#' + data[count]).text(data[count]);
								
								count++;
								
								if(count > colors) hwjs(this).hide();;
							});
							
							hwjs('span#colors-from-headway').hide();
							hwjs('span#colors-from-header-image').show();
							
							hwjs('div#wizard-uploaded-header-image').show();
					  }
					});

					headway_stop_ve_close();

					hwjs('div#wizard-header-image-upload ul.qq-upload-list li.qq-upload-success').remove();
				}
		    });

			hwjs('div#wizard-box div.wizard-panel div.color-selector').css('cursor', 'pointer').ColorPicker({
					onChange: function(hsb, hex, rgb, el){	
						hwjs(el).text(hex);
						hwjs(el).css('backgroundColor', '#'+hex);
					},
					onSubmit: function(hsb, hex, rgb, el) {
						hwjs(el).text(hex);
						hwjs(el).css('backgroundColor', '#'+hex);

						hwjs(el).ColorPickerHide();

						colorpicker_id = hwjs(el).data('colorpickerId');	
						hwjs('div#' + colorpicker_id).data('display', 'hidden');
					},
					onBeforeShow: function () {
						hwjs(this).ColorPickerSetColor(hwjs(this).text());
					}
			});

			headway_bind_color_selector_button('div#wizard-box div.wizard-panel div.color-selector');

			hwjs("div#wizard-box div.color-draggable").css('cursor', 'move').draggable({ revert: true, helper: 'clone', revertDuration: 150, opacity: 0.6, cursor: 'move' });

			hwjs('div#wizard-box div#style-generator-palette li').droppable({
				hoverClass: 'color-drop-hover',
				drop: function(event, ui) {
					color = hwjs(ui.draggable).text();

					hwjs(this).children('div.color-selector').css('backgroundColor', '#' + color).text(color).ColorPickerSetColor(hwjs(this).text());
				}
			});
		
			hwjs('a.skip-wizard').click(function(){
				hwjs('div#wizard-box, div#wizard-overlay').animate({opacity: 0}, 200, function(){
					hwjs('div#wizard-box, div#wizard-overlay').hide();		
					hwjs('div#wizard-box, div#wizard-overlay').css('opacity', 1);
					hwjs('div#wizard-box').addClass('ran-wizard');					
					
					hwjs('.wizard-warning').show();			
				});
							
				hwjs.ajax({url: headway_blog_url+'/?headway-process=skip-wizard'});
			});
			
			hwjs('a.wizard-finish').click(function(){	
				hwjs(this).after('<input type="hidden" name="wizard[ran-wizard]" value="true" class="headway-visual-editor-input" />');
							
				primary_color = hwjs('#primary-color-box').text();
				secondary_color = hwjs('#secondary-color-box').text();
				tertiary_color = hwjs('#tertiary-color-box').text();
				background_color = hwjs('#background-color-box').text();
				
				hwjs(this).addClass('wizard-finish-clicked');
				
				hwjs(this).unbind('click');
				hwjs(this).bind('click', function(){
					return false;
				});
				
				//If wizard ran for first time, move these overlays over the wizard.
				if(hwjs('div#wizard-overlay').length == 1){
					hwjs('div#visual-editor-working').css('zIndex', 15501);
					hwjs('div#ve-working-overlay').css('zIndex', 15500);					
				}
				
				hwjs('div#visual-editor-working').show().animate({'opacity':1}, 250);
				hwjs('div#ve-working-overlay').show().animate({'opacity':1}, 250, false, function(){
					if(primary_color != 'ffffff' && secondary_color != 'ffffff' && tertiary_color != 'ffffff'){
						hwjs.ajax({
							url: headway_blog_url+'/?headway-process=build-color-scheme&callback=?&primary-color='+primary_color+'&secondary-color='+secondary_color,
							dataType: 'json',
							success: function(data){								
								style = data['style'];
								colors = data['colors'];
																											
								for (var i=0;i<style.length;i++){									
									value = style[i].value.toString();
								
									if(style[i].property_type == 'sizing'){
										style[i].property_type = 'width';
								
										if(value == 'zero') value = '0';
									}
								
									style[i].property = style[i].property.replace('-width', '');
									
									if(hwjs('#'+style[i].property_type+'-'+style[i].element+'-'+style[i].property).length == 0){
										continue;
									}
									
									if(style[i].property == 'font-weight' || style[i].property == 'text-transform' || style[i].property == 'font-style' || style[i].property == 'text-decoration'){
										ve_check_function(hwjs('#'+style[i].property_type+'-'+style[i].element+'-'+style[i].property), value);
									}	
									
									if(style[i].property == 'background-transparent'){
										if(value == 'on'){
											hwjs('#'+style[i].property_type+'-'+style[i].element+'-'+style[i].property).attr('checked', true);
										} else {
											hwjs('#'+style[i].property_type+'-'+style[i].element+'-'+style[i].property).attr('checked', false);
										}
									}
									
									hwjs('#'+style[i].property_type+'-'+style[i].element+'-'+style[i].property).val(value);
								}

								hwjs('input.inherit-colors').val('on').attr('checked', true);

								hwjs('input.color-primary-preset').val(primary_color);
								hwjs('input.color-secondary-preset').val(secondary_color);
								hwjs('input.color-tertiary-preset').val(tertiary_color);

								hwjs('input#color-ul-period-navigation-space-li-space-a-color').val(colors['nav-item-color']);
								hwjs('input#color-ul-period-navigation-space-li-period-current_page_item-space-a-color').val(colors['nav-item-selected-color']);
								hwjs('input#color-ul-period-navigation-space-li-period-current_page_item-space-a-right-border').val(colors['nav-border']);
								hwjs('input#color-ul-period-navigation-space-li-space-a-right-border').val(colors['nav-border']);

								hwjs('input#color-body-background').val(background_color);

								title_font = hwjs('select#wizard-fonts-titles').val();
								content_font = hwjs('select#wizard-fonts-content').val();

								hwjs('table.fonts-inputs select.font-family').val(content_font);
								hwjs('select.title-fonts').val(title_font);

								headway_save_editor(true);
							}
						});
					} else {
						if(hwjs('p.wizard-warning').css('display') != 'block'){					
							title_font = hwjs('select#wizard-fonts-titles').val();
							content_font = hwjs('select#wizard-fonts-content').val();

							hwjs('table.fonts-inputs select.font-family').val(content_font);
							hwjs('select.title-fonts').val(title_font);
						}

						headway_save_editor(true);
					}
				});
						
			});
			
			hwjs('input#wizard-skip-layout').click(function(){
				if(hwjs(this).is(':checked')){
					hwjs('div#wizard-layout-controls').hide();
					hwjs(this).parent().css('marginBottom', 0);
					hwjs('div#wizard-box').removeClass('ran-wizard');
				} else {
					hwjs('div#wizard-layout-controls').show();
					hwjs(this).parent().css('marginBottom', '-45px');
					hwjs('div#wizard-box').addClass('ran-wizard');
				}
			})
		}
	/* End (Wizard) */
	
	
	/* Start (Linking) */
		hwjs('select#leafs-link-system-page').change(function(){
			hwjs('select#leafs-link-page').val('DELETE');
		});
		
		hwjs('select#leafs-link-page').change(function(){
			hwjs('select#leafs-link-system-page').val('DELETE');
		});
	/* End (Linking) */
	
	
	/* Start (Importing) */
		var import_leaf_template = new qq.FileUploader({
			text: 'Import Leaf Template',
	        element: hwjs('div#import-leaf-template')[0],
	        action: headway_blog_url + '/?headway-process=ve-upload&what=leaf-template',
			allowedExtensions: ['hwtpl'],
		    onComplete: function(id, fileName, response){
				fileName = escape(response.filename);

				hwjs.ajax({
				  url: headway_blog_url+'/?headway-process=import-leaf-template&callback=?&path='+fileName,
				  dataType: 'json',
				  success: function(data){				
						name = data['name'];
						id = data['id'];

						hwjs('div#template-selector').append('<div class="select-option" id="template-' + id + '"><span class="select-option-text">' + name + '</span><a href="#" class="template-select-edit select-edit">Edit</a></div>');

						hwjs('p#no-templates').hide();
						hwjs('a#load-template').show();

						headway_bind_template_select_option('div#template-' + id);

						headway_stop_ve_close();
				  }
				});
			
				hwjs('div#import-leaf-template ul.qq-upload-list li.qq-upload-success').remove();
			
			}
	    });
	/* End (Importing) */

	

	hwjs('div#floaty-box-loader').fadeOut(200, function(){
		hwjs('div.floaty-box:not(#post-meta-options, #save-box, #floaty-box-loader, #help-box)').fadeIn(200);
	});
	
	hwjs('div#ie-box').fadeIn(200);
}