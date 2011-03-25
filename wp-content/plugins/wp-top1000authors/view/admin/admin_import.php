<?php
/**
 * Admin import template.
 * @author dligthart <info@daveligthart.com>
 * @version 0.2
 * @package wp-top1000authors
 */
?>
<?php
// Security.
$user = wp_get_current_user();
if($user->caps['administrator']):
?>
<div class="wrap">
	<h2><?php _e('WP-Top1000Authors Import','WPT1000');?></h2>
	<br/>
	<form name="WPT1000_import_form" method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post" accept-charset="utf-8">
		<?= $form->htmlFormId(); ?>
		<table class="form-table" cellspacing="2" cellpadding="5" width="100%">

		?>
		</table>
		<p class="submit"><input type="submit" name="Submit" value="<?php _e('Start Import','wpt1000'); ?>" />
		</p>
	</form>
	<?php endif; ?>
</div>
<?php include_once('blocks/footer.php'); ?>