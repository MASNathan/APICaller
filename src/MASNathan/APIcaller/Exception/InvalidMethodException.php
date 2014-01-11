<?php

namespace MASNathan\APIcaller\Exception;

/**
 * Exception used when the comunication standard is invalid 
 *
 * @package	APIcaller
 * @subpackage exceptions
 * @author	André Filipe <andre.r.flip@gmail.com>
 * @license	MIT
 * @version	0.2.0
 */
class InvalidMethodException extends \Exception
{
	public function __construct($message = '', $code = 0, $previous = null) {

    	if (!$message) {
       		$message = "Invalid communication standard.";
    	}
        parent::__construct($message, $code, $previous);
    }
}
