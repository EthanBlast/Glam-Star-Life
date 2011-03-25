<?php
	/* Theme Two-Cols Config */
	$text_on_right_col = true; // Set it to true to use right column for text and left column for image
	/* End of Config */
?>

	<div class="theme-two-cols">
		<div class="arrow-right">
			<?php if ($config['ARROWS']): ?>
			<p class="arrow">
				<a href="javascript:stepcarousel.stepBy('carousel_<?php echo $c_id; ?>', <?php echo $config['SLIDE_POSTS']; ?>)">
					<span class="hide"><?php printf(__('Forward %s panel', 'wp_carousel'), $config['SLIDE_POSTS']); ?></span>
				</a>
			</p>
			<?php endif; ?>
		</div>
		<div class="arrow-left">
			<?php if ($config['ARROWS']): ?>
			<p class="arrow">
				<a href="javascript:stepcarousel.stepBy('carousel_<?php echo $c_id; ?>', -<?php echo $config['SLIDE_POSTS']; ?>)">
					<span class="hide"><?php printf(__('Back %s panel', 'wp_carousel'), $config['SLIDE_POSTS']); ?></span>
				</a>
			</p>
			<?php endif; ?>
		</div>
		<div id="carousel_<?php echo $c_id; ?>" class="stepcarousel">
		
			<div class="belt">
				<?php foreach ($items as $i_id => $item): ?>
				<div class="panel">
					<div class="panel-col-left" <?php if (!$text_on_right_col) { ?>style="margin-right:100px;"<?php } ?>>
						<?php if ($text_on_right_col) { ?>
						<a href="<?php echo $item['LINK_URL']; ?>" title="<?php echo $item['TITLE']; ?>" class="panel-image-link">
							<img src="<?php echo $item['IMAGE_URL']; ?>" alt="<?php echo $item['TITLE']; ?>" title="<?php echo $item['TITLE']; ?>" />
						</a>
						<?php } else { ?>
						<div class="panel-text">
							<?php echo $item['DESC']; ?>
						</div>
						<?php } ?>
					</div>
					<div class="panel-col-right" <?php if ($text_on_right_col) { ?>style="margin-left:100px;"<?php } ?>>
						<?php if ($text_on_right_col) { ?>
						<div class="panel-text">
							<?php echo $item['DESC']; ?>
						</div>
						<?php } else { ?>
						<a href="<?php echo $item['LINK_URL']; ?>" title="<?php echo $item['TITLE']; ?>" class="panel-image-link">
							<img src="<?php echo $item['IMAGE_URL']; ?>" alt="<?php echo $item['TITLE']; ?>" title="<?php echo $item['TITLE']; ?>" />
						</a>
						<?php } ?>
					</div>
					<div class="panel-col-right"></div><div class="panel-col-left"></div>
				</div>
				<?php endforeach; ?>
			</div>
		
		</div>
	</div>

	<?php if ($config['ENABLE_PAGINATION']): ?>
	<div id="carousel_<?php echo $c_id; ?>-paginate" class="wp_carousel_default_pagination">
		<img src="<?php echo $wp_carousel_path[6]; ?>img/opencircle.png" data-over="<?php echo $wp_carousel_path[6]; ?>img/graycircle.png" data-select="<?php echo $wp_carousel_path[6]; ?>img/closedcircle.png" data-moveby="1" />
	</div>
	<?php endif; ?>