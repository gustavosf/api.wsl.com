<?php

namespace WSL;

if (!class_exists('HttpRequest'))
	throw new Exception('WSL needs the PECL HttpRequest PHP extension.');
if (!function_exists('json_decode'))
	throw new Exception('WSL needs the JSON PHP extension.');

class Module {

	protected $data;
	protected $ws_uri = 'http://www.worksmartlabs.com/servlets/HighScoreServer/';

	/**
	 * Retrieves data from wsl webservice
	 * @return object 
	 */
	protected function retrieve_data($service, $params)
	{
		if (!isset($this->data[$service]))
		{
			$request = new \HttpRequest($this->ws_uri.$service.'.htm', \HttpRequest::METH_POST);
			$request->setPostFields($params);
			$request->send();
			$this->data = json_decode($request->getResponseBody());
		}
		return $this->data;
	}

}