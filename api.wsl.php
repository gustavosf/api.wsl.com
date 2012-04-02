<?php

namespace wsl;

class WSL {

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

class CardioTrainer {

	private $accessCode;
	private $data;

	
	public function __construct($accessCode)
	{
		$this->accessCode = $accessCode;
	}

}

class Calorific {

	private $accessCode;
	
	/*== Constructors ==*/

	public function __construct($accessCode)
	{
		$this->accessCode = $accessCode;
	}
	public static function forge($accessCode)
	{
		return new Calorific($accessCode);
	}

	
	/*== Methods ==*/

	/**
	 * Retrieves all weight-change operations in calorific history
	 * @return array subset of $this->data
	 */
	public function weight_entries()
	{
		return $this->filter_entries('WEIGHT_CHANGE');
	}

	/**
	 * Retrieves all profile-change operations in calorific history
	 * @return array subset of $this->data
	 */
	public function profile_entries()
	{
		return $this->filter_entries('PROFILE_CHANGE');
	}

	/**
	 * Retrieves all food-insert operations in calorific history
	 * @return array subset of $this->data
	 */
	public function food_entries()
	{
		return $this->filter_entries('FOOD_ENTRY');
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
	private $resource = 'http://www.worksmartlabs.com/servlets/HighScoreServer/calorific/download.htm';

	/**
	 * Retrieves data from calorific webservice
	 * @return object 
	 */
	public function retrieve_data()
	{
		if (!$this->data)
		{
			$params = json_encode(array(
				'accessCode' => $this->accessCode,
				'lastDownloadedGeneration' => 0,
				'timeZoneId' => date_default_timezone_get(),
			));
			$request = new \HttpRequest($this->resource, \HttpRequest::METH_POST);
			$request->setPostFields(array('jsonRequest' => $params));
			$request->send();
			$this->data = json_decode($request->getResponseBody())->operations;
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