<?php

/**
 * Print HTML and manage views
 *
 * Please report bugs on https://github.com/espino316/rsphp/issues
 *
 * @author Luis Espino <luis@espino.info>
 * @copyright Copyright (c) 2016, Luis Espino. All rights reserved.
 * @license MIT License
 */
class View {

	/**
	 *
	 * @var array
	 */
	protected static $vars;

	/**
	 * Set a variable value to the view
	 * @param string $key
	 * @param object $value
	 */
	static function set($key, $value) {
		self::$vars[$key] = $value;
	}

	/**
	 * Returns a value from a key
	 * @param string $key The key of the value
	 */
	static function get($key) {
		if ( self::$vars != null ) {
			if ( array_key_exists($key, self::$vars)) {
				return self::$vars[$key];
			} else {
				return null;
			}
		} else {
			return null;
		}
	}

	static function clearVars() {
		self::$vars = null;
	}

	/**
	 * This function prints the populated template to reponse
	 * @param string $viewName The name of the view. Must be the file name withour extension. It's the template
	 * @param array $data Array that contains key value pairs for use them in templating by simple replacing
	 * @throws Exception
	 */
	static function load($viewName, $data = null) {
		echo self::loadToString($viewName, $data);
	}

	/**
	 * Converts an array to object
	 * @param array $array
	 */
	private static  function convertToObject($array) {
        $object = new stdClass();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = self::convertToObject($value);
            }
            $object->$key = $value;
        }
        return $object;
    } // end function convertToObject

	/**
	 * This function returns the populated template as string
	 * @param string $viewName The name of the view. Must be the file name withour extension. It's the template
	 * @param array $data Array that contains key value pairs for use them in templating
	 * @throws Exception
	 */
	static function loadToString($viewName, $data = null) {
		$filePath = ROOT . DS . 'application' . DS . 'views' . DS . $viewName . '.php';

		if (file_exists($filePath)) {
			$view = self::requireToVar($filePath);
			$view = str_replace( '$baseUrl', BASE_URL, $view );

			if ( $data != null ) {
				foreach( array_keys($data) as $itemKey ) {
					if ( StringHelper::contains( $itemKey, "$" ) ) {
						if ( is_array( $data[$itemKey] ) ) {
							//$data[$itemKey] = (object)$data[$itemKey];
							//$data[$itemKey] = json_decode (json_encode ($data[$itemKey]), FALSE);
							$data[$itemKey] = self::convertToObject($data[$itemKey]);
						}
						if ( is_object($data[$itemKey]) ) {
							$properties = get_object_vars($data[$itemKey]);

							foreach ( array_keys($properties) as $key ) {

								if ( is_object( $properties[$key] ) ) {
									$subProperties = get_object_vars( $properties[$key] );

									foreach ( array_keys($subProperties) as $subKey ) {
										$view =
											str_replace(
												$itemKey."->".$key."->".$subKey,
												$subProperties[$subKey],
												$view
											);
									}

								} else {
									$view =
										str_replace(
												$itemKey."->".$key,
												$properties[$key],
												$view
										);
								} // end if is object else
							} // end foreach
						} else {
							$view = str_replace($itemKey, $data[$itemKey], $view);
						} // end if then else
					} // end if contains $
				} // end foreach
			} // end if data
			$view = self::dataBind( $view, $data );
			$view = StringHelper::specialCharsToHTML( $view );
			return $view;
		} else {
			throw new Exception("View $filePath does not exists.");
		}
	}

	/**
	 * Returns a file as a variable. It may be a php script that will be evaluated
	 * @param string $file The file path to return as a variable
	 */
	private static function requireToVar($file){
		ob_start();
		require($file);
		return ob_get_clean();
	} // end function requireToVar

	/**
	 * Populates a template of data
	 * @param string $template
	 * @param assoc_array $data
	 */
	static function populateTemplate( $template, $data = null ) {

		$template = str_replace('$baseUrl', BASE_URL, $template);

		if ( $data != null ) {
			foreach( array_keys($data) as $itemKey ) {
				if ( StringHelper::contains( $itemKey, "$" ) ) {
					if ( is_object($data[$itemKey]) ) {

						$properties = get_object_vars($data[$itemKey]);

						foreach ( array_keys($properties) as $key ) {
							$template =
								str_replace(
									$itemKey."->".$key,
									$properties[$key],
									$template
								);
						}
					} else if ( is_array( $data[$itemKey] ) ) {
						foreach ( array_keys ( $data[$itemKey] ) as $key ) {
							if ( is_array($data[$itemKey][$key]) ) {
								break;
							}
							$template =
								str_replace(
										$itemKey."[".$key."]",
										$data[$itemKey][$key],
										$template
								);
						}
					} else {
						$template = str_replace($itemKey, $data[$itemKey], $template);
					} // end if is object
				}
			} // end foreach
		} // end if data null

		return $template;
	} // end static function populateTemplate

	/**
	 * Binds all selects in $html
	 * @param $html The HTML to parse
	 */
	private static function viewsDataBind( $html, $data = null ) {
		#Logger::debug(array($html,$data));
		$dom = new DOMDocument();
		@$dom->loadHTML( mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8') );

		//	DataSource Selects
		$items = $dom->getElementsByTagName('section');
		$count = $items->length - 1;

		if ( $count > -1 ) {
			$toReplace = array();

			while ( $count > -1 ) {
				$item = $items->item($count);
				if (
					$item->hasAttribute( 'data-view' )
				) {

					$viewName = $item->getAttribute('data-view');

					if ( StringHelper::contains( $viewName, '$' ) ) {
						if ( isset( $data[$viewName] ) ) {
							$viewName = $data[$viewName];
						} // end if is set data [ viewName ]
					} // end if contains $

					$view = View::loadToString( $viewName, $data );

					//	Replacement
					$replacement = null;
					$replacement['search'] = $item->ownerDocument->saveHTML($item);
					$replacement['replace'] = $view;
					$toReplace[] = $replacement;
				} // end if has attributes
				$count--;
			} // end while item data-source

			if ( !empty($toReplace) ) {
				foreach ( $toReplace as $replacement ) {
					$old = $replacement['search'];
					$new = $replacement['replace'];
					$html = str_replace( $old, $new, $html );
				} // end foreach $replacement
			} // end if $toReplace not empty
		} // end if count > -1

		/*	Here begins data-bind attibute */
		$isFragment = true;
		if ( StringHelper::contains( $html, '<html') ) {
			$isFragment = false;
		}

		$toReplace = null;
		$dom = new DOMDocument();
		@$dom->loadHTML( mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8') );
		$items = $dom->getElementsByTagName('input');
		$count = $items->length - 1;

		if ( $count > -1 ) {
			while ( $count > -1 ) {
				$item = $items->item($count);
				if (
					$item->hasAttribute( 'data-bind' )
				) {
					$name = $item->getAttribute('data-bind');

					if ( isset( $data[$name] ) ) {
						$replacement = null;
						$replacement['search'] = $item->ownerDocument->saveHTML($item);

						$item->setAttribute( 'value', $data[$name] );

						$replacement['replace'] = $item->ownerDocument->saveHTML($item);
						$toReplace[] = $replacement;
					} else if ( StringHelper::contains( $name, "[") ) {
						$arr = explode( "[", $name );
						$key1 = $arr[0];
						$key2 = StringHelper::replace("]", "", $arr[1]);
						if ( isset( $data[$key1][$key2] ) ) {
							$replacement = null;
							$replacement['search'] = $item->ownerDocument->saveHTML($item);

							$item->setAttribute( 'value', $data[$key1][$key2] );

							$replacement['replace'] = $item->ownerDocument->saveHTML($item);
							$toReplace[] = $replacement;
						} // end if isset
					} // end if data[name]
				} // end if has attributes
				$count--;
			} // end while item data-source

			if ( $isFragment ) {
				$body = $dom->getElementsByTagName('body');
				$body = $body[0];
				$html = self::domInnerHTML( $body );
			} else {
				$html = @$dom->saveHTML();
			}

			if ( !empty($toReplace) ) {
				foreach ( $toReplace as $replacement ) {
					$old = $replacement['search'];
					$new = $replacement['replace'];
					$html = str_replace( $old, $new, $html );
				} // end foreach $replacement
			} // end if $toReplace not empty
		} // end if $count > -1

		// Spans
		$toReplace = null;
		$dom = new DOMDocument();
		@$dom->loadHTML( mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8') );
		$items = $dom->getElementsByTagName('span');

		$count = $items->length - 1;

		if ( $count > -1 ) {
			while ( $count > -1 ) {
				$item = $items->item($count);
				if (
					$item->hasAttribute( 'data-bind' )
				) {
					$name = $item->getAttribute('data-bind');
					if ( isset( $data[$name] ) ) {
						$replacement = null;
						$replacement['search'] = $item->ownerDocument->saveHTML($item);

						$textNode = $dom->createTextnode( $data[$name] );
						$item->appendChild( $textNode );

						$replacement['replace'] = $item->ownerDocument->saveHTML($item);
						$toReplace[] = $replacement;
					} // end if data[name]
				} // end if has attributes
				$count--;
			} // end while item data-source

			if ( $isFragment ) {
				$body = $dom->getElementsByTagName('body');
				$body = $body[0];
				$html = self::domInnerHTML( $body );
			} else {
				$html = @$dom->saveHTML();
			}

			if ( !empty($toReplace) ) {
				foreach ( $toReplace as $replacement ) {
					$old = $replacement['search'];
					$new = $replacement['replace'];
					$html = str_replace( $old, $new, $html );
				} // end foreach $replacement
			} // end if $toReplace not empty
		} // end if $count > -1

		return $html;
	} // end function selectsDataBind


	/**
	 * Binds all selects in $html
	 * @param $html The HTML to parse
	 */
	private static function selectsDataBind( $html, $data = null ) {

		$isFragment = true;
		if ( StringHelper::contains( $html, '<html') ) {
			$isFragment = false;
		}

		$dom = new DOMDocument();
		@$dom->loadHTML( mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8') );

		//	DataSource Selects
		$items = $dom->getElementsByTagName('select');
		$count = $items->length - 1;

		if ( $count == -1 ) {
			return $html;
		}

		$toReplace = array();

		while ( $count > -1 ) {
			$item = $items->item($count);
			if (
				$item->hasAttribute( 'data-source' )
				&& $item->hasAttribute( 'data-display-field' )
				&& $item->hasAttribute( 'data-value-field' )
			) {
				/*
				//	Get the data
				$ds = Db::$dataSources[$item->getAttribute('data-source')];
				$db = new DbHelper( $ds->connection );
				if ( $ds->type == 'SQLQUERY' ) {
					$data = $db->query($ds->text);
				} // end if SQLQUERY
				*/
				$data = Db::getResultFromDataSource( $item->getAttribute('data-source') );

				$value = '';
				if ( $item->hasAttribute( 'data-bind' ) ) {
					if ( $data ) {
						$value = $item->getAttribute( 'data-bind' );
						$value = $data[$value];
					}
				} // end if hasAttribute value
				if ( $item->hasAttribute( 'value' ) ) {
					$value = $item->getAttribute( 'value' );
				} // end if hasAttribute value

				//	Get the SELECT
				$select =
					HtmlHelper::formSelect(
						$item->getAttribute('id'),
						$data,
						$item->getAttribute('data-value-field'),
						$item->getAttribute('data-display-field'),
						$value,
						true
					);

				//	Replacement
				$replacement = null;
				$replacement['search'] = $item->ownerDocument->saveHTML($item);
				$replacement['replace'] = $select;
				$toReplace[] = $replacement;
			} // end if has attributes
			$count--;
		} // end while item data-source

		if ( $isFragment ) {
			$body = $dom->getElementsByTagName('body');
			$body = $body[0];
			$html = self::domInnerHTML( $body );
		} else {
			$html = @$dom->saveHTML();
		}

		if ( !empty($toReplace) ) {
			foreach ( $toReplace as $replacement ) {
				$old = $replacement['search'];
				$new = $replacement['replace'];
				$html = str_replace( $old, $new, $html );
			} // end foreach $replacement
		} // end if $toReplace not empty

		return $html;
	} // end function selectsDataBind

	private static function domGetAttribute( $element, $attributeName, $defaultValue ) {
		if ( $element->hasAttribute( $attributeName ) ) {
			return $element->getAttribute( $attributeName );
		} else {
			return $defaultValue;
		}
	} // end function domGetAttribute

	/**
	 * Binds all selects in $html
	 * @param $html The HTML to parse
	 */
	private static function tablesDataBind( $html ) {

		$isFragment = true;
		if ( StringHelper::contains( $html, '<html') ) {
			$isFragment = false;
		}

		$dom = new DOMDocument();
		@$dom->loadHTML( mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8') );

		//	DataSource Selects
		$items = $dom->getElementsByTagName('table');

		$count = $items->length - 1;

		if ( $count == -1 ) {
			//	Nothing to do
			return $html;
		}

		$toReplace = array();

		while ( $count > -1 ) {
			$item = $items->item($count);
			if (
				$item->hasAttribute( 'data-source' )
			) {
				$options = null;
				$columns = null;

				$id = self::domGetAttribute( $item, 'id', 'table'.time());
				$pagination = self::domGetAttribute( $item, 'data-pagination', null);
				$pageItems = self::domGetAttribute( $item, 'data-page-items', null);
				$currentPageSegment = self::domGetAttribute( $item, 'data-current-page-segment', null);
				$currentPage = UriHelper::getSegment($currentPageSegment);
				$paginationUrl = self::domGetAttribute( $item, 'data-pagination-url', null);

				$options['id'] = $id;
				$options['pagination'] = $pagination;
				$options['page_items'] = $pageItems;
				$options['current_page'] = $currentPage;
				$options['pagination_url'] = $paginationUrl;
				array_filter($options);

				//	Get the data
				$dsName = $item->getAttribute('data-source');
				$result = Db::getResultFromDataSource( $dsName, null, $pageItems, $currentPage);

				if ( $pagination ) {
					$data = $result['results'];
					$options['pages'] = $result['pages'];
				} else {
					$data = $result;
				} // end if pagination

				if ( isset( $item->firstChild ) ) {
					$tr = $item->firstChild;
					if ( $tr->tagName == 'tr' ) {
						$options["attributes"]["class"] = "table table-hover";

						$ths = $tr->getElementsByTagName('th');
						foreach ($ths as $th) {

							if ( $th->hasAttribute('data-field-type') ) {
								$type = $th->getAttribute('data-field-type');
							} else {
								$type = 'text';
							} // end if then else data-field-type

							switch ( $type ) {
								case 'hyperlink':
									$column = array();
									$column["name"] = self::domGetAttribute( $th, 'data-name', self::domGetAttribute( $th, 'data-field', '' ));
									$column["type"] = self::domGetAttribute($th, 'data-field-type', 'hyperlink');
									$column["header"] = self::domGetAttribute($th, 'data-header', $column['name']);
									$column["text"] = self::domGetAttribute($th, 'data-text', null);
									$column["visible"] = self::domGetAttribute($th, 'data-visible', null);

									$column["url_format"] = self::domGetAttribute($th, 'data-url-format', null);
									$urlFields = explode(",", self::domGetAttribute($th, 'data-url-fields', ''));
									$column["url_fields"] = $urlFields;

									$column["onclick_format"] = self::domGetAttribute($th, 'data-onclick-format', null);
									$urlFields = explode(",", self::domGetAttribute($th, 'data-onclick-fields', ''));
									$column["onclick_fields"] = $urlFields;

									array_filter( $column );
									$columns[] = $column;
									break;

								case 'text':
									$column = array();
									$column["name"] = self::domGetAttribute( $th, 'data-name', self::domGetAttribute( $th, 'data-field', '' ));
									$column["type"] = self::domGetAttribute($th, 'data-field-type', 'text');
									$column["header"] = self::domGetAttribute($th, 'data-header', $column['name']);
									$column["format"] = self::domGetAttribute($th, 'data-format-text', null);
									$column["visible"] = self::domGetAttribute($th, 'data-visible', null);
									array_filter( $column );
									$columns[] = $column;
									break;

								case 'textbox':
									$column = array();
									$column["name"] = self::domGetAttribute( $th, 'data-name', self::domGetAttribute( $th, 'data-field', '' ));
									$column["type"] = self::domGetAttribute($th, 'data-field-type', 'textbox');
									$column["header"] = self::domGetAttribute($th, 'data-header', $column['name']);
									$column["format"] = self::domGetAttribute($th, 'data-format-text', null);
									$column["visible"] = self::domGetAttribute($th, 'data-visible', null);
									array_filter( $column );
									$columns[] = $column;
									break;

								case 'hidden':
									$column = array();
									$column["name"] = self::domGetAttribute( $th, 'data-name', self::domGetAttribute( $th, 'data-field', '' ));
									$column["type"] = self::domGetAttribute($th, 'data-field-type', 'hidden');
									$column["header"] = self::domGetAttribute($th, 'data-header', $column['name']);
									$column["format"] = self::domGetAttribute($th, 'data-format-text', null);
									$column["visible"] = self::domGetAttribute($th, 'data-visible', null);
									array_filter( $column );
									$columns[] = $column;
									break;

								case 'textarea':
									$column = array();
									$column["name"] = self::domGetAttribute( $th, 'data-name', self::domGetAttribute( $th, 'data-field', '' ));
									$column["type"] = self::domGetAttribute($th, 'data-field-type', 'textarea');
									$column["header"] = self::domGetAttribute($th, 'data-header', $column['name']);
									$column["format"] = self::domGetAttribute($th, 'data-format-text', null);
									$column["visible"] = self::domGetAttribute($th, 'data-visible', null);
									array_filter( $column );
									$columns[] = $column;
									break;

								case 'image':
									$column = array();
									$column["name"] = self::domGetAttribute( $th, 'data-name', self::domGetAttribute( $th, 'data-field', '' ));
									$column["type"] = self::domGetAttribute($th, 'data-field-type', 'image');
									$column["header"] = self::domGetAttribute($th, 'data-header', $column['name']);
									$column["url"] = self::domGetAttribute( $th, 'data-img-src', null);
									$column["height"] = self::domGetAttribute($th, 'data-img-height', null);
									$column["width"] = self::domGetAttribute($th, 'data-img-width', null);
									$column["visible"] = self::domGetAttribute($th, 'data-visible', null);
									array_filter( $column );
									$columns[] = $column;
									break;

								case 'select':

									$dsName = self::domGetAttribute( $th, 'data-source', null);
									$selectData = Db::getResultFromDataSource( $dsName, null);

									if ( $selectData ) {

										$column = array();
										$column["name"] = self::domGetAttribute( $th, 'data-name', self::domGetAttribute( $th, 'data-field', '' ));
										$column["type"] = self::domGetAttribute($th, 'data-field-type', 'select');
										$column["header"] = self::domGetAttribute($th, 'data-header', $column['name']);
										$column["visible"] = self::domGetAttribute($th, 'data-visible', null);
										$column["value_field"] = self::domGetAttribute($th, 'data-value-field', null);
										$column["display_field"] = self::domGetAttribute($th, 'data-display-field', null);
										$column['data'] = $selectData;
										array_filter( $column );
										$columns[] = $column;

									} else {
										throw new Exception("Select field in table requieres data-source attribute", 1);
									}

									break;

								default:
									// Treats it as test
									break;
							} // end swith type
						} // end for each $th

						$options['columns'] = $columns;
					} // end if tr
				} // end if isset

				//	Get the SELECT
				$dataTable =
					HtmlHelper::dataTable(
						$data,
						$options,
						true
					);

				//	Replacement
				$replacement = null;
				$replacement['search'] = $item->ownerDocument->saveHTML($item);
				$replacement['replace'] = $dataTable;
				$toReplace[] = $replacement;
			} // end if has attributes
			$count--;
		} // end while item data-source

		if ( $isFragment ) {
			$body = $dom->getElementsByTagName('body');
			$body = $body[0];
			$html = self::domInnerHTML( $body );
		} else {
			$html = @$dom->saveHTML();
		}

		if ( !empty($toReplace) ) {
			foreach ( $toReplace as $replacement ) {
				$old = $replacement['search'];
				$new = $replacement['replace'];
				$html = str_replace( $old, $new, $html );
			} // end foreach $replacement
		} // end if $toReplace not empty

		return $html;
	} // end function selectsDataBind

	/**
	 * Performs data bind activities
	 * @html The HTML to parse
	 */
	static function dataBind( $html, $data = null ) {
		$html = self::viewsDataBind( $html, $data );
		$html = self::selectsDataBind( $html );
		$html = self::tablesDataBind( $html );
		return $html;
	} // end function dataBind

	function testdom() {
		$doc = new DOMDocument();
		$doc->loadHTML("<html><body>Test<br></body></html>");
		$body = $doc->getElementsByTagName('body');
		$body = $body[0];
		$frag = $doc->createDocumentFragment(); // create fragment
		$frag->appendXML("<h1>Hello World!</h1>");
		while ($body->hasChildNodes()) {
			$body->removeChild($body->firstChild);
		}
		$body->appendChild($frag);
		echo $doc->saveHTML();

	}

	static function domInnerHTML(DOMNode $element) {
			$innerHTML = "";
			$children  = $element->childNodes;

			foreach ($children as $child)
			{
					$innerHTML .= $element->ownerDocument->saveHTML($child);
			}

			return $innerHTML;
	} // end function DOMinnerHTML

} // end class view
