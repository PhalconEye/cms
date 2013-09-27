<?php

/**
* PhalconEye
*
* LICENSE
*
* This source file is subject to the new BSD license that is bundled
* with this package in the file LICENSE.txt.
*
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to phalconeye@gmail.com so we can send you a copy immediately.
*
*/

/**
* WARNING
*
* Manual changes to this file may cause a malfunction of the system.
* Be careful when changing settings!
*
*/

return new \Phalcon\Config(array (
  'installed' => false,
  'installedVersion' => '0.4.0',
  'database' => 
  array (
    'adapter' => 'Mysql',
    'host' => 'localhost',
    'username' => 'root',
    'password' => 'miniserver',
    'dbname' => 'phalconeye',
  ),
  'application' => 
  array (
    'debug' => false,
    'profiler' => true,
    'baseUri' => '/',
    'engineDir' => 'D:\\MiniServer\\www\\htdocs\\phalconeye/app/engine/',
    'modulesDir' => 'D:\\MiniServer\\www\\htdocs\\phalconeye/app/modules/',
    'pluginsDir' => 'D:\\MiniServer\\www\\htdocs\\phalconeye/app/plugins/',
    'widgetsDir' => 'D:\\MiniServer\\www\\htdocs\\phalconeye/app/widgets/',
    'librariesDir' => 'D:\\MiniServer\\www\\htdocs\\phalconeye/app/libraries/',
    'cache' => 
    array (
      'lifetime' => '86400',
      'prefix' => 'pe_',
      'adapter' => 'File',
      'cacheDir' => 'D:\\MiniServer\\www\\htdocs\\phalconeye/app/var/cache/data/',
    ),
    'logger' => 
    array (
      'enabled' => true,
      'path' => 'D:\\MiniServer\\www\\htdocs\\phalconeye/app/var/logs/',
      'format' => '[%date%][%type%] %message%',
    ),
    'view' => 
    array (
      'compiledPath' => 'D:\\MiniServer\\www\\htdocs\\phalconeye/app/var/cache/view/',
      'compiledExtension' => '.php',
    ),
    'assets' => 
    array (
      'local' => 'D:\\MiniServer\\www\\htdocs\\phalconeye\\public/assets/',
      'remote' => '/',
    ),
  ),
  'metadata' => 
  array (
    'adapter' => 'Files',
    'metaDataDir' => 'D:\\MiniServer\\www\\htdocs\\phalconeye/app/var/cache/metadata/',
  ),
  'annotations' => 
  array (
    'adapter' => 'Files',
    'annotationsDir' => 'D:\\MiniServer\\www\\htdocs\\phalconeye/app/var/cache/annotations/',
  ),
  'modules' => 
  array (
  ),
  'events' => 
  array (
  ),
  'plugins' => 
  array (
  ),
));