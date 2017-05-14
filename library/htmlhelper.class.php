<?php
/**
 * Helper for construct HTML
 *
 * Please report bugs on https://github.com/espino316/rsphp/issues
 *
 * @author Luis Espino <luis@espino.info>
 * @copyright Copyright (c) 2016, Luis Espino. All rights reserved.
 * @license MIT License
 */
class HtmlHelper {

	/**
	 * Returns an html anchor
	 * @param string $url
	 * @param string $text
	 * @param string $target
	 * @param string $options
	 * @return string
	 */
	static function anchor($url, $text, $target = "_self", $options = '') {
		return "<a href='$url' target='$target' $options >$text</a>";
	}

	/**
	 * Prints a script for console.log
	 * @param string $msg
	 */
	static function setJSLog($msg) {
		echo "<script language='javascript'>console.log('$msg');</script>";
	}

	/**
	 * Prints a jquery mobile compliant table
	 * @param array $data A bi-dimensional array with the data
	 * @param array $options An array with the options
	 * @param bool $returnString Indicates if the string is returned. If not or  ommited, prints the result
	 */
	static function dataTable ( $data, $options = null, $returnString = false ) {

		//	Asume no pagination
		$pagination = false;

		//	Si se especificaron opciones, obtenemos los arreglos
		if ( $options != null ) {

			//	Si se especificaron columnas
			if ( array_key_exists( 'columns', $options ) )
				$columns = $options['columns'];

			if ( array_key_exists('url_format', $options) )
				$url_format_row = $options['url_format'];

			if ( array_key_exists('url_fields', $options) )
				$url_fields_row = $options['url_fields'];

			if ( array_key_exists('onclick_format', $options) )
				$onclick_format_row = $options['onclick_format'];

			if ( array_key_exists('onclick_fields', $options) )
				$onclick_fields_row = $options['onclick_fields'];

			if ( array_key_exists('attributes', $options) )
				$attrs = $options['attributes'];

			if ( array_key_exists('id', $options) )
				$tableId = $options['id'];

			if ( array_key_exists('pagination', $options) ) {
				$pagination = $options['pagination'];
				if ( array_key_exists('page_items', $options) ) {
					$pageItems = $options['page_items'];
				} else {
					$pageItems = 10;
				}
				if ( array_key_exists('page_items', $options) ) {
					$currentPage = $options['current_page'];
				} else {
					$currentPage = 1;
				} // end if then else page_items
				if ( array_key_exists('pages', $options) ) {
					$pages = $options['pages'];
				} else if ( $pagination ) {
					throw new Exception( 'Must specify pages to paginate');
				} // end if then else page_items
				if ( array_key_exists('pagination_url', $options) ) {
					$paginationUrl = $options['pagination_url'];
				} else {
					throw new Exception( 'Must specify pagination url');
				} // end if then else page_items
			} // end if pagination
		} else {
			$tableId = 'table' . rand(11,99);
		}

		$count = count($data);

		if ( $count == 0 ) {
			if ( $returnString ) {
				return "";
			} else {
				echo "";
				return;
			}
		}
		//	Primero obtenemos el header
		$head = $data[0];

		//	Los atributos de la tabla
		$attributes = '';

		//	Si hay atributos, los establecemos
		if ( isset ( $attrs ) ) {
			foreach ( array_keys( $attrs ) as $key ) {
				$attributes .= " " . $key . "=\"$attrs[$key]\" ";
			}
		}

		//	Inicializamos la tabla
		$table = '<table id="' . $tableId . '" ' . $attributes .'>';

		//	Inicializamos la cabecera
		$thead = '<thead><tr>';

		//	Si hay datos, los mapeamos
		if ( count( $data ) > 0 ) {

			$attributes = '';

			if ( isset ( $options['headerAttributes'] ) ) {
				foreach ( array_keys( $options['headerAttributes'] ) as $key ) {
					$attributes .= ' ' . $key . '="' . $options['headerAttributes'][$key] . '" ';
				}
			}
			if ( isset ( $columns ) ) {

				foreach ( $columns as $column ) {

					$visible = '';
					if ( isset( $column['visible'] ) ) {
						if ( $column['visible'] == false ) {
							$visible = ' style="display: none;" ';
						}
					}

					if ( isset ( $column['header'] ) ) {
						$thead .= '<th ' . $attributes . ' ' . $visible . '>' . $column['header']. '</th>';
					} else {
						$thead .= '<th ' . $attributes . ' ' . $visible . '>' . $column['name']. '</th>';
					}
				}
			}
			else {
				foreach ( array_keys( $head ) as $key ) {
					$thead .= '<th ' . $attributes . '>' . $key . '</th>';
				}
			}
		}

		$thead .='</tr></thead>';

		$tbody = '<tbody>';

		$rowCount = 0;
		if ( count( $data ) > 0 ) {

			$cont = 1;

			foreach ( $data as $row ) {
				$attributes = '';
				if ( isset( $options['rows'] ) ) {
					if ( isset( $options['rows'][$rowCount]  ) ) {
						foreach ( array_keys( $options['rows'][$rowCount]['attributes'] ) as $key ) {
							$attributes .= ' ' . $key . '="' . $options['rows'][$rowCount]['attributes'][$key] . '" ';
						}
					}
				}
				if ( isset ( $url_format_row ) ) {
					$url_row = $url_format_row;
					if ( isset ( $url_fields_row ) ) {
						foreach ( $url_fields_row as $field ) {
							$url_row = str_replace( "@$field", $row[$field], $url_row);
						}
					}
					$tbody .= '<tr ' . $attributes . ' onclick="document.location=\'' . $url_row . '\'">';
				} else if ( isset ( $onclick_format_row ) ) {
					$onclick_row = $onclick_format_row;
					if ( isset ( $onclick_fields_row ) ) {
						foreach ( $onclick_fields_row  as $field ) {
							$onclick_row = str_replace( "@$field", $row[$field], $onclick_row);
						}
					}
					$tbody .= "<tr onclick=\"$onclick_row\">";
				}
				else {
					$tbody .= "<tr $attributes>";
				}

				//	Recorre los nombres de las columnas
				//	y si especificamos columnas, verifica que el nombre
				//	de la columna este espeficicada para imprimirla
				//	Si no, simplemente imprimira todas las columnas
				//	Debe ser al reves, recorrer las columnas especificadas y no los keys del resulset

				if ( isset ( $columns ) ) {
					foreach ( $columns as $column ) {
						$visible = '';
						$key = $column["name"];
						if ( isset( $column['visible'] ) ) {
							if ( $column['visible'] == false ) {
								$visible = ' style="display: none;" ';
							}
						}
						$conditional = "";

						if ( isset ( $column['conditional'] ) ) {
							$conditions = $column['conditional'];
							foreach ( array_keys ( $conditions ) as $condition ) {
								if ( $condition == $row[$column['condition_field']] ) {
									$conditional = $conditions[$condition];
								}
							}
						}

						switch ( $column['type'] ) {

							case "hyperlink":
								$url_format = "#";
								$onclick_format = "";

								if ( isset ( $column['url_format'] ) ) {
									$url_format = $column['url_format'];
									if ( isset ( $column['url_fields'] ) ) {
										foreach ( $column['url_fields'] as $field ) {
											$url_format = str_replace( "@$field", $row[$field], $url_format);
										}
									}
								}

								if ( isset( $column['onclick_format'] ) ) {
									$onclick_format = ' onclick="' . $column['onclick_format'] . '" ';
									if ( isset( $column['onclick_fields'] ) ) {
										foreach ( $column['onclick_fields'] as $field ) {
											$onclick_format = str_replace( "@$field", $row[$field], $onclick_format);
										}
									}
								}

								if ( isset ( $column['text'] ) ) {
									$text = $column['text'];
								} else {
									$text = $row[$field];
								}

								$tbody .=
								'<td ' . $visible . '><a href="' .
								$url_format . '"' .
								$onclick_format .
								$conditional .
								' data-ajax="false">' .
								$text .
								'</a></td>';
								break;

							case "image":

								$tbody .= "<td $visible $conditional >";
								$tbody .= '<img src="' . $column["url"] . '" ';
								$tbody .= ' height="' . $column["height"] . '" ';
								$tbody .= ' width="' . $column["width"] . '" ';
								$tbody .= " />";
								$tbody .= "</td>";
								break;

							case "text":

								$attributes = '';
								if ( isset( $column['attributes'] ) ) {
									foreach ( array_keys( $column['attributes'] ) as $key ) {
										$attributes .= ' ' . $key . '="' . $column['attributes'][$key] . '" ';
									}
								}
								if ( isset( $column['format'] ) ) {
									switch ( $column['format'] ) {
										case 'N2':
											$tbody .= "<td $visible $conditional $attributes >" . number_format( $row[$column["name"]], 2 ) . '</td>';
											break;
										default:
											$tbody .= "<td $visible $conditional $attributes >" . sprintf($column['format'], $row[$column["name"]]) . '</td>';
											break;
									}
								} else {
									$tbody .= "<td $visible $conditional $attributes >" . $row[$column["name"]] . '</td>';
								}
								break;

							case "textbox":

								$type = 'text';
								if ( isset( $column['visible'] ) ) {
									if ( $column['visible'] == false ) {
										$type = 'hidden';
									}
								}

								$tbody .= "<td $visible $conditional ><input type=\"$type\" name=\"";
								$name = "row" . $cont . "[" . $key . "]";
								//$tbody .= $tableId."__".$key."__textbox__".$cont."\" value=\"";
								$tbody .= $name . "\" value=\"";
								$tbody .= $row[$column["name"]] . "\" /></td>";

								break;

							case "hidden":

								$tbody .= "<td style=\"display: none;\"><input type=\"hidden\" name=\"";
								$name = "row" . $cont . "[" . $key . "]";
								$tbody .= $name . "\" value=\"";
								$tbody .= $row[$column["name"]] . "\" /></td>";

								break;

							case "textarea":

								$attributes = '';
								if ( isset ( $column['attributes'] ) ) {
									$attributes = $column['attributes'];
								}
								$tbody .= "<td $visible $conditional ><textarea $attributes name=\"";
								$tbody .= $tableId."__".$key."__textbox__".$cont."\">";
								$tbody .= $row[$column["name"]]  . "</textarea></td>";

								break;

							case "select":

								$name = $tableId."__".$key."__select__".$cont;
								$name = "row" . $cont . "[" . $key . "]";

								$select =
									self::formSelect(
											$name,
											$column['data'],
											$column['value_field'],
											$column['display_field'],
											$row[$column["name"]],
											true
									);

								$tbody .= "<td $visible >$select</td>";

								break;
						}
					}
				}
				else {
					foreach ( array_keys( $head ) as $key ) {
						$tbody .= '<td>' . $row[$key] . '</td>';
					}
				}

				$tbody .= '</tr>';
				$cont++;
				$rowCount++;
			}
		}

		$tbody .= '</tbody>';

		$table .= $thead . $tbody . '</table>';

		if ( $pagination ) {
			$paginationDiv = '<script type="text/javascript">function changePage(sel){window.location.href="@paginationUrl/" + sel.value;}</script><div class="pagination">@pagination</div>';
			$paginationDiv = str_replace('@paginationUrl', $paginationUrl, $paginationDiv);
			$paginationSelect = 'Página: <select class="paginator" onchange="changePage(this);">@options</select>';
			$options = '';
			foreach( $pages as $page ) {
				$selected = '';
				if ( $page == $currentPage ) {
					$selected = 'selected';
				} // end if page = currentPage
				$options.= "<option $selected>$page</option>";
			}
			$paginationSelect = str_replace( '@options', $options, $paginationSelect );
			$paginationDiv = str_replace( '@pagination', $paginationSelect, $paginationDiv );
			$table = '<div class="paginatedTable">'.$table.$paginationDiv.'</div>';
		} // end if pagination

		if ( $returnString )
			return $table;
		else
			echo $table;
	}

	/**
	 * Returns a Html Select Control string
	 * @param string $name
	 * @param string $data
	 * @param string $value_field
	 * @param string $display_field
	 * @param string $selected_value
	 * @return string
	 */
	static function formSelect(
		$name,
		$data,
		$value_field,
		$display_field,
		$selected_value = '',
		$returnString = false,
		$onChange = ''
	) {
		if ( !empty( $onChange ) ) {
			$onChange = "onchange=\"$onChange\"";
		} else {
			$onChange = '';
		}

		$select = "<select id=\"$name\" name=\"$name\" class=\"form-control\" $onChange >";

		if ( $data ) {
			foreach ( $data as $row ) {
				$selected = "";

				if ( $selected_value == $row[$value_field] )
					$selected = "selected";

				$select .= "<option value=\"$row[$value_field]\" $selected >$row[$display_field]</option>";
			}
		} // end if $data


		$select .= "</select>";

		if ( $returnString )
			return $select;
		else
			echo $select;
	}

	/**
	 * Returns a hidden html control
	 * @param string $name
	 * @param string $value
	 * @return string
	 */
	static function formHidden(
		$name,
		$value = null
	) {
		$hidden = "<input type=\"hidden\" id=\"$name\" name=\"$name\" value='$value'>";
		return $hidden;
	}

	/**
	 * Returns a form submit
	 */
	static function formSubmit(
		$name,
		$value,
		$cssClass = "btn btn-primary"
	) {
		$submit = "<button type=\"submit\" id=\"$name\" name=\"$name\" class=\"$cssClass\">$value</button>";
		return $submit;
	} // end function formSubmit

	/**
	 * Creates a input type text
	 * @param $name String
	 * @param $value String
	 * @param $attributes
	 */
	static function formText(
		$name,
		$value = "",
		$attributes = null
	) {
		$inputText = '<input type="text" id="@name" name="@name" @attributes value="@value" />';
		$inputText = str_replace('@name', $name, $inputText);
		$inputText = str_replace('@value', $value, $inputText);

		if ( $attributes ) {
			$attrs = '';
			foreach ($attributes as $key => $value) {
				$attrs.= " $key=\"$value\" ";
			}
			$inputText = str_replace('@attributes', $attrs, $inputText);
		} else {
			$inputText = str_replace('@attributes', '', $inputText);
		} // end if then else attributes

		return $inputText;
	} // end function formText

	/**
	 * Print a line
	 * @param string $text The text to print
	 */
	static function printLine (
			$text
	) {
		echo "$text<br/>";
	} // end printLn

	/**
	 *
	 * @param array $data Array of assoc arrays
	 * @param string $valueField
	 * @param string $displayField
	 * @param string $isCheckField
	 */
	static function getCheckBoxGroup(
			$data,
			$legend,
			$controlName,
			$valueField,
			$displayField,
			$isCheckField,
			$returnString = FALSE
	) {
		$html = "<fieldset><legend>$legend</legend>";
		foreach ( $data as $input ) {
			$template = '<div class="checkbox"><label><input type="checkbox" name="@name[]" value="@value" @checked>@display</label></div>';
			$template = str_replace("@value", $input[$valueField], $template);
			$template = str_replace("@display", $input[$displayField], $template);
			$template = str_replace("@name", $controlName, $template);
			if ( $input[$isCheckField] > 0 )
				$checked = 'checked';
			else
				$checked = '';
			$template = str_replace("@checked", $checked, $template);
			$html .= $template;
		}
		$html .= "</fieldset>";

		if ( $returnString )
			return $html;
		else
			echo $html;
	} // end function getSubBusinessLinesHTMLCheckBoxGrop

} // end class