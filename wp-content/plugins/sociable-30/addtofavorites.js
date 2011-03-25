function AddToFavorites()
{
	var title = document.title; var url = location.href;
	if (window.sidebar && window.sidebar.addPanel) // Firefox
		window.sidebar.addPanel(title, url, '');
	else if(window.opera && window.print) // Opera
	{
		var elem = document.createElement('a');
		elem.setAttribute('href',url);
		elem.setAttribute('title',title);
		elem.setAttribute('rel','sidebar'); // required to work in opera 7+
		elem.click();
	}
	else if(document.all) {
		window.external.AddFavorite(url, title);  // IE
	}
	else if (typeof(window.chrome !== 'undefined')) {
		alert('Please press <Control> + D to bookmark this page in Chrome.');
	}
}