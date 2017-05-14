<?php
/**
 * Helper for XML manipulation
 *
 * Please report bugs on https://github.com/espino316/rsphp/issues
 *
 * @author Luis Espino <luis@espino.info>
 * @copyright Copyright (c) 2016, Luis Espino. All rights reserved.
 * @license MIT License
 */
class XmlHelper {

  static function xmlResponse( $data ) {
    $xmlData = new SimpleXMLElement('<?xml version="1.0"?><data></data>');
    self::arrayToXml($data,$xmlData);
    $result = $xmlData->asXML();
    ob_end_clean();
    header('Content-Type: application/xml');
    if ( App::get('allowCORS') ) {
      $this->setCORSHeaders();
    }
    echo $result;
  }

  static function arrayToXml( $data, &$xmlData ) {
    foreach( $data as $key => $value ) {
      if ( is_object ( $value ) ) {
        $value = (array) $value;
      }

      if( is_array($value) ) {
        if( is_numeric($key) ){
          $key = 'item'.$key;
        }
        $subnode = $xmlData->addChild($key);
        self::arrayToXml($value, $subnode);
      } else {
        if (strpos($key, '*') === false) {
          $xmlData->addChild("$key",htmlspecialchars("$value"));
        } // end if *
      } // end if then else is numeric
    } // end if then else is array
  } // end function arrayToXml
} // end class XmlHelper