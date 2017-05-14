<div class="content content-min-height">
  <h1>Bases de Datos</h1>
  <p>
    El manejo de bases de datos es realmente sencillo en RSPhp. Lo primero que debes saber es como formar una conexión.
    Una conexión es configurable dentro de "config/app.json", como lo vimos <a href="$baseUrl/doc/config#dbconn">aqui</a>.
  </p>
  <h2 id="paramQueries">Consultas Parametrizadas</h2>
  <h3 id="query">Query</h3>
  <p>
    Ahora, vamos a utilizar esas conexiónes, primero con una consulta simple:
  </p>
  <pre>
    <code class="php">
      //  Instanciamos un asistente
      //  Si no pasamos parámetro, toma la conexión "default"
      $db = new Db();

      //  Establecemos una consulta parametrizada
      $sql = "SELECT * FROM orders WHERE id = :id;"

      //  Establecemos los parámetros de la consulta
      $params['id'] = 12369;

      //  Ejecutamos la consulta y obtenemos el resultado
      $resultSet = $db->query($sql, $params);
    </code>
  </pre>
  <p>
    El método es <i><b>"query"</b></i>, que acepta como parámetros:
    <ul>
      <li>
        <b>$sql</b>.- La consulta SQL. Los parámetros los establecemos con dos puntos (:) adelante.
      </li>
      <li>
        <b>$params</b>.- Un arreglo asociativo que contiene los parámetros, siendo la llave el nombre
        del parámetro, y el valor el valor del parámetro.
      </li>
    </ul>
  </p>
  <p>
    El resultado es un arreglo de arreglos asociativos, en donde cada arreglo asociativo es un renglón,
    y cada renglón es una arreglo de llave-valor, donde la llave es el nombre de la columna.
  </p>
  <pre>
    <code class="php">
      Array(
        [0] => Array (
          [id] => 1
          [customer] => 999
          [date] => 20160101
        )
        [1] => Array (
          [id] => 2
          [customer] => 889
          [date] => 20160102
        )
      )
    </code>
  </pre>
  <h3 id="nonQuery">NonQuery</h3>
  <p>NonQuery son las consultas que no devuelven datos, como INSERT, UPDATE y DELETE</p>
  <p>
    Tiene exactamente el mismo formato que "query", pero no devuleve un resultset,
    devuelve el número de registros afectados.
  </p>
  <pre>
    <code class="php">
      //  Instanciamos un asistente
      //  Si no pasamos parámetro, toma la conexión "default"
      $db = new Db();

      //  Establecemos una consulta parametrizada
      $sql = "INSERT INTO phonebook ( name, phone_number ) VALUES ( :name, :phoneNumber );";

      //  Establecemos los parámetros de la consulta
      $params['name'] = 'Luis Espino';
      $params['phoneNumber'] = '555-7890';

      //  Ejecutamos la consulta y obtenemos el resultado
      $rows = $db->nonquery($sql, $params);

      //  Imprime 1
      echo $rows;
    </code>
  </pre>

  <h3 id="scalar">Scalar</h3>
  <p>
    Scalar son las consultas que devuelven un solo dato, un campo de un registro, no un conjunto
    de registros.
  </p>
  <p>
    Tiene exactamente el mismo formato que "query", pero no devuleve un resultset,
    devuelve un dato escalar.
  </p>
  <pre>
    <code class="php">
      //  Instanciamos un asistente
      //  Si no pasamos parámetro, toma la conexión "default"
      $db = new Db();

      //  Establecemos una consulta parametrizada
      $sql = "SELECT MAX(order_id) as last_order FROM orders WHERE customer_id = :customerId;";

      //  Establecemos los parámetros de la consulta
      $params['customerId'] = 999;

      //  Ejecutamos la consulta y obtenemos el resultado
      $last_order_id = $db->scalar($sql, $params);

      //  Imprime 1001
      echo $last_order_id;
    </code>
  </pre>
  <h2 id="builder">Constructor de Consultas</h2>
  <p>
    RSPhp cuenta con un constructor de consultas que utiliza una sintáxis muy parecida a SQL.
    Comenzamos desde la instancia de la base de datos para acceder a todos los métodos, que son un
    espejo de los claúsulas de SQL:
  </p>
  <ul>
    <li>Select</li>
    <li>From</li>
    <li>Where</li>
    <li>Order By</li>
    <li>Top</li>
    <li>Join</li>
  </ul>
  <h3 id="select">Consultas de Selección</h3>
  <p>
    El ejemplo más sencillo es el de seleccionar todos los datos de una tabla (solo recomendable para tablas pequeñas
    de catálogo)
  </p>
  <br />
  <p>
    <i><strong>get( $tableName = null, $className = null )</strong></i>.
    <br /><br />
    Parámetros:
    <ul>
      <li><b>$tableName</b> El nombre de la tabla. Opcional, ya que podemos establecerlo también con "from".</li>
      <li>
        <b>$className</b> El nombre de la clase a la que queramos convertir cada registro en el arreglo. Silo especificamos,
        nos devolverá un arreglo de instances de esta clase. Por supuesto, la clase debe ser un modelo de la tabla, para poder
        realizar correctamente la conversión.
      </li>
    </ul>
  </p>
  <pre>
    <code class="php">
      //  Instanciamos un asistente
      //  Si no pasamos parámetro, toma la conexión "default"
      $db = new Db();

      //  Realiza un SELECT * FROM orders;
      $resultSet = $db->get('orders');
    </code>
  </pre>
  <p>
    "get" es el método para mandar obtener los datos. Por ejemplo, si creamos un consulta simple "SELECT ... FROM ...",
    tenemos que mandar llamar "get" para traer los datos:
  </p>
  <br />
  <p>
    <i><strong>select( $columns )</strong></i>
    <br /><br />
    Parámetros:
    <ul>
      <li><b>$columns</b> Lista de columnas separadas por comas. Acepta nomenclatura "colName AS MyColumn"</li>
    </ul>
  </p>
  <br />
  <p>
    <i><strong>from( $tableName )</strong></i>
    <br /><br />
    Parámetros:
    <ul>
      <li><b>$tableName</b> El nombre de la tabla a seleccionar</li>
    </ul>
  </p>
  <pre>
    <code class="php">
      //  Instanciamos un asistente
      //  Si no pasamos parámetro, toma la conexión "default"
      $db = new Db();

      //  Realiza un SELECT * FROM orders;
      $resultSet =
        $db->
          select('id, customer, date')->
          from('orders')->
          get();
    </code>
  </pre>
  <h3 id="where">Claúsula Where</h3>
  <p>
    El método "where" acepta dos tipos de argumentos:
    <ul>
      <li>
        <h4>Nombre de columna y valor</h4>
        <br />
        <p>
          <i><strong>where( $columnName, $value )</strong></i>.
          <br /><br />
          Parámetros:
          <ul>
            <li><b>$columnName</b> El nombre de la columna</li>
            <li><b>$value</b> El valor a comparar (usando operador "==")</li>
          </ul>
        </p>
        <pre>
          <code class="php">
            //  Instanciamos un asistente
            //  Si no pasamos parámetro, toma la conexión "default"
            $db = new Db();

            //  Realiza un SELECT * FROM orders WHERE id = 1001;
            $resultSet =
              $db->
                select('id, customer, date')->
                from('orders')->
                where('id', 1001)->
                get();
          </code>
        </pre>
      </li>
      <li>
        <h4>Arreglo asociativo</h4>
        <br />
        <p>
          <i><strong>where( $array )</strong></i>.
          <br /><br />
          Parámetros:
          <ul>
            <li><b>$array</b> El arreglo asociativo, colección par clave-valor, donde clave es el nombre de la columna.</li>
          </ul>
        </p>
        <pre>
          <code class="php">
            //  Instanciamos un asistente
            //  Si no pasamos parámetro, toma la conexión "default"
            $db = new Db();

            //  Realiza un SELECT * FROM orders WHERE customer = 999 AND date = '20160101';
            $params['customer'] = 999;
            $params['date'] = '20160101';

            $resultSet =
              $db->
                select('id, customer, date')->
                from('orders')->
                where($params)->
                get();
          </code>
        </pre>
      </li>
    </ul>
  </p>
  <h4>And's &amp; Or's</h4>
  <p>
    Utilizamos "andWhere" para incluir claúsulas "AND WHERE ...", y "orWhere" para incluir cláusilas del tipo " OR WHERE ...".
  </p>
  <br />
  <p>
    <i><strong>andWhere( $columnName, $value )</strong></i>.<br />
    <i><strong>orWhere( $columnName, $value )</strong></i>.
    <br /><br />
    Parámetros:
    <ul>
      <li><b>$columnName</b> El nombre de la columna</li>
      <li><b>$value</b> El valor a comparar (usando operador "==")</li>
    </ul>
  </p>
  <pre>
    <code class="php">
      //  Instanciamos un asistente
      //  Si no pasamos parámetro, toma la conexión "default"
      $db = new Db();

      //  Realiza un SELECT * FROM orders WHERE customer = 999 AND date = '20160101';
      $resultSet =
        $db->
          select('id, customer, date')->
          from('orders')->
          where('customer', 999)->
          andWhere('date', '20160101')->
          get();

      //  Realiza un SELECT * FROM orders WHERE customer = 999 OR date = '20160101';
      $resultSet =
        $db->
          select('id, customer, date')->
          from('orders')->
          where('customer', 999)->
          orWhere('date', '20160101')->
          get();

    </code>
  </pre>

  <h3 id="like">Claúsula Like</h3>
  <p>
    El método "like" genera una claúsula "WHERE LIKE '%value%'". Tambien existe el método "orLike", que genera una
    cláusula "OR LIKE '%value%'" y "andLike" que genera una cláusula "AND LIKE '%value%'"
  </p>
  <br />
  <p>
    <i><strong>like( $columnName, $value )</strong></i>.<br />
    <i><strong>andLike( $columnName, $value )</strong></i>.<br />
    <i><strong>orLike( $columnName, $value )</strong></i>.
    <br /><br />
    Parámetros:
    <ul>
      <li><b>$columnName</b> El nombre de la columna</li>
      <li><b>$value</b> El valor a comparar usando wilcards ('%$value%')</li>
    </ul>
  </p>
  <pre>
    <code class="php">
      //  Instanciamos un asistente
      //  Si no pasamos parámetro, toma la conexión "default"
      $db = new Db();

      //  Realiza un SELECT id, name, email FROM customers WHERE name LIKE '%Luis%' AND email LIKE '%espino%';
      $resultSet =
        $db->
          select('id, name, email')->
          from('customers')->
          like('name', 'Luis')->
          andLike('email', 'espino')->
          get();

      //  Realiza un SELECT id, name, email FROM customers WHERE name LIKE '%Luis%' OR email LIKE '%espino%';
      $resultSet =
      $db->
        select('id, name, email')->
        from('customers')->
        like('name', 'Luis')->
        orLike('email', 'espino')->
        get();

    </code>
  </pre>
  <h3 id="orderBy">Claúsula Order By</h3>
  <p>
    El método "orderBy" nos permite establecer ordenamientos. Sus argumentos son el nombre de la columna y, opcionalmente,
    "asc" o "desc".
  </p>
  <br />
  <p>
    <i><strong>orderBy( $columnName, $ascDesc = null )</strong></i>.
    <br /><br />
    Parámetros:
    <ul>
      <li><b>$columnName</b> El nombre de la columna</li>
      <li><b>$ascDesc</b> El texto "asc" o "desc". Opcional</li>
    </ul>
  </p>
  <pre>
    <code class="php">
      //  Instanciamos un asistente
      //  Si no pasamos parámetro, toma la conexión "default"
      $db = new Db();

      //  Realiza un SELECT id, name, email FROM customers ORDER BY name ASC;
      $resultSet =
        $db->
          select('id, name, email')->
          from('customers')->
          orderBy('name', 'asc')->
          get();

      //  Realiza un SELECT id, name, email FROM customers ORDER BY name DESC;
      $resultSet =
        $db->
          select('id, name, email')->
          from('customers')->
          orderBy('name', 'desc')->
          get();

    </code>
  </pre>
  <h3 id="top">Claúsula Top</h3>
  <p>
    El método "top" nos permite limitar el número de registros que devolverá la consulta. En caso de utilizar la base de datos
    MySql o PostgreSql, generará consultas con "LIMIT", para Sql Server utilizará "TOP".
  </p>
  <br />
  <p>
    <i><strong>top( $limit, $startAt = null )</strong></i>.
    <br /><br />
    Parámetros:
    <ul>
      <li><b>$limit</b> El número de registros a devolver</li>
      <li><b>$startAt</b> El número de registro inicial, donde comienza a contar para el límite. Opcional</li>
    </ul>
  </p>
  <pre>
    <code class="php">
      //  Instanciamos un asistente
      //  Si no pasamos parámetro, toma la conexión "default"
      $db = new Db();

      //  Realiza un SELECT id, name, email FROM customers LIMIT 10;
      $resultSet =
        $db->
          select('id, name, email')->
          top(10)->
          from('customers')->
          get();

      //  Realiza un SELECT id, name, email FROM customers WHERE LIMIT 10, 20;
      $resultSet =
        $db->
          select('id, name, email')->
          top(10, 20)->
          from('customers')->
          get();

    </code>
  </pre>
  <h3 id="join">Claúsula Join</h3>
  <p>
    El método "join" nos permite unir tablas. Utiliza opcionalmente el método "on" de forma consecutiva (con excepción de CROSS JOIN)
  </p>
  <br />
  <p>
    <i><strong>join( $tableName, $leftField, $operator, $rightField )</strong></i>.<br />
    <i><strong>leftJoin( $tableName, $leftField, $operator, $rightField )</strong></i>.<br />
    <i><strong>rightJoin( $tableName, $leftField, $operator, $rightField )</strong></i>.<br />
    <i><strong>crossJoin( $tableName )</strong></i>.
    <br /><br />
    Parámetros:
    <ul>
      <li><b>$tableName</b> El nombre de la tabla a unir</li>
      <li><b>$leftField</b> El nombre del campo de la tabla de la izquierda con el que se realizará la unión</li>
      <li><b>$operator</b> El operador con el que se realizará la comparación ( =, >=, <=, etc.)</li>
      <li><b>$rightField</b> El nombre del campo de la tabla de la derecha con el que se realizará la unión</li>
    </ul>
  </p>
  <pre>
    <code class="php">
      //  Instanciamos un asistente
      //  Si no pasamos parámetro, toma la conexión "default"
      $db = new Db();

      //  Realiza un 'O.id as order_id, c.name as customer_name FROM orders as O
      //  INNER JOIN customers as C ON C.id = O.custumer
      $resultSet =
        $db->
          select('O.id as order_id, c.name as customer_name')->
          from('orders as O')->
          join('customers as C', 'C.id', '=', 'O.custumer')->
          get();

      //  Realiza un 'O.id as order_id, c.name as customer_name FROM orders as O
      //  LEFT JOIN customers as C ON C.id = O.custumer
      $resultSet =
        $db->
          select('O.id as order_id, c.name as customer_name')->
          from('orders as O')->
          leftJoin('customers as C', 'C.id', '=', 'O.custumer')->
          get();

      //  Realiza un 'O.id as order_id, c.name as customer_name FROM orders as O
      //  RIGHT JOIN customers as C ON C.id = O.custumer
      $resultSet =
        $db->
          select('O.id as order_id, c.name as customer_name')->
          from('orders as O')->
          rightJoin('customers as C', 'C.id', '=', 'O.custumer')->
          get();

      //  Realiza un 'O.id as order_id, c.name as customer_name FROM orders as O
      //  CROSS JOIN customers
      $resultSet =
        $db->
          select('O.id as order_id, c.name as customer_name')->
          from('orders as O')->
          crossJoin('customers as C')->
          get();

    </code>
  </pre>
  <h2 id="dml">Manipulación de Datos, Registro Activo</h2>
  <p>
    La manipulación de datos incluye funciones para crear, actualizar y eliminar registros.
  </p>
  <h3 id="insert">Inserción de Datos</h3>
  <p>
    El método "insert" nos permite insertar un registro en una tabla específica.
  </p>
  <br />
  <p>
    <i><strong>insert( $tableName, $params )</strong></i>.
    <br /><br />
    Parámetros:
    <ul>
      <li><b>$tableName</b> El nombre de la tabla en la que se realizará la inserción</li>
      <li>
        <b>$params</b> Arreglo asociativo, colección de pares clave-valor, donde la clave
        es el nombre de la columna, y el valor es el valor a insertar.
      </li>
    </ul>
  </p>
  <pre>
    <code class="php">
      //  Instanciamos un asistente
      //  Si no pasamos parámetro, toma la conexión "default"
      $db = new Db();

      //  Los parámetros, el arreglo columna - valor a insertar;
      $params['id'] = 1;
      $params['customer'] = 999;
      $params['date'] = 20160101;

      //  Realizamos la inserción
      $db->insert( 'orders', $params );
    </code>
  </pre>
  <h3 id="update">Actualización de Datos</h3>
  <p>
    El método "update" nos permite actualizar un registro en una tabla específica.
  </p>
  <br />
  <p>
    <i><strong>update( $tableName, $params, $whereParams )</strong></i>.
    <br /><br />
    Parámetros:
    <ul>
      <li><b>$tableName</b> El nombre de la tabla en la que se realizará la inserción</li>
      <li>
        <b>$params</b> Arreglo asociativo, colección de pares clave-valor, donde la clave
        es el nombre de la columna, y el valor es el valor a actualizar.
      </li>
      <li>
        <b>$whereParams</b> Arreglo asociativo de columnas llave de la tabla,
        colección de pares clave-valor, donde la clave
        es el nombre de la columna, y el valor es el valor a de la columna clave.
      </li>
    </ul>
  </p>
  <pre>
    <code class="php">
      //  Instanciamos un asistente
      //  Si no pasamos parámetro, toma la conexión "default"
      $db = new Db();

      //  Los parámetros de la clausula where, condiciones para buscar los registros
      //  es un arreglo columna - valor a actualizar;
      $whereParams['id'] = 1;
      //  Los parámetros, el arreglo columna - valor a actualizar;
      $params['customer'] = 999;
      $params['date'] = 20160101;

      //  Realizamos la actualización
      $db->update( 'orders', $params, $whereParams );
    </code>
  </pre>
  <h3 id="upsert">Actualización/Inserción de Datos</h3>
  <p>
    El método "upsert" nos permite actualizar o insertar un registro en una tabla específica, dependiendo
    si este ya existe o nó.
  </p>
  <br />
  <p>
    <i><strong>upsert( $tableName, $params, $whereParams = null )</strong></i>.
    <br /><br />
    Parámetros:
    <ul>
      <li><b>$tableName</b> El nombre de la tabla en la que se realizará la inserción</li>
      <li>
        <b>$params</b> Arreglo asociativo, colección de pares clave-valor, donde la clave
        es el nombre de la columna, y el valor es el valor a actualizar.
      </li>
      <li>
        <b>$whereParams</b> (Opciona) Arreglo asociativo de columnas llave de la tabla,
        colección de pares clave-valor, donde la clave
        es el nombre de la columna, y el valor es el valor a de la columna clave. Si se excluye,
        se realizará una inserción con $params
      </li>
    </ul>
  </p>
  <pre>
    <code class="php">
      //  Instanciamos un asistente
      //  Si no pasamos parámetro, toma la conexión "default"
      $db = new Db();

      //  Los parámetros de la clausula where, condiciones para buscar los registros
      //  es un arreglo columna - valor a actualizar;
      $whereParams['id'] = 1;
      //  Los parámetros, el arreglo columna - valor a actualizar;
      $params['customer'] = 999;
      $params['date'] = 20160101;

      //  Realizamos la actualización
      //  Si el registro no existe, lo insertará
      $db->upsert( 'orders', $params, $whereParams );
    </code>
  </pre>
  <h3 id="delete">Eliminación de Datos</h3>
  <p>
    El método "delete" nos permite eliminar un registro en una tabla específica.
  </p>
  <br />
  <p>
    <i><strong>delete( $tableName, $whereParams )</strong></i>.
    <br /><br />
    Parámetros:
    <ul>
      <li><b>$tableName</b> El nombre de la tabla en la que se realizará la inserción</li>
      <li>
        <b>$whereParams</b> Arreglo asociativo de columnas llave de la tabla,
        colección de pares clave-valor, donde la clave
        es el nombre de la columna, y el valor es el valor a de la columna clave.
      </li>
    </ul>
  </p>
  <pre>
    <code class="php">
      //  Instanciamos un asistente
      //  Si no pasamos parámetro, toma la conexión "default"
      $db = new Db();

      //  Los parámetros, el arreglo columna - valor de columna clave;
      $whereParams['id'] = 1;

      //  Realizamos la eliminación
      $db->delete( 'orders', $whereParams );
    </code>
  </pre>
</div>