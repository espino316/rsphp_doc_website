<?php
/**
 * Helper for string manipulation
 *
 * Please report bugs on https://github.com/espino316/rsphp/issues
 *
 * @author Luis Espino <luis@espino.info>
 * @copyright Copyright (c) 2016, Luis Espino. All rights reserved.
 * @license MIT License
 */
class StringHelper {

	static function isUppercase( $string ) {
		return ctype_upper( $string );
	} // end function isUppercase
	/**
	 * Returns true if $str contains $val
	 */
	static function contains( $str, $val ) {
		return ( strpos( $str, $val ) !== false );
	} // end function contains

	static function startsWith($haystack, $needle) {
		// search backwards starting from haystack length characters from the end
		return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
	}

	static function endsWith($haystack, $needle) {
		// search forward starting from end minus needle length characters
		return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
	}

	static function left($str, $length) {
		return substr($str, 0, $length);
	}

	static function right($str, $length) {
		return substr($str, -$length);
	}

	static function stripAccents($string){
		$string = str_replace("á", "a", $string);
		$string = str_replace("é", "e", $string);
		$string = str_replace("í", "i", $string);
		$string = str_replace("ó", "o", $string);
		$string = str_replace("ú", "u", $string);
		$string = str_replace("Á", "A", $string);
		$string = str_replace("É", "E", $string);
		$string = str_replace("Í", "I", $string);
		$string = str_replace("Ó", "O", $string);
		$string = str_replace("Ú", "U", $string);
		$string = str_replace("ñ", "n", $string);
		$string = str_replace("Ñ", "N", $string);
		return $string;
	}

  /**
   * replace
   *
   * Replaces the key with the value of $dictionary in $string
   *
	 * @param $dictionary ( Assoc Array ) Dictionary with key and value
   * @param $string ( String ) The string in which the replacements are gonna be made
   * @return ( String )
   */
  /**
   * replace
   *
   * Replaces a text string for another, inside a third string
   *
   * @param $search ( String / Array ) The string or array of strings to look for
   * @param $replace ( String / Array ) The string or array of string to replace with
   * @param $str ( String ) The string in wich to perform the replacements
   * @return ( String )
   */
  static function replace( $param1, $param2, $param3 = null ) {
    if (
          is_array( $param1 ) &&
          !is_array( $param2 ) &&
          $param3 === null
    ) {
      $dictionary = $param1;
      $string = $param2;
      foreach ($dictionary as $key => $value) {
        $string = str_replace($key, $value, $string);
      } // end foreach $dictionary
      return $string;
    } // end if dictionary

    if ( $param1 && $param2 && $param3 ) {
      return str_replace( $param1, $param2, $param3 );
    } // end if all params
	} // end function stringReplace

	/**
	 * Converts special chars to HTML equivalents
	 * @param unknown $string
	 */
	static function specialCharsToHTML( $string ) {
		$search = array(
				'á', 'é', 'í', 'ó', 'ú',
				'Á', 'É', 'Í', 'Ó', 'Ú',
				'ñ', 'Ñ', '¿', '¡'
		);
		$replace = array(
				'&aacute;', '&eacute;', '&iacute;', '&oacute;', '&uacute;',
				'&Aacute;', '&Eacute;', '&Iacute;', '&Oacute;', '&Uacute;',
				'&ntilde;', '&Ntilde;', '&iquest;', '&iexcl;'
		);

		$string = str_replace($search, $replace, $string);
		return $string;
	}

	/**
	 * Converts to upper including accents
	 * @param unknown $string
	 */
	static function toUpper( $string ) {

		$string = strtoupper( $string );

		$search = array(
				'á', 'é', 'í', 'ó', 'ú', 'ñ'
		);
		$replace = array(
				'Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ'
		);

		$string = str_replace($search, $replace, $string);
		return $string;
	}

	/**
	 * Converts to lower including accents
	 * @param unknown $string
	 */
	static function toLower( $string ) {

		$string = strtoupper( $string );

		$search = array(
				'Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ'
		);
		$replace = array(
				'á', 'é', 'í', 'ó', 'ú', 'ñ'
		);

		$string = str_replace($search, $replace, $string);
		return $string;
	}

  /**
   * Returns a GUID string
   */
	static function GUID(){
		if (function_exists('com_create_guid')){
			return com_create_guid();
		}else{
			mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
			$charid = strtoupper(md5(uniqid(rand(), true)));
			$hyphen = chr(45);// "-"
			//$uuid = chr(123)// "{"
			$uuid = ""
			.substr($charid, 0, 8).$hyphen
			.substr($charid, 8, 4).$hyphen
			.substr($charid,12, 4).$hyphen
			.substr($charid,16, 4).$hyphen
			.substr($charid,20,12)
			;
			//.chr(125);// "}"
			return $uuid;
		}
	} // end function GUID

	/**
	 * Returns a random string of $len characters
	 * @param $len The length of the desired string
	 */
	static function random( $len, $useSymbols = false ) {
		$chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		if ( $useSymbols ) {
			$chars .= "°!#$%&/()=?¡*][{}-.,;:_";
		} // end if use symbols

		$charsLen = strlen( $chars );
		$charsLen--;

		$random = '';
		$count = 0;
		while( $count < $len ) {
			$rand = rand(0, $charsLen );
			$random .= $chars[$rand];
			$count++;
		} // end while

		return $random;
	} // end function random

  /**
   * isBase64
   *
   * Returns true if $str in base64
   *
   * @param (string) $str The string to verify
   * @return (boolean)
   */
	static function isBase64( $str )
	{
		if (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $str)) {
			return true;
		} else {
			return false;
		} // end if preg match
    } // end function isBase64

    /**
     * Returns the length of a string
     *
     * @param String $string The string to return the length
     *
     * @return String
     */
    static function len( $string )
    {
        return strlen( $string );
    } // end function len

} // end class
