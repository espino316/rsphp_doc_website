<?php
/**
 * Send push notifications, iOS and Android
 *
 * Please report bugs on https://github.com/espino316/rsphp/issues
 *
 * @author Luis Espino <luis@espino.info>
 * @copyright Copyright (c) 2016, Luis Espino. All rights reserved.
 * @license MIT License
 */
class Pusher {

  /*
   * Sends a push notification
   */
  static function send(
    $platform,
    $to,
    $title,
    $message,
    $info,
    $sound = null
  ) {
    if ( $platform == 1 ) { // Android
      if ( $sound )
        $sound = 1;

      GMCPusher::send(
        $to,
        $message,
        $title,
        $title,
        $info,
        1,
        $sound
      );

    } else if ( $platform == 2 ) { // iOS
      if ( $sound == null ) {
        $sound = 'default';
      }

      ApnsPusher::send(
        $to,
        $title,
        $message,
        $info,
        $sound
      );
    } // end if then else platform
  } // end function send
} // end class Pusher