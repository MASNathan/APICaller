#APIcaller 

[![Downloads with Composer](https://poser.pugx.org/masnathan/api-caller/downloads.png)](https://packagist.org/packages/masnathan/api-caller)
[![SensioLabs Insight](https://insight.sensiolabs.com/projects/a1bfb7a8-0b34-4118-a451-fc8f158ef9c7/mini.png)](https://insight.sensiolabs.com/projects/6d9231d8-9140-4b02-9522-5d3c3aa3d6f2)
[![ReiDuKuduro @gittip](http://bottlepy.org/docs/dev/_static/Gittip.png)](https://www.gittip.com/ReiDuKuduro/)

APIcaller is a class that helps you build API wrappers.  
You don't have to worry about building URLs, or even about parsing the requested data.

# How to install via Composer

The recommended way to install is through [Composer](http://composer.org).

```sh
# Install Composer
$ curl -sS https://getcomposer.org/installer | php

# Add APIcaller as a dependency
$ php composer.phar require masnathan/api-caller:dev-master
```

Once it's installed, you need to require Composer's autoloader:

```php
require 'vendor/autoload.php';
```

#How to Extend
Here is some quick example.

```php
class MyClass extends APIcaller
{
	function __construct()
	{
	    /*
	     * Calling the parent construct you can send the API URL, set the request method and/or the response type
	     * The API URL must be a valid url
       	 * The Request Method to use [GET|POST|PUT|DELETE], we have constants APIcaller::METHOD_GET, APIcaller::METHOD_…
       	 * The format of the data the webservice will return, can be APIcaller::CONTENT_TYPE_NONE, APIcaller::CONTENT_TYPE_JSON or APIcaller::CONTENT_TYPE_XML
	     */
		parent::__construct('http://www.some_api.com/', APIcaller::METHOD_GET, 'json');
		
		//You can also set some default parameters to use on the calls, like api keys and such.
		$this->setDefault('api_key', 'key');
	}
	
	/****/
}
```

Well, this is how you can start creating your class, now, lets make some calls!

```php
public function callMeBaby($some_number)
{   
    //1st, you need to set the parameters you want to send
    $params = array(
            'number' => $some_number,
            'other'  => 'info',
        );
    //2nd, you send the request
    return $this->call('call_a_friend', $params);
}
```

This function will call the following url:```http://www.some_api.com/call_a_friend?api_key=key&number=1&other=info```.

If you set the format/ response type to ```json``` or ```xml``` and the response has a valid format, the ```$this->call()```function will return an array with the parsed data, if not, it'll return a string of the response.

# License

This library is under the MIT License, see the complete license [here](LICENSE)

###Is your project using `APIcaller`? [Let me know](https://github.com/ReiDuKuduro/APIcaller/issues/new?title=New%20script%20using%20APIcaller&body=Name and Description of your script.)!
