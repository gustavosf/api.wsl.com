<?php

namespace WSL;

class Calorific extends Module {

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
		switch ($param) {
			case 'food':
				$filter = array('foodEntry');
				break;
			case 'profile':
				$filter = array('userProfile' => array('changeReason', 'PROFILE_CHANGE'));
				break;
			case 'weight':
				$filter = array('userProfile' => array('changeReason', 'WEIGHT_CHANGE'));
				break;
		}
		if (isset($filter))
		{
			return $this->request_data()->filter($filter);
		}
	}

	protected function request_data()
	{
		return new \Collection(parent::retrieve_data(
			'calorific/download',
			array('jsonRequest' => json_encode(array(
				'accessCode' => $this->accessCode,
				'lastDownloadedGeneration' => 0,
				'timeZoneId' => date_default_timezone_get(),
			)))
		)->operations);
	}

}