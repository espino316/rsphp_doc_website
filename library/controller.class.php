<?php
/**
 * Controller for the MVC design pattern
 *
 * Please report bugs on https://github.com/espino316/rsphp/issues
 *
 * @author Luis Espino <luis@espino.info>
 * @copyright Copyright (c) 2016, Luis Espino. All rights reserved.
 * @license MIT License
 */
class Controller {

  /**
   * Creates an instance of Controller
   */
  function __construct() {
  } // end function __construct

    /**
     * Prints an array or object as Xml
     */
    function xmlResponse( $data ) {
      XmlHelper::xmlResponse( $data );
    } // end function xmlResponse

    /**
     * Prints an array or object as Json
     */
    function jsonResponse( $data ) {

      $output = ob_get_contents();
      if ( StringHelper::contains($output, '<b>Notice</b>: ') ) {
        ob_end_clean();
        $data = array();
        $data['error'] = "Notice error." . $output;
      } else if ( StringHelper::contains($output, '<b>Warning</b>: ') ) {
        ob_end_clean();
        $data = array();
        $data['error'] = "Warning error." . $output;
      }

    	header('Content-Type: application/json');
      if ( App::get('allowCORS') ) {
        $this->setCORSHeaders();
      }
    	$result = json_encode( $data );
      echo $result;
    } // end function jsonResponse

    /**
     * Handles an exception
     * @param Exception $ex
     */
    function handleExeption( $ex ) {
      //  Catch exceptions, show messages
    	RS::handleExeption( $ex );
    } // end function handleExeption

    /**
     * Sets the CORS Headers
     */
    function setCORSHeaders() {
      header('Access-Control-Allow-Origin: *');
      header('Access-Control-Allow-Credentials: true');
      header('Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT');
      header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers');
    } // end function setCORS

} // end class Controller