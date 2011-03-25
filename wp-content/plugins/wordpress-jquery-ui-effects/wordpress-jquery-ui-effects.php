<?php
/*
Plugin Name: WordPress jQuery UI Effects
Plugin URI: http://pioupioum.fr/wordpress/plugins/wordpress-jquery-ui-effects.html
Version: 1.0.0
Description: Use the jQuery UI Effects library in your themes and plugins.
Author: Mehdi Kabab
Author URI: http://pioupioum.fr/
*/
/*
# ***** BEGIN LICENSE BLOCK *****
# Copyright (C) 2010 Mehdi Kabab <http://pioupioum.fr/>
#
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>.
#
# ***** END LICENSE BLOCK ***** */
/**
 * WP jQuery UI Effects class.
 *
 * @package   WPjQueryUIEffects
 * @author    Mehdi Kabab <http://pioupioum.fr/>
 * @copyright Copyright (C) 2010 Mehdi Kabab <http://pioupioum.fr/>
 * @license   http://www.gnu.org/licenses/gpl.html  GNU GPL version 3 or later
 **/
class WPjQueryUIEffects
{
	/**
	 * Last jQuery UI Effects version.
	 */
	const JQUERY_EFFECTS_VERSION = '1.7.2';

	/**
	 * Handle prefix name.
	 */
	const JQUERY_EFFECTS_PREFIX = 'jquery-ui-effects-';

	/**
	 * Plugin home path (symlink corrected).
	 *
	 * @var string
	 */
	protected $_dirname;

	/**
	 * Plugin file path (symlink corrected).
	 *
	 * @var string
	 */
	protected $_filename;

	/**
	 * Absolute URL of plugin folder.
	 *
	 * @var string
	 */
	protected $_absolute_url;

	/**
	 * List effects of jQuery UI Effects.
	 *
	 * @var array
	 */
	protected $_effects = array(
		'blind', 'bounce', 'clip', 'drop',
		'explode', 'fold', 'highlight', 'pulsate',
		'scale', 'shake', 'slide', 'transfert'
	);

	/**
	 * jQuery UI Effects version.
	 *
	 * @var string
	 */
	protected $_version_product = null;

	/**
	 * A possible error.
	 *
	 * @var Exception
	 */
	protected $_error = null;

	/**
	 * The current instance of the plugin.
	 *
	 * @var WPjQueryUIEffects
	 */
	protected static $_instance = null;

	/**
	 * Register main functions.
	 *
	 * @param string $home_path The filename of the plugin (generally given as __FILE__).
	 * @return WPjQueryUIEffects
	 */
	public static function createInstance($home_path)
	{
		self::$_instance = new self($home_path);

		return self::$_instance;
	}

	/**
	 * Returns the current instance of the plugin.
	 *
	 * @return WPjQueryUIEffects
	 */
	public static function getInstance()
	{
		return self::$_instance;
	}

	/**
	 * Retrieve the registered effect name.
	 *
	 * @param string Effect name.
	 * @return string
	 */
	public static function getHandle($name)
	{
		return self::JQUERY_EFFECTS_PREFIX . trim($name);
	}

	/**
	 * Test if an error was occured.
	 *
	 * @return boolean
	 */
	public function hasError()
	{
		return null !== $this->_error;
	}

	/**
	 * Remove a registered jQuery UI Effects script.
	 *
	 * @uses wp_deregister_script()
	 *
	 * @param string $name Script name.
	 * @return WPjQueryUIEffects The current instance.
	 */
	public function deregisterScript($name)
	{
		wp_deregister_script($this->getHandle($name));
	}

	/**
	 * Remove all registered jQuery UI Effects scripts.
	 *
	 * @return WPjQueryUIEffects The current instance.
	 */
	public function deregisterAllScript()
	{
		foreach ($this->_effects as $effect)
		{
			$this->deregisterScript($effect);
		}
		$this->deregisterScript('core');

		return $this;
	}

	/**
	 * Retrieve the version of jQuery UI Effects library used.
	 *
	 * @return string
	 */
	public function getVersionProduct()
	{
		return $this->_version_product;
	}

	/**
	 * Initializes environment and loads the plugin core.
	 *
	 * @uses add_action() Calls 'plugins_loaded' when the initialize is going well
	 *       and 'admin_notices' if an error was encountered.
	 *
	 * @param string $home_path Path to the plugin home (generally given as __FILE__).
	 */
	public static function bootstrap($home_path)
	{
		$instance = self::createInstance($home_path);
		if ($instance->hasError())
		{
			if (is_admin('manage_options'))
			{
				add_action('admin_notices', array($instance, 'actShowError'));
			}
		}
		else
		{
			add_action('plugins_loaded', array($instance, 'actRegisterComponents'));
		}
	}

	/**
	 * Register all scripts effects.
	 */
	public function actRegisterComponents()
	{
		$this->_registerScript('core', 'jquery');
		$core = $this->getHandle('core');
		foreach ($this->_effects as $effect)
		{
			$this->_registerScript($effect, $core);
		}
	}

	/**
	 * Shows error message in backend.
	 */
	public function actShowError()
	{
		printf('<div class="error fade"><p>%s</p></div>', $this->_error->getMessage());
	}

	/**
	 * Singleton => protected constructor.
	 *
	 * @see WPjQueryUIEffects::createInstance() For parameter information.
	 */
	protected function __construct($home_path)
	{
		try
		{
			$this->_version_product = $this->_initVersionProduct();
		}
		catch (Exception $e)
		{
			$this->_error = $e;
		}
		list($this->_dirname, $this->_filename, $this->_absolute_url) = $this->_getPaths($home_path);
	}

	/**
	 * Select the version of jQuery UI Effects library to use.
	 *
	 * @global $wp_version
	 *
	 * @return string The version used.
	 * @throws Exception If the WordPress version is insuffisant.
	 */
	protected function _initVersionProduct()
	{
		if (null !== $this->_version_product)
			return $this->_version_product;

		global $wp_version;
		if (version_compare($wp_version, '2.8', '>='))
		{
			$this->_version_product = self::JQUERY_EFFECTS_VERSION;
		}
		else
		{
			throw new Exception(sprintf(
				'Your WordPress version (%1$s) is insuffisant. WordPress %2$s is required.',
				$wp_version,
				'2.8'
			));
		}

		return $this->_version_product;
	}

	/**
	 * Register new jQuery UI Effects file.
	 *
	 * @uses wp_register_script()
	 *
	 * @param string $name Effect name.
	 * @param array|string $deps Name or an array of script names on which this script depends.
	 */
	protected function _registerScript($name, $deps)
	{
		wp_register_script(
			$this->getHandle($name),
			sprintf('%s/js/%s/effects.%s.js', $this->_absolute_url, $this->_version_product, $name),
			(array) $deps,
			$this->_version_product,
			true
		);
	}

	/**
	 * Retrieve plugin paths.
	 *
	 * @param string $filepath Plugin home path.
	 * @return array
	 */
	private function _getPaths($filepath)
	{
		if (false === strpos($filepath, WP_PLUGIN_DIR))
		{
			$dirname      = WP_PLUGIN_DIR . '/' . basename(dirname($filepath));
			$filename     = $dirname . '/' . basename($filepath);
			$url_absolute = rtrim(str_replace(get_option('siteurl'), '', plugin_dir_url($filename)), '/');
			return array(
				$dirname,
				$filename,
				$url_absolute
			);
		}
		else
		{
			$url_absolute = rtrim(str_replace(get_option('siteurl'), '', plugin_dir_url($filepath)), '/');
			return array(
				dirname($filepath),
				$filepath,
				$url_absolute
			);
		}
	}
}

/**
 * Go!
 */
WPjQueryUIEffects::bootstrap(__FILE__);
