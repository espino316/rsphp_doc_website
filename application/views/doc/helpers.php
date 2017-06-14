<div class="content content-min-height">
  <h1>Asistentes</h1>
  <p>
    Los asistentes son clases con funciones que nos ayudan a crear nuestro programa. Tenemos asistentes
    para fechas, cadenas y url's.
  </p>
  <h2 id="date">Fechas</h2>
  <p>
    La clase <b>Date</b> contiene las funciones para asistirnos con las fechas.
  </p>
  <ul>
    <li>
      <b><i>now()</i></b>
      <p>
        Devuelve una cadena con la fecha actual en formato yyyy-MM-dd HH:mm:ss. No tiene parámetros.
      </p>
      <p>
        Ejemplo de uso:
      </p>
      <pre>
        <code class="php">
          echo Date::now();
          // Imprime:
          $dateTime
        </code>
      </pre>
    </li>
    <li>
      <b><i>diff( $interval, $date1, $date2 )</i></b>
      <p>
        Calcula la diferencia entre dos fechas. Tiene los siguientes parámetros.
      </p>
      <ul>
        <li>
          <b>$interval</b><i>( String )</i>.- Es un caracter, que puede ser:
          <ul>
            <li>y Para año</li>
            <li>m Para mes</li>
            <li>d Para dia</li>
            <li>h Para hora</li>
            <li>i Para minuto</li>
            <li>s Para segundo</li>
          </ul>
        </li>
        <li>
          <b>$date1</b><i>( String / Datetime )</i>.- Es la primera fecha a considerar para la diferencia. Puede ser un objeto Datetime o una cadena en formato fecha.
        </li>
        <li>
          <b>$date2</b><i>( String / Datetime )</i>.- Es la segunda fecha a considerar para la diferencia. Puede ser un objeto Datetime o una cadena en formato fecha.
        </li>
      </ul>
      <p>
        Regresa un valor flotante, la diferencia entre ambas fechas.
      </p>
      <p>
        Ejemplo de uso:
      </p>
      <pre>
        <code class="php">
          $date1 = '2013-11-23 08:34:12';
          $date2 = '2016-10-23 22:23:54';
          echo "Diferencia en: \n";
          echo "Años: "     . Date::diff( 'y', $date1, $date2 ) . "\n";
          echo "Meses: "    . Date::diff( 'm', $date1, $date2 ) . "\n";
          echo "Dias: "     . Date::diff( 'd', $date1, $date2 ) . "\n";
          echo "Horas: "    . Date::diff( 'h', $date1, $date2 ) . "\n";
          echo "Minutos: "  . Date::diff( 'i', $date1, $date2 ) . "\n";
          echo "Segundos: " . Date::diff( 's', $date1, $date2 ) . "\n";

          //  Imprime:
          //  Diferencia en:
          //  Años:     2.9172745075671
          //  Meses:    35.007294090805
          //  Dias:     1065.5345138889
          //  Horas:    25572.828333333
          //  Minutos:  1534369.7
          //  Segundos: 92062182
        </code>
      </pre>
    </li>
  </ul>

  <h2 id="string">Cadenas</h2>
  <p>
    La clase <b>String</b> es la encargada de asistirnos con las cadenas. Tiene la siguientes funciones:
  </p>
  <ul>
    <li>
      <b><i>isUppercase( $string )</i></b>
      <p>
        Determina si $string está en mayúsculas. Su único parámetro es:
      </p>
      <ul>
        <li>
          <b>$string</b><i>( String )</i>.- Es la cadena que deseamos verificar si es mayúsculas.
        </li>
      </ul>
      <p>
        Ejemplo de uso:
      </p>
      <pre>
        <code class="php">
          $isUpper = Str::isUppercase( 'HELLO MY NAME IS' );
          //  Imprime true
          echo $isUpper;

          $isUpper = Str::isUppercase( 'hello my name is' );
          //  Imprime false
          echo $isUpper;
        </code>
      </pre>
    </li>
    <li>
      <b><i>contains( $str, $val )</i></b>
      <p>
        Regresa true si la cadena $val se encuentra en la cadena $str. Sus parámetros son:
      </p>
      <ul>
        <li><b>$str</b><i>(String)</i>.- Cadena en la cual se buscará un contenido.</li>
        <li><b>$val</b><i>(String)</i>.- Cadena de texto que se buscará dentro de otra.</li>
      </ul>
      <p>
        Ejemplo de uso:
      </p>
      <pre>
        <code class="php">
          if ( Str::contains( 'Hola me llamo Luis', 'llamo' ) ) {
            //  Do something if contains
            echo "Esta contenida!";
          } else {
            //  Do something id not contains
            echo "No está contenida";
          } // end if then else contains
        </code>
      </pre>
    </li>
    <li>
      <b><i>startsWith( $str, $value )</i></b>
      <p>
        Verifica si una cadena de texto comienza con otra. Sus parámetros son:
      </p>
      <ul>
        <li><b>$str</b><i>( String )</i>.- </li> Es la cadena en la cual buscar.
        <li><b>$value</b><i>( String )</i>.- </li> El valor a buscar en el principio de la cadena.
      </ul>
      <p>
        Ejemplo de uso:
      </p>
      <pre>
        <code class="php">
          $str = "Hola me llamo Slim Shady!";
          $value = "Hola";
          //  Verifica si $str comienza con $value
          //  Imprime 1
          echo Str::startsWith( $str, $val );

          //  Cambiamos el valor:
          $value = "me llamo"
          //  Verifica si $str comienza con $value
          //  Imprime 0, es false
          echo Str::startsWith( $str, $val );
        </code>
      </pre>
    </li>
    <li>
      <b><i>endsWith( $str, $value )</i></b>
      <p>
        Verifica si una cadena de texto termina con otra. Sus parámetros son:
      </p>
      <ul>
        <li><b>$str</b><i>( String )</i>.- </li> Es la cadena en la cual buscar.
        <li><b>$value</b><i>( String )</i>.- </li> El valor a buscar en la final de la cadena.
      </ul>
      <p>
        Ejemplo de uso:
      </p>
      <pre>
        <code class="php">
          $str = "Hola me llamo Slim Shady!";
          $value = "Shady!";
          //  Verifica si $str termina con $value
          //  Imprime 1
          echo Str::endsWith( $str, $val );

          //  Cambiamos el valor:
          $value = "Slim"
          //  Verifica si $str termina con $value
          //  Imprime 0, es false
          echo Str::endsWith( $str, $val );
        </code>
      </pre>
    </li>
    <li>
      <b><i>left( $str, $n )</i></b>
      <p>
        Obtiene la parte izquierda de una cadena de texto, "n" numero de caracteres.
      </p>
      <ul>
        <li><b>$str</b><i>( String )</i>.- </li> Es la cadena de la cual se va a obtener la parte izquierda.
        <li><b>$n</b><i>( Integer )</i>.- </li> El número de caracteres que deseamos devolver.
      </ul>
      <p>
        Ejemplo de uso:
      </p>
      <pre>
        <code class="php">
          $str = "Hola me llamo Slim Shady!";
          $n = 7;
          // Imprime Hola me
          echo Str::left( $str, 7 );
        </code>
      </pre>
    </li>
    <li>
      <b><i>right( $str, $n )</i></b>
      <p>
        Obtiene la parte derecha de una cadena de texto, "n" numero de caracteres.
      </p>
      <ul>
        <li><b>$str</b><i>( String )</i>.- </li> Es la cadena de la cual se va a obtener la parte derecha.
        <li><b>$n</b><i>( Integer )</i>.- </li> El número de caracteres que deseamos devolver.
      </ul>
      <p>
        Ejemplo de uso:
      </p>
      <pre>
        <code class="php">
          $str = "Hola me llamo Slim Shady!";
          $n = 6;
          // Imprime Shady!
          echo Str::right( $str, 6 );
        </code>
      </pre>
    </li>
    <li>
      <b><i>stripAccents( $str )</i></b>
      <p>
        Elimina los acentos en una cadena.
      </p>
      <ul>
        <li><b>$str</b><i>( String )</i>.- </li> Es la cadena de la cual se van a eliminar los acentos.
      </ul>
      <p>
        Ejemplo de uso:
      </p>
      <pre>
        <code class="php">
          $str = "¿Cuál es el avión de él?";
          // ¿Cual es el avion de el?
          echo Str::stripAccentes( $str );
        </code>
      </pre>
    </li>
    <li>
      <b>replace</b>
      <p>
        Reemplaza una cadena por otra, dentro de una tercera cadena de texto. Puedes llamar a esta función dedos maneras,
        la primera es la clásica forma de Php ( busqueda, reemplazo, cadena original ) y la segunda es utilizando un
        diccionario de datos mediante un arreglo asociativo.
      </p>
      <b><i>stringReplace( $search, $replace, $string )</i></b>
      <ul>
        <li><b>$search</b><i>( String / Assoc Array )</i>.- Es la cadena o arreglo de cadenas a buscar.</li>
        <li><b>$replace</b><i>( String / Assoc Array )</i>.- Es la cadena o arreglo de cadenas a reemplazar.</li>
        <li><b>$str</b><i>( String )</i>.- Es la cadena de texto sobre la cual se van a efectuar los reemplazos.</li>
      </ul>
      <pre>
        <code class="php">
          $str = "¡Hola mundo!";
          $search = "mundo";
          $replace = "Luis";
          echo Str::replace( $search, $replace, $str );
          //  Imprime:
          //  ¡Hola Luis!
        </code>
      </pre>
      <b><i>replace( $dictionary, $str )</i></b>
      <ul>
        <li><b>$dictionary</b><i>( Assoc Array )</i>.- Es un arreglo asociativo en el cual la llave es la cadena
          a buscar y el valor es la cadena para reemplazar.</li>
        <li><b>$str</b><i>( String )</i>.- Es la cadena de texto sobre la cual se van a efectuar los reemplazos.</li>
      </ul>
      <pre>
        <code class="php">
          $str = "Luke y Han son rebeldes.";
          $dictionary = array(
            "Luke" => "R2D2",
            "Han" => "C3PO",
            "rebeldes" => "androides"
          );
          echo Str::replace( $dictionary, $str );
          //  Imprime:
          //  R2D2 y C3PO son androides
        </code>
      </pre>
    </li>
    <li>
      <b><i>specialCharsToHTML( $str )</i></b>
      <p>
        Convierte los caracteres especiales en una cadena a html válido.
      </p>
      <ul>
        <li><b>$str</b><i>( String )</i>.- </li> Es la cadena a modificar.
      </ul>
      <p>
        Ejemplo de uso:
      </p>
      <pre>
        <code class="php">
          $str = "¿Eres tú? ¿No perdiste el avión?";
          echo Str::specialCharsToHTML( $str );
          //  Imprime:
          //  &amp;iquest;Eres t&amp;uacute;? &amp;iquest;No perdiste el avi&amp;oacute;n?
        </code>
      </pre>
    </li>
    <li>
      <b><i>toUpper( $str )</i></b>
      <p>
        Convierte la cadena a solo mayúsculas, incluyendo los acentos.
      </p>
      <ul>
        <li><b>$str</b><i>( String )</i>.- </li> Es la cadena a convertir en mayúsculas
      </ul>
      <p>
        Ejemplo de uso:
      </p>
      <pre>
        <code class="php">
          $str = "El invierno está cerca.";
          echo Str::toUpper( $str );
          //  Imprime:
          //  EL INVIERNO ESTÁ ACERCA
        </code>
      </pre>
    </li>
    <li>
      <b><i>toLower( $string )</i></b>
      <p>
        Convierte la cadena a solo minúsculas, incluyendo los acentos.
      </p>
      <ul>
        <li><b>$str</b><i>( String )</i>.- </li> Es la cadena a convertir en minúsculas
      </ul>
      <p>
        Ejemplo de uso:
      </p>
      <pre>
        <code class="php">
          $str = "LO SIENTO DAVE. NO PUEDO HACER ESO.";
          echo Str::toLower( $str );
          //  Imprime:
          //  lo siento dave. no puedo hacer eso.
        </code>
      </pre>
    </li>
    <li>
      <b><i>UUID()</i></b>
      <p>
        Regresa un identificador universal único. No tiene parámetros.
      </p>
      <p>
        Ejemplo de uso:
      </p>
      <pre>
        <code class="php">
          echo Str::UUID();
          //  Imprime:
          //  4c15901f-e57d-4f8c-9327-016b1c368f22
        </code>
      </pre>
    </li>
    <li>
      <b><i>random( $len, $useSymbols )</i></b>
      <p>
        Regresa una cadena aleatoria de "n" caracteres.
      </p>
      <ul>
        <li><b>$len</b><i>( Integer )</i>.- </li> La longitud que tendrá la cadena.
        <li><b>$useSymbols</b><i>( Boolean, default = false )</i>.- </li> Indica si deberán utilizarse caracteres especiales. Por defecto esfalse.
      </ul>
      <p>
        Ejemplo de uso:
      </p>
      <pre>
        <code class="php">
          echo Str::random( 20 );
          //  Imprime:
          //  XystBZ2Zk9AhEyPjj8TG

          echo Str::random( 15, true );
          //  Imprime:
          //  -}mySMKjx%lX):k
        </code>
      </pre>
    </li>
    <li>
      <b><i>isBase64( $str )</i></b>
      <p>
        Devuelve true si la cadena es base 64
      </p>
      <ul>
        <li><b>$str</b><i>( String )</i>.- </li> La cadena de texto a verificar si es base 64.
      </ul>
      <p>
        Ejemplo de uso:
      </p>
      <pre>
        <code class="php">
          if ( Str::isBase64( 'TmV2ZXIgYXJndWUgd2l0aCB0aGUgZGF0YS4=' ) ) {
            //  Hacer algo si es base 64
            echo "Exito! Es base 64";
          } else {
            //  Hacer algo si no
            echo "Lo siento, no es base 64";
          }  // end if then else is base64
        </code>
      </pre>
    </li>
  </ul>
</div>
