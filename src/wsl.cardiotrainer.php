<?php

namespace WSL;

require 'wsl.cardiotrainer.workout.php';

class CardioTrainer extends Module {

	private $accessCode;
	
	/*== Constructors ==*/

	public function __construct($accessCode)
	{
		$this->accessCode = $accessCode;
	}
	public static function forge($accessCode)
	{
		return new self($accessCode);
	}

	public function __get($param)
	{
		if ($param == 'workouts')
		{
			return $this->request_data();
		}
	}

	protected function request_data()
	{
		return new \Collection(parent::retrieve_data(
			'account/getExerciseHistory', 
			array('accessCode' => $this->accessCode)
		)->uploadedExerciseInfos);
	}

}