<?php
/*
  +------------------------------------------------------------------------+
  | PhalconEye CMS                                                         |
  +------------------------------------------------------------------------+
  | Copyright (c) 2013-2016 PhalconEye Team (http://phalconeye.com/)       |
  +------------------------------------------------------------------------+
  | This source file is subject to the New BSD License that is bundled     |
  | with this package in the file LICENSE.txt.                             |
  |                                                                        |
  | If you did not receive a copy of the license and are unable to         |
  | obtain it through the world-wide-web, please send an email             |
  | to license@phalconeye.com so we can send you a copy immediately.       |
  +------------------------------------------------------------------------+
  | Author: Ivan Vorontsov <lantian.ivan@gmail.com>                 |
  +------------------------------------------------------------------------+
*/

namespace Engine;

use Engine\Behavior\DIBehavior;
use Engine\Package\PackageData;
use Engine\Plugin\CacheAnnotation;
use Engine\Plugin\DispatchErrorHandler;
use Phalcon\Config as PhalconConfig;
use Phalcon\DiInterface;
use Phalcon\Events\Manager;

/**
 * Bootstrap class.
 *
 * @category  PhalconEye
 * @package   Engine
 * @author    Ivan Vorontsov <lantian.ivan@gmail.com>
 * @copyright 2013-2016 PhalconEye Team
 * @license   New BSD License
 * @link      http://phalconeye.com/
 */
abstract class AbstractBootstrap implements BootstrapInterface
{
    use DIBehavior {
        DIBehavior::__construct as protected __DIConstruct;
    }

    /**
     * Module name.
     *
     * @var string
     */
    protected $_moduleName = "";

    /**
     * Configuration.
     *
     * @var PhalconConfig
     */
    private $_config;

    /**
     * Events manager.
     *
     * @var Manager
     */
    private $_em;

    /**
     * Create Bootstrap.
     *
     * @param DiInterface $di Dependency injection.
     * @param Manager     $em Events manager.
     */
    public function __construct($di, $em)
    {
        $this->__DIConstruct($di);
        $this->_em = $em;
        $this->_config = $this->getDI()->get('config');
    }

    /**
     * Register the services.
     *
     * @throws Exception
     * @return void
     */
    public function initialize()
    {
        if (empty($this->_moduleName)) {
            $class = new \ReflectionClass($this);
            throw new Exception('Bootstrap has no module name: ' . $class->getFileName());
        }

        $di = $this->getDI();
        $config = $this->getConfig();
        $eventsManager = $this->getEventsManager();

        /*************************************************/
        //  Initialize dispatcher.
        /*************************************************/
        $eventsManager->attach("dispatch:beforeException", new DispatchErrorHandler());
        if (!$config->application->debug) {
            $eventsManager->attach('dispatch:beforeExecuteRoute', new CacheAnnotation());
        }

        // Create dispatcher.
        $dispatcher = new Dispatcher();
        $dispatcher->setEventsManager($eventsManager);
        $di->set('dispatcher', $dispatcher);
    }

    /**
     * Get current module data.
     *
     * @return PackageData package data.
     */
    public function getModuleData()
    {
        return $this->getDI()->getModules()->get(strtolower($this->getModuleName()));
    }

    /**
     * Get config object.
     *
     * @return mixed|PhalconConfig
     */
    public function getConfig()
    {
        return $this->_config;
    }

    /**
     * Get events manager.
     *
     * @return Manager
     */
    public function getEventsManager()
    {
        return $this->_em;
    }

    /**
     * Get current module name.
     *
     * @return string
     */
    public function getModuleName()
    {
        return $this->_moduleName;
    }
}