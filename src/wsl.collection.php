<?php

namespace WSL;

class Collection implements ArrayAccess, Countable, IteratorAggregate {
	
	protected $collection;

	public function __construct($data)
	{
		if (!is_array($data))
		{
			throw new Exception("Collection input must be an Array");
		}
		$this->collection = $data;
	}

	public function first()
	{
		return array_shift($this->collection);
	}

	public function last()
	{
		return array_pop($this->collection);
	}

	
	/*== Simple implementations of ArrayAccess, Countable e IteratorAggregate ==*/
	   
	public function offsetSet($offset, $value) {
		if (is_null($offset))
		{
			$this->collection[] = $value;
		}
		else
		{
			$this->collection[$offset] = $value;
		}
	}

	public function offsetExists($offset) {
		return isset($this->collection[$offset]);
	}

	public function offsetUnset($offset) {
		unset($this->collection[$offset]);
	}

	public function offsetGet($offset) {
		return isset($this->collection[$offset]) ? $this->collection[$offset] : null;
	}

	public function count() {
		return sizeof($this->collection);
	}

	public function getIterator() {
		return new ArrayIterator($this->collection);
	}

}