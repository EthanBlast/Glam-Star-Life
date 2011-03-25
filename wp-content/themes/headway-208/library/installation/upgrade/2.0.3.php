<?php
////////Fix leaf alignment
$leafs = headway_get_all_leafs();

foreach($leafs as $leaf){
	$leaf['config'] = maybe_unserialize($leaf['config']);
	
	if($leaf['config']['align-right'] == true){
		$leaf['config']['align'] = 'right';
		unset($leaf['config']['align-right']);
	}

	headway_update_leaf($leaf['id'], array('config' => $leaf['config']));
}

//Remove element styling
headway_delete_element_style('div.entry-content a', 'font', 'line-height');

////////Tell the DB it has been done
update_option('headway-version', '2.0.3');