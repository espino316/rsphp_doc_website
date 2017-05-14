    <div id="intromvc" class="content" style="float: left; width: 80%; margin-left: 10%;">
      <span>RSPhp es un web framework para el lenguaje php que es como lo indica su nombre: Realmente simple.</span>
      <span>Soporta el patrón de diseño MVC, sin embargo, es flexible y puedes prescindir de el y utilizarlo como gustes. Está diseñado para utilizar bases de datos SQL y  realizar database frontends de manera rápida y sencilla, aunque  puedes utilizarlo para crear cualquier tipo de aplicación.</span>
      Objetivos:
      - Simplicidad
      - Mantenibilidad
      - Eliminar uso de código spaguetti
      - Ligereza
      - Desarrollo rápido de aplicaciones
      Puedes descargarlo aqui:
      &nbsp;
      <span>Pues bien, comencemos!</span>
      <h1>Inicio Rápido</h1>
      &nbsp;
      <ol>
        <li><span>Descarga RSphp.</span></li>
        <li><span>Descomprime en el directorio de tu web.</span></li>
        <li><span>Observa la estructura:</span></li>
      </ol>
      <ul>
        <li>
          <span>root</span>
          <ul>
            <li>
              <span>application</span>
              <ul>
                <li><span>controllers</span></li>
                <li><span>libraries</span></li>
                <li><span>models</span></li>
                <li><span>views</span></li>
              </ul>
            </li>
            <li>
              <span>config</span>
              <ul>
                <li><span>app.json</span></li>
              </ul>
            </li>
            <li><span>library</span></li>
            <li>
              <span>public</span>
              <ul>
                <li><span>css</span></li>
                <li><span>files</span></li>
                <li><span>fonts</span></li>
                <li><span>image</span></li>
                <li><span>js</span></li>
              </ul>
            </li>
            <li>
              <span>tmp</span>
              <ul>
                <li><span>logs</span></li>
              </ul>
            </li>
            <li><span>rs.php</span></li>
          </ul>
        </li>
      </ul>
      &nbsp;
      <span>Directorio <strong>application</strong>.- Este directorio contendrá nuestra aplicación, el código fuente de controladores, modelos, vistas y librerías. Cada categoría tiene su subdirectorio.</span>
      <span>Directorio <strong>config</strong>.- Este directorio contendrá las configuraciones de nuestra aplicación, nuestras conexiones a bases de datos, ruteos entre controladores, nuestras definiciones, etc.</span>
      <em><strong>**Nota**</strong></em> Si haz utilizado web frameworks antes, esto te puede resultar tan familiar que llega redundante. Si es tu caso puedes irte directo a la documentación del framework aqui:
      &nbsp;
      <span>Lo primero que debemos tomar en cuenta son las rutas. Las rutas, las direcciones url “apuntan” a un controlador, una acción y les pueden pasar parámetros, así:</span>
      <a href="http://localhost/misitio/micontrolador/mifuncion"><span>http://localhost/misitio/micontrolador/mifuncion</span></a>
      Aqui "misitio" es el directorio donde instalaste el framework.
      <span>Las funciones dentro del controlador deben ser públicas para que funcione de esta manera.</span>
      <span>Ejemplo, quiero que el visitar </span><a href="http://localhost/misitio/micontrolador/mifuncion"><span>http://localhost/misitio/micontrolador/mifuncion</span></a><span> se me muestre el texto “Hola mundo”. </span><span>Pues lo primero que haces es crear un controlador, llamado “MiControladorController” y extendemos la clase “Controller” para que utilice los métodos de controlador.</span>
      <ul>
        <li>
          misitio
          <ul>
            <li>
              application
              <ul>
                <li>
                  controllers
                  <ul>
                    <li>micontroladorcontroller.php</li>
                  </ul>
              </ul>
          </ul>
      </ul>
      <br>
      <pre><code class="php">

  &lt;?php
  class MiControladorController extends Controller {

  } // end class MiControladorController

  </code></pre>
      <span>Por convención, el nombre del controlador debe comenzar com mayúsculas, luego seguir camelcase e incluir “Controller” como sufijo, para que el framework lo reconozca y lo ejecute sin problemas.</span>
      <span>Luego, dentro de micontroladorcontroller.php.  creamos una funcion llamada “miFuncion”.</span>
      <pre><code class="php">

  &lt;?php
  class MiControladorController extends Controller {

    function mifuncion() {

    } // end function miFuncion

  } // end class MiControladorController

  </code></pre>
      <span>Luego, dentro de mi función imprimirmos hola mundo.</span>
      <pre><code class="php">

  &lt;?php
  class MiControladorController extends Controller {

    function mifuncion() {
      echo "Hola mundo!";
    } // end function miFuncion

  } // end class MiControladorController

  </code></pre>
      <span>Listo, vamos al explorador y navegamos a </span><a href="http://localhost/misitio/micontrolador/mifuncion"><span>http://localhost/misitio/micontrolador/mifuncion</span></a>
      <img  src="http://rsphp.espino.info/files/2016/05/holamundo.png" alt="holamundo" />
      <span>Simple ¿Verdad?. Ok, ahora hagamos un ejemplo con parámetros.</span>
      Para poder utilizar parámetros, utilizamos el formato
      http://localhost/misitio/micontrolador/mifunction/miparametro1/miparametro2
      En dónde los parámetros son pasados como valores, estos son declarados en la función:
      <pre><code class="php">

  &lt;?php
  class MiControladorController extends Controller {

    function mifuncion($nombre, $edad) {
      echo "Hola $nombre! ¿Tienes $edad años? Eres joven.";
    } // end function miFuncion

  } // end class MiControladorController

  </code></pre>
      Ejemplo: http://localhost/micontrolador/mifuncion/luis/32
      <img  src="http://rsphp.espino.info/files/2016/05/holaluis32.png" alt="hola-luis-32" />
      Ahora, si vuelves a http://localhost/misitio/micontrolador/mifuncion, te devolverá un error.
      <img  src="http://rsphp.espino.info/files/2016/05/holamundoerror-1024x211.png" alt="hola-mundo-error" />
      Esto es por que nuestra funcion está esperando los parámetros que declaramos.
      <span>Muy bien, ahora crearemos una vista. Las vistas se crean dentro del directorio "views".</span>
      <ul>
        <li>
          misitio
          <ul>
            <li>
              application
              <ul>
                <li>
                  views
                  <ul>
                    <li>mivista.php</li>
                  </ul>
                </li>
              </ul>
            </li>
          </ul>
        </li>
      </ul>
      <pre><code class="html">

  &lt;h1&gt;Hola!&lt;/h1&gt;
  &lt;p&gt;Esta es una vista.&lt;/p&gt;

  </code></pre>
      Ahora crearemos otra funcion llamada index en el controlador "MiControlador".  Esta función en el framework es la <strong>función por defecto</strong>, si no establecemos una funcion en la url, se ejecutará esta función. Como es la clase por defecto la pondré arriba de "MiFuncion".
      <pre><code class="php">

  &lt;?php
  class MiControladorController extends Controller {

    /**
    * Default function
    */
    function index() {

    } // end function index

    function mifuncion($nombre, $edad) {
      echo "Hola $nombre! ¿Tienes $edad años? Eres joven.";
    } // end function miFuncion

  } // end class MiControladorController

  </code></pre>
      Ahora, dentro de esta función mandaremos cargar la vista, utilizando la clase "View", pasandole cómo parámetro la ruta de la vista, el archivo mivista.php, sin la extensión. La ruta es relativa al directorio "views", ya que el framework buscará aqui las vistas.
      <pre><code class="php">

  &lt;?php
  class MiControladorController extends Controller {

    /**
    * Default function
    */
    function index() {
      View::load('mivista');
    } // end function index

    function mifuncion($nombre, $edad) {
      echo "Hola $nombre! ¿Tienes $edad años? Eres joven.";
    } // end function miFuncion

  } // end class MiControladorController

  </code></pre>

      <p>Ahora podemos visitar http://localhost/micontrolador asi sin nombre de la función, ya que index es por defecto:</p>
      <img src="http://rsphp.espino.info/files/2016/05/holavista.png" alt="hola-vista" />
      <p>En el siguiente ejemplo, pasaremos una serie de datos a la vista, para poblarla. En la vista puedes especificar variables con la notación php $mivariable, y pasarselas dentro un arreglo al cargarla. El framework mapeará sustituirá las variables por los valores, así:
      /application/views/mivista.php</p>
      <pre><code class="php">
  <h1>Hola $nombre, de $edad años!</h1>
  <p>Esta es una vista.</p>
  </code></pre>
      /application/controllers/micontroladorcontroller.php
      <pre><code class="php">
  class MiControladorController extends Controller {

    /**
     * Default function
     */
    function index() {
      $data['$nombre'] = 'Luis!';
      $data['$edad'] = 32;
      View::load('mivista', $data);
    } // end function index

    function mifuncion($nombre, $edad) {
      echo "Hola $nombre! ¿Tienes $edad años? Eres joven.";
    } // end function miFuncion

  } // end class MiControladorController

  </code></pre>
      <img src="http://rsphp.espino.info/files/2016/05/holaluis32vista.png" alt="hola-luis-32-vista"  />
      <br />
      Genial! Ahora vamos a hacer un ejemplo de un formulario:
      Primero lo primero, donde van a estar los componentes: Tendremos una página misdatos en /misitio/micontrolador/captura, en esta página capturaremos nuestro nombre y edad, luego al hacer clic en "Mostrar" iremos a /misitio/micontrolador/misdatos donde se nos mostrarán los datos.
      Lo primero es crear las funciones en el controlador, para que las direcciones funcionen correctamente:
      en /application/controllers/micontroladorcontroller.php agregamos estas funciones:
      <pre><code class="php">
     /**
     * Muestra mis datos
     * url: $baseUrl/micontrolador/misdatos
     */
    function misdatos() {
      //  Obtenemos los datos enviados por la forma
      //  "Input" es la clase del framework de donde obtendremos
      //  toda la información enviada en las peticiones
      //  aqui esta $_POST, $_GET, $_REQUEST, etc
      //  Simplemente lo mandamos llamar con ::get('nombreParametro')
      $nombre = Input::get('nombre');
      $edad = Input::get('edad');

      //  Los pasamos aun arreglo
      //  notemos que pasamos la llave con el formato
      //  de variable de php $miVariable
      $data['$nombre'] = $nombre;
      $data['$edad'] = $edad;

      //  Mando llamar la vista "misdatos" y le paso la información obtenida
      //  del input
      View::load('misdatos', $data);
    } // end function misdatos
  </code></pre>
      Como vemos, necesitaremos un par de vistas. Lo importante aqui es la utilización de la clase estática "Input" del framework, es la de la cual obtendremos los parámetros enviados en las peticiones, desde cualquier método (post, get, put, delete, etc).
      Para obtener los parámetros mandamos llamar Input::get('nombreParametro'). Así de simple.
      Ahora, en /application/views/captura.php:
      <pre><code class="html">
        &lt;form action=&quot;misdatos&quot; method=&quot;post&quot;&gt;
          &lt;label&gt;Tu nombre:&lt;/label&gt;
          &lt;input
              type=&quot;text&quot;
              name=&quot;nombre&quot;
              /&gt;
          &lt;br&gt;
          &lt;label&gt;Tu edad:&lt;/label&gt;
          &lt;input
              type=&quot;text&quot;
              name=&quot;edad&quot;
              /&gt;
          &lt;br&gt;
          &lt;input
              type=&quot;submit&quot;
              value=&quot;Mostrar&quot;
              /&gt;
        &lt;/form&gt;

  </code></pre>
      Y en /application/views/misdatos.php:
      <pre><code class="html">
        &lt;h1&gt;Hola $nombre!&lt;/h1&gt;
        &lt;h2&gt;&#191;Tienes $edad? &#161;Ya estas viejo!&lt;/h2&gt;
  </code></pre>
      Y listo, si visitamos http://localhost/misitio/micontrolador/captura:
      <img src="http://rsphp.espino.info/files/2016/05/captura.png" alt="captura"  />
      Capturamos información:
      <img src="http://rsphp.espino.info/files/2016/05/captura-datos.png" alt="captura-datos"  />
      Hacemos clic en "Mostrar":
      <img src="http://rsphp.espino.info/files/2016/05/mostrar-datos.png" alt="mostrar-datos"  />
      Genial! Ahora pondremos interesantes las cosas, vamos crear una base de datos en MySql, creamos una tabla y luego configuramos una conexión en la aplicación.
      Utilizamos MySQL por simplicidad y popularidad, ya que practicamente todo hosting lo tiene, sin embargo, recomendamos ampliamente para desarrollos serios <a href="http://www.postgresql.org/">PostgreSQL</a> como motor de base de datos.
      Primero, creamos la base de datos misitio en MySql:
      <pre><code class="sql">
  CREATE DATABASE
    `misitio`
  COLLATE
    'utf8_unicode_ci';
  </code></pre>
      Segundo, creamos la tabla personas.
      <pre><code class="sql">
  CREATE TABLE
      personas (
      	persona_id INT AUTO_INCREMENT NOT NULL,
          nombre VARCHAR(50) NOT NULL,
          edad INT NOT NULL,
          CONSTRAINT
              pk_personas
          PRIMARY KEY (
              persona_id
          ),
          CONSTRAINT
          	unq_nombre
          UNIQUE (
          	nombre
          )
      );
  </code></pre>
      Tercero, configuramos la conexión en la apliación, esto se realiza en la carpeta config, archivo app.json, creamos una sección llamada "dbConnections":
      <pre><code class="javascript">
  {
  	"appName": "MiSitio",
  	"dbConnections": [
  		{
  			"name": "default",
  			"driver": "mysql",
  			"hostName": "127.0.0.1",
  			"databaseName": "misitio",
  			"userName": "root",
  			"password": ""
  		}
  	]
  }
  </code></pre>
      Como podemos ver el nombre de la conexión es default, lo que nos indica que es la conexión por defecto. Se pueden tener varias conexiones, pero solo una puede ser la de defecto. Para la conexión de defecto no es necesario especificar el nombre al crear la instancia de la clase Db, que de la que nos valdremos para acceder y modificar datos.
      Antes que nada, vamos a redactar la especificación funcional:
      Lo que capturemos en /misitio/personas/alta se guardará en la tabla personas.
      La información de la tabla personas se mostrará en /misitio/personas/lista
      Aqui podremos seleccionar una persona para editar su información o para eliminarla.
      Listo, ahora manos a la obra:
      Primero, crearemos un nuevo controlador llamado PersonasController en /application/controllers/personascontroller.php
      <pre><code class="php">
  &lt;?php
  class PersonasController extends Controller {

    /**
     * Crea una instancia de la clase PersonasController
     */
    function __construct() {

    } // end function __construct

  } // end class PersonasController
  </code></pre>
      Segundo, crearemos las funciones alta(), lista(), editar() y borrar() en /application/controllers/personascontroller.php
      <pre><code class="php">
  &lt;?php
  class PersonasController extends Controller {

    /**
     * Crea una instancia de la clase PersonasController
     */
    function __construct() {

    } // end function __construct

    /**
     * Mostrará el formulario para dar de alta|
     */
    function alta() {

    } // end function alta

    /**
     * Mostrará la lista de las personas
     */
    function lista() {

    } // end function lista

    /**
     * Mostrará el formulario para editar datos de personas
     */
    function editar() {

    } // end function editar

    /**
     * Guarda la información de la persona en el banco
     */
    function guardar() {

    } // end function guardar

    /**
     * Tomará el registro de una persona y lo eliminará
     */
    function borrar() {

    } // end function borrar

  } // end class PersonasController
  </code></pre>
      Tercero, codificamos su implementación en una librería. Una librería es una clase en la cual nos apoyamos para implementar funcionalidad, y dejar al controlador solamente como un "despachador", que obtenga las peticiones, mande llamar a librerias y otras clases para hacer el trabajo y entre una respuesta.
      Por lo tanto, creamos una libreria en /application/libraries/personaslib.php
      <pre><code class="php">
  &lt;?php
  /**
   * Contiene la lógica de negocios para administrar personas
   */
  class PersonasLib {


  } // end class PersonasLib
  </code></pre>
      Ahora implementamos las funciones en el controlador:
      <pre><code class="php">
  &lt;?php
  class PersonasController extends Controller {

    /**
     * La libreria de personas
     */
    protected $lib;

    /**
     * Crea una instancia de la clase PersonasController
     */
    function __construct() {
      $this->lib =new PersonasLib();
    } // end function __construct

    /**
     * Mostrará el formulario para dar de alta|
     */
    function alta() {
      View::load('personas/registro');
    } // end function alta

    /**
     * Mostrará la lista de las personas
     */
    function lista() {
      $listaPersonas = $this->lib->lista();
      $tablaPersonas = $this->lib->tabla( $listaPersonas );

      $data['$tablaPersonas'] = $tablaPersonas;

      View::load('personas/lista', $data);
    } // end function lista

    /**
     * Mostrará el formulario para editar datos de personas
     */
    function editar( $personaId ) {
      //  Consultamos persona con la librería
      $persona = $this->lib->consultar( $personaId );
      //  Formamos $data con persona y title
      $data['$persona'] = $persona;
      $data['$title'] = "Editar Persona";
      //  Cargamos la vista
      View::load('personas/registro', $data);
    } // end function editar

    /**
     * Guarda la información de la persona en el banco
     */
    function guardar() {
      $guardado = $this->lib->guardar();
      if ( $guardado ) {
        View::load('personas/exito');
      } else {
        View::load('personas/exito');
      } // end if guardado
    } // end function guardar

    /**
     * Tomará el registro de una persona y lo eliminará
     * @param $personaId int El id de la persona a eliminar
     */
    function borrar( $personaId ) {
      $borrado = $this->lib->borrar( $personaId );
      if ( $borrado ) {
        Uri::redirect(BASE_URL . '/personas/lista');
      } else {
        Uri::redirect(BASE_URL . '/personas/lista');
      } // end if borrado
    } // end function borrar

  } // end class PersonasController

  </code></pre>
      Como podemos observar, necesitaremos las siguientes funciones en PersonasLib:
      lista();
      tabla();
      borrar();
      consultar();
      guardar();
      Ahora, declaramos estas funciones en /application/libraries/personaslib.php
      <pre><code class="php">
  &lt;?php
  /**
   * Contiene la lógica de negocios para administrar personas
   */
  class PersonasLib {

    /**
     * Regresa un conjunto de registros con las personas
     */
    function lista() {

    } // end function lista

    /**
     *  Regresa una table HTML con el listado de personas
     *  @param $data array Es un conjunto de resultados a utilizar para formar
     *  la tabla
     */
    function tabla( $data ) {

    } // end function tabla

    /**
     * Elimina a una persona de la base de datos
     */
    function borrar() {

    } // end function borrar

    /**
     * Consulta los datos de una persona en la base de datos
     * @param $personaId El id de la persona que queremos consultar
     */
    function consultar( $personaId ) {

    } // end function consultar

    /**
     *  Guarda los datos de una persona en la base de datos
     */
    function guardar() {

    } // end function guardar

  } // end class PersonasLib

  </code></pre>
      Ahora, como necesitaremos acceso a datos, implementaremos una variable protegida en PersonasLib para poder hacer consultas:
      <pre><code class="php">
  /**
   * Contiene la lógica de negocios para administrar personas
   */
  class PersonasLib {

    /**
     * @var Db acceso a datos en la libreria
     */
    protected $db;

    /**
     * Crea una nueva instancia de PersonasLib
     */
    function __construct() {
      //  Instanciamos el acceso a datos
      //  Db espera el nombre de la conexión previamente configurada
      //  si lo establecemos, tomará la de "default"
      $this->db = new Db();

      //  Pero también puede ser así:
      $this->db = new Db('default');

      //  Cuando queramos instanciar otra conexión
      //  pasamos su nombre a Db
    } // end function __construct
  </code></pre>
      Db es la clase del framework que nos ayuda con el acceso a datos. Db, al ser instanciada, espera que pasemos como parámetro un nombre de una conexión a datos previamente configurada. Si omitimos este parámetro, buscará la conexión por defecto, "default".
      Ahora vamos a codificar la funcion "guardar()"; esperamos las variables que serán los campos del registro, y realizaremos una función upsert, es decir, que pued ser un update o un insert, si nos envian el campo personaId, entonces será un update, pero si no, quiere decir que el registro aún no existe y será un insert:
      <pre><code class="php">
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

  </code></pre>
      Ahora lo que necesitamos es la vista application/views/personas/registro.php y la vista /application/views/personas/exito.php.
      registro.php, que nos ayudará tanto a dar de alta como a modificar los registros:
      <pre><code class="html">
        &lt;h1&gt;$title&lt;/h1&gt;
        &lt;form action=&quot;$baseUrl/personas/guardar&quot; method=&quot;post&quot;&gt;
          &lt;div style=&quot;width: 20%;&quot;&gt;
            &lt;label&gt;Id:&lt;/label&gt;
          &lt;/div&gt;
          &lt;div style=&quot;width: 40%;&quot;&gt;
            &lt;input
              type=&quot;text&quot;
              disabled
              value=&quot;$persona-&gt;persona_id&quot;
              /&gt;
            &lt;input
              type=&quot;hidden&quot;
              id=&quot;personaId&quot;
              name=&quot;personaId&quot;
              value=&quot;$persona-&gt;persona_id&quot;
              /&gt;
          &lt;/div&gt;
          &lt;br /&gt;
          &lt;div style=&quot;width: 20%;&quot;&gt;
            &lt;label&gt;Nombre:&lt;/label&gt;
          &lt;/div&gt;
          &lt;div style=&quot;width: 40%;&quot;&gt;
            &lt;input
              type=&quot;text&quot;
              id=&quot;nombre&quot;
              name=&quot;nombre&quot;
              value=&quot;$persona-&gt;nombre&quot;
              /&gt;
          &lt;/div&gt;
          &lt;br /&gt;
          &lt;div style=&quot;width: 20%;&quot;&gt;
            &lt;label&gt;Edad:&lt;/label&gt;
          &lt;/div&gt;
          &lt;div style=&quot;width: 40%;&quot;&gt;
            &lt;input
              type=&quot;number&quot;
              id=&quot;edad&quot;
              name=&quot;edad&quot;
              value=&quot;$persona-&gt;edad&quot;
              /&gt;
          &lt;/div&gt;
          &lt;br /&gt;
          &lt;input
              type=&quot;submit&quot;
              value=&quot;Guardar&quot;
              /&gt;
        &lt;/form&gt;
  </code></pre>
      Como podemos ver, en los valores de los controles del formulario estamos estableciendo la variable $persona y sus propiedades ( $persona->persona_id, $persona->nombre etc ), esto quiere decir que estará esperenda que se le pase como parte del model una variable que sea de tipo "persona" ( en nuestro caso pasaremos un arreglo ) para obtener las propiedades de la misma.
      Establecemos un control input text disables y un hidden para "persona_id", ya que los controles disabled no viajan en las peticiones al hacer un "submit".
      Notarás también que utilizamos una variable que no pasamos en $data, $baseUrl, esta variable es la variable global del framework BASE_URL, la clase View la incluye automáticamente.
      Ahora modificamos la función "alta()" en el controlador "PersonasController" para que soporte las variables, le enviaremos "$persona" vacia.
      <pre><code class="php">

    /**
     * Mostrará el formulario para dar de alta|
     */
    function alta() {

      //  configuramos un conjunto vacio, por que le estamos pasando
      //  dato vacios, esto limpiará los valores en la forma
      //  lo que estamos haciendo es pasar un registro "nuevo"
      $persona['persona_id'] = '';
      $persona['nombre'] = '';
      $persona['edad'] = '';
      //  Creamos data y pasamos persona como variable
      $data['$persona'] = $persona;
      //  Agregamos el título a aparecer en el encabezado
      $data['$title'] = 'Alta de Persona';

      //  Cargamos la vista
      View::load('personas/registro', $data);
    } // end function alta
  </code></pre>
      exito.php:
      <pre><code class="html">
        &lt;h1&gt;&#201;xito!&lt;/h1&gt;
        &lt;p&gt;
          Haz clic &lt;a href=&quot;lista&quot;&gt;aqu&#237;&lt;/a&gt; para ir al listado de personas
        &lt;/p&gt;
  </code></pre>
      Si te fijas, estoy poniendo los acentos tal cuales en las vistas, ya que el framework automáticamente se encargará de obtener el HTML correcto para ellos.
      Pues bien, ahora si naveguemos a http://localhost/personas/alta y capturemos informción:
      <img src="http://rsphp.espino.info/files/2016/05/alta-persona.png" alt="alta-persona"  />
      Al hacer clic en "Guardar":
      <img src="http://rsphp.espino.info/files/2016/05/exito.png" alt="exito"  />
      Ahora, procedamos a realizar la consulta de la información, será un simple "select all", en /application/libraries/personaslib.php, función "lista()":
      <pre><code class="php">
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
      //  de la clase Db, construyendo dinámicamente la consulta:
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

  </code></pre>
      **IMPORTANTE** En el código se ilustran varias formas de obtener un resultado, pero solo una es necesaria.
      Tanto "query" como "get" regresan un arreglo de arreglos asociativos, donde cada arreglo asociativo es un renglón de una tabla. Siempre va a regresar esta estructura, aunque sea un solo renglón o busques un solo campo, regresará un conjunto de arreglos, representando renglones. Para regresar un solo registro o un solo campo, tenemos otros métodos como "first" y "scalar", que los veremos posteriormente.
      Para ilustrarlo mejor, si le dieramos un print_r a $result esto nos imprimiría:
      <pre><code class="php">
  Array
  (
      [0] => Array
          (
              [persona_id] => 1
              [nombre] => Luis Espino
              [edad] => 32
          )
  )
  </code></pre>
      Ahora, crearemos una tabla a partir de los resultados de la consulta, en /application/libraries/personaslib.php, function "tabla()":
      <pre><code class="php">
  /**
     *  Regresa una table HTML con el listado de personas
     *  @param $data array Es un conjunto de resultados a utilizar para formar
     *  la tabla
     */
    function tabla( $data ) {

      //  Utilizaremos la clase Html para crear la table
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

      //  Mandamos llamar la funcion estática "dataTable" de la clase Html
      //  le pasamos como parpametros:
      $personasTable =
        Html::dataTable(
          $data, // El arreglo que contiene los datos que mostrará la tabla
          $options, // el arreglo de configuración para la tabla
          true // true si queremos que nos devuelva una cadena con la tabla,
              //false (o excluyendolo) para imprimirla directamente
        );

      //  Regresamos una cadena con el HTML de la tabla
      return $personasTable;
    } // end function tabla
  </code></pre>
      Utilizamos la clase Html del framework, con la función "dataTable" para obtener la tabla. Esta función necesita una fuente de datos y un arreglo de configuración para poder crear el HTML de la tabla.
      La fuente de datos es una rreglo de arreglos asociativos, un conjunto de resultados obtenidos con Db.
      La configuración determina las opciones de la tabla, como su id y sus atributos, así como el conjunto de columnas que mostrará la tabla.
      Cada columna puede ser de varios tipos, desde texto simple, pasando por hipervínculos e imágenes hasta controles de formulario como cajas de texto y listas de selección.
      Los comentarios del código explican en cada paso estas configuraciones.
      Pues bien, nos hace falta crear la vista, en /application/views/personas/lista.php:
      <pre><code class="html">
        &lt;h1&gt;Lista de Personas&lt;/h1&gt;
        &lt;a href=&quot;alta&quot;&gt;Nuevo registro&lt;/a&gt;
        &lt;br&gt;&lt;br&gt;
        &lt;!-- Aqui va a ir la tabla: --&gt;
        $tablaPersonas
  </code></pre>
      Ahora si nos dirigimos a http://localhost/personas/lista:
      <img src="http://rsphp.espino.info/files/2016/05/lista-personas.png" alt="lista-personas"  />
      Como podemos ver tenemos la tabla. Hagamos clic en "Nuevo registro", insertemos uno nuevo y volvamos a lista:
      <img src="http://rsphp.espino.info/files/2016/05/lista-personas2.png" alt="lista-personas-2"  />
      Ok, ahora pacemos a editar el registro. si revisamos lo que codificamos en la función "editar()" del controlador PersonasController necesitaremos consultar el registro de la persona y una vista editar.php. Comencemos por consultar el registro de la personas, a partir del parámetro "$personaId", en PersonasLib /application/libraries/personaslib.php, funciton "consultar()":
      <pre><code class="php">
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
      //  los métodos asistentes de Db, construyendo
      //  la consulta dinámicamente
      $persona =
        $this->db
        ->form('personas')                  // de personas
        ->where('persona_id', $personaId)   // donde persona_id = $personaId
        ->first();                          //  traemos el primer registro (ya no es necesario hacer $result[0])

      return $persona;
    } // end function consultar
  </code></pre>
      Ahora si hacemos clic en "Editar" en el primer registro en http://localhost/personas/lista:
      <img src="http://rsphp.espino.info/files/2016/05/editar-persona.png" alt="editar-persona"  />
      Notemos la notación de la url: http://localhost/personas/editar/1 -> http://localhost/controlador/function/parametro
      Ahora hacemos unas modificaciones:
      <img src="http://rsphp.espino.info/files/2016/05/editar-persona-2.png" alt="editar-persona-2"  />
      Hacemos clic en "Guardar" y luego visitamos http://localhost/personas/lista:
      <img src="http://rsphp.espino.info/files/2016/05/lista-personas-2.png" alt="lista-personas-2"  />
      Ya editamos registros, genia! Ahora vamos a borrar el registro, implementarémos la lógica en /application/libraries/personaslib.php, function "borrar()":
      <pre><code class="php">
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

      //  Mandamos llamar "nonQuery" de Db
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
  </code></pre>
      Ahora vamos a http://localhost/mistio/personas/lista y hacemos clic en "Borrar" de un registro:
      <img src="http://rsphp.espino.info/files/2016/05/persona-borrada.png" alt="persona-borrada"  />
    <p>
      Pues bien, creamos registros, los editamos borramos y listamos, cumpliendo con el objetivo:
      Simple, mantenible, ordenado y sin código spaguetti.
    </p>
    <p>
      Es algo verboso, ¿No hay una manera más sencilla? Si la hay, introducimos los modelos.
      Los modelos en RsPhp son ORM (Object Relational Mapping), son clases que representan tablas
      en la base de datos.
    </p>
    <p>
      Por favor, para más detalle, hecha un ojo a las funciones del framework aqui: <i><b><a href="$baseUrl/doc">Documentación</a></b></i>
    </p>
    </div>