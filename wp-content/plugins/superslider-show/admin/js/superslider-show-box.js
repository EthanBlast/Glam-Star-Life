
	function insertAtCursor(myField, myValue) {
		//IE support
		if (document.selection) {
			myField.focus();
			sel = document.selection.createRange();
			sel.text = myValue;
		}
		//MOZILLA/NETSCAPE support
		else if (myField.selectionStart || myField.selectionStart == '0') {
			var startPos = myField.selectionStart;
			var endPos = myField.selectionEnd;
			myField.value = myField.value.substring(0, startPos)
			+ myValue
			+ myField.value.substring(endPos, myField.value.length);
		} else {
			myField.value += myValue;
		}
	}
	function addshow() {
		//var form = 'document.showshortcode';
		var show_code = '[';
		var f = document.getElementById('ss_shortcode'); 
			show_code = show_code+f.value+' ';			
		var f = document.getElementById('myid'); 
		if (f.value != "") {
			show_code = show_code+'id="'+f.value+'" ';
			}
		var f = document.getElementById('fromfolder'); 
		if (f.value != "") {
			show_code = show_code+'fromfolder="'+f.value+'" ';
			}
		var f = document.getElementById('show_class'); 
		if (f.value != "") {
			show_code = show_code+'show_class="'+f.value+'" ';
			}
		var f = document.getElementById('href'); 
		if (f.value != "") {
			show_code = show_code+'href="'+f.value+'" ';
			}
		var f = document.getElementById('css_theme'); 
		if (f.value != "") {
			show_code = show_code+'css_theme="'+f.value+'" ';
			}
		var f = document.getElementById('show_type'); 
		if (f.value != "") {
			show_code = show_code+'show_type="'+f.value+'" ';
			}
		var f = document.getElementById('image_size'); 
		if (f.value != "") {
			show_code = show_code+'image_size="'+f.value+'" ';
			}
		var f = document.getElementById('pop_size'); 
		if (f.value != "") {
			show_code = show_code+'pop_size="'+f.value+'" ';
			}
		var f = document.getElementById('limit'); 
		if (f.value != "") {
			show_code = show_code+'limit="'+f.value+'" ';
			}
		var f = document.getElementById('first'); 
		if (f.value != "") {
			show_code = show_code+'first_slide="'+f.value+'" ';
			}
		var f = document.getElementById('zoom'); 
		if (f.value != "") {
			show_code = show_code+'zoom="'+f.value+'" ';
			}
		var f = document.getElementById('pan'); 
		if (f.value != "") {
			show_code = show_code+'pan="'+f.value+'" ';
			}
		var f = document.getElementById('color'); 
		if (f.value != "") {
			show_code = show_code+'color="'+f.value+'" ';
			}
		var f = document.getElementById('height'); 
		if (f.value != "") {
			show_code = show_code+'height="'+f.value+'" ';
			}
		var f = document.getElementById('width'); 
		if (f.value != "") {
			show_code = show_code+'width="'+f.value+'" ';
			}
		var f = document.getElementById('delay'); 
		if (f.value != "") {
			show_code = show_code+'delay="'+f.value+'" ';
			}
		var f = document.getElementById('duration'); 
		if (f.value != "") {
			show_code = show_code+'duration="'+f.value+'" ';
			}
		var f = document.getElementById('linkedto'); 
		if (f.value != "") {
			show_code = show_code+'linked="'+f.value+'" ';
			}
		var f = document.getElementById('fast'); 
		if (f.checked) {
			show_code = show_code+'fast="true" ';
			}
		var f = document.getElementById('captions'); 
		if (f.checked) {
			show_code = show_code+'captions="true" ';
			}
		var f = document.getElementById('overlap'); 
		if (f.checked) {
			show_code = show_code+'overlap="true" ';
			}
		var f = document.getElementById('thumbnails'); 
		if (f.checked) {
			show_code = show_code+'thumbnails="true" ';
			}
		var f = document.getElementById('thumbframe'); 
		if (f.checked) {
			show_code = show_code+'thumbframe="true" ';
			}
		var f = document.getElementById('mythumbsize'); 
		if (f.value != "") {
			show_code = show_code+'thumbsize="'+f.value+'" ';
			}
		var f = document.getElementById('mouseover'); 
		if (f.checked) {
			show_code = show_code+'mouseover="true" ';
			}
		var f = document.getElementById('paused'); 
		if (f.checked) {
			show_code = show_code+'paused="true" ';
			}
		var f = document.getElementById('random'); 
		if (f.checked) {
			show_code = show_code+'random="true" ';
			}
		var f = document.getElementById('loop'); 
		if (f.checked) {
			show_code = show_code+'loop="true" ';
			}
		var f = document.getElementById('loader'); 
		if (f.checked) {
			show_code = show_code+'loader="true" ';
			}
		var f = document.getElementById('controller'); 
		if (f.checked) {
			show_code = show_code+'controller="true" ';
			}
		var f = document.getElementById('center'); 
		if (f.checked) {
			show_code = show_code+'center="true" ';
			}
		var f = document.getElementById('resize'); 
		if (f.checked) {
			show_code = show_code+'resize="true" ';
			}
		var f = document.getElementById('exclude'); 
		if (f.value != "") {
			show_code = show_code+'exclude="'+f.value+'" ';
			}
		var f = document.getElementById('clear'); 
		if (f.value != "") {
			show_code = show_code+'clear="'+f.value+'" ';
			}	
				show_code = show_code+']';
				var destination1 = document.getElementById('content');
				
				if (destination1) {
				// calling the function
				    insertAtCursor(destination1, show_code);
				}
				
				/*var destination2 = content_ifr.tinymce;
				var destination2 = window.frames[0].document.getelementbyid('tinymce')
				if (destination2) {
					destination2.value += show_code;
					 alert(document.frames("content_ifr").document.getelementbyid('tinymce').value);
					}*/
			
}
