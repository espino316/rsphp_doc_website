<?php
/**
 * Do some test
 */
class TestController extends Controller {

  /**
   * Creates a new instance of TestController
   */
  function __construct() {

  } // end function constructs

  /**
   * The home %baseUrl/test/
   */
  function index() {

  } // end function index

  function globals() {
    print_r( APP_NAME );
    print_r( App::get('EMAIL_ADMIN') );
  }

  function addUser( $user ) {
    echo $user;
  }

  function ds() {
    $dsName = "dsMedals";
    $result = Db::getResultFromDataSource( $dsName, null);
    print_r( $result );
  }

  function nonQuery() {
    //  Instanciamos un asistente
    //  Si no pasamos parámetro, toma la conexión "default"
    $db = new DbHelper();

    //  Establecemos una consulta parametrizada
    $sql = "INSERT INTO phonebook ( name, phone_number ) VALUES ( :name, :phoneNumber );";

    //  Establecemos los parámetros de la consulta
    $params['name'] = 'Luis Espino';
    $params['phoneNumber'] = '555-7890';

    //  Ejecutamos la consulta y obtenemos el resultado
    $rows = $db->nonquery($sql, $params);

    //  Imprime 1
    echo $rows;
  } // end function nonQuery

  function scalar() {
    //  Instanciamos un asistente
    //  Si no pasamos parámetro, toma la conexión "default"
    $db = new DbHelper();

    //  Establecemos una consulta parametrizada
    $sql = "SELECT MAX(order_id) as last_order FROM orders WHERE customer_id = :customerId;";

    //  Establecemos los parámetros de la consulta
    $params['customerId'] = 999;

    //  Ejecutamos la consulta y obtenemos el resultado
    $last_order_id = $db->scalar($sql, $params);

    //  Imprime 1001
    echo $last_order_id;
  } // end function scalar

  /**
   * Prints random strings
   */
  function random() {
    echo StringHelper::random(48);
    echo "<br />";
    echo StringHelper::random(16);
    echo "<br />";
    echo CryptHelper::generateKey(24);
    echo "<br />";
    echo CryptHelper::generateKey(8);
  } // end function random

  function config() {
    Config::load();
    print_r( Config::get() );
  } // end function config

  function serialize() {
    $x = new Controller();
    print_r( base64_encode(serialize($x)) );
  }

  function deserialize() {
    $x = unserialize(base64_decode('TzoxMDoiQ29udHJvbGxlciI6MTp7czoyMzoiAENvbnRyb2xsZXIAc2hvd01lc3NhZ2UiO2I6MDt9'));
    print_r( $x );
  }

  function error() {
    throw new Exception("Error Processing Request", 1);
  }

  function xml() {
    $params['name'] = 'luis';
    $params['age'] = 32;
    $params['inner'] = $params;
    $this->xmlResponse( $params );
  }

  function crypt( $data ) {
    $ch = new CryptHelper();
    echo $ch->tripleDesEncrypt( $data );
  }

  function decrypt( $data ) {
    $ch = new CryptHelper();
    echo $ch->tripleDesDecrypt( $data );
  }

  function getFiles() {
    $directoryPath = '/var/www/rsphp.espino.info/public_html';
    $files = DirectoryHelper::getFiles( $directoryPath );
    print_r( $files );
  } // end function getFiles

  function printDirectories() {
    $directoryPath = '/var/www/rsphp.espino.info/public_html';
    $directories = DirectoryHelper::getDirectories( $directoryPath );
    print_r( $directories );
  } // end function printDirectories

  function writetoresponse() {
    FileHelper::writeToResponse( 'C:\xampp\htdocs\rsphp\public\images\doc.png' );
  } // end function write to response

  function mail() {
    try {
      Mailer::setConfig(
        array(
          "mailServer" => "simka.websitewelcome.com",
          "mailUser" => "luis.espino@eboletos.com.mx",
          "mailPassword" => "Sandman316"
        )
      );

      //  Simplemente configuramos cada dato del email
      //  Remitente
      Mailer::$from = 'luis@espino.info';
      //  Destinatario
      Mailer::$to = 'luis.m.espino@gmail.com';
      //  Asunto
      Mailer::$subject = 'Cuenta creada en miasombrosositio.com';
      //  Mensaje
      Mailer::$message = 'Tu cuenta ha sido creada!';
      //  Indicamos si el mensaje es php ( true or false )
      Mailer::$html = true;

      //  Mandamos el mensaje con send
      Mailer::send();
    } catch ( Exception $ex ) {
      print_r( $ex );
    } // end try catch
  } // end function mail

  function randomString( $len ) {
    echo StringHelper::random( $len );
  } // end function random
} // end class TestController
