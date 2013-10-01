<?php

require_once '../APIcaller.php';

APIcaller::get('http://www.geoplugin.net/json.gp', function($data) {
	$tmp = '';
	foreach ($data as $key => $value) {
		$tmp .= sprintf("<tr><td>%s</td><td>%s</td></tr>", $key, $value);
	}

	echo sprintf("<table border='1'><tr><th>key</th><th>value</th></tr>%s</table>", $tmp);
}, 'json');


APIcaller::post('http://tinyurl.com/api-create.php', array('url' => 'http://www.phpclasses.org/browse/author/1183559.html'), function($data) {
	echo sprintf('<a href="%s" target="__blank">%s</a><br /><br />', $data, $data);
});
