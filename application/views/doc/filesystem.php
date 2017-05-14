<div class="content content-min-height">
  <h1>Sistema de Archivos</h1>
  <p>
    Podmos manejar tanto los directorios como los archivos de manera sencilla con dos clases básicas: <b>Directory</b> y <b>File</b>.
  </p>
  <h2 id="directory">Directory</h2>
  <p>
    Como su nombre lo indica, es un asistente para manejar archivos, nos permite verificar si un directorio existe, listar archivos en directorios, directorios en directorios, crear y eliminar carpetas.
  </p>
  <p>
    <ul>
      <li>
        <b><i>exists( $directoryPath )</i></b>
        <p>Pasamos el nombre de un directorio y nos devuelve verdadero si existe. Caso contario nos devolverá falso. Su parámeto es:</p>
        <ul>
          <li>
            <b>$directoryPath</b>.- Es una cadena, la ruta del directorio del cual deseamos verificar su existencia.
          </li>
        </ul>
      </li>
      <li><b>getFiles</b></li>
      <li><b>getDirectories</b></li>
      <li><b>create</b></li>
      <li><b>delete</b></li>
    </ul>
  </p>
</div>
