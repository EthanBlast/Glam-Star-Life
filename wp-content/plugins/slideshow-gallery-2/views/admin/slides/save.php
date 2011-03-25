<div class="wrap">
	<h2><?php _e('Save a Slide', $this -> plugin_name); ?></h2>
	
	<form action="<?php echo $this -> url; ?>&amp;method=save" method="post">
		<input type="hidden" name="Slide[id]" value="<?php echo $this -> Slide -> data -> id; ?>" />
		<input type="hidden" name="Slide[order]" value="<?php echo $this -> Slide -> data -> order; ?>" />
	
		<table class="form-table">
			<tbody>
				<tr>
					<th><label for="Slide.title"><?php _e('Title', $this -> plugin_name); ?></label></th>
					<td>
						<input class="widefat" type="text" name="Slide[title]" value="<?php echo esc_attr($this -> Slide -> data -> title); ?>" id="Slide.title" />
						<?php echo (!empty($this -> Slide -> errors['title'])) ? '<div style="color:red;">' . $this -> Slide -> errors['title'] . '</div>' : ''; ?>
					</td>
				</tr>
				<tr>
					<th><label for="Slide.description"><?php _e('Description', $this -> plugin_name); ?></label></th>
					<td>
						<textarea class="widefat" name="Slide[description]"><?php echo esc_attr($this -> Slide -> data -> description); ?></textarea>
						<?php echo (!empty($this -> Slide -> errors['description'])) ? '<div style="color:red;">' . $this -> Slide -> errors['description'] . '</div>' : ''; ?>
					</td>
				</tr>
				<tr>
					<th><label for="Slide.image_url"><?php _e('Image URL', $this -> plugin_name); ?></th>
					<td>
						<input class="widefat" type="text" name="Slide[image_url]" value="<?php echo esc_attr($this -> Slide -> data -> image_url); ?>" id="Slide.image_url" />
						<?php echo (!empty($this -> Slide -> errors['image_url'])) ? '<div style="color:red;">' . $this -> Slide -> errors['image_url'] . '</div>' : ''; ?>
					</td>
				</tr>
				<tr>
					<th><label for=""><?php _e('Use Link', $this -> plugin_name); ?></label></th>
					<td>
						<label><input onclick="jQuery('#Slide_uselink_div').show();" <?php echo ($this -> Slide -> data -> uselink == "Y") ? 'checked="checked"' : ''; ?> type="radio" name="Slide[uselink]" value="Y" id="Slide_uselink_Y" /> <?php _e('Yes', $this -> plugin_name); ?></label>
						<label><input onclick="jQuery('#Slide_uselink_div').hide();" <?php echo (empty($this -> Slide -> data -> uselink) || $this -> Slide -> data -> uselink == "N") ? 'checked="checked"' : ''; ?> type="radio" name="Slide[uselink]" value="N" id="Slide_uselink_N" /> <?php _e('No', $this -> plugin_name); ?></label>
					</td>
				</tr>
			</tbody>
		</table>
		
		<div id="Slide_uselink_div" style="display:<?php echo ($this -> Slide -> data -> uselink == "Y") ? 'block' : 'none'; ?>;">
			<table class="form-table">
				<tbody>
					<tr>
						<th><label for="Slide.link"><?php _e('Link To', $this -> plugin_name); ?></label></th>
						<td><input class="widefat" type="text" name="Slide[link]" value="<?php echo esc_attr($this -> Slide -> data -> link); ?>" id="Slide.link" /></td>
					</tr>
				</tbody>
			</table>
		</div>
		
		<p class="submit">
			<input class="button-primary" type="submit" name="submit" value="<?php _e('Save Slide', $this -> plugin_name); ?>" />
		</p>
	</form>
</div>