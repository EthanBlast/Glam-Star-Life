function utf8_encode ( argString ) {
    // http://kevin.vanzonneveld.net
    // +   original by: Webtoolkit.info (http://www.webtoolkit.info/)
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: sowberry
    // +    tweaked by: Jack
    // +   bugfixed by: Onno Marsman
    // +   improved by: Yves Sucaet
    // +   bugfixed by: Onno Marsman
    // +   bugfixed by: Ulrich
    // *     example 1: utf8_encode('Kevin van Zonneveld');
    // *     returns 1: 'Kevin van Zonneveld'

    var string = (argString+''); // .replace(/\r\n/g, "\n").replace(/\r/g, "\n");

    var utftext = "";
    var start, end;
    var stringl = 0;

    start = end = 0;
    stringl = string.length;
    for (var n = 0; n < stringl; n++) {
        var c1 = string.charCodeAt(n);
        var enc = null;

        if (c1 < 128) {
            end++;
        } else if (c1 > 127 && c1 < 2048) {
            enc = String.fromCharCode((c1 >> 6) | 192) + String.fromCharCode((c1 & 63) | 128);
        } else {
            enc = String.fromCharCode((c1 >> 12) | 224) + String.fromCharCode(((c1 >> 6) & 63) | 128) + String.fromCharCode((c1 & 63) | 128);
        }
        if (enc !== null) {
            if (end > start) {
                utftext += string.substring(start, end);
            }
            utftext += enc;
            start = end = n+1;
        }
    }

    if (end > start) {
        utftext += string.substring(start, string.length);
    }

    return utftext;
}

function base64_encode (data) {
    // http://kevin.vanzonneveld.net
    // +   original by: Tyler Akins (http://rumkin.com)
    // +   improved by: Bayron Guevara
    // +   improved by: Thunder.m
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   bugfixed by: Pellentesque Malesuada
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // -    depends on: utf8_encode
    // *     example 1: base64_encode('Kevin van Zonneveld');
    // *     returns 1: 'S2V2aW4gdmFuIFpvbm5ldmVsZA=='

    // mozilla has this native
    // - but breaks in 2.0.0.12!
    //if (typeof this.window['atob'] == 'function') {
    //    return atob(data);
    //}
        
    var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
    var o1, o2, o3, h1, h2, h3, h4, bits, i = 0, ac = 0, enc="", tmp_arr = [];

    if (!data) {
        return data;
    }

    data = this.utf8_encode(data+'');
    
    do { // pack three octets into four hexets
        o1 = data.charCodeAt(i++);
        o2 = data.charCodeAt(i++);
        o3 = data.charCodeAt(i++);

        bits = o1<<16 | o2<<8 | o3;

        h1 = bits>>18 & 0x3f;
        h2 = bits>>12 & 0x3f;
        h3 = bits>>6 & 0x3f;
        h4 = bits & 0x3f;

        // use hexets to index into b64, and append result to encoded string
        tmp_arr[ac++] = b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
    } while (i < data.length);
    
    enc = tmp_arr.join('');
    
    switch (data.length % 3) {
        case 1:
            enc = enc.slice(0, -2) + '==';
        break;
        case 2:
            enc = enc.slice(0, -1) + '=';
        break;
    }

    return enc;
}

function wp_carousel_update_ajax_item() {
	jQuery(function($){
		var wp_carousel_update_url = $("#current_url_js").attr("href");
		var wp_carousel_carousel_id = $("#carousel_id").text();

		var order = '';
		$("#sortable_carousel .wp_carousel_ajax_form").each(function(index) {
			if (index != 0)
			{
				order = order + '&';
			}
			order = order + 'iteration_' + index + '=' + base64_encode($(this).serialize());
		});
		order = order + '&action=updateSortableContent&internal_type=serialized&carousel_id=' + wp_carousel_carousel_id;
		
		//var order = $(".wp_carousel_ajax_form").serialize() + '&action=updateRecordsListings';
		$.post(wp_carousel_update_url, order, function(theResponse){
			$("#wp_carousel_ajax_response").html(theResponse);
		});
		
		//alert(order);
		
	});
	return false;
}

jQuery(document).ready(function($) {
	
	$("#items_in_carousel .costumized_content").each(function (index) {
		$("input#category_id", this).val(index);
	});
	
	$("#wp_carousel_ajax_loader, .changes_saved").hide();
	
	$("#wp_carousel_ajax_loader").ajaxStop(function() {
		$(this).hide(300);
		$("#wp_carousel_ajax_response").show(400);
	});
	
	$("#wp_carousel_ajax_loader").ajaxStart(function() {
		$(this).show(300);
		$("#wp_carousel_ajax_response").hide(400);
	});
	
	$(".wp_carousel_tabs_js").tabs();
	$(".js_hide, .add_form").hide();
	$("#content_posts").hide();
	$("#content_pages").hide();
	$("#content_categories").hide();
	$("#show_in_loop_div").hide();
	$("#posts_set_number").hide();
	$("#posts_set_order").hide();
	$("#sortable_items .item").draggable({
		connectToSortable: '.connected',
		placeholder: 'wp_carousel_ui-state-highlight',
		helper: 'clone',
		items: ".item", 
		handle: ".handle",
		revert: 'invalid'
	}).disableSelection();
	$("#sortable_deleted").sortable({
		items: ".item", 
		handle: ".handle",
		connectWith: '.connected2',
		update: function() {
			$("#sortable_deleted div").hide(500);
			wp_carousel_update_ajax_item();
		}
	});
	$("#sortable_carousel").sortable({
		connectWith: '.connected, .connected2',
		items: ".item", 
		handle: ".handle",
		placeholder: 'wp_carousel_ui-state-highlight',
		cancel: '.clear, .wp_carousel_disable_drag',
		update: function() {
			$(".add_form", this).show(500);
			$(".pre_dropped", this).hide(500);
			$(".post_dropped", this).show(500);
			$("#items_in_carousel .item").each(function (index) {
				$("input#order", this).val(index);
			});
			$("#items_in_carousel .costumized_content").each(function (index) {
				$("input#category_id", this).val(index);
			});
			wp_carousel_update_ajax_item();
		}
	}).disableSelection();
});