<?php

namespace MASNathan\APIcaller\Exception;

/**
 * Exception used when the content type is not valid
 *
 * @package	APIcaller
 * @subpackage exceptions
 * @author	André Filipe <andre.r.flip@gmail.com>
 * @license	MIT
 * @version	0.2.0
 */
class InvalidContentTypeException extends \Exception
{
	public function __construct($message = '', $code = 0, $previous = null) {

    	if (!$message) {
       		$message = "Content type not supported";
    	}
        parent::__construct($message, $code, $previous);
    }
}
