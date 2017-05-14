<?php
/**
 * Accesses session variables
 *
 * Please report bugs on https://github.com/espino316/rsphp/issues
 *
 * @author Luis Espino <luis@espino.info>
 * @copyright Copyright (c) 2016, Luis Espino. All rights reserved.
 * @license MIT License
 */
class Session {

	/**
	 *
	 * @var array
	 */
	private static $data;

	public static $cookieName = "sesdat";

	/**
	 * Load the cookie into $data
	 */
	private static function load(){

		if (session_status() == PHP_SESSION_NONE) {
		    session_start();
		}

		//	If no session
		if ( !isset($_SESSION[self::$cookieName])) {

			//	but there is cookie
			if ( isset($_COOKIE[self::$cookieName]) ) {

				//	Decrypt the cookie
				//	and set the array
				$crypt = new CryptHelper();
				self::$data =
					json_decode(
						$crypt->tripleDesDecrypt(
							$_COOKIE[self::$cookieName]
						),
						true
					);
				//	Pass the cookie value to the session
				$_SESSION[self::$cookieName] = $_COOKIE[self::$cookieName];
			} else {
				//	No session, no cookie, start session
				self::$data = array();
			} // end else
		} else {
			//	There is session, then decrypt session and pass to array
			$crypt = new CryptHelper();
			self::$data =
				json_decode(
					$crypt->tripleDesDecrypt(
						$_SESSION[self::$cookieName]
					),
					true
				);
			//	Set cookie
			setcookie(
					self::$cookieName,
					$_SESSION[self::$cookieName],
					time() + ( 3600 * 24 )
			);
		} // end else

	} // end load

	/**
	 * Write a key value pair to $data and set the cookie
	 * @param string $itemKey
	 * @param object $itemValue
	 */
	static function set($itemKey, $itemValue) {

		//	Load the array
		self::load();
		//	Set the item
		self::$data[$itemKey] = $itemValue;
		self::$data['clear'] = false;
		//	Crypt
		$crypt = new CryptHelper();
		$str = json_encode(self::$data);
		$val = $crypt->tripleDesEncrypt(
				json_encode(self::$data)
			);
		//	Set session
		$_SESSION[self::$cookieName] = $val;
		//	Set cookie
		setcookie(
			self::$cookieName,
			$_SESSION[self::$cookieName],
			time() + 3600
		);

	}

	/**
	 * Retrives a value from $data
	 * @param string $itemKey
	 */
	static function get($itemKey = null) {

		//	Load the array
		self::load();
		//	If no request specific key
		if ( $itemKey == null ) {
			//	Return the whole array
			return self::$data;
		} else {
			//	Else, return value, if exists
			if ( empty( self::$data) ) {
				return null;
			} else {
				if ( array_key_exists($itemKey, self::$data))
					return self::$data[$itemKey];
				else
					return null;
			} // end if then else empty data
		} // end if then else itemkey null
	} // end function

	/**
	 * Remove the cookie data, destroy the session
	 */
	static function clear() {

		//	Load the array
		self::load();
		self::$data = null;
		//	Set the item
		self::$data['clear'] = true;
		//	Crypt
		$crypt = new CryptHelper();
		$str = json_encode(self::$data);
		$val = $crypt->tripleDesEncrypt(
				json_encode(self::$data)
		);
		//	Set session
		$_SESSION[self::$cookieName] = $val;
		//	Set cookie
		setcookie(
				self::$cookieName,
				$_SESSION[self::$cookieName],
				time() + 3600
		);
	} // end clear

} // end class