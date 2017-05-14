<?php
/**
 * Helper for file management
 *
 * Please report bugs on https://github.com/espino316/rsphp/issues
 *
 * @author Luis Espino <luis@espino.info>
 * @copyright Copyright (c) 2016, Luis Espino. All rights reserved.
 * @license MIT License
 */
class FileHelper {

  /**
   * Verify a file exits
   */
  public static function exists( $file ) {
    return file_exists( $file );
  } // end function exists
  /**
   * Reads a file and return its content
   * @param $file The file path
   */
  public static function read( $file ) {
    return file_get_contents( $file );
  } // end function read

  /**
   * Write a file
   * @param $file The file path
   * @param $content The content of the file
   * @param bool $append Indicates if append to file
   */
  public static function write( $file, $content = "", $append = false ) {
    if ( $append ) {
      file_put_contents( $file, $content, FILE_APPEND | LOCK_EX );
    } else {
      file_put_contents( $file, $content, LOCK_EX );
    }
  } // end function write

  /**
   * Deletes a file
   * @param $file The file to delete
   */
  public static function delete( $file ) {
    if ( file_exists( $file ) ) {
      unlink( $file );
    }
  } // end function delete

  /**
   * Copy a file
   * @param $fileSrc The file to copy
   * @param $fileDest The destination path
   */
  public static function copy( $fileSrc, $fileDest ) {
    if ( !file_exists( $fileSrc ) ) {
      throw new Exception("File source do not exists ($fileSrc)", 1);
    } // end if !file exists
    copy( $fileSrc, $fileDest );
  } // end function copy

  /**
   * Moves or renames a file
   * @param $fileSrc The file to move
   * @param $fileDest The destination path
   */
  public static function move ( $fileSrc, $fileDest ) {
    if ( !file_exists( $fileSrc ) ) {
      throw new Exception("File source do not exists ($fileSrc)", 1);
    } // end if !file exists
    rename( $fileSrc, $fileDest );
  } // end function move

  public static function getExtension( $fileName ) {
    return end( ( explode( ".", $fileName ) ) );
  }

  public static function writeToResponse( $file ) {
    $type = mime_content_type( $file );
    $name = basename($file);
    header("Content-type:application/$type");
    header("Content-Disposition:attachment;filename=$name");
    ob_end_clean();
    readfile($file);
  }
} // end class File Helper