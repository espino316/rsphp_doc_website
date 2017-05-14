<?php

// API access key from Google API's Console
define( 'API_ACCESS_KEY', App::get('GOOGLE_API_PUSH_KEY') );
define( 'ANDROID_PUSH_URL', 'https://android.googleapis.com/gcm/send');

/**
 * Does push notifications for android
 *
 * Please report bugs on https://github.com/espino316/rsphp/issues
 *
 * @author Luis Espino <luis@espino.info>
 * @copyright Copyright (c) 2016, Luis Espino. All rights reserved.
 * @license MIT License
 */
class GMCPusher {

	public static $apiKey;
	static $androidPushUri = 'https://android.googleapis.com/gcm/send';

	/**
	 * Send a GCM push message
	 * @param string or array of strings $to
	 * @param string $message
	 * @param string $title
	 * @param string $subTitle
	 * @param int $vibrate optional, boolean either 1 or 0, default 1
	 * @param int $sound optional, boolean either 1 or 0, default 1
	 */
	static function send(
		$to,
		$message,
		$title,
		$subTitle,
		$info = null,
		$vibrate = 1,
		$sound = 1
	) {
		if ( is_array( $to ) ) {
			$fields['registration_ids'] = $to;
		} else {
			$fields['to'] = $to;
		}

		$msg['message'] = $message;
		$msg['title'] = $title;
		$msg['subtitle'] = $subTitle;
		$msg['vibrate'] = $vibrate;
		$msg['sound'] = $sound;

		if ( $info != null ) {
			$msg['info'] = $info;
		}

		$fields['data'] = $msg;

		$headers[] = 'Authorization: key=' . API_ACCESS_KEY;
		$headers[] = 'Content-Type: application/json';

		$result
			= self::doCurl(
				ANDROID_PUSH_URL,
				$headers,
				$fields
			);

		$result = json_decode( $result, true );

		if ( $result['success'] == "1" ) {
			return true;
		} else {
			return false;
		}
	} // end function send

	public static function sendWeb ( $id ) {
		// Set GCM endpoint
		$url = 'https://android.googleapis.com/gcm/send';

		$fields = array(
				'registration_ids' => $id,
		);

		$headers = array(
				'Authorization: key=' . API_ACCESS_KEY,
				'Content-Type: application/json'
		);

		$result
			= self::doCurl(
					ANDROID_PUSH_URL,
					$headers,
					$fields
			);

		$result = json_decode( $result, true );

		if ( $result['success'] == "1" ) {
			return true;
		} else {
			return false;
		}

	} // end function sendWeb

	/**
	 * Do a curl requests
	 * @param string $url
	 * @param array $headers
	 * @param array $fields
	 * @return mixed
	 */
	static function doCurl(
		$url,
		$headers,
		$fields
	) {

		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, $url );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch );
		curl_close( $ch );
		return $result;

	}

	function example() {

		$registrationIds = array( $_GET['id'] );

		// prep the bundle
		$msg = array
		(
			'message' 	=> 'here is a message. message',
			'title'		=> 'This is a title. title',
			'subtitle'	=> 'This is a subtitle. subtitle',
			'tickerText'	=> 'Ticker text here...Ticker text here...Ticker text here',
			'vibrate'	=> 1,
			'sound'		=> 1,
			'largeIcon'	=> 'large_icon',
			'smallIcon'	=> 'small_icon'
		);
		$fields = array
		(
			'registration_ids' 	=> $registrationIds,
			'data'			=> $msg
		);

		$headers = array
		(
			'Authorization: key=' . API_ACCESS_KEY,
			'Content-Type: application/json'
		);

		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, ANDROID_PUSH_URL );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch );
		curl_close( $ch );
		echo $result;

	} // end function

}