<?php
/**
 * Represents a connection to a database
 *
 * Please report bugs on https://github.com/espino316/rsphp/issues
 *
 * @author Luis Espino <luis@espino.info>
 * @copyright Copyright (c) 2016, Luis Espino. All rights reserved.
 * @license MIT License
 */
class DbConnection {

	public $driver;
	public $hostName;
	public $databaseName;
	public $userName;
	public $password;
	public $port;

	function __construct( $options ) {

		$this->driver = $options['driver'];
		$this->hostName = $options['hostName'];
		$this->databaseName = $options['databaseName'];
		$this->userName = $options['userName'];
		$this->password = $options['password'];

		if ( array_key_exists('port', $options) ) {
			$this->port = $options['port'];
		}

	} // end __construct

} // end class