<?php
/**
 * Exception used when the args passed are not what they should be
 *
 * @package	APIcaller
 * @subpackage exceptions
 * @author	André Filipe <andre.r.flip@gmail.com>
 * @license	MIT
 * @version	0.2.0
 */
class InvalidArgsException extends Exception
{
	public function __construct($message = '', $code = 0, $previous = null) {

    	if (!$message) {
       		$message = "Something is wrong with some method that you recently called.";
    	}
        parent::__construct($message, $code, $previous);
    }
}
