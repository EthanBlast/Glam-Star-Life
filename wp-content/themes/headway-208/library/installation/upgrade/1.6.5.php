<?php
//////Upgrade for 1.6.5/////

headway_add_element_styles(
	array(
		'div.entry-content blockquote' => array(
				'top-border-width' => '1',
				'bottom-border-width' => '1',
				'top-border' => '999999',
				'bottom-border' => '999999',
				'color' => '666666',
				'font-family' => 'verdana, sans-serif',
				'font-size' => 12,
				'line-height' => 20
			)
	)
);

headway_update_option('seo-slugs-numbers', 'true');


////////Tell the DB it has been done
update_option('headway-version', '1.6.5');