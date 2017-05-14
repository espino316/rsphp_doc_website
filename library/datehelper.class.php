<?php
/**
 * Helper for date operations
 *
 * Please report bugs on https://github.com/espino316/rsphp/issues
 *
 * @author Luis Espino <luis@espino.info>
 * @copyright Copyright (c) 2016, Luis Espino. All rights reserved.
 * @license MIT License
 */
class DateHelper {
	static function now() {
		return date('Y-m-d H:i:s');
	}

  static function addSeconds( $date, $days ) {
    $interval = "PT$days"."S";
    return self::add( $date, $interval );
  } // end function addDays

  static function addMinutes( $date, $days ) {
    $interval = "PT$days"."M";
    return self::add( $date, $interval );
  } // end function addDays

  static function addHours( $date, $days ) {
    $interval = "PT$days"."H";
    return self::add( $date, $interval );
  } // end function addDays

  static function addDays( $date, $days ) {
    $interval = "P$days"."D";
    return self::add( $date, $interval );
  } // end function addDays

  static function addMonths( $date, $days ) {
    $interval = "P$days"."M";
    return self::add( $date, $interval );
  } // end function addDays

  static function addYears( $date, $days ) {
    $interval = "P$days"."Y";
    return self::add( $date, $interval );
  } // end function addDays

	/**
   * add
   *
   * Adds a interval to a date
   *
   * @param Date $date The date to add the interval
   * @param String $interval The interval added to the date
   * @return Date
	 */
	static function add( $date, $interval ) {
		$date = new DateTime( $date );
		$date->add(new DateInterval( $interval ));
		return $date->format('Y-m-d H:i:s');
	} // end function add

	/**
	 * Gets the difference from two dates
	 */
	static function diff( $interval, $date1, $date2 ) {
		$time1 = null;
		$time2 =  null;

		$typeOf = getType( $date1 );
		if ( $typeOf == 'object' ) {
			$className = get_class( $date1 );
			if ( $className != 'DateTime' ) {
				throw new Exception("Object 1 is not date");
			} else {
				$time1 = $date1->getTimestamp();
			} // end if class is DateTime
		} else if ( $typeOf == 'string' ) {
			try {
				$date1 = new DateTime($date1);
				$time1 = $date1->getTimestamp();
			} catch ( Exception $ex ) {
				throw new Exception("Object 1 is not a date", 1);
			} // end try catch create date
		} else {
			throw new Exception("Object 1 is not date");
		}// end if type is object

		$typeOf = getType( $date2 );
		if ( $typeOf == 'object' ) {
			$className = get_class( $date2 );
			if ( $className != 'DateTime' ) {
				throw new Exception("Object 2 is not date");
			} else {
				$time2 = $date2->getTimestamp();
			} // end if class is DateTime
		} else if ( $typeOf == 'string' ) {
			try {
				$date2 = new DateTime($date2);
				$time2 = $date2->getTimestamp();
			} catch ( Exception $ex ) {
				throw new Exception("Object 2 is not a date", 1);
			} // end try catch create date
		} else {
			throw new Exception("Object 2 is not date");
		}// end if type is object

		$result = $time1 - $time2;

		switch ( $interval ) {
			case 'y':
				$result = $result / 60 / 60 / 24 / 365.25; // years
				break;

			case 'm':
				$result = $result / 60 / 60 / 24 / 30.4375; // month
				break;

			case 'd':
				$result = $result / 60 / 60 / 24; // days
				break;

			case 'h':
				$result = $result / 60 / 60; // hours
				break;

			case 'i':
				$result = $result / 60; // minutes
				break;

			case 's':
				$result = $result; // seconds
				break;

			default:
				# code...
				break;
		}

		return abs( $result );
	} // end function diff
} // end class DateHelper
