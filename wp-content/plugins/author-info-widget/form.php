<p>
<?php Echo $this->t('Title') ?>:
<input type="text" name="<?php Echo $this->get_field_name('title')?>" value="<?php Echo $this->get_option('title') ?>" /><br />
<small><?php Echo $this->t('Leave blank to use the widget default title.') ?></small>
</p>


<h3><?php Echo $this->t('Author(s)') ?></h3>
<p>
<input type="radio" name="<?php Echo $this->get_field_name('show_author') ?>" value="current" <?php Checked($this->get_option('show_author'), 'current') ?>> <?php Echo $this->t('Show the author(s) of the post/page') ?>
</p>
<?php If ($this->get_authors() ) : ?>
<p>
<input type="radio" name="<?php Echo $this->get_field_name('show_author') ?>" value="selected" <?php Checked($this->get_option('show_author'), 'selected') ?>> <?php Echo $this->t('Show the following author(s)') ?>
</p>
<p><small><?php Echo $this->t('Please select the authors which should be shown by the widget.') ?></small></p>
<?php ForEach( (ARRAY) $this->get_authors() AS $author ) : ?>
  <input type="checkbox" name="<?php Echo $this->get_field_name('selected_authors') ?>[]" value="<?php Echo $author->ID ?>" <?php Checked(Array_Search($author->ID, (Array) $this->get_option('selected_authors')) !== False) ?> >
  <?php Echo HTMLSpecialChars($author->display_name) ?>
  <br />
<?php EndForEach ?>
</p>
<?php EndIf; ?>


<h3><?php Echo $this->t('Gravatar') ?></h3>
<p>
<input type="checkbox" name="<?php Echo $this->get_field_name('show_gravatar')?>" value="yes" <?php Checked($this->get_option('show_gravatar'), 'yes')?> />
<?php Echo $this->t('Show the authors avatar') ?>
</p>
<p><small><?php Echo $this->t('You can setup your gravatar on <a href="http://gravatar.com/" target="_blank">the gravatar website</a>.') ?></small></p>
<p>
<?php Echo $this->t('Size') ?>:
<input type="text" name="<?php Echo $this->get_field_name('gravatar_size')?>" value="<?php Echo $this->get_option('gravatar_size')?>" size="3" />px
</p>


<h3><?php Echo $this->t('Display') ?></h3>
<p>
<input type="checkbox" name="<?php Echo $this->get_field_name('show_name')?>" value="yes" <?php Checked($this->get_option('show_name'), 'yes')?> />
<?php Echo $this->t('Show the name of the author.')?>
</p>
<p>
<input type="checkbox" name="<?php Echo $this->get_field_name('show_website')?>" value="yes" <?php Checked($this->get_option('show_website'), 'yes')?> />
<?php Echo $this->t('Show the homepage link.')?><br />
<?php Echo $this->t('Caption') ?>: <input type="text"  name="<?php Echo $this->get_field_name('caption_website')?>" value="<?php Echo HTMLSpecialChars($this->get_option('caption_website')) ?>" size="15" />
</p>
<p>
<input type="checkbox" name="<?php Echo $this->get_field_name('show_email')?>" value="yes" <?php Checked($this->get_option('show_email'), 'yes')?> />
<?php Echo $this->t('Show the E-Mail address.')?><br />
<?php Echo $this->t('Caption') ?>: <input type="text"  name="<?php Echo $this->get_field_name('caption_email')?>" value="<?php Echo HTMLSpecialChars($this->get_option('caption_email')) ?>" size="15" />
</p>
<p>
<input type="checkbox" name="<?php Echo $this->get_field_name('show_jabber')?>" value="yes" <?php Checked($this->get_option('show_jabber'), 'yes')?> />
<?php Echo $this->t('Show the jabber name.')?><br />
<?php Echo $this->t('Caption') ?>: <input type="text"  name="<?php Echo $this->get_field_name('caption_jabber')?>" value="<?php Echo HTMLSpecialChars($this->get_option('caption_jabber')) ?>" size="15" />
</p>
<p>
<input type="checkbox" name="<?php Echo $this->get_field_name('show_aim')?>" value="yes" <?php Checked($this->get_option('show_aim'), 'yes')?> />
<?php Echo $this->t('Show the AIM name.')?><br />
<?php Echo $this->t('Caption') ?>: <input type="text"  name="<?php Echo $this->get_field_name('caption_aim')?>" value="<?php Echo HTMLSpecialChars($this->get_option('caption_aim')) ?>" size="15" />
</p>
<p>
<input type="checkbox" name="<?php Echo $this->get_field_name('show_yim')?>" value="yes" <?php Checked($this->get_option('show_yim'), 'yes')?> />
<?php Echo $this->t('Show the YIM name.')?><br />
<?php Echo $this->t('Caption') ?>: <input type="text"  name="<?php Echo $this->get_field_name('caption_yim')?>" value="<?php Echo HTMLSpecialChars($this->get_option('caption_yim')) ?>" size="15" />
</p>
<p>
<input type="checkbox" name="<?php Echo $this->get_field_name('show_posts_link')?>" value="yes" <?php Checked($this->get_option('show_posts_link'), 'yes')?> />
<?php Echo $this->t('Show a link to the authors posts on this blog.')?>
</p>


<h3><?php Echo $this->t('Miscellaneous') ?></h3>
<p>
<input type="checkbox" name="<?php Echo $this->get_field_name('only_logged_in')?>" value="yes" <?php Checked($this->get_option('only_logged_in'), 'yes')?> />
<?php Echo $this->t('Hide this widget from users which are not logged in.')?>
</p>

<p>
<input type="checkbox" name="<?php Echo $this->get_field_name('hide_on_pages')?>" value="yes" <?php Checked($this->get_option('hide_on_pages'), 'yes')?> />
<?php Echo $this->t('Hide this Widget on pages.')?>
</p>
