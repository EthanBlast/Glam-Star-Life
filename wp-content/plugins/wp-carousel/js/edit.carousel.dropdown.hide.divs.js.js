function wp_carousel_hideDivs(){
	document.getElementById("show_in_loop_div").style.display = "none";
	document.getElementById("content_posts").style.display = "none";
	document.getElementById("content_pages").style.display = "none";
	document.getElementById("content_categories").style.display = "none";
	document.getElementById("posts_set_number").style.display = "none";
	document.getElementById("posts_set_order").style.display = "none";
}

function wp_carousel_doShow(){
	
	switch (window.document.forms.post_wordpress.type_list.item(window.document.forms.post_wordpress.type_list.selectedIndex).value) {
		
		case "2": wp_carousel_hideDivs();
		document.getElementById("content_posts").style.display = "block";
		document.getElementById("show_in_loop_div").style.display = "block";
		break;
		
		case "3": wp_carousel_hideDivs();
		document.getElementById("content_pages").style.display = "block";
		break;
		
		case "1": wp_carousel_hideDivs();
		document.getElementById("content_categories").style.display = "block";
		document.getElementById("posts_set_number").style.display = "block";
		document.getElementById("posts_set_order").style.display = "block";
		document.getElementById("show_in_loop_div").style.display = "block";
		break;
		
		default: wp_carousel_hideDivs();
		break;
		
	}
}

wp_carousel_doShow();