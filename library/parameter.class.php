<?php
/**
 * Represent a parameter for data sources
 *
 * Please report bugs on https://github.com/espino316/rsphp/issues
 *
 * @author Luis Espino <luis@espino.info>
 * @copyright Copyright (c) 2016, Luis Espino. All rights reserved.
 * @license MIT License
 */
class Parameter {
  public $name;
  public $type;
  public $defaultValue = null;

  function __construct( $name, $type, $defaultValue = null ) {
    $this->name = $name;
    $this->type = $type;
    $this->defaultValue = $defaultValue;
  } // end function construct
} // end class Parameter