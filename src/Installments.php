<?php

namespace MyApp;

class Installments
{
	private $installments;
	private $amount;
	private $air;
	private $maturityDate;
	private $utilisationDate;
	private $taxes;

	private $number;
	private $date;
	private $period;
	private $installmentAmount;
	private $principal;
	private $interest;
	private $balance;

	private $monthlyInterest;
	
	
	public const INPUT_DATA_FORMAT = [
		'numberOfInstallments' => [
			'dataType' => 'int',
			'allowedValues' => [
				3,
				24
			]
		],
		'amount' => [
			'dataType' => 'int',
			'allowedValues' => [
				500,
				5000
			]
		],
		'air' => [
			'dataType' => 'int',
			'allowedValues' => []
		],
		'maturityDate' => [
			'dataType' => 'date',
			'allowedValues' => 'Y-m-d'
		],
		'utilisationDate' => [
			'dataType' => 'date',
			'allowedValues' => 'Y-m-d'
		],
		'taxes' => [
			'dataType' => 'int',
			'allowedValues' => []
		]
	];

    public function checkInputValue($inputValue)
	{
		foreach (self::INPUT_DATA_FORMAT as $field => $rules) {
			if ($rules['dataType'] === 'int') {
				if (is_numeric($inputValue)) {
					
				}
			}
		}
	}

    public function __construct($inputData)
	{
		if ($this->validateInputData($inputData)) {

			$this->installments = (int)$inputData['numberOfInstallments'];
			$this->amount = (float)$inputData['amount'];
			$this->air = (float)$inputData['air'];
			$this->maturityDate = $inputData['maturityDate'];
			$this->utilisationDate = $inputData['utilisationDate'];
			$this->taxes = $inputData['taxes'];

			$this->number = 0;
			$this->balance = $this->amount;
			
			// one month interest 
			$this->monthlyInterest  = $this->air / (12 * 100);
		}
    }

	private function validateInputData($inputData)
	{
		$result = false;
		$dataFormat = [
			'numberOfInstallments' => [
				3,
				24
			],
			'amount' => [
				500,
				5000
			],
			'air' => 0,
			'maturityDate' => 0,
			'utilisationDate' => 0,
			// 'taxes' => []
		];

		return true;
		
		
		// foreach ($dataFormat as $key => $value) {
			// if (array_key_exists($key, $inputData)) {
				// if (is_array($value)) {
					// if ($inputData[$key] >= min($value) && $inputData[$key] <= max($value)) {
						// continue;
					// } else {
						// return false;
					// }
				// }
			// }
		// }

		// $validateData = array_diff_key($dataFormat, $inputData);
		
		// if (empty($validateData)) {
			// $result = true;
		// } else {
			// echo "<div style='background-color:#ccc;padding:0.5em;'>";
			// echo '<p style="color:red;margin:0.5em 0em;font-weight:bold;background-color:#fff;padding:0.2em;">Missing Values</p>';
			// foreach ($validateData as $key => $value) {
				// echo ":: Value <b>$key</b> is missing.<br>";
			// }
			// echo "</div>";
			
			// $result = false;
		// }
		
		// return $result;
	}

	public function calculateDate()
	{
		if ($this->number <= 1) {
			$time = $this->maturityDate;
		} else {
			$time = date('Y-m-d', strtotime('last day of +1 month',
				strtotime($this->maturityDate)));
		}

		return $this->maturityDate = $time;
	}

	public function calculatePeriod()
	{
		if ($this->number <= 1) {
			$this->period = date('t', strtotime($this->maturityDate)) - date('j');
		} else {
			$this->period = date('t', strtotime($this->maturityDate));
		}

		return $this->period;
	}

	public function calculateInstallment()
	{
		$this->monthlyPay = number_format(($this->amount * $this->monthlyInterest) / (1 - 1 / pow((1 + $this->monthlyInterest), $this->installments)), 2);
		$this->interest = number_format($this->amount * $this->monthlyInterest, 2);
		$this->principal = number_format($this->monthlyPay - $this->interest, 2);
		$this->installmentAmount = number_format($this->principal + $this->interest, 2);
		$this->balance = number_format($this->amount - $this->principal, 2);

		return [
			'number' => ++$this->number,
			'date' => $this->calculateDate(),
			'period' => $this->calculatePeriod(),
			'installmentAmount' => $this->installmentAmount,
			'principal' => $this->principal,
			'interest' => $this->interest,
			'tax1' => '',
			'tax2' => ''
		];
	}

	public function getSchedule()
	{
		$schedule = [];

		while ($this->balance > 0) {
			array_push($schedule, $this->calculateInstallment());
			$this->amount = $this->balance;
			$this->installments--;
		}

		return $schedule;
	}

	// public function calculatePrincipal() {
		// $this->principal = round($this->monthlyPay - $this->interest, 2);
	// }

	// public function calculateInterest() {
		
	// }

	// public function calculateTaxes() {
		
	// }

	// ---
	// public function getMaturityDay() {
		// return $maturityDay = date('d', strtotime($this->maturityDate));
	// }
	// public function getMaturityMonth() {
		// return $maturityMonth = date('m', strtotime($this->maturityDate));
	// }
	// public function getMaturityYear() {
		// return $maturityYear = date('Y', strtotime($this->maturityDate));
	// }
	
	// public function generateInstallments() {
		// for ($i = 1; $i <= $this->installments; $i++) {
			// $this->number = $i;
			// $this->calculateDate($i);
			// $this->calculatePeriod();
			
			// echo $installments->period;
			
			// TODO new Installment()!!!
			// or ::Factory?
		// }
	// }
}
