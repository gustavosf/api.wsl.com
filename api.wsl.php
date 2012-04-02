<?php

namespace wsl;

class WSL {

	private $accessCode;
	private $modules = array();

	public function __construct($accessCode)
	{
		$this->accessCode = $accessCode;
	}

	public function cardioTrainer()
	{
		if (!isset($this->modules['cardioTrainer']))
		{
			$this->modules['cardioTrainer'] = new CardioTrainer($this->accessCode);
		}
		return $this->modules['cardioTrainer'];
	}

	public function calorific()
	{
		if (!isset($this->modules['calorific']))
		{
			$this->modules['calorific'] = new Calorific($this->accessCode);
		}
		return $this->modules['calorific'];
	}

}

class CardioTrainer {

	private $accessCode;

	public function __construct($accessCode)
	{
		$this->accessCode = $accessCode;
	}

}

class Calorific {

	private $accessCode;

	public function __construct($accessCode)
	{
		$this->accessCode = $accessCode;
	}

}