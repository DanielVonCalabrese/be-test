<?php

require_once realpath('vendor/autoload.php');

use MyApp\Installments;

$inputData = [];

foreach (Installments::INPUT_DATA_STRUCTURE as $key => $value) {
	if (is_array($value)) {
		foreach ($value as $k => $val) {
			$inputData[$key][$k] = readline('Input value for ' . $k . ': ');
		}
	} else {
		$inputData[$key] = readline('Input value for ' . $key . ': ');
	}
}

$installments = new Installments($inputData);

$result = $installments->getSchedule();

echo '<pre>';
print_r($result);
