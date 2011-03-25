<?php
//Load main functions
require_once HEADWAYLIBRARY.'/visual-editor/functions.php';

//Load visual editor content
require_once HEADWAYLIBRARY.'/visual-editor/content/boxes.php';
require_once HEADWAYLIBRARY.'/visual-editor/content/panels.php';
require_once HEADWAYLIBRARY.'/visual-editor/content/form.php';
require_once HEADWAYLIBRARY.'/visual-editor/content/main.php';

//Load color analysis library
require_once HEADWAYLIBRARY.'/visual-editor/misc/color-analysis.php';

add_action('init', 'headway_visual_editor', 12);
function headway_visual_editor(){	
	if(!headway_can_visually_edit()) return false;
					
	if(isset($_GET['visual-editor'])){			
		header("Cache-Control: no-cache, must-revalidate");
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
		
		//WordPress Multi-Site support API
		if (is_multisite()) {
			require_once(ABSPATH.'wp-admin/includes/ms.php');
			require_once(ABSPATH.'wp-admin/includes/ms-deprecated.php');
		}
		
		//Remove 3.1 bar
		remove_action('init', 'wp_admin_bar_init');
		remove_action('wp_footer', 'wp_admin_bar_render', 1000 );
		remove_action('admin_footer', 'wp_admin_bar_render', 1000 );
		remove_action('wp_head', 'wp_admin_bar_css' );
		remove_action('admin_head', 'wp_admin_bar_css' );
		
		//Deactivate MU Domain Mapping
		remove_filter( 'plugins_url', 'domain_mapping_plugins_uri', 1 );
		remove_filter( 'theme_root_uri', 'domain_mapping_themes_uri', 1 );
		remove_filter( 'pre_option_siteurl', 'domain_mapping_siteurl' );
		remove_filter( 'pre_option_home', 'domain_mapping_siteurl' );
		remove_filter( 'the_content', 'domain_mapping_post_content' );
		remove_action( 'wp_head', 'remote_login_js_loader' );
		remove_action( 'login_head', 'redirect_login_to_orig' );
		remove_action( 'wp_logout', 'remote_logout_loader', 9999 );
		remove_action( 'template_redirect', 'redirect_to_mapped_domain' );
				
		wp_deregister_script('jquery-ui-core');
		wp_deregister_script('jquery-ui-tabs');
		wp_deregister_script('jquery-ui-draggable');
		wp_deregister_script('jquery-ui-droppable');
		wp_deregister_script('jquery-ui-resizable');
		wp_deregister_script('jquery-ui-sortable');
		
		wp_register_script('jquery-no-conflict', get_bloginfo('template_directory').'/library/visual-editor/js/jquery.no-conflict.js', array('jquery'), HEADWAYVERSION);
		wp_register_script('headway-jquery-ui-core', get_bloginfo('template_directory').'/library/visual-editor/js/jquery.ui.packed.js', array('jquery', 'jquery-no-conflict'), '1.8.5');
		
		wp_enqueue_script('headway-ve-includes', get_bloginfo('template_directory').'/library/visual-editor/js/includes.js', array('headway-jquery-ui-core'), HEADWAYVERSION);
		
		add_action('wp', 'headway_layout_chooser_slider_form_action');
		add_action('headway_stylesheets', 'headway_visual_editor_head', 12);
		add_action('headway_scripts', 'headway_visual_editor_scripts', 12);
		
		add_filter('wp_title', 'headway_visual_editor_title', 12);
	
		if(isset($_COOKIE['headway-visual-editor-ie']) || !is_ie()){
			add_action('headway_before_everything', 'headway_pre_visual_editor', 1);
			add_action('headway_before_everything', 'headway_ve_form_start', 2);
			add_action('headway_after_everything', 'headway_ve_form_end', 2);
		} else {
			add_action('headway_before_everything', 'headway_ie_box', 1);
		}
	} else {
		if(!headway_get_option('hide-visual-editor-link')){
			wp_enqueue_style('headway-visual-editor-mini', get_bloginfo('template_directory').'/library/visual-editor/css/visual-editor-mini.css');
			add_action('headway_before_everything', 'headway_visual_editor_link', 1);
		}
	}
}


function headway_visual_editor_title($title){
	return 'Visual Editor: '.$title;
}


add_action('init', 'headway_visual_editor_redirect', 1);
function headway_visual_editor_redirect(){
	if(strpos(get_bloginfo('wpurl'), get_bloginfo('siteurl')) !== false)
		return;
	
	$original_url = (function_exists('get_original_url')) ? get_original_url ('siteurl') : get_bloginfo('wpurl');
	
	if(isset($_GET['visual-editor']) && strpos(headway_current_url(), $original_url) === false){
		header('Location:'.$original_url.'/?visual-editor=true');
		die();
	}
}


add_action('admin_bar_menu', 'headway_wordpress_menu_node', 75);
function headway_wordpress_menu_node(){
	if(!headway_can_visually_edit()) return false;
	
	$current_url = str_replace(array('dismiss-headway-nag=', 'headway-folder-nag', 'cache-folder-nag'), '', $_SERVER['REQUEST_URI']);
	
	$sign = (strpos($current_url, '?')) ? '&amp;' : '?';
	$system_page = headway_is_system_page(false, true) ? '&amp;visual-editor-system-page='.headway_current_page(true) : false;
	
	global $wp_admin_bar;
	$wp_admin_bar->add_menu(array('id' => 'headway-ve', 'title' => 'Headway Visual Editor',  'href' =>  $current_url.$sign.'visual-editor=true'.$system_page));
}


function headway_pre_visual_editor(){
	do_action('headway_pre_visual_editor');
}


function headway_visual_editor_scripts(){		
	echo '<script type="text/javascript" src="'.HEADWAYURL.'/library/visual-editor/js/visual-editor-options.js"></script>'."\n";

	if(!headway_is_page_linked())
		echo '<script type="text/javascript" src="'.HEADWAYURL.'/library/visual-editor/js/visual-editor-leafs.js"></script>'."\n";
	
	if(!headway_get_option('disable-visual-editor') && !headway_get_option('enable-developer-mode') && !headway_is_skin_active())
		echo '<script type="text/javascript" src="'.HEADWAYURL.'/library/visual-editor/js/visual-editor-design-editor.js"></script>'."\n";
				
	echo '<script type="text/javascript" src="'.HEADWAYURL.'/library/visual-editor/js/visual-editor.js"></script>'."\n";
?>

<script type="text/javascript">
	headway_blog_url = "<?php echo get_bloginfo('siteurl') ?>";
	headway_template_directory = "<?php echo get_bloginfo('template_directory') ?>";
	headway_is_linked = <?php echo headway_is_page_linked() ? 'true' : 'false' ?>;
</script>
<?php				
}


function headway_visual_editor_head(){	
	echo '<link rel="stylesheet" type="text/css" href="'.get_bloginfo('template_directory').'/library/visual-editor/css/visual-editor.css" />';
	echo '<link rel="stylesheet" type="text/css" href="'.get_bloginfo('template_directory').'/library/shared-media/css/colorpicker.css" />';

	do_action('headway_visual_editor_head');	
}

function headway_visual_editor_link(){
	global $wp_admin_bar;
	
	if(isset($wp_admin_bar)) return false;
	
	$current_url = headway_current_url();
	
	$sign = (strpos($current_url, '?')) ? '&amp;' : '?';
	$system_page = headway_is_system_page(false, true) ? '&amp;visual-editor-system-page='.headway_current_page(true) : false;
	
	if(!isset($_GET['skin-preview']) && !defined('HEADWAYHIDEMENUS') && !isset($_GET['headway-skin-preview'])){	
		echo '<p class="visual-editor-link"><a href="'.$current_url.$sign.'visual-editor=true'.$system_page.'">'.__('Enter Visual Editor', 'headway').'</a></p>';
		echo '<p class="visual-editor-link wordpress-admin-link"><a href="'.get_bloginfo('wpurl').'/wp-admin" target="_blank">'.__('WordPress Admin', 'headway').'</a></p>';
	}
}


function headway_layout_chooser_slider_form_action(){
	if(isset($_POST['switch-layout']) && $_POST['switch-layout'] == true){
		if(isset($_POST['layout-page']) && $_POST['layout-page'] == true){
			$sign = (strpos(get_permalink($_POST['layout-page']), '?')) ? '&' : '?';
			$safe_mode = (isset($_GET['safe-mode'])) ? '&safe-mode=true' : false;
			
			$link = str_replace(home_url(), get_bloginfo('wpurl'), get_permalink($_POST['layout-page']));
			
			header('Location: '.$link.'/'.$sign.'visual-editor=true'.$safe_mode);
		}
		elseif(isset($_POST['layout-system-page']) && $_POST['layout-system-page'] == true){
			if($_POST['layout-system-page'] == 'category'){
				$cats = get_categories(false);
				
				foreach($cats as $cat){
					$cat_id = $cat->term_id;
					break;
				}	

				$system_page_link = get_category_link($cat_id);
			}
			if($_POST['layout-system-page'] == 'four04') $system_page_link = home_url().'/'.rand(10000, 50000);
			if($_POST['layout-system-page'] == 'archives'){ 
				preg_match('/href=\'(.*)\'/', wp_get_archives( array('type' => 'monthly', 'limit' => 1, 'format' => 'link', 'echo' => 0) ), $regs);
				$system_page_link =  $regs[1];
			}
			if($_POST['layout-system-page'] == 'single'){ 
				global $post;
				$single_loop = get_posts('showposts=1&post_type=post');
				foreach($single_loop as $post) $system_page_link = get_permalink(get_the_id());
			}
			if(strpos($_POST['layout-system-page'], 'custom-single-') !== false){ 
				$type = str_replace('custom-single-', '', $_POST['layout-system-page']);
				global $post;
				$loop = get_posts('post_type='.$type);
								
				if(is_array($loop)){
					foreach($loop as $page){
						$system_page_link = $page->guid;
						
						break;
					}
				}
			}
			if($_POST['layout-system-page'] == 'index'){ 
				if(get_option('show_on_front') == 'page'){
					$system_page_link = get_permalink(get_option('page_for_posts'));
				}
				else
				{
					$system_page_link = home_url();
				}
			}
			if($_POST['layout-system-page'] == 'tag') { 
										
				$tags = get_tags(array('number' => 1));
								
				foreach($tags as $tag){
					$tag_id = $tag->term_id;
					break;
				}
						
				$system_page_link = get_tag_link($tag_id);
								
			}
			if($_POST['layout-system-page'] == 'author'){
				$authors = get_users_of_blog(false);
							
				foreach($authors as $author){
					$author_id = $author->user_id;
					break;
				}
									
				$system_page_link = home_url().'/?author='.$author_id;
			}
			
			if($_POST['layout-system-page'] == 'search') $system_page_link = home_url().'/?s=%20';			

			if(!is_object($system_page_link)){
				$sign = (strpos($system_page_link, '?')) ? '&' : '?';
				$sign = (strpos(get_option('permalink_structure'), '/') && !strpos($system_page_link, '?')) ? '?' : $sign;
			}
			
			$safe_mode = (isset($_GET['safe-mode'])) ? '&safe-mode=true' : false;
			
			$system_page_link = str_replace(home_url(), get_bloginfo('wpurl'), $system_page_link);
			
			if(!is_object($system_page_link)){
				header('Location: '.$system_page_link.$sign.'visual-editor=true&visual-editor-system-page='.$_POST['layout-system-page'].$safe_mode);
			} else {
				header('Location: '.$_SERVER["REQUEST_URI"]);
			}
		}
	}
}