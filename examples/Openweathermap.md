#Openweathermap

Simple example on How to use the APIcaller to call an API

API used: http://openweathermap.org/

##How to use this class:
```php
//Initializing the caller
$weather = new MASNathan\Openweathermap\Openweathermap('my_api_key');
//Setting the default properties
$weather
	->setLanguage('pt')
	->setUnits('metric');

//Getting current weather data
$weather->getCurrentWeatherByCity('London,uk');
$weather->getCurrentWeatherByCoordinats(35, 139);
$weather->getCurrentWeatherByID(2172797);

//Getting forecast weather data
$weather->getForecastByCity('London,uk');
$weather->getForecastByCoordinats(35, 139);
$weather->getForecastByID(2172797, 4);

//Searching of city
$weather->getForecastByCity('London,uk', 10, 'accurate');
$weather->getForecastByCoordinats(57, -2.15);

//Get the last call to the API
$weather->getLastCall();
```
