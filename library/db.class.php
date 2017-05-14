<?php
/*
	Simple contains the connections to databases
*/
class Db {

	public static $connections;
	public static $dataSources;

	/**
	 * Returns a result from a DataSource
	 * @param $dsName The name of the datasource to query
	 * @param $params The name of the datasource to query
	 * @param $pageItems The name of the datasource to query
	 * @param $currentPage The name of the datasource to query
	 */
	public static function getResultFromDataSource(
		$dsName,
		$params = null,
		$pageItems = null,
		$currentPage = null )
	{
		if ( ! isset( self::$dataSources[$dsName] ) ) {
			throw new Exception ( "Data Source $dsName not configured" );
		}	// end if is set dsName

		$ds = self::$dataSources[$dsName];

		if ( $ds->type == 'JSON' ) {
			$fileName = StringHelper::replace( '$root', ROOT, $ds->text );
			$fileName = StringHelper::replace( '/', DS, $fileName );
			$result = FileHelper::read( $fileName );
			$result = json_decode( $result, true );

      if ( $ds->filters ) {
        foreach( $ds->filters as $key => $value ) {
					$result = DbHelper::resultSetFilter( $result, $key, $value);
        } // end foreach
      } // end ds Filters

			return $result;
		} // end if JSON

		$db = new DbHelper( $ds->connection );
		if ( $ds->type == 'SQLQUERY' ) {

			$sql = $ds->text;
			$pattern = '/\:[a-zA-Z0-9_]*$/';
			preg_match( $pattern, $sql, $matches);

			if ( $matches ) {
				foreach ( $matches as $match ) {
					$paramName = str_replace( ":", "", $match );

					if ( $ds->parameters ) {
						foreach ( $ds->parameters as $param ) {
							if ( $paramName == $param->name ) {
								switch ( $param->type ) {
									case 'session':
										$value = Session::get($param->name);
										if ( $value ) {
											$params[$param->name] = $value;
										} else {
											if ( $param->defaultValue ) {
												$params[$param->name] = $param->defaultValue;
											} else {
												throw new Exception("Param not exists " . $paramName, 1);
											}	// end if
										} // end if value
									break;
									case 'input':
										$value = Input::get($param->name);
										if ( $value ) {
											$params[$param->name] = $value;
										} else {
											if ( $param->defaultValue ) {
												$params[$param->name] = $param->defaultValue;
											} else {
												throw new Exception("Param not exists " . $paramName, 1);
											}	// end if
										} // end if value
									break;
								} // end swutch
							} // end if $paramName = $param->name
						} // end foreach param
					} // end if has parameters
				} // end foreach
			} // end if $matches

			if ( $pageItems ) {
				if ( !$currentPage ) {
					$currentPage = 1;
				}
				$result = $db->paginateQuery($pageItems, $currentPage, $sql, $params);
			} else {
				$result = $db->query($sql, $params);
			} // end pageItems
		} // end if SQLQUERY

		return $result;
	} // end function getResultFromDataSource

} // end class