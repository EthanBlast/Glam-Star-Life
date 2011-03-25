=== WordPress jQuery UI Effects ===
Contributors: piouPiouM
Tags: jquery, jquery-ui, effects, library, javascript
Requires at least: 2.8
Tested up to: 3.0-alpha
Stable tag: 1.0.0

Use effects of the jQuery UI Effects library in your themes and plugins!

== Description ==

Easily register and load the effects of the [jQuery UI Effects](http://docs.jquery.com/UI/Effects/ "UI/Effects - jQuery JavaScript Library") library in your themes and plugins.

Supported effects:

* [Blind](http://docs.jquery.com/UI/Effects/Blind "UI/Effects/Blind - jQuery JavaScript Library") - Blinds the element away or shows it by blinding it in.
* [Clip](http://docs.jquery.com/UI/Effects/Clip "UI/Effects/Clip - jQuery JavaScript Library") - Clips the element on or off, vertically or horizontally.
* [Drop](http://docs.jquery.com/UI/Effects/Drop "UI/Effects/Drop - jQuery JavaScript Library") - Drops the element away or shows it by dropping it in.
* [Explode](http://docs.jquery.com/UI/Effects/Explode "UI/Effects/Explode - jQuery JavaScript Library") - Explodes the element into multiple pieces.
* [Fold](http://docs.jquery.com/UI/Effects/Fold "UI/Effects/Fold - jQuery JavaScript Library") - Folds the element like a piece of paper.
* [Puff](http://docs.jquery.com/UI/Effects/Puff "UI/Effects/Puff - jQuery JavaScript Library") - Scale and fade out animations create the puff effect.
* [Slide](http://docs.jquery.com/UI/Effects/Slide "UI/Effects/Slide - jQuery JavaScript Library") - Slides the element out of the viewport.
* [Scale](http://docs.jquery.com/UI/Effects/Scale "UI/Effects/Scale - jQuery JavaScript Library") - Shrink or grow an element by a percentage factor. 
* [Bounce](http://docs.jquery.com/UI/Effects/Bounce "UI/Effects/Bounce - jQuery JavaScript Library") - Bounces the element vertically or horizontally n-times.
* [Highlight](http://docs.jquery.com/UI/Effects/Highlight "UI/Effects/Highlight - jQuery JavaScript Library") - Highlights the background with a defined color.
* [Pulsate](http://docs.jquery.com/UI/Effects/Pulsate "UI/Effects/Pulsate - jQuery JavaScript Library") - Pulsates the opacity of the element multiple times.
* [Shake](http://docs.jquery.com/UI/Effects/Shake "UI/Effects/Shake - jQuery JavaScript Library") - Shakes the element vertically or horizontally n-times.
* [Size](http://docs.jquery.com/UI/Effects/Size "UI/Effects/Size - jQuery JavaScript Library") - Resize an element to a specified width and height.
* [Transfer](http://docs.jquery.com/UI/Effects/Transfer "UI/Effects/Transfer - jQuery JavaScript Library") - Transfers the outline of an element to another.

= Requirements =

WordPress jQuery UI Effects requires `PHP5`.

== Installation ==

1. Upload `wp-jquery-ui-effects` folder and all it's contents to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Refer to the [FAQ](http://wordpress.org/extend/plugins/wordpress-jquery-ui-effects/faq/ "WordPress &#8250; WordPress Plugins") for usage and tips.

== Frequently Asked Questions ==

= How do I use this plugin? =

In your theme or plugin, if you want to load the *Bounce* effect, use [wp_enqueue_script](http://codex.wordpress.org/Function_Reference/wp_enqueue_script "Function Reference/wp enqueue script &laquo; WordPress Codex"):

	<?php
	function my_init_method()
	{
		wp_enqueue_script('jquery-ui-effects-bounce');
	}
	
	add_action('init', 'my_init_method');
	?>

WordPress charge jQuery, jQuery UI Effects Core and jQuery UI Effects Bounce.  
Note that all effect scripts are loaded in the footer.

= Load script depends on jQuery UI Effect Highlight =

Add and load a new script that depends on jQuery UI Effect Highlight:

	<?php
	wp_enqueue_script(
	    'my-script',
	    get_bloginfo('template_url', 'raw') . '/js/my-script.js',
	    array('jquery-ui-effects-highlight'),
	    '1.0',
	    true
	);
	?>

= How to find the name of scripts available? =

Prefixing the name of the effect by `jquery-ui-effects-`. Lowercase string.

For example, load the *Shake* effect with:

* `jquery-ui-effects-shake`.
* or by using the static method `WPjQueryUIEffects::getHandle('shake')`.

= jQuery UI is it necessary to run jQuery UI Effects? =

No. Please note that `jquery-ui-core` (`ui.core.js` file) is not a dependency for the effects to work.

= Wich is the version of jQuery UI Effects used by the plugin? =

The **WP jQuery UI Effects plugin** use the version `1.7.2`. Requires jQuery `1.3+`.

= Can I make a suggestion for a new feature or report a bug? =

Sure can! Add your feature request or report your bug report other at the [bug tracker](http://github.com/piouPiouM/wordpress-jquery-ui-effects/issues "Issues - piouPiouM/wordpress-jquery-ui-effects - GitHub").

== Changelog ==

= 1.0.0 =

* Initial release.
