#APIcaller <a href="https://www.gittip.com/ReiDuKuduro/" target="__blank" alt="ReiDuKuduro @gittip" ><img alt="ReiDuKuduro @gittip" src="http://bottlepy.org/docs/dev/_static/Gittip.png" /></a> ![Total Downloads](https://poser.pugx.org/masnathan/api-caller/downloads.png)


APIcaller is a class that helps you build API wrappers.  
You don't have to worry about building URLs, or even about parsing the requested data.

You can either extend APIcaller or simply use it similarly to ```$.post``` and ```$.get``` from jQuery.

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
		parent::__construct('http://www.some_api.com/', APIcaller::METHOD_GET, APIcaller::CONTENT_TYPE_JSON);
		
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

#Static usage
This is very similar to the ```$.post``` and ```$.get``` functions from jQuery. Below are a few quick examples on how to use it:
    
```php
//Using GET
APIcaller::get('http://www.geoplugin.net/json.gp', function($data) {
    $tmp = '';
    foreach ($data as $key => $value) {
	   	$tmp .= sprintf("<tr><td>%s</td><td>%s</td></tr>", $key, $value);
    }

	echo sprintf("<table border='1'><tr><th>key</th><th>value</th></tr>%s</table>", $tmp);
}, 'json');

//Using POST
APIcaller::post('http://tinyurl.com/api-create.php', array('url' => 'http://www.phpclasses.org/browse/author/1183559.html'), function($data) {
    echo sprintf('<a href="%s" target="__blank">%s</a><br /><br />', $data, $data);
});
```

You can also use ```APIcaller::put(/***/)``` and ```APIcaller::delete(/***/)```.

Here is the arguments order
    
    APIcaller::get( string $url [, array $params [, function $callback [, string $data_type]]]);
    
and a few more examples:

```php
APIcaller::get('http://path_to_api.com', array('param1' => 'some value', 'param2' => 'some other value'));
APIcaller::get('http://path_to_api.com', array('param1' => 'some value', 'param2' => 'some other value'), function(data) { var_dump($data); });
APIcaller::get('http://path_to_api.com', array('param1' => 'some value', 'param2' => 'some other value'), function(data) { var_dump($data); }, 'json');
APIcaller::get('http://path_to_api.com', array('param1' => 'some value', 'param2' => 'some other value'), 'json');
APIcaller::get('http://path_to_api.com', 'json');
APIcaller::get('http://path_to_api.com', function(data) { var_dump($data); }, 'json');
``` 
   
#Changelog
###0.2.0
* Class rework
* Added static functions to execute REST requests

###0.1.1
* Added support for xml  
* Added support for posting xml or json data  

###0.1.0
* Initial release

#Add me to your project
If using composer just add:
```json
{
    "require": {
        "masnathan/api-caller": "0.2.0"
    }
}
```
If not, just include the `APIcaller.php` file.

Is your project using `APIcaller`? [Let me know](https://github.com/ReiDuKuduro/APIcaller/issues/new?title=New%20script%20using%20APIcaller&body=Name and Description of your script.)!
