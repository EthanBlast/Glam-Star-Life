<?php header("Content-Type: text/css"); ?>



<?php $styles = array(); ?>

<?php foreach ($_GET as $skey => $sval) : ?>

	<?php $styles[$skey] = urldecode($sval); ?>

<?php endforeach; ?>



#slideshow { list-style:float:left; color:#fff;  position:relative;
 top:20px;
 left:-40px;}

#slideshow span {float: left; }

#slideshow-wrapper { width:<?php echo ((int) $styles['width'] - 6); ?>px; background:<?php echo $styles['background']; ?>; padding:2px; border:<?php echo $styles['border']; ?>; margin:25px auto; display:none;  position: relative; position:relative;
 top:20px;
 left:-40px;}

#slideshow-wrapper * { margin:0; padding:0; }

#fullsize { position:relative; z-index:1; overflow:hidden; width:<?php echo ((int) $styles['width'] - 6); ?>px; height:<?php echo $styles['height']; ?>px; }

#information { position:absolute; bottom:0; width:<?php echo ((int) $styles['width'] - 6); ?>px; height:0; background:<?php echo $styles['infobackground']; ?>; color:<?php echo $styles['infocolor']; ?>; overflow:hidden; z-index:200; opacity:.7; filter:alpha(opacity=70); }

#information h3 { color:<?php echo $styles['infocolor']; ?>; padding:4px 8px 3px; font-size:14px; }

#information p { color:<?php echo $styles['infocolor']; ?>; padding:0 8px 8px; }

#image { width:<?php echo ((int) $styles['width'] - 6); ?>px; }

<?php if (empty($styles['resizeimages']) || $styles['resizeimages'] == "Y") : ?>#image img { position:absolute; border:none; width:<?php echo ((int) $styles['width'] - 6); ?>px; height:auto; }<?php else : ?>#image img { position:absolute; border:none; width:auto; }<?php endif; ?> 

.imgnav { position:absolute; width:25%; height:<?php echo ((int) $styles['height'] + 6); ?>px; cursor:pointer; z-index:150; }

#imgprev { left:0; background:url('../images/left.gif') left center no-repeat; }

#imgnext { right:0; background:url('../images/right.gif') right center no-repeat; }

#imglink { position:absolute; height:<?php echo ((int) $styles['height'] + 6); ?>px; width:100%; z-index:100; opacity:.4; filter:alpha(opacity=40); }

.linkhover { background:url('../images/link.gif') center center no-repeat; }

#thumbnails {  }

.thumbstop { margin-bottom:15px !important; }

.thumbsbot { margin-top:15px !important; }

#slideleft { float:left; width:20px; height:81px; background:url('../images/scroll-left.gif') center center no-repeat; background-color:#222; }

#slideleft:hover { background-color:#333; }

#slideright { float:right; width:20px; height:81px; background:#222 url('../images/scroll-right.gif') center center no-repeat; }

#slideright:hover { background-color:#333; }

#slidearea { float:left; background:<?php echo $styles['background']; ?>; position:relative; width:<?php echo ((int) $styles['width'] - 55); ?>px; margin-left:5px; height:81px; overflow:hidden; }

#slider { position:absolute; left:0; height:81px; }

#slider img { cursor:pointer; border:1px solid #666; padding:2px; -moz-border-radius:4px; -webkit-border-radius:4px; float:left !important; }