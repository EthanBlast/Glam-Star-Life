<?php

/*

Plugin Name: Superslider-PostsinCat 

Plugin URI: http://wp-superslider.com/superslider/superslider-postsincat

Author: Daiv Mowbray

Author URI: http://wp-superslider.com

Description: This widget scrolls the thumbnails dynamicaly created list of posts from the active category. Displaying the first image and title.

Version: 1.6



*/



function ss_postsincat_init()  {

        global $slidebox_id;

        global $load_css;

        srand((double)microtime()*1000000); 

		$slidebox_id = rand(0,1000);

		

	//gracefully fail if sidebar gets deactivated

	if ( !function_exists('register_sidebar_widget') )

	return;

	

    $pic_js_path = WP_PLUGIN_URL.'/'. plugin_basename(dirname(__FILE__)) . '/js/';



    $options = get_option( 'ssPostsinCat_options' );

    

    if ( is_array($options) ) extract($options);

            

    if ($load_css_from_theme == 'on') {				  

        $css_path = WP_PLUGIN_URL.'/superslider-postsincat/plugin-data/superslider/ssPostinCat/';

               

     } else {			

        $css_path = WP_CONTENT_URL.'/plugin-data/superslider/ssPostinCat/';

     }

  

   if($load_css == 'on')  {

        $pic_css_file = $css_path.$theme.'/'.$theme.'.css';

    }else {

        $pic_css_file = '';

    }

       

    wp_register_script('moocore',$pic_js_path.'mootools-1.2.3-core-yc.js',NULL, '1.2.3');

    wp_register_script('moomore',$pic_js_path.'mootools-1.2.3.1-more.js',array( 'moocore' ), '1.2.3');

    wp_register_script('slideBox',$pic_js_path.'slideBox-v1.0.js',array( 'moocore','moomore' ), '1.0');

    wp_register_style('postincat_style', $pic_css_file);

	

	if (!is_admin())  {				

        if (function_exists('wp_enqueue_script'))  {

           wp_enqueue_script('moocore');

           wp_enqueue_script('moomore');

           wp_enqueue_script('slideBox');

        }

    }

	                   

	register_widget_control(array('SuperSlider PostsinCat', 'widgets'), 'ss_postsincat_widget_control', 350, 450);

	register_sidebar_widget(array('SuperSlider PostsinCat','widgets'), 'ss_postsincat_widget');

    add_action ( 'template_redirect' , 'ss_postsincat_head');



}



function ss_postsincat_widget($args)  {

	     

	    global $post;

	    global $slidebox_id;

	    global $img_width;

        global $img_height;

        global $load_css;

        global $css_path;

		extract($args);

		$options = get_option ( 'ssPostsinCat_options' );



		if ( is_array($options) ) extract($options);

		

		// get the size of the default thumbnail image

        $img_width = get_option ( $imagesize.'_size_w' );
// echo $img_width
// echo $img_height
        $img_height = get_option ( $imagesize.'_size_h' );

      //  $img_width = 25;
		
	//	$img_height = 25;

		$the_output = NULL;

		$image_output = NULL;

		//$order_of_image = 1;

		

		$this_post_id = $post->ID;

	
        $id = 10;
		
        $categories = get_the_category($id);
		
		//added 'news' to line 165

        $num = count($categories);

        if ($num > 1) $categories = array_slice($categories, 0, 1);



        if (empty($categories))  {

			return NULL;

		 }

    

        foreach ($categories as $category)  {

            

           if ($add_cat_name == 'on')  { $cat_name = $category->name;  }else { $cat_name = ''; }           

           $title_text = $options['title'].' '.$cat_name;

           $posts = get_posts('numberposts='.$postnumb.'&category='. $category->term_id);

           

           // remove the active post from the list

           foreach( $posts as $key => $obj)  {

               if ( $obj->ID == $this_post_id)

                unset($posts[$key]);

            }

           

           if ($load_css_from_theme == 'on')  {

                    $css_path = WP_PLUGIN_URL.'/superslider-postsincat/plugin-data/superslider/ssPostinCat/';			

			} else {

				    $css_path = WP_CONTENT_URL.'/plugin-data/superslider/ssPostinCat/';				    

			}

					   

           foreach($posts as $post)  {

                $postid = $post->ID; 

                $attachments = array();

                $image_output = '';

				// check first for a post 2.9 post thumb setting

                if ( function_exists( 'get_the_post_thumbnail' )) {          

                    $image_output = get_the_post_thumbnail($post_id = $postid, $size = $imagesize, $attr = array('class'=>'postincat_thumb'));

                 }

                if ( empty($image_output) ) {		

				    $attachments = get_children( array('post_parent' => $postid, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image') );	//, 'order' => $order, 'orderby' => $orderby		

			         

			         $att_count = count($attachments);

                     if($att_count > 1) $attachments = array_slice($attachments, 0, 1, true);

                     $image_output = '';			        

			         $image = '';

                     

                     foreach ( $attachments as $id => $attachment ) {  

                         $image = wp_get_attachment_image_src($id, $imagesize);

                         $image_output = "<img src=\"$image[0]\" alt=\" {$attachment->post_excerpt }\" width=\"$image[1]\" height=\"$image[2]\" />";    

                     }

			    }

			    if ( empty($image_output)) { 

			    if ( empty($attachments)) {     

			        $image_output = '';			        

			        $image = '';

			        // use ss_image_by_scan to search the content for an image

			        $image_output = ss_image_by_scan( $args = array() );

			         			        

			        // if there are no images in the content, we'll use the default/last resort image.

                    if ($image_output == false)  {

                        $image = $css_path.'images/empty.jpg';

                        $alt = 'default image';

                        $image_output = '<img src="'.$image.'" alt="'.$alt.'" width="'.$img_width.'" height="'.$img_height.'" />';                            

                     }   

			    }

			    }

                $the_output .= "\n".'<li class="ss_postincat_post" ><a href="' .get_permalink($post->ID) . '">' .$image_output. '</a><br />';

                if ($show_post_title == 'on') $the_output .= '<a href="' .get_permalink($post->ID) . '">' .$post->post_title . '</a>';

                $the_output .= '</li>';

          

           } // end foreach posts           



            $ss_widget_out = $before_widget.$before_title.$title_text.$after_title; 



            $ss_widget_out .= "\n".'<div id="slider'.$slidebox_id.'" class="slideBox-container" style="height:'.$display_height.'px; overflow:hidden;" >

              

            <div class="slideBox-wrapper">

            <div class="slideBox-slider">

                 <ul class="ss_post_list">'.$the_output.'</ul>

            </div></div>

            <div class="slideBox-previous"><a href="#" class="slideBox-previous" title="Previous">&nbsp;</a></div>

            <div class="slideBox-next"><a href="#" class="slideBox-next" title="Next">&nbsp;</a></div>

               

            <div class="slideBox_overlay a"></div>

            <div class="slideBox_overlay b"></div>

            </div>'.$after_widget;

             

       }    

      

        //load the starter javascript now

        $ss_widget_out .= ss_postsincat_js_starter($options);



        echo $ss_widget_out;



 }



/**

 * Scans the post for images within the content

 * Not called by default 

 *

 * @since 1.0

 * @param array $args

 * @return string|image

 */

function ss_image_by_scan( $args = array() )  {



    global $post;

    global $img_width;

    global $img_height;

    $image = '';

        

    preg_match( '|<img.*?src=[\'"](.*?)[\'"].*?>|i', $post->post_content, $matches );



    if ( isset( $matches ) ) $image = $matches[1][0];

    if ( $matches[0] )  {



            $image = $matches[0];

      

            $pattern = '/<img(.*?)class=[\'"](.*?)[\'"](.*?)\/>/i';

            $replacement = '<img$1 class="thumbnail" $3 />';

            $image = preg_replace($pattern, $replacement, $image);

 

            $pos1 = stripos($image, $img_width);

           

         // Yep, 'img_width' is certainly in 'image'

         if ($pos1 !== false)  {   

                    // send back the image

                    return $image;

                   

          } else  {

                // now replace the height, width and scr extension 

                

                $patterns[0] = '/width=[\'"](.*?)[\'"]/';

                $patterns[1] = '/height=[\'"](.*?)[\'"]/';

                $patterns[2] = '/(-\d+)(.*?)x(.*?)\./';

    

                $replacements[0] = 'width="' . $img_width . '"';

                $replacements[1] = 'height="' . $img_height . '"';

                $replacements[2] = "-" . $img_width . 'x' . $img_height.'.';

              

                $image = preg_replace( $patterns, $replacements, $image );

    

                // get the image path on server, look to see if it is there;    

                $pattern = '/<img(.*?)src=[\'"](.*?)[\'"](.*?)\/>/i';

                $replacement = ' $2 ';

                $justimage = preg_replace($pattern, $replacement, $image);    

                $imagefile = ABSPATH.substr($justimage,stripos($justimage,"wp-content"));



/*

echo '<br /> justimage -- '.$justimage.' -- <br />';                 

echo '<br /> ABSPATH -- '.ABSPATH.' -- <br />';

echo '<br /> substr  -- '.substr($justimage,stripos($justimage,"wp-content")).' -- <br />';

*/                              

               if (file_exists($imagefile)) {   // is_readable 

                    return $image;

                }else{                      

                     return false;

                }

          }

          

      } else  {

        return false;

      }



}



/**

* User config form for widget

* called by register_widget_control

*

*/



function ss_postsincat_widget_control() {	        

		

		$options = get_option ( 'ssPostsinCat_options' );



		if ( ! is_array($options) ){

			

			$options = array( 'add_cat_name'=>'on', 'trans_type'=>'Sine', 'trans_typeout'=>'easeIn', 'myaction'=>'click', 'speed'=>40, 'title'=>'More from', 'postnumb'=>12, 'show_post_title'=>'on', 'display_height'=>552, 'load_css'=>'on', 'load_css_from_theme'=>'on', 'theme'=>'default', 'imagesize'=>'thumbnail');



		 }else{	

			extract($options);

		 }

		

		//clean up and post

		if ( $_POST['op-submit'] ) {

		

			$options['title'] = strip_tags(stripslashes($_POST['op-title']));

			$options['add_cat_name'] = $_POST['op-add_cat_name'];

			$options['show_post_title'] = $_POST['op-show_post_title'];

			

			$options['postnumb'] = (int) $_POST['op-postnumb'];			

			$options['display_height'] = (int) $_POST['op-display_height'];

			$options['load_css'] =  $_POST['op-load_css'];

			$options['load_css_from_theme'] =  $_POST['op-load_css_from_theme'];

			$options['theme'] =  $_POST['op-theme'];

			$options['imagesize'] =  $_POST['op-imagesize'];

			$options['myaction'] =  $_POST['op-click'];

			$options['speed'] =  $_POST['op-speed'];

			

			/**/

			$options['trans_type']	= $_POST["op-trans_type"];

			$options['trans_typeout']	= $_POST["op-trans_typeout"];

			

			

			//save user options

			update_option('ssPostsinCat_options', $options);

		

		 }

	    

	    $selected ='selected="selected"';

	    $checked = 'checked="checked"';

	    

		// This is the form where we collect the user preferences

		// Notice that we don't need a complete form. This will be embedded into the existing form.

		

?>		

		<p style="text-align:right;"><label for="op-title"><?php echo __('Title:') ;?> 

		  <input style="width: 120px;" id="op-title" name="op-title" type="text" value="<?php echo $options['title']; ?>" /></label>

		<label for="op-add_cat_name"><?php echo __('Add cat name to title:') ;?> 

		  <input id="op-add_cat_name" name="op-add_cat_name" type="checkbox" <?php if($options['add_cat_name'] == "on") echo $checked; ?> value="on" /></label></p>

        <p style="text-align:right;"><label for="op-show_post_title"><?php echo __('Show Post tile:') ;?> 

            <input id="op-show_post_title" name="op-show_post_title" type="checkbox" <?php if($options['show_post_title'] == "on") echo $checked; ?> value="on" /></label></p>

		

		<p style="text-align:right;"><label for="op-postnumb"><?php echo __('Number of total posts:') ; ?> 

		  <input style="width: 40px;" id="op-postnumb" name="op-postnumb" type="text" value="<?php echo $options['postnumb']; ?>" /></label></p>

		<p style="text-align:right;"><label for="op-display_height"><?php echo __('Height of display area:') ; ?> 

		  <input style="width: 40px;" id="op-display_height" name="op-display_height" type="text" value="<?php echo $options['display_height']; ?>" /></label></p>

		<p style="text-align:right;"><label for="op-speed"> <?php echo __('Speed of display area scroll: (0 fast - 100 slow)') ; ?> 

		  <input style="width: 40px;" id="op-speed" name="op-speed" type="text" value="<?php echo $options['speed']; ?>" /></label></p>



		<p style="text-align:right;"><label for="op-click"><?php echo  __('To activate the scroller use:'); ?> <select id="op-click" name="op-click" >

            <option label="click" value="click" <?php if($options['myaction'] == "click") echo $selected; ?> >click</option>

            <option label="mouseover" value="mouseover" <?php if($options['myaction'] == "mouseover") echo $selected; ?>>mouseover</option>

        </select>

		</label></p>

		

		<p style="text-align:right;">

		<label for="op-trans_type"> <?php echo __(" Transition type:") ?> </label>  

		 <select name="op-trans_type" id="op-trans_type">

			 <option   <?php if($options['trans_type'] == "Sine") echo $selected; ?> id="Sine" value="Sine"> Sine</option>

			 <option   <?php if($options['trans_type'] == "Elastic") echo $selected; ?> id="Elastic" value="Elastic"> Elastic</option>

			 <option   <?php if($options['trans_type'] == "Bounce") echo $selected; ?> id="Bounce" value="Bounce"> Bounce</option>

			 <option   <?php if($options['trans_type'] == "Back") echo $selected; ?> id="Back" value="Back"> Back</option>

			 <option   <?php if($options['trans_type'] == "Expo") echo $selected; ?> id="Expo" value="Expo"> Expo</option>

			 <option   <?php if($options['trans_type'] == "Circ") echo $selected; ?> id="Circ" value="Circ"> Circ</option>

			 <option   <?php if($options['trans_type'] == "Quad") echo $selected; ?> id="Quad" value="Quad"> Quad</option>

			 <option   <?php if($options['trans_type'] == "Cubic") echo $selected; ?> id="Cubic" value="Cubic"> Cubic</option>

			 <option   <?php if($options['trans_type'] == "Linear") echo $selected; ?> id="Linear" value="Linear"> Linear</option>

			 <option   <?php if($options['trans_type'] == "Quart") echo $selected; ?> id="Quart" value="Quart"> Quart</option>

			 <option   <?php if($options['trans_type'] == "Quint") echo $selected; ?> id="Quint" value="Quint"> Quint</option>

			</select>

		



		<label for="op-trans_typeout"> <?php echo __(' Transition action:'); ?></label>

		<select name="op-trans_typeout" id="op-trans_typeout">

			 <option <?php if($options['trans_typeout'] == "easeIn") echo $selected; ?> id="easeIn" value="easeIn"> ease in</option>

			 <option <?php if($options['trans_typeout'] == "easeOut") echo $selected; ?> id="easeOut" value="easeOut"> ease out</option>

			 <option <?php if($options['trans_typeout'] == "easeInOut") echo $selected; ?>  id="easeInOut" value="easeInOut"> ease in out</option>     

		</select></p>

		

		<p style="text-align:right;><label for="op-imagesize"><?php echo __('Image size to use :') ; ?> 

		  <input style="width: 120px;" id="op-imagesize" name="op-imagesize" type="text" value="<?php echo $options['imagesize']; ?>" /></label></p><p style="text-align:right; font-size:0.8em; border-bottom: 1px solid #cdcdcd;"><?php echo __('medium, large, (minithumb, or slideshow, available if you have the superslider-show plugin installed)') ; ?></p>

		<p style="text-align:right;"><label for="op-load_css"><?php echo __('Load css into head:') ; ?> 

		  <input id="op-load_css" name="op-load_css" type="checkbox" <?php if($options['load_css'] == "on") echo $checked; ?> value="on" /></label></p><p style="text-align:right; font-size:0.8em; border-bottom: 1px solid #cdcdcd;"> If turned off you will want to add the required css to your theme css file.</p>		

		<p style="text-align:right;"><label for="op-load_css_from_theme"><?php echo __('Load css from postsincat plugin folder:') ; ?> 

		  <input id="op-load_css_from_theme" name="op-load_css_from_theme" type="checkbox" <?php if($options['load_css_from_theme'] == "on") echo $checked; ?> value="on" /> </label> </p><p style="text-align:right; font-size:0.8em; border-bottom: 1px solid #cdcdcd;">If turned off the css will load from wp-content/plugin-data/superslider/ssPostinCat/</p>

		

		<p style="text-align:right;"><label for="op-theme"><?php echo __('Theme to use:') ; ?> <select id="op-theme" name="op-theme" >

            <option label="default" value="default" <?php if( $options['theme'] == "default") echo $selected; ?> >default</option>

            <option label="black" value="black" <?php if( $options['theme'] == "black") echo $selected; ?> >black</option>

            <option label="blue" value="blue" <?php if( $options['theme'] == "blue") echo $selected; ?> >blue</option>

            <option label="custom" value="custom" <?php if( $options['theme'] == "custom") echo $selected; ?> >custom</option>

        </select>

		</label></p>

		

		<input type="hidden" id="op-submit" name="op-submit" value="1" />

<?php

 }

	    

/**

* Adds js and css to head of template file

* called by function ss_postsincat_head

*

*/

function ss_postsincat_load_css() {



		wp_enqueue_style( 'postincat_style');

}

function ss_postsincat_js_starter($options){

       

       global $slidebox_id;

      

        $initwidget = '

     var slidePosts'.$slidebox_id.' = new slidePosts(\'slider'.$slidebox_id.'\',{

		fadeArrows:true,

		startOpacity:0.4,

		endOpacity:1,

		speed:'.$options['speed'].',

		transition:Fx.Transitions.'.$options['trans_type'].'.'.$options['trans_typeout'].',

		myaction:\''.$options['myaction'].'\'

	});

       

        ';

        $headoutput = '<!-- SuperSlider postincat widget. -->';

        $headoutput .= "\n"."<li><script type=\"text/javascript\">\n";

        $headoutput .= "\t"."// <![CDATA[\n";		

        $headoutput .= "window.addEvent('domready', function() {

                ".$initwidget."

                });\n";

        $headoutput .= "\t".'// ]]>';

        $headoutput .= "\n".'</script></li>'."\n";

		

		return $headoutput;    

}

/**

* Calls the ss_postsincat_js_starter to add js and css to head of template file

* called by add action tempate redirect

*

*/

function ss_postsincat_head(){



    //if ( false !== strpos ( $content, 'slideBox-container' ) ) { 

    add_action ( 'wp_print_styles', 'ss_postsincat_load_css');    

    //do_action( 'ss_postsincat_load_css', $options  );

    

    //}

}



    add_action ( 'widgets_init', 'ss_postsincat_init');

    //add_action ( 'template_redirect' , 'ss_postsincat_head');

    





?>