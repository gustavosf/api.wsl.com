<?php

namespace WSL;

require 'collection.php';
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

	/**
	 * Optional constructor to provide method chaining
	 * @param  string $accessCode
	 * @return Connector
	 */
	public static function forge($accessCode)
	{
		return new self($accessCode);
	}

	/**
	 * Getter for this Connector modules
	 * @param  string $module module name
	 * @return object
	 */
	public function __get($module)
	{
		if (in_array($module, array('calorific', 'cardioTrainer')))
		{
			return $this->$module;
		}
	}


}