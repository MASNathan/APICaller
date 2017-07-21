#Geoplugin

Simple example on How to use the APIcaller to call an API

API used: http://www.geoplugin.com/

##How to use this class:
```php
//Initializing the caller
$client = new MASNathan\Geoplugin\GeoPluginClient();
$geo = new MASNathan\Geoplugin\Geoplugin($client);

//Getting IP info
$geo->getLocation('173.194.41.223');
$geo->getLocation('173.194.41.223', 'EUR', true);
$geo->getLocation('173.194.41.223', 'USD', true);

//Get the last request and response to the API
var_dump($geo->getLastOperation());
```
