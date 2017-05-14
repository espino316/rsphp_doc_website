<?php
/**
 * Validates user input
 *
 * Please report bugs on https://github.com/espino316/rsphp/issues
 *
 * @author Luis Espino <luis@espino.info>
 * @copyright Copyright (c) 2016, Luis Espino. All rights reserved.
 * @license MIT License
 */
class Validation {

	protected $errors;
	public $errorCount;
	protected $rules;
	protected $messages;
	protected $values;

/*
	Type of rules:
		required
		minlenght
		maxlenght
		email
		url
		integer
		IPs
 */

	function clearRules() {
		$this->rules = array();
	}

	/**
	 * $validate->addRule('inputName', 'valType', 'OptionalArgument	')
	 */
	function addRule($key, $type, $arg = null) {
		$this->rules[] = array(
			'key' => $key,
			'type' => $type,
			'arg' => $arg
		);
	}

	function validate($values) {

		$this->errorCount = 0;
		$this->errors = array();

		foreach( $this->rules as $rule ) {

			switch( $rule['type']) {
				case "required":
					if (
						!$this->validRequired($values[$rule['key']])
					) {
						$this->errors[] = "Field $key is required";
						$this->errorCount++;
					}
				break;
				case "minlenght":
					if (
					!$this->validMinLength($values[$rule['key']])
					) {
						$this->errors[] = "Field $key must have at least " . $rule['arg'] . " characters.";
						$this->errorCount++;
					}
				break;
				case "maxlenght":
					if (
					!$this->validMaxLength($values[$rule['key']])
					) {
						$this->errors[] = "Field $key must have at most " . $rule['arg'] . " characters.";
						$this->errorCount++;
					}
				break;
				case "email":
					if (
					!$this->validEmail($values[$rule['key']])
					) {
						$this->errors[] = "Field $key must be a valid email";
						$this->errorCount++;
					}
				break;
				case "url":
					if (
					!$this->validUrl($values[$rule['key']])
					) {
						$this->errors[] = "Field $key must be a valid url";
						$this->errorCount++;
					}
				break;
				case "integer":
					if (
					!$this->validInt($values[$rule['key']])
					) {
						$this->errors[] = "Field $key must be a valid integer";
						$this->errorCount++;
					}
					break;
				case "IP":
					if (
					!$this->validIP($values[$rule['key']])
					) {
						$this->errors[] = "Field $key must be a valid IP address";
						$this->errorCount++;
					}
					break;
				case "regex":
					if (
						!$this->validateRegEx($rule['arg'], $values[$rule['key']])
					) {
						$this->errors[] = "Field $key must be have the regular expression pattern " . $rule['arg'];
						$this->errorCount++;
					}
					break;
			} // end switch
		}// end foreach

		if ( $this->errorCount > 0 )
			return false;
		else
			return true;

	}// end function

	function getErrors() {
		$errMsg = '';
		foreach($this->errors as $error) {
			$errMsg .= $error;
			$errMsg .= '<br/>';
		}
		return $errMsg;
	}

	function validMin($value, $min) {
		return $value >= $min;
	}

	function validMax($value, $max) {
		return $value <= $max;
	}

	function validMinLength($value, $min) {
		return strlen($value) >= $min;
	}

	function validMaxLength($value, $max) {
		return strlen($value) <= $max;
	}

	function validRequired($value) {
		return ($value != null) && !empty($value);
	}

	function validEmail($value) {
		return filter_var($value, FILTER_VALIDATE_EMAIL);
	}

	function validBool($value) {
		return filter_var($value, FILTER_VALIDATE_BOOLEAN);
	}

	function validFloat($value) {
		return filter_var($value, FILTER_VALIDATE_FLOAT);
	}

	function validInt($value) {
		return filter_var($value, FILTER_VALIDATE_INT);
	}

	function validIP($value) {
		return filter_var($value, FILTER_VALIDATE_IP);
	}

	function validMAC($value) {
		return filter_var($value, FILTER_VALIDATE_MAC);
	}

	function validUrl($value) {
		return filter_var($value, FILTER_VALIDATE_URL);
	}

	function validateRegEx( $pattern, $subject ) {
		$pattern = '/' . $pattern . '/';
		preg_match($pattern, $subject, $matches);
		return !empty($matches);
	} // end function ValidateRegEx

	/**
	 * 	Validayes the inputs from a view
	 *	Saves the work of create rules
	 */
	function validateInputsFromView($viewName) {

		$this->errorCount = 0;
		$this->errors = array();

		$view = View::loadToString($viewName);

		if ( !$view ) {
			return true;
		}

		$doc = new DOMDocument();
    $doc->loadHTML($view);
    $inputs = $doc->getElementsByTagName('input');
		foreach ($inputs as $input) {
			$name = $input->getAttribute('name');
			$value = Input::get($name);
			$required = $input->getAttribute('required');
			$pattern = $input->getAttribute('pattern');
			$type = $input->getAttribute('type');
			$title = $input->getAttribute('title');
			$minlenght = $input->getAttribute('minlenght');
			$maxlenght = $input->getAttribute('maxlenght');
			$min = $input->getAttribute('min');
			$max = $input->getAttribute('max');

			if ( $required ) {
				if ( !$value ) {
					if ( $title ) {
						$this->errors[] = $title;
						$this->errorCount++;
					} else {
						$this->errors[] = "Field $name is required";
						$this->errorCount++;
					} // end if then else $title
				} // end if !$value
			} // end if $required

			if ( $pattern ) {
				if ( $value ) {
					if ( !$this->validateRegEx($patter, $value) ) {
						if ( $title ) {
							$this->errors[] = $title;
							$this->errorCount++;
						} else {
							$this->errors[] = "Field $name must be have the regular expression pattern " . $pattern;
							$this->errorCount++;
						} // end if then else $title
					} // end validateRegEx
				} // end if $value
			} // end if $pattern

			switch ($type) {
				case 'email':
					if ( $value ) {
						if ( ! validEmail($value) ) {
							if ( $title ) {
								$this->errors[] = $title;
								$this->errorCount++;
							} else {
								$this->errors[] = "Field $name must be a valid email";
								$this->errorCount++;
							}
						} // end if !validEmail
					} // end if $value
					break;

				case 'url':
					if ( $value ) {
						if ( ! validUrl($value) ) {
							if ( $title ) {
								$this->errors[] = $title;
								$this->errorCount++;
							} else {
								$this->errors[] = "Field $name must be a valid email";
								$this->errorCount++;
							}
						} // end if !validEmail
					} // end if $value
					break;

				default:
					# code...
					break;
			} // end switch
		} // end foreach

		if ( $this->errorCount > 0 )
			return false;
		else
			return true;

	} // end function validateInputsFromView
} // end class