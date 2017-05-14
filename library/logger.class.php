<?php
/**
 * Logs helper
 *
 * Please report bugs on https://github.com/espino316/rsphp/issues
 *
 * @author Luis Espino <luis@espino.info>
 * @copyright Copyright (c) 2016, Luis Espino. All rights reserved.
 * @license MIT License
 */
class Logger {

	static function clearLogs() {
		try {
			$filename = ROOT . DS . 'tmp' . DS . 'logs' . DS . 'debugSql.txt';
			if ( is_writable( $filename ) ) {
				file_put_contents($filename, "");
			} // end if is writable

			$filename = ROOT . DS . 'tmp' . DS . 'logs' . DS . 'debug.txt';
			if ( is_writable( $filename ) ) {
				file_put_contents($filename, "");
			} // end if is writable
		} catch (Exception $ex) {
			error_log($ex->getMessage());
		} // end try catch
	} // end function clearLogs

	static function debugSql($text) {
		try {
			$filename = ROOT . DS . 'tmp' . DS . 'logs' . DS . 'debugSql.txt';
			if ( is_writable( $filename ) ) {
				if ( is_array($text) ) {
					$text = print_r($text, true);
				}
				$text = date('Y-m-d H:i:s') . '		' . $text . '
	';
				file_put_contents($filename, $text, FILE_APPEND | LOCK_EX);
			} else {
				error_log( $text );
			} // end if then else is writable
		} catch (Exception $ex) {
			error_log($text);
			error_log($ex->getMessage());
		} // end try catch
	} // end function debugSql

	static function debug($text) {
		try {
			$filename = ROOT . DS . 'tmp' . DS . 'logs' . DS . 'debug.txt';

			if ( is_writable( $filename ) ) {
				if ( is_array($text) || is_object($text) ) {
					$text = print_r($text, true);
				}
				$text = date('Y-m-d H:i:s') . '		' . $text . '
	';
				file_put_contents($filename, $text, FILE_APPEND | LOCK_EX);
			} else {
				error_log($text);
			} // end if then else is writable
		} catch (Exception $ex) {
			error_log($text);
			error_log($ex->getMessage());
		} // end try catch
	} // end function debug

	static function showDebug() {
		$filename = ROOT . DS . 'tmp' . DS . 'logs' . DS . 'debug.txt';
		echo file_get_contents($filename);
	}

	static function showDebugSql() {
		$filename = ROOT . DS . 'tmp' . DS . 'logs' . DS . 'debugSql.txt';
		echo file_get_contents($filename);
	}
}