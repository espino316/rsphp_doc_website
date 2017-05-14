<?php
/**
 * Helper for Uri manipulation
 *
 * Please report bugs on https://github.com/espino316/rsphp/issues
 *
 * @author Luis Espino <luis@espino.info>
 * @copyright Copyright (c) 2016, Luis Espino. All rights reserved.
 * @license MIT License
 */
class UriHelper {

	/**
	 *
	 * @var array The segments of the current url
	 */
	protected static $segments;

	static function setSegments( $segments ) {
		self::$segments = $segments;
	} // end function setSegments

	/**
	 * retrieve a segment
	 * @param string $index The index of the segment
	 */
	static function getSegment( $index ) {
		if ( isset( self::$segments[ $index ] ) ) {
			return self::$segments[ $index ];
		} else {
			return null;
		} // end if then else index exists
	} // end function getSegment

	/**
	 * Retrieve the number of segments
	 */
	static function getSegmentsLength () {
		if ( isset ( self::$segments ) ) {
			return count ( self::$segments );
		} else {
			return 0;
		} // end if then else is $segments set
	} // end function getSegmentsLength

	/**
	 * Redirects to another page
	 * @param string $url The web address to redirect
	 * @param number $statusCode
	 */
	static function redirect($url, $statusCode = 303)
	{
		$output = ob_get_contents();
		if ( $output ) {
			ob_clean();
			echo '<meta http-equiv="refresh" content="0; URL=' . $url . '">';
			exit;
		} else {
			header('Location: ' . $url, true, $statusCode);
			exit;
		}

	} // end redirect

	/**
	 * Redirect with POST data.
	 *
	 * @param string $url URL.
	 * @param array $post_data POST data. Example: array('foo' => 'var', 'id' => 123)
	 * @param array $headers Optional. Extra headers to send.
	 */
	static function redirectPost($url, array $data, array $headers = null) {
		$params = array(
				'http' => array(
						'method' => 'POST',
						'content' => http_build_query($data)
				)
		);
		if (!is_null($headers)) {
			$params['http']['header'] = '';
			foreach ($headers as $k => $v) {
				$params['http']['header'] .= "$k: $v\n";
			}
		}
		$ctx = stream_context_create($params);
		$fp = @fopen($url, 'rb', false, $ctx);
		if ($fp) {
			echo @stream_get_contents($fp);
			die();
		} else {
			// Error
			throw new Exception("Error loading '$url', $php_errormsg");
		}
	} // end function redirectPost

	//  Here saves the visit
	function checkIP($ip) {
	   if (!empty($ip) && ip2long($ip)!=-1 && ip2long($ip)!=false) {

				 $private_ips = array (
		       array('0.0.0.0','2.255.255.255'),
		       array('10.0.0.0','10.255.255.255'),
		       array('127.0.0.0','127.255.255.255'),
		       array('169.254.0.0','169.254.255.255'),
		       array('172.16.0.0','172.31.255.255'),
		       array('192.0.2.0','192.0.2.255'),
		       array('192.168.0.0','192.168.255.255'),
		       array('255.255.255.0','255.255.255.255')
	       );

	       foreach ($private_ips as $r) {
	           $min = ip2long($r[0]);
	           $max = ip2long($r[1]);
	           if ((ip2long($ip) >= $min) && (ip2long($ip) <= $max)) return false;
	       }
	       return true;
	   } else {
	       return false;
	   }
	}

	function determineIP() {
	   if (checkIP($_SERVER["HTTP_CLIENT_IP"])) {
	       return $_SERVER["HTTP_CLIENT_IP"];
	   }
	   foreach (explode(",",$_SERVER["HTTP_X_FORWARDED_FOR"]) as $ip) {
	       if (checkIP(trim($ip))) {
	           return $ip;
	       }
	   }
	   if (checkIP($_SERVER["HTTP_X_FORWARDED"])) {
	       return $_SERVER["HTTP_X_FORWARDED"];
	   } elseif (checkIP($_SERVER["HTTP_X_CLUSTER_CLIENT_IP"])) {
	       return $_SERVER["HTTP_X_CLUSTER_CLIENT_IP"];
	   } elseif (checkIP($_SERVER["HTTP_FORWARDED_FOR"])) {
	       return $_SERVER["HTTP_FORWARDED_FOR"];
	   } elseif (checkIP($_SERVER["HTTP_FORWARDED"])) {
	       return $_SERVER["HTTP_FORWARDED"];
	   } else {
	       return $_SERVER["REMOTE_ADDR"];
	   }
	}

	static function getTrackInfo() {
	  $info['time'] = '"'.DateHelper::now().'"';
	  $info['ip'] = '"'.determineIP().'"';;
	  $info['url'] = '""';
	  $info['referer'] = '""';
	  $info['browser'] = '""';

	  if ( isset( $_SERVER['HTTP_HOST'] ) && isset( $_SERVER['REQUEST_URI'] ) ) {
	    $info['url'] = '"'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'"';
	  }

	  if ( isset( $_SERVER['HTTP_REFERER'] ) ) {
	    $info['referer'] = '"'.$_SERVER['HTTP_REFERER'].'"';
	  }

	  if ( isset( $_SERVER['HTTP_USER_AGENT'] ) ) {
	    if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE)
	      $browser = 'Internet explorer';
	    elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== FALSE) //For Supporting IE 11
	      $browser = 'Internet explorer';
	    elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== FALSE)
	      $browser = 'Mozilla Firefox';
	    elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== FALSE)
	      $browser = 'Google Chrome';
	    elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== FALSE)
	      $browser = "Opera Mini";
	    elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== FALSE)
	      $browser = "Opera";
	    elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') !== FALSE)
	      $browser = "Safari";
	    else
	      echo 'Unknown';
	    $info['browser'] = '"'.$browser.'"';
	  }

	  return $info;
	} // end getTrackInfo
} // end class UriHelper