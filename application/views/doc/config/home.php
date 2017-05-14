<div class="content content-min-height">
  <h1>Configuración</h1>
  <p>
    Para configurar la aplicación utilizamos el archivo /config/app.json. Es un conjunto
    de datos json en los que cada clave es un elemento a configurar.
  </p>

  <h2 id="appName">Nombre de la aplicación</h2>
  <p>
    Este archivo es capaz de configurar el nombre de la aplicación con la clave "appName",
    esta variable podrá ser accesida en toda la aplicación mediante $appName en las vistas
    y APP_NAME en el código Php.
  </p>
  <h3>Configuración:</h3>
  <pre>
    <code class="json">
      {
        "appName": "Mi asombrosa aplicación"
      }
    </code>
  </pre>
  <h3>Utilización (código):</h3>
  <pre>
    <code class="php">
      echo "Mi aplicación se llama: " . APP_NAME;
    </code>
  </pre>
  <h3>Utilización (vistas):</h3>
  <pre>
    <code class="html">
      &lt;span&gt;Nombre de la aplicaci&#243;n: $appName&lt;/span&gt;
    </code>
  </pre>

  <h2 id="globals">Variables globales</h2>
  <p>
    Para configurar más variables globales, utilizamos la clave "globals", siendo esta un elemento
    json, en la que cada clave es el nombre de la variable global y su valor es especificado como
    el valor de la clave.
    Podremos acceder a esta información con la sintaxis App::get('nombreVariableGlobal') en nuestro
    código.
  </p>
  <h3>Configuración:</h3>
  <pre>
    <code class="json">
      {
        "globals": {
          "EMAIL_ADMIN": "luis@espino.info"
        }
      }
    </code>
  </pre>
  <h3>Utilización:</h3>
  <pre>
    <code class="php">
      print_r( App::get('EMAIL_ADMIN') );
    </code>
  </pre>

  <h2 id="routes">Rutas</h2>
  <p>
    Las rutas representan url's o direcciones web. La configuración las "apunta" a controladores y funciones.
    Se configura con la clave "routes" y es un arreglo de elementos json.
  </p>
  <p>
    Cada elemento cuenta con las claves:
    <ul>
      <li>
        <strong>method</strong>.- Se refiere al método HTTP ( GET, POST, DELETE, PUT, HEAD ).
        Para especificar "cualquiera" utilizamos asterisco "*".
      </li>
      <li>
        <strong>url</strong>.- La url a la que entrará el usuario, por ejemplo: <i>clientes/nuevo</i>
      </li>
      <li>
        <strong>newUrl</strong>.- La url interna con la sintaxis controlador/funcion que ejecutará el framework,
        por ejemplo: <i>customer/newcustomer</i>. Aqui el framework ejecutará la función "newCustomer"
        del controlador "Customer".
      </li>
    </ul>
  </p>
  <h3>Configuración:</h3>
  <pre>
    <code class="json">
      {
        "routes": [
          {
            "method": "*",
            "url": "",
            "newUrl": "default"
          },
          {
            "method": "GET",
            "url": "cliente/nuevo",
            "newUrl": "customer/newcustomer"
          }
        ]
      }
    </code>
  </pre>
  <p>
    Los segmentos restantes son considerados como parámetros para la función, por ejemplo:
  </p>
  <pre>
    <code class="json">
      {
        "routes": [
          {
            "method": "*",
            "url": "usuarios/detalle",
            "newUrl": "users/get"
          }
        ]
      }
    </code>
  </pre>
  <p>
    Suponiendo que nuestro sitio es misitio.com, al entrar a <i>http://misitio.com/usuarios/detalle/123</i>, el framework ejecutará:
  </p>
  <pre>
    <code class="php">
      $UsersController->get('123');
    </code>
  </pre>
  <p>
    Pueden ser multiples parámetros.
  </p>
  <p>
    Otra manera de declarar parámetros es con dos puntos (":"), así:
  </p>
  <pre>
    <code class="json">
      {
        "routes": [
          {
            "method": "*",
            "url": "admin/empresas/:id/usuarios/nuevo",
            "newUrl": "admin/adduser/:id"
          }
        ]
      }
    </code>
  </pre>
  <p>
    Al entrar a <i>http://misitio.com/admin/empresas/123/usuarios/nuevo</i> el framework ejecutará:
  </p>
  <pre>
    <code class="php">
      $AdminController->addUser('123');
    </code>
  </pre>
  <p>
    <strong>**IMPORTANTE**</strong> Si se utiliza este método, los parámetros deben ser numéricos.
  </p>

  <h2 id="dbconn">Conexiones a Bases de Datos</h2>
  <p>
    Utilizamos la clave: "dbConnections", siendo un arreglo de objetos json.
    Podemos declarar cuantas conexiones a bases de datos deseemos, asignándole un nombre a cada una
    de ellas, sin poderse repetir este dato. Debemos declarar además:
    <ul>
      <li>
        <strong>driver</strong>.- mysql, pgsql, sqlserver, dblib. Son los drivers hasta el momento soportados.
      </li>
      <li>
        <strong>hostName</strong>.- La dirección ip o nombre de host del servidor.
      </li>
      <li>
        <strong>databaseName</strong>.- El nombre de la base de datos.
      </li>
      <li>
        <strong>userName</strong>.- El nombre de usuario de la base de datos.
      </li>
      <li>
        <strong>password</strong>.- El password del usuario de la base de datos.
      </li>
      <li>
        <strong>port</strong>.- Opcional, el puerto del servidor de base de datos.
      </li>
    </ul>
  </p>
  <h3>Configuración:</h3>
  <pre>
    <code class="json">
      {
        "dbConnections": [
          {
            "name": "miSitio",
            "driver": "mysql",
            "hostName": "127.0.0.1",
            "databaseName": "misitio",
            "userName": "luis",
            "password": "myPass"
          }
        ]
      }
    </code>
  </pre>
  <h3>Utilización:</h3>
  <pre>
    <code class="php">
      $db = new Db( 'miSitio' );
      $sql = "SELECT * FROM customers";
      $resultSet = $db->query( $sql );
    </code>
  </pre>
  <p>
    Para utilizar una conexion por defecto, simplemente la nombramos como "default", luego en las
    declaraciones no tenemos que especificar un nombre de conexión.
  </p>
  <h3>Configuración:</h3>
  <pre>
    <code class="json">
      {
        "dbConnections": [
          {
            "name": "default",
            "driver": "pgsql",
            "hostName": "127.0.0.1",
            "databaseName": "mydb",
            "userName": "luis",
            "password": "myPass"
          }
        ]
      }
    </code>
  </pre>
  <h3>Utilización:</h3>
  <pre>
    <code class="php">
      $db = new Db(); // no es necesario el nombre
      $sql = "SELECT * FROM customers";
      $resultSet = $db->query( $sql );
    </code>
  </pre>

  <h2 id="datasource">Fuentes de Datos</h2>
  <p>
    Las fuentes de datos pueden ser consultas a bases de datos, a una tabla o multiples tablas, y pueden estar parametrizadas.
    El framework ejecutará la fuente de datos automáticamente y devolverá el resultado.
  </p>
  <p>
    Toda fuente de datos tiene que tener una conexión especificada, a la base de datos de la cual tomará la información.
  </p>
  <p>
    Para declararla utilizamos la clave "dataSources", es un arreglo de objetos Json, en el que para cada objeto debemos
    especificar:
    <ul>
      <li>
        <strong>connection</strong>.- El nombre de la conexión de base de datos a utilizar.
      </li>
      <li>
        <strong>name</strong>.- El nombre de la fuente de datos. No debe repetirse.
      </li>
      <li>
        <strong>type</strong>.- SQLQUERY, especifica una consulta SQL.
      </li>
      <li>
        <strong>text</strong>.- El texto de la consulta, el enunciado SQL.
      </li>
      <li>
        <strong>parameters</strong>.- Opcional, un arreglo de objetos json, en el cual cada uno especifica:
        <ul>
          <li>
            <strong>name</strong>.- El nombre del parámetro.
          </li>
          <li>
            <strong>type</strong>.- El tipo de parámetro (session para obtenerlo de la sesión actual, input para obtenerlo
            del usuario, ya sea mediante POST o GET)
          </li>
          <li>
            <strong>defaultValue</strong>.- Opcional, el valor por defecto del parámetro
          </li>
        </ul>
      </li>
    </ul>
  </p>
  <h3>Configuración:</h3>
  <pre>
    <code class="json">
      {
        "dataSources": [
          {
              "connection": "default",
              "name": "dsMedals",
              "type": "SQLQUERY",
              "text": "SELECT * FROM medals"
          }
        ]
      }
    </code>
  </pre>
  <h3>Utilización:</h3>
  <pre>
    <code class="php">
      $dsName = "dsMedals";
      $result = Db::getResultFromDataSource( $dsName, null);
      print_r( $result );
    </code>
  </pre>


  <p>
    La estructura completa de la configuraicón se ve así:
  </p>
  <pre>
    <code class="json">
      {
        "appName": "MiSitio",
        "globals": {
          "EMAIL_ADMIN": "luis@espino.info"
        },
        "routes": [
          {
            "method": "*",
            "url": "",
            "newUrl": "default"
          }
        ],
        "dbConnections": [
          {
            "name": "default",
            "driver": "mysql",
            "hostName": "127.0.0.1",
            "databaseName": "misitio",
            "userName": "luis",
            "password": "myPass"
          }
        ],
        "dataSources": [
          {
              "connection": "default",
              "name": "dsCustomers",
              "type": "SQLQUERY",
              "text": "SELECT * FROM customer WHERE customer_id = :customerId",
              "parameters": [
                {
                  "name": "customerId",
                  "type": "session",
                  "defaultValue": "1"
                }
              ]
          }
        ]
      }
    </code>
  </pre>
</div>