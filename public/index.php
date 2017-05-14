<?php
/**
 * Start point for the application
 *
 * Please report bugs on https://github.com/espino316/rsphp/issues
 *
 * @author Luis Espino <luis@espino.info>
 * @copyright Copyright (c) 2016, Luis Espino. All rights reserved.
 * @license MIT License
 */
date_default_timezone_set('America/Mexico_City');
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)));
define('TAB', "\t");
define('CRLF', "\r\n");
define('NEW_LINE',"\r\n");
define( 'IS_CLI', (php_sapi_name() === 'cli') );

require_once (ROOT . DS . 'config' . DS . 'tdeskeys.php');
require_once (ROOT . DS . 'library' . DS . 'autoload.php');

set_exception_handler( array('RS', 'handleExeption') );
RS::startUp();
//set_error_handler("error_handler");
//register_shutdown_function("error_handler");