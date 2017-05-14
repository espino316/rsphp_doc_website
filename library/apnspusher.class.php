<?php
/**
 * Send push notifications for iOS
 *
 * Please report bugs on https://github.com/espino316/rsphp/issues
 *
 * @author Luis Espino <luis@espino.info>
 * @copyright Copyright (c) 2016, Luis Espino. All rights reserved.
 * @license MIT License
 */
class ApnsPusher {

  //  Must set APNS_CERTIFICATE_FILE, APNS_PASSPHRASE and APNS_GATEWAY_URL
  //  as global variables
  private static $certificatePath = App::get('APNS_CERTIFICATE_FILE');
  private static $passPhrase = App::get('APNS_PASSPHRASE');
  //'ssl://gateway.sandbox.push.apple.com:2195'; // change to prod
  private static $apiGateway = App::get('APNS_GATEWAY_URL');

  /*
   * Send the message to one device
   */
  private static function sendToDevice(
    $deviceToken,
    $title,
    $message,
    $info = null,
    $sound = 'default'
  ) {

    //  Create the stream
    $context = stream_context_create();

    //  Set the certificate
    stream_context_set_option(
      $context,
      'ssl',
      'local_cert',
      self::$certificatePath
    );

    //  Set the passphrase
    stream_context_set_option(
      $context,
      'ssl',
      'passphrase',
      self::$passPhrase
    );

    // Open a connection to the APNS server
    $conn = stream_socket_client(
    	self::$apiGateway,
      $err,
    	$errstr,
      60,
      STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT,
      $context
    );

    //  If cannot connect, die
    if ( !$conn )
    	throw new Exception( "Failed to connect: $err $errstr" );

    //  Construct the payload:

    //  The alert:
    $alert['title'] = $title;
    $alert['body'] = $message;

    $payload['aps'] = array();

    if ( $info != null ) {
      $payload['aps']['info'] = $info;
    } // end if $info null

    $payload['aps']['alert'] = $alert;
    $payload['aps']['sound'] = $sound;
    $payload = json_encode( $payload );

    // Build the binary notification
    $msg =
      chr(0) .
      pack('n', 32) .
      pack('H*', $deviceToken) .
      pack('n', strlen($payload)) .
      $payload;

    // Send it to the server
    $result =
      fwrite(
        $conn,
        $msg,
        strlen($msg)
      );

    if ( !$result )
      	throw new Exception( "APNS message not sent" );

    // Close the connection to the server
    fclose($conn);

  } // end function sendToDevice

  /*
   * Sends the message to the devices
   */
  static function send(
    $to,
    $title,
    $message,
    $info = null,
    $sound = 'default'
  ) {

    if ( is_array( $to ) ) {
      foreach ($to as $deviceToken) {
        self::sendToDevice(
          $deviceToken,
          $title,
          $message,
          $info,
          $sound
        );
      }
    } else {
      self::sendToDevice(
        $to,
        $title,
        $message,
        $info,
        $sound
      );
    } // end if then else is array
  } // end function send

} // end class ApnsPusher