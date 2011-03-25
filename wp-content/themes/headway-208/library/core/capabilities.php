<?php
add_filter('members_get_capabilities', 'headway_register_capabilities');
function headway_register_capabilities($capabilities) {
	$capabilities[] = 'headway_admin';
	$capabilities[] = 'headway_tools';
	$capabilities[] = 'headway_easy_hooks';
	$capabilities[] = 'headway_visual_editor';

	return $capabilities;
}

function headway_can_user($capability){
	if(!function_exists('members_check_for_cap')) return true;
	
	return members_check_for_cap($capability);
}