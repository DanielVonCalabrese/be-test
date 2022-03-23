<?php

// $arr = explode(' ', readline());

// $i = 1;
// while (true) {
	// $numbers[$i] = readline('Input number {$i}: ');
	// if ($numbers[$i] == 'END') {
		// break;
	// }
	// $i++;
// }

// print_r($numbers);

require_once realpath('vendor/autoload.php');

use MyApp\Installments;

$params = [
    'numberOfInstallments' => 3,
	'amount' => 500,
    'air' => 10,
    'maturityDate' => '2021-03-31',
    'utilisationDate' => '2021-03-16',
    'taxes' => [
        'tax1' => 25,
        'tax2' => 17
    ]
];

$inputData = [];
foreach ($params as $key => $value) {
	// if (array_key_exists($key, Installments::INPUT_DATA_FORMAT)) {
		// var_dump(Installments::INPUT_DATA_FORMAT[$key]['dataType']);
		// if () {
			
		// }
	// }
	if (is_array($value)) {
		foreach ($value as $k => $val) {
			$inputData[$key][$k] = readline('Input value for ' . $k . ': ');
		}
	} else {
		$inputData[$key] = readline('Input value for ' . $key . ': ');
		// var_dump(is_numeric($numbers[$key]));
	}
}

print_r($inputData);

$installments = new Installments($inputData);

// $result = array();
// while ($installments->balance > 0) { 
	// array_push($result, $installments->calculateInstallmen());
	// $installments->amount = $installments->balance;
	// $installments->installments--;
// }

$result = $installments->getSchedule();

echo '<pre>';
print_r($result);
