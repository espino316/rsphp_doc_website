<div class="content content-min-height">
  <h1>Manejo de la Consola</h1>
  <p>
    Podemos correr comandos para asistirnos en el uso del framework. Para ello hechamos mano de la consola,
    llamando el script rs.php en el directorio raíz.
  </p>
  <p>
    Debemos llamar al programa php, pasarle como parámetros el script rs.php y los parámetros adecuados, según sea
    lo que queremos realizar.
  </p>
  <p>
    Por ejemplo, para mostrar la versión del framework tecleamos:
  </p>
  <pre>
    <code class="bash">
      php rs.php version
    </code>
  </pre>
  <h2 id="cleanapp">Limpiar la aplicación</h2>
  <p>
    Podemos limpiar la aplicación, es decir, borrar todos los controladores, vistas, librerías y modelos,
    usamos este comando:
  </p>
  <pre>
    <code class="bash">
      php rs.php cleanapp
    </code>
  </pre>
  <h2 id="createcontroller">Crear un Controlador</h2>
  <p>
    Para crear un controlador, utilizamos la sintaxis create controller nommbreDelControlador
  </p>
  <pre>
    <code class="bash">
      php rs.php create controller micontrolador "Documentación"
    </code>
  </pre>
  <p>
    Dónde <b>micontrolador</b> Es el nombre del controllador y <b>"Documentación"</b>, así, entre comillas,
    es la documentación o comentarios que agregaremos al controlador
    Este comando creará un archivo en /application/controllers/
  </p>
  <p>
    El archivo se llamará micontroladorcontroller.php
  </p>
  <p>
    Contendrá la declaración de la clase, extendida de Controller y las funciones __construct() e index()
  </p>
  <h2 id="createmodel">Crear un Modelo</h2>
  <p>
    Para crear un modelo, utilizamos la sintaxis create model nombreDeTabla
  </p>
  <pre>
    <code class="bash">
      php rs.php create model mitabla
    </code>
  </pre>
  <p>
    El framework consultará la base de datos y construirá el modelo a partir de los datos de la tabla.
    Previamente debimos declarar una conexión a bases de datos por defecto.
  </p>
  <p>
    <b>*IMPORTANTE*</b> El framework está preparado para crear modelos basados en una estructura de llaves
    surrogadas, es decir, con un campo autonumérico como llave principal.
  </p>
  <p>
    El framework creará un archivo en /application/models con el nombre de la tabla y el sufijo model: mitablemodel.php
  </p>
  <p>
    Puedes ver como utilizar los modelos aqui: <a href="$baseUrl/doc/models">Modelos</a>.
  </p>
</div>