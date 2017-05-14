<div class="awesome-title pallete-1-bg row-offset-1">
  <div class="middle center">
    <h1>Asombrosas Funcionalidades</h1>
  </div>
</div>

<div class="col-10 offset-1 row-offset-1">

    <h1>Manejo de Bases de Datos</h1>
    <h2 class="offset-1">Tan fácil como...</h2>
      <pre>
        <code class="php">
  $db = new Db();
  $sql = "SELECT * FROM orders WHERE id = :id;"
  $params['id'] = 12369;
  $resultSet = $db->query($sql, $params);
        </code>
      </pre>

    <h2 class="offset-1">Tan flexible como...</h2>
      <pre>
        <code class="php">
  $db = new Db();
  $resultSet = $db->select('order_number, customer_number, order_date')
    ->from('orders')
    ->where('order_number', 12369)
    ->orderBy('order_date')
    ->get();
        </code>
      </pre>

    <h2 class="offset-1">Tan simple como...</h2>
      <pre>
        <code class="php">
  $db = new Db();
  $orders = $db->get('orders');
        </code>
      </pre>

    <h1>Fuentes de Datos</h1>
    <h2 class="offset-1">Globales y Configuradas en Json</h2>
      <pre>
        <code class="json">
  [
      {
          "connection": "default",
          "name": "dsMyDataSource",
          "type": "SQLQUERY",
          "text": "SELECT * FROM customers WHERE email = :email",
          "parameters": [
            {
              "name": "email",
              "type": "session",
              "defaultValue": "luis@espino.info"
            }
          ]
      }
  ]
        </code>
      </pre>

    <h1>Modelos ORM</h1>
    <h2 class="offset-1">Un "save()" y el framework hace el resto.</h2>

      <pre>
        <code class="php">
  $customer = new Customers_model();
  $customer->id = 123456;
  $customer->name = "Luis Espino";
  $customer->save();
        </code>
      </pre>

    <h1>Vistas</h1>
    <h2 class="offset-2">Con $variables tipo Php pasándolas como parámetros:</h2>
      <pre>
        <code class="html">
          &lt;h1&gt;Hola $name!&lt;/h1&gt;
        </code>
      </pre>
      <pre>
        <code class="php">
  $data["$name"] = "Luis";
  View::load('viewname', $data);
        </code>
      </pre>

    <h2 class="offset-1">Ligado a datos con atributos "data" en el HTML</h2>
      <pre>
        <code class="html">
  &lt;select
    data-source=&quot;dsMyDataSource&quot;
    data-display-field=&quot;name&quot;
    data-value-field=&quot;id&quot;
  &gt;
  &lt;/select&gt;
        </code>
      </pre>

    <h2 class="offset-1">LLama a otra vista desde una vista:</h2>
      <pre>
        <code class="html">
    &lt;div id=&quot;tabDireccion&quot; class=&quot;tab&quot;&gt;
     &lt;h2&gt;Mi direcci&#243;n fiscal&lt;/h2&gt;
     &lt;section data-view=&quot;member/address&quot;&gt;&lt;/section&gt;
    &lt;/div&gt;
        </code>
      </pre>

    <h1>Routing</h1>
    <h2 class="offset-1">Configuración en JSON</h2>

      <pre>
        <code class="json">
  "routes": [
    {
      "method": "*",
      "url": "",
      "newUrl": "default"
    },{
      "method": "*",
      "url": "privacidad",
      "newUrl": "default/privacypolicy"
    },{
      "method": "*",
      "url": "terminos",
      "newUrl": "default/tos"
    }
  ]
        </code>
      </pre>

    <h1>Paginación</h1>
    <h2 class="offset-1">Tan simple como declararla con atributos en el HTML</h2>
      <pre>
        <code class="html">
    &lt;table
      data-source=&quot;dsactorsComboBox&quot;
      data-name=&quot;navigation&quot;
      data-pagination=&quot;true&quot;
      data-page-items=&quot;5&quot;
      data-current-page-segment=&quot;2&quot;
      data-pagination-url=&#39;$baseUrl/test/lista&#39;
    &gt;
      &lt;tr&gt;
        &lt;th
          data-field-type=&quot;hyperlink&quot;
          data-url-fields=&quot;actor_id,email&quot;
          data-header=&quot;&quot;
          data-text=&quot;Click Me&quot;
          data-url-format=&quot;$baseUrl/simple/case/@actor_id/@email&quot;
        &gt;
        &lt;/th&gt;
        &lt;th
          data-field=&quot;actor_id&quot;
          data-header=&quot;Actor&quot;
        &gt;
        &lt;/th&gt;
      &lt;/tr&gt;
    &lt;/table&gt;
        </code>
      </pre>
</div>
