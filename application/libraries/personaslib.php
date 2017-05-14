<?php
/**
 * Contiene la lógica de negocios para administrar personas
 */
class PersonasLib {

  /**
   * @var DbHelper acceso a datos en la libreria
   */
  protected $db;

  /**
   * Crea una nueva instancia de PersonasLib
   */
  function __construct() {
    //  Instanciamos el acceso a datos
    //  DbHelper espera el nombre de la conexión previamente configurada
    //  si lo establecemos, tomará la de "default"
    $this->db = new DbHelper();

    //  Pero también puede ser así:
    $this->db = new DbHelper('default');

    //  Cuando queramos instanciar otra conexión
    //  pasamos su nombre a DbHelper
  } // end function __construct

  /**
   * Regresa un conjunto de registros con las personas
   */
  function lista() {
    //  Ponemos la consulta en una variable
    $sql = "SELECT
              *
            FROM
              personas;";
    //  Mandamos llamar "query", pasando la consulta
    //  El resultado es un arreglo de arreglos asociativos, una tabla
    //  un conjunto de resultados
    $result = $this->db->query($sql);

    //  Otra manera de obtener el resultado es utilizando los métodos
    //  de la clase DbHelper, construyendo dinámicamente la consulta:
    $result =
      $this->db
      ->from('personas') // establecemos la clausula "from"
      ->get(); // get para traer un conjunto de resultados
      //  Como es un select all, no es necesario establecer columnas

    //  Otra manera es simplemente llamar get y pasar como parámetro
    //  el nombre de la tabla
    $result =
      $this->db->get('personas');

    //  Regresamos el resultado
    return $result;

  } // end function lista

  /**
   *  Regresa una table HTML con el listado de personas
   *  @param $data array Es un conjunto de resultados a utilizar para formar
   *  la tabla
   */
  function tabla( $data ) {

    //  Utilizaremos la clase HtmlHelper para crear la table
    //  Necesitaremos pasarle un arreglo de configuración
    //  le llamarémos $options

    //  La primera configuración es el id de la tabla
    $options["id"] = "billsTable"; // el id
    //  En $options["attributes"] van los atributos, class, style, etc.
		$options["attributes"]["class"] = "table table-hover"; //

    //  Ahora vamos a configurar un arreglo de columnas
    //  para la tabla
    //  El arreglo se llamará $columns
    //  Y cada elemento será otro arreglo, estaremos utilizando
    //  la misma variable $column reiniciada cada vez
    //  para cada columna y agregándola al conjunto de
    //  columnas $columns:
		$column = array();
    //  El nombre de la columna es el nombre del campo en la tabla
		$column["name"] = "persona_id";
    //  Este es el tipo de columna, puede ser "text", "textbox", "textarea", "select", "image" o "hyperlink"
		$column["type"] = "text";
    //  El encabezado de la columna
    $column["header"] = "Id";
    //  "visible" true o false, visible o invisible
		$column["visible"] = true;
    //  Aqui agregamos la columna al arreglo
		$columns[] = $column;

    //  Reiniciamos el arreglo $column, para configurarlo de nuevo
		$column = array();
		$column["name"] = "nombre";
		$column["type"] = "text";
		$column["header"] = "Nombre";
    //  Si se omite visible, siempre serán visibles
    //  Lo agregamos al arreglo
		$columns[] = $column;

    // y de nuevo:
		$column = array();
		$column["name"] = "edad";
		$column["type"] = "text";
		$column["header"] = "Edad";
    //  lo agregamos al arreglo, conjunto de columns $columns
		$columns[] = $column;

    //  Ahora agregaremos un par de columnas con un hipervínculos
    //  Una para editar el registro y otra para borrarlo:
		$column = array();
		$column["name"] = "edit";
    //  Tipo "hyperlink"
		$column["type"] = "hyperlink";
    //  El encabezao en blanco
		$column["header"] = "";
    //  Este es el texto que ira en el hyperlink en cada reglón
		$column["text"] = "Editar";
    //  El formato que llevará la url
    //  los campos a substituir los ponemos con el prejifo "@"
    //  BASE_URL es una variable global del framework que apunta al
    //  directorio raíz url del sitio web
    //  despues de BASE_URL va el controlador, luego la función y los parámetros
		$column["url_format"] = BASE_URL . "/personas/editar/@persona_id";
    //  crearemos un arreglo para los campos que se van a Utilizar
    //  en la url, siempre un arreglo, aunque sea uno (pueden ser muchos)
    //  tanto en la url como en este arreglo debe llevar el nombre tal cual está
    //  en la base de datos
		$urlFields[] = "persona_id";
    //  Agregamos el arreglo de campos a la columna con la llave "url_fields"
		$column["url_fields"] = $urlFields;
    //  Agregamos la colummna al arreglo de columnas
		$columns[] = $column;

    //  Ahora uno para eliminar el registro
    $column = array();
		$column["name"] = "edit";
		$column["type"] = "hyperlink";
		$column["header"] = "";
		$column["text"] = "Borrar";
		$column["url_format"] = BASE_URL . "/personas/borrar/@persona_id";
		$urlFields[] = "persona_id";
		$column["url_fields"] = $urlFields;
		$columns[] = $column;

    //  Agregamos las columnas al arreglo de configuración $options
		$options["columns"] = $columns;

    //  Mandamos llamar la funcion estática "dataTable" de la clase HtmlHelper
    //  le pasamos como parpametros:
		$personasTable =
			HtmlHelper::dataTable(
				$data, // El arreglo que contiene los datos que mostrará la tabla
				$options, // el arreglo de configuración para la tabla
				true // true si queremos que nos devuelva una cadena con la tabla,
            //false (o excluyendolo) para imprimirla directamente
			);

    //  Regresamos una cadena con el HTML de la tabla
		return $personasTable;
  } // end function tabla

  /**
   * Elimina a una persona de la base de datos
   * @param $personaId int El id de la persona a borrar
   */
  function borrar( $personaId ) {
    //  Declaramos el Sql
    $sql = "DELETE
            FROM
              personas
            WHERE
              persona_id = :personaId";

    // Creamos los parámetros
    $queryParams['personaId'] = $personaId;

    //  Mandamos llamar "nonQuery" de DbHelper
    $deleted = $this->db->nonQuery($sql, $queryParams);

    //  Otra forma:
    //  Método delete, se le pasa como parámetros
    //  - nombre de la tabla
    //  - parámetros de la clausula where
    $where['persona_id'] = $personaId;
    $deleted =
      $this->db->delete(
        'personas',
        $where
      );

    // regresamos el resultado
    return $deleted;
  } // end function borrar

  /**
   * Consulta los datos de una persona en la base de datos
   * @param $personaId int Id de la persona a consultar
   */
  function consultar( $personaId ) {

    /* ***IMPORTANTE***
      Es más seguro utilizar parámetros que construir el sql directamente,
      es decir, que simplemente pegarle el valor $personaId a la cadena así:
      $sql = "SELECT * FROM presonas WHERE persona_id = $personaId;";
      Al utilizar parámetros, construimos el Sql y lo validamos utilizando
      enunciados preparados de PDO, por lo que PHP se encarga de validar
      los enunciados y evitar SQL Injection
    */

    //  Establecems el sql, con un parámetro
    //  los parámetros llevan el prefijo ":"
    $sql = "SELECT
              *
            FROM
              personas
            WHERE
              persona_id = :persona_id;";

    //  Creamos un arreglo para los parámetros
    //  Establecemos el parámetro utilizado
    $queryParams['persona_id'] = $personaId;

    //  Mandamos llamar query, pasándole los parámetros
    $result =
      $this->db->query(
        $sql,
        $queryParams
      );

    //  Si hay datos, persona es el primer registro
    if ( $result ) {
      $persona = $result[0];
    } // end if $result

    //  Esto lo podemos hacer de otra manera, utilizando
    //  los métodos asistentes de DbHelper, construyendo
    //  la consulta dinámicamente
    $persona =
      $this->db
      ->from('personas')                  // de personas
      ->where('persona_id', $personaId)   // donde persona_id = $personaId
      ->first();                          //  traemos el primer registro (ya no es necesario hacer $result[0])

    return $persona;
  } // end function consultar

  /**
   *  Guarda los datos de una persona en la base de datos
   */
  function guardar() {

    $personaId = Input::get('personaId');
    $nombre = Input::get('nombre');
    $edad = Input::get('edad');

    $data['nombre'] = $nombre;
    $data['edad'] = $edad;

    if ( $personaId ) {
      //  Update
      $where['persona_id'] = $personaId;
      $updated = $this->db->update('personas', $data, $where);
      return $updated;
    } else {
      //  Insert
      $inserted = $this->db->insert('personas', $data);
      return $inserted;
    } // end if personaId

  } // end function guardar

} // end class PersonasLib
