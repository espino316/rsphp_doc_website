<?php
/**
 * Send emails
 *
 * Please report bugs on https://github.com/espino316/rsphp/issues
 *
 * @author Luis Espino <luis@espino.info>
 * @copyright Copyright (c) 2016, Luis Espino. All rights reserved.
 * @license MIT License
 */
class Mailer {

	static $to;
	static $from;
	static $subject;
	static $message;
	static $html = false;
	static $attachments = array();

	private static $emailToVerify;
	private static $smtpConn;
	private static $recordLog;
	private static $server;
	private static $lastResponse;
  private static $isValid = TRUE;

  static function setConfig( $config ) {
    $valid = (
        isset( $config['mailServer'] ) &&
        isset( $config['mailUser'] ) &&
        isset( $config['mailPassword'] )
      );

    if ( !$valid ) {
      throw new Exception( 'Mail settings incomplete' );
    } // end if not valid

    App::set( 'MAIL_SERVER', $config['mailServer'] );
    App::set( 'MAIL_USER', $config['mailUser'] );
    App::set( 'MAIL_PWD', $config['mailPassword'] );

    if ( isset( $config['mailPort'] ) ) {
      App::set( 'MAIL_PORT', $config['mailPort'] );
    } // end if mail port config
  } // en function setConfig

	/**
	 * Adds an attachment to the message
	 */
	static function addAttachment( $file ) {
		if ( is_array( $file ) ) {
			foreach ( $file as $attachment ) {
				self::$attachments[] = $attachment;
			} // end foreach
		} else {
			self::$attachments[] = $file;
		}// end if then else is array
	} // end function addAttachment

	static function send() {

		require_once "Mail.php";
		require_once "Mail/mime.php";

    $valid = ( App::get( 'MAIL_SERVER' ) && App::get( 'MAIL_USER' ) && App::get( 'MAIL_PWD' ) );
    if ( ! $valid ) {
      print_r ( App::get() );
      print_r ( 'throw exception email settings missing' );
      return;
      //throw new Exception( 'Email settings missing' );
    } // end is not valid

		$host = App::get('MAIL_SERVER');
		$username = App::get('MAIL_USER');
    $password = App::get('MAIL_PWD');

    if ( App::get( 'MAIL_PORT' ) ) {
      $port = App::get( 'MAIL_PORT' );
      $config['port'] = $port;
    } // end if port

		$headers['From'] = self::$from;
		$headers['To'] = self::$to;
		$headers['Subject'] = self::$subject;
		if ( self::$html ) {
			$headers['Content-Type'] = 'text/html; charset=ISO-8859-1';
		}

		$config['host'] = $host;
		$config['auth'] = true;
		$config['username'] = $username;
		$config['password'] = $password;

		if ( count( self::$attachments ) ) {
			$mime = new Mail_mime("\r\n");
			if ( self::$html ) {
				$mime->setHTMLBody( self::$message );
			} else {
				$mime->setTXTBody( self::$message );
			} // end if html

			foreach( self::$attachments as $attachment ) {
				$mime->addAttachment( $attachment, 'application/octet-stream' );
			} // foreach

			self::$message = $mime->get();
			$headers = $mime->headers( $headers );
		} // end if $attachments

		$smtp =
			Mail::factory(
				'smtp',
				$config
			);

		$mail =
			$smtp->send(
				self::$to,
				$headers,
				self::$message
			);

		//	Clear the attachments
		self::$attachments = array();

		if ( PEAR::isError( $mail ) ) {
			throw new Exception( $mail->getMessage() );
		} else {
			return $mail;
		} // end if PEAR error

	} // end function send

	static function sendLocal() {

		if ( self::$html ) {
			$headers = "From: " . self::$from . "\r\n";
			$headers .= "Reply-To: ". self::$from . "\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			$headers .= 'X-Mailer: PHP/' . phpversion();
		} else {
			$headers = "From: " . self::$from . "\r\n" .
					'Reply-To: ' . self::$from . "\r\n" .
					'X-Mailer: PHP/' . phpversion();
		}

		mail(self::$to, self::$subject, self::$message, $headers);
	} // end function send

	static function verifyEmail( $email ) {
		$verifier = new EmailVerifier( $email );
		$verifier->verify();
		$result['result'] = $verifier->isValid;
		$result['log'] = $verifier->getLog();
		return $result;
	}

} // end class Mailer
