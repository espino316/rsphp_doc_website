<?php
/**
 * Helper for database manipulation
 *
 * Please report bugs on https://github.com/espino316/rsphp/issues
 *
 * @author Luis Espino <luis@espino.info>
 * @copyright Copyright (c) 2016, Luis Espino. All rights reserved.
 * @license MIT License
 */
class DbHelper {

	/**
	 *
	 * @var DbConnection The connection details to the database
	 */
	public $dbConn;

	/**
	 *
	 * @var PDO The actual connection
	 */
	protected $conn;

	/**
	 *
	 * @var The select statement
	 */
	protected $selectStatement;

	/**
	 *
	 * @var string The where conditions
	 */
	protected $whereStatement;

	/**
	 *
	 * @var string The table to query on
	 */
	protected $from;

	/**
	 *
	 * @var array The params for the array
	 */
	protected $whereParams;

	/**
	 *
	 * @var string Order by statement
	 */
	protected $orderByStatement;

	/**
	 * The array for store the join tables
	 * @var array
	 */
	protected $joinStatement;

	/**
	 * Number or rows to return statement
	 * @var string
	 */
	protected $limitStatement;

	/**
	 * This will indicate if the limit is at beginning (2) or at the end (1)
	 * @var int
	 */
	protected $limitType;

	/**
	 * For SqlServer only. Indicates where to start
	 * @var int
	 */
	protected $limitStartAt;

	/**
	 * This is the name of the class to return, if any
	 * @var string
	 */
	protected $returnClassName;

	/**
	 * Set the class to return
	 * @param string $returnClassName
	 */
	function setReturnClass( $returnClassName ) {
		$this->returnClassName = $returnClassName;
	} // end function setReturnClass

	/**
	 * Creates an instance of DbHelper
	 * @param string $dbConnName The name of the connection to the database
	 */
	function __construct( $dbConnName = NULL ) {

		if ( $dbConnName != NULL ) {
			$this->dbConn = DB::$connections[$dbConnName];
		} else {
			if ( !isset( DB::$connections ) ) {
				throw new Exception("No connections are set up", 1);
			} // end if isset DBConn
			if (DB::$connections['default'] == NULL) {
				throw new Exception('Default database not set up!');
			}
			$this->dbConn = DB::$connections['default'];
		}
	}

	private function clear() {
		$this->from = null;
		$this->joinStatement = null;
		$this->limitStatement = null;
		$this->orderByStatement = null;
		$this->selectStatement = null;
		$this->whereParams = null;
		$this->whereStatement = null;
	}
	/**
	 * Connect to the database
	 */
	protected function connect() {
		try {

			$driver = $this->dbConn->driver;
			$hostName = $this->dbConn->hostName;
			$databaseName = $this->dbConn->databaseName;
			$userName = $this->dbConn->userName;
			$password = $this->dbConn->password;

			if ( $this->dbConn->port !== null ) {
				if ( is_numeric( $this->dbConn->port) ) {
					$hostName = $hostName . ':' . $this->dbConn->port;
				}
			}

			$host = 'host';
			$dbname = 'dbname';

			if ( $driver == 'sqlsrv' ) {
				$host = 'Server';
				$dbname = 'Database';
			}

			$this->conn = new PDO(
				"$driver:$host=$hostName;$dbname=$databaseName",
				$userName,
				$password
			);

			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch(PDOException $e)
		{
			echo "Connection failed: " . $e->getMessage();
		}
	}

	/**
	 * Returns the insert statement
	 * @param string $tableName The name of the table
	 * @param array $params The key value par of column name and value
	 */
	function getInsertStatement( $tableName, $params ) {

		//	Connect
		$this->connect();

		//	The sql instruction
		$sql = "INSERT INTO $tableName (";

		//	To check if is the first key
		$isInit = true;

		//	Loop the keys and add the column names
		foreach ( array_keys( $params ) as $colName ) {
			if ( $isInit ) {
				$isInit = false;
				$sql .= "$colName";
			}
			else {
				$sql .= ", $colName";
			}
		}

		$sql .= ") VALUES ( ";

		//	Init again
		$isInit = true;

		//	New array with the ":" at the beginning
		//	and continuation of the insert statement

		//	Loop the keys and add the column Names for values
		foreach ( array_keys( $params ) as $colName ) {
			if ( $isInit ) {
				$isInit = false;
				$sql .= ":$colName";
			}
			else {
				$sql .= ", :$colName";
			}

			$queryParams[":$colName"] = $params[$colName];
		}

		$sql .= ");";

		return $sql;
	} // end insert

	/**
	 * Inserts a row to the database
	 * @param string $tableName The name of the table
	 * @param array $params The key value par of column name and value
	 */
	function insert( $tableName, $params ) {

		//	remove nulls
		foreach ( array_keys($params) as $key ) {
			if ( $params[$key] == null &&
					! is_numeric($params[$key]) &&
					$params[$key] !== 0 ) {
				unset( $params[$key] );
			}
		}

		//	Connect
		$this->connect();

		//	The sql instruction
		$sql = "INSERT INTO $tableName (";

		//	To check if is the first key
		$isInit = true;

		//	Loop the keys and add the column names
		foreach ( array_keys( $params ) as $colName ) {
			if ( $isInit ) {
				$isInit = false;
				$sql .= "$colName";
			}
			else {
				$sql .= ", $colName";
			}
		}

		$sql .= ") VALUES ( ";

		//	Init again
		$isInit = true;

		//	New array with the ":" at the beginning
		//	and continuation of the insert statement

		//	Loop the keys and add the column Names for values
		foreach ( array_keys( $params ) as $colName ) {
			if ( $isInit ) {
				$isInit = false;
				$sql .= ":$colName";
			}
			else {
				$sql .= ", :$colName";
			}

			$queryParams[":$colName"] = $params[$colName];
		}

		$sql .= ");";

		//	If logSql, then log Sql
		if ( App::get('logSql') ) {
			Logger::debugSql($sql);
			Logger::debugSql($queryParams);
		} // end if App::get('logSql')

		//	Execute statement
		$statement = $this->conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$statement->execute($queryParams);

		//	Disconnect
		$this->conn = null;
	} // end insert

	/**
	 * Updates records in the database
	 * @param string $tableName the name of the table
	 * @param array $params the column name -> value for parameters
	 * @param array $where the column name -> value for where clause
	 */
	function update( $tableName, $params, $where) {

		//	remove the wheres from params
		foreach ( array_keys($where) as $whereKey ) {
			if ( array_key_exists($whereKey, $params) ) {
				unset($params[$whereKey]);
			}
		}

		//	Connect
		$this->connect();

		//	$sql will hold the sql statements
		$sql = "UPDATE $tableName SET ";

		//	isInit indicates if is the first column,
		//	to handle commas
		$isInit = TRUE;

		//	New array with the ":" at the beginning
		//	and continuation of the update statement
		//	Loop the keys and add the column Names for values
		foreach ( array_keys( $params ) as $colName ) {
			if ( $isInit ) {
				$isInit = FALSE;
				$sql .= "$colName = :$colName";
			}
			else {
				$sql .= ", $colName = :$colName";
			}

			$queryParams[":$colName"] = $params[$colName];
		}

		if ( $where != null ) {
			$sql .= " WHERE ";

			//	Loop for the where clause
			//	again isInit
			$isInit = TRUE;

			foreach ( array_keys( $where ) as $colName ) {
				if ( $isInit ) {
					$isInit = FALSE;
					$sql .= " $colName = :$colName ";
				}
				else {
					$sql .= " AND $colName = :$colName ";
				}

				//	Same array as data to update
				$queryParams[":$colName"] = $where[$colName];
			} // end foreach
		} // end if

		$sql .= ";";

		//	If logSql, then log Sql
		if ( App::get('logSql') ) {
			Logger::debugSql($sql);
			Logger::debugSql($queryParams);
		} // end if App::get('logSql')

		//	Execute statement
		$statement = $this->conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$statement->execute($queryParams);

		//	Disconnect
		$this->conn = null;
	} // end update

	function distinctArray($array) {
		$new = array();
		foreach ( array_keys($array) as $key ) {
			if ( !array_key_exists($key, $new)) {
				$new[$key] = $array[$key];
			}
		}
		return $new;
	}
	/**
	 *
	 * @param string $tableName The name of the table
	 * @param array $params The key value par of columns for insert / update
	 * @param array $where The key value par of columns for where clause. NULL if none.
	 */
	function upsert($tableName, $params, $where) {

		//	remove nulls
		foreach ( array_keys($params) as $key ) {
			if ( $params[$key] == null &&
					! is_numeric($params[$key]) &&
					$params[$key] !== 0 ) {
				unset( $params[$key] );
			}
		}

		foreach ( array_keys($where) as $key ) {

			if ( $where[$key] == null &&
					! is_numeric($where[$key]) ) {
				unset( $where[$key] );
			}
		}

		//	Connect
		$this->connect();

		if ($where == NULL) {

			return $this->insert($tableName, $params);
		} else {
			$sql = "SELECT COUNT(*) FROM $tableName WHERE ";
			//	Loop for the where clause
			//	we assume all AND because is restrictive to the primary key
			//	isInit for first item
			$isInit = TRUE;


			foreach ( array_keys( $where ) as $colName ) {
				if ( $isInit ) {
					$isInit = FALSE;
					$sql .= " $colName = :$colName ";
				}
				else {
					$sql .= " AND $colName = :$colName ";
				}

				//	Same array as data to update
				$queryParams[":$colName"] = $where[$colName];
			} // end foreach

			//	Execute statement
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$statement = $this->conn->prepare($sql);
			$statement->execute($queryParams);
			$result = $statement->fetchColumn();

			//	If 0 the is insert, else is update
			if ( $result == 0 ) {

				$this->insert(
					$tableName,
					$this->distinctArray(
						array_merge(
							$params,
							$where
						)
					)
				);
			}
			else {

				return $this->update($tableName, $params, $where);
			} // end else

		} // end else
	} // end upsert


	/**
	 * Deletes records on the database
	 * @param string $tableName The name of the table
	 * @param array $where The where clause column name -> values array
	 */
	function delete($tableName, $where) {
		//	Connect
		$this->connect();

		//	$sql will store the sql statements
		$sql = "DELETE FROM $tableName WHERE ";

		//	isInit indicates if is the first column,
		//	to handle commas
		$isInit = TRUE;

		//	Loop the keys and add the column Names for values
		foreach ( array_keys( $where ) as $colName ) {
			if ( $isInit ) {
				$isInit = false;
				$sql .= " $colName = :$colName ";
			}
			else {
				$sql .= " AND $colName = :$colName ";
			}

			$queryParams[":$colName"] = $where[$colName];
		}

		$sql .= ";";

		//	Execute statement
		$statement = $this->conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

		//	If logSql, then log Sql
		if ( App::get('logSql') ) {
			Logger::debugSql($sql);
			Logger::debugSql($queryParams);
		} // end if App::get('logSql')

		$statement->execute($queryParams);

		//	Disconnect
		$this->conn = null;
	} // end delete

	/**
	 * Deletes all rows in a tbale
	 * @param string $tableName The name of the table
	 */
	function truncate($tableName) {
		//	Connect
		$this->connect();

		// $sql will holde the sql statements
		$sql = "DELETE FROM $tableName;";

		//	Execute statement
		$statement = $this->conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$statement->execute($queryParams);

		//	Disconnect
		$this->conn = null;
	} // end truncate

	/**
	 * Query a specific table the database. Returns a bidimensional array as resultset
	 * @param string $sql The query, with named params format ":paramName"
	 * @param array $params The actual key value pair collection of params
	 * @param string $className (optional) The name of the class to return
	 * @return assoc_array
	 */
	function queryTable($tableName, $queryParams = NULL, $className = NULL) {

		//	remove nulls
		if ( $queryParams != null ) {
			foreach ( array_keys($queryParams) as $key ) {
				if ( $queryParams[$key] == null &&
						! is_numeric($queryParams[$key]) &&
						$queryParams[$key] !== 0 ) {
					unset( $queryParams[$key] );
				}
			} // end foreach key
		} // end if $queryParams is null

		$where = "";
		if ( $queryParams != null ) {
			foreach ( array_keys($queryParams) as $key ) {
				if ( $where == "") {
					$where .= " WHERE " . str_replace(":", "", $key) . " = $key ";
				} else {
					$where .= " AND " . str_replace(":", "", $key) . " = $key ";
				}
			}
		}

		$sql = "SELECT * from $tableName $where";

		//	Connect
		$this->connect();

		//	Prepare
		$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$statement = $this->conn->prepare($sql);

		//	If logSql, then log Sql
		if ( App::get('logSql') ) {
			Logger::debugSql($sql);
			Logger::debugSql($queryParams);
		} // end if App::get('logSql')

		//	Desicion if params
		if ( $queryParams != null ) {
			$statement->execute($queryParams);
		} else {
			$statement->execute();
		}

		if ( $className != NULL) {
			$resultset = $statement->fetchAll(PDO::FETCH_CLASS, $className);
		} else {

			if ( $this->returnClassName !== null ) {
				$resultset = $statement->fetchAll(PDO::FETCH_CLASS, $this->returnClassName);
			} else {
				//	The result set to return
				$resultset = array();

				//	Loop throw rows and create an array
				//	assign the array to the resultset
				foreach($statement->fetchAll(PDO::FETCH_ASSOC) as $v) {
					$result = array();
					foreach ( array_keys( $v ) as $colName ) {
						$result[$colName] = $v[$colName];
					}
					$resultset[] = $result;
				}
			}
		}

		//	Disconnect
		$this->conn = null;
		$this->clear();

		//	Return the resultset
		return $resultset;
	} // end query

	/**
	 * Query the database. Returns a bidimensional array as resultset
	 * @param string $sql The query, with named params format ":paramName"
	 * @param array $params The actual key value pair collection of params
	 * @param string $className (optional) The name of the class to return
	 * @return assoc_array
	 */
	function query($sql, $queryParams = NULL, $className = NULL) {

		//	remove nulls
		if ( $queryParams != null ) {
			foreach ( array_keys($queryParams) as $key ) {
				if ( $queryParams[$key] == null &&
						! is_numeric($queryParams[$key]) &&
						$queryParams[$key] !== 0 ) {
					unset( $queryParams[$key] );
				}
			} // end foreach key
		} // end if $queryParams is null

		//	Connect
		$this->connect();

		//	Prepare
		$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$statement = $this->conn->prepare($sql);

		//	If logSql, then log Sql
		if ( App::get('logSql') ) {
			Logger::debugSql($sql);
			Logger::debugSql($queryParams);
		} // end if App::get('logSql')

		//	Desicion if params
		if ( $queryParams != null ) {
			$statement->execute($queryParams);
		} else {
			$statement->execute();
		}

		if ( $className !== NULL) {
			$resultset = $statement->fetchAll(PDO::FETCH_CLASS, $className);
		} else {

			if ( $this->returnClassName !== null ) {
				$resultset = $statement->fetchAll(PDO::FETCH_CLASS, $this->returnClassName);
			} else {
				//	The result set to return
				$resultset = array();

				//	Loop throw rows and create an array
				//	assign the array to the resultset
				foreach($statement->fetchAll(PDO::FETCH_ASSOC) as $v) {
					$result = array();
					foreach ( array_keys( $v ) as $colName ) {
						$result[$colName] = $v[$colName];
					}
					$resultset[] = $result;
				}
			}
		}

		//	Disconnect
		$this->conn = null;
		$this->clear();

		//	Return the resultset
		return $resultset;
	} // end query

	/**
	 * Query the database, return scalar value
	 * @param string $sql The query, with named params format ":paramName"
	 * @param array $params The actual key value pair collection of params
	 * @return object
	 */
	function scalar($sql, $queryParams) {

		//	remove nulls
		if ( $queryParams != null ) {
			foreach ( array_keys($queryParams) as $key ) {
				if ( $queryParams[$key] == null &&
						! is_numeric($queryParams[$key]) &&
						$queryParams[$key] !== 0 ) {
					unset( $queryParams[$key] );
				}
			} // end foreach key
		} // end if $queryParams is null

		//	Connect
		$this->connect();

		//	If logSql, then log Sql
		if ( App::get('logSql') ) {
			Logger::debugSql($sql);
			Logger::debugSql($queryParams);
		} // end if App::get('logSql')

		//	Prepare
		$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$statement = $this->conn->prepare($sql);

		//	Desicion if params
		if ( $queryParams != null ) {
			$statement->execute($queryParams);
		} else {
			$statement->execute();
		}

		$result = $statement->fetchColumn();
		$this->conn = null;
		$this->clear();

		return $result;
	} // end query

	/**
	 * Query the database, execute DML statements
	 * @param string $sql The query, with named params format ":paramName"
	 * @param array $params The actual key value pair collection of params
	 * @return assoc_array
	 */
	function nonQuery($sql, $queryParams) {

		//	remove nulls
		if ( $queryParams != null ) {
			foreach ( array_keys($queryParams) as $key ) {
				if ( $queryParams[$key] == null &&
						! is_numeric($queryParams[$key]) &&
						$queryParams[$key] !== 0 ) {
					unset( $queryParams[$key] );
				}
			} // end foreach key
		} // end if $queryParams is null

		//	Connect
		$this->connect();

		//	If logSql, then log Sql
		if ( App::get('logSql') ) {
			Logger::debugSql($sql);
			Logger::debugSql($queryParams);
		} // end if App::get('logSql')

		//	Prepare
		$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$statement = $this->conn->prepare($sql);

		//	Desicion if params
		if ( $queryParams != null ) {
			$statement->execute($queryParams);
		} else {
			$statement->execute();
		}

		$this->conn = null;
		$this->clear();

		return TRUE;
	} // end query

	/**
	 * Returns the las id inserted to a table
	 * @param string $tableName
	 * @param string $idColName
	 * @param array $where
	 * @return object
	 */
	function getLastId($tableName, $idColName, $where) {
		$this->where( $where );
		return $this->max( $idConName, $tableName );
	}

	/**
	 * Same as where
	 * @param string or array $columnName
	 * @param string $value
	 * @return DbHelper
	 */
	function andWhere($columnName, $value = NULL) {
		return $this->where($columnName, $value);
	} // end function andWhere

	/**
	 * Sets an WHERE statement
	 * @param string $columnName The column name, alternatively, an assoc array
	 * @param string $value
	 * @return DbHelper
	 */
	function where($columnName, $value = NULL) {

		if ( $value == NULL && is_array($columnName) ) {

			foreach ( array_keys( $columnName ) as $colName ) {

				if ( is_null($columnName[$colName]) &&
					! is_numeric($columnName[$colName]) ) {

					unset( $columnName[$colName] );

				} else {
					$cont = count($this->whereParams) + 1;
					$paramName = str_replace(".", "_", $colName) . $cont;
					$this->whereParams[$paramName] = $columnName[$colName];

					if ( empty($this->whereStatement) ) {
						$this->whereStatement = "$colName = :$paramName";
					}
					else {
						$this->whereStatement .= " AND $colName = :$paramName";
					}
				}
			}

			return $this;
		}

		if ( $value == null && $columnName ) {
			if ( empty($this->whereStatement) ) {
				$this->whereStatement = " $columnName ";
			}
			else {
				$this->whereStatement .= " AND $columnName ";
			}
		} else {
			$cont = count($this->whereParams) + 1;
			$paramName = str_replace(".", "_", $columnName) . $cont;
			$this->whereParams[$paramName] = $value;

			if ( empty($this->whereStatement) ) {
				$this->whereStatement = "$columnName = :$paramName";
			}
			else {
				$this->whereStatement .= " AND $columnName = :$paramName";
			}
		} // end if value == null & columnName

		//	Returns this same instance
		return $this;
	}

	/**
	 * Sets and OR statement
	 * @param string $columnName
	 * @param object $value
	 * @return DbHelper
	 */
	function orWhere($columnName, $value) {

		if ( $value == null && $columnName ) {
			if ( empty($this->whereStatement) ) {
				$this->whereStatement = " $columnName ";
			}
			else {
				$this->whereStatement .= " OR $columnName ";
			}
		} else {
			$cont = count($this->whereParams) + 1;
			$paramName = str_replace(".", "_", $columnName) . $cont;
			$this->whereParams[$paramName] = $value;

			if ( empty($this->whereStatement) ) {
				$this->whereStatement = "$columnName = :$paramName";
			}
			else {
				$this->whereStatement .= " OR $columnName = :$paramName";
			}
		} // end if value == null & columnName

		//	Returns this same instance
		return $this;
	} // end function orWhere

	/**
	 * Sets an LIKE statement on an AND clause
	 * @param string $columnName
	 * @param string $value
	 * @return DbHelper
	 */
	function like($columnName, $value) {

		$cont = count($this->whereParams) + 1;
		$paramName = str_replace(".", "_", $columnName) . $cont;
		$this->whereParams[$paramName] = '%' . strtoupper($value) . '%';;

		if ( empty($this->whereStatement) ) {
			$this->whereStatement = "UPPER($columnName) LIKE :$paramName";
		}
		else {
			$this->whereStatement .= " AND UPPER($columnName) LIKE :$paramName";
		}

		//	Returns this same instance
		return $this;
	}

	/**
	 * Sets a like statement on an OR clause
	 * @param string $columnName
	 * @param object $value
	 */
	function orLike($columnName, $value) {
		$cont = count($this->whereParams) + 1;
		$paramName = str_replace(".", "_", $columnName) . $cont;
		$this->whereParams[$paramName] = '%' . $value . '%';;

		if ( empty($this->whereStatement) ) {
			$this->whereStatement = "$columnName LIKE :$paramName";
		}
		else {
			$this->whereStatement .= " OR $columnName LIKE :$paramName";
		}

		//	Returns this same instance
		return $this;

		//	Returns this same instance
		return $this;
	}

	/**
	 * Set the table to query from
	 * @param string $tableName The name of the table
	 * @return DbHelper
	 */
	function from($tableName) {
		$this->from = $tableName;

		//	Returns this same instance
		return $this;
	}

	/**
	 * Adds columns to the select statement
	 * @param string $column The column, or list of columns comma separated
	 */
	function select($column) {

		if ( empty($this->selectStatement) ) {
			$this->selectStatement = $column;
		}
		else {
			$this->selectStatement .= ", $column";
		}

		//	Returns this same instance
		return $this;
	}

	/**
	 * Adds an order by statement to the current select statement
	 * @param string $column
	 * @param string $ascDesc
	 * @return DbHelper
	 */
	function orderBy($column, $ascDesc = NULL) {
		if ( empty($this->orderByStatement) ) {
			$this->orderByStatement = $column . ($ascDesc != NULL ? " $ascDesc" : "");
		}
		else {
			$this->orderByStatement .= ", $column" . ($ascDesc != NULL ? " $ascDesc" : "");
		}

		//	Returns this same instance
		return $this;
	}

	function first($tableName = NULL, $className = NULL) {

		$result = $this->get($tableName, $className);

		if ( count($result) > 0 ) {
			return $result[0];
		} else {
			return null;
		}
	}

	/**
	 * Returns the Sql Statement
	 */
	function getSql( $tableName = NULL ) {
		$sql = "";

		if ( $tableName != NULL) {
			$this->from = $tableName;
		}
		else {
			if ( empty($this->from) ) {
				throw new Exception("From table not specified!");
			}
		}

		if ( empty($this->selectStatement) ) {
			$this->selectStatement = " * ";
		}

		$sql = "SELECT ";
		if ( $this->limitType == 2 )
			$sql .= $this->limitStatement;

		$sql .= $this->selectStatement;
		$sql .= " FROM " . $this->from;

		if ( !empty($this->joinStatement) ) {
			$sql .= $this->joinStatement;
		}

		if ( !empty($this->whereStatement) ) {
			$sql .= " WHERE " . $this->whereStatement;
		}

		if ( !empty($this->orderByStatement) ) {
			$sql .= " ORDER BY " . $this->orderByStatement;
		}

		if ( $this->limitType == 1 )
			$sql .= $this->limitStatement;

		if ( $this->limitType == 3 ) {
			$sql = str_replace( 'SELECT', '', $sql);
			$sql = str_replce('@selectStatement', $sql, $this->limitStatement);
			if ( $this->orderByStatement ) {
				$sql = str_replce('@orderByStatement', $this->orderByStatement, $sql);
			} else {
				if ( $this->from ) {
					$orderBy = $this->getIdentityColumn( $this->from );
					if ( $orderBy ) {

					} else {
						$primaryKeys = $this->getPrimaryKeys( $this->from );
						$orderBy = $primaryKeys[0]['column_name'];
					}
				} else {
					throw new Exception( "Statement has no from" );
				} // end if from
				$sql = str_replce('@orderByStatement', " ORDER BY $orderBy ", $sql);
			} // end if orderByStatement
		} // end if limitType 3

		return $sql;
	} // end function getSql

	/**
	 * Performs a query to the database, constructed by the class
	 * @param string $tableName The name of the table to query
	 * @throws Exception
	 */
	function get($tableName = NULL, $className = NULL) {

		$sql = $this->getSql( $tableName );

		$queryParams = NULL;
		if ( !empty($this->whereStatement) ) {
			$queryParams = $this->whereParams;
		}

		//	Returns a resultset
		return $this->query($sql, $queryParams, $className);
	}

	/**
	 * Performs a query to the database, constructed by the class
	 * and returns a scalar value
	 * @param string $tableName The name of the table to query
	 * @throws Exception
	 */
	function getScalar($tableName = NULL) {

		$sql = "";

		if ( $tableName != NULL) {
			$this->from = $tableName;
		}
		else {
			if ( empty($this->from) ) {
				throw new Exception("From table not specified!");
			}
		}

		if ( empty($this->selectStatement) ) {
			$this->selectStatement = " * ";
		}

		$sql = "SELECT " . $this->selectStatement;
		$sql .= " FROM " . $this->from;

		if ( !empty($this->joinStatement) ) {
			$sql .= $this->joinStatement;
		}

		if ( !empty($this->whereStatement) ) {
			$sql .= " WHERE " . $this->whereStatement;
		}

		if ( !empty($this->orderByStatement) ) {
			$sql .= " ORDER BY " . $this->orderByStatement;
		}

		$queryParams = NULL;
		if ( !empty($this->whereStatement) ) {
			$queryParams = $this->whereParams;
		}

		//	Returns a resultset
		return $this->scalar($sql, $queryParams);
	}

	/**
	 * Performs a query count to the database, constructed by the class
	 * @param string $tableName The name of the table to query
	 * @throws Exception
	 */
	function count($tableName = NULL) {

		$sql = "";

		if ( $tableName != NULL) {
			$this->from = $tableName;
		}
		else {
			if ( empty($this->from) ) {
				throw new Exception("From table not specified!");
			}
		}

		$sql = "SELECT COUNT(*) ";
		$sql .= " FROM " . $this->from;

		if ( !empty($this->joinStatement) ) {
			$sql .= $this->joinStatement;
		}

		if ( !empty($this->whereStatement) ) {
			$sql .= " WHERE " . $this->whereStatement;
		}

		$queryParams = NULL;
		if ( !empty($this->whereStatement) ) {
			$queryParams = $this->whereParams;
		}

		//	Returns a resultset
		return $this->scalar($sql, $queryParams);
	}

	/**
	 * Performs a query count to the database, constructed by the class
	 * @param string $tableName The name of the table to query
	 * @throws Exception
	 */
	function max($colName, $tableName = NULL) {

		$sql = "";

		if ( $tableName != NULL) {
			$this->from = $tableName;
		}
		else {
			if ( empty($this->from) ) {
				throw new Exception("From table not specified!");
			}
		}

		$sql = "SELECT MAX($colName) ";
		$sql .= " FROM " . $this->from;

		if ( !empty($this->joinStatement) ) {
			$sql .= $this->joinStatement;
		}

		if ( !empty($this->whereStatement) ) {
			$sql .= " WHERE " . $this->whereStatement;
		}

		$queryParams = NULL;
		if ( !empty($this->whereStatement) ) {
			$queryParams = $this->whereParams;
		}

		//	Returns a resultset
		return $this->scalar($sql, $queryParams);
	}

	function exists($tableName = NULL) {

		$count = $this->count($tableName);

		if ( $count > 0 )
			return TRUE;
		else
			return FALSE;
	}

	function andOn($leftField, $operator, $rightField) {
		if ( !empty($this->joinStatement) ) {
			$this->joinStatement .= " AND $leftField $operator $rightField ";
		}

		//	Returns this same instance
		return $this;
	}

	function join($tableName, $leftField, $operator, $rightField) {

		if ( empty($this->joinStatement) ) {
			$this->joinStatement = " JOIN $tableName ON $leftField $operator $rightField ";
		}
		else {
			$this->joinStatement .= " JOIN $tableName ON $leftField $operator $rightField ";
		}

		//	Returns this same instance
		return $this;
	}

	function leftJoin($tableName, $leftField, $operator, $rightField) {

		if ( empty($this->joinStatement) ) {
			$this->joinStatement = " LEFT JOIN $tableName ON $leftField $operator $rightField ";
		}
		else {
			$this->joinStatement .= " LEFT JOIN $tableName ON $leftField $operator $rightField ";
		}

		//	Returns this same instance
		return $this;
	}

	function rightJoin($tableName, $leftField, $operator, $rightField) {

		if ( empty($this->joinStatement) ) {
			$this->joinStatement = " RIGHT JOIN $tableName ON $leftField $operator $rightField ";
		}
		else {
			$this->joinStatement .= " RIGHT JOIN $tableName ON $leftField $operator $rightField ";
		}

		//	Returns this same instance
		return $this;
	}

	function crossJoin($tableName) {

		if ( empty($this->joinStatement) ) {
			$this->joinStatement = " LEFT JOIN $tableName ";
		}
		else {
			$this->joinStatement .= " LEFT JOIN $tableName ";
		}

		//	Returns this same instance
		return $this;
	}

	/**
	 * Sets an "TOP" or "LIMIT" clause
	 * @param int $limit
	 * @param int $startAt
	 */
	function top($limit, $startAt = null) {
		switch($this->dbConn->driver) {
			case "mysql":
				$this->limitType = 1;
				if ( $startAt ) {
					$this->limitStatement = " LIMIT $startAt, $limit ";
				} else {
					$this->limitStatement = " LIMIT $limit ";
				}
				break;
			case "pgsql":
				$this->limitType = 1;
				$this->limitStatement = " LIMIT $limit ";
				if ( $startAt != null )
					$this->limitStatement .= " offset $startAt";
				break;
			case "sqlsrv":
				$this->limitType = 2;
				if ( $startAt ) {
					$this->limitType = 3;
					$this->limitStatement = "SELECT
							TOP $limit
							*
						FROM
							(
								SELECT
									ROW_NUMBER() OVER ( @orderByStatement ) AS ROW,
									@selectStatement
							) AS A
						WHERE
							A.ROW > $startAt";
				} else {
					$this->limitStatement = " TOP ( $limit ) ";
				}
				break;
			case "dblib":
					$this->limitType = 2;
					$this->limitStatement = " TOP ($limit) ";
					break;
		}

		return $this;
	} // end function top


	function paginateQuery($pageItems, $page, $query, $queryParams) {

		$sql = "SELECT COUNT(*) FROM ($query) AS A;";
		$clone = clone $this;
		$count = $this->scalar( $sql, $queryParams );

		//	Then, get the pages
		$pagesCount = ceil( $count / $pageItems );

		//	Then the start at
		$startAt = ($page - 1) * $pageItems;

		$sql = $this
			->select("A.*")
			->from("($query) AS A")
			->top($pageItems, $startAt)
			->getSql();

		$results = $this->query( $sql, $queryParams );

		$result['results'] = $results;

		//	Get the pages
		for( $i=1; $i<=$pagesCount; $i++ ) {
			$pages[] = $i;
		}

		$result['pages'] = $pages;
		$result['currentPage'] = $page;

		return $result;
	} // end function paginateQuery

	/**
	 * Paginates a result set, use instead of get
	 * @param $pageItems Number of items per page
	 * @param $page The page number, from 1 to N
	 */
	function paginate($pageItems, $page, $tableName = NULL, $className = NULL) {
		//	First, get the count
		$clone = clone $this;
		$count = $clone->count();

		//	Then, get the pages
		$pagesCount = ceil( $count / $pageItems );

		//	Then the start at
		$startAt = ($page - 1) * $pageItems;

		//	Then set the top
		$this->top($pageItems, $startAt);

		//	Get the results
		$results = $this->get($tableName, $className);
		$result['results'] = $results;

		//	Get the pages
		for( $i=1; $i<=$pagesCount; $i++ ) {
			$pages[] = $i;
		}

		$result['pages'] = $pages;
		$result['currentPage'] = $page;

		return $result;

	} // en function paginate

	function getColumns( $tableName ) {
		$sql = "";

		switch ($this->dbConn->driver) {
			case 'pgsql':
				$sql = "SELECT
								  ORDINAL_POSITION,
								  COLUMN_NAME,
								  DATA_TYPE,
								  CHARACTER_MAXIMUM_LENGTH,
								  IS_NULLABLE
								FROM
								  INFORMATION_SCHEMA.COLUMNS
								WHERE
									TABLE_CATALOG = :databaseName
									AND
										TABLE_NAME = :tableName";
				break;

			case 'mysql':
				$sql = "SELECT
								  ORDINAL_POSITION,
								  COLUMN_NAME,
								  DATA_TYPE,
								  CHARACTER_MAXIMUM_LENGTH,
								  IS_NULLABLE
								FROM
								  INFORMATION_SCHEMA.COLUMNS
								WHERE
									TABLE_SCHEMA = :databaseName
									AND
										TABLE_NAME = :tableName";
				break;

			case 'sqlsrv':
				$sql = "SELECT
								  ORDINAL_POSITION,
								  COLUMN_NAME,
								  DATA_TYPE,
								  CHARACTER_MAXIMUM_LENGTH,
								  IS_NULLABLE
								FROM
								  INFORMATION_SCHEMA.COLUMNS
								WHERE
									TABLE_CATALOG = :databaseName
									AND
										TABLE_NAME = :tableName";
				break;

			case 'dblib':
				$sql = "SELECT
								  ORDINAL_POSITION,
								  COLUMN_NAME,
								  DATA_TYPE,
								  CHARACTER_MAXIMUM_LENGTH,
								  IS_NULLABLE
								FROM
								  INFORMATION_SCHEMA.COLUMNS
								WHERE
									TABLE_CATALOG = :databaseName
									AND
										TABLE_NAME = :tableName";
				break;

			default:
				# code...
				break;
		} // end switch

		$queryParams['tableName'] = $tableName;
		$queryParams['databaseName'] = $this->dbConn->databaseName;
		$result = $this->query($sql, $queryParams);
		return $result;

	} // end function getProperties


	function getPublicProperties( $tableName ) {
		$sql = "";

		switch ($this->dbConn->driver) {
			case 'pgsql':
				$sql = "SELECT 'public $' || column_name || ';' AS property
								FROM information_schema.columns
								WHERE table_catalog = :databaseName
								  AND table_name   = :tableName";
				break;

			case 'mysql':
				$sql = "SELECT
									CONCAT('public $',COLUMN_NAME,';') AS property
								FROM
									INFORMATION_SCHEMA.COLUMNS
								WHERE
									TABLE_SCHEMA = :databaseName
									AND
										TABLE_NAME = :tableName";
				break;

			case 'sqlsrv':
				$sql = "SELECT
									'public $' + COLUMN_NAME + ';' AS property
								FROM
									INFORMATION_SCHEMA.COLUMNS
								WHERE
									TABLE_CATALOG = :databaseName
									AND
										TABLE_NAME = :tableName";
				break;

			case 'dblib':
				$sql = "SELECT
									'public $' + COLUMN_NAME + ';' AS property
								FROM
									INFORMATION_SCHEMA.COLUMNS
								WHERE
									TABLE_CATALOG = :databaseName
									AND
										TABLE_NAME = :tableName";
				break;

			default:
				# code...
				break;
		} // end switch

		$queryParams['tableName'] = $tableName;
		$queryParams['databaseName'] = $this->dbConn->databaseName;
		$result = $this->query($sql, $queryParams);

		$publicProperties = '';
		foreach ( $result as $row ) {
			$publicProperties .= "\t" . $row['property'] . PHP_EOL;
		} // end foreach

		return $publicProperties;

	} // end function getProperties

	/**
	 *	Return the identity column of the table if any
	 */
	function getIdentityColumn( $tableName ) {
		$sql = "";
		switch ($this->dbConn->driver) {
			case 'pgsql':
				$sql = "SELECT column_name as property
			FROM information_schema.columns
			WHERE table_catalog = :databaseName
			AND table_name   = :tableName
			AND column_default LIKE '%nextval%'";
				break;

			case 'mysql':
				$sql = "SELECT
									COLUMN_NAME
								FROM
									INFORMATION_SCHEMA.`COLUMNS`
								WHERE
									TABLE_NAME = :tableName
									AND
										TABLE_SCHEMA = :databaseName
									AND
										EXTRA = 'auto_increment';";
				break;

			case 'sqlsrv':
				$sql = "SELECT
									COLUMN_NAME
								FROM
									INFORMATION_SCHEMA.COLUMNS
								WHERE
									COLUMNPROPERTY(object_id(TABLE_NAME), COLUMN_NAME, 'IsIdentity') = 1
									AND TABLE_NAME = :tableName
									AND TABLE_CATALOG = :datebaseName
								ORDER BY
									TABLE_NAME";
			break;

			case 'sqlsrv':
				$sql = "SELECT
									COLUMN_NAME
								FROM
									INFORMATION_SCHEMA.COLUMNS
								WHERE
									COLUMNPROPERTY(object_id(TABLE_NAME), COLUMN_NAME, 'IsIdentity') = 1
									AND TABLE_NAME = :tableName
									AND TABLE_CATALOG = :datebaseName
								ORDER BY
									TABLE_NAME";
			break;

			default:
				# code...
				break;
		} // end switch

		$queryParams['tableName'] = $tableName;
		$queryParams['databaseName'] = $this->dbConn->databaseName;
 		$result = $this->scalar($sql, $queryParams);

		return $result;
	} // end function getIdentityColumn

	/**
	 * Returns a resultset with the primary keys
	 */
	function getPrimaryKeys( $tableName ) {

		$sql = "";
		switch ($this->dbConn->driver) {
			case 'pgsql':
				$sql = "SELECT  kcu.column_name
		FROM    INFORMATION_SCHEMA.TABLES t
		         LEFT JOIN INFORMATION_SCHEMA.TABLE_CONSTRAINTS tc
		                 ON tc.table_catalog = t.table_catalog
		                 AND tc.table_schema = t.table_schema
		                 AND tc.table_name = t.table_name
		                 AND tc.constraint_type = 'PRIMARY KEY'
		         LEFT JOIN INFORMATION_SCHEMA.KEY_COLUMN_USAGE kcu
		                 ON kcu.table_catalog = tc.table_catalog
		                 AND kcu.table_schema = tc.table_schema
		                 AND kcu.table_name = tc.table_name
		                 AND kcu.constraint_name = tc.constraint_name
		WHERE   t.table_catalog = :databaseName
		AND t.table_name = :tableName
		ORDER BY t.table_catalog,
		         t.table_schema,
		         t.table_name,
		         kcu.constraint_name,
		         kcu.ordinal_position";
				break;

			case 'mysql':
				$sql = "SELECT
									KCU.COLUMN_NAME
								FROM
									INFORMATION_SCHEMA.TABLE_CONSTRAINTS AS TC
								INNER JOIN
									INFORMATION_SCHEMA.KEY_COLUMN_USAGE AS KCU
								ON
									KCU.TABLE_NAME = TC.TABLE_NAME
									AND
										KCU.TABLE_SCHEMA = TC.TABLE_SCHEMA
									AND
										KCU.CONSTRAINT_NAME = TC.CONSTRAINT_NAME
								WHERE
									TC.TABLE_NAME = :tableName
									AND
										TC.CONSTRAINT_TYPE = 'PRIMARY KEY'
									AND
										TC.TABLE_SCHEMA = :databaseName
								ORDER BY
									KCU.ORDINAL_POSITION";
				break;

			case 'sqlsrv':
				$sql = "SELECT
									KCU.COLUMN_NAME
								FROM
									INFORMATION_SCHEMA.TABLE_CONSTRAINTS AS TC
								INNER JOIN
									INFORMATION_SCHEMA.KEY_COLUMN_USAGE AS KCU
								ON
									KCU.TABLE_NAME = TC.TABLE_NAME
									AND
										KCU.TABLE_CATALOG = TC.TABLE_CATALOG
									AND
										KCU.CONSTRAINT_NAME = TC.CONSTRAINT_NAME
								WHERE
									TC.TABLE_NAME = :tableName
									AND
										TC.CONSTRAINT_TYPE = 'PRIMARY KEY'
									AND
										TC.TABLE_CATALOG = :databaseName
								ORDER BY
									KCU.ORDINAL_POSITION";
				break;

			case 'dblib':
				$sql = "SELECT
									KCU.COLUMN_NAME
								FROM
									INFORMATION_SCHEMA.TABLE_CONSTRAINTS AS TC
								INNER JOIN
									INFORMATION_SCHEMA.KEY_COLUMN_USAGE AS KCU
								ON
									KCU.TABLE_NAME = TC.TABLE_NAME
									AND
										KCU.TABLE_CATALOG = TC.TABLE_CATALOG
									AND
										KCU.CONSTRAINT_NAME = TC.CONSTRAINT_NAME
								WHERE
									TC.TABLE_NAME = :tableName
									AND
										TC.CONSTRAINT_TYPE = 'PRIMARY KEY'
									AND
										TC.TABLE_CATALOG = :databaseName
								ORDER BY
									KCU.ORDINAL_POSITION";
				break;

			default:
				//	The default is pgsql
				break;
		} // end switch

		$queryParams['tableName'] = $tableName;
		$queryParams['databaseName'] = $this->dbConn->databaseName;

 		$result = $this->query($sql, $queryParams);

		return $result;

	} // end function getPrimaryKeys

	/**
	 * Returns an filtered array
	 * @param assoc_array $resultSet
	 * @param string $itemKey
	 * @param variable $itemValue
	 */
	static function resultSetFilter( $resultSet, $itemKey, $itemValue ) {
		$result = array();
		if ( $resultSet ) {
			foreach( $resultSet as $row ) {
				if ( isset( $row[$itemKey] ) ) {
					if ( $row[$itemKey] === $itemValue ) {
						$result[] = $row;
					} // end if = value
				} // end if isset
			} // end foreach
		}

		return $result;
	} // end function resultSetFilter

	/**
	 * Updates a resultset
	 */
	static function updateResultSet( &$resultSet, $row, $keys ) {

		$idx = null;
		foreach( $resultSet as $index => $rs ) {
			$cond = true;
			foreach ( $keys as $key ) {
				if ( $rs[$key] !== $row[$key] ) {
					$cond = false;
				}
			} // end foreach $key
			if ( $cond ) {
				$idx = $index;
				break;
			}
		} // end foreach resultset

		if ( $idx ) {
			unset($resultSet[$idx]);
			$resultSet[] = $row;
			return true;
		} // end if $idx

		return false;
	} // end function updateResultSet

	/**
	 * Returns the index for a key value pair
	 */
	static function resultSetIndex( $resultSet, $itemKey, $itemValue ) {
		if ( $resultSet ) {
			foreach( $resultSet as $index => $row ) {
				if ( $row[$itemKey] === $itemValue ) {
					return $index;
				} // end if = value
			} // end foreach
		} // end if resultSet

		return null;
	} // end function resultSetIndex

	static function deleteFromResultSet( $resultSet, $params ) {
		$indexes = null;
		foreach( $resultSet as $index => $rs ) {

			$paramCount = count( $params );
			$count = 0;
			foreach ( array_keys( $params ) as $key ) {
				if ( $rs[$key] == $params[$key] ) {
					$count++;
				}
			} // end foreach $key
			if ( $paramCount === $count ) {
				$indexes[] = $index;
				break;
			}
		} // end foreach resultset

		if ( $indexes ) {
			foreach( $indexes as $index ) {
				unset( $resultSet[ $index ] );
			} // end foreach
		} // end if $idx

		return $resultSet;
	} // deleteFromResultSet
} // end class DbHelper