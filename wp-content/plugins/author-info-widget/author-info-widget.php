<?php Echo $this->get_option('widget_title') ?>

<?php ForEach ($this->get_option('author') AS $author) : ?>

  <?php If ($this->get_option('show_name') && $author->display_name) : ?>
  <div class="author-name"><?php Echo $author->display_name ?></div>
  <?php EndIf ?>
  
  <?php If ($this->get_option('show_gravatar') && $author->user_email) : ?>
  <div class="author-avatar"><?php Echo get_avatar($author->user_email, $this->get_option('gravatar_size')) ?></div>
  <?php EndIf ?>
    
  <?php If ($author->description) : ?>
  <p class="author-description"><?php Echo nl2br($author->description)?></p>
  <?php EndIf ?>
  
  <div class="clear"></div>
  
  <ul class="author-contact">
  
    <?php If ($this->get_option('show_website') && $author->user_url) : ?>
    <li class="author-website"><a href="<?php Echo $author->user_url?>" title="<?php Echo $this->get_option('caption_website') ?>"><?php Echo $this->get_option('caption_website') ?></a></li>
    <?php EndIf ?>
  
    <?php If ($this->get_option('show_email') && $author->user_email) : ?>
    <li class="author-email"><a href="mailto:<?php Echo $author->user_email?>" title="<?php Echo $this->get_option('caption_email') ?>"><?php Echo $this->get_option('caption_email') ?></a></li>
    <?php EndIf ?>
    
    <?php If ($this->get_option('show_jabber') && $author->jabber) : ?>
    <li class="author-jabber"><a href="xmpp:<?php Echo $author->jabber ?>" title="<?php Echo $this->get_option('caption_jabber') ?>"><?php Echo $this->get_option('caption_jabber') ?></a></li>
    <?php EndIf ?>
  
    <?php If ($this->get_option('show_aim') && $author->aim) : ?>
    <li class="author-aim"><a href="aim:AddBuddy?ScreenName=<?php Echo UrlEncode($author->aim) ?>" title="<?php Echo $this->get_option('caption_aim') ?>"><?php Echo $this->get_option('caption_aim') ?></a></li>
    <?php EndIf ?>
    
    <?php If ($this->get_option('show_yim') && $author->yim) : ?>
    <li class="author-yim"><a href="http://profiles.yahoo.com/<?php Echo $author->yim ?>" title="<?php Echo $this->get_option('caption_yim') ?>"><?php Echo $this->get_option('caption_yim') ?></a></li>
    <?php EndIf ?>
  
    <?php If ($this->get_option('show_posts_link')) : ?>
    <li class="author-posts-link"><a href="<?php Echo get_author_posts_url($author->ID)?>" title="<?php Echo $this->posts_link_caption($author->display_name) ?>"><?php Echo $this->posts_link_caption($author->display_name) ?></a></li>      
    <?php EndIf ?>
  
  </ul>
  
  <div class="clear"></div>

<?php EndForEach;
/* End of File */