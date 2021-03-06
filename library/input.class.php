<?php
/**
 * Access all inputs
 *
 * Please report bugs on https://github.com/espino316/rsphp/issues
 *
 * @author Luis Espino <luis@espino.info>
 * @copyright Copyright (c) 2016, Luis Espino. All rights reserved.
 * @license MIT License
 */
class Input {

	protected static $data;

	static function load() {
		parse_str(file_get_contents("php://input"), self::$data);
		if ( count(self::$data) === 0 ) {

			//	Check if data
			if ( count($_POST) > 0 ) {
				//	is multipart
				foreach ( array_keys($_POST) as $key) {
					self::$data[$key] = $_POST[$key];
				} // end foreach
			} // end if

			//	Check if files
			if ( count($_FILES) > 0 ) {
				foreach ( array_keys($_FILES) as $key) {
					self::$data[$key] = $_FILES[$key];
				} // end foreach
			}
		} // end if
	} // end load

	static function get($key = NULL) {
		if ( $key == NULL ) {
			return self::$data;
		} else {
			if ( array_key_exists($key, self::$data)) {
				return self::$data[$key];
			} else {
				return null;
			}
		}
	} // end function get

	/**
	 * Saves an uploades file
	 */
	static function saveUploadedFile( $key, $folder = null, $name = null, $conditions = null ) {

		$file = self::get($key);

		if (
        !isset( $file['error'] ) ||
        is_array( $file['error'] )
    ) {
        throw new RuntimeException('Invalid parameters.');
    }

    // Check $_FILES['upfile']['error'] value.
    switch ( $file['error'] ) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            throw new RuntimeException('No file sent.');
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            throw new RuntimeException('Exceeded filesize limit.');
        default:
            throw new RuntimeException('Unknown errors.');
    }

		if ( !$folder ) {
			$folder = ROOT.DS.'public'.DS.'files'.DS;
		} // end if folder

		if ( !$name ) {
			$name = $file["name"];
		} // end if $name

		if ( !preg_match("/(\S*)\.[a-z]{3,4}/", $name ) ) {
			$ext = FileHelper::getExtension( $file['name'] );
			$name = $name.'.'.$ext;
    } // end not has extension

		//	Sets the destination
		$fileNameDestination = $folder.$name;

		//	Conditions: MAX_SIZE, MIME TYPES
		if ( $conditions ) {
			if ( isset( $condictions['MAX_SIZE'] ) ) {
				if ( $file["size"] > $conditions['MAX_SIZE'] ) {
					throw new Exception("Max size exceed " . $condictions['MAX_SIZE'] );
				} // end if size > max size
			} // end if maz size
			if ( isset ( $conditions['MIME_TYPES'] ) ) {
				if ( is_array( $conditions['MIME_TYPES'] ) ) {
					$result = false;
					foreach( $conditions['MIME_TYPES'] as $mimeType ) {
						if ( $mimeType === $file['type'] ) {
							$result = true;
						} // end if mimeType
					} // end for each mime type
					if ( ! $result ) {
						throw new Exception("Type not founded in " . implode( " || ", $condictions['MIME_TYPES'] ) );
					} // end if not result
				} // end MIME_TYPES is array
			} // end MIME TYPES
		} // end if conditions

		if ( !move_uploaded_file( $file['tmp_name'], $fileNameDestination ) ) {
			throw new Exception("Fail to move uoloaded file to destination" );
		} // end if ! move_uploaded_file

		return $fileNameDestination;
	} // end function saveFile
} // end class Input