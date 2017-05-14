<?php
  /**
   * Downloads zip RSphp
   */
  class DownloadController extends Controller {

    /**
     * Creates a new instance of DownloadController
     */
    function __construct() {

    } // end function constructs

    /**
     * The home %baseUrl/download/
     */
    function index() {
      FileHelper::writeToResponse(ROOT.DS.'public'.DS.'files'.DS.'rsphp1.0.zip');
    } // end function index

  } // end class DownloadController