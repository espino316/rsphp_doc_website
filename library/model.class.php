<?php
/**
 * Represent a Model in the MVC pattern
 *
 * Please report bugs on https://github.com/espino316/rsphp/issues
 *
 * @author Luis Espino <luis@espino.info>
 * @copyright Copyright (c) 2016, Luis Espino. All rights reserved.
 * @license MIT License
 */
class Model {

	/**
	 *
	 * @var DbHelper
	 */
	protected static $db;

	function __construct($dbConnName = NULL) {
		self::$db = new DbHelper($dbConnName);
	}

	/**
	 * Return a list of its properties
	 */
	public function getProperties() {
		return get_object_vars($this);
	}

	/**
	 * Takes the value of its properties from the input
	 */
	function getPropertiesFromInput() {
		$properties = get_object_vars($this);
		$inputs = Input::get();
		foreach( array_keys($inputs) as $inputKey ) {
			foreach ( array_keys($properties) as $propertyKey ) {
				if ( $inputKey == $propertyKey ) {
					$this->$propertyKey = $inputs[$inputKey];
				}
			}
		}
	} // end function getPropertiesFromInput

	/**
	 *
	 * @var string
	 */
	protected $tableName;

	/**
	 * Sets a table name for the model
	 * @param string $tableName
	 */
	function setTableName( $tableName ) {
		$this->tableName = $tableName;
	} // end function setTableName

	/**
	 * Returns the table name of the class, assumes stripping Model from class name by default
	 * @return string
	 */
	function getTableName() {
		if ($this->tableName == NULL) {
			return substr_replace(get_class($this), "", -5);
		} else {
			return $this->tableName;
		}
	} // end getTableName

	static function tableName() {
		return substr_replace(get_called_class(), "", -5);
	} // end function

	/**
	 * Gets the first record as an instance of this class
	 * @return self
	 */
	static function first() {
		self::setDB();
		return self::$db->first(
			self::TableName(),
			get_called_class()
		);
	} // end function first

	/**
	 * Gets a
	 */
	static function get() {
		self::setDB();
		return self::$db->get(
			self::TableName(),
			get_called_class()
		);
	} // end function get

	/**
	 * Sets a where clause
	 * @param string or array $columnName
	 * @param string $value
	 */
	static function where($columnName, $value = NULL) {
		self::setDB();
		return self::$db->where($columnName, $value);
	} // end static function where

	/**
	 * Sets and OR statement
	 * @param string $columnName
	 * @param object $value
	 * @return DbHelper
	 */
	static function orWhere($columnName, $value) {
		self::setDB();
		return self::$db->orWhere($columnName, $value);
	} // end static function orWhere

	/**
	 * Adds an order by statement to the current select statement
	 * @param string $column
	 * @param string $ascDesc
	 * @return DbHelper
	 */
	static function orderBy($column, $ascDesc = NULL) {
		self::setDB();
		return self::$db->orderBy($column, $ascDesc);
	} // end static function orderBy

	/**
	 * Sets an "TOP" or "LIMIT" clause
	 * @param int $limit
	 * @param int $startAt
	 */
	static function top($limit, $startAt = null) {
		self::setDB();
		return self::$db->top($limit, $startAt);
	} // end static function top

	/**
	 * set the static properti DB to a default connection
	 */
	protected static function setDB() {
		if ( self::$db === null ) {
			self::$db = new DbHelper();
		}
		self::$db->from(self::tableName());
		self::$db->setReturnClass(get_called_class());
	} // end protected static function setDB

} // end class Model