<?php require('../../../wp-blog-header.php');?> 
<?php
update_option ('pic_size',$_REQUEST['width']);
update_option ('pic_size_home',$_REQUEST['width_home']);
update_option ('pic_size_page',$_REQUEST['width_page']);
update_option ('pic_size_category',$_REQUEST['width_category']);
echo "<script>alert('Values updated!.'); javascript:history.back();</script>";
?>
<FORM><INPUT type=button value=" Back " onClick="history.back();"></FORM> 