<?php
/**
 * WPT1000AdminConfigForm model object.
 * @author Dave Ligthart <info@daveligthart.com>
 * @version 0.1
 * @package wp-top1000authors
 */
include_once('WPT1000BaseForm.php');
class WPT1000AdminImportForm extends WPT1000BaseForm{

	var $wpt1000_total_download_count;
	var $wpt1000_total_author_count;
	var $wpt1000_total_plugin_count;

	var $wpt1000_my_download_count;
	var $wpt1000_my_rank;

	var $wpt1000_author_data_serialized;
	var $author_data = array();

	function WPT1000AdminImportForm(){
		parent::WPT1000BaseForm();

		$this->setExcludeVars(array('author_data'));

		if($this->setFormValues()){

			$this->startImport();

			$this->saveOptions();
		}
		$this->loadOptions();
	}
	/**
	 * Import authors from page.
	 * @param integer $page
	 * @return imported item count
	 * @access private
	 */
	function importAuthorsFromPage($page = 1) {
		$args = array(
			'browse' => 'new',
			'page' => $page,
			'per_page' => 100,
			'fields' => array('downloaded' => true, 'description' => true,
				'sections' => false, 'tested' => true, 'homepage' => true, 'rating' => true,
				'num_ratings' => true, 'requires' => true, 'last_updated' =>true,
			  ),
		 );
		$api = plugins_api('query_plugins', $args);

		$i=0;
		foreach($api->plugins as $p) {

			$profile = trim($p->author_profile);

			$name = trim(strip_tags($p->author));

			if('' != $name) {
				$this->wpt1000_total_download_count += $p->downloaded;

				$this->author_data[$profile][$name]['plugins'][] = array('name'=>$p->name, 'downloads'=>$p->downloaded, 'uri'=>$p->homepage);

				$total_downloads = $this->author_data[$profile][$name]['totals']['downloads'];
				if($total_downloads != null) {
					$total_downloads += $p->downloaded;
				} else {
					$total_downloads = $p->downloaded;
				}

				$this->author_data[$profile][$name]['totals'] = array('downloads'=>$total_downloads);

				$i++;
			}
		}

		$this->wpt1000_total_plugin_count += $i;

		return $i;
	}

	/**
	 * Start import;
	 */
	function startImport() {
		ob_start();
		ini_set("memory_limit","50M");
		for($i=1;$i<200;$i++) {
			$result_count = $this->importAuthorsFromPage($i);
			if($result_count == 0) {
				break;
			}
		}
		$this->wpt1000_total_author_count = count($this->author_data);
		$this->writeToCache($this->author_data);
		ob_end_clean();

		//$this->wpt1000_author_data_serialized = $this->__serialize($this->author_data);
	}

	function writeToCache($author_data) {

		$ranking = array();

		foreach($author_data as $profile_url=>$author_obj) {

			$plugin = '';
			$rank = 0;
			$author_name = '';

			foreach($author_obj as $name=>$data) {
				$ps = $data['plugins'];

				$dl_count = 0;
				foreach($ps as $p){
					$dl_temp = $p['downloads'];
					if($dl_temp > $dl_count) {
						$dl_count = $dl_temp;
						$plugin = '<a href="'.$p['uri'].'" target="_blank" title="visit '.$p['name'].' homepage">'. $p['name'] .'</a>';
					}
				}

				$totals = $data['totals'];
				$rank += $totals['downloads'];
				if($name != '') {
					$author_name = $name;
				}
			}

			$temp = '<td class="wpt1000-author-name">';

			$highlight = false;
			if($profile_url == WPT1000_PROFILE_URL . get_option('wpt1000_profile_name')) {
				$highlight = true;

				$this->wpt1000_my_download_count = $rank;
			}

			$temp .= '<a href="'.$profile_url.'" target="_blank" title="view profile '.$author_name.'">' .
								$author_name . '</a></td>' .
					 '<td class="wpt1000-author-downloads">' . $rank . '</td>' .
					 		'<td class="wpt1000-author-plugin">' . $plugin . '</td>';

			$ranking[$rank] = array('html'=>$temp, 'highlight'=>$highlight);
		}

		// Sort stats.
		ksort($ranking, SORT_NUMERIC);
		$ranking = array_reverse($ranking);

		// Prepare html.
		$html = '<h3 class="wpt1000-title">Top 1000 Wordpress Plugin Authors</h3>';
		$html .= '<p class="wpt1000-modified-date">' . date('l jS F Y h:i:s A') . '</p>';
		$html .= '<table class="tablesorter" id="wpt1000-table">' .
				'<thead><tr>' .
				'<th>RANK</th>' .
				'<th>AUTHOR</th>' .
				'<th>DOWNLOADS</th>' .
				'<th>PLUGIN</th>' .
				'</tr></thead>' .
				'<tbody>';
		$i = 1;
		foreach($ranking as $item) {
			$li_html = $item['html'];
			$highlight = $item['highlight'];
			if(!$highlight){
				$html .= '<tr class="wpt1000-item">';
			} else {
				$html .= '<tr class="wpt1000-item-highlight">';
				$this->wpt1000_my_rank = $i;
			}
			$html .= '<td class="wpt1000-author-rank">' . $i . '</td>';
			$html .= $li_html;
			$html .= '</tr>';
			if($i == 1000) {
				break;
			}
			$i++;
		}

		$html .= '</tbody></table><!-- end wp-top1000authors - by daveligthart.com -->';

		// Write cache.
		ob_start();
		set_time_limit(180);
		wpt1000_write_cache($html);
		ob_end_clean();
	}

	/**
	 * Get authors.
	 * @return author data array
	 * @access public
	 */
	function getAuthors() {
		$data = $this->__unserialize($this->wpt1000_author_data_serialized);
		$this->writeToCache($data);
	}

	/**
	 * Get total download count.
	 * @return download count
	 * @access public
	 */
	function getTotalDownloadCount() {
		return $this->wpt1000_total_download_count;
	}

	/**
	 * Get total author count.
	 * @return author count
	 * @access public
	 */
	function getTotalAuthorCount() {
		return $this->wpt1000_total_author_count;
	}

	/**
	 * Get total plugin count.
	 * @return count
	 * @access public
	 */
	function getTotalPluginCount() {
		return $this->wpt1000_total_plugin_count;
	}

	/**
	 * Get download count for author.
	 * @return count
	 * @access public
	 */
	function getMyDownloadCount() {
		return $this->wpt1000_my_download_count;
	}

	/**
	 * Get my rank.
	 * @return rank
	 */
	function getMyRank() {
		return $this->wpt1000_my_rank;
	}

	/**
	 * Serialize.
	 */
	function __serialize($object) {
		$json = new Services_JSON();
		$result = $json->encode($object);
		return $result;
	}

	/**
	 * Unserialize.
	 */
	function __unserialize($serialized) {
		ob_start();
		set_time_limit(300);
    	$json = new Services_JSON();
		$result = $json->decode($serialized);
		ob_end_clean();
		return $result;
	}
}
?>