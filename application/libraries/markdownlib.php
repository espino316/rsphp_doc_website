<?php

/**
 * Handles markdown
 */
class MarkDownLib
{
    /**
     * The rules to apply to the markdown
     */
    private static $_rules = array(
        "/`{3}.+?`{3}/s" => "self::_codeBlock",
        "/#.*/" => "self::_header",
        "/\*{2}.*?\*{2}/" => "self::_bold",
        "\*{1}.*?\S\*{1}" => "self::_italic",
        "/\n\*\s.*?\n\n/s" => "self::_ul",
    );

    private static function _header( $collection )
    {
        $collection = $collection[0];
        $newCollection = array();
        foreach( $collection as $header ) {
            preg_match_all( "/#/", $header, $output );
            $len = count( $output[0] );
            $newHeader = StringHelper::replace( "#", " ", $header );
            $newHeader = "<h$len>$newHeader</h$len>";
            $newCollection[$header] = $newHeader;
        } // end foreach
        return $newCollection;
    } // end if _header

    /**
     * Parses a collection of ul
     */
    private static function _ul( $collection )
    {
        $collection = $collection[0];
        $newCollection = array();
        $newUl = "";
        foreach( $collection as $ul ) {
            preg_match_all( "/\*.*\s/", $ul, $output );
            $output = $output[0];
            foreach( $output as $li ) {
                $newLi = StringHelper::replace( "* ", " ", $li );
                $newLi = StringHelper::replace( "\n", " ", $newLi );
                $newUl .= "<li>".$newLi."</li>\n";
            } // end foreach li
            $newUl = "<ul>".$newUl."</ul>";
            print_r( $newUl );
            $newCollection[] = $newUl;
        } // end foreach $collection

        return $newCollection;
    } // end function _ul

    /**
     * Parses a collection of codeBlocks
     */
    private static function _italic( $collection )
    {
        $collection = $collection[0];
        foreach( $collection as $codeBlock ) {
        } // end foreach $collection
        return array();
    } // end function _codeBlock

    /**
     * Parses a collection of codeBlocks
     */
    private static function _bold( $collection )
    {
        $collection = $collection[0];
        foreach( $collection as $codeBlock ) {
        } // end foreach $collection
        return array();
    } // end function _codeBlock

    /**
     * Parses a collection of codeBlocks
     */
    private static function _codeBlock( $collection )
    {
        $collection = $collection[0];
        foreach( $collection as $codeBlock ) {
        } // end foreach $collection
        return array();
    } // end function _codeBlock

    /**
     * Renders markdown to html
     */
    public static function render( $markDown )
    {
        $render = array();
        foreach ( self::$_rules as $rule => $function ) {
            @preg_match_all( $rule, $markDown, $output );
            $collection = call_user_func( $function, $output );
            $render = array_merge( $render, $collection );
        } // end foreach

        return StringHelper::replace( $render, $markDown );
    } // end function rended
} // end clas MarkDownLib
