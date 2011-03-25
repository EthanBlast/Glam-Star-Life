<?php
class content_leaf {
	
	function content_leaf($leaf){
		$this->leaf = $leaf;
			
		$this->set_options();
		$this->determine_page_type();
		$this->set_up_hooks();
	}
	
	
	function set_options(){
		$this->count = 0;
		
		if($this->leaf['options']['post-limit'] && $this->leaf['options']['mode'] == 'posts'){
			$this->post_limit = $this->leaf['options']['post-limit'];
		} else {
			global $wp_query;
			
			$this->post_limit = ($wp_query->post_count < get_option('posts_per_page')) ? $wp_query->post_count : get_option('posts_per_page');
		}
		
		if(!isset($this->leaf['options']['excerpts']) || $this->leaf['options']['excerpts'] == 'default'){
			$this->disable_excerpts = headway_get_option('disable-excerpts') ? true : false;
			$this->small_excerpts = headway_get_option('small-excerpts') ? true : false; 
		} elseif($this->leaf['options']['excerpts'] == 'disable'){
			$this->disable_excerpts = true;
			$this->small_excerpts = false;
		} elseif($this->leaf['options']['excerpts'] == 'excerpts'){
			$this->disable_excerpts = false;
			$this->small_excerpts = false; 
		} elseif($this->leaf['options']['excerpts'] == 'small'){
			$this->disable_excerpts = false;
			$this->small_excerpts = true; 
		}

		$this->small_excepts_container_count = 0;
				
		$this->paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		
		$this->featured_posts = (isset($this->leaf['options']['featured-posts']) && $this->leaf['options']['mode'] != 'page') ? $this->leaf['options']['featured-posts'] : headway_get_option('featured-posts');
	}
	
	
	function determine_page_type(){
		$this->is_page = false;
		$this->is_post_query = false;
		$this->is_single = false;
		$this->is_date = false;
		$this->is_archive = false;
		$this->is_category = false;
		$this->is_tag = false;
		$this->is_author = false;
		$this->is_search = false;
		
		if($this->leaf['options']['other-page'] && $this->leaf['options']['mode'] == 'page'){
			$this->other_page_query = new WP_Query('page_id='.$this->leaf['options']['other-page']);
			$this->is_page = true;
		} elseif(is_home() || $this->leaf['options']['mode'] == 'posts'){
			$this->is_post_query = true;
		} elseif(is_page()){
			$this->is_page = true;
		} elseif(is_singular()){
			$this->is_single = true;
		} elseif(is_date()){
			$this->is_date = true;
			$this->is_archive = true;
		} elseif(is_category()){
			$this->is_category = true;
			$this->is_archive = true;
		} elseif(is_tag()){
			$this->is_tag = true;
			$this->is_archive = true;
		} elseif(is_author()){
			$this->is_author = true;
			$this->is_archive = true;
		} elseif(is_search()){
			$this->is_search = true;
			$this->is_archive = true;
		}
	}
	
	
	function set_up_hooks(){		
		add_action('headway_after_post_content', array(&$this, 'display_more_link'));
		add_action('headway_after_excerpt_content', array(&$this, 'display_more_link'));

		add_action('headway_post_content_open', array(&$this, 'display_post_thumbnail'));
		add_action('headway_excerpt_content_open', array(&$this, 'display_post_thumbnail'));

		add_action('headway_post_navigation', array(&$this, 'display_post_navigation'));

		if(!isset($this->leaf['options']['hide-post-meta'])){
			//Post Meta
			add_action('headway_before_post_title', array(&$this, 'display_post_meta_before_title'));
			add_action('headway_before_excerpt_title', array(&$this, 'display_post_meta_before_title'));

			add_action('headway_after_post_title', array(&$this, 'display_post_meta_after_title'));	
			add_action('headway_after_excerpt_title', array(&$this, 'display_post_meta_after_title'));	
			
			add_action('headway_after_post_content', array(&$this, 'display_post_meta_after_content'));
			add_action('headway_after_excerpt_content', array(&$this, 'display_post_meta_after_content'));
		}
		
		do_action('headway_content_leaf_hook_setup', $this->leaf);
	}
	
	
	function reset_hooks(){
		//Reset hooks
		
		remove_action('headway_after_post_content', array(&$this, 'display_more_link'));
		remove_action('headway_after_excerpt_content', array(&$this, 'display_more_link'));

		remove_action('headway_post_content_open', array(&$this, 'display_post_thumbnail'));
		remove_action('headway_excerpt_content_open', array(&$this, 'display_post_thumbnail'));

		remove_action('headway_before_post_title', array(&$this, 'display_post_meta_before_title'));
		remove_action('headway_before_excerpt_title', array(&$this, 'display_post_meta_before_title'));

		remove_action('headway_after_post_title', array(&$this, 'display_post_meta_after_title'));	
		remove_action('headway_after_excerpt_title', array(&$this, 'display_post_meta_after_title'));	

		remove_action('headway_after_post_content', array(&$this, 'display_post_meta_after_content'));
		remove_action('headway_after_excerpt_content', array(&$this, 'display_post_meta_after_content'));
		
		remove_action('headway_post_navigation', array(&$this, 'display_post_navigation'));
	}
	
	
	function display(){
		$this->display_greet_box();
		
		if($this->is_page){
			$this->display_page();
		} elseif($this->is_single){
			$this->display_featured_post(array('single' => true));
		} elseif($this->is_archive) {
			$this->display_post_query(array('archive' => true));
		} else {
			$this->display_post_query();
		}
		
		$this->reset_hooks();
	}
	
	
	function display_page(){		
		if(isset($this->other_page_query)){ 
			$this->other_page_query->the_post();
		} else {
			the_post();
		}
		
		if(!$this->leaf['options']['hide-content']){
			do_action('headway_before_entry');
			do_action('headway_before_page');
		
			//Set up post wrapper
			echo '<div id="post-'.get_the_id().'" class="'.headway_post_class(false).' clearfix">';
		
				//If hide title post meta property is not existant, display the title
				if(!get_post_meta(get_the_id(), '_hide_title', true)){
					$custom_title = headway_get_write_box_value('custom-title', false, get_the_id());
			
					$title = $custom_title ? $custom_title : get_the_title();;
			
					do_action('headway_before_entry_title');
					do_action('headway_before_page_title');
					echo '<h1 class="page-title">'.$title.'</h1>';
					do_action('headway_after_page_title');
					do_action('headway_after_entry_title');
				}
		
				do_action('headway_before_entry_content');
				do_action('headway_before_page_content');
		
				echo '<div class="entry-content">';
			
					do_action('headway_page_content_open');
					do_action('headway_entry_content_open');
		
					the_content();
					wp_link_pages(array('before' => '<div id="page-links"><strong>Pages: </strong>', 'after' => '</div>'));
				
					do_action('headway_entry_content_close');
					do_action('headway_page_content_close');
		
				echo '</div><!-- .entry-content -->';
			
				do_action('headway_after_page_content');
				do_action('headway_after_entry_content');
	
			echo '</div><!-- #post-'.get_the_id().' -->';
		
			do_action('headway_after_page');
			do_action('headway_after_entry');
		}
		
		if(headway_get_option('page-comments') && !$this->leaf['options']['hide-comments']){
			do_action('headway_before_comments', 'page');
			comments_template('', true);
			do_action('headway_after_comments', 'page');
		}
	}
	
	
	function display_post_query($args = array()){
		$defaults = array('archive' => false);
		extract($defaults);
		extract($args, EXTR_OVERWRITE);
		
		//If is default mode
		if($this->leaf['options']['mode'] == 'page'){
			$this->display_query_title();
						
			$count = 0;			
						
			while(have_posts()){
				the_post();
				global $post;
							
				$count++;
				
				if(($archive) && !$this->disable_excerpts){
					$this->display_post(array('count' => $count));
				} elseif(($count <= $this->featured_posts && $this->paged == 1) || $this->disable_excerpts){
					$this->display_featured_post(array('count' => $count));
				} else {	
					$this->display_post(array('count' => $count));
				}
			}
					
			do_action('headway_post_navigation');
			
			
		//If Custom Query
		} else {
			
			//Setup Query Options
			$query_options = array();

			if($this->leaf['options']['post-limit'] && $this->leaf['options']['paginate']) $query_options['posts_per_page'] = $this->post_limit;
			if($this->leaf['options']['post-limit'] && !$this->leaf['options']['paginate']) $query_options['showposts'] = $this->post_limit;

			if(isset($this->leaf['options']['categories']) && $this->leaf['options']['categories'][0] != NULL){
				if($this->leaf['options']['categories-mode'] == 'include') $query_options['category__in'] = $this->leaf['options']['categories'];
				if($this->leaf['options']['categories-mode'] == 'exclude') $query_options['category__not_in'] = $this->leaf['options']['categories'];	
			} 

			if($this->leaf['options']['author']) $query_options['author'] = $this->leaf['options']['author'];

			$query_options['orderby'] = $this->leaf['options']['orderby'];
			$query_options['order'] = $this->leaf['options']['order'];
			$query_options['offset'] = $this->leaf['options']['offset'];
			
			if($this->leaf['options']['post-type']) $query_options['post_type'] = $this->leaf['options']['post-type'];
			
			if($this->leaf['options']['paginate']){
				$query_options['paged'] = $this->paged;
				
				if($query_options['offset'] >= 1 && $query_options['paged'] > 1){
					$query_options['offset'] = $query_options['offset'] + $this->post_limit*($query_options['paged']-1);
				}
			}

			//Initiate query instance
			$this->posts_query = new WP_Query($query_options);
			
			$this->post_limit = ($this->posts_query->post_count < $this->post_limit) ? $this->posts_query->post_count : $this->post_limit;
			
			$count = 0;
			
			while($this->posts_query->have_posts()){
				$this->posts_query->the_post();
				global $post;
							
				$count++;
				
				if($archive){
					$this->display_post(array('count' => $count));
				} elseif(($count <= $this->featured_posts && $this->paged == 1) || $this->disable_excerpts){
					$this->display_featured_post(array('count' => $count));
				} else {	
					$this->display_post(array('count' => $count));
				}
			}
			
			if($this->leaf['options']['paginate']){
				do_action('headway_post_navigation', array('max_pages' => $this->posts_query->max_num_pages));
			}
		}
	}
	
	
	function display_featured_post($args = array()){
		$defaults = array('count' => false, 'single' => false);
		extract($defaults);
		extract($args, EXTR_OVERWRITE);
		
		if(!isset($this->leaf['options']['hide-content']) || !$this->leaf['options']['hide-content']){
			global $more;
			if($single){
				$more = 1;
			} else {
				$more = 0;
			}
		
			do_action('headway_before_entry');
			do_action('headway_before_post', array('count' => $count, 'featured' => true, 'single' => $single));
		
			echo "\n".'<div id="post-'.get_the_id().'" class="'.headway_post_class(false).' clearfix">'."\n";
		
				do_action('headway_before_entry_title');
				do_action('headway_before_post_title', array('count' => $count, 'featured' => true, 'single' => $single));
				
				if(is_single()){
					echo '<h1 class="entry-title">'.get_the_title().'</h1>'."\n";
				} else {
					echo '<h2 class="entry-title"><a href="'.get_permalink().'" title="Link to '.esc_html(get_the_title(), 1).'" rel="bookmark">'.get_the_title().'</a></h2>'."\n";
				}
				
				do_action('headway_after_post_title', array('count' => $count, 'featured' => true, 'single' => $single));
				do_action('headway_after_entry_title');

				do_action('headway_before_entry_content');
				do_action('headway_before_post_content', array('count' => $count, 'featured' => true, 'single' => $single));

				echo '<div class="entry-content">'."\n";
			
					do_action('headway_post_content_open', array('count' => $count, 'featured' => true, 'single' => $single));
					do_action('headway_entry_content_open');
								
					the_content(false); 
				
					//Single post stuff
					if($single){
						wp_link_pages(array('before' => '<div id="page-links"><strong>Pages: </strong>', 'after' => '</div>'));
					}
					//End single post stuff
				
					do_action('headway_entry_content_close');
					do_action('headway_post_content_close', array('count' => $count, 'featured' => true, 'single' => $single));
		
				echo '</div><!-- .entry-content -->'."\n";
		
				do_action('headway_after_post_content', array('count' => $count, 'featured' => true, 'single' => $single));
				do_action('headway_after_entry_content');
		
			echo '</div><!-- .post-'.get_the_id().' -->'."\n\n";
		
			do_action('headway_after_post', array('count' => $count, 'featured' => true, 'single' => $single));
			do_action('headway_after_entry');
		}

		//Will display adjacent post navigation and comments on single posts only.
		if($single){
			if(!isset($this->leaf['options']['hide-content']) || !$this->leaf['options']['hide-content']){
				if(get_adjacent_post() || get_adjacent_post(false, false, false, false, false))
					do_action('headway_post_navigation', array('single' => true));
					
			}
		
			if(!isset($this->leaf['options']['hide-comments']) || !$this->leaf['options']['hide-comments']){
				do_action('headway_before_comments', 'post');
				comments_template('', true);
				do_action('headway_after_comments', 'post');
			}
		}
		//End single post stuff
		
	}
	
	
	function display_post($args = array()){
		$defaults = array('count' => false, 'small_excerpts' => $this->small_excerpts, 'single' => false);
		extract($defaults);
		extract($args, EXTR_OVERWRITE);
		
		global $more;
		$more = 0;
		
		if($small_excerpts){
			$this->small_excerpts_opening($count);
			$small_excerpts_class = $this->small_excerpts_class;
		} else {
			$small_excerpts_class = false;
		}
				
		do_action('headway_before_entry');		
		do_action('headway_before_excerpt', array('count' => $count, 'excerpts' => true, 'small_excerpts' => $small_excerpts));
		
		echo "\n".'<div id="post-'.get_the_id().'" class="'.headway_post_class(false).' small-post'.$small_excerpts_class.' clearfix">'."\n";
		
			do_action('headway_before_entry_title');
			if(get_post_type() == 'post') do_action('headway_before_excerpt_title', array('count' => $count, 'excerpts' => true, 'small_excerpts' => $small_excerpts));
			echo '<h2 class="entry-title"><a href="'.get_permalink().'" title="Link to '.esc_html(get_the_title(), 1).'" rel="bookmark">'.get_the_title().'</a></h2>'."\n";
			if(get_post_type() == 'post') do_action('headway_after_excerpt_title', array('count' => $count, 'excerpts' => true, 'small_excerpts' => $small_excerpts));
			do_action('headway_after_entry_title');
			
			echo '<h2><a href="'.get_permalink().'" title="Link to '.esc_html(get_the_title(), 1).'" rel="bookmark">'.$avatar.'</a></h2>'."\n";

			do_action('headway_before_entry_content');
			if(get_post_type() == 'post') do_action('headway_before_excerpt_content', array('count' => $count, 'excerpts' => true, 'small_excerpts' => $small_excerpts));

			echo '<div class="entry-content">'."\n";
			
				do_action('headway_excerpt_content_open', array('count' => $count, 'excerpts' => true, 'small_excerpts' => $small_excerpts));
				do_action('headway_entry_content_open');
										
				the_excerpt(); 
				
				do_action('headway_entry_content_close');
				do_action('headway_excerpt_content_close', array('count' => $count, 'excerpts' => true, 'small_excerpts' => $small_excerpts));
		
			echo '</div><!-- .entry-content -->'."\n";
		
			if(get_post_type() == 'post') do_action('headway_after_excerpt_content', array('count' => $count, 'excerpts' => true, 'small_excerpts' => $small_excerpts));
			do_action('headway_after_entry_content');
		
		echo '</div><!-- .post-'.get_the_id().' -->'."\n\n";
		
		do_action('headway_after_excerpt', array('count' => $count, 'excerpts' => true, 'small_excerpts' => $small_excerpts));
		do_action('headway_after_entry');
		
		if($small_excerpts){
			$this->small_excerpts_close($count);
		}
	}
	
	
	function display_post_meta_before_title($args = array()){
		echo '<h2><a href="'.get_permalink().'" title="Link to '.esc_html(get_the_title(), 1).'" rel="bookmark">'.$avatar.'</a></h2>'."\n";
		$defaults = array('small_excerpts' => false);
		extract($defaults);
		extract($args, EXTR_OVERWRITE);
		
		headway_post_meta('title', 'above', $small_excerpts);
	}
	
	
	function display_post_meta_after_title($args = array()){
		echo '<h2><a href="'.get_permalink().'" title="Link to '.esc_html(get_the_title(), 1).'" rel="bookmark">'.$avatar.'</a></h2>'."\n";
		$defaults = array('small_excerpts' => false);
		extract($defaults);
		extract($args, EXTR_OVERWRITE);
		
		headway_post_meta('title', 'below', $small_excerpts);
	}
	
	
	function display_post_meta_after_content($args = array()){
		echo '<h2><a href="'.get_permalink().'" title="Link to '.esc_html(get_the_title(), 1).'" rel="bookmark">'.$avatar.'</a></h2>'."\n";
		$defaults = array('small_excerpts' => false);
		extract($defaults);
		extract($args, EXTR_OVERWRITE);
		
		headway_post_meta('content', 'below', $small_excerpts);
	}

	
	function display_post_navigation($args = false){
		$defaults = array('single' => false, 'max_pages' => 0);
		extract($defaults);
		
		if(is_array($args))
			extract($args, EXTR_OVERWRITE);
		
		if(!$single){
			$next = get_next_posts_link(apply_filters('headway_older_posts_link', '<span class="meta-nav">&laquo;</span> Older Posts'), $max_pages);
			$previous = get_previous_posts_link(apply_filters('headway_newer_posts_link', 'Newer Posts <span class="meta-nav">&raquo;</span>'));
			
			if(!$next && !$previous) return false;
			
			echo '<div class="nav-below navigation clearfix">';
	
			if(!function_exists('wp_pagenavi')){
				echo '<div class="nav-previous">';
					echo $next;
				echo '</div>';
		
				echo '<div class="nav-next">';
					echo $previous;
				echo '</div>';
			} else { 
				if(isset($this->posts_query)){
					global $wp_query;
					$wp_query_temp = $wp_query;
					$wp_query = $this->posts_query;
					
					wp_pagenavi(); 
					
					$wp_query = $wp_query_temp;
				} else {
					wp_pagenavi(); 
				}
			}			
	
			echo '</div><!-- .nav-below -->';
		} else {
			if(!get_adjacent_post(false, false, true) && !get_adjacent_post(false, false, false)) return false;
			
			echo '<div id="nav-below-single" class="nav-below navigation clearfix">';
				
			if(!function_exists('wp_pagenavi')){
				echo '<div class="nav-previous">';
					previous_post_link('%link', apply_filters('headway_previous_post_link', __('<span class="meta-nav">&laquo;</span> Previous Post', 'headway')));
				echo '</div>';
		
				echo '<div class="nav-next">';
					next_post_link('%link', apply_filters('headway_next_post_link', __('Next Post <span class="meta-nav">&raquo;</span>', 'headway')));
				echo '</div>';
			} else { 
				wp_pagenavi(); 
			}			
	
			echo '</div><!-- #nav-below-single -->';
		}
	}


	function display_query_title(){
		if(!$this->is_archive) return false;
		
		//Category
		if($this->is_category){
			echo apply_filters('headway_category_archives_title', sprintf(__('<h2 class="page-title archives-title">Category Archives: %s</span></h2>', 'headway'), single_cat_title(false, false)));
				
			if(category_description()) echo apply_filters('headway_category_archives_description', '<div class="archive-meta">'.category_description().'</div>');
			
		//Chronological/Date Archive
		} elseif($this->is_date){
			if(is_day()){
				echo apply_filters('headway_archives_title', sprintf(__('<h2 class="page-title archives-title">Daily Archives: %s</h2>', 'headway'), get_the_time(headway_get_option('date_format'))));
			} elseif(is_month()){
				echo apply_filters('headway_archives_title', sprintf(__('<h2 class="page-title archives-title">Monthly Archives: %s</h2>', 'headway'), get_the_time('F Y')));
			} elseif(is_year()){
				echo apply_filters('headway_archives_title', sprintf(__('<h2 class="page-title archives-title">Yearly Archives: %s</h2>', 'headway'), get_the_time('Y')));
			}
			
		//Tag Archive
		} elseif($this->is_tag){
			 echo apply_filters('headway_tag_archives_title', sprintf(__('<h2 class="page-title archives-title">Tag Archive: %s</h2>', 'headway'), single_tag_title(false, false)));
			
		//Author Archive
		} elseif($this->is_author){
			if(get_query_var('author_name')){
				$author_data = get_userdatabylogin(get_query_var('author_name'));
			} else {
				$author_data = get_userdata(get_query_var('author'));
			}
			
			echo apply_filters('headway_author_archives_title', sprintf(__('<h2 class="page-title author archives-title">Author Archives: %s</h2>', 'headway'), $author_data->display_name));
			if(isset($author_data->user_description)) echo apply_filters('headway_author_bio', '<div class="archive-meta">'.$author_data->user_description.'</div>');
			
		//Search
		} elseif($this->is_search){
			echo apply_filters('headway_search_archives_title', sprintf(__('<h2 class="page-title archives-title">Search Results for: <span id="search-terms">%s</span></h2>', 'headway'), get_search_query()));
		}
	}
	

	function display_greet_box(){
		if(function_exists('wp_greet_box') && !isset($this->wp_greet_box_ran)){
			wp_greet_box(); 
			$this->wp_greet_box_ran = true;
		}
	}
	
	
	function display_more_link($args = array()){	
		$defaults = array('count' => false, 'single' => false, 'small_excerpts' => false);
		extract($defaults);
		extract($args, EXTR_OVERWRITE);
		
		if($single) return false;
		
		global $post;
				
		if(strpos($post->post_content, '<!--more-->') || $excerpts){
			echo '<p><a href="'.get_permalink().'" class="more-link">'.headway_get_option('read-more-text').'</a></p>';
		}
	}
	
	
	function display_post_thumbnail($args = array()){
		$defaults = array('count' => false, 'single' => false, 'featured' => false, 'excerpts' => false, 'small_excerpts' => false);
		extract($defaults);
		extract($args, EXTR_OVERWRITE);
		
		if(function_exists('the_post_thumbnail') && has_post_thumbnail()){
			if(headway_get_option('hide-post-thumbnail-on-single') && $single) return false;
			if(headway_get_option('hide-post-thumbnail-on-featured') && $featured) return false;
			if(headway_get_option('hide-post-thumbnail-on-excerpts') && $excerpts) return false;
			if(headway_get_option('hide-post-thumbnail-on-small-excerpts') && $small_excerpts) return false;
			
			$position_query = headway_get_write_box_value('image-alignment', false, get_the_id());
			$position = ($position_query) ? ' '.$position_query : ' post-image-right';
						
			//Set Up Size		
			if($small_excerpts){
				$small_excerpt_thumbnail_width = str_replace('px', '', headway_get_option('small-excerpt-thumbnail-width'));			
				$small_excerpt_thumbnail_height = str_replace('px', '', headway_get_option('small-excerpt-thumbnail-height'));
				
				if($small_excerpt_thumbnail_width && $small_excerpt_thumbnail_height){
					$size = array($small_excerpt_thumbnail_width,  $small_excerpt_thumbnail_height);
				} else {
					$size = array(460, 460);
				}
			} else {
				$size = array(str_replace('px', '', headway_get_option('post-thumbnail-width')), str_replace('px', '', headway_get_option('post-thumbnail-height')));
			}
									
			echo '<div class="post-image'.$position.'">'.get_the_post_thumbnail(get_the_id(), $size).'</div>'."\n";
		}
	}
	

	function small_excerpts_opening($count){
		if($this->small_excepts_container_count === 0){
			echo '<div class="small-excerpts-row">'; 
			
			$this->small_excerpts_class = ' small-excerpts-post small-excerpts-post-left';
		} else {
			$this->small_excerpts_class = ' small-excerpts-post small-excerpts-post-right';
		}
				
		$this->small_excepts_container_count++;
	}
	
	
	function small_excerpts_close($count){
		if($this->small_excepts_container_count === 2){
			echo '</div>';
			
			$this->small_excepts_container_count -= 2;
		} elseif($count == $this->post_limit && $this->small_excepts_container_count !== 0){
			echo '</div>';
		}
	}
	
	
}


function content_leaf_inner($leaf){
	if(isset($leaf['new'])){
		$leaf['options']['mode'] = 'page';
		$leaf['options']['categories-mode'] = 'include';
		$leaf['options']['post-limit'] = get_option('posts_per_page');		
		$leaf['options']['featured-posts'] = is_numeric(headway_get_option('featured-posts')) ? headway_get_option('featured-posts') : '1';	
		$leaf['options']['paginate'] = 'on';	
		$leaf['options']['offset'] = '0';

		$leaf['options']['order'] = 'date';	
		$leaf['options']['orderby'] = 'DESC';	
	}	
	
		if($categories_select): //Fixes select for multiple featured boxes.  Without this it will compound the categories.
			$categories_select = '';
			$categories = '';
			$select_selected = array();
		endif;

		$categories = $leaf['options']['categories'];
		$categories_select_query = get_categories();
		$categories_select = '';
		foreach($categories_select_query as $category){ 
			if(is_array($categories)){
				if(in_array($category->term_id, $categories)) $select_selected[$category->term_id] = ' selected';
			}

			$categories_select .= '<option value="'.$category->term_id.'"'.$select_selected[$category->term_id].'>'.$category->name.'</option>';

		}


		$pages_select = '<option value="">&mdash; Do Not Fetch &mdash;</option>';
		$page_select_query = get_pages();
		foreach($page_select_query as $page){ 
			if($page->ID == $leaf['options']['other-page']) $selected[$page->ID] = ' selected';
			$pages_select .= '<option value="'.$page->ID.'"'.$selected[$page->ID].'>'.$page->post_title.'</option>';
		}
	
	
	if($leaf['options']['mode'] != 'posts'){
		$display['posts-options'] = ' style="display: none;"';	
		$display['page-options'] = null;	
	} else {
		$display['posts-options'] = null;	
		$display['page-options'] = ' style="display: none;"';
	}
?>
	<ul class="clearfix tabs">
        <li><a href="#mode-tab-<?php echo $leaf['id'] ?>">Mode</a></li>
        <li><a href="#options-tab-<?php echo $leaf['id'] ?>">Options</a></li>
        <li class="<?php echo $leaf['id'] ?>_posts_options"<?php echo $display['posts-options']; ?>><a href="#filters-tab-<?php echo $leaf['id'] ?>">Filters</a></li>
        <li class="<?php echo $leaf['id'] ?>_page_options"<?php echo $display['page-options']; ?>><a href="#comments-options-tab-<?php echo $leaf['id'] ?>">Comments</a></li>
        <li><a href="#miscellaneous-tab-<?php echo $leaf['id'] ?>">Miscellaneous</a></li>
    </ul>

	<div id="mode-tab-<?php echo $leaf['id'] ?>">
		<table class="tab-options">
			<tr class="no-border">
				<th scope="row"><label>Mode</label></th>
				<td>
						<script type="text/javascript">
							var posts_options_<?php echo $leaf['id'] ?> = ".<?php echo $leaf['id'] ?>_posts_options";
							var page_options_<?php echo $leaf['id'] ?> = ".<?php echo $leaf['id'] ?>_page_options";
						</script>
						<p class="radio-container">
							<input type="radio" name="leaf-options[<?php echo $leaf['id'] ?>][mode]" id="<?php echo $leaf['id'] ?>_mode_page" class="radio headway-visual-editor-input" value="page" onclick="jQuery(posts_options_<?php echo $leaf['id'] ?>).hide();jQuery(page_options_<?php echo $leaf['id'] ?>).show();"<?php echo headway_radio_value($leaf['options']['mode'], 'page') ?> /><label for="<?php echo $leaf['id'] ?>_mode_page" class="no-clear">Default Behavior</label>
						</p>

						<p class="radio-container">
							<input type="radio" name="leaf-options[<?php echo $leaf['id'] ?>][mode]" id="<?php echo $leaf['id'] ?>_mode_posts" class="radio headway-visual-editor-input" value="posts" onclick="jQuery(posts_options_<?php echo $leaf['id'] ?>).show();jQuery(page_options_<?php echo $leaf['id'] ?>).hide();"<?php echo headway_radio_value($leaf['options']['mode'], 'posts') ?> /><label for="<?php echo $leaf['id'] ?>_mode_posts" class="no-clear">Custom Query</label>
						</p>
				</td>
			</tr>
		
			<tr style="<?php echo $display['page-options'] ?>" class="<?php echo $leaf['id'] ?>_page_options no-border">
				<td colspan="2">
					<p class="info-box info-box-with-bg">The content leaf is extremely versatile.  If the default behavior is selected, it will do what you expect it to do.  For example, if you add this on a page, it will display that pages content.  If you add it on the index system page, it will list the posts like a normal blog template and if you add this box on a category page, it will list posts of that category.  You can also add a content box and display the content from a completely separate page.  Choose the page below, otherwise leave the select box empty.</p>
				</td>
			</tr>
		
		
			<tr style="<?php echo $display['page-options'] ?>" class="<?php echo $leaf['id'] ?>_page_options no-border">
				<th scope="row"><label for="<?php echo $leaf['id'] ?>_other_page">Fetch Content From Other Page</label></th>
				<td>
					<select class="headway-visual-editor-input" name="leaf-options[<?php echo $leaf['id'] ?>][other-page]" id="<?php echo $leaf['id'] ?>_other_page">
						<?php echo $pages_select ?>
					</select>
				</td>
			</tr>
		</table>
	</div>	
	
	<div id="filters-tab-<?php echo $leaf['id']; ?>">
		<table class="tab-options">
			
			<tr>
				<th scope="row"><label for="<?php echo $leaf['id'] ?>_post_type">Limit By Post Type</label></th>
				<td>
					<select class="headway-visual-editor-input" name="leaf-options[<?php echo $leaf['id'] ?>][post-type]" id="<?php echo $leaf['id'] ?>_post_type">
						<?php 
						$post_types = get_post_types(false, 'objects'); 
						
						$bad_post_types = array('page', 'revision', 'nav_menu_item');
					
						foreach($post_types as $post_type => $post_type_options){
							if(in_array($post_type, $bad_post_types)) continue;
							
							$selected_post_type = $post_type == $leaf['options']['post-type'] ? ' selected' : false;
							
							echo '<option value="'.$post_type.'"'.$selected_post_type.'>'.$post_type_options->labels->name.'</option>';
						}
						?>
					</select>
				</td>
			</tr>
			
			<tr>
				<th scope="row"><label>Categories Mode</label></th>
				<td>
					<p class="radio-container">
						<input type="radio" name="leaf-options[<?php echo $leaf['id'] ?>][categories-mode]" id="<?php echo $leaf['id'] ?>_mode_include" class="radio headway-visual-editor-input" value="include"<?php echo headway_radio_value($leaf['options']['categories-mode'], 'include') ?>  />
						<label for="<?php echo $leaf['id'] ?>_mode_include" class="no-clear">Include</label>
					</p>
				
					<p class="radio-container">
						<input type="radio" name="leaf-options[<?php echo $leaf['id'] ?>][categories-mode]" id="<?php echo $leaf['id'] ?>_mode_exclude" class="radio headway-visual-editor-input" value="exclude"<?php echo headway_radio_value($leaf['options']['categories-mode'], 'exclude') ?>  />
						<label for="<?php echo $leaf['id'] ?>_mode_exclude" class="no-clear">Exclude</label>
					</p>
				</td>
			</tr>
		
		
			
			<tr>
				<td colspan="2">
					<p class="info-box info-box-with-bg">The categories select box has two modes. You can set it to include specific categories or you can exclude specific categories.  Leave it blank to include all categories.</p>
				</td>
			</tr>
		
		
			<tr>
				<th scope="row"><label for="<?php echo $leaf['id'] ?>_categories">Categories</label></th>
				<td>
					<select class="headway-visual-editor-input" name="leaf-options[<?php echo $leaf['id'] ?>][categories][]" id="<?php echo $leaf['id'] ?>_categories" multiple size="10">
						<?php echo $categories_select; ?>
					</select>
				</td>
			</tr>
			
			
			<tr class="no-border">
				<th scope="row"><label for="<?php echo $leaf['id'] ?>_author">Limit By Author</label></th>
				<td>
					<?php wp_dropdown_users( array('show_option_all' => '   ', 'multi' => true, 'name' => 'leaf-options['.$leaf['id'].'][author]', 'selected' => $leaf['options']['author'], 'class' => 'headway-visual-editor-input') ); ?> 
				</td>
			</tr>
		</table>
	</div>
	
	<div id="options-tab-<?php echo $leaf['id']; ?>">
		<table class="tab-options">
			<tr class="<?php echo $leaf['id'] ?>_posts_options"<?php echo $display['posts-options']; ?>>
				<th scope="row"><label for="<?php echo $leaf['id'] ?>_post_limit">Post Limit</label></th>
				<td>
					<input type="text" class="headway-visual-editor-input" name="leaf-options[<?php echo $leaf['id'] ?>][post-limit]" id="<?php echo $leaf['id'] ?>_post_limit" value="<?php echo $leaf['options']['post-limit'] ?>" />
				</td>
			</tr>
	
	
			<tr>
				<th scope="row"><label for="<?php echo $leaf['id'] ?>_featured_posts">Featured Posts</label></th>
				<td>
					<input type="text" class="headway-visual-editor-input" name="leaf-options[<?php echo $leaf['id'] ?>][featured-posts]" id="<?php echo $leaf['id'] ?>_featured_posts" value="<?php echo $leaf['options']['featured-posts'] ?>" />
				</td>
			</tr>
	
	
			<tr class="<?php echo $leaf['id'] ?>_posts_options"<?php echo $display['posts-options']; ?>>
				<th scope="row"><label>Pagination</label></th>
				<td>
					<p class="radio-container">
						<input type="checkbox" class="radio headway-visual-editor-input" id="<?php echo $leaf['id'] ?>_paginate" name="leaf-options[<?php echo $leaf['id'] ?>][paginate]"<?php echo headway_checkbox_value($leaf['options']['paginate']) ?> />
						<label for="<?php echo $leaf['id'] ?>_paginate">Paginate Posts</label>
					</p>
				</td>	
			</tr>
	
			<tr class="<?php echo $leaf['id'] ?>_posts_options"<?php echo $display['posts-options']; ?>>
				<th scope="row"><label for="<?php echo $leaf['id'] ?>_orderby">Order By</label></th>
				<td>
					<select class="headway-visual-editor-input" name="leaf-options[<?php echo $leaf['id'] ?>][orderby]" id="<?php echo $leaf['id'] ?>_orderby">
						<option value="date"<?php echo headway_option_value($leaf['options']['orderby'], 'date') ?>>Date</option>
						<option value="title"<?php echo headway_option_value($leaf['options']['orderby'], 'title') ?>>Title</option>
						<option value="rand"<?php echo headway_option_value($leaf['options']['orderby'], 'rand') ?>>Random</option>
						<option value="ID"<?php echo headway_option_value($leaf['options']['orderby'], 'ID') ?>>ID</option>
					</select>
					<select class="headway-visual-editor-input" name="leaf-options[<?php echo $leaf['id'] ?>][order]" id="<?php echo $leaf['id'] ?>_order">
						<option value="desc"<?php echo headway_option_value($leaf['options']['order'], 'desc') ?>>Descending</option>
						<option value="asc"<?php echo headway_option_value($leaf['options']['order'], 'asc') ?>>Ascending</option>
					</select>
				</td>
			</tr>
		
			<tr class="<?php echo $leaf['id'] ?>_posts_options"<?php echo $display['posts-options']; ?>>
				<th scope="row"><label for="<?php echo $leaf['id'] ?>_offset">Offset (Skip Posts)</label></th>
				<td>
					<input type="text" class="headway-visual-editor-input" name="leaf-options[<?php echo $leaf['id'] ?>][offset]" id="<?php echo $leaf['id'] ?>_offset" value="<?php echo $leaf['options']['offset'] ?>" />
				</td>
			</tr>
		
			<tr>
				<th scope="row"><label>Post Meta</label></th>
				<td>
					<p class="radio-container">
						<input type="checkbox" class="radio headway-visual-editor-input" id="<?php echo $leaf['id'] ?>_hide_post_meta" name="leaf-options[<?php echo $leaf['id'] ?>][hide-post-meta]"<?php echo headway_checkbox_value($leaf['options']['hide-post-meta']) ?> />
						<label for="<?php echo $leaf['id'] ?>_hide_post_meta">Hide Post Meta</label>
					</p>
				</td>	
			</tr>
		
			<tr class="no-border">
				<th scope="row"><label>Excerpts</label></th>
				<td>
					<select class="headway-visual-editor-input" name="leaf-options[<?php echo $leaf['id'] ?>][excerpts]" id="<?php echo $leaf['id'] ?>_excerpts">
						<option value="default"<?php echo headway_option_value($leaf['options']['excerpts'], 'default') ?>>Default</option>
						<option value="disable"<?php echo headway_option_value($leaf['options']['excerpts'], 'disable') ?>>Disable Excerpts</option>
						<option value="excerpts"<?php echo headway_option_value($leaf['options']['excerpts'], 'excerpts') ?>>Force Regular Excerpts</option>
						<option value="small"<?php echo headway_option_value($leaf['options']['excerpts'], 'small') ?>>Force Small Excerpts</option>
					</select>
				</td>	
			</tr>
		</table>
	</div>

	<div id="comments-options-tab-<?php echo $leaf['id'] ?>">
		<table class="tab-options" id="leaf-options-<?php echo $leaf['id'] ?>-comments">
			<tr>
				<td colspan="2">
					<p class="info-box info-box-with-bg">If you're using this leaf on the single post system page or page with comments, you can use these settings to split the content in one content leaf and the comments in another.</p>
				</td>
			</tr>
			
			<tr>	
				<th scope="row"><label for="<?php echo $leaf['id'] ?>_hide_content">Content</label></th>	
				<td>
					<p class="radio-container">
						<input type="checkbox" class="radio headway-visual-editor-input" id="<?php echo $leaf['id'] ?>_hide_content" name="leaf-options[<?php echo $leaf['id'] ?>][hide-content]"<?php echo headway_checkbox_value($leaf['options']['hide-content']) ?>/><label for="<?php echo $leaf['id'] ?>_hide_content">Hide Content</label>
					</p>
				</td>	
			</tr>
			
			<tr class="no-border">	
				<th scope="row"><label for="<?php echo $leaf['id'] ?>_hide_comments">Comments</label></th>	
				<td>
					<p class="radio-container">
						<input type="checkbox" class="radio headway-visual-editor-input" id="<?php echo $leaf['id'] ?>_hide_comments" name="leaf-options[<?php echo $leaf['id'] ?>][hide-comments]"<?php echo headway_checkbox_value($leaf['options']['hide-comments']) ?>/><label for="<?php echo $leaf['id'] ?>_hide_comments">Hide Comments</label>
					</p>
				</td>	
			</tr>
		</table>
	</div>
	
	<div id="miscellaneous-tab-<?php echo $leaf['id'] ?>">
		<table class="tab-options" id="leaf-options-<?php echo $leaf['id'] ?>-miscellaneous">
			<tr>	
				<th scope="row"><label for="<?php echo $leaf['id'] ?>_show_title">Leaf Title</label></th>	
				<td>
					<p class="radio-container">
						<input type="checkbox" class="radio headway-visual-editor-input" id="<?php echo $leaf['id'] ?>_show_title" name="config[<?php echo $leaf['id'] ?>][show-title]"<?php echo headway_checkbox_value($leaf['config']['show-title']) ?>/><label for="<?php echo $leaf['id'] ?>_show_title">Show Title</label>
					</p>
				</td>	
			</tr>

			<tr>					
				<th scope="row"><label for="<?php echo $leaf['id'] ?>_leaf_title_link">Leaf Title Link</label></th>
				<td><input type="text" class="headway-visual-editor-input" name="config[<?php echo $leaf['id'] ?>][leaf-title-link]" id="<?php echo $leaf['id'] ?>_leaf_title_link" value="<?php echo $leaf['config']['title-link'] ?>" /></td>	
			</tr>

			<tr class="no-border">					
				<th scope="row"><label for="<?php echo $leaf['id'] ?>_custom_css_classes">Custom CSS Class(es)</label></th>
				<td><input type="text" class="headway-visual-editor-input" name="config[<?php echo $leaf['id'] ?>][custom-css-classes]" id="<?php echo $leaf['id'] ?>_custom_css_classes" value="<?php echo $leaf['config']['custom-classes'] ?>" /></td>	
			</tr>
		</table>
	</div>
<?php
}

function content_leaf_content($leaf){
	$content_leaf = new content_leaf($leaf);
	
	$content_leaf->display();
}

$options = array(
		'id' => 'content',
		'name' => 'Content',
		'default_leaf' => true,
		'options_callback' => 'content_leaf_inner',
		'content_callback' => 'content_leaf_content',
		'icon' => false,
		'live_saving' => false
	);

$content_leaf = new HeadwayLeaf($options);