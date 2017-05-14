<?php
/**
 * This class contains functions for RS framework behavior
 */
class RS {

  static $sapi;
  static $baseUrl;
  static $url;
  static $method;
  static $version = '1.0';

  function __construct() {
  } // end function __construct

  static function startUp() {

    $sapi = php_sapi_name();
    if ( $sapi != 'cli' ) {
      //	Gets the protocol
      if ( isset( $_SERVER['HTTPS'] ) ) {
        $protocol = $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://';
      } else if ( isset( $_SERVER['SERVER_PROTOCOL'] ) ) {
        $protocol = 'http://';
      }

      //	This is the path to index
      $indexPath = '/public/index.php';

      $scriptUrl = $_SERVER['PHP_SELF'];

      //	Base url, without "public", use for controller references
      $baseUrl = $protocol . $_SERVER['HTTP_HOST']. $scriptUrl;
      $baseUrl = str_replace($indexPath, '', $baseUrl);

      if ( strpos( $baseUrl, '.php' ) !== false ) {
        $baseUrl = explode( '/', $baseUrl );
        array_pop( $baseUrl );
        $baseUrl = implode( '/', $baseUrl );
      }

      define('BASE_URL', $baseUrl); // directory or domain web accesible

      //	This is the directory in wich the app is hosted, within the server
      if ( $scriptUrl != $indexPath ) {
        $directory = str_replace($indexPath, '', $_SERVER['PHP_SELF']);
        $url = str_replace($directory . '/', '', $_SERVER['REQUEST_URI']);
      } else {
        $url = $_SERVER['REQUEST_URI'];
        $url = substr( $url, 1 );
      }
      $method = $_SERVER['REQUEST_METHOD'];
      $headers = getallheaders();
    }

    $inputString = file_get_contents("php://input");
    $inputData = array();
    parse_str($inputString, $inputData);

    self::$sapi = $sapi;
    self::$baseUrl = $baseUrl;
    self::$url = $url;
    self::$method = $method;

    Config::load();
    Input::load();
    self::removeMagicQuotes();
    self::unregisterGlobals();
    self::setReporting();
    if ( ! IS_CLI ) {
      self::doRouting();
    } // end if not cli
  } // end function startUp

  /**
   * Display errors only in development
   */
  static function setReporting() {

  	if (DEVELOPMENT_ENVIRONMENT == true) {
  	    error_reporting(E_ALL);
  	    ini_set('display_errors','On');
  	} else {
  	    error_reporting(E_ALL);
  	    ini_set('display_errors','Off');
  	    ini_set('log_errors', 'On');
  	    ini_set('error_log', ROOT.DS.'tmp'.DS.'logs'.DS.'error.log');
  	}
  }

  /**
   * Removes the magic quotes
   */
  static function stripSlashesDeep($value) {
    if ( is_array( $value ) ) {
      array_map('stripSlashesDeep', $value);
    } else {
      stripslashes($value);
    }
      return $value;
  }

  /**
   * Removes the magic quotes
   */
  static function removeMagicQuotes() {
  	if ( get_magic_quotes_gpc() ) {
  	    $_GET    = stripSlashesDeep($_GET   );
  	    $_POST   = stripSlashesDeep($_POST  );
  	    $_COOKIE = stripSlashesDeep($_COOKIE);
  	} // end if
  } // end function removeMagicQuotes

  /**
   * Unregister the globals
   */
  static function unregisterGlobals() {
    if (ini_get('register_globals')) {
      $array = array(
        '_SESSION',
        '_POST',
        '_GET',
        '_COOKIE',
        '_REQUEST',
        '_SERVER',
        '_ENV',
        '_FILES'
      );

      foreach ($array as $value) {
        foreach ($GLOBALS[$value] as $key => $var) {
          if ($var === $GLOBALS[$key]) {
            unset($GLOBALS[$key]);
          } // end if
        } // end foreach
      } //end foreach
    } // end if
  } // end function unregisterGlobals

  /**
   * This function does the routing process
   */
  static function doRouting() {

  	$url = self::$url;
  	$method = self::$method;

  	$routes = App::get('routes');

   	$controller = '';
   	$model = '';
   	$action = '';
   	$queryString = array();

  	if ( $method == 'GET' ) {
  		$urlParts = explode('?', $url);
  		if ( count($urlParts) > 1 ){
  			$url = $urlParts[0];
  			Input::setQueryString($urlParts[1]);
  		}
  	} // end if method is get

   	/* Here we search routes */


  	/**
    * Loop through routes
    */
    $defaultController = 'default';
    if ( $routes ) {
  		foreach ($routes as $route) {
  			if ( $route->_uri == "") {
  				$defaultController = $route->_newUri;
  			} // end if default
  	    $route->match($url);
  	 		if ($route->_method == "*" || $route->_method == $method) {
  	 			$url = str_replace($route->_uri, $route->_newUri, $url);
  	 		} // end if method
  	 	} // end foreach
  	} // end if route

   	if ( $url == "" || $url == "/") {
   		$cont = 0;
   	}
   	else {
   		$urlArray = explode("/",$url);
   		$cont = sizeof($urlArray);
   	} // end if then else

  	if ( !empty( $urlArray ) ) {
  		UriHelper::setSegments( $urlArray );
  	} // end if not empty urlArray

    switch ($cont) {
    	case 0:
    		$controller = $defaultController; // go to default controller
    		$action = 'index';
    		break;
    	case 1:
    		$controller = $urlArray[0];
    		$action = 'index';
    		break;
    	case 2:
    		$controller = $urlArray[0];
    		if ( $urlArray[1] == '') {
    			$action = 'index';
    		} else {
    			$action = $urlArray[1];
    		}
    		break;
    	default:
    		$controller = $urlArray[0];
    		array_shift($urlArray);
    		$action = $urlArray[0];
    		array_shift($urlArray);
    		$queryString = $urlArray;
    	break;
    }

    $controllerName = $controller;
    $controller = ucwords($controllerName);
    $controller .= 'Controller';
  	$defaultController .= 'Controller';

    if ((int)method_exists($controller, $action)) {
  			$dispatch = new $controller();
        call_user_func_array(array($dispatch,$action),$queryString);
  			//Session::validate( $controller );
    } else {
      /* Error Generation Code Here */
  		//	try default
  		//	if not
  		//	try file
  		//	if not, error

  		$action = $controllerName;
  		if ( (int)method_exists( $defaultController, $action ) ) {
  			$dispatch = new $defaultController();
  			call_user_func_array(array($dispatch, $action),$queryString);
  		} else if ( file_exists( ROOT.DS.$action ) ) {
  			self::serveFile( ROOT.DS.$action );
  		} else {
  			throw new Exception('Controller or action do not exist: ' . $controller . ' / ' . $action . 'nor file ' . ROOT.DS.$action);
  		} // end if then else file method or file exists
    } // end if then else method exists
  } // end callHook

  static function serveFile( $file ) {
  	ob_end_clean();

  	if ( StringHelper::endsWith($file, '.xml') ) {
  		header('Content-type: application/xml');
  	} // end if ends with xml

  	if ( StringHelper::endsWith($file, '.json') ) {
  		header('Content-type: application/json');
  	} // end if ends with xml

  	echo file_get_contents( $file );
  } // end serveFile


  /**
   * Creates a new controller
   */
  static function createController( $name, $description ) {

  	$ucName = ucwords( $name );
  	$controllerName = $ucName.'Controller';
  	$filename = strtolower( $ucName );
  	$filename = $filename."controller.php";
  	$filename = ROOT.DS.'application'.DS.'controllers'.DS.$filename;

  	$template = "<?php
  /**
   * $description
   */
  class $controllerName extends Controller {

    /**
     * Creates a new instance of $controllerName
     */
    function __construct() {

    } // end function constructs

    /**
     * The home %baseUrl/$name/
     */
    function index() {

    } // end function index

  } // end class $controllerName";

  	file_put_contents( $filename, $template );
  } // end function createController

  /**
   * Remove all files and directories in /application
   */
  static function cleanApp() {
  	$dirs[] = ROOT.DS.'application'.DS.'controllers';
  	$dirs[] = ROOT.DS.'application'.DS.'data';
  	$dirs[] = ROOT.DS.'application'.DS.'libraries';
  	$dirs[] = ROOT.DS.'application'.DS.'models';
  	$dirs[] = ROOT.DS.'application'.DS.'views';

  	foreach( $dirs as $dir ) {
  		//self::printLine( $dir );
  		//self::printLine( scandir( $dir ) );

  		$files = scandir( $dir );
  		foreach( $files as $file ) {
  			if ( $file == '.' || $file == '..' ) {
  				continue;
  			}

  			$file = $dir.DS.$file;
  			if ( is_dir( $file ) ) {
  				self::printLine( 'remove dir '.$file );
  				DirectoryHelper::delete( $file, true );
  			} else {
  				self::printLine( 'remove file '.$file );
  				unlink( $file );
  			} // end if is dir
  		} // end foreach $file
  	} // end foreach dir
  } // end function cleanApp

  /**
   * Adds a datasource to the configuration
   * @param $name The name of the datasource
   * @param $type The type of the datasource
   * @param $text The text, query, procedure or table name
   */
  static function addDataSource( $connection, $name, $type, $text ) {
  	$fileDataSources = ROOT.DS.'config'.DS.'datasources.json';
  	$indexToRemove = null;
  	$removeIndex = false;

  	if ( file_exists( $fileDataSources ) ) {
  		$dataSources = json_decode( file_get_contents( $fileDataSources ), true );
  		foreach( $dataSources as $index => $ds ) {
  			if ( $ds['name'] == $name ) {
  				self::printLine('Data source ' . $name . ' already exists');
  				self::printLine('Overriding...');
  				$removeIndex = true;
  				$indexToRemove = $index;
  			} // end if $name == $name
  		} // end foreach datasource
  	} // end if file exists

  	if ( $removeIndex ) {
  		array_splice( $dataSources, $indexToRemove, 1);
  	} // end if removeIndex

  	$dataSource['connection'] = $connection;
  	$dataSource['name'] = $name;
  	$dataSource['type'] = $type;
  	$dataSource['text'] = $text;
  	$dataSources[] = $dataSource;
  	$json = json_encode( $dataSources, JSON_PRETTY_PRINT );
  	file_put_contents( $fileDataSources, $json );

  	self::printLine( 'Datasource added' );
  } // end function addDataSource

  static function createViewNewRecord( $tableName ) {

  	self::printLine('Creating view for new record, table ' . $tableName);
    if ( !isset( DB::$connections ) ) {
    	throw new Exception("No connections are set up", 1);
    } // end if isset DBConn
    $db = new DbHelper();

  	$sql = "SELECT
  		column_name,
  		data_type,
  		ordinal_position,
  		is_nullable,
  		character_maximum_length
  		FROM
  			information_schema.columns
  		WHERE
  			table_catalog = :databaseName
  			AND
  				table_name = :tableName";

  	$queryParams['tableName'] = $tableName;
  	$queryParams['databaseName'] = $db->dbConn->databaseName;
  	$result = $db->query($sql, $queryParams);

  	foreach ($result as $row) {
  		$row2 = $row;
  		$sql = "SELECT  t.table_name, kcu.column_name AS value_field,
  		(SELECT column_name FROM information_schema.columns WHERE table_name = t.table_name AND column_name <> kcu.column_name AND data_type like '%char%' ORDER BY ordinal_position LIMIT 1) AS display_field
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
  	                and t.table_name IS NOT NULL
  	                and kcu.column_name = :columnName
  		ORDER BY t.table_catalog,
  		         t.table_schema,
  		         t.table_name,
  		         kcu.constraint_name,
  		         kcu.ordinal_position";

  		$relParams['databaseName'] = $db->dbConn->databaseName;
  		$relParams['columnName'] = $row['column_name'];
  		$namingRelations = $db->query( $sql, $relParams );

  		if ( $namingRelations ) {
  			$firstRow = $namingRelations[0];
  			$row2['related_to'] = $firstRow['table_name'];
  			$row2['value_field'] = $firstRow['value_field'];
  			$row2['display_field'] = $firstRow['display_field'];

  			$text = "SELECT value_field, display_field FROM table_name";
  			$text = StringHelper::stringReplace( $firstRow, $text );

  			$type = 'SQLQUERY';
  			$connection = 'default';
  			$name = 'ds'.$firstRow['table_name'].'ComboBox';

  			self::printLine( 'Before datasource' );
  			addDataSource( $connection, $name, $type, $text );

  		} else {
  			$row2['related_to'] = '';
  			$row2['value_field'] = '';
  			$row2['display_field'] = '';
  		}	// end if namingRelations

  		$result2[] = $row2;
  	} // end foreach

  	$html = '<table>'.CRLF;
  	foreach( $result2 as $row ) {
  		if ( $row['related_to'] ) {
  			self::printLine( 'related_to' );
  			$select = '<select id="@name" data-source="@dataSource" data-value-field="@valueField" data-display-field="@displayField"></select>';
  			$keys = array('@name', '@dataSource', '@valueField', '@displayField');
  			$values = array($row['column_name'], 'ds'.$row['related_to'].'ComboBox', $row['value_field'], $row['display_field']);
  			$select = str_replace( $keys, $values, $select);
  			$html.=TAB.'<tr><td>'.$row['column_name'].'</td><td>'.$select.'</td></tr>'.CRLF;
  		} else {
  			$html.=TAB.'<tr><td>'.$row['column_name'].'</td><td>'.HtmlHelper::formText($row['column_name'], '').'</td></tr>'.CRLF;
  		}
  	}

  	$html.='</table>';
  	$file = ROOT.DS.'application'.DS.'views'.DS.'new'.$tableName.'.php';

  	$html = View::dataBind( $html );
  	if ( file_exists( $file ) ) {
  		unlink( $file );
  	} // end if file exists
  	file_put_contents($file, $html);
  } // end function createViewNewRecord

  static function printLine($text) {
  	if ( IS_CLI ) {
  		print_r($text);
  		echo '' . PHP_EOL;
  	}	else {
  		//print_r($text);
  		//echo '<br />';
  	}// end if IS_CLI
  } // end function printLine

  static function createModel( $tableName ) {
    try {
      $classDefinition = '<?php
      class @tableNameModel extends Model {

      @publicProperties

        /**
         * Returns an instance of ActorModel
         * @param long $@id
         */
        function load($@id) {

          $result =
            parent::$db->from($this->getTableName())
            ->where("@id", $@id)
            ->first();

      @loadProperties

        } // end function load

        /**
         * Save the model to the database table
         * @param string $forceInsert
         */
        function save($forceInsert = FALSE) {

          $params = array(
            @saveProperties
          );

          $where = array(
            \'@id\' => $this->@id
          );

          if ($forceInsert) {
            parent::$db->insert(
              $this->getTableName(),
              $params
            );
          } else {
            parent::$db->upsert(
              $this->getTableName(),
              $params,
              $where
            );
          } // end if then else

          if ( $this->@id === null ) {
            $this->@id =
              parent::$db->from($this->getTableName())
              ->where($params)
              ->max("@id");
          }
        } // end function save

      } // end class @tableNameModel';

    if ( !isset( DB::$connections ) ) {
    	throw new Exception("No connections are set up", 1);
    } // end if isset DBConn
    $db = new DbHelper();

    	//	Get the id:
    	$id = $db->getIdentityColumn( $tableName );

    	if ( !$id ) {
    		//	Look for primary key
     		$result = $db->getPrimaryKeys($tableName);
    		//	if multiple then
    		if ( count($result) > 1 ) {
    			print_r ( 'Error: Table does not have id identity / sequence or you do not have granted permissions to read the table.');
    			exit;
    		} // end if multipleresult

        if ( isset($result[0]['column_name']) ) {
          $id = $result[0]['column_name'];
        } else if ( isset($result[0]['COLUMN_NAME'])) {
          $id = $result[0]['COLUMN_NAME'];
        }
    	} // end if not id

    	//	Public properties
    	$publicProperties = $db->getPublicProperties( $tableName );

      //  Columns
    	$columns = $db->getColumns( $tableName );      
      //	Load properties
      $loadProperties = "";
    	foreach ( $columns as $row ) {
        $columnName = $row['COLUMN_NAME'];
        $loadProperties .= "\t\t$"."this->$columnName = $"."result['$columnName'];". PHP_EOL;
    	}

    	//	Save properties
    	$saveProperties = "";
    	foreach ( $columns as $row ) {
        $columnName = $row['COLUMN_NAME'];
        $saveProperties .= "\t\t\t'$columnName' => $"."this->$columnName". PHP_EOL;
    	}

    	$text = $classDefinition;
    	$text = str_replace("@tableName", ucfirst($tableName), $text);
    	$text = str_replace("@id", strtolower($id), $text);
    	$text = str_replace("@publicProperties", $publicProperties, $text);
    	$text = str_replace("@loadProperties", $loadProperties, $text);
    	$text = str_replace("@saveProperties", $saveProperties, $text);

    	$filename = "application/models/" . strtolower($tableName) . "model.php";

    	unlink( $filename );
    	file_put_contents($filename, $text);
    	self::printLine ( "Model for table $tableName created in $filename.");
    	self::printLine("");

    } catch( Exception $ex ) {
      print_r ( $ex );
    } // end try catch
  } // end function createModel

  private static function getLoadProperties() {
    $sql = 'SELECT \'$this->\' || column_name || \' = $result[\'\'\' || column_name || \'\'\'];\' AS property
  FROM information_schema.columns
  WHERE table_schema = \'public\'
    AND table_name   = :tableName';

    $queryParams['tableName'] = $tableName;

    $result = $db->query($sql, $queryParams);

    foreach ( $result as $row ) {
      $loadProperties .= "\t\t" . $row['property'] . PHP_EOL;
    }

    return $loadProperties;
  } // end function getLoadProperties

  static function handleExeption( $ex ) {
    ob_end_clean();
    if ( App::get('appName') ) {
      $data['$appName'] = 'RS Php';
    } else {
      $data['$appName'] = App::get('appName');
    }
    $data['$error'] = $ex->getMessage();
    View::load( 'rs/error', $data );
  } // end function handleExeption
} // end function class RS
