<?php
function headway_image_color_palette($imageFile, $maxColors = 5, $granularity = 10, $exclude_white_black = false) {
	$granularity = max(1, abs((int)$granularity));
	$colors = array();
	$size = @getimagesize($imageFile);
	
	if($size === false){
		user_error('Unable to get image dimensions.');
		return false;
	}

	$fileInfo = pathinfo($imageFile);

	if($fileInfo['extension'] == 'png'){
		$img = @imagecreatefrompng($imageFile);
	} elseif($fileInfo['extension'] == 'jpeg' || $fileInfo['extension'] == 'jpg'){
		$img = @imagecreatefromjpeg($imageFile);
	} elseif($fileInfo['extension'] == 'gif'){
		$img = @imagecreatefromgif($imageFile);
	}

	if(!$img){
		user_error('Unable to open image file.');
		return false;
	}
	
	for($x = 0; $x < $size[0]; $x += $granularity){
		for($y = 0; $y < $size[1]; $y += $granularity){			
			$thisColor = imagecolorat($img, $x, $y);
			$rgb = imagecolorsforindex($img, $thisColor);
			
			$hex = headway_rgb_to_hex($rgb, true);
						
			if(!($exclude_white_black && (strtolower($hex) == 'ffffff' || $hex == '000000'))){			
				if(array_key_exists($hex, $colors)){
					$colors[$hex]++;
				} else {
					$colors[$hex] = 1;
				}
			}
		}
	}

	arsort($colors);	
			
	$colors = array_splice(array_keys($colors), 0, $maxColors);
					
	return $colors;
}


function headway_rgb_to_hex($rgb, $round = false){	
	if(!is_array($rgb)) return false;
	
	$keys = array_keys($rgb);	
			
	if(!is_string($keys[0])){				
		$rgb_temp = $rgb;
		
		unset($rgb);
		
		$rgb['red'] = $rgb_temp[0];
		$rgb['green'] = $rgb_temp[1];
		$rgb['blue'] = $rgb_temp[2];
	} else {
		if(isset($rgb['alpha'])) unset($rgb['alpha']);
	}	
	
	$rgb['red'] = ($rgb['red'] >= 0) ? $rgb['red'] : 0;
	$rgb['green'] = ($rgb['green'] >= 0) ? $rgb['green'] : 0;
	$rgb['blue'] = ($rgb['blue'] >= 0) ? $rgb['blue'] : 0;
		
	$rgb['red'] = ($rgb['red'] <= 255) ? $rgb['red'] : 255;
	$rgb['green'] = ($rgb['green'] <= 255) ? $rgb['green'] : 255;
	$rgb['blue'] = ($rgb['blue'] <= 255) ? $rgb['blue'] : 255;	
		
	if($round){
		$rgb['red'] = round(round(($rgb['red'] / 0x33)) * 0x33);
		$rgb['green'] = round(round(($rgb['green'] / 0x33)) * 0x33);
		$rgb['blue'] = round(round(($rgb['blue'] / 0x33)) * 0x33);
	}
		
	return sprintf('%02X%02X%02X', $rgb['red'], $rgb['green'], $rgb['blue']); 	
}


function headway_hex_to_rgb($hex, $return_keys = false){
	if($hex[0] == '#') $hex = substr($hex, 1);

    if(strlen($hex) == 6){
        $r = $hex[0].$hex[1];
		$g = $hex[2].$hex[3];
        $b = $hex[4].$hex[5];
    } elseif(strlen($hex) == 3){
       	$r = $hex[0].$hex[0];
		$g = $hex[1].$hex[1];
        $b = $hex[2].$hex[2];
    } else {
        return false;
	}

    $r = hexdec($r); 
	$g = hexdec($g); 
	$b = hexdec($b);

	if($return_keys){
		return array('red' => $r, 'green' => $g, 'blue' => $b);
	} else {
		return array($r, $g, $b);
	}
}


function headway_rgb_to_lightness($rgb){
	$min = min($rgb);
    $max = max($rgb);

    $lightness = ($max + $min) / 2;

	return (int)round(($lightness / 255)*100);
}