<?php //Header
/*
Plugin Name: WP Coda Slider
Plugin URI: http://wp-performance.com/wp-coda-slider/
Description: Coda Slider featured category slider with shortcodes
Author: c3mdigital
Author URI: http://c3mdigital.com/
Version: 0.2.3.1
License: GPL v2 - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/
?>
<?php
	//   You can use the short code or template tag if you need to parse posts that include a shortcode
	//   To use the template tag add: <?php if( function_exists('c3m_wpcodaslider') ) { c3m_wpcodaslider($id, $cat, $show, $args);} ?>
<?php	//   you must supply the variables when you add the function to your template.  Example: c3m_codaslider('myslider', '81', '4', 'dynamicArrows:false');
	//   this would add a slider with the id of myslider and show 4 posts from category 81 with dynamic arrows set to false.
	//   all the variables must be present and in the same order.
	//
	//
//Template tag function

	function c3m_wpcodaslider($id, $cat, $show, $args) {   


		query_posts('post_type=post&order=desc&cat=' . $cat . '&posts_per_page= ' . $show . '');

           	echo  '<div class="coda-slider-wrapper"> <!-- yes -->
                         <div class="coda-slider preload" id="'. $id .'">';

        	if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

        		<div id="post-<?php the_ID(); ?>" <?php post_class('panel'); ?>>

                        	<div class="panel-wrapper">

                              		<h2 class="title"><?php the_title(); ?></h2>

                                        <?php the_content(); ?>

                                </div> <!-- .panel-wrapper -->

                        </div> <!-- .panel -->

       		 <?php endwhile; else: 
       		       endif; 
		 wp_reset_query(); ?>

   			</div><!-- .coda-slider .preload -->

                      </div><!-- coda-slider-wrapper -->

				<?php echo'<script type="text/javascript">
        					jQuery(document).ready(function($){
                               			 $().ready(function() {
                                       		 $(\'#'. $id .'\').codaSlider({' . $args .'});
                               			 });
        					});
                        		</script>';
			return $id;
			return $cat;
			return $show;
			return $args;

	}
//End Template Tag Function

//Lets load the scripts
        add_action ('init', 'c3m_coda_scripts');

        function c3m_coda_scripts() {
                 wp_enqueue_script('coda_slider', WP_PLUGIN_URL . '/wp-coda-slider/js/coda.slider.js', array('jquery'));
//enqueue style sheet
                wp_enqueue_style('coda_slider', WP_PLUGIN_URL . '/wp-coda-slider/css/coda-slider-2.0.1.css');
  	}

 //Begin Shortcode function

	$my_wpcodaslider = new wpcodaslider();
	class wpcodaslider{

        var $shortcode_name = 'wpcodaslider';
        var $pattern = '<!-- wpcodaslider -->';
        var $posts_content = '';

        function wpcodaslider() {
                 add_shortcode( $this->shortcode_name, array( &$this, 'shortcode' ) );

        }

// insert the shortcode in any page ie: [wpcodaslider id=slidername cat=4 show=3] will show first three post in category with id $
        function shortcode( $atts, $content = null ) {
                extract( shortcode_atts( array(
                        'cat' => null,
                    	'id'  =>  null,
                        'show' => null,
                        'args' => null
                	), $atts ) );

//Make sure there is a query and name
                if (! $cat || ! $id)
                        return 'Could not load slider. Mallformed shortcode.';
        $o = '
                <div class="coda-slider-wrapper">
                        <div class="coda-slider preload" id="'. $id .'">';

                $posts = get_posts('post_type=post&order=desc&cat= '. $cat . '&posts_per_page= ' . $show . '');
                foreach($posts as $post){

                        $o.=
                        '<div class="panel" id="post-' . $post->ID . '">
                                <div class="panel-wrapper">
                                        <h2 class="title">' . $post->post_title . '</h2>
                                        ' . $post->post_content . '
                                </div> <!-- .panel-wrapper -->
                        </div><!-- .panel #post-$id -->';
                }


                $o.='
                                </div><!-- .coda-slider .preload -->
                        </div><!-- coda-slider-wrapper -->
                        <script type="text/javascript">
        jQuery(document).ready(function($){
                                $().ready(function() {
                                        $(\'#'. $id .'\').codaSlider({' . $args .'});
                                });
        });
                        </script>';


              return $o;
        }
}

 ?>
