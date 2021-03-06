<?php
/**
 * Does reverse geocoding
 *
 * Please report bugs on https://github.com/espino316/rsphp/issues
 *
 * @author Luis Espino <luis@espino.info>
 * @copyright Copyright (c) 2016, Luis Espino. All rights reserved.
 * @license MIT License
 */
class Geocoder {
	// function to geocode address, it will return false if unable to geocode address
	function getGeoLocation($address){
		//	string Accents
		$address = StringHelper::stripAccents($address);

	    // url encode the address
	    $address = urlencode($address);

	    // google map geocode api url
	    $url = "http://maps.google.com/maps/api/geocode/json?sensor=false&address={$address}";

	    // get the json response
	    $resp_json = file_get_contents($url);

	    // decode the json
	    $resp = json_decode($resp_json, true);

	    // response status will be 'OK', if able to geocode given address
	    if($resp['status']=='OK'){

	        // get the important data
	        $lat = $resp['results'][0]['geometry']['location']['lat'];
	        $lng = $resp['results'][0]['geometry']['location']['lng'];
	        $formatted_address = $resp['results'][0]['formatted_address'];

	        // verify if data is complete
	        if($lat && $lng && $formatted_address){

	            // put the data in the array
	            $data_arr = array();

	            $data_arr['lat'] = $lat;
	            $data_arr['lng'] = $lng;
	            $data_arr['formatted_address'] = $formatted_address;

	            return $data_arr;

	        }else{
	            return false;
	        }

	    }else{
	        return false;
	    }
	}
} // end class