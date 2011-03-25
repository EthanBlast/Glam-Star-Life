<?php
	
	/*
		Plugin Name: WP Carousel
		Plugin URI: http://sumolari.com/?p=1759
		Description: A great carousel manager for WordPress
		Version: 0.5.3
		Author: Sumolari
		Author URI: http://sumolari.com
	*/
	
	define('WP_CAROUSEL_VERSION', 0.53); // 0.5 = 0.50 < 0.5X < 0.60 == 0.6

	/*
		Copyright 2010 Lluís Ulzurrun de Asanza Sàez  (email : info@sumolari.com, sumolari@gmail.com)
	
		This program is free software; you can redistribute it and/or modify
		it under the terms of the GNU General Public License as published by
		the Free Software Foundation; either version 2 of the License, or
		(at your option) any later version.
	
		This program is distributed in the hope that it will be useful,
		but WITHOUT ANY WARRANTY; without even the implied warranty of
		MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
		GNU General Public License for more details.
	
		You should have received a copy of the GNU General Public License
		along with this program; if not, write to the Free Software
		Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
	*/
	
	/*
		Changes this value to TRUE to enable the WP Carousel's External Integration option
	*/
	
	define('WP_CAROUSEL_EI', true);
	
	/*
		Definimos la URL de la encuesta para mejorar WP Carousel
	*/
		
	if (!defined('WP_CAROUSEL_SURVEY')) define('WP_CAROUSEL_SURVEY', 'http://polldaddy.com/s/28CADAB2FBEB965F');
	
	/*
		Definimos WP_CONTENT_URL y WP_PLUGIN_URL, si es que no están definidos ya
	*/
	
	if (!defined( 'WP_CONTENT_URL' ) ) define( 'WP_CONTENT_URL', get_option( 'siteurl' ).'/wp-content');
	if (!defined( 'WP_PLUGIN_URL' ) ) define( 'WP_PLUGIN_URL', WP_CONTENT_URL.'/plugins');
	
	/*
		Creamos la matriz $wp_carousel_path, que almacenará:
			[1] -> Ruta a este archivo, partiendo de: wp-content/plugins .
			[2] -> Nombre de la carpeta que contiene a este archivo, por defecto es wp-carousel, pero puede ser modificado por el usuario.
			[3] -> Ruta a este archivo, partiendo de la carpeta que contiene los archivos de WordPress
			[4] -> Equivalente a WP_CONTENT_URL, sólo que con una barra al final
			[5] -> Equivalente a WP_PLUGIN_URL, sólo que con una barra al final
			[6] -> Ruta a la carpeta que contiene a este archivo (con barra), partiendo de la carpeta que contiene los archivos de WordPress
			[7] -> Nombre de la carpeta generalmente conocida como wp-admin
			[8] -> Nombre de la carpeta generalmente conocida como wp-content
	*/
	
	$wp_carousel_path[1] = plugin_basename(__FILE__);
	$wp_carousel_path[2] = ereg_replace('/wp-carousel.php', '', $wp_carousel_path[1]);
	$wp_carousel_path[3] = WP_PLUGIN_URL.'/'.$wp_carousel_path[1];
	$wp_carousel_path[4] = WP_CONTENT_URL.'/';
	$wp_carousel_path[5] = WP_PLUGIN_URL.'/';
	$wp_carousel_path[6] = ereg_replace('wp-carousel.php', '', $wp_carousel_path[3]);
	$wp_carousel_path[7] = ereg_replace('/wp-carousel.php', '', $wp_carousel_path[1]);
	$wp_carousel_path[8] = ereg_replace('/', '', ereg_replace(get_option('siteurl'), '', $wp_carousel_path[4]));
			
	/*
		Cargamos los archivos del idioma correspondiente
	*/
		
	$currentLocale = get_locale();
	if(!empty($currentLocale)) 
	{
		$moFile = dirname(__FILE__) . "/language/" . $currentLocale . ".mo";
		if(@file_exists($moFile) && is_readable($moFile)) load_textdomain('wp_carousel', $moFile);
	}
	
	/*
		Definimos la URL de los videos de información
	*/
	
	if ($currentLocale == 'es_ES')
	{
		define('WP_CAROUSEL_INFO_VIDEOS', 'http://www.youtube.com/watch?v=L7ty8sM5DEE'); // Vídeos en Español
	}
	else
	{
		define('WP_CAROUSEL_INFO_VIDEOS', 'http://www.youtube.com/watch?v=W9paB9JJ5bo'); // Vídeos en Inglés
	}
	
	/*
		Habilitamos las miniaturas de los artículos
	*/
	
	if (function_exists('add_theme_support')) add_theme_support('post-thumbnails');
	
	/*
		Registremos todos los archivos JS que cargaremos en un momento o en otro, así los tenemos todos juntitos y no perdemos tiempo buscando
	*/
		
	wp_register_script('wp_carousel_jquery_tablesorter', $wp_carousel_path[6].'js/jquery.tablesorter.js', array('jquery')); // Para ordenar las tablas 
	wp_register_script('wp_carousel_tablesorter_init_edit_carousel', $wp_carousel_path[6].'js/tablesorter.init.edit-carousel.js', array('jquery', 'wp_carousel_jquery_tablesorter')); //Inicia tablesorter en las páginas de opciones de los carruseles
	wp_register_script('wp_carousel_edit_carousel_init_js', $wp_carousel_path[6].'js/edit.carousel.init.js.js', array('jquery', 'jquery-ui-core', 'jquery-ui-draggable', 'jquery-ui-droppable', 'jquery-ui-sortable', 'jquery-ui-tabs')); // Código Javascript necesario para las páginas de opciones de cada carrusel
	wp_register_script('wp_carousel_edit_carousel_show_lists_js', $wp_carousel_path[6].'js/edit.carousel.dropdown.hide.divs.js.js'); // Código encargado de mostrar y ocultar ciertos campos del formulario
	wp_register_script('wp_carousel_stepcarousel', $wp_carousel_path[6].'js/stepcarousel.js', array('jquery'), false, true); // Stepcarousel, el script JS principal del carrusel (tomado de http://www.dynamicdrive.com/dynamicindex4/stepcarousel.htm)
	wp_register_script('wp_carousel_init_all_stepcarousel', $wp_carousel_path[6].'js/init.all.stepcarousel.php', array('wp_carousel_stepcarousel')); // Configuraciones de todos los carruseles
	
	// Buscamos a continuación plugins incompatibles, de momento sólo está Smooth Slider
	
	if (function_exists('smooth_slider_enqueue_scripts')) // Buscamos a Smooth Slider
	{
		// ¡Vaya! Está activado
		wp_deregister_script('stepcarousel'); // Desregistramos el StepCarousel de Smooth Slider
		// Registramos el StepCarousel de WP Carousel con el nombre del que usa Smooth Slider 
		wp_register_script('stepcarousel', $wp_carousel_path[6].'js/stepcarousel.js', array('jquery'), SMOOTH_SLIDER_VER, false); // Stepcarousel, el script JS principal del carrusel (tomado de http://www.dynamicdrive.com/dynamicindex4/stepcarousel.htm)
		// Cargamos el StepCarousel de Smooth Slider modificado
		wp_enqueue_script('stepcarousel', $wp_carousel_path[6].'js/stepcarousel.js', array('jquery'), SMOOTH_SLIDER_VER, false); // Stepcarousel, el script JS principal del carrusel (tomado de http://www.dynamicdrive.com/dynamicindex4/stepcarousel.htm)
	}
	
	// Creamos los iniciadores de cada carrusel individual
	$wp_carousel_config_temp = maybe_unserialize(get_option('wp_carousel_config'));
	if (is_array($wp_carousel_config_temp))
	{
		foreach ($wp_carousel_config_temp as $key => $value)
		{
			wp_register_script('wp_carousel_init_stepcarousel_carousel_'.$key, $wp_carousel_path[6].'js/init.stepcarousel.php?id='.$key, array('wp_carousel_stepcarousel'), false, true); // Configuraciones del carrusel con ID $key
		}
	}
	unset($wp_carousel_config_temp);
	
	/*
		Cargamos todas las acciones que se añaden
	*/
	
	add_action('admin_menu', 'wp_carousel_adminmenu_links'); // Menú de WP Carousel
	add_action('widgets_init', create_function('', 'return register_widget("WP_Carousel_Widget");')); // Widget de WP Carousel
	
	/*
		Comprobamos si tenemos que borrar algún carrusel o  si tenemos que hacer un guardado NO-AJAX
	*/
	
	if (strpos(wp_carousel_create_internal_urls('SELF_URL'), 'admin.php') !== false)
	{
		
		if (isset($_GET['action']))
		{
			$action = explode(':', $_GET['action']);
			switch ($action[0])
			{
				case 'DELETE_CAROUSEL':
					if (isset($_GET['sure']))
					{
						if ($_GET['sure'] == 'yes')
						{
							$items = maybe_unserialize(get_option('wp_carousel'));
							unset($items[$action[1]]);
							$items_db = serialize($items);
							update_option('wp_carousel', $items_db);
							unset($items);
						}
					}
					break;
				case 'UNINSTALL':
					delete_option('wp_carousel');
					delete_option('wp_carousel_config');
				default:
					break;
			}
		}
	
	}
	
	/*
		Comprobamos si estamos en una página de opciones de algún carrusel
	*/
	
	if (isset($_GET['page']))
	{
		if ($_GET['page'] == 'wp-carousel-add-theme')
		{
			$items = maybe_unserialize(get_option('wp_carousel'));
			$items[] = array();			
			$items_db = serialize($items);
			update_option('wp_carousel', $items_db);
			$config = maybe_unserialize(get_option('wp_carousel_config'));
			$config[] = array(
				'THEME' => 'default',
				'SHOW_ARROWS' => '1',
				'SLIDE_POSTS' => '1',
				'ENABLE_PAGINATION' => '1',
				'AUTOSLIDE_TIME' => '3000',
				'AUTOSLIDE_POSTS' => '1',
				'IMG_WIDTH' => '',
				'IMG_HEIGHT' => '',
				'PANEL_WIDTH' => '',
				'LOOP_MODE' => '1'
			);					
			$config_db = serialize($config);
			update_option('wp_carousel_config', $config_db);
		}
			
		if (strrpos($_GET['page'], 'edit-carousel-') === false)
		{
			// No estamos editando carruseles
		}
		else
		{
			// ¡Bingo! Tenemos cosas que hacer, como cargar el código Javascript :)
			wp_enqueue_script('wp_carousel_tablesorter_init_edit_carousel'); // Para ordenar las tablas
			wp_enqueue_script('wp_carousel_edit_carousel_init_js'); // Para crear el sistema de pestañas
			wp_enqueue_script('wp_carousel_edit_carousel_show_lists_js'); // Para ocultar y mostrar ciertos campos del formulario
			//wp_enqueue_style('wp_carousel_tablesorter_edit_carousel_table_css'); // Para darle estilo a las tablas (y mostrar las flechas)
			
			// Esta función al ser temporal, no tiene más información
			function wp_carousel_print_edit_carousel_page_css() {
				global $wp_carousel_path;
				echo '<link rel="stylesheet" href="'.$wp_carousel_path[6].'css/tablesorter.edit-carousel.table-css.css'.'" type="text/css" media="all" />';
				echo '<link rel="stylesheet" href="'.$wp_carousel_path[6].'css/edit-carousel.add-content-form.css'.'" type="text/css" media="all" />';
			}
			add_action('admin_head', 'wp_carousel_print_edit_carousel_page_css');
			
		}
	}
	else
	{
		/*
			Cargamos el código JS y CSS del carrusel
		*/
		if (!is_admin())
		{
			unset($wp_carousel_config_temp);
			wp_enqueue_script('wp_carousel_stepcarousel'); // Indicamos que se tiene que cargar el código JS de StepCarousel
			add_action('wp_head', 'wp_carousel_load_theme_css'); // Lo mismo que antes, sólo que cargamos CSS
		}
	}
	
	/*
		@Función: wp_carousel_load_extras()
		@Versión: 1.0
		@Parámetros:
								$debug (bool): Determina si al acabar de ejecutar la función se debe mostrar el registro o no
								$dir: Si se carga desde una carpeta distinta, puede ser necesario establecer un valor inicial para $dir
		@Descripción: Carga los plugins instalados en WP Carousel para poder ser usados
		@Añadida en la versión: 0.5	
	*/
	
	function wp_carousel_load_extras($debug=false, $dir = '')
	{
		
		global $wp_carousel_path;
		
		// Antes de nada, veamos si estamos ejecutando la función en modo debug
		
		$log[] = 'Comprobacion del valor de $debug en wp_carousel_load_extras()';
		if (!$debug)
		{
			$log[] = 'La comprobacion ha determinado que $debug no tiene por valor true (booleano)';
			// De momento sabemos que no estamos en modo debug, pero ¿será porque la variable es false o porque la variable no es true?
			if (is_bool($debug)) 
			{
				// Vale, todo va bien de momento, no seamos paranóicos
				$log[] = 'La comprobacion ha determinado que $debug tiene por valor false (booleano)';
			}
			else
			{
				// Vaya, parece que no íbamos desencaminados: la variable $debug tiene un valor no booleano, así que estamos ante un error. De momento almacenamos el error y ponemos esta función en modo debug, a ver si encontramos el origen del (o los) problemas
				$log[] = 'La comprobacion ha determinado que $debug tiene por valor: "'.$debug.'" (no booleano)';
				$errors[] = 'La variable $debug tiene un valor no booleano, de hecho su valor es: "'.$debug.'".';
				$log[] = 'Se ha añadido a la matriz de errores el error '.key($errors).': "'.current($errors).'"';
			}
		}
						
		if (is_admin())
		{
			$dir .= '../';
		}
						
		$dir .= $wp_carousel_path[8].'/plugins/'.$wp_carousel_path[2].'/extras';
		$log[] = 'La ruta relativa desde la carpeta actual hasta la carpeta de extras es: "'.$dir.'"';
			
		if (is_dir($dir))
		{
			if ($handle = opendir($dir))
			{
				$log[] = 'La ruta relativa dirige hasta una carpeta';
				$extras = array();
				while (($file = readdir($handle)) !== false)
				{
					if (is_dir($dir.'/'.$file) && $file != '.' && $file != '..' && $file != '.svn')
					{
						$log[] = 'Se ha determinado que la carpeta "'.$file.'" contiene los archivos de un extra';
						$extras[] = $file;
					}
				}
			closedir($handle);
			} 
		}
		else
		{
			$log[] = 'La ruta relativa no dirige hasta una carpeta, de hecho dirige hasta "'.$dir.'"';
			$errors[] = 'La ruta relativa no dirige hasta una carpeta, de hecho dirige hasta: "'.$dir.'"';
			$log[] = 'Se ha añadido a la matriz de errores el error '.key($errors).': "'.current($errors).'"';
		}
		
		foreach ($extras as $temp_key => $temp_value)
		{
			$to_check_information_file = '';
			$to_check_functions_file = '';
			
			if (is_admin())
			{
				$to_check_information_file .= '../';
				$to_check_functions_file .= '../';
			}
			
			$to_check_information_file .= $wp_carousel_path[8].'/plugins/'.$wp_carousel_path[2].'/extras/'.$temp_value.'/index.php';
			$to_check_functions_file .= $wp_carousel_path[8].'/plugins/'.$wp_carousel_path[2].'/extras/'.$temp_value.'/extra.php';
						
			if (is_file($to_check_information_file))
			{
				$log[] = 'Se ha localizado un archivo de informacion (index.php) en la carpeta';
				if (is_file($to_check_functions_file))
				{
					$log[] = 'Se ha localizado un archivo de funciones (functions.php) en la carpeta';
					include($to_check_information_file);
					$extras[$temp_value] = $extra;
					require_once($to_check_functions_file);
					unset($extra);
					unset($extras[$temp_key]);
				}
				else
				{
					$log[] = 'No hay ningun archivo de funciones (functions.php) en la carpeta: "'.$to_check_information_file.'"';
					$errors[] = 'No hay ningun archivo de funciones (functions.php) en la carpeta: "'.$to_check_information_file.'"';
					$log[] = 'Se ha añadido a la matriz de errores el error '.key($errors).': "'.current($errors).'"';
				}
			}
			else
			{
				$log[] = 'No hay ningun archivo de informacion (index.php) en la carpeta: "'.$to_check_information_file.'"';
				$errors[] = 'No hay ningun archivo de informacion (index.php) en la carpeta: "'.$to_check_information_file.'"';
				$log[] = 'Se ha añadido a la matriz de errores el error '.key($errors).': "'.current($errors).'"';
			}
			
		}
		
		$_SESSION['WP_CAROUSEL_EXTRAS'] = $extras;
		
		if ($debug)
		{
			$log[] = 'Comprobacion de recuento de errores de la funcion wp_carousel_load_extras()';
			if(!empty($errors))
			{
				// Uy uy uy... ha habido errores durante la ejecución de esta función, cortemos el script y mostremos los errores de forma legible
				$log[] = 'La comprobacion del recuento de errores de la funcion wp_carousel_load_extras() ha detectado que hay errores en la funcion wp_carousel_options_page()';
				echo '<h2>'.__('Errors', 'wp_carousel').'</h2><pre>';
				print_r($errors);
				echo '</pre>';
				$log[] = 'Se ha mostrado el listado de errores de la funcion wp_carousel_load_extras()';
				
				echo '<h2>'.__('Log', 'wp_carousel').'</h2><pre>';
				print_r($log);
				echo '</pre>';
				
				// Avisemos de que cortamos el script
				echo '<p>El script se deja de ejecutar a partir de ahora debido a que se han detectado errores durante su ejecución</p>';
				exit; // Cortamos el script
			} else {
				// ¡Qué bien, no hay errores!
				$log[] = 'La comprobacion del recuento de errores de la funcion wp_carousel_load_extras() ha determinado que no ha habido errores durante la ejecucion de wp_carousel_load_extras()';
				echo '<h2>'.__('Log', 'wp_carousel').'</h2><pre>';
				print_r($log);
				echo '</pre>';
			}
		}
		
	}	
	
	/*
		@Función: wp_carousel_custom_help_tab()
		@Versión: 1.0
		@Descripción: Añade la función wp_carousel_custom_help_tab_filter al filtro de la pestaña de ayuda
		@Añadida en la versión: 0.5	
	*/
	
	function wp_carousel_custom_help_tab() {
	   add_filter('contextual_help', 'wp_carousel_custom_help_tab_filter');
	}
	
	/*
		@Función: wp_carousel_custom_help_tab_filter()
		@Versión: 1.0
		@Parámetros:
								$help: El contenido inicial de la pestaña
		@Descripción: Modifica el contenido de la pestaña de ayuda de WordPress
		@Añadida en la versión: 0.5		
	*/
	
	function wp_carousel_custom_help_tab_filter($help) {
		printf ("<p>".__('Please, fill up <a href="%s">this survey</a> in order to improve WP Carousel', 'wp_carousel').".</p>", WP_CAROUSEL_SURVEY);
		echo '<hr class="wp_carousel_help_separator" />';
		echo "<h5>".__('WP Carousel\'s Quick Help', 'wp_carousel')."</h5>";
		echo '<p>';
		printf(__('Did you find any error? Please, report them <a href="%s">here (English)</a> or <a href="%s">here (Spanish)</a>.', 'wp_carousel'), 'http://foro.sumolari.com/index.php/board,34.0.html', 'http://foro.sumolari.com/index.php/board,15.0.html');
		echo '</p>';
		echo "<h5>".__('How to show a carousel', 'wp_carousel')."</h5>";
		echo '<p>';
		printf(__('You can show a carousel by to ways. One way is adding <code>%s</code> in your WordPress theme. The other way is adding the WP Carousel\'s Widget into the sidebar.', 'wp_carousel'), 'wp_carousel(CAROUSEL_ID)');
		echo '</p>';
		echo "<h5>".__('WP Carousel\'s External Integration Mode', 'wp_carousel')."</h5>";
		echo '<p>';
		printf(__('With WP Carousel\'s External Integration Mode you can show a WP Carousel carousel from a WordPress blog in your WordPress blog. To do this, both blogs must have enabled this mode, which is disabled by default for security reasons. To enable it, just search %s and replace it with %s ', 'wp_carousel'), "<code>define('WP_CAROUSEL_EI', false);</code>", "<code>define('WP_CAROUSEL_EI', true);</code>");
		echo '</p>';
		echo "<h5>".__('WP Carousel\'s Tutorials', 'wp_carousel')."</h5>";
		echo '<p>';
		printf(__('Are you looking for more information? In that case, please, watch <a href="%s">this video</a>. It explains how to use WP Carousel and how to manage your carousels.', 'wp_carousel'), WP_CAROUSEL_INFO_VIDEOS);
		echo '</p>';
	}
	
	/*
		@Función: wp_carousel_show_translation_info()
		@Versión: 1.0
		@Parámetros:
								$text: Texto original del pie de página
		@Descripción: Muestra información sobre la traducción
		@Añadida en la versión: 0.5		
	*/
		
	function wp_carousel_show_translation_info ($text) {
    	return $text.' | '.__('WP Carousel translated to English by <a href="http://sumolari.com">Sumolari</a>', 'wp_carousel');
    }
	
	// Añadimos la función al filtro
    add_filter('admin_footer_text', 'wp_carousel_show_translation_info'); 
	
	/*
		@Función: wp_carousel()
		@Versión: 2.2
		@Parámetros:
								$id: ID del carrusel a mostrar.
								$mode (show | get | array | carousel_ei): Dependiento del valor, muestra el carrusel (show), lo devuelve (get) o devuelve una matriz con su contenido (array).
		@Descripción: Muestra el carrusel con ID $id.
		@Añadida en la versión: 0.1		
	*/
	
	function wp_carousel($id, $mode='show')
	{
		
		global $wp_carousel_path;
		
		/*
			Cargamos los extras
		*/
			
		if ($mode == 'carousel_ei')
		{
			wp_carousel_load_extras(false, '../../../');
		}
		else
		{
			wp_carousel_load_extras(false);
		}
			
		$items = maybe_unserialize(get_option('wp_carousel'));
		$items = $items[$id];
		
		$config = maybe_unserialize(get_option('wp_carousel_config'));
		$config = $config[$id];
				
		if ($config['SHOW_ARROWS'] != '0' && $config['SLIDE_POSTS'] > 0)
		{
			$config['ARROWS'] = true;
		}
		else
		{
			$config['ARROWS'] = false;
		}
		
		if ($config['ENABLE_PAGINATION'] == '0')
		{
			$config['ENABLE_PAGINATION'] = false;
		}
		else
		{
			$config['ENABLE_PAGINATION'] = true;
		}
		
		if (isset($config['IMG_WIDTH']))
		{
			if ($config['IMG_WIDTH'] != '')
			{
				$config['HAS_IMG_WIDTH'] = true;
			}
			else
			{
				$config['HAS_IMG_WIDTH'] = false;
			}
		}
		else
		{
			$config['HAS_IMG_WIDTH'] = false;
		}
		
		if (isset($config['IMG_HEIGHT']))
		{
			if ($config['IMG_HEIGHT'] != '')
			{
				$config['HAS_IMG_HEIGHT'] = true;
			}
			else
			{
				$config['HAS_IMG_HEIGHT'] = false;
			}
		}
		else
		{
			$config['HAS_IMG_HEIGHT'] = false;
		}
		
		if (isset($config['PANEL_WIDTH']))
		{
			if ($config['PANEL_WIDTH'] != '')
			{
				$config['HAS_PANEL_WIDTH'] = true;
			}
			else
			{
				$config['HAS_PANEL_WIDTH'] = false;
			}
		}
		else
		{
			$config['HAS_PANEL_WIDTH'] = false;
		}
		
		if (count($items) > 0)
		{
			
			$items = wp_carousel_adapt_items($items);
			
			$delete_posts = array();		
			foreach ($items as $key => $value)
			{	
				if ($value['TYPE'] == 2 && !$value['SHOW']) $delete_posts[] = $value['ID'];	
			}
			
			/* Alteramos la consulta a la DB y eliminamos los artículos que queremos ocultar */
			global $wp_query;
			if (is_home()) {
				$wp_query->query_vars['post__not_in'] = $delete_posts;
				$wp_query->query($wp_query->query_vars);
			}
			/* Ya están ocultos */
			
			switch ($mode)
			{
				case 'array':
				case 'carousel_ei':
					$return = array(
						'ITEMS' => $items,
						'CONFIG' => $config,
						'ID' => $id
					);
					$c_id = $id;
					eval('if (!function_exists("wp_carousel_load_carousel_'.$c_id.'_js_code")) { function wp_carousel_load_carousel_'.$c_id.'_js_code() { wp_carousel_load_carousel_js('.$c_id.'); } }');
					add_action('wp_footer', 'wp_carousel_load_carousel_'.$c_id.'_js_code');
					return $return;
					break;
				case 'show':
					$c_id = $id;
					unset($id);
					require('themes/'.$config['THEME'].'/theme.php');
					eval('if (!function_exists("wp_carousel_load_carousel_'.$c_id.'_js_code")) { function wp_carousel_load_carousel_'.$c_id.'_js_code() { wp_carousel_load_carousel_js('.$c_id.'); } }');
					add_action('wp_footer', 'wp_carousel_load_carousel_'.$c_id.'_js_code');
					break;
				case 'get':
					ob_start();
					$c_id = $id;
					unset($id);
					require('themes/'.$config['THEME'].'/theme.php');
					$out = ob_get_contents();
					ob_end_clean();
					eval('if (!function_exists("wp_carousel_load_carousel_'.$c_id.'_js_code")) { function wp_carousel_load_carousel_'.$c_id.'_js_code() { wp_carousel_load_carousel_js('.$c_id.'); } }');
					add_action('wp_footer', 'wp_carousel_load_carousel_'.$c_id.'_js_code');
					return $out;
					break;
				default:
					break;
			}
		
		}
	
	}
	
	/*
		@Función: wp_carousel_load_theme_css()
		@Versión: 1.0
		@Descripción: Carga el CSS de todos los carruseles.
		@Añadida en la versión: 0.4
	*/
	
	function wp_carousel_load_theme_css()
	{	
		global $wp_carousel_path;
		$config = maybe_unserialize(get_option('wp_carousel_config'));
		
		$loaded_css = array();
		
		if (is_array($config))
		{
			foreach ($config as $config_key => $config_value)
			{
				require('themes/'.$config_value['THEME'].'/index.php');
								
				if (isset($theme['css']))
				{
					if (is_array($theme['css']))
					{		
						foreach ($theme['css'] as $key => $value)
						{
							if (!in_array($wp_carousel_path[6].'themes/'.$config_value['THEME'].'/'.$value, $loaded_css))
							echo '<link rel="stylesheet" href="'.$wp_carousel_path[6].'themes/'.$config_value['THEME'].'/'.$value.'" type="text/css" media="all" />';
							$loaded_css[] = $wp_carousel_path[6].'themes/'.$config_value['THEME'].'/'.$value;
						}		
					}
				}
					
			}
		}
	}
	
	/*
		@Función: wp_carousel_load_carousel_js()
		@Versión: 2.0
		@Parámetros:
								$id: ID del carrusel del cual cargaremos su código JS.
		@Descripción: Se carga el código JS del carrusel con ID: $id
		@Añadida en la versión: 0.4	
		@Actualizada en la versión: 0.5
	*/
	
	function wp_carousel_load_carousel_js($id)
	{
		$config = unserialize(get_option('wp_carousel_config'));
		if (isset($config[$id]))
		{
			$value = $config[$id];
			?>
			<script type="text/javascript">
			stepcarousel.setup({
				galleryid: 'carousel_<?php echo $id; ?>', //id of carousel DIV
				beltclass: 'belt', //class of inner "belt" DIV containing all the panel DIVs
				panelclass: 'panel', //class of panel DIVs each holding content
				autostep: {enable:<?php if ($value['AUTOSLIDE_TIME'] != '0' && $value['AUTOSLIDE_POSTS'] != '0') { echo 'true, moveby:'.$value['AUTOSLIDE_POSTS'].', pause:'.$value['AUTOSLIDE_TIME']; } else { echo 'false'; } ?>},
				panelbehavior: {speed:500, wraparound:<?php if (isset($value['LOOP_MODE'])) { if ($value['LOOP_MODE'] == '0') { echo 'false'; } else { echo 'true'; } } ?>, persist:true},
				defaultbuttons: {enable: false, moveby: 1, leftnav: ['http://i34.tinypic.com/317e0s5.gif', -5, 80], rightnav: ['http://i38.tinypic.com/33o7di8.gif', -20, 80]},
				statusvars: ['statusA', 'statusB', 'statusC'], //register 3 variables that contain current panel (start), current panel (last), and total panels
				contenttype: ['inline'] //content setting ['inline'] or ['ajax', 'path_to_external_file']
			})
			</script>
			<?php 
		}
	}
	
	/*
		@Función: wp_carousel_adapt_items()
		@Versión: 2.0
		@Parámetros:
								$items: Matriz de elementos del carrusel
		@Descripción: Prepara la matriz para que se contenga toda la información necesaria, obtenida de diferentes funciones
		@Añadida en la versión: 0.4
		@Última actualización en la versión: 0.5
	*/
	
	function wp_carousel_adapt_items($items) {
		foreach ($items as $key => $value)
		{
			$items_adapted[$key] = $value;
			if ($items_adapted[$key]['TYPE'] != 1 && $items_adapted[$key]['TYPE'] != 4 && $items_adapted[$key]['TYPE'] != 5 && $items_adapted[$key]['TYPE'] != 6 && $items_adapted[$key]['TYPE'] != 7)
			{
				if (!isset($items_adapted[$key]['TITLE']))
				{
					$items_adapted[$key]['TITLE'] = wp_carousel_item_value($items_adapted[$key]['ID'], $items_adapted[$key]['TYPE'], 'name');
				}
				if (!isset($items_adapted[$key]['DESC']))
				{
					$items_adapted[$key]['DESC'] = wp_carousel_item_value($items_adapted[$key]['ID'], $items_adapted[$key]['TYPE'], 'desc');
				}
				if (!isset($items_adapted[$key]['IMAGE_URL']))
				{
					$items_adapted[$key]['IMAGE_URL'] = wp_carousel_item_value($items_adapted[$key]['ID'], $items_adapted[$key]['TYPE'], 'image_url');
				}
				if (!isset($items_adapted[$key]['LINK_URL']))
				{
					$items_adapted[$key]['LINK_URL'] = wp_carousel_item_value($items_adapted[$key]['ID'], $items_adapted[$key]['TYPE'], 'link_url');
				}
			}
			elseif ($items_adapted[$key]['TYPE'] == 4)
			{
				$items_adapted[$key] = $value;
			}
			elseif ($items_adapted[$key]['TYPE'] == 7)
			{
				if (WP_CAROUSEL_EI)
				{
					unset ($items_adapted[$key]);
					$temp_item_content = maybe_unserialize(base64_decode(file_get_contents($value['WP_CAROUSEL_EI_URL'].'?carousel_id='.$value['WP_CAROUSEL_EI_ID'])));
					foreach ($temp_item_content as $temp_key => $temp_value)
					{
						$items_adapted[$value['ORDER'].'_'.$temp_key] = $temp_value;
					}
				}
				else
				{
					unset ($items_adapted[$key]);
				}
			}
			elseif ($items_adapted[$key]['TYPE'] == 5)
			{
				if (isset($items_adapted[$key]['POSTS_NUMBER']))
				{
					if (is_numeric($items_adapted[$key]['POSTS_NUMBER']) && ($items_adapted[$key]['POSTS_NUMBER'] != '0'))
					{
					}
					else 
					{
						$items_adapted[$key]['POSTS_NUMBER'] = '10';
					}
				}
				else
				{
					$items_adapted[$key]['POSTS_NUMBER'] = '10';
				}
				
				if (isset($items_adapted[$key]['POSTS_ORDER']))
				{
					if ($items_adapted[$key]['POSTS_ORDER'] == 'first_old')
					{
						$temp_query_order = 'asc';
					}
					else
					{
						$temp_query_order = 'desc';
					}
				}
				else
				{
					$items_adapted[$key]['POSTS_ORDER'] = 'first_new';
					$temp_query_order = 'desc';
				}
				
				
				$temp_tag_info = get_term_by('id', $items_adapted[$key]['ID'], 'post_tag');
				$temp_query = new WP_Query('tag='.$temp_tag_info->slug.'&showposts='.$items_adapted[$key]['POSTS_NUMBER'].'&orderby=date&order='.$temp_query_order);
				
				while ($temp_query->have_posts())
				{
					$temp_query->the_post();
					$items_temp_adapted[$items_adapted[$key]['ORDER'].'_'.get_the_ID().'_2'] = array(
						'ID' => get_the_ID(),
						'TYPE' => 2,
						'ORDER' => $items_adapted[$key]['ORDER'],
						'SHOW' => $items_adapted[$key]['SHOW'],
						'TITLE' => wp_carousel_item_value(get_the_ID(), 2, 'name'),
						'DESC' => wp_carousel_item_value(get_the_ID(), 2, 'desc'),
						'IMAGE_URL' => wp_carousel_item_value(get_the_ID(), 2, 'image_url'),
						'LINK_URL' => wp_carousel_item_value(get_the_ID(), 2, 'link_url')
					);
				}
				
				if ($items_adapted[$key]['POSTS_ORDER'] == 'first_new') // Primero van los nuevos artículos, así que... ¡A ordenar se ha dicho!
				{
					$temp_max_id = 0;
					foreach ($items_temp_adapted as $temp_key => $temp_value)
					{
						$temp_array_key = explode('_', $temp_key);
						if ($temp_max_id < $temp_array_key[1]) // Calculamos la ID más alta
						{
							$temp_max_id = $temp_array_key[1];
						}
					}
					if ($temp_max_id > 0) // Verificamos que no haya errores
					{
						foreach ($items_temp_adapted as $old_temp_key => $temp_value)
						{
							$old_temp_array_key = explode('_', $old_temp_key);
							$new_temp_key = $temp_max_id - $old_temp_array_key[1];
							$items_adapted[$old_temp_array_key[0].'_'.$new_temp_key.'_'.$old_temp_array_key[2]] = $temp_value;
						}
					}
					else // Error, omitimos el proceso de ordenado
					{
						foreach ($items_temp_adapted as $temp_key => $temp_value)
						{
							$items_adapted[$temp_key] = $temp_value;
						}
					}
				}
				else // No es necesario ordenar, la matriz ya está lista
				{
					foreach ($items_temp_adapted as $temp_key => $temp_value)
						{
							$items_adapted[$temp_key] = $temp_value;
						}
				}
				
				unset($items_temp_adapted);
				unset($items_adapted[$key]);
				
			}
			elseif ($items_adapted[$key]['TYPE'] == 6)
			{
				
				if (isset($items_adapted[$key]['POSTS_NUMBER']))
				{
					if (is_numeric($items_adapted[$key]['POSTS_NUMBER']) && ($items_adapted[$key]['POSTS_NUMBER'] != '0'))
					{
					}
					else 
					{
						$items_adapted[$key]['POSTS_NUMBER'] = '10';
					}
				}
				else
				{
					$items_adapted[$key]['POSTS_NUMBER'] = '10';
				}
			
				if (isset($items_adapted[$key]['POSTS_ORDER']))
				{
					if ($items_adapted[$key]['POSTS_ORDER'] == 'first_old')
					{
						$temp_query_order = 'asc';
					}
					else
					{
						$temp_query_order = 'desc';
					}
				}
				else
				{
					$items_adapted[$key]['POSTS_ORDER'] = 'first_new';
					$temp_query_order = 'desc';
				}
			
				$temp_query = new WP_Query('author='.$items_adapted[$key]['ID'].'&showposts='.$items_adapted[$key]['POSTS_NUMBER'].'&orderby=date&order='.$temp_query_order);
				
				while ($temp_query->have_posts())
				{
					$temp_query->the_post();
					$items_temp_adapted[$items_adapted[$key]['ORDER'].'_'.get_the_ID().'_2'] = array(
						'ID' => get_the_ID(),
						'TYPE' => 2,
						'ORDER' => $items_adapted[$key]['ORDER'],
						'SHOW' => $items_adapted[$key]['SHOW'],
						'TITLE' => wp_carousel_item_value(get_the_ID(), 2, 'name'),
						'DESC' => wp_carousel_item_value(get_the_ID(), 2, 'desc'),
						'IMAGE_URL' => wp_carousel_item_value(get_the_ID(), 2, 'image_url'),
						'LINK_URL' => wp_carousel_item_value(get_the_ID(), 2, 'link_url')
					);
				}
				
				if ($items_adapted[$key]['POSTS_ORDER'] == 'first_new') // Primero van los nuevos artículos, así que... ¡A ordenar se ha dicho!
				{
					$temp_max_id = 0;
					foreach ($items_temp_adapted as $temp_key => $temp_value)
					{
						$temp_array_key = explode('_', $temp_key);
						if ($temp_max_id < $temp_array_key[1]) // Calculamos la ID más alta
						{
							$temp_max_id = $temp_array_key[1];
						}
					}
					if ($temp_max_id > 0) // Verificamos que no haya errores
					{
						foreach ($items_temp_adapted as $old_temp_key => $temp_value)
						{
							$old_temp_array_key = explode('_', $old_temp_key);
							$new_temp_key = $temp_max_id - $old_temp_array_key[1];
							$items_adapted[$old_temp_array_key[0].'_'.$new_temp_key.'_'.$old_temp_array_key[2]] = $temp_value;
						}
					}
					else // Error, omitimos el proceso de ordenado
					{
						foreach ($items_temp_adapted as $temp_key => $temp_value)
						{
							$items_adapted[$temp_key] = $temp_value;
						}
					}
				}
				else // No es necesario ordenar, la matriz ya está lista
				{
					foreach ($items_temp_adapted as $temp_key => $temp_value)
						{
							$items_adapted[$temp_key] = $temp_value;
						}
				}
				
				unset($items_temp_adapted);
				unset($items_adapted[$key]);
				
			}
			else
			{	
				if (isset($items_adapted[$key]['POSTS_NUMBER']))
				{
					if (is_numeric($items_adapted[$key]['POSTS_NUMBER']) && ($items_adapted[$key]['POSTS_NUMBER'] != '0'))
					{
					}
					else 
					{
						$items_adapted[$key]['POSTS_NUMBER'] = '10';
					}
				}
				else
				{
					$items_adapted[$key]['POSTS_NUMBER'] = '10';
				}
			
				if (isset($items_adapted[$key]['POSTS_ORDER']))
				{
					if ($items_adapted[$key]['POSTS_ORDER'] == 'first_old')
					{
						$temp_query_order = 'asc';
					}
					else
					{
						$temp_query_order = 'desc';
					}
				}
				else
				{
					$items_adapted[$key]['POSTS_ORDER'] = 'first_new';
					$temp_query_order = 'desc';
				}
			
				$temp_query = new WP_Query('cat='.$items_adapted[$key]['ID'].'&showposts='.$items_adapted[$key]['POSTS_NUMBER'].'&orderby=date&order='.$temp_query_order);
				
				while ($temp_query->have_posts())
				{
					$temp_query->the_post();
					$items_temp_adapted[$items_adapted[$key]['ORDER'].'_'.get_the_ID().'_2'] = array(
						'ID' => get_the_ID(),
						'TYPE' => 2,
						'ORDER' => $items_adapted[$key]['ORDER'],
						'SHOW' => $items_adapted[$key]['SHOW'],
						'TITLE' => wp_carousel_item_value(get_the_ID(), 2, 'name'),
						'DESC' => wp_carousel_item_value(get_the_ID(), 2, 'desc'),
						'IMAGE_URL' => wp_carousel_item_value(get_the_ID(), 2, 'image_url'),
						'LINK_URL' => wp_carousel_item_value(get_the_ID(), 2, 'link_url')
					);
				}
				
				if ($items_adapted[$key]['POSTS_ORDER'] == 'first_new') // Primero van los nuevos artículos, así que... ¡A ordenar se ha dicho!
				{
					$temp_max_id = 0;
					foreach ($items_temp_adapted as $temp_key => $temp_value)
					{
						$temp_array_key = explode('_', $temp_key);
						if ($temp_max_id < $temp_array_key[1]) // Calculamos la ID más alta
						{
							$temp_max_id = $temp_array_key[1];
						}
					}
					if ($temp_max_id > 0) // Verificamos que no haya errores
					{
						foreach ($items_temp_adapted as $old_temp_key => $temp_value)
						{
							$old_temp_array_key = explode('_', $old_temp_key);
							$new_temp_key = $temp_max_id - $old_temp_array_key[1];
							$items_adapted[$old_temp_array_key[0].'_'.$new_temp_key.'_'.$old_temp_array_key[2]] = $temp_value;
						}
					}
					else // Error, omitimos el proceso de ordenado
					{
						foreach ($items_temp_adapted as $temp_key => $temp_value)
						{
							$items_adapted[$temp_key] = $temp_value;
						}
					}
				}
				else // No es necesario ordenar, la matriz ya está lista
				{
					foreach ($items_temp_adapted as $temp_key => $temp_value)
						{
							$items_adapted[$temp_key] = $temp_value;
						}
				}
				
				unset($items_temp_adapted);
				unset($items_adapted[$key]);
				
			}
			
		}
		uksort($items_adapted, 'wp_carousel_compare_items_keys');		// Nuevo método de ordenado
		//ksort($items_adapted, SORT_NUMERIC); 								// Método antiguo de ordenado
		return $items_adapted;
	}
	
	/*
		@Función: wp_carousel_compare_items_keys()
		@Versión: 1.0
		@Parámetros:
								$a: Primer índice
								$b: Segundo índice
		@Descripción: Compara dos índices de matrices de elementos. Si el primer índice es menor que el segundo, devuelve -1, si es mayor, 1 y si es igual, 0
		@Nota: Sólo la usa la función wp_carousel_adapt_items(), y no debe usarse en ningún otro caso 
		@Añadida en la versión: 0.4.0.11	
	*/
	
	function wp_carousel_compare_items_keys($a, $b)
	{
		$a_exploded = explode('_', $a);
		$b_exploded = explode('_', $b);
		
		if ($a_exploded[0] < $b_exploded[0]) // Comparamos órdenes
		{
			// El orden es menor, así que primero va B
			return -1;
		}
		elseif ($a_exploded[0] > $b_exploded[0])
		{
			// El orden es mayor, así que primera va A
			return 1;
		}
		else {
			// El orden es el mismo, así que analizamos la ID
			if ($a_exploded[1] < $a_exploded[1])
			{
				// La ID es menor, así que primero va B
				return -1;
			}
			elseif ($a_exploded[1] > $b_exploded[1])
			{
				// La es mayor, así que primero va A
				return 1;
			}
			else
			{
				// Las IDs son iguales, así que analizamos el tipo de contenido
				if ($a_exploded[2] < $b_exploded[2])
				{
					// El tipo de contenido es menor, así que primero va B
					return -1;
				}
				elseif ($a_exploded[2] > $b_exploded[2])
				{
					// El tipo de contenido es mayor, así que primero va A
					return 1;
				}
				else {
					// POSIBLE ERROR: TODO ES IGUAL, así que devolvemos 0
					return 0;
				}
			}
		}
		
	}
	
	/*
		@Función: wp_carousel_adminmenu_links()
		@Versión: 2.1
		@Descripción: Añadimos las páginas de opciones de WP Carousel al menú de WordPress.
		@Añadida en la versión: 0.4		
		@Actualizada en la versión: 0.5.3
	*/
	
	function wp_carousel_adminmenu_links()
	{
		global $wp_carousel_path, $import_name;
		
		// Por un error con Poedit muevo unas frases aquí
		$temp_import_name = __(
		'Import', 
		'wp_carousel');
		$temp_uninstall_name = __(
		'Uninstall', 
		'wp_carousel');
		$temp_add_name = __(
		'Add', 
		'wp_carousel');
		
		$items = maybe_unserialize(get_option('wp_carousel'));
				
		$wp_carousel_temp_hook = add_object_page('WP Carousel', 'WP Carousel', 'administrator', 'wp-carousel', 'wp_carousel_options_page', $wp_carousel_path[6].'img/wp_carousel.png');
		add_action('load-'.$wp_carousel_temp_hook, 'wp_carousel_custom_help_tab'); // Modificamos la pestaña de ayuda
		
		//add_submenu_page('wp-carousel', 'WP Carousel', 8, 'edit-carousel-1', 'wp_carousel_carousel_options_page', $wp_carousel_path[6].'img/wp_carousel.png');
				
		if (is_array($items))
		{
			foreach ($items as $key => $value)
			{
				$wp_carousel_temp_hook = add_submenu_page('wp-carousel', __('Carousel', 'wp_carousel').' '.$key, __('Carousel', 'wp_carousel').' '.$key, 'administrator', 'edit-carousel-'.$key, 'wp_carousel_carousel_options_page');
				add_action('load-'.$wp_carousel_temp_hook, 'wp_carousel_custom_help_tab'); // Modificamos la pestaña de ayuda
			}
		}
				
		$wp_carousel_temp_hook = add_submenu_page('wp-carousel', __('Export', 'wp_carousel'), __('Export', 'wp_carousel'), 'administrator', 'wp-carousel-export', 'wp_carousel_export_page');
		add_action('load-'.$wp_carousel_temp_hook, 'wp_carousel_custom_help_tab'); // Modificamos la pestaña de ayuda
		
		$wp_carousel_temp_hook = add_submenu_page('wp-carousel', $temp_import_name, $temp_import_name, 'administrator', 'wp-carousel-import', 'wp_carousel_import_page');
		add_action('load-'.$wp_carousel_temp_hook, 'wp_carousel_custom_help_tab'); // Modificamos la pestaña de ayuda
		
		$wp_carousel_temp_hook = add_submenu_page('wp-carousel', $temp_uninstall_name, $temp_uninstall_name, 'administrator', 'wp-carousel-uninstall', 'wp_carousel_uninstall_page');
		add_action('load-'.$wp_carousel_temp_hook, 'wp_carousel_custom_help_tab'); // Modificamos la pestaña de ayuda
		
		$wp_carousel_temp_hook = add_submenu_page('wp-carousel', $temp_add_name, $temp_add_name, 'administrator', 'wp-carousel-add-theme', 'wp_carousel_add_carousel_page');
		add_action('load-'.$wp_carousel_temp_hook, 'wp_carousel_custom_help_tab'); // Modificamos la pestaña de ayuda
		
	}
	
	/*
		@Función: wp_carousel_options_page()
		@Versión: 2.0
		@Parámetros:
								$var: Almacena datos enviados por WordPress, así se evita un problema con la variable $debug
								$debug (bool): Determina si al acabar de ejecutar la función se debe mostrar el registro o no
		@Descripción: Crea la página principal de WP Carousel.
		@Añadida en la versión: 0.4	
		@Actualizada en la versión: 0.5
	*/
	
	function wp_carousel_options_page($var='', $debug=false)
	{
		// Cargamos la ID del usuario: las configuraciones de esta página se almacenan como metadatos de los usuarios, no en la tabla de WP Carousel
		global $user_ID, $wp_carousel_path;
		
		/*
			Cargamos los extras
		*/
			
		wp_carousel_load_extras($debug);
		
		// Antes de nada, veamos si estamos ejecutando la función en modo debug
		
		/*
		
			Vale, os podéis preguntar por que he creado la matriz $log, bien, yo mismo me lo he preguntado hace unos segundos (y me lo volveré a preguntar dentro de otros pocos más), y sinceramente, no sabría dar una justificación que satisfaga a todos.
			
			Por un lado, para mí, como desarrollador del plugin, me es muy útil saber qué ha ocurrido exactamente cuando hay un error y muchas veces los errores se reportan a medias. Si además de reportar el error como de costumbre, se adjunta la matriz $log, yo puedo ver exactamente qué ha estado pasando y los resultados de comprobaciones internas que el usuario medio desconoce que existen.
			
			Por otro lado, puede serles de utilidad a aquellos que quieran conocer mejor qué hace en cada momento el plugin, por eso trato de hacer que el registro (log) almacene cada acción del plugin.
			
			Por otro lado, le da un toque geek al plugin, puede que sea una tontería pero seguro que a más de uno le hace gracia ver el resultado del registro :) .
			
			Finalmente, y aunque esto no justifique nada, el consumo de CPU del registro no debería ser elevado, de hecho supongo que será casi inadvertible. Sin embargo, si queréis eliminar el registro, adelante, no pasa nada.
			
			PD: No se almacena ninguna información personal en el registro, como IDs de usuario, nombres de tablas de la Base de Datos, etc
			
		*/
		
		$log[] = 'Comprobacion del valor de $debug en wp_carousel_options_page()';
		if (!$debug)
		{
			$log[] = 'La comprobacion ha determinado que $debug no tiene por valor true (booleano)';
			// De momento sabemos que no estamos en modo debug, pero ¿será porque la variable es false o porque la variable no es true?
			if (is_bool($debug)) 
			{
				// Vale, todo va bien de momento, no seamos paranóicos
				$log[] = 'La comprobacion ha determinado que $debug tiene por valor false (booleano)';
			}
			else
			{
				// Vaya, parece que no íbamos desencaminados: la variable $debug tiene un valor no booleano, así que estamos ante un error. De momento almacenamos el error y ponemos esta función en modo debug, a ver si encontramos el origen del (o los) problemas
				$log[] = 'La comprobacion ha determinado que $debug tiene por valor: "'.$debug.'" (no booleano)';
				$errors[] = 'La variable $debug tiene un valor no booleano, de hecho su valor es: "'.$debug.'".';
				$log[] = 'Se ha añadido a la matriz de errores el error '.key($errors).': "'.current($errors).'"';
			}
		}
		
		$items = get_option('wp_carousel');
		
		$will['SHOW_INFO_TABLE'] = true;
		$will['SHOW_UPDATE_MESSAGE'] = false;
		
		$log[] = 'Se ha cargado la matriz de contenido desde la Base de Datos';
		$items = maybe_unserialize($items);
		$log[] = 'Se ha dessearializado el contenido extraido de la Base de Datos';
		$count = count($items);
		$log[] = 'El recuento de indices de la matriz $items es de '.$count.', lo cual indica que hay '.$count.' carruseles';
		
		/*
		$log[] = 'Comienza el analisis del posible envio del formulario';
		if (isset($_POST['ui']))
		{
			$log[] = 'El formulario ha sido enviado, comienza el procesado del mismo';
			
			if ($_POST['ui'] == 'classic' || $_POST['ui'] == 'drag_drop')
			{
				update_user_meta($user_ID, 'wp_carousel_ui', $_POST['ui']);
			}
			else
			{
				$log[] = 'El valor de la variable $_POST[\'ui\'] no esta entre los admitidos, de hecho es: '.$_POST['ui'];
				$errors[] = 'El valor de la variable $_POST[\'ui\'] no esta entre los admitidos, de hecho es: '.$_POST['ui'];
				$log[] = 'Se ha añadido a la matriz de errores el error '.key($errors).': "'.current($errors).'"';
				update_user_meta($user_ID, 'wp_carousel_ui', 'drag_drop');
			}
			
			$will['SHOW_UPDATE_MESSAGE'] = true;
			
		}
		else
		{
			$log[] = 'El analisis indica que la variable $_POST[\'ui\'] no esta establecida: no se ha enviado el formulario';
		}
		$log[] = 'Han finalizado las tareas relativas al procesado del formulario';
		*/
		
		if ($will['SHOW_UPDATE_MESSAGE'])
		{
			?>
			<div class="updated fade"><p><?php _e('The new config has been saved, please, reload this page to see changes.', 'wp_carousel'); ?></p></div>
			<?php
		}
		?>
		<div class="wrap">
			<div id="icon-options-general" class="icon32"><br></div>
			<h2>WP Carousel</h2>
						
			<?php
				$log[] = 'Comienza la comprobación de la variable $_GET[\'action\']';
				if (isset($_GET['action']))
				{
					$log[] = 'La variable $_GET[\'action\'] existe, se procede a su separacion en fragmentos a partir del serparador: ":"';
					$action = explode(':', $_GET['action']);
					$log[] = 'La variable $_GET[\'action\'] ha sido separada y sus fragmentos almacenados en la matriz $action';
					$log[] = 'Ha comenzado el analisis de la variable $action[0]';
					switch ($action[0])
					{
						case 'DELETE_CAROUSEL':
							if (isset($_GET['sure']))
							{
								if ($_GET['sure'] == 'yes')
								{
									// Este código es el que elimina el carrusel, pero lo he movido fuera de la función, así que no es necesario. De todos modos lo dejo aquí aunque comentado.
									/*
									unset($items[$action[1]]);
									$items_db = serialize($items);
									update_option('wp_carousel', $items_db);
									*/
								}
							}
							else
							{
								// Mostramos el aviso
								printf(__('<p>Do you really want to delete the carousel with ID "%s"? That can\'t be undone.</p>', 'wp_carousel'), $action[1]);
								printf(__('<p>Click <a href="%s">here</a> to delete the carousel or click <a href="%s">here</a> to return to the carousel\'s options page</p>', 'wp_carousel'), wp_carousel_create_internal_urls('SELF_URL').'&sure=yes', wp_carousel_create_internal_urls('SELF_URL:DELETE_ALL_URL_VARIABLES').'?page=edit-carousel-'.$action[1]);
								$will['SHOW_INFO_TABLE'] = false;
							}
							break;
						default:
							$log[] = 'La accion de la variable $action[0] no esta contemplada en la lista de acciones';
							$errors[] = 'La accion de la variable $action[0] no esta contemplada en la lista de acciones';
							$log[] = 'Se ha añadido a la matriz de errores el error '.key($errors).': "'.current($errors).'"';
							break;
					}
				}
				else
				{
					$log[] = 'La variable $_GET[\'action\'] no existe, no se ejecutara ninguna accion';
				}
				
				if ($will['SHOW_INFO_TABLE'])
				{
					$log[] = 'Comienza a mostrarse la tabla de informacion general';
			?>
			<table class="widefat post fixed" cellspacing="0">
			
				<thead>
					<tr>
						<th scope="col" id="name" class="manage-column column-name"><?php echo _e('Name', 'wp_carousel'); ?></th>
						<th scope="col" id="value" class="manage-column column-value"><?php echo _e('Value', 'wp_carousel'); ?></th>
					</tr>
				</thead>

				<tfoot>
					<tr>
						<th scope="col" class="manage-column column-name"><?php echo _e('Name', 'wp_carousel'); ?></th>
						<th scope="col" class="manage-column column-value"><?php echo _e('Value', 'wp_carousel'); ?></th>
					</tr>
				</tfoot>

				<tbody>
				
					<tr id="item-1" valign="top">
						<td class="item-name column-name"><strong><?php echo _e('Number of carousels', 'wp_carousel'); ?></strong></td>
						<td class="item-value column-value"><?php echo $count; ?></td>
					</tr>
					<tr id="item-2" class="alternate" valign="top">
						<td class="item-name column-name"><strong><?php echo _e('Language', 'wp_carousel'); ?></strong></td>
						<td class="item-value column-value"><?php echo get_locale(); ?></td>
					</tr>
					<tr id="item-3" valign="top">
						<td class="item-name column-name"><strong><?php echo _e('External Integration', 'wp_carousel'); ?></strong></td>
						<td class="item-value column-value"><?php if(WP_CAROUSEL_EI == true) { _e('Enabled', 'wp_carousel'); echo ' ('.$wp_carousel_path[6].'wp-carousel-ei.php)'; } else _e('Disabled'); ?></td>
					</tr>
					<tr id="item-4" class="alternate" valign="top">
						<td class="item-name column-name"><strong><?php echo _e('Version', 'wp_carousel'); ?></strong></td>
						<td class="item-value column-value"><?php echo WP_CAROUSEL_VERSION; ?></td>
					</tr>
					<tr id="item-5" valign="top">
						<td class="item-name column-name"><strong><?php echo _e('Extras', 'wp_carousel'); ?></strong></td>
						<td class="item-value column-value">
							<ul>
								<?php
								foreach ($_SESSION['WP_CAROUSEL_EXTRAS'] as $key => $value)
								{
									echo '<li><a href="'.$value['url'].'">'.$value['name'].'</a> '.$value['version'].' '.__('by', 'wp_carousel').' <a href="'.$value['author_url'].'">'.$value['author'].'</a> ('.$value['desc'].')</li>';
								}
								?>
							</ul>
						</td>
					</tr>
					

				</tbody>
				
			</table>
			
			<?php
					$log[] = 'Se ha mostrado la tabla de informacion general';
				}
			?>
						
		</div>
		
		<div class="clear"></div>

		<?php
		
		if ($debug)
		{
			$log[] = 'Comprobacion de recuento de errores de la funcion wp_carousel_options_page()';
			if(!empty($errors))
			{
				// Uy uy uy... ha habido errores durante la ejecución de esta función, cortemos el script y mostremos los errores de forma legible
				$log[] = 'La comprobacion del recuento de errores de la funcion wp_carousel_options_page() ha detectado que hay errores en la funcion wp_carousel_options_page()';
				echo '<h2>'.__('Errors', 'wp_carousel').'</h2><pre>';
				print_r($errors);
				echo '</pre>';
				$log[] = 'Se ha mostrado el listado de errores de la funcion wp_carousel_options_page()';
				
				echo '<h2>'.__('Log', 'wp_carousel').'</h2><pre>';
				print_r($log);
				echo '</pre>';
				
				// Avisemos de que cortamos el script
				echo '<p>El script se deja de ejecutar a partir de ahora debido a que se han detectado errores durante su ejecución</p>';
				exit; // Cortamos el script
			} else {
				// ¡Qué bien, no hay errores!
				$log[] = 'La comprobacion del recuento de errores de la funcion wp_carousel_options_page() ha determinado que no ha habido errores durante la ejecucion de wp_carousel_options_page()';
				echo '<h2>'.__('Log', 'wp_carousel').'</h2><pre>';
				print_r($log);
				echo '</pre>';
			}
		}
		
	}
	
	/*
		@Función: wp_carousel_carousel_options_page()
		@Versión: 2.1
		@Parámetros:
								$var: Almacena datos enviados por WordPress, así se evita un problema con la variable $debug
								$debug (bool): Determina si al acabar de ejecutar la función se debe mostrar el registro o no
		@Descripción: Crea la página de opciones de cada carrusel, donde también se añadirán contenidos y se eliminarán. Toma el valor de $_GET['action'] para detectar qué acción debe realizar.
		@Añadida en la versión: 0.4		
		@Última actualización en la versión: 0.5.2
	*/
	
	/*
		Valores de $_GET['action']
			
			__ACTION__:__PARAMETRO__ -> Sintaxis: ACCION:PARAMETRO. En esta lista aparecen todas las acciones y los parámetros que acepta la función. Ojo, sólo acepta un parámetro.
						
			REMOVE:__INTERNAL_ID__ -> Elimina el contenido con la ID INTERNA __INTERNAL_ID__ del carrusel actual.
			
			ADD -> Añade contenido al carrusel actual, el contenido que añadirá lo tomará de $_POST.
			
			
	*/
	
	function wp_carousel_carousel_options_page($var='', $debug=false)
	{
		// Cargamos la ID del usuario, que usaremos para mostrar una u otra interfaz y las rutas
		global $user_ID, $wp_carousel_path;
		
		$wp_carousel_no_ajax_mode = false; // Damos por hecho que el modo AJAX funciona
		
		/* Comprobamos si podemos cargar el archivo */
		
		if (!is_readable('../wp-blog-header.php'))
		{
			$wp_carousel_no_ajax_mode = true;
		}
		
		/*
			Cargamos los extras
		*/
			
		wp_carousel_load_extras($debug);
		
		// Antes de nada, veamos si estamos ejecutando la función en modo debug
		
		$log[] = 'Comprobacion del valor de $debug en wp_carousel_carousel_options_page()';
		if (!$debug)
		{
			$log[] = 'La comprobacion ha determinado que $debug no tiene por valor true (booleano)';
			// De momento sabemos que no estamos en modo debug, pero ¿será porque la variable es false o porque la variable no es true?
			if (is_bool($debug)) 
			{
				// Vale, todo va bien de momento, no seamos paranóicos
				$log[] = 'La comprobacion ha determinado que $debug tiene por valor false (booleano)';
			}
			else
			{
				// Vaya, parece que no íbamos desencaminados: la variable $debug tiene un valor no booleano, así que estamos ante un error. De momento almacenamos el error y ponemos esta función en modo debug, a ver si encontramos el origen del (o los) problemas
				$log[] = 'La comprobacion ha determinado que $debug tiene por valor: "'.$debug.'" (no booleano)';
				$errors[] = 'La variable $debug tiene un valor no booleano, de hecho su valor es: "'.$debug.'".';
				$log[] = 'Se ha añadido a la matriz de errores el error '.key($errors).': "'.current($errors).'"';
			}
		}
		
		// Establecemos $can['UNDO'] en false, ya que no podemos deshacer nada (de momento)
		$can['UNDO'] = false;
		$will['UPDATE_WP_CAROUSEL_OPTION'] = false;
		$will['CANCEL'] = false;
		$will['ADD_COSTUMIZED_CONTENT'] = false;
		$will['SHOW_EDIT_FORM'] = false;
		$will['UPDATE_WP_CAROUSEL_CONFIG'] = false;
					
		// Cargamos la ID del carrusel en la matriz
		$log[] = 'Cargando el valor de la ID del carrusel, la variable $_GET[\'page\'] es: "'.$_GET['page'].'"';
		$this_carousel['ID'] = explode('-', $_GET['page']);
		$log[] = 'Se ha separado la variable $_GET[\'page\'] en segmentos separados por "-". El tercer segmento (indice 2 en la matriz) vale: "'.$this_carousel['ID'][2].'"';
		$this_carousel['ID'] = $this_carousel['ID'][2];
		
		// Comprobamos que la supuesta ID es un número, si no lo es, no se podrá ejecutar correctamente el script
		$log[] = 'Comprobacion del valor de la ID del carrusel';
		if(is_numeric($this_carousel['ID']))
		{
			$log[] = 'La comprobacion ha determinado que se trata de un valor numerico, el tipo de valor esperado por la funcion, de hecho el valor es: "'.$this_carousel['ID'].'"';
			// Como el valor es numérico, podemos proseguir aquí con el código del plugin
			// Tenemos que crear una variable que almacene el contenido del carrusel, que más adelante le pasaremos a otra función
			$items = get_option('wp_carousel');
			$log[] = 'Se ha cargado la matriz de contenido desde la Base de Datos';
			$items = maybe_unserialize($items);
			$log[] = 'Se ha dessearializado el contenido extraido de la Base de Datos';
						
			// Vamos a analizar la acción a realizar y la vamos a ejecutar
			if (isset($_GET['action']))
			{
					
				$log[] = 'Separando el contenido de $_GET[\'action\'], que es: "'.$_GET['action'].'"';
				$action = explode(':', $_GET['action']);
				$log[] = 'Comienza el analisis de $action[0], que vale: "'.$action[0].'"';
				
				switch($action[0])
				{
					case 'REMOVE':
					
						/* ESTA ACCION SE REALIZA CON AJAX, YA NO DEBE DARSE MAS ESTE CASO */
						
						$log[] = 'Se ha accedido a la pagina de opciones del carrusel '.$this_carousel['ID'].' con el indicador de accion "'.$action[0].'", que corresponde a una accion realizada via AJAX. Se desestima la accion.';
						
						/*
						
						// Se borra algo del carrusel
						$log[] = 'El analisis ha determinado que la accion a realizar es eliminar contenido del carrusel';
						$log[] = 'El carrusel afectado por el borrado es el carrusel con ID: "'.$this_carousel['ID'].'"';
						$log[] = 'El contenido afectado por el borrado es el que tiene de ID INTERNA: "'.$action[1].'"';
						$items_backup = $items;
						$log[] = 'Se ha almacenado una copia de la variable $items en la variable $items_backup';
						$items_backup = serialize($items_backup);
						$log[] = 'Se ha serializado la variable $items_backup, ahora vale: "'.$items_backup.'"';
						$items_backup = base64_encode($items_backup);
						$log[] = 'Se ha codificado en base64 la variable $items_backup, ahora vale: "'.$items_backup.'"';
						unset($items[$this_carousel['ID']][$action[1]]);
						$log[] = 'Se ha eliminado de la variable $items el indice: ['.$this_carousel['ID'].']['.$action[1].']';
						$can['UNDO'] = true;
						$can['UNDO_URL_TYPE'] = 'UNDO_REMOVE:'.$items_backup;
						$will['UPDATE_WP_CAROUSEL_OPTION'] = true;
						
						*/
						
						break;
		
					case 'ADD':
					
						/* ESTA ACCION SE REALIZA CON AJAX, YA NO DEBE DARSE MAS ESTE CASO */
						
						$log[] = 'Se ha accedido a la pagina de opciones del carrusel '.$this_carousel['ID'].' con el indicador de accion "'.$action[0].'", que corresponde a una accion realizada via AJAX. Se desestima la accion.';
						
						/*
											
						// Se añade algo al carrusel
						$log[] = 'El analisis ha determinado que la accion a realizar es insertar contenido en el carrusel';
						$log[] = 'El carrusel afectado por el borrado es el carrusel con ID: "'.$this_carousel['ID'].'"';
						$items_backup = $items;
						$log[] = 'Se ha almacenado una copia de la variable $items en la variable $items_backup';
						$items_backup = serialize($items_backup);
						$log[] = 'Se ha serializado la variable $items_backup, ahora vale: "'.$items_backup.'"';
						$items_backup = base64_encode($items_backup);
						$log[] = 'Se ha codificado en base64 la variable $items_backup, ahora vale: "'.$items_backup.'"';
						$log[] = 'Comienza el analisis del tipo de contenido';
						switch ($_POST['type_list'])
						{
							case 1:
								$log[] = 'El analisis ha detectado que se trata de una categoria';
								$id_type_temp = $_POST['category_id'];
								$log[] = 'La ID del elemento se corresponde con la ID de la variable $_POST[\'category_id\'], que tiene por valor: "'.$_POST['category_id'].'"';
								break;
							case 2:
								$log[] = 'El analisis ha detectado que se trata de un articulo';
								$id_type_temp = $_POST['post_id'];
								$log[] = 'La ID del elemento se corresponde con la ID de la variable $_POST[\'post_id\'], que tiene por valor: "'.$_POST['post_id'].'"';
								break;
							case 3:
								$log[] = 'El analisis ha detectado que se trata de una pagina';
								$id_type_temp = $_POST['page_id'];
								$log[] = 'La ID del elemento se corresponde con la ID de la variable $_POST[\'page_id\'], que tiene por valor: "'.$_POST['page_id'].'"';
								break;
							case 4:
								$log[] = 'El analisis ha detectado que se trata de contenido personalizado';
								$will['ADD_COSTUMIZED_CONTENT'] = true;
								$id_type_temp = wp_carousel_calculate_new_id($items[$this_carousel['ID']], '4');
								$log[] = 'La ID del elemento se corresponde con la funcion wp_carousel_calculate_new_id, que devuelve por valor: "'.wp_carousel_calculate_new_id($items[$this_carousel['ID']], '4').'"';
								break;
							default:
								$log[] = 'El analisis ha detectado un tipo no contemplado en la lista de tipos, se debe cancelar la insercion de contenidos';
								$errors[] = 'La variable $_POST[\'type_list\'] tiene un valor no contemplado en la lista de tipos, de hecho su valor es: "'.$_POST['type_list'].'".';
								$log[] = 'Se ha añadido a la matriz de errores el error '.key($errors).': "'.current($errors).'"';
								$will['CANCEL'] = true;
								break;
						}
						
						if (!$will['CANCEL'])
						{
							$log[] = 'Comienza el analisis de la variable $_POST[\'order\'], el valor de la cual es: "'.$_POST['order'].'"';
							if (is_numeric($_POST['order']))
							{
								$log[] = 'El orden tiene un valor numerico, aunque se eliminara cualquier rastro de decimales';
								$order_temp = floor($_POST['order']);
								$log[] = 'Se ha creado la variable $order_temp con el valor truncado de la variable $_POST[\'order\'], el valor final es: "'.$order_temp.'"';
							}
							else
							{
								// Esto es un error, pero ha sido producido por el usuario al insertar un valor no numérico para el orden, así que no lo añadiremos a la matriz de erroers sino que simplemente no tendremos en cuenta la elección del usuario y tomaremos un valor correcto.
								$log[] = 'El orden no tiene un valor numerico, se establece su nuevo valor en 0';
								$order_temp = '0';
							}
							$log[] = 'El analiis ha finalizado';
														
							$items[$this_carousel['ID']][$order_temp.'_'.$id_type_temp.'_'.$_POST['type_list']]['ID'] = $id_type_temp;
							$log[] = 'Se ha insertado el indice: "['.$this_carousel['ID'].'][\''.$order_temp.'_'.$id_type_temp.'_'.$_POST['type_list'].'\'][\'ID\']", con el valor: "'.$id_type_temp.'"';
							$items[$this_carousel['ID']][$order_temp.'_'.$id_type_temp.'_'.$_POST['type_list']]['TYPE'] = $_POST['type_list'];
							$log[] = 'Se ha insertado el indice: "['.$this_carousel['ID'].'][\''.$order_temp.'_'.$id_type_temp.'_'.$_POST['type_list'].'\'][\'TYPE\']", con el valor: "'.$_POST['type_list'].'"';
							$items[$this_carousel['ID']][$order_temp.'_'.$id_type_temp.'_'.$_POST['type_list']]['ORDER'] = $order_temp;
							$log[] = 'Se ha insertado el indice: "['.$this_carousel['ID'].'][\''.$order_temp.'_'.$id_type_temp.'_'.$_POST['type_list'].'\'][\'ORDER\']", con el valor: "'.$order_temp.'"';
							
							$log[] = 'Iniciamos la insercion de datos especificos para las categorias';
							if ($_POST['type_list'] == '1')
							{
								if (is_numeric($_POST['posts_number']))
								{
									$log[] = 'El numero de articulos a mostrar en esta categoria es un numero y su valor es: '.$_POST['posts_number'];
									$posts_number = floor($_POST['posts_number']);
									$log[] = 'Se ha truncado el numero de articulos a mostrar, ahora su valor es: '.$posts_number;
								}
								else
								{
									// Esto es un error provocado por el usuario: el número de artículos a mostrar debe ser un número
									$log[] = 'El numero de articulos a mostrar en esta categoria no es un numero, tomamos el valor por defecto, 10';
									$posts_number = '10';
								}
								$items[$this_carousel['ID']][$order_temp.'_'.$id_type_temp.'_'.$_POST['type_list']]['POSTS_ORDER'] = $_POST['posts_order'];
								$log[] = 'Se ha insertado el indice: "['.$this_carousel['ID'].'][\''.$order_temp.'_'.$id_type_temp.'_'.$_POST['type_list'].'\'][\'POSTS_ORDER\']", con el valor: "'.$_POST['posts_order'].'"';
								$items[$this_carousel['ID']][$order_temp.'_'.$id_type_temp.'_'.$_POST['type_list']]['POSTS_NUMBER'] = $posts_number;
								$log[] = 'Se ha insertado el indice: "['.$this_carousel['ID'].'][\''.$order_temp.'_'.$id_type_temp.'_'.$_POST['type_list'].'\'][\'POSTS_NUMBER\']", con el valor: "'.$posts_number.'"';
							}
							$log[] = 'Se ha finalizado la insercion de datos especificos para las categorias';
							
							if ($will['ADD_COSTUMIZED_CONTENT'])
							{
								$log[] = 'Se procede a la insercion del contenido unico de los contenidos personalizables';
								$items[$this_carousel['ID']][$order_temp.'_'.$id_type_temp.'_'.$_POST['type_list']]['TITLE'] = $_POST['post_title'];
								$log[] = 'Se ha insertado el indice: "['.$this_carousel['ID'].'][\''.$order_temp.'_'.$id_type_temp.'_'.$_POST['type_list'].'\'][\'TITLE\']", con el valor: "'.$_POST['post_title'].'"';
								$items[$this_carousel['ID']][$order_temp.'_'.$id_type_temp.'_'.$_POST['type_list']]['DESC'] = $_POST['desc'];
								$log[] = 'Se ha insertado el indice: "['.$this_carousel['ID'].'][\''.$order_temp.'_'.$id_type_temp.'_'.$_POST['type_list'].'\'][\'DESC\']", con el valor: "'.$_POST['desc'].'"';
								$items[$this_carousel['ID']][$order_temp.'_'.$id_type_temp.'_'.$_POST['type_list']]['IMAGE_URL'] = $_POST['url_image'];
								$log[] = 'Se ha insertado el indice: "['.$this_carousel['ID'].'][\''.$order_temp.'_'.$id_type_temp.'_'.$_POST['type_list'].'\'][\'IMAGE_URL\']", con el valor: "'.$_POST['url_image'].'"';
								$items[$this_carousel['ID']][$order_temp.'_'.$id_type_temp.'_'.$_POST['type_list']]['LINK_URL'] = $_POST['url_link'];
								$log[] = 'Se ha insertado el indice: "['.$this_carousel['ID'].'][\''.$order_temp.'_'.$id_type_temp.'_'.$_POST['type_list'].'\'][\'LINK_URL\']", con el valor: "'.$_POST['url_link'].'"';
							}
							
							if (isset($_POST['show_in_loop']))
							{
								$items[$this_carousel['ID']][$_POST['order'].'_'.$id_type_temp.'_'.$_POST['type_list']]['SHOW'] = $_POST['show_in_loop'];
								$log[] = 'Se ha insertado el indice: "['.$this_carousel['ID'].'][\''.$_POST['order'].'_'.$id_type_temp.'_'.$_POST['type_list'].'\'][\'SHOW\']", con el valor: "'.$_POST['show_in_loop'].'"';
							}
							else 
							{
								$items[$this_carousel['ID']][$_POST['order'].'_'.$id_type_temp.'_'.$_POST['type_list']]['SHOW'] = 0;
								$log[] = 'Se ha insertado el indice: "['.$this_carousel['ID'].'][\''.$_POST['order'].'_'.$id_type_temp.'_'.$_POST['type_list'].'\'][\'SHOW\']", con el valor: "0"';
							}
							
							$can['UNDO'] = true;
							$can['UNDO_URL_TYPE'] = 'UNDO_REMOVE:'.$items_backup;
							$will['UPDATE_WP_CAROUSEL_OPTION'] = true;
						}
						else
						{
							$log[] = 'Comprobacion de recuento de errores de la funcion wp_carousel_carousel_options_page()';
							if(!empty($errors))
							{
								// Uy uy uy... ha habido errores durante la ejecución de esta función, cortemos el script y mostremos los errores de forma legible
								$log[] = 'La comprobacion del recuento de errores de la funcion wp_carousel_carousel_options_page() ha detectado que hay errores en la funcion wp_carousel_carousel_options_page()';
								echo '<h2>'.__('Errors', 'wp_carousel').'</h2><pre>';
								print_r($errors);
								echo '</pre>';
								$log[] = 'Se ha mostrado el listado de errores de la funcion wp_carousel_carousel_options_page()';
								
								echo '<h2>'.__('Log', 'wp_carousel').'</h2><pre>';
								print_r($log);
								echo '</pre>';
								
								// Avisemos de que cortamos el script
								echo '<p>El script se deja de ejecutar a partir de ahora debido a que se han detectado errores durante su ejecución</p>';
								exit; // Cortamos el script
							} else {
								// ¡Qué bien, no hay errores!
								$log[] = 'La comprobacion del recuento de errores de la funcion wp_carousel_carousel_options_page() ha determinado que no ha habido errores durante la ejecucion de wp_carousel_carousel_options_page()';
							}	
						}
						
						*/
						
						break;
						
					case 'IMPORT':
					
						/* ESTA ACCION SE REALIZA CON AJAX, YA NO DEBE DARSE MAS ESTE CASO */
						
						$log[] = 'Se ha accedido a la pagina de opciones del carrusel '.$this_carousel['ID'].' con el indicador de accion "'.$action[0].'", que corresponde a una accion realizada via AJAX. Se desestima la accion.';
						
						/*
						
						// Se sustituye el contenido del carrusel por una copia de seguridad
						$log[] = 'El analisis ha determinado que la accion a realizar es importar contenido al carrusel';
						$log[] = 'El carrusel afectado por el borrado es el carrusel con ID: "'.$this_carousel['ID'].'"';
						$log[] = 'La copia de seguridad es: "'.$action[1].'"';
						$items_backup = $action[1];
						$items_backup = base64_decode($items_backup);
						$log[] = 'La variable $items_backup se ha decodificado en base64, ahora vale: "'.$items_backup.'"';
						$items_backup = maybe_unserialize($items_backup);
						$log[] = 'La variable $items_backup se ha desserializado, ahora vale: "'.$items_backup.'"';
						$items = $items_backup;
						$will['UPDATE_WP_CAROUSEL_OPTION'] = true;
						
						*/
						
						break;
						
					case 'EDIT':
					
						/* ESTA ACCION SE REALIZA CON AJAX, YA NO DEBE DARSE MAS ESTE CASO */
						
						$log[] = 'Se ha accedido a la pagina de opciones del carrusel '.$this_carousel['ID'].' con el indicador de accion "'.$action[0].'", que corresponde a una accion realizada via AJAX. Se desestima la accion.';
						
						/*
						
						// Se está editando contenido personalizable
						$log[] = 'El analisis ha determinado que la accion a realizar es editar contenido personalizable';
						$log[] = 'El carrusel afectado por el borrado es el carrusel con ID: "'.$this_carousel['ID'].'"';
						$log[] = 'El contenido afectado por el borrado es el que tiene de ID INTERNA: "'.$action[1].'"';
						$will['SHOW_EDIT_FORM'] = true;
						
						*/
						
						break;
						
					case 'SAVE_EDIT':
					
						/* ESTA ACCION SE REALIZA CON AJAX, YA NO DEBE DARSE MAS ESTE CASO */
						
						$log[] = 'Se ha accedido a la pagina de opciones del carrusel '.$this_carousel['ID'].' con el indicador de accion "'.$action[0].'", que corresponde a una accion realizada via AJAX. Se desestima la accion.';
						
						/*
					
						// Se guardan los cambios de la edición anterior						
						$log[] = 'El carrusel afectado por la edicion es el carrusel con ID: "'.$this_carousel['ID'].'"';
						$log[] = 'El contenido afectado por la edicion es el que tiene de ID INTERNA: "'.$_POST['older_internal_id'].'"';
												
						$log[] = 'Se inicia la fase 1/3 del guardado de la edicion: crear la copia de seguridad';
						
						$items_backup = $items;
						$log[] = 'Se ha almacenado una copia de la variable $items en la variable $items_backup';
						$items_backup = serialize($items_backup);
						$log[] = 'Se ha serializado la variable $items_backup, ahora vale: "'.$items_backup.'"';
						$items_backup = base64_encode($items_backup);
						$log[] = 'Se ha codificado en base64 la variable $items_backup, ahora vale: "'.$items_backup.'"';
						
						$log[] = 'La fase 1/3 se ha finalizado';
						$log[] = 'Comienza la fase 2/3: el borrado del elemento anterior';
						
						unset($items[$this_carousel['ID']][$_POST['older_internal_id']]);
						$log[] = 'Se ha eliminado de la variable $items el indice: ['.$this_carousel['ID'].']['.$_POST['older_internal_id'].']';
						
						$log[] = 'La fase 2/3 se ha finalizado';
						$log[] = 'Comienza la fase 3/3: la insercion del nuevo contenido';
						
						$log[] = 'Comienza el analisis de la variable $_POST[\'order\'], el valor de la cual es: "'.$_POST['order'].'"';
						if (is_numeric($_POST['order']))
						{
							$log[] = 'El orden tiene un valor numerico, aunque se eliminara cualquier rastro de decimales';
							$order_temp = floor($_POST['order']);
							$log[] = 'Se ha creado la variable $order_temp con el valor truncado de la variable $_POST[\'order\'], el valor final es: "'.$order_temp.'"';
						}
						else
						{
							// Esto es un error, pero ha sido producido por el usuario al insertar un valor no numérico para el orden, así que no lo añadiremos a la matriz de erroers sino que simplemente no tendremos en cuenta la elección del usuario y tomaremos un valor correcto.
							$log[] = 'El orden no tiene un valor numerico, se establece su nuevo valor en 0';
							$order_temp = '0';
						}
						$log[] = 'El analiis ha finalizado';
						
						$items[$this_carousel['ID']][$order_temp.'_'.$_POST['older_id'].'_'.$_POST['type_list']]['ID'] = $_POST['older_id'];
						$log[] = 'Se ha insertado el indice: "['.$this_carousel['ID'].'][\''.$order_temp.'_'.$_POST['older_id'].'_'.$_POST['type_list'].'\'][\'ID\']", con el valor: "'.$_POST['older_id'].'"';
						$items[$this_carousel['ID']][$order_temp.'_'.$_POST['older_id'].'_'.$_POST['type_list']]['TYPE'] = $_POST['type_list'];
						$log[] = 'Se ha insertado el indice: "['.$this_carousel['ID'].'][\''.$order_temp.'_'.$_POST['older_id'].'_'.$_POST['type_list'].'\'][\'TYPE\']", con el valor: "'.$_POST['type_list'].'"';
						$items[$this_carousel['ID']][$order_temp.'_'.$_POST['older_id'].'_'.$_POST['type_list']]['ORDER'] = $order_temp;
						$log[] = 'Se ha insertado el indice: "['.$this_carousel['ID'].'][\''.$order_temp.'_'.$_POST['older_id'].'_'.$_POST['type_list'].'\'][\'ORDER\']", con el valor: "'.$order_temp.'"';
						$items[$this_carousel['ID']][$order_temp.'_'.$_POST['older_id'].'_'.$_POST['type_list']]['TITLE'] = htmlspecialchars_decode($_POST['post_title']);
						$log[] = 'Se ha insertado el indice: "['.$this_carousel['ID'].'][\''.$order_temp.'_'.$_POST['older_id'].'_'.$_POST['type_list'].'\'][\'TITLE\']", con el valor: "'.htmlspecialchars_decode($_POST['post_title']).'"';
						$items[$this_carousel['ID']][$order_temp.'_'.$_POST['older_id'].'_'.$_POST['type_list']]['DESC'] = $_POST['desc'];
						$log[] = 'Se ha insertado el indice: "['.$this_carousel['ID'].'][\''.$order_temp.'_'.$_POST['older_id'].'_'.$_POST['type_list'].'\'][\'DESC\']", con el valor: "'.$_POST['desc'].'"';
						$items[$this_carousel['ID']][$order_temp.'_'.$_POST['older_id'].'_'.$_POST['type_list']]['SHOW'] = '0';
						$log[] = 'Se ha insertado el indice: "['.$this_carousel['ID'].'][\''.$order_temp.'_'.$_POST['older_id'].'_'.$_POST['type_list'].'\'][\'SHOW\']", con el valor: "0"';
						$items[$this_carousel['ID']][$order_temp.'_'.$_POST['older_id'].'_'.$_POST['type_list']]['IMAGE_URL'] = $_POST['url_image'];
						$log[] = 'Se ha insertado el indice: "['.$this_carousel['ID'].'][\''.$order_temp.'_'.$_POST['older_id'].'_'.$_POST['type_list'].'\'][\'IMAGE_URL\']", con el valor: "'.$_POST['url_image'].'"';
						$items[$this_carousel['ID']][$order_temp.'_'.$_POST['older_id'].'_'.$_POST['type_list']]['LINK_URL'] = $_POST['url_link'];
						$log[] = 'Se ha insertado el indice: "['.$this_carousel['ID'].'][\''.$order_temp.'_'.$_POST['older_id'].'_'.$_POST['type_list'].'\'][\'LINK_URL\']", con el valor: "'.$_POST['url_link'].'"';
						
						$log[] = 'La fase 3/3 se ha completado';
																								
						$can['UNDO'] = true;
						$can['UNDO_URL_TYPE'] = 'UNDO_REMOVE:'.$items_backup;
						$will['UPDATE_WP_CAROUSEL_OPTION'] = true;		
						
						*/
										
						break;
						
					case 'SAVE_OPTIONS':
						$config = get_option('wp_carousel_config');
						$log[] = 'Se ha cargado la matriz de configuracion en la variable $config';
						$config = maybe_unserialize($config);
						$log[] = 'Se ha desserializado la matriz de configuracion (variable $config)';
						$config[$this_carousel['ID']]['SHOW_ARROWS'] = $_POST['show_arrows'];
						$config[$this_carousel['ID']]['SLIDE_POSTS'] = $_POST['slide_posts'];
						$config[$this_carousel['ID']]['ENABLE_PAGINATION'] = $_POST['enable_pagination'];
						$config[$this_carousel['ID']]['AUTOSLIDE_TIME'] = $_POST['autoslide_time'];
						$config[$this_carousel['ID']]['AUTOSLIDE_POSTS'] = $_POST['autoslide_posts'];
						$config[$this_carousel['ID']]['IMG_WIDTH'] = $_POST['img_width'];
						$config[$this_carousel['ID']]['IMG_HEIGHT'] = $_POST['img_height'];
						$config[$this_carousel['ID']]['PANEL_WIDTH'] = $_POST['panel_width'];
						$config[$this_carousel['ID']]['LOOP_MODE'] = $_POST['loop_mode'];
						if ($config[$this_carousel['ID']]['SHOW_ARROWS'] == '') $config[$this_carousel['ID']]['SHOW_ARROWS'] = '1';
						if ($config[$this_carousel['ID']]['SLIDE_POSTS'] == '' || !is_numeric($config[$this_carousel['ID']]['SLIDE_POSTS']) || $config[$this_carousel['ID']]['SLIDE_POSTS'] < 0) $config[$this_carousel['ID']]['SLIDE_POSTS'] = '1';
						if ($config[$this_carousel['ID']]['ENABLE_PAGINATION'] == '') $config[$this_carousel['ID']]['ENABLE_PAGINATION'] = '1';
						if ($config[$this_carousel['ID']]['AUTOSLIDE_TIME'] == '' || !is_numeric($config[$this_carousel['ID']]['AUTOSLIDE_TIME']) || $config[$this_carousel['ID']]['AUTOSLIDE_TIME'] < 0) $config[$this_carousel['ID']]['AUTOSLIDE_TIME'] = '0';
						if ($config[$this_carousel['ID']]['AUTOSLIDE_POSTS'] == '' || !is_numeric($config[$this_carousel['ID']]['AUTOSLIDE_POSTS']) || $config[$this_carousel['ID']]['AUTOSLIDE_POSTS'] < 0) $config[$this_carousel['ID']]['AUTOSLIDE_POSTS'] = '0';
						$log[] = 'Se ha actualizado la matriz de la configuracion del carrusel con ID: "'.$this_carousel['ID'].'"';
						$will['UPDATE_WP_CAROUSEL_CONFIG'] = true;
						break;
						
					case 'UPDATE_THEME':
						$config = get_option('wp_carousel_config');
						$log[] = 'Se ha cargado la matriz de configuracion en la variable $config';
						$config = maybe_unserialize($config);
						$log[] = 'Se ha desserializado la matriz de configuracion (variable $config)';
						$config[$this_carousel['ID']]['THEME'] = $action[1];
						$log[] = 'Se ha actualizado la matriz de la configuracion del carrusel con ID: "'.$this_carousel['ID'].'"';
						$will['UPDATE_WP_CAROUSEL_CONFIG'] = true;
						break;
						
					case 'SAVE-NO-AJAX':
						if (isset($action[1]) && isset($action[2]))
						{
							$log[] = 'Se van a guardar los cambios del carrusel '.$action[2];
							$log[] = 'Se va a guardar el contenido sin AJAX';
							$items[$action[2]] = maybe_unserialize(base64_decode($action[1]));
							$will['UPDATE_WP_CAROUSEL_OPTION'] = true;
						}
						break;
						
					default:
						// No hacemos nada
						$log[] = 'La accion no se contempla en la lista de acciones, la accion en cuestion es: "'.$action[0].'"';
						$errors[] = 'La accion no se contempla en la lista de acciones, la accion en cuestion es: "'.$action[0].'"';
						$log[] = 'Se ha añadido a la matriz de errores el error '.key($errors).': "'.current($errors).'"';
						break;
				}
				$log[] = 'El analisis ha finalizado';
			}
			
			// Actualizamos la Base de Datos, si es que hay algo que actualizar...
			if ($will['UPDATE_WP_CAROUSEL_OPTION'])
			{
				$log[] = 'Hay que actualizar el contenido de la Base de Datos (elementos del carrusel)';
				$items_serialized = serialize($items);
				$log[] = 'Se ha creado la variable $items_serialized, que contiene el valor serializado de $items, que es: "'.$items_serialized.'"';
				$log[] = 'CONTENIDO ANTERIOR: "'.get_option('wp_carousel').'"';
				$log[] = 'NUEVO CONTENIDO: "'.$items_serialized.'"';
				$log[] = 'Se va a ejecutar la actualizacion';
				update_option('wp_carousel', $items_serialized);
				$log[] = 'Se ha completado la actualizacion';
			}
			elseif ($will['UPDATE_WP_CAROUSEL_CONFIG'])
			{
				$log[] = 'Hay que actualizar el contenido de la Base de Datos (configuracion del carrusel)';
				$config_serialized = serialize($config);
				$log[] = 'Se ha creado la variable $config_serialized, que contiene el valor serializado de $config, que es: "'.$config_serialized.'"';
				$log[] = 'CONTENIDO ANTERIOR: "'.get_option('wp_carousel_config').'"';
				$log[] = 'NUEVO CONTENIDO: "'.$config_serialized.'"';
				$log[] = 'Se va a ejecutar la actualizacion';
				update_option('wp_carousel_config', $config_serialized);
				$log[] = 'Se ha completado la actualizacion';
			}
			else
			{
				$log[] = 'El sistema ha determinado que no hay nada que actualizar en la Base de Datos';
			}
			
			// Ahora que ya hemos acabado de ejecutar acciones, podemos seguir
			$items = $items[$this_carousel['ID']];
			$log[] = 'Se ha seleccionado el contenido del indice '.$this_carousel['ID'].' de la matriz de contenido como contenido del carrusel';
			ksort($items);
			$log[] = 'Se ha ordenador la matriz de contenido atendiendo al valor del indice';
		}
		else
		{
			// ERROR: ¡La variable no es un número! Almacenemos el error en la matriz $errors
			$log[] = 'La comprobacion ha determinado que se trata de un valor no numerico, cuando la funcion esperaba un valor numerico, de hecho el valor es: "'.$this_carousel['ID'].'"';
			$errors[] = 'El tercer parámetro de la variable $_GET[\'page\'] (separados por "-") no tiene un valor numérico. De hecho su valor es: "'.$this_carousel['ID'].'"';
			$log[] = 'Se ha añadido a la matriz de errores el error '.key($errors).': "'.current($errors).'"';
		}
		
		$log[] = 'Comprobacion de recuento de errores de la funcion wp_carousel_carousel_options_page()';
		if(!empty($errors))
		{
			// Uy uy uy... ha habido errores durante la ejecución de esta función, cortemos el script y mostremos los errores de forma legible
			$log[] = 'La comprobacion del recuento de errores de la funcion wp_carousel_carousel_options_page() ha detectado que hay errores en la funcion wp_carousel_carousel_options_page()';
			echo '<h2>'.__('Errors', 'wp_carousel').'</h2><pre>';
			print_r($errors);
			echo '</pre>';
			$log[] = 'Se ha mostrado el listado de errores de la funcion wp_carousel_carousel_options_page()';
			
			echo '<h2>'.__('Log', 'wp_carousel').'</h2><pre>';
			print_r($log);
			echo '</pre>';
			
			// Avisemos de que cortamos el script
			echo '<p>El script se deja de ejecutar a partir de ahora debido a que se han detectado errores durante su ejecución</p>';
			exit; // Cortamos el script
		} else {
			// ¡Qué bien, no hay errores!
			$log[] = 'La comprobacion del recuento de errores de la funcion wp_carousel_carousel_options_page() ha determinado que no ha habido errores durante la ejecucion de wp_carousel_carousel_options_page()';
		}
		
		$log[] = 'Comienza a mostrarse la interfaz de la pagina';
		
		if ($can['UNDO'])
		{
			switch($action[0])
			{
				case 'REMOVE':
					echo '<div id="message" class="updated fade"><p>';
					printf(__('The item has been removed. Do you want to <a href="%s">undo</a>?', 'wp_carousel'), wp_carousel_create_internal_urls($can['UNDO_URL_TYPE'], 'get', false));
					echo '</p></div>';
					break;
				case 'ADD':
					echo '<div id="message" class="updated fade"><p>';
					printf(__('The item has been added. Do you want to <a href="%s">undo</a>?', 'wp_carousel'), wp_carousel_create_internal_urls($can['UNDO_URL_TYPE'], 'get', false));
					echo '</p></div>';
					break;
				case 'SAVE_EDIT':
					echo '<div id="message" class="updated fade"><p>';
					printf(__('The item has been edited. Do you want to <a href="%s">undo</a>?', 'wp_carousel'), wp_carousel_create_internal_urls($can['UNDO_URL_TYPE'], 'get', false));
					echo '</p></div>';
					break;
				default:
					// No hacemos nada
					$log[] = 'La accion no se contempla en la lista de acciones, la accion en cuestion es: "'.$action[0].'"';
					$errors[] = 'La accion no se contempla en la lista de acciones, la accion en cuestion es: "'.$action[0].'"';
					$log[] = 'Se ha añadido a la matriz de errores el error '.key($errors).': "'.current($errors).'"';
					break;
			}
		}
			
		?>
				
		<div class="updated fade"><p><?php _e('To show this carousel in your theme, add this code in the place where you want to show it:', 'wp_carousel'); echo '&nbsp;<code>&lt;?php wp_carousel('.$this_carousel['ID'].'); ?&gt;</code>'; ?></p></div>
		
		<div class="wrap">
			<div id="icon-options-general" class="icon32"><br></div>
			<h2><?php printf(__('Carousel %s options page', 'wp_carousel'), $this_carousel['ID']); ?><span id="carousel_id"><?php echo $this_carousel['ID']; ?></span></h2>
					
			<?php if (get_the_author_meta('wp_carousel_has_shown_survey', $user_ID) != 'yes'): ?>
				<div class="updated survey"><p><?php printf(__('Help me to improve <strong>WP Carousel</strong>, fill <a href="%s">this survey</a>', 'wp_carousel'), WP_CAROUSEL_SURVEY); ?></p></div>
				<?php update_user_meta($user_ID, 'wp_carousel_has_shown_survey', 'yes'); ?>
			<?php endif; ?>
						
			<div id="wp_carousel_ajax_loader">
				<div>
					<img src="<?php echo $wp_carousel_path[6]; ?>/img/ajax-loader.gif" align="<?php _e('Saving changes', ' wp_carousel'); ?>" title="<?php _e('Saving changes, please, wait a moment', ' wp_carousel'); ?>" />
				</div>
			</div>
			
			<div id="wp_carousel_ajax_response">
				
			</div>
			
			<a id="current_url_js" href="<?php echo $wp_carousel_path[6]; ?>/update_db.php"></a>
			
			<div class="manage_items">
				
				<div id="items_addable_carousel">
					<h3><?php _e('Items', 'wp_carousel'); ?></h3>
					<div class="items_padder">	
						<div id="sortable_items" class="connected">
							
							<div id="item_1" class="item">
								<div class="handle">
									<h4><?php _e('Category', 'wp_carousel'); ?></h4>
								</div>
								<div class="item_content">
									<p class="pre_dropped"><?php _e('Drag this item into the carousel to add posts from an <strong>specific category</strong>', 'wp_carousel'); ?></p>
									<div class="add_form wp_carousel_disable_drag">
										<form method="post" class="wp_carousel_ajax_form" onsubmit="return wp_carousel_update_ajax_item()">
											
											<dl>
												<dt><?php _e('Category', 'wp_carousel'); ?></dt>
												<dd><?php wp_carousel_dropdown_type_items('1'); ?></dd>
											</dl>
											<dl>
												<dt><?php _e('Posts\' order', 'wp_carousel'); ?></dt>
												<dd>
													<select id="posts_order" name="posts_order">
														<option id="first_old" value="first_old"><?php _e('Show the oldest posts first', 'wp_carousel'); ?></option>
														<option id="first_new" value="first_new"><?php _e('Show the newest posts first', 'wp_carousel'); ?></option>
													</select>		
												</dd>
											</dl>
 											<dl>
												<dt><?php _e('Number of posts', 'wp_carousel'); ?></dt>
												<dd><input type="text" name="posts_number" id="posts_number" value="10" /></dd>
											</dl>
											<dl>
												<dt><?php _e('Must I show this element in the loop?', 'wp_carousel'); ?></dt>
												<dd><input type="checkbox" name="show_in_loop" id="show_in_loop" value="yes" checked="checked" /></dd>
											</dl>
											
											<div class="clear_dl"></div>
																																	
											<input type="hidden" name="order" id="order" value="0" />
											<input type="hidden" name="type" id="type" value="1" />
																																												
										</form>
									</div>
								</div>
							</div>
							<div id="item_5" class="item">
								<div class="handle"><h4><?php _e('Tag', 'wp_carousel'); ?></h4></div>
								<div class="item_content">
									<p class="pre_dropped"><?php _e('Drag this item into the carousel to add posts from an <strong>specific tag</strong>', 'wp_carousel'); ?></p>
									<div class="add_form wp_carousel_disable_drag">
											<form method="post" class="wp_carousel_ajax_form" onsubmit="return wp_carousel_update_ajax_item()">
												
												<dl>
													<dt><?php _e('Tag', 'wp_carousel'); ?></dt>
													<dd><?php wp_carousel_dropdown_type_items('5'); ?></dd>
												</dl>
												<dl>
													<dt><?php _e('Posts\' order', 'wp_carousel'); ?></dt>
													<dd>
														<select id="posts_order" name="posts_order">
															<option id="first_old" value="first_old"><?php _e('Show the oldest posts first', 'wp_carousel'); ?></option>
															<option id="first_new" value="first_new"><?php _e('Show the newest posts first', 'wp_carousel'); ?></option>
														</select>		
													</dd>
												</dl>
												<dl>
													<dt><?php _e('Number of posts', 'wp_carousel'); ?></dt>
													<dd><input type="text" name="posts_number" id="posts_number" value="10" /></dd>
												</dl>
												<dl>
													<dt><?php _e('Must I show this element in the loop?', 'wp_carousel'); ?></dt>
													<dd><input type="checkbox" name="show_in_loop" id="show_in_loop" value="yes" checked="checked" /></dd>
												</dl>
												
												<div class="clear_dl"></div>
																																		
												<input type="hidden" name="order" id="order" value="0" />
												<input type="hidden" name="type" id="type" value="5" />
																																													
											</form>
										</div>
									</div>
							</div>
							<div id="item_2" class="item">
								<div class="handle"><h4><?php _e('Post', 'wp_carousel'); ?></h4></div>
								<div class="item_content">
									<p class="pre_dropped"><?php _e('Drag this item into the carousel to add a <strong>single post</strong> to the carousel', 'wp_carousel'); ?></p>
									<div class="add_form wp_carousel_disable_drag">
										<form method="post" class="wp_carousel_ajax_form" onsubmit="return wp_carousel_update_ajax_item()">
											
											<dl>
												<dt><?php _e('Post', 'wp_carousel'); ?></dt>
												<dd><?php wp_carousel_dropdown_type_items('2'); ?></dd>
											</dl>
											<dl>
												<dt><?php _e('Must I show this element in the loop?', 'wp_carousel'); ?></dt>
												<dd><input type="checkbox" name="show_in_loop" id="show_in_loop" value="yes" checked="checked" /></dd>
											</dl>
											
											<div class="clear_dl"></div>
																																	
											<input type="hidden" name="order" id="order" value="0" />
											<input type="hidden" name="type" id="type" value="2" />
											<input type="hidden" name="posts_order" id="posts_order" value="first_new" />
											<input type="hidden" name="posts_number" id="posts_number" value="0" />
																																												
										</form>
									</div>
								</div>
							</div>
							<div id="item_3" class="item">
								<div class="handle"><h4><?php _e('Page', 'wp_carousel'); ?></h4></div>
								<div class="item_content">
									<p class="pre_dropped"><?php _e('Drag this item into the carousel to add a <strong>single page</strong> to the carousel', 'wp_carousel'); ?></p>
									<div class="add_form wp_carousel_disable_drag">
										<form method="post" class="wp_carousel_ajax_form" onsubmit="return wp_carousel_update_ajax_item()">
											
											<dl>
												<dt><?php _e('Page', 'wp_carousel'); ?></dt>
												<dd><?php wp_carousel_dropdown_type_items('3'); ?></dd>
											</dl>
											
											<div class="clear_dl"></div>
																																	
											<input type="hidden" name="order" id="order" value="0" />
											<input type="hidden" name="type" id="type" value="3" />
											<input type="hidden" name="posts_order" id="posts_order" value="first_new" />
											<input type="hidden" name="posts_number" id="posts_number" value="0" />
											<input type="hidden" name="show_in_loop" id="show_in_loop" value="yes" />
																																												
										</form>
									</div>
								</div>
							</div>
							<div id="item_6" class="item">
								<div class="handle"><h4><?php _e('Author', 'wp_carousel'); ?></h4></div>
								<div class="item_content">
									<p class="pre_dropped"><?php _e('Drag this item into the carousel to add posts from an <strong>specific author</strong>', 'wp_carousel'); ?></p>
									<div class="add_form wp_carousel_disable_drag">
											<form method="post" class="wp_carousel_ajax_form" onsubmit="return wp_carousel_update_ajax_item()">
												
												<dl>
													<dt><?php _e('Author', 'wp_carousel'); ?></dt>
													<dd><?php wp_carousel_dropdown_type_items('6'); ?></dd>
												</dl>
												<dl>
													<dt><?php _e('Posts\' order', 'wp_carousel'); ?></dt>
													<dd>
														<select id="posts_order" name="posts_order">
															<option id="first_old" value="first_old"><?php _e('Show the oldest posts first', 'wp_carousel'); ?></option>
															<option id="first_new" value="first_new"><?php _e('Show the newest posts first', 'wp_carousel'); ?></option>
														</select>		
													</dd>
												</dl>
												<dl>
													<dt><?php _e('Number of posts', 'wp_carousel'); ?></dt>
													<dd><input type="text" name="posts_number" id="posts_number" value="10" /></dd>
												</dl>
												<dl>
													<dt><?php _e('Must I show this element in the loop?', 'wp_carousel'); ?></dt>
													<dd><input type="checkbox" name="show_in_loop" id="show_in_loop" value="yes" checked="checked" /></dd>
												</dl>
												
												<div class="clear_dl"></div>
																																		
												<input type="hidden" name="order" id="order" value="0" />
												<input type="hidden" name="type" id="type" value="6" />
																																													
											</form>
										</div>
									</div>
							</div>
							<div id="item_4" class="item costumized_content">
								<div class="handle"><h4><?php _e('Costumized Content', 'wp_carousel'); ?></h4></div>
								<div class="item_content">
									<p class="pre_dropped"><?php _e('Drag this item into the carousel to add a <strong>costumized content</strong> to the carousel', 'wp_carousel'); ?></p>
									<div class="add_form wp_carousel_disable_drag">
										<form method="post" class="wp_carousel_ajax_form" onsubmit="return wp_carousel_update_ajax_item()">
											
											<dl>
												<dt><?php echo _e('Image URL', 'wp_carousel'); ?></dt>
												<dd><input type="text" name="url_image" id="url_image" value="http://" /></dd>
											</dl>
											
											<dl>
												<dt><?php echo _e('Link URL', 'wp_carousel'); ?></dt>
												<dd><input type="text" name="url_link" id="url_link" value="http://" /></dd>
											</dl>
											
											<input type="text" name="post_title" size="30" tabindex="1" id="title" autocomplete="off" value="<?php echo _e('Title', 'wp_carousel'); ?>" />
											
											<br /><br />
											
											<textarea rows='10' cols='26' name='desc' tabindex='2' id='desc'><?php echo _e('Description', 'wp_carousel'); ?></textarea>
											
											<div class="clear_dl"></div>
																																	
											<input type="hidden" name="order" id="order" value="0" />
											<input type="hidden" name="type" id="type" value="4" />
											<input type="hidden" name="posts_order" id="posts_order" value="first_new" />
											<input type="hidden" name="posts_number" id="posts_number" value="0" />
											<input type="hidden" name="show_in_loop" id="show_in_loop" value="yes" />
											<input type="hidden" name="category_id" id="category_id" value="0" />
																																												
										</form>
									</div>
								</div>
							</div>
							<?php if (WP_CAROUSEL_EI): ?>
							<div id="item_7" class="item">
								<div class="handle"><h4><?php _e('External Carousel', 'wp_carousel'); ?></h4></div>
								<div class="item_content">
									<p class="pre_dropped"><?php _e('Drag this item into the carousel to add a <strong>WP Carousel\'s carousel from other WordPress blog</strong> to the carousel', 'wp_carousel'); ?></p>
									<div class="add_form wp_carousel_disable_drag">
										<form method="post" class="wp_carousel_ajax_form" onsubmit="return wp_carousel_update_ajax_item()">
											
											<dl>
												<dt><?php _e('URL', 'wp_carousel'); ?></dt>
												<dd><input type="text" name="wp_carousel_ei_url" id="wp_carousel_ei_url" value="http://" /></dd>
											</dl>
											<dl>
												<dt><?php _e('Carousel\'s ID', 'wp_carousel'); ?></dt>
												<dd><input type="text" name="wp_carousel_ei_id" id="wp_carousel_ei_id" value="0" /></dd>
											</dl>
											
											<div class="clear_dl"></div>
											
											<input type="hidden" name="order" id="order" value="0" />
											<input type="hidden" name="type" id="type" value="7" />
											<input type="hidden" name="posts_order" id="posts_order" value="first_new" />
											<input type="hidden" name="posts_number" id="posts_number" value="0" />
											<input type="hidden" name="show_in_loop" id="show_in_loop" value="yes" />
											<input type="hidden" name="category_id" id="category_id" value="0" />
																																												
										</form>
									</div>
								</div>
							</div>
							<?php endif; ?>
							
							<?php
								foreach ($_SESSION['WP_CAROUSEL_EXTRAS'] as $key => $value)
								{
							?>
							<div id="item_<?php echo $key; ?>" class="item">
								<div class="handle"><h4><?php echo $value['name']; ?></h4></div>
								<div class="item_content">
									<p class="pre_dropped"><?php echo $value['desc']; ?></p>
									<div class="add_form wp_carousel_disable_drag">
										<form method="post" class="wp_carousel_ajax_form" onsubmit="return wp_carousel_update_ajax_item()">
											
											<dl>
												<dt><?php _e('Item', 'wp_carousel'); ?></dt>
												<dd><input type="text" name="category_id" id="category_id" value="" /></dd>
											</dl>
											
											<div class="clear_dl"></div>
											
											<input type="hidden" name="order" id="order" value="0" />
											<input type="hidden" name="type" id="type" value="<?php echo $key; ?>" />
											<input type="hidden" name="posts_order" id="posts_order" value="first_new" />
											<input type="hidden" name="posts_number" id="posts_number" value="0" />
											<input type="hidden" name="show_in_loop" id="show_in_loop" value="yes" />
																																												
										</form>
									</div>
								</div>
							</div>
							<?php
								}
							?>
							
							<div class="clear"></div>
							
						</div>
					</div>
				</div>
				
				<div id="will_be_deleted">
					<h3><?php _e('Delete', 'wp_carousel'); ?></h3>
					<div class="items_padder">
						<div id="sortable_deleted" class="connected2">
							<p><?php _e('Drop items here to remove them from the carousel', 'wp_carousel'); ?>
						</div>
					</div>
					<hr class="fixer">
				</div>
				
				<hr class="wp_carousel_admin_separator" />
				
				<div id="items_in_carousel">
					<h3><?php _e('Carousel', 'wp_carousel'); ?></h3>
					<div class="items_padder">
						<div id="sortable_carousel" class="connected">
						
							<form method="post" onsubmit="return wp_carousel_update_ajax_item()">
								<input name="publish" type="submit" class="button-primary" tabindex="5" accesskey="p" value="<?php echo _e('Save', 'wp_carousel'); ?>" />
							</form>
						
							<?php wp_carousel_carousel_show_carousel_item_list($items, 'drag_drop'); ?>														
							
							<?php if (count($items) > 0 ) { ?>
							
							<form method="post" onsubmit="return wp_carousel_update_ajax_item()">
								<input name="publish" type="submit" class="button-primary" tabindex="5" accesskey="p" value="<?php echo _e('Save', 'wp_carousel'); ?>" />
							</form>		
							
							<?php } else { ?>	
							
							<p><?php _e('Drag items here to add them to the carousel', 'wp_carousel'); ?></p>
							
							<?php } ?>
							
						</div>
					</div>
					<hr class="fixer">
				</div>
				
				<div class="clear"></div>
			
			</div>

			
			<?php 			
				$log[] = 'Comienza a mostrar la parte de gestion de themes y opciones de visualizacion';
				wp_carousel_themes_options_area($this_carousel['ID']);
				$log[] = 'Se ha mostrado la parte de gestion de themes y opciones de visualizacion';
			?>
			
			<p><a href="<?php wp_carousel_create_internal_urls('DELETE_CAROUSEL:'.$this_carousel['ID'], 'show'); ?>" class="button-primary button-delete"><?php echo _e('Delete this carousel', 'wp_carousel'); ?></a></p>
						
		</div>
		
		<div class="clear"></div>

		<?php
		
		if ($debug)
		{
			$log[] = 'Comprobacion de recuento de errores de la funcion wp_carousel_carousel_options_page()';
			if(!empty($errors))
			{
				// Uy uy uy... ha habido errores durante la ejecución de esta función, cortemos el script y mostremos los errores de forma legible
				$log[] = 'La comprobacion del recuento de errores de la funcion wp_carousel_carousel_options_page() ha detectado que hay errores en la funcion wp_carousel_carousel_options_page()';
				echo '<h2>'.__('Errors', 'wp_carousel').'</h2><pre>';
				print_r($errors);
				echo '</pre>';
				$log[] = 'Se ha mostrado el listado de errores de la funcion wp_carousel_carousel_options_page()';
				
				echo '<h2>'.__('Log', 'wp_carousel').'</h2><pre>';
				print_r($log);
				echo '</pre>';
				
				// Avisemos de que cortamos el script
				echo '<p>El script se deja de ejecutar a partir de ahora debido a que se han detectado errores durante su ejecución</p>';
				exit; // Cortamos el script
			} else {
				// ¡Qué bien, no hay errores!
				$log[] = 'La comprobacion del recuento de errores de la funcion wp_carousel_carousel_options_page() ha determinado que no ha habido errores durante la ejecucion de wp_carousel_carousel_options_page()';
				echo '<h2>'.__('Log', 'wp_carousel').'</h2><pre>';
				print_r($log);
				echo '</pre>';
			}
		}
		
	}
	
	/*
		@Función: wp_carousel_carousel_show_carousel_item_list()
		@Versión: 2.0
		@Parámetros:
							$items (array): Contiene los elementos del carrusel actual, que parseará la función y mostrará en formato de lista.
							$debug (bool): Determina si al acabar de ejecutar la función se debe mostrar el registro o no.
		@Descripción: Muestra la lista de elementos del carrusel.
		@Añadida en la versión: 0.4	
		@Última actualización en la versión: 0.5
	*/
	
	function wp_carousel_carousel_show_carousel_item_list($items, $debug=false)
	{
		
		$log[] = 'Comprobacion del valor de $debug en wp_carousel_carousel_show_carousel_item_list()';
		if (!$debug)
		{
			$log[] = 'La comprobacion ha determinado que $debug no tiene por valor true (booleano)';
			// De momento sabemos que no estamos en modo debug, pero ¿será porque la variable es false o porque la variable no es true?
			if (is_bool($debug)) 
			{
				// Vale, todo va bien de momento, no seamos paranóicos
				$log[] = 'La comprobacion ha determinado que $debug tiene por valor false (booleano)';
			}
			else
			{
				// Vaya, parece que no íbamos desencaminados: la variable $debug tiene un valor no booleano, así que estamos ante un error. De momento almacenamos el error y ponemos esta función en modo debug, a ver si encontramos el origen del (o los) problemas
				$log[] = 'La comprobacion ha determinado que $debug tiene por valor: "'.$debug.'" (no booleano)';
				$errors[] = 'La variable $debug tiene un valor no booleano, de hecho su valor es: "'.$debug.'".';
				$log[] = 'Se ha añadido a la matriz de errores el error '.key($errors).': "'.current($errors).'"';
			}
		}
		
		// Comprobamos $items
		$log[] = 'Comprobacion del valor de $items';
		if (!is_array($items))
		{
			// ¡Vaya! ¡La variable no es una matriz!
			$log[] = 'La comprobacion del valor de $items ha determinado que no es una matriz, de hecho su valor es: "'.$items.'"';
			$errors[] = 'La variable $items no es una matriz, de hecho su valor es: "'.$items.'"';
			$log[] = 'Se ha añadido a la matriz de errores el error '.key($errors).': "'.current($errors).'"';
		}
		else
		{
			// Todo va bien :)
			$log[] = 'La comprobacion del valor de $items ha determinado que la variable es una matriz';
		}		
		
		$log[] = 'Comprobacion de recuento de errores de la funcion wp_carousel_carousel_show_carousel_item_list()';
		if(!empty($errors))
		{
			// Uy uy uy... ha habido errores durante la ejecución de esta función, cortemos el script y mostremos los errores de forma legible
			$log[] = 'La comprobacion del recuento de errores de la funcion wp_carousel_carousel_show_carousel_item_list() ha detectado que hay errores en la funcion wp_carousel_carousel_options_page()';
			echo '<h2>'.__('Errors', 'wp_carousel').'</h2><pre>';
			print_r($errors);
			echo '</pre>';
			$log[] = 'Se ha mostrado el listado de errores de la funcion wp_carousel_carousel_show_carousel_item_list()';
			
			echo '<h2>'.__('Log', 'wp_carousel').'</h2><pre>';
			print_r($log);
			echo '</pre>';
			
			// Avisemos de que cortamos el script
			echo '<p>El script se deja de ejecutar a partir de ahora debido a que se han detectado errores durante su ejecución</p>';
			exit; // Cortamos el script
		} else {
			// ¡Qué bien, no hay errores!
			$log[] = 'La comprobacion del recuento de errores de la funcion wp_carousel_carousel_options_page() ha determinado que no ha habido errores durante la ejecucion de wp_carousel_carousel_options_page()';
		}
		
		$log[] = 'Se inicia el bucle foreach';
		foreach ($items as $internal_id => $item):
			$log[] = 'Comienza a mostrarse la fila correspondiente al elemento con ID INTERNA: "'.$internal_id.'"';
		
			// Cargamos el nombre del elemento
			if ($item['TYPE'] == 4)
			{
				$item['NAME'] = wp_carousel_item_value($internal_id, $item['TYPE'], 'name', $items);
			}
			elseif ($item['TYPE'] == 7)
			{
				$item['NAME'] = __('External Carousel', 'wp_carousel');
			}
			elseif (is_numeric($item['TYPE']))
			{
				$item['NAME'] = wp_carousel_item_value($item['ID'], $item['TYPE'], 'name');
			}
			else
			{
				//eval('$item["NAME"] = '.$_SESSION['WP_CAROUSEL_EXTRAS'][$item['TYPE']]['title_function'].'("'.$item['ID'].'");');
				$item['NAME'] = '';
			}
			$log[] = 'Se le ha asignado al indice NAME de la matriz $item el valor del nombre del elemento, que es: "'.$item['NAME'].'"';
			
			// Ahora cargamos su descripcion
			if ($item['TYPE'] == 4)
			{
				$item['DESC'] = wp_carousel_item_value($internal_id, $item['TYPE'], 'desc', $items);
			}
			elseif ($item['TYPE'] == 1)
			{
				$item['DESC'] = wp_carousel_item_value($item['ID'], $item['TYPE'], 'desc', $item);
			}
			elseif ($item['TYPE'] == 7)
			{
				$item['DESC'] = sprintf(__('External Carousel from %s', 'wp_carousel'), $item['WP_CAROUSEL_EI_URL']);
			}
			elseif (is_numeric($item['TYPE']))
			{
				$item['DESC'] = wp_carousel_item_value($item['ID'], $item['TYPE'], 'desc');
			}
			else
			{
				//eval('$item["DESC"] = '.$_SESSION['WP_CAROUSEL_EXTRAS'][$item['TYPE']]['desc_function'].'("'.$item['ID'].'");');
				$item['DESC'] = '';
			}
			$log[] = 'Se le ha asignado al indice DESC de la matriz $item el valor de la descripcion del elemento, que es: "'.$item['DESC'].'"';
			
			// Ahora cargamos la URL de la imagen y del link
			if ($item['TYPE'] == 2 ||$item['TYPE'] == 3)
			{
				$item['LINK_URL'] = wp_carousel_item_value($item['ID'], $item['TYPE'], 'link_url');
				$log[] = 'Se le ha asignado al indice LINK_URL de la matriz $item el valor de la URL del enlace del elemento, que es: "'.$item['LINK_URL'].'"';
				$item['IMAGE_URL'] = wp_carousel_item_value($item['ID'], $item['TYPE'], 'image_url');
				$log[] = 'Se le ha asignado al indice IMAGE_URL de la matriz $item el valor de la URL de la imagen del elemento, que es: "'.$item['IMAGE_URL'].'"';
			}
			
			// Comprobamos si este elemento es de un tipo de la función "wp_carousel_create_internal_urls()" pueda crear un enlace hacia su página de edición mediante el uso del tipo "EDIT_URL"
			if ($item['TYPE'] != 1 && $item['TYPE'] != 4)
			{
				// Es un elemento con un link hacia su página de edición sencillo de calcular
				$log[] = 'Este elemento es de un tipo que es reconocido por el tipo "EDIT_URL" de la funcion "wp_carousel_create_internal_urls()", de hecho, es de tipo "'.$item['TYPE'].'"';
				$item['HAS_WP_EDIT_URL'] = true;
			}
			elseif ($item['TYPE'] == 1)
			{
				$log[] = 'Este elemento es una categoria y por tanto no admite edicion alguna';
				$item['HAS_WP_EDIT_URL'] = false;
			}
			else
			{
				// Hoy no estamos de suerte, el link hacia la página de edición de este elemento es más complejo de lo que querríamos
				$log[] = 'Este elemento es de un tipo que NO es reconocido por el tipo "EDIT_URL" de la funcion "wp_carousel_create_internal_urls()", de hecho, es de tipo "'.$item['TYPE'].'"';
				$item['HAS_WP_EDIT_URL'] = false;
			}
			
			// Comprobemos qué tipo de enlace hacia su página de edición hemos dicho que tiene
			if ($item['HAS_WP_EDIT_URL'])
			{
				// Tiene un enlace fácil, así que lo almacenamos ya en $item['EDIT_URL']
				$item['EDIT_URL'] = wp_carousel_create_internal_urls('EDIT_URL:'.$item['ID']);
				$log[] = 'Se ha establecido la URL de la pagina de edicion de este elemento en: "'.$item['EDIT_URL'].'"';
			}
			
			// Comprobemos si tenía un link de algún tipo hacia su página de edición (las categorías no tienen este tipo de link)
			if (!$item['HAS_WP_EDIT_URL'] && $item['TYPE'] != 4)
			{
				// Vale, no tiene un link sencillo y no es un contenido personalizable: estamos ante un elemento sin página de edición
				$item['HAS_EDIT_PAGE_URL'] = false;
			}
			else
			{
				// Bien, o es un elemento sencillo, o es un contenido personalizable, así que tiene página de edición
				$item['HAS_EDIT_PAGE_URL'] = true;
				
				if ($item['TYPE'] == 4)
				{
					$item['EDIT_URL'] = wp_carousel_create_internal_urls('EDIT_COSTUMIZED_CONTENT_URL:'.$internal_id);
					$log[] = 'Se ha almacenado la URL a la pagina de edicion de este contenido personalizable en en indice EDIT_URL de la matriz $items, el valor del cual es: "'.$item['EDIT_URL'].'"';
				}
				
			}

			?>
							<div id="item_<?php echo $drag_drop_id; ?>" class="item">
								<div class="handle">
									<h4>
										<?php if (is_numeric($item['TYPE'])) _e(wp_carousel_type_name($item['TYPE'], 'get'), 'wp_carousel'); else echo $_SESSION['WP_CAROUSEL_EXTRAS'][$item['TYPE']]['name']; ?>
									</h4>
								</div>
								<div class="item_content">
								
								<form method="post" class="wp_carousel_ajax_form" onsubmit="return wp_carousel_update_ajax_item()">
									
									<?php if ($item['TYPE'] != 4): ?>	
									<?php if ($item['TYPE'] != 7) { ?>	
										<?php if (is_numeric($item['TYPE'])) { ?>
											<dl>
												<dt><?php _e(wp_carousel_type_name($item['TYPE'], 'get'), 'wp_carousel'); ?></dt>
												<dd><?php wp_carousel_dropdown_type_items($item['TYPE'], $item['ID']); ?></dd>
											</dl>
										<?php } else { ?>
											<dl>
												<dt><?php _e('Item', 'wp_carousel'); ?></dt>
												<dd><input type="text" name="category_id" id="category_id" value="<?php echo $item['ID']; ?>" /></dd>
											</dl>
										<?php } ?>
									<?php } else { ?>											
									<dl>
										<dt><?php _e('URL', 'wp_carousel'); ?></dt>
										<dd><input type="text" name="wp_carousel_ei_url" id="wp_carousel_ei_url" value="<?php echo $item['WP_CAROUSEL_EI_URL']; ?>" /></dd>
									</dl>
									<dl>
										<dt><?php _e('Carousel\'s ID', 'wp_carousel'); ?></dt>
										<dd><input type="text" name="wp_carousel_ei_id" id="wp_carousel_ei_id" value="<?php echo $item['WP_CAROUSEL_EI_ID']; ?>" /></dd>
									</dl>
									
									<?php 
																												
										if (fopen($item['WP_CAROUSEL_EI_URL'], 'r')) {
												
											$wp_carousel_ei_content = file_get_contents($item['WP_CAROUSEL_EI_URL'].'?carousel_id='.$item['WP_CAROUSEL_EI_ID']);
											if ($wp_carousel_ei_content != 'ERROR:WP_CAROUSEL_EI:FALSE' && $wp_carousel_ei_content != 'ERROR:$_GET["carousel_id"]:NOT-SET' && $wp_carousel_ei_content != 'ERROR:$_GET["carousel_id"]:IS-NOT-A-CAROUSEL' && WP_CAROUSEL_EI)
											{
												echo '<p>'.__('This external carousel can be loaded', 'wp_carousel').'</p>';
											}
											else
											{
												if ($wp_carousel_ei_content == 'ERROR:WP_CAROUSEL_EI:FALSE')
												{
													echo '<p>'.__('This carousel can\'t be loaded because its content is not shared', 'wp_carousel').'</p>';
												}
												if ($wp_carousel_ei_content == 'ERROR:$_GET["carousel_id"]:NOT-SET')
												{
													echo '<p>'.__('This carousel can\'t be loaded because the carousel\'s ID hasn\'t been set', 'wp_carousel').'</p>';
												}
												if ($wp_carousel_ei_content == 'ERROR:$_GET["carousel_id"]:IS-NOT-A-CAROUSEL')
												{
													echo '<p>'.__('This carousel can\'t be loaded because it doesn\'t exists', 'wp_carousel').'</p>';
												}
												if (WP_CAROUSEL_EI == 'ERROR:WP_CAROUSEL_EI:FALSE')
												{
													echo '<p>'.__('Your carousel must be set in shared mode to be able to show external carousels', 'wp_carousel').'</p>';
												}
											}
											
										}
										else
										{
											echo '<p>'.__('This carousel can\'t be loaded because WP Carousel can\'t load that URL', 'wp_carousel').'</p>';
										}
									?>
																		
									<input type="hidden" name="order" id="order" value="0" />
									<input type="hidden" name="type" id="type" value="7" />
									<input type="hidden" name="posts_order" id="posts_order" value="first_new" />
									<input type="hidden" name="posts_number" id="posts_number" value="0" />
									<input type="hidden" name="show_in_loop" id="show_in_loop" value="yes" />
									<input type="hidden" name="category_id" id="category_id" value="0" />
									<?php } ?>
									
									<?php if ($item['TYPE'] == 1 || $item['TYPE'] == 5 || $item['TYPE'] == 6) { ?>
									<dl>
										<dt><?php _e('Content\'s order', 'wp_carousel'); ?></dt>
										<dd>
											<select id="posts_order" name="posts_order">
												<option id="first_old" value="first_old"<?php if ($item['POSTS_ORDER'] == 'first_old') echo ' selected="selected"'; ?>><?php _e('Show the oldest content first', 'wp_carousel'); ?></option>
												<option id="first_new" value="first_new"<?php if ($item['POSTS_ORDER'] == 'first_new') echo ' selected="selected"'; ?>><?php _e('Show the newest content first', 'wp_carousel'); ?></option>
											</select>		
										</dd>
									</dl>
																		
									<dl>
										<dt><?php _e('Number of items', 'wp_carousel'); ?></dt>
										<dd><input type="text" name="posts_number" id="posts_number" value="<?php echo $item['POSTS_NUMBER']; ?>" /></dd>
									</dl>
									<?php } ?>
																		
									<?php if ($item['TYPE'] == 1 || $item['TYPE'] == 2 || $item['TYPE'] == 5 || $item['TYPE'] == 6) { ?>
									<dl>
										<dt><?php _e('Must I show this element in the loop?', 'wp_carousel'); ?></dt>
										<dd><input type="checkbox" name="show_in_loop" id="show_in_loop" value="yes"<?php if ($item['SHOW'] === "yes") { echo ' checked="checked"'; } ?> /></dd>
									</dl>
									<?php } ?>
									<?php else: ?>
									<dl>
										<dt><?php echo _e('Image URL', 'wp_carousel'); ?></dt>
										<dd><input type="text" name="url_image" id="url_image" value="<?php echo $item['IMAGE_URL']; ?>" /></dd>
									</dl>
									
									<dl>
										<dt><?php echo _e('Link URL', 'wp_carousel'); ?></dt>
										<dd><input type="text" name="url_link" id="url_link" value="<?php echo $item['LINK_URL']; ?>" /></dd>
									</dl>
									
									<input type="text" name="post_title" size="30" tabindex="1" id="title" autocomplete="off" value="<?php echo $item['NAME']; ?>" />
									
									<br /><br />
									
									<textarea rows='10' cols='26' name='desc' tabindex='2' id='desc'><?php echo $item['DESC']; ?></textarea>
									
									<div class="clear_dl"></div>
									
									<input type="hidden" name="posts_order" id="posts_order" value="first_new" />
									<input type="hidden" name="posts_number" id="posts_number" value="0" />
									<input type="hidden" name="show_in_loop" id="show_in_loop" value="yes" />
									<input type="hidden" name="category_id" id="category_id" value="0" />
									<?php endif; ?>
									
									<div class="clear_dl"></div>
																											
									<input type="hidden" name="order" id="order" value="<?php echo $item['ORDER']; ?>" />
									<input type="hidden" name="type" id="type" value="<?php echo $item['TYPE']; ?>" />
									
									<?php if($item['TYPE'] == 3) { ?>
										<input type="hidden" name="posts_order" id="posts_order" value="first_new" />
										<input type="hidden" name="posts_number" id="posts_number" value="0" />
										<input type="hidden" name="show_in_loop" id="show_in_loop" value="yes" />
									<?php } ?>
																																																												
								</form>
								
																														
							</div>
						</div>
			<?php
			$log[] = 'Ha acabado de mostrarse la fila correspondiente al elemento con ID INTERNA: "'.$internal_id.'"';
		endforeach;
		$log[] = 'El bucle foreach ha finalizado';
		
	}
	
	/*
		@Función: wp_carousel_type_name()
		@Versión: 1.0
		@Parámetros:
							$id: ID del tipo de elemento del carrusel.
							$mode (get | show | get-list | show-list): Sólo admite los valores anteriores. Dependiendo del valor, devolverá el nombre (get), lo mostrará (show), devolverá la lista de tipos en modo de menú desplegable (get-list) o la mostrará (show-list).
							$debug (bool): Determina si al acabar de ejecutar la función se debe mostrar el registro o no.
		@Descripción: Devuelve (o muestra) el nombre del tipo de elemento del carrusel a partir de la ID del tipo de elemento (por ejemplo, el tipo de elemento cuya ID es 1 es una categoría).
		@Nota: Sirve sólo para hacer correspondencias entre ID del tipo y nombre del mismo, así si en algún momento añado más IDs o modifico alguna, sólo cambio en una función y no en todas :) .
		@Añadida en la versión: 0.4		
	*/
	
	function wp_carousel_type_name($id, $mode='get', $debug=false)
	{
		
		// Comprobamos $id
		$log[] = 'Comprobacion del valor de $id';
		if (!is_numeric($id))
		{
			// ¡Vaya! ¡La variable no es un número!
			$log[] = 'La comprobacion del valor de $id ha determinado que no es un numero, de hecho su valor es: "'.$id.'"';
			$errors[] = 'La variable $id no es un numero, de hecho su valor es: "'.$id.'"';
			$log[] = 'Se ha añadido a la matriz de errores el error '.key($errors).': "'.current($errors).'"';
		}
		else
		{
			// Todo va bien :)
			$log[] = 'La comprobacion del valor de $id ha determinado que la variable es un numero';
		}
		
		$log[] = 'Comprobacion del valor de $debug en wp_carousel_type_name()';
		if (!$debug)
		{
			$log[] = 'La comprobacion ha determinado que $debug no tiene por valor true (booleano)';
			// De momento sabemos que no estamos en modo debug, pero ¿será porque la variable es false o porque la variable no es true?
			if (is_bool($debug)) 
			{
				// Vale, todo va bien de momento, no seamos paranóicos
				$log[] = 'La comprobacion ha determinado que $debug tiene por valor false (booleano)';
			}
			else
			{
				// Vaya, parece que no íbamos desencaminados: la variable $debug tiene un valor no booleano, así que estamos ante un error. De momento almacenamos el error y ponemos esta función en modo debug, a ver si encontramos el origen del (o los) problemas
				$log[] = 'La comprobacion ha determinado que $debug tiene por valor: "'.$debug.'" (no booleano)';
				$errors[] = 'La variable $debug tiene un valor no booleano, de hecho su valor es: "'.$debug.'".';
				$debug = true;
				$log[] = 'Se ha añadido a la matriz de errores el error '.key($errors).': "'.current($errors).'"';
			}
		}
		
		switch ($id)
		{
			case '1':
				$log[] = 'La variable $id indica que se trata de una categoria';
				$return = __('Category', 'wp_carousel');
				break;
			case '2':
				$log[] = 'La variable $id indica que se trata de un articulo';
				$return = __('Post', 'wp_carousel');
				break;
			case '3':
				$log[] = 'La variable $id indica que se trata de una pagina';
				$return = __('Page', 'wp_carousel');
				break;
			case '4':
				$log[] = 'La variable $id indica que se trata de contenido personalizable';
				$return = __('Customized Content', 'wp_carousel');
				break;
			case '5':
				$log[] = 'La variable $id indica que se trata de una etiqueta';
				$return = __('Tag', 'wp_carousel');
				break;
			case '6':
				$log[] = 'La variable $id indica que se trata de un autor';
				$return = __('Author', 'wp_carousel');
				break;
			case '7':
				$log[] = 'La variable $id indica que se trata de un carrusel externo';
				$return = __('External Carousel', 'wp_carousel');
				break;
			default:
				$log[] = 'La variable $id tiene un valor no contemplado en la lista de ID -> Tipos, de hecho su valor es: "'.$id.'"';
				$errors[] = 'La variable $id tiene un valor no contemplado en la lista de ID -> Tipos, de hecho su valor es: "'.$id.'"';
				$log[] = 'Se ha añadido a la matriz de errores el error '.key($errors).': "'.current($errors).'"';
				$return = '';
				break;
		}
		
		// En este caso omitiremos los errores, ya que el switch hace que no nos molesten, excepto si estamos en modo debug, entonces sí que los mostraremos, eso sí, antes del return
		
		if ($debug && ($mode == 'get' || $mode == 'get-list'))
		{
			$log[] = 'Comprobacion de recuento de errores de la funcion wp_carousel_type_name()';
			if(!empty($errors))
			{
				// Uy uy uy... ha habido errores durante la ejecución de esta función, cortemos el script y mostremos los errores de forma legible
				$log[] = 'La comprobacion del recuento de errores de la funcion wp_carousel_type_name() ha detectado que hay errores en la funcion wp_carousel_type_name()';
				echo '<h2>'.__('Errors', 'wp_carousel').'</h2><pre>';
				print_r($errors);
				echo '</pre>';
				$log[] = 'Se ha mostrado el listado de errores de la funcion wp_carousel_type_name()';
				
				echo '<h2>'.__('Log', 'wp_carousel').'</h2><pre>';
				print_r($log);
				echo '</pre>';
				
				// Avisemos de que cortamos el script
				echo '<p>El script se deja de ejecutar a partir de ahora debido a que se han detectado errores durante su ejecución</p>';
				exit; // Cortamos el script
			} else {
				// ¡Qué bien, no hay errores!
				$log[] = 'La comprobacion del recuento de errores de la funcion wp_carousel_type_name() ha determinado que no ha habido errores durante la ejecucion de wp_carousel_type_name()';
				echo '<h2>'.__('Log', 'wp_carousel').'</h2><pre>';
				print_r($log);
				echo '</pre>';
			}
		}
		
		$log[] = 'Procedemos a crear la lista de tipos';
		$list = '<option value="1">'.__('Category', 'wp_carousel').'</option>';
		$list .= '<option value="2">'.__('Post', 'wp_carousel').'</option>';
		$list .= '<option value="3">'.__('Page', 'wp_carousel').'</option>';
		$log[] = 'La lista de tipos se ha creado';
		
		switch ($mode)
		{
			case 'get':
				$log[] = 'La variable $mode tiene por valor: "get", asi que procederemos a devolverla';
				return $return;
				break;
			case 'show':
				$log[] = 'La variable $mode tiene por valor: "show", asi que procederemos a mostrarla';
				echo $return;
				break;
			case 'get-list':
				$log[] = 'La variable $mode tiene por valor: "get-list", asi que procederemos a devolver la lista de tipos';
				return $list;
				break;
			case 'show-list':
				$log[] = 'La variable $mode tiene por valor: "show-list", asi que procederemos a mostrar la lista de tipos';
				echo $list;
				break;
			default:
				$log[] = 'La variable $mode no tiene por valor ni "get" ni "show" ni "get-list" ni "show-list"';
				$errors[] = 'La variable $mode no tiene uno de los dos valores aceptados ("get" | "show" | "get-list" | "show-list"), de hecho tiene por valor: "'.$mode.'"';
				$log[] = 'Se ha añadido a la matriz de errores el error '.key($errors).': "'.current($errors).'"';
				break;
		}
		
		// En este caso omitiremos los errores, ya que el switch hace que no nos molesten, excepto si estamos en modo debug, entonces sí que los mostraremos, como el modo no es "get", podemos mostrar el log cuando queramos
		
		if ($debug && ($mode != 'get' || $mode != 'get-list'))
		{
			$log[] = 'Comprobacion de recuento de errores de la funcion wp_carousel_type_name()';
			if(!empty($errors))
			{
				// Uy uy uy... ha habido errores durante la ejecución de esta función, cortemos el script y mostremos los errores de forma legible
				$log[] = 'La comprobacion del recuento de errores de la funcion wp_carousel_type_name() ha detectado que hay errores en la funcion wp_carousel_type_name()';
				echo '<h2>'.__('Errors', 'wp_carousel').'</h2><pre>';
				print_r($errors);
				echo '</pre>';
				$log[] = 'Se ha mostrado el listado de errores de la funcion wp_carousel_type_name()';
				
				echo '<h2>'.__('Log', 'wp_carousel').'</h2><pre>';
				print_r($log);
				echo '</pre>';
				
				// Avisemos de que cortamos el script
				echo '<p>El script se deja de ejecutar a partir de ahora debido a que se han detectado errores durante su ejecución</p>';
				exit; // Cortamos el script
			} else {
				// ¡Qué bien, no hay errores!
				$log[] = 'La comprobacion del recuento de errores de la funcion wp_carousel_type_name() ha determinado que no ha habido errores durante la ejecucion de wp_carousel_type_name()';
				echo '<h2>'.__('Log', 'wp_carousel').'</h2><pre>';
				print_r($log);
				echo '</pre>';
			}
		}
		
	}
	
	/*
		@Función: wp_carousel_first_image()
		@Versión: 2.1
		@Parámetros:
							$id: ID del artículo o página de la que se debe extraer la primera imagen.
							$mode (get | show): Sólo admite los valores anteriores. Dependiendo del valor, devolverá el nombre (get) o lo mostrará (show).
		@Descripción: Devuelve (o muestra) la URL de la primera imagen del artículo o de la página con ID $id.
		@Nota: Sí, esta es la única función copiada de las versiones anteriores a la 0.4 de WP Carousel
		@Añadida en la versión: 0.1
		@Última actualización en la versión: 0.5		
	*/
	
	function wp_carousel_first_image($id, $mode='get') {
		
		$image_url = '';
		
		ob_start();
		ob_end_clean();
		
		$post = get_post($id);
		
		if (is_object($post) && isset($post->post_content))
		{
			$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
			if (isset($matches[1][0])) $image_url = $matches[1][0];
		}
		
		if(empty($image_url)){ // Define una imagen por defecto
			$image_url = "";
		}
		
		switch ($mode)
		{
			case 'get':
				return $image_url;
				break;
			case 'show':
				echo $image_url;
				break;
			default:
				return $image_url;
				break;
		}
		
	}
	
	/*
		@Función: wp_carousel_item_value()
		@Versión: 2.0
		@Parámetros:
							$id: ID del elemento del carrusel (ID del contenido, NO ID INTERNA). En el caso en el que estemos buscando la información de un contenido personalizable se utilizará la ID INTERNA.
							$type: ID del tipo de elemento del que se trata (ver función wp_carousel_type_name()).
							$value (name | desc | image_url | link_url): Sólo admite los valores anteriores. Tipo de valor que se quiere obtener.
							$items: Matriz principal del contenido del carrusel (ojo, es la matriz que contiene los elementos, no la matriz que contiene los carruseles).
							$mode (get | show): Sólo admite los valores anteriores. Dependiendo del valor, devolverá el nombre (get) o lo mostrará (show).
							$debug (bool): Determina si al acabar de ejecutar la función se debe mostrar el registro o no.
		@Descripción: Devuelve (o muestra) algún valor referente al elemento de tipo $type, con $id, del carrusel.
		@Nota: Aunque en realidad la función es capaz de funcionar con cualquier tipo de contenido, sólo debe aplicarse al contenido personalizable, ya que éste es el único que no tiene una ID fija.
		@Añadida en la versión: 0.4
		@Última actualización en la versión: 0.5
	*/
	
	function wp_carousel_item_value($id, $type, $value, $items=array(), $mode='get', $debug=false)
	{
		// Comprobamos $id
		$log[] = 'Comprobacion del valor de $id';
		if (!is_numeric($id))
		{
			// ¡Vaya! ¡La variable no es un número!
			$log[] = 'La comprobacion del valor de $id ha determinado que no es un numero, de hecho su valor es: "'.$id.'"';
			$errors[] = 'La variable $id no es un numero, de hecho su valor es: "'.$id.'"';
			$log[] = 'Se ha añadido a la matriz de errores el error '.key($errors).': "'.current($errors).'"';
		}
		else
		{
			// Todo va bien :)
			$log[] = 'La comprobacion del valor de $id ha determinado que la variable es un numero';
		}
		
		// Comprobamos $type
		$log[] = 'Comprobacion del valor de $type';
		if (!is_numeric($type))
		{
			// ¡Vaya! ¡La variable no es un número!
			$log[] = 'La comprobacion del valor de $type ha determinado que no es un numero, de hecho su valor es: "'.$type.'"';
			$errors[] = 'La variable $type no es un numero, de hecho su valor es: "'.$type.'"';
			$log[] = 'Se ha añadido a la matriz de errores el error '.key($errors).': "'.current($errors).'"';
		}
		else
		{
			// Todo va bien :)
			$log[] = 'La comprobacion del valor de $type ha determinado que la variable es un numero';
		}
		
		$log[] = 'Comprobacion del valor de $debug en wp_carousel_item_value()';
		if (!$debug)
		{
			$log[] = 'La comprobacion ha determinado que $debug no tiene por valor true (booleano)';
			// De momento sabemos que no estamos en modo debug, pero ¿será porque la variable es false o porque la variable no es true?
			if (is_bool($debug)) 
			{
				// Vale, todo va bien de momento, no seamos paranóicos
				$log[] = 'La comprobacion ha determinado que $debug tiene por valor false (booleano)';
			}
			else
			{
				// Vaya, parece que no íbamos desencaminados: la variable $debug tiene un valor no booleano, así que estamos ante un error. De momento almacenamos el error y ponemos esta función en modo debug, a ver si encontramos el origen del (o los) problemas
				$log[] = 'La comprobacion ha determinado que $debug tiene por valor: "'.$debug.'" (no booleano)';
				$errors[] = 'La variable $debug tiene un valor no booleano, de hecho su valor es: "'.$debug.'".';
				$debug = true;
				$log[] = 'Se ha añadido a la matriz de errores el error '.key($errors).': "'.current($errors).'"';
			}
		}
		
		$text_temp = __('This category has %s posts. The limit is set to %s posts.', 'wp_carousel');
		
		$text_temp_oldest = __('The oldest posts will be showed first', 'wp_carousel');
		$text_temp_newest = __('The newest posts will be showed first', 'wp_carousel');
		
		switch ($type)
		{
			case '1':
				$log[] = 'La variable $type indica que se trata de una categoria';
				// Cargamos en la variable $category todo el contenido de la categoría
				$category = &get_category($id);
				// Cargamos en la variable $return['name'] el nombre de la categoría
				$return['name'] = $category->cat_name;
				$log[] = 'Se ha cargado el nombre del elemento en la variable $return[\'name\'], que ahora vale: "'.$return['name'].'"';
				// Podríamos cargar la descripción de la categoría, pero al fin y al cabo se muestran artículos de una categoría, así que mostraremos un recuento del contenido que se mostrará
				
				// POEDIT_ERROR:  He tenido problemas al ejecutar __('This category has %s posts', 'wp_carousel') en la posición correspondiente, así que me he visto obligado a crear una variable que almacene su valor para mostrarlo más adelante.
				
				if (!isset($items['POSTS_NUMBER']))
				{
					$items['POSTS_NUMBER'] = '10';
				}
								
				$return['desc'] = '<p>'.sprintf($text_temp, $category->category_count, $items['POSTS_NUMBER']).'</p><p>';
				
				if (isset($items['POSTS_ORDER']))
				{
					if ($items['POSTS_ORDER']== 'first_old')
					{
						$return['desc'].= $text_temp_oldest;
					}
					else
					{
						$return['desc'].= $text_temp_newest;
					}
				}
				else
				{
					$return['desc'].= $text_temp_newest;
				}
				
				$return['desc'].= '</p>';
				
				$log[] = 'Se ha cargado la descripcion del elemento en la variable $return[\'desc\'], que ahora vale: "'.$return['desc'].'"';
				break;
			case '2':
				$log[] = 'La variable $type indica que se trata de un articulo';
				// Cargamos en $return['name'] el nombre del elemento
				$return['name'] = get_the_title($id);
				$log[] = 'Se ha cargado el nombre del elemento en la variable $return[\'name\'], que ahora vale: "'.$return['name'].'"';
				// Cargamos en $return['image_url'] la URL de la imagen
				$return['image_url'] = '';
				if (function_exists('get_the_post_thumbnail'))
				{
					$log[] = 'La funcion get_the_post_image existe, se procede a cargarla como imagen del articulo';
					$img_url_temp = get_post_thumbnail_id($id);
					$return['image_url'] = wp_get_attachment_url($img_url_temp);
					$log[] = 'Se ha cargado la URL de la miniatura del elemento en la variable $return[\'image_url\'], que ahora vale: "'.$return['image_url'].'"';
				}
				if ($return['image_url'] == '')
				{
					$log[] = 'El articulo no tenia miniatura o WordPress no admite esta funcionalidad, se procede a cargar la URL de la imagen del  campo personalizado: "wp_carousel_image_url"';
					$return['image_url'] = get_post_meta($id, 'wp_carousel_image_url', true);
					$log[] = 'El valor del campo personalizado "wp_carousel_image_url" es: "'.$return['image_url'].'"';
				}
				if ($return['image_url'] == '')
				{
					$log[] = 'El campo personalizado no tenía un valor adecuado, se toma la primera imagen del articulo para el carrusel';
					$return['image_url'] = wp_carousel_first_image($id, 'get');
					$log[] = 'La primera imagen del articulo tiene por URL: "'.$return['image_url'].'"';
				}
				$log[] = 'Ha finalizado la obtencion de la URL de la imagen del articulo, que es: "'.$return['image_url'].'"';
				// Obtenemos la URL del enlace del artículo
				$return['link_url'] = get_post_meta($id, 'wp_carousel_link_url', true);
				$log[] = 'La URL indicada en el campo personalizado "wp_carousel_link_url" es: "'.$return['link_url'].'"';
				if ($return['link_url'] == '')
				{
					$return['link_url'] = get_permalink($id);
					$log[] = 'La URL del articulo se ha tomado por URL del enlace, que es: "'.$return['link_url'].'"';
				}
				// Comprobamos que el elemento tiene extracto
				$post_excerpt = has_excerpt($id);
				// Cargamos el extracto del elemento en la variable $post_excerpt. Si el elemento no tiene extracto, entonces $post_excerpt tendrá por valor false (booleano)
				if ($post_excerpt)
				{
					$post_temp = &get_post($id);
					$post_excerpt = $post_temp->post_excerpt;
					$log[] = 'Se ha cargado el extracto del elemento en la variable $post_excerpt, que ahora vale: "'.$post_excerpt.'"';
				}
				else
				{
					$log[] = 'Este elemento no tiene extracto';
				}
				// Ahora cargamos el valor del campo personalizado
				$post_meta_carousel_text = get_post_meta($id, 'wp_carousel_carousel_text', true);
				$log[] = 'Se ha cargado el campo personalizado "wp_carousel_carousel_text" del elemento en la variable $post_meta_carousel_text, que ahora vale: "'.$post_meta_carousel_text.'"';
				// Si el campo personalizado no está en blanco, mostraremos como descripción su valor, si está en blanco, mostraremos el extracto
				if ($post_meta_carousel_text == '')
				{
					// El campo personalizado está en blanco, así que mostraremos el extracto
					$log[] = 'Se ha detectado que el campo personalizado "wp_carousel_carousel_text" no tiene valor';
					$return['desc'] = $post_excerpt;
					$log[] = 'Se ha asignado como descripcion el extracto del elemento';
				}
				else
				{
					$log[] = 'Se ha detectado que el campo personalizado "wp_carousel_carousel_text" tiene valor';
					$return['desc'] = $post_meta_carousel_text;
					$log[] = 'Se ha asignado como descripcion el campo personalizado "wp_carousel_carousel_text"';
				}
				break;
			case '3':
				$log[] = 'La variable $type indica que se trata de una pagina';
				// Cargamos en $return['name'] el nombre del elemento
				$return['name'] = get_the_title($id);
				$log[] = 'Se ha cargado el nombre del elemento en la variable $return[\'name\'], que ahora vale: "'.$return['name'].'"';
				// Cargamos en $return['image_url'] la URL de la imagen
				$return['image_url'] = '';
				if (function_exists('get_the_post_thumbnail'))
				{
					$log[] = 'La funcion get_the_post_image existe, se procede a cargarla como imagen del articulo';
					$img_url_temp = get_post_thumbnail_id($id, 'thumbnail');
					$return['image_url'] = wp_get_attachment_url($img_url_temp);
					$log[] = 'Se ha cargado la URL de la miniatura del elemento en la variable $return[\'image_url\'], que ahora vale: "'.$return['image_url'].'"';
				}
				if ($return['image_url'] == '')
				{
					$log[] = 'El articulo no tenia miniatura o WordPress no admite esta funcionalidad, se procede a cargar la URL de la imagen del  campo personalizado: "wp_carousel_image_url"';
					$return['image_url'] = get_post_meta($id, 'wp_carousel_image_url', true);
					$log[] = 'El valor del campo personalizado "wp_carousel_image_url" es: "'.$return['image_url'].'"';
				}
				if ($return['image_url'] == '')
				{
					$log[] = 'El campo personalizado no tenía un valor adecuado, se toma la primera imagen del articulo para el carrusel';
					$return['image_url'] = wp_carousel_first_image($id, 'get');
					$log[] = 'La primera imagen del articulo tiene por URL: "'.$return['image_url'].'"';
				}
				$log[] = 'Ha finalizado la obtencion de la URL de la imagen del articulo, que es: "'.$return['image_url'].'"';
				// Obtenemos la URL del enlace del artículo
				$return['link_url'] = get_post_meta($id, 'wp_carousel_link_url', true);
				$log[] = 'La URL indicada en el campo personalizado "wp_carousel_link_url" es: "'.$return['link_url'].'"';
				if ($return['link_url'] == '')
				{
					$return['link_url'] = get_permalink($id);
					$log[] = 'La URL del articulo se ha tomado por URL del enlace, que es: "'.$return['link_url'].'"';
				}
				// Ahora cargamos el valor del campo personalizado
				$page_meta_carousel_text = get_post_meta($id, 'wp_carousel_carousel_text', true);
				$log[] = 'Se ha cargado el campo personalizado "wp_carousel_carousel_text" del elemento en la variable $page_meta_carousel_text, que ahora vale: "'.$page_meta_carousel_text.'"';
				$return['desc'] = $page_meta_carousel_text;
				$log[] = 'Se ha asignado como descripcion el campo personalizado "wp_carousel_carousel_text"';
				break;
			case '4':
				$log[] = 'La variable $type indica que se trata de contenido personalizable';
				if (isset($items[$id]['TITLE'])) 
				{
					$return['name'] = $items[$id]['TITLE'];
				} 
				else
				{
					$log[] = 'La matriz $items[\''.$id.'\'] no contiene el indice TITLE';
					$errors[] = 'La matriz $items[\''.$id.'\'] no contiene el indice TITLE';
					$log[] = 'Se ha añadido a la matriz de errores el error '.key($errors).': "'.current($errors).'"';
					$return['name'] = '';
				}
				if (isset($items[$id]['DESC'])) 
				{
					$return['desc'] = $items[$id]['DESC'];
				} 
				else
				{
					$log[] = 'La matriz $items[\''.$id.'\'] no contiene el indice DESC';
					$errors[] = 'La matriz $items[\''.$id.'\'] no contiene el indice DESC';
					$log[] = 'Se ha añadido a la matriz de errores el error '.key($errors).': "'.current($errors).'"';
					$return['desc'] = '';
				}
				if (isset($items[$id]['IMAGE_URL'])) 
				{
					$return['image_url'] = $items[$id]['IMAGE_URL'];
				} 
				else
				{
					$log[] = 'La matriz $items[\''.$id.'\'] no contiene el indice IMAGE_URL';
					$errors[] = 'La matriz $items[\''.$id.'\'] no contiene el indice IMAGE_URL';
					$log[] = 'Se ha añadido a la matriz de errores el error '.key($errors).': "'.current($errors).'"';
					$return['image_url'] = '';
				}
				if (isset($items[$id]['LINK_URL'])) 
				{
					$return['link_url'] = $items[$id]['LINK_URL'];
				} 
				else
				{
					$log[] = 'La matriz $items[\''.$id.'\'] no contiene el indice LINK_URL';
					$errors[] = 'La matriz $items[\''.$id.'\'] no contiene el indice LINK_URL';
					$log[] = 'Se ha añadido a la matriz de errores el error '.key($errors).': "'.current($errors).'"';
					$return['link_url'] = '';
				}
				break;
			case 5:
				$log[] = 'La variable $type indica que se trata de una tag';
				// Cargamos en la variable $tag todo el contenido de la etiqueta
				$tag = &get_tag($id);

				// Cargamos en la variable $return['name'] el nombre de la etiqueta
				$return['name'] = $tag->name;
				$log[] = 'Se ha cargado el nombre del elemento en la variable $return[\'name\'], que ahora vale: "'.$return['name'].'"';
				// Podríamos cargar la descripción de la etiqueta, pero al fin y al cabo se muestran artículos de una etiqueta, así que mostraremos un recuento del contenido que se mostrará
								
				if (!isset($items['POSTS_NUMBER']))
				{
					$items['POSTS_NUMBER'] = '10';
				}
								
				$return['desc'] = '<p>'.sprintf(__('This tag has %s posts', 'wp_carousel'), $tag->count).'</p>';
				
				$log[] = 'Se ha cargado la descripcion del elemento en la variable $return[\'desc\'], que ahora vale: "'.$return['desc'].'"';
				break;
			case 6:
				$log[] = 'La variable $type indica que se trata de un autor';

				// Cargamos en la variable $return['name'] el nombre para mostrar del autor
				$return['name'] = get_the_author_meta('display_name', $id);
				$log[] = 'Se ha cargado el nombre del elemento en la variable $return[\'name\'], que ahora vale: "'.$return['name'].'"';
								
				// Cargamos la descripcion del autor en la variable $return['desc']
				$return['desc'] = get_the_author_meta('user_description ', $id);
				$log[] = 'Se ha cargado la descripcion del elemento en la variable $return[\'desc\'], que ahora vale: "'.$return['desc'].'"';
				break;
			default:
				if (isset($_SESSION['WP_CAROUSEL_EXTRAS'][$type]))
				{
					$log[] = 'La variable $type se corresponde con un extra de WP Carousel, en concreto es: "'.$type.'"';
					eval('$return["name"] = '.$_SESSION['WP_CAROUSEL_EXTRAS'][$type]['title_function'].'("'.$id.'");');
					eval('$return["desc"] = '.$_SESSION['WP_CAROUSEL_EXTRAS'][$type]['desc_function'].'("'.$id.'");');
					eval('$return["link_url"] = '.$_SESSION['WP_CAROUSEL_EXTRAS'][$type]['link_url_function'].'("'.$id.'");');
					eval('$return["image_url"] = '.$_SESSION['WP_CAROUSEL_EXTRAS'][$type]['image_url_function'].'("'.$id.'");');
				}
				else
				{
					$log[] = 'La variable $type tiene un valor no contemplado en la lista de ID -> Tipos, de hecho su valor es: "'.$type.'"';
					$errors[] = 'La variable $type tiene un valor no contemplado en la lista de ID -> Tipos, de hecho su valor es: "'.$type.'"';
					$log[] = 'Se ha añadido a la matriz de errores el error '.key($errors).': "'.current($errors).'"';
				}
				break;
		}
		
		switch ($value)
		{
			case 'name':
				$log[] = 'La variable $value tiene por valor: "name", asi que procederemos a devolverla';
				break;
			case 'desc':
				$log[] = 'La variable $value tiene por valor: "desc", asi que procederemos a mostrarla';
				break;
			case 'link_url':
				$log[] = 'La variable $value tiene por valor: "link_url", asi que procederemos a mostrarla';
				break;
			case 'image_url':
				$log[] = 'La variable $value tiene por valor: "image_url", asi que procederemos a mostrarla';
				break;
			default:
				$log[] = 'La variable $value no tiene por valor ni "name" ni "desc" ni "link_url" ni "image_url"';
				$errors[] = 'La variable $value no tiene uno de los dos valores aceptados ("name" | "desc" | "link_url" | "image_url"), de hecho tiene por valor: "'.$value.'"';
				$log[] = 'Se ha añadido a la matriz de errores el error '.key($errors).': "'.current($errors).'"';
				$value = 'name';
				$log[] = 'Se ha establecido el valor de la varaible $value en "name" para evitar posibles conflictos';
				break;
		}
		
		// En este caso omitiremos los errores, ya que el switch hace que no nos molesten, excepto si estamos en modo debug, entonces sí que los mostraremos, eso sí, siempre antes del return, que impide que mostremos nada
		
		if ($debug && $mode == 'get')
		{
			$log[] = 'Comprobacion de recuento de errores de la funcion wp_carousel_item_value()';
			if(!empty($errors))
			{
				// Uy uy uy... ha habido errores durante la ejecución de esta función, cortemos el script y mostremos los errores de forma legible
				$log[] = 'La comprobacion del recuento de errores de la funcion wp_carousel_item_value() ha detectado que hay errores en la funcion wp_carousel_item_value()';
				echo '<h2>'.__('Errors', 'wp_carousel').'</h2><pre>';
				print_r($errors);
				echo '</pre>';
				$log[] = 'Se ha mostrado el listado de errores de la funcion wp_carousel_item_value()';
				
				echo '<h2>'.__('Log', 'wp_carousel').'</h2><pre>';
				print_r($log);
				echo '</pre>';
				
				// Avisemos de que cortamos el script
				echo '<p>El script se deja de ejecutar a partir de ahora debido a que se han detectado errores durante su ejecución</p>';
				exit; // Cortamos el script
			} else {
				// ¡Qué bien, no hay errores!
				$log[] = 'La comprobacion del recuento de errores de la funcion wp_carousel_item_value() ha determinado que no ha habido errores durante la ejecucion de wp_carousel_item_value()';
				echo '<h2>'.__('Log', 'wp_carousel').'</h2><pre>';
				print_r($log);
				echo '</pre>';
			}
		}
		
		switch ($mode)
		{
			case 'get':
				$log[] = 'La variable $mode tiene por valor: "get", asi que procederemos a devolverla';
				return $return[$value];
				break;
			case 'show':
				$log[] = 'La variable $mode tiene por valor: "show", asi que procederemos a mostrarla';
				echo $return[$value];
				break;
			default:
				$log[] = 'La variable $mode no tiene por valor ni "get" ni "show"';
				$errors[] = 'La variable $mode no tiene uno de los dos valores aceptados ("get" | "show"), de hecho tiene por valor: "'.$mode.'"';
				$log[] = 'Se ha añadido a la matriz de errores el error '.key($errors).': "'.current($errors).'"';
				break;
		}
		
		// En este caso omitiremos los errores, ya que el switch hace que no nos molesten, excepto si estamos en modo debug, entonces sí que los mostraremos, esta vez, después del switch, ya que no hay return
		
		if ($debug && $mode != 'get')
		{
			$log[] = 'Comprobacion de recuento de errores de la funcion wp_carousel_item_value()';
			if(!empty($errors))
			{
				// Uy uy uy... ha habido errores durante la ejecución de esta función, cortemos el script y mostremos los errores de forma legible
				$log[] = 'La comprobacion del recuento de errores de la funcion wp_carousel_item_value() ha detectado que hay errores en la funcion wp_carousel_item_value()';
				echo '<h2>'.__('Errors', 'wp_carousel').'</h2><pre>';
				print_r($errors);
				echo '</pre>';
				$log[] = 'Se ha mostrado el listado de errores de la funcion wp_carousel_item_value()';
				
				echo '<h2>'.__('Log', 'wp_carousel').'</h2><pre>';
				print_r($log);
				echo '</pre>';
				
				// Avisemos de que cortamos el script
				echo '<p>El script se deja de ejecutar a partir de ahora debido a que se han detectado errores durante su ejecución</p>';
				exit; // Cortamos el script
			} else {
				// ¡Qué bien, no hay errores!
				$log[] = 'La comprobacion del recuento de errores de la funcion wp_carousel_item_value() ha determinado que no ha habido errores durante la ejecucion de wp_carousel_item_value()';
				echo '<h2>'.__('Log', 'wp_carousel').'</h2><pre>';
				print_r($log);
				echo '</pre>';
			}
		}
		
	}
	
	/*
		@Función: wp_carousel_calculate_new_id()
		@Versión: 1.0
		@Parámetros:
							$items: Matriz principal de contenido, se debe enviar la matriz correspondiente al contenido del carrusel que se quiere analizar, no la matriz que contiene los carruseles.
							$tpye: ID del tipo de contenido del cual se ha de calcular la siguiente ID.
							$mode (get | show): Sólo admite los valores anteriores. Dependiendo del valor, devolverá la URL (get) o la mostrará (show).
							$debug (bool): Determina si al acabar de ejecutar la función se debe mostrar el registro o no.
		@Descripción: Dependiendo del tipo de contenido y de la matriz de contenido que se le envíe a la función, ésta devolverá la ID que debería tener el siguiente elemente del mismo tipo y orden para que no reemplace a ningún otro.
		@Nota: La usa la función wp_carousel_carousel_options_page()
		@Añadida en la versión: 0.4		
	*/
	
	function wp_carousel_calculate_new_id($items, $type, $mode='get', $debug=false)
	{
		$will['CANCEL'] = false;
		
		$log[] = 'Comprobacion del valor de $debug en wp_carousel_calculate_new_id()';
		if (!$debug)
		{
			$log[] = 'La comprobacion ha determinado que $debug no tiene por valor true (booleano)';
			// De momento sabemos que no estamos en modo debug, pero ¿será porque la variable es false o porque la variable no es true?
			if (is_bool($debug)) 
			{
				// Vale, todo va bien de momento, no seamos paranóicos
				$log[] = 'La comprobacion ha determinado que $debug tiene por valor false (booleano)';
			}
			else
			{
				// Vaya, parece que no íbamos desencaminados: la variable $debug tiene un valor no booleano, así que estamos ante un error. De momento almacenamos el error y ponemos esta función en modo debug, a ver si encontramos el origen del (o los) problemas
				$log[] = 'La comprobacion ha determinado que $debug tiene por valor: "'.$debug.'" (no booleano)';
				$errors[] = 'La variable $debug tiene un valor no booleano, de hecho su valor es: "'.$debug.'".';
				$debug = true;
				$log[] = 'Se ha añadido a la matriz de errores el error '.key($errors).': "'.current($errors).'"';
			}
		}
		
		$log[] = 'Se inicia la comprobacion de la variable $items';
		if (is_array($items))
		{
			$log[] = 'Se ha determinado que la variable $items es una matriz, podemos proceder con la funcion';
		}
		else
		{
			$log[] = 'La variable $items no es una matriz, de hecho su valor es: "'.$items.'"';
			$errors[] = 'La variable $items no es una matriz, de hecho su valor es: "'.$items.'"';
			$log[] = 'Se ha añadido a la matriz de errores el error '.key($errors).': "'.current($errors).'"';
			$will['CANCEL'] = true;
		}
		
		$log[] = 'Se inicia la comprobacion de la variable $type';
		if (is_numeric($type))
		{
			$log[] = 'Se ha determinado que la variable $type tiene un valor numerico, asi que el script puede proceder su ejecucion';
		}
		else
		{
			$log[] = 'La variable $type no tiene un valor numerico, de hecho su valor es: "'.$type.'"';
			$errors[] = 'La variable $type no tiene un valor numerico, de hecho su valor es: "'.$type.'"';
			$log[] = 'Se ha añadido a la matriz de errores el error '.key($errors).': "'.current($errors).'"';
			$will['CANCEL'] = true;
		}
		
		if ($will['CANCEL'])
		{
			$log[] = 'Durante la ejecucion de este script ha habido un error grave que impide la correcta ejecucion del resto de la funcion, se han cancelado posibles futuras acciones de esta funcion';
		}
		else
		{
			$log[] = 'No se han detectado errores criticos durante la ejecucion de la parte anterior del script, procedemos con el resto de la funcion';
			$id_list_temp = array();
			$log[] = 'Se ha creado la matriz $id_list_temp';
			
			$log[] = 'Comienza el bucle foreach principal';
			foreach ($items as $key => $value)
			{
				$log[] = 'Comienza un ciclo';
				
				$key_temp = explode('_', $key);
				$log[] = 'Se ha separado la variable $key segun el separador: "_"';
				if ($key_temp[2] == $type)
				{
					$log[] = 'El elemento actual de la matriz coincide en tipo con el tipo buscado';
					$id_list_temp[] = $value['ID'];
					$log[] = 'Se ha insertado el indice '.key($id_list_temp).' en la matriz $id_list_temp, que tiene por valor: "'.current($id_list_temp).'"';
				}
				else
				{
					$log[] = 'El tipo del elemento actual no coincide con el tipo buscado';
				}
				$log[] = 'Finaliza un ciclo';
			}
			$log[] = 'El bucle foreach principal ha finalizado';
			
			$log[] = 'Comprobacion de elementos en la matriz $id_list_temp';
			if (count($id_list_temp) == 0)
			{
				$log[] = 'No hay ningun contenido del tipo establecido en el carrusel. Se establece la ID maxima en "-1"';
				$max_id_temp = -1;
			}
			else
			{
				$max_id_temp = max($id_list_temp);
				$log[] = 'Se ha calculado la ID mas alta, la cual es: "'.$max_id_temp.'"';
			}
			
			//Por lógica, si la ID más alta es $max_id_temp, la siguiente ID no estará ocupada por ningún elemento
			$id_returned = $max_id_temp + 1;
			$log[] = 'La ID del proximo elemento se almacena en la variable $id_returned, la cual tiene por valor: "'.$id_returned.'"';
			
			$log[] = 'Ultima verificacion de ID libre';
			if (in_array($id_returned, $id_list_temp))
			{
				$log[] = 'La verificacion ha detectado que ha habido un error en el script: la ID del proximo elemento ya existe en la matriz $items';
				$errors[] = 'La ID del siguiente elemento ya existe en la matriz $items';
				$log[] = 'Se ha añadido a la matriz de errores el error '.key($errors).': "'.current($errors).'"';
				$log[] = 'Para evitar posibles errores al finalizar la ejecucion de esta funcion, se va a calcular una nueva ID aleatoria';
				// Pensemos con un poco de lógica: Hay una variable en alguna función que espera que esta función le de un valor numérico de la próxima ID. Si por algún motivo resulta que esta función no tiene algún comprobador de que la nueva ID no existe ya, entonces habrá corrupción de datos y el usuario tendrá pérdidas de contenido del carrusel. Para evitar esto recurriremos a un método no muy correcto: generar un número aleatorio. Está claro que es muy probable que este número se repita, así que para ello lo multiplicaremos dos veces y le sumaremos un entero. Esperemos que así reduzcamos las posibilidades de repetición.
				$number[0] = rand($id_returned, getrandmax());
				$number[1] = rand($id_returned, getrandmax());
				$number[2] = rand($id_returned, getrandmax());
				$number[3] = rand($id_returned, getrandmax());
				// Esta simple operación matemática puede modificarse sin miedo para crear un algoritmo más eficaz a la hora de generar una ID que no se haya usado ya.
				$id_returned = ($number[0] * $number[1] * $number[2]) + $number[3];
				$log[] = 'El algoritmo ha determinado que la nueva ID sera: "'.$id_returned.'"';
			}
			else
			{
				$log[] = 'La verificacion ha determinado que la ID actual no esta siendo usada por ningun elemento';
			}
			$log[] = 'La ultima verificacion ha finalizado';
			
		}
		
		if ($debug && $mode == 'get')
		{
			$log[] = 'Comprobacion de recuento de errores de la funcion wp_carousel_calculate_new_id()';
			if(!empty($errors))
			{
				// Uy uy uy... ha habido errores durante la ejecución de esta función, cortemos el script y mostremos los errores de forma legible
				$log[] = 'La comprobacion del recuento de errores de la funcion wp_carousel_calculate_new_id() ha detectado que hay errores en la funcion wp_carousel_calculate_new_id()';
				echo '<h2>'.__('Errors', 'wp_carousel').'</h2><pre>';
				print_r($errors);
				echo '</pre>';
				$log[] = 'Se ha mostrado el listado de errores de la funcion wp_carousel_calculate_new_id()';
				
				echo '<h2>'.__('Log', 'wp_carousel').'</h2><pre>';
				print_r($log);
				echo '</pre>';
				
				// Avisemos de que cortamos el script
				echo '<p>El script se deja de ejecutar a partir de ahora debido a que se han detectado errores durante su ejecución</p>';
				exit; // Cortamos el script
			} else {
				// ¡Qué bien, no hay errores!
				$log[] = 'La comprobacion del recuento de errores de la funcion wp_carousel_calculate_new_id() ha determinado que no ha habido errores durante la ejecucion de wp_carousel_calculate_new_id()';
				echo '<h2>'.__('Log', 'wp_carousel').'</h2><pre>';
				print_r($log);
				echo '</pre>';
			}
		}
		
		switch ($mode)
		{
			case 'get':
				$log[] = 'La variable $mode tiene por valor: "get", asi que procederemos a devolverla';
				return $id_returned;
				break;
			case 'show':
				$log[] = 'La variable $mode tiene por valor: "show", asi que procederemos a mostrarla';
				echo $id_returned;
				break;
			default:
				$log[] = 'La variable $mode no tiene por valor ni "get" ni "show"';
				$errors[] = 'La variable $mode no tiene uno de los tres valores aceptados ("get" | "show"), de hecho tiene por valor: "'.$mode.'"';
				$log[] = 'Se ha añadido a la matriz de errores el error '.key($errors).': "'.current($errors).'"';
				break;
		}
		
		if ($debug && $mode != 'get')
		{
			$log[] = 'Comprobacion de recuento de errores de la funcion wp_carousel_calculate_new_id()';
			if(!empty($errors))
			{
				// Uy uy uy... ha habido errores durante la ejecución de esta función, cortemos el script y mostremos los errores de forma legible
				$log[] = 'La comprobacion del recuento de errores de la funcion wp_carousel_calculate_new_id() ha detectado que hay errores en la funcion wp_carousel_calculate_new_id()';
				echo '<h2>'.__('Errors', 'wp_carousel').'</h2><pre>';
				print_r($errors);
				echo '</pre>';
				$log[] = 'Se ha mostrado el listado de errores de la funcion wp_carousel_calculate_new_id()';
				
				echo '<h2>'.__('Log', 'wp_carousel').'</h2><pre>';
				print_r($log);
				echo '</pre>';
				
				// Avisemos de que cortamos el script
				echo '<p>El script se deja de ejecutar a partir de ahora debido a que se han detectado errores durante su ejecución</p>';
				exit; // Cortamos el script
			} else {
				// ¡Qué bien, no hay errores!
				$log[] = 'La comprobacion del recuento de errores de la funcion wp_carousel_calculate_new_id() ha determinado que no ha habido errores durante la ejecucion de wp_carousel_calculate_new_id()';
				echo '<h2>'.__('Log', 'wp_carousel').'</h2><pre>';
				print_r($log);
				echo '</pre>';
			}
		}
		
	}
	
	/*
		@Función: wp_carousel_strleft()
		@Versión: 1.0
		@Parámetros:
							$s1: Cadena de texto principal.
							$s2: Cadena de texto a buscar a partir de la cual se recorta la variable $s1.
		@Descripción: Busca en la cadena de texto $s1 la cadena de texto $s2 y devuelve la cadena de texto $s1 hasta  el punto en el que aparece la cadena $s2.
		@Nota: La usa la función wp_carousel_create_internal_urls() y es tan simple que no tiene registro (log) ni modo debug
		@Añadida en la versión: 0.4		
	*/
	
	function wp_carousel_strleft($s1, $s2)
	{
		return substr($s1, 0, strpos($s1, $s2));
	}
	
	/*
		@Función: wp_carousel_create_internal_urls()
		@Versión: 1.2
		@Parámetros:
							$type: Cadena de texto que indica el tipo de URL y el elemento al que se dirige (por ejemplo, tipo editar y elemento artículo con ID 55).
							$mode (get | show | array): Sólo admite los valores anteriores. Dependiendo del valor, devolverá la URL (get), la mostrará (show) o devolverá la matriz que contiene todos los datos intermedios (array).
							$debug (bool): Determina si al acabar de ejecutar la función se debe mostrar el registro o no.
		@Descripción: Genera la URL a cierta página, como por ejemplo a la página de edición de cierto artículo o a la página de opciones de cierto carrusel.
		@Añadida en la versión: 0.4
		@Última actualización en la versión: 0.5
	*/
	
	/*
		Valores de $type (sólo acepta un parámetro)
			
			__TYPE__:__PARAMETRO__ -> Sintaxis: TIPO:PARAMETRO. En esta lista aparecen todos los tipos y los parámetros que acepta la función. Ojo, la función sólo reconoce el primer parámetro, así que usar más es tontería.
			
			SELF_URL -> URL a la página actual (ojo, página, no archivo) - Sólo válida para el Panel de Administración
			
			REAL_SELF_URL -> URL a la página actual, eliminado parámetros de URL (indicado cuando no se trata del Panel de Administraión)
						
			POST_URL:__ID__ -> Permalink del artículo (o página) con la ID __ID__.
			
			EDIT_URL:__ID__ -> URL a la página de edición del artículo / adjunto / revisión / página con ID __ID__.
			
			THEME_FOLDER_URL -> URL a la carpeta de los themes de WP Carousel.
			
			EDIT_COSTUMIZED_CONTENT_URL:__INTERNAL_ID__ -> URL a la página de edición del contenido personalizado con ID INTERNA __INTERNAL_ID__.
			
			REMOVE_URL:__INTERNAL_ID__ -> URL a la página que elimina el contenido con ID INTERNA __INTERNAL_ID__ del carrusel
			
			UNDO_REMOVE:__SERIALIZED_BACKUP__ -> URL a la página que deshace el borrado, importa el backup __SERIALIZED_BACKUP__.
			
			DELETE_CAROUSEL:__CAROUSEL_ID__ -> URL a la página que elimina el carrusel con ID __CAROUSEL_ID__.
			
			__TYPE__:SAVE_ONLY_FIRST_URL_VARIABLE -> Mismo resultado que __TYPE__, y mantiene sólo la primera variable de URL
			
			__TYPE__:DELETE_ALL_URL_VARIABLES -> Mismo resultado que __TYPE__, sólo que elimina TODAS las variables de URL
			
	*/
	
	function wp_carousel_create_internal_urls($type, $mode='get', $debug=false)
	{
		global $wp_carousel_path;
		
		$log[] = 'Comprobacion del valor de $debug en wp_carousel_create_internal_urls()';
		if (!$debug)
		{
			$log[] = 'La comprobacion ha determinado que $debug no tiene por valor true (booleano)';
			// De momento sabemos que no estamos en modo debug, pero ¿será porque la variable es false o porque la variable no es true?
			if (is_bool($debug)) 
			{
				// Vale, todo va bien de momento, no seamos paranóicos
				$log[] = 'La comprobacion ha determinado que $debug tiene por valor false (booleano)';
			}
			else
			{
				// Vaya, parece que no íbamos desencaminados: la variable $debug tiene un valor no booleano, así que estamos ante un error. De momento almacenamos el error y ponemos esta función en modo debug, a ver si encontramos el origen del (o los) problemas
				$log[] = 'La comprobacion ha determinado que $debug tiene por valor: "'.$debug.'" (no booleano)';
				$errors[] = 'La variable $debug tiene un valor no booleano, de hecho su valor es: "'.$debug.'".';
				$debug = true;
				$log[] = 'Se ha añadido a la matriz de errores el error '.key($errors).': "'.current($errors).'"';
			}
		}
		
		// Comprobemos que es lo que nos piden que detectemos
		$log[] = 'Comienza la comprobacion del tipo de accion';
		$type_exploded = explode(':', $type);
		$log[] = 'Se ha separado la peticion en '.count($type_exploded).' partes, siendo la accion principal: "'.$type_exploded[0].'"';
		
		// Si el recuento de acciones es mayor que uno, estamos ante una acción con parámetro
		$log[] = 'Comprobacion del recuento de parametros';
		if (count($type_exploded) > 1)
		{
			// Ok, tenemos parámetros, veamos qué nos piden que hagamos
			$log[] = 'Se han detectado parametros';
			$log[] = 'Comienza el analisis del parametro de la accion';
			switch ($type_exploded[1])
			{
				case 'SAVE_ONLY_FIRST_URL_VARIABLE':
					// Tenemos que guardar sólo la primera variable de URL
					$log[] = 'Se ha detectado el parametro "SAVE_ONLY_FIRST_URL_VARIABLE"';
					$delete['URL_VARIABLES'] = true; // Esta determina si se borran o no las variables de URL A PARTIR DE LA PRIMERA (no incluída)
					$log[] = 'Se ha establecido el valor de $delete[\'URL_VARIABLES\'] en "true" (booleano)';
					$delete['FIRST_URL_VARIABLE'] = false; // Esta determina si se borra la primera variable, si es así, se borran todas
					$log[] = 'Se ha establecido el valor de $delete[\'FIRST_URL_VARIABLE\'] en "false" (booleano)';
					break;
				case 'DELETE_ALL_URL_VARIABLES':
					// Tenemos que borrar todos las variables de URL
					$log[] = 'Se ha detectado el parametro "DELETE_ALL_URL_VARIABLES"';
					$delete['URL_VARIABLES'] = true; // Esta determina si se borran o no las variables de URL A PARTIR DE LA PRIMERA (no incluída)
					$log[] = 'Se ha establecido el valor de $delete[\'URL_VARIABLES\'] en "true" (booleano)';
					$delete['FIRST_URL_VARIABLE'] = true; // Esta determina si se borra la primera variable, si es así, se borran todas
					$log[] = 'Se ha establecido el valor de $delete[\'FIRST_URL_VARIABLE\'] en "false" (booleano)';
					break;
				default:
					if(is_numeric($type_exploded[1]))
					{
						$log[] = 'El parametro tiene un valor numerico, se asume que se trata de la ID de un elemento, el valor en cuestion es: "'.$type_exploded[1].'"';
					}
					else
					{
						$log[] = 'No se ha podido detectar el objetivo del paremetro, se asume que se trata de una ID INTERNA';
					}
					break;
			}
			$log[] = 'Ha finalizado el analisis del parametro de la accion';
		}
		else
		{
			// Vale, no hay parámetros, así que podemos seguir tranquilamente, pero antes démosles a las variables que controlan que los parámetros se borren el valor false (booleano), así no se borrarán los parámetros (a no ser que el tipo de acción lo requiera por defecto)
			$log[] = 'No se han detectado parametros';
			$delete['URL_VARIABLES'] = false; // Esta determina si se borran o no las variables de URL A PARTIR DE LA PRIMERA (no incluída)
			$log[] = 'Se ha establecido el valor de $delete[\'URL_VARIABLES\'] en "false" (booleano)';
			$delete['FIRST_URL_VARIABLE'] = false; // Esta determina si se borra la primera variable, si es así, se borran todas
			$log[] = 'Se ha establecido el valor de $delete[\'FIRST_URL_VARIABLE\'] en "false" (booleano)';
		}
		
		$log[] = 'Comienza el analisis de la accion';
		switch ($type_exploded[0])
		{
			case 'EDIT_URL':
				$log[] = 'La accion a realizar es devolver la URL de la pagina de edicion del elemento (se presupone que se trata de un elemento compatible con la funcion "get_edit_post_link" de WordPress) con ID: "'.$type_exploded[1].'"';
				$url['FLOORED'] = get_edit_post_link($type_exploded[1]);
				break;
			case 'EDIT_COSTUMIZED_CONTENT_URL':
				$log[] = 'La accion a realizar es deolver la URL de la pagina de edicion del contenido personalizable con ID INTERNA: "'.$type_exploded[1].'"';
				$base_url = wp_carousel_create_internal_urls('SELF_URL:SAVE_ONLY_FIRST_URL_VARIABLE', 'get', false);
				$log[] = 'Se ha cargado la URL actual con la primera variable de URL en la variable $base_url, que es: "'.$base_url.'"';
				$base_url.= '&action=EDIT:'.$type_exploded[1].'#edit_content';
				$url['FLOORED'] = $base_url;
				break;
			case 'DELETE_CAROUSEL':
				$log[] = 'La accion a realizar es deolver la URL de la pagina de borrado del carrusel con ID: "'.$type_exploded[1].'"';
				$base_url = wp_carousel_create_internal_urls('SELF_URL:DELETE_ALL_URL_VARIABLES', 'get', false);
				$log[] = 'Se ha cargado la URL actual sin variables de URL en la variable $base_url, que es: "'.$base_url.'"';
				$base_url.= '?page=wp-carousel&action=DELETE_CAROUSEL:'.$type_exploded[1];
				$url['FLOORED'] = $base_url;
				break;
			case 'SELF_URL':
				$log[] = 'La accion a realizar es devolver la URL a la pagina actual';
				
				$request_uri_exploded = explode('/', $_SERVER['REQUEST_URI']);
				$request_uri_exploded_cound = count($request_uri_exploded);
				$log[] = 'Se ha separado la variable $_SERVER[\'REQUEST_URI\'] en '.$request_uri_exploded_cound.' usando \'/\' de separador';
				
				$log[] = 'Se ha iniciado el proceso de ordenado de la matriz $request_uri_exploded';
				foreach ($request_uri_exploded as $key => $value)
				{
					$new_temp_key = $request_uri_exploded_cound - $key;
					$log[] = 'El nuevo indice para el anterior indice \''.$key.'\' es \''.$new_temp_key.'\' y su valor es \''.$value.'\'';
					$request_uri_exploded_new[$new_temp_key] = $value;
				}
				$log[] = 'Ha finalizado el proceso de ordenado de la matriz $request_uri_exploded';
				ksort($request_uri_exploded_new);
				
				$url['COMPLETE'] = get_bloginfo('url').'/wp-admin/'.$request_uri_exploded_new[1];
				$url['FLOORED'] = $url['COMPLETE'];
				
				$log[] = 'Se ha determinado que la URL a la pagina actual es: "'.$url['COMPLETE'].'"';
			
				break;
			case 'REAL_SELF_URL':
				$log[] = 'La accion a realizar es devolver la URL REAL a la pagina actual sin variables de URL';
			
				$this_page_url = 'http';
				
				$log[] = 'Comienza a determinarse si la URL utiliza HTTPS o no';
				if (isset($_SERVER["HTTPS"]))
				{
					if ($_SERVER["HTTPS"] == "on")
					{
						$log[] = 'La URL utiliza HTTPS';
						$this_page_url .= "s";
					}
					else
					{
						$log[] = 'La URL utiliza no HTTPS';
					}
				}
				else
				{
					$log[] = 'La URL utiliza no HTTPS';
				}
				
				$this_page_url .= "://";
				
				$log[] = 'Comienza a analizarse si el puerto accedido es el 80 o no';
				if ($_SERVER["SERVER_PORT"] != "80")
				{
					$log[] = 'El puerto no es el 80, de hecho es: "'.$_SERVER['SERVER_PORT'].'"';
					$this_page_url .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
				}
				else
				{
					$this_page_url .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
				}
				
				$log[] = 'La URL a esta pagina es: "'.$this_page_url.'"';
				
				$url['COMPLETE'] = $this_page_url;
				$url['FLOORED'] = $this_page_url;
				
				break;
			case 'REMOVE_URL':
				$log[] = 'La accion a realizar es devolver la URL a la pagina de borrado de un elemento del carrusel';
				$base_url = wp_carousel_create_internal_urls('SELF_URL:SAVE_ONLY_FIRST_URL_VARIABLE', 'get', false);
				$log[] = 'Se ha cargado la URL actual con la primera variable de URL en la variable $base_url, que es: "'.$base_url.'"';
				$base_url.= '&action=REMOVE:'.$type_exploded[1];
				$url['FLOORED'] = $base_url;
				break;
			case 'UNDO_REMOVE':
				$log[] = 'La accion a realizar es devolver la URL a la pagina de restaurar el contenido de un carrusel desde un backup';
				$base_url = wp_carousel_create_internal_urls('SELF_URL:SAVE_ONLY_FIRST_URL_VARIABLE', 'get', false);
				$log[] = 'Se ha cargado la URL actual con la primera variable de URL en la variable $base_url, que es: "'.$base_url.'"';
				$base_url.= '&action=IMPORT:'.$type_exploded[1];
				$url['FLOORED'] = $base_url;
				break;
			case 'POST_URL':
				$log[] = 'La accion a realizar es devolver la URL de cierto articulo o pagina';
				$url['FLOORED'] = get_permalink($type_exploded[1]);
				$log[] = 'Se ha cargado la URL del articulo (o pagina) con ID '.$type_exploded[1].', que es: "'.$url['FLOORED'].'"';
				break;
			case 'THEME_FOLDER_URL':
				$log[] = 'La accion a realizar es devolver la URL a la carpeta de themes de WP Carousel';
				$url['FLOORED'] = get_option('siteurl').'/'.$wp_carousel_path[8].'/plugins/'.$wp_carousel_path[2].'/themes';
				$log[] = 'Se ha cargado la URL de la carpeta de themes de WP Carousel, que es: "'.$url['FLOORED'].'"';
				break;
			default:
				$log[] = 'No se ha podido detectar el objetivo de la accion';
				$errors[] = 'La accion no se contempla en la lista de acciones, la accion en cuestion es: "'.$type_exploded[0].'"';
				$log[] = 'Se ha añadido a la matriz de errores el error '.key($errors).': "'.current($errors).'"';
				break;
		}
		$log[] = 'Ha finalizado el analisis de la accion';
		
		if (isset($delete['FIRST_URL_VARIABLE']) || isset($delete['URL_VARIABLES']))
		{
			if ($delete['FIRST_URL_VARIABLE'])
			{
				$url['FLOORED'] = explode('?', $url['COMPLETE']);
				$url['FLOORED'] = $url['FLOORED'][0];
			}
			elseif ($delete['URL_VARIABLES'])
			{
				$url['FLOORED'] = explode('&', $url['COMPLETE']);
				$url['FLOORED'] = $url['FLOORED'][0];
			}
		}
		
		$return = $url['FLOORED'];
		$log[] = 'La URL que se devolvera es: "'.$return.'"';
		
		if ($debug && $mode == 'get')
		{
			$log[] = 'Comprobacion de recuento de errores de la funcion wp_carousel_create_internal_urls()';
			if(!empty($errors))
			{
				// Uy uy uy... ha habido errores durante la ejecución de esta función, cortemos el script y mostremos los errores de forma legible
				$log[] = 'La comprobacion del recuento de errores de la funcion wp_carousel_create_internal_urls() ha detectado que hay errores en la funcion wp_carousel_create_internal_urls()';
				echo '<h2>'.__('Errors', 'wp_carousel').'</h2><pre>';
				print_r($errors);
				echo '</pre>';
				$log[] = 'Se ha mostrado el listado de errores de la funcion wp_carousel_create_internal_urls()';
				
				echo '<h2>'.__('Log', 'wp_carousel').'</h2><pre>';
				print_r($log);
				echo '</pre>';
				
				// Avisemos de que cortamos el script
				echo '<p>El script se deja de ejecutar a partir de ahora debido a que se han detectado errores durante su ejecución</p>';
				exit; // Cortamos el script
			} else {
				// ¡Qué bien, no hay errores!
				$log[] = 'La comprobacion del recuento de errores de la funcion wp_carousel_create_internal_urls() ha determinado que no ha habido errores durante la ejecucion de wp_carousel_create_internal_urls()';
				echo '<h2>'.__('Log', 'wp_carousel').'</h2><pre>';
				print_r($log);
				echo '</pre>';
			}
		}
		
		switch ($mode)
		{
			case 'get':
				$log[] = 'La variable $mode tiene por valor: "get", asi que procederemos a devolverla';
				return $return;
				break;
			case 'show':
				$log[] = 'La variable $mode tiene por valor: "show", asi que procederemos a mostrarla';
				echo $return;
				break;
			case 'array':
				$log[] = 'La variable $mode tiene por valor: "array", asi que procederemos a devolver la matriz';
				return $url;
				break;
			default:
				$log[] = 'La variable $mode no tiene por valor ni "get" ni "show" ni "array"';
				$errors[] = 'La variable $mode no tiene uno de los tres valores aceptados ("get" | "show" | "array"), de hecho tiene por valor: "'.$mode.'"';
				$log[] = 'Se ha añadido a la matriz de errores el error '.key($errors).': "'.current($errors).'"';
				break;
		}
		
		if ($debug && $mode != 'get')
		{
			$log[] = 'Comprobacion de recuento de errores de la funcion wp_carousel_create_internal_urls()';
			if(!empty($errors))
			{
				// Uy uy uy... ha habido errores durante la ejecución de esta función, cortemos el script y mostremos los errores de forma legible
				$log[] = 'La comprobacion del recuento de errores de la funcion wp_carousel_create_internal_urls() ha detectado que hay errores en la funcion wp_carousel_create_internal_urls()';
				echo '<h2>'.__('Errors', 'wp_carousel').'</h2><pre>';
				print_r($errors);
				echo '</pre>';
				$log[] = 'Se ha mostrado el listado de errores de la funcion wp_carousel_create_internal_urls()';
				
				echo '<h2>'.__('Log', 'wp_carousel').'</h2><pre>';
				print_r($log);
				echo '</pre>';
				
				// Avisemos de que cortamos el script
				echo '<p>El script se deja de ejecutar a partir de ahora debido a que se han detectado errores durante su ejecución</p>';
				exit; // Cortamos el script
			} else {
				// ¡Qué bien, no hay errores!
				$log[] = 'La comprobacion del recuento de errores de la funcion wp_carousel_create_internal_urls() ha determinado que no ha habido errores durante la ejecucion de wp_carousel_create_internal_urls()';
				echo '<h2>'.__('Log', 'wp_carousel').'</h2><pre>';
				print_r($log);
				echo '</pre>';
			}
		}
		
	}
	
	/*
		@Función: wp_carousel_dropdown_type_items()
		@Versión: 2.0
		@Parámetros:
							$type: ID del tipo de elemento que se quiere mostrar en la lista. No acepta contenido personalizable
							$selected: ID del elemento que debe devolverse seleccionado
							$debug (bool): Determina si al acabar de ejecutar la función se debe mostrar el registro o no.
		@Descripción: Muestra un listado de artículos en formato de menú desplegable
		@Nota: La función está basada en la que usa WP Main Menu para mostrar los artículos
		@Añadida en la versión: 0.5		
	*/
	
	function wp_carousel_dropdown_type_items($type, $selected = '', $debug=false)
	{
		
		// Comprobamos $type
		$log[] = 'Comprobacion del valor de $type';
		if (!is_numeric($type))
		{
			// ¡Vaya! ¡La variable no es un número!
			$log[] = 'La comprobacion del valor de $type ha determinado que no es un numero, de hecho su valor es: "'.$type.'"';
			$errors[] = 'La variable $type no es un numero, de hecho su valor es: "'.$type.'"';
			$log[] = 'Se ha añadido a la matriz de errores el error '.key($errors).': "'.current($errors).'"';
		}
		else
		{
			// Todo va bien :)
			$log[] = 'La comprobacion del valor de $type ha determinado que la variable es un numero';
		}
		
		// Comprobamos $selected
		$log[] = 'Comprobacion del valor de $selected';
		if (!is_numeric($selected) || $selected < 0)
		{
			// ¡Vaya! ¡La variable no es un número!
			$log[] = 'La comprobacion del valor de $selected ha determinado que no es un numero correcto, de hecho su valor es: "'.$selected.'"';
			$errors[] = 'La variable $selected no es un numero, de hecho su valor es: "'.$selected.'"';
			$log[] = 'Se ha añadido a la matriz de errores el error '.key($errors).': "'.current($errors).'"';
		}
		else
		{
			// Todo va bien :)
			$log[] = 'La comprobacion del valor de $selected ha determinado que la variable es un numero';
		}
		
		// Comprobamos $debug
		$log[] = 'Comprobacion del valor de $debug en wp_carousel_dropdown_type_items()';
		if (!$debug)
		{
			$log[] = 'La comprobacion ha determinado que $debug no tiene por valor true (booleano)';
			// De momento sabemos que no estamos en modo debug, pero ¿será porque la variable es false o porque la variable no es true?
			if (is_bool($debug)) 
			{
				// Vale, todo va bien de momento, no seamos paranóicos
				$log[] = 'La comprobacion ha determinado que $debug tiene por valor false (booleano)';
			}
			else
			{
				// Vaya, parece que no íbamos desencaminados: la variable $debug tiene un valor no booleano, así que estamos ante un error. De momento almacenamos el error y ponemos esta función en modo debug, a ver si encontramos el origen del (o los) problemas
				$log[] = 'La comprobacion ha determinado que $debug tiene por valor: "'.$debug.'" (no booleano)';
				$errors[] = 'La variable $debug tiene un valor no booleano, de hecho su valor es: "'.$debug.'".';
				$debug = true;
				$log[] = 'Se ha añadido a la matriz de errores el error '.key($errors).': "'.current($errors).'"';
			}
		}
		
		switch ($type)
		{
			case '1':
				$log[] = 'La variable $type indica que se trata de una categoria';
				wp_dropdown_categories('name=category_id&selected='.$selected);
				break;
			case '2':
				$log[] = 'La variable $type indica que se trata de un articulo';
				echo "<select name='category_id' id='category_id' class='postform' >";
				$posts_query = new WP_Query('showposts=10000');
				while ($posts_query->have_posts())
				{
					$posts_query->the_post();
					echo '<option value="'.get_the_ID().'"';
					if (get_the_ID() == $selected)
					{
						echo ' selected="selected"';
					}
					echo '>'.get_the_title().'</option>';
				}
				echo "</select>";
				break;
			case '3':
				$log[] = 'La variable $type indica que se trata de una pagina';
				wp_dropdown_pages('name=category_id&selected='.$selected);
				break;
			case '5':
				$log[] = 'La variable $type indica que se trata de una etiqueta (tag)';
				echo "<select name='category_id' id='category_id' class='postform' >";
				$tag_list = get_tags();
				foreach ($tag_list as $key => $tag)
				{
					echo '<option value="'.$tag->term_id.'"';
					if ($tag->term_id == $selected)
					{
						echo ' selected="selected"';
					}
					echo '>'.$tag->name.'</option>';
				}
				echo "</select>";
				break;
			case '6':
				$log[] = 'La variable $type indica que se trata de una autor';
				wp_dropdown_users('name=category_id&selected='.$selected);
				break;
			default:
				$log[] = 'La variable $type tiene un valor no contemplado en la lista de ID -> Tipos, de hecho su valor es: "'.$type.'"';
				$errors[] = 'La variable $type tiene un valor no contemplado en la lista de ID -> Tipos, de hecho su valor es: "'.$type.'"';
				$log[] = 'Se ha añadido a la matriz de errores el error '.key($errors).': "'.current($errors).'"';
				break;
		}
		
		if ($debug)
		{
			$log[] = 'Comprobacion de recuento de errores de la funcion wp_carousel_dropdown_type_items()';
			if(!empty($errors))
			{
				// Uy uy uy... ha habido errores durante la ejecución de esta función, cortemos el script y mostremos los errores de forma legible
				$log[] = 'La comprobacion del recuento de errores de la funcion wp_carousel_dropdown_type_items() ha detectado que hay errores en la funcion wp_carousel_dropdown_type_items()';
				echo '<h2>'.__('Errors', 'wp_carousel').'</h2><pre>';
				print_r($errors);
				echo '</pre>';
				$log[] = 'Se ha mostrado el listado de errores de la funcion wp_carousel_dropdown_type_items()';
				
				echo '<h2>'.__('Log', 'wp_carousel').'</h2><pre>';
				print_r($log);
				echo '</pre>';
				
				// Avisemos de que cortamos el script
				echo '<p>El script se deja de ejecutar a partir de ahora debido a que se han detectado errores durante su ejecución</p>';
				exit; // Cortamos el script
			} else {
				// ¡Qué bien, no hay errores!
				$log[] = 'La comprobacion del recuento de errores de la funcion wp_carousel_dropdown_type_items() ha determinado que no ha habido errores durante la ejecucion de wp_carousel_dropdown_type_items()';
				echo '<h2>'.__('Log', 'wp_carousel').'</h2><pre>';
				print_r($log);
				echo '</pre>';
			}
		}
	
	}

	/*
		@Función: wp_carousel_themes_options_area()
		@Versión: 1.0
		@Parámetros:
							$id: Se corresponde con la ID del carrusel actual.
							$debug (bool): Determina si al acabar de ejecutar la función se debe mostrar el registro o no (afecta a las funciones que llama esta función).
		@Descripción: Muestra el formulario para seleccionar themes y cambiar opciones de visualización
		@Añadida en la versión: 0.4		
	*/
	
	function wp_carousel_themes_options_area($id, $debug=false)
	{
		
		$will['CANCEL'] = false;
		
		// Comprobamos $debug
		$log[] = 'Comprobacion del valor de $debug en wp_carousel_themes_options_area()';
		if (!$debug)
		{
			$log[] = 'La comprobacion ha determinado que $debug no tiene por valor true (booleano)';
			// De momento sabemos que no estamos en modo debug, pero ¿será porque la variable es false o porque la variable no es true?
			if (is_bool($debug)) 
			{
				// Vale, todo va bien de momento, no seamos paranóicos
				$log[] = 'La comprobacion ha determinado que $debug tiene por valor false (booleano)';
			}
			else
			{
				// Vaya, parece que no íbamos desencaminados: la variable $debug tiene un valor no booleano, así que estamos ante un error. De momento almacenamos el error y ponemos esta función en modo debug, a ver si encontramos el origen del (o los) problemas
				$log[] = 'La comprobacion ha determinado que $debug tiene por valor: "'.$debug.'" (no booleano)';
				$errors[] = 'La variable $debug tiene un valor no booleano, de hecho su valor es: "'.$debug.'".';
				$debug = true;
				$log[] = 'Se ha añadido a la matriz de errores el error '.key($errors).': "'.current($errors).'"';
			}
		}
		
		$log[] = 'Comienza el analisis de la variable $id';
		if (is_numeric($id))
		{
			$log[] = 'El analisis ha determinado que la variable es un numero';
		}
		else
		{
			$log[] = 'La variable $id no es numerica, de hecho su valor es: "'.$id.'"';
			$errors[] = 'La variable $id no es numerica, de hecho su valor es: "'.$id.'"';
			$log[] = 'Se ha añadido a la matriz de errores el error '.key($errors).': "'.current($errors).'"';
			$log[] = 'Ha habido un error critico en la funcion, se cancela su ejecucion';
			$will['CANCEL'] = true;
		}
		
		if ($will['CANCEL'])
		{
			
			if ($debug)
			{
				$log[] = 'Comprobacion de recuento de errores de la funcion wp_carousel_themes_options_area()';
				if(!empty($errors))
				{
					// Uy uy uy... ha habido errores durante la ejecución de esta función, cortemos el script y mostremos los errores de forma legible
					$log[] = 'La comprobacion del recuento de errores de la funcion wp_carousel_themes_options_area() ha detectado que hay errores en la funcion wp_carousel_themes_options_area()';
					echo '<h2>'.__('Errors', 'wp_carousel').'</h2><pre>';
					print_r($errors);
					echo '</pre>';
					$log[] = 'Se ha mostrado el listado de errores de la funcion wp_carousel_themes_options_area()';
					
					echo '<h2>'.__('Log', 'wp_carousel').'</h2><pre>';
					print_r($log);
					echo '</pre>';
					
					// Avisemos de que cortamos el script
					echo '<p>El script se deja de ejecutar a partir de ahora debido a que se han detectado errores durante su ejecución</p>';
					exit; // Cortamos el script
				} else {
					// ¡Qué bien, no hay errores!
					$log[] = 'La comprobacion del recuento de errores de la funcion wp_carousel_themes_options_area() ha determinado que no ha habido errores durante la ejecucion de wp_carousel_themes_options_area()';
					echo '<h2>'.__('Log', 'wp_carousel').'</h2><pre>';
					print_r($log);
					echo '</pre>';
				}
			}
			
		}
		else
		{
		?>
		<div class="wp_carousel_tabs wp_carousel_tabs_js">
		
			<ul>
				<li><a href="#select_theme"><?php echo _e('Themes', 'wp_carousel'); ?></a></li>
				<li class="right"><a href="#theme_options"><?php echo _e('Display options', 'wp_carousel'); ?></a></li>
			</ul>
			
			<div class="clear"></div>
			
			<div id="select_theme">
				<?php wp_carousel_list_themes($id); ?>
			</div>
			
			<div id="theme_options">
				<?php
					$config = maybe_unserialize(get_option('wp_carousel_config'));
					$config = $config[$id];
					if (!isset($config['SHOW_ARROWS'])) $config['SHOW_ARROWS'] = '1';
					if (!isset($config['SLIDE_POSTS']) || !is_numeric($config['SLIDE_POSTS']) || $config['SLIDE_POSTS'] < 0) $config['SLIDE_POSTS'] = '1';
					if (!isset($config['ENABLE_PAGINATION'])) $config['ENABLE_PAGINATION'] = '1';
					if (!isset($config['AUTOSLIDE_TIME']) || !is_numeric($config['AUTOSLIDE_TIME']) || $config['AUTOSLIDE_TIME'] < 0) $config['AUTOSLIDE_TIME'] = '0';
					if (!isset($config['AUTOSLIDE_POSTS']) || !is_numeric($config['AUTOSLIDE_POSTS']) || $config['AUTOSLIDE_POSTS'] < 0) $config['AUTOSLIDE_POSTS'] = '0';
					if (!isset($config['LOOP_MODE'])) $config['LOOP_MODE'] = '1';
					if (!isset($config['PANEL_WIDTH'])) $config['PANEL_WIDTH'] = '';
					if (!isset($config['IMG_WIDTH'])) $config['IMG_WIDTH'] = '';
					if (!isset($config['IMG_HEIGHT'])) $config['IMG_HEIGHT'] = '';
				?>
				<form name="theme_display_options" action="<?php wp_carousel_create_internal_urls('SELF_URL:SAVE_ONLY_FIRST_URL_VARIABLE', 'show'); ?>&action=SAVE_OPTIONS" method="post" id="theme_display_options">
					<input name="publish" type="submit" class="button-primary right" id="publish" tabindex="5" accesskey="p" value="<?php echo _e('Save changes', 'wp_carousel'); ?>" />  
					<h3><?php echo _e('Carousel settings', 'wp_carousel'); ?></h3>
					<table class="form-table">
						<tr valign="top">
							<th scope="row"><label for="show_arrows"><?php echo _e('Show arrows?', 'wp_carousel'); ?></label></th>
							<td>
								<select name="show_arrows" id="show_arrows">
									<option value="0"<?php if ($config['SHOW_ARROWS'] == '0') echo ' selected="selected"'; ?>>No</option>
									<option value="1"<?php if ($config['SHOW_ARROWS'] == '1') echo ' selected="selected"'; ?>>Yes</option>
								</select>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><label for="slide_posts"><?php echo _e('Posts moved in each manual movement (0 for disable manual movements and arrows)', 'wp_carousel'); ?></label></th>
							<td>
								<input name="slide_posts" type="text" id="slide_posts" value="<?php echo $config['SLIDE_POSTS']; ?>" />
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><label for="enable_pagination"><?php echo _e('Enable pagination?', 'wp_carousel'); ?></label></th>
							<td>
								<select name="enable_pagination" id="enable_pagination">
									<option value="0"<?php if ($config['ENABLE_PAGINATION'] == '0') echo ' selected="selected"'; ?>>No</option>
									<option value="1"<?php if ($config['ENABLE_PAGINATION'] == '1') echo ' selected="selected"'; ?>>Yes</option>
								</select>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><label for="loop_mode"><?php echo _e('Enable loop mode?', 'wp_carousel'); ?></label></th>
							<td>
								<select name="loop_mode" id="loop_mode">
									<option value="0"<?php if ($config['LOOP_MODE'] == '0') echo ' selected="selected"'; ?>>No</option>
									<option value="1"<?php if ($config['LOOP_MODE'] == '1') echo ' selected="selected"'; ?>>Yes</option>
								</select>
							</td>
						</tr>
					</table>
					<h3><?php echo _e('Autoslide settings', 'wp_carousel'); ?></h3>
					<table class="form-table">
						<tr valign="top">
							<th scope="row"><label for="autoslide_time"><?php echo _e('Time between each autoslide (0 for disable autoslides)', 'wp_carousel'); ?></label></th>
							<td>
								<input name="autoslide_time" type="text" id="autoslide_time" value="<?php echo $config['AUTOSLIDE_TIME']; ?>" />
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><label for="autoslide_posts"><?php echo _e('Posts moved in each autoslide (0 for disable autoslides)', 'wp_carousel'); ?></label></th>
							<td>
								<input name="autoslide_posts" type="text" id="autoslide_posts" value="<?php echo $config['AUTOSLIDE_POSTS']; ?>" />
							</td>
						</tr>
					</table>
					<h3><?php echo _e('Size settings', 'wp_carousel'); ?></h3>
					<table class="form-table">
						<tr valign="top">
							<th scope="row"><label for="panel_width"><?php echo _e('Panel width (size and unit, leave blank to use panel original\'s width)', 'wp_carousel'); ?></label></th>
							<td>
								<input name="panel_width" type="text" id="panel_width" value="<?php echo $config['PANEL_WIDTH']; ?>" />
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><label for="img_width"><?php echo _e('Image width (size and unit, leave blank to use image original\'s width)', 'wp_carousel'); ?></label></th>
							<td>
								<input name="img_width" type="text" id="img_width" value="<?php echo $config['IMG_WIDTH']; ?>" />
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><label for="img_height"><?php echo _e('Image height (size and unit, leave blank to use image original\'s height)', 'wp_carousel'); ?></label></th>
							<td>
								<input name="img_height" type="text" id="img_height" value="<?php echo $config['IMG_HEIGHT']; ?>" />
							</td>
						</tr>
					</table>
				</form>
			</div>
			
		</div>
		<?php
			
			if ($debug)
			{
				$log[] = 'Comprobacion de recuento de errores de la funcion wp_carousel_themes_options_area()';
				if(!empty($errors))
				{
					// Uy uy uy... ha habido errores durante la ejecución de esta función, cortemos el script y mostremos los errores de forma legible
					$log[] = 'La comprobacion del recuento de errores de la funcion wp_carousel_themes_options_area() ha detectado que hay errores en la funcion wp_carousel_themes_options_area()';
					echo '<h2>'.__('Errors', 'wp_carousel').'</h2><pre>';
					print_r($errors);
					echo '</pre>';
					$log[] = 'Se ha mostrado el listado de errores de la funcion wp_carousel_themes_options_area()';
					
					echo '<h2>'.__('Log', 'wp_carousel').'</h2><pre>';
					print_r($log);
					echo '</pre>';
					
					// Avisemos de que cortamos el script
					echo '<p>El script se deja de ejecutar a partir de ahora debido a que se han detectado errores durante su ejecución</p>';
					exit; // Cortamos el script
				} else {
					// ¡Qué bien, no hay errores!
					$log[] = 'La comprobacion del recuento de errores de la funcion wp_carousel_themes_options_area() ha determinado que no ha habido errores durante la ejecucion de wp_carousel_themes_options_area()';
					echo '<h2>'.__('Log', 'wp_carousel').'</h2><pre>';
					print_r($log);
					echo '</pre>';
				}
			}
			
		}
	}
	
	/*
		@Función: wp_carousel_add_carousel_page()
		@Versión: 1.0
		@Parámetros:
							$parameters: Almacena parámetros que envía WordPress.
							$debug (bool): Determina si al acabar de ejecutar la función se debe mostrar el registro o no.
		@Descripción: Añade carruseles a la matriz principal.
		@Añadida en la versión: 0.4		
	*/
	
	function wp_carousel_add_carousel_page($parameters, $debug=false)
	{
		
		// Comprobamos $debug
		$log[] = 'Comprobacion del valor de $debug en wp_carousel_add_carousel_page()';
		if (!$debug)
		{
			$log[] = 'La comprobacion ha determinado que $debug no tiene por valor true (booleano)';
			// De momento sabemos que no estamos en modo debug, pero ¿será porque la variable es false o porque la variable no es true?
			if (is_bool($debug)) 
			{
				// Vale, todo va bien de momento, no seamos paranóicos
				$log[] = 'La comprobacion ha determinado que $debug tiene por valor false (booleano)';
			}
			else
			{
				// Vaya, parece que no íbamos desencaminados: la variable $debug tiene un valor no booleano, así que estamos ante un error. De momento almacenamos el error y ponemos esta función en modo debug, a ver si encontramos el origen del (o los) problemas
				$log[] = 'La comprobacion ha determinado que $debug tiene por valor: "'.$debug.'" (no booleano)';
				$errors[] = 'La variable $debug tiene un valor no booleano, de hecho su valor es: "'.$debug.'".';
				$debug = true;
				$log[] = 'Se ha añadido a la matriz de errores el error '.key($errors).': "'.current($errors).'"';
			}
		}
		
		$page_title_temp = __('New carousel added', 'wp_carousel');
		$text_temp = __('A carousel with ID %s has been added into the DataBase. Click <a href="%s">here</a> to add another one or click <a href="%s">here</a> to go to its options page.', 'wp_carousel');
		
		$items = get_option('wp_carousel');
		$log[] = 'Se ha cargado la matriz principal';
		$items = maybe_unserialize($items);
		$log[] = 'Se ha desserializado la matriz principal';
		
		// En un principio pensaba usar esta función para añadir contenido, pero entonces el menú no se actualizaba, así que he movido el código fuera de la función. De todos modos dejo aquí el código comentado.
		/*
		$items[] = array();
		$log[] = 'Se ha insertado un nuevo carrusel en la matriz principal, que corresponde al indice: "'.key($items).'"';
		
		$items_db = serialize($items);
		update_option('wp_carousel', $items_db);
		*/
		
		// POEDIT_ERROR: He tenido problemas al ejecutar _e('New carousel added', 'wp_carousel'); en la posición correspondiente, así que me he visto obligado a crear una variable que almacene su valor para mostrarlo más adelante.
		
		// POEDIT_ERROR: Mismo problema que arriba, pero con este código __('A carousel with ID %s has been added into the DataBase. Click <a href="%s">here</a> to add another one or click <a href="%s">here</a> to go to its options page.', 'wp_carousel')
				
		?>
		<div class="wrap">
			<div id="icon-options-general" class="icon32"><br></div>
			<h2>WP Carousel - <?php echo $page_title_temp; ?></h2>
			<?php end($items); ?>
			<p><?php printf($text_temp, key($items), wp_carousel_create_internal_urls('SELF_URL'), wp_carousel_create_internal_urls('SELF_URL:DELETE_ALL_URL_VARIABLES').'?page=edit-carousel-'.key($items)); ?></p>
					
		</div>
		<div class="clear"></div>
		
		<?php
		
		if ($debug)
		{
			$log[] = 'Comprobacion de recuento de errores de la funcion wp_carousel_add_carousel_page()';
			if(!empty($errors))
			{
				// Uy uy uy... ha habido errores durante la ejecución de esta función, cortemos el script y mostremos los errores de forma legible
				$log[] = 'La comprobacion del recuento de errores de la funcion wp_carousel_add_carousel_page() ha detectado que hay errores en la funcion wp_carousel_add_carousel_page()';
				echo '<h2>'.__('Errors', 'wp_carousel').'</h2><pre>';
				print_r($errors);
				echo '</pre>';
				$log[] = 'Se ha mostrado el listado de errores de la funcion wp_carousel_add_carousel_page()';
				
				echo '<h2>'.__('Log', 'wp_carousel').'</h2><pre>';
				print_r($log);
				echo '</pre>';
				
				// Avisemos de que cortamos el script
				echo '<p>El script se deja de ejecutar a partir de ahora debido a que se han detectado errores durante su ejecución</p>';
				exit; // Cortamos el script
			} else {
				// ¡Qué bien, no hay errores!
				$log[] = 'La comprobacion del recuento de errores de la funcion wp_carousel_add_carousel_page() ha determinado que no ha habido errores durante la ejecucion de wp_carousel_add_carousel_page()';
				echo '<h2>'.__('Log', 'wp_carousel').'</h2><pre>';
				print_r($log);
				echo '</pre>';
			}
		}
		
	}
	
	/*
		@Función: wp_carousel_list_themes()
		@Versión: 1.0
		@Parámetros:
							$id: Se corresponde con la ID del carrusel actual.
							$debug (bool): Determina si al acabar de ejecutar la función se debe mostrar el registro o no.
		@Descripción: Muestra un listado de los themes disponibles para WP Carousel
		@Añadida en la versión: 0.4		
	*/
	
	function wp_carousel_list_themes($id, $debug=false)
	{
		global $wp_carousel_path;
		
		$will['CANCEL'] = false;
		
		// Comprobamos $debug
		$log[] = 'Comprobacion del valor de $debug en wp_carousel_list_themes()';
		if (!$debug)
		{
			$log[] = 'La comprobacion ha determinado que $debug no tiene por valor true (booleano)';
			// De momento sabemos que no estamos en modo debug, pero ¿será porque la variable es false o porque la variable no es true?
			if (is_bool($debug)) 
			{
				// Vale, todo va bien de momento, no seamos paranóicos
				$log[] = 'La comprobacion ha determinado que $debug tiene por valor false (booleano)';
			}
			else
			{
				// Vaya, parece que no íbamos desencaminados: la variable $debug tiene un valor no booleano, así que estamos ante un error. De momento almacenamos el error y ponemos esta función en modo debug, a ver si encontramos el origen del (o los) problemas
				$log[] = 'La comprobacion ha determinado que $debug tiene por valor: "'.$debug.'" (no booleano)';
				$errors[] = 'La variable $debug tiene un valor no booleano, de hecho su valor es: "'.$debug.'".';
				$debug = true;
				$log[] = 'Se ha añadido a la matriz de errores el error '.key($errors).': "'.current($errors).'"';
			}
		}
		
		$config = get_option('wp_carousel_config');
		$log[] = 'Se ha cargado la matriz de configuraciones';
		$config = maybe_unserialize($config);
		$log[] = 'Se ha desserializado la matriz de configuraciones';
		$config = $config[$id];
		$log[] = 'Se ha centrado la variable $config en el configuracion del carrusel con ID: "'.$id.'"';
		
		$log[] = 'Comienza el analisis de la variable $id';
		if (is_numeric($id))
		{
			$log[] = 'El analisis ha determinado que la variable es un numero';
		}
		else
		{
			$log[] = 'La variable $id no es numerica, de hecho su valor es: "'.$id.'"';
			$errors[] = 'La variable $id no es numerica, de hecho su valor es: "'.$id.'"';
			$log[] = 'Se ha añadido a la matriz de errores el error '.key($errors).': "'.current($errors).'"';
			$log[] = 'Ha habido un error critico en la funcion, se cancela su ejecucion';
			$will['CANCEL'] = true;
		}
		
		if ($will['CANCEL'])
		{
			
			if ($debug)
			{
				$log[] = 'Comprobacion de recuento de errores de la funcion wp_carousel_list_themes()';
				if(!empty($errors))
				{
					// Uy uy uy... ha habido errores durante la ejecución de esta función, cortemos el script y mostremos los errores de forma legible
					$log[] = 'La comprobacion del recuento de errores de la funcion wp_carousel_list_themes() ha detectado que hay errores en la funcion wp_carousel_list_themes()';
					echo '<h2>'.__('Errors', 'wp_carousel').'</h2><pre>';
					print_r($errors);
					echo '</pre>';
					$log[] = 'Se ha mostrado el listado de errores de la funcion wp_carousel_list_themes()';
					
					echo '<h2>'.__('Log', 'wp_carousel').'</h2><pre>';
					print_r($log);
					echo '</pre>';
					
					// Avisemos de que cortamos el script
					echo '<p>El script se deja de ejecutar a partir de ahora debido a que se han detectado errores durante su ejecución</p>';
					exit; // Cortamos el script
				} else {
					// ¡Qué bien, no hay errores!
					$log[] = 'La comprobacion del recuento de errores de la funcion wp_carousel_list_themes() ha determinado que no ha habido errores durante la ejecucion de wp_carousel_list_themes()';
					echo '<h2>'.__('Log', 'wp_carousel').'</h2><pre>';
					print_r($log);
					echo '</pre>';
				}
			}
			
			exit;
			
		}
		else
		{
			$log[] = 'No ha habido errores criticos hasta ahora, se procede con la ejecucion de la funcion';
			
			$dir = '../'.$wp_carousel_path[8].'/plugins/'.$wp_carousel_path[2].'/themes';
			$url = wp_carousel_create_internal_urls('THEME_FOLDER_URL' , 'get');
			$log[] = 'La ruta relativa desde la carpeta actual hasta la carpeta de themes es: "'.$dir.'"';
		
			if (is_dir($dir))
			{
				if ($handle = opendir($dir))
				{
					$log[] = 'La ruta relativa dirige hasta una carpeta';
					$themes = array();
					while (($file = readdir($handle)) !== false)
					{
						if (is_dir($dir.'/'.$file) && $file != '.' && $file != '..' && $file != '.svn')
						{
							$log[] = 'Se ha determinado que la carpeta "'.$file.'" contiene los archivos de un theme';
							$themes[] = $file;
						}
					}
				closedir($handle);
				} 
			}
			else
			{
				$log[] = 'La ruta relativa no dirige hasta una carpeta';
			}
				
			$log[] = 'Comienza el bucle de asignacion de informacion a la matriz $themeinfo';
			foreach ($themes as $key => $value) {
				include($dir.'/'.$value.'/index.php');
				$themeinfo[$value] = $theme;
				$log[] = 'Se ha insertado el indice '.$value.' a la matriz $themeinfo, que tiene por valor "'.print_r($theme, true).'"';
			}
			
			$log[] = 'Comienza a mostrarse la informacion refernete al theme activado';
			
			$log[] = 'Se inicia el analisis del indice THEME de la variable $config';
			if (isset($config['THEME']))
			{
				$log[] = 'Hay un theme determinado en la Base de Datos';
			}
			else
			{
				$log[] = 'No hay ningun theme activado, se procede a activar el theme Default';
				$config['THEME'] = 'default';
			}
			?>
			
			<h3><?php echo __('Current Theme', 'wp_carousel'); ?></h3>
			<div id="current-theme">
				<img src="<?php echo $url.'/'.$config['THEME'].'/screenshot.png'; ?>" alt="<?php echo __('Current theme preview', 'wp_carousel'); ?>" />
				<h4><?php echo $themeinfo[$config['THEME']]['name'].' '; echo __('by', 'wp_carousel'); ?> <a href="<?php echo $themeinfo[$config['THEME']]['author_url']; ?>" title="<?php echo __('Visit author homepage', 'wp_carousel'); ?>"><?php echo $themeinfo[$config['THEME']]['author']; ?></a></h4>
				<p class="theme-description"><?php echo $themeinfo[$config['THEME']]['desc']; ?> <br/> <?php printf(__('Version %s', 'wp_carousel'), $themeinfo[$config['THEME']]['version']); ?></p>
				<?php printf(__('<p>All of this theme&#8217;s files are located in <code>%s</code>.</p>', 'wp_carousel'), $dir.'/'.$config['THEME']); ?>
			</div>

			<div class="clear"></div>
			
			<h3><?php echo __('Available Themes', 'wp_carousel'); ?></h3>
			<div class="clear"></div>
			
			<?php
			
			unset($themeinfo[$config['THEME']]);
			$log[] = 'Se ha eliminado el indice correspondiente al theme activado de la matriz de themes';
			
			$total_avaible_themes = count($themeinfo);
			$log[] = 'El recuento de los themes es '.$total_avaible_themes;
			
			$n_temp = 0; // Número de ciclos completos
			$td_temp = 0; // Número de celdas de la fila actual
			$tr_showed = 0; // Número de filas mostradas
			
			$tr_temp = ceil($total_avaible_themes / 3); // Total de filas a mostrar
			
			echo '<table id="availablethemes" cellspacing="0" cellpadding="0">';
			
			foreach ($themeinfo as $key => $value)
			{
				if ($tr_showed == 0) { $b_t = 'top'; } elseif (($tr_showed + 1) == $tr_temp) { $b_t = 'bottom'; } else { $b_t = ''; } // Calculamos si es la última o la primera fila
				if ($td_temp == 0) { $l_r = 'left'; } elseif ($td_temp == 2) { $l_r = 'right'; } else { $l_r = 'center'; }
				if ($td_temp == 0)
				{
					echo '<tr>';
				}
				?>
				<td class="wp_carousel_available_theme <?php echo $b_t.' '.$l_r; ?>">
				
					<a href="<?php echo wp_carousel_create_internal_urls('SELF_URL:SAVE_ONLY_FIRST_URL_VARIABLE').'&action=UPDATE_THEME:'.$key; ?>" class="thickbox thickbox-preview screenshot">
						<img src="<?php echo $url.'/'.$key.'/screenshot.png'; ?>" alt="<?php echo $value['name']; ?>" />
					</a>
					
					<h3><?php echo $value['name'].' '.__('by', 'wp_carousel').' '.$value['author']; ?></h3>
					<p class="description"><?php echo $value['desc']; ?> <br /> <?php printf(__('Version %s', 'wp_carousel'), $value['version']); ?></p>
					<span class='action-links'><a href="<?php echo wp_carousel_create_internal_urls('SELF_URL:SAVE_ONLY_FIRST_URL_VARIABLE').'&action=UPDATE_THEME:'.$key; ?>"><?php echo __('Activate', 'wp_carousel'); ?></a> | <a href="<?php echo $value['url']; ?>"><?php echo __('Visit theme site', 'wp_carousel'); ?></a> | <a href="<?php echo $value['author_url']; ?>"><?php echo __('Visit author site', 'wp_carousel'); ?></a></span>

						<?php printf(__('<p>All of this theme&#8217;s files are located in <code>%s</code>.</p>', 'wp_carousel'), $dir.'/'.$key); ?>
				
				</td>
				<?php
				$n_temp++;
				$td_temp++;
				
				if ($td_temp == 3)
				{
					$td_temp = 0;
					$tr_showed++;
				}
				
				if ($n_temp == $total_avaible_themes) 
				{
					$extra_td_showed = 0; // Número de celdas extra mostradas para igualar la tabla
					while ($td_temp != $extra_td_showed)
					{	
						if ($td_temp == 0) { $l_r = 'left'; } elseif ($td_temp == 2) { $l_r = 'right'; } else { $l_r = ''; }
						echo '<td class="wp_carousel_available_theme '.$b_t.' '.$l_r.'"></td>';
						$extra_td_showed++;
					}
				}
				
				if ($td_temp == 0)
				{
					echo '</tr>';
				}
				
			}	
			
			echo '</table>';
			
		}
		
		if ($debug)
		{
			$log[] = 'Comprobacion de recuento de errores de la funcion wp_carousel_list_themes()';
			if(!empty($errors))
			{
				// Uy uy uy... ha habido errores durante la ejecución de esta función, cortemos el script y mostremos los errores de forma legible
				$log[] = 'La comprobacion del recuento de errores de la funcion wp_carousel_list_themes() ha detectado que hay errores en la funcion wp_carousel_list_themes()';
				echo '<h2>'.__('Errors', 'wp_carousel').'</h2><pre>';
				print_r($errors);
				echo '</pre>';
				$log[] = 'Se ha mostrado el listado de errores de la funcion wp_carousel_list_themes()';
				
				echo '<h2>'.__('Log', 'wp_carousel').'</h2><pre>';
				print_r($log);
				echo '</pre>';
				
				// Avisemos de que cortamos el script
				echo '<p>El script se deja de ejecutar a partir de ahora debido a que se han detectado errores durante su ejecución</p>';
				exit; // Cortamos el script
			} else {
				// ¡Qué bien, no hay errores!
				$log[] = 'La comprobacion del recuento de errores de la funcion wp_carousel_list_themes() ha determinado que no ha habido errores durante la ejecucion de wp_carousel_list_themes()';
				echo '<h2>'.__('Log', 'wp_carousel').'</h2><pre>';
				print_r($log);
				echo '</pre>';
			}
		}
		
	}
	
	/*
		@Función: wp_carousel_export_page()
		@Versión: 1.0
		@Descripción: Obtiene el código de exportación y muestra página de exportación
		@Añadida en la versión: 0.4		
	*/
	
	function wp_carousel_export_page()
	{
		$items = get_option('wp_carousel');
		$config = get_option('wp_carousel_config');
		$export = array('ITEMS' => $items, 'CONFIG' => $config);
		$export = serialize($export);
		$export = base64_encode($export);
		?>
		<div class="wrap">
			<div id="icon-options-general" class="icon32"><br></div>
			<h2>WP Carousel - <?php echo _e('Export page', 'wp_carousel'); ?></h2>
						
			<p><?php echo _e('Copy this code and paste it in a text file to make a backup. If you want to load a backup, copy this code and paste it in the import page.', 'wp_carousel'); ?></p>
			<form>
				<textarea cols="60" rows="10"><?php echo $export; ?></textarea>
			</form>
		</div>
		<div class="clear"></div>
		<?php
	}
	
	/*
		@Función: wp_carousel_import_page()
		@Versión: 1.0
		@Descripción: Pide el código de exportación y carga el backup.
		@Añadida en la versión: 0.4		
	*/
	
	function wp_carousel_import_page()
	{
		$bad_backup = false;
		if (isset($_POST['import']))
		{
			if ($_POST['import'] != '')
			{
				$array = $_POST['import'];
				$array = base64_decode($array);
				$array = maybe_unserialize($array);
				$items = $array['ITEMS'];
				$config = $array['CONFIG'];
				if (!is_array(maybe_unserialize($items)))
				{
					$bad_backup = true;
				}
				if (!is_array(maybe_unserialize($config)))
				{
					$bad_backup = true;
				}
				if (!$bad_backup)
				{
					update_option('wp_carousel', $items);
					update_option('wp_carousel_config', $config);
				}
			}
		}
		?>		
		<div class="wrap">
			<div id="icon-options-general" class="icon32"><br></div>
			<h2>WP Carousel - <?php echo _e('Import page', 'wp_carousel'); ?></h2>
						
			<p><?php echo _e('Copy in this page the code of a backup. Importing a backup is undoable and will replace all WP Carousel\'s data with the backup\'s data.', 'wp_carousel'); ?></p>
			<?php if ($bad_backup) { echo '<p>'.__('That code is not a valid WP Carousel backup code', 'wp_carousel').'</p>'; } ?>
			<form action="<?php echo wp_carousel_create_internal_urls('SELF_URL', 'get'); ?>" method="post" name="import-form" id="import-form">
				<textarea cols="60" rows="10" name="import" id="import"></textarea>
				<br />
				<input type="submit" name="submit" id="submit" class="button primary-button" value="<?php echo _e('Import', 'wp_carousel'); ?>" />
			</form>
		</div>
		<div class="clear"></div>
		<?php
	}
	
	/*
		@Función: wp_carousel_uninstall_page()
		@Versión: 1.0
		@Descripción: Muestra la página para desinstalar el carrusel
		@Añadida en la versión: 0.4		
	*/
	
	function wp_carousel_uninstall_page()
	{
		?>		
		<div class="wrap">
			<div id="icon-options-general" class="icon32"><br></div>
			<h2><?php echo _e('Uninstall WP Carousel', 'wp_carousel'); ?></h2>
			<p><?php echo _e('This will delete all the content in the Data Base that was added by WP Carousel. It can\'t be undone. Are you sure?', 'wp_carousel'); ?></p>
			<p><?php printf(__('Click <a href="%s">here</a> to continue.', 'wp_carousel'), wp_carousel_create_internal_urls('SELF_URL:DELETE_ALL_URL_VARIABLES').'?page=wp-carousel&action=UNINSTALL'); ?></p>
		</div>
		<div class="clear"></div>
		<?php
	}	
	
	/*
		@Clase: WP_Carousel_Widget
		@Versión: 1.1
		@Descripción: Se encarga del plugin de la sidebar de WP Carousel
		@Nota: Sí, esta es la única función copiada de WP Carousel 0.3
		@Añadida en la versión: 0.3
	*/
	
	class WP_Carousel_Widget extends WP_Widget
	{
	
		function WP_Carousel_Widget()
		{
			parent::WP_Widget(false, $name = 'WP Carousel Widget');	
		}
	
		function widget($args, $instance)
		{		
			extract( $args );
			echo $before_widget;
			wp_carousel($instance['id']);
			echo $after_widget;
		}
	
		function update($new_instance, $old_instance)
		{				
			return $new_instance;
		}
	
		function form($instance)
		{
			if (isset($instance['id']))
			{
				$id = esc_attr($instance['id']);
			}
			else
			{
				$id = '';
			}
			?>
				<p><label for="<?php echo $this->get_field_id('id'); ?>"><?php echo _e('Carousel\'s ID', 'wp_carousel'); ?> <input class="widefat" id="<?php echo $this->get_field_id('id'); ?>" name="<?php echo $this->get_field_name('id'); ?>" type="text" value="<?php echo $id; ?>" /></label></p>
			<?php 
		}

	}
	
?>