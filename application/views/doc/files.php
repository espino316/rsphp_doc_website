<div class="content content-min-height">
  <h1>Sistema de Archivos</h1>
  <p>
    Podemos manejar tanto los directorios como los archivos de manera sencilla con dos clases básicas: <b>Directory</b> y <b>File</b>.
  </p>
  <h2 id="directory">Directory</h2>
  <p>
    Como su nombre lo indica, es un asistente para manejar directorios, nos permite verificar si un directorio existe, listar archivos en directorios, directorios en directorios, crear y eliminar carpetas.
  </p>
  <p>
    <ul>
      <br />
      <li>
        <b><i>exists( $directoryPath )</i></b>
        <p>Pasamos el nombre de un directorio y nos devuelve verdadero si existe. Caso contario nos devolverá falso. Su parámeto es:</p>
        <ul>
          <li>
            <b>$directoryPath</b>.- Es una cadena, la ruta del directorio del cual deseamos verificar su existencia.
          </li>
        </ul>
        <br />
      </li>
      <br/ >
      <li>
        <b><i>getFiles( $directoryPath, $extensions = nul )</i></b>
        <p>
          Con esta función obtenemos un listado de archivos a partir de una ruta de directorio, permitiendonos aplicar filtros por extensión de archivo. Sus parámetros son:
        </p>
        <ul>
          <li>
            <b>$directoryPath</b>.- Es un cadena, la ruta del directorio del cual deseamos listar los archivos.
          </li>
          <li>
            <b>$extensions</b>.- Es un arreglo de cadenas, un listado de extensiones de archivo para filtrar los resultados.
          </li>
          <p>
            Ejemplo del código:
          </p>
          <pre>
            <code class="php">
              function getfilesTest() {
                $directoryPath = '/home/luis';
                $files = Directory::getFiles( $directoryPath );
                print_r( $files );
              } // end function getFiles
            </code>
          </pre>
          <p>
            Lo que imprimiría algo como esto:
          </p>
          <pre>
            <code class="php">
              Array
              (
                  [0] => /home/luis/doc.html
                  [1] => /home/luis/phones.txt
                  [2] => /home/luis/centosrecipes.txt
                  [3] => /home/luis/screenshot103024.jpg
              )
            </code>
          </pre>
        </ul>
        <br />
      </li>
      <br/ >
      <li>
        <b><i>getDirectories( $directoryPath )</i></b>
        <p>
          Esta función regresa un listado de directorios a partir de una ruta de un directorio particular. Tiene un único parámetro:
        </p>
        <ul>
          <li>
            <b>$directoryPath</b>.- Es un cadena, la ruta del directorio del cual deseamos listar los directorios.
          </li>
          <p>
            Ejemplo del código:
          </p>
          <pre>
            <code class="php">
              function printDirectories() {
                $directoryPath = '/home/luis';
                $directories = Directory::getDirectories( $directoryPath );
                print_r( $directories );
              } // end function printDirectories
            </code>
          </pre>
          <p>
            Lo que imprimiría algo como esto:
          </p>
          <pre>
            <code class="php">
              Array
              (
                  [0] => /home/luis/apps
                  [1] => /home/luis/config
                  [2] => /home/luis/lib
                  [3] => /home/luis/tmp
              )
            </code>
          </pre>
        </ul>
      </li>
      <br/ ><br />
      <li>
        <b><i>create( $directoryPath )</i></b>
        <p>
          Crea un directorio. Si el directorio ya existe, devolverá un error. Tiene solo un parámetro:
        </p>
        <ul>
          <li>
            <b>$directoryPath</b>.- Es la ruta del directorio a crear.
          </li>
          <p>
            Ejemplo del código:
          </p>
          <pre>
            <code class="php">
              //  La ruta deseada:
              $directoryPath = '/home/luis/docs';
              //  Crea un directorio
              if ( Directory::create( $directoryPath ) {
                //  Hacer algo con el directorio
                echo( "Directorio $directoryPath creado.");
              } // end if Directory create
            </code>
          </pre>
        </ul>
      </li>
      <br/ ><br />
      <li>
        <b><i>delete( $directoryPath, $recursive = false )</i></b>
        <p>
          Elimina un directorio. Puede hacerlo de forma recursiva y eliminar todos los archivos y sub directorios dentro del mismo.
        </p>
        <ul>
          <li>
            <b>$directoryPath</b>.- Es la ruta del directorio a eliminar.
          </li>
          <li>
            <b>$recursive</b>.- Por defecto es "false". Si se especifica como "true", aplicar recursividad y elimina todo dentro del directorio, incluidos sub directorios.
          </li>
          <p>
            Ejemplo del código:
          </p>
          <pre>
            <code class="php">
              //  La ruta deseada:
              $directoryPath = '/home/luis/tmp';
              //  Borra el directorio
              if ( Directory::delete( $directoryPath ) {
                //  Hacer algo con el directorio
                echo( "Directorio $directoryPath ha sido borrado.");
              } // end if Directory delete

              //  La ruta deseada:
              $directoryPath = '/home/luis/tmp2';
              //  Borra el directorio y todos sus sub directorios
              if ( Directory::delete( $directoryPath, true ) {
                //  Hacer algo con el directorio
                echo( "Directorio $directoryPath y todos su contenido ha sido borrado.");
              } // end if Directory delete
            </code>
          </pre>
        </ul>
      </li>
    </ul>
  </p>


  <h2 id="files">File </h2>
  <p>
    Nos asiste en el manejo de archivos, para checar si existen, crearlos, leerlos, modificarlos, eliminarlos y moverlos. Es una clase estática y tiene las siguientes funciones:
  </p>
  <ul>
    <li>
      <b><i>exists( $filePath )</i></b>
      <p>
        Devuelve "true" Si el archivo existe. Su único parámetro es:
      </p>
      <br />
      <ul>
        <li>
          <b>$filePath</b>.- Es una cadena que contiene la ruta al archivo a verificar.
        </li>
      </ul>
      <p>
        Ejemplo:
      </p>
      <pre>
        <code class="php">
          if ( File::exists( '/home/luis/phonelist.text' ) ) {
            //  Do something with the file
            echo "Files exists";
          } // end if file exists
        </code>
      </pre>
    </li>
    <br />
    <li>
      <b><i>read( $filePath )</i></b>
      <p>
        Devuelve el contenido del archivo en una cadena. Tiene un parámetro:
      </p>
      <ul>
        <li>
          <b>$ filePath</b>.- Es la ruta del archivo a leer.
        </li>
      </ul>
      <p>
        Ejemplo:
      </p>
      <pre>
        <code class="php">
          //  Obtener el contenido del archivo
          $content = File::read( '/home/luis/phonelist.text' );
          //  Hacer algo con el contenido
          echo "El contenido del archivo es $content";
        </code>
      </pre>
    </li>
    <br />
    <li>
      <b><i>write( $filePath, $content = "", $append = false )</i></b>
      <p>
        Escribe contenido a un archivo. Tiene los siguiente parámetros:
      </p>
      <ul>
        <li>
          <b>$filePath</b>.- Es la ruta al archivo que deseamos escribir.
        </li>
        <li>
          <b>$content</b>.- Es el contenido que deseamos escribir en el archivo.
        </li>
        <li>
          <b>$append</b>.- Indica si se agregará el contenido al actual (si es true) o si se reemplazará (si es false, por defecto).
        </li>
      </ul>
      <pre>
        <code class="php">
          //  Obtener el contenido del archivo
          $content = 'Hola mundo! Este es un texto importante';
          //  Escribir el contenido
          File::write( '/home/luis/prueba.text', $content );

          // O, si queremos agregar el contenido
          File::write( 'home/luis/prueba.text', $content, true );
        </code>
      </pre>
    </li>
    <br />
    <li>
      <b><i>delete( $filePath )</i></b>
      <p>
        Borra un archivo. Tiene los siguiente parámetros:
      </p>
      <ul>
        <li>
          <b>$filePath</b>.- Es la ruta al archivo que deseamos escribir.
        </li>
      </ul>
      <pre>
        <code class="php">
          //  Borra el archivo
          File::delete( '/home/luis/phonelist.text' );
        </code>
      </pre>
    </li>
    <br/ >
    <li>
      <b><i>copy( $fileOrigin, $fileDestination )</i></b>
      <p>
        Copia un archivo de una ubicación a otra.
      </p>
      <ul>
        <li>
          <b>$fileOrigin</b>.- Es la ruta al archivo que deseamos copiar.
        </li>
        <li>
          <b>$fileDestination</b>.- Es la ruta a donde deseamos copiar el archivo.
        </li>
      </ul>
      <pre>
        <code class="php">
          //  Copia el archivo
          File::copy(
            'home/luis/phonelist.text',
            'home/cosme/fulanito/phonelist2.text'
          );
        </code>
      </pre>
    </li>

    <br/ >
    <li>
      <b><i>move( $fileOrigin, $fileDestination )</i></b>
      <p>
        Mueve un archivo de una ubicación a otra. Sirve para renombrar el archivo.
      </p>
      <ul>
        <li>
          <b>$fileOrigin</b>.- Es la ruta al archivo que deseamos mover.
        </li>
        <li>
          <b>$fileDestination</b>.- Es la nueva ruta que deseamos que tenga el archivo.
        </li>
      </ul>
      <pre>
        <code class="php">
          //  Mueve el archivo
          File::move(
            'home/luis/phonelist.text',
            'home/cosme/fulanito/phonelist2.text'
          );
        </code>
      </pre>
    </li>

    <br/ >
    <li>
      <b><i>getExtension( $filePath )</i></b>
      <p>
        Obtiene la extensión de un archivo.
      </p>
      <ul>
        <li>
          <b>$filePath</b>.- Es la ruta al archivo.
        </li>
      </ul>
      <pre>
        <code class="php">
          //  Obtiene la ruta del archivo
          $ext = File::getExtension( $filePath );
          //  Haz algo con la extensión
          echo $ext;
        </code>
      </pre>
    </li>

    <br/ >
    <li>
      <b><i>writeToResponse( $filePath )</i></b>
      <p>
        Escribe el contenido de un archivo a la respuesta http. Limpia el buffer de salida, obtiene el tipo de archivo y escribe el contenido.
      </p>
      <ul>
        <li>
          <b>$filePath</b>.- Es la ruta al archivo.
        </li>
      </ul>
      <pre>
        <code class="php">
          //  Escribe un archivo a la respuesta http.
          File::writeToResponse( '/home/luis/pic.jpg' );
        </code>
      </pre>
    </li>

  </ul>
</div>
