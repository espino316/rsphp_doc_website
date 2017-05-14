<?php
/**
 * Autoload any classes that are required
 *
 * Please report bugs on https://github.com/espino316/rsphp/issues
 *
 * @author Luis Espino <luis@espino.info>
 * @copyright Copyright (c) 2016, Luis Espino. All rights reserved.
 * @license MIT License
 */
function __autoload($className) {

	if (file_exists(ROOT . DS . 'library' . DS . strtolower($className) . '.class.php')) {
		require_once(ROOT . DS . 'library' . DS . strtolower($className) . '.class.php');
	} else if (file_exists(ROOT . DS . 'application' . DS . 'controllers' . DS . strtolower($className) . '.php')) {
			require_once(ROOT . DS . 'application' . DS . 'controllers' . DS . strtolower($className) . '.php');
	} else if (file_exists(ROOT . DS . 'application' . DS . 'models' . DS . strtolower($className) . '.php')) {
			require_once(ROOT . DS . 'application' . DS . 'models' . DS . strtolower($className) . '.php');
	} else if (file_exists(ROOT . DS . 'application' . DS . 'libraries' . DS . strtolower($className) . '.php')) {
			require_once(ROOT . DS . 'application' . DS . 'libraries' . DS . strtolower($className) . '.php');
	} else {
			/* Error Generation Code Here */
		throw new Exception("$className not found anywhere!");
	} // end if then else

} // end function __autoload