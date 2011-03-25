<?php 
/* 
Plugin Name: Yet Another Featured Posts Plugin
Plugin URI: http://jonraasch.com/blog/yet-another-featured-posts-plugin
Description: A plugin to manage and display a list of featured posts
Version: 1.4
Author: Jon Raasch
Author URI: http://jonraasch.com/
Documentation: http://dev.jonraasch.com/yafpp/docs
*/

/*  Copyright 2009-2010 Jon Raasch - Released under the FreeBSD License - See http://dev.jonraasch.com/yafpp/docs#licensing for more info
*/


// return either an array or HTML formatted list of featured posts
function get_featured_posts($opts = array(
    'before' => '<li>', 
    'after'  => '</li>',
    'method' => 'echo',
)) {
    global $post;
    $yafpp_opts = get_option('yafpp_opts');
    
    // posts_id from database
    $featured = $yafpp_opts['featured_posts'];
    
    if ( isset( $opts['excerpt_length'] ) ) $yafpp_opts['excerpt_length'] = $opts['excerpt_length'];
    // if max_posts is 0 set to -1 (0 is deprecated)
    if ( isset( $opts['max_posts'] ) ) $yafpp_opts['max_posts'] = $opts['max_posts'] ? $opts['max_posts'] : -1;
    if ( isset( $opts['no_posts_text'] ) ) $yafpp_opts['no_posts_text'] = $opts['no_posts_text'];
    
    $out = $opts['method'] == 'arr' ? array() : '';
    
    if($featured) {
     
        $featured_arr = explode(',',$featured);
        
        // if method is the_loop, set it up and die
        if ( $opts['method'] == 'the_loop' ) {
            $query_opts = apply_filters('yafpp_get_featured_posts_query', array(
                'post__in' => $featured_arr,
                'posts_per_page' => $yafpp_opts['max_posts'],
            ));
            if ( isset( $opts['post_type'] ) ) $query_opts['post_type'] = $opts['post_type'];
            query_posts($query_opts);
            
            return $featured_arr;
        }
        
        function new_excerpt_length($length) {
            $yafpp_opts = get_option('yafpp_opts');
            return $yafpp_opts['excerpt_length'];
        }
        
        $query_opts = apply_filters('yafpp_get_featured_posts_query',array(
            'post__in'          => $featured_arr,
            'posts_per_page'    => $yafpp_opts['max_posts'],
        ));
        if ( isset( $opts['post_type'] ) ) $query_opts['post_type'] = $opts['post_type'];
        $featured_query = new WP_Query($query_opts);
        
        while ($featured_query->have_posts()) {
            $featured_query->the_post();
            
            add_filter('excerpt_length', 'new_excerpt_length');
            
            // define some vars
            $the_title = get_the_title();
            $the_permalink = get_permalink();
            $the_excerpt = get_the_excerpt();
            
            $the_image = (function_exists('yapb_is_photoblog_post') && yapb_is_photoblog_post()) ? yapb_get_thumbnail(
                '', // HTML before image tag
                array(
                    'alt' => $the_title, // image tag alt attribute
                ),
                '',               // HTML after image tag
                array('w=' . $yafpp_opts['photo_width'], 'h=' . $yafpp_opts['photo_height'], 'q=60'), // phpThumb configuration parameters
                'thumbnail'             // image tag custom css class
            ) : 0;
            
            if ($opts['method'] == 'arr') {
                $the_post = array(
                    'id'      => get_the_ID(),
                    'title'   => $the_title,
                    'excerpt' => $the_excerpt,
                    'url' => $the_permalink,
                    'image' => $the_image,
                    'author' => get_the_author(),
                );
                array_push( $out, $the_post);
            }
            else {
                $out .= $opts['before'];
                
                $out .= $yafpp_opts['display_title'] ? '<a href="' . $the_permalink . '" rel="bookmark" title="Permanent Link: ' . $the_title . '">' . $the_title . '</a><br />' : '';
                
                $out .= ($yafpp_opts['display_image'] && $the_image) ? '<a href="' . $the_permalink . '" rel="bookmark" title="Permanent Link: ' . $the_title . '" class="yafpp-img">' . $the_image . '</a><br />' : '';
                $out .= $yafpp_opts['display_excerpt'] ? $the_excerpt : '';
                
                $out .= $opts['after'];
            }
        } # end while WP Loop
        
    }  
    else if ( $opts['method'] != 'arr' ) $out .= $opts['before'] . $yafpp_opts['no_posts_text'] . $opts['after'];
    
    if ($opts['method'] == 'echo') echo $out;
    else return $out;
}

// load the YAFPP Settings stuff
function yafpp_menu_item() {
    $yafpp_opts = get_option('yafpp_opts');
    
    // Add new menu in Setting or Options tab:
    add_options_page('YAFPP - Yet Another Featured Posts Plugin', 'Featured Posts (YAFPP)', $yafpp_opts['admin_level'], 'YAFPP', 'yafpp_admin');
}

// YAFPP admin page
function yafpp_admin() {
    global $ol_flash, $current_user;
    get_currentuserinfo();
    
    $yafpp_opts = get_option('yafpp_opts');

    if ($_POST['change_featured']) {
        $featuredStr = '';
        
        // parse featured array into string
        if ( $_POST['featured_posts'] ) {
            foreach ( $_POST['featured_posts'] as $post_id=>$val ) $featuredStr .= $post_id . ',';
            $featuredStr = substr($featuredStr, 0, -1);
        }
        
        if ( $yafpp_opts['featured_posts'] != $featuredStr ) {
            $yafpp_opts['featured_posts'] = $featuredStr;
            
            update_option('yafpp_opts', $yafpp_opts);
            
            $ol_flash = "Your featured posts have been saved.";
        }
        else $ol_flash = "Please use the checkboxes to remove any currently featured posts.  To feature new posts <a href=\"edit.php\">go here</a>";
    }
    
    // process posted options
    else if (isset($_POST['excerpt_length']) && $current_user->allcaps['level_10']) {
        // set other options
        $yafpp_opts['display_title'] = $_POST['display_title'] ? 1 : 0;
        $yafpp_opts['display_excerpt'] = $_POST['display_excerpt'] ? 1 : 0;
        $yafpp_opts['feature_pages'] = $_POST['feature_pages'] ? 1 : 0;
        $yafpp_opts['display_image'] = $_POST['display_image'] ? 1 : 0;
        
        $yafpp_opts['photo_height'] = (int) $_POST['photo_height'];
        $yafpp_opts['photo_width'] = (int) $_POST['photo_width'];
        
        $yafpp_opts['excerpt_length'] = (int) $_POST['excerpt_length'];
        $yafpp_opts['max_posts'] = (int) $_POST['max_posts'];
        $yafpp_opts['no_posts_text']  = $_POST['no_posts_text'];
        
        if ( $_POST['admin_level']) $yafpp_opts['admin_level'] = (int) $_POST['admin_level'];
        
        update_option('yafpp_opts',$yafpp_opts);
        
        $ol_flash = "Your YAFPP settings have been saved.";
    }
    
    
    // build the page output
    $out = '';
    
    if ($ol_flash) $out .= '<div id="message"class="updated fade"><p>' . $ol_flash . '</p></div>';
    
    $out .= '<div class="wrap">';
    $out .= '<h2>Yet Another Featured Posts Plugin (YAFPP) Settings</h2>';
    
    $out .= '<p><a href="http://jonraasch.com/blog/yet-another-featured-posts-plugin" target="_blank">Plugin Homepage</a> | <a href="http://dev.jonraasch.com/yafpp/docs" target="_blank">Documentation</a> | <a href="http://dev.jonraasch.com/yafpp/changelog" target="_blank">Changelog</a> | <a href="javascript:document.getElementById(\'donate_form\').submit()">Donate</a></p>';
    
    $out .= '<form action="" method="post"><input type="hidden" name="change_featured" value="1" />';
    
    // featured posts form
    $out .= '<h3>Currently Featured Posts</h3>';
    $out .= '<p><em>Manage featured posts - uncheck to unfeature</em></p>';
    
    $out .= '<table class="widefat">
    <thead><tr><th>Post Title</th><th>Author</th><th>Date</th><th>Featured</th></tr></thead><tbody>';
    
    $featured_arr = explode(',', $yafpp_opts['featured_posts']);
    
    // loop through the posts,
    query_posts(apply_filters('yafpp_get_featured_posts_query_admin',array(
        'post__in' => $featured_arr,
        'posts_per_page' => $yafpp_opts['max_posts']
    )));
    
    $count = 0;
    if ( have_posts() ) : while ( have_posts() ) : the_post();
        $postID = get_the_ID();
        
        if ( in_array($postID, $featured_arr) ) {
            $count++;
            
            $out .= '<tr' . ($count % 2 ? ' class="alternate"' : '') . '><td><strong>' . get_the_title() . '</strong></td><td>By ' . get_the_author() . '</td><td>' . get_the_time('l, F j. Y') . '</td><td><input type="checkbox" name="featured_posts[' . $postID . ']" value="1" checked /></td></tr>';        
        }
    endwhile; 
    else : $out .= '<tr><td colspan="4">No posts have been featured - Please add featured posts using the <a href="edit.php">Edit Posts</a> page</td></tr>';
    endif;
    
    $out .= '</tbody><tfoot><tr><th>Post Title</th><th>Author</th><th>Date</th><th>Featured</th></tr></tfoot></table>';
    
    if ($count) $out .= '<div class="submit"><input type="submit" value="Update Featured Posts" /></div></form>';
    else $out .= '<br />';
    
    // only admin may edit YAFPP settings
    if ($current_user->allcaps['level_10']) {
        $out .= '<hr /><form action="" method="post">';
        
        // display settings
        $out .= '<h3>Display Settings</h3>';
        
        $out .= '<p><em>Select which parts of the listing you want to display</em></p>';
        
        $out .= '<table class="optiontable form-table">';
        
        $out .= '<tr><td><input type="checkbox" name="display_title" ' . ($yafpp_opts['display_title'] ? 'checked ' : '') . '/> &nbsp; <strong>Post Title</strong></td></tr>';
        
        $out .= '<tr><td><input type="checkbox" name="display_excerpt" ' . ($yafpp_opts['display_excerpt'] ? 'checked ' : '') . '/> &nbsp; <strong>Post Excerpt</strong></td></tr>';
        
        // photo options
        if ( function_exists('yapb_is_photoblog_post') ) {
            $out .= '<tr><td><input type="checkbox" name="display_image" ' . ($yafpp_opts['display_image'] ? 'checked ' : '') . '/> &nbsp; <strong>Post Image (from YAPB)</strong></td></tr>';
            
            $out .= '<tr><td><h4>Max Photo Dimensions</h4><strong>Width:</strong> <input type="text" name="photo_width" value="' . $yafpp_opts['photo_width'] . '" /> <strong>Height:</strong> <input type="text" name="photo_height" value="' . $yafpp_opts['photo_height'] . '" /></td></tr>';
        }
        $out .= '</table>';
        
        // other settings fields
        $out .= '<h3>Other Settings</h3>
        <table class="optiontable form-table">';
        
        $out .= '<tr><td><input type="checkbox" name="feature_pages" ' . ($yafpp_opts['feature_pages'] ? 'checked ' : '') . '/> &nbsp; <strong>Allow Pages to be featured</strong></td></tr>';

        $out .= '<tr><td><strong>Excerpt Length (# of words):</strong></td><td><input type="text" name="excerpt_length" value="' . (int) $yafpp_opts['excerpt_length'] . '" size="50%" /></td></tr>';
        
        $out .= '<tr><td><strong>Max # of featured posts (<code>-1</code> for unlimited):</strong></td><td><input type="text" name="max_posts" value="' . (int) $yafpp_opts['max_posts'] . '" size="50%" /></td></tr>';
        
        $out .= '<tr><td><strong>No Posts Text:</strong></td><td><input type="text" name="no_posts_text" value="' . htmlentities($yafpp_opts['no_posts_text']) . '" size="50%" /></td></tr>';
        
        $out .= '</table>';
    
        // permission settings
        $out .= '<h3>Permission Settings</h3>
        <table class="optiontable form-table">
        <tr><td><strong>Minimum User Level to control Featured Posts</strong></td><td><select name="admin_level">
            <option value="10"' . ($yafpp_opts['admin_level'] == 10 ? ' selected' : '' ) . '>Admin only (10)</option>
            <option value="7"' . ($yafpp_opts['admin_level'] == 7 ? ' selected' : '' ) . '>Editor and Admin (7+)</option>
            <option value="2"' . ($yafpp_opts['admin_level'] == 2 ? ' selected' : '' ) . '>Author, Editor and Admin (2+)</option>
            <option value="1"' . ($yafpp_opts['admin_level'] == 1 ? ' selected' : '' ) . '>Contributor, Author, Editor and Admin (1+)</option>
            <option value="0"' . ($yafpp_opts['admin_level'] == 0 ? ' selected' : '' ) . '>All registered users (not recommended) (0+)</option>
        </select></td></tr></table>
        ';
        
        $out .= '<div class="submit"><input type="submit" value="Save Settings" /></div></form>';
    }
    
    // donations
    
    $out .= '<hr />
    <h2>Do you like this plugin?</h2>
    <p>Please consider making a donation.  I\'m a free-lance web developer, and your donations make these plugins possible.</p>
    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" id="donate_form">
        <input type="hidden" name="cmd" value="_s-xclick">
        <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
        ">
        <input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
        <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
    </form>
    <br />';

    $out .= '</div>';

    echo $out;
}

// add featured column to edit posts listing
function yafpp_add_posts_column($defaults) {
    global $current_user;
    get_currentuserinfo();
    
    $yafpp_opts = get_option('yafpp_opts');
    
    if ($current_user->allcaps['level_' . $yafpp_opts['admin_level']]) {
        $defaults['Featured'] = __('Featured');
    }
    
    return $defaults;
}

// build the post listing cta for each row
function yafpp_posts_column($column_name, $id) {
    if ( $column_name == 'Featured' ) {
        $root = get_option('home');
        $yafpp_dir = $root . '/wp-content/plugins/yet-another-featured-posts-plugin';
        
        $yafpp_opts = get_option('yafpp_opts');
        
        $featured_arr = explode(',', $yafpp_opts['featured_posts']);
        
        if (in_array( $id, $featured_arr )) {
            $class = 'class="yafpp_on"';
        }
        else $class = 'class="yafpp_off"';
        
        echo '<div id="yafpp_' .$id . '" onclick="yafpp_ajax_feature(' . $id . ')" ' . $class . '></div><img src="' . $yafpp_dir . '/img/loading.gif" alt="" class="yafpp_loading" />';
    }
}

// add css for post listings UI
function yafpp_css() {
    $script_path = substr( __FILE__, 0, -10);

    $yafpp_dir = get_option('siteurl') . '/wp-content/plugins' . substr( $script_path, strrpos( $script_path, '/')) . '/';
    
    $out = <<<EOT
<!-- YAFPP ( Yet Another Featured Post Plugin ) -->

<style type="text/css">
.yafpp_on, .yafpp_off {
    background: url('$yafpp_dir/img/stars.png') no-repeat 0 0;
    width: 16px;
    height: 16px;
    cursor: pointer;
}

.yafpp_off {
    background-position: -16px 0;
}

td.loading .yafpp_on, td.loading .yafpp_off {
    display: none;
}

.yafpp_loading {
    display: none;
}

td.loading .yafpp_loading {
    display: inline;
}
</style>

<!-- end YAFPP -->
EOT;

    echo $out;
}

// append JS for processing edit posts page
function yafpp_ajax_feature_js() {
    // use JavaScript SACK library for Ajax
    wp_print_scripts( array( 'sack' ));
    ?>
    <script type="text/javascript">
    //<![CDATA[
    function yafpp_ajax_feature(postId) {
        document.getElementById('yafpp_' + postId).parentNode.className = 'loading';
        
        var mysack = new sack("<?php bloginfo( 'wpurl' ); ?>/wp-admin/admin-ajax.php");
        var theBox = document.getElementById('yafpp_' + postId);
        
        mysack.execute = 1;
        mysack.method = 'POST';
        mysack.setVar("action", "yafpp_process");
        mysack.setVar("id", postId);
        mysack.setVar("is_on", (theBox.className == 'yafpp_on' ? 1 : 0));
        mysack.encVar("cookie", document.cookie, false);
        mysack.onError = function() { alert('Error featuring, please try again.' )};
        mysack.runAJAX();
    
    return true;
    }
    //]]>
    </script>
<?php
}

// backend ajax processing for edit posts page
function yafpp_process_feature() {
    global $current_user;
    get_currentuserinfo();
    
    $yafpp_opts = get_option('yafpp_opts');
    
    // boot if they don't have edit rights
    if (! $current_user->allcaps['level_' . $yafpp_opts['admin_level']]) die( "alert('Sorry, you do not have the correct permissions');");
    
    // read submitted information
    $id = $_POST['id'];
    $is_on = $_POST['is_on'];
    
    $featured_arr = $yafpp_opts['featured_posts'] ? explode(',', $yafpp_opts['featured_posts']) : array();
    
    // add to array if not on and not currently in the array
    if (! $is_on && ! in_array($id, $featured_arr)) array_push($featured_arr, $id);
    
    rsort($featured_arr);
    
    $featured_str = '';
    foreach ( $featured_arr as $post_id ) {
        // if not the same as selected, add to the featured str
        if (! ($is_on && $post_id == $id)) $featured_str .= $post_id . ',';
    }
    
    if ($featured_str) $featured_str = substr($featured_str, 0, -1);
    
    $yafpp_opts['featured_posts'] = $featured_str;
    update_option('yafpp_opts', $yafpp_opts);
    
    // Compose JavaScript for return
    die( "var thisStar = document.getElementById('yafpp_$id'); thisStar.className = '" . ($is_on ? 'yafpp_off' : 'yafpp_on' ) . "'; thisStar.parentNode.className = ''");
}

/***** init *****/

$yafpp_opts = get_option('yafpp_opts');

// set defaults if first time
if (is_null($yafpp_opts['no_posts_text'])) {
    $yafpp_opts['display_title'] = 1;
    $yafpp_opts['display_excerpt'] = 1;
    $yafpp_opts['feature_pages'] = 0;
    $yafpp_opts['photo_width'] = 300;
    $yafpp_opts['photo_height'] = 240;
    $yafpp_opts['excerpt_length'] = 20;
    $yafpp_opts['max_posts'] = 0;
    $yafpp_opts['no_posts_text'] = 'No featured posts';
    $yafpp_opts['admin_level'] = 7;
    
    update_option('yafpp_opts',$yafpp_opts);
}

// add admin menu item
add_action('admin_menu', 'yafpp_menu_item');

// add column to post listings
add_filter('manage_posts_columns', 'yafpp_add_posts_column');
add_filter('manage_posts_custom_column', 'yafpp_posts_column', 10, 2);

// add ajax processing stuff
add_action('wp_ajax_yafpp_process', 'yafpp_process_feature' );
add_action('admin_print_scripts', 'yafpp_ajax_feature_js' );
add_action('admin_head', 'yafpp_css');

// add Page support
if($yafpp_opts['feature_pages']) {
    add_filter('manage_pages_columns', 'yafpp_add_posts_column');
    add_filter('manage_pages_custom_column', 'yafpp_posts_column', 10, 2);
    add_filter('yafpp_get_featured_posts_query','yafpp_add_pages_to_query_opts');
    add_filter('yafpp_get_featured_posts_query_admin','yafpp_add_pages_to_query_opts');
}
function yafpp_add_pages_to_query_opts($query_opts) {
    $query_opts['post_type'] = array('page','post');
    return $query_opts;
}

?>