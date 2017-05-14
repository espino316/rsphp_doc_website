<div class="content content-min-height">
  <h1>Encriptación</h1>
  <p>
    Se utiliza una encriptación Triple DES
  </p>
  <h2>Encriptar una cadena</h2>
  <p>
    Para encriptar una cadena, instanciamos un objeto asistente Crypt, y llamamos el método
    tripleDesEncrypt.
  </p>
  <p>
    El método <i><b>"tripleDesEncrypt"</b></i>, acepta como parámetro:
    <ul>
      <li>
        <b>$data</b>.- La cadena a encriptar.
      </li>
    </ul>
  </p>
  <p>
    Regresa una cadena encriptada. Ejemplo:
  </p>
  <pre>
    <code class="php">
      $data = "luis";
      $ch = new Crypt();
      echo $ch->tripleDesEncrypt( $data );
      // Imprime 07851a08014498da
    </code>
  </pre>
  <h2>Desencriptar una cadena</h2>
  <p>
    Para encriptar una cadena, instanciamos un objeto asistente Crypt, y llamamos el método
    tripleDesDecrypt.
  </p>
  <p>
    El método <i><b>"tripleDesDecrypt"</b></i>, acepta como parámetro:
    <ul>
      <li>
        <b>$data</b>.- La cadena a desencriptar.
      </li>
    </ul>
  </p>
  <p>
    Regresa una cadena desencriptada. Ejemplo:
  </p>
  <pre>
    <code class="php">
      $data = "07851a08014498da";
      $ch = new Crypt();
      echo $ch->tripleDesDecrypt( $data );
      // Imprime luis
    </code>
  </pre>
</div>