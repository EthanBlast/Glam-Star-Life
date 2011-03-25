<?php
/**
 * Add New Slide Framing
 * 
 * @package SlideDeck
 */
?>
<?php
	$count = $_GET['count'] + 1;
	$slide = array(
		'title' => "Slide " . $count,
		'slide_order' => $count,
		'content' => "",
		'gallery_id' => $_GET['gallery_id']
	);
	
    include( dirname( __FILE__ ) . '/_edit-slide.php' );
?>