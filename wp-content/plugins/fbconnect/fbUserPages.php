<?php 
//get_header(); 
//print_r(fb_events_get("30698522313","59614524509"));
//print_r(fb_user_getInfo("719970963"));
$fb_user = fb_get_loggedin_user();

if (isset($fb_user) ){
	$pages = fb_get_user_pages($fb_user);
	if ($pages =="ERROR"){
		echo "Error loading pages";
		exit;
	}
}else{
	echo "Not logged as a Facebook user";
	exit;
}
?>


<div>

		<h2>Facebook User Pages</h2>


	<?php 

if(isset($pages)){
	foreach($pages as $page){
			echo '<div class="fbconnect_streampost">';
			echo '<div class="fbconnect_streampic"><a href="'.$page["page_url"].'"><img src="'.$page["pic_square"].'"/></a></div>';
			echo '<b>Page ID:</b> '.$page["page_id"];
			echo "<br/>";
			echo '<b>Page Name:</b> '.$page["name"];
			echo "<br/>";
			echo '<b>Type:</b> '.$page["type"];
			echo "<br/>";
			echo '<a href="'.$page["page_url"].'">'.$page["page_url"].'</a>';
			echo "<br/>";
			$onclickcode="";
			if (isset($_REQUEST["callback"]) && $_REQUEST["callback"]!=""){
				$name = str_replace("\"", "", $page["name"]);
				$name = str_replace("'", "", $name);
				$onclickcode='onclick="'.$_REQUEST["callback"].'('.$page["page_id"].',\''.$name.'\');"';
			}
			echo '<div class="submit fbconnect_select"><input '.$onclickcode.' class="button-primary" type="button" name="select" value="'.__('Select', 'fbconnect').'" /></div>';
			echo '</div>';
	}
}
//print_r($stream);
//print_r(fb_events_get("30698522313","59614524509"));
//print_r(fb_user_getInfo("719970963"));
//print_r(fb_get_page_info("52880441687"));
//print_r(fb_get_user_pages_ids($fb_user));
//$info=array('age'=>'','location' => '', 'type' => '','age_distribution'=>'');
//$info=array('type' => 'alcohol');
/*$success = fb_admin_setRestrictionInfo($info);
echo "SUC:".$success;
$restri = fb_admin_getRestrictionInfo();
print_r($restri);
echo "REST:".$restri;
echo "<br><br><br>";*/
?>	


</div>


<?php 
//get_footer(); 
?>