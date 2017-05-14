<?php
/**
 * Handles documentation requests
 */
class DocController extends Controller
{

    /**
     * Creates a new instance of DocController
     */
    function __construct()
    {

    } // end function __construct

    /**
     * Loads a view for content, including header and footer
     */
    private function loadContent($viewName, $data = null)
    {
        $data['$timestamp'] = time();
        View::load($viewName, $data);
    }

    private function loadMarkDown($markDownFile)
    {
        $markDownFile = str_replace("\\", DS, $markDownFile);
        $markDownFile = str_replace("/", DS, $markDownFile);
        $markDownFile = ROOT . DS . 'application' . DS . 'views' . DS . $markDownFile;
        $markDownFile = file_get_contents($markDownFile);
        View::load('header');
        echo '<div class="content content-min-height">';
        echo MarkDownLib::render($markDownFile);
        echo '</div>';
        View::load('footer');
    } // end function loadMarkDown

    /**
     * The home: $baseUrl/doc
     */
    function index()
    {
        $data['$option'] = "doc/welcome";
        $this->loadContent('doc/home', $data);
    } // end function index

    /**
     * Shows the getting started view
     *
     * @return null
     */
    function gettingStarted()
    {
        $data['$option'] = "doc/gettingstarted";
        $this->loadContent('doc/home', $data);
    } // end function gettingStarted

    /**
     * Shows the intro mvc
     */
    function intromvc()
    {
        $data['$option'] = "doc/intromvc";
        $this->loadContent('doc/home', $data);
    } // end function intromvc

    function config($option = null)
    {
        $data['$option'] = "doc/config/home";
        $this->loadContent('doc/home', $data);
    } // end function config

    function views()
    {
        $data['$option'] = "doc/views";
        $this->loadContent('doc/home', $data);
    }

    function database()
    {
        $data['$option'] = "doc/database";
        $this->loadContent('doc/home', $data);
    }

    function console()
    {
        $data['$option'] = "doc/console";
        $this->loadContent('doc/home', $data);
    }

    function controllers()
    {
        $data['$option'] = "doc/controllers";
        $this->loadContent('doc/home', $data);
    }

    function crypthelper()
    {
        $data['$option'] = "doc/crypthelper";
        $this->loadContent('doc/home', $data);
    }

    function input()
    {
        $data['$option'] = "doc/input";
        $this->loadContent('doc/home', $data);
    }

    function files()
    {
        $data['$option'] = "doc/files";
        $this->loadContent('doc/home', $data);
    } // end function files

    function email()
    {
        $data['$option'] = "doc/email";
        $this->loadContent('doc/home', $data);
    } // end function email

    function helpers()
    {
        $data['$option'] = "doc/helpers";
        $this->loadContent('doc/home', $data);
    } // end function helpers

} // end class DocController
