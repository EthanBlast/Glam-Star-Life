function disableIt(divid, disable) {
	disableElement(document.getElementById(divid), disable);
}
            
function disableElement(el, disable) {
	try {
		el.disabled = disable;
	}
	catch(E){}

	if (el.childNodes && el.childNodes.length > 0) {
		for (var x = 0; x < el.childNodes.length; x++) {
			disableElement(el.childNodes[x], disable);
		}
	}
}

function popup(url) 
{
	var width  = 800;
	var height = 600;
	var left   = (screen.width  - width)/2;
	var top    = (screen.height - height)/2;
	var params = 'width='+width+', height='+height;
	params += ', top='+top+', left='+left;
	params += ', directories=no';
	params += ', location=no';
	params += ', menubar=no';
	params += ', resizable=yes';
	params += ', scrollbars=yes';
	params += ', status=no';
	params += ', toolbar=no';
	newwin=window.open(url,'popup', params);
	if (window.focus) {newwin.focus()}
	return false;
}

function ltrim(str, chars) {
	chars = chars || "\\s";
	return str.replace(new RegExp("^[" + chars + "]+", "g"), "");
}

function flash_content (visibility) {
	embeds = document.getElementsByTagName('embed');
	for(i = 0; i < embeds.length; i++) {
		embeds[i].style.visibility = visibility;
	}
	objects = document.getElementsByTagName('object');
	for(i = 0; i < objects.length; i++) {
		objects[i].style.visibility = visibility;
	}
}

jQuery(document).ready(function($){

	$(function() {
		$("#quick_post_success").dialog({
			close: function() {
				location.href = location.href;
			},
			closeOnEscape: false,
			resizable: false,
			modal: true
		});
	});
			
	$('#quick_post_load').click(function(){
		var tinymce_path = $('#quick_post_tinymce_path').val();
		var plugin_path = $('#quick_post_plugin_path').val();
		var plugins = $('#quick_post_plugins').val();
		var buttons1 = $('#quick_post_buttons1').val();
		var buttons2 = $('#quick_post_buttons2').val();
		var file_manager = $('#quick_post_file_manager').val();
		var mce_locale = $('#quick_post_language').val();
		var label_ok = $('#quick_post_ok').val();
		var label_cancel = $('#quick_post_cancel').val();
		var width  = 605;
		var height = 520;
		var left   = (screen.width  - width)/2;
		var top    = ((screen.height - height)/2)-70;
		var quick_post_newlines = $('#quick_post_newlines').val();
		var root_block = 'P';
		var br_newlines = false;
		var p_newlines = true;
		var buttons = {};

		buttons[label_cancel] = function() {
				$(this).dialog('close');
		};

		buttons[label_ok] = function() {
				var content = tinyMCE.get('qpweditor').getContent();
				$('#quick_post_content').val(content);
				$(this).dialog('close')
		};
				

		if ( quick_post_newlines == 'BR' ) {
			root_block = false;
			br_newlines = true;
			p_newlines = false;
		};

		$('#quick_post_dialog').remove();
		$('body').append('<iframe style="display: none;"\/><div id="quick_post_dialog" \/>');
		$('#quick_post_dialog').dialog({	
			autoOpen: false,
			bgiframe: true,
			resizable: true,
			width: width,
			minWidth: 550,
			minHeight: 240,
			position: [left,top],
			closeOnEscape: false,
			beforeclose: function(event, ui) {
				tinyMCE.get('qpweditor').remove();
				$('#qpweditor').remove();
				flash_content('visible');
			},
			resize: function(event, ui) {
				var tble, frame, dialogHeight;
				frame = document.getElementById('qpweditor_ifr');
				if ( frame != null ) {
					tble = frame.parentNode.parentNode.parentNode.parentNode;
					tble.style.height = 'auto';
					dialogHeight = $('#quick_post_dialog').height();
					frame.style.height = (dialogHeight - 76) + "px";
				}
			}
		});

		$('#quick_post_dialog').dialog('option', 'title', 'Quick Post Widget Editor');
		$('#quick_post_dialog').dialog('option', 'modal', true);
		$('#quick_post_dialog').dialog('option', 'buttons', buttons);

		$('#quick_post_dialog').html('<textarea style="visibility: hidden" name="qpweditor" id="qpweditor"><\/textarea>');
		flash_content('hidden');
		$('#quick_post_dialog').dialog('open');
		tinyMCEPreInit_qpw = {
				base : tinymce_path,
				suffix : '',
				query : '',
				mceInit : {
					mode : 'exact',
					elements: 'qpweditor',
					auto_focus: 'qpweditor',
					language: mce_locale,
					theme: 'advanced',
					plugins: plugins,
					dialog: 'modal',
					spellchecker_languages:'+English=en,Danish=da,Dutch=nl,Finnish=fi,French=fr,German=de,Italian=it,Polish=pl,Portuguese=pt,Spanish=es,Swedish=sv',
					skin: 'o2k7',
					skin_variant: 'silver',
					theme_advanced_buttons1: buttons1,
					theme_advanced_buttons2: buttons2,
					theme_advanced_buttons3: '',
					theme_advanced_toolbar_location: 'top',
					theme_advanced_toolbar_align: 'center',
					theme_advanced_path: true,
					theme_advanced_statusbar_location: 'bottom',
					height: '400px',
					width: '100%',
					relative_urls: false,
					remove_script_host: false,
					convert_urls: false,
					apply_source_formatting: false,
					remove_linebreaks: true,
					gecko_spellcheck: true,
					entities: '38,amp,60,lt,62,gt',
					media_strict: false,
					paste_remove_styles: true,
					paste_remove_spans: true,
					paste_strip_class_attributes: 'all',
					forced_root_block: root_block,
					force_br_newlines: br_newlines,
					force_p_newlines: p_newlines,
					file_browser_callback : file_manager,
					setup : function(ed) {
						ed.onInit.add(function(ed) {
							tinyMCE.get('qpweditor').setContent($('#quick_post_content').val());
							tinyMCE.execCommand('mceRepaint');
						});
					}
				},
				load_ext : function(url,lang){var sl=tinymce.ScriptLoader;sl.markDone(url+'/langs/'+lang+'.js');sl.markDone(url+'/langs/'+lang+'_dlg.js');sl.markDone(url+'/themes/advanced/langs/'+lang+'.js');}
			};

		tinyMCEPreInit_qpw.load_ext(tinyMCEPreInit_qpw.base, mce_locale);
		if (plugins.length > 0) {
			tinymce.PluginManager.load("advlink", plugin_path + "advlink/editor_plugin.js");
			tinymce.PluginManager.load("advimage", plugin_path + "advimage/editor_plugin.js");
			tinymce.PluginManager.load("emotions", plugin_path + "emotions/editor_plugin.js");
			tinymce.PluginManager.load("preview", plugin_path + "preview/editor_plugin.js");
			tinymce.PluginManager.load("searchreplace", plugin_path + "searchreplace/editor_plugin.js");
			tinymce.PluginManager.load("inlinepopups", plugin_path + "inlinepopups/editor_plugin.js");
		}
		tinyMCE.init(tinyMCEPreInit_qpw.mceInit);
		return false;	
	});
});