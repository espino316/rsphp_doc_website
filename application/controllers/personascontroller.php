<?php
class PersonasController extends Controller {

  /**
   * La libreria de personas
   */
  protected $lib;

  /**
   * Crea una instancia de la clase PersonasController
   */
  function __construct() {
    $this->lib =new PersonasLib();
  } // end function __construct

  /**
   * Mostrará el formulario para dar de alta|
   */
  function alta() {

    //  configuramos un conjunto vacio, por que le estamos pasando
    //  dato vacios, esto limpiará los valores en la forma
    //  lo que estamos haciendo es pasar un registro "nuevo"
    $persona['persona_id'] = '';
    $persona['nombre'] = '';
    $persona['edad'] = '';
    //  Creamos data y pasamos persona como variable
    $data['$persona'] = $persona;
    //  Agregamos el título a aparecer en el encabezado
    $data['$title'] = 'Alta de Persona';

    //  Cargamos la vista
    View::load('personas/registro', $data);
  } // end function alta

  /**
   * Mostrará la lista de las personas
   */
  function lista() {
    $listaPersonas = $this->lib->lista();
    $tablaPersonas = $this->lib->tabla( $listaPersonas );

    $data['$tablaPersonas'] = $tablaPersonas;

    View::load('personas/lista', $data);
  } // end function lista

  /**
   * Mostrará el formulario para editar datos de personas
   */
  function editar( $personaId ) {
    //  Consultamos persona con la librería
    $persona = $this->lib->consultar( $personaId );
    //  Formamos $data con persona y title
    $data['$persona'] = $persona;
    $data['$title'] = "Editar Persona";
    //  Cargamos la vista
    View::load('personas/registro', $data);
  } // end function editar

  /**
   * Guarda la información de la persona en el banco
   */
  function guardar() {
    $guardado = $this->lib->guardar();
    if ( $guardado ) {
      View::load('personas/exito');
    } else {
      View::load('personas/exito');
    } // end if guardado
  } // end function guardar

  /**
   * Tomará el registro de una persona y lo eliminará
   * @param $personaId int El id de la persona a eliminar
   */
  function borrar( $personaId ) {
    $borrado = $this->lib->borrar( $personaId );
    if ( $borrado ) {
      UriHelper::redirect(BASE_URL . '/personas/lista');
    } else {
      UriHelper::redirect(BASE_URL . '/personas/lista');
    } // end if borrado
  } // end function borrar

} // end class PersonasController
