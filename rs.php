<?php
/**
 * Console manipulation for the aplication
 *
 * Please report bugs on https://github.com/espino316/rsphp/issues
 *
 * @author Luis Espino <luis@espino.info>
 * @copyright Copyright (c) 2016, Luis Espino. All rights reserved.
 * @license MIT License
 */

require_once 'public/index.php';

if ( $argv[1] == 'version' ) {
	echo RS::$version;
	return;
}

if ( $argv[1] == 'cleanapp' ) {
	RS::cleanApp();
	return;
}

if ( $argv[1] == 'controller' && $argv[2] == 'create' && $argv[3] && $argv[4] ) {
	RS::createController( $arg3, $arg4 );
	return;
}

//	function create model
//	php rs.php model create <tableName>
//	php rs.php model create attachments
if ( $argv[1] == 'model' && $argv[2] == 'create' ) {

	RS::printLine("Model create");
	//	The table name
	$tableName = strtolower($argv[3]);

	RS::printLine("Beginning creation of model for table $tableName...");

	// Model Create
	RS::createModel( $tableName );
	return;
}

if ( $argv[1] == 'datasource' && $argv[2] == 'add' && $argv[3] && $argv[4] && $argv[5] && $argv[6] ) {
	RS::addDataSource( $arg3, $arg4, $arg5, $arg6 );
	return;
} // end if dataSource

if ( $argv[1] == 'view' && $argv[2] == 'create' && $argv[3] == 'new' && $argv[4] != '' ) {
	$tableName = $argv[4];
	RS::createViewNewRecord( $tableName );
	return;
} // end if view create new actors

//	Creates a geofence
//	php rs.php geofence create <fileName> <tableName> <businessId>
//  php rs.php geofence create "public/files/test.kml" geogence 30

if ( $argv[1] == "geofence" && $argv[2] == "create" && isset( $argv[3] ) && isset( $argv[4] ) && isset( $argv[5] ) ) {

	RS::printLine("");
	$tableName = $argv[4];
	$businessId = $argv[5];
	$fileName = $argv[3];
	return;
	// createGeoFence
}

RS::printLine("");