<div class="content content-min-height">
  <h1>Entrada de datos</h1>
  <p>
    Para manejar la entrada de datos utilizamos la clase estática <b>Input</b>
  </p>
	<p>
		Todo lo que los exploradores manden por formularios o por peticiones "post" y otros métodos, tambien via línea de comandos
		( de hecho todo lo que llegue a php://input )	las podemos acceder de esta manera:
	</p>
	<p>
    Ejemplo:
  </p>
  <pre>
    <code class="php">
      //  Obtenemos la variable nombre
      $name = Input::get('name');
    </code>
  </pre>
  <h2 id="files">Guardar archivos</h2>
  <p>
    Para guardar archivos que carguemos a nuestros sitios utilzaremos la funcion <b>Input::saveUploadedFile</b>.
    Esta función guarda el archivo y nos devuelve la ruta completa donde lo guardó. Acepta los siguientes parámetros:
  </p>
  <p>
    El método <i><b>"tripleDesEncrypt"</b></i>, acepta como parámetro:
    <ul>
      <li>
        <b>$key</b>.- El nombre del control que contiene el archivo.
        El valor del atributo "name" en el objeto input de tipo file en el html original.
      </li>
      <li>
        <b>$folder</b>.- Opcional, La carpeta donde guardaremos el archivo. De no proporcionarse,
        se guardará en /public/files
      </li>
      <li>
        <b>$name</b>.- Opcional, es el nombre que tendrá el archivo guardado. De no proporcionarse, se
        utilizará el nombre del archivo que subimos.
      </li>
      <li>
        <b>$conditions</b>.- Opcional, es un arreglo que contiene las condiciones que debe cumplir el archivo
        para que se proceda a guardar.
        Las condiciones son:
        <ul>
          <li>
            <i>MAX_SIZE</i>.- El tamaño máximo del archivo en bytes.
          </li>
          <li>
            <i>MIME_TYPES</i>.- Es un arreglo con los tipos que aceptará la aplicación para guardar el archivo,
            en formato mime.
          </li>
        </ul>
      </li>
    </ul>
  </p>
  <p>
    Ejemplo:
  </p>
  <pre>
    <code class="php">
      //  Establecemos las condiciones
      //  Máximo un mega
      $conditions["MAX_SIZE"] = 1000000;
      //  jpg o png
      $conditions["MIME_TYPES"][] = 'image/jpeg';
      $conditions["MIME_TYPES"][] = 'image/png';

      //  El nombre del archivo será su timestamp
      $name = time();

      //  El nombre del control es inputFile
      // &lt;input type=&quot;file&quot; name=&quot;inputFile&quot;...
      $key = "inputFile";

      //  Mandamos guardar el archivo
      $fileName =
        Input::saveUploadedFile(
          "inputFile",
          null, // que sea la carpeta por defecto
          $name,
          $conditions
        );

      echo $fileName;
      // Imprime /c/var/www/misitio/public/files/1474991768.jpg
    </code>
  </pre>
</div>
