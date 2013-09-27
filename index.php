<?php

require_once 'APIcaller.class.php';

$url = 'http://localhost/webservice/';

$x = APIcaller::get($url . 'data.xml', function($data) {
	var_dump($data);
}, 'xml');

var_dump($x);