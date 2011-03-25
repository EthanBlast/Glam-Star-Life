<?php

/*

Plugin Name: Author Information Widget
Description: This Widget shows the "about me" text, gravatar and social network/contact links of an author of your blog. You can add this widget to sidebars on author relevant sections, i.e. pages, posts or author archives.
Plugin URI: http://dennishoppe.de/wordpress-plugins/author-info-widget
Version: 1.2.10
Author: Dennis Hoppe
Author URI: http://DennisHoppe.de

*/


// Please think about a donation
If (Is_File(DirName(__FILE__).'/donate.php')) Include DirName(__FILE__).'/donate.php';


If (!Class_Exists('wp_widget_author_info')){
Class wp_widget_author_info Extends WP_Widget {
  var $base_url;
  var $arr_option;
  
  Function __construct(){
    // Get ready to translate
    $this->Load_TextDomain();
    
    // Setup the Widget data
    parent::__construct (
      False,
      $this->t('Author Info'),
      Array('description' => $this->t('You can add this widget to sidebars on author relevant sections i.e. pages, posts or author archives.'))
    );
    
    // Read base_url
    $this->base_url = get_bloginfo('wpurl').'/'.Str_Replace("\\", '/', SubStr(RealPath(DirName(__FILE__)), Strlen(ABSPATH)));

    // Hooks
    Add_Action ('wp_head', Array($this, 'print_header'));      
  }
  
  Function Load_TextDomain(){
    $locale = Apply_Filters( 'plugin_locale', get_locale(), __CLASS__ );
    load_textdomain (__CLASS__, DirName(__FILE__).'/language/' . $locale . '.mo');
  }
  
  Function t ($text, $context = ''){
    // Translates the string $text with context $context
    If ($context == '')
      return __($text, __CLASS__);
    Else
      return _x($text, $context, __CLASS__);
  }
  
  Function default_settings(){
    // Default settings
    return Array(
      'show_author' => 'current',
      'gravatar_size' => 80,
      'caption_website' => $this->t('Visit website'),
      'caption_email' => $this->t('Write an E-Mail'),
      'caption_jabber' => $this->t('Contact via Jabber'),
      'caption_aim' => $this->t('Contact via AIM'),
      'caption_yim' => $this->t('Contact via YIM')
    );
  }
  
  Function load_options($options){
    $options = (ARRAY) $options;
    
    // Delete empty values
    ForEach ($options AS $key => $value)
      If (!$value) Unset($options[$key]);
    
    // Check Gravatar size
    If (IsSet($options['gravatar_size'])){
      $options['gravatar_size'] = IntVal ($options['gravatar_size']);
      If ($options['gravatar_size'] == 0) Unset ($options['gravatar_size']);
    }
    
    // Load options
    $this->arr_option = Array_Merge ($this->default_settings(), $options);
  }
  
  Function get_option($key, $default = False){
    If (IsSet($this->arr_option[$key]) && $this->arr_option[$key])
      return $this->arr_option[$key];
    Else
      return $default;
  }
  
  Function set_option($key, $value){
    $this->arr_option[$key] = $value;
  }
  
  Function print_header(){
    If (Is_File(get_stylesheet_directory() . '/author-info-widget.css'))
      $style_sheet = get_stylesheet_directory_uri() . '/author-info-widget.css';
    ElseIf (Is_File(DirName(__FILE__) . '/author-info-widget.css'))
      $style_sheet = $this->base_url . '/author-info-widget.css';
    
    // run the filter for the template file
    $style_sheet = Apply_Filters('author_info_widget_style_sheet', $style_sheet);
    
    If ($style_sheet) :?>
    <link rel="stylesheet" href="<?php Echo HTMLSpecialChars($style_sheet) ?>" type="text/css" />
    <?php EndIf;
    
  }
 
  Function widget ($args, $settings){
    // Load options
    $this->load_options ($settings); Unset ($settings);
    
    // Check if the user has to be logged in
    If ($this->get_option('only_logged_in') && !is_user_logged_in()) return False;
    
    // Check if the widget should be visible on pages
    If ($this->get_option('hide_on_pages') && is_page()) return False;

    // Authors of the current post
    $arr_author = Array();
    If ($this->get_option('show_author') == 'current'){
      If (is_author()){
        Global $wp_query;
        $arr_author[] = $wp_query->get_queried_object();
      }
      ElseIf (is_singular()){
        Global $post;
        If (!$post) $post = get_post(get_the_id());
        If (Function_Exists ('get_coauthors') ){
          $arr_author = (Array) get_coauthors();
        }
        Else
          $arr_author[] = get_userdata($post->post_author);
      }
      Else
        return False;
    }
    Else {
      // Add the selected authors
      ForEach ( (Array) $this->get_option('selected_authors') AS $author_id ){
        $arr_author[] = get_userdata( $author_id );
      }
      Unset ($author_id);
    }
    
    // Save the authors as objects
    If (Empty($arr_author))
      return False;
    Else
      $this->set_option('author', $arr_author);
    Unset ($arr_author);
    
    // Default widget title
    If (!$this->get_option('title')){
      If (Count($this->get_option('author')) == 1)
        // There is only one author to show
        $this->set_option('title', $this->t('About the author'));
      Else
        // there are more authors to show
        $this->set_option('title', $this->t('About the authors'));
    }
    $this->set_option('widget_title', $args['before_title'] . $this->get_option('title') . $args['after_title']  );

    // Look for the template file
    $template_name = 'author-info-widget.php';
    $template_file = Get_Query_Template(BaseName($template_name, '.php'));
    If (!Is_File($template_file) && Is_File(DirName(__FILE__) . '/' . $template_name))
      $template_file = DirName(__FILE__) . '/' . $template_name;
    
    // run the filter for the template file
    $template_file = Apply_Filters('author_info_widget_template', $template_file);
    
    // Print the widet
    If ($template_file && Is_File ($template_file)){
      Echo $args['before_widget'];
      Include $template_file;
      Echo $args['after_widget'];
    }
  }
  
  Function posts_link_caption($author_name){
    If (SubStr($author_name, -1, 1) == 's' || SubStr($author_name, -1, 1) == 'x')
      $caption = SPrintF($this->t('Read all of %s Posts'), $author_name);
    Else
      $caption = SPrintF($this->t('Read all of %s\'s Posts'), $author_name);
    
    return $caption;  
  }  
 
  Function form ($settings){
    // Load options
    $this->load_options ($settings); Unset ($settings);
    
    // Show form
    Include DirName(__FILE__).'/form.php';
  }
 
  Function update ($new_settings, $old_settings){
    return $new_settings;
  }
  
  Function get_authors(){    
    $arr_author = Array();
    
    ForEach ( (Array) get_author_user_ids() AS $author_id)
      $arr_author[] = get_userdata( $author_id );
    
    If (Empty($arr_author))
      return False;
    Else
      return $arr_author;
  }

} /* End of Class */
Add_Action ('widgets_init', Create_Function ('','Register_Widget(\'wp_widget_author_info\');') );
} /* End of If-Class-Exists-Condition */
/* End of File */
