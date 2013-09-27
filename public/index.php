<?php
/*
  +------------------------------------------------------------------------+
  | PhalconEye CMS                                                         |
  +------------------------------------------------------------------------+
  | Copyright (c) 2013 PhalconEye Team (http://phalconeye.com/)            |
  +------------------------------------------------------------------------+
  | This source file is subject to the New BSD License that is bundled     |
  | with this package in the file LICENSE.txt.                             |
  |                                                                        |
  | If you did not receive a copy of the license and are unable to         |
  | obtain it through the world-wide-web, please send an email             |
  | to license@phalconeye.com so we can send you a copy immediately.       |
  +------------------------------------------------------------------------+
*/
error_reporting(E_ALL);
define('PE_VERSION', '0.4.0');
define('PHALCON_VERSION_REQUIRED', '1.2.0');
define('PHP_VERSION_REQUIRED', '5.4.0');
define('DS', DIRECTORY_SEPARATOR);
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(dirname(__FILE__)));
}
if (!defined('PUBLIC_PATH')) {
    define('PUBLIC_PATH', dirname(__FILE__));
}

require_once ROOT_PATH . "/app/engine/Error.php";

try {
    if (php_sapi_name() !== 'cli') {
        require_once ROOT_PATH . "/app/engine/Application.php";
        $application = new Engine\Application();
    } else {
        require_once ROOT_PATH . "/app/engine/Cli.php";
        $application = new Engine\Cli();
    }

    $application->run();
    echo $application->getOutput();
} catch (Exception $e) {
    \Engine\Error::exception($e);
    throw $e;
}

