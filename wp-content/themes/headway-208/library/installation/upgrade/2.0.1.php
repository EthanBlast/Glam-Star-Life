<?php
////////Fix leaf types and leaf settings
$leafs = headway_get_all_leafs();

foreach($leafs as $leaf){
	$leaf['options'] = maybe_unserialize($leaf['options']);
	
	if($leaf['type'] == 'text' && !isset($leaf['options']['content'])){
		$leaf['type'] = 'html';
	}

	headway_update_leaf($leaf['id'], array('type' => $leaf['type']));
}

////////Tell the DB it has been done
update_option('headway-version', '2.0.1');