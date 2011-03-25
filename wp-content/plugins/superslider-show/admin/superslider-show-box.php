<?php
$box = '
<div id="show-box">
<form id="showshort" name="showshort"  action="">
			<input name="ss_shortcode" id="ss_shortcode" type="hidden" value="'.$ss_shortcode.'" />
			
			<label class ="ss_label" for="myid">id : <input tabindex="8" type="text" size="8" class="ss_input" name="myid" id="myid"  maxlength="100" value="" /> 
	
	<a href="#show_info_id" class="ss_tool" style="padding: 2px 8px;"> ? </a></label>
    <div id ="show_info_id" class="info_box" style="display:none;">
                        <h3>field: id info </h3>
                        To use images attached to this post, leave the id field empty.
                        Or add the id number of a single post with images to slide.<br />
                        Or add a Comma seperated list of post numbers.<br />
                        Or add recent@ 7, 9, 12 where 7, 9, 12 are category numbers to include.(shows latest from each category)<br />
                        Or add recent: 7, 9, 12 where 7, 9, 12 are category numbers to exclude.<br />
                        Or add category: 7 where 7 is the category number.<br />
                        Or add featured which pulls posts with custom field named featured.<br />
                        Or add nextgen-2 where 2 is the next-gen gallery number.<br />
                        Or add random@7 where 7 is the cat number.<br />
                        Or add random@ which gets random from all cats.
                        (remember to add limit=\"any number\" to your shortcode or you\'ll get ALL attachments). 
                        
                        </div>

			<label class ="ss_label" for="fromfolder">folder : <input tabindex="9" type="text" size="8" class="ss_input" name="fromfolder" id="fromfolder"  maxlength="100" value="" />
	
	<a href="#show_info_folder" class="ss_tool" style="padding: 2px 8px;"> ? </a></label>
    <div id ="show_info_folder" class="info_box" style="display:none;">
                       <h3>field: folder info </h3>
                        Enter the name of the folder.<br />
                        The folder of images must be in wp-content.<br />
                        You can enter sub folders ie: images/summer/beach<br />
                        Remember to attach one image to this post (required) for the fromfolder option to work.
                        </div>
            <label class ="ss_label" for="height">show height : <input tabindex="18" type="text" class="ss_input" name="height" id="height" size="4" maxlength="4" value="" /></label> 
			<label class ="ss_label" for="width">show width : <input tabindex="19" type="text" class="ss_input" name="width" id="width" size="4" maxlength="4" value="" /></label>
			           
			
			
			
			<label class ="ss_label" for="show_type">Trans style
					<select name="show_type" id="show_type" tabindex="12" >
						<option id="op_show_type" value=\'\'> select</option>
						 <option id="op_show_type1" value=\'default\'> default</option>
						 <option id="op_show_type2" value=\'kenburns\'> kenburns</option>
						 <option id="op_show_type3" value=\'push\'> push</option>
						 <option id="op_show_type4" value=\'fold\'> fold</option>
						 <option id="op_show_type5" value=\'flash\'> flash</option>
						 <!--<option id="op_show_type6" value=\'shrink\'> shrink</option>-->
					</select></label>
			<label class ="ss_label" for="image_size">Image size
					<select name="image_size" id="image_size" tabindex="13" >
						<option id="image_size0" value=\'\'> select</option>
						 <option id="image_size1" value=\'thumbnail\'> thumbnail</option>
						 <option id="image_size2" value=\'medium\'> medium</option>
						 <option id="image_size3" value=\'slideshow\'> slideshow</option>
						 <option id="image_size4" value=\'large\'> large</option>
						 <option id="image_size5" value=\'full\'> full</option>
					</select></label>
			
			<label class ="ss_label" for="linkedto">Linked to
					<select name="linkedto" id="linkedto" tabindex="15" >
						<option id="linkedto_0" value=\'\'> select</option>
						 <option id="linkedto_1" value=\'attach\'> attach</option>
						 <option id="linkedto_2" value=\'parent\'> parent</option>
						 <option id="linkedto_3" value=\'lightbox\'> lightbox</option>
					</select></label>
			<label class ="ss_label" for="limit">limit slides : <input tabindex="16" type="text" class="ss_input" name="limit" id="limit" size="2" maxlength="2" value="" />
			<a href="#show_info_limit" class="ss_tool" style="padding: 2px 8px;"> ? </a></label>
    <div id ="show_info_limit" class="info_box" style="display:none;">
                        <h3>field: limit info </h3>
                        To limit the total number of slides in the show enter that number here. (default limit is 50).
                        </div>
			
			
			
			<br style="clear:both;" />
			<div class="ss-show-advanced" style="display: none;">
			<div>
			<label class ="ss_label" for="pop_size">Pop size
					<select name="pop_size" id="pop_size" tabindex="14" >
						<option id="pop_size0" value=\'\'> select</option>
						 <option id="pop_size1" value=\'thumbnail\'> thumbnail</option>
						 <option id="pop_size2" value=\'medium\'> medium</option>
						 <option id="pop_size3" value=\'slideshow\'> slideshow</option>
						 <option id="pop_size4" value=\'large\'> large</option>
						 <option id="pop_size5" value=\'full\'> full</option>
					</select></label>
					
			<label class ="ss_label" for="css_theme">theme : 
			<select name="css_theme" id="css_theme" tabindex="12" >
						<option id="css_theme0" value=\'\'> select</option>
						 <option id="css_theme1" value=\'default\'> default</option>
						 <option id="css_theme2" value=\'blue\'> blue</option>
						 <option id="css_theme3" value=\'black\'> black</option>
						 <option id="css_theme4" value=\'custom\'> custom</option>
					</select>

			</label>
			<label class ="ss_label" for="first">first slide : <input tabindex="17" type="text" class="ss_input" name="first" id="first" size="3" maxlength="3" value="" />
			
			<a href="#show_info_first" class="ss_tool" style="padding: 2px 8px;"> ? </a></label>
    <div id ="show_info_first" class="info_box" style="display:none;">
                        <h3>field: first slide info </h3>
                        The slide count starts at 0, to start the show at slide 3 you would enter the number 2 here. 
                        </div>
		    
		    <label class ="ss_label" for="exclude">exclude : <input tabindex="37" type="text" size="6" class="ss_input" name="exclude" id="exclude"  maxlength="100" value="" />
		    <a href="#show_info_exclude" class="ss_tool" style="padding: 2px 8px;"> ? </a></label>
    <div id ="show_info_exclude" class="info_box" style="display:none;">
                        <h3>field: exclude info </h3>
                        To exclude specific images, enter a comma seperated list of attachment id numbers. 
                        </div>
			
			
			
			
			
			<label class ="ss_label" for="show_class">class : <input tabindex="10" type="text" size="8"  class="ss_input" name="show_class" id="show_class"  maxlength="20" value="" />
			<a href="#show_info_showclass" class="ss_tool" style="padding: 2px 8px;"> ? </a></label>
    <div id ="show_info_showclass" class="info_box" style="display:none;">
                        <h3>field: class info </h3>
                        Add a unique class name to this show. If using the featured function you would want to add the class featured.
                        </div>
			
			<label class ="ss_label" for="href">href : <input tabindex="11" type="text" size="10"  class="ss_input" name="href" id="href"  maxlength="100" value="" />
	        <a href="#show_info_href" class="ss_tool" style="padding: 2px 8px;"> ? </a></label>
	<div id ="show_info_href" class="info_box" style="display:none;">
                        <h3>field: href info </h3>
                        Add a global link destination to all slideshow images. eg: http://www.google.com
                        </div>
			
			
			<label class ="ss_label" for="zoom">burns zoom : <input tabindex="19" type="text" class="ss_input" name="zoom" id="zoom" size="4" maxlength="100" value="" />
			<a href="#show_info_zoom" class="ss_tool" style="padding: 2px 8px;"> ? </a></label>
	<div id ="show_info_zoom" class="info_box" style="display:none;">
                        <h3>field: zoom info </h3>
                        Add zoom parameters for the kenburns transition type. As a percentage.
                        </div>
			<label class ="ss_label" for="pan">burns pan : <input tabindex="20" type="text" class="ss_input" name="pan" id="pan" size="4" maxlength="100" value="" />
			<a href="#show_info_pan" class="ss_tool" style="padding: 2px 8px;"> ? </a></label>
	<div id ="show_info_pan" class="info_box" style="display:none;">
                        <h3>field: burns pan info </h3>
                        Add pan parameters for the kenburns transition type. As a percentage, from A to B, eg: 25, 75 
                        </div>
                        
			<label class ="ss_label" for="color">flash color : <input tabindex="21" type="text" class="ss_input" name="color" id="color" size="6" maxlength="100" value="" />
			<a href="#show_info_flashcolor" class="ss_tool" style="padding: 2px 8px;"> ? </a></label>
    <div id ="show_info_flashcolor" class="info_box" style="display:none;">
                        <h3>field: flash color info </h3>
                        If you are using the transition type of flash, you can enter a comma seperated list of colors here. eg: #000000, #cdcdcd, #ffffff . The script will cycle through these colors, flashing a transition from the color to the image.
                        </div>
			
			<label class ="ss_label" for="delay">slide delay : <input tabindex="22" type="text" class="ss_input" name="delay" id="delay" size="4" maxlength="6" value="" /></label> 
			<label class ="ss_label" for="duration">Trans time : <input tabindex="23" type="text" class="ss_input" name="duration" id="duration" size="4" maxlength="6" value="" /></label> 				
			<label class ="ss_label" for="fast"><input tabindex="24" name="fast" id="fast" type="checkbox" value="true" />thumb fast change</label>	
			<label class ="ss_label" for="captions"><input tabindex="25" name="captions" id="captions" type="checkbox" value="true" />show captions</label>
			<label class ="ss_label" for="overlap"><input tabindex="26" name="overlap" id="overlap" type="checkbox" value="true" />overlap images</label>
			<label class ="ss_label" for="thumbnails"><input tabindex="27" name="thumbnails" id="thumbnails" type="checkbox" value="true" />show thumbnails</label>
			<label class ="ss_label" for="thumbframe"><input tabindex="28" name="thumbframe" id="thumbframe" type="checkbox" value="true" />thumbnail frame</label>
			<label class ="ss_label" for="mythumbsize">Thumb size
					<select name="mythumbsize" id="mythumbsize" tabindex="28" >
						<option id="mythumbsize0" value=\'\'> select</option>
						 <option id="mythumbsize1" value=\'thumbnail\'> default</option>
						 <option id="mythumbsize3" value=\'minithumb\'> minithumb</option>
					</select></label>
			<label class ="ss_label" for="mouseover"><input tabindex="29" name="mouseover" id="mouseover" type="checkbox" value="true" />mouseover start-stop</label>
			<label class ="ss_label" for="paused"><input tabindex="30" name="paused" id="paused" type="checkbox" value="true" />paused at start</label>
			<label class ="ss_label" for="random"><input tabindex="31" name="random" id="random" type="checkbox" value="true" />random image order</label>	
			<label class ="ss_label" for="loop"><input tabindex="32" name="loop" id="loop" type="checkbox" value="true" />loop back to start</label>
			<label class ="ss_label" for="loader"><input tabindex="33" name="loader" id="loader" type="checkbox" value="true" />show image loader</label>
			<label class ="ss_label" for="controller"><input tabindex="34" name="controller" id="controller" type="checkbox" value="true" />show controller</label>
			<label class ="ss_label" for="center"><input tabindex="35" name="center" id="center" type="checkbox" value="true" />center image to show</label>	
			<label class ="ss_label" for="resize"><input tabindex="36" name="resize" id="resize" type="checkbox" value="true" />resize image to fit</label>
			<label class ="ss_label" for="clear">Clear
					<select name="clear" id="clear" tabindex="38" >
						<option id="clear0" value=\'\'> none</option>
						 <option id="clear1" value=\'left\'> left</option>
						 <option id="clear2" value=\'right\'> right</option>
						 <option id="clea3" value=\'both\'> both</option>
					</select></label>
				
		<div style=" padding: 10px; clear: left;">
		<a href="" class="ss-toggler-close" >close</a>
	</div>
				</div></div>
				
			<input type="button" tabindex="39" value="Add Show" name="sendtotextfield" id="sendtotextfield" class="button-primary action" style="margin:10px 40px 0 10px; float: right;" onclick="addshow(); return false;" />
<div class="ss-toggler-holder" style=" padding: 10px; clear: left;">
		<a href="" class="ss-toggler-open" >advanced</a>
	</div>
</form>
<br style="clear:both;" /><p>This shortcode helper presently only works for the Html view.</p>
</div>
';
?>