<?php

require_once 'Geoplugin.class.php';

/**
 * Simple example on How to use the APIcaller to call an API
 * 
 * API used: http://www.geoplugin.com/
 */

echo '<pre>';

//Getting IP info
var_dump( Geoplugin::getInstance() -> getLocation( '173.194.41.223' ) );
var_dump( Geoplugin::getInstance() -> getLocation( '173.194.41.223', 'EUR', true ) );
var_dump( Geoplugin::getInstance() -> getLocation( '173.194.41.223', 'USD', true ) );

var_dump( Geoplugin::getInstance() -> getLastCall() );

echo '</pre>'; 