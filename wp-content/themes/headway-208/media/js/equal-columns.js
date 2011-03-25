if(typeof headway_equal_column_heights != 'function'){
	function headway_equal_column_heights(){	
		document.getElementsByClassName = function(className){
			var hasClassName = new RegExp("(?:^|\\s)" + className + "(?:$|\\s)");
			var allElements = document.getElementsByTagName("*");
			var results = [];

			var element;
			for (var i = 0; (element = allElements[i]) != null; i++) {
				var elementClass = element.className;
				if (elementClass && elementClass.indexOf(className) != -1 && hasClassName.test(elementClass))
					results.push(element);
			}
			
			return results;
		}

		numberColumns = document.getElementsByClassName('leafs-column').length;
		maxHeight = 0;

		for(i = 1; i <= numberColumns; i++){
			column = document.getElementsByClassName('leafs-column-' + i)[0];

			height = column.offsetHeight;

		    topBorder = retrieveComputedStyle(column, "borderTopWidth");
		    bottomBorder = retrieveComputedStyle(column, "borderBottomWidth");
			paddingTop = retrieveComputedStyle(column, "paddingTop");
			paddingBottom = retrieveComputedStyle(column, "paddingBottom");

			if(topBorder == null){ topBorder = '0'; }
			if(bottomBorder == null){ bottomBorder = '0'; }
			if(paddingTop == null){ paddingTop = '0'; }
			if(paddingBottom == null){ paddingBottom = '0'; }
			
			border = Number(topBorder.replace("px", "")) + Number(bottomBorder.replace("px", ""));
			padding = Number(paddingTop.replace("px", "")) + Number(paddingBottom.replace("px", ""));
			
			if(isNaN(border)) border = 0;
			if(isNaN(padding)) padding = 0;
			
			extras = border + padding;

			totalHeight = height + extras; 

			if(maxHeight == 0 || totalHeight > maxHeight){
				maxHeight = totalHeight;
			}
		}

		for(i = 1; i <= numberColumns; i++){
			column = document.getElementsByClassName('leafs-column-' + i)[0];
			
			column.style.height = maxHeight+'px';
		}
	}

	function retrieveComputedStyle(element, styleProperty){	
		if (element.currentStyle)
			var style = element.currentStyle[styleProperty];
		else if (window.getComputedStyle)
			var style = document.defaultView.getComputedStyle(element, null).getPropertyValue(styleProperty);

		return style;
	}
	
	function addLoadListener(fn){
		if (typeof window.addEventListener != 'undefined'){
			window.addEventListener('load', fn, false);
		} else if (typeof document.addEventListener != 'undefined'){
			document.addEventListener('load', fn, false);
		} else if (typeof window.attachEvent != 'undefined'){
			window.attachEvent('onload', fn);
		} else {
			var oldfn = window.onload;
			if (typeof window.onload != 'function'){
				window.onload = fn;
			} else {
				window.onload = function(){
					oldfn();
					fn();
				};
			}
		}
	}
}

if(typeof headway_blog_url == 'undefined'){
	addLoadListener(headway_equal_column_heights);
}