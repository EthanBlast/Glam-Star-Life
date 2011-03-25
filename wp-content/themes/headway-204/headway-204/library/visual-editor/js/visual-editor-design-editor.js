function headway_register_element_click(option){		
	element_selector = option.attr('selector');
	
	// Make sure the element isn't a div.headway-leaf (leaf).  Otherwise it'll kill the controls due to hierarchy.
	if(!hwjs(element_selector).hasClass('headway-leaf')){
		hwjs(element_selector).hover(function(){
			nice_name = option.text();
			element = option.val();
			
			hwjs('div#inspector').html('<p><strong>' + nice_name + '</strong></p><p class="selector"><b>CSS Selector:</b> <code>' + element + '</code></p>');
		});
	}
	
	hwjs(element_selector).click(function(event){
		nice_name = option.text();
		element = option.val();
		
		design_widget = hwjs('div#design-editor-widget');
				
		if(!hwjs('div#inspector-container').hasClass('hidden')){			
			event.stopPropagation();

			form_name = headway_make_form_name(element);
						
			hwjs('div#colors').show();
			hwjs('.colors-inputs').hide();
			hwjs('table#colors-' + form_name).show();
			hwjs('span#colors-heading').text(nice_name);
						
			if(option.attr('fonts') == 'true'){
				hwjs('div#fonts').css('display', '');
			} else {
				hwjs('div#fonts').hide();
			}
			
			if(option.attr('colors') == 'true'){
				hwjs('div#colors').css('display', '');
			} else {
				hwjs('div#colors').hide();
			}
			
			hwjs('.fonts-inputs').hide();
			hwjs('table#fonts-' + form_name).show();
			hwjs('span#fonts-heading').text(nice_name);
			hwjs('option#option-' + form_name).attr('selected', 'selected');
		
			hwjs('p#callout').show();
			
			headway_initiate_sidebar_scroll();
		}
		
		return false;
	});
}


function headway_update_style(element, property, value){
	var element;
	var styling;
	
	styling = new Object();
	styling[property] = value;

	if(!stylesheet.update_rule(element, styling)){
		property = property.split('-');
		
		if(property.length == 1){
			property = property[0];
		} else if(property.length == 2){
			property = property[0] + property[1].substr(0, 1).toUpperCase() + property[1].substr(1);
		} else if(property.length == 3){
			property = property[0] + property[1].substr(0, 1).toUpperCase() + property[1].substr(1) + property[2].substr(0, 1).toUpperCase() + property[2].substr(1);
		}
		
		element = hwjs(element);
		element.css(property, value);
	}
}

// Bind VE Check Boxes
function ve_check_function(input, value){
	element = input.attr('selector');

	if(input.hasClass('font-weight')){
		property = 'font-weight';
		icon = 'bold';
	} else if(input.hasClass('font-variant')){
		property = 'font-variant';
		icon = 'small-caps';
	} else if(input.hasClass('font-style')){
		property = 'font-style';
		icon = 'italic';
	} else if(input.hasClass('text-decoration')){ 
		property = 'text-decoration';
		icon = 'underline';
	} else {
		return false;
	}
	
	if(value == 'normal' || value == 'none'){
		input.attr('checked', false);
				
		input.parents('tr').find('.ve-icon-' + icon).removeClass('ve-icon-depressed');
	} else {
		input.attr('checked', true);
		
		input.parents('tr').find('.ve-icon-' + icon).addClass('ve-icon-depressed');
	}

	headway_update_style(element, property, value);

	headway_stop_ve_close();
}

function get_color_property(element){
	if(element.hasClass('color')) property = 'color';
	if(element.hasClass('background')) property = 'background';
	if(element.hasClass('border')) property = 'border-color';
	if(element.hasClass('top-border')) property = 'border-top-color';
	if(element.hasClass('right-border')) property = 'border-right-color';
	if(element.hasClass('bottom-border')) property = 'border-bottom-color';
	if(element.hasClass('left-border')) property = 'border-left-color';
	
	return property;
}

function purge_name_junk(element){
	return element.replace('width-', '')
		      .replace('-inherit-colors', '')
	  		  .replace('color-picker-', '')
			  .replace('color-', '')
			  .replace('-bottom-border', '')
			  .replace('-left-border', '')
			  .replace('-top-border', '')
			  .replace('-right-border', '')
			  .replace('-border', '')
			  .replace('-color', '')
			  .replace('-background-transparent', '')
			  .replace('-background', '')
			  .replace('font-', '')
			  .replace('-font-family', '')
			  .replace('-font-size', '')
			  .replace('-line-height', '')
			  .replace('-letter-spacing', '')
			  .replace('-text-transform', '')
			  .replace('-font-weight', '')
		      .replace('-font-style', '')
			  .replace('-font-variant', '');
}


function headway_bind_style_select_option(element){
	hwjs(element).click(function(){
		hwjs(this).siblings('div.selected-option').removeClass('selected-option');
		hwjs(this).addClass('selected-option');
		
		hwjs('a#load-style').show();
	});
	
	hwjs(element).children('.style-select-edit').click(function(){		
		headway_edit_style(hwjs(this).parent());
	});
}


function headway_edit_style(option){
	option_text = option.children('span.select-option-text');
	rename_input = hwjs('input#rename-style');
	rename_input.val(option_text.text());
	
	hwjs('input#style-settings-style-id').val(option.attr('id'));
	hwjs('input#style-settings-style-name').val(option_text.text());
	
	headway_open_box('edit-style');
}




function headway_design_editor() {
	stylesheet = new ITStylesheet({href: headway_settings['headway-css-url']}, 'find');
	
	headway_bind_style_select_option('div.headway-custom-select div.select-option');
	
	hwjs('a#save-style').click(function(){
		headway_open_box('save-style');
	});

	// Bind Font Select
	hwjs('select#element-selector').change(function(){	
	
			english_name = hwjs(this).find(':selected').text();
			id = hwjs(this).find(':selected').val();
			nice_id = headway_make_form_name(id);
		
			hwjs('div#colors').show();
			hwjs('.colors-inputs').hide();
			hwjs('table#colors-' + nice_id).show();
			hwjs('span#colors-heading').text(english_name);

			if(hwjs('option#option-'+nice_id).attr('fonts') == 'true'){
				hwjs('div#fonts').css('display', '');
			} else {
				hwjs('div#fonts').hide();
			}
			
			if(hwjs('option#option-'+nice_id).attr('colors') == 'true'){
				hwjs('div#colors').css('display', '');
			} else {
				hwjs('div#colors').hide();
			}

			hwjs('.fonts-inputs').hide();
			hwjs('table#fonts-' + nice_id).show();
			hwjs('span#fonts-heading').text(english_name);

			hwjs('p#callout').show();
		
			headway_initiate_sidebar_scroll();

	});

	// Bind Callout Button
	hwjs('p#callout a').click(function(){
		hwjs(hwjs('select#element-selector :selected').val()).highlightFade({speed: 1000});
		return false;
	});

	// Go through element select box to bind actions.
	if(headway_settings['use-inspector']){
		hwjs('select#element-selector option:not(#element-selector-blank)').each(function(){
			if(hwjs(this).attr('noclick') == 'false'){
				headway_register_element_click(hwjs(this));
			}
		});
	}


	// Bind actions to color boxes. 
	hwjs('div#de-tab').delegate('input.color-text', 'blur', function(){
		//Get the ID only
		element = hwjs(this).attr('selector');
		element_form_id = purge_name_junk(hwjs(this).attr('id'));
		
		//Figure Out Which Property
		property = get_color_property(hwjs(this));
		
		//If inherit colors is checked, do not change colors	
		if(hwjs('#color-' + element_form_id + '-inherit-colors').is(':checked'))
			return false;
				
		colorpicker = hwjs(this).attr('id').replace('color-', 'color-picker-');
		
		if(property == 'background'){
			hwjs('input#' + hwjs(this).attr('id').replace('-picker', '') + '-transparent').attr('checked', false);
		}
		
		hwjs('div#'+colorpicker).ColorPickerSetColor(hwjs(this).val());
		hwjs('div#'+colorpicker).children('.color-picker-color').css('background', '#'+hwjs(this).val());
	
		hwjs(this).val(hwjs(this).val());
	
		headway_update_style(element, property, '#' + hwjs(this).val());
	});

	hwjs('table.colors-inputs div.color-picker:not(.no-color-picker)').ColorPicker({
			onChange: function(hsb, hex, rgb, el){	
				color_input = hwjs('input#' + hwjs(el).attr('id').replace('-picker', ''));				
				element = color_input.attr('selector');

				property = get_color_property(color_input);
				color_input.val(hex);
				
				if(property == 'background'){
					hwjs('input#' + hwjs(el).attr('id').replace('-picker', '') + '-transparent').attr('checked', false);
				}
								
				headway_update_style(element, property, '#' + hex)
				
				hwjs(el).children('.color-picker-color').css('background-color', '#'+hex);
				
				headway_stop_ve_close();
			},
			onSubmit: function(hsb, hex, rgb, el){
				color_input = hwjs('input#' + hwjs(el).attr('id').replace('-picker', ''));				
				element = color_input.attr('selector');

				property = get_color_property(color_input);
				color_input.val(hex);
			
				headway_update_style(element, property, '#' + hex);
				
				hwjs(el).children('.color-picker-color').css('background-color', '#'+hex);
				
				hwjs(el).ColorPickerHide();
				
				colorpicker_id = hwjs(el).data('colorpickerId');	
				hwjs('div#' + colorpicker_id).data('display', 'hidden');	
				
				headway_stop_ve_close();			
			},
			onShow: function(colpkr){	
				siblings = hwjs(this).parents('tr').siblings('tr');
				
				siblings.css('opacity', .5);
							
				return false;
			},
			onHide: function(colpkr){
				siblings.css('opacity', 1);
				
				siblings = false;
			},
			onBeforeShow: function(){				
				hwjs(this).ColorPickerSetColor(hwjs('input#' + hwjs(this).attr('id').replace('-picker', '')).val());
			}, 
	});
	
	headway_bind_color_selector_button('table.colors-inputs div.color-picker:not(.no-color-picker)');
	
	
	//Bind Leaf Columns Stuff
	hwjs('div#color-picker-leaf-columns-border-color').ColorPicker({
			onChange: function(hsb, hex, rgb, el){	
				color_input = hwjs('input#leaf-columns-border-color');				

				color_input.val(hex);
			
				hwjs('div.leafs-column').css('borderRightColor', '#'+hex);
				hwjs('div#top-container').css('borderBottomColor', '#'+hex);				
				hwjs('div#bottom-container').css('borderTopColor', '#'+hex);				
								
				hwjs(el).children('.color-picker-color').css('background-color', '#'+hex);
				
				headway_stop_ve_close();
			},
			onSubmit: function(hsb, hex, rgb, el) {
				color_input = hwjs('input#leaf-columns-border-color');				

				color_input.val(hex);
			
				hwjs('div.leafs-column').css('borderRightColor', '#'+hex);
				hwjs('div#top-container').css('borderBottomColor', '#'+hex);				
				hwjs('div#bottom-container').css('borderTopColor', '#'+hex);

				hwjs(el).children('.color-picker-color').css('background-color', '#'+hex);
				
				hwjs(el).ColorPickerHide();
				
				colorpicker_id = hwjs(el).data('colorpickerId');	
				hwjs('div#' + colorpicker_id).data('display', 'hidden');
				
				headway_stop_ve_close();				
			},
			onShow: function(colpkr){	
				siblings = hwjs(this).parents('tr').siblings('tr');
				
				siblings.css('opacity', .5);
							
				return false;
			},
			onHide: function(colpkr){
				siblings.css('opacity', 1);
				
				siblings = false;
			},
			onBeforeShow: function () {				
				hwjs(this).ColorPickerSetColor(hwjs('input#leaf-columns-border-color').val());
			}
	});
	headway_bind_color_selector_button('div#color-picker-leaf-columns-border-color');
	
	
	hwjs('select#leaf-columns-border-style').change(function(){
		if(hwjs(this).val() == 'double'){
			hwjs('div.leafs-column:not(.last-leafs-column)').css('borderRightStyle', 'double');
			hwjs('div.leafs-column:not(.last-leafs-column)').css('borderRightWidth', 3);
			hwjs('div.leafs-column:not(.last-leafs-column)').css('paddingRight', 7);
			
			hwjs('div#top-container').css('borderBottomStyle', 'double');
			hwjs('div#top-container').css('borderBottomWidth', 3);
			hwjs('div#top-container').css('paddingBottom', 13);
			
			hwjs('div#bottom-container').css('borderTopStyle', 'double');
			hwjs('div#bottom-container').css('borderTopWidth', 3);
			hwjs('div#bottom-container').css('paddingTop', 13);
		} else if(hwjs(this).val() == 'no-border'){
			hwjs('div.leafs-column:not(.last-leafs-column)').css('borderRightStyle', 'none');
			hwjs('div.leafs-column:not(.last-leafs-column)').css('borderRightWidth', 0);
			hwjs('div.leafs-column:not(.last-leafs-column)').css('paddingRight', 10);

			hwjs('div#top-container').css('borderBottomStyle', 'none');
			hwjs('div#top-container').css('borderBottomWidth', 0);
			hwjs('div#top-container').css('paddingBottom', 10);

			hwjs('div#bottom-container').css('borderTopStyle', 'none');
			hwjs('div#bottom-container').css('borderTopWidth', 0);
			hwjs('div#bottom-container').css('paddingTop', 10);
		} else {
			hwjs('div.leafs-column:not(.last-leafs-column)').css('borderRightWidth', 1);
			hwjs('div.leafs-column:not(.last-leafs-column)').css('paddingRight', 9);
			hwjs('div.leafs-column:not(.last-leafs-column)').css('borderRightStyle', hwjs(this).val());
			
			hwjs('div#top-container').css('borderBottomStyle', hwjs(this).val());
			hwjs('div#top-container').css('borderBottomWidth', 1);
			hwjs('div#top-container').css('paddingBottom', 15);
			
			hwjs('div#bottom-container').css('borderTopStyle', hwjs(this).val());
			hwjs('div#bottom-container').css('borderTopWidth', 1);
			hwjs('div#bottom-container').css('paddingTop', 15);
		}
	});
	

	// Bind action to border inputs.
	hwjs('div#de-tab').delegate('input.border-width', 'blur', function(){
		element = hwjs(this).attr('selector');
	
		if(hwjs(this).hasClass('border')) property = 'border-width';
		if(hwjs(this).hasClass('top-border')) property = 'border-top-width';
		if(hwjs(this).hasClass('right-border')) property = 'border-right-width';
		if(hwjs(this).hasClass('bottom-border')) property = 'border-bottom-width';
		if(hwjs(this).hasClass('left-border')) property = 'border-left-width';
		
		headway_update_style(element, property, hwjs(this).val() + 'px');
	
		headway_stop_ve_close();
	});


	// Bind selects
	hwjs('div#de-tab').delegate('select.font-select', 'change', function(){	
		element = hwjs(this).attr('selector');
	
		if(hwjs(this).hasClass('font-family')) property = 'font-family';
		if(hwjs(this).hasClass('font-size')) property = 'font-size';
		if(hwjs(this).hasClass('line-height')) property = 'line-height';
		if(hwjs(this).hasClass('letter-spacing')) property = 'letter-spacing';
		if(hwjs(this).hasClass('text-transform')) property = 'text-transform';
		if(hwjs(this).hasClass('text-align')) property = 'text-align';

	
		if(property == 'font-size'){
			value = hwjs(this).val() + 'px';
		} else if(property == 'line-height'){
			value = hwjs(this).val() + '%';
		} else {
			value = hwjs(this).val();
		}
	
		headway_update_style(element, property, value);
	
		headway_stop_ve_close();
	});


	hwjs('div#de-tab').delegate('input.font-check', 'click', function(){
		element = hwjs(this).attr('selector');
	
		if(hwjs(this).hasClass('font-weight')) property = 'font-weight';
		if(hwjs(this).hasClass('font-variant')) property = 'font-variant';
		if(hwjs(this).hasClass('font-style')) property = 'font-style';
		if(hwjs(this).hasClass('text-decoration')) property = 'text-decoration';
		
		if(property == 'text-decoration'){
			default_value = 'none';
		} else {
			default_value = 'normal';
		}
		
		if(hwjs(this).is(":checked")){
			value = hwjs(this).val();
		} else {     
			value = default_value;
		}
		
		headway_update_style(element, property, value);
	
		headway_stop_ve_close();
		
		return false;
	});


	//Bind Transparency Check boxes
	hwjs('div#de-tab').delegate('input.background-transparent', 'click', function(){
		element_form_id = purge_name_junk(hwjs(this).attr('id'));
		element = hwjs(this).attr('selector');
	
		if(hwjs(this).is(":checked")){
			value = 'transparent';
		} else {     
			value = '#'+hwjs('input#color-'+element_form_id+'-background').val();
		}	
		
		headway_update_style(element, 'background', value);

		headway_stop_ve_close();
	});

	function background_transparency_check_function(input, value){
		element_form_id = purge_name_junk(input.attr('id'));
		element = input.attr('selector');	
		
		//If inherit colors is checked, do not do anything	
		if(hwjs('#color-' + element_form_id + '-inherit-colors').is(':checked'))
			return false;
				
		if(value == 'on'){
			value = 'transparent';
			
			input.attr('checked', true);
		} else {     
			value = '#'+hwjs('input#color-'+element_form_id+'-background').val();
			
			input.attr('checked', false);
		}	
		
		headway_update_style(element, 'background', value);
		
		headway_stop_ve_close();
	}
	
	
	//Bind Transparency Check boxes
	hwjs('div#de-tab').delegate('input.inherit-colors', 'click', function(){
		element_form_id = purge_name_junk(hwjs(this).attr('id'));
		element = hwjs(this).attr('selector');
	
		if(hwjs(this).is(":checked")){
			color_value = null;
			background_color = null;
			
			hwjs(this).parent().parent().siblings('tr').hide();
		} else {     
			color_value = '#'+hwjs('input#color-'+element_form_id+'-color').val();
			background_value = '#'+hwjs('input#color-'+element_form_id+'-background').val();
			
			hwjs(this).parent().parent().siblings('tr').show();
		}	
		
		headway_update_style(element, 'color', color_value);
		headway_update_style(element, 'background', background_value);

		headway_stop_ve_close();
	});

	function inherit_colors_check_function(input, value){
		element_form_id = purge_name_junk(input.attr('id'));
		element = input.attr('selector');
			
		if(value == 'on'){
			input.attr('checked', true);
			
			input.parent().parent().siblings('tr').hide();
			
			color_value = null;
			background_value = null;

			headway_update_style(element, 'color', color_value);
			headway_update_style(element, 'background', background_value);
		} else {    
			input.attr('checked', false);
			
			input.parent().parent().siblings('tr').show();
		}
		
		headway_stop_ve_close();
	}


	// Bind Mass Font Select
	hwjs('select#mass-font-select-titles').change(function(){
		mass_value = hwjs(this).val();
	
		hwjs('select.title-fonts:not(.mass-font-change)').each(function(){
			hwjs(this).val(mass_value).attr('selected', true);
		
			element = hwjs(this).attr('selector');
			
			headway_update_style(element, 'font-family', hwjs(this).val());
		});
	
		headway_stop_ve_close();
	});
	
	hwjs('select#mass-font-select-content').change(function(){
		mass_value = hwjs(this).val();
	
		hwjs('select.font-family:not(.mass-font-change, .title-fonts)').each(function(){
			hwjs(this).val(mass_value).attr('selected', true);
		
			var element = hwjs(this).attr('selector');
			
			headway_update_style(element, 'font-family', hwjs(this).val());
		});
	
		headway_stop_ve_close();
	});
	/////////////////////////
	
	
	hwjs('a#save-style-submit').click(function(){
		style_name = hwjs('input#style-name').val();
		
		color_primary = hwjs('input#color--period-header-link-text-inside-color').val();
		color_secondary = hwjs('input#color-div-pound-navigation-background').val();
		color_tertiary = hwjs('input#color--pound-tagline-color').val();
		
		design_data = hwjs('.headway-visual-editor-color-input, .headway-visual-editor-font-input, .headway-visual-editor-border-input').serialize();
		
		hwjs.post( 
			headway_blog_url+'/?headway-process=save-style&style-name=' + escape(style_name) + '&color-primary=' + escape(color_primary) + '&color-secondary=' + escape(color_secondary) + '&color-tertiary=' + escape(color_tertiary), 
			design_data,
			function(response){
				style_id = response;
								
				hwjs('div#style-selector').append('<div class="select-option" id="style-' + style_id + '"><span class="select-option-text">' + style_name + '</span><div class="color-preview" style="background: #' + color_tertiary + ';"></div><div class="color-preview" style="background: #' + color_secondary + ';"></div><div class="color-preview" style="background: #' + color_primary + ';"></div><a href="#" class="style-select-edit select-edit">Edit</a></div>');
				
				hwjs('p#no-styles').hide();
				hwjs('a#load-style').show();
				
				hwjs('input#style-name').val('Style Name');
				
				headway_bind_style_select_option('div#style-' + style_id);
				
				headway_close_box('save-style');
			}
		);
	});
	
	
	hwjs('a#export-style-button').click(function(){		
		style_name = hwjs('select#export-style-selector').find(':selected').text();
		style_id = hwjs('select#export-style-selector').find(':selected').val().replace('style-', '');
		
		url = headway_blog_url + '/?headway-process=export-style&style-id='+escape(style_name + '-' + style_id) + '&style-name='+escape(style_name);
				
		window.open(url);	
		
		headway_close_box('export-window');	
				
		return false;
	});
	
	hwjs('a#import-style-box-button').click(function(){
		headway_open_box('style-import');
	});
	
	
	var import_style = new qq.FileUploader({
		text: 'Import Style',
        element: hwjs('div#import-style')[0],
        action: headway_blog_url + '/?headway-process=ve-upload&what=style',
		allowedExtensions: ['hwstyle', 'txt'],
	    onComplete: function(id, fileName, response){
			fileName = escape(response.filename);
							
			hwjs.ajax({
			  url: headway_blog_url+'/?headway-process=import-style&callback=?&path='+fileName,
			  dataType: 'json',
			  success: function(data){				
					style_name = data['style-name'];
					
					if(data['color-primary']){
						color_primary = data['color-primary'];
						color_secondary = data['color-secondary'];
						color_tertiary = data['color-tertiary'];
						
						colors = '<div class="color-preview" style="background: #' + color_tertiary + ';"></div><div class="color-preview" style="background: #' + color_secondary + ';"></div><div class="color-preview" style="background: #' + color_primary + ';"></div>';
					} else {
						colors = '';
					}
					
					style_id = data['style-id'];

					hwjs('div#style-selector').append('<div class="select-option" id="style-' + style_id + '"><span class="select-option-text">' + style_name + '</span>' + colors + '<a href="#" class="style-select-edit select-edit">Edit</a></div>');
					
					hwjs('p#no-styles').hide();
					hwjs('a#load-style').show();
					
					headway_bind_style_select_option('div#style-' + style_id);
					
					headway_stop_ve_close();
				  }
			});
			
			hwjs('div#import-style ul.qq-upload-list li.qq-upload-success').remove();
			
		}
    });

	
	hwjs('a#load-style').click(function(){
		if(confirm('Are you sure?  Loading a style will allow you to see the style, but the new style will NOT be applied unless you click the Save All Changes button.') === false) return false;
		
		style_name = hwjs('div#style-selector .selected-option span.select-option-text').text();
		style_id = hwjs('div#style-selector .selected-option').attr('id').replace('style-', '');
		
		hwjs('div#visual-editor-working').show().animate({'opacity':1}, 250);
		hwjs('div#ve-working-overlay').show().animate({'opacity':1}, 250, false, function(){
			hwjs.ajax({
			  url: headway_blog_url+'/?headway-process=load-style&callback=?&style-name='+escape(style_name + '-' + style_id),
			  dataType: 'json',
			  success: function(data){
					if(typeof data['styles'] == 'undefined'){
						style = data;
					} else {
						style = data['styles'];
					}

					line_height_percentage_calc = false;
					line_height_original = new Array();
					
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

						if(style[i].property == 'line-height' && value < 50){
							line_height_percentage_calc = true;
							
							line_height_original[style[i].element] = value;
						}
							
						if(style[i].property == 'font-weight' || style[i].property == 'font-style' || style[i].property == 'text-decoration' || style[i].property == 'font-variant'){
							ve_check_function(hwjs('#'+style[i].property_type+'-'+style[i].element+'-'+style[i].property), value);
							
							continue;
						} else if(style[i].property == 'background-transparent'){
							background_transparency_check_function(hwjs('#'+style[i].property_type+'-'+style[i].element+'-'+style[i].property), value);
							
							continue;
						} else if(style[i].property == 'inherit-colors'){
							inherit_colors_check_function(hwjs('#'+style[i].property_type+'-'+style[i].element+'-'+style[i].property), value);
							
							continue;
						}
						
						if(hwjs('#'+style[i].property_type+'-'+style[i].element+'-'+style[i].property).length > 0){
							input = hwjs('#'+style[i].property_type+'-'+style[i].element+'-'+style[i].property);
							
							input.val(value).trigger('blur').trigger('change');
														
							if(style[i].property == 'text-transform'){
								input.parents('tr').find('span.ve-icon-uppercase, span.ve-icon-lowercase').removeClass('ve-icon-depressed');

								if(input.val() == 'lowercase'){
									input.parents('tr').find('span.ve-icon-lowercase').addClass('ve-icon-depressed');
								} else if(input.val() == 'uppercase'){
									input.parents('tr').find('span.ve-icon-uppercase').addClass('ve-icon-depressed');
								} else {
									input.parents('tr').find('span.ve-icon-uppercase, span.ve-icon-lowercase').removeClass('ve-icon-depressed');
								}
							}
							
							if(style[i].property == 'text-align'){								
								input.parents('tr').find('span.ve-icon-align-toggle').removeClass('ve-icon-depressed');

								if(input.val() == 'left'){
									input.parents('tr').find('span.ve-icon-align-left').addClass('ve-icon-depressed');
								} else if(input.val() == 'center'){
									input.parents('tr').find('span.ve-icon-align-center').addClass('ve-icon-depressed');
								} else if(input.val() == 'right'){
									input.parents('tr').find('span.ve-icon-align-right').addClass('ve-icon-depressed');
								}
							}
						}
					}
					
					if(line_height_percentage_calc){
						hwjs('select.line-height').each(function(){
							this_id = hwjs(this).attr('id');
							font_size_select = this_id.replace('-line-height', '-font-size');
							element = this_id.replace('-line-height', '').replace('font-', '');
						
							font_size = hwjs('select#' + font_size_select).val();

							hwjs(this).val(Math.round((Math.round((line_height_original[element]/font_size)*10)/10)*100)).trigger('change');
						});
					}

					hwjs('div#visual-editor-working, div#ve-working-overlay').animate({'opacity':0}, 250, false, function(){ 
						hwjs('div#visual-editor-working, div#ve-working-overlay').hide();
					});
					
					headway_stop_ve_close();
			  }
			});
		});
	});
	
	
	hwjs('a#save-style-settings-button').click(function(){
		style_id = hwjs('input#style-settings-style-id').val();
		style_name = hwjs('input#style-settings-style-name').val();
		rename_input = hwjs('input#rename-style');
		
		hwjs.ajax({
			url: headway_blog_url+'/?headway-process=rename-style&style-id=' + style_id + '&style-name=' + style_name + '&style-new-name=' + rename_input.val(),
			async: false,
			success: function(data){	
				option_text.text(rename_input.val());

				headway_close_box('edit-style');
			}
		});

		return false;
	});

	hwjs('a#delete-style-button').click(function(){
		style_id = hwjs('input#style-settings-style-id').val();
		style_name = hwjs('input#style-settings-style-name').val();

		if(confirm('Are you sure?') === true){			
			hwjs.ajax({
				url: headway_blog_url+'/?headway-process=delete-style&style-id=' + style_id + '&style-name=' + style_name,
				async: false,
				success: function(data){	
					hwjs('div#'+style_id).remove();

				    headway_close_box('edit-style');

					if(hwjs('div#style-selector div.select-option').length == 0){
						hwjs('p#no-styles').show();
						hwjs('a#load-style').hide();
					}
				}
			});
		}

		return false;
	});
	
	
	hwjs('span.ve-icon-style-toggle').click(function(){
		hwjs(this).toggleClass('ve-icon-depressed');
		
		if(hwjs(this).hasClass('ve-icon-bold')){
			if(hwjs(this).hasClass('ve-icon-depressed')){
				hwjs('input#font-' + hwjs(this).attr('element') + '-font-weight').attr('checked', true).trigger('click');
			} else {
				hwjs('input#font-' + hwjs(this).attr('element') + '-font-weight').attr('checked', false).trigger('click');
			}		
		}
		
		if(hwjs(this).hasClass('ve-icon-italic')){
			if(hwjs(this).hasClass('ve-icon-depressed')){
				hwjs('input#font-' + hwjs(this).attr('element') + '-font-style').attr('checked', true).trigger('click');
			} else {
				hwjs('input#font-' + hwjs(this).attr('element') + '-font-style').attr('checked', false).trigger('click');
			}		
		}
		
		if(hwjs(this).hasClass('ve-icon-underline')){
			if(hwjs(this).hasClass('ve-icon-depressed')){
				hwjs('input#font-' + hwjs(this).attr('element') + '-text-decoration').attr('checked', true).trigger('click');
			} else {
				hwjs('input#font-' + hwjs(this).attr('element') + '-text-decoration').attr('checked', false).trigger('click');
			}		
		}
		
		if(hwjs(this).hasClass('ve-icon-small-caps')){
			if(hwjs(this).hasClass('ve-icon-depressed')){
				hwjs('input#font-' + hwjs(this).attr('element') + '-font-variant').attr('checked', true).trigger('click');
				hwjs('select#font-' + hwjs(this).attr('element') + '-text-transform').val('none').trigger('change');
				hwjs(this).siblings('.ve-icon-lowercase, .ve-icon-uppercase').removeClass('ve-icon-depressed');
			} else {
				hwjs('input#font-' + hwjs(this).attr('element') + '-font-variant').attr('checked', false).trigger('click');
			}
		}
		
		if(hwjs(this).hasClass('ve-icon-uppercase')){
			if(hwjs(this).hasClass('ve-icon-depressed')){
				hwjs('select#font-' + hwjs(this).attr('element') + '-text-transform').val('uppercase').trigger('change');
				hwjs('input#font-' + hwjs(this).attr('element') + '-font-variant').attr('checked', false).trigger('click');
				hwjs(this).siblings('.ve-icon-lowercase, .ve-icon-small-caps').removeClass('ve-icon-depressed');
			} else {
				hwjs('select#font-' + hwjs(this).attr('element') + '-text-transform').val('none').trigger('change');
			}
		}

		if(hwjs(this).hasClass('ve-icon-lowercase')){
			if(hwjs(this).hasClass('ve-icon-depressed')){
				hwjs('select#font-' + hwjs(this).attr('element') + '-text-transform').val('lowercase').trigger('change');
				hwjs('input#font-' + hwjs(this).attr('element') + '-font-variant').attr('checked', false).trigger('click');
				hwjs(this).siblings('.ve-icon-uppercase, .ve-icon-small-caps').removeClass('ve-icon-depressed');
			} else {
				hwjs('select#font-' + hwjs(this).attr('element') + '-text-transform').val('none').trigger('change');
			}
		}
	});
	
	hwjs('span.ve-icon-align-toggle').click(function(){
		hwjs('span.ve-icon-align-toggle').removeClass('ve-icon-depressed');
		hwjs(this).addClass('ve-icon-depressed');
		
		if(hwjs(this).hasClass('ve-icon-align-left')){
			hwjs('select#font-' + hwjs(this).attr('element') + '-text-align').val('left').trigger('change');
		}
		
		if(hwjs(this).hasClass('ve-icon-align-center')){
			hwjs('select#font-' + hwjs(this).attr('element') + '-text-align').val('center').trigger('change');
		}
		
		if(hwjs(this).hasClass('ve-icon-align-right')){
			hwjs('select#font-' + hwjs(this).attr('element') + '-text-align').val('right').trigger('change');
		}
	});
	
}