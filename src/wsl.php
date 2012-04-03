<?php

namespace WSL;

require 'wsl.calorific.php';
require 'wsl.cardiotrainer.php';

class Connector {

	private $accessCode;

	protected $calorific;
	protected $cardioTrainer;

	public function __construct($accessCode)
	{
		$this->accessCode = $accessCode;
		$this->cardioTrainer = new CardioTrainer($accessCode);
		$this->calorific = new Calorific($accessCode);
	}

}