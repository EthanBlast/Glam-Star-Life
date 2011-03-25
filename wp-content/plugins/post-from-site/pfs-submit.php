<?php 
/* * *
 * Processed form data into a proper post array, uses wp_insert_post() to add post. 
 * 
 * @param array $pfs_data POSTed array of data from the form
 */
require('../../../wp-load.php');

/**
 * Create post from form data, including uploading images
 * @param array $post
 * @param array $files
 * @return string success or error message.
 */
function pfs_submit($post,$files){
	$pfs_options = get_option('pfs_options');
	$pfs_data = $post;
	$pfs_files = $files;
	//echo "<pre style=\"border:1px solid #ccc;margin-top:5px;\">".print_r($pfs_data, true)."</pre>\n";
	//echo "<pre style=\"border:1px solid #ccc;margin-top:5px;\">".print_r($pfs_files, true)."</pre>\n";
	
	foreach($pfs_data as $key=>$value) ${$key} = $value;
	$imgAllowed = 0;
	$result = Array(
		'image'=>"",
		'error'=>"",
		'success'=>"",
		'post'=>""
	);
	$success = False;
	if (is_user_logged_in()) { 
		/* play with the image */
		switch (True) {
		case (1 < count($pfs_files['image']['name'])):
			// multiple file upload
			$result['image'] = "multiple";
			$file = $pfs_files['image'];
			for ( $i = 0; $i < count($file['tmp_name']); $i++ ){
				if( ''!=$file['tmp_name'][$i] ){
					$imgAllowed = (getimagesize($file['tmp_name'][$i])) ? True : (''==$file['name'][$i]);
					if ($imgAllowed){
						$j=$i+1;
						$upload[$j] = wp_upload_bits($pfs_files["image"]["name"][$i], null, file_get_contents($pfs_files["image"]["tmp_name"][$i]));
						if (False === $upload[$j]['error']){
							$success[$j] = True;
						} else {
							$result['error'] = "There was an error uploading the image $j {$upload['error']}";
							return $result;
						}
					} else {
						$result['error'] = "Incorrect filetype. Only images (.gif, .png, .jpg, .jpeg) are allowed.";
					}
				}
			}
			break;
		case ((1 == count($pfs_files['image']['name'])) && ('' != $pfs_files['image']['name'][0]) ):
			// single file upload
			$file = $pfs_files['image'];
			$result['image'] = 'single';
			$imgAllowed = (getimagesize($file['tmp_name'][0])) ? True : (''==$file['name'][0]);
			if ($imgAllowed){
				$upload[1] = wp_upload_bits($file["name"][0], null, file_get_contents($file["tmp_name"][0]));
				//echo "<pre style=\"border:1px solid #ccc;margin-top:5px;\">".print_r($upload, true)."</pre>\n";
				if (False === $upload[1]['error']){
					$success[1] = True;
				} else {
					$result['error'] = "There was an error uploading the image: {$upload[1]['error']}";
					return $result;
				}
			} else {
				$result['error'] = "Incorrect filetype. Only images (.gif, .png, .jpg, .jpeg) are allowed.";
			}
			break;
		default: 
			$result['image'] = 'none';
		}
		if ('' != $result['error']) return $result;
		//echo "<pre style=\"border:1px solid #ccc;margin-top:5px;\">".print_r($upload, true)."</pre>\n";
		//echo "<pre style=\"border:1px solid #ccc;margin-top:5px;\">".print_r($success, true)."</pre>\n";
		/* manipulate $pfs_data into proper post array */
		if ($title != '' && $postcontent != '') {
			$content = $postcontent;
			global $user_ID;
			get_currentuserinfo();
			if (is_array($success)){
				foreach(array_keys($success) as $i){
					//$i++;
					$imgtag = "[!--image$i--]";
					if (False === strpos($content,$imgtag)) $content .= "<br />$imgtag";
					$content = str_replace($imgtag, "<img src='{$upload[$i]['url']}' class='pfs-image' />", $content);
				}
			} else {
				$imgtag = "[!--image1--]";
				if (False === strpos($content,$imgtag)) $content .= "<br />$imgtag";
				$content = str_replace($imgtag, "<img src='{$upload[1]['url']}' class='pfs-image' />", $content);
			}
			//if any [!--image#--] tags remain, they are invalid and should just be deleted.
			$content = preg_replace('/\[\!--image\d*--\]/','',$content);
			$categories = $cats;
			$newcats = explode(',',$newcats);
			foreach ($newcats as $cat) $categories[] = wp_insert_category(array('cat_name' => trim($cat), 'category_parent' => 0));
			$newtags = explode(',',$newtags);
			foreach ($newtags as $tag) {
				wp_create_tag(trim($tag));
				$tags[] = trim($tag);
			}
			$postarr = array();
			$postarr['post_title'] = $title;
			$postarr['post_content'] = $content;
			$postarr['comment_status'] = $pfs_options['pfs_comment_status'];
			$postarr['post_status'] = $pfs_options['pfs_post_status'];
			$postarr['post_author'] = $user_ID;
			$postarr['post_category'] = $categories;
			$postarr['tags_input'] = implode(',',$tags);
			$postarr['post_type'] = 'post';
			//echo "<pre style=\"border:1px solid #ccc;margin-top:5px;\">".print_r($postarr, true)."</pre>\n";
			$post_id = wp_insert_post($postarr);
			
			if (0 == $post_id) {
				$result['error'] = __("Unable to insert post- unknown error.",'pfs_domain');
			} else {
				$result['success'] = __("Post added, please wait to return to the previous page.",'pfs_domain');
				$result['post'] = $post_id;
			}
		} else {
			 $result['error'] = __("You've left either the title or content empty.",'pfs_domain');
		}
	} else {
		/* TODO: translate following */
		$result['error'] = "You are no longer logged in. Did something happen? Try <a href='".get_bloginfo('url')."/wp-login.php'>logging in</a> again.";
	}
	return $result;
}

if (!empty($_POST)){
	$pfs = pfs_submit($_POST,$_FILES);
	echo json_encode($pfs);
	//echo "<pre style=\"border:1px solid #ccc;margin-top:5px;\">".print_r($pfs, true)."</pre>\n";
} else {
	/* TODO: translate following */
	echo "You should not be seeing this page, something went wrong. <a href='".get_bloginfo('url')."'>Go home</a>?";
}

//get_footer();
?>
