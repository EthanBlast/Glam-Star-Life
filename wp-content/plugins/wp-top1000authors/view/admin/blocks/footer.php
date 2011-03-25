<?php
/**
 * Footer block template.
 * @author Dave Ligthart <info@daveligthart.com>
 * @version 0.1
 */
?>
<div class="wrap">
	<p>
		<br style="clear:both" />
		<img style="float:left;" src="<?php bloginfo ('wpurl') ?>/wp-content/plugins/<?=$plugin_name; ?>/resources/images/logo.png" alt="daveligthart logo"/>
		<div align="center" style="border:0px solid #000000;">
			<h1>&copy; <?= date('Y') ?>&nbsp;-&nbsp;
			<?php _e ('Some rights reserved'); ?>.
			<a href="http://www.daveligthart.com" target="_blank">daveligthart.com</a>
			</h1>
			<?php _e ('Plugin by'); ?>
			<a href="http://daveligthart.com" title="Dave Ligthart" target="_blank">Dave Ligthart</a>
		</div>
	</p>
	<br style="clear:both" />
	<p>
	</p>
</div>
