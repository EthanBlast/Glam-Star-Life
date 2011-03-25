<?php

	/* Calculamos la ruta al archivo wp-blog-header.php */
	
	$folder = str_replace('/update_db.php', '', $_SERVER['PHP_SELF']);
	$folder_exploded = explode('/', $folder);
	$folder_count = count($folder_exploded);
	krsort($folder_exploded);
	$folder_count--;
	unset ($folder_exploded[$folder_count]);
	$folder_count -= 2;
	$folder_path = "";
	$folder_temp = 0;
	for ($folder_temp = 0; $folder_temp < $folder_count; $folder_temp++)
	{
		$folder_path .= '../';
	}
		
	if (!is_readable($folder_path . 'wp-blog-header.php')) $folder_path = "../../../";
	
	if (!is_readable($folder_path . 'wp-blog-header.php'))
	{
		echo 'ERROR:WP_CAROUSEL_EI:FALSE';
		exit;
	}
		
	/* Cargamos el archivo */
	
	require_once($folder_path . 'wp-blog-header.php');
	
	if(WP_CAROUSEL_EI)
	{
		if (isset($_GET['carousel_id']))
		{
			$wp_carousel_content = maybe_unserialize(get_option('wp_carousel'));
			
			if (isset($wp_carousel_content[$_GET['carousel_id']]))
			{
				$carousel_content = wp_carousel($_GET['carousel_id'], 'carousel_ei');
				echo base64_encode(serialize($carousel_content['ITEMS']));
			}
			else
			{
				echo 'ERROR:$_GET["carousel_id"]:IS-NOT-A-CAROUSEL';
			}
			
		}
		else
		{
			echo 'ERROR:$_GET["carousel_id"]:NOT-SET';
		}
	}
	else
	{
		echo 'ERROR:WP_CAROUSEL_EI:FALSE';
	}
	
?>