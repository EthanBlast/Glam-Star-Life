<?php
////////Fix Featured Leafs
$leafs = headway_get_all_leafs();

foreach($leafs as $leaf){
	$leaf['options'] = maybe_unserialize($leaf['options']);
	
	if(!$leaf['options']['featured-meta-title-above-left']) $leaf['options']['featured-meta-title-above-left'] = headway_get_option('post-above-title-left');
	if(!$leaf['options']['featured-meta-title-below-left']) $leaf['options']['featured-meta-title-below-left'] = headway_get_option('post-below-title-left');
	if(!$leaf['options']['featured-meta-content-below-left']) $leaf['options']['featured-meta-content-below-left'] = headway_get_option('post-below-content-left');

	headway_update_leaf($leaf['id'], array('options' => $leaf['options']));
}

////////Tell the DB it has been done
update_option('headway-version', '2.0.7');