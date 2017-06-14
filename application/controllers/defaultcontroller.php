<?php
class defaultController extends Controller
{

    private function loadContent( $viewName )
    {
        $data['$timestamp'] = time();
        View::load('header', $data);
        View::load($viewName);
        View::load('footer');
    }

    function index()
    {
        $this->loadContent('home');
    } // end function index

    function intromvc()
    {
        $this->loadContent('doc/intromvc');
    } // end function intromvc
} // end class defaultController
