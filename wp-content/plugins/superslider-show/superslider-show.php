<?php
/*
Plugin Name: SuperSlider-Show
Plugin URI: http://wp-superslider.com/superslider/superslider-show/
Description: Animated Gallery slideshow uses Mootools 1.2 javascript and Slideshow2 to replace wordpress gallery with a slideshow. 
Author: Daiv Mowbray
Author URI: http://wp-superslider.com
Version: 2.7.7

credits:
squeezebox - Harald Kirschner <http://www.digitarald.de>
slimbox - Christophe Beyls <http://www.digitalia.be>

Copyright 2008
       SuperSlider-Show is free software; you can redistribute it and/or
    modify it under the terms of the GNU General Public License as published by 
    the Free Software Foundation; either version 2 of the License, or (at your
    option) any later version.

    SuperSlider-show is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Collapsing Categories; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if (!class_exists("ssShow")) {
	class ssShow {
		
		/**
		* @var names used in this class.
		*/
	var $site_url;
	var $js_path;
	var $css_path;
	var $css_theme_override = '';
	var $css_theme = '';
	var $theme_function;
	var $light_path;
	var $kenburns_js = '';
	var $push_js = '';
	var $flash_js = '';
	var $fold_js = '';	
	var $shrink_js = '';
	var $Slim_over_ride = '';
	var $shortcode_showtype;
	var $ssShowOpOut ;//= array()	
	var $defaultAdminOptions;
	var $AdminOptionsName = 'ssShow_options';
	var $ssBaseOpOut;
	var $ssShow_domain = 'superslider-show';

	// get a number to make the slideshow unique
	var $show_id;

		/**
		* PHP 4 Compatible Constructor
		*/
	function ssShow() {	//$this->__construct();
			
		ssShow::superslider_show();

		}
				
		/**
		* PHP 5 Constructor
		*/		
	function __construct(){
		
		self::superslider_show();
	
	}
	
		/**
		*	Pre-2.6 compatibility
		*/
	function set_show_paths()
	{
	$this->site_url = get_option( 'siteurl' );
		if ( !defined( 'WP_CONTENT_URL' ) )
			define( 'WP_CONTENT_URL', $this->site_url . '/wp-content' );
		if ( !defined( 'WP_CONTENT_DIR' ) )
			define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
		if ( !defined( 'WP_PLUGIN_URL' ) )
			define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
		if ( !defined( 'WP_PLUGIN_DIR' ) )
			define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );
		if ( !defined( 'WP_LANG_DIR') )
			define( 'WP_LANG_DIR', WP_CONTENT_DIR . '/languages' );
	}
	
		/**
		 * language switcher
		 */
	function language_switcher(){
		global $ssShow_domain, $ssShow_is_setup;

  				if ( $ssShow_is_setup) {
     				return;
  				} 
  			// define some language related variables
		$ssShow_domain = 'superslider-show';
  		$ss_show_locale = get_locale();
		$ss_show_mofile = WP_LANG_DIR."/superslider_show-".$ss_show_locale.".mo";
  				//load the language
  			load_plugin_textdomain($ssShow_domain, $ss_show_mofile);
  			$plugin_text_loaded = true; // language is loaded
	}
	
			/**
		* Retrieves the options from the database.
		* @return array
		*/			
	function set_default_admin_options() {
		global $defaultAdminOptions; 
		
		$defaultAdminOptions = array(
				'ss_shortcode' => "gallery",
				'show_shortcode' => "true",	
				'id' => "",
				'load_moo' => "on",
				'css_load' => "default",		
				'css_theme' => "default",
				'show_class' => "",
				'href'	=>	"",
				'show_type' => "default",
				'image_size' => "medium",
				'pop_size'	=>	"large",
				'first_slide' => "0",
				'limit' =>  "50",
				'zoom' => "25",
				'pan' => "25, 75",
				'color' => "#fff",
				'height' => "400",
				'width' => "450",
				'clear'	=>	"both",
				'center' => "true",
				'resize' => "true",
				'linked' => "lightbox",
				'fast' => "false",
				'captions' => "true",
				'caption_title' => "post_title",
				'caption_text' => "image_description",
				'caption_link' => "image_title",
				'caption_link_text' => "",
				
				'overlap' => "true",
				'thumbnails' => "true",
				'thumbframe' => "on",
				'mouseover'	=> "false",
				'mythumbon' => "on",
				'thumbsize' => "thumbnail",
				'mythumb_height' => "80",
				'mythumb_width' => "80",
				'thumbcrop' => "on",
				'myslide_height' => "360",
				'myslide_width' => "480",
				'myslide_crop' => "off",
				'paused' => "false",
				'random' => "false",
				'loop' => "true",
				'loader' => "true",
				'delay' => "4000",
				'controller' => "true",
				'exclude'	=> "",
				'duration' => "1200",
				'trans_type' => "sine",
				'trans_inout' => "out",
				
				'accesskeys'=> "",//'first': 'shift + left', 'prev': 'left', 'pause': 'p', 'next': 'right', 'last': 'shift + right' 
				'properties' => "",//'href', 'rel', 'rev', 'title'
				'preload' => 'false',
				'replace' => "",
				
				'tool_tips' => "true",
				'lightbox_add' => "on",
				'squeeze_width' => "600",
				'squeeze_height' => "450",
				'lightbox_type' => "Lightbox",
				'recent_limit' => "1",
				'delete_options' => '');
		
		$defaultOptions = get_option($this->AdminOptionsName);
		if (!empty($defaultOptions)) {
			foreach ($defaultOptions as $key => $option) {
				$defaultAdminOptions[$key] = $option;
			}
		}
		update_option($this->AdminOptionsName, $defaultAdminOptions);
		return $defaultAdminOptions;
		
	}

		/**
		* Saves the admin options to the database.
		*/
	function save_default_show_options(){
		update_option($this->AdminOptionsName, $this->defaultAdminOptions);
	}
		
		/**
		* load default options into data base
		*/		
	function ssShow_init() {

		$this->set_show_paths();
		$this->defaultAdminOptions = $this->set_default_admin_options();			
		
		// lets see if the base plugin is here and get its options
		if (class_exists('ssBase')) {
				$this->ssBaseOpOut = get_option('ssBase_options');
				extract($this->ssBaseOpOut);
				$this->base_over_ride = $ss_global_over_ride;
		}else{
			$this->base_over_ride = 'false';
		}
		if (class_exists('ssSlim') )  $this->Slim_over_ride = 'true'; 
			
		$this->js_path = WP_CONTENT_URL . '/plugins/'. plugin_basename(dirname(__FILE__)) . '/js/';
  		$admin_js_path = WP_CONTENT_URL . '/plugins/'. plugin_basename(dirname(__FILE__)) . '/admin/js/';
  		
        wp_register_script( 'moocore', $this->js_path.'mootools-1.2.3-core-yc.js', NULL, '1.2.3');        
        wp_register_script( 'moomore', $this->js_path. 'mootools-1.2.3.1-more.js', array( 'moocore' ), '1.2.3');                
        wp_register_script( 'slideshow', $this->js_path. 'slideshow.js', array( 'moomore' ), '2');        
        wp_register_script( 'squeezebox', $this->js_path.'squeezebox.js', array( 'moomore' ), '2', true);        
        wp_register_script( 'slimbox', $this->js_path.'slimbox.js', array( 'moomore' ), '2', true);        
        wp_register_script( 'lightbox', $this->js_path.'lightbox.js', array( 'moomore' ), '2', true);
                
	    wp_register_script( 'jquery-dimensions', $admin_js_path.'jquery.dimensions.min.js', array( 'jquery' ), '2', false);	    
	    wp_register_script( 'jquery-tooltip', $admin_js_path.'jquery.tooltip.min.js', array( 'jquery-dimensions' ), '2', false);
	    wp_register_script( 'superslider-show-info', $admin_js_path.'superslider-show-info.js', array( 'jquery-tooltip' ), '2', false);
	    wp_register_script( 'superslider-admin-tool', $admin_js_path.'superslider-admin-tool.js', array( 'jquery-tooltip' ), '2', false);
	    wp_register_script( 'superslider-show-box', $admin_js_path.'superslider-show-box.js',  NULL , '2', false);
	
	}		
		/**
		* Load admin options page
		*/
	function ssShow_ui() {		
		global $base_over_ride;
		global $ssShow_domain;
		
		include_once 'admin/superslider-show-ui.php';
		
	}

		/**
		* Initialize the admin panel, Add the plugin options page, loading it in from superslider-show-ui.php
		*/
	function ssShow_setup_optionspage() {
		if (  function_exists('add_options_page') ) {
			if (  current_user_can('manage_options') ) {
				if (!class_exists('ssBase')) $plugin_page = add_options_page(__('SuperSlider Show'),__('SuperSlider-Show'), 8, 'superslider-show', array(&$this, 'ssShow_ui'));
				add_filter('plugin_action_links_' . plugin_basename(__FILE__), array(&$this, 'filter_plugin_show'), 10, 2 );
				
				add_action('admin_print_styles', array(&$this,'ssShow_admin_style'));

				if (!class_exists('ssBase')) add_action('admin_print_scripts-'.$plugin_page, array(&$this,'ssshow_admin_script'));
			}					
		}
	}
		/**
		* Add link to options page from plugin list WP 2.6.
		*/
	function filter_plugin_show($links, $file) {
		 static $this_plugin;
			if (  ! $this_plugin ) $this_plugin = plugin_basename(__FILE__);

		if (  $file == $this_plugin )
			$settings_link = '<a href="admin.php?page=superslider-show">'.__('Settings').'</a>';
			array_unshift( $links, $settings_link ); //  before other links
			return $links;
	}
	
		/**
		* Removes user set options from data base upon deactivation
		*/
		
	function options_deactivation(){
		if($this->ssShowOpOut[delete_options] == true){
            delete_option($this->AdminOptionsName);
            delete_option('slideshow_size_w');
            delete_option('slideshow_size_h');
            delete_option('slideshow_crop');
            delete_option('minithumb_size_w');
            delete_option('minithumb_size_h');
            delete_option('minithumb_crop');
		}
	}

	function ssShow_add_javascript(){

		extract($this->ssShowOpOut);

		$lightbox_type = strtolower($lightbox_type);
		
		$this->kenburns_js = "\t".'<script src="'.$this->js_path.'slideshow.kenburns.js" type="text/javascript"></script> '."\n";
		$this->push_js = "\t".'<script src="'.$this->js_path.'slideshow.push.js" type="text/javascript"></script> '."\n";
		$this->fold_js = "\t".'<script src="'.$this->js_path.'slideshow.fold.js" type="text/javascript"></script> '."\n";
		$this->flash_js = "\t".'<script src="'.$this->js_path.'slideshow.flash.js" type="text/javascript"></script> '."\n";		
		$this->lightbox_js = "\t".'<script src="'.$this->js_path.''.$lightbox_type.'.js" type="text/javascript"></script> '."\n";
		
		wp_register_script('kenburns', $this->js_path.'slideshow.kenburns.js', array( 'slideshow' ), '2');
		wp_register_script('push', $this->js_path.'slideshow.push.js', array( 'slideshow' ), '2');
		wp_register_script('fold', $this->js_path.'slideshow.fold.js', array( 'slideshow' ), '2');
		wp_register_script('flash', $this->js_path.'slideshow.flash.js', array( 'slideshow' ), '2');
		
				
		if (!is_admin() ){
		  if( function_exists('wp_enqueue_script') && $this->base_over_ride != "on" && $load_moo == 'on') {
			wp_enqueue_script('moocore');		
			wp_enqueue_script('moomore');
		   }
		  wp_enqueue_script('slideshow');		
		  wp_enqueue_script($show_type);
        
          if ($lightbox_add == 'on') wp_enqueue_script(strtolower($lightbox_type));	
					
		}// is not admin
	}
		/**
		* register and Add css script into head 
		*/
	function ssShow_create_css(){
		extract($this->ssShowOpOut);
  
        if (($css_load == 'off')) break;

		if ( (class_exists('ssBase')) && ($this->ssBaseOpOut['ss_global_over_ride']) ) extract($this->ssBaseOpOut);

        if ($this->css_theme_override == 'true') $css_theme = $this->css_theme;

		if (class_exists('ssSlim') && ($lightbox_type == 'Slimbox')) { 
            $ssSlimbox_ops = get_option('ssSlimbox_options');
            $slim_css_theme = $ssSlimbox_ops[css_theme];
        }			
		$lightbox_type = strtolower($lightbox_type);

   		if ($css_load == 'default'){    		
  			$inner_path = 'superslider-show/plugin-data/superslider/ssShow';
  			
            if ( ($this->Slim_over_ride == "true") && ($lightbox_type == 'slimbox')) {              
                $this->light_path = WP_PLUGIN_URL.'/superslider-slimbox/plugin-data/superslider/ssSlimbox/'.$slim_css_theme.'/'.$slim_css_theme.'.css';
 
            }else{
    	      $this->light_path = WP_PLUGIN_URL.'/'.$inner_path.'/'.$lightbox_type.'/'.$lightbox_type.'.css';  	      
    	    }
    	 
    	$this->css_path = WP_PLUGIN_URL.'/'.$inner_path.'/'.$css_theme.'/'.$css_theme.'.css';
    	 
    	}elseif ($css_load == 'pluginData') { 
    	   $inner_path = 'plugin-data/superslider/ssShow';
    		
           if ( ($this->Slim_over_ride == "true") && ($lightbox_type == 'SlimBox')) {
                $this->light_path = WP_CONTENT_URL.'/plugin-data/superslider/ssSlimbox/'.$slim_css_theme.'/'.$slim_css_theme.'.css';
    	       
    	    }else{    	      
    	      $this->light_path = WP_CONTENT_URL.'/'.$inner_path.'/'.$lightbox_type.'/'.$lightbox_type.'.css';    	       
    	    }
    	        
        $this->css_path = WP_CONTENT_URL.'/'.$inner_path.'/'.$css_theme.'/'.$css_theme.'.css';
        }	

	}
	function ssShow_add_css(){		
        $this->ssShow_create_css();
    	
    	wp_register_style('slideshow_style', $this->css_path);			
		wp_register_style('show_lightbox_style', $this->light_path);

		wp_enqueue_style('slideshow_style');			
        if ($this->ssShowOpOut[lightbox_add] == 'on'){	
              wp_enqueue_style('show_lightbox_style');              
        }

	}

           /**
           *  get images from folder function
           */
      function ssShow_find_images($fromfolder) {
            
            $minwidth = "200";
			$linkpath= WP_CONTENT_URL."/".$fromfolder;
			$imagepath = WP_CONTENT_DIR."/".$fromfolder;

            $pattern="(\.jpg$)|(\.png$)|(\.jpeg$)|(\.gif$)"; //valid image extensions
            $files = array();
            $curimage=0;
            
            if($handle = opendir($imagepath)) { // if folder is found
                while(false !== ($file = readdir($handle))){ // loop through the images
                
                if(eregi($pattern, $file)){ //if this file is a valid image
                   
                   $path_file = $imagepath.'/'.$file;
                   $link_file = $linkpath.'/'.$file;
                   $size = getimagesize($path_file); // lets get the image size
                   $width = $size[0];
                   
                       if($width > $minwidth){ // if this image is larger than min_width
                       $caption = substr($file, 0, -4); // make a caption from the image name
                       
                         $file = "'".$file ."': { caption: '".$caption."', href: '".$link_file."'}";
                         
                         array_push($files, $file); // add the file entry to the array of files
                         
                        $curimage++;
                        }
                    }
                }
                $files = join(", ", $files); // seperate the file list with commas
 
                return $files;
                closedir($handle); // close the folder
                
            }
        }
		
		
		/**
		* Write the slideshow code 
		*/
	function ssShow_set_show($atts,$id) {

		extract($this->ssShowOpOut);
		
			if (array_key_exists('transition', $atts) && ( $atts['transition'] != '')) {
				$transition = $atts['transition'];
				} else {
				$transition = $trans_type.':'.$trans_inout;	
			}

		//set the path for the loader graphics
		if ($css_load == 'default' || $css_load == 'off') {
			$loaderPath = WP_PLUGIN_URL.'/superslider-show/plugin-data/superslider/ssShow/loader/loader-#.png';
		}elseif ($css_load == 'pluginData') {
			$loaderPath = WP_CONTENT_URL.'/plugin-data/superslider/ssShow/loader/loader-#.png';
		}
		
		// give the show a name and number
		$showName = "this.el = $('slide_gallery".$this->show_id."');";
		
		if ( $accesskeys !== '' ) $myaccesskeys = "accesskeys: {".stripslashes($accesskeys)." },";
		if ( $properties !== '' ) $myproperties = "properties: [".stripslashes($properties)."],";
		$mypreload = "preload:".$preload.",";
		if ( $replace !== '' && $fromfolder !== '') $myreplace = "replace:[/(\.[^\.]+)$/, '".$replace."$1'],";

		// Create the lightbox binders
		if ($linked == 'lightbox') {
				if ($lightbox_type == 'SqueezeBox') {
					$mylightbox = 'SqueezeBox.initialize({
									size: {x: '.$squeeze_width.', y: '.$squeeze_height.'},
									sizeLoading: {x: 150, y: 150},
									marginInner: {x: 18, y: 18},
									closeBtn: true,
									overlayOpacity: 0.6,
									resizeFx: {transition: Fx.Transitions.Sine.easeIn},
									contentFx: {transition: Fx.Transitions.Sine.easeOut},
									onOpen: function(){ ssShow'.$this->show_id.'.pause(1); }.bind(window.ssShow'.$this->show_id.'), 
						  			onClose: function(){ ssShow'.$this->show_id.'.pause(0); }.bind(window.ssShow'.$this->show_id.')						
									});
									SqueezeBox.assign($$(\'a[rel=squeezebox:ssShow]\'));
									';				
				} else if ($lightbox_type == 'Slimbox'){

				add_action ( "wp_footer", array(&$this,"slimbox_linker"));
					$mylightbox = '';
								
				} else {
					$mylightbox = 'var popbox = new '.$lightbox_type.'({ 

						   	onOpen : function(){ this.pause(1); }.bind(ssShow'.$this->show_id.'), 
						  	onClose : function(){ this.pause(0); }.bind(ssShow'.$this->show_id.'),
						  	initialWidth : 50,
							initialHeight : 50,
							showControls : true,
							showNumbers : true,
							descriptions : true
						});';
				}
		}else { $mylightbox = '';
		}
			
		// Add mouseover image stop function
		if ($mouseover == 'true') {
			$mymouse = '
			ssShow'.$this->show_id.'.slideshow.addEvents({
			  \'mouseenter\': function(){ this.pause(1); }.bind(ssShow'.$this->show_id.'),
			  \'mouseleave\': function() { this.pause(0); }.bind(ssShow'.$this->show_id.')			
			});
			';
		}else { $mymouse = '';}

		// transfer options into objects before constructing the slideshow js call.
		
		if ($thumbnails == 'true') {
			$mythumb = 'fast: '.$fast.',
			thumbnails: { duration: 700, transition: Fx.Transitions.Circ.easeOut},';
				if ( $thumbframe == 'on') {
					$mythumb .= 'thumbframe: true,';
				}else { $mythumb .= 'thumbframe: false,'; }
			$mythumbcover = "['a', 'b'].each(function(p){ 
						  new Element('div', { 'class': 'overlay ' + p }).inject(ssShow".$this->show_id.".slideshow.retrieve('thumbnails'));
				  		}, this);";
			} else{
			$mythumb = 'fast: false,
			thumbnails: false, 
			thumbframe: false,';
			}
		if ($controller == 'true') {
			$mycontroller = 'controller: {duration: 1300, transition: Fx.Transitions.Sine.easeOut},';
			}
		if ($loader == 'true') {
			$myloader = 'loader: { duration: 700, transition: Fx.Transitions.Sine.easeOut, \'animate\': [\''.$loaderPath.'\', 12] },';
			} else { $myloader ='loader:false,'; }
		if ($captions == 'true') {
			$mycaptions = 'captions: {duration: 700, transition: Fx.Transitions.Sine.easeOut},';
			}
		
		if (!$height == '') $myheight = 'height:'.$height.',';
		if (!$width == '') $mywidth = 'width:'.$width.',';
		
		if ($linked !== 'false') $mylinked = 'true'; else $mylinked = 'false' ;
		
		if ($show_type == 'kenburns'){
			$mynewjs = $this->kenburns_js;
			$new_type = 'Slideshow.KenBurns';			
			if (strpos($zoom, ',')) $zoom = ('['.$zoom.']');
			if (strpos($pan, ',')) $pan = '['.$pan.']';
			$type_ops = 'zoom: '.$zoom.', pan: '.$pan;			
			}
		elseif ($show_type == 'push'){
			$mynewjs = $this->push_js;
			$new_type = 'Slideshow.Push';
			$type_ops = "transition: '".$transition."'";
			}
		elseif ($show_type == 'fold'){
			$mynewjs = $this->fold_js;
			$new_type = 'Slideshow.Fold';
			$type_ops = "transition: '".$transition."'";
			}
		elseif ($show_type == 'flash'){
			$mynewjs = $this->flash_js;
			$new_type = 'Slideshow.Flash';
			if (strpos($color, ',') !== false) {
  				 $color = "['".implode("', '",explode(', ',$color))."']";
			}else{$color = "'".$color."'";}
			$type_ops = "color: ".$color."";
			}
		elseif ($show_type == 'shrink'){
			$mynewjs = $this->shrink_js;
			$new_type = 'Slideshow.Shrink';
			$type_ops = "transition: '".$transition."'";
			}
		elseif ($show_type == 'default'){
			$mynewjs = '';
			$new_type = 'Slideshow';
			$type_ops = "transition: '".$transition."'";
			}
		
		/* This needs to be developed for future version
		if ($ani_class != '') {
			//$thumbsup = 'thumbsup'; // to be flushed out
			$myclass = "classes: ['slideshow', 'first', 'prev', 'play', 'pause', 'next', 'last', 'images', 'captions', 'controller', 'thumbnails', 'hidden', 'visible', 'inactive', 'active', 'loader'],";
		}else { $myclass = '';}
		*/
		
		$myclass = ''; 
		// if the user has set featured, lets set the captions to featured-captions.
		//  we could also set any of the other classes to have them behave differently than the base objects.
        if ($show_class != ''){             
            $myclass = "classes: ['slideshow', 'first', 'prev', 'play', 'pause', 'next', 'last', 'images', '".$show_class."-captions', 'controller', 'thumbnails', 'hidden', 'visible', 'inactive', 'active', 'loader'],";
      
        } else {         
            $myclass = ''; 
       
        }
				
		// if shortcode has set a new show type we need to load the required javascript		
		if ($this->shortcode_showtype == 'true') echo $mynewjs;
			
		// set a global href for the show if there is one
		if ( $href != '') { $myhref = 'href: "'.$href.'", linked: false,';}else{ $myhref = 'linked: '.$mylinked.',';}
		
		// onEnd handler to act at end of non looping show
		// something like this: function(){ window.location.href = 'http://some.url'; }
		// onStart, onComplete, onEnd
		//match: /\?slide=(\d+)$/,
		//replace: [/(\.[^\.]+)$/, 't$1'],
				
        // if the from folder has been set lets get the array of images
		if ( $fromfolder != '' ) { 

			$folderoutput = "\n"."<script type=\"text/javascript\">\n";
			$folderoutput .= "\t"."// <![CDATA[\n";
			$folderoutput .= 'var showdata'.$this->show_id.'={'; 
			$folderoutput .= $this->ssShow_find_images($fromfolder);
			$folderoutput .= '}';
			$folderoutput .= "\t".'// ]]>';
			$folderoutput .= "\n".'</script>'."\n";
			
		    echo $folderoutput;
		
			$data = 'showdata'.$this->show_id; 
			$hu = "'".WP_CONTENT_URL."/".$fromfolder."'";
						
		} else { 
		    
		    $data = 'null'; $hu = '\'\''; $getfolder = ''; 
		    
		}

		$initshow = "".$showName."
			var ssShow".$this->show_id." = new ".$new_type."(this.el, ".$data.",{
                center: ".$center.",
                delay: ".$delay.",
                duration: ".$duration.",
                hu: ".$hu.",
                resize: ".$resize.",
                overlap: ".$overlap.",
                paused: ".$paused.",
                random: ".$random.",
                loop: ".$loop.",
                slide: ".$first_slide.",
                ".$myaccesskeys."
                ".$myproperties."
                ".$myreplace."
                ".$mypreload."
                ".$myclass."
                ".$mycaptions."
                ".$mycontroller."
                ".$myloader."
                ".$myheight."
                ".$mywidth."
                ".$mythumb."
                ".$myhref."
                ".$type_ops."
                });";

				$startshow = "\n\t <!-- superslider-show 2.7.4-->\n";								
				$startshow .= "\n <script type=\"text/javascript\">\n";
				$startshow .= "\t // <![CDATA[\n";		
				$startshow .= "window.addEvent('domready', function() {
                    ".$initshow."
                    ".$mylightbox."
                    ".$mymouse."
                    ".$mythumbcover."						
                    });\n\t";
				$startshow .= ' // ]]>';
				$startshow .= "</script>\n";
							
		return $startshow;
		
	}
	
	
	function slimbox_linker() {
		
	        $slimOpOut = get_option('ssSlimbox_options') ;
	        extract ( $slimOpOut ) ;
						
            $slim_overlayFadeDur = ' 600 ';
            $slim_resizeTrans = $trans_type.':'.$trans_typeout;

			$resizeTrans = 'Fx.Transitions.'.$trans_type.'.'.$trans_typeout;

			$myslimbox =	"<script type=\"text/javascript\">				
				Slimbox.scanPage = function() {
					$$(document.links).filter(function(el) {
					return el.rel && el.rel.test(/^lightbox/i);
					}).slimbox({
						loop: true,
						overlayOpacity: ".$opacity.",
						overlayFadeDuration: ".$slim_overlayFadeDur.",
						resizeDuration: ".$resize_dur.",
						resizeTransition: '".$slim_resizeTrans."',
						initialWidth: ".$width.",
						initialHeight: ".$height.",
						imageFadeDuration: ".$image_dur.",
						captionAnimationDuration: ".$caption_dur.",
						counterText: '".$counter_text."',					
						onOpen : function(){ 
						     //console.log(  'is onOpen has fired' );
						     //ssShow.pause(1).bind(ssShow);
						     //ssShow'.$this->show_id.'.pause(1).bind(ssShow'.$this->show_id.'); },
						  },
			            onClose : function(){ 
			                //console.log(  'is onClose has fired' );
			                //ssShow.pause(0).bind(ssShow); 
			                //ssShow'.$this->show_id.'.pause(0).bind(ssShow'.$this->show_id.');
			                }		
					}, null, function(el) {
					return (this == el) || ((this.rel.length > 8) && (this.rel == el.rel));
					});
				};
		window.addEvent('domready', Slimbox.scanPage);
				</script>";
			echo "$myslimbox";

	}
	function ss_change_options( $atts ){

			$this->ssShowOpOut = array_merge($this->ssShowOpOut, array_filter($atts));   				
  			return $this->ssShowOpOut;
	}
	/**
	* transform the var exclude into an array, remove its contents from all_attachments
	*/
	/*	*/
	function PHP4_array_diff_key(&$all_attachments, &$exclude){
			$arrs = func_get_args();
				$new_att = array_shift($arrs);
				foreach ($arrs as $array) {
					foreach ($new_att as $key => $v) {
						if (array_key_exists($key, $array)) {
						unset($new_att[$key]);
					}
				}
				}
			return $new_att;
	}	
	
	/**
		* Write the Slideshow html structure.
		*/	
	function slideshow_shortcode_out ( $atts ) {				
		global $post;
		global $wpdb;
		srand((double)microtime()*1000000); 
		$this->show_id = rand(0,1000); 		
		if ( isset( $atts['orderby'] ) ) {
			$atts['orderby'] = sanitize_sql_orderby( $atts['orderby'] );
			if ( !$atts['orderby'] )
				unset( $atts['orderby'] );
		}
		$atts = shortcode_atts(array(
			'order'      => 'ASC', 'orderby'    => 'menu_order', 'id' => $post->ID,
			'show_class' => '', 'href' => '', 'show_type' => '', 'image_size' => '', 'pop_size'	=>	'', 
			'first_slide' => '', 'limit'  =>  '', 'zoom' => '', 'pan' => '', 'color' => '', 'height' => '', 
			'width' => '', 'center' => '', 'resize' => '', 'linked' => '', 'fast' => '', 'captions' => '', 'overlap' => '', 
			'thumbnails' => '', 'thumbsize' => '', 'thumbframe'=> '', 'mouseover' => '', 'paused' => '', 
			'random' => '', 'loop' => '', 'loader' => '', 'delay' => '', 'duration' => '', 'controller' => '', 
			'exclude' => '', 'clear' =>	'', 'fromfolder' => '', 'css_theme' => '',
			'transition' => ''), $atts);

		// opdate options if any changes with shortcode
		if ($atts !='') $this->ss_change_options($atts);
		
		// this loads the new show type javascript
		if ( array_key_exists('show_type', $atts) && ($atts['show_type'] != '')) $this->shortcode_showtype = 'true';
	
		extract($this->ssShowOpOut);

		$all_attachments = array();// pre define the master array
		$attachments = array();
		
      switch ($id) {
        
      case ( strpos($id,'recent') ):
        
        if (strpos($id,':')) { $inex = 'exclude';
            $cats_exclude = str_replace('recent:', "", $id); }
        if (strpos($id,'@')) { $inex = 'include';
            $cats_include = str_replace('recent@', "", $id); }
                      
        $sort_code = 'ORDER BY cat_name DESC, post_date DESC';
	    $mysqlnow = current_time('mysql');
		$cat_list_code = '';
		$ss = $wpdb->prefix;

		if ($cats_exclude) {
			$cats_exclude = (array)explode(',', $cats_exclude);
			foreach ($cats_exclude as $cat) {
				$cat_list_code .= " AND {$ss}terms.term_id != '" . trim(mysql_escape_string($cat)) . "' ";
			}
		} else if ($cats_include) {
			$cats_include = (array)explode(',', $cats_include);
			$cat_list_code .= " AND ( ";
			$cat_tmp = array();
			foreach ($cats_include as $cat) {
				$cat_tmp[] .= "  {$ss}terms.term_id = '" . trim(mysql_escape_string($cat)) . "' ";
			}			
			$cat_list_code .= implode(" OR ", $cat_tmp);
			$cat_list_code .= " ) ";
		} else {
		   $cat_list_code .= " ";
		}

		$last_posts = (array)$wpdb->get_results("
			SELECT post_date,ID, post_title, 
			{$ss}terms.name as cat_name, 
			{$ss}terms.term_id as cat_ID
			FROM {$ss}posts, {$ss}terms, {$ss}term_taxonomy, {$ss}term_relationships
			WHERE {$ss}posts.ID = {$ss}term_relationships.object_id 
			AND {$ss}term_relationships.object_id = {$ss}posts.ID
			AND {$ss}term_relationships.term_taxonomy_id = {$ss}term_taxonomy.term_taxonomy_id 
			AND {$ss}terms.term_id  = {$ss}term_taxonomy.term_id 
			AND {$ss}term_taxonomy.taxonomy = 'category' 
			AND post_status = 'publish' 
			AND post_type = 'post' 
			AND post_date < '$mysqlnow'    
			{$cat_list_code}			
			{$sort_code}");

		if ((int)$recent_limit > 0) {
            $used_cats = array();
            foreach ($last_posts as $posts) {
                $used_cats[$posts->cat_ID] = $recent_limit;
            }    
            $i = 0;
            foreach ($last_posts as $posts) {
                if ($used_cats[$posts->cat_ID] > 0) {
                    $used_cats[$posts->cat_ID] -= 1;
                } else {
                    unset($last_posts[$i]);
                }
                $i++;
            }    
            $last_posts = array_values($last_posts);
	    } 
        $recent_posts = array_reverse($last_posts);
        
        foreach ( $recent_posts as $id){                        
            $attachments = get_children("post_parent=$id->ID&post_type=attachment&post_mime_type=image&orderby=$orderby");                      
            if (!empty($attachments)) { 
                $attachments = array_reverse($attachments);
                $attachments = array_slice($attachments, 0, 1);
                $all_attachments = array_merge((array)$all_attachments, (array)$attachments);
            }                    
        }
        break;
        
      case ( strpos($id,'featured') ):

          $querystr = "
            SELECT wposts.* 
            FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta
            WHERE wposts.ID = wpostmeta.post_id 
            AND wpostmeta.meta_key = 'featured' 
            AND wpostmeta.meta_value = '1'
            AND wposts.post_type = 'post'
            AND wposts.post_status = 'publish'  
            ORDER BY wposts.post_date DESC ";

        $featuredposts = $wpdb->get_results($querystr,OBJECT);

        if ($featuredposts) {
            $featuredposts = array_reverse($featuredposts);                 
                foreach ( $featuredposts as $id){
        
                   $id = intval($id->ID);                 
                   $attachments = get_children("post_parent=$id&post_type=attachment&post_mime_type=image&orderby=$orderby");
                  
                    if (!empty($attachments)) { 
                        $attachments = array_slice($attachments, 0, 1);
                        $all_attachments = array_merge((array)$all_attachments, (array)$attachments);
                    }                    
                }
        }
        break;
        
      case ( strpos($id,'category:') ):
                        
            $catid = explode('category:', $id); 
            $catid = $catid[1];
            $querystr = "
            SELECT ID FROM $wpdb->posts
            LEFT JOIN $wpdb->postmeta ON($wpdb->posts.ID = $wpdb->postmeta.post_id)
            LEFT JOIN $wpdb->term_relationships ON($wpdb->posts.ID = $wpdb->term_relationships.object_id)
            LEFT JOIN $wpdb->term_taxonomy ON($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
            WHERE $wpdb->term_taxonomy.term_id IN (".$catid.")
            AND $wpdb->term_taxonomy.taxonomy = 'category'
            AND $wpdb->posts.post_type = 'post'
            AND $wpdb->posts.post_status = 'publish'
            ORDER BY $wpdb->posts.post_date DESC";
            
            $categoryposts = $wpdb->get_results($querystr, OBJECT);//ARRAY_A  ARRAY_N OBJECT

            if (!$categoryposts) break;          
            $categoryposts = array_reverse($categoryposts);
            $unique = array();
            foreach ($categoryposts as $object) {
                if (isset($unique[$object->ID])) {                
                    continue;      
                }              
                $unique[$object->ID] = $object;         
             } 
            $categoryposts = $unique;
            foreach ( $categoryposts as $id){              
               $attachments = get_children("post_parent=$id->ID&post_type=attachment&post_mime_type=image&orderby=$orderby");

                if (!empty($attachments)) {                       
                    //$attachments = array_slice($attachments, 0, 1); //disconected                                
                    $all_attachments = array_merge((array)$all_attachments, (array)$attachments);
                }
            }            
            break;
            
      case ( strpos($id,'random@') ):            
      
            $randomid = explode('random@', $id); 
            $catid = $randomid[1];
            // prevent a select of every post, limit x 4 is the base number of posts to randomly pull
            $postlimit = $limit * 4; 

            if ($catid !== '') $cat = "WHERE $wpdb->term_taxonomy.term_id IN (".$catid.")
            AND $wpdb->term_taxonomy.taxonomy = 'category'";

           $querystr = "SELECT ID FROM $wpdb->posts
            LEFT JOIN $wpdb->postmeta ON($wpdb->posts.ID = $wpdb->postmeta.post_id)
            LEFT JOIN $wpdb->term_relationships ON($wpdb->posts.ID = $wpdb->term_relationships.object_id)
            LEFT JOIN $wpdb->term_taxonomy ON($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
            ".$cat."
            AND $wpdb->posts.post_type = 'post'
            AND $wpdb->posts.post_status = 'publish'          
            ORDER BY RAND() LIMIT ".$postlimit;
            
            $posts = $wpdb->get_results($querystr, OBJECT);
     
            if ($posts) {            
                $posts = array_reverse($posts);
                $unique = array();                
                foreach ($posts as $object) {                
                    if (isset($unique[$object->ID])) {                
                        continue;  }              
                    $unique[$object->ID] = $object;                
                }
             $posts = $unique;            
             }             
            foreach ( $posts as $id){               
               $attachments = get_children("post_parent=$id->ID&post_type=attachment&post_mime_type=image&orderby=$orderby");                 
               $all_attachments = array_merge((array)$all_attachments, (array)$attachments);
             }                             
            break;
            
        case ( strpos($id,'nextgen-') ):            
            
            $nextgenhere = "true";            
            $linked == 'lightbox'; // can only have a pop over link.           
            $nextgen = explode('nextgen-', $id); 
            $nextgenid = $nextgen[1];

            $querystr = "
            SELECT s.*,ss.* 
            FROM $wpdb->nggallery 
            AS s INNER JOIN $wpdb->nggpictures 
            AS ss ON s.gid = ss.galleryid 
            WHERE s.gid = '$nextgenid' 
            AND ss.exclude != 1";
            
            $pictures = $wpdb->get_results($querystr, OBJECT);
            
            foreach($pictures as $picture) {
                  $att_image = array();                  
                  $thumbnail = $picture->thumbnail;
                  $att_image["post_title"] = $picture->alttext;
                  $att_image["post_excerpt"]  = $picture->description;                  
                  $att_image["img"]   = $this->site_url . "/" . $picture->path ."/" . $picture->filename;
                  $att_image["thumb"] = $this->site_url . "/" . $picture->path ."/thumbs/thumbs_" . $picture->filename;
               
                  $all_attachments[] = $att_image;
            }            
            break;
            
        case ( strpos($id,',') && !strpos($id,'recent') ):            
            $idz = explode(',', $id);
			foreach ($idz as $id) {				
				$id = intval($id);
				$attachments = get_children("post_parent={$id}&post_type=attachment&post_mime_type=image&orderby={$orderby}");						
				$all_attachments = array_merge((array)$all_attachments, (array)$attachments);
			}            
            break;
            
        case ( $id ):
            if (array_key_exists('id', $atts) && ( $atts['id'] != '')) {
				$myid = $atts['id'];
			} else{
				$myid = '';
			}
			if ($myid == '') $id = $post->ID;
			$all_attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
        }
		
		if ( empty($all_attachments))	
		return '';
				
		/**
		* transform the var exclude into an array, remove its contents from all_attachments
		*/
		if($exclude !== '') {		
				$exclude = array_flip(array_map('trim', explode(',', $exclude)));
				$b = $this->PHP4_array_diff_key($all_attachments, $exclude);
				$all_attachments = $b;
		}
		
		/* Time to set the limit on the total number of images passed to the show.
        */
	    $all_attachments = array_slice($all_attachments, 0, $limit);

		if ( is_feed() ) {
			$output = "\n";
			foreach ( $all_attachments as $attachment )
				$output .= wp_get_attachment_link($id, $size = 'thumbnail', true) . "\n";
			return $output;
		}

	// Open gallery
		$output = apply_filters('show_style', "<div id='slide_gallery".$this->show_id."' class='slideshow ".$show_class."'><div class='slideshow-images show-$post->ID' >");

	    $open_h3 = "&lt;h3&gt; ";
        $close_h3 = "&lt;/h3&gt; ";
		
		foreach ( $all_attachments as $attachment ) {

		 if (!$nextgenhere) {

		// lets get the attachment parent (post) id
			$my_parent = $attachment->post_parent;		
		// get some image info	
			$image = wp_get_attachment_image_src($attachment->ID, $size = $image_size);// $image_size, $pop_size
			$att_image = wp_get_attachment_image_src($attachment->ID, $size = $pop_size);        
        // caption resources
            $image_title = $attachment->post_title;
            $image_description = $attachment->post_content; 
         
         } else {  // is nextgen               
          
             $image[0] = $attachment["img"];
             $image_title = $attachment["post_title"];
             $image_description = $attachment["post_excerpt"];             
             $caption_link = '';
             $caption_title = 'image_title';
             $caption_text = 'image_description';
          }             
        switch ($caption_title) {
            case 'post_title':
                $my_caption_title = $open_h3.get_the_title($my_parent).$close_h3;
                break;
            case 'post_category':
                $cat = get_the_category($my_parent);
                $cat = $cat[0];
                $cat_name = $cat->name ;
                /*if($cat->category_parent !== '0') {
                    $cat_name = get_the_category_by_ID($cat->category_parent);
                }*/
                $my_caption_title = $open_h3.$cat_name.$close_h3;
                break;
            case 'image_title':
                $my_caption_title = $open_h3.$image_title.$close_h3;
                break;
            case 'image_caption':
                $my_caption_title = $open_h3.$image_caption.$close_h3;
                break;
            case '':
                $my_caption_title = '';
            }

        switch ($caption_text) {
            case 'post_title':
                $my_caption_text = get_the_title($my_parent);
                break;
            case 'image_title':
                $my_caption_text = $image_title;
                break;
            case 'image_caption':
                $my_caption_text = $attachment->post_excerpt;
                break;
            case 'image_description':
                $my_caption_text = $image_description;
                break;
            case 'post_excerpt': // this will need to be escaped and treated to work
                $post = &get_post( $my_parent );
                if( !empty( $post->post_excerpt ) ) {
                    $my_caption_text = '<p>'.$post->post_excerpt.'</p>';
                }else{ $my_caption_text = ''; }
                break;
            case '':
                $my_caption_text = '';
            }
         switch ($caption_link) {
            case 'custom_link_text':
                $my_caption_link = $caption_link_text;
                break;
            case 'post_title':
                $my_caption_link = get_the_title($my_parent);
                break;
            case 'image_title':
                $my_caption_link = $image_title;
                break;
            case 'image_caption':
                $my_caption_link = $attachment->post_excerpt;
                break;
            case 'image_description':
                $my_caption_link = $image_description;;
                break;
            case '':
                $my_caption_link = '';
            }
            
            // Make caption link eliment
       if ( $my_caption_link != '') $insert_caption_link = " &lt;a href=&quot;".get_permalink($my_parent) ."&quot;&gt;".$my_caption_link."&lt;/a&gt; "; 

		// If using Lightbox, set the link to the img URL
		// Else, set the image link to the attachment URL, or parent post		
		  $lightbox_type = strtolower($lightbox_type);
			if ( ($linked == 'lightbox') && ($href == '') ) {
				$linkto = ' href="'.$att_image[0].'"';
				if ($lightbox_type == 'slimbox') {
					$a_rel = ' rel="lightbox-ssShow"';
					}else{
					$a_rel = ' rel="'.$lightbox_type.':ssShow"';
				}
				$a_class = $lightbox_type.' '; //could add tool as a class here
			}
			elseif ($linked == 'parent') { 
				$linkto = ' href="'.get_permalink($my_parent).'"';
				$a_rel = '';
				$a_class = ' ';
			}
			elseif ($linked == 'attach') {
				$linkto = ' href="'.get_attachment_link($attachment->ID, NULL , true).'"';
				$a_rel = ' ';
				$a_class = ' ';			
			}else{
			   	$linkto = ' ';
				$a_rel = ' ';
				$a_class = ' ';	
			}
			
		// Do we link the image 
            if ($linked != 'false') $output .= '<a '.$linkto.$a_rel.' class="'.$a_class.'" title="'.$image_title.'" > ';

		// output the main images		      		     
            $output .= '<img id="slide-'.$attachment->ID.'" src="'.$image[0].'" alt="'.$my_caption_title.' '.$my_caption_text.' '.$insert_caption_link.'" width="'.$image[1].'" height="'.$image[2].'" style="visibility: hidden; opacity: 0;" />'; //style="visibility: hidden; opacity: 0;"
		 
            if ($linked != 'false') $output .= "</a>"."\n";
			
		} // end foreach
	
		// Add the caption
	if ( $captions == 'true') $output .= "\n<div class='slideshow-captions'></div>";
					
		// Add loader
	if ( $loader == 'true')	$output .= "\n<div class='slideshow-loader'></div>";
					
	// CLose the Images div
	$output .= '</div>';		
		
	// Add controller
    if ( $controller == 'true')	{
        $output .= '<div class="slideshow-controller" style="visibility: hidden; opacity: 0;">
                 <ul>
                    <li class="first"><a></a></li>
                    <li class="prev"><a></a></li>
                    <li class="pause play"><a></a></li>
                    <li class="next"><a></a></li>
                    <li class="last"><a></a></li>
                </ul>
            </div>';
    }

    if ( $thumbnails == 'true')	{
   
      $output .= '<div class="slideshow-thumbnails"><ul>';			
        foreach ( $all_attachments as $id => $attachment ) {
            if (!$nextgenhere) {
                $image = wp_get_attachment_image_src($attachment->ID, $size= $thumbsize, $myicon = false);
            
            } else {				
                $image[0] = $attachment["thumb"];		      
            }
            $output .= "<li><a href=\"#\" title=\"view Image\">";
            $output .= "<img src=\"$image[0]\" alt=\"show Thumbnail\" width=\"$image[1]\" height=\"$image[2]\" />";

            if($thumbframe == 'on') $output .= "<span class='thumbframe'>&nbsp;</span>";
            $output .= "</a></li>";
            }			
        $output .= '</ul></div><br style="clear:both;" />';			
    }
	if ( $clear !== '') $myclear = '<div style="clear:'.$clear.';height:0px;width:0px;" >&nbsp;</div>'; 
	// Close slideshow
	$output .= '</div>'.$myclear;
	
	// Add invisible imagelist
	if ( ($linked == 'lightbox') && (!$nextgenhere) && ($fromfolder == '') ) {
		$output .= '<div class="hidenlinks" >';		
		foreach ( $all_attachments as $id => $attachment ) {
                $id = $attachment->ID;
				$att_image = wp_get_attachment_image_src($id, $size = $pop_size);
				$linkto = 'href="'.$att_image[0].'"';
				if ($linked !== 'false') $output .= "\n\t"."<a $linkto $a_rel style=\"display:none;\" title=\"{$attachment->post_excerpt} :: {$attachment->post_content}\" ></a>";			
		}
		$output .= '</div>';
	}
	// get the domready js
	$output .= $this-> ssShow_set_show($atts,$id);

	return $output;	
	
	}
	
	/**
	*	Add the [gallery / slideshow] shortcode
	*/
	function ssShow_add_shortcode(){
		
		$this->ssShowOpOut = get_option($this->AdminOptionsName);
		$ss_shortcode = $this->ssShowOpOut['ss_shortcode'];

		if ($ss_shortcode == 'gallery'){
				remove_shortcode ( 'gallery' );	// Remove included WordPress [gallery] shortcode function
				add_shortcode ( 'gallery' , array(&$this, 'slideshow_shortcode_out') );	// Add new [gallery] shortcode function
			} else {
				add_shortcode ( 'slideshow' , array(&$this, 'slideshow_shortcode_out') );	// Add [slideshow] shortcode function
			}	
	}
	/**
	*	Look ahead to check if any posts contain the [gallery / slideshow] shortcode
	*/
	function ssShow_slide_scan () { 
			$this->set_show_paths();
			$this->ssShowOpOut = get_option($this->AdminOptionsName);
			$ss_shortcode = $this->ssShowOpOut['ss_shortcode'];
			
			global $posts; 
			
			if ( !is_array ( $posts ) ) 
					return; 	 
			foreach ( $posts as $mypost ) { 
					if ( false !== strpos ( $mypost->post_content, '[gallery')  ||  false !== strpos ( $mypost->post_content, '[slideshow') ){ 
							
						if ( false !== strpos ( $mypost->post_content, 'css_theme=')) {
							 
							 $this->css_theme_override = 'true';
						     preg_match( '/(css_theme=")(.*?)(")/', $mypost->post_content, $matches);
							 $this->css_theme = $matches[2];
							
							}
							
							add_action('wp_print_styles', array(&$this,'ssShow_add_css'));
							add_action('wp_print_scripts', array(&$this,'ssShow_add_javascript')); //this loads the mootools scripts.
							
							break; 
					} 
			} 
	} 
		/**
		*	called by superslider_show add_action upload_files_(tab)
		*	creates slideshow options tab in media window
		*/	
	function ss_print_box() {
		global $ssShow_domain;
		$this->ssShowOpOut = get_option($this->AdminOptionsName);
		extract($this->ssShowOpOut);

		if	($show_shortcode == 'true')	{
			if (is_admin ()) {			
				if( function_exists( 'add_meta_box' )) {
					add_meta_box( 'ss_show', __( 'SuperSlider-Show', $ssShow_domain ), array(&$this,'ss_writebox'), 'post', 'advanced', 'high');
					add_meta_box( 'ss_show', __( 'SuperSlider-Show', $ssShow_domain ), array(&$this,'ss_writebox'), 'page', 'advanced', 'high' );
				    
				    add_action('admin_print_scripts', array(&$this,'ss_enqueue_admin_js'));

				}
			}
   		}
	}
	function ssShow_admin_style(){

			$cssAdminPath = WP_PLUGIN_URL.'/superslider-show/admin/';    			
    		
    		wp_register_style('superslider_admin', $cssAdminPath.'ss_admin_style.css');
    		wp_register_style('superslider_admin_tool', $cssAdminPath.'ss_admin_tool.css');
        	wp_enqueue_style( 'superslider_admin');
    	    wp_enqueue_style( 'superslider_admin_tool');
	}
	
	function ssshow_admin_script(){
		wp_enqueue_script('jquery-ui-tabs');	// this should load the jquery tabs script into head
		
	}
	function ss_writebox() {
	    
	    global $ssShow_domain;
  		$this->ssShowOpOut = get_option($this->AdminOptionsName);
		
		extract($this->ssShowOpOut);
		
		include_once 'admin/superslider-show-box.php';
		echo $box;
		        
	}
	function ss_enqueue_admin_js() {

	  wp_enqueue_script( 'jquery' );
	  wp_enqueue_script( 'jquery-dimensions' );	  
	  wp_enqueue_script( 'jquery-tooltip' );
	  wp_enqueue_script( 'superslider-show-info' );
	  wp_enqueue_script( 'superslider-admin-tool' );
	  wp_enqueue_script( 'superslider-show-box' );
	
	}
	
	/**
	*	This is the function called from the template file to load js/css into head.
	* 	still in development 
	*	this probably isn't needed, seems it already loaded into head.
		*/
	
	function ssShow_theme_scripts() { 
                
        global $wp_scripts, $template_js;

        if ( $template_js ) {
            $wp_scripts->do_items('moocore'); //, 'moomore', 'slideshow' 
            $wp_scripts->do_items('moomore');
            $wp_scripts->do_items('slideshow');
            $type = $this->ssShowOpOut[show_type];
            
        }
        $this->ssShow_create_css();
        $this->ssShow_add_theme_css();


	}
	function ssShow_add_theme_css(){

	  	echo '<link rel="stylesheet" rev="stylesheet" href="'.$this->css_path.'" media="screen" />';			
		echo '<link rel="stylesheet" rev="stylesheet" href="'.$this->light_path.'" media="screen" />';

	}
	
	/**
	*	This is the function called from the template file to write the slideshow.
	* 	still in development
	*	if(class_exists('ssShow')) $myssShow -> ssShow_theme_loader($show_options, $type);
	*
	*    $type = cat, tag, week, month, year, search
	*/
	function ssShow_theme_loader($p, $type ){ 
		
		$GLOBALS['template_js'] = true;
		
		global $wp_query;
	    $cat = $wp_query->get_queried_object();
	    $my_cat_id = $cat->term_id;
	
	    if( $type == 'cat' ) $p['id'] = 'category:'.$my_cat_id;	
        
        $this->ssShowOpOut = get_option($this->AdminOptionsName);
		
		$this->ssShow_add_theme_css();
		$this->ssShow_add_javascript();
		
		add_action ( 'wp_footer', array(&$this, 'ssShow_theme_scripts') );
		//add_action('template_redirect', array(&$this, 'ssShow_theme_scripts') ); 
		$this->slideshow_shortcode_out($p); 
	}

	/**
	*	This is the function that sets up all the actions.
	*/
	function superslider_show() {
		
		register_activation_hook(__FILE__, array(&$this,'ssShow_init') );
		register_deactivation_hook( __FILE__, array(&$this,'options_deactivation') );
		
		add_action ( "init", array(&$this,"ssShow_init" ) );
		add_action ( "admin_menu", array(&$this,"ssShow_setup_optionspage" ) ); // start the backside options page		
		add_action ( "template_redirect" , array(&$this,"ssShow_slide_scan") );	// Add look ahead for [slideshow] shortcode
		add_action ( "admin_menu", array(&$this,"ss_print_box" ) ); 			// adds the shortcode meta box		
		
		add_action ( "init", array(&$this,"ssShow_add_shortcode" ) );		
		add_action ( "admin_init", array(&$this,"ss_create_thumbs" ) );		
		add_action ( 'admin_init', array(&$this, 'Show_create_media_page') );
		
		$ssShow_is_setup = 'true';
	
	}	
	function Show_create_media_page() {
    			
    		register_setting( 'media', 'minithumb_size_w' );
    		register_setting( 'media', 'minithumb_size_h' );
    		register_setting( 'media', 'minithumb_crop' );
    		
    		register_setting( 'media', 'slideshow_size_w' );
    		register_setting( 'media', 'slideshow_size_h' );
    		register_setting( 'media', 'slideshow_crop' );
			
			add_settings_field('minithumb_size', 'SlideShow Minithumb', array(&$this, 'Slide_mini_size'), 'media', 'default');			
			add_settings_field('slideshow_size', 'SlideShow Image', array(&$this, 'Slide_image_size'), 'media', 'default');

    }
    
    function Slide_image_size(){        
       /* $slide_w = get_option ('slideshow_size_w');
        $slide_h = get_option('slideshow_size_h');
        $slide_crop = get_option('slideshow_crop');*/
        
        echo '<label for="slideshow_size_w">'.__(' Max Width ', 'superslider-show').'</label>
        <input name="slideshow_size_w" id="slideshow_size_w" type="text" value="'. get_option ('slideshow_size_w').'" class="small-text" />
        <label for="slideshow_size_h">'.__(' Max Height ', 'superslider-show').'</label>
        <input name="slideshow_size_h" id="slideshow_size_h" type="text" value="'. get_option('slideshow_size_h').'" class="small-text" />
        <br /><input type="checkbox"'; 
            checked('1', get_option('slideshow_crop'));        
          echo ' value="1" id="slideshow_crop" name="slideshow_crop"><label for="slideshow_crop">'.__('Crop slide image to exact dimensions').'</label>'; 
	}
	
	function Slide_mini_size(){        
        /*$show_w = get_option ('minithumb_size_w');
        $show_h = get_option('minithumb_size_h'); 
        $show_crop = get_option('minithumb_crop');*/
        
        echo '<label for="minithumb_size_w">'.__(' Max Width ', 'superslider-show').'</label>
        <input name="minithumb_size_w" id="minithumb_size_w" type="text" value="'.get_option ('minithumb_size_w').'" class="small-text" />
        <label for="minithumb_size_h">'.__(' Max Height ', 'superslider-show').'</label>
        <input name="minithumb_size_h" id="minithumb_size_h" type="text" value="'. get_option('minithumb_size_h').'" class="small-text" />
        <br /><input type="checkbox"'; 
            checked('1', get_option('minithumb_crop'));        
        echo ' value="1" id="minithumb_crop" name="minithumb_crop"><label for="minithumb_crop">'.__('Crop minithumb to exact dimensions', 'superslider-show').'</label>';

	}
	function ss_create_thumbs(){
			$this->listnewimages();
			add_filter( 'intermediate_image_sizes',  array(&$this, 'additional_thumb_sizes') );	
	}
	
	function additional_thumb_sizes( $sizes ) {
			$sizes[] = "slideshow";
			$sizes[] = "minithumb";
			return $sizes;
	}

	function listnewimages() { 		
		if( FALSE === get_option('minithumb_size_w') ) {
            add_option('slideshow_size_w', '480' );
            add_option('slideshow_size_h', '480');
            add_option('slideshow_crop', '0');
            
            add_option('minithumb_size_w', '80' );
            add_option('minithumb_size_h', '80');
            add_option('minithumb_crop', '1');
        }

				
	}

}	//end class
} //End if Class ssShow

/**
*instantiate the class
*/	
//if (class_exists('ssShow')) {
	$myssShow = new ssShow();
//}
?>