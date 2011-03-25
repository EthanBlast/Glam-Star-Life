<?php



class GallerySlide extends GalleryDbHelper {



	var $table;

	var $model = 'Slide';

	var $controller = "slides";

	var $plugin_name = 'slideshow-gallery-2';

	

	var $data = array();

	var $errors = array();

	

	var $fields = array(

		'id'				=>	"INT(11) NOT NULL AUTO_INCREMENT",

		'title'				=>	"VARCHAR(150) NOT NULL DEFAULT ''",

		'description'		=>	"TEXT NOT NULL",

		'image'				=>	"VARCHAR(50) NOT NULL DEFAULT ''",

		'image_url'			=>	"VARCHAR(200) NOT NULL DEFAULT ''",

		'uselink'			=>	"ENUM('Y','N') NOT NULL DEFAULT 'N'",

		'link'				=>	"VARCHAR(200) NOT NULL DEFAULT ''",

		'order'				=>	"INT(11) NOT NULL DEFAULT '0'",

		'created'			=>	"DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'",

		'modified'			=>	"DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'",

		'key'				=>	"PRIMARY KEY (`id`)",

	);



	function GallerySlide($data = array()) {

		global $wpdb;

		$this -> table = $wpdb -> prefix . strtolower($this -> pre) . "_" . $this -> controller;

		$this -> check_table($this -> model);

	

		if (!empty($data)) {

			foreach ($data as $dkey => $dval) {

				$this -> {$dkey} = $dval;

			}

		}

		

		return true;

	}

	

	function defaults() {

		$defaults = array(

			'order'				=>	0,

			'created'			=>	GalleryHtmlHelper::gen_date(),

			'modified'			=>	GalleryHtmlHelper::gen_date(),

		);

		

		return $defaults;

	}

	

	function validate($data = null) {

		$this -> errors = array();

	

		if (!empty($data)) {

			$data = (empty($data[$this -> model])) ? $data : $data[$this -> model];

			

			foreach ($data as $dkey => $dval) {

				$this -> data -> {$dkey} = stripslashes($dval);

			}

			

			extract($data, EXTR_SKIP);

			

			if (empty($title)) { $this -> errors['title'] = __('Please fill in a title', $this -> plugin_name); }

			if (empty($description)) { $this -> errors['description'] = __('Please fill in a description', $this -> plugin_name); }

			if (empty($image_url)) { $this -> errors['image_url'] = __('Please specify an image', $this -> plugin_name); }

			else {

				if ($image = wp_remote_fopen($image_url)) {

					$filename = basename($image_url);

					$filepath = ABSPATH . 'wp-content' . DS . 'uploads' . DS . $this -> plugin_name . DS;

					$filefull = $filepath . $filename;

					

					if (!file_exists($filefull)) {

						$fh = @fopen($filefull, "w");

						@fwrite($fh, $image);

						@fclose($fh);

						

						$name = GalleryHtmlHelper::strip_ext($filename, 'filename');

						$ext = GalleryHtmlHelper::strip_ext($filename, 'ext');

						$thumbfull = $filepath . $name . '-thumb.' . $ext;

						$smallfull = $filepath . $name . '-small.' . $ext;

						

						image_resize($filefull, $width = null, $height = 75, $crop = false, $append = 'thumb', $dest = null, $quality = 100);

						image_resize($filefull, $width = 50, $height = 50, $crop = true, $append = 'small', $dest = null, $quality = 100);

						

						@chmod($filefull, 0777);

						@chmod($thumbfull, 0777);

						@chmod($smallfull, 0777);

					}

				}

			}

		} else {

			$this -> errors[] = __('No data was posted', $this -> plugin_name);

		}

		

		return $this -> errors;

	}

}



?>