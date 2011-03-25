<?php

/**
 * User list class: provides a filtered and ordered list of users and different ways of outputting them.
 */
class UserList {

	/**
	 * Constructor
	 */
	function UserList() {
	}
	
	/**
	 * Array of users that are not displayed
	 */ 
	var $hiddenusers = array();
	
	/**
	 * Array of blog ids which to take users from. (empty = only current blog, "-1" = all blogs)
	 */
	var $blogs = array();
	
	/**
	 * Array of role names. Only users belonging to one of these roles are displayed.
	 */
	var $roles = array('administrator', 'editor');
	
	/**
	 * Grouping of users. For example set to "blog" to group users by blogs.
	 */
	var $group_by = '';
		
	/**
	 * Link the user to either the "authorpage", "blog" (wpmu) or "website"
	 */
	var $user_link = 'authorpage';
	
	/**
	 * Flag whether to show the username underneith their avatar.
	 */
	var $show_name = false;

	/**
	 * Flag wether to show the post count for each user after the username.
	 */
	var $show_postcount = false;

	/**
	 * Flag whether to show a user's biography
	 */
	var $show_biography = false;
	
	/**
	 * Size of avatars.
	 */
	var $avatar_size = 0;
	
	/**
	 * Maximum number of users.
	 */
	var $limit = 0;

	/**
	 * Minimum number of posts which a user needs to have in order to be shown in the listing
	 */
	var $min_post_count = 0;
	
	/**
	 * The order which the users are shown in.
	 */
	var $order = 'display_name';
	
	/**
	 * The direction which the users are sorted in.
	 * Possible values: 'ascending' / 'asc' or 'descending' / 'desc'.
	 */
	var $sort_direction = 'asc';
	
	/**
	 * Group wrapper template
	 * - {groups} is replaced by the list of groups
	 */
	var $group_wrapper_template = '<div class="grouped-author-list">{groups}</div>';
	
	/**
	 * Group template
	 * - {name} is replaced by the name of the group
	 * - {group} is replaced by the list of users
	 */
	var $group_template = '<div class="author-group"><strong>{name}</strong><br/>{group}</div>';
	
	/**
	 * Wrapper template
	 * - {users} is replaced by the list of users
	 */
	var $userlist_template = '<div class="author-list">{users}</div>';
		
	/**
	 * User template
	 * - {class} is replaced by user specific classes
	 * - {user} is replaced by the user avatar (and possibly name)
	 */
	var $user_template = '<div class="{class}">{user}</div>';
	
	/**
	 * Changes the template strings so the user is rendered in a html list.
	 *
	 * @param $ordered set to true to use an ordered list (<ol>) instead of an unordered one (<ul>)
	 * @return void
	 */
	function use_list_template($ordered = false) {
		if ((bool)$ordered) {
			$this->userlist_template = '<ol class="author-list">{users}</ol>';		
		}
		else {
			$this->userlist_template = '<ul class="author-list">{users}</ul>';
		}
		$this->user_template = '<li class="{class}">{user}</li>';
	}
	
	/**
	 * Echos the list of users.
	 *
	 * @return void
	 */
	function output() {
		echo $this->get_output();
	}
	
	/**
	 * Returns the list of users.
	 *
	 * @uses apply_filters() Calls 'aa_userlist_empty' hook
	 * @return String the html formatted list of users
	 */
	function get_output() {
		// get users
		$users = $this->get_users();
		
		if (empty($users)) {
			return apply_filters('aa_userlist_empty', '<p class="no_users">'. __('No users found.', 'author-avatars'). '</p>');
		}
		elseif (!empty($this->group_by)) {
			return $this->format_groups($users);
		}
		else {
			return $this->format_users($users);
		}
	}
	
	/**
	 * Formats a grouped list of users
	 *
	 * @param Array $groups Array of an array of users. The array keys are used to retrieve the group name (see _group_name())
	 * @uses apply_filters() Calls 'aa_userlist_group_wrapper_template' hook
	 * @uses apply_filters() Calls 'aa_userlist_group_template' hook
	 * @return String the html formatted list of grouped users
	 */
	function format_groups ($groups) {
		$html = '';	
		foreach ($groups as $id => $group_users) {
			$tpl_vars = array(
				'{name}' => $this->_group_name($id),
				'{group}' => $this->format_users($group_users),
			);
			
			$html .= str_replace(array_keys($tpl_vars), $tpl_vars, apply_filters('aa_userlist_group_template', $this->group_template));
		}
		return str_replace('{groups}', $html, apply_filters('aa_userlist_group_wrapper_template', $this->group_wrapper_template));
	}
	
	/**
	 * Formats a list of users
	 *
	 * @param Array $groups An array of users.
	 * @uses apply_filters() Calls 'aa_userlist_template' hook
	 * @return String the html formatted list of users
	 */
	function format_users($users) {
		$html = '';
		foreach ($users as $user) {
			$html .= $this->format_user($user);
		}
		return str_replace('{users}', $html, apply_filters('aa_userlist_template', $this->userlist_template));
	}
	
	
	/**
	 * Formats the given user as html.
	 *
	 * @param WP_User $user The user to format (object of type WP_User).
 	 * @uses apply_filters() Calls 'aa_user_template' hook
	 * @return String html
	 */
	function format_user($user) {
		$tpl_vars = array('{class}' => '', '{user}' => '');
	
		$avatar_size = intval($this->avatar_size);
		if (!$avatar_size) $avatar_size = false;

		$name = $user->display_name;
		$alt = $title = $name;

		$divcss = array('user');
		if ($this->show_name) $divcss[] = 'with-name';
		
		$link = false;
		$link_type = $this->user_link;
		
		// always use 'website' for commentators
		if ( $user->user_id == -1) {
			$link_type = 'website';
		}

		switch ($link_type) {
			case 'authorpage':
				$link = get_author_posts_url($user->user_id);
				break;
			case 'website':
				$link = $user->user_url;
				if (empty($link) || $link == 'http://') $link = false;
				break;
			case 'blog':
				if (AA_is_wpmu()) {
					$blog = get_active_blog_for_user($user->user_id);
					if (!empty($blog->siteurl)) $link = $blog->siteurl;
				}
				break;
			case 'bp_memberpage':
				if (function_exists('bp_core_get_user_domain')) {
					$link = bp_core_get_user_domain($user->user_id);
				}
				elseif (function_exists('bp_core_get_userurl')) { // BP versions < 1.1
					$link = bp_core_get_userurl($user->user_id);
				}
				break;
		}

		if ($this->show_postcount) {
			$postcount = 0;
			if ($user->user_id == -1) {
				$postcount = $this->get_comment_count($user->user_email);
				$title .= ' ('. sprintf(_n("%d comment", "%d comments", $postcount, 'author-avatars'), $postcount) .')';
			}
			else {
				$postcount = $this->get_user_postcount($user->user_id);
				$title .= ' ('. sprintf(_n("%d post", "%d posts", $postcount, 'author-avatars'), $postcount) .')';
			}
			$name .= sprintf(' (%d)', $postcount);
		}

		$biography = false;
		if ($this->show_biography && $user->user_id > 0) {
			$biography = get_the_author_meta('description', $user->user_id);
			$divcss[] = 'with-biography';
			$name = '<strong>'. $name .'</strong>';
			if (empty($biography)) $divcss[] = 'biography-missing';
		}

		if ($user->user_id == -1) {
			// use email for commentators
			$avatar = get_avatar($user->user_email, $avatar_size);
		}
		else {
			$avatar = get_avatar($user->user_id, $avatar_size);
		}

		/* Strip all existing links (a tags) from the get_avatar() code to
		* remove e.g. the link which is added by the add-local-avatar plugin
		* @see http://wordpress.org/support/topic/309878 */
		if (!empty($link)) {
			$avatar = preg_replace('@<\s*\/?\s*[aA]\s*.*?>@', '', $avatar);
		}
		/* strip alt and title parameter */
		$avatar = preg_replace('@alt=["\'][\w]*["\'] ?@', '', $avatar);
		$avatar = preg_replace('@title=["\'][\w]*["\'] ?@', '', $avatar);
		/* insert alt and title parameters */
		$avatar = preg_replace('@ ?\/>@', ' alt="'.$alt.'" title="'.$title.'" />', $avatar);

		$html = '';
		if ($link) $html .= '<a href="'. $link .'">';
		$html .= '<span class="avatar">'. $avatar .'</span>';
		if ($this->show_name) $html .= '<span class="name">'. $name . '</span>';
		if ($link) $html .= '</a>';
		if ($biography) $html .= '<div class="biography">'. $biography .'</div>';
		
		$tpl_vars['{class}'] = implode($divcss, ' ');
		$tpl_vars['{user}'] = $html;

		return str_replace(array_keys($tpl_vars), $tpl_vars, apply_filters('aa_user_template', $this->user_template));
	}
	
	/**
	 * Returns a filtered and sorted list of users
	 *
	 * @return Array of users (WP_User objects), filtered, sorted and limited to the maximum number.
	 */
	function get_users() {
		// get all users
		$users = $this->get_blog_users();

		// add commentators if requested
		if(in_array('Commentator', $this->roles)) {
			$commentators = $this->get_commentators();
			if (is_array($users) && is_array($commentators)) {
				$users = array_merge($users, $commentators);
			}
			else if (is_array($commentators)) {
				$users = $commentators;
			}
		}

		// filter them
		$this->_filter($users);
		
		// sort them
		$this->_sort($users);
		
		// group them
		$this->_group($users);
		
		// and limit the number
		if (intval($this->limit) > 0) {
			$users = atrim($users, intval($this->limit));
		}
		
		return $users;
	}
	
	/**
	 * Returns array of all users from all blogs specified in field $blogs. 
	 * If $blogs is empty only users from the current blog are returned.
	 * 
	 * @return Array of users (WP_User objects).
	 */
	function get_blog_users() {
		global $wpdb, $blog_id;
		
		if (AA_is_wpmu() && !empty($this->blogs)) {

			// make sure all values are integers
			$this->blogs = array_map ('intval', $this->blogs);
			
			// if -1 is in the array display all users (no filtering)
			if (in_array('-1', $this->blogs)) {
				$blogs_condition = "meta_key LIKE '". $wpdb->base_prefix ."%capabilities'";
			}
			// else filter by set blog ids
			else {
				$blogs = array_map(create_function('$v', 'global $wpdb; return "\'" . $wpdb->get_blog_prefix($v) . "capabilities\'";'), $this->blogs);
				$blogs_condition = 'meta_key IN ('.  implode(', ', $blogs) .')';
			}
		}
		else {
			$blogs_condition = "meta_key = '". $wpdb->prefix ."capabilities'";
		}

		$query = "SELECT user_id, user_login, display_name, user_email, user_url, user_registered, meta_key, meta_value FROM $wpdb->users, $wpdb->usermeta".
			" WHERE " . $wpdb->users . ".ID = " . $wpdb->usermeta . ".user_id AND ". $blogs_condition . " AND user_status = 0";

		$users = $wpdb->get_results( $query );

		
		return $users;
	}

	/**
	 * Returns an array of all commentators
	 *
	 * @return array of users (commentators)
	 */
	function get_commentators() {
		global $wpdb;

		$query = "SELECT
			'-1' as user_id,
			comment_author_email as 'user_login',
			comment_author as 'display_name',
			comment_author_email as 'user_email',
			comment_author_url as'user_url',
			comment_date as 'user_registered',
			'wp_capabilities' as 'meta_key',
			'" . serialize( array('Commentator' => true) ) . "' as 'meta_value'
			FROM ". $wpdb->comments."
			WHERE comment_author_email <> '' AND comment_approved = 1 AND comment_type NOT IN( 'trackback', 'pingback' )";

		$commentators = $wpdb->get_results($query);

		return $commentators;
	}
	
	/**
	 * Filters the given array of users by $roles and $hiddenusers if set.
	 *
	 * @access private
	 * @param $users Array of users (WP_User objects). (by reference)
	 * @return void
	 */
	function _filter(&$users) {
		if (is_array($users)) {
			// arrays for keeping track of all 'valid' user ids and commentator emails
			$user_ids = array();
			$user_emails = array();
			
			foreach($users as $id => $usr) {
				$user = &$users[$id];
				$add = true;
				
				// Check user role
				// if we have set some roles to restrict by
				if ( is_array($this->roles) && !empty($this->roles)) {
					if (!isset($user->user_roles)) {
						$user->user_roles = array_keys(unserialize($user->meta_value));
					}
					// if the current user does not have one of those roles
					if (!array_in_array($user->user_roles, $this->roles)) {
						// do not add this user
						$add = false;
					}
				}
				
				// Hide hidden users
				if (
					// if we have set some users which we want to hide
					is_array($this->hiddenusers) && !empty($this->hiddenusers) &&
					// and the current user is one of them
					(in_array($user->user_login, $this->hiddenusers) || in_array($user->user_id, $this->hiddenusers))) {
					// do not add this user
					$add = false;
				}

				// real user
				if($user->user_id != -1) {
					// Remove duplicates
					if (
						// if we're not grouping anything
						empty($this->group_by) &&
						// and the current value has already been added
						in_array($user->user_id, $user_ids) ) {
						// do not add this user
						$add = false;
					}

					// Remove users with zero posts
					if (
						// if the flag is set to remove respective users
						$this->min_post_count > 0 &&
						// and they have zero posts
						$this->get_user_postcount($user->user_id) < $this->min_post_count ) {
						// do not add this user
						$add = false;
					}
				}
				// commentator
				else {
					if (
						// if we're not grouping anything
						empty($this->group_by) &&
						// and the current value has already been added
						in_array($user->user_email, $user_emails) ) {
						// do not add this user
						$add = false;
					}
				}

				if ($add === true) {
					// store current user_id/user_email for uniqueness check
					$user_ids[] = $user->user_id;
					$user_emails[] = $user->user_email;
				}
				else {
					// remove the current user from the array
					unset($users[$id]);
				}
			}
		}
	}
	
	/**
	 * Returns 1 if the sort direction is "ascending" and -1 if it is "descending"
	 *
	 * @access private
	 * @return int '-1' if field $sort_direction is 'desc', '1' otherwise.
	 */
	function _sort_direction() {
		if ($this->sort_direction == 'desc' || $this->sort_direction == 'descending')
			return -1;
		else 
			return 1;
	}
	
	/**
	 * Sorts the given array of users.
	 * 
	 * @access private
	 * @param Array $users Array of users (WP_User objects). (by reference)
	 * @param String $order The key to sort by. Can be one of the following: random, user_id, user_login, display_name.
	 * @return void
	 */
	function _sort(&$users, $order=false) {
		if (!$order) $order = $this->order;
		
		switch ($order) {
			case 'random':
				shuffle($users);
				break;
			case 'user_id':
				usort($users, array($this, '_users_cmp_id'));
				break;
			case 'user_login':
				usort($users, array($this, '_users_cmp_login'));
				break;
			case 'display_name':
				usort($users, array($this, '_users_cmp_name'));
				break;
			case 'first_name':
				usort($users, array($this, '_users_cmp_first_name'));
				break;
			case 'last_name':
				usort($users, array($this, '_users_cmp_last_name'));
				break;
			case 'post_count':
				usort($users, array($this, '_user_cmp_postcount'));
				break;
			case 'date_registered':
				usort($users, array($this, '_user_cmp_regdate'));
				break;
			case 'recent_activity':
				usort($users, array($this, '_user_cmp_activity'));
				break;
		}
	}

	/**
	 * Given two users, this function compares the user_ids.
	 * 
	 * @access private
	 * @param WP_User $a
	 * @param WP_User $b
	 * @return int result of a string compare of the user_ids.
	 */
	function _users_cmp_id($a, $b) {
	    if ($a->user_id == $b->user_id) return 0;
		return $this->_sort_direction() * ( $a->user_id < $b->user_id ? 1 : -1);
	}

	/**
	 * Given two users, this function compares the user_logins.
	 * 
	 * @access private
	 * @param WP_User $a
	 * @param WP_User $b
	 * @return int result of a string compare of the user_logins.
	 */
	function _users_cmp_login($a, $b) {
		return $this->_sort_direction() * strcasecmp($a->user_login, $b->user_login);
	}

	/**
	 * Given two users, this function compares the user's display names.
	 * 
	 * @access private
	 * @param WP_User $a
	 * @param WP_User $b
	 * @return int result of a string compare of the user display names.
	 */
	function _users_cmp_name($a, $b) {
		return $this->_sort_direction() * strcasecmp($a->display_name, $b->display_name);
	}

	/**
	 * Given two users, this function compares the user's display names.
	 *
	 * @access private
	 * @param WP_User $a
	 * @param WP_User $b
	 * @return int result of a string compare of the user first names.
	 */
	function _users_cmp_first_name($a, $b) {
		$an = $this->get_user_firstname ($a->user_id);
		$bn = $this->get_user_firstname ($b->user_id);
		return $this->_sort_direction() * strcasecmp( $an, $bn );
	}

	/**
	 * Given a user id returns the first name of the respective user.
	 *
	 * @param int $user_id
	 * @return string first name of user
	 */
	function get_user_firstname($user_id) {
		return get_usermeta( $user_id, 'first_name', true );
	}

	/**
	 * Given two users, this function compares the user's last names.
	 *
	 * @access private
	 * @param WP_User $a
	 * @param WP_User $b
	 * @return int result of a string compare of the user display names.
	 */
	function _users_cmp_last_name($a, $b) {
		$an = $this->get_user_lastname ($a->user_id);
		$bn = $this->get_user_lastname ($b->user_id);
		return $this->_sort_direction() * strcasecmp( $an, $bn );
	}

	/**
	 * Given a user id returns the last name of the respective user.
	 *
	 * @param int $user_id
	 * @return string last name of user
	 */
	function get_user_lastname($user_id) {
		return get_usermeta( $user_id, 'last_name', true );
	}
	
	/**
	 * Given two users, this function compares the user's post count.
	 * 
	 * @access private
	 * @param WP_User $a
	 * @param WP_User $b
	 * @return int result of a string compare of the user display names.
	 */
	function _user_cmp_postcount($a, $b) {
		$ac = $this->get_user_postcount($a->user_id);
		$bc = $this->get_user_postcount($b->user_id);
		
		if ($ac == $bc) return 0;
		return $this->_sort_direction() * ($ac < $bc ? -1 : 1);
	}
	
	/**
	 * Returns the postcount for a given user. 
	 * On WPMU sites posts are counted from all blogs in field $blogs and summed up.
	 *
	 * @param int $user_id
	 * @return int post count
	 */
	function get_user_postcount($user_id) {	
		$total = 0;
		if (AA_is_wpmu() && !empty($this->blogs)) {
			$blogs = $this->blogs;
			// all blogs -> only search the user's blogs
			if (in_array('-1', (array)$this->blogs)) {
				$blogs = (array) $this->get_user_blogs($user_id);
			}
			foreach ($blogs as $blog_id) {
				switch_to_blog($blog_id);
				$total += get_usernumposts($user_id);
				restore_current_blog();
			}
		}
		else {
			$total += get_usernumposts($user_id);
		}
		
		return $total;
	}
	
	/**
	 * Returns the comment count for a given email address.
	 * 
	 * @param string $user_email
	 * @return int number of comments
	 */
	function get_comment_count($user_email) {
		static $comment_counts = array();

		// retrieve counts for all commentators
		if (empty($comment_counts)) {
			global $wpdb;
			$query = 'SELECT comment_author_email, COUNT(*) AS total FROM ' . $wpdb->comments . ' WHERE comment_approved = 1 GROUP BY comment_author_email';
			$results = $wpdb->get_results($query);
			foreach ($results as $result) {
				$comment_counts[$result->comment_author_email] = $result->total;
			}
		}

		if (array_key_exists($user_email, $comment_counts)) {
			return $comment_counts[$user_email];
		}
		return 0;
	}

	/**
	 * Given two users, this function compares the date on which the user registered.
	 * 
	 * @access private
	 * @param WP_User $a
	 * @param WP_User $b
	 * @return int result of a string compare of the user's register date.
	 */
	function _user_cmp_regdate($a, $b) {
		return $this->_sort_direction() * strcasecmp($a->user_registered, $b->user_registered);
	}
	
	/**
	 * Given two users, this function compares the time of last user activity on the page.
	 *
	 * @access private
	 * @param WP_User $a
	 * @param WP_User $b
	 * @return int result of a string compare of the user's recent activity.
	 */
	function _user_cmp_activity($a, $b) {
		$a_activity = $this->get_user_last_activity($a->user_id);
		$b_activity = $this->get_user_last_activity($b->user_id);
		
		return $this->_sort_direction() * strcasecmp($a_activity, $b_activity);
	}
	
	/**
	 * Returns the time of last activity for a given user. 
	 *
	 * This requires that BuddyPress is installed.
	 * If it's not available the function returns an empty string.
	 *
	 * @param int $user_id
	 * @return string last activity date
	 */
	function get_user_last_activity($user_id) {
		if (AA_is_bp()) {
			return gmdate( 'Y-m-d H:i:s', (int)get_usermeta( $user_id, 'last_activity' ) );
		}
		return "";
	}
	
	/**
	 * Get blogs of user
	 *
	 * @param int $user_id
	 * @return Array of blog ids
	 */
	function get_user_blogs($user_id) {
		global $wpdb;
		
		$user = get_userdata( (int) $user_id );
		if ( !$user )
			return false;
 
		$blogs = $match = array();
		foreach ( (array) $user as $key => $value ) {
			if ( 	false !== strpos( $key, '_capabilities') &&
					0 === strpos( $key, $wpdb->base_prefix ) &&
					preg_match( '/' . $wpdb->base_prefix . '(\d+)_capabilities/', $key, $match )
			) $blogs[] = $match[1];
		}
		
		return $blogs;
	}
	
	/**
	 * Group the given set of users if set in field "group_by"
	 *
	 * @param Array of WP_User objects, by reference
	 * @access private
	 * @return void
	 */
	function _group(&$users) {
		if (empty($this->group_by)) return;
		
		switch($this->group_by) {
			case 'blog':
				if (AA_is_wpmu()) {
					$users_new = array();
					
					global $wpdb;
					$pattern = '/' . $wpdb->base_prefix . '([0-9]+)_capabilities/';
										
					foreach($users as $user) {
						$key = $user->meta_key;
						$matches = array();

						// blog id 1
						if ($key == $wpdb->base_prefix . 'capabilities') {
							$users_new[1][] = $user;
						}
						// other blogs
						else if (preg_match($pattern, $key, $matches) > 0) {
							$users_new[$matches[1]][] = $user;
						}
					}
					
					if (!empty($users_new)) $users = $users_new;
				}
				
				break;
		}
	}
	
	/**
	 * Retrieves the name for a group
	 * 
	 * @param int Group identifier
	 * @access private
	 * @return string
	 */
	function _group_name($id) {
		$name = 'Group #'. $id;
		if (!empty($this->group_by)) {			
			switch ($this->group_by) {
				case 'blog':
					$name = get_blog_option( $id, 'blogname');
					break;
			}
		}
		return $name;
	}
}

?>