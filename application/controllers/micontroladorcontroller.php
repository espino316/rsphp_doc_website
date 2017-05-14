<?php
class MiControladorController extends Controller {

  /**
   * Default function
   */
  function index() {
    $data['$nombre'] = 'Luis!';
    $data['$edad'] = 32;
    View::load('mivista', $data);
  } // end function index

  function mifuncion($nombre, $edad) {
    echo "Hola $nombre! ¿Tienes $edad años? Eres joven.";
  } // end function miFuncion

  /**
   * Captura mis datos
   * url: $baseUrl/micontrolador/captura
   */
  function captura() {
    //  Mostraremos la vista captura
    View::load('captura');
  } // end function captura

  /**
   * Muestra mis datos
   * url: $baseUrl/micontrolador/misdatos
   */
  function misdatos() {
    //  Obtenemos los datos enviados por la forma
    //  "Input" es la clase del framework de donde obtendremos
    //  toda la información enviada en las peticiones
    //  aqui esta $_POST, $_GET, $_REQUEST, etc
    //  Simplemente lo mandamos llamar con ::get('nombreParametro')
    $nombre = Input::get('nombre');
    $edad = Input::get('edad');

    //  Los pasamos aun arreglo
    $data['$nombre'] = $nombre;
    $data['$edad'] = $edad;

    //  Mando llamar la vista "misdatos" y le paso la información obtenida
    //  del input
    View::load('misdatos', $data);
  } // end function misdatos

  function test() {
    $bar = Input::get('foo');
    echo time();
  }

} // end class MiControladorController
