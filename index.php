<?php

require_once 'APIcaller.php';

$url = 'http://test.local/api.php';
/*
$x = APIcaller::get($url . 'data.xml', function($data) {
	var_dump($data);
}, 'xml');
//*/

echo '<pre>';

$x = APIcaller::post($url, array('x' => '007'), function($data) {
	?>
		<fieldset>
			<legend><?= $data['method'] ?></legend>
			<pre><?php print_r($data['data']) ?></pre>
		</fieldset>
	<?php

	return $data;
}, 'json');

$x = APIcaller::put($url, array('x' => '007'), function($data) {
	?>
		<fieldset>
			<legend><?= $data['method'] ?></legend>
			<pre><?php print_r($data['data']) ?></pre>
		</fieldset>
	<?php

	return $data;
}, 'json');

$x = APIcaller::delete($url, array('x' => '007'), function($data) {
	?>
		<fieldset>
			<legend><?= $data['method'] ?></legend>
			<pre><?php print_r($data['data']) ?></pre>
		</fieldset>
	<?php

	return $data;
}, 'json');

$x = APIcaller::get($url, array('x' => '007'), function($data) {
	?>
		<fieldset>
			<legend><?= $data['method'] ?></legend>
			<pre><?php print_r($data['data']) ?></pre>
		</fieldset>
	<?php

	return $data;
}, 'json');



echo '<br>';

//var_dump($x);