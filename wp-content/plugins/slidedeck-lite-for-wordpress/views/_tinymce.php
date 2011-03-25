<?php
/**
 * TinyMCE initiation blocks for Edit/Create WYSIWYG editors
 * 
 * @package SlideDeck
 * 
 * @uses bloginfo()
 */
?>
<script type="text/javascript">
/* <![CDATA[ */
tinyMCEPreInit = {
	base : "<?php echo bloginfo( 'wpurl' ); ?>/wp-includes/js/tinymce",
	suffix : "",
	mceInit : {
		mode : "specific_textareas", 
		editor_selector : "slide-content", 
		width : "100%", 
		theme : "advanced", 
		skin : "wp_theme", 
		theme_advanced_buttons1 : "bold,italic,strikethrough,|,bullist,numlist,blockquote,|,justifyleft,justifycenter,justifyright,|,link,unlink,|,spellchecker,fullscreen,wp_adv", 
		theme_advanced_buttons2 : "formatselect,underline,justifyfull,forecolor,|,pastetext,pasteword,removeformat,|,media,charmap,|,outdent,indent,|,undo,redo,wp_help", 
		theme_advanced_buttons3 : "", 
		theme_advanced_buttons4 : "", 
		language : "en", 
		spellchecker_languages : "+English=en,Danish=da,Dutch=nl,Finnish=fi,French=fr,German=de,Italian=it,Polish=pl,Portuguese=pt,Spanish=es,Swedish=sv", 
		theme_advanced_toolbar_location : "top", 
		theme_advanced_toolbar_align : "left", 
		theme_advanced_resize_horizontal : "", 
		dialog_type : "modal", 
		relative_urls : "", 
		remove_script_host : "", 
		convert_urls : "", 
		apply_source_formatting : "", 
		remove_linebreaks : "1", 
		gecko_spellcheck : "1", 
		entities : "38,amp,60,lt,62,gt", 
		accessibility_focus : "1", 
		tabfocus_elements : "major-publishing-actions", 
		media_strict : "", 
		paste_remove_styles : "1", 
		paste_remove_spans : "1", 
		paste_strip_class_attributes : "all", 
		wpeditimage_disable_captions : "", 
		plugins : "safari,inlinepopups,spellchecker,paste,wordpress,media,fullscreen,wpeditimage,wpgallery,tabfocus"
	},
	go : function() {
		var t = this, sl = tinymce.ScriptLoader, ln = t.mceInit.language, th = t.mceInit.theme, pl = t.mceInit.plugins;
	
		sl.markDone(t.base + '/langs/' + ln + '.js');
	
		sl.markDone(t.base + '/themes/' + th + '/langs/' + ln + '.js');
		sl.markDone(t.base + '/themes/' + th + '/langs/' + ln + '_dlg.js');
	
		tinymce.each(pl.split(','), function(n) {
			if (n && n.charAt(0) != '-') {
				sl.markDone(t.base + '/plugins/' + n + '/langs/' + ln + '.js');
				sl.markDone(t.base + '/plugins/' + n + '/langs/' + ln + '_dlg.js');
			}
		});
	},
	load_ext : function(url,lang){
		var sl=tinymce.ScriptLoader;
		
		sl.markDone(url+'/langs/'+lang+'.js');
		sl.markDone(url+'/langs/'+lang+'_dlg.js');
	}
};
/* ]]> */
</script>

<?php
	$filename = get_bloginfo('wpurl') . "/wp-includes/js/tinymce/";
	if(file_exists( ABSPATH . "/wp-includes/js/tinymce/wp-tinymce.php" )) {
		$filename.= "wp-tinymce.php";
	} else {
		$filename.= "tiny_mce.js";
	}
?>
<script type="text/javascript" src="<?php echo $filename; ?>?c=1"></script>
<script type="text/javascript" src="<?php echo bloginfo('wpurl'); ?>/wp-includes/js/tinymce/langs/wp-langs-en.js"></script>

<script type="text/javascript">
/* <![CDATA[ */
tinyMCEPreInit.go();
tinyMCE.init(tinyMCEPreInit.mceInit);
/* ]]> */
</script>