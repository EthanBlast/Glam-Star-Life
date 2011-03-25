<?php
/*
 * @package Featured Posts
 * @author Nando Pappalardo e Giustino Borzacchiello
 * @version 1.4
 */
/*
Plugin Name: Featured Post with thumbnail
Plugin URI: http://www.yourinspirationweb.com/en/wordpress-plugin-featured-posts-with-thumbnails-highlighting-your-best-articles/
Description: This widget allows you to add in your blog's sidebar a list of featured post with thumbanil.
Author: Nando Pappalardo e Giustino Borzacchiello
Version: 1.4
Author URI: http://en.yourinspirationweb.com/

USAGE:

	LICENCE:

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
   
*/

/**
 * Determina il percorso del plugin
 * Determine plugin path
 */
$featured_post_plugin_path = WP_CONTENT_URL.'/plugins/'.plugin_basename(dirname(__FILE__)).'/';

if(function_exists('add_theme_support')) {
   add_theme_support( 'post-thumbnails' );
}


/**
 * Aggiunge il CSS del plugin
 * Enqueue plugin CSS file
 */
function featured_post_css() {
   global $featured_post_plugin_path;
   wp_enqueue_style('featured-post-css',$featured_post_plugin_path.'featured-post.css');
}
add_action('wp_print_styles', 'featured_post_css');


/**
 * Recupera la prima immagine del post
 * Returns the first image in the post
 *
 */
function catch_that_image() {
   global $post, $posts, $featured_post_plugin_path;
   $first_img = '';
   ob_start();
   ob_end_clean();
   $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
   $first_img = $matches [1] [0];

   if(empty($first_img)) { //Defines a default image
      $first_img = $featured_post_plugin_path . "images/default.gif";
   }
   return $first_img;
}


/**
 *
 * Mostra i post in evidenza
 * Show featured posts using unordered list
 * @param mixed $args
 */
function featured_posts_YIW( $args = null ) {

   global $featured_post_plugin_path;
   $defaults = array(
       'title'	      => 'Featured Posts',
       'numberposts'  => 5,
       'orderby'      => 'DESC',
       'widththumb'   => 73,
       'heightthumb'  => 73,
       'beforetitle'  => '<h3>',
       'aftertitle'   => '</h3>'
   );

   $fp = wp_parse_args($args, $defaults);
   /* User-selected settings. */
   $title	 = $fp['title'];
   $showposts    = $fp['numberposts'];
   $orderby      = $fp['orderby'];
   $width_thumb  = $fp['widththumb'];
   $height_thumb = $fp['heightthumb'];
   $before_title = $fp['beforetitle'];
   $after_title	 = $fp['aftertitle'];




   /* Titolo del widget (before e after definiti dal tema). */
   if ( ! empty($title) ) {
      echo $before_title . $title . $after_title;
   }

   /*
    * Modificare i parametri di questa funzione per mostrare/escludere categorie, pagine.
    * If you want to exclude categories and/or pages modify this function's arguments properly
    * Info: http://codex.wordpress.org/Template_Tags/get_posts
    */
   global $post;
   $featured_posts = get_posts('meta_key=featured&meta_value=1&numberposts='.$showposts.'&orderby='.$orderby);

   echo '<ul class="clearfix">';
   foreach ($featured_posts as $post):
      setup_postdata($post);
      ?>
<li>
   
	 <?php if ( (function_exists('the_post_thumbnail')) && (has_post_thumbnail()) ) :
	    the_post_thumbnail(array($width_thumb,$height_thumb,true));
	    else: ?>
	    <img src="<?php echo $featured_post_plugin_path ?>scripts/timthumb.php?src=<?php echo catch_that_image() ?>&amp;h=<?php echo $height_thumb ?>&amp;w=<?php echo $width_thumb ?>&amp;zc=1" class="alignleft" alt="<?php the_title(); ?>" />
	    <?php endif; ?>
   <h4 class="featured-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
</li>
   <?php
   endforeach;
   echo "</ul>";
}


/**
 * WIDGET SECTION
 *-----------------------------------------------------------------------*/

/*
 * Aggiunge file per la traduzione
 * add translate language
 */
load_plugin_textdomain('featured-post','wp-content/plugins/featured-posts/language/');

/*
 * Aggiungiamo la nostra funzione al gancio widgets_init
 * Add our function to widgets_init hook
 */
add_action( 'widgets_init', 'my_widget_featured_posts' );

/*
 * Funzione che registra il nostro widget
 * Register our widget
 */
function my_widget_featured_posts() {
   register_widget( 'Featured_posts' );
}

class Featured_posts extends WP_Widget {

   function Featured_posts() {
		/* Impostazione del widget */
      $widget_ops = array( 'classname' => 'widget_featured-posts', 'description' => __('This widget allows you to add in your blog\'s sidebar a list of featured posts.','featured-post') );

		/* Impostazioni di controllo del widget */
      $control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'widget_featured-posts' );

		/* Creiamo il widget */
      $this->WP_Widget( 'widget_featured-posts', __('Featured Posts','featured-post'), $widget_ops, $control_ops );
   }

   function widget( $args, $instance ) {
      extract( $args );
      $arguments = array(
	  'title'	     => $instance['title'],
	  'numberposts'  => $instance['showposts'],
	  'orderby'	     => $instance['orderby'],
	  'widththumb'   => $instance['width-thumb'],
	  'heightthumb'  => $instance['height-thumb'],
	  'beforetitle'  => $before_title,
	  'aftertitle'   => $after_title
      );
      global $featured_post_plugin_path;
      /* Before widget (definito dal tema). */
      echo $before_widget;
      featured_posts_YIW($arguments);
      echo $after_widget;
   }

   function update( $new_instance, $old_instance ) {
      $instance = $old_instance;
      /* Strip tags (if needed) and update the widget settings. */
      $instance['title'] = strip_tags( $new_instance['title'] );
      $instance['showposts'] = $new_instance['showposts'];
      $instance['orderby'] = $new_instance['orderby'];
      $instance['width-thumb'] = $new_instance['width-thumb'];
      $instance['height-thumb'] = $new_instance['height-thumb'];

      return $instance;
   }

   function form( $instance ) {
      /* Impostazioni di default del nostro widget */
      $defaults = array( 'title' => __('Featured Posts','featured-post'), 'showposts' => '', 'orderby' => '', 'width-thumb' => '73', 'height-thumb' => '73' );

      $instance = wp_parse_args( (array) $instance, $defaults ); ?>
<p>
   <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:','featured-post') ?></label>
   <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
</p>

<!-- numero di post da visualizzare -->
<p>
   <select name="<?php echo $this->get_field_name( 'showposts' ); ?>" id="<?php echo $this->get_field_id( 'showposts' ); ?>" >
	    <?php
	    for ($i=0; $i<=100; $i++) {
	       if ($instance['showposts'] == $i) {
		  $selected = " selected='selected'";
	       } else {
		  $selected = "";
	       }
	       echo '<option class="level-0" value="'.$i.'"'.$selected.'>'.$i.'</option>';
	    }
	    ?>
   </select>
   <label for="<?php echo $this->get_field_id( 'showposts' ); ?>"><?php _e('How many posts do you want to display?','featured-post') ?></label>
</p>

<!-- scelta del tipo di ordinamento -->
<p>
   <select name="<?php echo $this->get_field_name( 'orderby' ); ?>" id="<?php echo $this->get_field_id( 'orderby' ); ?>" >
      <option class="level-0" value="rand" <?php if ($instance['orderby'] == "random") echo " selected='selected'" ?>><?php _e('Random','featured-post') ?></option>
      <option class="level-0" value="title" <?php if ($instance['orderby'] == "title") echo " selected='selected'" ?>><?php _e('Title','featured-post') ?></option>
      <option class="level-0" value="date" <?php if ($instance['orderby'] == "date") echo " selected='selected'" ?>><?php _e('Date','featured-post') ?></option>
      <option class="level-0" value="author" <?php if ($instance['orderby'] == "author") echo " selected='selected'" ?>><?php _e('Author','featured-post') ?></option>
      <option class="level-0" value="modified" <?php if ($instance['orderby'] == "modified") echo " selected='selected'" ?>><?php _e('Modified','featured-post') ?></option>
      <option class="level-0" value="ID" <?php if ($instance['orderby'] == "ID") echo " selected='selected'" ?>><?php _e('ID','featured-post') ?></option>
   </select>
   <label for="<?php echo $this->get_field_id( 'orderby' ); ?>"><?php _e('Choose type of order:','featured-post') ?></label>
</p>

<p>
   <input id="<?php echo $this->get_field_id( 'width-thumb' ); ?>" name="<?php echo $this->get_field_name( 'width-thumb' ); ?>" value="<?php echo $instance['width-thumb']; ?>" style="width:20%;" />
   <label for="<?php echo $this->get_field_id( 'width-thumb' ); ?>"><?php _e('Width Thumbnail','featured-post') ?></label>
</p>

<p>
   <input id="<?php echo $this->get_field_id( 'height-thumb' ); ?>" name="<?php echo $this->get_field_name( 'height-thumb' ); ?>" value="<?php echo $instance['height-thumb']; ?>" style="width:20%;" />
   <label for="<?php echo $this->get_field_id( 'height-thumb' ); ?>"><?php _e('Height Thumbnail','featured-post') ?></label>
</p>
   <?php

   }
}//end class Featured_posts

/**
 * Aggiunge/rimuove il campo personalizzato featured
 * Add/remove featured custom field
 *
 * @param integer $post_ID
 */
function add_featured($post_ID) {
   $articolo = get_post($post_ID);

   if ($_POST['insert_featured_post'] == 'yes') {
      add_post_meta($articolo->ID, 'featured', 1, TRUE) or update_post_meta($articolo->ID, 'featured', 1);
   }
   elseif ( $_POST['insert_featured_post'] == 'no' ) {
      delete_post_meta($articolo->ID, 'featured');
   }
}

/**
 *
 * Mostra il form featured nella sezione "Scrivi Post"
 * Shows featured form in "Write Post" section
 */
function post_box() {
   global $post;
   $featured = get_post_meta($post->ID,featured,1);
   ?>
<label for="insert_featured_post"><?php _e('Featured post?','featured-post') ?></label>
<select name="insert_featured_post" id="insert_featured_post">
   <option value="yes" <?php if ($featured) echo 'selected="selected"'?>><?php _e('Yes','featured-post') ?>&nbsp;</option>
   <option value="no" <?php if (!$featured) echo 'selected="selected"'?>><?php _e('No ','featured-post') ?>&nbsp;</option>
</select>
<?php
}
function my_post_options_box() {
   add_meta_box('post_info', __('Featured','featured-post'), 'post_box', 'post', 'side', 'high');
}


add_action('admin_menu', 'my_post_options_box');

add_action('new_to_publish', 'add_featured');
add_action('save_post', 'add_featured');

?>
