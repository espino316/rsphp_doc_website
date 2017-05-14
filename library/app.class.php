<?php
class App {
	protected static $variables;

	public static function set($name, $value = null) {
		if ( !self::$variables )
			self::$variables = array();

    if ( is_array ( $name ) ) {
      foreach( array_keys($name) as $key ) {
        self::$variables[$key] = $name[$key];
      }
    } else {
      self::$variables[$name] = $value;
    }
	} // end function set

  public static function get( $name = null ) {
    if ( $name === null ) {
      return self::$variables;
    } // end if name is null
		if ( self::$variables ){
			if ( isset( self::$variables[$name]) ) {
					return self::$variables[$name];
			}	else {
				return null;
			} // end if then else self::variables[name] is set
		} else {
			return null;
		} // end if then else self::variables exists
	} // end function get

} // end class App
