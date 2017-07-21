# APIcaller 

[![Downloads with Composer](https://poser.pugx.org/masnathan/api-caller/downloads.png)](https://packagist.org/packages/masnathan/api-caller)
[![SensioLabs Insight](https://insight.sensiolabs.com/projects/a1bfb7a8-0b34-4118-a451-fc8f158ef9c7/mini.png)](https://insight.sensiolabs.com/projects/6d9231d8-9140-4b02-9522-5d3c3aa3d6f2)
[![ReiDuKuduro @gittip](http://bottlepy.org/docs/dev/_static/Gittip.png)](https://www.gittip.com/ReiDuKuduro/)

APIcaller is a class that helps you build API wrappers.  
You don't have to worry about building URLs, or even about parsing the requested data.

## How to use

You will have to extend the ```Client``` class and the ```Caller``` class, the ```Client``` will handle all the 
configuration to use on the requests and the ```Caller``` will be used as the interface to interact with the API.

```php
use MASNathan\APICaller\Client;
use MASNathan\APICaller\Caller;

class MyPackageClient extends Client
{
    /**
     * Here you can set the default headers and parameters on a global scope
     */
    public function __construct($ip = null)
    {
        $this->setDefaultHeaders([
            'User-Agent' => 'PHP APICaller SDK',
            'Accept'     => 'application/json',
            'Token'      => '123456',
        ]);
        $this->setDefaultParameters([
            'ip' => $ip ?: '127.0.0.1',
        ]);
    }

    /**
     * Returns the API Endpoint
     *
     * @return string
     */
    public function getEndpoint()
    {
        return 'http://api.domain.com/v1/';
    }
}

class MyPackageCaller extends Caller
{
    public function requestSomething($foo, $bar)
    {
        $params = [
            'foo' => $foo,
            'bar' => $bar,
        ];

        // this will result in this url http://api.domain.com/v1/some-method.json?ip={$ip}&foo={$foo}&bar={$bar}
        $response = $this->client->get('some-method.json', $params);

        $data = $this->handleResponseContent($response, 'json');

        // Do something with your data

        return $data;
    }
}
```

Well, this is how you can start creating your class, now, lets make some calls!

```php
$client = new MyPackageClient('8.8.8.8');
$caller = new MyPackageCaller($client);

$result = $caller->requestSomething(13, 37);

var_dump($result);
```

This will call the following url:```http://api.domain.com/v1/some-method.json?ip=8.8.8.8&foo=13&bar=37```.

## Installation

To install the SDK, you will need to be using [Composer](http://composer.org) in your project. If you don't have composer 
installed check this page and follow the [installation steps](https://getcomposer.org/download/)

This library is not hard coupled to Guzzle or any other library that sends HTTP messages. 
It uses an abstraction called [HTTPlug](http://httplug.io/). 
This will give you the flexibility to choose what PSR-7 implementation and HTTP client to use.

To get started ASAP you should run the following command:

```sh
# Add APIcaller as a dependency
$ composer require masnathan/api-caller php-http/curl-client guzzlehttp/psr7
```

## Why do I need to require all those packages?

APICaller depends on the virtual package [php-http/client-implementation](https://packagist.org/providers/php-http/client-implementation) 
which requires to you install an adapter, but we do not care which one. 
That is an implementation detail in your application. 
We also need a PSR-7 implementation and a message factory.

You don't have to use the [php-http/curl-client](https://github.com/php-http/curl-client) if you don't want to. 
Read more about the virtual packages, why this is a good idea and about the flexibility it brings at the [HTTPlug docs](http://docs.php-http.org/en/latest/index.html).

# License

This library is under the MIT License, see the complete license [here](LICENSE)
