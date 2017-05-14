<?php
class Config {

  /**
   * @var assoc_array
   * The configuration data
   */
  private static $data = array();

  /**
   * Loads the config
   */
  static function load() {

    $folder = ROOT.DS.'config';
    $files = DirectoryHelper::getFiles( $folder, array( '.json' ) );

    foreach ( $files as $file ) {
      self::loadConfig( $file );
    } // end foreach

    if ( isset( self::$data['configFiles']) ) {
      foreach ( self::$data['configFiles'] as $file ) {
        self::loadConfig( $file );
      } // end foreach configFiles
    } // end if configFiles

    self::processConfig();
  } // end function load

  static function get($key = NULL) {
		if ( $key == NULL ) {
			return self::$data;
		} else {
			if ( array_key_exists($key, self::$data)) {
				return self::$data[$key];
			} else {
				return null;
			} // end if array key exists
		} // end if $key is null
	} // end function get

  static function set( $key, $value ) {
    self::$data[$key] = $value;
  } // end function set

  static function setEncryptionKeys() {
    $tripleDesKey = CryptHelper::generateKey( 24 );
    $tripleDesVector = CryptHelper::generateKey( 8 );
    $template = "<?php
    define('TRIPLEDES_KEY', hex2bin('$tripleDesKey'));
    define('TRIPLEDES_IV', hex2bin('$tripleDesVector'));";
    $fileName = ROOT.DS.'config'.DS.'tdeskeys.php';
    if ( !is_writable( $fileName) ) {
      throw new Exception("$fileName not writeable", 1);
    } // end if not writeable
    FileHelper::write( $fileName, $template);
  } // end function

  private static function loadConfig( $file ) {

  	//	Read json into array
  	if ( file_exists( $file ) ) {
  		$config = file_get_contents($file);
  		$config = json_decode($config, true);
      self::$data = array_merge( self::$data, $config );
  	} else {
  		throw new Exception( "$file do not exists" );
  	} // end if file exists
  } // end function loadConfig

  private static function processConfig() {
    $config = self::$data;

    //	Appname
    if ( isset( $config['appName'] ) ) {
      if ( !defined('APP_NAME') ) {
    		define('APP_NAME', $config['appName']);
    	} // end if not defined app name
    } // end if config->appName

    //	Data Connections
    if ( isset( $config["dbConnections"] ) ) {
      foreach( $config['dbConnections'] as $dbConn ) {
    		Db::$connections[$dbConn['name']] = new DbConnection( $dbConn );
    		//print_r( Db:$connections['default'] );
    	} // end foreach
    } // if isset dbConnections

  	//	Routes
    if ( isset( $config["routes"] ) ) {
      foreach( $config['routes'] as $route ) {
    		$routes[] = new Route($route['method'], $route['url'], $route['newUrl']);
    		//print_r( Db:$connections['default'] );
    	} // end foreach
      App::set('routes', $routes);
    } // end if routes

  	//	Global variables
    if ( isset( $config['globals'] ) ) {
      App::set( $config['globals'] );
    } // end if isset globals

  	//	Load dataSources
  	self::loadDataSources();
  } // end function processConfig

  private static function loadDataSources() {

    $dataSources = self::$data['dataSources'];

  	foreach ( $dataSources as $ds ) {
  		$dataSource = new DataSource(
  			$ds['connection'],
  			$ds['name'],
  			$ds['type'],
  			$ds['text']
  		); // end add dataSources

  		if ( isset( $ds['parameters'] ) ) {
  			$params = $ds['parameters'];
  			foreach ( $params as $param ) {
  				$dataSource->addParam(
  					$param['name'],
  					$param['type'],
  					$param['defaultValue']
  				);
  			} // end function foreach
  		} // end if parameters

  		if ( isset( $ds['filters'] ) ) {
  			$filters = $ds['filters'];
  			foreach ( $filters as $key => $value ) {
  				$dataSource->addFilter(
  					$key,
  					$value
  				);
  			} // end function foreach
  		} // end if parameters
  		Db::$dataSources[$ds['name']] = $dataSource;
  	} // end foreach datasources
  } // end function loadDataSources
} // end class Config