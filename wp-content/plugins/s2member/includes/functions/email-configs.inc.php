<?php
/*
Copyright: © 2009 WebSharks, Inc. ( coded in the USA )
<mailto:support@websharks-inc.com> <http://www.websharks-inc.com/>

Released under the terms of the GNU General Public License.
You should have received a copy of the GNU General Public License,
along with this software. In the main directory, see: /licensing/
If not, see: <http://www.gnu.org/licenses/>.
*/
/*
Direct access denial.
*/
if (realpath (__FILE__) === realpath ($_SERVER["SCRIPT_FILENAME"]))
	exit ("Do not access this file directly.");
/*
Functions that modify the email From: name/address.
*/
if (!function_exists ("ws_plugin__s2member_email_config"))
	{
		function ws_plugin__s2member_email_config ()
			{
				do_action ("ws_plugin__s2member_before_email_config", get_defined_vars ());
				/**/
				add_filter ("wp_mail_from", "_ws_plugin__s2member_email_config_email");
				add_filter ("wp_mail_from_name", "_ws_plugin__s2member_email_config_name");
				/**/
				do_action ("ws_plugin__s2member_after_email_config", get_defined_vars ());
				/**/
				return;
			}
	}
/**/
if (!function_exists ("_ws_plugin__s2member_email_config_email"))
	{
		function _ws_plugin__s2member_email_config_email ($email = FALSE)
			{
				do_action ("_ws_plugin__s2member_before_email_config_email", get_defined_vars ());
				/**/
				return apply_filters ("_ws_plugin__s2member_email_config_email", $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["reg_email_from_email"], get_defined_vars ());
			}
	}
/**/
if (!function_exists ("_ws_plugin__s2member_email_config_name"))
	{
		function _ws_plugin__s2member_email_config_name ($name = FALSE)
			{
				do_action ("_ws_plugin__s2member_before_email_config_name", get_defined_vars ());
				/**/
				return apply_filters ("_ws_plugin__s2member_email_config_name", $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["reg_email_from_name"], get_defined_vars ());
			}
	}
/*
Convert primitive Role names in emails sent by WordPress®.
Attach to: add_filter("wpmu_signup_user_notification_email");
	~ Only necessary with this particular email.
*/
if (!function_exists ("ws_plugin__s2member_ms_nice_email_roles"))
	{
		function ws_plugin__s2member_ms_nice_email_roles ($message = FALSE)
			{
				eval ('foreach(array_keys(get_defined_vars())as$__v)$__refs[$__v]=&$$__v;');
				do_action ("ws_plugin__s2member_before_ms_nice_email_roles", get_defined_vars ());
				unset ($__refs, $__v); /* Unset defined __refs, __v. */
				/**/
				$message = preg_replace ("/ as a (subscriber|s2member_level[1-4])/i", " as a Member", $message);
				/**/
				return apply_filters ("ws_plugin__s2member_ms_nice_email_roles", $message, get_defined_vars ());
			}
	}
?>