<?php
/*
  +------------------------------------------------------------------------+
  | PhalconEye CMS                                                         |
  +------------------------------------------------------------------------+
  | Copyright (c) 2013-2014 PhalconEye Team (http://phalconeye.com/)       |
  +------------------------------------------------------------------------+
  | This source file is subject to the New BSD License that is bundled     |
  | with this package in the file LICENSE.txt.                             |
  |                                                                        |
  | If you did not receive a copy of the license and are unable to         |
  | obtain it through the world-wide-web, please send an email             |
  | to license@phalconeye.com so we can send you a copy immediately.       |
  +------------------------------------------------------------------------+
  | Author: Ivan Vorontsov <ivan.vorontsov@phalconeye.com>                 |
  | Author: Piotr Gasiorowski <piotr.gasiorowski@vipserv.org>              |
  +------------------------------------------------------------------------+
*/

namespace Engine;

use Phalcon\Config as PhalconConfig;

/**
 * Application config.
 *
 * @category  PhalconEye
 * @package   Engine
 * @author    Ivan Vorontsov <ivan.vorontsov@phalconeye.com>
 * @copyright 2013-2014 PhalconEye Team
 * @license   New BSD License
 * @link      http://phalconeye.com/
 */
class Config
{
    const
        /**
         * System config location.
         */
        CONFIG_PATH = '/app/config/',

        /**
         * System config location.
         */
        CONFIG_CACHE_PATH = '/app/var/cache/data/config.php',

        /**
         * Default language if there is no default selected.
         */
        CONFIG_DEFAULT_LANGUAGE = 'en',

        /**
         * Default locale if there no default language selected.
         */
        CONFIG_DEFAULT_LOCALE = 'en_US',

        /**
         * Application metadata.
         */
        CONFIG_METADATA_APP = '/app/var/data/app.php',

        /**
         * Packages metadata location.
         */
        CONFIG_METADATA_PACKAGES = '/app/var/data/packages',

        /**
         * Default configuration section.
         */
        CONFIG_DEFAULT_SECTION = 'application';

    /**
     * Load configuration according to selected stage.
     *
     * @param string $stage Configuration stage.
     *
     * @return PhalconConfig
     */
    public static function factory($stage = null)
    {
        if (!$stage) {
            $stage = APPLICATION_STAGE;
        }

        if ($stage == APPLICATION_STAGE_DEVELOPMENT) {
            $config = self::_getConfiguration($stage);
        } else {
            if (file_exists(self::CONFIG_CACHE_PATH)) {
                $config = new PhalconConfig(include_once(self::CONFIG_CACHE_PATH));
                $config->stage = $stage;
            } else {
                $config = self::_getConfiguration($stage);
                self::refreshCache($config);
            }
        }

        return $config;
    }

    /**
     * Load configuration from all files.
     *
     * @param string $stage Configuration stage.
     *
     * @throws Exception
     * @return PhalconConfig
     */
    protected static function _getConfiguration($stage)
    {
        $config = new PhalconConfig;
        $config->stage = $stage;
        $configDirectory = ROOT_PATH . self::CONFIG_PATH . $stage;
        $configFiles = glob($configDirectory .'/*.php');

        // create config files from .dist
        if (!$configFiles) {
            foreach (glob($configDirectory .'/*.dist') as $file) {
                $configFile = substr($file, 0, -5);
                copy($file, $configFile);
                $configFiles[] = $configFile;
            }
        }

        foreach ($configFiles as $file) {
            $data = include_once($file);
            $config->offsetSet(basename($file, ".php"), new PhalconConfig($data));
        }

        $appPath = ROOT_PATH . self::CONFIG_METADATA_APP;

        if (!file_exists($appPath)) {
            $emptyConfig = new PhalconConfig;
            $config->offsetSet('installed', false);
            $config->offsetSet('events', clone $emptyConfig);
            $config->offsetSet('modules', clone $emptyConfig);
            $config->offsetSet('widgets', clone $emptyConfig);
            return $config;
        }

        $data = include_once($appPath);
        $config->merge(new PhalconConfig($data));

        return $config;
    }

    /**
     * Save config.
     *
     * @param PhalconConfig $config   Config instance
     * @param string|array  $sections Config section name to save. By default: Config::CONFIG_DEFAULT_SECTION.
     *
     * @return void
     * @throws Exception if invalid configuration is used
     */
    public static function save(PhalconConfig $config, $sections = self::CONFIG_DEFAULT_SECTION)
    {
        // Added here to protect the config file from overriding by custom instance
        if (!$config->stage) {
            throw new Exception('Using invalid configuration');
        }

        $configDirectory = ROOT_PATH . self::CONFIG_PATH . $config->stage;
        if (!is_array($sections)) {
            $sections = array($sections);
        }

        foreach ($sections as $section) {
            file_put_contents(
                $configDirectory . '/' . $section . '.php',
                self::_toConfigurationString($config->get($section)->toArray())
            );
        }

        self::refreshCache($config);
    }

    /**
     * Save config file into cached config file.
     *
     * @param PhalconConfig $config Config instance
     *
     * @return void
     * @throws Exception if invalid configuration is used
     */
    public static function refreshCache(PhalconConfig $config)
    {
        // Added here to protect the config file from overriding by custom instance
        if (!$config->stage) {
            throw new Exception('Using invalid configuration');
        }

        $data = $config->toArray();

        file_put_contents(ROOT_PATH . self::CONFIG_CACHE_PATH, self::_toConfigurationString($data));
    }

    /**
     * Save application config to file.
     *
     * @param array $data Configuration data.
     *
     * @return string
     */
    protected static function _toConfigurationString(array $data)
    {
        $configText = var_export($data, true);

        // Fix paths. This related to windows directory separator.
        $configText = str_replace('\\\\', DS, $configText);

        $configText = str_replace("'" . PUBLIC_PATH, "PUBLIC_PATH . '", $configText);
        $configText = str_replace("'" . ROOT_PATH, "ROOT_PATH . '", $configText);
        $headerText = '<?php
/*
  +------------------------------------------------------------------------+
  | PhalconEye CMS                                                         |
  +------------------------------------------------------------------------+
  | Copyright (c) 2013-2014 PhalconEye Team (http://phalconeye.com/)       |
  +------------------------------------------------------------------------+
  | This source file is subject to the New BSD License that is bundled     |
  | with this package in the file LICENSE.txt.                             |
  |                                                                        |
  | If you did not receive a copy of the license and are unable to         |
  | obtain it through the world-wide-web, please send an email             |
  | to license@phalconeye.com so we can send you a copy immediately.       |
  +------------------------------------------------------------------------+
*/

/**
* WARNING
*
* Manual changes to this file may cause a malfunction of the system.
* Be careful when changing settings!
*
*/

return ';

        return $headerText . $configText . ';';
    }
}
