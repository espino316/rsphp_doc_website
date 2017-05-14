<?php
/**
 * Represents a data source
 *
 * Please report bugs on https://github.com/espino316/rsphp/issues
 *
 * @author Luis Espino <luis@espino.info>
 * @copyright Copyright (c) 2016, Luis Espino. All rights reserved.
 * @license MIT License
 */
class DataSource {

  public $connection;
  public $name;
  public $type;
  public $text;
  public $parameters;
  public $filters;

  /**
   * Creates a new instance of a datasource
   */
  function __construct( $connection, $name, $type, $text ) {
    $this->connection = $connection;
    $this->name = $name;
    $this->type = $type;
    $this->text = $text;
  } // end class DataSource  } // end function __construct

  function addParam( $name, $type, $defaultValue = null ) {
    $param = new Parameter( $name, $type, $defaultValue );
    $this->parameters[] = $param;
  }

  function addFilter( $key, $value ) {
    $this->filters[$key] = $value;
  }
} // end class DataSource