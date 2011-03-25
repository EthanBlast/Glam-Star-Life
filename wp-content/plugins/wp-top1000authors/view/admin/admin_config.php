<?php
/**
 * Admin config template.
 * @author dligthart <info@daveligthart.com>
 * @version 0.2
 * @package wp-top1000authors
 */

$cache_path = dirname(__FILE__) . '/../../../../cache/';

// Security.
$user = wp_get_current_user();

if($user->caps['administrator']):

?>
<div class="wrap">
	<h2><?php _e('WP-Top1000Authors Options', 'wpt1000');?></h2>
	<p>
		<?php if(!file_exists($cache_path)): ?>

		<strong><?php _e('wp-content/cache does not exist:','wpt1000');?></strong>
		&nbsp;<?php _e('please make sure that the "wp-content/cache" directory is created','wpt1000');?>.
		<br/>

		<?php else: ?>

		<?php if(!is_writable($cache_path)): ?>

		<strong><?php _e('wp-content/cache is not writable:','wpt1000');?></strong>
		&nbsp;<?php _e('please make sure that the "wp-content/cache" directory is writable by webserver.','wpt1000');?>.
		<br/>

		<?php endif; ?>

		<?php endif; ?>
	</p>

	<?php if(file_exists($cache_path) && is_writable($cache_path)): ?>

	<h2><?php _e('Usage', 'wpt1000'); ?></h2>
	<p><?php _e('Display the Wordpress Top 1000 Authors by adding this function in your template file:', 'wpt1000'); ?>
	<pre>
	&lt;?php wpt1000_display(); ?&gt;
	</pre>
	</p>

	<form name="WPT1000_config_form" method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post" accept-charset="utf-8">
		<?= $form->htmlFormId(); ?>
		<table class="form-table" cellspacing="2" cellpadding="5" width="100%">
		<?php dl_load_admin_block('form-table-row',
			array(
			'input_key'=>'wpt1000_profile_name',
			'input_value'=>$form->getProfileName(),
			'input_description'=>__('Enter your WordPress Plugin Developer Profile Name <br/>e.g http://wordpress.org/extend/plugins/profile/<strong>daveligthart-1</strong>', 'wpt1000'),
			'label_name'=>__('Profile Name', 'wpt1000'))
		);
		?>
		</table>
		<p class="submit"><input type="submit" name="Submit" value="<?php _e('Save Changes','wpt1000'); ?>" />
		</p>
	</form>

	<?php if('' != $form->getProfileName()): ?>

	<h2><?php _e('Create the Top 1000 list', 'wpt1000'); ?></h2>
	<form name="WPT1000_import_form" method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post" accept-charset="utf-8">
		<?= $form_import->htmlFormId(); ?>
		<p class="submit"><input type="submit" name="Submit" value="<?php _e('Start Import','wpt1000'); ?>" /></p>
	</form>
	<h2><?php _e('WordPress Plugin Database Stats', 'wpt1000'); ?></h2>
	<div>
	<p><strong><?php _e('Authors:','wpt1000'); ?></strong> <?php echo $form_import->getTotalAuthorCount(); ?>
	</p>
	<p><strong><?php _e('Plugins:', 'wpt1000'); ?></strong> <?php echo $form_import->getTotalPluginCount(); ?>
	</p>
	<p><strong><?php _e('Downloads:', 'wpt1000'); ?></strong> <?php echo $form_import->getTotalDownloadCount(); ?>
	</p>
	<p><strong><?php _e('My rank:', 'wpt1000'); ?></strong> <?php echo wpt1000_get_rank(); ?> / 1000
	</p>
	<p><strong><?php _e('My downloads:', 'wpt1000'); ?></strong> <?php echo wpt1000_get_downloads(); ?>
	</p>
	</div>

	<?php endif; ?>

	<?php endif; ?>

	<?php endif; ?>
</div>
<?php include_once('blocks/footer.php'); ?>