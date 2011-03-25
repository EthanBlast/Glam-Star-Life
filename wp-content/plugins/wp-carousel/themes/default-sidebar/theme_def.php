	<div class="theme-default-sidebar">
		<div class="top-div">
			<?php if ($config['ARROWS']): ?>
			<div class="arrow-right">
				<p class="arrow">
					<a href="javascript:stepcarousel.stepBy('carousel_<?php echo $c_id; ?>', <?php echo $config['SLIDE_POSTS']; ?>)">
						<span class="hide"><?php printf(__('Forward %s panel', 'wp_carousel'), $config['SLIDE_POSTS']); ?></span>
					</a>
				</p>
			</div>
			<div class="arrow-left">
				<p class="arrow">
					<a href="javascript:stepcarousel.stepBy('carousel_<?php echo $c_id; ?>', -<?php echo $config['SLIDE_POSTS']; ?>)">
						<span class="hide"><?php printf(__('Back %s panel', 'wp_carousel'), $config['SLIDE_POSTS']); ?></span>
					</a>
				</p>
			</div>
			<div class="clear"></div>
			<?php endif; ?>
		</div>
	</div>
	<div id="carousel_<?php echo $c_id; ?>" class="stepcarousel theme-default-sidebar-carousel">
	
		<div class="belt">
			<?php foreach ($items as $i_id => $item): ?>
			<div class="panel" <?php if ($config['HAS_PANEL_WIDTH']) echo 'style="width:'.$config['PANEL_WIDTH'].';"'; else echo 'style="width:100px;"';?>>
				<a href="<?php echo $item['LINK_URL']; ?>" title="<?php echo $item['TITLE']; ?>">
					<img src="<?php echo $item['IMAGE_URL']; ?>" alt="<?php echo $item['TITLE']; ?>" title="<?php echo $item['TITLE']; ?>" width="<?php if ($config['HAS_IMG_WIDTH']) { echo $config['IMG_WIDTH']; } else { echo '100px'; } ?>" height="<?php if ($config['HAS_IMG_HEIGHT']) { echo $config['IMG_HEIGHT']; } else { echo '100px'; } ?>" />
				</a>
				<div class="panel-text">
				<?php echo $item['DESC']; ?>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	
	</div>
	<div class="theme-default-sidebar">
		<div class="bottom-div">
			<?php if ($config['ENABLE_PAGINATION']): ?>
			<div id="carousel_<?php echo $c_id; ?>-paginate" class="wp_carousel_default_sidebar_pagination">
				<img src="<?php echo $wp_carousel_path[6]; ?>img/opencircle.png" data-over="<?php echo $wp_carousel_path[6]; ?>img/graycircle.png" data-select="<?php echo $wp_carousel_path[6]; ?>img/closedcircle.png" data-moveby="1" />
			</div>
			<?php endif; ?>
		</div>
	</div>
