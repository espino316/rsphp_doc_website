<?php
/**
 * Helper for directory management
 *
 * Please report bugs on https://github.com/espino316/rsphp/issues
 *
 * @author Luis Espino <luis@espino.info>
 * @copyright Copyright (c) 2016, Luis Espino. All rights reserved.
 * @license MIT License
 */
class DirectoryHelper {

  /**
   * Verify a file exits
   */
  public static function exists( $file ) {
    return file_exists( $file );
  } // end function exists
  
  /**
   * Return full path files (only files) from directory
   * @param $dir The directory
   */
  public static function getFiles( $dir, $extensions = null ) {

    //  Boolean to control the continue process
    $continue = 0;

    if ( self::exists( $dir ) ) {
      //  Array of string representing files
      $result = array();

      //  Get the files with scandir
      $files = scandir( $dir );

      //  Adds the directory
      //  Filter directories, return only files
      foreach ( $files as $file ) {

        if ( $file == '.' || $file == '..' ) {
          continue;
        } // end if $file not . nor ..

        if ( $extensions ) {
          $continue = 1;
          foreach( $extensions as $ext ) {
            if ( StringHelper::endsWith( $file, $ext ) ) {
              $continue = 0;
              continue;
            } // end if
          } // end foreach
        } // end if extensions

        if ( $continue ) {
          continue;
        } // end if continue

        //  Adds the directory
        $file = $dir.DS.$file;

        if ( is_dir( $file ) ) {
          continue;
        } // end if is_idr

        //  Add the file to the array
        $result[] = $file;
      } // end foreach $file

      return $result;
    } else {
      return null;
    } // end if directory exists
  } // end function getFiles

  /**
   * Delete a directory
   * @param $dir The directory (full path)
   * @param $recursive (default false) Indicates if remove inner files and
   * directories too.
   */
  public static function delete( $dir, $recursive = false ) {

    if ( $recursive ) {
      if ( !is_dir( $dir ) ) {
    		return false;
    	}

    	$files = scandir( $dir );

    	foreach ( $files as $file ) {
    		if ( $file == '.' || $file == '..' ) {
    			continue;
    		}
    		$file = $dir.DS.$file;

    		if ( is_dir( $file ) ) {
    			self::delete( $file, true );
    		} else {
    			unlink( $file );
    		} // end if is dir
    	} // end foreach
    }

    //  remove directory
  	rmdir( $dir );
  } // end function delete

  /**
   * Creates a directory
   */
  public static function create( $dir ) {
    if ( file_exists( $dir) ) {
      throw new Exception("Directory already exists", 1);
      return;
    } // end if file exists
    mkdir( $dir );
  } // end function create

  /**
   * Return full path directories (only directories) from directory
   * @param $dir The directory
   */
  public static function getDirectories( $dir ) {

    //  Array of string representing files
    $result = array();

    //  Get the files with scandir
    $files = scandir( $dir );

    //  Adds the directory
    //  Filter directories, return only files
    foreach ( $files as $file ) {

      if ( $file == '.' || $file == '..' ) {
        continue;
      } // end if $file not . nor ..

      //  Adds the directory
      $file = $dir.DS.$file;

      if ( !is_dir( $file ) ) {
        continue;
      } // end if is_idr

      //  Add the file to the array
      $result[] = $file;
    } // end foreach $file

    return $result;
  } // end function getDirectories
} // end class DirectoryHelper
