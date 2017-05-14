<?php
class GeoFenceLib {
  function create( $fileName, $tableName, $businessId) {
    RS::printLine("Table name set $tableName");
  	RS::printLine("Reading file...");
  	$simple = file_get_contents($fileName);
  	RS::printLine("Parsing file...");
  	$p = xml_parser_create();
  	xml_parse_into_struct($p, $simple, $vals, $index);
  	xml_parser_free($p);

  	$geofences = null;
  	$geofence = null;
  	$insertSql = 'INSERT INTO ' . $tableName . ' VALUES (nextval(\'geofences_geofence_id_seq\'::regclass), @businessId, \'@geofenceName\', st_geomfromtext(\'POLYGON((@coordinates))\')::polygon)';
  	$updateSql = 'UPDATE ' . $tableName . ' SET coordinates = st_geomfromtext(\'POLYGON((@coordinates))\')::polygon WHERE business_id = @businessId AND name = \'@geofenceName\';';

  	foreach ( $vals as $node ) {

  		if ( $node['level'] == 4 && $node['tag'] == 'PLACEMARK' && $node['type'] == 'open') {
  			//	Here we open geofence
  			$geofence = array();
  		}

  		if ( $node['level'] == 5 && $node['tag'] == 'NAME' && $node['type'] == 'complete') {
  			//	Here we name the geofence
  			$geofence['name'] = $node['value'];
  		}

  		if ( $node['level'] == 5 && $node['tag'] == 'POLYGON' && $node['type'] == 'open') {
  			//	Here is polygon
  			$geofence['type'] = $node['tag'];
  			RS::printLine("Geofence founded...");
  		}

  		if ( $node['level'] == 8 && $node['tag'] == 'COORDINATES' && $node['type'] == 'complete') {
  			//	Here we name the geofence
  			$newCoordinates = null;
  			$coordinates = $node['value'];
  			$coordinates = split(',0.0', $coordinates);
  			$coordinates = array_filter($coordinates);
  			foreach ( $coordinates as $coord ) {
  				$coords = split( ',', $coord );
  				$newCoordinates[] = $coords[1] . ' ' . $coords[0];
  			}

  			$geofence['coordinates'] = join( ", ", $newCoordinates );
  			$geofences[] = $geofence;
  			RS::printLine("Geofence added " . $geofence['name']);
  		}
  	}

  	foreach ( $geofences as $geofence ) {

  		RS::printLine("Updating geofence " . $geofence['name']);

  		$sql = "SELECT COUNT(*) FROM geofences WHERE business_id = :businessId AND name = :geofenceName";
  		$queryParams['businessId'] = $businessId;
  		$queryParams['geofenceName'] = $geofence['name'];

  		$count = $db->scalar($sql, $queryParams);

  		if ( $count > 0 ) {
  			$sql = $updateSql;
  		} else {
  			$sql = $insertSql;
  		}

  		$sql = str_replace("@businessId", $businessId, $sql);
  		$sql = str_replace("@geofenceName", $geofence['name'], $sql);
  		$sql = str_replace("@coordinates", $geofence['coordinates'], $sql);

  		$db->query($sql, null);
  		RS::printLine("Geofence updated " . $geofence['name']);
  	} // end foreach
  } // end function create
} // end class GeofenceLib