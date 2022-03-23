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
	
	
	public const INPUT_DATA_STRUCTURE = [
		'numberOfInstallments' => '',
		'amount' => '',
		'air' => '',
		'maturityDate' => '',
		'utilisationDate' => '',
		'taxes' => [
			'tax1' => '',
			'tax2' => ''
		]
	];

    public function __construct($inputData)
	{
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
}
