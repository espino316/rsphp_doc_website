<div class="awesome-title pallete-1-bg row-offset-1">
  <div class="middle center">
    <h1>¡Y aún hay más!</h1>
  </div>
</div>

<div class="col-10 offset-1 row-offset-1">
    <div class="col-5">
      <h2>Push Notifications</h2>
      <pre>
        <code class="php">
Pusher::send( 1, $to, $title, $message, $info );
        </code>
      </pre>
      <h2>Encriptación</h2>
      <pre>
        <code class="php">
$ch = new Crypt();
$result = $ch->tripleDesEncrypt("cadena");
        </code>
      </pre>
      <h2>Geolocalización</h2>
      <pre>
        <code class="php">
$geocoder = new Geocoder();
$result =
$geocoder->getGeoLocation ("Mi dirección");
        </code>
      </pre>
      <h2>Validaciones</h2>
      <pre>
        <code class="php">
$validator = new Validation();
//	Adds the rules
$validator->addRule('email', 'required');
$validator->addRule('email', 'email');
        </code>
      </pre>
      <h2>Creación dinámica de HTML</h2>
      <pre>
        <code class="php">
$vendorsTable =
Html::dataTable($vendors, $options, true);
        </code>
      </pre>
    </div>
    <div class="col-5 offset-1">
      <h2>Registro de Eventos</h2>
      <pre>
        <code class="php">
Logger::debug('Mensaje de error');
Logger::debug($objecto);
        </code>
      </pre>
      <h2>Xml</h2>
      <pre>
        <code class="php">
//  Arreglo a xml
$xml = Xml::arrayToXml( $myArray );
// Imprimir un arreglo como xml
XmlHelper::xmlResponse( $myArray );
        </code>
      </pre>
      <h2>Administración de Inputs</h2>
      <pre>
        <code class="php">
// GET, POST, PUT, etc, todo en Input
$name = Input::get('name');
$email = Input::get('email');
        </code>
      </pre>
      <h2>Asistente de URIs</h2>
      <pre>
        <code class="php">
//  Obtener segmento de la url
$param = Uri::getSegment(4);
//  Redirigir
Uri::redirect('http://espino.info');
        </code>
      </pre>
    </div>
</div>
