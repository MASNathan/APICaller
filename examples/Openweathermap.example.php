<?php

require_once 'Openweathermap.class.php';

$weather = Openweathermap::getInstance( '11c1397f8a82dca91502efa87401c48d' )
			-> setLanguage( 'pt')
			-> setUnits( 'metric');

//Current Weather
//var_dump( $weather -> getCurrentWeatherByCity( 'London,uk' ) );
//var_dump( $weather -> getCurrentWeatherByCoordinats( 35, 139 ) );
//var_dump( $weather -> getCurrentWeatherByID( 2172797 ) );

//Forecast
//var_dump( $weather -> getForecastByCity( 'London,uk' ) );
//var_dump( $weather -> getForecastByCoordinats( 35, 139 ) );
var_dump( $weather -> getForecastByID( 2172797, 4 ) );

echo $weather -> getLastCall();
