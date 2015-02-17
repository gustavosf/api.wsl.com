<?php

namespace WSL;

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
		$data = parent::retrieve_data(
			'account/getExerciseHistory',
			array('accessCode' => $this->accessCode)
		)->uploadedExerciseInfos;

		$data = array_map(function($o){
			return new \WSL\CardioTrainer\Workout($o);
		}, $data);

		return new \Collection($data);
	}

}