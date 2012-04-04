<?php

namespace WSL;

if (!class_exists('HttpRequest'))
	throw new Exception('WSL needs the PECL HttpRequest PHP extension.');
if (!function_exists('json_decode'))
	throw new Exception('WSL needs the JSON PHP extension.');

class Module {

	private $data;
	private $ws_uri = 'http://www.worksmartlabs.com/servlets/HighScoreServer/';
	private $cache_path = '/tmp/';
	private $cache_time_to_expire = 10800; /* 3 hours */

	/**
	 * Retrieves data from wsl webservice
	 * @return object 
	 */
	protected function retrieve_data($service, $params)
	{
		if (!isset($this->data[$service]))
		{
			/* cache file must include a uniqueid for the user (accesscode maybe?) */
			$cache_file = $this->cache_path.str_replace('/','_',$service).'.cache';
			if (time() - @filemtime($cache_file) > $this->cache_time_to_expire)
			{
				$request = new \HttpRequest($this->ws_uri.$service.'.htm', \HttpRequest::METH_POST);
				$request->setPostFields($params);
				$request->send();
				$this->data = json_decode($request->getResponseBody());
				file_put_contents($cache_file, serialize($this->data));
			}
			else
			{
				$this->data = unserialize(file_get_contents($cache_file));
			}
		}
		return $this->data;
	}

}