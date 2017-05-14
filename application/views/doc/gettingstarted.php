<div class="content content-min-height">
    <h1>Iniciando con RSPhp</h1>

    <h2>Instalación</h2>

    <p>Creamos una carpeta en un directorio accesible al servidor web, como <i>/var/www</i> o <i>C:\xampp\htdocs</i> si usas Xampp sobre Windows. En este caso usaremos "misitio" como el nombre de la carpeta, pero tu puedes usar la que gustes :).</p>

    <p>Si utilizas Windows recomiendo utilizar Git Bash o MinSys para que te vayas acostumbrando a los comandos linux :).</p>

    <pre><code>
      cd /var/www
      mkdir misitio
      cd misitio
    </pre></code>

    <p>Luego, lo siguiente que vamos a hacer es instalarlo:</p>

    <pre><code>
      composer require espino316/rsphp
    </pre></code>

    <p>Luego, desde la línea de comandos:</p>

    <pre><code>
      ./rsphp init --default
    </pre></code>

    <p>Esto creará la estructura de directorios y archivos necesarios de la aplicación:</p>

    <pre>
    * application
      * Controllers
      * Libraries
      * Models
      * Views
    * config
    * public
      * css
      * img
      * js
      * index.php
      * .htaccess
    * .htaccess
    </pre>

    <p>A continuación, desde la línea de comandos podemos teclear:</p>

    <pre><code>
      ./rsphp help
    </pre></code>

    <h2>Tu primer controlador</h2>

    <p>Esto nos mostrará la ayuda de la línea de comandos, cuando la necesitemos.</p>

    <p>Lo primero que vamos a hacer ahora es crear un controlador. Desde la línea de comandos tecleamos:</p>

    <pre><code>
      ./rsphp controller create --name=Default --doc="Controlador por defecto"
    </pre></code>

    <p>Esto creará DefaultController.php en el directorio application/Controllers</p>

    <p>Abrimos el archivo y veremos:</p>

    <pre><code class="php">

    namespace Application\Controllers;

    use RSPhp\Framework\Controller;

    /**
     * Controlador por defecto
     */
    class DefaultController extends Controller
    {
        /**
         * Creates a new instance of DefaultController
         */
        function __construct()
        {
        } // end function constructs

        /**
         * The home %baseUrl/Default/
         */
        function index()
        {
        } // end function index

    } // end class DefaultController
    </pre></code>

    <p>Como podemos observar, tenemos que el controlador se encuentra en el namespace <i>Application\Controllers</i> y que utilizamos la clase <i>RSPhp\Framework\Controller.</i></p>

    <p>La función inicial, por defecto es "index". Aqui colocaremos un mensaje:</p>

    <pre><code class="php">
        /**
         * The home %baseUrl/Default/
         */
        function index()
        {
            echo "Hola mundo!";
        } // end function index
    </pre></code>

    <p>Guardamos y navegamos a [http://localhost/misitio](http://localhost/misitio/) y podremos ver el mensaje "Hola mundo!" en la pantalla. Bastante simple ¿No?</p>

    <h3>Las funciones</h3>

    <p>Creando funciones nuevas podemos utilzar nuevas urls, el formato es el siguiente:</p>

    <pre><code>
      http://misitio/nombrecontrolador/nombrefuncion
      ejem.
      http://misitio/mensajes/hola
    </pre></code>

    <p>En el ejemplo anterior no especificamos el nombre del controlador. Esto es por que es el controlador por defect y si navegamos a la home, entonces lo podemos omitir. Sin embargo, si utilizamos algun otro controlador con una función, hemos de especificarlo.</p>

    <p>Pues bien, creemos otro controlador:</p>

    <pre><code>
      ./rsphp controller create --name=Mensajes --doc="Controlador para probar mensajes"
    </pre></code>

    <p>Agreguémosle una función hola:</p>

    <pre><code class="php">
        /**
         * Función para decir hola, url: %baseUrl/mensajes/hola
         */
        function hola()
        {
            echo "Hola!";
        } // end function index
    </pre></code>

    <p>Y naveguemos a [http://localhost/misitio/mensajes/hola](http://localhost/misitio/mensajes/hola).
    Se nos mostrará el mensaje: "Hola!".</p>

    <h3>Los parámetros</h3>

    <p>Para pasarle parámetros a la función, podemos especificarlos en el código y agregarlos directamente a la url, así:</p>

    <pre><code class="php">
        /**
         * Función para decir hola, url: %baseUrl/mensajes/hola
         */
        function hola( $name )
        {
            echo "Hola! $name";
        } // end function index
    </pre></code>

    <p>Y naveguemos a http://localhost/misitio/mensajes/hola/luis.</p>
    <p>Se nos mostrará el mensaje "Hola luis!".</p>

    <p>Podemos especificar más parámetros de la misma forma:</p>

    <pre><code class="php">
        /**
         * Función para decir hola, url: %baseUrl/mensajes/hola
         */
        function hola( $name, $age )
        {
            echo "Hola! $name, de $age años";
        } // end function index
    </pre></code>

    <p>Y naveguemos a [http://localhost/misitio/mensajes/hola/luis/33](http://localhost/misitio/mensajes/hola/luis/33).
    Se nos mostrará el mensaje "Hola luis, de 33 años!".
    Como podemos ver es bastante natural y simple.</p>

    <h2>Las Vistas</h2>

    <p>Ahora procederemos a utilizar vistas. Las vistas se almacenarán en la carpeta <i>application/Views</i> y serán archivos * .php.</p>

    <p>Vamos a crear una vista, estas se almacenarán en application/Views, así que allí crearemos el archivo sample.php.</p>

    <pre><code class="html">
        &lt;html&gt;
          &lt;head&gt;&lt;title&gt;Ejemplo&lt;/title&gt;&lt;/head&gt;
          &lt;body&gt;Este es un ejemplo&lt;/body&gt;
        &lt;/html&gt;
    </pre></code>

    <p>Para utilizar las vistas, usaremos la clase View y la función load. Creemos otra función en el controlador "Mensajes" y añadamos la referencia a la clase. Queda de la siguiente manera, la clase completa:</p>

    <pre><code class="php">

    namespace Application\Controllers;

    use RSPhp\Framework\Controller;
    use RSPhp\Framework\View;

    /**
     * Controlador de Mensajes
     */
    class MensajesController extends Controller
    {
        /**
         * Creates a new instance of MensajesController
         */
        function __construct()
        {
        } // end function constructs

        /**
         * The home %baseUrl/Mensajes/
         *
         * @return null
         */
        function index()
        {
        } // end function index

        /**
         * Dice "Hola"
         *
         * @param String $name El nombre a mostrar
         * @param String $age La edad a mostrar
         *
         * @return null
         */
        function hola( $name, $age )
        {
            echo "Hola $name, de $age años";
        } // end function hola

        /**
         * Muestra una vista
         *
         * @return null
         */
        function ejemplo() {
            View::load("sample");
        } // end function ejemplo

    } // end class MensajesController
    </pre></code>

    <p>Esto cargará el archivo application/View/ejemplo.php y los escribirá, como si hubiesemos hecho un "echo".</p>

    <h3>Variables en las Vistas</h3>

    <p>Para usar variables en las vistas utilizaremos la notación <b>$nombreVariable</b> como si estuvieramos usando php directamente. Las variables las pondremos en un arreglo, con la llave incluyendo el símbolo "$" y las pasaremos como parámetro en la función <b>View::load</b>, así:</p>

    <b>La vista:</b>
    <pre><code class="html">
        &lt;label&gt;$myTitle&lt;/label&gt;
    </pre></code>

    <b>El código php</b>
    <pre><code class="php">
        $data["$myTitle"] = "Mi asombroso artículo";
        View::load( "miVista", $data );
    </pre></code>

    <p>Simple ¿no? Ahora vamos a hacer el ejemplo con el nombre y la edad del ejemplo anterior, creamos un archivo <b>hola.php</b> en el directorio <b>application/Views</b> que quedaría así:</p>

    <pre><code class="html">
        &lt;html&gt;
          &lt;head&gt;&lt;title&gt;Hola&lt;/title&gt;&lt;/head&gt;
          &lt;body&gt;
            &lt;h1&gt;Hola $name, de $age años.&lt;/h1&gt;
          &lt;/body&gt;
        &lt;/html&gt;
    </pre></code>

    <p>Ahora modificamos la función hola para que quede de la siguiente manera:</p>

    <pre><code class="php">
    /**
     * Dice "Hola"
     *
     * @param String $name El nombre a mostrar
     * @param String $age La edad a mostrar
     *
     * @return null
     */
    function hola( $name, $age )
    {
        $data['$name'] = $name;
        $data['$age'] = $age;
        View::load( "hola", $data );
    } // end function hola
    </pre></code>

    <p>Como podemos ver, es la mar de sencillo utilizar RSPhp. Checa sus asombrosas funcionalidades en la <i><a href="$baseUrl/doc">documentación</a></i>.</p>


</div>
