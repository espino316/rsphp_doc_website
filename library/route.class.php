<?php
/**
 * Represents a route
 *
 * Please report bugs on https://github.com/espino316/rsphp/issues
 *
 * @author Luis Espino <luis@espino.info>
 * @copyright Copyright (c) 2016, Luis Espino. All rights reserved.
 * @license MIT License
 */
class Route {

	public $_method;
	public $_uri;
	public $_newUri;

	/**
	 * Construction of the Route
	 * @param unknown $method * for all, 'GET', 'POST', 'PUT', 'DELETE'
	 * @param unknown $uri
	 * @param unknown $newUri
	 */
	function __construct(
		$method,
		$uri,
		$newUri
	) {

		$this->_method = $method;
		$this->_uri = $uri;
		$this->_newUri = $newUri;

	} // end construct

	function match( $url ) {

		if ( StringHelper::contains( $this->_uri, ":") ) {
			$segments = explode( "/", $this->_uri );
			$newSegments = explode( "/", $this->_newUri );
			$urlSegments = explode( "/", $url );

			$segments = array_filter( $segments );
			$newSegments = array_filter( $newSegments );
			$urlSegments = array_filter( $urlSegments );

			$pattern = "";
			foreach ($segments as $key => $value) {
				if ( !empty( $pattern ) ) {
					$pattern.="\\/";
				}
				if ( StringHelper::contains( $value, ":") ) {
					$pattern .= "(\d+)";
				} else {
					$pattern .= $value;
				}
			} // end foreach
			$pattern="/".$pattern."/";

			if ( preg_match( $pattern, $url ) ) {
				$patterns = array();
				$replacements = array();
				foreach ( $segments as $key => $value ) {
					if ( StringHelper::contains($value, ":") ) {
						$segments[$key] = $urlSegments[$key];
						$patterns[] = "/$value/";
						$replacements[] = $urlSegments[$key];
					} // end if
				} // end foreach
				$this->_newUri = preg_replace( $patterns, $replacements, $this->_newUri );
				$this->_uri = preg_replace( $patterns, $replacements, $this->_uri );
			} // end if preg_match
		} // end if contains ":"
	} // end function getUrl
} // end class