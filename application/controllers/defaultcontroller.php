<?php
class defaultController extends Controller {

    private function loadContent( $viewName ) {
    $data['$timestamp'] = time();
    View::load( 'header', $data );
    View::load( $viewName );
    View::load( 'footer' );
  }

  function index() {
    $this->loadContent( 'welcome' );
  }

  function home() {
    $this->loadContent( 'home' );
  }

  function intromvc() {
    $this->loadContent( 'doc/intromvc' );
  }
}
