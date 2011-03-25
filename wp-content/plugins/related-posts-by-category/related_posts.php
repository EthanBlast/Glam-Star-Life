<?php
/*
Plugin Name: Related Posts by Category
Text Domain: related_posts
Domain Path: /lang
Description: WordPress plugin for related posts ordered by current category. It's small. It's fast. Really!
Author: Sergej M&uuml;ller
Author URI: http://www.wpSEO.org
Plugin URI: http://playground.ebiene.de/400/related-posts-by-category-the-wordpress-plugin-for-similar-posts/
Version: 0.8
*/


if (!function_exists('is_admin')) {
header('Status: 403 Forbidden');
header('HTTP/1.1 403 Forbidden');
exit();
}
class RPBC {
function RPBC($params, $id = 0) {
if (!is_array($params) || empty($params)) {
return;
}
$entries = array();
$output = '';
$limit = 0;
$type = '';
$order = '';
$orderby = '';
$exclude = '';
if (!intval($id)) {
$id = $GLOBALS['post']->ID;
}
if (isset($params['limit'])) {
$limit = intval($params['limit']);
}
if (!empty($params['type'])) {
$type = ($params['type'] == 'page' ? 'page' : 'post');
}
if (!empty($params['order'])) {
$order = (strtoupper($params['order']) == 'DESC' ? 'DESC' : 'ASC');
}
if (!empty($params['orderby']) && preg_match('/^[a-zA-Z_]+$/', $params['orderby'])) {
$orderby = $params['orderby'];
}
if (!empty($params['exclude'])) {
$exclude = preg_split('/, /', (string)$params['exclude'], -1, PREG_SPLIT_NO_EMPTY);
}
$posts = $GLOBALS['wpdb']->get_results(
sprintf(
"SELECT DISTINCT object_id as ID, post_title FROM {$GLOBALS['wpdb']->term_relationships} r, {$GLOBALS['wpdb']->term_taxonomy} t, {$GLOBALS['wpdb']->posts} p WHERE t.term_id IN (SELECT t.term_id FROM {$GLOBALS['wpdb']->term_relationships} r, {$GLOBALS['wpdb']->term_taxonomy} t WHERE r.term_taxonomy_id = t.term_taxonomy_id AND t.taxonomy = 'category' AND r.object_id = $id %s) AND r.term_taxonomy_id = t.term_taxonomy_id AND p.post_status = 'publish' AND p.ID = r.object_id AND object_id <> $id %s %s %s",
($exclude ? ('AND t.term_taxonomy_id NOT IN(' .implode(',', (array)$exclude). ')') : ''),
($type ? ("AND p.post_type = '" .$type. "'") : ''),
($orderby ? ('ORDER BY ' .(strtoupper($params['orderby']) == 'RAND' ? 'RAND()' : $orderby. ' ' .$order)) : ''),
($limit ? ('LIMIT ' .$limit) : '')
),
OBJECT
);
if ($posts) {
foreach ($posts as $post) {
$title = (function_exists('esc_attr') ? esc_attr($post->post_title) : str_replace('"', '&quot;', $post->post_title));
$rel = (!empty($params['rel']) ? (' rel="' .$params['rel']. '"') : '');
$hidden = (isset($params['hidden']) && $params['hidden'] == 'title' ? '' : $title);
$inside = (isset($params['inside']) ? $params['inside'] : '');
$outside = (isset($params['outside']) ? $params['outside'] : '');
$before = (isset($params['before']) ? $params['before'] : '');
$after = (isset($params['after']) ? $params['after'] : '');
$image = '';
if (!empty($params['image']) && !(isset($params['hidden']) && $params['hidden'] == 'image')) {
$thumb = $this->get_image($post->ID, $params['image']);
if (empty($thumb)) {
if (!empty($params['default'])) {
$image = sprintf(
'<img src="%s" class="rpbc_default" alt="%2$s" title="%2$s" />',
$params['default'],
$title
);
}
} else {
$image = preg_replace(
'#alt=([\'"]).*?([\'"]) title=([\'"]).*?([\'"])#si',
'alt=$1' .$title. '$2 title=$3' .$title. '$4',
$thumb
);
}
}
$output .= sprintf(
'%s<a href="%s" title="%s"%s>%s%s%s%s</a>%s',
$before,
get_permalink($post->ID),
$title,
$rel,
$image,
$inside,
$hidden,
$outside,
$after
);
}
} else {
$output = $params['message'];
}
if (!empty($params['echo'])) {
echo $output;
} else {
$this->output = $output;
}
}
function get_image($id, $type = 'thumbnail') {
if (!intval($id)) {
return;
}
if (function_exists('get_the_post_thumbnail') && $image = get_the_post_thumbnail($id, $type)) {
return $image;
}
if (function_exists('wp_get_attachment_image')) {
$children = get_children(
array(
'post_parent'=> $id,
'post_type'=> 'attachment',
'numberposts'=> 1,
'post_status'=> 'inherit',
'post_mime_type' => 'image',
'order'=> 'ASC',
'orderby'=> 'menu_order ASC'
)
);
if (!empty($children)) {
foreach ($children as $key => $value) {
return wp_get_attachment_image($key, $type);
}
}
}
}
}
if (!function_exists('related_posts_by_category')) {
function related_posts_by_category($params, $id = 0) {
$GLOBALS['RPBC'] = new RPBC($params, $id);
}
}
if (function_exists('current_theme_supports') && !current_theme_supports('post-thumbnails')) {
add_theme_support('post-thumbnails');
}
add_action(
'related_posts_by_category',
'related_posts_by_category',
10,
2
);