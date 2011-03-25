<?php
global $headway_admin_success;
	
if(isset($headway_admin_success) && $headway_admin_success === true)
	echo '<div class="success"><span>Hooks Updated!</span> <a href="'.get_bloginfo('url').'">View Site &raquo;</a></div>';
?>

<p>Headway Easy Hooks provides you a simple way to add content to your site that would otherwise be impossible.  You can use HTML in the following boxes.  If you need to use PHP, please refer to the documentation on how to use actual hooks.</p>

<form method="post">
	<input type="hidden" value="<?php echo wp_create_nonce('headway-admin-nonce'); ?>" name="headway-admin-nonce" />

			<table class="form-table">				
				<tr>
					<th scope="row"><label for="select-hook">Select a Hook</label></th>
					<td>
						<select name="select-hook" id="select-hook" style="width: 240px;">
							
							<?php
								foreach(headway_get_hooks() as $group => $hooks){
									echo '<optgroup label="'.$group.'">';
								
										foreach($hooks as $hook){
											$customized = (headway_get_option('easy-hooks-'.$hook[0])) ? 'Customized: ' : false;
											$customized_class = $customized ? ' class="customized"' : false;
										
											echo '<option value="'.$hook[0].'"'.$customized_class.'>&nbsp;&nbsp;'.$customized.$hook[1].'</option>';
										}
								
									echo '</optgroup>';
								}
							?>
						
						</select>
					</td>
				</tr>
				
				
				<?php
					foreach(headway_get_hooks() as $group => $hooks){
						foreach($hooks as $hook){
							$i++;
							
							if($i !== 1) $display = ' style="display: none;"'; 	
				?>
							<tr<?php echo $display; ?> class="hook" id="<?php echo $hook[0]; ?>">
								<th scope="row"><label for="<?php echo $hook[0]; ?>"><?php echo $hook[1]; ?></label></th>
								<td><textarea rows="15" cols="55" class="regular-text" id="<?php echo $hook[0]; ?>" name="easy-hooks-<?php echo $hook[0]; ?>" style="float:left;"><?php echo stripslashes(headway_get_option('easy-hooks-'.$hook[0]))?></textarea>
								<span class="setting-description" style="float:left;clear:left;margin:5px 0 0 2px;"><?php echo $hook[2]; ?></span></td>
							</tr>
				<?php
						}
					}
				?>
			</table>
			
		
					
		
	</div>

	<p class="submit">
	<input type="submit" value="Save Changes" class="button-primary" name="headway-submit"/>
	</p>
</form>