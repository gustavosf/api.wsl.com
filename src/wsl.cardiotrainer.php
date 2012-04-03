<?php

namespace WSL;

require 'wsl.cardiotrainer.workout.php';

class CardioTrainer {

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
			return new \Collection($this->retrieve_data());
		}
	}

	/*== Private / Helper Methods ==*/

	/**
	 * Contains data retrieved from resource (calorific webservice)
	 * @var stdObject
	 */
	private $data;

	/**
	 * Resource uri
	 * @var string
	 */
	private $resource = 'http://www.worksmartlabs.com/servlets/HighScoreServer/account/getExerciseHistory.htm';

	/**
	 * Retrieves data from calorific webservice
	 * @return object 
	 */
	private function retrieve_data()
	{
		if (!$this->data)
		{
			$request = new \HttpRequest($this->resource, \HttpRequest::METH_POST);
			$request->setPostFields(array('accessCode' => $this->accessCode));
			$request->send();
			$this->data = json_decode($request->getResponseBody())->uploadedExerciseInfos;
		}
		return $this->data;
	}

	/**
	 * Filter data
	 * @param  string $filter
	 * @return array
	 */
	private function filter_entries($filter)
	{
		return array_filter($this->retrieve_data(), function(&$entry) use (&$filter) {
			if ($filter == 'FOOD_ENTRY' && isset($entry->foodEntry)) 
			{
				$entry = $entry->foodEntry;
				return true;
			}
			elseif (isset($entry->userProfile) && $entry->userProfile->changeReason == $filter)
			{
				$entry = $entry->userProfile;
				return true;
			}
			return false;
		});
	}

}