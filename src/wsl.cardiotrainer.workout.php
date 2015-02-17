<?php

namespace WSL\CardioTrainer;

class Workout {

	private $data;

	public function __construct($data)
	{
		$this->data = $data;
	}

	public function __get($param)
	{

		switch ($param) {
			case 'start':
				return new \DateTime(date('r', $this->data->startTime / 1000));
				break;
			case 'end':
				return new \DateTime(date('r', $this->data->endTime / 1000));
				break;
			case 'type':
				return ucwords(str_replace(array('exercise_type_', '_'), array('', ' '), $this->data->exerciseType));
				break;
			case 'route':
				if ( ! isset($this->data->route))
				{
					$this->data->route = new \WSL\CardioTrainer\Workout\Route($this->data->id);
				}
				return $this->data->route;
				break;
			default:
				/* review */
				return $this->data->$param;
				break;
		}
		
	}


}