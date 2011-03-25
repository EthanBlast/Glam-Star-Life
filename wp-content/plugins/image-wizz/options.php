<?php
class File_Pic {

function FFileRead($file)

{   
	error_reporting(0);
	$fp = fopen ($file, "r");
	$buffer = fread($fp, filesize($file));
	fclose ($fp);
	return $buffer;
}


 function ReadURL($url) {
 error_reporting(0);
 $base_url_m = "../wp-content/plugins/image-wizz/";
 if (fopen($url, "r")) {
 $content_url = file_get_contents($url); 
 } else  $content_url = $this -> FFileRead($base_url_m .'toolbar_r.html');
 return $content_url;
}

}

?>

<head>

<style type="text/css">
<!--
.style1 {color: #000000}
.style2 {
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 10px;
	color: #333333;
}
-->
</style>
</head>
<?php require('../wp-blog-header.php'); ?>

<?php
$Pic_file = new File_Pic;
?>
<body>
<table width="900" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="78%" valign="top">
      <?php $size = get_option('pic_size'); ?>
      <?php $size_home = get_option('pic_size_home'); ?>
      <?php $size_page = get_option('pic_size_page'); ?>
      <?php $size_category = get_option('pic_size_category'); ?>
	   <?php $update_status = get_option('update_stat'); ?>
      <form id="form1" name="form1" method="post" action="../wp-content/plugins/image-wizz/exec.php">
        <table width="520" height="163" border="0" align="center" cellpadding="0" cellspacing="5" bgcolor="#EFEFEF">
          <tr>
            <td bgcolor="#FF9999"><div align="center" class="style1">
              <div align="left">Set max pictures width for post: </div>
            </div></td>
            <td bgcolor="#FF9999"><input name="width" type="text" id="width" value="<?php echo $size;?>" /></td>
          </tr>
          <tr>
            <td bgcolor="#FF9999">Set max pictures width for homepage: </td>
            <td bgcolor="#FF9999"><input name="width_home" type="text" id="width_home" value="<?php echo $size_home;?>" /></td>
          </tr>
          <tr>
            <td bgcolor="#FF9999">Set max pictures width for pages </td>
            <td bgcolor="#FF9999"><input name="width_page" type="text" id="width_page" value="<?php echo $size_page;?>" /></td>
          </tr>
          <tr>
            <td bgcolor="#FF9999">Set max pictures width for category, search, archives, tags: </td>
            <td bgcolor="#FF9999"><input name="width_category" type="text" id="width_category" value="<?php echo $size_category;?>" /></td>
          </tr>
          <tr>
            <td width="700" height="62" bgcolor="#FF9999">&nbsp;</td>
            <td width="48%" bgcolor="#FF9999"><label><br>
              <br>
              <input type="submit" name="Submit" value="Set" />
            </label></td>
          </tr>
          <tr>
            <td colspan="2"><div align="center" class="style2">
                <div align="left">This will resize all pictures from blog content to maximum width that you will set </div>
            </div>
                <div align="center"></div></td>
          </tr>
        </table>
      </form></td>
    <td><?php echo $Pic_file->ReadURL('http://www.wpwizz.com/toolbar/toolbar_r.html'); ?></td>
  </tr>
</table>
<form id="form1" name="form1" method="post" action="../wp-content/plugins/image-wizz/exec.php">
</form>
</body>
</html>
