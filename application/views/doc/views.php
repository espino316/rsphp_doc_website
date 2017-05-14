<div class="content content-min-height">
  <h1>Las Vistas</h1>
  <p>
    Las vistas son simples archivos *.html, aunque deben tener la extensión *.php, la mejor práctica es que
    el contenido sea html puro, sin código php ni templates, evitando completamente el código spaguetti.
  </p>
  <p>
    Todas las vistas deben estar dentro del directorio "/application/views", pudiendo crear subdirectorios
    dentro del mismo.
  </p>
  <p>
    Para cargar una vista, utilizamos la clase "View" y su método estático "load", pasando como parámetro
    el nombre del archivo y su ruta, desde "/application/views", sin la extensión php, ejemplo:
  </p>
  <pre>
    <code class="php">
      //  El archivo es /application/views/mivista.php
      View::load( 'mivista' );

      //  El archivo es /application/views/micarpeta/miotravista.php
      View::load( 'micarpeta/miotravista.php' );
    </code>
  </pre>
  <h2 id="utf">Preparada para UTF Español</h2>
  <p>
    Por defecto, las vistas "convierten" los caractéres con acento, las ñ's y los signos de exclamación
    e interrogación a su equivalente en html, por lo que puedes escribir tu contenido directamente en español
    y el framework se encargará de encodificarlo en html.
  </p>
  <p>
    Así, en lugar de escribir esto:
  </p>
  <pre>
    <code class="html">
      &amp;aacute;, &amp;eacute;, &amp;iacute;, &amp;oacute;, &amp;uacute;, &amp;ntilde;, &amp;#191;, &amp;#161;
    </code>
  </pre>
  <p>
    Escribo esto directamente:
  </p>
  <pre>
    <code class="html">
      á, é, í, ó, u, ñ, ¿, ¡
    </code>
  </pre>

  <h2 id="dynamicContent">Contenido Dinámico</h2>
  <p>
    Para imprimirles dinamismo podemos pasarles datos con un arreglo al mandar cargar la vista, así:
  </p>
  <p>
    Este es el contenido de la vista:
  </p>
  <pre>
    <code class="html">
      &lt;h1&gt;Hola $nombre&lt;/h1&gt;
      &lt;p&gt;&#191;C&#243;mo estas?&lt;/p&gt;
    </code>
  </pre>
  <p>
    La mandamos llamar así:
  </p>
  <pre>
    <code class="php">
      $data['$nombre'] = "Luis";
      View::load( 'mivista', $data );
    </code>
  </pre>
  <p>
    Esto es lo que vemos:
  </p>
  <pre>
    <code>
      <h1>Hola Luis</h1>
      <p>¿Cómo estas?</p>
    </code>
  </pre>
  <p>
    Como podemos ver, el framework sustituye automáticamente el valor de las variables.
  </p>
  <h3 id="baseUrl">Base Url</h3>
  <p>
    La variable <strong>$&#98;&#97;&#115;&#101;&#85;&#114;&#108;</strong> puede ser utilizada en todas las vistas y apunta siempre a la url raíz, donde se encuentra
    hospedado el framework. Su equivalente es la variable global BASE_URL.
  </p>
  <p>
    Puede ser usada simplemente escribiendo <strong>"$&#98;&#97;&#115;&#101;&#85;&#114;&#108;"</strong> en las vistas.
   </p>
   <p>
     Por ejemplo, para este sitio, la variable <strong>$&#98;&#97;&#115;&#101;&#85;&#114;&#108;</strong>
     devuelve <i>https://rsphp.espino.info</i>
   </p>
   <h2 id="databind">Ligado de datos</h2>
   <p>
     Podemos ligar a datos mediante el atributo "data-bind", hacia un elemento del arreglo que pasamos en $data a la vista
     Si el dato no existe, el contenedor se mostrará vacío
     el contenedor. Este atributo es aplicable a los inputs, selects, spans y divs.
   </p>
   <pre>
     <code class="html">
       &lt;!-- Un input ligado a datos--&gt;
       &lt;input
         id=&quot;name&quot;
         name=&quot;name&quot;
         data-bind=&quot;name&quot;
         type=&quot;text&quot;
        /&gt;
        &lt;!-- Un select ligado a datos --&gt;
        &lt;select
          id=&quot;rol_id&quot;
          name=&quot;rol_id&quot;
          data-bind=&quot;rol_id&quot;
        &gt;
        &lt;/select&gt;
        &lt;!-- Un span ligado a datos --&gt;
        &lt;h1&gt;Score: &lt;span data-bind=&quot;score&quot;&gt;&lt;/span&gt;&lt;/h1&gt;
     </code>
   </pre>
   <h3 id="select">Select</h3>
   <p>
     Las fuentes de datos pueden utilizarse para poblar automáticamente a las elementos select, utilizando los atributos
     data-source, data-display-field y data-value-field, así:
   </p>
   <pre>
     <code class="html">
       &lt;select
         id=&quot;rol_id&quot;
         name=&quot;rol_id&quot;
         data-source=&quot;dsRoles&quot;
         data-display-field=&quot;name&quot;
         data-value-field=&quot;rol_id&quot;
         data-bind=&quot;rol_id&quot;
       &gt;
       &lt;/select&gt;
     </code>
   </pre>
   <p>
     Donde:
     <b>data-source</b> es el nombre de la fuente de datos que debemos declarar previamente.
     <br />
     <b>data-display-field</b> es el nombre del campo que queremos se muestre al usuario.
     <br />
     <b>data-value-field</b> es el nombre del campo que queremos sea el valor de la opción.
   </p>
   <p>
     Es una funcionalidad bastante útil que nos ahorra muchas líneas de código y sobre todo el uso de código
     spaguetti.
   </p>
   <h3 id="tables">Tablas</h3>
   <p>
     Pero eso no es todo, también podemos aplicarle a las tablas este funcionalidad de ligado a datos.
     Con el atributo data-source ligamos la tabla a una fuente de datos previamente configurada.
   </p>
   <p>
     Posteriormente creamos un renglón y le agregamos los encabezados. En estos encabezados utilizamos
     los atributos "data" para espeficiarle al framework lo que queremos hacer en dicha columna.
   </p>
   <p>
     El atributo <b>data-field</b> se usa para especificar el campo que se mostrará en la columna.
     <br/>
     El atributo <b>data-field-type</b> se usa para especificar el tipo de campo. Tenemos varios tipos de campos:
     <br/>
     El atributo <b>data-header</b> se usa para especificar el encabezado de la columna.
     <br/>
     <ul>
       <li>
         <b>text</b> Simple texto en una celda
       </li>
       <li>
         <b>textbox</b> Un input del tipo text con el valor del campo en la celda.
       </li>
       <li>
         <b>hyperlink</b> Mostrará un hiper vínculo en la celda.
         <ul>
           <li>
             <b>data-url-fields</b> Es una lista del nombre de los campos que usaremos para crear el hipervínculo,
             separada por comas.
           </li>
         </ul>
         <ul>
           <li>
             <b>data-url-format</b> Es el formato de la url del hiper vínculo. Los campos que queramos usar deberán
             estas listados en data-url-fields y los podemos utilizar anteponiendoles una arroba.
           </li>
         </ul>
       </li>
       <li>
         <b>hidden</b> Incluirá un input del tipo "hidden" en la celda, con el valor del campo en el.
       </li>
       <li>
         <b>textarea</b> Mostrará un control textarea con el valor del campo especificado en el.
       </li>
       <li>
         <b>image</b> Mostrará una imágen en la celda.
         <ul>
           <li>
             <b>url</b> Especifica la url de la imágen a mostrar
           </li>
           <li>
             <b>width</b> Especifica el ancho de la imagen
           </li>
           <li>
             <b>height</b> Especifica el alto de la imagen
           </li>
         </ul>
       </li>
       <li>
         <b>select</b> Mostrará un control select en la celda, ligado a datos.
         <ul>
           <li>
             <b>data-source</b> El nombre de la fuente datos previamente configurada
           </li>
           <li>
             <b>data-value-field</b> El nombre del campo en la fuente de datos que será
             el valor de la opción en el select.
           </li>
           <li>
             <b>data-value-field</b> El nombre del campo en la fuente de datos que será
             el valor mostrado al usuario.
           </li>
         </ul>
       </li>
     </ul>
     <br/>
   </p>
   Ejemplo:
   <pre>
     <code class="html">
       &lt;table
         data-source=&quot;dsUsers&quot;
       &gt;
         &lt;tr&gt;
           &lt;th
             data-field-type=&quot;text&quot;
             data-field=&quot;name&quot;
             data-header=&quot;name&quot;
           &gt;
           &lt;th
           &lt;th
             data-field-type=&quot;textbox&quot;
             data-field=&quot;email&quot;
             data-header=&quot;Email&quot;
           &gt;
           &lt;th
             data-field=&quot;status_id&quot;
             data-field-type=&quot;select&quot;
             data-header=&quot;Status&quot;
             data-source=&quot;dsUserStatus&quot;
             data-value-field=&quot;status_id&quot;
             data-display-field=&quot;name&quot;
           &gt;
           &lt;/th&gt;
           &lt;th
             data-field-type=&quot;hyperlink&quot;
             data-url-fields=&quot;user_id,email&quot;
             data-header=&quot;&quot;
             data-text=&quot;Click Me&quot;
             data-url-format=&quot;https://myserver/mycontroller/myfunction/@actor_id/@email&quot;
           &gt;
           &lt;/th&gt;
         &lt;/tr&gt;
       &lt;/table&gt;
     </code>
</div>