<div class="content content-min-height">
  <h1>Controladores</h1>
  <p>
    Los controladores son los encargados de "despachar" las peticiones de los clientes. Son los ejecutores,
    toman una petición, la procesan y devuelven un resultado. Un controlador debe existir para que la aplicación
    pueda responder una petición.
  </p>
  <p>
    Los controladores, vistos desde fuera, en las url, son como subdirectorios, y las funciones como archivos. En
    realidad, conjuntamente un controlador y una función es un comando, una petición de una acción a la aplicación.
  </p>
  <p>
    Los controladores son archivos que se encuentran en /application/controllers. Todos tienen la convencion
    de nombre de archivo <i>nombrecontroladorcontroller.php</i>. Es decir, el nombre del controlador, unido al sufijo
    "controller", con la extensión ".php".
  </p>
  <p>
    Todos los controladores son clases, con la convención MiControladorController y todos extienden la clase Controller.
  </p>
  <pre>
    <code class="php">
      MiControladorController extends Controler {

      } // end class MiControlladorController
    </code>
  </pre>
  <p>
    Los controladores necesitan de al menos dos funciones, __construct() e index().
  </p>
  <p>
    <b>__construct</b> crea una instancia de la clase, como sabemos.
    <br />
    <b>index</b> es como la home, como index.html o index.php, la función por defecto, que se ejecutará si no se
      solicita explícitamente una función del controlador.
  </p>
  <pre>
    <code class="php">
      MiControladorController extends Controller {
        /**
         * Crea una instancia del controlador
         */
        function __construct() {

        } // end function __construct

        /**
         * Es la home, la función por defecto, si no se llama a ninguna otra
         * Url: &#36;baseUrl/micontrolador/
         */
        function index() {

        } // end function index
      } // end class MiControlladorController
    </code>
  </pre>
  <h2 id="json">JSON Response</h2>
  <p>
    Para entregar los datos en formato JSON en lugar de HTML, contamos con la función <b>jsonResponse()</b>, que acepta los siguientes parámetros:
    <ul>
      <li>
        <b>$data</b>.- Es un arreglo que convertiremos a JSON y los escribiremos en la respuesta web.
      </li>
    </ul>
  </p>
  <p>
    No regresa datos, escribe directamente el JSON como si llamaramos <i>"echo"</i>. Ejemplo:
  </p>
  <pre>
    <code class="php">
      CustomerController extends Controller {

        /**
         * Devuelve datos de un usuario en json
         */
        function getUserData() {
          //  Preparamos los datos
          $data["name"]= "luis";
          $data["lastname"] = "espino";
          $data["customer_id"] = 12959;
          //  Los escribimos en json
          $this->jsonResponse( $data );
        } // end function getUserData

      } // end class CustomerController
    </code>
  </pre>
  <p>
    Al visitar http://misitio.com/customer/getuserdata nos devolverá:
  </p>
  <pre>
    <code class="json">
      {
        "name": "luis",
        "lastname": "espino",
        "customer_id": "12959
      }
    </code>
  </pre>

  <h2 id="xml">XML Response</h2>
  <p>
    Para entregar los datos en formato XML en lugar de HTML, contamos con la función <b>xmlResponse()</b>, que acepta los siguientes parámetros:
    <ul>
      <li>
        <b>$data</b>.- Es un arreglo que convertiremos a XML y los escribiremos en la respuesta web.
      </li>
    </ul>
  </p>
  <p>
    No regresa datos, escribe directamente el XML como si llamaramos <i>"echo"</i>. Ejemplo:
  </p>
  <pre>
    <code class="php">
      CustomerController extends Controller {

        /**
         * Devuelve datos de un usuario en json
         */
        function getUserData() {
          //  Preparamos los datos
          $data["name"]= "luis";
          $data["lastname"] = "espino";
          $data["customer_id"] = 12959;
          //  Los escribimos en xml
          $this->xmlResponse( $data );
        } // end function getUserData

      } // end class CustomerController
    </code>
  </pre>
  <p>
    Al visitar http://misitio.com/customer/getuserdata nos devolverá:
  </p>
  <pre>
    <code class="xml">
      &lt;data&gt;
        &lt;name&gt;luis&lt;/name&gt;
        &lt;lastName&gt;espino&lt;/lastName&gt;
        &lt;customer_id&gt;32&lt;/customer_id&gt;
      &lt;/data&gt;
    </code>
  </pre>

  <h2 id="cors">CORS Headers</h2>
  <p>
    En ocasiones queremos permitir que otros sitios consuman nuestros servicios con javascript, mediante algun API REST. En estos casos, tenemos que permitir CORS (Cross Origin Resource Sharing). Para lograrlo necesitamos establecer ciertos headers en la respuesta http.
  </p>
  <p>
    Esto puede ser logrado fácilmente con la función <b>setCORSHeaders()</b> de la clase <b>Controller</b>. No tiene parámetros y se utiliza de la siguiente manera:
  </p>
  <pre>
    <code class="php">
      CustomerController extends Controller {

        /**
         * Devuelve datos de un usuario en json
         */
        function getUserData() {
          //  Preparamos los datos
          $data["name"]= "luis";
          $data["lastname"] = "espino";
          $data["customer_id"] = 12959;
          //  Los escribimos en json
          //  Permitiendo acceso con CORS
          $this->setCORSHeaders();

          //  Ahora si escribimos los datos
          $this->jsonResponse( $data );
        } // end function getUserData

      } // end class CustomerController

    </code>
  </pre>
</div>
