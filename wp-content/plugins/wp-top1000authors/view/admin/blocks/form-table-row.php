<?php
/**
 * Form table row.
 * @author Dave Ligthart <info@daveligthart.com>
 * @version 0.1
 */
?>

	<tr>
		<th scope="row">
			<label for="<?php echo $input_key; ?>"><?php echo $label_name;?>:</label>
		</th>
		<td>
			<?php echo '<input type="text" size="60" name="'.$input_key.'" id="'.$input_key.'" value="'.$input_value.'" />' . "\n"; ?>
			<br/>
			<?php echo $input_description; ?>
		</td>
	</tr>