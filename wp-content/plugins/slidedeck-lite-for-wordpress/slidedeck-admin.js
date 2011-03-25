/**
 * SlideDeck for WordPress 1.3.3 - 2010-10-19
 * Copyright 2010 digital-telepathy  (email : support@digital-telepathy.com)
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * 
 * More information on this project:
 * http://www.slidedeck.com/
 * 
 * Full Usage Documentation: http://www.slidedeck.com/usage-documentation 
 * 
 * @package SlideDeck
 * @subpackage SlideDeck Pro for WordPress
 * 
 * @author digital-telepathy
 * @version 1.3.3 
 */
var SlideDeckSlides = {
	processing: false,
    namespace: 'slidedeck',
	
	updateTitle: function(e){
		var element = e;
		if (this.timer) {
			clearTimeout(element.timer);
		}
		this.timer = setTimeout(function(){
			jQuery('#hndle_for_' + jQuery(element).parents('.slide')[0].id).text(element.value);
			jQuery(element).parents('.slide').find('h3.hndle').text(element.value);
			document.getElementById('slide-start').options[jQuery(element).parents('.slide').find('input.slide-order')[0].value - 1].text = element.value;
		},100);
		return true;
	},
	
	addSlide: function(e){
		var self = this;
		
		if(this.processing === false){
			this.processing = true;
			
			var el = e;
			var url = typeof(ajaxurl) != 'undefined' ? ajaxurl : e.href.split('?')[0].replace(document.location.protocol + '//' + document.location.hostname, "");
            
            // Create array of existing indexes and increment if necessary to prevent ID duplication
            var slideCount = parseInt(jQuery('.slide').length);
            var existingIndexes = [];
            for(var i=0, hSlides=jQuery('.slide textarea.horizontal.slide-content'); i<hSlides.length; i++){
                existingIndexes.push(parseInt(hSlides[i].id.split('_')[1], 10));
            }
            // Descending sort to get highest present index value first 
            existingIndexes.sort(function(a, b){
                return a < b;
            });
            if(existingIndexes[0] > slideCount){
                slideCount = existingIndexes[0];
            }
    
			jQuery.ajax({
				url: url,
				type: 'get',
				data: {
                    action: 'slidedeck_add_slide',
					count: slideCount,
					gallery_id: jQuery('#slidedeck_gallery_id').val()
				},
				complete: function(data){
					var row_id = "slide_editor_" + (slideCount + 1),
						editor_id = "slide_" + (slideCount + 1) + "_content";
					
					jQuery('.slides').append(data.responseText);
					jQuery('#re-order-slides .slide-order').append('<li><a href="#' + row_id + '" class="hndle" id="hndle_for_slide_editor_' + (slideCount + 1) + '">Slide ' + (slideCount + 1) + '</a></li>');
					jQuery('#slide-start').append('<option value="' + (slideCount + 1) + '">Slide ' + (slideCount + 1) + '</option>');
					
					tinyParams = tinyMCEPreInit.mceInit;
					tinyParams.mode = "exact";
					tinyParams.elements = editor_id;
					
					tinyMCE.init(tinyParams);
	
		            tb_init(jQuery('#' + row_id + ' .horizontal-slide-media a.thickbox'));
                    tb_init(jQuery('#' + row_id + ' .vertical-slide-media a.thickbox'));
					
					self.updateEditorControls(jQuery('#' + editor_id)); // html element textarea.

					self.processing = false;
				}
			});
		}
	},
	
	updateEditorControls: function(e,row_id){
		var slide = e.parents('.slide');
		
		slide.find('.slide-title').unbind('keyup.' + this.namespace).bind('keyup.' + this.namespace, function(){
			SlideDeckSlides.updateTitle(this);
		});
		
		slide.find('.editor-nav a.mode').unbind('click.' + this.namespace).bind('click.' + this.namespace, function(event){
			event.preventDefault();
			SlideDeckSlides.editorNavigation(this);
		});
		
		slide.find('.slide-delete').unbind('click.' + this.namespace).bind('click.' + this.namespace, function(event){
			event.preventDefault();
			SlideDeckSlides.deleteSlide(this);
		});
		        
        slide.find('.handlediv').unbind('click.' + this.namespace).bind('click.' + this.namespace, function(event){
            event.preventDefault();
            jQuery(this).parent().find('.inside').toggle();
        });
		
		slide.find('.slide-delete').unbind('click.' + this.namespace).bind('click.' + this.namespace, function(event){
			event.preventDefault();
			SlideDeckSlides.deleteSlide(this);
		});

        slide.find('.media-buttons').show();
		slide.find('.media-buttons a.thickbox').unbind('click.' + this.namespace).bind('click.' + this.namespace, function(){
			SlideDeckSlides.tb_click(this);
		});
    },
	
	deleteSlide: function(e){
		if(confirm("Are you sure you would like to delete this slide?")){
			var slide_id = jQuery(e).parents('.slide').attr('id').split('_')[2];
			
			jQuery('#hndle_for_slide_editor_' + slide_id).parents('li').remove();
			jQuery('#slide-start').find('option[value="' + slide_id + '"]').remove();

			jQuery(e).parents('.slide').remove();
		}		
	},
	
	tb_click: function(e){
		if ( typeof tinyMCE != 'undefined' && tinyMCE.activeEditor ) {
			var url = 	jQuery(e).attr('href');
			url = url.split('editor=');
			if(url.length>1){
				url = url[1];
				url = url.split('&');
				if(url.length>1){
					editorid = url[0];
				}
			}
			tinyMCE.get(editorid).focus();
			tinyMCE.activeEditor.windowManager.bookmark = tinyMCE.activeEditor.selection.getBookmark('simple');
			jQuery(window).resize();
		}
	},

	editorNavigation: function(e){
		var p = jQuery(e).parents('li:eq(0)');
		var navs = p.find('.editor-nav a');
		navs.removeClass('active');
		jQuery(e).addClass('active');

		var editor = e.href.split("#")[1];
		var textarea = p.find('textarea.slide-content')[0];
		
		switch(editor){
			case "visual":
                this.switchEditorNav( textarea.id, 'tinymce' );
			break;
			
			case "html":
                this.switchEditorNav( textarea.id, 'html' );
			break;
		}
    },
    
    switchEditorNav: function( textarea_id, mode ){
        var editor = false;
        if(typeof(tinyMCE) != 'undefined'){
            editor = tinyMCE.get(textarea_id);
        }
        var textarea = jQuery('#' + textarea_id);
        
        switch(mode){
            case "tinymce":
                textarea.css('color','#fff').val(switchEditors.wpautop(textarea.val()));
                editor.show();
                tinyMCE.execCommand('mceAddControl', false, textarea_id);
                textarea.css('color','#000');
            break;
            
            case "html":
                textarea.css('color','#000');
                editor.hide();
            break;
        }
    }
};


function send_to_editor(h) {
	var ed;
	var editorid;
	var url = jQuery('#TB_window iframe').attr('src');
	url = url.split('editor=');
	if(url.length>1){
		url = url[1];
		url = url.split('&');
		if(url.length>1){
			editorid = url[0];
		}
	}

	if ( typeof(tinyMCE) != 'undefined' && ( ed = tinyMCE.get(editorid) ) && !ed.isHidden() ) {
        ed.focus();
		if (tinymce.isIE)
			ed.selection.moveToBookmark(tinymce.EditorManager.activeEditor.windowManager.bookmark);

		if ( h.indexOf('[caption') === 0 ) {
			if ( ed.plugins.wpeditimage )
				h = ed.plugins.wpeditimage._do_shcode(h);
		} else if ( h.indexOf('[gallery') === 0 ) {
			if ( ed.plugins.wpgallery )
				h = ed.plugins.wpgallery._do_gallery(h);
		}

		ed.execCommand('mceInsertContent', false, h);

	} else if ( typeof edInsertContent == 'function' ) {
		edInsertContent(editorid, h);
	} else {
		jQuery( editorid ).val( jQuery( editorid ).val() + h );
	}

	tb_remove();
}


function updateSlideDeckPreview(el){
    var btn = document.getElementById('btn_slidedeck_preview_submit');
    
    var params_raw = btn.href.split('?')[1].split('&');
    var params = {};
    for(var p in params_raw){
        var param = params_raw[p].split('=');
        params[param[0]] = param[1];
    }
    
    params[el.id] = el.value;
    switch(el.id){
        case "preview_w":
            params['width'] = Math.max(630,params[el.id].match(/([0-9]+)/g)[0]) + 20;
        break;
        case "preview_h":
            params['height'] = parseInt(params[el.id].match(/([0-9]+)/g)[0]) + 200;
        break;
    }

    var href = btn.href.split('?')[0];
    var sep = "?";
    for(var k in params){
        href += sep + k + "=" + params[k];
        sep = "&";
    }

    btn.href = href;
}


function closePreviewWatcher(){
    var timer;
    timer = setInterval(function(){
        if(document.getElementById('TB_closeWindowButton')){
            clearInterval(timer);
            jQuery('#TB_closeWindowButton, #TB_overlay').bind('mouseup', function(event){
                cleanUpSlideDecks();
            });
        }
    }, 20);
}


function cleanUpSlideDecks(){
    jQuery('body > a').filter(function(){
        return (this.id.indexOf('SlideDeck_Bug') != -1);
    }).remove();
}


(function($){
	$(document).ready(function(){
		$('.slide-title').bind('keyup.' + SlideDeckSlides.namespace, function(){
			SlideDeckSlides.updateTitle(this);
		});

		$('.editor-nav a.mode').bind('click.' + SlideDeckSlides.namespace, function(event){
			event.preventDefault();
			SlideDeckSlides.editorNavigation(this);
		});

		$('.slide-delete').bind('click.' + SlideDeckSlides.namespace, function(event){
			event.preventDefault();
			SlideDeckSlides.deleteSlide(this);
		});
        
        $('.slide .handlediv').bind('click.' + SlideDeckSlides.namespace, function(event){
            event.preventDefault();
            $(this).parent().find('.inside').toggle();
        });

		$('.media-buttons a.thickbox').bind('click.' + SlideDeckSlides.namespace, function(){
			SlideDeckSlides.tb_click(this);
		});

		$('#btn_add-another-slide').bind('click.' + SlideDeckSlides.namespace, function(event){
			event.preventDefault();
			SlideDeckSlides.addSlide(this);
		});
		
		if($('.slide-order').length){
			$('.slide-order').sortable({
				update: function(event,ui){
					$('ul.slide-order').find('li:not(.ui-sortable-helper)').each(function(inc){
						var target = $(this).find('a.hndle').attr('href').split("#")[1];
						$('#' + target).find('input.slide-order').val(inc + 1);
					});
				}
			});
		}
		
		$('a.skin-thumbnail').bind('click', function(event){
			event.preventDefault();
			var slug = this.href.split("#")[1];
			$('a.skin-thumbnail').removeClass('active');
			$(this).addClass('active');
			$('#slidedeck_skin').val(slug);
		});
		
		$('a.navigation-type').bind('click', function(event){
			event.preventDefault();
			if(!$(this).hasClass('disabled')){
				var slug = this.href.split("#")[1];
				$('a.navigation-type').removeClass('active');
				$(this).addClass('active');
				$('#slidedeck_navigation_type').val(slug);
			}
		});
		
		// Event listener for showing/hiding RSS feed entry field.
		$('#smart_slidedeck_type_of_content :radio').change(function(event){
			// Show the filter posts by category option and children.
			$('#filter_posts_by_category').slideDown();
		});
		
		$('#slidedeck_filter_by_category').bind('click.' + SlideDeckSlides.namespace, function(event){
			if(this.checked == true){
				$('#category_filter_categories').slideDown();
			} else {
				$('#category_filter_categories').slideUp();
			}
		});
		
		$('#slidedeck_total_slides').bind('change.' + SlideDeckSlides.namespace, function(){
			if(this.value > 5){
				$('#navigation_simple-dots').click();
				$('#navigation_dates, #navigation_post-titles').addClass('disabled');
			} else {
				$('#navigation_dates, #navigation_post-titles').removeClass('disabled');
			}
		});
		$('#slidedeck_total_slides').trigger('change');
		
		if($('#form_action').val() == "create"){
			$('#titlewrap #title').css({
				color: '#999',
				fontStyle: 'italic'
			}).focus(function(event){
                this.style.color = "";
				this.style.fontStyle = "";
				if(this.value == this.defaultValue){
    				this.value = "";
                }
			});
		}

		$('a.slidedeck-action.delete, a.submitdelete.deletion').bind('click.' + SlideDeckSlides.namespace, function(event){
			event.preventDefault();
			
			if(confirm("Are you sure you want to delete this SlideDeck?\nThis CANNOT be undone.")){
				var callback;
				if($(this).hasClass('submitdelete')){
					var href = this.href.split("&")[0];
					callback = function(){
						document.location.href = href;
					};
				} else {
					var row = $(this).parents('tr');
					callback = function(){
						row.fadeOut(500,function(){
							row.remove();
						});
					};
				}
				$.get(this.href,function(){
					callback();
				});
			}
		});
		
		$('#template_snippet_w, #template_snippet_h').bind('keyup.' + SlideDeckSlides.namespace, function(event){
			var element = this;
			if (this.timer) {
				clearTimeout(element.timer);
			}
			this.timer = setTimeout(function(){
				var w = $('#template_snippet_w').val(),
					h = $('#template_snippet_h').val(),
					slidedeck_id = $('#slidedeck_id').val();
				
				var snippet = "<" + "?php slidedeck( " + slidedeck_id + ", array( 'width' => '" + w + "', 'height' => '" + h + "' ) ); ?" + ">";
				
				$('#slidedeck-template-snippet').val(snippet);
			},100);
			return true;
		});
		
		$('#slidedeck-template-snippet').focus(function(){
			this.select();
		});
	});
    
    $(window).load(function(){
        $('.ajax-masker').hide();
    });
    
	// thickbox settings
	$(window).resize(function() {
		var tbWindow = $('#TB_window'), width = $(window).width(), H = $(window).height(), W = ( 720 < width ) ? 720 : width;
		if (tbWindow.size()) {
    		if (tbWindow.find('#slidedeck_preview_window').length){
    			return false;
    		}
			tbWindow.width(W - 50).height(H - 45);
			$('#TB_iframeContent').width(W - 50).height(H - 75);
			tbWindow.css({
				'margin-left': '-' + parseInt(((W - 50) / 2), 10) + 'px'
			});
			if (typeof document.body.style.maxWidth != 'undefined') {
                tbWindow.css({
                    'top': '20px',
                    'margin-top': '0'
                });
            }
		};
		
		return $('.media-buttons a.thickbox').each(function(){
			var href = this.href;
			if (!href) 
				return;
			href = href.replace(/&width=[0-9]+/g, '');
			href = href.replace(/&height=[0-9]+/g, '');
			this.href = href + '&width=' + (W - 80) + '&height=' + (H - 85);
		});
	});
})(jQuery);