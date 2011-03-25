<?php
/*
 * WP-FB-AutoConnect Premium Add-On
 * http://www.justin-klein.com/projects/wp-fb-autoconnect
 * 
 * When placed in the same directory as the WP-FB-AutoConnect Wordpress plugin,
 * this addon file will enable all premium functionality shown in the core plugin's admin panel.
 * This file does not operate as a standalone plugin; it must be used in conjunction with WP-FB-AutoConnect.
 * 
 * You are permitted to modify the code below for personal use.
 * You are not permitted to share, sell, or in any way distribute any of the code below.
 * You are not permitted to share, sell, or in any way distribute any work derived from the code below, including new plugins that may include similar functionality.
 * You are not permitted to instruct others on how to reproduce the behavior implemented below.
 * Basically, you can use this plugin however you like *on your own site* - just don't share it with anyone else :)
 * 
 * Additional features under consideration:
 * -Use OpenGraph
 * -Specify multiple addresses to forward login log reports
 * -Customizable default email address for users who deny access to Facebook addresses
 * -Autofill BuddyPress X-Profile fields with data pulled from Facebook
 * -Collapse "allow access to information" and "allow access to email" into one prompt
 * 
 * Changelog:
 * v1: 
 * -Initial Release
 *
 * v2: 
 * -Better integration with core
 * -Premium updates no longer require updates to the core
 * -Requires core plugin 1.5.1 or later
 * 
 * v3: 
 * -Add this changelog :) 
 * -Add support for choosing button size
 * -Add support for choosing button text
 * -Add support for silently handling double-logins
 * -Add ability to ENFORCE that real emails are revealed (reject proxied emails)
 * 
 * v4:
 * -Fixed auth
 * 
 */


/**********************************************************************/
/*************************PREMIUM OPTIONS******************************/
/**********************************************************************/
//Identify the premium version as being present & available
define('JFB_PREMIUM', 57);
define('JFB_PREMIUM_VALUE', 'YTozOntzOjU6Im9yZGVyIjtzOjI6IjU3IjtzOjQ6ImRhdGUiO3M6MTk6IjIwMTAtMTEtMjAgMTg6MTE6MDUiO3M6MjoiSVAiO3M6MTM6Ijc2LjIxOC4yMDcuNjIiO30=');
define('JFB_PREMIUM_VER', 4);

//Override plugin name
global $jfb_name, $jfb_version;
$jfb_name = "WP-FB AutoConnect Premium";
$jfb_version .= "+p" . JFB_PREMIUM . 'v' . JFB_PREMIUM_VER;

//Define new premium options
global $opt_jfbp_notifyusers, $opt_jfbp_notifyusers_content, $opt_jfbp_notifyusers_subject;
global $opt_jfbp_commentfrmlogin, $opt_jfbp_wploginfrmlogin, $opt_jfbp_cache_avatars;
global $opt_jfbp_buttonsize, $opt_jfbp_buttontext, $opt_jfbp_ignoredouble, $opt_jfbp_requirerealmail;
$opt_jfbp_notifyusers = "jfb_p_notifyusers";
$opt_jfbp_notifyusers_subject = "jfb_p_notifyusers_subject";
$opt_jfbp_notifyusers_content = "jfb_p_notifyusers_content";
$opt_jfbp_commentfrmlogin = "jfb_p_commentformlogin";
$opt_jfbp_wploginfrmlogin = "jfb_p_wploginformlogin";
$opt_jfbp_cache_avatars = "jfb_p_cacheavatars";
$opt_jfbp_buttonsize = "jfb_p_buttonsize";
$opt_jfbp_buttontext = "jfb_p_buttontext";
$opt_jfbp_ignoredouble = "jfb_p_ignoredouble";
$opt_jfbp_requirerealmail = "jfb_p_requirerealmail";

//Called when we save our options in the admin panel
function jfb_update_premium_opts()
{
    global $_POST, $jfb_name, $jfb_version, $opt_jfb_req_perms;
    global $opt_jfbp_notifyusers, $opt_jfbp_notifyusers_content, $opt_jfbp_notifyusers_subject;
    global $opt_jfbp_commentfrmlogin, $opt_jfbp_wploginfrmlogin, $opt_jfbp_cache_avatars;
    global $opt_jfbp_buttonsize, $opt_jfbp_buttontext, $opt_jfbp_ignoredouble, $opt_jfbp_requirerealmail;
    update_option( $opt_jfbp_notifyusers, $_POST[$opt_jfbp_notifyusers] );
    update_option( $opt_jfbp_notifyusers_subject, stripslashes($_POST[$opt_jfbp_notifyusers_subject]) );
    update_option( $opt_jfbp_notifyusers_content, stripslashes($_POST[$opt_jfbp_notifyusers_content]) );
    update_option( $opt_jfbp_commentfrmlogin, $_POST[$opt_jfbp_commentfrmlogin] );
    update_option( $opt_jfbp_wploginfrmlogin, $_POST[$opt_jfbp_wploginfrmlogin] );
    update_option( $opt_jfbp_cache_avatars, $_POST[$opt_jfbp_cache_avatars] );
    update_option( $opt_jfbp_buttonsize, $_POST[$opt_jfbp_buttonsize] );
    update_option( $opt_jfbp_buttontext, $_POST[$opt_jfbp_buttontext] );
    update_option( $opt_jfbp_ignoredouble, $_POST[$opt_jfbp_ignoredouble] );
    
    //If "require real email" is set, auto-check the basic email-prompting option too
    update_option( $opt_jfbp_requirerealmail, $_POST[$opt_jfbp_requirerealmail] );
    if( $_POST[$opt_jfbp_requirerealmail] ) update_option( $opt_jfb_req_perms, 1 );
    jfb_auth($jfb_name, $jfb_version, 5, JFB_PREMIUM_VALUE);
    ?><div class="updated"><p><strong>Premium Options saved.</strong></p></div><?php    
}

//Called to delete our options from the admin panel
function jfb_delete_premium_opts()
{
    global $opt_jfbp_notifyusers, $opt_jfbp_notifyusers_content, $opt_jfbp_notifyusers_subject;
    global $opt_jfbp_commentfrmlogin, $opt_jfbp_wploginfrmlogin, $opt_jfbp_cache_avatars;
    global $opt_jfbp_buttonsize, $opt_jfbp_buttontext, $opt_jfbp_ignoredouble, $opt_jfbp_requirerealmail;
    delete_option($opt_jfbp_notifyusers);
    delete_option($opt_jfbp_notifyusers_subject);
    delete_option($opt_jfbp_notifyusers_content);
    delete_option($opt_jfbp_commentfrmlogin);
    delete_option($opt_jfbp_wploginfrmlogin);
    delete_option($opt_jfbp_cache_avatars);
    delete_option($opt_jfbp_buttonsize);
    delete_option($opt_jfbp_buttontext);
    delete_option($opt_jfbp_ignoredouble);
    delete_option($opt_jfbp_requirerealmail);
}


/**********************************************************************/
/**************************ADMIN PANEL*********************************/
/**********************************************************************/
function jfb_output_premium_panel()
{
    global $opt_jfbp_notifyusers, $opt_jfbp_notifyusers_subject, $opt_jfbp_notifyusers_content, $opt_jfbp_commentfrmlogin, $opt_jfbp_wploginfrmlogin, $opt_jfbp_cache_avatars;
    global $opt_jfbp_buttonsize, $opt_jfbp_buttontext, $opt_jfbp_ignoredouble, $opt_jfbp_requirerealmail;
    function disableatt() { echo (true?"":"disabled='disabled'"); }
    ?>
    <h3>Premium Options</h3>
    <form name="formPremOptions" method="post" action="">
        
        <b>Button Size:</b><br />
        <?php add_option($opt_jfbp_buttonsize, "2"); ?>
        <input <?php disableatt() ?> type="radio" name="<?php echo $opt_jfbp_buttonsize; ?>" value="1" <?php echo (get_option($opt_jfbp_buttonsize)==1?"checked='checked'":"")?> >Icon Only<br />
        <input <?php disableatt() ?> type="radio" name="<?php echo $opt_jfbp_buttonsize; ?>" value="2" <?php echo (get_option($opt_jfbp_buttonsize)==2?"checked='checked'":"")?>>Small Text<br />
        <input <?php disableatt() ?> type="radio" name="<?php echo $opt_jfbp_buttonsize; ?>" value="3" <?php echo (get_option($opt_jfbp_buttonsize)==3?"checked='checked'":"")?>>Medium Text<br />
        <input <?php disableatt() ?> type="radio" name="<?php echo $opt_jfbp_buttonsize; ?>" value="4" <?php echo (get_option($opt_jfbp_buttonsize)==4?"checked='checked'":"")?>>Large Text<br />
        <input <?php disableatt() ?> type="radio" name="<?php echo $opt_jfbp_buttonsize; ?>" value="5" <?php echo (get_option($opt_jfbp_buttonsize)==5?"checked='checked'":"")?>>X-Large Text<br /><br />
        
        <b>Button Text:</b><br />
        <?php add_option($opt_jfbp_buttontext, "Login with Facebook"); ?>
        <input <?php disableatt() ?> type="text" size="100" name="<?php echo $opt_jfbp_buttontext; ?>" value="<?php echo get_option($opt_jfbp_buttontext); ?>" /><br /><br />
        
        <b>Additional Buttons:</b><br />
        <input <?php disableatt() ?> type="checkbox" name="<?php echo $opt_jfbp_commentfrmlogin?>" value="1" <?php echo get_option($opt_jfbp_commentfrmlogin)?'checked="checked"':''?> /> Add a Facebook Login button below the comment form<br />
        <input <?php disableatt() ?> type="checkbox" name="<?php echo $opt_jfbp_wploginfrmlogin?>" value="1" <?php echo get_option($opt_jfbp_wploginfrmlogin)?'checked="checked"':''?> /> Add a Facebook Login button to wp-login.php<br /><br />
    
        <b>Avatars:</b><br />
        <input <?php disableatt() ?> type="checkbox" name="<?php echo $opt_jfbp_cache_avatars?>" value="1" <?php echo get_option($opt_jfbp_cache_avatars)?'checked="checked"':''?> /> Cache Facebook avatars locally<br />
        <small>(This will make a local copy of Facebook avatars, so they'll always load reliably, even if Facebook's servers go offline or if a user deletes their photo from Facebook. They will be fetched and updated whenever a user logs in.)</small><br /><br />
                
        <b>E-Mail:</b><br />
        <input <?php disableatt() ?> type="checkbox" name="<?php echo $opt_jfbp_requirerealmail?>" value="1" <?php echo get_option($opt_jfbp_requirerealmail)?'checked="checked"':''?> /> Enforce access to user's real email <i>(beta)</i><br />
        <small>The basic option to "Request and require permission" prevents users from logging in unless they click "Allow" when prompted for their email.  However, they can still mask their true address by using a Facebook proxy (click "change" in the permissions dialog, and select "xxx@proxymail.facebook.com").  This option performs a secondary check to absolutely enforce that they allow access to their <i>real</i> e-mail.  Note that the check requires several extra queries to Facebook's servers, so it could result in a slightly longer delay before the login initiates on slower connections.)</small><br /><br />
        
        <?php add_option($opt_jfbp_notifyusers_content, "Thank you for logging into " . get_option('blogname') . " with Facebook.\nIf you would like to login manually, you may do so with the following credentials.\n\nUsername: %username%\nPassword: %password%"); ?>
        <?php add_option($opt_jfbp_notifyusers_subject, "Welcome to " . get_option('blogname')); ?>
        <input <?php disableatt() ?> type="checkbox" name="<?php echo $opt_jfbp_notifyusers?>" value="1" <?php echo get_option($opt_jfbp_notifyusers)?'checked="checked"':''?> /> Send a custom welcome e-mail to users who register via Facebook:<br />
        <input <?php disableatt() ?> type="text" size="100" name="<?php echo $opt_jfbp_notifyusers_subject?>" value="<?php echo get_option($opt_jfbp_notifyusers_subject) ?>" /><br />
        <textarea <?php disableatt() ?> cols="100" rows="5" name="<?php echo $opt_jfbp_notifyusers_content?>"><?php echo get_option($opt_jfbp_notifyusers_content) ?></textarea><br /><br />
        
        <b>Double Logins:</b><br />
        <?php add_option($opt_jfbp_ignoredouble, "1"); ?>
        <input <?php disableatt() ?> type="checkbox" name="<?php echo $opt_jfbp_ignoredouble?>" value="1" <?php echo get_option($opt_jfbp_ignoredouble)?'checked="checked"':''?> /> Silently handle double logins (recommended)<br />
        <small>(If a visitor opens two browser windows, logs into one, then logs into the other, the security nonce check will fail (see <a href="http://codex.wordpress.org/WordPress_Nonces">Wordpress Nonces</a>).  This is because in the second window, the current user no longer matches the user for which the nonce was generated.  The free version of the plugin reports this to the visitor, giving them a link to their desired redirect page.  This option will let your site transparently handle such double-logins: to visitors, it'll look like the page has just been refreshed and they're now logged in.)</small><br />
        
        <input type="hidden" name="prem_opts_updated" value="1" />
        <div class="submit"><input <?php disableatt() ?> type="submit" name="Submit" value="Save" /></div>
    </form>
    <hr />
    <?php    
}



/**********************************************************************/
/***************************IMPLEMENTATION*****************************/
/**********************************************************************/


/**
 * Send a custom notification message to newly connecting users
 */
if( get_option($opt_jfbp_notifyusers)) add_action('wpfb_inserted_user', 'jfb_notify_newuser');
function jfb_notify_newuser( $args )
{
    global $jfb_log, $opt_jfbp_notifyusers_subject, $opt_jfbp_notifyusers_content;
    $jfb_log .= "PREMIUM: Sending new registration notification to " . $userdata['user_email'] . ".\n";
    $userdata = $args['WP_UserData'];
    $mailContent = get_option($opt_jfbp_notifyusers_content);
    $mailContent = str_replace("%username%", $userdata['user_login'], $mailContent);
    $mailContent = str_replace("%password%", $userdata['user_pass'], $mailContent);
    wp_mail($userdata['user_email'], get_option($opt_jfbp_notifyusers_subject), $mailContent);
}


/**
 * Add another Login with Facebook button below the comment form
 */
if(get_option($opt_jfbp_commentfrmlogin)) add_action('comment_form', 'jfb_show_comment_button');
function jfb_show_comment_button()
{
    $userdata = wp_get_current_user();
    if( !$userdata->ID )
    {
        echo '<div id="facebook-btn-wrap">';
        jfb_output_facebook_btn();
        echo "</div>";
    }
}


/**
 * Add another Login with Facebook button to wp-login.php
 * This requires 4 separate filters.
 */
if( get_option($opt_jfbp_wploginfrmlogin) )
{
    add_filter('login_redirect', 'jfb_show_loginform_btn_getredirect');
    add_action('login_form', 'jfb_show_loginform_btn_initbtn');
    add_filter('login_message', 'jfb_show_loginform_btn_outputcallback');
    add_action('login_head', 'jfb_show_loginform_btn_styles' );
}
function jfb_show_loginform_btn_getredirect($arg)
{
    global $jfb_saved_redirect;
    $jfb_saved_redirect = $arg;
    return $arg;
}
function jfb_show_loginform_btn_initbtn()
{
    echo '<div id="facebook-btn-wrap">';
    jfb_output_facebook_btn();
    jfb_output_facebook_init(false);
    echo "</div>";
}
function jfb_show_loginform_btn_outputcallback( $arg )
{
    //Unfortunately, the login_form hook runs inside the <form></form> tags, so we can't use that to output our form.
    //Instead, I use login_message, which is run before the wp-login.php form.  If this isn't wp-login, stop executing.
    if( strpos($_SERVER['SCRIPT_FILENAME'], 'wp-login.php') === FALSE ) return $arg;
    
    //Output the form
    global $jfb_saved_redirect;
    jfb_output_facebook_callback($jfb_saved_redirect);
    return $arg;
}
function jfb_show_loginform_btn_styles()
{
    //Output CSS so our form isn't visible.
    echo '<style type="text/css" media="screen">
		#wp-fb-ac-fm { width: 0; height: 0; margin: 0; padding: 0; border: 0; }
		</style>';
}


/*
 * If present, this function will override the default <fb:login-button> tag outputted by
 * jfb_output_facebook_btn() in the free plugin.  It references the premium options to let us
 * customize the button from the admin panel.
 */
add_filter('wpfb_output_button', 'jfb_output_facebook_btn_premium'); 
function jfb_output_facebook_btn_premium($arg)
{
    global $jfb_js_callbackfunc, $opt_jfbp_buttonsize, $opt_jfbp_buttontext;
    $attr = "";
    if( get_option($opt_jfbp_buttonsize) == 1 )     $attr = 'size="small"';
    else if( get_option($opt_jfbp_buttonsize) == 2 )$attr = 'v="2" size="small"';
    else if( get_option($opt_jfbp_buttonsize) == 3 )$attr = 'v="2" size="medium"';
    else if( get_option($opt_jfbp_buttonsize) == 4 )$attr = 'v="2" size="large"';
    else if( get_option($opt_jfbp_buttonsize) == 5 )$attr = 'v="2" size="xlarge"';
    return "document.write('<fb:login-button $attr onlogin=\"$jfb_js_callbackfunc();\">" . get_option($opt_jfbp_buttontext) . "</fb:login-button>');";
}


/**
  * Silently handle "double-logins" by returning to the referring page (i.e. don't perform the login - we're already logged in!)
  */
if( get_option($opt_jfbp_ignoredouble) ) add_action('wpfb_prelogin', 'jfb_ignore_redundant_logins');
function jfb_ignore_redundant_logins()
{
    //If we're trying to login and a user is already logged-in, this is a "double login"
    $currUser = wp_get_current_user();
    if( !$currUser->ID ) return;
    
    //Get the redirect URL.  _wp_http_referer comes from the NONCE, not the user-specified redirect url.
    if( isset($_POST['_wp_http_referer']))
        $redirect = $_POST['_wp_http_referer'];
    else if( isset($_POST['redirectTo']))
        $redirect = $_POST['redirectTo'];
    else
        return;
 
    global $jfb_log;
    $jfb_log .= "PREMIUM: User \"$currUser->user_login\" has already logged in via another browser session.  Silently refreshing the current page.\n";
    j_mail("Facebook Double-Login: " . $currUser->user_login);
    header("Location: " . $redirect);
    exit;
}


/**
  * Enforcing that the user doesn't select a proxied e-mail address is actually a 2-step process.
  * First, we insert an additional check in Javascript where we pull their data from Facebook again
  * and see if we can get their real address.  If so, let them login.  If not, we reject them -
  * however, since the user technically clicked "accept" (after selecting to use the proxied address),
  * they won't be re-prompted for the same permission on future logins, so we also have to 
  * revoke the email permission so they'll have another chance to accept next time.
  */
if(get_option($opt_jfbp_requirerealmail) && get_option($opt_jfb_req_perms))
    add_filter('wpfb_submit_loginfrm', 'jfb_enforce_real_email');
function jfb_enforce_real_email( $submitCode )
{
    return	"//PREMIUM CHECK: Enforce non-proxied emails\n" .
           	"FB.Facebook.apiClient.users_getLoggedInUser( function(uid)\n".
         	"{\n".
            "    FB.Facebook.apiClient.users_getInfo(uid, 'email,contact_email', function(emailCheckStrict)\n".
            "    {\n" .
            "        if(emailCheckStrict[0].contact_email)               //User allowed their real email\n".
            "            ".$submitCode.                 
            "        else if(emailCheckStrict[0].email)                  //User clicked allow, but chose a proxied email.\n".
            "        {\n".
            "            alert('Sorry, the site administrator has chosen not to allow anonymous emails.\\nYou must allow access to your real email address to login.');\n" .
            "            FB.Facebook.apiClient.callMethod('auth.revokeExtendedPermission', {'perm':'email'}, function(){});\n".
            "        }\n".
            "    });\n".
            "});\n";      
}


/*
 * Cache Facebook avatars to the local server
 */
if( get_option($opt_jfbp_cache_avatars) ) add_action('wpfb_login', 'jfb_cache_local_avatar');
function jfb_cache_local_avatar( $args )
{
    //Make sure the path exists
    global $jfb_log;
    $ud = wp_upload_dir();
    $path = $ud['path'] . '/facebook-avatars';
    @mkdir($path);
    $jfb_log .= "PREMIUM: Caching Facebook avatar to " . $path . ".\n";
    
    //Try to copy the thumbnail & update the meta
    $srcFile = get_usermeta($args['WP_ID'], 'facebook_avatar_thumb');
    $dstFile = $path . '/' . $args['WP_ID'] . "_thumb.jpg";
    if( !copy( $srcFile, $dstFile ) )
    {
        $jfb_log .= "   ERROR copying file '$srcFile' to '$dstFile'.  Avatar caching aborted.\n";
        return;
    }
    update_usermeta($args['WP_ID'], 'facebook_avatar_thumb', $ud['url'] . '/facebook-avatars/' . $args['WP_ID'] . '_thumb.jpg');

    //Try to copy the full image & update the meta
    $srcFile = get_usermeta($args['WP_ID'], 'facebook_avatar_full');
    $dstFile = $path . '/' . $args['WP_ID'] . "_full.jpg";
    if( !copy( $srcFile, $dstFile ) )
    {
        $jfb_log .= "   ERROR copying file '$srcFile' to '$dstFile'.  Avatar caching aborted.\n";
        return;
    }
    update_usermeta($args['WP_ID'], 'facebook_avatar_full', $ud['url'] . '/facebook-avatars/' . $args['WP_ID'] . '_full.jpg');
}

?>