<?php

namespace WSL;

if (!function_exists('json_decode'))
	throw new \Exception('WSL needs the JSON PHP extension.');

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

				$result = file_get_contents($this->ws_uri.$service.'.htm', false,
					stream_context_create(array('http' => array(
						'method'  => 'POST',
						'header'  => 'Content-type: application/x-www-form-urlencoded',
						'content' => http_build_query($params),
					)))
				);
				$this->data = json_decode($result);
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