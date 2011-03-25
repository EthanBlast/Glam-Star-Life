<?php

	/* Esta variable almacenara si se ha producido un error o no */
	
	$wp_carousel_error = false;

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
		
	/* Comprobamos si podemos cargar el archivo */
	
	if (!is_readable($folder_path . 'wp-blog-header.php')) 
	{
		$wp_carousel_error = true;
		?>
			<div class="error">
				<p><?php printf('File <code>%s</code> can\'t be read!', $folder_path . 'wp-blog-header.php'); ?></p>
			</div>
		<?php
	if (isset($_POST['action']))
	{
		if ($_POST['action'] == 'updateSortableContent')
		{
			if (!isset($_POST['action'])) $_POST['action'] = '';
			if (!isset($_POST['internal_type'])) $_POST['internal_type'] = '';
			
			foreach ($_POST as $key => $value)
			{
				if (($key != 'action' && $key != 'internal_type' && $key != 'carousel_id') && $_POST['internal_type'] == 'serialized') // No es el campo de accion y el indicador es de serializado
				{
					$temp_printable = base64_decode($_POST[$key]);
					$temp_printable = explode('&', $temp_printable);
					foreach ($temp_printable as $temp_key => $temp_value)
					{
						$temp_value = explode('=', $temp_value);
						$array_keys = array('category_id', 'posts_order', 'posts_number', 'show_in_loop', 'order', 'type', 'post_title', 'desc', 'url_image', 'url_link', 'wp_carousel_ei_url', 'wp_carousel_ei_id');
						$array_names = array('ID', 'POSTS_ORDER', 'POSTS_NUMBER', 'SHOW', 'ORDER', 'TYPE', 'TITLE', 'DESC', 'IMAGE_URL', 'LINK_URL', 'WP_CAROUSEL_EI_URL', 'WP_CAROUSEL_EI_ID');
						$temp_value[0] = str_replace($array_keys, $array_names, $temp_value[0]);
						$temp_printable[$temp_value[0]] = urldecode($temp_value[1]);
						unset($temp_printable[$temp_key]);
					}
					if (!isset($temp_printable['POSTS_NUMBER'])) $temp_printable['POSTS_NUMBER'] = 0;
					if (!isset($temp_printable['SHOW'])) $temp_printable['SHOW'] = 0;
					unset($_POST[$key]);
					$key_exploded = explode('_', $key);
					$key = $key_exploded[1].'_'.$temp_printable['ID'].'_'.$temp_printable['TYPE'];
					$_POST[$key] = $temp_printable;
				}
			}
			
			$new_content = $_POST;
			$carousel_id = $new_content['carousel_id'];
			
			unset($new_content['action']);
			unset($new_content['internal_type']);
			unset($new_content['carousel_id']);
			
			/* ONLY FOR DEBUG */
			
			/*
			
			echo '<pre>';
			print_r($new_content);
			echo '</pre>';
			
			*/ 
			
		}
		elseif ($_POST['action'] == 'updateSortableOrder')
		{
			
			/* ONLY FOR DEBUG */
			
			/*
			
			echo '<pre>';
			print_r($_POST);
			echo '<pre>';
			
			*/ 
			
		}
				
	}
	$action_sended = 'SAVE-NO-AJAX:'.base64_encode(serialize($new_content)).':'.$carousel_id;
		?>
			<div class="updated fade">
				<p>Click <a href="admin.php?page=edit-carousel-<?php echo $_POST['carousel_id']; ?>&action=<?php echo $action_sended; ?>">here</a> to save changes.</p>
			</div>
		<?php
		exit;
	}
		
	/* Cargamos el archivo */
	
	echo '<p style="display:none;">';	
	require_once($folder_path . 'wp-blog-header.php');
	echo '</p>';
	
	/*
		Cargamos los archivos del idioma correspondiente
	*/
		
	$currentLocale = get_locale();
	if(!empty($currentLocale)) 
	{
		$moFile = dirname(__FILE__) . "/language/" . $currentLocale . ".mo";
		if(@file_exists($moFile) && is_readable($moFile)) load_textdomain('wp_carousel', $moFile);
	}

	$wp_carousel_content = maybe_unserialize(get_option('wp_carousel'));
	
	if (!isset($_POST['carousel_id']))
	{
		$wp_carousel_error = true;
		?>
			<div class="error">
				<p>
					<?php printf(__('There was an error, please, report it in the forum and attach this error message:', 'wp_carousel'), $folder_path . 'wp-blog-header.php'); ?>
				</p>
				<p>
					<?php echo base64_encode(serialize($_POST)); ?>
				</p>
			</div>
		<?php
		exit;
	}
	
	$carousel_content = $wp_carousel_content[$_POST['carousel_id']];

	if (isset($_POST['action']))
	{
		if ($_POST['action'] == 'updateSortableContent')
		{
			if (!isset($_POST['action'])) $_POST['action'] = '';
			if (!isset($_POST['internal_type'])) $_POST['internal_type'] = '';
			
			foreach ($_POST as $key => $value)
			{
				if (($key != 'action' && $key != 'internal_type' && $key != 'carousel_id') && $_POST['internal_type'] == 'serialized') // No es el campo de accion y el indicador es de serializado
				{
					$temp_printable = base64_decode($_POST[$key]);
					$temp_printable = explode('&', $temp_printable);
					foreach ($temp_printable as $temp_key => $temp_value)
					{
						$temp_value = explode('=', $temp_value);
						$array_keys = array('category_id', 'posts_order', 'posts_number', 'show_in_loop', 'order', 'type', 'post_title', 'desc', 'url_image', 'url_link', 'wp_carousel_ei_url', 'wp_carousel_ei_id');
						$array_names = array('ID', 'POSTS_ORDER', 'POSTS_NUMBER', 'SHOW', 'ORDER', 'TYPE', 'TITLE', 'DESC', 'IMAGE_URL', 'LINK_URL', 'WP_CAROUSEL_EI_URL', 'WP_CAROUSEL_EI_ID');
						$temp_value[0] = str_replace($array_keys, $array_names, $temp_value[0]);
						$temp_printable[$temp_value[0]] = urldecode($temp_value[1]);
						unset($temp_printable[$temp_key]);
					}
					if (!isset($temp_printable['POSTS_NUMBER'])) $temp_printable['POSTS_NUMBER'] = 0;
					if (!isset($temp_printable['SHOW'])) $temp_printable['SHOW'] = 0;
					unset($_POST[$key]);
					$key_exploded = explode('_', $key);
					$key = $key_exploded[1].'_'.$temp_printable['ID'].'_'.$temp_printable['TYPE'];
					$_POST[$key] = $temp_printable;
				}
			}
			
			$new_content = $_POST;
			$carousel_id = $new_content['carousel_id'];
			
			unset($new_content['action']);
			unset($new_content['internal_type']);
			unset($new_content['carousel_id']);
			
			/* ONLY FOR DEBUG */
			
			/*
			
			echo '<pre>';
			print_r($new_content);
			echo '</pre>';
			
			*/ 
			
		}
		elseif ($_POST['action'] == 'updateSortableOrder')
		{
			
			/* ONLY FOR DEBUG */
			
			/*
			
			echo '<pre>';
			print_r($_POST);
			echo '<pre>';
			
			*/ 
			
		}
		
		$wp_carousel_content[$_POST['carousel_id']] = $new_content;
		update_option('wp_carousel', serialize($wp_carousel_content));
		
	}
	
	if (!$wp_carousel_error)
	{
		?><div class="updated changes_saved"><p><?php _e('Changes saved', 'wp_carousel'); ?></p></div><?php
	}
	else
	{
		?><div class="error"><p><?php _e('There was an error!', 'wp_carousel'); ?></p></div><?php
	}
?>