<?php

namespace WSL;

require 'wsl.calorific.php';
require 'wsl.cardiotrainer.php';

class Connector {

	private $accessCode;
	private $modules = array();

	public function __construct($accessCode)
	{
		$this->accessCode = $accessCode;
	}

	/**
	 * Retrieves Cardiotrainer module
	 * @return CardioTrainer
	 */
	public function cardioTrainer()
	{
		if (!isset($this->modules['cardioTrainer']))
		{
			$this->modules['cardioTrainer'] = new CardioTrainer($this->accessCode);
		}
		return $this->modules['cardioTrainer'];
	}

	/**
	 * Retrieves Calorific module
	 * @return Calorific
	 */
	public function calorific()
	{
		if (!isset($this->modules['calorific']))
		{
			$this->modules['calorific'] = new Calorific($this->accessCode);
		}
		return $this->modules['calorific'];
	}

}