<?php
function wysiwyg_options($leaf){
	if($leaf['new']){ 
		$leaf['config']['show-title'] = true; 
	}
	
	HeadwayLeafsHelper::create_tabs(array('content' => 'Content', 'miscellaneous' => 'Miscellaneous'), $leaf['id']);
	
	//////
	// Leaf Options That YOU Code
	HeadwayLeafsHelper::open_tab('content', $leaf['id']);
	HeadwayLeafsHelper::create_textarea(array('name' => 'content', 'value' => $leaf['options']['content'], 'no-border' => true), $leaf['id']);
	HeadwayLeafsHelper::close_tab();
	
	//////
	// Miscellaneous Options Found in Every Leaf
	HeadwayLeafsHelper::open_tab('miscellaneous', $leaf['id']);
		HeadwayLeafsHelper::create_show_title_checkbox($leaf['id'], $leaf['config']['show-title']);
		HeadwayLeafsHelper::create_title_link_input($leaf['id'], $leaf['config']['leaf-title-link']);
		HeadwayLeafsHelper::create_classes_input($leaf['id'], $leaf['config']['custom-css-classes'], true);
	HeadwayLeafsHelper::close_tab();
}

function wysiwyg_content($leaf){
	$content = $leaf['options']['content'];
	
	echo "<div class=\"entry-content\">\n".do_shortcode(wptexturize(stripslashes($content)))."\n</div>\n";
}

function wysiwyg_options_js($leaf){	
?>
jQuery(document).ready(function(){
	if(typeof jQuery().wysiwyg == 'undefined'){
		jQuery.getScript('<?php echo get_bloginfo('template_directory').'/library/leafs/includes/text/jquery.wysiwyg.js'; ?>', function(){
			jQuery('textarea#<?php echo $leaf['id']?>_content').wysiwyg({
				controls : {
					h1    : { visible : false },
					h1mozilla    : { visible : false },
					insertHorizontalRule : {visible : false}
				}
			});
		});
		
		jQuery('head').append('<link>');
		    css = jQuery('head').children(':last');
		    css.attr({
		      rel:  'stylesheet',
		      type: 'text/css',
		      href: '<?php echo get_bloginfo('template_directory').'/library/leafs/includes/text/jquery.wysiwyg.css'; ?>'
		});
	} else {
		jQuery('textarea#<?php echo $leaf['id']?>_content').wysiwyg({
			controls : {
				h1    : { visible : false },
				h1mozilla    : { visible : false },
				insertHorizontalRule : {visible : false}
			}
		});
	}
});
<?php	
}

$options = array(
			'id' => 'text',
			'name' => 'Text Leaf',
			'options_callback' => 'wysiwyg_options',
			'options_js_callback' => 'wysiwyg_options_js',
			'content_callback' => 'wysiwyg_content',
			'options_width' => 550,
			'icon' => get_bloginfo('template_directory').'/library/leafs/icons/text.png',
			'show_hooks' => true
		);

$wysiwyg = new HeadwayLeaf($options);