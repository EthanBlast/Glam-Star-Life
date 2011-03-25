function ready_leafs(leaf){
	leaf_nonobject = leaf;
	
	hwjs(leaf_nonobject).css('position', 'relative');
	hwjs(leaf_nonobject).children('.headway-leaf-inside').prepend('<div class="leaf-icon-container"><a href="" title="This is the ID of the leaf, which is used for duplicate sidebars and advanced coding." class="ve-tooltip leaf-id"></a><div class="leaf-icon"></div></div>');
	hwjs(leaf_nonobject).children('.headway-leaf-inside').prepend('<div class="leaf-control"><a class="leaf-height leaf-height-fluid ve-tooltip" href="" title="Toggle fluid height of leaf.">Fluid Height</a><a class="leaf-alignment-toggle ve-tooltip" href="" title="Toggle align to left, right, or tell the leaf to start a new row.">Align/New Row</a><a class="edit ve-tooltip" href="" title="Open the options for this leaf.">Edit</a><a class="delete ve-tooltip" href="" title="Delete this leaf.">Delete</a></div><a href="#" class="precise-resize">Precise Resize</a>');

	hwjs(leaf_nonobject).each(function(){
		leaf = hwjs(this);
		leaf_id = leaf.attr('id').replace('leaf-', '');
		leaf_full_id = leaf.attr('id');
		leaf_inside = leaf.children('.headway-leaf-inside');
				
		leaf_type = leaf.attr('class').split(' ');
		leaf_type = leaf_type[0];

		leaf_icon = hwjs('a#add-'+leaf_type).siblings('img').attr('src');
		
		//If leaf type doesn't exist, show an X to show there's an error.
		if(typeof leaf_icon == 'undefined'){
			leaf_icon = headway_settings['template-directory']+'/library/visual-editor/icons/question_mark.png';
			
			hwjs('div#'+leaf_full_id+' .leaf-control a:not(.delete)').css({ 'position':'absolute', 'top':'-9999px', 'left':'-9999px'});
			hwjs('div#'+leaf_full_id+' .leaf-control').css('width', '16px');
		}
		
		hwjs('div#'+leaf_full_id+' .leaf-icon').html('<img src="' + leaf_icon + '" width="16px" height="16px" />');

		
		hwjs('div#'+leaf_full_id+' .leaf-id').text(leaf_id).click(function(){ return false; });
		
		
		url = headway_blog_url+'/?headway-trigger=process&process=leaf-sizes&callback=?';
		postData = {'page-id':hwjs('input#current-page').val()};
		
		hwjs('div#'+leaf_full_id+' .ve-tooltip').tooltip({track: true, delay: 0, showURL: false, fade: 250, id: 've-tooltip-bubble'});
		
		if(typeof sizing == 'undefined'){
			hwjs.ajax({
			  type: 'POST',
			  url: url,
			  data: postData,
			  async: false,
			  dataType: 'json',
			  success: function(data){
			    sizing = data;
			  }
			});	
		}
				
		size = new Object;
	
		if(typeof sizing[leaf_id] == 'undefined' || sizing[leaf_id]['new-leaf'] == true){
			size['width'] = Math.round(parseInt(leaf.width()));
			size['height'] = Math.round(parseInt(leaf.css('height').replace('px', '')));
		} else {
			size['width'] = Math.round(parseInt(sizing[leaf_id]['width']));
			size['height'] = Math.round(parseInt(sizing[leaf_id]['height']));
		}
				
		leaf_inside.append('\
		<div class="leaf-dimensions">\
			<div class="dimension dimension-width">\
				<label for="' + leaf_full_id + '_width">Width</label>\
				<input type="text" id="' + leaf_full_id + '_width" name="dimensions[' + leaf_full_id + '][width]" value="' + size['width'] + '" class="width-input headway-visual-editor-input" />\
				<span>px</span>\
			</div>\
			<div class="dimension dimension-height">\
				<label for="' + leaf_full_id + '_height">Height</label>\
				<input type="hidden" id="' + leaf_full_id + '_height_changed" name="dimensions[' + leaf_full_id + '][height-changed]" value="false" class="headway-visual-editor-input" />\
				<input type="text" id="' + leaf_full_id + '_height" name="dimensions[' + leaf_full_id + '][height]" value="' + size['height'] + '" class="height-input headway-visual-editor-input" />\
				<span>px</span>\
			</div>\
		</div>');
		
		leaf_inside.append('\
		<div class="leaf-alignment">\
			<label for="' + leaf_full_id + '_alignment">Alignment</label>\
			<select name="" id="' + leaf_full_id + '_alignment">\
				<option value="left">Left</option>\
				<option value="right">Right</option>\
			</select>\
			\
			<label for="' + leaf_full_id + '_clear">Leaf Wrapping</label>\
			<select name="" id="' + leaf_full_id + '_clear">\
				<option value="none">None</option>\
				<option value="both">Force New Row</option>\
				<option value="same">Same Side as Alignment</option>\
			</select>\
		</div>');
		
		function alignment_select_function(leaf_id, value){		
			leaf_full_id = 'leaf-'+leaf_id;
							
			if(value == 'left'){
				hwjs('div#' + leaf_full_id).addClass('headway-leaf-left');
				hwjs('div#' + leaf_full_id).removeClass('headway-leaf-right');
				
				if(hwjs('div#' + leaf_full_id).hasClass('headway-leaf-clear-right')){
					hwjs('div#' + leaf_full_id).addClass('headway-leaf-clear-left');
					hwjs('div#' + leaf_full_id).removeClass('headway-leaf-clear-right');
				}
				
				hwjs('input#' + leaf_full_id + '_align').val('left');
			}
			
			if(value == 'right'){
				hwjs('div#' + leaf_full_id).addClass('headway-leaf-right');
				hwjs('div#' + leaf_full_id).removeClass('headway-leaf-left');
				
				if(hwjs('div#' + leaf_full_id).hasClass('headway-leaf-clear-left')){
					hwjs('div#' + leaf_full_id).addClass('headway-leaf-clear-right');
					hwjs('div#' + leaf_full_id).removeClass('headway-leaf-clear-left');
				}
				
				hwjs('input#' + leaf_full_id + '_align').val('right');
			}
		}
		
		hwjs('select#' + leaf_full_id + '_alignment').change(function(){
			leaf_id = hwjs(this).attr('id').replace('leaf-', '').replace('_alignment', '');
			
			alignment_select_function(leaf_id, hwjs(this).val());
		});
		
		hwjs('select#' + hwjs(this).attr('id') + '_alignment').blur(function(){
			leaf_id = hwjs(this).attr('id').replace('leaf-', '').replace('_alignment', '');
			
			alignment_select_function(leaf_id, hwjs(this).val());
		});		
		
		function clear_select_function(leaf_id, value){	
			leaf_full_id = 'leaf-' + leaf_id;
											
			if(value == 'none'){
				hwjs('div#' + leaf_full_id).removeClass('headway-leaf-clear-left');
				hwjs('div#' + leaf_full_id).removeClass('headway-leaf-clear-right');
				hwjs('div#' + leaf_full_id).removeClass('headway-leaf-clear-both');
				
				hwjs('input#' + leaf_full_id + '_clear').val('none');
			}
			
			if(value == 'same'){
				if(hwjs('div#' + leaf_full_id).hasClass('headway-leaf-right')){
					hwjs('div#' + leaf_full_id).addClass('headway-leaf-clear-right');
					hwjs('div#' + leaf_full_id).removeClass('headway-leaf-clear-left');
					hwjs('div#' + leaf_full_id).removeClass('headway-leaf-clear-both');
				} else {
					hwjs('div#' + leaf_full_id).addClass('headway-leaf-clear-left');
					hwjs('div#' + leaf_full_id).removeClass('headway-leaf-clear-right');
					hwjs('div#' + leaf_full_id).removeClass('headway-leaf-clear-both');
				}
			
				
				hwjs('input#' + leaf_full_id + '_clear').val('left');
			}
			
			
			if(value == 'both'){
				hwjs('div#' + leaf_full_id).addClass('headway-leaf-clear-both');
				hwjs('div#' + leaf_full_id).removeClass('headway-leaf-clear-left');
				hwjs('div#' + leaf_full_id).removeClass('headway-leaf-clear-right');
				
				hwjs('input#' + leaf_full_id + '_clear').val('both');
			}
		}
		
		hwjs('select#' + hwjs(this).attr('id') + '_clear').change(function(){
			leaf_id = hwjs(this).attr('id').replace('leaf-', '').replace('_clear', '');
			
			clear_select_function(leaf_id, hwjs(this).val());
		});
		
		hwjs('select#' + hwjs(this).attr('id') + '_clear').blur(function(){
			leaf_id = hwjs(this).attr('id').replace('leaf-', '').replace('_clear', '');
			
			clear_select_function(leaf_id, hwjs(this).val());
		});

		
		fluid_height_value = '';
		align_value = '';
		clear_value = '';
		
		if(leaf.hasClass('fluid-height')) fluid_height_value = 'true';	
		
		if(leaf.hasClass('headway-leaf-right')){
			align_value = 'right';
			
			hwjs('#' + leaf_full_id + '_alignment').val('right');
		} else {
			align_value = 'left';
		}
		
		if(leaf.hasClass('headway-leaf-clear-left')){
			clear_value = 'left';
			
			hwjs('#' + leaf_full_id + '_clear').val('same');
		}
		
		if(leaf.hasClass('headway-leaf-clear-right')){
			clear_value = 'right';
			
			hwjs('#' + leaf_full_id + '_clear').val('same');
		}
		
		if(leaf.hasClass('headway-leaf-clear-both')){
			clear_value = 'both';
			
			hwjs('#' + leaf_full_id + '_clear').val('both');
		}
					
		leaf_inside.append('\
			<input type="hidden" name="leaf-switches[' + leaf_full_id + '][fluid-height]" value="' + fluid_height_value + '" id="' + leaf_full_id + '_fluid_height" class="headway-visual-editor-input" />\
			<input type="hidden" name="leaf-switches[' + leaf_full_id + '][align]" value="' + align_value + '" id="' + leaf_full_id + '_align" class="headway-visual-editor-input" />\
			<input type="hidden" name="leaf-switches[' + leaf_full_id + '][clear]" value="' + clear_value + '" id="' + leaf_full_id + '_clear" class="headway-visual-editor-input" />');
		
		title_value = leaf_inside.children('.leaf-top').text().replace(/"/g, '&quot;');
		
		if(leaf_inside.children('.leaf-top').length > 0){
			leaf_inside.children('.leaf-top').attr('title', 'Double-click to edit title.').addClass('ve-tooltip').tooltip({track: true, delay: 0, showURL: false, fade: 250, positionLeft: false, id: 've-tooltip-bubble'});
			
			leaf_inside.prepend('<input type="text" value="' + title_value + '" id="' + leaf_full_id + '_title" name="title[' + leaf_id + ']" class="inline-title-edit headway-visual-editor-input" style="display:none;" />');
		}
		
	});
	
	leaf = hwjs(leaf_nonobject);
	leaf_inside = leaf.children('.headway-leaf-inside');
	
	hwjs('div.fluid-height a.leaf-height').removeClass('leaf-height-fluid');

	hwjs(leaf_nonobject+' .leaf-height').click(function(){
		this_leaf = hwjs(this).parent().parent().parent();
		
		if(this_leaf.hasClass('fluid-height')){
			this_leaf.removeClass('fluid-height');	
			hwjs('input#'+this_leaf.attr('id') + '_fluid_height').attr('value', 'false');
			hwjs(this).addClass('leaf-height-fluid');
		} else {
			this_leaf.addClass('fluid-height');
			hwjs(this).removeClass('leaf-height-fluid');
			hwjs('input#'+this_leaf.attr('id') + '_fluid_height').attr('value', 'true');
		}
		
		headway_stop_ve_close();
				
		return false;
	});


	hwjs(leaf_nonobject+' a.leaf-alignment-toggle').click(function(){
		leaf_alignment_box = hwjs(this).parent().parent().children('div.leaf-alignment');
		
		if(leaf_alignment_box.hasClass('leaf-alignment-show')){
			leaf_alignment_box.hide();
			leaf_alignment_box.removeClass('leaf-alignment-show');
		} else {
			leaf_alignment_box.show();
			leaf_alignment_box.addClass('leaf-alignment-show');
		}

		return false;
	});
	

	leaf.hoverIntent({
		sensitivity: 5, 
		interval: 50, 
		over: function(){		
			leaf_inside = hwjs(this).children('.headway-leaf-inside');
				
			leaf_inside.children('.leaf-control').fadeIn(200);
			leaf_inside.children('.leaf-icon-container').fadeIn(200);
			leaf_inside.children('.leaf-alignment-show').fadeIn(200);
		}, 
		timeout: 50,
		out: function(){
			leaf_inside.children('.leaf-control').fadeOut(200);
			leaf_inside.children('.leaf-icon-container').fadeOut(200);
			leaf_inside.children('.leaf-alignment').fadeOut(200);
		}
	});


	hwjs(leaf_nonobject+' .leaf-control .delete').click(function(){
		leaf = hwjs(this).parent().parent().parent();
		
		if(confirm('Are you sure you want to delete this leaf?') == true){
			leaf.remove();
			hwjs('div#control-'+leaf.attr('id')).remove();
			
			if(hwjs('input#'+leaf.attr('id')+'_add').length > 0){
				hwjs('input#'+leaf.attr('id')+'_add').remove();
			}
			
			headway_serialize_leaf_order();
			
			hwjs("div#headway-visual-editor").prepend('<input type="hidden" class="headway-visual-editor-input" name="delete['+leaf.attr('id')+']" id="'+leaf.attr('id')+'_delete" value="true" />');
			
			headway_equal_column_heights();
			
			headway_stop_ve_close();
		}
				
		return false;
	});
	
	
	hwjs(leaf_nonobject+' div.leaf-top').disableSelection();
	hwjs(leaf_nonobject+' div.leaf-top').dblclick(function(){
		parent = hwjs(this).parent().parent();
		parent_id = parent.attr('id');
		
		parent.children('div.leaf-control, div.leaf-icon-container').css('top', '-9999px');
		
		if(hwjs('input#' + parent_id + '_title').length == 0){
			this_value = hwjs(this).text().replace(/"/g, '&quot;');

			hwjs(this).hide().addClass('title-edit');
			
			hwjs(parent).prepend('<input type="text" value="' + this_value + '" id="' + parent_id + '_title" name="title[' + parent_id + ']" class="inline-title-edit headway-visual-editor-input" />');
							
			hwjs('div.headway-leaf input.inline-title-edit').focus();

			hwjs('div.headway-leaf input.inline-title-edit').blur(function(){
				this_value = hwjs(this).val().replace(/&quot;/g, '"');
				hwjs(this).hide();

				if(hwjs(this).siblings('div.leaf-top').find('a').length > 0){
					hwjs(this).siblings('div.leaf-top').find('a').html(this_value);
					hwjs(this).siblings('div.leaf-top').show();
				} else {
					hwjs(this).siblings('div.leaf-top').text(this_value).show();
				}
				
				parent.children('div.leaf-control, div.leaf-icon-container').css('top', 'auto');
				
				
			});
		}
		else
		{
			hwjs('div.headway-leaf input.inline-title-edit').focus();

			hwjs('div.headway-leaf input.inline-title-edit').blur(function(){
				headway_stop_ve_close();
				
				this_value = hwjs(this).val().replace(/&quot;/g, '"');
				hwjs(this).hide();

				if(hwjs(this).siblings('div.leaf-top').find('a').length > 0){
					hwjs(this).siblings('div.leaf-top').find('a').html(this_value);
					hwjs(this).siblings('div.leaf-top').show();
				} else {
					hwjs(this).siblings('div.leaf-top').text(this_value).show();
				}
				
				parent.children('div.leaf-control, div.leaf-icon-container').css('top', 'auto');
				
			});
			
			hwjs(this).hide();
			hwjs('input#' + parent_id + '_title').show().focus();

		}
	});


	hwjs(leaf_nonobject+' .width-input').blur(function(){
		headway_stop_ve_close();
		
		value = hwjs(this).val();
		
		if(parseInt(value) > parseInt(headway_settings['wrapper-width'])-20){
			value = headway_settings['wrapper-width']-20;
			hwjs(this).val(value);
		}
		
		leaf = hwjs(this).parent().parent().parent().parent();

		leaf.css('width', value+'px');
		leaf.css('minWidth', value+'px');			
	});
	hwjs(leaf_nonobject+' .height-input').blur(function(){
		headway_stop_ve_close();
		
		value = hwjs(this).val();
		leaf = hwjs(this).parent().parent().parent().parent();
		
		hwjs('input#' + hwjs(this).attr('id') + '_height_changed').attr('value', 'true');

		leaf.css('height', value+'px');
		leaf.css('minHeight', value+'px');
	});

	
	hwjs(leaf_nonobject+' .leaf-control .edit').click(function(){
		leaf_id = hwjs(this).parent().parent().parent().attr('id');	

		if(hwjs('div#control-' + leaf_id).length > 0){
			headway_open_box('control-' + leaf_id, false, true);
		} else {
			leaf_type = hwjs('div#' + leaf_id).attr('class').split(' ');				
			load_leaf_options(leaf_id, leaf_type[0]);
		}

		return false;
	});

	hwjs(leaf_nonobject+' a.precise-resize').click(function(){
		this_leaf = hwjs(this).parent();
		this_dimensions = hwjs(this).siblings('div.leaf-dimensions');

		if(this_dimensions.length > 0){
			this_dimensions.show();	
		} else {
			this_leaf.append('\
				<div class="leaf-dimensions">\
					<div class="dimension dimension-width">\
						<label for="' + this_leaf.attr('id') + '_width">Width</label>\
						<input type="text" id="' + this_leaf.attr('id') + '_width" name="dimensions[' + this_leaf.attr('id') + '][width]" value="' + this_leaf.width() + '" class="width-input headway-visual-editor-input" />\
					</div>\
					<div class="dimension dimension-height">\
						<label for="' + this_leaf.attr('id') + '_height">Height</label>\
						<input type="text" id="' + this_leaf.attr('id') + '_height" name="dimensions[' + this_leaf.attr('id') + '][height]" value="' + this_leaf.height() + '" class="height-input headway-visual-editor-input" />\
					</div>\
				</div>');
		}

		hwjs(this).hide();

		return false;
	});
	
	if(leaf.parent('.resize-container').length > 0 || leaf.parent('.resize-column').length > 0){
		width = parseInt(hwjs('div.container').width())-parseInt(headway_settings['leaf-padding'])*2-parseInt(headway_settings['leaf-margins'])*2;
		
		leaf.resizable({
			maxWidth: width,
			resize: function(event, ui) { 
				this_id = hwjs(this).attr('id');
				width = ui.size.width;
				height = ui.size.height;
		
				hwjs(this).css('width', width);
				hwjs(this).css('minHeight', height);
		
				hwjs('input#' + hwjs(this).attr('id') + '_width').attr('value', ui.size.width);
				hwjs('input#' + hwjs(this).attr('id') + '_height').attr('value', ui.size.height);
									
				hwjs('input#' + hwjs(this).attr('id') + '_height_changed').attr('value', 'true');
				
				headway_stop_ve_close();
				
			}
		});
		leaf.addClass('resize');

		hwjs('div.container '+ leaf_nonobject +' a.precise-resize').show();
		
		leaf.css('borderWidth', (parseInt(headway_settings['leaf-margins'])-1)+'px');
		leaf.css('margin', '1px');
	}

	
	if(leaf.parent('.resize-column').length > 0){
		leaf.css('borderWidth', '0 0 ' + ((parseInt(headway_settings['leaf-margins'])-1)*2)+'px');
		leaf.css('margin', '0 0 1px 0');
	}
	
	headway_disable_enter(); 

}


function headway_serialize_leaf_order(){
	if(hwjs('a#toggle-arrange').data('enabled') != true){
		headway_leafs_sortable();
	}
		
	if(hwjs('div.leafs-column').length > 1){
		hwjs('div.leafs-column').each(function(){
			column = hwjs(this).attr('id').match(/\d+-/g)[0].replace('-', '');
								
			layout_order = hwjs(this).sortable('serialize');
			hwjs('input#column-' + column + '-layout-order').attr('value', layout_order);
		});
		
		if(hwjs('div#top-container').length == 1){
			top_container_layout_order = hwjs('div#top-container').sortable('serialize');
			hwjs('input#top-container-layout-order').attr('value', top_container_layout_order);
		}
		
		if(hwjs('div#bottom-container').length == 1){
			bottom_container_layout_order = hwjs('div#bottom-container').sortable('serialize');
			hwjs('input#bottom-container-layout-order').attr('value', bottom_container_layout_order);
		}
	} else {
		var order = hwjs('div.container').sortable('serialize');
		hwjs('input#layout-order').attr('value', order);
	}
	
	if(hwjs('a#toggle-arrange').data('enabled') != true){
		hwjs('div.container').sortable('destroy');
	}
}


function headway_leafs_sortable(){
	hwjs('div.container').sortable({
		opacity:  0.35,
		forcePlaceholderSize: true,
		items: 'div.headway-leaf',
		connectWith: '.container',
		scroll: true,
		tolerance: 'pointer',
		update: function(){ 
			headway_serialize_leaf_order(true);
			
			headway_equal_column_heights();

			headway_stop_ve_close();
		},
		start: function(event, ui){
			ui.helper.css('width', parseInt(ui.item.css('width')) + 'px !important');
		}
	});
}


function prepare_leaf_options(leaf_id, leaf_options_width){
	height = hwjs('div#control-'+leaf_id).height();
	
	hwjs('div#control-'+leaf_id).width(parseInt(leaf_options_width));
	hwjs('div#control-'+leaf_id+' div.tabs ul.tabs').width(parseInt(leaf_options_width));

	hwjs('div#control-'+leaf_id).resizable({minWidth: leaf_options_width, minHeight: 200, alsoResize: 'div#control-'+leaf_id+' tr.textarea textarea, div#control-'+leaf_id+' ul.tabs'});
	hwjs('div#control-'+leaf_id).draggable({ 
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
	
	hwjs('div#control-'+leaf_id+' div.tabs').tabs();

	hwjs('div#control-'+leaf_id+' h4.floaty-box-header').append('<a class="close window-top-right" href="#">X</a>');


	hwjs('div#control-'+leaf_id+' h4.floaty-box-header a.close').click(function(){
		hwjs(this).parent().parent().hide();

		return false;
	});
	
	
	hwjs('div#control-'+leaf_id+' .headway-save-leaf-button').click(function(){
	    leaf_id_int = leaf_id.replace('leaf-', '');
	
		leaf_data = hwjs('div#control-'+leaf_id+' .headway-visual-editor-input:not(.width-input, .height-input)').serialize();
		
		hwjs('div#leaf-' + leaf_id_int).append('<span class="headway-leaf-saving">Saving...</span>');
		hwjs('div#leaf-' + leaf_id_int + ' span.headway-leaf-saving').animate( { opacity: 1 }, 50);
	
		hwjs.post( headway_blog_url + '/?headway-trigger=process&process=edit-leaf', { encoded: headway_encode64(leaf_data) }, function(){
			hwjs('div#leaf-' + leaf_id_int + ' div.leaf-content').load(headway_blog_url + '/?headway-trigger=process&process=get-leaf-content', {'leaf-id': leaf_id_int}, function(){
				hwjs('div#leaf-' + leaf_id_int + ' span.headway-leaf-saving').animate( { opacity: 0 }, 50, function(){
					hwjs(this).remove();
				});

				headway_equal_column_heights();
			});
		});
		
	});
	
	
	hwjs('div#control-'+leaf_id+' a.rotator-add-image').click(function(){
		table = hwjs(this).parent().parent().parent();
		
		if(hwjs(this).parent().parent().siblings('tr:last').length > 0){
			last_id = hwjs(this).parent().parent().siblings('tr:last').attr('class').replace('image-', '');
		} else {
			last_id = 0;
		}
		image_id = parseInt(last_id) + 1;
		leaf_id = hwjs(this).parent().attr('class');
		
		hwjs('div#control-item_'+leaf_id).css('height', 'auto');
		
		content = '\
			<tr id="' + leaf_id + '_rotator_image_' + image_id + '" class="image-' + image_id + '">\
				<th scope="row"><label for="' + leaf_id + '_rotator_image_' + image_id + '_url">Image ' + image_id + '</label></th>\
				<td>\
					<label for="' + leaf_id + '_rotator_image_' + image_id + '_url">Image URL</label><input type="text" name="leaf-options[' + leaf_id + '][images][' + image_id + '][path]" id="' + leaf_id + '_rotator_image_' + image_id + '_url" value="" class="headway-visual-editor-input" />\
					<label for="' + leaf_id + '_rotator_image_' + image_id + '_hyperlink">Image Hyperlink</label><input type="text" name="leaf-options[' + leaf_id + '][images][' + image_id + '][hyperlink]" id="' + leaf_id + '_rotator_image_' + image_id + '_hyperlink" value="" class="headway-visual-editor-input" />\
				</td>\
				<td>\
					<a href="" title="Delete This Image" class="rotator-delete-image"><img src="' + headway_settings['template-directory'] + '/library/shared-media/icons/minus.png" /></a>\
				</td>\
			</tr>';
			
		table.append(content);
		
		hwjs('tr#' + leaf_id + '_rotator_image_' + image_id + ' a.rotator-delete-image').click(function(){
			if(confirm('Are you sure?') == true) hwjs(this).parent().parent().remove();
			return false;
		});
		
		
		return false;
	});
	
	hwjs('div#control-'+leaf_id+' a.rotator-delete-image').click(function(){
		if(confirm('Are you sure?') == true) hwjs(this).parent().parent().remove();
		return false;
	});
}


function load_leaf_options(leaf_id, leaf_type, hide){		
	hwjs('div#headway-visual-editor').prepend('<div id="control-' + leaf_id + '" class="floaty-box leaf-options leaf-options-hidden"><p class="loading"><img src="' + headway_settings['template-directory'] + '/library/shared-media/images/loading.gif" class="loading-image" /></p></div>');
	
	hwjs.ajax({
		type: 'POST',
		data: {'leaf': leaf_type, 'id': leaf_id},
		url: headway_blog_url+'/?headway-trigger=process&process=get-leaf-options-width',
		async: false,
		success: function (data) {				
		    leaf_options_width = data;
		}
	})
	
	if(typeof leaf_options_width == 'undefined'){
		leaf_options_width = 350;
	}
	
	hwjs('div#control-' + leaf_id).load(headway_blog_url+'/?headway-trigger=process&process=leaf-options', {'leaf':leaf_type, 'id':leaf_id}, function(){
		prepare_leaf_options(leaf_id, leaf_options_width);		
				
		hwjs.ajax({
			url: headway_blog_url+'/?headway-trigger=process&process=options-js',
			data: {'leaf': leaf_type, 'id': leaf_id},
			type: 'POST',
			dataType: 'script'
		});
	});
		
	if(typeof hide == 'undefined' || !hide){
		headway_open_box('control-' + leaf_id, false, true);
	}
	
	headway_disable_enter();
}


function headway_columns_sortable(){			
	headway_equal_column_heights();
	
	hwjs('div#columns-container').sortable({
		opacity:  0.35,
		forcePlaceholderSize: true,
		items: 'div.leafs-column',
		scroll: false,
		tolerance: 'pointer',
		update: function(){ 			
			hwjs('div.leafs-column').removeClass('last-leafs-column');
			hwjs('div.leafs-column').last().addClass('last-leafs-column');
			
			hwjs('input#column-order').val(hwjs('div#columns-container').sortable('serialize'));

			hwjs('div.leafs-column').removeClass('leafs-column-1');
			hwjs('div.leafs-column').removeClass('leafs-column-2');
			hwjs('div.leafs-column').removeClass('leafs-column-3');
			hwjs('div.leafs-column').removeClass('leafs-column-4');
			
			column_foreach = 1;
			
			hwjs('div.leafs-column').each(function(){				
				hwjs(this).addClass('leafs-column-' + column_foreach);
				column_foreach++;
			});
			
			if(hwjs('div.leafs-column-1').length == 1 && hwjs('div.leafs-column-2').length == 1){
				headway_resize_columns(1, 2);
			}

			if(hwjs('div.leafs-column-2').length == 1 && hwjs('div.leafs-column-3').length == 1){
				headway_resize_columns(2, 3);
			}

			if(hwjs('div.leafs-column-3').length == 1 && hwjs('div.leafs-column-4').length == 1){
				headway_resize_columns(3, 4);
			}
			
			order = hwjs('div#columns-container').sortable('toArray');

			for(var index = 0; index < order.length; index++) {
				page_id = hwjs('div.leafs-column-1').attr('id').match(/(-)(page)(-)((?:[a-z0-9_]*))/)[0];

				order[index] = parseInt(order[index].replace(page_id, '').replace('column-', ''));
			}
			
			hwjs('tr#column-' + order[0] + '-width-row').appendTo('table#column-widths').find('th label').text('Column 1');
			hwjs('tr#column-' + order[1] + '-width-row').appendTo('table#column-widths').find('th label').text('Column 2');
			hwjs('tr#column-' + order[2] + '-width-row').appendTo('table#column-widths').find('th label').text('Column 3');
			hwjs('tr#column-' + order[3] + '-width-row').appendTo('table#column-widths').find('th label').text('Column 4');
			
			headway_stop_ve_close();
		},
		start: function(event, ui){			
			ui.placeholder.css('width', parseInt(ui.item.css('width'))-1);
			ui.placeholder.css('height', parseInt(ui.item.css('height'))-1);
		
			ui.placeholder.css('borderRightWidth', 1);
			ui.placeholder.css('paddingRight', 9);
			ui.placeholder.css('paddingBottom', 9);
			
			hwjs('div.leafs-column').addClass('last-leafs-column');
		},
		stop: function(){
			hwjs('div.leafs-column').removeClass('last-leafs-column');
			hwjs('div.leafs-column').last().addClass('last-leafs-column');
		}
	});
	
	hwjs('div.leafs-column').css('cursor', 'move');
}


function headway_bind_column_width_input(column_main, column_alt){
	hwjs('input#column-' + column_main + '-width').blur(function(){	
		page_id = hwjs('div.leafs-column-1').attr('id').match(/(-)(page)(-)((?:[a-z0-9_]*))/)[0];
		
		column_main_obj = hwjs('div#column-' + column_main + page_id);
		column_alt_obj = hwjs('div#column-' + column_alt + page_id);
		
		column_main_width = column_main_obj.width();
		column_alt_width = column_alt_obj.width();
		
		difference = column_main_width - hwjs(this).val();
		column_alt_width_computed = parseInt(column_alt_width + difference);

		if(isNaN(hwjs(this).val())){
			hwjs(this).val(column_main_width);
			return false;
		}
		
		if(hwjs(this).val() < 120){
			hwjs(this).val(column_main_width);
			return false;
		}
		
		if(column_alt_width_computed < 120){
			hwjs(this).val(column_main_width);
			hwjs('input#column-' + column_alt + '-width').val(column_alt_width);
			
			return false;
		}
		
		column_main_obj.css('width', hwjs(this).val() + 'px');
		column_alt_obj.css('width', column_alt_width_computed + 'px');

		hwjs('input#column-' + column_alt + '-width').val(column_alt_width_computed);
	});
}

function headway_bind_column_width_inputs(columns){
	if(columns == 2){
		headway_bind_column_width_input(1, 2);
		headway_bind_column_width_input(2, 1);
	} else if(columns == 3){
		headway_bind_column_width_input(1, 2);
		headway_bind_column_width_input(2, 1);
		headway_bind_column_width_input(3, 2);
	} else if(columns == 4){
		headway_bind_column_width_input(1, 2);
		headway_bind_column_width_input(2, 1);
		headway_bind_column_width_input(3, 2);
		headway_bind_column_width_input(4, 3);
	}
}


function headway_resize_columns(column_1, column_2){
	hwjs('div.leafs-column-'+column_1).resizable({
		maxWidth: hwjs('div#wrapper').width(),
		minWidth: 170,
		handles: 'e',
		resize: function(event, ui) { 
			main_column = hwjs(this);
			main_column_width = ui.size.width;

			other_column = hwjs('div.leafs-column-'+column_2);
			
			//Get the difference between the size when first resizing to what it is after resizing
			difference = ui.originalSize.width - ui.size.width;

			//Other column width is original width starting out plus the difference of the main column's starting width and current width
			other_column_width = parseInt(other_column_original_width) + parseInt(difference);
			
			//Get the current total of the two columns
			total_now = other_column_width + parseInt(main_column.css('width').replace('px', ''));
		
			//To soothe the jumpiness, find the deviation by taking the original total of the two column widths and the total now and subtrace the deviation from the new other column's width
			deviation = total_now - total_original;
			other_column_width = other_column_width - deviation;
			
			other_column.css('width', other_column_width + 'px');

			page_id = hwjs('div.leafs-column-'+column_1).attr('id').match(/(-)(page)(-)((?:[a-z0-9_]*))/)[0];
			
			column_1_real_width = parseInt(hwjs('div#column-'+column_1+page_id).width());
			column_2_real_width = parseInt(hwjs('div#column-'+column_2+page_id).width());
			
			//Don't allow the resizable to crush the other column.  Allow this to only be set once to avoid jumpiness						
			if(set_max_width != true){	
				offense = 170 - column_2_real_width;
									
				hwjs(this).resizable('option', 'maxWidth', column_1_real_width - offense);
										
				set_max_width = true;
			}
			
			hwjs('input#column-' + column_1 + '-width').val(column_1_real_width);
			hwjs('input#column-' + column_2 + '-width').val(column_2_real_width);
		},
		start: function(event, ui){
			set_max_width = false;
			
			hwjs(this).resizable('option', 'maxWidth', hwjs('div#wrapper').width());
			
			other_column_original_width = hwjs('div.leafs-column-'+column_2).css('width').replace('px', '');
			total_original = parseInt(other_column_original_width) + parseInt(hwjs(this).css('width'));
		}
	});

}


function headway_bind_template_select_option(element){
	hwjs(element).click(function(){
		hwjs(this).siblings('div.selected-option').removeClass('selected-option');
		hwjs(this).addClass('selected-option');
		
		hwjs('a#load-template').show();
	});
		
	hwjs(element).children('.template-select-edit').click(function(){	
		headway_edit_template(hwjs(this).parent());
	});
}


function headway_edit_template(option){
	option_text = option.children('span.select-option-text');
	rename_input = hwjs('input#rename-template');
	rename_input.val(option_text.html().replace('<small id="default-template">Default</small>', ''));
	
	hwjs('input#template-settings-template-id').val(option.attr('id'));
	hwjs('input#template-settings-template-name').val(option_text.html().replace('<small id="default-template">Default</small>', ''));
	
	if(option_text.children('#default-template').length == 1){
		hwjs('#remove-template-as-default').show();
		hwjs('#set-template-as-default').hide();
	} else {
		hwjs('#set-template-as-default').show();
		hwjs('#remove-template-as-default').hide();
	}
	
	headway_open_box('edit-template');
}


function headway_visual_editor_leafs(){
	if(headway_settings['link'] === false){
		ready_leafs('.headway-leaf');
	}
	
	hwjs('a#toggle-column-arrange-resize').toggle(function(){
		headway_columns_sortable();
				
		if(hwjs('div.leafs-column-1').length == 1 && hwjs('div.leafs-column-2').length == 1){
			headway_resize_columns(1, 2);
		}

		if(hwjs('div.leafs-column-2').length == 1 && hwjs('div.leafs-column-3').length == 1){
			headway_resize_columns(2, 3);
		}

		if(hwjs('div.leafs-column-3').length == 1 && hwjs('div.leafs-column-4').length == 1){
			headway_resize_columns(3, 4);
		}
		
		hwjs(this).text('Disable');
	}, function(){
		if(hwjs('div.leafs-column-1').length == 1 && hwjs('div.leafs-column-2').length == 1){
			hwjs('div.leafs-column-1').resizable('destroy');	
		}

		if(hwjs('div.leafs-column-2').length == 1 && hwjs('div.leafs-column-3').length == 1){
			hwjs('div.leafs-column-2').resizable('destroy');	
		}

		if(hwjs('div.leafs-column-3').length == 1 && hwjs('div.leafs-column-4').length == 1){
			hwjs('div.leafs-column-3').resizable('destroy');	
		}
		
		hwjs('div#columns-container').sortable('destroy');
		
		hwjs('div.leafs-column').css('cursor', 'default');				
						
		hwjs(this).text('Enable');
	});


	hwjs('a#toggle-resize').toggle(function(){
		
		if(hwjs('div.container').length > 1){
			width = parseInt(hwjs('div#top-container').width())-parseInt(headway_settings['leaf-padding'])*2-parseInt(headway_settings['leaf-margins'])*2;
		} else {
			width = parseInt(hwjs('div#container').width())-parseInt(headway_settings['leaf-padding'])*2-parseInt(headway_settings['leaf-margins'])*2;
		}
		
		hwjs('div.headway-leaf').resizable({
			maxWidth: width,
			minWidth: 95,
			resize: function(event, ui) { 
				this_leaf = hwjs(this);
				this_id = this_leaf.attr('id');
				
				width = ui.size.width;
				height = ui.size.height;

				this_leaf.css('minWidth', width);
				this_leaf.css('minHeight', height);
				
				if(!this_leaf.parent().hasClass('leafs-column')){					
					hwjs('input#' + hwjs(this).attr('id') + '_width').attr('value', ui.size.width);
				}
				
				hwjs('input#' + hwjs(this).attr('id') + '_height').attr('value', ui.size.height);
				
				hwjs('input#' + hwjs(this).attr('id') + '_height_changed').attr('value', 'true');
				
				headway_stop_ve_close();
				
			}
		});
		hwjs(this).text('Disable');
		
		hwjs('div.leafs-container, div#container').addClass('resize-container');
		hwjs('div.leafs-column').addClass('resize-column');
		
		hwjs('div.headway-leaf').addClass('resize');
		
		//Modify settings of all leafs
		hwjs('div.resize').css('borderWidth', (parseInt(headway_settings['leaf-margins'])-1)+'px');
		hwjs('div.resize').css('margin', '1px');

		//Modify settings of leafs in columns
		hwjs('div.leafs-column div.resize').css('borderWidth', '0 0 ' + (((parseInt(headway_settings['leaf-margins']))*2)-1)+'px');
		hwjs('div.leafs-column div.resize').css('margin', '0 0 1px 0');
		
		if(hwjs('div.container').length == 1){
			hwjs('div.container').css({padding: 0, borderWidth: headway_settings['leaf-container-vertical-padding']+'px '+headway_settings['leaf-container-horizontal-padding']+'px'});
		} else {			
			hwjs('div.resize-column').each(function(){
				hwjs(this).data('borderRightWidth', parseInt(hwjs(this).css('borderRightWidth').replace('px', '')));
				hwjs(this).data('paddingRight', parseInt(hwjs(this).css('paddingRight').replace('px', '')));
				
				column_padding_top = parseInt(hwjs(this).css('paddingTop').replace('px', ''));
				column_padding_right = parseInt(hwjs(this).css('paddingRight').replace('px', '')) + parseInt(hwjs(this).css('borderRightWidth').replace('px', ''));
				column_padding_bottom = parseInt(hwjs(this).css('paddingBottom').replace('px', ''));
				column_padding_left = parseInt(hwjs(this).css('paddingLeft').replace('px', ''));
								
				hwjs(this).css({
					borderTopWidth: column_padding_top, 
					borderRightWidth: column_padding_right, 
					borderBottomWidth: column_padding_bottom, 
					borderLeftWidth: column_padding_left,
					padding: 0
				});
			});
			
			hwjs('div.resize-container').each(function(){				
				hwjs(this).data('borderTopWidth', parseInt(hwjs(this).css('borderTopWidth').replace('px', '')));
				hwjs(this).data('paddingTop', parseInt(hwjs(this).css('paddingTop').replace('px', '')));
				
				hwjs(this).data('borderBottomWidth', parseInt(hwjs(this).css('borderBottomWidth').replace('px', '')));
				hwjs(this).data('paddingBottom', parseInt(hwjs(this).css('paddingBottom').replace('px', '')));
				
				container_padding_top = parseInt(hwjs(this).css('paddingTop').replace('px', '')) + parseInt(hwjs(this).css('borderTopWidth').replace('px', ''));
				container_padding_right = parseInt(hwjs(this).css('paddingRight').replace('px', ''));
				container_padding_bottom = parseInt(hwjs(this).css('paddingBottom').replace('px', '')) + parseInt(hwjs(this).css('borderBottomWidth').replace('px', ''));
				container_padding_left = parseInt(hwjs(this).css('paddingLeft').replace('px', ''));
				
				hwjs(this).css({
					borderTopWidth: container_padding_top, 
					borderRightWidth: container_padding_right, 
					borderBottomWidth: container_padding_bottom, 
					borderLeftWidth: container_padding_left,
					padding: 0
				});
			});
		}
		
		hwjs('div.container div.headway-leaf a.precise-resize').show();
		
		return false;
	}, function(){
		hwjs(this).text('Enable');
		
		hwjs('div.headway-leaf').resizable('destroy');
		hwjs('div.headway-leaf').removeClass('resize');
		
		hwjs('div.leafs-container, div#container').removeClass('resize-container');
		hwjs('div.leafs-column').removeClass('resize-column');
		
		hwjs('div.headway-leaf').css({borderWidth: 'normal', margin: headway_settings['leaf-margins']+'px'});
		hwjs('div.leafs-column div.headway-leaf').css({borderWidth: 'normal', margin: '0 0 ' + (parseInt(headway_settings['leaf-margins'])*2) + 'px'});
		
		if(hwjs('div.container').length == 1){
			hwjs('div.container').css({padding: headway_settings['leaf-container-vertical-padding']+'px '+headway_settings['leaf-container-horizontal-padding']+'px', borderWidth: 'normal'});
		} else {
			hwjs('div.leafs-column').each(function(){
				padding = parseInt(hwjs(this).css('borderLeftWidth').replace('px', ''));
								
				hwjs(this).css({
					borderTopWidth: 0, 
					borderRightWidth: hwjs(this).data('borderRightWidth'), 
					borderBottomWidth: 0, 
					borderLeftWidth: 0,
					padding: padding,
					paddingRight: hwjs(this).data('paddingRight')
				});
			});
			
			
			hwjs('div.leafs-container').each(function(){
				padding = parseInt(hwjs(this).css('borderRightWidth').replace('px', ''));
				padding_top = parseInt(hwjs(this).css('borderTopWidth').replace('px', ''));
				padding_bottom = parseInt(hwjs(this).css('borderBottomWidth').replace('px', ''));

				
				hwjs(this).css({
					borderTopWidth: hwjs(this).data('borderTopWidth'), 
					borderRightWidth: 0, 
					borderBottomWidth: hwjs(this).data('borderBottomWidth'), 
					borderLeftWidth: 0,
					padding: padding,
					paddingTop: padding_top-hwjs(this).data('borderTopWidth'),
					paddingBottom: padding_bottom-hwjs(this).data('borderBottomWidth')
				});
			});
		}
				
		hwjs('div.container div.headway-leaf a.precise-resize').hide();
		hwjs('div.container div.headway-leaf div.leaf-dimensions').hide();
		
		
		return false;
	});
	hwjs('a#toggle-arrange').toggle(function(){		
		headway_leafs_sortable();
		
		hwjs('div.container div.headway-leaf').css('cursor', 'move');
		
		hwjs(this).text('Disable');
		hwjs(this).data('enabled', true);
		
		return false;
	}, function(){
		hwjs('div.container').sortable('destroy');
		hwjs(this).text('Enable');
		
		hwjs(this).data('enabled', false);

		hwjs('div.container div.headway-leaf').css('cursor', 'pointer');
		
		return false;
	});

		
	hwjs('ul.add-leafs li a').click(function(){
		headway_stop_ve_close();
		
		leaf_nice_type = hwjs(this).siblings('span').text().replace(' Leaf', '');
		leaf_class = hwjs(this).parent().attr('class').replace('add-', '').replace(' alt', '');
				
		headway_settings['last-leaf-id'] = parseInt(headway_settings['last-leaf-id']) + 1;
		
		hwjs('div.container').each(function(){
			content = '<div id="leaf-' + headway_settings['last-leaf-id'] + '" class="' + leaf_class + ' headway-leaf" style="position: relative; cursor: pointer;">\
				<div class="headway-leaf-inside">\
				<div class="leaf-top" unselectable="on" style="-moz-user-select: none; cursor: pointer;">' + leaf_nice_type + '</div>\
				\
				<div class="leaf-content" style="cursor: pointer;">\
				\
					Customize the leaf settings to your heart\'s desire then save to view your changes.\
				\
				</div>\
				</div>\
			</div>';
			
			if(hwjs(this).hasClass('leafs-column')){
				hwjs(this).prepend(content);
			} else {
				hwjs(this).append(content);
			}
			
			return false;
		});
						
		ready_leafs('#leaf-'+headway_settings['last-leaf-id']);
		load_leaf_options('leaf-'+headway_settings['last-leaf-id'], leaf_class, true);
		
		if(leaf_class == 'content' || leaf_class == 'sidebar'){
			hwjs('div#leaf-' + headway_settings['last-leaf-id']).find('a.leaf-height-fluid').trigger('click');
		}
		
		headway_equal_column_heights();
		
		headway_serialize_leaf_order();
		
		hwjs("div#headway-visual-editor").prepend('<input type="hidden" name="add[leaf-'+headway_settings['last-leaf-id']+']" id="leaf-'+headway_settings['last-leaf-id']+'_add" value="' + leaf_class + '" class="headway-visual-editor-input headway-add-leaf-input" />');
				
		headway_disable_enter(); 
	});
		
	hwjs('select#leaf-columns').change(function(){
		hwjs('p#leaf-page-setup-reload-notice').show();
	
		if(hwjs(this).val() == 1){
			hwjs('div.leaf-column-settings').hide();
		} else {
			hwjs('div.leaf-column-settings').show();
		}
	});
	
	hwjs('input#show-top-leafs-container, input#show-bottom-leafs-container').click(function(){
		hwjs('p#leaf-page-setup-reload-notice').show();
	});
	
	headway_bind_template_select_option('div#template-selector div.select-option');
	
	hwjs('a#load-template').click(function(){
		if(confirm('Are you sure?  Loading a template will automatically save all settings and replace the current leafs.') !== true) return false;
		
		if(hwjs('div#template-selector .selected-option').length == 0) return false;
		
		template_name = hwjs('div#template-selector .selected-option span.select-option-text').html().replace('<small id="default-template">Default</small>', '');
		template_id = hwjs('div#template-selector .selected-option').attr('id').replace('template-', '');
		
		current_page = hwjs('input#current-page').val();
				
		hwjs('div#visual-editor-working').show().animate({'opacity':1}, 250);
		hwjs('div#ve-working-overlay').show().animate({'opacity':1}, 250, false, function(){
			hwjs.ajax({
				type: 'POST',
				data: {'page': escape(current_page), 'template-name': escape(template_name + '-' + template_id)},
				url: headway_blog_url+'/?headway-trigger=process&process=load-template',
				success: function(data){
					hwjs('select#leaf-columns, input.column-width-input, input#show-top-leafs-container, input#show-bottom-leafs-container, input#show-top-leafs-container-hidden, input#show-bottom-leafs-container-hidden').removeClass('headway-visual-editor-input');
										
					form_data = hwjs('.headway-visual-editor-input').serialize();
					
					headway_save_editor(true);
				}
			});
		});
	});
	
	hwjs('a#save-template').click(function(){
		headway_open_box('save-template');
	});
	
	hwjs('a#save-template-submit').click(function(){
		template_name = hwjs('input#template-name').val();
		
		current_page = hwjs('input#current-page').val();
		
		columns = hwjs('select#leaf-columns').val();

		column_1_width = hwjs('input#column-1-width').val();
		column_2_width = hwjs('input#column-2-width').val();
		column_3_width = hwjs('input#column-3-width').val();
		column_4_width = hwjs('input#column-4-width').val();		
		
		hwjs.ajax({ 
			type: 'POST',
			data: {'template-name':escape(template_name), 'page':escape(current_page), 'columns':escape(columns), 'column-1-width':escape(column_1_width), 'column-2-width':escape(column_2_width), 'column-3-width':escape(column_3_width), 'column-4-width':escape(column_4_width)},
			url: headway_blog_url+'/?headway-trigger=process&process=save-leaf-template', 
			success: function(response){
				template_id = response;
								
				hwjs('div#template-selector').append('<div class="select-option" id="template-' + template_id + '"><span class="select-option-text">' + template_name + '</span><a href="#" class="template-select-edit select-edit">Edit</a></div>');
				
				hwjs('p#no-templates').hide();
				hwjs('a#load-template').show();
				hwjs('p#load-template-message').show();
				
				hwjs('input#template-name').val('Template Name');
				
				headway_bind_template_select_option('div#template-' + template_id);
				
				headway_close_box('save-template');
			}
		});
	});
	
	
	hwjs('a#delete-template-link').click(function(){
		template_id = hwjs('input#template-settings-template-id').val();
		template_name = hwjs('input#template-settings-template-name').val();

		if(confirm('Are you sure?') === true){			
			hwjs.ajax({
				type: 'POST',
				data: {'template-id': template_id, 'template-name': template_name},
				url: headway_blog_url+'/?headway-trigger=process&process=delete-template',
				async: false,
				success: function(data){	
					hwjs('div#'+template_id).remove();

				    headway_close_box('edit-template');

					if(hwjs('div#template-selector div.select-option').length == 0){
						hwjs('p#no-templates').show();
						hwjs('a#load-template').hide();
						hwjs('p#load-template-message').hide();
					}
				}
			});
		}

		return false;
	});
	
	
	hwjs('a#save-template-settings-button').click(function(){
		template_id = hwjs('input#template-settings-template-id').val();
		template_name = hwjs('input#template-settings-template-name').val();
		rename_input = hwjs('input#rename-template');
		
		hwjs.ajax({
			type: 'POST',
			data: {'id': template_id, 'template-name': template_name, 'new-name': rename_input.val()},
			url: headway_blog_url+'/?headway-trigger=process&process=rename-template',
			async: false,
			success: function(data){	
				option_text.text(rename_input.val());

				headway_close_box('edit-template');
			}
		});

		return false;
	});
	
	
	hwjs('a#set-template-as-default').click(function(){
		template_id = hwjs('input#template-settings-template-id').val();
		template_name = hwjs('input#template-settings-template-name').val();
		
		hwjs.ajax({
			type: 'POST',
			data: {'id': template_id, 'template-name': template_name},
			url: headway_blog_url+'/?headway-trigger=process&process=set-default-leafs-template',
			async: false,
			success: function(data){
				hwjs('small#default-template').remove();	
				hwjs('div#' + template_id).children('.select-option-text').append('<small id="default-template">Default</small>');

				headway_close_box('edit-template');
			}
		});

		return false;
	});
	
	
	hwjs('a#remove-template-as-default').click(function(){
		template_id = hwjs('input#template-settings-template-id').val();
		template_name = hwjs('input#template-settings-template-name').val();
		
		hwjs.ajax({
			url: headway_blog_url+'/?headway-trigger=process&process=remove-default-leafs-template',
			async: false,
			success: function(data){
				hwjs('small#default-template').remove();	

				headway_close_box('edit-template');
			}
		});

		return false;
	});
	
	
	hwjs('a#export-template-button').click(function(){		
		template_name = hwjs('select#export-template-selector').find(':selected').html().replace('<small id="default-template">Default</small>', '');
		template_id = hwjs('select#export-template-selector').find(':selected').val().replace('template-', '');

		url = headway_blog_url + '/?headway-trigger=process&process=export-leaf-template&template-id='+escape(template_name + '-' + template_id) + '&template-name='+escape(template_name);

		window.open(url);
		
		headway_close_box('export-window');	

		return false;
	});
	
	if(hwjs('div.leafs-column-4').length == 1){
		headway_bind_column_width_inputs(4);
	} else if(hwjs('div.leafs-column-3').length == 1){
		headway_bind_column_width_inputs(3);
	} else if(hwjs('div.leafs-column-2').length == 1){
		headway_bind_column_width_inputs(2);
	}
	
	hwjs('input#disable-equal-column-heights').click(function(){
		if(hwjs(this).is(':checked')){
			hwjs('div.leafs-column').css('height', 'auto');
		} else {
			headway_equal_column_heights();
		}
	});
}