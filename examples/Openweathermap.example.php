<?php

require_once 'Openweathermap.class.php';

/**
 * Simple example on How to use the APIcaller to call an API
 * 
 * API used: http://openweathermap.org/
 */

$weather = new Openweathermap( 'api_key' );
$weather -> setLanguage( 'pt')-> setUnits( 'metric');

echo '<pre>';
//Getting current weather data
var_dump( $weather -> getCurrentWeatherByCity( 'London,uk' ) );
//var_dump( $weather -> getCurrentWeatherByCoordinats( 35, 139 ) );
//var_dump( $weather -> getCurrentWeatherByID( 2172797 ) );

//Getting forecast weather data
//var_dump( $weather -> getForecastByCity( 'London,uk' ) );
//var_dump( $weather -> getForecastByCoordinats( 35, 139 ) );
//var_dump( $weather -> getForecastByID( 2172797, 4 ) );

//Searching of city
//var_dump( $weather -> getForecastByCity( 'London,uk', 10, 'accurate' ) );
//var_dump( $weather -> getForecastByCoordinats( 57, -2.15 ) );


var_dump( $weather -> getLastCall() );

echo '</pre>';
